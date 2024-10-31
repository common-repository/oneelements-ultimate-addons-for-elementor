<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( !trait_exists( 'One_Elements_Badge_Trait') )
{
	trait One_Elements_Badge_Trait
	{
		function init_content_badge_controls() {
			$this->start_controls_section(
				'_section_badge',
				[
					'label' => __( 'Badge', 'one-elements-pro' ),
				]
			);

			$this->add_control(
				'show_badge',
				[
					'label' => __( 'Show', 'one-elements-pro' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'one-elements-pro' ),
					'label_off' => __( 'Hide', 'one-elements-pro' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'style_transfer' => true,
				]
			);

			$this->add_control(
				'badge_text',
				[
					'label' => __( 'Badge Text', 'one-elements-pro' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'Recommended', 'one-elements-pro' ),
					'placeholder' => __( 'Type badge text', 'one-elements-pro' ),
					'condition' => [
						'show_badge' => 'yes'
					],
					'dynamic' => [
						'active' => true
					]
				]
			);

			$this->add_control(
				'badge_position',
				[
					'label' => __( 'Position', 'one-elements-pro' ),
					'type' => Controls_Manager::CHOOSE,
					'label_block' => false,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'one-elements-pro' ),
							'icon' => 'eicon-h-align-left',
						],
						'right' => [
							'title' => __( 'Right', 'one-elements-pro' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'toggle' => false,
					'default' => 'left',
					'style_transfer' => true,
					'condition' => [
						'show_badge' => 'yes'
					]
				]
			);

			$this->end_controls_section();
		}
		function init_style_badge_controls() {
			$this->start_controls_section(
				'_section_style_badge',
				[
					'label' => __( 'Badge', 'one-elements-pro' ),
					'tab'   => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_badge' => 'yes'
					]
				]
			);

			$this->add_responsive_control(
				'badge_padding',
				[
					'label' => __( 'Padding', 'one-elements-pro' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .one-elements-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'badge_color',
				[
					'label' => __( 'Text Color', 'one-elements-pro' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .one-elements-badge' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'badge_bg_color',
				[
					'label' => __( 'Background Color', 'one-elements-pro' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .one-elements-badge' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'badge_border',
					'selector' => '{{WRAPPER}} .one-elements-badge',
				]
			);

			$this->add_responsive_control(
				'badge_border_radius',
				[
					'label' => __( 'Border Radius', 'one-elements-pro' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .one-elements-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'badge_box_shadow',
					'selector' => '{{WRAPPER}} .one-elements-badge',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'badge_typography',
					'label' => __( 'Typography', 'one-elements-pro' ),
					'selector' => '{{WRAPPER}} .one-elements-badge',
				]
			);

			$this->add_control(
				'badge_translate_toggle',
				[
					'label' => __( 'Offset', 'one-elements-pro' ),
					'type' => Controls_Manager::POPOVER_TOGGLE,
					'return_value' => 'yes',
					'condition' => [
						'show_badge' => 'yes'
					],
				]
			);

			$this->start_popover();

			$this->add_responsive_control(
				'badge_translate_x',
				[
					'label' => __( 'Offset X', 'one-elements-pro' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
						]
					],
					'style_transfer' => true,
					'render_type' => 'ui',
					'condition' => [
						'show_badge' => 'yes'
					],
				]
			);

			$this->add_responsive_control(
				'badge_translate_y',
				[
					'label' => __( 'Offset Y', 'one-elements-pro' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
						]
					],
					'style_transfer' => true,
					'render_type' => 'ui',
					'condition' => [
						'show_badge' => 'yes'
					],
					'selectors' => [
						'(desktop){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px);'
							. 'transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px);',
						'(tablet){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px);'
							. 'transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px);',
						'(mobile){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px);'
							. 'transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px);',
					]
				]
			);

			$this->end_popover();

			$this->add_control(
				'badge_rotate_toggle',
				[
					'label' => __( 'Rotate', 'one-elements-pro' ),
					'type' => Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'show_badge' => 'yes'
					],
				]
			);

			$this->start_popover();

			$this->add_responsive_control(
				'badge_rotate_z',
				[
					'label' => __( 'Rotate Z', 'one-elements-pro' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => -180,
							'max' => 180,
						]
					],
					'style_transfer' => true,
					'render_type' => 'ui',
					'condition' => [
						'show_badge' => 'yes'
					],
					'selectors' => [
						'(desktop){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z.SIZE || 0}}deg);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z.SIZE || 0}}deg);'
							. 'transform:'
							. 'translate({{badge_translate_x.SIZE || 0}}px, {{badge_translate_y.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z.SIZE || 0}}deg);',
						'(tablet){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_tablet.SIZE || 0}}deg);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_tablet.SIZE || 0}}deg);'
							. 'transform:'
							. 'translate({{badge_translate_x_tablet.SIZE || 0}}px, {{badge_translate_y_tablet.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_tablet.SIZE || 0}}deg);',
						'(mobile){{WRAPPER}} .one-elements-badge' =>
							'-ms-transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_mobile.SIZE || 0}}deg);'
							. '-webkit-transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_mobile.SIZE || 0}}deg);'
							. 'transform:'
							. 'translate({{badge_translate_x_mobile.SIZE || 0}}px, {{badge_translate_y_mobile.SIZE || 0}}px) '
							. 'rotateZ({{badge_rotate_z_mobile.SIZE || 0}}deg);'
					]
				]
			);

			$this->end_popover();
			$this->end_controls_section();
		}
		function render_badge($settings = []) {
			if (empty( $settings)) $settings = $this->get_settings_for_display();

			if ( !empty( $settings['show_badge']) && 'yes' === $settings['show_badge'] ) {
				$this->add_render_attribute( 'badge_text', 'class',
					[
						'one-elements-badge',
						'one-elements-badge--' . $settings['badge_position']
					]
				);
				?>
				<span <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings['badge_text'] ); ?></span>
			<?php }
		}

	}
}