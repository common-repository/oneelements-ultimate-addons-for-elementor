<?php

namespace OneElements\Includes\Upgrade\Core;

use Elementor\Core\Base\Background_Task_Manager;
use Elementor\Plugin;
use ReflectionClass;
use ReflectionException;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class DB_Upgrades_Manager extends Background_Task_Manager {
	protected $current_version = null;
	protected $query_limit = 100;

	abstract public function get_new_version();
	abstract public function get_version_option_name();
	abstract public function get_upgrades_class();
	abstract public function get_updater_label();

	public function get_task_runner_class() {
		return 'OneElements\Includes\Upgrade\Updater';
	}

	public function get_query_limit() {
		return $this->query_limit;
	}

	public function set_query_limit( $limit ) {
		$this->query_limit = $limit;
	}

	public function get_current_version() {
		if ( null === $this->current_version ) {
			$this->current_version = get_option( $this->get_version_option_name() );
		}

		return $this->current_version;
	}

	public function should_upgrade() {
		$current_version = $this->get_current_version();


		// It's a new install.
		if ( ! $current_version ) {
			$this->update_db_version();
			return false;
		}

		return version_compare( $this->get_new_version(), $current_version, '>' );
	}

	public function on_runner_start() {
		parent::on_runner_start();

		define( 'IS_ONE_ELEMENTS_UPGRADE', true );
	}

	public function on_runner_complete( $did_tasks = false ) {
		$logger = Plugin::$instance->logger->get_logger();

		$logger->info( 'OneElements data updater process has been completed.', [
			'meta' => [
				'plugin' => $this->get_plugin_label(),
				'from' => $this->current_version,
				'to' => $this->get_new_version(),
			],
		] );

		Plugin::$instance->files_manager->clear_cache();

		$this->update_db_version();

		if ( $did_tasks ) {
			$this->add_flag( 'completed' );
		}
	}

	public function admin_notice_start_upgrade() {
		$start_link = $this->get_start_action_url();
		$message = '<p>' . sprintf( __( '%s Your site database needs to be updated to the latest version.', 'one-elements' ), $this->get_updater_label() ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $start_link, __( 'Update Now', 'one-elements' ) ) . '</p>';

		echo '<div class="notice notice-error">' . $message . '</div>';
	}

	public function admin_notice_upgrade_is_running() {
		$upgrade_continue_link = $this->get_continue_action_url();
		$message = '<p>' . sprintf( __( '%s Database update process is running in the background.', 'one-elements' ), $this->get_updater_label() ) . '</p>';
		$message .= '<p>' . __( 'Taking a while?', 'one-elements' ) . '<a href="' . $upgrade_continue_link . '" class="button-primary">' . __( 'Click here to run it now', 'one-elements' ) . '</a></p>';

		echo '<div class="notice notice-warning">' . $message . '</div>';
	}

	public function admin_notice_upgrade_is_completed() {
		$this->delete_flag( 'completed' );

		$message = '<p>' . sprintf( __( '%s The database update process is now complete. Thank you for updating to the latest version!', 'one-elements' ), $this->get_updater_label() ) . '</p>';

		echo '<div class="notice notice-success">' . $message . '</div>';
	}

	/**
	 * @access protected
	 */
	protected function start_run() {
		$updater = $this->get_task_runner();

		if ( $updater->is_running() ) {
			return;
		}

		$upgrade_callbacks = $this->get_upgrade_callbacks();

		if ( empty( $upgrade_callbacks ) ) {
			$this->on_runner_complete();
			return;
		}

		foreach ( $upgrade_callbacks as $callback ) {
			$updater->push_to_queue( [
				'callback' => $callback,
			] );
		}

		$updater->save()->dispatch();

		Plugin::$instance->logger->get_logger()->info( 'OneElements data updater process has been queued.', [
			'meta' => [
				'plugin' => $this->get_plugin_label(),
				'from' => $this->current_version,
				'to' => $this->get_new_version(),
			],
		] );
	}

	protected function update_db_version() {
		update_option( $this->get_version_option_name(), $this->get_new_version() );
	}

	public function get_upgrade_callbacks() {
		$prefix = '_v_';
		$upgrades_class = $this->get_upgrades_class();
		$callbacks = [];

		try {
			$upgrades_reflection = new ReflectionClass( $upgrades_class );
			foreach ( $upgrades_reflection->getMethods() as $method ) {
				$method_name = $method->getName();

				if ( false === strpos( $method_name, $prefix ) ) {
					continue;
				}

				if ( ! preg_match_all( "/$prefix(\d+_\d+_\d+)/", $method_name, $matches ) ) {
					continue;
				}

				$method_version = str_replace( '_', '.', $matches[1][0] );

				if ( ! version_compare( $method_version, $this->current_version, '>' ) ) {
					continue;
				}

				$callbacks[] = [ $upgrades_class, $method_name ];
			}
		}
		catch ( ReflectionException $e ) {
		// handle the exception
		}

		return $callbacks;
	}

	public function __construct() {
		// If upgrade is completed - show the notice only for admins.
		// Note: in this case `should_upgrade` returns false, because it's already upgraded.
		if ( is_admin() && current_user_can( 'update_plugins' ) && $this->get_flag( 'completed' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_upgrade_is_completed' ] );
		}

		if ( ! $this->should_upgrade() ) {
			return;
		}

		$updater = $this->get_task_runner();

		$this->start_run();

		if ( $updater->is_running() && current_user_can( 'update_plugins' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_upgrade_is_running' ] );
		}

		parent::__construct();
	}
}
