<?php
namespace OneElements\Includes;

use OneElements\Includes\Upgrade\Manager;

/**
 * Upgrader
 *
 * @link       https://themexclub.com
 * @since      1.3.2
 *
 * @package    One_Elements
 * @subpackage One_Elements/includes
 */

/**
 * Upgrader
 *
 * @since      1.0.0
 * @package    One_Elements
 * @subpackage One_Elements/includes
 * @author     ThemeXclub <hello@themexclub.com>
 */
class One_Elements_Upgrader {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'elementor/init', [$this, 'maybe_upgrade'] );
		add_action( 'elementor/document/save_version', [$this, 'save_version'] );

	}
	
	public function save_version( $document ) {

		$document->update_meta( '_one_elements_version', ONE_ELEMENTS_VERSION );

	}
	
	public function maybe_upgrade() {

		global $wpdb;

		require_once ONE_ELEMENTS_INC_PATH . 'upgrade/core/db-upgrades-manager.php';
		require_once ONE_ELEMENTS_INC_PATH . 'upgrade/upgrade-utils.php';
		require_once ONE_ELEMENTS_INC_PATH . 'upgrade/upgrades.php';
		require_once ONE_ELEMENTS_INC_PATH . 'upgrade/updater.php';
		require_once ONE_ELEMENTS_INC_PATH . 'upgrade/manager.php';
		// @TODO; remove in the next version

		// check if new or old user of the plugin.
		if ( null === get_option('one_elements_version', null) ) {

			// check if at least one widget is used in any post.
			$post_ids = $wpdb->get_col(
				'SELECT `post_id`
					FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = "_elementor_data"
					AND `meta_value` LIKE \'%"widgetType":"one-elements-icon-box"%\'
					OR `meta_value` LIKE \'%"widgetType":"one-elements-counter"%\'
					LIMIT 1;'
			);
			
			if ( !empty($post_ids) ) {

				// User is an old user. so add the temp solution to trigger db update.
				// we did not add 'one_elements_version' before, so we need this hack once only.
				update_option( 'one_elements_version', '1.3.1'); // Do not change the version name
			}

			return new Manager();

		}
		
		new Manager();

	}

}

new One_Elements_Upgrader();