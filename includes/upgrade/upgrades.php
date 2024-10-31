<?php
namespace OneElements\Includes\Upgrade;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * OneElements upgrades.
 *
 * OneElements upgrades handler class is responsible for updating different
 * OneElements versions.
 *
 * @since 1.0.0
 */
class Upgrades {

	/**
	 * Upgrade OneElements 1.3.2
	 * Change icon box and Counter widget's layout and order options
	 *
	 * @param Updater $updater
	 *
	 * @return bool|void
	 * @since  2.0.0
	 * @static
	 * @access public
	 */
	public static function _v_1_3_2($updater) {

		global $wpdb;

		// get ids of all post that uses icon box widget and replace its data
		$post_ids = $wpdb->get_col(
			'SELECT `post_id`
					FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = "_elementor_data"
					AND `meta_value` LIKE \'%"widgetType":"one-elements-icon-box"%\'
					OR `meta_value` LIKE \'%"widgetType":"one-elements-counter"%\';'
		);


		if ( empty( $post_ids ) ) {
			return;
		}

		foreach ( $post_ids as $post_id ) {
			// Clear WP cache for next step.
			wp_cache_flush();
			$document = Plugin::$instance->documents->get( $post_id );
			if ( ! $document ) continue;

			$data = $document->get_elements_data();

			if ( empty( $data ) ) continue;


			$data = Plugin::$instance->db->iterate_data( $data, function( $element ) {
				if ( empty( $element['widgetType'] ) ) {
					return $element;
				}

				// upgrade the data of icon box widget
				if ( 'one-elements-icon-box' === $element['widgetType']) {
					// do the data upgrade
					$s = $element['settings'];
					$layout = isset( $s['icon_box_layout']) ? $s['icon_box_layout'] : 1;
					// update old layouts
					switch ($layout){
						case '1':
							$element['settings']['icon_box_layout'] = '1';
							$element['settings']['align'] = 'left';
							break;
						case '2':
							$element['settings']['icon_box_layout'] = '1';
							$element['settings']['align'] = 'center';
							break;
						case '3':
							$element['settings']['icon_box_layout'] = '1';
							$element['settings']['align'] = 'right';
							break;

						case '4':
							$element['settings']['icon_box_layout'] = '2';
							$element['settings']['align'] = 'left';
							break;

						case '5':
							$element['settings']['icon_box_layout'] = '2';
							$element['settings']['align'] = 'center';
							break;
						case '6':
							$element['settings']['icon_box_layout'] = '2';
							$element['settings']['align'] = 'right';
							break;
						case '7':
							$element['settings']['icon_box_layout'] = '3';
							break;
						case '8':
							$element['settings']['icon_box_layout'] = '4';
							break;
						case '9':
							$element['settings']['icon_box_layout'] = '5';
							break;
						case '10':
							$element['settings']['icon_box_layout'] = '6';
							break;
						case '11':
							$element['settings']['icon_box_layout'] = '7';
							break;
						case '12':
							$element['settings']['icon_box_layout'] = '8';
							break;


					}
					// update old orders
					switch ($layout) {
						case '3':
						case '4':
							// this layout does not support icon n title ordering, so unset old order
							if (isset( $element['settings']['icon_order'])){
								unset( $element['settings']['icon_order']);
							}

							if (isset( $element['settings']['title_order'])){
								unset( $element['settings']['title_order']);
							}

							// update desc n button order key
							if (isset( $element['settings']['description_order'])){
								$element['settings']['description_order_l34'] = $element['settings']['description_order'];
								unset( $element['settings']['description_order']);
							}

							if (isset( $element['settings']['button_order'])){
								$element['settings']['button_order_l34'] = $element['settings']['button_order'];
								unset( $element['settings']['button_order']);
							}
							break;
						case '5':
						case '6':
						case '7':
						case '8':
							// this layout does not support icon  so unset old order
							if (isset( $element['settings']['icon_order'])){
								unset( $element['settings']['icon_order']);
							}

							// update title, desc n button order key
							$ib_order_items = ['title', 'description', 'button'];
							foreach ( $ib_order_items as $ib_order_item ) {
								if (isset( $element['settings'][$ib_order_item.'_order'])){
									$element['settings'][$ib_order_item.'_order_l58'] = $element['settings'][$ib_order_item.'_order'];
									unset( $element['settings'][$ib_order_item.'_order']);
								}
							}

							break;

					}
				}


				// upgrade counter data
				if ( 'one-elements-counter' === $element['widgetType']) {
					$s = $element['settings'];
					$layout = isset( $s['counter_layout']) ? $s['counter_layout'] : 1;
					// update old layouts

					switch ($layout) {
						case '1':
							$element['settings']['counter_layout'] = '1';
							$element['settings']['align'] = 'left';
							break;
						case '2':
							$element['settings']['counter_layout'] = '1';
							$element['settings']['align'] = 'center';
							break;
						case '3':
							$element['settings']['counter_layout'] = '1';
							$element['settings']['align'] = 'right';
							break;
						case '4':
							$element['settings']['counter_layout'] = '2';
							$element['settings']['align'] = 'left';
							break;

						case '5':
							$element['settings']['counter_layout'] = '2';
							$element['settings']['align'] = 'center';
							break;
						case '6':
							$element['settings']['counter_layout'] = '2';
							$element['settings']['align'] = 'right';
							break;
						case '7':
							$element['settings']['counter_layout'] = '3';
							break;
						case '8':
							$element['settings']['counter_layout'] = '4';
							break;
						case '9':
							$element['settings']['counter_layout'] = '5';
							break;
						case '10':
							$element['settings']['counter_layout'] = '6';
					}
					// update old orders
					switch ($layout) {
						case '7':
						case '8':
						case '9':
						case '10':
							// this layout does not support icon ordering, so unset old order
							if (isset( $element['settings']['icon_order'])){
								unset( $element['settings']['icon_order']);
							}

							// update number, divider and title order key
							$orders_items = ['number', 'title', 'divider'];
						foreach ( $orders_items as $orders_item ) {
							if ( isset( $element['settings'][ $orders_item . '_order' ] ) ) {
								$element['settings'][ $orders_item . '_order_l36' ] = $element['settings'][ $orders_item . '_order' ];
								unset( $element['settings'][ $orders_item . '_order' ] );
							}
						}

							break;

					}

				}

				return $element;
			} );


			// We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
			$json_value = wp_slash( wp_json_encode( $data ) );

			update_metadata( 'post', $post_id, '_elementor_data', $json_value );

		} // End foreach().
		return $updater->should_run_again( $post_ids );
	}

}
