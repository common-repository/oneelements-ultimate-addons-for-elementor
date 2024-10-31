<?php

/**
 * Helper Functions
 *
 * @since    1.0.0
 */
if ( !function_exists( 'uniqidReal') ) {
	function uniqidReal( $lenght = 13 ) {
		// uniqid gives 13 chars, but you could adjust it to your needs.
		if (function_exists("random_bytes")) {
			try {
				$bytes = random_bytes(ceil($lenght / 2));
			}catch (Exception $e){
				// handle exception
			}
		} elseif (function_exists("openssl_random_pseudo_bytes")) {
			$bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
		} else {
			throw new Exception("no cryptographically secure random function available");
		}
		return substr(bin2hex($bytes), 0, $lenght);
	}
}
if ( ! function_exists( 'one_elements_get_elementor_tmpl_lists') ) :



	/**
	 * Fetch saved elementor templates
	 * @return array
	 */
	function one_elements_get_elementor_tmpl_lists() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			return [ '' => __('Elementor has been not loaded', 'one-elements')];
		}

		$templates_query = new \WP_Query(
			[
				'post_type' => 'elementor_library',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'ASC',
				'meta_key' => '_elementor_template_type',
			]
		);

		$templates = [];

		if ( $templates_query->have_posts() ) {

			foreach ( $templates_query->get_posts() as $p ) {
				/** @var WP_Post $p*/
				$templates[$p->ID] = $p->post_title;
			}

		}

		return $templates;

	}

endif;



if ( !function_exists( 'one_elements_get_page_array' ) ) :

	/**
	 * Return Pages as ID => Title pair
	 *
	 * @return array
	 */
	function one_elements_get_page_array() {

		$pages = get_pages();

		if ( empty($pages) ) return [ '' => __('No Page Found', 'one-elements') ];

		$p_array = [];

		foreach ( $pages as $page ) {
			$p_array[ $page->ID ] = $page->post_title;
		}

		return $p_array;

	}

endif;



if ( ! function_exists( 'one_elements_get_ae_tmpl_lists') ) :

	/**
	 * Fetch saved templates of anywhere elementor plugin
	 * @return array
	 */
	function one_elements_get_ae_tmpl_lists() {

		if ( ! post_type_exists('ae_global_templates') ) return ['0' => esc_html__( 'No Template found', 'one-elements' ) ]; // vail early

		$ae_posts = get_posts([
			'posts_per_page' => -1,
			'post_type'      => 'ae_global_templates',
		]);

		if ( empty($ae_posts) || !is_array($ae_posts) ) return ['0' => esc_html__( 'Nowhere Elementor\'s Template found', 'one-elements' ) ];

		// build the array & return it
		$ae_posts_options = ['0' => esc_html__( 'Select Template', 'one-elements' ) ];

		foreach ($ae_posts as $ae_post) {
			/* @var WP_Post $ae_post */
			$ae_posts_options[ $ae_post->ID ] = $ae_post->post_title;
		}

		return $ae_posts_options;

	}

endif;



if ( ! function_exists( 'one_elements_get_cb_tmpl_lists') ) :

	/**
	 * Fetche saved templates of content blocks
	 * @return array
	 */
	function one_elements_get_cb_tmpl_lists() {

		if ( ! post_type_exists('ec_content_blocks')) return ['0' => esc_html__( 'No Content blocks found', 'one-elements' ) ]; // vail early

		$ae_posts = get_posts([
			'posts_per_page' => -1,
			'post_type'      => 'ec_content_blocks',
		]);

		if ( empty($ae_posts) || !is_array($ae_posts) ) return ['0' => esc_html__( 'No Content blocks found', 'one-elements' ) ];

		// build the array & return it
		$ae_posts_options = ['0' => esc_html__( 'Select Template', 'one-elements' ) ];

		foreach ($ae_posts as $ae_post) {
			/* @var WP_Post $ae_post */
			$ae_posts_options[ $ae_post->ID ] = $ae_post->post_title;
		}

		return $ae_posts_options;
	}

endif;



if ( ! function_exists( 'one_elements_insert_array_at') ) :

	/**
	 * Insert array item into the given position
	 * @param array      $array the array to merge with
	 * @param int|string $position at which position to insert the array
	 * @param mixed      $insert this array will be inserted
	 * @return array
	 */
	function one_elements_insert_array_at( $array, $position, $insert ) {

		$merged_array = array_slice( $array, 0, $position, true ) + $insert + array_slice( $array, $position, count($array) - $position, true );

		return $merged_array;

	}

endif;


if ( ! function_exists( 'render_theme_icon') ) :

	/**
	 * Insert array item into the given position
	 * @param array      $array the array to merge with
	 * @param int|string $position at which position to insert the array
	 * @param mixed      $insert this array will be inserted
	 * @return array
	 */
	function render_theme_icon( $icon = '' ) {
		
		$icon = json_decode( $icon, true );

		if ( isset($icon['class']) && !empty($icon['class'])  ) {
			return "<i class='".$icon['class']."'></i>";
		}

		if ( isset($icon['id']) && !empty($icon['id']) ) {
			return Elementor\Core\Files\Assets\Svg\Svg_Handler::get_inline_svg( $icon['id'] );
		}

		return '';

	}

endif;


