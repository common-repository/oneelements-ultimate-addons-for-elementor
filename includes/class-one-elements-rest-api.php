<?php
namespace OneElements\Includes;

use WP_REST_Request;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

class One_Elements_Rest_API {
	public $prefix = '';
	public $param = '';
	/**
	 * @var WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public $request = null;

	public function __construct(){
		$this->prefix = 'one-elements-select2';
		$this->register_rest_endpoint();
	}


	public function register_rest_endpoint(){
		add_action( 'rest_api_init', function () {
			register_rest_route(
				untrailingslashit('one-elements/v1/' . $this->prefix),
				'/(?P<post_or_tax_type>\p{L}+(?:\-\p{L}+)*)/(?P<action>\w+)/' . ltrim($this->param, '/'),
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => [$this, 'action'],
					'permission_callback' => '__return_true'
				)
			);
		});

		// we need the second api until we can match(name, name-part, name_part etc.), eg. post|case-study|post_tag texts in the url.
		add_action( 'rest_api_init', function () {
			register_rest_route(
				untrailingslashit('one-elements/v1/' . $this->prefix),
				'/(?P<post_or_tax_type>\p{L}+(?:\_\p{L}+)*)/(?P<action>\w+)/' . ltrim($this->param, '/'),
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => [$this, 'action'],
					'permission_callback' => '__return_true'
				)
			);
		});
		// here <action> = post_list | term_list
		// example match for post & tax: Pay attention to - and _ too.
		//http://themexclub.local/wp-json/one-elements/v1/one-elements-select2/post-type_name/post_list/?query_var=test
		//http://themexclub.local/wp-json/one-elements/v1/one-elements-select2/taxonomy-type_name/term_list/?query_var=test
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function action($request){
		$this->request = $request;
		$action_method = strtolower($this->request->get_method()) .'_'. sanitize_key($this->request['action']);
		if(method_exists($this, $action_method)){
			return $this->{$action_method}($this->request['post_or_tax_type']); // post_or_tax_type: any post type or any taxonomy
		}
		return null;
	}


	public function get_post_list($post_type=''){
		return $this->get_the_results($post_type);
	}

	public function get_term_list( $taxonomy = '' ) {
		// Let's not return any data if the user does not type anything or the selected data requested ids is empty. It will improve response time and memory usage.
		if (empty( $this->request['q']) && empty( $this->request['ids'])) {
			return ['results' => []];
		}

		$taxonomy = !empty( $taxonomy ) ? $taxonomy
			: (!empty( $this->request['post_or_tax_type']) ? trim( $this->request['post_or_tax_type']) : '');
			return $this->get_terms( $taxonomy);
	}

	public function get_the_results( $post_type='' ) {
		global $wpdb;
		// vail early without querying database, if the ids, or s query var is set but they are empty. NO need to hit DB
		if(isset($this->request['ids'])){
			if (empty( $this->request['ids'])) return ['results' => []];
		}
		if(isset($this->request['q'])){
			if (empty( $this->request['q'])) return ['results' => []];
		}

		// Let's not return any data if the user does not type anything or the selected data requested ids is empty. It will improve response time and memory usage.
		if (empty( $this->request['q']) && empty( $this->request['ids'])) {
			return ['results' => []];
		}

		$st = $this->get_sql_statement( $post_type);
		return ['results' => $wpdb->get_results($st, ARRAY_A)];
	}

	public function get_sql_statement( $post_type ) {
		global $wpdb;

		$post_type = !empty( $post_type ) ? $post_type
					: (!empty( $this->request['post_or_tax_type']) ? trim( $this->request['post_or_tax_type']) : '');

		$tbl = $wpdb->posts;
		$stmnt = "SELECT $tbl.ID as id, $tbl.post_title as text FROM $tbl";
		$oder_by =" ORDER BY $tbl.menu_order ASC LIMIT 10";
		$where = " WHERE 1=1 AND $tbl.post_status = 'publish'";

		if ( !empty( $post_type) && 'any' != $post_type ){
			$where .= " AND $tbl.post_type = '$post_type'";
		}else{
			$post_types = get_transient( 'one_e_get_post_public_nav_types');
			if (!$post_types) {
				$post_types = one_e_get_post_public_nav_types();
				set_transient( 'one_e_get_post_public_nav_types', $post_types, MINUTE_IN_SECONDS*30);
			}
			$post_types = array_keys( $post_types);
			$pts = implode( ',', array_map( function ($item ){
				return "'".sanitize_text_field( $item)."'";
			}, $post_types ) );

			$where .= " AND $tbl.post_type IN ($pts)";
		}

		if(isset($this->request['ids'])){
			$ids = explode(',', $this->request['ids']);
			$ids = implode( ',', array_map( 'absint',$ids ) );
			$where .= " AND $tbl.ID IN ($ids)";
		}

		if(isset($this->request['q'])){
			$search_term = $this->request['q'];
			$n = ! empty( $this->request['exact'] ) ? '' : '%';
			$where .= " AND $tbl.post_title LIKE '$n".$wpdb->esc_like( $search_term )."$n'";
		}

		return $stmnt . $where . $oder_by;

	}

	public function get_terms($taxonomy){
		$query_args = [
			'taxonomy'      => $taxonomy,
			'orderby'       => 'name',
			'order'         => 'DESC',
			'hide_empty'    => true,
			'number'        => 15
		];

		if(isset($this->request['ids'])){
			// if empty return immediately without quering database
			if (empty( $this->request['ids'])) return ['results' => []];
			$ids = explode(',', $this->request['ids']);
			$query_args['slug'] = $ids;
		}
		if(isset($this->request['q']) ){
			if (empty( $this->request['q'])) return ['results' => []];
			$query_args['name__like'] = $this->request['q'];
		}

		$terms = get_terms( $query_args );
		$options = [];

		if(count($terms)){
			foreach ($terms as $term) {
				$options[] = [ 'id' => $term->slug, 'text' => $term->name ];
			}
		}

		return ['results' => $options];
	}

}
new One_Elements_Rest_API();