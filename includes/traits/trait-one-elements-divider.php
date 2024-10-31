<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;

if ( !trait_exists( 'One_Elements_Divider_Trait') )
{
	trait One_Elements_Divider_Trait
	{
		protected function init_content_divider_controls($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);

			if ( !in_array( 'one_divider', $options['excludes']) )
			{
				$this->start_controls_section($options['prefix'].'one_divider',
					[
						'label' => array_key_exists( 'one_divider', $options['labels']) ? $options['labels']['one_divider'] : __( 'Divider', 'one-elements' ),
						'condition' => array_key_exists( 'one_divider', $options['conditions']) ? $options['conditions']['one_divider'] : [],
					]
				);
			}

			if ( !in_array( 'show_secondary_divider', $options['excludes']) ){
				$this->add_control($options['prefix'].'show_secondary_divider',
					[
						'label' => array_key_exists( 'show_secondary_divider', $options['labels']) ? $options['labels']['show_secondary_divider'] : __( 'Enable Secondary divider', 'one-elements' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'Show', 'one-elements' ),
						'label_off' => __( 'Hide', 'one-elements' ),
						'return_value' => 'yes',
						'condition' => array_key_exists( 'show_secondary_divider', $options['conditions']) ? $options['conditions']['show_secondary_divider'] : [],
					]
				);
			}

			if ( !in_array( 'divider_height', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_height',
					[
						'label' =>  array_key_exists( 'divider_height', $options['labels']) ? $options['labels']['divider_height'] : __( 'Height', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'divider_height', $options['selectors']) ? $options['selectors']['divider_height'] : [
							'{{WRAPPER}} .one-elements-divider__primary' => 'height: {{SIZE}}{{UNIT}};'
						],
						'condition' => array_key_exists( 'divider_height', $options['conditions']) ? $options['conditions']['divider_height'] : [],
						'separator' => 'after'
					]
				);
			}


			$this->start_controls_tabs( $options['prefix'].'one_divider_tabs', [
				'condition' => array_key_exists( 'one_divider_tabs', $options['conditions']) ? $options['conditions']['one_divider_tabs'] : [],
			] );

			$this->start_controls_tab($options['prefix'].'one_element_divider_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'divider_color', $options['excludes']) ){
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'divider_color',
						'label' => array_key_exists( 'divider_color', $options['labels']) ? $options['labels']['divider_color'] : __( 'Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],// if we add  'extra', or leave the types arg empty, we will have border style width etc with color
						'default' => '',
						'selector' => array_key_exists( 'divider_color', $options['selectors']) ? $options['selectors']['divider_color'] : '{{WRAPPER}} .one-elements-divider__primary .one-elements-element__regular-state',
						'condition' => array_key_exists( 'divider_color', $options['conditions']) ? $options['conditions']['divider_color'] : [],
					]
				);
			}

			if ( !in_array( 'divider_width', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_width',
					[
						'label' => array_key_exists( 'divider_width', $options['labels']) ? $options['labels']['divider_width'] : __( 'Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ '%', 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],
						'default' => [
							'size' => 100,
							'unit' => '%',
						],
						'tablet_default' => [
							'unit' => '%',
						],
						'mobile_default' => [
							'unit' => '%',
						],
						'selectors' => array_key_exists( 'divider_width', $options['selectors']) ? $options['selectors']['divider_width'] : [
							'{{WRAPPER}} .one-elements-divider__primary' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider_width', $options['conditions']) ? $options['conditions']['divider_width'] : [],
					]
				);
			}

			if ( !in_array( 'divider_indent', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_indent',
					[
						'label' => array_key_exists( 'divider_indent', $options['labels']) ? $options['labels']['divider_indent'] : __( 'Indent', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],

						'selectors' => array_key_exists( 'divider_indent', $options['selectors']) ? $options['selectors']['divider_indent'] : [
							'{{WRAPPER}} .one-elements-divider__primary' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider_indent', $options['conditions']) ? $options['conditions']['divider_indent'] : [],
						'separator' => 'after'
					]
				);
			}

			if ( !in_array( 'divider_gap', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_gap',
					[
						'label' => array_key_exists( 'divider_gap', $options['labels']) ? $options['labels']['divider_gap'] : __( 'Gap', 'one-elements' ),
						'description' => __( 'Specify the space between the divider and content.', 'one-elements' ),

						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 15,
						],
						'range' => [
							'px' => [
								'min' => 2,
								'max' => 50,
							],
						],
						'selectors' => array_key_exists( 'divider_gap', $options['selectors']) ? $options['selectors']['divider_gap'] :                         [ '{{WRAPPER}} .one-elements-divider__wrapper' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};', ],
						'condition' => array_key_exists( 'divider_gap', $options['conditions']) ? $options['conditions']['divider_gap'] : [],
					]
				);
			}

			if ( !in_array( 'divider_inner_gap', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_inner_gap',
					[
						'label' => array_key_exists( 'divider_inner_gap', $options['labels']) ? $options['labels']['divider_inner_gap'] : __( 'Inner Gap', 'one-elements' ),
						'description' => __( 'Specify the space between the primary and secondary divider.', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],

						'selectors' => array_key_exists( 'divider_inner_gap', $options['selectors']) ? $options['selectors']['divider_inner_gap'] : [
							'{{WRAPPER}} .one-elements-divider__secondary' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider_gap', $options['conditions']) ? $options['conditions']['divider_gap'] : [$options['prefix'].'show_secondary_divider' => 'yes']

					]
				);
			}

			if ( !in_array( 'divider_align', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_align',
					[
						'label' => array_key_exists( 'divider_align', $options['labels']) ? $options['labels']['divider_align'] : __( 'Alignment', 'one-elements' ),
						'type' => Controls_Manager::CHOOSE,
						'prefix_class' => 'elementor%s-align-', //@TODO; we should use our own class
						'options' => [
							'left' => [
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
						],
						'condition' => array_key_exists( 'divider_align', $options['conditions']) ? $options['conditions']['divider_align'] : [],
					]
				);
			}

			if ( !in_array( 'divider_view', $options['excludes']) ){
				$this->add_control($options['prefix'].'divider_view',
					[
						'label' => __( 'View', 'one-elements' ),
						'type' => Controls_Manager::HIDDEN,
						'default' => 'traditional',
					]
				);
			}


			$this->end_controls_tab(); // end divider normal

			$this->start_controls_tab(
				$options['prefix'].'one_divider_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);
			if ( !in_array( 'divider_color_hover', $options['excludes']) ){
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'divider_color_hover',
						'label' => array_key_exists( 'divider_color_hover', $options['labels']) ? $options['labels']['divider_color_hover'] : __( 'Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'default' => '',
						'selector' => array_key_exists( 'divider_color_hover', $options['selectors']) ? $options['selectors']['divider_color_hover'] : '{{WRAPPER}}:hover .one-elements-divider__primary .one-elements-element__hover-state',
						'condition' => array_key_exists( 'divider_color_hover', $options['conditions']) ? $options['conditions']['divider_color_hover'] : [],
					]
				);
			}

			if ( !in_array( 'divider_width_hover', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_width_hover',
					[
						'label' => array_key_exists( 'divider_width_hover', $options['labels']) ? $options['labels']['divider_width_hover'] : __( 'Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ '%', 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],
						'tablet_default' => [
							'unit' => '%',
						],
						'mobile_default' => [
							'unit' => '%',
						],
						'selectors' => array_key_exists( 'divider_width_hover', $options['selectors']) ? $options['selectors']['divider_width_hover'] : [
							'{{WRAPPER}}:hover .one-elements-divider__primary' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider_width_hover', $options['conditions']) ? $options['conditions']['divider_width_hover'] : [],
					]
				);
			}

			if ( !in_array( 'divider_indent_hover', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider_indent_hover',
					[
						'label' =>  array_key_exists( 'divider_indent_hover', $options['labels']) ? $options['labels']['divider_indent_hover'] : __( 'Indent', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],

						'selectors' => array_key_exists( 'divider_indent_hover', $options['selectors']) ? $options['selectors']['divider_indent_hover'] : [
							'{{WRAPPER}}:hover .one-elements-divider__primary' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider_indent_hover', $options['conditions']) ? $options['conditions']['divider_indent_hover'] : [],
					]
				);
			}

			if ( !in_array( 'one_divider_transition', $options['excludes']) ){
				$this->add_control($options['prefix'].'one_divider_transition',
					[
						'label' => array_key_exists( 'one_divider_transition', $options['labels']) ? $options['labels']['one_divider_transition'] : __( 'Transition Speed', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'max' => 1,
								'step' => 0.05
							],
						],
						'separator' => 'before',
						'selectors' => array_key_exists( 'one_divider_transition', $options['selectors']) ? $options['selectors']['one_divider_transition'] : [
							'{{WRAPPER}}:hover .one-elements-divider__wrapper' => 'transition: all {{SIZE}}s;',
						],
						'condition' => array_key_exists( 'one_divider_transition', $options['conditions']) ? $options['conditions']['one_divider_transition'] : [],
					]
				);
			}


			$this->end_controls_tab();// end divider hover
			$this->end_controls_tabs();

			if ( !in_array( 'one_divider', $options['excludes']) ){
				$this->end_controls_section();
			}
		}
		protected function init_content_secondary_divider_controls($options=[]) {
			$defaults = [
				'prefix' => '',
				'excludes' => [],
				'labels' => [],
				'selectors'=>[],
				'conditions' => [],
			];
			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			if ( !in_array( 'one_divider2', $options['excludes']) ){
				$this->start_controls_section($options['prefix'].'one_divider2',
					[
						'label' => array_key_exists( 'one_divider2', $options['labels']) ? $options['labels']['one_divider2'] : __( 'Secondary Divider', 'one-elements' ),
						'condition' => array_key_exists( 'one_divider2', $options['conditions']) ? $options['conditions']['one_divider2'] : [$options['prefix'].'show_secondary_divider' => 'yes']
					]
				);
			}

			if ( !in_array( 'divider2_height', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider2_height',
					[
						'label' => array_key_exists( 'divider2_height', $options['labels']) ? $options['labels']['divider2_height'] : __( 'Height', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
							'size' => 1,
						],
						'range' => [
							'px' => [
								'min' => 1,
								'max' => 100,
							],
						],
						'selectors' => array_key_exists( 'divider2_height', $options['selectors']) ? $options['selectors']['divider2_height'] : [
							'{{WRAPPER}} .one-elements-divider__secondary' => 'height: {{SIZE}}{{UNIT}};'
						],
						'condition' => array_key_exists( 'divider2_height', $options['conditions']) ? $options['conditions']['divider2_height'] : [],
						'separator' => 'after'
					]
				);
			}

			$this->start_controls_tabs($options['prefix']. 'one_divider2_tabs' );

			$this->start_controls_tab(
				$options['prefix'].'one_element_divider2_normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			if ( !in_array( 'divider2_color', $options['excludes']) ){
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'divider2_color',
						'label' => array_key_exists( 'divider2_color', $options['labels']) ? $options['labels']['divider2_color'] : __( 'Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],// if we add  'extra', or leave the types arg empty, we will have border style width etc with color
						'default' => '',
						'selector' => array_key_exists( 'divider2_color', $options['selectors']) ? $options['selectors']['divider2_color'] : '{{WRAPPER}} .one-elements-divider__secondary .one-elements-element__regular-state',
						'condition' => array_key_exists( 'divider2_color', $options['conditions']) ? $options['conditions']['divider2_color'] : [],
					]
				);
			}
			if ( !in_array( 'divider2_width', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider2_width',
					[
						'label' => array_key_exists( 'divider2_width', $options['labels']) ? $options['labels']['divider2_width'] : __( 'Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ '%', 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],
						'default' => [
							'size' => 100,
							'unit' => '%',
						],
						'tablet_default' => [
							'unit' => '%',
						],
						'mobile_default' => [
							'unit' => '%',
						],
						'selectors' => array_key_exists( 'divider2_width', $options['selectors']) ? $options['selectors']['divider2_width'] : [
							'{{WRAPPER}} .one-elements-divider__secondary' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider2_width', $options['conditions']) ? $options['conditions']['divider2_width'] : [],
					]
				);
			}

			if ( !in_array( 'divider2_indent', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider2_indent',
					[
						'label' => array_key_exists( 'divider2_indent', $options['labels']) ? $options['labels']['divider2_indent'] : __( 'Indent', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],

						'selectors' => array_key_exists( 'divider2_indent', $options['selectors']) ? $options['selectors']['divider2_indent'] : [
							'{{WRAPPER}} .one-elements-divider__secondary' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider2_indent', $options['conditions']) ? $options['conditions']['divider2_indent'] : [],
						'separator' => 'after'
					]
				);
			}
			if ( !in_array( 'divider2_view', $options['excludes']) ){
				$this->add_control($options['prefix'].'divider2_view',
					[
						'label' => __( 'View', 'one-elements' ),
						'type' => Controls_Manager::HIDDEN,
						'default' => 'traditional',
					]
				);
			}

			$this->end_controls_tab(); // end divider normal

			$this->start_controls_tab(
				$options['prefix'].'one_divider2_hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);
			if ( !in_array( 'divider2_color_hover', $options['excludes']) ){
				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $options['prefix'].'divider2_color_hover',
						'label' => array_key_exists( 'divider2_color_hover', $options['labels']) ? $options['labels']['divider2_color_hover'] : __( 'Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'default' => '',
						'selector' => array_key_exists( 'divider2_color_hover', $options['selectors']) ? $options['selectors']['divider2_color_hover'] : '{{WRAPPER}}:hover .one-elements-divider__secondary .one-elements-element__hover-state',
						'condition' => array_key_exists( 'divider2_color_hover', $options['conditions']) ? $options['conditions']['divider2_color_hover'] : [],
					]
				);
			}
			if ( !in_array( 'divider2_width_hover', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider2_width_hover',
					[
						'label' => array_key_exists( 'divider2_width_hover', $options['labels']) ? $options['labels']['divider2_width_hover'] : __( 'Width', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ '%', 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],
						'tablet_default' => [
							'unit' => '%',
						],
						'mobile_default' => [
							'unit' => '%',
						],
						'selectors' => array_key_exists( 'divider2_width_hover', $options['selectors']) ? $options['selectors']['divider2_width_hover'] : [
							'{{WRAPPER}}:hover .one-elements-divider__secondary' => 'width: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider2_width_hover', $options['conditions']) ? $options['conditions']['divider2_width_hover'] : [],
					]
				);
			}
			if ( !in_array( 'divider2_indent_hover', $options['excludes']) ){
				$this->add_responsive_control(
					$options['prefix'].'divider2_indent_hover',
					[
						'label' =>  array_key_exists( 'divider2_indent_hover', $options['labels']) ? $options['labels']['divider2_indent_hover'] : __( 'Indent', 'one-elements' ),
						'type' => Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'max' => 1000,
							],
						],

						'selectors' => array_key_exists( 'divider2_indent_hover', $options['selectors']) ? $options['selectors']['divider2_indent_hover'] : [
							'{{WRAPPER}}:hover .one-elements-divider__secondary' => 'margin-left: {{SIZE}}{{UNIT}};',
						],
						'condition' => array_key_exists( 'divider2_indent_hover', $options['conditions']) ? $options['conditions']['divider2_indent_hover'] : [],
					]
				);
			}

			$this->end_controls_tab();// end divider hover

			$this->end_controls_tabs();
			if ( !in_array( 'one_divider2', $options['excludes']) )
			{

				$this->end_controls_section();
			}
		}

		protected function render_divider( $settings=[] )
		{
			$settings = !empty( $settings) ? $settings : $this->get_settings_for_display();
			?>
			<div class="one-elements-divider__wrapper">

				<div class="one-elements-element one-elements-divider one-elements-divider__primary">
					<div class="one-elements-element__regular-state"></div>
					<div class="one-elements-element__hover-state"></div>
				</div>


				<?php if (!empty($settings['show_secondary_divider']) &&  'yes' == $settings['show_secondary_divider'] ): ?>
					<!-- We need this empty div -->
					<div></div>
					<div class="one-elements-element one-elements-divider one-elements-divider__secondary">
						<div class="one-elements-element__regular-state"></div>
						<div class="one-elements-element__hover-state"></div>
					</div>
				<?php endif ?>

			</div>

			<?php

		}
		protected function _content_template_divider(){
			?>
			<div class="one-elements-divider__wrapper">

				<div class="one-elements-element one-elements-divider one-elements-divider__primary">
					<div class="one-elements-element__regular-state"></div>
					<div class="one-elements-element__hover-state"></div>
				</div>

				<# if ( settings.show_secondary_divider && 'yes' === settings.show_secondary_divider ) { #>
				<div></div>
				<div class="one-elements-element one-elements-divider one-elements-divider__secondary">
					<div class="one-elements-element__regular-state"></div>
					<div class="one-elements-element__hover-state"></div>
				</div>

				<# } #>

			</div>
			<?php
		}
	}
}