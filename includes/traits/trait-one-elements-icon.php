<?php
namespace OneElements\Includes\Traits;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;

if ( !trait_exists( 'One_Elements_Icon_Trait') )
{
	trait One_Elements_Icon_Trait {

		protected function init_content_icon_settings($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			if ( !in_array( 'section_icon', $options['excludes']) ){
				$this->start_controls_section(
					$options['prefix'].'section_icon',
					[
						'label' => array_key_exists( 'section_icon', $options['labels']) ? $options['labels']['section_icon'] : __( 'Icon', 'one-elements' ),
						'condition' => array_key_exists( 'section_icon', $options['conditions']) ? $options['conditions']['section_icon'] : [],
					]
				);
			}


			if ( !in_array( 'icon', $options['excludes'])){
				$this->add_control(
					$options['prefix'].'icon',
					[
						'label' => array_key_exists( 'icon', $options['labels']) ? $options['labels']['icon'] : __( 'Icon', 'one-elements' ),
						'type' => Controls_Manager::ICONS,
						'default' => [
							'value' => 'fas fa-star',
							'library' => 'fa-solid',
						],
						'condition' => array_key_exists( 'icon', $options['conditions']) ? $options['conditions']['icon'] : [],
					]
				);
			}

			if ( !in_array( 'icon_align', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'icon_align',
					[
						'label' => array_key_exists( 'icon_align', $options['labels']) ? $options['labels']['icon_align'] : __( 'Alignment', 'one-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'left'    => [
								'title' => __( 'Left', 'one-elements' ),
								'icon' => 'fa fa-align-left',
							],
							'center' => [
								'title' => __( 'Center', 'one-elements' ),
								'icon' => 'fa fa-align-center',
							],
							'right' => [
								'title' => __( 'Right', 'one-elements' ),
								'icon' => 'fa fa-align-right',
							],
							'justify' => [
								'title' => __( 'Justified', 'one-elements' ),
								'icon' => 'fa fa-align-justify',
							],
						],
						'prefix_class' => 'elementor%s-align-', //here %s = mobile/tablet/desktop. eg. elementor-{mobile}-align-{value}
						'default' => '',
						'condition' => array_key_exists( 'icon_align', $options['conditions']) ? $options['conditions']['icon_align'] : [],
					]
				);

			}



			if ( !in_array( 'icon_box_size', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'icon_box_size',
					[
						'label' => array_key_exists( 'icon_box_size', $options['labels']) ? $options['labels']['icon_box_size'] : __( 'Icon Box Size', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'max' => 500,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'icon_box_size', $options['selectors']) ? $options['selectors']['icon_box_size'] : [
							'{{WRAPPER}} .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
						],
						'condition' => array_key_exists( 'icon_box_size', $options['conditions']) ? $options['conditions']['icon_box_size'] : [ $options['prefix'].'icon[value]!'=> ''],
					]
				);
			}


			if ( !in_array( 'icon_size', $options['excludes']) ) {
				$this->add_responsive_control(
					$options['prefix'].'icon_size',
					[
						'label' => array_key_exists( 'icon_size', $options['labels']) ? $options['labels']['icon_size'] : __( 'Icon Size', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'max' => 800,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'icon_size', $options['selectors']) ? $options['selectors']['icon_size'] : [
							'{{WRAPPER}} .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .one-elements-icon__svg .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};'
						],
						'condition' => array_key_exists( 'icon_size', $options['conditions']) ? $options['conditions']['icon_size'] : [ $options['prefix'].'icon[value]!'=> ''],
					]
				);

			}

			if ( !in_array( 'view', $options['excludes']) ){
				$this->add_control(
					$options['prefix'].'view',
					[
						'label' => __( 'View', 'one-elements' ),
						'type' => Controls_Manager::HIDDEN,
						'default' => 'traditional',
					]
				);
			}


			if ( !in_array( 'icon_css_id', $options['excludes']) ){
				$this->add_control(
					$options['prefix'].'icon_css_id',
					[
						'label' => array_key_exists( 'icon_css_id', $options['labels']) ? $options['labels']['icon_css_id'] : __( 'Icon ID', 'one-elements' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
						'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'one-elements' ),
						'label_block' => false,
						'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this icon is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'one-elements' ),
						'separator' => 'before',
						'condition' => array_key_exists( 'icon_css_id', $options['conditions']) ? $options['conditions']['icon_css_id'] : [ $options['prefix'].'icon[value]!'=> ''],
					]
				);
			}


			if ( !in_array( 'section_icon', $options['excludes']) ){
				$this->end_controls_section();
			}
		}

		protected function init_content_icon_presets($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'section_icon_presets', $options['excludes']) )
			{
				$this->start_controls_section(
					$options['prefix'].'section_icon_presets',
					[
						'label' => array_key_exists( 'section_icon_presets', $options['labels']) ? $options['labels']['section_icon_presets'] : __( 'Preset Styles', 'one-elements' ),
						'condition' => array_key_exists( 'section_icon_presets', $options['conditions']) ? $options['conditions']['section_icon_presets'] : [],
					]
				);
			}
			if ( !in_array( 'preset_styles', $options['excludes']))
			{
				$this->add_control(
					$options['prefix'].'preset_styles',
					[
						'label' => array_key_exists( 'preset_styles', $options['labels']) ? $options['labels']['preset_styles'] : __( 'Icon Presets', 'one-elements' ),
						'condition' => array_key_exists( 'preset_styles', $options['conditions']) ? $options['conditions']['preset_styles'] : [],
						'type' => Controls_Manager::SELECT,
						'options' => [
							'style_1' => __( 'Style 1', 'one-elements' ),
							'style_2' => __( 'Style 2', 'one-elements' ),
							'style_3' => __( 'Style 3', 'one-elements' ),
							'style_4' => __( 'Style 4', 'one-elements' ),
							'style_5' => __( 'Style 5', 'one-elements' )
						]
					]
				);
			}

			if ( !in_array( 'section_icon_presets', $options['excludes']) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_icon_settings($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'section_icon_style', $options['excludes']) )
			{
				$this->start_controls_section(
					$options['prefix'].'section_icon_style',
					[
						'label' => array_key_exists( 'section_icon_style', $options['labels']) ? $options['labels']['section_icon_style'] : __( 'Icon', 'one-elements' ),
						'condition' => array_key_exists( 'section_icon_style', $options['conditions']) ? $options['conditions']['section_icon_style'] : [],
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
			}

			$this->start_controls_tabs( $options['prefix'].'tabs_icon_style' );

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'icon_color', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $options['prefix'].'icon_color',
						'label' => array_key_exists( 'icon_color', $options['labels']) ? $options['labels']['icon_color'] : __( 'Icon Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'icon_color', $options['selectors']) ? $options['selectors']['icon_color'] : '{{WRAPPER}} .one-elements-icon .one-elements-icon__content_icon > *',
						'condition' => array_key_exists( 'icon_color', $options['conditions'])
						? $options['conditions']['icon_color'] : [],
					]
				);

			}

			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			if ( !in_array( 'icon_hover_color', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $options['prefix'].'icon_hover_color',
						'label' => array_key_exists( 'icon_hover_color', $options['labels']) ? $options['labels']['icon_hover_color'] : __( 'Icon Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'icon_hover_color', $options['selectors']) ? $options['selectors']['icon_hover_color'] : '{{WRAPPER}} .one-elements-icon:hover .one-elements-icon__content_icon > *',
						'condition' => array_key_exists( 'icon_hover_color', $options['conditions']) ? $options['conditions']['icon_hover_color'] : [],
					]
				);

			}


			if ( !in_array( 'icon_hover_animation', $options['excludes']) ) {

				$this->add_control(
					$options['prefix'].'icon_hover_animation',
					[
						'label' => array_key_exists( 'icon_hover_animation', $options['labels']) ? $options['labels']['icon_hover_animation'] : __( 'Hover Animation', 'one-elements' ),
						'condition' => array_key_exists( 'icon_hover_animation', $options['conditions']) ? $options['conditions']['icon_hover_animation'] : [],
						'type' => Controls_Manager::HOVER_ANIMATION,
					]
				);

			}


			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array( 'icon_transition', $options['excludes']) ) {

				$this->add_control(
					$options['prefix'].'icon_transition',
					[
						'label' => array_key_exists( 'icon_transition', $options['labels']) ? $options['labels']['icon_transition'] : __( 'Transition Speed', 'one-elements' ),
						'condition' => array_key_exists( 'icon_transition', $options['conditions']) ? $options['conditions']['icon_transition'] : [],
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'max' => 1,
								'step' => 0.05
							],
						],
						'separator' => 'before',
						'selectors' => [
							'{{WRAPPER}} .one-elements-icon' => 'transition: all {{SIZE}}s;',
						],
					]
				);

			}

			if ( !in_array( 'section_icon_presets', $options['excludes']) ) {
				$this->end_controls_section();
			}

		}

		protected function init_style_icon_background_settings( $options=[] ) {

			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'icon_background_section', $options['excludes']) ) {

				$this->start_controls_section(
					$options['prefix'].'icon_background_section',
					[
						'label' =>  array_key_exists( 'icon_background_section', $options['labels']) ? $options['labels']['icon_background_section'] : __( 'Icon Background', 'one-elements' ),
						'condition' =>  array_key_exists( 'icon_background_section', $options['conditions']) ? $options['conditions']['icon_background_section'] : [],
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);

			}


			$this->start_controls_tabs( $options['prefix'].'icon_tabs_background' );

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_background_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'icon_background', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'icon_background',
						'label' => array_key_exists( 'icon_background', $options['labels']) ? $options['labels']['icon_background'] : __( 'Background', 'one-elements' ),
						'condition' => array_key_exists( 'icon_background', $options['conditions']) ? $options['conditions']['icon_background'] : [],
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'icon_background', $options['selectors']) ? $options['selectors']['icon_background'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',
					]
				);

			}

			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_background_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			if ( !in_array( 'icon_background_hover', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'icon_background_hover',
						'selector' => array_key_exists( 'icon_background_hover', $options['selectors']) ? $options['selectors']['icon_background_hover'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',
						'condition' =>  array_key_exists( 'icon_background_hover', $options['conditions']) ? $options['conditions']['icon_background_hover'] : [],
					]
				);

			}

			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array( 'icon_background_section', $options['excludes']) ) {
				$this->end_controls_section();
			}

		}

		protected function init_style_icon_background_overlay_settings($options=[]) {

			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'icon_background_overlay_section', $options['excludes']) )
			{
				$this->start_controls_section(
					$options['prefix'].'icon_background_overlay_section',
					[
						'label' => array_key_exists( 'icon_background_overlay_section', $options['labels']) ? $options['labels']['icon_background_overlay_section'] : __( 'Icon Background Overlay', 'one-elements' ),
						'condition' => array_key_exists( 'icon_background_overlay_section', $options['conditions']) ? $options['conditions']['icon_background_overlay_section'] : [],
						'tab' => Controls_Manager::TAB_STYLE
					]
				);
			}

			$this->start_controls_tabs( $options['prefix'].'icon_tabs_background_overlay' );

			$this->start_controls_tab(
				$options['prefix'].'icon_tab_background_overlay_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'background_overlay', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'background_overlay',
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'background_overlay', $options['selectors']) ? $options['selectors']['background_overlay'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner:after',
						'condition' => array_key_exists( 'background_overlay', $options['conditions']) ? $options['conditions']['background_overlay'] : [],
					]
				);
			}


			if ( !in_array( 'background_overlay_opacity', $options['excludes']))
			{
				$this->add_control(
					$options['prefix'].'background_overlay_opacity',
					[
						'label' => array_key_exists( 'background_overlay_opacity', $options['labels']) ? $options['labels']['background_overlay_opacity'] : __( 'Opacity', 'one-elements' ),
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
						'condition' => array_key_exists( 'background_overlay_opacity', $options['conditions']) ? $options['conditions']['background_overlay_opacity'] :  [
							$options['prefix'].'background_overlay_background' => [ 'classic', 'gradient' ],
						],
						'selectors' => array_key_exists( 'background_overlay_opacity', $options['selectors']) ? $options['selectors']['background_overlay_opacity'] : [
							'{{WRAPPER}} .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
						],
					]
				);
			}


			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'icon_tab_background_overlay_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			if ( !in_array( 'hover_background_overlay', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'hover_background_overlay',
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'hover_background_overlay', $options['selectors']) ? $options['selectors']['hover_background_overlay'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner:after',
						'condition' => array_key_exists( 'hover_background_overlay', $options['conditions']) ? $options['conditions']['hover_background_overlay'] : [],
					]
				);
			}


			if ( !in_array( 'background_overlay_hover_opacity', $options['excludes']))
			{
				$this->add_control(
					$options['prefix'].'background_overlay_hover_opacity',
					[
						'label' => array_key_exists( 'background_overlay_hover_opacity', $options['labels']) ? $options['labels']['background_overlay_hover_opacity'] : __( 'Opacity', 'one-elements' ),
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
						'condition' => array_key_exists( 'background_overlay_hover_opacity', $options['conditions']) ? $options['conditions']['background_overlay_hover_opacity'] : [
							$options['prefix'].'hover_background_overlay_background' => [ 'classic', 'gradient' ],
						],
						'selectors' => array_key_exists( 'background_overlay_hover_opacity', $options['selectors']) ? $options['selectors']['background_overlay_hover_opacity'] : [
							'{{WRAPPER}} .one-elements-icon:hover .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
						],
					]
				);
			}


			$this->end_controls_tab();

			$this->end_controls_tabs();
			if ( !in_array( 'icon_background_overlay_section', $options['excludes']) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_icon_border_settings($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults );

			if ( !in_array( 'icon_border_section', $options['excludes']) ) {

				$this->start_controls_section(
					$options['prefix'].'icon_border_section',
					[
						'label' => array_key_exists( 'icon_border_section', $options['labels']) ? $options['labels']['icon_border_section'] : __( 'Icon Border & Shadow', 'one-elements' ),
						'condition' => array_key_exists( 'icon_border_section', $options['conditions']) ? $options['conditions']['icon_border_section'] : [],
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);

			}

			$this->start_controls_tabs( $options['prefix'].'tabs_icon_border', [
				'condition' => array_key_exists( 'icon_border_section', $options['conditions']) ? $options['conditions']['icon_border_section'] : [],
			] );

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_border_normal',
				[
					'label' => __( 'Normal', 'one-elements' )
				]
			);

			if ( !in_array( 'icon_border', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $options['prefix'].'icon_border',
						'label' => array_key_exists( 'icon_border', $options['labels']) ? $options['labels']['icon_border'] : __( 'Color', 'one-elements' ),
						'condition' => array_key_exists( 'icon_border', $options['conditions']) ? $options['conditions']['icon_border'] : [],
						'selector' => array_key_exists( 'icon_border', $options['selectors']) ? $options['selectors']['icon_border'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__regular-state',
						'separator' => 'before',
						'render_type' => 'template'
					]
				);

			}


			if ( !in_array( 'icon_border_radius', $options['excludes']) ) {

				$this->add_responsive_control(
					$options['prefix'].'icon_border_radius',
					[
						'label' => array_key_exists( 'icon_border_radius', $options['labels']) ? $options['labels']['icon_border_radius'] : __( 'Border Radius', 'one-elements' ),
						'condition' => array_key_exists( 'icon_border_radius', $options['conditions']) ? $options['conditions']['icon_border_radius'] : [],
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'icon_border_radius', $options['selectors']) ? $options['selectors']['icon_border_radius'] : [
							'{{WRAPPER}} .one-elements-icon, {{WRAPPER}} .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
						],
					]
				);

			}

			if ( !in_array( 'icon_shadow', $options['excludes']) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $options['prefix'].'icon_shadow',
						'selector' => array_key_exists( 'icon_shadow', $options['selectors']) ? $options['selectors']['icon_shadow'] : '{{WRAPPER}} .one-elements-icon',
						'condition' => array_key_exists( 'icon_shadow', $options['conditions']) ? $options['conditions']['icon_shadow'] : [],
					]
				);
			}

			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_icon_border_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			if ( !in_array( 'icon_border_hover', $options['excludes']) ) {
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $options['prefix'].'icon_border_hover',
						'label' => array_key_exists( 'icon_border_hover', $options['labels']) ? $options['labels']['icon_border_hover'] : __( 'Color', 'one-elements' ),
						'condition' => array_key_exists( 'icon_border_hover', $options['conditions']) ? $options['conditions']['icon_border_hover'] : [],
						'selector' => array_key_exists( 'icon_border_hover', $options['selectors']) ? $options['selectors']['icon_border_hover'] : '{{WRAPPER}} .one-elements-icon .one-elements-element__hover-state',
						'separator' => 'before',
						'render_type' => 'template'
					]
				);
			}

			if ( !in_array( 'icon_border_radius_hover', $options['excludes']) ) {
				$this->add_responsive_control(
					$options['prefix'].'icon_border_radius_hover',
					[
						'label' => array_key_exists( 'icon_border_radius_hover', $options['labels']) ? $options['labels']['icon_border_radius_hover'] : __( 'Border Radius', 'one-elements' ),
						'condition' => array_key_exists( 'icon_border_radius_hover', $options['conditions']) ? $options['conditions']['icon_border_radius_hover'] : [],
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'icon_border_radius_hover', $options['selectors']) ? $options['selectors']['icon_border_radius_hover'] : [
							'{{WRAPPER}} .one-elements-icon:hover, {{WRAPPER}} .one-elements-icon:hover .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon:hover .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon:hover .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
						],
					]
				);
			}

			if ( !in_array( 'icon_hover_shadow', $options['excludes']) ) {
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $options['prefix'].'icon_hover_shadow',
						'selector' => array_key_exists( 'icon_hover_shadow', $options['selectors'] ) ? $options['selectors']['icon_hover_shadow'] : '{{WRAPPER}} .one-elements-icon:hover',
						'condition' => array_key_exists( 'icon_hover_shadow', $options['conditions']) ? $options['conditions']['icon_hover_shadow'] : [],
					]
				);
			}

			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array('icon_border_section', $options['excludes']) ) {
				$this->end_controls_section();
			}

		}

	}
}