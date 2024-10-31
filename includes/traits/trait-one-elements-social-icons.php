<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;

if ( !trait_exists( 'One_Elements_Social_Icons_Trait') )
{
	trait One_Elements_Social_Icons_Trait{

		protected function init_style_icon_controls()
		{

			$this->start_controls_section(
				'one_section_social_style',
				[
					'label' => __( 'Social Icons', 'one-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'tabs_button_style' );

			$this->start_controls_tab(
				'tab_button_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			$this->add_control(
				'icon_color_type',
				[
					'label' => __( 'Color', 'one-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => __( 'Official Color', 'one-elements' ),
						'custom' => __( 'Custom Color', 'one-elements' ),
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'icon_secondary_color',
					'label' => __( 'Custom Icon Color', 'one-elements' ),
					'description'     => __( 'This control changes only Fonts based icons color like Font Awesome icons color.', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon .one-elements-icon__content_icon > *',
					'condition' => [
						'icon_color_type' => 'custom',
					],
				]
			);

			$this->end_controls_tab();

			// hover
			$this->start_controls_tab(
				'tab_button_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			$this->add_control(
				'icon_color_type_hover',
				[
					'label' => __( 'Color', 'one-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => __( 'Official Color', 'one-elements' ),
						'custom' => __( 'Custom Color', 'one-elements' ),
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'icon_secondary_color_hover',
					'label' => __( 'Custom Icon Color', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'description' => __( 'This control changes only Fonts based icons color like Font Awesome icons color.', 'one-elements' ),
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover .one-elements-icon__content_icon > *',
					'condition' => [
						'icon_color_type_hover' => 'custom',
					],
				]
			);

			$this->add_control(
				'social_hover_animation',
				[
					'label' => __( 'Hover Animation', 'one-elements' ),
					'type' => Controls_Manager::HOVER_ANIMATION,
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs(); // end all tabs

			$this->add_responsive_control(
				'one_icon_size',
				[
					'label' => __( 'Icon Size', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};'
					],
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'box_size',
				[
					'label' => __( 'Box Size', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};'
					],
				]
			);

			$this->add_control(
				'social_icon_transition',
				[
					'label' => __( 'Transition Speed', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 1,
							'step' => 0.05
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons' => 'transition: all {{SIZE}}s;',
					],
				]
			);


			$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

			$this->add_responsive_control(
				'one_icon_spacing',
				[
					'label' => __( 'Spacing', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons__single:not(:last-child)' => $icon_spacing
					],
				]
			);

			$this->end_controls_section();

		}

		protected function init_style_background_controls() {

			$this->start_controls_section(
				'social_icon_background_section',
				[
					'label' => __( 'Background', 'one-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'social_icon_tabs_background' );

			$this->start_controls_tab(
				'social_icon_tab_background_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			$this->add_control(
				'icon_bg_type',
				[
					'label' => __( 'Color', 'one-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => __( 'Official Color', 'one-elements' ),
						'custom' => __( 'Custom Color', 'one-elements' ),
					],

				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'background',
					'label' => __( 'Background', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',
					'condition' => [
						'icon_bg_type' => 'custom',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_background_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			$this->add_control(
				'icon_bg_type_hover',
				[
					'label' => __( 'Color', 'one-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => __( 'Official Color', 'one-elements' ),
						'custom' => __( 'Custom Color', 'one-elements' ),
					],

				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'background_hover',
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',
					'condition' => [
						'icon_bg_type_hover' => 'custom',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

		}

		protected function init_style_border_controls() {

			$this->start_controls_section(
				'section_style_border_shadow',
				[
					'label' => __( 'Border & Shadow', 'one-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->start_controls_tabs( 'social_icon_tabs_border' );
				$this->init_style_border_normal_tab_controls();
				$this->init_style_border_hover_tab_controls();
			$this->end_controls_tabs();
			$this->end_controls_section();

		}

		protected function init_style_border_normal_tab_controls() {

			$this->start_controls_tab(
				'social_icon_tab_border_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			$this->add_group_control(
				Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
				[
					'name' => 'border',
					'label' => __('Border', 'one-elements' ),
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon .one-elements-element__regular-state',
				]
			);

			$this->add_control(
				'one_border_radius',
				[
					'label' => __( 'Border Radius', 'one-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon, {{WRAPPER}} .one-elements-social_icons .one-elements-element__regular-state, {{WRAPPER}} .one-elements-social_icons .one-elements-element__hover-state, {{WRAPPER}} .one-elements-social_icons .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
				]
			);

			$this->add_group_control(
				Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon'
				]
			);

			$this->end_controls_tab();
		}

		protected function init_style_border_hover_tab_controls() {

			$this->start_controls_tab(
				'tab_border_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			$this->add_group_control(
				Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
				[
					'name' => 'border_hover',
					'label' => __('Border', 'one-elements' ),
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon .one-elements-element__hover-state'
				]
			);


			$this->add_control(
				'border_radius_hover',
				[
					'label' => __( 'Border Radius', 'one-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover, {{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover .one-elements-element__regular-state, {{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover .one-elements-element__hover-state, {{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
				]
			);

			$this->add_group_control(
				Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow_hover',
					'selector' => '{{WRAPPER}} .one-elements-social_icons .one-elements-icon:hover'
				]
			);

			$this->end_controls_tab();

		}

	}
}