<?php
namespace OneElements\Includes;

use OneElements\Admin\One_Elements_Admin;
use OneElements\Frontend\One_Elements_Frontend;
use OneElements\Includes\Feedback\ProductFeedbackClient;

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * frontend-facing side of the site and the admin area.
 * @link       https://themexclub.com
 * @since      1.0.0
 * @package    One_Elements
 * @subpackage One_Elements/includes
 */



/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * frontend-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    One_Elements
 * @subpackage One_Elements/includes
 * @author     ThemeXclub <hello@themexclub.com>
 */
class One_Elements {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      One_Elements_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the frontend-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'one-elements';

		$this->version = defined('ONE_ELEMENTS_VERSION') ? ONE_ELEMENTS_VERSION : '1.0.0';

		do_action( 'one_element/before_loaded_dependency');
		$this->load_dependencies();
		do_action( 'one_element/after_loaded_dependency');


		$this->set_locale();
		
		$this->define_admin_hooks();
		$this->define_public_hooks();


	}



	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - One_Elements_Loader. Orchestrates the hooks of the plugin.
	 * - One_Elements_i18n. Defines internationalization functionality.
	 * - One_Elements_Admin. Defines all hooks for the admin area.
	 * - One_Elements_Frontend. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once ONE_ELEMENTS_INC_PATH . 'one-elements-helper.php';
		require_once ONE_ELEMENTS_INC_PATH . 'utils.php';
		require_once ONE_ELEMENTS_INC_PATH . 'one-elements-queries-helper.php';
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-settings-api.php';

		/**
		 * The class responsible for handling data over rest api specially for ajax select2
		 */
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-rest-api.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-i18n.php';

		/**
		 * The class responsible for upgrading
		 * of the plugin.
		 */
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-upgrader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once ONE_ELEMENTS_PATH . 'admin/class-one-elements-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the frontend-facing
		 * side of the site.
		 */
		require_once ONE_ELEMENTS_PATH . 'frontend/class-one-elements-frontend.php';

		/**
		 * The class responsible for all elementor related tasks
		 */
		require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-editor.php';

		//feedback or plugin usage tracker
		//require_once ONE_ELEMENTS_INC_PATH . 'feedback/product-feedback-client.php';

		$this->loader = new One_Elements_Loader();

		// custom elementor widgets;
		new One_Elements_Editor();
		// activate plugin usage tracking.
		//$tracker_home = 'https://04fa24c7.ngrok.io/wp-json/product-feedback/v1/create'; // test site
		//$tracker_home = 'https://txc.local/wp-json/product-feedback/v1/create'; // test site
		//new ProductFeedbackClient(ONE_ELEMENTS_FILE, $tracker_home );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the One_Elements_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new One_Elements_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new One_Elements_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'elementor_enqueue_scripts' );
		// Allow SVG upload
		$this->loader->add_action( 'wp_check_filetype_and_ext', $plugin_admin, 'filter_fix_wp_check_filetype_and_ext', 10, 4 );
		$this->loader->add_action( 'upload_mimes', $plugin_admin, 'wp_allow_svg' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_pages' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'icon_css' );

	}

	/**
	 * Register all of the hooks related to the frontend-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new One_Elements_Frontend( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_load_more_posts', $plugin_public, 'load_more_posts' );
		$this->loader->add_action( 'wp_ajax_nopriv_load_more_posts', $plugin_public, 'load_more_posts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    One_Elements_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}