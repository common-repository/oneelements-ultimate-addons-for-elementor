<?php
namespace OneElements\Includes\Extending;

use Elementor\Controls_Manager;

/**
 * Fired during plugin deactivation
 *
 * @link       https://themexclub.com
 * @since      1.0.0
 *
 * @package    One_Elements
 * @subpackage One_Elements/includes
 */

if ( ! defined( 'WPINC' ) ) die;

if ( ! class_exists( 'OneElementsExtension' ) ) {

	/**
	 * Define OneElementsExtension class
	 */
	class OneElementsExtension {


		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {

			add_action( 'elementor/element/section/section_effects/before_section_end', array( $this, 'after_section_start' ), 10, 2 );
			add_action( 'elementor/element/column/section_effects/before_section_end', array( $this, 'after_section_start' ), 10, 2 );
			add_action( 'elementor/element/common/section_effects/before_section_end', array( $this, 'after_section_start' ), 10, 2 );

		}

		/**
		 * After section_effects callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_section_start( $obj, $args ) {
			$obj->update_control(
				'animation_duration',
				[
					'options' => [
						'slow' => __( 'Slow', 'elementor' ),
						'' => __( 'Normal', 'elementor' ),
						'fast' => __( 'Fast', 'elementor' ),
						'custom' => __( 'Custom', 'elementor' ),
					],
				]
			);

			$obj->start_injection( [
				'type' => 'control',
				'at'   => 'after',
				'of'   => 'animation_duration',
			] );


			$obj->add_control( 'custom_animation_duration',
				[
					'label' => __( 'Custom Animation Duration (ms)', 'one-elements' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
					'min' => 0,
					'step' => 100,
					'condition' => [
						'animation_duration' => 'custom'
					],
					'render_type' => 'none',
					'frontend_available' => true,
					'selectors' => [
						'{{WRAPPER}}' => 'animation-duration:{{VALUE}}ms;',
					],
				]
			);

			$obj->end_injection();
		}

	}
}

