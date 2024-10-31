<?php
namespace OneElements\Includes\Traits;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use OneElements\Includes\Controls\Control_One_Element_Select2;
use OneElements\Includes\Controls\Group\ONE_ELEMENTS_Posts_Group_Control;
use WP_Query;

if ( !trait_exists( 'One_Elements_Common_Widget_Trait') ) {

	/**
	 * Trait One_Elements_Common_Widget_Trait
	 * This trait contains common methods useful for creating elementor widgets, it helps reducing duplicate code
	 * @package
	 */
	trait One_Elements_Common_Widget_Trait {

		/**
		 * It outputs elementor controls for fetching practice area post type
		 *
		 * @param array $excludes [optional] the array of controls id to exclude
		 * @param string $post_type
		 */
		protected function get_query_controls_for_single_posttype($excludes=[], $post_type='post') {
			if ( !in_array( 'one_elements_section_query', $excludes) ){
				$this->start_controls_section( 'one_elements_section_query', [
					'label' => __( 'Query Settings', 'one-elements' ),
				] );
			}

			if ( !in_array( $this->query_option_key.'_post_type', $excludes) ) {
				$this->add_control( $this->query_option_key.'_post_type', [
					'label'       => __( 'Data Type', 'one-elements' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'by_id'   => __( 'Single' ),
						$post_type => __( 'Multiple' ),
					],
					'default'   => $post_type, // making it flexible like postgrid query
				]);
			}

			$this->add_control( 'one_elements_fetch_type', [
				'label' => __( 'Content Source', 'one-elements' ),
				'type'        => Controls_Manager::HIDDEN,
				'default'   => 'multiple',
				'condition'   => [
					$this->query_option_key.'_post_type!' => ['by_id'],
				],
			]); // we  need this hidden field for now not to break carousel and other controls.

			if ( !in_array( $this->query_option_key.'_posts_ids', $excludes) ) {

				$this->add_control( $this->query_option_key.'_posts_ids', [
					'label'       => __( 'Select the Item', 'one-elements' ),
					'type'        => Control_One_Element_Select2::SELECT2,
					'options'     => "one-elements-select2/{$post_type}/post_list",//Rest API end point
					'label_block' => true,
					'condition'   => [
						$this->query_option_key.'_post_type' => ['by_id'],
					],
				]);
			}

			if ( !in_array('by_taxonomy', $excludes) ) {
				$this->get_tax_controls_of($post_type);
			}

			if ( !in_array( $this->query_option_key.'_limit', $excludes) ) {

				$this->add_control( $this->query_option_key.'_limit', [
					'label'       => __( 'Posts per page', 'one-elements' ),
					'description' => __( 'Enter how many number of items you want to show', 'one-elements' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => '3',
					'condition'   => [
						$this->query_option_key.'_post_type' => $post_type,
					],
				]);
			}

			if ( !in_array($this->query_option_key.'_orderby', $excludes) ) {

				$this->add_control( $this->query_option_key.'_orderby', [
					'label'     => __( 'Order By', 'one-elements' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => apply_filters('one_elements_query_orderby', [
						'date'       => __( 'Default Order (Date)', 'one-elements' ),
						'ID'         => __( 'ID', 'one-elements' ),
						'title'      => __( 'Title', 'one-elements' ),
						'rand'       => __( 'Random', 'one-elements' ),
						'menu_order' => __( 'Menu Order', 'one-elements' ),
					]),
					'default'   => 'date',
					'condition' => [
						$this->query_option_key.'_post_type' => $post_type,
					],
				]);
			}


			if ( !in_array($this->query_option_key.'_order', $excludes) ) {

				$this->add_control( $this->query_option_key.'_order', [
					'label'     => __( 'Order', 'one-elements' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'asc'  => 'Ascending',
						'desc' => 'Descending',
					],
					'default'   => 'desc',
					'condition' => [
						$this->query_option_key.'_post_type' => $post_type,
					],

				]);
			}

			if ( !in_array( 'one_elements_section_query', $excludes) )
			{
				$this->end_controls_section();
			}

		}

		/**
		 * It fetches (post type) ID & Title from the database
		 *
		 * @param $post_type
		 *
		 * @return array|bool|mixed it returns an array of practice area's ID as key & Title as Value.
		 */
		protected function get_posts_of( $post_type ) {

			$posts_args = [
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => '100',//@TODO; in future, use ajax, see elementor core post widget
			];

			$r         = new \WP_Query( $posts_args );
			$posts     = $r->posts;
			if (empty( $posts) && !is_array( $posts)) return ['id'=> 0, 'title' => __('No Items found')];
			$post_list = [];
			foreach ( $posts as $post ) {
				$post_list[ $post->ID ] = $post->post_title;
			}

			return $post_list;

		}

		public function get_query_options() {

			$settings =  $this->get_settings_for_display();
			return array_filter( $settings, function( $k ) {
				return false !== strpos( $k, $this->query_option_key );
			}, ARRAY_FILTER_USE_KEY );

		}


		/**
		 * It out puts taxonomy controls of a given post type
		 *
		 * @param string $post_type name of the post type to show its tax controls
		 * @param array  $condition Custom condition to display tax controls
		 *
		 * @return void
		 */
		protected function get_tax_controls_of( $post_type='post', $condition=[] ) {

			$taxonomy_filter_args = [
				'show_in_nav_menus' => true,
			];

			if ( ! empty( $post_type ) ) {
				$taxonomy_filter_args['object_type'] = [ $post_type ];
			}

			$taxonomies = get_taxonomies( $taxonomy_filter_args, 'objects' );

			foreach ( $taxonomies as $taxonomy => $object ) {
				$taxonomy_args = [
					'label' => sprintf( __('Select %s', 'one-elements'), $object->label),
					'description' => __('Type 1 or more character to search', 'one-elements'),
					'type' => Control_One_Element_Select2::SELECT2,
					'label_block' => true,
					'multiple' => true,
					'object_type' => $taxonomy,
					'options' => "one-elements-select2/{$taxonomy}/term_list",//Rest API end point
					'condition' => !empty( $condition) ? $condition : [
						$this->query_option_key.'_post_type' => $post_type,
					],
				];

				$this->add_control(  "{$this->query_option_key}_{$taxonomy}_slugs" , $taxonomy_args);
			}

		}


		protected function get_query_controls_for_all_posttypes() {

			$this->start_controls_section( 'one_elements_section_query', [
				'label' => __( 'Query Settings', 'one-elements' ),
			]);

			$this->add_control( 'one_elements_fetch_type', [
				'label'       => __( 'Data Type', 'one-elements' ),
				'type'        => Controls_Manager::HIDDEN,
				'default'   => 'multiple',
			]);

			$this->add_group_control( ONE_ELEMENTS_Posts_Group_Control::get_type(), ['name'=> $this->query_option_key]);
			$this->end_controls_section();

		}

		public function get_items_gap_class( $gap_value = 'default' ) {

			$gap_value = empty( $gap_value ) ? 'default' : $gap_value;

			return 'oee__column-gap-' . $gap_value;

		}


		/**
		 * Get attachment image HTML with one-elements-image class.
		 *
		 * Retrieve the attachment image HTML code.
		 *
		 * Note that some widgets use the same key for the media control that allows
		 * the image selection and for the image size control that allows the user
		 * to select the image size, in this case the third parameter should be null
		 * or the same as the second parameter. But when the widget uses different
		 * keys for the media control and the image size control, when calling this
		 * method you should pass the keys.
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 *
		 * @param array  $settings       Control settings.
		 * @param string $image_size_key Optional. Settings key for image size.
		 *                               Default is `image`.
		 * @param string $image_key      Optional. Settings key for image. Default
		 *                               is null. If not defined uses image size key
		 *                               as the image key.
		 *
		 * @return string Image HTML.
		 */
		public function get_attachment_image_html( $settings, $image_size_key = 'image', $image_key = null ) {

			if ( ! $image_key ) {
				$image_key = $image_size_key;
			}

			$image = $settings[ $image_key ];

			// Old version of image settings.
			if ( ! isset( $settings[ $image_size_key . '_size' ] ) ) {
				$settings[ $image_size_key . '_size' ] = '';
			}

			$size = $settings[ $image_size_key . '_size' ];
			$image_class = 'oee__image ';
			//$image_class .= ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

			$html = '';

			// If is the new version - with image size.
			$image_sizes = get_intermediate_image_sizes();

			$image_sizes[] = 'full';

			if ( ! empty( $image['id'] ) && ! wp_attachment_is_image( $image['id'] ) ) {
				$image['id'] = '';
			}

			if ( ! empty( $image['id'] ) && in_array( $size, $image_sizes ) ) {
				$image_class .= " attachment-$size size-$size";
				$image_attr = [
					'class' => trim( $image_class ),
				];

				$html .= wp_get_attachment_image( $image['id'], $size, false, $image_attr );
			} else {
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $image['id'], $image_size_key, $settings );

				if ( ! $image_src && isset( $image['url'] ) ) {
					$image_src = $image['url'];
				}

				if ( ! empty( $image_src ) ) {
					$_image_class = ! empty( $image_class ) ? $image_class : '';

					$html .= sprintf( '<img src="%s" title="%s" alt="%s" class="%s" />', esc_attr( esc_url( $image_src) ), esc_attr(Control_Media::get_image_title( $image )), esc_attr(Control_Media::get_image_alt( $image )), esc_attr($_image_class) );
				}
			}

			return $html;
			
		}
		protected function get_content_max_lines() {
			return apply_filters('one_elements_posts_content_max_lines',[
				'' => __( 'Auto', 'one-elements' ),
				'1'    =>  __( '1', 'one-elements' ),
				'2'    =>  __( '2', 'one-elements' ),
				'3'    =>  __( '3', 'one-elements' ),
				'4'    =>  __( '4', 'one-elements' ),
				'5'    =>  __( '5', 'one-elements' ),
				'6'    =>  __( '6', 'one-elements' ),
				'7'    =>  __( '7', 'one-elements' ),
				'8'    =>  __( '8', 'one-elements' ),
				'9'    =>  __( '9', 'one-elements' ),
				'10'    =>  __( '10', 'one-elements' ),
			]);
		}

		protected function get_content_positions() {

			return apply_filters('one_elements_practice_area_content_positions', [
				'' => __( 'Default', 'one-elements' ),
				'top-left'   => __( 'Top Left', 'one-elements' ),
				'top-center'   => __( 'Top Center', 'one-elements' ),
				'top-right' => __( 'Top Right', 'one-elements' ),
				'center-left' => __( 'Center Left', 'one-elements' ),
				'center-center' => __( 'Center Center', 'one-elements' ),
				'center-right' => __( 'Center Right', 'one-elements' ),
				'bottom-left' => __( 'Bottom Left', 'one-elements' ),
				'bottom-center' => __( 'Bottom Center', 'one-elements' ),
				'bottom-right' => __( 'Bottom Right', 'one-elements' ),
				'mixed' => __( 'Mixed', 'one-elements' ),
			]);

		}


		public function filter_excerpt_length($length) {
			return 30;// return 30 for now and later make it dynamic
		}

		public function filter_excerpt_more( $more ) {
			return ''; // we do not want to show WP's default read more button as we have our own button for this purpose.
		}
		public function get_query_args($control_id, $settings)
		{
			$defaults = [
				$control_id . '_post_type' => 'post',
				$control_id . '_posts_ids' => [],
				$control_id . '_orderby' => 'date',
				$control_id . '_order' => 'desc',
				$control_id . '_limit' => 3,
				$control_id . '_offset' => 0,
			];

			$settings = wp_parse_args($settings, $defaults);

			$this->post_type = $post_type = $settings[$control_id . '_post_type'];

			$query_args = [
				'orderby' => $settings[$control_id . '_orderby'],
				'order' => $settings[$control_id . '_order'],
				'ignore_sticky_posts' => 1,
				'post_status' => 'publish', // Hide drafts/private posts for admins
			];

			// handle manually selected posts by ids query first if applicable
			if ('by_id' === $post_type) {
				$query_args['post_type'] = 'any';
				$query_args['post__in'] = (array) $settings[$control_id . '_posts_ids'];

				if (empty($query_args['post__in'])) {
					// If no selection - return an empty query
					$query_args['post__in'] = [0];
				}
			} else {
				$query_args['post_type'] = $post_type;
				$query_args['posts_per_page'] = $this->post_per_page = $settings[$control_id .'_limit'];
				$query_args['tax_query'] = [];

				$query_args['offset'] = $settings[$control_id .'_offset'];

				// get all tax of selected post type eg. category and post_tag
				$taxonomies = get_object_taxonomies($post_type, 'objects');
				$terms_in_q_options = [];
				if (!empty( $taxonomies) && is_array( $taxonomies)){
					foreach ($taxonomies as $object) {
						if ('post_format' == $object->name) continue; // skip post format tax
						$setting_key = $control_id . '_' . $object->name . '_slugs';
						if (!empty( $settings[$setting_key]) && is_array( $settings[$setting_key])){
							$terms_in_q_options  = array_merge( $terms_in_q_options, $settings[$setting_key]) ;
						}

						if (!empty($settings[$setting_key])) {
							$query_args['tax_query'][] = [
								'taxonomy' => $object->name,
								'field' => 'slug',
								'terms' => $settings[$setting_key],
							];
						}
					}
				}


				// run the following filter query only for ajax filter posts
				$this->taxonomy = !empty( $settings["{$this->filter_option_key}_initial_{$post_type}_tax"])
								? $settings["{$this->filter_option_key}_initial_{$post_type}_tax"]
								: '';// init tax
				$this->term_slug = !empty( $settings["{$this->filter_option_key}_initial_{$this->taxonomy}_slug"])
								 ? $settings["{$this->filter_option_key}_initial_{$this->taxonomy}_slug"]
								 : '';// initial term

				if (
					(!empty( $settings['display_type']) && $settings['display_type'] == 'filter')
					&& $this->is_ajax_filter_enabled( $settings)
					&& !empty($this->taxonomy )
					&& !empty($this->term_slug )
				) {
					// if terms exist in query settings then check if the selected initial filter term exist there too.
					// In other words, initial filter term must be within the query terms if query terms exist.
					if (!empty( $terms_in_q_options)){

						//@TODO; ask why are giving $terms_in_q_options priority here for filter tax, because in the render function, it is said to display post of $this->term_slug only regardless of $terms_in_q_options?
						if ( in_array( $this->term_slug, $terms_in_q_options)){
							unset( $query_args['tax_query']); // unset multiple tax query, and limit this query by the initial filter term
							$query_args['tax_query'][] = [
								'taxonomy' => $this->taxonomy,
								'field'    => 'slug',
								'terms'    => $this->term_slug,
							];
						}
					}else{
						// no terms set in query settings,
						// so we can use any initial term selected by user in the filter settings.
						unset( $query_args['tax_query']);
						$query_args['tax_query'][] = [
							'taxonomy' => $this->taxonomy,
							'field'    => 'slug',
							'terms'    => $this->term_slug,
						];
					}
				}


			}


			// limit author
			if (!empty($settings[$control_id . '_authors'])) {
				$query_args['author__in'] = $settings[$control_id . '_authors'];
			}

			// exclude posts
			$post__not_in = [];
			if (!empty($settings[$control_id . '_' .$post_type.'_not_in'])) {
				$post__not_in = array_merge($post__not_in, $settings[$control_id . '_' .$post_type.'_not_in']);
				$query_args['post__not_in'] = $post__not_in;
			}

			// set tax query relation
			if (isset($query_args['tax_query']) && count($query_args['tax_query']) > 1) {
				$query_args['tax_query']['relation'] = 'OR';
			}

			return $query_args;
		}

		protected function query_posts() {

			$args = $this->get_query_args( $this->query_option_key, $this->get_settings_for_display() );

			$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

			if ( $paged > 1 ) {
				unset( $args['offset'] );
			}

			$args['paged'] = $paged;

			$this->query = new WP_Query( $args ); // put this in the query property to implement pagination later like archive posts

			wp_reset_query();

			return $this->query->posts;

		}

		public function is_ajax_enabled( $settings ) {

			if ($this->is_ajax_filter_enabled( $settings) || $this->is_load_more_enabled( $settings)) return true;

			return false;

		}

		public function is_ajax_filter_enabled( $settings ) {
			return (!empty( $settings[ $this->filter_option_key . '_enable_ajax' ]) && wp_validate_boolean( $settings[ $this->filter_option_key . '_enable_ajax' ] ));
		}

		public function is_load_more_enabled( $settings = [] ) {
			$settings = !empty( $settings) ? $settings : $this->get_settings_for_display();
			return ( ! empty( $settings[$this->lm_prefix.'show_button'] ) && $settings[$this->lm_prefix.'show_button'] == 'yes' );
		}

	}
}