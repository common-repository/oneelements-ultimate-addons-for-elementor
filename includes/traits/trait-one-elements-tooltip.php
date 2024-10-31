<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( !trait_exists( 'One_Elements_Tooltip_Trait') )
{
	trait One_Elements_Tooltip_Trait
	{
		//@Todo; add content method later after discussing the controls.
		protected function init_style_tooltips_controls($options=[]) {

			$prefix = isset(  $options['prefix']) ?  $options['prefix'] : '';
			$defaults = [
				'prefix' => $prefix,
				'excludes' => [],
				'selectors'=>[],
				'labels' => [],
				'conditions' => [],
			];

			$options = one_elements_wp_parse_args_recursive( $options, $defaults);
			if ( !in_array( 'section_style_tooltip', $options['excludes']) ) {
				$this->start_controls_section( 'section_style_tooltip',
					[
						'label' => array_key_exists( 'section_style_tooltip', $options['labels']) ? $options['labels']['section_style_tooltip'] : __( 'Features Tooltip', 'one-elements-pro' ),
						'tab'   => Controls_Manager::TAB_STYLE,
						'condition' => array_key_exists( 'section_style_tooltip', $options['conditions'])
							? $options['conditions']['section_style_tooltip']
							: [],
					]
				);
			}
			if ( !in_array( 'tooltip_padding', $options['excludes']) ) {
				$this->add_control( 'tooltip_padding',
					[
						'label' => __( 'Padding', 'one-elements-pro' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => array_key_exists( 'tooltip_padding', $options['selectors'])
							? $options['selectors']['tooltip_padding'] : [
							'{{WRAPPER}} .one-elements-tooltip-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}


			if ( !in_array( 'tooltip_border', $options['excludes']) ) {
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => $prefix.'tooltip_border',
						'label' => __( 'Border', 'one-elements-pro' ),
						'selector' => array_key_exists( 'tooltip_border', $options['selectors'])
							? $options['selectors']['tooltip_border'] : '{{WRAPPER}} .one-elements-tooltip-text',
					]
				);
			}

			if ( !in_array( 'tooltip_border_radius', $options['excludes']) ) {
				$this->add_control( $prefix.'tooltip_border_radius',
					[
						'label' => __( 'Border Radius', 'one-elements-pro' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%' ],
						'selectors' => array_key_exists( 'tooltip_border_radius', $options['selectors'])
							? $options['selectors']['tooltip_border_radius'] :  [
							'{{WRAPPER}} .one-elements-tooltip-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
			}

			if ( !in_array( 'tooltip_color', $options['excludes']) ) {
				$this->add_control(
					$prefix.'tooltip_color',
					[
						'label' => __( 'Text Color', 'one-elements-pro' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => array_key_exists( 'tooltip_color', $options['selectors'])
							? $options['selectors']['tooltip_color'] : [
							'{{WRAPPER}} .one-elements-tooltip-text' => 'color: {{VALUE}};',
						],
						'separator' => 'before',
					]
				);
			}

			if ( !in_array( 'tooltip_background', $options['excludes']) ) {
				$this->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name' => $prefix.'tooltip_background',
						'label' => __( 'Background', 'one-elements-pro' ),
						'types' => [ 'classic', 'gradient'],
						'selector' => array_key_exists( 'tooltip_background', $options['selectors'])
						? $options['selectors']['tooltip_background'] : '{{WRAPPER}} .one-elements-tooltip-text',
						'separator' => 'before',
						'exclude' => [
							'image',
						],
					]
				);
			}

			if ( !in_array( 'tooltip_typography', $options['excludes']) ) {
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name' => $prefix.'tooltip_typography',
						'selector' => array_key_exists( 'tooltip_typography', $options['selectors'])
							? $options['selectors']['tooltip_typography'] :
							'{{WRAPPER}} .one-elements-tooltip-text',
					]
				);
			}

			if ( !in_array( $prefix.'section_style_tooltip', $options['excludes']) )
			{
				$this->end_controls_section();
			}
		}

		function render_tooltip( $settings = [] ) {
			// @todo; update markup later
			if( !empty( $settings['tooltip_text']) ) : ?>
				<span class="one-elements-tooltip-text"><?php echo esc_html( $settings['tooltip_text'] ); ?></span>
			<?php endif;
		}
	}
}