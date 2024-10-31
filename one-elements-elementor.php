<?php
namespace OneElements;

use OneElements\Includes\One_Elements;
use OneElements\Includes\One_Elements_Activator;
use OneElements\Includes\One_Elements_Deactivator;

/**
 * @link              https://themexclub.com
 * @since             1.0.0
 * @package           One_Elements
 * @wordpress-plugin
 * Plugin Name:       OneElements - Ultimate Addons for Elementor
 * Plugin URI:        https://oneelements.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Description:       The Most Advanced Elementor Addons to customize everything by ThemeXclub
 * Version:           1.3.7
 * Author:            ThemeXclub
 * Author URI:        https://themexclub.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       one-elements
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) die;


/**
 * Define constants.
 *
 * @since 1.0.0
 * @ignore
 *
 */
	
define( 'ONE_ELEMENTS_VERSION', '1.3.7' );

define( 'ONE_ELEMENTS_FILE',  __FILE__ );
define( 'ONE_ELEMENTS_PATH',  plugin_dir_path( __FILE__ ) );
define( 'ONE_ELEMENTS_INC_PATH', ONE_ELEMENTS_PATH  . 'includes/');
define( 'ONE_ELEMENTS_GLOBAL_PATH', ONE_ELEMENTS_PATH  . 'global/');
define( 'ONE_ELEMENTS_WIDGET_PATH', ONE_ELEMENTS_INC_PATH  . 'widgets/');

define( 'ONE_ELEMENTS_URL', plugin_dir_url( __FILE__) );
define( 'ONE_ELEMENTS_ASSET_URL', ONE_ELEMENTS_URL . 'assets/' );
define( 'ONE_ELEMENTS_CSS_URL', ONE_ELEMENTS_ASSET_URL . 'css/' );
define( 'ONE_ELEMENTS_JS_URL', ONE_ELEMENTS_ASSET_URL . 'js/' );

define( 'ONE_ELEMENTS_FRONTEND_PATH',  ONE_ELEMENTS_PATH . 'frontend/' );
define( 'ONE_ELEMENTS_FRONTEND_URL',  ONE_ELEMENTS_URL . 'frontend/' );

define( 'ONE_ELEMENTS_ADMIN_PATH',  ONE_ELEMENTS_PATH . 'admin/' );
define( 'ONE_ELEMENTS_ADMIN_URL', ONE_ELEMENTS_URL . 'admin/' );
define( 'ONE_ELEMENTS_ADMIN_ASSET_URL', ONE_ELEMENTS_ADMIN_URL . 'assets/' );
define( 'ONE_ELEMENTS_ADMIN_CSS_URL', ONE_ELEMENTS_ADMIN_ASSET_URL . 'css/' );
define( 'ONE_ELEMENTS_ADMIN_JS_URL', ONE_ELEMENTS_ADMIN_ASSET_URL . 'js/' );



/**
 * Plugin Activator
 *
 * @since 1.0.0
 * @ignore
 *
 * @return void
 */
function activate_one_elements() {

	require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-activator.php';
	One_Elements_Activator::activate();

}

register_activation_hook( __FILE__, __NAMESPACE__.'\activate_one_elements' );



/**
 * Plugin Deactivator
 *
 * @since 1.0.0
 * @ignore
 *
 * @return void
 */
function deactivate_one_elements() {

	require_once ONE_ELEMENTS_INC_PATH . 'class-one-elements-deactivator.php';
	One_Elements_Deactivator::deactivate();

}

register_deactivation_hook( __FILE__, __NAMESPACE__.'\deactivate_one_elements' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_one_elements() {

	if ( !in_array( 'elementor/elementor.php', (array) get_option('active_plugins', []) ) ) {
		// show notice that free version is required
		add_action('admin_notices', __NAMESPACE__.'\oee_elementor_version_required_notice');
		return;
	}

	require ONE_ELEMENTS_INC_PATH . 'class-one-elements.php';

	$plugin = new One_Elements();
	do_action( 'before-one-elements-loaded');
	$plugin->run();

	do_action( 'one-elements-loaded');
	do_action( 'after-one-elements-loaded');

}

add_action( 'plugins_loaded', 'OneElements\run_one_elements' );

function oee_elementor_version_required_notice() {
	?>
	<div id="message" class="error notice is-dismissible">
		<p><strong>OneElements - Ultimate Addons for Elementor</strong> plugin requires the <strong>Elementor</strong> plugin to be installed on your site.</p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
	<?php
}