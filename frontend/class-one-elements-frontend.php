<?php
namespace OneElements\Frontend;

/**
 * The frontend-facing functionality of the plugin.
 *
 * @link       https://themexclub.com
 * @since      1.0.0
 *
 * @package    One_Elements
 * @subpackage One_Elements/Frontend
 */

/**
 * The frontend-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend-facing stylesheet and JavaScript.
 *
 * @package    One_Elements
 * @subpackage One_Elements/Frontend
 * @author     ThemeXclub <hello@themexclub.com>
 */
class One_Elements_Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in One_Elements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The One_Elements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$one_elements_frontend = 'one-elements-frontend';
		$one_elements_frontend = ( SCRIPT_DEBUG ) ? $one_elements_frontend : $one_elements_frontend.'.min';

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/'.$one_elements_frontend.'.css', array('elementor-frontend'), null, 'all' );

	}

	/**
	 * Register the JavaScript for the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in One_Elements_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The One_Elements_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$one_elements_frontend = 'one-elements-frontend';
		$one_elements_frontend = ( SCRIPT_DEBUG ) ? $one_elements_frontend : $one_elements_frontend.'.min';

		wp_register_script( 'one-elements--isotope', plugin_dir_url( __FILE__ ) . 'libs/isotope/isotope.min.js', array( 'jquery' ), null, true );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/'.$one_elements_frontend.'.js', array( 'jquery' ), null, true );

		wp_localize_script( $this->plugin_name, 'oee_data', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'security' => wp_create_nonce( "oee_load_more_posts--nonce" )
		));

		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Register the JavaScript for the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function load_more_posts() {

		check_ajax_referer( 'oee_load_more_posts--nonce', 'security' );

		$args = array(
			'post__not_in' => array(),
			'post_type'   => 'post',
			'post_status' => 'publish',
			'order'               => 'DESC',
			'orderby'             => 'date',
			'ignore_sticky_posts' => false,
			'posts_per_page'      => 6,
		);

		$tax_query_defaults = array(
			'field'            => 'slug',
			'include_children' => true,
			'operator'         => 'IN',
		);
		$post_args = (array) $_POST['query_args'];
		$args = one_elements_wp_parse_args_recursive( $post_args, $args );

		if ( isset( $args['tax_query'] ) && isset( $args['tax_query']['queries'] ) ) {

			$tax_queries = array();

			foreach ( $args['tax_query']['queries'] as $tax_query ) {
				$tax_queries[] = one_elements_wp_parse_args_recursive( $tax_query, $tax_query_defaults );
			}
			
			$args['tax_query'] = $tax_queries;
	
		}
		
		$query = new \WP_Query( $args );

		$data = $query->posts;
			
		foreach ( $data as $post ) {

			// Add Excerpt
			$post->post_excerpt = get_the_excerpt( $post );

			// Add Thumbnail
			$post->thumbnail = get_the_post_thumbnail_url( $post->ID, 'full' );

			// Add Icon
			$post_icon = get_post_meta( $post->ID, $key = '_post_icon', true );
			if ( !empty($post_icon) ) {
				$post->post_icon = render_theme_icon( $post_icon );
			}

			if ( ! isset($_POST['additional']) ) continue;

			if ( isset( $_POST['additional']['taxonomy'] ) ) {
				$post->taxonomies = get_the_terms( $post->ID, sanitize_text_field( $_POST['additional']['taxonomy']) );
			}

			// Add Author
			if ( wp_validate_boolean( $_POST['additional']['include_author'] ) ) {

				$user_id = $post->post_author;
				$avatar_size = 45;

				$post->author_link = esc_url( get_author_posts_url( $user_id ) );
				$post->author_avatar = get_avatar_url( $user_id, ['size' => $avatar_size] );
				$post->author_display_name = get_the_author_meta( 'display_name', $user_id );

			}

			// Add Comments
			if ( wp_validate_boolean( $_POST['additional']['include_comments'] ) ) {
				$post->comment_text  = $post->comment_count > 1 ? __('Comments', 'one-elements') : __('Comment', 'one-elements');
			}

			// Add Taxonomy Links
			if ( wp_validate_boolean( $_POST['additional']['include_taxonomy_links'] ) ) {

				if ( !empty($post->taxonomies) && count($post->taxonomies) > 0 ) {
					foreach ( $post->taxonomies as $index => $term ) {
						$post->taxonomies[$index]->term_link = get_term_link( $term );
					}
				}

			}

			// Add Date
			if ( wp_validate_boolean( $_POST['additional']['include_date'] ) ) {

				$date_format = !empty( $_POST['additional']['date_format'] ) ? sanitize_text_field( $_POST['additional']['date_format'] ) : '';
				
				$post->iso_date = get_the_date( 'c', $post );
				$post->display_date = get_the_date( $date_format, $post );

			}

			// Add Date
			if ( wp_validate_boolean( $_POST['additional']['include_time'] ) ) {
				$post->display_time = date( 'h:i A', get_post_time('U', false, $post) );
			}

		}

		return wp_send_json_success( $data, 200 );

		wp_die();

	}

}
