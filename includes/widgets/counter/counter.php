<?php
namespace OneElements\Includes\Widgets\Counter;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Counter;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Divider_Trait;
use OneElements\Includes\Traits\One_Elements_Icon_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor counter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Counter extends Widget_Counter {
	use One_Elements_Icon_Trait;
	use One_Elements_Divider_Trait;
	/**
	 * @var array
	 */
	protected $content_position;

	/**
	 * Get widget name.
	 *
	 * Retrieve counter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-counter';
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
		return 'one-elements-widget-eicon eicon-counter';
	}

	public static function get_heading_tags() {
		return [
			'h1' => 'H1',
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
			'div' => 'div',
			'span' => 'span',
			'p' => 'p'
		];
	}


	private static function get_order_numbers($limit = 4) {
		if ( !empty( $limit) && is_int( $limit) ) {
			$a =[];
			for ($i=1; $i <= $limit; $i++){
				$a[$i] = $i;
			}
			return $a;
		}
		return [];

	}

	private function get_icon_control_default_args( $prefix='' ) {
		return [
			'prefix' => $prefix,
			'excludes' => ['icon_css_id', 'icon_align'],
			'includes' => [],
			'selectors'=>[
				'icon_box_size' => [
					'{{WRAPPER}} .counter_icon__wrapper .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			        '{{WRAPPER}} .one-elements-counter__layout_2' => 'padding-top: calc({{SIZE}}{{UNIT}}/2);',
			        '{{WRAPPER}} .one-elements-counter__layout_5' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
			        '{{WRAPPER}} .one-elements-counter__layout_6' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);'
				],
			],
			'labels' => [],
			'conditions' => [
				'section_icon' => [ $prefix.'enable_icon' => 'yes' ],
				'section_icon_style' => [
					$prefix.'enable_icon' => 'yes',
					$prefix.'icon[value]!' => '',
				],
				'icon_background_section' => [
					$prefix.'enable_icon' => 'yes',
					$prefix.'icon[value]!' => '',
				],
				'icon_border_section' => [
					$prefix.'enable_icon' => 'yes',
					$prefix.'icon[value]!' => '',
				],
			],
		];
	}


	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
	    $divider_options = [
		    'conditions' => [
			    'one_divider' => ['show_divider' => 'yes'],
		    ]
        ];
		$this->init_content_counter_settings();
		// Content -> Icon
		$this->init_content_icon_settings($this->get_icon_control_default_args());
		//Content -> Divider from the trait
		$this->init_content_divider_controls($divider_options);
		$this->init_content_secondary_divider_controls($divider_options);

		$this->init_content_layout_settings();
		$this->init_style_counter_settings();
		$this->init_style_counter_background_settings();
		$this->init_style_counter_overlay_settings();
		$this->init_style_counter_border_settings();
		// Style > Icon
		$this->init_style_icon_background_settings($this->get_icon_control_default_args());
		$this->init_style_icon_border_settings($this->get_icon_control_default_args());
	}

	protected function init_content_counter_settings() {

		$this->start_controls_section(
			'section_counter',
			[
				'label' => __( 'Counter', 'one-elements' ),
			]
		);

		$this->add_control(
			'starting_number',
			[
				'label' => __( 'Starting Number', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->add_control(
			'ending_number',
			[
				'label' => __( 'Ending Number', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => __( 'Number Prefix', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Eg. 1', 'one-elements' ),
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Eg. Plus', 'one-elements' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
		);

		$this->add_control(
			'thousand_separator',
			[
				'label' => __( 'Thousand Separator', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'one-elements' ),
				'label_off' => __( 'Hide', 'one-elements' ),
			]
		);

		$this->add_control(
			'thousand_separator_char',
			[
				'label' => __( 'Separator', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'thousand_separator' => 'yes',
				],
				'options' => [
					'' => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Cool Number', 'one-elements' ),
				'placeholder' => __( 'Cool Number', 'one-elements' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'header_tag',
			[
				'label' => __( 'Title HTML Tag', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_heading_tags(),
				'default' => 'div',
				'condition' => [ 'title!' => ''],
			]
		);

		$this->add_control(
			'enable_icon',
			[
				'label' => __( 'Enable Icon', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);
		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Show Divider', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'one-elements' ),
				'label_off' => __( 'Hide', 'one-elements' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

	}

	private function init_content_layout_settings() {

		$this->start_controls_section( 'section_layout',
			[
				'label' => __( 'Layout', 'one-elements' ),
			]
		);
		$this->add_control( 'counter_layout',
			[
				'label' => __( 'Choose a Layout', 'one-elements' ),
				'type' => 'image_choose',
				'display_per_row' => 4,
				'options' => [
					'1' => [
						'title' => __( 'Layout 1', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_2.png',
					],

					'2' => [
						'title' => __( 'Layout 2', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_5.png',
					],

					'3' => [
						'title' => __( 'Layout 3', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_7.png',
					],
					'4' => [
						'title' => __( 'Layout 4', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_8.png',
					],
					'5' => [
						'title' => __( 'Layout 5', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_9.png',
					],
					'6' => [
						'title' => __( 'Layout 6', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/counter/layout_10.png',
					]
				],
				'default' => '1'
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'one-elements' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'one-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'one-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .one-elements-element__content' => "text-align: {{VALUE}}"
				],
				'condition' => [
					'counter_layout' => ['1', '2']
				]
			]
		);

		$this->add_control( 'content_order_heading',
			[
				'label' => __( 'Content Order', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control( 'icon_order',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 1,
				'condition' => [
					'counter_layout' => ['1', '2'],
				],
			]
		);
		$this->add_control( 'number_order',
			[
				'label' => __( 'Number', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 2,
				'condition' => [
					'counter_layout' => ['1', '2'],
				],
			]
		);
		$this->add_control( 'divider_order',
			[
				'label' => __( 'Divider', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 3,
				'condition' => [
				    'show_divider' => 'yes',
				    'counter_layout' => ['1', '2'],
                ],

			]
		);
		$this->add_control( 'title_order',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 4,
				'condition' => [ 'title!' => '', 'counter_layout' => ['1', '2'],],
			]
		);
		// for layout 3 to 6, Number order will be 1/3, default 2.

		$this->add_control( 'number_order_l36',
			[
				'label' => __( 'Number', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 1,
				'condition' => [
					'counter_layout' => ['3', '4', '5', '6'],
				],
			]
		);
		$this->add_control( 'divider_order_l36', [
				'label' => __( 'Divider', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 2,
				'condition' => [
					'show_divider' => 'yes',
					'counter_layout' => ['3', '4', '5', '6'],
				],

			]
		);

		$this->add_control( 'title_order_l36',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 3,
				'condition' => [ 'title!' => '', 'counter_layout' => ['3', '4', '5', '6'],],
			]
		);

		$this->end_controls_section();
	}

	private function init_style_counter_settings() {

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Counter', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_typography',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter .one-elements-heading .one-elements-element__content',
				'condition' => [ 'title!' => ''],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => __( 'Number', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter__number',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_prefix_typography',
				'label' => __( 'Number Prefix', 'one-elements' ),
				'condition' => ['prefix!' => ''],
				'selector' => '{{WRAPPER}} .one-elements-counter__number-prefix',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_suffix_typography',
				'label' => __( 'Number Suffix', 'one-elements' ),
				'condition' => ['suffix!' => ''],
				'selector' => '{{WRAPPER}} .one-elements-counter__number-suffix',
			]
		);


		$this->add_control(
			'style_color',
			[
				'label' => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'tabs_style_colors' );
        //Normal
		$this->start_controls_tab(
			'tab_style_color_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_color',
				'label' => __( 'Icon Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-counter .one-elements-icon .one-elements-icon__content_icon > *'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title_color',
				'label' => __( 'Title Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter .one-elements-heading .one-elements-element__content',
				'condition' => [ 'title!' => ''],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_color',
				'label' => __( 'Number Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter__number',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_prefix_color',
				'label' => __( 'Number Prefix Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => ['prefix!' => ''],
				'selector' => '{{WRAPPER}} .one-elements-counter__number-prefix',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_suffix_color',
				'label' => __( 'Number Suffix Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => ['suffix!' => ''],
				'selector' => '{{WRAPPER}} .one-elements-counter__number-suffix',
			]
		);
		$this->end_controls_tab();

        //Hover
		$this->start_controls_tab(
			'tab_style_color_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'hover_icon_color',
				'label' => __( 'Icon Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-counter:hover .one-elements-icon .one-elements-icon__content_icon > *',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title_color_hover',
				'label' => __( 'Title Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter:hover .one-elements-heading .one-elements-element__content',
				'condition' => [ 'title!' => ''],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_color_hover',
				'label' => __( 'Number Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter:hover .one-elements-counter__number',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_prefix_color_hover',
				'label' => __( 'Number Prefix Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => ['prefix!' => ''],
				'selector' => '{{WRAPPER}} .one-elements-counter:hover .one-elements-counter__number-prefix',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'number_suffix_color_hover',
				'label' => __( 'Number Suffix Color', 'one-elements' ),
				'condition' => ['suffix!' => ''],
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter:hover .one-elements-counter__number-suffix',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// SPACING
		$this->add_control(
			'style_spacing',
			[
				'label' => __( 'Spacing', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
                ],
				'selectors' => [
					'{{WRAPPER}} .counter_icon__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-heading .one-elements-element__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [ 'title!' => ''],
			]
		);

		$this->add_responsive_control(
			'number_margin',
			[
				'label' => __( 'Number Area', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter__number-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'number_prefix_margin',
			[
				'label' => __( 'Number Prefix', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter__number-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => ['prefix!' => ''],
			]
		);

		$this->add_responsive_control(
			'number_suffix_margin',
			[
				'label' => __( 'Number Suffix', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'condition' => ['suffix!' => ''],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter__number-suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Counter Padding', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);


		$this->add_control(
			'counter_transition',
			[
				'label' => __( 'Transition Speed', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);


		$this->end_controls_section();

	}

	private function init_style_counter_background_settings() {

		$this->start_controls_section(
			'counter_background_section',
			[
				'label' => __( 'Background', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__regular-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__hover-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_counter_overlay_settings() {

		$this->start_controls_section(
			'counter_background_overlay',
			[
				'label' => __( 'Background Overlay', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_background_overlay' );

		$this->start_controls_tab(
			'tab_background_overlay_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__regular-state .one-elements-element__state-inner:after',
			]
		);

		$this->add_control(
			'background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'condition' => [
					'background_overlay_background' => [ 'classic', 'gradient' ],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_overlay_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hover_background_overlay',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__hover-state .one-elements-element__state-inner:after',
			]
		);

		$this->add_control(
			'background_overlay_hover_opacity',
			[
				'label' => __( 'Opacity', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'condition' => [
					'hover_background_overlay_background' => [ 'classic', 'gradient' ],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter:hover > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_counter_border_settings() {

		$this->start_controls_section(
			'counter_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_counter_border' );

		$this->start_controls_tab(
			'tab_counter_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'counter_border',
				'label' => __( 'Counter Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter, {{WRAPPER}} .one-elements-counter > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-counter > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-counter > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'counter_box_shadow',
				'label' => __( 'Counter Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_counter_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'counter_border_hover',
				'label' => __( 'Counter Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-counter:hover, {{WRAPPER}} .one-elements-counter:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-counter:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-counter:hover > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_counter_box_shadow',
				'label' => __( 'Counter Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-counter:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$layout = !empty( $settings['counter_layout']) ? $settings['counter_layout']: 1;
		$this->add_render_attribute(
			'wrapper',
			[
		        'class' => [
                    'one-elements-counter__wrapper',
                    'one-elements-counter__layout_' . $settings['counter_layout'],
                    $settings['_css_classes']
                ]
            ]
		);

		$this->add_render_attribute(
			'counter',
			[
		        'class' => [
                    'one-elements-element one-elements-counter',
                ]
            ]
		);
		
		$this->add_render_attribute( 'counter_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['counter_border_gradient_type'] ) {
			$this->add_render_attribute( 'counter_regular_state', 'class', 'one-elements-element__border-gradient' );
		}
		
		$this->add_render_attribute( 'counter_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['counter_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'counter_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( isset( $settings['counter_css_id']) && '' !== $settings['counter_css_id'] ) {
			$this->add_render_attribute( 'counter', 'id', $settings['counter_css_id'] );
		}
        //@todo; ask if we need to hove animaiton as it is missing in control but used below?
		//if ( !empty( $settings['hover_animation']) ) {
		//	$this->add_render_attribute( 'counter', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		//}
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'counter' ); ?>>
				<!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string( 'counter_regular_state' ); ?>>
                	<span class="one-elements-element__state-inner"></span>
                </span>
                <?php if ( $settings['background_hover_background'] || $settings['counter_border_hover_background'] ) : ?>
                	<!-- Hover State Background -->
	                <span <?php $this->print_render_attribute_string( 'counter_hover_state' ); ?>>
	                	<span class="one-elements-element__state-inner"></span>
	                </span>
	            <?php endif; ?>
                <!-- Content -->
                <?php $this->get_content_by_layout( $layout ); ?>
            </div>
        </div>
		<?php
	}

	private function get_item_by_position( $pos ) {

        $pos = (int) $pos; // make sure it is int

		--$pos;

		if ( $pos < 0 ) {
			$pos = 0;
		}

		if ( $pos > 3 ) {
			$pos = 3;
		}

		$settings = $this->get_settings_for_display();
		$layout = !empty( $settings['counter_layout']) ? $settings['counter_layout'] : 1;

		if ( empty($this->content_position) ) {
			if ( in_array( $layout, [1,2 ]) ) {
				$content_position = array(
					array(
						'content_name' => 'icon',
						'position' => $settings['icon_order']
					),
					array(
						'content_name' => 'title',
						'position' => $settings['title_order']
					),
					array(
						'content_name' => 'divider',
						'position' => $settings['divider_order']
					),
					array(
						'content_name' => 'number',
						'position' => $settings['number_order']
					),
				);
			}else{
				$content_position = [
					[
						'content_name' => 'number',
						'position' => $settings['number_order_l36']
					],
					[
						'content_name' => 'divider',
						'position' => $settings['divider_order_l36']
					],
					[
						'content_name' => 'title',
						'position' => $settings['title_order_l36']
					],
				];
			}



			usort($content_position, function($a, $b) {
				return $a['position'] - $b['position'];
			});

			$this->content_position = $content_position;

		}

		$content_fun = 'render_counter_content_' . $this->content_position[ $pos ]['content_name'];

		call_user_func( array( $this, $content_fun ), $settings );

	}

	private function get_content_by_layout( $layout ) {

        $layout = (int) $layout;

		if ( $layout < 1 ) {
			$layout = 1;
		}

		if ( $layout > 6 ) {
			$layout = 6;
		}

		$layout_fun = 'get_content_by_layout_' . $layout;

		call_user_func( array( $this, $layout_fun ) );

	}

	private function get_content_by_layout_1() {
		?>
        <div class="one-elements-element__content">
			<?php $this->get_item_by_position(1); ?>
			<?php $this->get_item_by_position(2); ?>
			<?php $this->get_item_by_position(3); ?>
			<?php $this->get_item_by_position(4); ?>
        </div>
		<?php
	}


	private function get_content_by_layout_2() {
		$this->get_content_by_layout_1();
	}


	private function get_content_by_layout_3() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content">
			<div class="oee__row oee__align-middle">
				<div class="oee__column">
					<?php $this->render_counter_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_content_by_layout_4() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content elementor-align-right">
			<div class="oee__row oee__content-right oee__row-reverse oee__align-middle">
				<div class="oee__column">
					<?php $this->render_counter_content_icon($settings); ?>
                </div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);
					 ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_content_by_layout_5() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content">
			<div class="oee__row oee__align-middle">
				<div class="oee__column">
					<?php $this->render_counter_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_content_by_layout_6() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content elementor-align-right">
			<div class="oee__row oee__content-right oee__row-reverse oee__align-middle">

				<div class="oee__column">
					<?php $this->render_counter_content_icon($settings); ?>
				</div>

				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3); ?>
				</div>

			</div>
		</div>
		<?php
	}

	private function render_counter_content_icon( $settings ) {

		if ( 'yes' !== $settings['enable_icon'] ||  empty($settings['icon']['value']) ) return;

		$this->add_render_attribute( 'counter_icon', 'class', 'one-elements-element one-elements-icon one-elements-counter__content_icon' );
		$this->add_render_attribute( 'counter_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_gradient_type'] ) {
			$this->add_render_attribute( 'counter_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'counter_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'counter_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		// for svg icon
		if ( $settings['icon']['library'] == 'svg' ) {
			$this->add_render_attribute( 'counter_icon', 'class', 'one-elements-icon__svg' );
		}

		?>

		<div class="counter_icon__wrapper">

			<span <?php $this->print_render_attribute_string( 'counter_icon' ); ?>>

				<!-- Regular State Background -->
				<span <?php $this->print_render_attribute_string( 'counter_icon_regular_state' ); ?>>
					<span class="one-elements-element__state-inner"> </span>
				</span>

				<?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
					<!-- Hover State Background -->
					<span <?php $this->print_render_attribute_string( 'counter_icon_hover_state' ); ?>>
						<span class="one-elements-element__state-inner"></span>
					</span>
				<?php endif; ?>


				<!-- Icon Content -->
				<span class="one-elements-element__content">
				    <span class="one-elements-icon__content_icon">
                        <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
				</span>

			</span>

		</div>

		<?php

	}

	private function render_counter_content_title( $settings ) {

		if ( !isset($settings['title']) || '' === $settings['title'] ) return;

		$this->add_inline_editing_attributes( 'title' );

		$this->add_render_attribute( 'title', 'class', 'one-elements-element__content' );
		?>

		<div class="one-elements-element one-elements-heading">
			<?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_tag'], $this->get_render_attribute_string( 'title' ), $settings['title'] ); ?>
		</div>

		<?php

	}

	private function render_counter_content_number( $settings ) {

		$this->add_render_attribute( 'counter-number', [
			'class' => 'one-elements-counter__number',
			'data-duration' => $settings['duration'],
			'data-to-value' => $settings['ending_number'],
		]);

		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter-number', 'data-delimiter', $delimiter );
		}

		?>

		<div class="one-elements-counter__number-wrapper">

			<?php if ( isset( $settings['prefix'] ) && '' !==$settings['prefix'] ) : ?>
				<span class="one-elements-counter__number-prefix"><?php echo esc_html( $settings['prefix']); ?></span>
			<?php endif ?>

            <span <?php $this->print_render_attribute_string( 'counter-number' ); ?>><?php echo esc_html( $settings['starting_number']); ?></span>

			<?php if ( isset( $settings['suffix'] ) && '' !==$settings['suffix'] ) : ?>
				<span class="one-elements-counter__number-suffix"><?php echo esc_html( $settings['suffix']); ?></span>
			<?php endif ?>

        </div>

        <?php

	}

	private function render_counter_content_divider( $settings ) {
		if (isset( $settings['show_divider']) && $settings['show_divider'] !== 'yes' ) return;
        $this->render_divider($settings);
	}

	/**
	 * Render counter widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
        ?>
        <#
        var OneCounterHandler={};
        let iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );

        //Defining all attributes
        var layout = settings['counter_layout'] ? settings['counter_layout']: 1;

        view.addRenderAttribute( 'wrapper', 'class', [
            'one-elements-counter__wrapper',
            'one-elements-counter__layout_' + settings['counter_layout'],
            settings['_css_classes']
        ]);

        view.addRenderAttribute( 'counter','class', 'one-elements-element one-elements-counter');

        view.addRenderAttribute( 'counter_regular_state', 'class', 'one-elements-element__regular-state' );

        if ( settings['counter_border_gradient_type'] ) {
            view.addRenderAttribute( 'counter_regular_state', 'class', 'one-elements-element__border-gradient' );
        }

        view.addRenderAttribute( 'counter_hover_state', 'class', 'one-elements-element__hover-state' );

        if ( settings['counter_border_hover_gradient_type'] ) {
            view.addRenderAttribute( 'counter_hover_state', 'class', 'one-elements-element__border-gradient' );
        }

        if ( settings['counter_css_id']  ) {
            view.addRenderAttribute( 'counter', 'id', settings['counter_css_id'] );
        }





        // define render related functions
        // add all dynamic layout functions in to OneCounterHandler for cleaner dynamic call without messing with global object

        //---- DISPLAY ICON-------------
        OneCounterHandler.render_counter_content_icon = function () {

            if ( 'yes' !== settings['enable_icon']  ||  !settings['icon']['value'] ) return;
            view.addRenderAttribute( 'counter_icon', 'class', 'one-elements-element one-elements-icon one-elements-counter__content_icon' );
            view.addRenderAttribute( 'counter_icon_regular_state', 'class', 'one-elements-element__regular-state' );

            if ( settings['icon_border_gradient_type'] ) {
                view.addRenderAttribute( 'counter_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
            }

            view.addRenderAttribute( 'counter_icon_hover_state', 'class', 'one-elements-element__hover-state' );

            if ( settings['icon_border_hover_gradient_type'] ) {
                view.addRenderAttribute( 'counter_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
            }


            if ( settings.icon.library == 'svg' ) {
                view.addRenderAttribute( 'counter_icon', {
                'class': 'one-elements-icon__svg'
                });
            }

            #>
            <div class="counter_icon__wrapper">

                <span {{{ view.getRenderAttributeString( 'counter_icon' ) }}}>

                    <!-- Regular State Background -->
                    <span {{{ view.getRenderAttributeString( 'counter_icon_regular_state' ) }}}>
                        <span class="one-elements-element__state-inner"></span>
                    </span>

                    <!-- Hover State Background -->
                    <span {{{ view.getRenderAttributeString( 'counter_icon_hover_state' ) }}}>
                        <span class="one-elements-element__state-inner"></span>
                    </span>

                    <!-- Icon Content -->
                    <span class="one-elements-element__content">
                        <span class="one-elements-icon__content_icon">
                                {{{iconHTML.value}}}
                        </span>
                    </span>

                </span>

            </div>
        <#
        }


        //---- DISPLAY TITLE-------------
        OneCounterHandler.render_counter_content_title = function () {
            if ( !settings['title'] ) return;
            view.addInlineEditingAttributes( 'title' );
            view.addRenderAttribute( 'title', 'class', 'one-elements-element__content' );
            #>
            <div class="one-elements-element one-elements-heading">
                <{{{settings['header_tag']}}} {{{ view.getRenderAttributeString( 'title' ) }}}>
                {{{settings['title']}}}
            </{{{settings['header_tag']}}}>
            </div>
            <#
        }



        //---- DISPLAY NUMBER-------------
        OneCounterHandler.render_counter_content_number = function () {

            view.addRenderAttribute( 'counter-number', 'class', 'one-elements-counter__number');
            if ( settings['thousand_separator']  ) {
                 const delimiter = settings['thousand_separator_char'] ? settings['thousand_separator_char'] : ',';
                 view.addRenderAttribute( 'counter-number', 'data-delimiter', delimiter );
            }

            #>
            <div class="one-elements-counter__number-wrapper">
                <# if ( settings['prefix'] ) { #>
                <span class="one-elements-counter__number-prefix">{{ settings['prefix'] }}</span>
                <# } #>

                <span {{{ view.getRenderAttributeString( 'counter-number' ) }}} data-duration = "{{ settings['duration'] }}" data-to-value="{{ settings['ending_number'] }}" >{{ settings['starting_number'] }}</span>

                <# if ( settings['suffix'] ) { #>
                <span class="one-elements-counter__number-suffix">{{ settings['suffix'] }}</span>
                <# } #>
            </div>
            <#
        }

        //---- DISPLAY DIVIDER-------------
        OneCounterHandler.render_counter_content_divider = function (  ) {
        if ( 'yes' !== settings['show_divider'] ) return;
        #>
        <?php $this->_content_template_divider(); ?>
        <#
        }


        function get_item_by_position(pos) {
            pos = parseInt(pos, 10);
            --pos;
            if ( pos < 0 ) {pos = 0;}
            if ( pos > 3 ) {pos = 3;}
        let layout = parseInt(settings.counter_layout, 10);

            if ( !OneCounterHandler.content_position) {
                let content_position = [];

                if( [1,2].includes(layout) ) {
                    content_position = [
                        { content_name: 'icon', position: settings.icon_order },
                        { content_name: 'title', position: settings.title_order },
                        { content_name: 'number', position: settings.number_order },
                        { content_name: 'divider', position: settings.divider_order },
                    ]

                } else {
                    content_position = [
                        { content_name: 'title', position: settings.title_order_l36 },
                        { content_name: 'number', position: settings.number_order_l36 },
                        { content_name: 'divider', position: settings.divider_order_l36 },
                    ]

                }


                content_position.sort((a, b) => (a.position > b.position) ? 1 : -1);
                OneCounterHandler.content_position = content_position;
            }

            const content_fun = 'render_counter_content_' + OneCounterHandler.content_position[ pos ]['content_name'];

            if( typeof OneCounterHandler[content_fun] === 'function'){
                OneCounterHandler[content_fun](); // call content related function dynamically
            }
        }


        OneCounterHandler.get_content_by_layout_1 = function (){
            #>
            <div class="one-elements-element__content">
                <#
                    get_item_by_position(1);
                    get_item_by_position(2);
                    get_item_by_position(3);
                    get_item_by_position(4);
                #>
            </div>
            <#
        };


        OneCounterHandler.get_content_by_layout_2 = function (){
            OneCounterHandler.get_content_by_layout_1();
        };


        OneCounterHandler.get_content_by_layout_3 = function (){
            #>
            <div class="one-elements-element__content">
                <div class="oee__row oee__align-middle">
                    <div class="oee__column">
                        <# OneCounterHandler.render_counter_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };

        OneCounterHandler.get_content_by_layout_4 = function (){
            #>
            <div class="one-elements-element__content elementor-align-right">
                <div class="oee__row oee__content-right oee__row-reverse oee__align-middle">
                    <div class="oee__column">
                        <# OneCounterHandler.render_counter_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };

        OneCounterHandler.get_content_by_layout_5 = function (){
            #>
            <div class="one-elements-element__content">
                <div class="oee__row oee__align-middle">
                    <div class="oee__column">
                        <# OneCounterHandler.render_counter_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };

        OneCounterHandler.get_content_by_layout_6 = function (){
            #>
            <div class="one-elements-element__content elementor-align-right">
                <div class="oee__row oee__content-right oee__row-reverse oee__align-middle">
                    <div class="oee__column">
                        <# OneCounterHandler.render_counter_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };

        function get_content_by_layout(layout){
            layout = parseInt(layout, 10);
            if ( layout < 1 )  layout = 1;
            if ( layout > 6 ) layout = 6;
            let layout_fun = 'get_content_by_layout_' + layout;

            if( typeof OneCounterHandler[layout_fun] === 'function'){
                OneCounterHandler[layout_fun](); // call layout function dynamically
            }
        }

        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

            <div {{{ view.getRenderAttributeString( 'counter' ) }}}>

                <!-- Regular State Background -->
                <span {{{ view.getRenderAttributeString( 'counter_regular_state' ) }}}>
                	<span class="one-elements-element__state-inner"></span>
                </span>

                <!-- Hover State Background -->
                <span {{{ view.getRenderAttributeString( 'counter_hover_state' ) }}}>
                    <span class="one-elements-element__state-inner"></span>
                </span>

                <!-- Content -->
                <# get_content_by_layout(layout); #>

            </div>

        </div>
        <?php
	}

}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Counter() );
