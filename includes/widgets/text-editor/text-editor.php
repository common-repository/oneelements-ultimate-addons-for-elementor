<?php
namespace OneElements\Includes\Widgets\Text_Editor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Text_Editor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Text_Editor extends Widget_Text_Editor {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-text-editor';
	}



	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
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
		return 'one-elements-widget-eicon eicon-text';
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		
		parent::_register_controls();

		$this->remove_control( 'text_color' );

		$this->remove_control( 'typography_typography' );
		$this->remove_control( 'typography_font_family' );
		$this->remove_control( 'typography_font_weight' );
		$this->remove_control( 'typography_text_transform' );
		$this->remove_control( 'typography_font_style' );
		$this->remove_control( 'typography_text_decoration' );
		$this->remove_responsive_control( 'typography_font_size' );
		$this->remove_responsive_control( 'typography_line_height' );
		$this->remove_responsive_control( 'typography_letter_spacing' );

		$this->init_typography_controls();
		$this->init_colors_controls();
		$this->init_spacing_controls();

	}

	protected function init_colors_controls(){
		$this->start_controls_section(
			'section_colors',
			[
				'label' => __( 'Colors', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Content', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'h1_color',
			[
				'label' => __( 'H1', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h1' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'h2_color',
			[
				'label' => __( 'H2', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'h3_color',
			[
				'label' => __( 'H3', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h3' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'h4_color',
			[
				'label' => __( 'H4', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'h5_color',
			[
				'label' => __( 'H5', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h5' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'h6_color',
			[
				'label' => __( 'H6', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} h6' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control( 'li_color', [
				'label' => __( 'li', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} li' => 'color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);
		$this->start_controls_tabs( 'tabs_link_colors' );

		$this->start_controls_tab(
			'tab_link_color_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);
		$this->add_control(
			'a_color',
			[
				'label' => __( 'a', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_color_hover',
			[
				'label' => __( 'Hover', 'one-elements' )
			]
		);

		$this->add_control(
			'a_color_hover',
			[
				'label' => __( 'a', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a:Hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function init_typography_controls()
	{
		$this->start_controls_section(
			'section_typography',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		// $this->add_group_control(
		// 	Group_Control_Typography::get_type(),
		// 	[
		// 		'name' => 'typography',
		// 		'selector' => '{{WRAPPER}}',
		// 		'global' => [
		// 			'default' => Global_Typography::TYPOGRAPHY_TEXT,
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Content', 'one-elements' ),
				'selector' => '{{WRAPPER}}'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'h1',
				'label' => __( 'H1', 'one-elements' ),
				'selector' => '{{WRAPPER}} h1'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'h2',
				'label' => __( 'H2', 'one-elements' ),
				'selector' => '{{WRAPPER}} h2'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'h3',
				'label' => __( 'H3', 'one-elements' ),
				'selector' => '{{WRAPPER}} h3'
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'h4',
				'label' => __( 'H4', 'one-elements' ),
				'selector' => '{{WRAPPER}} h4'
			]
		);
		$this->end_controls_section();
	}

	protected function init_spacing_controls(){
		$this->start_controls_section(
			'section_spacing',
			[
				'label' => __( 'Spacings', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'p_margin',
			[
				'label' => __( 'p', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'h1_margin',
			[
				'label' => __( 'H1', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'h2_margin',
			[
				'label' => __( 'H2', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'h3_margin',
			[
				'label' => __( 'H3', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'h4_margin',
			[
				'label' => __( 'H4', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'img_margin',
			[
				'label' => __( 'img', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ul_margin',
			[
				'label' => __( 'ul', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ol_margin',
			[
				'label' => __( 'ol', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} ol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'li_margin',
			[
				'label' => __( 'li', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Text_Editor() );