if ( ! function_exists( 'one_elements_wp_parse_args_recursive') ) :

	/**
	 * It merges args recursively.
	 * @param array      args the array to merge with
	 * @param array     $defaults Default values to merge args with
	 * @return array    returned the recursively merged array
	 */
	function one_elements_wp_parse_args_recursive( $args, $defaults ) {

		if ( !is_array( $args) ) return $defaults;

		$new_args = (array) $defaults;
		foreach ( $args as $key => $value ) {

			if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
				if ( one_is_assoc_array( $value) ){
					$new_args [$key] = $value;
				}else{
					$new_args[ $key ] = one_elements_wp_parse_args_recursive( $value, $new_args[ $key ] );
				}
			}
			else {
				$new_args[ $key ] = $value;
			}
		}

		return $new_args;

	}

endif;

if ( !function_exists( 'one_is_assoc_array') ) {

	/**
	 * It checks if an array is sequential(array keys are number) or non sequential ( array keys are string )
	 * @param array $arr
	 *
	 * @return bool
	 */
	function one_is_assoc_array(array $arr) {
		if ([] === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

}

if ( !function_exists( 'one_get_image_size') ) {
	/**
	 * It returns the height, width and crop information of an image size key.
	 * @param string $size_key
	 *
	 * @return array|boolean
	 */
	function one_get_image_size($size_key)
	{
		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];
		if ( in_array( $size_key, $default_image_sizes) )
		{
			return[
					'width' => (int) get_option( $size_key . '_size_w' ),
					'height' => (int) get_option( $size_key . '_size_h' ),
					'crop' => (bool) get_option( $size_key . '_crop' ),
				];
		}
		if ( array_key_exists( $size_key, $_wp_additional_image_sizes) )
		{
			return $_wp_additional_image_sizes[$size_key];
		}

		return false;


	}
}


if ( !function_exists( 'one_get_public_post_types') )
{
	function one_get_public_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types elementor should work on
		 *
		 * @since 2.3.0
		 *
		 * @param array $post_types Elementor supported public post types.
		 */
		return apply_filters( 'one_elements/utils/get_public_post_types', $post_types );
	}
}

if ( !function_exists( 'one_elements_allowed_html') )
{
	function one_elements_allowed_html() {
	return array(
		'a' => [
			'class' => [],
			'href' => [],
			'rel' => [],
			'title' => [],
		],
		'time' => [
			'class' => [],
			'id' => [],
			'datetime' => [],
			'title' => [],
		],
		'abbr' => [
			'title' => [],
		],
		'b' => [],
		'blockquote' => [
			'cite' => [],
		],
		'cite' => [
			'title' => [],
		],
		'code' => [],
		'del' => [
			'datetime' => [],
			'title' => [],
		],
		'dd' => [],
		'div' => [
			'class' => [],
			'title' => [],
			'style' => [],
		],
		'dl' => [],
		'dt' => [],
		'em' => [],
		'h1' => [
			'class' => [],
		],
		'h2' => [
			'class' => [],
		],
		'h3' => [
			'class' => [],
		],
		'h4' => [
			'class' => [],
		],
		'h5' => [
			'class' => [],
		],
		'h6' => [
			'class' => [],
		],
		'i' => [
			'class' => [],
		],
		'img' => [
			'alt' => [],
			'class' => [],
			'height' => [],
			'src' => [],
			'width' => [],
		],
		'li' => [
			'class' => [],
		],
		'ol' => [
			'class' => [],
		],
		'p' => [
			'class' => [],
		],
		'q' => [
			'cite' => [],
			'title' => [],
		],
		'span' => [
			'id' => [],
			'class' => [],
			'title' => [],
			'style' => [],
		],
		'iframe' => [
			'width' => [],
			'height' => [],
			'scrolling' => [],
			'frameborder' => [],
			'allow' => [],
			'src' => [],
		],
		'strike' => [],
		'br' => [],
		'strong' => [],
		'ul' => [
			'class' => [],
		],
		'svg' => array(
			'class' => true,
			'aria-hidden' => true,
			'aria-labelledby' => true,
			'role' => true,
			'xmlns' => true,
			'width' => true,
			'height' => true,
			'viewbox' => true, // <= Must be lower case!
		),
		'g' => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path' => array( 'd' => true, 'fill' => true, ),
	);
}
}

/**
 * Get a list of all the allowed html tags.
 * @return array
 */
function one_elements_allowed_basic_html() {
	return [
		'b' => [],
		'i' => [],
		'u' => [],
		'em' => [],
		'br' => [],
		'abbr' => [
			'title' => [],
		],
		'span' => [
			'class' => [],
		],
		'strong' => [],
		'a' => [
			'href' => [],
			'title' => [],
			'class' => [],
			'id' => [],
		],
	];
}
if ( !function_exists( 'one_elements_get_section_templates') ) {
	function one_elements_get_section_templates() {
		$items = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
		if ( ! empty( $items ) ) {
			$items = wp_list_filter( $items, ['type' => 'section'] );
			$items = wp_list_pluck( $items, 'title', 'template_id' );
			return $items;
		}
		return [];
	}
}


/**
 * Strip all the tags except allowed html tags
 * @param string $text
 * @return string
 */
function one_kses_basic( $text = '' ) {
	return wp_kses( $text, one_elements_allowed_basic_html(  ) );
}

if ( !function_exists( 'oee_is_elementor_pro_active') ) {
	function oee_is_elementor_pro_active() {
		$plugin = 'elementor-pro/elementor-pro.php';
		if (function_exists( 'is_plugin_active')){
			return is_plugin_active( $plugin);
		}
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( $plugin);
	}
}