<?php
namespace OneElements\Includes\Upgrade;

use OneElements\Includes\Upgrade\Core\DB_Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends DB_Upgrades_Manager {


	public function get_name() {
		return 'upgrade';
	}

	public function get_action() {
		return 'updater';
	}

	public function get_plugin_name() {
		return 'one_elements';
	}

	public function get_plugin_label() {
		return __( 'OneElements', 'elementor' );
	}

	public function get_updater_label() {
		return sprintf( '<strong>%s </strong> &#8211;', __( 'OneElements Data Updater', 'elementor' ) );
	}


	public function get_new_version() {
		return ONE_ELEMENTS_VERSION;
	}

	public function get_version_option_name() {
		return 'one_elements_version';
	}

	public function get_upgrades_class() {
		return 'OneElements\Includes\Upgrade\Upgrades';
	}

}