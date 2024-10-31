<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;

if ( !trait_exists( 'One_Elements_Button_Trait') )
{
	trait One_Elements_Button_Trait {

		use One_Elements_Icon_Trait;

		protected function init_content_button_settings($options=[]) {
			$prefix = isset(  $options['prefix']) ?  $options['prefix'] : '';
			$defaults = [
				'prefix' => $prefix,
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [
					'section_icon' => [  $prefix . 'enable_button_icon' => 'yes'],
					'icon_position' => [  $prefix . 'button_type!' => ['circle']],
				],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'section_content_button', $options['excludes']) )
			{
				$this->start_controls_section(
					$options['prefix'].'section_content_button',
					[
						'label' => array_key_exists( 'section_content_button', $options['labels']) ? $options['labels']['section_content_button'] : __( 'Button', 'one-elements' ),
						'condition' => array_key_exists( 'section_content_button', $options['conditions'])
						? $options['conditions']['section_content_button']
						: [ $options['prefix'].'show_button' => 'yes',],
					]
				);
			}

			if (!in_array( 'button_type', $options['excludes']) ){
				$this->add_control(
					$options['prefix'].'button_type',
					[
						'label' => array_key_exists( 'button_type', $options['labels']) ? $options['labels']['button_type'] : __( 'Button Type', 'one-elements' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'regular',
						'options' => [
							'regular' => __( 'Regular', 'one-elements' ),
							'circle' => __( 'Circle', 'one-elements' ),
							'flat' => __( 'Flat', 'one-elements' ),
						],
						'condition' => array_key_exists( 'button_type', $options['conditions'])
							? $options['conditions']['button_type']
							: [],
					]
				);
			}

			if (!in_array( 'button_text', $options['excludes']) )
			{
			    $default_btn_text = !empty( $options['defaults']) && array_key_exists( 'button_text', $options['defaults'])
				    ? $options['defaults']['button_text']
				    : __( 'Click here', 'one-elements' );
				$this->add_control(
					$options['prefix'].'button_text',
					[
						'label' => array_key_exists( 'button_text', $options['labels']) ? $options['labels']['button_text'] : __( 'Text', 'one-elements' ),
						'type' => Controls_Manager::TEXT,
						'dynamic' => [
							'active' => true,
						],
						'default' => $default_btn_text,
						'placeholder' => $default_btn_text,
						'condition' => array_key_exists( 'button_text', $options['conditions'])
							? $options['conditions']['button_text']
							: [  $options['prefix'].'button_type!' => ['circle'] ],
					]
				);
			}

			if (!in_array( 'button_link', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'button_link',
					[
						'label' => array_key_exists( 'button_link', $options['labels']) ? $options['labels']['button_link'] : __( 'Link', 'one-elements' ),
						'type' => Controls_Manager::URL,
						'dynamic' => [
							'active' => true,
						],
						'placeholder' => __( 'https://your-link.com', 'one-elements' ),
						'default' => [
							'url' => '',
						],
						'condition' => array_key_exists( 'button_link', $options['conditions'])
							? $options['conditions']['button_link']
							: [],
					]
				);
			}
			if (!in_array( 'button_align', $options['excludes']))
			{
				$this->add_responsive_control(
					$options['prefix'].'button_align',
					[
						'label' => array_key_exists( 'button_align', $options['labels'])
							? $options['labels']['button_align']
							: __( 'Alignment', 'one-elements' ),
						'condition' => array_key_exists( 'button_align', $options['conditions'])
							? $options['conditions']['button_align']
							: [],
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
					]
				);

			}

			if (!in_array( 'button_size', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'button_size',
					[
						'label' => array_key_exists( 'button_size', $options['labels']) ? $options['labels']['button_size'] : __( 'Size', 'one-elements' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'sm',
						'options' => [
							'xs' => __( 'Extra Small', 'one-elements' ),
							'sm' => __( 'Small', 'one-elements' ),
							'md' => __( 'Medium', 'one-elements' ),
							'lg' => __( 'Large', 'one-elements' ),
							'xl' => __( 'Extra Large', 'one-elements' ),
						],
						'style_transfer' => true,
						'condition' => array_key_exists( 'button_size', $options['conditions'])
							? $options['conditions']['button_size']
							: [],
					]
				);
			}


			if (!in_array( 'enable_button_icon', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'enable_button_icon',
					[
						'label' => array_key_exists( 'enable_button_icon', $options['labels']) ? $options['labels']['enable_button_icon'] : __( 'Enable Button Icon', 'one-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'On', 'one-elements' ),
						'label_off' => __( 'Off', 'one-elements' ),
						'return_value' => 'yes',
						'separator' => 'before',
						'condition' => array_key_exists( 'enable_button_icon', $options['conditions'])
							? $options['conditions']['enable_button_icon']
							: [],
					]
				);
			}

			if (!in_array( 'open_in_lightbox', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'open_in_lightbox',
					[
						'label' => array_key_exists( 'open_in_lightbox', $options['labels']) ? $options['labels']['open_in_lightbox'] : __( 'Open Link in Lightbox', 'one-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'On', 'one-elements' ),
						'label_off' => __( 'Off', 'one-elements' ),
						'return_value' => 'yes',
						'separator' => 'before',
						'condition' => array_key_exists( 'open_in_lightbox', $options['conditions'])
							? $options['conditions']['open_in_lightbox']
							: [],
					]
				);
			}

			if (!in_array( 'lightbox_content_type', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'lightbox_content_type',
					[
						'label' => array_key_exists( 'lightbox_content_type', $options['labels']) ? $options['labels']['lightbox_content_type'] : __( 'Lightbox Content', 'one-elements' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'image',
						'options' => [
							'image' => __( 'Image', 'one-elements' ),
							'video' => __( 'Video', 'one-elements' ),
						],
						'condition' => array_key_exists( 'lightbox_content_type', $options['conditions'])
							? $options['conditions']['lightbox_content_type']
							: [  $options['prefix'].'open_in_lightbox' => 'yes' ],
					]
				);
			}

			if (!in_array( 'content_hosted_on', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'content_hosted_on',
					[
						'label' => array_key_exists( 'content_hosted_on', $options['labels']) ? $options['labels']['content_hosted_on'] : __( 'Video Type', 'one-elements' ),
						'type' => Controls_Manager::SELECT,
						'default' => '',
						'options' => [
							'' => __( 'Embeded like Youtube', 'one-elements' ),
							'hosted' => __( 'Hosted', 'one-elements' ),
						],
						'condition' => array_key_exists( 'content_hosted_on', $options['conditions'])
							? $options['conditions']['content_hosted_on']
							: [
							    $options['prefix'].'open_in_lightbox' => 'yes',
								$options['prefix'].'lightbox_content_type' => ['video']
							],
					]
				);
			}



			if ( !in_array( 'button_css_id', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'button_css_id',
					[
						'label' => array_key_exists( 'button_css_id', $options['labels'])
							? $options['labels']['button_css_id']
							: __( 'Button ID', 'one-elements' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
						'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'one-elements' ),
						'label_block' => false,
						'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this button is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'one-elements' ),
						'separator' => 'before',
						'condition' => array_key_exists( 'button_css_id', $options['conditions'])
							? $options['conditions']['button_css_id']
							: [],

					]
				);
			}


			if ( !in_array( 'section_content_button', $options['excludes']) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_content_button_icon_settings( $options=[] ) {
			// we are using icons from the icon trait, so lets set some conditions to display icon based on button settings
			$prefix = isset(  $options['prefix']) ?  $options['prefix'] : '';
			$defaults = [
				'prefix' => $prefix,
				'excludes' => ['icon_css_id', 'icon_align'],// dont use icon align and icon id from the icon trait for button icon
				'labels' => [
					'section_icon' => __('Button Icon', 'one-elements'),
				],
				'selectors' => [
					'icon_box_size' => [
						'{{WRAPPER}} .one-elements-button .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .one-elements-button.one-elements-button__type-circle' => 'line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // this selector added by K.
					],
					'icon_size' => [
						'{{WRAPPER}} .one-elements-button .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .one-elements-button .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .one-elements-button .one-elements-icon__svg .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};'
					],
				],
				'conditions' => [
					'section_icon' => [  $prefix .'enable_button_icon' => 'yes'],
					'icon_position' => [  $prefix .'button_type!' => ['circle']
					],
				],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			$this->init_content_icon_settings($options);


            if (!in_array( 'icon_position', $options['excludes'])){
	            $this->start_injection( [
		            'type' => 'control',
		            'at'    => 'after',
		            'of'    => $prefix . 'icon',
	            ]);
	            $this->add_control( $prefix . 'icon_position', [
		            'label' => array_key_exists( 'icon_position', $options['labels']) ? $options['labels'][$prefix . 'icon_position'] : __( 'Icon Position', 'one-elements' ),
		            'type' => Controls_Manager::SELECT,
		            'default' => 'left',
		            'options' => [
			            'left' => __( 'Before', 'one-elements' ),
			            'right' => __( 'After', 'one-elements' ),
		            ],
		            'render_type' => 'template',
		            'condition' => [
			            $prefix . 'button_type!' => ['circle'],
		            ],
		            'prefix_class' => '',

	            ]);
	            $this->end_injection();
            }

			if (!in_array( 'icon_indent', $options['excludes'])){
				$this->start_injection( [
					'type' => 'control',
					'at'    => 'after',
					'of'    => $prefix . 'icon_size',
				]);
				$this->add_responsive_control(
					$prefix .'icon_indent',
					[
						'label' => __( 'Icon Spacing', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'range' => [
							'px' => [
								'max' => 50,
							],
						],
						'condition' => array_key_exists( 'icon_indent', $options['conditions']) ? $options['conditions']['icon_indent']:[
							$prefix . 'button_type!' => 'circle',
						],
						'selectors' => array_key_exists( 'icon_indent', $options['selectors']) ? $options['selectors']['icon_indent']:[
							'{{WRAPPER}} .one-elements-button__icon-right .one-elements-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .one-elements-button__icon-left .one-elements-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->end_injection();
			}


		}

		protected function init_style_button_background_settings($options= []) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'button_background_section', $options['excludes']))
			{
				$this->start_controls_section(
					$options['prefix'].'button_background_section',
					[
						'label' => array_key_exists( 'button_background_section', $options['labels']) ? $options['labels']['button_background_section'] : __( 'Button Background', 'one-elements' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => array_key_exists( 'button_background_section', $options['conditions']) ? $options['conditions']['button_background_section'] : [
							$options['prefix'].'button_type!' => ['flat'],
							 $options['prefix'].'show_button' => 'yes'
						]
					]
				);
			}


			$this->start_controls_tabs( $options['prefix'].'button_tabs_background' );

			$this->start_controls_tab(
				$options['prefix'].'button_tab_background_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);
			if ( !in_array( 'button_background', $options['excludes']))
			{
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'button_background',
						'label' => array_key_exists( 'button_background', $options['labels']) ? $options['labels']['button_background'] : __( 'Background', 'one-elements' ),
						'condition' => array_key_exists( 'button_background', $options['conditions']) ? $options['conditions']['button_background'] : [],
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'button_background', $options['selectors']) ? $options['selectors']['button_background'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__regular-state .one-elements-element__state-inner',
					]
				);
			}


			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'button_tab_background_hover',
				[
					'label' => __( 'Hover | Active', 'one-elements' ),
				]
			);
			if ( !in_array( 'button_background_hover', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'button_background_hover',
						'label' => array_key_exists( 'button_background_hover', $options['labels']) ? $options['labels']['button_background_hover'] : __( 'Background', 'one-elements' ),
						'condition' => array_key_exists( 'button_background_hover', $options['conditions']) ? $options['conditions']['button_background_hover'] : [],
						'selector' => array_key_exists( 'button_background_hover', $options['selectors']) ? $options['selectors']['button_background_hover'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__hover-state .one-elements-element__state-inner',
					]
				);
			}


			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array( 'button_background_section', $options['excludes']))
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_button_overlay_settings($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'button_background_overlay', $options['excludes'])){
				$this->start_controls_section(
					$options['prefix'].'button_background_overlay',
					[
						'label' => array_key_exists( 'button_background_overlay', $options['labels'])
							? $options['labels']['button_background_overlay']
							: __( 'Background Overlay', 'one-elements' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => array_key_exists( 'button_background_overlay', $options['conditions'])
							? $options['conditions']['button_background_overlay']
							: [$options['prefix'].'button_type!' => ['flat']],

					]
				);

			}

			$this->start_controls_tabs( $options['prefix'].'tabs_background_overlay' );

			$this->start_controls_tab(
				$options['prefix'].'tab_background_overlay_normal',
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
						'selector' => array_key_exists( 'background_overlay', $options['selectors']) ? $options['selectors']['background_overlay'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__regular-state .one-elements-element__state-inner:after',
					]
				);
			}

			if ( !in_array( 'background_overlay_opacity', $options['excludes'])){
				$this->add_control(
					$options['prefix'].'background_overlay_opacity',
					[
						'label' => array_key_exists( 'background_overlay_opacity', $options['labels'])
							? $options['labels']['background_overlay_opacity']
							: __( 'Opacity', 'one-elements' ),
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
						'condition' => array_key_exists( 'background_overlay_opacity', $options['conditions'])
							? $options['conditions']['background_overlay_opacity']
							: [
								$options['prefix'].'background_overlay_background' => [ 'classic', 'gradient' ],
						],
						'selectors' => array_key_exists( 'background_overlay_opacity', $options['selectors']) ? $options['selectors']['background_overlay_opacity'] : [
							'{{WRAPPER}} .one-elements-button > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
						],
					]
				);

			}

			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_background_overlay_hover',
				[
					'label' => __( 'Hover | Active', 'one-elements' ),
				]
			);
			if ( !in_array( 'hover_background_overlay', $options['excludes']))
			{
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $options['prefix'].'hover_background_overlay',
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'hover_background_overlay', $options['selectors']) ? $options['selectors']['hover_background_overlay'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__hover-state .one-elements-element__state-inner:after',
					]
				);
			}

			if ( !in_array( 'background_overlay_hover_opacity', $options['excludes']))
			{
				$this->add_control(
					$options['prefix'].'background_overlay_hover_opacity',
					[
						'label' => array_key_exists( 'background_overlay_hover_opacity', $options['labels'])
							? $options['labels']['background_overlay_hover_opacity']
							: __( 'Opacity', 'one-elements' ),
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
						'condition' => array_key_exists( 'background_overlay_hover_opacity', $options['conditions'])
							? $options['conditions']['background_overlay_hover_opacity']
							: [
								$options['prefix'].'hover_background_overlay_background' => [ 'classic', 'gradient' ],
						],
						'selectors' => array_key_exists( 'hover_background_overlay_background', $options['selectors']) ? $options['selectors']['hover_background_overlay_background'] : [
							'{{WRAPPER}} .one-elements-button > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
						],
					]
				);

			}

			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array( 'button_background_overlay', $options['excludes'] ) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_button_border_settings($options = []) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'button_border_section', $options['excludes']) )
			{
				$this->start_controls_section(
					$options['prefix'].'button_border_section',
					[
						'label' => array_key_exists( 'button_border_section', $options['labels']) ? $options['labels']['button_border_section'] : __( 'Button Border & Shadow', 'one-elements' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => array_key_exists( 'button_border_section', $options['conditions']) ? $options['conditions']['button_border_section'] : [
							$options['prefix'].'button_type!' => ['flat'],
                            $options['prefix'].'show_button' => 'yes'
						]
					]
				);
			}


			$this->start_controls_tabs( $options['prefix'].'tabs_button_border', [
				'condition' => array_key_exists( 'button_border_section', $options['conditions']) ? $options['conditions']['button_border_section'] : [
					$options['prefix'].'button_type!' => ['flat'],
					$options['prefix'].'show_button' => 'yes'
				]
            ] );

			$this->start_controls_tab(
				$options['prefix'].'tab_button_border_normal',
				[
					'label' => __( 'Normal', 'one-elements' )
				]
			);
			if ( !in_array( 'button_border', $options['excludes'])){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $options['prefix'].'button_border',
						'label' => array_key_exists( 'button_border', $options['labels']) ? $options['labels']['button_border'] : __( 'Button Border', 'one-elements' ),
						'condition' => array_key_exists( 'button_border', $options['conditions']) ? $options['conditions']['button_border'] : [],
						'selector' => array_key_exists( 'button_border', $options['selectors']) ? $options['selectors']['button_border'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__regular-state',
					]
				);
			}


			if ( !in_array( 'button_border_radius', $options['excludes']) )
			{
				$this->add_responsive_control(
					$options['prefix'].'button_border_radius',
					[
						'label' => array_key_exists( 'button_border_radius', $options['labels']) ? $options['labels']['button_border_radius'] : __( 'Border Radius', 'one-elements' ),
						'condition' => array_key_exists( 'button_border_radius', $options['conditions']) ? $options['conditions']['button_border_radius'] : [],
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'button_border_radius', $options['selectors']) ? $options['selectors']['button_border_radius'] : [
							'{{WRAPPER}} .one-elements-button, {{WRAPPER}} .one-elements-button > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
						],
					]
				);
			}


			if ( !in_array( 'button_box_shadow', $options['excludes'])){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $options['prefix'].'button_box_shadow',
						'label' => array_key_exists( 'button_box_shadow', $options['labels']) ? $options['labels']['button_box_shadow'] : __( 'Button Shadow', 'one-elements' ),
						'selector' => array_key_exists( 'button_box_shadow', $options['selectors']) ? $options['selectors']['button_box_shadow'] : '{{WRAPPER}} .one-elements-button',
						'condition' => array_key_exists( 'button_box_shadow', $options['conditions']) ? $options['conditions']['button_box_shadow'] : [
							$options['prefix'].'button_type!' => ['flat'],
						]
					]
				);
			}


			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_button_border_hover',
				[
					'label' => __( 'Hover | Active', 'one-elements' ),
				]
			);

			if ( !in_array( 'button_border_hover', $options['excludes'])  )
			{
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $options['prefix'].'button_border_hover',
						'label' => array_key_exists( 'button_border_hover', $options['labels']) ? $options['labels']['button_border_hover'] : __( 'Button Border', 'one-elements' ),
						'condition' => array_key_exists( 'button_border_hover', $options['conditions']) ? $options['conditions']['button_border_hover'] :[],
						'selector' => array_key_exists( 'button_border_hover', $options['selectors']) ? $options['selectors']['button_border_hover'] : '{{WRAPPER}} .one-elements-button > .one-elements-element__hover-state',
					]
				);
			}


			if ( !in_array( 'button_border_radius_hover', $options['excludes']))
			{
				$this->add_responsive_control(
					$options['prefix'].'button_border_radius_hover',
					[
						'label' => array_key_exists( 'button_border_radius_hover', $options['labels']) ? $options['labels']['button_border_radius_hover'] : __( 'Border Radius', 'one-elements' ),
						'condition' => array_key_exists( 'button_border_radius_hover', $options['conditions']) ? $options['conditions']['button_border_radius_hover'] : [],
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'button_border_radius_hover', $options['selectors'])
							? $options['selectors']['button_border_radius_hover']
							: [
                                '{{WRAPPER}} .one-elements-button:hover, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button:hover > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                            ],
					]
				);
			}


			if ( !in_array( 'hover_button_box_shadow', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $options['prefix'].'hover_button_box_shadow',
						'label' => array_key_exists( 'hover_button_box_shadow', $options['labels']) ? $options['labels']['hover_button_box_shadow'] : __( 'Button Shadow', 'one-elements' ),
						'selector' => array_key_exists( 'hover_button_box_shadow', $options['selectors'])
							? $options['selectors']['hover_button_box_shadow']
							: '{{WRAPPER}} .one-elements-button:hover',
						'condition' => array_key_exists( 'hover_button_box_shadow', $options['conditions']) ? $options['conditions']['hover_button_box_shadow'] : [
							$options['prefix'].'button_type!' => ['flat'],
						]
					]
				);
			}


			$this->end_controls_tab();

			$this->end_controls_tabs();
			if ( !in_array( 'button_border_section', $options['excludes']) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_button_underline_settings($options=[]) {

			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'button_underline_section', $options['excludes']))
			{
				$this->start_controls_section(
					$options['prefix'].'button_underline_section',
					[
						'label' => array_key_exists( 'button_underline_section', $options['labels']) ? $options['labels']['button_underline_section'] : __( 'Button Underline', 'one-elements' ),
						'tab' => Controls_Manager::TAB_STYLE,
						'condition' => array_key_exists( 'button_underline_section', $options['conditions'] ) ? $options['conditions']['button_underline_section'] : [
							$options['prefix'].'button_type' => ['flat'],
							 $options['prefix'].'show_button' => 'yes'
						]
					]
				);
			}


			$this->start_controls_tabs( $options['prefix'].'tabs_button_underline_style', [
				'condition' => array_key_exists( 'button_underline_section', $options['conditions'] ) ? $options['conditions']['button_underline_section'] : [
					$options['prefix'].'button_type' => ['flat'],
					$options['prefix'].'show_button' => 'yes'
				]
            ] );

			$this->start_controls_tab(
				$options['prefix'].'tab_button_underline_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'underline_height', $options['excludes'])){
				$this->add_control(
					$options['prefix'].'underline_height',
					[
						'label' => array_key_exists( 'underline_height', $options['labels']) ? $options['labels']['underline_height'] : __( 'Underline Height', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'underline_height', $options['selectors'])
							? $options['selectors']['underline_height']
							: [
							'{{WRAPPER}} .one-elements-button__underline' => 'height: {{SIZE}}{{UNIT}};', //@TODO; add proper class here after researching markup
						],
						'condition' => array_key_exists( 'underline_height', $options['conditions']) ? $options['conditions']['underline_height'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);
			}

			if ( !in_array( 'underline_width', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'underline_width',
					[
						'label' => array_key_exists( 'underline_width', $options['labels']) ? $options['labels']['underline_width'] : __( 'Underline Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'underline_width', $options['selectors'])
							? $options['selectors']['underline_width']
							: [
							'{{WRAPPER}} .one-elements-button__underline' => 'width: {{SIZE}}{{UNIT}};', //@TODO; add proper class here after researching markup
						],
						'condition' => array_key_exists( 'underline_width', $options['conditions']) ? $options['conditions']['underline_width'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);
			}

			if ( !in_array( 'underline_radius', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'underline_radius',
					[
						'label' => array_key_exists( 'underline_radius', $options['labels']) ? $options['labels']['underline_radius'] : __( 'Underline Radius', 'one-elements' ),
						'condition' => array_key_exists( 'underline_radius', $options['conditions']) ? $options['conditions']['underline_radius'] : [],
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'underline_radius', $options['selectors'])
							? $options['selectors']['underline_radius']
							: [
							'{{WRAPPER}} .one-elements-button__underline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}

			if ( !in_array( 'underline_spacing', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'underline_spacing',
					[
						'label' => array_key_exists( 'underline_spacing', $options['labels']) ? $options['labels']['underline_spacing'] : __( 'Underline Spacing', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'selectors' => array_key_exists( 'underline_spacing', $options['selectors'])
							? $options['selectors']['underline_spacing']
							: [
							'{{WRAPPER}} .one-elements-button .one-elements-element__content > *' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'underline_spacing', $options['conditions']) ? $options['conditions']['underline_spacing'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);
			}

			if ( !in_array( 'underline_color', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'underline_color',
						'label' => array_key_exists( 'underline_color', $options['labels']) ? $options['labels']['underline_color'] : __( 'Underline Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'underline_color', $options['selectors'])
							? $options['selectors']['underline_color']
							: '{{WRAPPER}} .one-elements-button__underline',

						'condition' => array_key_exists( 'underline_color', $options['conditions']) ? $options['conditions']['underline_color'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);

			}

			$this->end_controls_tab();

			$this->start_controls_tab(
				$options['prefix'].'tab_button_underline_hover',
				[
					'label' => __( 'Hover | Active', 'one-elements' ),
				]
			);
			if ( !in_array( 'hover_underline_width', $options['excludes']) )
			{
				$this->add_control(
					$options['prefix'].'hover_underline_width',
					[
						'label' => array_key_exists( 'hover_underline_width', $options['labels']) ? $options['labels']['hover_underline_width'] : __( 'Underline Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 200,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'hover_underline_width', $options['selectors'])
							? $options['selectors']['hover_underline_width']
							: [
							'{{WRAPPER}} .one-elements-button:hover .one-elements-button__underline' => 'width: {{SIZE}}{{UNIT}};', //@TODO; add proper class here after researching markup
						],
						'condition' => array_key_exists( 'hover_underline_width', $options['conditions']) ? $options['conditions']['hover_underline_width'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);

			}

			if ( !in_array( 'hover_underline_color', $options['excludes']) )
			{
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'hover_underline_color',
						'label' => array_key_exists( 'hover_underline_color', $options['labels']) ? $options['labels']['hover_underline_color'] : __( 'Underline Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => array_key_exists( 'hover_underline_color', $options['selectors'])
							? $options['selectors']['hover_underline_color']
							: '{{WRAPPER}} .one-elements-button:hover .one-elements-button__underline',
						'condition' => array_key_exists( 'hover_underline_color', $options['conditions']) ? $options['conditions']['hover_underline_color'] : [
							$options['prefix'].'button_type' => ['flat']
						]
					]
				);

			}

			$this->end_controls_tab();

			$this->end_controls_tabs();

			if ( !in_array( 'button_underline_section', $options['excludes']) )
			{
				$this->end_controls_section();
			}

		}

		protected function init_style_button_icon_settings($options=[]) {
			$prefix = isset(  $options['prefix']) ?  $options['prefix'] : '';

			$defaults = [
				'prefix' => $prefix,
				'excludes' => [],
				'selectors'=>[
					'icon_color' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-icon__content_icon > *',
					'icon_hover_color' => '{{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-icon__content_icon > *',
				],
				'labels' => [
					'section_icon_style' => __('Button Icon', 'one-elements'),
				],
				'conditions' => [
				    'section_icon_style' => [
					    $prefix.'enable_button_icon' => 'yes',
					    $prefix.'icon[value]!' => '',
                    ]
                ],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			$this->init_style_icon_settings($options);
		}

		protected function init_style_button_icon_background_settings($options=[]) {
			$prefix = isset(  $options['prefix']) ?  $options['prefix'] : '';

			$defaults = [
				'prefix' => $prefix,
				'excludes' => [],
				'selectors'=>[
					'icon_background' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',
					'icon_background_hover' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',
				],
				'labels' => [
					'icon_background_section' => __('Button Icon Background', 'one-elements'),
				],
				'conditions' => [
					'icon_background_section' => [
					        $prefix.'enable_button_icon' => 'yes',
                            $prefix.'icon[value]!' => '',
                            $prefix.'button_type!' => ['flat'],
                    ],
				],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			$this->init_style_icon_background_settings($options);
		}

		protected function init_style_button_icon_border_settings($options=[]) {
		    $prefix = isset( $options['prefix']) ? $options['prefix'] : '';
			$defaults = [
				'prefix' => $prefix,
				'excludes' => [],
				'selectors'=>[
					'icon_hover_shadow' => '{{WRAPPER}} .one-elements-button:hover .one-elements-icon',

					'icon_border_radius_hover' =>  [
						'{{WRAPPER}} .one-elements-button:hover .one-elements-icon, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					],
					'icon_shadow' => '{{WRAPPER}} .one-elements-button .one-elements-icon',

				],
				'labels' => [
					'icon_border_section' => __('Button Icon Border & Shadow', 'one-elements'),
				],
				'conditions' => [
				    'icon_border_section' => [
					    $prefix.'enable_button_icon' => 'yes',
					    $prefix.'icon[value]!' => '',
					    $prefix.'button_type!' => ['flat'],
				    ]
                ],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			$this->init_style_icon_border_settings($options);
		}

		/**
		 * It renders button
		 *
		 * @param array $options for example : ['settings' => [widget_settings], 'prefix'=> '', 'add_render_attribute' => true|false]
		 */
		protected function render_button($options=[]) {
		    
		    $settings = isset( $options['settings']) ? $options['settings'] : $this->get_settings_for_display();
		    $button_atts = isset( $options['button_atts']) ? $options['button_atts'] : '';
		    $add_render_attribute = isset( $options['add_render_attribute']) ? $options['add_render_attribute'] : true;
            $prefix = !empty( $options['prefix']) ? $options['prefix'] : $this->prefix; // allow prefix overriding when rendering twice in a widget like practice area where we have two buttons.
			if ( $settings[$prefix.'show_button'] !== 'yes' ) return;

			$html_tag = ! empty( $options['button_tag' ] ) ? $options[ 'button_tag' ] : 'button';
            $open_in_lb = (!empty( $settings[$prefix.'open_in_lightbox']) && 'yes' === $settings[$prefix.'open_in_lightbox']);
            $link_available = ( !empty( $settings[ $prefix . 'button_link' ]['url'] ) || ! empty( $options[ $prefix . 'button_link' ] ) );
			$btn_link = '';

			if ( $link_available ) {
				// give link passed as options priority over settings link
				$btn_link = ! empty( $options[ $prefix . 'button_link' ] ) ? $options[ $prefix . 'button_link' ] : $settings[ $prefix . 'button_link' ]['url'];
            }

			if ( $add_render_attribute ) {

				// lightbox selected.
			    if ( $open_in_lb && $link_available && $btn_link ) {
			        
			        $lb_content_type = !empty( $settings[$prefix.'lightbox_content_type']) ? $settings[$prefix.'lightbox_content_type'] : 'image';
			        $content_hosted_on = !empty( $settings[$prefix.'content_hosted_on']) ? $settings[$prefix.'content_hosted_on'] : '';

			        $data_elementor_lightbox = [];

			        if ( $lb_content_type == 'video' ) {

			        	$data_elementor_lightbox['type'] = 'video';
			        	$data_elementor_lightbox['videoType'] = $content_hosted_on;
			        	$data_elementor_lightbox['url'] = $content_hosted_on == 'hosted' ? $btn_link : $this->get_embed_link( $btn_link );

					    // reset link availability to prevent outputting a tag for lightbox. we will print button tag instead for lightbox
					    $link_available = false; // only for video
			        }

				    $this->add_render_attribute( $prefix . 'button', [
					    'data-elementor-open-lightbox' => "yes",
					    'data-elementor-lightbox' => json_encode( $data_elementor_lightbox )
				    ]);

			    }

				$this->add_render_attribute( $prefix . 'button', [
					'class' => [
						'one-elements-element one-elements-button',
						'one-elements-button__type-' . $settings[ $prefix . 'button_type' ],
						'one-elements-button__icon-' . $settings[ $prefix . 'icon_position' ]
					]
				] );


				$this->add_render_attribute( $prefix . 'button_regular_state', 'class', 'one-elements-element__regular-state' );

				if ( $settings[ $prefix . 'button_border_background' ] == 'gradient' )
				{
					$this->add_render_attribute( $prefix . 'button_regular_state', 'class', 'one-elements-element__border-gradient' );
				}

				$this->add_render_attribute( $prefix . 'button_hover_state', 'class', 'one-elements-element__hover-state' );

				if ( $settings[ $prefix . 'button_border_hover_background' ] == 'gradient' )
				{
					$this->add_render_attribute( $prefix . 'button_hover_state', 'class', 'one-elements-element__border-gradient' );
				}


				if ( $link_available ) {

					$html_tag = 'a';

					$this->add_render_attribute( $prefix . 'button', 'href', $btn_link );
					$this->add_render_attribute( $prefix . 'button', 'class', 'one-elements-button__link' );

					if ( $settings[ $prefix . 'button_link' ]['is_external'] ) {
						$this->add_render_attribute( $prefix . 'button', 'target', '_blank' );
					}

					if ( $settings[ $prefix . 'button_link' ]['nofollow'] ) {
						$this->add_render_attribute( $prefix . 'button', 'rel', 'nofollow' );
					}

				}


				if ( ! empty( $settings[ $prefix . 'button_size' ] ) ) {
					$this->add_render_attribute( $prefix . 'button', 'class', 'one-elements-button__size-' . $settings[ $prefix . 'button_size' ] );
				}


				if ( ! empty( $settings[ $prefix . 'button_size_tablet' ] ) ) {
					$this->add_render_attribute( $prefix . 'button', 'class', 'one-elements-button__tablet-size-' . $settings[ $prefix . 'button_size_tablet' ] );
				}

				if ( ! empty( $settings[ $prefix . 'button_size_mobile' ] ) ) {
					$this->add_render_attribute( $prefix . 'button', 'class', 'one-elements-button__mobile-size-' . $settings[ $prefix . 'button_size_mobile' ] );
				}

				if ( ! empty( $settings[ $prefix . 'hover_animation' ] ) ) {
					$this->add_render_attribute( $prefix . 'button', 'class', 'elementor-animation-' . $settings[ $prefix . 'hover_animation' ] );
				}

				if ( $settings[ $prefix . 'icon' ]['library'] == 'svg' ) {
					$this->add_render_attribute( 'button_icon', 'class', 'one-elements-icon__svg' );
				}

				$this->add_inline_editing_attributes( $prefix . 'button_text', 'none' );

			}

			?>

			<<?php echo esc_attr($html_tag) . ' '; $this->print_render_attribute_string( $prefix . 'button' ); echo $button_atts; //XSS ok. only escaped data will be MANUALLY passed here as extra atts if needed.  ?>>

                <!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string( $prefix . 'button_regular_state' ); ?>>
					<span class="one-elements-element__state-inner"></span>
				</span>

				<?php
				if (
				$settings[ $prefix . 'button_background_hover_background' ]
				|| !empty( $settings['hover_common_button_background_background' ])
				) : ?>
	                <!-- Hover State Background -->
	                <span <?php $this->print_render_attribute_string( $prefix . 'button_hover_state' ); ?>>
						<span class="one-elements-element__state-inner"></span>
					</span>
				<?php endif; ?>

                <!-- Content including Button Icon -->
				<?php $this->render_button_text( $settings, $prefix, $add_render_attribute ); ?>

            </<?php echo esc_attr($html_tag); ?>>

			<?php

		}

		protected function get_embed_link( $url ) {

		    $iframe = wp_oembed_get( $url );

			preg_match('/src="([^"]+)"/', $iframe, $match);
			$url = $match[1];

			return add_query_arg( array(
				'feature' => 'oembed',
				'wmode' => 'opaque',
				'controls' => '1',
				'controls' => '1',
				'loop' => '0',
				'mute' => '0',
				'rel' => '0',
				'modestbranding' => '0',
				'start',
				'end',
			), $url );

		}

		/**
		 * Render button text.
		 *
		 * @param   array $settings
		 * @param   string $prefix
		 * @param   boolean $add_render_attribute
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render_button_text( $settings, $prefix, $add_render_attribute ) {

			if ( $add_render_attribute ) {
				$this->add_render_attribute( $prefix.'button_icon', 'class', 'one-elements-element one-elements-icon' );
				$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );
				if ( $settings[$prefix.'icon_border_gradient_type'] ) {
					$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
				}

				$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

				if ( $settings[$prefix.'icon_border_hover_gradient_type'] ) {
					$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
				}
			}

			?>
			<!-- Button Content -->
			<span class="one-elements-element__content">

            <?php if ( ( $settings[$prefix.'button_type'] == 'circle' || $settings[$prefix.'enable_button_icon'] == 'yes' )
                       && ( !empty($settings[$prefix.'icon']) || !empty($settings[$prefix.'button_icon_svg']) ) ) { ?>

	            <span <?php $this->print_render_attribute_string( $prefix.'button_icon' ); ?>>

					<?php if ( $settings[$prefix.'button_type'] !== 'circle' ) { ?>

						<!-- Regular State Background -->
						<span <?php $this->print_render_attribute_string( $prefix.'button_icon_regular_state' ); ?>>
							<span class="one-elements-element__state-inner"></span>
						</span>

						<?php if ( $settings[$prefix.'icon_background_hover_background'] || $settings[$prefix.'icon_border_hover_background'] ) { ?>
							<!-- Hover State Background -->
							<span <?php $this->print_render_attribute_string( $prefix.'button_icon_hover_state' ); ?>>
								<span class="one-elements-element__state-inner"></span>
							</span>
						<?php } ?>

					<?php } ?>


					<!-- Content including Button Icon -->
					<span class="one-elements-element__content">

					    <span class="one-elements-icon__content_icon">
						    <?php Icons_Manager::render_icon( $settings[$prefix.'icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>

					</span>

				</span>

            <?php } ?>

				<?php if ( $settings[$prefix.'button_type'] !== 'circle' ) : ?>

					<span class="one-elements-button__content_text">

					<?php if (isset( $settings[$prefix.'button_text']) && '' !== $settings[$prefix.'button_text'] ) { echo esc_html(  $settings[$prefix.'button_text']); } ?>

						<?php if ( $settings[$prefix.'button_type'] == 'flat' ) : ?>
							<span class="one-elements-button__underline"></span>
						<?php endif; ?>

				    </span>

				<?php endif; ?>

        	</span>

			<?php

		}

	}
}
