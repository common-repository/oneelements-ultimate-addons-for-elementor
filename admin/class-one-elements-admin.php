<?php
namespace OneElements\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themexclub.com
 * @since      1.0.0
 *
 * @package    One_Elements
 * @subpackage One_Elements/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    One_Elements
 * @subpackage One_Elements/admin
 * @author     ThemeXclub <hello@themexclub.com>
 */
class One_Elements_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, ONE_ELEMENTS_ADMIN_CSS_URL . 'one-elements-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, ONE_ELEMENTS_ADMIN_JS_URL . 'one-elements-admin.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function elementor_enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name . '-elementor', ONE_ELEMENTS_ADMIN_JS_URL . 'one-elements-elementor-admin.js', array( 'elementor-editor' ), $this->version, true );

	}

	/**
	 * Allow files with .svg extension to be uploaded via
	 * the wordpress media uploader
	 *
	 * @since 1.0.0
	 * @param array $mime_types Existing allowed mime types
	 * @return array
	 * @access public
	 */
	public function wp_allow_svg( $mime_types ) {

		if ( ! in_array( 'svg', $mime_types ) ) { // Check if it hasn't been enabled already
			// allow SVG file upload
			$mime_types['svg'] = 'image/svg+xml|application/octet-stream|image/x-svg+xml';
		}

		return $mime_types;
	}

	/**
	 * A workaround for upload validation which relies on a PHP extension (fileinfo) with inconsistent reporting behaviour.
	 * ref: https://core.trac.wordpress.org/ticket/39550
	 * ref: https://core.trac.wordpress.org/ticket/40175
	 */
	public function filter_fix_wp_check_filetype_and_ext( $data, $file, $filename, $mimes ) {
		
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}

		$registered_file_types = ['svg' => 'image/svg+xml|application/octet-stream|image/x-svg+xml'];
		$filetype = wp_check_filetype( $filename, $mimes );

		if ( ! isset( $registered_file_types[ $filetype['ext'] ] ) ) {
			return $data;
		}

		return [
			'ext' => $filetype['ext'],
			'type' => $filetype['type'],
			'proper_filename' => $data['proper_filename'],
		];
	}

	public function admin_pages() {

		add_menu_page(
			__( 'OneElements', 'oneelements' ),
			'OneElements',
			'manage_options',
			'one-elements',
			[$this, 'admin_view'],
			ONE_ELEMENTS_ADMIN_ASSET_URL . 'img/oneelements-icon.svg',
			59
		);

	}

	public function admin_view() {
		
		include ONE_ELEMENTS_ADMIN_PATH . 'partials/one-elements-admin-display.php';

	}

	public function icon_css() {
		
		echo "<style>#adminmenu .toplevel_page_one-elements .wp-menu-image img { padding-top:7px; width:20px; opacity:.8; height:auto; }</style>";

	}

}