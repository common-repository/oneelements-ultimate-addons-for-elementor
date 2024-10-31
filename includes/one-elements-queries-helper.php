<?php

/**
 * Get All POst Types
 * @return array
 */
function one_elements_get_post_types(){

	$one_elements_cpts = get_post_types( [ 'public'   => true, 'show_in_nav_menus' => true ], 'object' );

	$one_elements_exclude_cpts = apply_filters('one_elements_query_excluded_post_types', ['elementor_library', 'attachment', 'product', 'practice', 'page', 'case-study']);

	foreach ( $one_elements_exclude_cpts as $exclude_cpt ) {
		unset($one_elements_cpts[$exclude_cpt]);
	}
	$types = [];
	foreach( $one_elements_cpts as $type ) {
		$types[ $type->name ] = $type->label;
	}

	return $types;
}

function one_e_get_post_public_nav_types(){

	$one_elements_cpts = get_post_types( [ 'public'   => true, 'show_in_nav_menus' => true ], 'object' );

	$types = [];
	foreach( $one_elements_cpts as $type ) {
		$types[ $type->name ] = $type->label;
	}

	return $types;
}

/**
 * Get all types of post.
 * @return array
 */
function one_elements_get_posts_from_all_types(){
	return one_elements_get_posts_from_post_type('any');
}


function one_elements_get_posts_from_post_type($post_type='post') {
	$posts_args = [
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => '-1',
	];

	$r         = new WP_Query( $posts_args );
	$posts     = $r->posts;
	$post_list = [];

	foreach ( $posts as $p ){
		$post_list[ $p->ID ] = $p->post_title;
	}

	return $post_list;
}



/**
 * Getting Excerpts By Post Id
 * @param  int $post_id
 * @param  int $excerpt_length
 * @return string
 */
function one_elements_get_excerpt_by_id( $post_id, $excerpt_length ){
	$the_post = get_post( $post_id ); //Gets post ID

	$the_excerpt = null;
	if( $the_post ){
		$the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content;
	}

	$the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) ); //Strips tags and images
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);

	if(count($words) > $excerpt_length) :
		array_pop($words);
		array_push($words, '…');
		$the_excerpt = implode(' ', $words);
	endif;

	return $the_excerpt;
}

/**
 * Get Post Thumbnail Size
 * @return array
 */
function one_elements_get_thumbnail_sizes(){
	$sizes = get_intermediate_image_sizes();
	$ret=[];
	foreach($sizes as $s){
		$ret[$s] = $s;
	}

	return $ret;
}

/**
 * POst Orderby Options
 * @return array
 */
function one_elements_get_post_orderby_options(){
	$orderby = [
		'ID'            => 'Post ID',
		'author'        => 'Post Author',
		'title'         => 'Title',
		'date'          => 'Date',
		'modified'      => 'Last Modified Date',
		'parent'        => 'Parent Id',
		'rand'          => 'Random',
		'comment_count' => 'Comment Count',
		'menu_order'    => 'Menu Order',
	];

	return $orderby;
}


// Get all elementor page templates
if ( !function_exists('one_elements_get_page_templates') ) {
	function one_elements_get_page_templates(){
		$page_templates = get_posts( [
			'post_type'         => 'elementor_library',
			'posts_per_page'    => -1
		]);

		$options = array();

		if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
			foreach ( $page_templates as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		}
		return $options;
	}
}

// Get all Authors
if ( !function_exists('one_elements_get_authors') ) {
	function one_elements_get_authors() {
		$options = [];
		$users = get_users();
		if($users) {
			foreach( $users as $user ) {
				$options[ $user->ID ] = $user->display_name;
			}
		}
		return $options;
	}
}

// Get all Authors
if ( !function_exists('one_elements_get_tags') ) {
	function one_elements_get_tags() {
		$options = [];
		$tags = get_tags();
		foreach ( $tags as $tag ) {
			$options[ $tag->term_id ] = $tag->name;
		}
		return $options;
	}
}




if (!function_exists( 'one_elements_get_query_args')){
	function one_elements_get_query_args( $control_id, $settings ) {
		$defaults = [
			$control_id . '_post_type' => 'post',
			$control_id . '_posts_ids' => [],
			'orderby' => 'date',
			'order' => 'desc',
			'posts_per_page' => 3,
			'offset' => 0,
		];

		$settings = wp_parse_args( $settings, $defaults );

		$post_type = $settings[ $control_id . '_post_type' ];

		$query_args = [
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish', // Hide drafts/private posts for admins
		];

		if ( 'by_id' === $post_type ) {
			$query_args['post_type'] = 'any';
			$query_args['post__in']  = $settings[ $control_id . '_posts_ids' ];

			if ( empty( $query_args['post__in'] ) ) {
				// If no selection - return an empty query
				$query_args['post__in'] = [ 0 ];
			}
		} else {
			$query_args['post_type'] = $post_type;
			$query_args['posts_per_page'] = $settings['posts_per_page'];
			$query_args['tax_query'] = [];

			$query_args['offset'] = $settings['offset'];

			$taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $control_id . '_' . $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$query_args['tax_query'][] = [
						'taxonomy' => $object->name,
						'field' => 'term_id',
						'terms' => $settings[ $setting_key ],
					];
				}
			}
		}

		if ( ! empty( $settings[ $control_id . '_authors' ] ) ) {
			$query_args['author__in'] = $settings[ $control_id . '_authors' ];
		}

		$post__not_in = [];
		if ( ! empty( $settings['post__not_in'] ) ) {
			$post__not_in = array_merge( $post__not_in, $settings['post__not_in'] );
			$query_args['post__not_in'] = $post__not_in;
		}

		if( isset( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 ) {
			$query_args['tax_query']['relation'] = 'OR';
		}

		return $query_args;
	}
}



if ( !function_exists( 'one_elements_get_practice_cats') ) {

	function one_elements_get_practice_cats( $hide_empty=0, $index_key='slug', $prepend=[] ) {

		$pCats = get_categories([
			'taxonomy' => 'practice-category',
			'hide_empty' => $hide_empty
		]);

		$r = wp_list_pluck( $pCats, 'name', $index_key );
		
		if ( !empty($prepend) && is_array($prepend) ) {
			$r = $prepend + $r;
		}

		return $r;

	}
	
}
