<?php
namespace OneElements\Includes\Widgets\AccordionMembers;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Accordion extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * Retrieve icon widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-accordion';
	}

	public function get_recommended_icons() {
		return [
			'fa-regular' => [
				'arrow-alt-circle-down',
				'arrow-alt-circle-up',
				'arrow-alt-circle-left',
				'arrow-alt-circle-right',
			],
			'fa-solid' => [
				'plus',
				'plus-circle',
				'plus-square',
				'minus',
				'minus-circle',
				'minus-square',
				'angle-down',
				'angle-up',
				'angle-left',
				'angle-right',
				'caret-down',
				'caret-up',
				'caret-left',
				'caret-right',
				'angle-double-down',
				'angle-double-up',
				'angle-double-left',
				'angle-double-right',
			],
		];
	}


	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the icon widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'one_elements' ];
	}

	public function get_keywords() {
		return ['accordion', 'faq', 'toggle', 'questions', 'documentation'];
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve social icons widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'one-elements-widget-eicon eicon-accordion';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return __( 'Accordion/FAQs', 'one-elements' );
	}
	
	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the element requires.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Element scripts dependencies.
	 */
	public function get_script_depends() {
		return [];// add the name of the script handles inside the array
	}

	/**
	 * Register icon widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		/**
		 * Accordion Content General Settings
		 */
		$this->get_content_general_controls();
		/**
		 * Accordion Content Settings
		 */
		$this->get_content_content_controls();
		/**
		 * -------------------------------------------
		 * Style Accordion General Style
		 * -------------------------------------------
		 */
		$this->get_style_general_controls();
		/**
		 * -------------------------------------------
		 * Accordion Caret Settings
		 * -------------------------------------------
		 */
		$this->get_style_icon_controls();
		/**
		 * -------------------------------------------
		 * Style Accordion Title Style
		 * -------------------------------------------
		 */
		$this->get_style_title_controls();
		/**
		 * -------------------------------------------
		 * Style Accordion Content Style
		 * -------------------------------------------
		 */
		$this->get_style_content_controls();

	}

	private function get_content_general_controls() {

		$this->start_controls_section(
			'one_elements_section_accordion_settings',
			[
				'label' => esc_html__( 'General Settings', 'one-elements' )
			]
		);

		$this->add_control(
			'accordion_icon_show',
			[
				'label' 		=> esc_html__( 'Enable Icon', 'one-elements' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes',
				'return_value'	=> 'yes',
			]
		);

		$this->add_control(
			'accordion_icon',
			[
				'label'		=> esc_html__( 'Accordion Icon', 'one-elements' ),
				'type'		=> Controls_Manager::ICONS,
				'condition' => [
					'accordion_icon_show' => 'yes'
				],
				'default' => [
					'value' => 'fas fa-caret-right',
					'library' => 'fa-solid',
				],
				'recommended' => $this->get_recommended_icons(),
			]
		);

		$this->add_control(
			'accordion_hide_icon_show',
			[
				'label' 		=> esc_html__( 'Enable Secondary Icon', 'one-elements' ),
				'type'			=> Controls_Manager::SWITCHER,
				'return_value'	=> 'yes',
				'condition' => [
					'accordion_icon_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'accordion_hide_icon',
			[
				'label'		=> esc_html__( 'Accordion Icon', 'one-elements' ),
				'type'		=> Controls_Manager::ICONS,
				'condition' => [
					'accordion_icon_show' => 'yes',
					'accordion_hide_icon_show' => 'yes',
				],
				'default' => [
					'value' => 'fas fa-caret-down',
					'library' => 'fa-solid',
				],
				'recommended' => $this->get_recommended_icons(),
			]
		);

		$this->add_control(
			'accordion_icon_position',
			[
				'label' => __( 'Accordion Icon Position', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'one-elements' ),
						'icon' => 'fa fa-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'one-elements' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'condition' => [
					'accordion_icon_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'accordion_single_active_mode',
			[
				'label'			=> esc_html__( 'Active Single Item Mode', 'one-elements' ),
				'Description'	=> esc_html__( 'Enabling this means: when you open an item other item will get closed', 'one-elements' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'	=> 'no',
				'return_value' => 'yes',
			]
		);


		$this->add_control( 'accordion_transition_speed',
			[
				'label'			=> esc_html__( 'Transition Speed', 'one-elements' ),
				'type'			=> Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.05
					],
				],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);

		$this->add_control(
			'accordion_title_tag',
			[
				'label'       		=> esc_html__( 'Title HTML Tag', 'one-elements' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'h3',
				'label_block' 	=> false,
				'options' 		=> [
					'h1' 	=> esc_html__( 'H1', 'one-elements' ),
					'h2' 	=> esc_html__( 'H2', 'one-elements' ),
					'h3' 	=> esc_html__( 'H3', 'one-elements' ),
					'h4' 	=> esc_html__( 'H4', 'one-elements' ),
					'h5' 	=> esc_html__( 'H5', 'one-elements' ),
					'h6' 	=> esc_html__( 'H6', 'one-elements' ),
					'div' 	=> esc_html__( 'Div', 'one-elements' ),
					'p' 	=> esc_html__( 'P', 'one-elements' ),
				],
			]
		);



		$this->end_controls_section();

	}

	private function get_content_content_controls() {

		$this->start_controls_section(
			'one_elements_section_accordion_content_settings',
			[
				'label' => esc_html__( 'Content Settings', 'one-elements' )
			]
		);

		$this->add_control(
			'accordion_tabs',
			[
				'type'		=> Controls_Manager::REPEATER,
				'separator'	=> 'before',
				'default'	=> [
					[ 'accordion_tab_title' => esc_html__( 'Accordion Title 1', 'one-elements' ) ],
					[ 'accordion_tab_title' => esc_html__( 'Accordion Title 2', 'one-elements' ) ],
					[ 'accordion_tab_title' => esc_html__( 'Accordion Title 3', 'one-elements' ) ],
				],
				'fields' => [
					[
						'name'		=> 'accordion_tab_default_active',
						'label'		=> esc_html__( 'Make it active by default', 'one-elements' ),
						'type'		=> Controls_Manager::SWITCHER,
						'default'	=> 'no',
						'return_value' => 'yes',
					],
					[
						'name'		=> 'accordion_tab_title',
						'label'		=> esc_html__( 'Title', 'one-elements' ),
						'type'		=> Controls_Manager::TEXT,
						'default'	=> esc_html__( 'Title', 'one-elements' ),
						'dynamic'	=> [ 'active' => true ]
					],

					// @todo Wil limplement later
					// [
					// 	'name'					=> 'accordion_content_type',
					// 	'label'                 => __( 'Content Type', 'one-elements' ),
					// 	'type'                  => Controls_Manager::SELECT,
					// 	'options'               => [
					// 		'content'       => __( 'Content', 'one-elements' ),
					// 		'template'      => __( 'Saved Templates', 'one-elements' ),
					// 	],
					// 	'default'               => 'content',
					// ],
					// [
					// 	'name'					=> 'one_elements_primary_templates',
					// 	'label'                 => __( 'Choose Template', 'one-elements' ),
					// 	'type'                  => Controls_Manager::SELECT,
					// 	'options'               => one_elements_get_page_templates(),
					// 	'condition'             => [
					// 		'accordion_content_type'      => 'template',
					// 	],
					// ],
					[
						'name'		=> 'accordion_tab_content',
						'label'		=> esc_html__( 'Content', 'one-elements' ),
						'type'		=> Controls_Manager::WYSIWYG,
						'default'	=> esc_html__( 'Add or edit accordion content ', 'one-elements' ),
						'dynamic'	=> [ 'active' => true ],
						// 'condition'	=> [
						// 	'accordion_content_type'	=> 'content', // @todo Wil limplement later
						// ],
					],
				],
			]
		);

		$this->end_controls_section();

	}

	private function get_style_general_controls() {

		$this->start_controls_section(
			'one_elements_section_accordion_style_settings',
			[
				'label'	=> esc_html__( 'Single Accordion Style', 'one-elements' ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'accordion_tab_margin_bottom',
			[
				'label'	=> esc_html__( 'Bottom Space', 'one-elements' ),
				'type'	=> Controls_Manager::SLIDER,
				'default'	=> [
					'unit'	=> 'px',
				],
				'size_units'	=> [ 'px' ],
				'range'	=> [
					'px'	=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'accordion_tab_tabs' );

		# Normal State Tab
		$this->start_controls_tab( 'accordion_tab_normal', [ 'label' => esc_html__( 'Normal', 'one-elements' ) ] );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'accordion_tab_border',
				'label'		=> esc_html__( 'Border', 'one-elements' ),
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single',
			]
		);

		$this->add_responsive_control(
			'accordion_tab_border_radius',
			[
				'label'			=> esc_html__( 'Border Radius', 'one-elements' ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'		=> 'accordion_tab_box_shadow',
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single',
			]
		);

		$this->end_controls_tab();
		# Normal State Tab End


		# Active State Tab
		$this->start_controls_tab( 'accordion_tab_active', [ 'label' => esc_html__( 'Hover', 'one-elements' ) ] );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'accordion_tab_border_active',
				'label'		=> esc_html__( 'Border', 'one-elements' ),
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single.oee_is--active',
			]
		);

		$this->add_responsive_control(
			'accordion_tab_border_radius_active',
			[
				'label'			=> esc_html__( 'Border Radius', 'one-elements' ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single.oee_is--active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'		=> 'accordion_tab_active_box_shadow',
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--single.oee_is--active',
			]
		);

		$this->end_controls_tab();
		# Active State Tab End


		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function get_style_icon_controls() {

		$this->start_controls_section(
			'one_elements_section_accordion_caret_settings',
			[
				'label'	=> esc_html__( 'Icon(s)', 'one-elements' ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'accordion_toggle_icon_size',
			[
				'label'	=> __( 'Icon Size', 'one-elements' ),
				'type'	=> Controls_Manager::SLIDER,
				'default'	=> [
					'unit'	=> 'px',
				],
				'size_units'	=> [ 'px' ],
				'range'	=> [
					'px'	=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__icon_indicator i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__icon_indicator span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__icon_indicator svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'accordion_icon_show' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'accordion_tab_icon_gap',
			[
				'label'		=> __( 'Icon Spacing', 'one-elements' ),
				'type'		=> Controls_Manager::SLIDER,
				'default'	=> [
					'unit'	=> 'px',
				],
				'size_units'	=> [ 'px' ],
				'range'			=> [
					'px'	=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .oee__icon-indicator-position--left .one-elements-accordion--title .oee__icon_indicator' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .oee__icon-indicator-position--right .one-elements-accordion--title .oee__icon_indicator' => 'margin-left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->start_controls_tabs( 'accordion_icon_style_tabs' );

		# Normal State Tab
		$this->start_controls_tab( 'accordion_icon_style_normal', [ 'label' => esc_html__( 'Normal', 'one-elements' ) ] );
		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'label'		=> esc_html__( 'Accordion Icon Color', 'one-elements' ),
			'name'		=> 'accordion_toggle_color',
			'type'		=> Controls_Manager::COLOR,
			'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__icon_indicator span > *',
			'conditions' => [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'accordion_icon_show',
						'operator' => '===',
						'value' => 'yes',
					],
				],
			],
		] );
		$this->end_controls_tab();

		# Active State Tab
		$this->start_controls_tab( 'accordion_icon_style_active', [ 'label' => esc_html__( 'Active', 'one-elements' ) ] );
		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'label'		=> esc_html__( 'Accordion Icon Color', 'one-elements' ),
			'name'		=> 'accordion_toggle_color_active',
			'type'		=> Controls_Manager::COLOR,
			'selector'	=> '{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title .oee__icon_indicator span > *',
			'conditions' => [
				'relation' => 'and',
				'terms' => [
					[
						'name' => 'accordion_icon_show',
						'operator' => '===',
						'value' => 'yes'
					],
				],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function get_style_title_controls() {

		$this->start_controls_section(
			'one_elements_section_accordions_title_style',
			[
				'label'	=> esc_html__( 'Title', 'one-elements' ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'		=> 'accordion_tab_title_typography',
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__title',
			]
		);

		$this->start_controls_tabs( 'accordion_title_tabs' );

		# Normal State Tab
		$this->start_controls_tab( 'accordion_title_normal', [ 'label' => esc_html__( 'Normal', 'one-elements' ) ] );

		$this->add_responsive_control(
			'accordion_title_padding',
			[
				'label'	=> esc_html__( 'Title Padding', 'one-elements' ),
				'type'	=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'accordion_title_color',
			[
				'label'	=> esc_html__( 'Background Color', 'one-elements' ),
				'type'	=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accordion_title_text_color',
			[
				'label'		=> esc_html__( 'Text Color', 'one-elements' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors'	=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title .oee__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'accordion_title_border',
				'label'		=> esc_html__( 'Border', 'one-elements' ),
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title',
			]
		);

		$this->add_responsive_control(
			'accordion_title_border_radius',
			[
				'label'			=> esc_html__( 'Border Radius', 'one-elements' ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();




		#Active State Tab
		$this->start_controls_tab(
			'accordion_header_active',
			[
				'label' => esc_html__( 'Active', 'one-elements' )
			]
		);

		$this->add_responsive_control(
			'accordion_title_active_padding',
			[
				'label'	=> esc_html__( 'Title Padding', 'one-elements' ),
				'type'	=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'accordion_title_color_active',
			[
				'label'		=> esc_html__( 'Background Color', 'one-elements' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accordion_title_text_color_active',
			[
				'label'		=> esc_html__( 'Text Color', 'one-elements' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors'	=> [
					'{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title .oee__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'accordion_title_border_active',
				'label'		=> esc_html__( 'Border', 'one-elements' ),
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title',
			]
		);

		$this->add_responsive_control(
			'accordion_title_border_radius_active',
			[
				'label'			=> esc_html__( 'Border Radius', 'one-elements' ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .oee_is--active .one-elements-accordion--title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function get_style_content_controls() {

		$this->start_controls_section(
			'one_elements_section_accordion_tab_content_style_settings',
			[
				'label'	=> esc_html__( 'Content', 'one-elements' ),
				'tab'	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'		=> 'accordion_content_typography',
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content',
			]
		);

		$this->add_responsive_control(
			'accordion_content_padding',
			[
				'label'	=> esc_html__( 'Padding', 'one-elements' ),
				'type'	=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'accordion_content_bg_color',
			[
				'label'		=> esc_html__( 'Background Color', 'one-elements' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors'	=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'accordion_content_text_color',
			[
				'label'		=> esc_html__( 'Text Color', 'one-elements' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'accordion_content_border',
				'label'		=> esc_html__( 'Border', 'one-elements' ),
				'selector'	=> '{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content',
			]
		);

		$this->add_responsive_control(
			'accordion_content_border_radius',
			[
				'label'			=> esc_html__( 'Border Radius', 'one-elements' ),
				'type'			=> Controls_Manager::DIMENSIONS,
				'size_units'	=> [ 'px', 'em', '%' ],
				'selectors'		=> [
					'{{WRAPPER}} .one-elements-accordion .one-elements-accordion--content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$accordion_tabs = $this->get_settings_for_display( 'accordion_tabs' );
		$id_int = substr( $this->get_id_int(), 0, 3 );

		$display_accordion_icon = $settings['accordion_icon_show'] == 'yes' ? true : false;
		$display_accordion_hide_icon = $settings['accordion_hide_icon_show'] == 'yes' ? true : false;
		$accordion_icon_position = empty($settings['accordion_icon_position']) ? 'left' : $settings['accordion_icon_position'];

		$js_settings = [
			'transition_speed' => $settings['accordion_transition_speed'],
			'hide_others' => $settings['accordion_single_active_mode'] == 'yes' ? true : false
		];

		$this->add_render_attribute( 'one-elements-accordion', [
			'class' => [ 'one-elements-accordion' ],
			'role' => 'tablist',
			'data-accordion-settings' => json_encode($js_settings)
		]);
		
		$this->add_render_attribute( 'accordion_tab_title__html', 'class', 'oee__title' );

		if ( $display_accordion_icon && !empty($settings['accordion_icon']) ) {
			
			$this->add_render_attribute( 'one-elements-accordion', 'class', 'oee__has_icon-indicator' );
			$this->add_render_attribute( 'one-elements-accordion', 'class', 'oee__icon-indicator-position--' . $accordion_icon_position );

			if ( $display_accordion_hide_icon && !empty($settings['accordion_hide_icon']) ) {
				$this->add_render_attribute( 'one-elements-accordion', 'class', 'oee__has_secondary--icon-indicator' );
			}

		}

		?>

		<div <?php $this->print_render_attribute_string( 'one-elements-accordion' ); ?>>
				
			<?php foreach ( $accordion_tabs as $index => $item ) :

				$tab_count = $index + 1;

				$tab_single_setting_key = $this->get_repeater_setting_key( 'accordion_tab_area', 'accordion_tabs', $tab_count );
				$tab_title_setting_key = $this->get_repeater_setting_key( 'accordion_tab_title', 'accordion_tabs', $tab_count );
				$tab_content_setting_key = $this->get_repeater_setting_key( 'accordion_tab_content', 'accordion_tabs', $index );

				$item_is_default_active = $item['accordion_tab_default_active'] == 'yes' ? true : false;

				$this->add_render_attribute( $tab_single_setting_key, [
					'id' => 'one-elements-accordion--single-' . $id_int . $tab_count,
					'class' => [ 'one-elements-accordion--single' ],
				]);

				if ( $item_is_default_active ) {
					$this->add_render_attribute( $tab_single_setting_key, 'class', 'oee_is--active' );
				}

				$this->add_render_attribute( $tab_title_setting_key, [
					'class' => [ 'one-elements-accordion--title' ],
					'data-tab' => $tab_count,
					'role' => 'tab',
				]);

				$this->add_render_attribute( $tab_content_setting_key, [
					'class' => [ 'one-elements-accordion--content elementor-clearfix' ],
					'data-tab' => $tab_count,
					'role' => 'tabpanel',
				]);


				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );

				?>

				<div <?php $this->print_render_attribute_string( $tab_single_setting_key ); ?>>

					<div <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>>
						
						<?php if ( $display_accordion_icon && !empty($settings['accordion_icon']) ): ?>

							<span class="oee__icon_indicator">

								<span><?php Icons_Manager::render_icon( $settings['accordion_icon'] ); ?></span>

								<?php if ( $display_accordion_hide_icon && !empty($settings['accordion_hide_icon']) ): ?>
									<span><?php Icons_Manager::render_icon( $settings['accordion_hide_icon'] ); ?></span>
								<?php endif ?>

							</span>

						<?php endif ?>

						<?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['accordion_title_tag'], $this->get_render_attribute_string( 'accordion_tab_title__html' ), $item['accordion_tab_title'] ); ?>
						
					</div>

					<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>>

						<?php


						// @todo Will do later

						// if ( "template" == $item['accordion_content_type'] && !empty($item['one_elements_primary_templates']) ) {

							// echo Plugin::$instance->frontend->get_builder_content_for_display( $item['one_elements_primary_templates'] );

						// } elseif ('content' == $item['accordion_content_type'] && !empty( $item['accordion_tab_content'] )){

							echo wpautop( $this->parse_text_editor($item['accordion_tab_content']) );

						// }

						?>

					</div>
					
				</div>

			<?php endforeach; ?>

		</div>

		<?php

	}

	/**
	 * Render icon widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {

	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Accordion() );