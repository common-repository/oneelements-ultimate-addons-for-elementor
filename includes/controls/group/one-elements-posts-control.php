<?php
namespace OneElements\Includes\Controls\Group;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;
use OneElements\Includes\Controls\Control_One_Element_Select2;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists( 'ONE_ELEMENTS_Posts_Group_Control' ) ) :
	class ONE_ELEMENTS_Posts_Group_Control extends Group_Control_Base {

		protected static $fields;

		public static function get_type() {
			return 'one-elements-posts';
		}

		protected function init_fields() {
			$fields = [];
			$fields['post_type'] = [
				'label' => __( 'Content Source', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
			];

			$fields['posts_ids'] = [
				'label' => __( 'Search & Select', 'one-elements' ),
				'type' => Control_One_Element_Select2::SELECT2,
				'options' => "one-elements-select2/any/post_list",//Rest API end point for post
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'post_type' => ['by_id'],
				],
			];


			$fields['authors'] = [
				'label' => __( 'Author', 'one-elements' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $this->get_authors(), //@todo; make it dynamic
				'condition' => [
					'post_type!' => [ 'by_id'],
				],
			];



			$fields['limit'] = [
				'label' => __( 'Posts Per Page', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4
			];

			$fields['offset'] = [
				'label' => __( 'Offset', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0
			];

			$fields['orderby'] = [
				'label' => __( 'Order By', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => apply_filters('one_elements_orderby', one_elements_get_post_orderby_options()),
				'default' => 'date',
			];

			$fields['order'] = [
				'label' => __( 'Order', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'asc' => 'Ascending',
					'desc' => 'Descending'
				],
				'default' => 'desc',
			];

			return $fields;
		}

		protected function prepare_fields( $fields ) {
			$post_types = get_transient( 'one_elements_public_post_types');
			if (!$post_types) {
				$post_types = one_elements_get_post_types();
				set_transient( 'one_elements_public_post_types', $post_types, MINUTE_IN_SECONDS*30);
			}

			$post_types_options = $post_types;
			$post_types_options['by_id'] = __( 'Manual Selection', 'one-elements' );
			$fields['post_type']['options'] = $post_types_options;
			$fields['post_type']['default'] = key( $post_types );

			$pa = array_keys( $post_types );

			$excludes_fields = [];
			foreach ( $post_types as $p_name => $p_title ){
				$exclude_args = [
					'label' => sprintf( __('Excludes from %s', 'one-elements'), $p_title),
					'type' => Control_One_Element_Select2::SELECT2,
					'options' => "one-elements-select2/{$p_name}/post_list",//Rest API end point
					'label_block' => true,
					'multiple' => true,
					'condition' => [
						'post_type' => $p_name,
						'post_type!' => 'by_id'
					],
				];
				$excludes_fields[ $p_name . '_not_in' ] = $exclude_args;
			}


			//prepare post type's tax controls
			$taxonomies = get_object_taxonomies( $pa, 'objects' );

			$tax_fields = [];
			foreach ( $taxonomies as $taxonomy => $object ) {
				if (!$object->show_in_nav_menus || 'post_format' === $taxonomy) continue;

				$taxonomy_args = [
					'label' => $object->label,
					'type' => Control_One_Element_Select2::SELECT2,
					'options' => "one-elements-select2/{$taxonomy}/term_list",//Rest API end point
					'label_block' => true,
					'multiple' => true,
					'condition' => [
						'post_type' => $object->object_type[0],
						'post_type!' => 'by_id'
					],
				];

				$tax_fields[ $taxonomy . '_slugs' ] = $taxonomy_args;// update to slug
			}



			$new_fields = array_merge( $tax_fields, $excludes_fields);
			// insert tax controls in a proper position
			$fields = one_elements_insert_array_at( $fields, 2, $new_fields);

			return parent::prepare_fields( $fields );
		}



		/**
		 * All authors name and ID, who published at least 1 post.
		 * @return array
		 */
		public function get_authors() {
			$authors = get_transient( 'one_elements_authors');
			if ($authors) return $authors;

			$user_query = new \WP_User_Query(
				[
					'who' => 'authors',
					'has_published_posts' => true,
					'fields' => [
						'ID',
						'display_name',
					],
				]
			);

			$authors = [];

			foreach ( $user_query->get_results() as $result ) {
				$authors[ $result->ID ] = $result->display_name;
			}

			set_transient( 'one_elements_authors', $authors, MINUTE_IN_SECONDS*30);

			return $authors;
		}

		protected function get_default_options() {
			return [
				'popover' => false,
			];
		}
	}
endif;