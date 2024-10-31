<?php
namespace OneElements\Includes\Controls\Group;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * OneElements Custom Border Control control.
 *
 *
 *
 * @since 1.0.0
 */
if ( class_exists( 'Elementor\Plugin') && !class_exists( 'Group_Control_ONE_ELEMENTS_Border_Gradient') )
{
	/**
	 * Elementor Custom gradient control.
	 *
	 * A base control for creating border control. Displays input fields to define
	 * border type, border width and border color.
	 *
	 * @since 1.0.0
	 */
	class Group_Control_ONE_ELEMENTS_Border_Gradient extends Group_Control_Base {

		/**
		 * Fields.
		 *
		 * Holds all the background control fields.
		 *
		 * @since 1.2.2
		 * @access protected
		 * @static
		 *
		 * @var array Border Color control fields.
		 */
		protected static $fields;

		/**
		 * Border Color Types.
		 *
		 * Holds all the available background types.
		 *
		 * @since 1.2.2
		 * @access private
		 * @static
		 *
		 * @var array
		 */
		private static $border_color_types;

		/**
		 * Get background control type.
		 *
		 * Retrieve the control type, in this case `background`.
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 *
		 * @return string Control type.
		 */
		public static function get_type() {
			return 'one_elements_border_gradient';
		}

		/**
		 * Get background control types.
		 *
		 * Retrieve available background types.
		 *
		 * @since 1.2.2
		 * @access public
		 * @static
		 *
		 * @return array Available background types.
		 */
		public static function get_border_color_types() {
			if ( null === self::$border_color_types ) {
				self::$border_color_types = self::get_default_border_color_types();
			}

			return self::$border_color_types;
		}

		/**
		 * Get Default background types.
		 *
		 * Retrieve background control initial types.
		 *
		 * @since 2.0.0
		 * @access private
		 * @static
		 *
		 * @return array Default background types.
		 */
		private static function get_default_border_color_types() {
			return [
				'classic' => [
					'title' => _x( 'Classic', 'Border Color Control', 'one-elements' ),
					'icon' => 'fa fa-paint-brush',
				],
				'gradient' => [
					'title' => _x( 'Gradient', 'Border Color Control', 'one-elements' ),
					'icon' => 'fa fa-barcode',
				],
				'extra' => [
					'title' => _x( 'Minimal', 'Border Color Control', 'one-elements' ),
					'icon' => 'fa fa-plus',
				],
			];
		}

		/**
		 * Init fields.
		 *
		 * Initialize background control fields.
		 *
		 * @since 1.2.2
		 * @access public
		 *
		 * @return array Control fields.
		 */
		public function init_fields() {
			$fields = [];

			$fields['background'] = [
				'label' => _x( 'Type', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'render_type' => 'ui',
			];// we will set its options field dynamically in prepare fields

			$fields['color'] = [
				'label' => _x( 'Color', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'title' => _x( 'Border Color', 'Border Color Control', 'one-elements' ),
				'selectors' => [
					'{{SELECTOR}}' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'background' => [ 'classic', 'gradient' ],
				],
				'default' => '#0c4cce'
			];

			$fields['border_style'] = [
				'label' => _x( 'Border Style', 'Border Control', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => _x( 'Solid', 'Border Control', 'one-elements' ),
					'double' => _x( 'Double', 'Border Control', 'one-elements' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'one-elements' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'one-elements' ),
					'groove' => _x( 'Groove', 'Border Control', 'one-elements' ),
				],
				'selectors' => [
					'{{SELECTOR}}' => 'border-style: {{VALUE}};',
				],
				'default' => 'solid',
				'condition' => [
					'background' => 'classic',
				],
				'of_type' => 'extra',
			];

			$fields['color_stop'] = [
				'label' => _x( 'Location', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 0,
				],
				'render_type' => 'ui',
				'condition' => [
					'background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			];

			$fields['color_b'] = [
				'label' => _x( 'Second Color', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'render_type' => 'ui',
				'condition' => [
					'background' => [ 'gradient' ],
				],
				'of_type' => 'gradient'
			];

			$fields['color_b_stop'] = [
				'label' => _x( 'Location', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'render_type' => 'ui',
				'condition' => [
					'background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			];

			$fields['gradient_type'] = [
				'label' => _x( 'Type', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'linear' => _x( 'Linear', 'Border Color Control', 'one-elements' ),
					'radial' => _x( 'Radial', 'Border Color Control', 'one-elements' ),
				],
				'default' => 'linear',
				'render_type' => 'ui',
				'condition' => [
					'background' => [ 'gradient' ],
				],
				'of_type' => 'gradient',
			];

			$fields['gradient_angle'] = [
				'label' => _x( 'Angle', 'Border Color Control', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'unit' => 'deg',
					'size' => 135,
				],
				'range' => [
					'deg' => [
						'step' => 5,
					],
				],
				'selectors' => [
					'{{SELECTOR}}' => 'background-color: transparent; background-image: -webkit-gradient(linear, left top, left bottom, from({{SIZE}}{{UNIT}}), color-stop({{color_stop.SIZE}}{{color_stop.UNIT}}, {{color.VALUE}}), color-stop({{color_b_stop.SIZE}}{{color_b_stop.UNIT}}, {{color_b.VALUE}})); background-image: -webkit-linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); background-image: -o-linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
				],
				'condition' => [
					'background' => [ 'gradient' ],
					'gradient_type' => 'linear',
				],
				'of_type' => 'gradient',
			];

			$fields['gradient_position'] = [
				'label' => _x( 'Position', 'Background Control', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'center center' => _x( 'Center Center', 'Background Control', 'one-elements' ),
					'center left' => _x( 'Center Left', 'Background Control', 'one-elements' ),
					'center right' => _x( 'Center Right', 'Background Control', 'one-elements' ),
					'top center' => _x( 'Top Center', 'Background Control', 'one-elements' ),
					'top left' => _x( 'Top Left', 'Background Control', 'one-elements' ),
					'top right' => _x( 'Top Right', 'Background Control', 'one-elements' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'one-elements' ),
					'bottom left' => _x( 'Bottom Left', 'Background Control', 'one-elements' ),
					'bottom right' => _x( 'Bottom Right', 'Background Control', 'one-elements' ),
				],
				'default' => 'center center',
				'selectors' => [
					'{{SELECTOR}}' => 'background-color: transparent; background-image: -webkit-radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); background-image: -o-radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
				],
				'condition' => [
					'background' => [ 'gradient' ],
					'gradient_type' => 'radial',
				],
				'of_type' => 'gradient',
			];

			$fields['width_classic'] = [
				'label' => _x( 'Width', 'Border Control', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{SELECTOR}}' => 'border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition' => [
					'background' => 'classic'
				],
				'responsive' => true,
				'of_type' => 'extra',

			];

			$fields['width_gradient'] = [
				'label' => _x( 'Width', 'Border Control', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{SELECTOR}} .one-elements-element__state-inner' => 'width: calc(100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}}); height: calc(100% - {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}}); top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'background' => 'gradient'
				],
				'responsive' => true,
				'of_type' => 'extra',

			];

			return $fields;
		}

		/**
		 * Get child default args.
		 *
		 * Retrieve the default arguments for all the child controls for a specific group
		 * control.
		 *
		 * @since 1.2.2
		 * @access protected
		 *
		 * @return array Default arguments for all the child controls.
		 */
		protected function get_child_default_args() {
			return [
				'types' => [ 'classic', 'gradient', 'extra' ],
			];
		}

		/**
		 * Filter fields.
		 *
		 * Filter which controls to display, using `include`, `exclude`, `condition`
		 * and `of_type` arguments.
		 *
		 * @since 1.2.2
		 * @access protected
		 *
		 * @return array Control fields.
		 */
		protected function filter_fields() {
			$fields = parent::filter_fields();

			$args = $this->get_args();

			foreach ( $fields as $key => $field ) {
				if ( isset( $field['of_type'] )
				&& ! in_array( $field['of_type'], $args['types'] )
				) {
					unset( $fields[$key] );
				}
			}

			return $fields;
		}

		/**
		 * Prepare fields.
		 *
		 * Process background control fields before adding them to `add_control()`.
		 *
		 * @since 1.2.2
		 * @access protected
		 *
		 * @param array $fields Border Color control fields.
		 *
		 * @return array Processed fields.
		 */
		protected function prepare_fields( $fields ) {
			$args = $this->get_args();

			$border_color_types = self::get_border_color_types();
			// we do not need to show extra fields as a tab, 'extra' as type only to filter out controls to display

			if (isset( $border_color_types['extra'])){
				unset( $border_color_types['extra']);
			}

			$choose_types = [];

			foreach ( $args['types'] as $type ) {
				if ( isset( $border_color_types[ $type ] ) ) {
					$choose_types[ $type ] = $border_color_types[ $type ];
				}
			}

			$fields['background']['options'] = $choose_types;

			if ( !empty( $args['_padding'] ) ) {
				$fields['width_classic']['selectors'][$args['_padding']] = 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;';
				$fields['width_gradient']['selectors'][$args['_padding']] = 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;';
			}

			return parent::prepare_fields( $fields );
		}

		/**
		 * Get default options.
		 *
		 * Retrieve the default options of the background control. Used to return the
		 * default options while initializing the background control.
		 *
		 * @since 1.9.0
		 * @access protected
		 *
		 * @return array Default background control options.
		 */
		protected function get_default_options() {
			return [
				'popover' => [],
			];
		}
	}
}

