<?php

namespace OneElements\Includes\Widgets\DualButton;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Traits\One_Elements_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor button widget.
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 * @since 1.0.0
 */
class Widget_OneElements_Dual_ButtonNew extends Widget_Base {

	use One_Elements_Button_Trait;
	/**
	 * Prefix for trait's control.
	 * @return string Prefix for trait's control.
	 * @since 1.0.0
	 */
	protected $prefix = ''; // primary Button Prefix
	protected $sec_prefix = 'sec_';// secondary button prefix

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'one-elements-dual-button-new';
	}

	/**
	 * Get widget categories.
	 * Retrieve the list of categories the button widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 * @return array Widget categories.
	 * @since  2.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ 'one_elements' ];
	}

	/**
	 * Get widget icon.
	 * Retrieve social icons widget icon.
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'one-elements-widget-eicon eicon-button';
	}
	public function get_keywords() {
		return ['button', 'buttons', 'dual button', 'dual', 'two button', '2buttons'];
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return __( 'Dual Button New', 'one-elements' );
	}

	protected function get_button_control_default_args( $prefix='' ) {
		$label = __('Primary', 'one-elements');
		$btn_wrapper = 'one-elements-button__primary';
		if (  strpos( $prefix, 'sec') !== false ) {
			$label = __('Secondary', 'one-elements');
			$btn_wrapper = 'one-elements-button__secondary';
		}
		// if priority fails to override common section's style, then make the class below more specific or give it more priority.
		$priority_btn_class = ".one-elements-dual_button__wrapper .{$btn_wrapper}";
		return [
			'prefix' => $prefix,
			'excludes' => ['button_align', 'button_css_id', 'icon_css_id', 'icon_align', 'button_border_section', 'button_underline_section', 'icon_border_section'],
			'includes' => [],
			'selectors'=>[
				'icon_box_size' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-icon" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} {$priority_btn_class}.one-elements-button__type-circle" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // this selector added by K.
				],

				'icon_size' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-icon__content_icon" => 'font-size: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} {$priority_btn_class} .one-elements-icon" => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} {$priority_btn_class} .one-elements-icon__content_icon svg" => 'width: {{SIZE}}{{UNIT}};'
				],

				'icon_indent' => [
					"{{WRAPPER}} {$priority_btn_class}.one-elements-button__icon-right  .one-elements-icon" => 'margin-left: {{SIZE}}{{UNIT}};',
					"{{WRAPPER}} {$priority_btn_class}.one-elements-button__icon-left  .one-elements-icon" => 'margin-right: {{SIZE}}{{UNIT}};',
				],

				/*-------Button Icon Color & Background override-------*/

				'icon_color' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-icon__content_icon > *",

				'icon_hover_color' => "{{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon .one-elements-icon__content_icon > *",

				'icon_background' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner",

				'icon_background_hover' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner",

				/*-------Button Icon Border & Shadow override-------*/
				'icon_border' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__regular-state",

				'icon_border_hover' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__hover-state",

				'icon_border_radius' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-icon, {{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'icon_border_radius_hover' =>  [
					"{{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon, {{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],

				'icon_shadow' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon",
				'icon_hover_shadow' => "{{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon",

				// lets prefix button widget related css classes so that we can use multiple unique button's style like read/load more btn
				/*-------Button background override-------*/
				'button_background' => "{{WRAPPER}} {$priority_btn_class} > .one-elements-element__regular-state .one-elements-element__state-inner",

				'button_background_hover' => "{{WRAPPER}} {$priority_btn_class} > .one-elements-element__hover-state .one-elements-element__state-inner",

				/*-------Button Border & Shadow override-------*/
				'button_border' => "{{WRAPPER}} {$priority_btn_class} > .one-elements-element__regular-state",
				'button_border_radius' => [
					"{{WRAPPER}} {$priority_btn_class}, {{WRAPPER}} {$priority_btn_class} > .one-elements-element__regular-state, {{WRAPPER}} {$priority_btn_class} > .one-elements-element__hover-state, {{WRAPPER}} {$priority_btn_class} > .one-elements-element__border-gradient .one-elements-element__state-inner" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'button_box_shadow' => "{{WRAPPER}} {$priority_btn_class}",


				'button_border_hover' => "{{WRAPPER}} {$priority_btn_class}:hover > .one-elements-element__regular-state",

				'button_border_radius_hover' => [
					"{{WRAPPER}} {$priority_btn_class}:hover, {{WRAPPER}} {$priority_btn_class}:hover > .one-elements-element__regular-state, {{WRAPPER}} {$priority_btn_class}:hover > .one-elements-element__hover-state, {{WRAPPER}} {$priority_btn_class}:hover > .one-elements-element__border-gradient .one-elements-element__state-inner" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'hover_button_box_shadow' => "{{WRAPPER}} {$priority_btn_class}:hover",

				/*-------Button Underline override-------*/
				'underline_height' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-button__underline" => 'height: {{SIZE}}{{UNIT}};',
				],
				'underline_width' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-button__underline" => 'width: {{SIZE}}{{UNIT}};',
				],
				'underline_radius' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-button__underline" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'underline_spacing' => [
					"{{WRAPPER}} {$priority_btn_class} .one-elements-element__content > *" => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'underline_color' => "{{WRAPPER}} {$priority_btn_class} .one-elements-button__underline",
				'hover_underline_width' => [
					"{{WRAPPER}} {$priority_btn_class}:hover .one-elements-button__underline" => 'width: {{SIZE}}{{UNIT}};',
				],
				'hover_underline_color' => "{{WRAPPER}} {$priority_btn_class}:hover .one-elements-button__underline",
			],
			'labels' => [
				'section_content_button' => sprintf( __('%s Button', 'one-elements'), $label),
				'button_background_section' => sprintf( __('%s Background', 'one-elements'), $label),
				'button_border_section' => sprintf( __('%s Border & Shadow', 'one-elements'), $label),
				'button_underline_section' => sprintf( __('%s Underline', 'one-elements'), $label),
				'section_icon' => sprintf( __('%s Button Icon', 'one-elements'), $label),
				'section_icon_style' => sprintf( __('%s Button Icon', 'one-elements'), $label),
				'icon_background_section' => sprintf( __('%s Icon Background', 'one-elements'), $label),
				'icon_border_section' =>sprintf( __('%s Icon Border & Shadow', 'one-elements'), $label),
			],
			'defaults' => [
				'button_text' => $label,
			],
			'conditions' => [
				'section_content_button' => [ $prefix.'show_button' => 'yes', ],
				'section_icon' => [  $prefix .'enable_button_icon' => 'yes', $prefix.'show_button' => 'yes', ],
				'button_background_section' => [ $prefix.'show_button' => 'yes',  $prefix.'button_type!' => ['flat']],
				'button_border_section' => [ $prefix.'show_button' => 'yes',  $prefix.'button_type!' => ['flat']],
				'section_icon_style' => [ $prefix .'enable_button_icon' => 'yes', $prefix.'show_button' => 'yes',  $prefix.'icon[value]!' => '',],
				'icon_background_section' => [
					$prefix.'enable_button_icon' => 'yes',
					$prefix.'show_button' => 'yes',
					$prefix.'icon[value]!' => '',
					$prefix.'button_type!' => 'flat',
				],
				'icon_border_section' => [
                    $prefix .'enable_button_icon' => 'yes',
                    $prefix.'button_type!' => ['flat'],
                    $prefix.'icon[value]!' => '',
				]
			]
		];

	}


	/**
	 * Register button widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// Secondary Button Content
		$this->init_content_button_settings($this->get_button_control_default_args($this->prefix));
		$this->start_injection( [
			'type' => 'section',
			'at'   => 'start',
			'of'   => $this->prefix.'section_content_button',
		] );
		// we need this hidden field for condition
		$this->add_control(  $this->prefix.'show_button', [
			'label'   => __( 'Show Button', 'one-elements' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'yes',
		] );
		// we need this hidden field for condition
		$this->add_control( $this->sec_prefix.'show_button', [
			'label'   => __( 'Show Secondary Button', 'one-elements' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'yes',
		] );
		$this->end_injection();

		$this->init_content_button_icon_settings($this->get_button_control_default_args($this->prefix));
		// Button Connector
		$this->init_content_connector_controls();
		// Secondary Button Content
		$this->init_content_button_settings($this->get_button_control_default_args($this->sec_prefix));
		$this->init_content_button_icon_settings($this->get_button_control_default_args($this->sec_prefix));

		$this->init_content_custom_actions_settings();


		/*-----STYLE TAB------*/
		// Style > Dual Button Common
		$this->init_style_dual_button_common_settings();
		// Style > Primary Button
		$this->init_style_button_settings($this->prefix);
		$this->init_style_button_icon_settings($this->prefix);

		// Style > Connector
		$this->init_style_connector_settings();

		// Style > Secondary Button
		$this->init_style_button_settings($this->sec_prefix);
		$this->init_style_button_icon_settings($this->sec_prefix);
	}



	private function init_content_custom_actions_settings() {

		$this->start_controls_section( 'button_custom_actions_section', [
			'label' => __( 'Custom Actions', 'one-elements' ),
		] );

		// Dual button events tabs
		$this->start_controls_tabs( 'dual_button_actions_tabs');
		// Primary Button Action tab
		$this->start_controls_tab( 'primary_button_action_tab', [ 'label' => __( 'Primary', 'one-elements' )] );
		$this->add_control( $this->prefix.'btn_ca_event_type', [
			'label'   => __( 'Event', 'one-elements' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'click' => __( 'Click', 'one-elements' ),
				'hover' => __( 'Hover', 'one-elements' ),
			],
			'default' => 'click',
		] );
		$this->add_control( $this->prefix.'btn_ca_action_type', [
			'label'   => __( 'Action Type', 'one-elements' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'slider' => __( 'Slider', 'one-elements' ),
				'custom' => __( 'Custom', 'one-elements' ),
			],
		] );


		$this->add_control( $this->prefix.'btn_ca_slider_id', [
			'label'     => __( 'Slider ID', 'one-elements' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '',
			'condition' => [
				$this->prefix.'btn_ca_action_type' => [ 'slider' ],
			],
		] );

		$this->add_control( $this->prefix.'btn_ca_slider_action', [
			'label'     => __( 'Action', 'one-elements' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'next' => __( 'Next', 'one-elements' ),
				'prev' => __( 'Prev', 'one-elements' ),
			],
			'default'   => 'next',
			'condition' => [
				$this->prefix.'btn_ca_slider_id!'  => '',
				$this->prefix.'btn_ca_action_type' => [ 'slider' ],
			],
		] );

		$this->add_control( $this->prefix.'btn_ca_custom_function', [
			'label'       => __( 'Custom Function', 'one-elements' ),
			'description' => __( 'Enter a Custom Function that will handle the event', 'one-elements' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => __( 'Enter a custom function name', 'one-elements' ),
			'condition'   => [
				$this->prefix.'btn_ca_action_type' => [ 'custom' ],
			],
		] );


		$this->end_controls_tab(); // end primary button action tab

		// Secondary Button Action tab
		$this->start_controls_tab( 'secondary_button_action_tab', [ 'label' => __( 'Secondary', 'one-elements' )] );
		$this->add_control( $this->sec_prefix.'btn_ca_event_type', [
			'label'   => __( 'Event', 'one-elements' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'Select an event', 'one-elements' ),
				'click' => __( 'Click', 'one-elements' ),
				'hover' => __( 'Hover', 'one-elements' ),
			],
			'default' => 'click',
		] );
		$this->add_control( $this->sec_prefix.'btn_ca_action_type', [
			'label'   => __( 'Action Type', 'one-elements' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'Select', 'one-elements' ),
				'slider' => __( 'Slider', 'one-elements' ),
				'custom' => __( 'Custom', 'one-elements' ),
			],
		] );


		$this->add_control( $this->sec_prefix.'btn_ca_slider_id', [
			'label'     => __( 'Slider ID', 'one-elements' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => '',
			'condition' => [
				$this->sec_prefix.'btn_ca_action_type' => [ 'slider' ],
			],
		] );

		$this->add_control( $this->sec_prefix.'btn_ca_slider_action', [
			'label'     => __( 'Action', 'one-elements' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'next' => __( 'Next', 'one-elements' ),
				'prev' => __( 'Prev', 'one-elements' ),
			],
			'default'   => 'next',
			'condition' => [
				$this->sec_prefix.'btn_ca_slider_id!'  => '',
				$this->sec_prefix.'btn_ca_action_type' => [ 'slider' ],
			],
		] );

		$this->add_control( $this->sec_prefix.'btn_ca_custom_function', [
			'label'       => __( 'Custom Function', 'one-elements' ),
			'description' => __( 'Enter a Custom Function that will handle the event', 'one-elements' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => __( 'Enter a custom function name', 'one-elements' ),
			'condition'   => [
				$this->sec_prefix . 'btn_ca_action_type' => [ 'custom' ],
			],
		] );

		$this->end_controls_tab(); // end primary button action tab

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function init_style_dual_button_common_settings() {

		$this->start_controls_section( 'section_style_common', [
			'label' => __( 'Dual Button Common Style', 'one-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'common_typography',
			'selector'  => '{{WRAPPER}} .one-elements-button__content_text',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
					[
						'name' => $this->sec_prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
				],
			],
		] );


		$this->add_control( 'common_typography_separator', [ 'type' => Controls_Manager::DIVIDER] );
		$this->start_controls_tabs( 'common_tabs_button_style' );

		$this->start_controls_tab( 'common_tab_button_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'common_btn_text_color',
			'label'     => __( 'Text Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => '{{WRAPPER}} .one-elements-button__content_text',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
					[
						'name' => $this->sec_prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
				],
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'common_btn_icon_color',
			'label'     => __( 'Icon Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => '{{WRAPPER}} .one-elements-icon__content_icon > *',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'enable_button_icon',
						'operator' => '==',
						'value' => 'yes',
						'relation' => 'and',
						'terms' => [
							[
								'name' => $this->prefix.'icon[value]',
								'operator' => '!=',
								'value' => '',
							],
							[
								'name' => $this->prefix.'icon[library]',
								'operator' => '!==',
								'value' => 'svg',
							],
						]
					],
					[
						'name' => $this->sec_prefix.'enable_button_icon',
						'operator' => '==',
						'value' => 'yes',
						'relation' => 'and',
						'terms' => [
							[
								'name' => $this->sec_prefix.'icon[value]',
								'operator' => '!=',
								'value' => '',
							],
						]
					],
				],
			],
		] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'common_button_background',
				'label' => __( 'Button Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-button > .one-elements-element__regular-state .one-elements-element__state-inner',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => $this->prefix.'button_type',
							'operator' => '!in',
							'value' => ['flat'],
						],
						[
							'name' => $this->sec_prefix.'button_type',
							'operator' => '!in',
							'value' => ['flat'],
						],
					],
				],
			]
		);

		$this->add_responsive_control( 'common_button_padding', [
			'label'      => __( 'Padding', 'one-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .one-elements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle', 'flat'],
					],
					[
						'name' => $this->sec_prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle', 'flat'],
					],
				],
			],
		] );

		$this->add_responsive_control(
			'common_button_border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-button, {{WRAPPER}} .one-elements-button > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'common_tab_button_hover', [
			'label' => __( 'Hover | Active', 'one-elements' ),
		] );


		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'hover_common_btn_text_color',
			'label'     => __( 'Text Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => '{{WRAPPER}} .one-elements-button:hover .one-elements-button__content_text',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
					[
						'name' => $this->sec_prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle'],
					],
				],
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'hover_common_btn_icon_color',
			'label'     => __( 'Icon Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => '{{WRAPPER}} .one-elements-button:hover .one-elements-icon__content_icon > *',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'enable_button_icon',
						'operator' => '==',
						'value' => 'yes',
						'relation' => 'and',
						'terms' => [
							[
								'name' => $this->prefix.'icon[value]',
								'operator' => '!=',
								'value' => '',
							],
							[
								'name' => $this->prefix.'icon[library]',
								'operator' => '!==',
								'value' => 'svg',
							],
						]
					],
					[
						'name' => $this->sec_prefix.'enable_button_icon',
						'operator' => '==',
						'value' => 'yes',
						'relation' => 'and',
						'terms' => [
							[
								'name' => $this->sec_prefix.'icon[value]',
								'operator' => '!=',
								'value' => '',
							],
						]
					],
				],
			],
		] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hover_common_button_background',
				'label' => __( 'Button Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-button:hover > .one-elements-element__hover-state .one-elements-element__state-inner',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => $this->prefix.'button_type',
							'operator' => '!in',
							'value' => ['flat'],
						],
						[
							'name' => $this->sec_prefix.'button_type',
							'operator' => '!in',
							'value' => ['flat'],
						],
					],
				],
			]
		);

		$this->add_responsive_control( 'hover_common_button_padding', [
			'label'      => __( 'Padding', 'one-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .one-elements-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => $this->prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle', 'flat'],
					],
					[
						'name' => $this->sec_prefix.'button_type',
						'operator' => '!in',
						'value' => ['circle', 'flat'],
					],
				],
			],
		] );

		$this->add_responsive_control(
			'hover_common_button_border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-button:hover, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'common_button_gap',
			[
				'label' => __( 'Space Between Buttons', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-button__primary' => 'margin-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .one-elements-button__secondary' => 'margin-left: calc({{SIZE}}{{UNIT}}/2);',
				],
				'separator' => 'before',
			]
		);

		$this->add_control( 'common_button_transition', [
			'label'     => __( 'Transition Speed', 'one-elements' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 3,
					'step' => 0.1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'transition-duration: {{SIZE}}s;',
			],
		] );

		$this->end_controls_section();

	}

	protected function init_style_button_settings($prefix='') {
		$label = __('Primary', 'one-elements');
		$btn_wrapper = 'one-elements-button__primary';
		if (  strpos( $prefix, 'sec') !== false ) {
			$label = __('Secondary', 'one-elements');
			$btn_wrapper = 'one-elements-button__secondary';
		}
		// if priority fails to override common section's style, then make the class below more specific or give it more priority.
        $priority_btn_class = ".one-elements-dual_button__wrapper .{$btn_wrapper}";

		$this->start_controls_section( $prefix.'section_style_primary_button', [
			'label' => sprintf( __( '%s Button', 'one-elements' ), $label),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => $prefix.'bn_typography',
			'selector'  => "{{WRAPPER}} {$priority_btn_class} .one-elements-button__content_text",
			'condition' => [
				$prefix.'button_type!' => [ 'circle' ],
			],
		] );

		$this->start_controls_tabs( $prefix.'tabs_button_style' );

		$this->start_controls_tab( $prefix.'tab_button_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => $prefix.'button_text_color',
			'label'     => __( 'Text Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => "{{WRAPPER}} {$priority_btn_class} .one-elements-button__content_text",
			'condition' => [
				$prefix.'button_type!' => [ 'circle' ],
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(),
			[
				'name' => $prefix.'button_background',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class}.one-elements-button > .one-elements-element__regular-state .one-elements-element__state-inner",
				'condition'  => [
					$prefix.'button_type!' => [ 'flat'],
				],
			]
		);
		$this->add_responsive_control( $prefix.'button_padding', [
			'label'      => __( 'Padding', 'one-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} {$priority_btn_class}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				$prefix.'button_type!' => [
					'flat',
					'circle',
				],
			],
		] );


		$this->end_controls_tab();

		$this->start_controls_tab( $prefix.'tab_button_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		] );
		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => $prefix.'button_text_color_hover',
			'label'     => __( 'Text Color', 'one-elements' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => "{{WRAPPER}} {$priority_btn_class}:hover .one-elements-button__content_text",
			'condition' => [
				$prefix.'button_type!' => [ 'circle' ],
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(),
			[
				'name' => $prefix.'button_background_hover',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class}.one-elements-button:hover > .one-elements-element__hover-state .one-elements-element__state-inner",
				'condition'  => [
					$prefix.'button_type!' => [ 'flat'],
				],
			]
		);
		$this->add_responsive_control( $prefix.'button_padding_hover', [
			'label'      => __( 'Padding', 'one-elements' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				"{{WRAPPER}} {$priority_btn_class}:hover" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				$prefix.'button_type!' => [
					'flat',
					'circle',
				],
			],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();


		/*---------- BUTTON BORDER STYLE ----------*/

		$this->add_control( $prefix.'button_border_separator', [
			'type' => Controls_Manager::DIVIDER,
			'condition' => [
				$prefix.'button_type!' => ['flat'],
			]
		] );

		$this->add_control(
			$prefix.'button_border_heading',
			[
				'label' => __('Border & Shadow', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$prefix.'button_type!' => ['flat'],
				]
			]
		);
		$this->init_style_button_border_settings($this->get_button_control_default_args($prefix));


		/*---------- BUTTON UNDERLINE STYLE ----------*/

		$this->add_control( $prefix.'flat_button_separator', [
		    'type' => Controls_Manager::DIVIDER,
		    'condition' => [
			    $prefix.'button_type' => ['flat'],
		    ]
		] );

		$this->add_control(
			$prefix.'flat_button_style_heading',
			[
				'label' => __('Button Underline Style', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$prefix.'button_type' => ['flat'],
				]
			]
		);
		$this->init_style_button_underline_settings($this->get_button_control_default_args($prefix));

		$this->end_controls_section();

	}

	protected function init_style_button_icon_settings($prefix='') {
		$label = __('Primary', 'one-elements');
		$btn_wrapper = 'one-elements-button__primary';
		if (  strpos( $prefix, 'sec') !== false ) {
			$label = __('Secondary', 'one-elements');
			$btn_wrapper = 'one-elements-button__secondary';
		}
		// if priority fails to override common section's style, then make the class below more specific or give it more priority.
		$priority_btn_class = ".one-elements-dual_button__wrapper .{$btn_wrapper}";
		$this->start_controls_section( $prefix.'section_style_button_icon', [
			'label' => sprintf( __( '%s Button Icon', 'one-elements' ), $label),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [
				$prefix.'enable_button_icon' => 'yes',
				$prefix.'icon[value]!' => '',
			],
		] );

		$this->start_controls_tabs( $prefix.'section_style_button_icon_tabs' );

		$this->start_controls_tab( $prefix.'style_button_icon_tab_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
				'name' => $prefix.'icon_color',
				'label'     => __( 'Icon Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-icon__content_icon > *",
			]
		);


		$this->add_group_control( Group_Control_Background::get_type(),
			[
				'name' => $prefix.'icon_background',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner",
				'condition' => [
					$prefix.'button_type!' => ['flat'],
				],
			]
		);

		$this->end_controls_tab();




		$this->start_controls_tab( $prefix.'style_button_icon_tab_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
				'name' => $prefix.'hover_icon_color',
				'label'     => __( 'Icon Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class}:hover .one-elements-icon .one-elements-icon__content_icon > *",
			]
		);

		$this->add_group_control( Group_Control_Background::get_type(),
			[
				'name' => $prefix.'icon_background_hover',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => "{{WRAPPER}} {$priority_btn_class} .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner",
				'condition' => [
					$prefix.'button_type!' => ['flat'],
				],
			]
		);


		$this->end_controls_tab();
		$this->end_controls_tabs();


		/*---------- BUTTON ICON BORDER STYLE ----------*/

		$this->add_control( $prefix.'button_icon_border_separator', [
			'type' => Controls_Manager::DIVIDER,
			'condition' => [
				$prefix.'button_type!' => ['flat'],
			]
		] );

		$this->add_control(
			$prefix.'button_icon_border_heading',
			[
				'label' => __('Border & Shadow', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$prefix.'button_type!' => ['flat'],
				]
			]
		);
		$this->init_style_button_icon_border_settings($this->get_button_control_default_args($prefix));

		$this->end_controls_section();

	}

	protected function init_style_connector_settings() {
		$this->start_controls_section( 'section_style_button_connector', [
			'label' => __( 'Connector', 'one-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_connector' => 'yes',
			],
		] );
		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'connector',
			'selector'  => '{{WRAPPER}} .one-elements-connector--text',
			'condition' => [
				'connector_type' => 'text',
				'button_connector_text!' => '',
			]
		] );
		$this->start_controls_tabs( 'section_style_button_connector_tabs',[
		    'separator' => 'before',
        ] );
		$this->start_controls_tab( 'style_button_connector_tab_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		] );
            $this->add_control( 'connector_color', [
                'type' => Controls_Manager::COLOR,
                'label'     => __( 'Color', 'one-elements' ),
                'types'     => [
                    'classic',
                    'gradient',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .one-elements-connector' => 'color: {{VALUE}}'
                ],
            ] );
            $this->add_control( 'connector_bg_color', [
                'type' => Controls_Manager::COLOR,
                'label'     => __( 'Background Color', 'one-elements' ),
                'selectors'  => [
                    //'{{WRAPPER}} .one-elements-connector--text, {{WRAPPER}} .one-elements-connector--icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .one-elements-connector' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_connector' => 'yes',
                ],
            ] );
            /*---------- BUTTON Connector BORDER STYLE ----------*/
            $this->add_control( 'style_connector_border_separator', [
                'type' => Controls_Manager::DIVIDER,
            ] );
            $this->add_control( 'style_connector_heading', [
                    'label' => __('Border & Shadow', 'one-elements'),
                    'type' => Controls_Manager::HEADING,
            ] );
            $this->add_group_control( Group_Control_Border::get_type(), [
                    'name' => 'connector_border',
                    'label' => __( 'Border', 'one-elements' ),
                    'condition' =>  [],
                    'selector' => '{{WRAPPER}} .one-elements-connector',
                ]
            );
            $this->add_responsive_control( 'connector_border_radius', [
                    'label' => __( 'Border Radius', 'one-elements' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .one-elements-connector' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control( Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(), [
                    'name' =>'connector_box_shadow',
                    'label' => __( 'Shadow', 'one-elements' ),
                    'selector' => '{{WRAPPER}} .one-elements-connector',
                ]
            );
		$this->end_controls_tab();
        $this->start_controls_tab( 'style_button_connector_tab_hover', [
            'label' => __( 'Hover', 'one-elements' ),
        ] );

            $this->add_control( 'hover_connector_color', [
                'type' => Controls_Manager::COLOR,
                'label'     => __( 'Color', 'one-elements' ),
                'types'     => [
                    'classic',
                    'gradient',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector' => 'color: {{VALUE}}',
                ],
            ] );
            $this->add_control( 'hover_connector_bg_color', [
                'type' => Controls_Manager::COLOR,
                'label'     => __( 'Background Color', 'one-elements' ),
                'selectors'  => [
                    //'{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector--text, {{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector--icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'show_connector' => 'yes',
                ],
            ] );

            /*---------- BUTTON Connector BORDER HOVER STYLE ----------*/
            $this->add_control( 'style_connector_border_separator_hover', [
                'type' => Controls_Manager::DIVIDER,
            ] );
            $this->add_control( 'style_connector_heading_hover', [
                    'label' => __('Border & Shadow', 'one-elements'),
                    'type' => Controls_Manager::HEADING,
                ]);
            $this->add_group_control( Group_Control_Border::get_type(), [
                    'name' => 'hover_connector_border',
                    'label' => __( 'Border', 'one-elements' ),
                    'condition' =>  [],
                    'selector' => '{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector',
                ]
            );
            $this->add_responsive_control( 'hover_connector_border_radius', [
                    'label' => __( 'Border Radius', 'one-elements' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );
            $this->add_group_control( Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(), [
                    'name' =>'hover_connector_box_shadow',
                    'label' => __( 'Shadow', 'one-elements' ),
                    'selector' => '{{WRAPPER}} .one-elements-dual_button__wrapper:hover .one-elements-connector',
                ]
            );
        $this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	protected function init_content_connector_controls() {
		$this->start_controls_section( 'section_content_connector', [
			'label' => __( 'Button Connector', 'one-elements' ),
		]);

		$this->add_control( 'show_connector',
			[
				'label' => __( 'Show Connector', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'default' => 'yes',
			]
		);

		$this->add_control( 'connector_type',
			[
				'label' => __( 'Connector Type', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'text'  => __( 'Text', 'one-elements' ),
					'icon' => __( 'Icon', 'one-elements' ),
				],
				'default' => 'text',
				'condition' => [
					'show_connector' => 'yes',
				],
			]
		);

		$this->add_control( 'button_connector_text',
			[
				'label' => __( 'Text', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Or', 'one-elements' ),
				'condition' => [
					'show_connector' => 'yes',
					'connector_type' => 'text',
				]
			]
		);

		$this->add_control( 'button_connector_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-heart',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_connector' => 'yes',
					'connector_type' => 'icon',
				]
			]
		);

		$this->add_responsive_control(
			'button_connector_icon_size',
			[
				'label' => __( 'Icon Size', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-connector--icon' => 'font-size: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-connector--icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'show_connector' => 'yes',
					'connector_type' => 'icon',
					'button_connector_icon!' => '',
				]
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings         = $this->get_settings_for_display();
		$this->add_button_render_atts( $this->prefix, $settings );// add unique render attributes for primary button
		$this->add_button_render_atts( $this->sec_prefix, $settings );// add unique render attributes for secondary
		?>
        <div class="one-elements-dual_button__wrapper">
			<?php
			$this->render_dual_button( $settings, 'primary' );
			$this->render_button_connector($settings);
			$this->render_dual_button( $settings, 'secondary' );
			?>
        </div>
		<?php
	}

	protected function render_dual_button( $settings, $type='primary' ) {
		$btn_tag = 'button';
		$prefix = ('primary' === $type) ? $this->prefix : $this->sec_prefix;
		if ( ! empty( $settings[ $prefix . 'button_link' ]['url'] ) ) {
			$btn_tag = 'a';
		}

		$btn_options = [
			'prefix' => $prefix,
			'settings' => $settings,
			'button_tag' => $btn_tag,
			'add_render_attribute' => false,
		];
		$this->render_button( $btn_options );
	}


	protected function render_button_connector( $settings = [] ) {
		if (empty( $settings )) $settings = $this->get_settings_for_display();
		if ( 'yes' == $settings['show_connector'] ) {
			?>
            <span class="one-elements-element one-elements-connector one-elements-connector--<?php echo esc_attr($settings['connector_type']); ?>">
            <?php
            if ( 'icon' === $settings['connector_type'] ) {
	            Icons_Manager::render_icon( $settings['button_connector_icon'], [ 'aria-hidden' => 'true' ] );
            }else{
	            echo esc_html( $settings['button_connector_text']);
            }
            ?>
            </span>
			<?php
		}
	}

	protected function add_button_render_atts( $prefix='', $settings = []) {

		$settings = !empty( $settings ) ? $settings : $this->get_settings_for_display();
		$primary_or_secondary_btn = strpos( $prefix, 'sec') !== false ? 'one-elements-button__secondary' : 'one-elements-button__primary';
		$this->add_render_attribute( $prefix.'button', [
				'class' => [
					'one-elements-element one-elements-button ' .$primary_or_secondary_btn,
					'one-elements-button__type-' . $settings[$prefix.'button_type'],
					'one-elements-button__icon-' . $settings[$prefix.'icon_position']
				]
			]
		);

		$this->add_render_attribute( $prefix.'button_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings[$prefix.'button_border_background'] == 'gradient' ) {
			$this->add_render_attribute( $prefix.'button_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( $prefix.'button_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings[$prefix.'button_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( $prefix.'button_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( ! empty( $settings[$prefix.'button_size'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'one-elements-button__size-' . $settings[$prefix.'button_size']);
		}

		if ( !empty( $settings[$prefix.'hover_animation'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'elementor-animation-' . $settings[$prefix.'hover_animation']);
		}

		if ( $settings[$prefix.'icon']['library'] == 'svg' ) {
			$this->add_render_attribute( 'button_icon', 'class', 'one-elements-icon__svg' );
		}

		$this->add_inline_editing_attributes( $prefix.'button_text', 'none' );

		// Button Text and Icon related atts
		$this->add_render_attribute( $prefix.'button_icon', 'class', 'one-elements-element one-elements-icon' );
		$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings[$prefix.'icon_border_gradient_type'] ) {
			$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings[$prefix.'icon_border_hover_gradient_type'] ) {
			$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}
		if ( ! empty( $settings[ $prefix . 'button_link' ]['url'] ) )
		{
			// give link passed as options priority over settings link
			$btn_link = $settings[ $prefix . 'button_link' ]['url'];

			$this->add_render_attribute( $prefix . 'button', 'href', esc_url( $btn_link) );
			$this->add_render_attribute( $prefix . 'button', 'class', 'one-elements-button__link' );

			if ( $settings[ $prefix . 'button_link' ]['is_external'] )
			{
				$this->add_render_attribute( $prefix . 'button', 'target', '_blank' );
			}

			if ( $settings[ $prefix . 'button_link' ]['nofollow'] )
			{
				$this->add_render_attribute( $prefix . 'button', 'rel', 'nofollow' );
			}
		}


	}


	/**
	 * Render button widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _content_template(  ) {

	}

	/**
	 * Render button text.
	 * Render button widget text.
	 * @since  1.5.0
	 * @access protected
	 */
	protected function render_text() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'button_icon', 'class', 'one-elements-element one-elements-icon' );
		$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		?>
        <!-- Button Content -->
        <span class="one-elements-element__content">

            <?php if ( ( $settings['button_type'] == 'circle' || $settings['enable_button_icon'] == 'yes' ) && ( ! empty( $settings['icon']['value'] ) ) ) : ?>

                <span <?php $this->print_render_attribute_string( 'button_icon' ); ?>>

					<?php if ( $settings['button_type'] !== 'circle' ) : ?>

                        <!-- Regular State Background -->
                        <span <?php $this->print_render_attribute_string( 'button_icon_regular_state' ); ?>>
							<span class="one-elements-element__state-inner"></span>
						</span>

						<?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
                            <!-- Hover State Background -->
                            <span <?php $this->print_render_attribute_string( 'button_icon_hover_state' ); ?>>
								<span class="one-elements-element__state-inner"></span>
							</span>
						<?php endif; ?>

					<?php endif; ?>


					<!-- Content including Icon -->
					<span class="one-elements-element__content">
					    <span class="one-elements-icon__content_icon">
                            <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					</span>

				</span>

            <?php endif; ?>

			<?php if ( $settings['button_type'] !== 'circle' ) : ?>

                <span class="one-elements-button__content_text">

					<?php echo esc_html( $settings['button_text'] ); ?>

					<?php if ( $settings['button_type'] == 'flat' ) : ?>
                        <span class="one-elements-button__underline"></span>
					<?php endif; ?>

				</span>

			<?php endif; ?>

        </span>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Dual_ButtonNew() );