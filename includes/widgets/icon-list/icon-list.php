<?php
namespace OneElements\Includes\Widgets\IconList;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Icon_List;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Traits\One_Elements_Divider_Trait;
use OneElements\Includes\Traits\One_Elements_Icon_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Icon_List extends Widget_Icon_List {

    use One_Elements_Divider_Trait;
    use One_Elements_Icon_Trait;
	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'txd-icon-list';
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
		return 'one-elements-widget-eicon eicon-bullet-list';
	}

	private function init_style_list_controls()
	{
		$this->start_controls_section(
			'section_icon_list_style',
			[
				'label' => __( 'List', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_align',
			[
				'label' => __( 'Alignment', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'one-elements' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'one-elements' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'one-elements' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_list_icon_typography',
				'selector' => '{{WRAPPER}} .one-elements-icon_list--text',
			]
		);
		$this->start_controls_tabs( 'icon_box_colors' );
		$this->start_controls_tab(
			'icon_list_colors_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_list_color',
				'label' => __( 'Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_list--text',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_list_colors_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_box_color_hover',
				'label' => __( 'Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_list:hover .one-elements-icon_list--text',

			]
		);
		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs

		$this->add_control(
			'icon_list_icon_alignment',
			[
				'label' => __( 'Icon Alignment', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'oee__align-middle' => __( 'Middle', 'one-elements' ),
					'oee__align-top' => __( 'Top', 'one-elements' ),
				],
				'default' => 'oee__align-middle',
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'icon_list_icon_space',
			[
				'label' => __( 'Icon vertical space', 'one-elements' ),
				'description' => __( 'Adjust the top space of icon', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_list_text_indent',
			[
				'label' => __( 'Text Indent', 'one-elements' ),
				'description' => __( 'Specify the space between the icon and content.', 'one-elements' ),

				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 2,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon' => is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_list_space_between',
			[
				'label' => __( 'Space Between', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-icon-list--layout-traditional .one-elements-icon_lists > .one-elements-element:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-icon-list--layout-inline .one-elements-icon_lists' => 'margin: calc(-{{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.elementor-icon-list--layout-inline .one-elements-icon_lists > .one-elements-element' => 'margin: calc({{SIZE}}{{UNIT}}/2);',
				],
			]
		);

		$this->add_responsive_control(
			'icon_list_padding',
			[
				'label' => __( 'Padding', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_list_divider',
			[
				'label' => __( 'Show Divider', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'one-elements' ),
				'label_on' => __( 'Yes', 'one-elements' ),
				'separator' => 'before',
			]
		);
		$this->add_control( 'icon_list_divider_heading', [
			'label' => __( 'Divider Options', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => ['icon_list_divider' => 'yes'],
		]);
        // show divider from the divider trait without its section.
        $this->init_content_divider_controls([
            'excludes' => ['one_divider', 'show_secondary_divider', 'divider_inner_gap'],
            'conditions' => [
                'divider_height' => ['icon_list_divider' => 'yes'],
                'one_divider_tabs' => ['icon_list_divider' => 'yes'],
            ],
        ]);

		$this->end_controls_section();

	}
	private function init_style_list_background_controls() {

		$this->start_controls_section(
			'icon_list_background_section',
			[
				'label' => __( 'List Background', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'icon_list_tabs_background' );

		$this->start_controls_tab(
			'icon_list_tab_background_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_list_background',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_list > .one-elements-element__regular-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_list_tab_background_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_list_background_hover',
				'selector' => '{{WRAPPER}} .one-elements-icon_list > .one-elements-element__hover-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_list_border_controls() {

		$this->start_controls_section(
			'icon_list_border_section',
			[
				'label' => __( 'List Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon_list_border' );

		$this->start_controls_tab(
			'tab_icon_list_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'icon_list_border',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_list > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'icon_list_border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_list, {{WRAPPER}} .one-elements-icon_list > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon_list > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon_list > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'icon_list_box_shadow',
				'label' => __( 'Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_list',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_list_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'icon_list_border_hover',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_list > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'icon_list_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_list:hover, {{WRAPPER}} .one-elements-icon_list:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon_list:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon_list:hover > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_icon_list_box_shadow',
				'label' => __( 'Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_list:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_icon_controls($options = []) {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->init_content_icon_settings($options);

		$this->init_style_icon_settings($options);

		$this->end_controls_section();

	}


	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$defaults = [
			'excludes' => ['section_icon', 'icon', 'icon_css_id', 'icon_align', 'section_icon_style', 'view'],// dont use icon align and icon id from the icon trait for button icon
			// reset nearly all conditions as we need controls for icons coming from wordpress post editor
			'conditions' => [
				'icon_box_size' => [],
				'icon_size' => [],
				'icon_color' => [],
				'icon_hover_color' => [],
				'icon_hover_animation' => [],
				'icon_transition' => [],
				'svg_icon_color' => [],
				'svg_icon_color_hover' => [],
			],
			'labels' => [
			    'svg_icon_color' => __('SVG Icon Color', 'one-elements'),
			    'svg_icon_color_hover' => __('SVG Icon Color', 'one-elements'),
            ],
			'selectors' => [
			    'icon_hover_color' => '{{WRAPPER}} .one-elements-icon_list:hover .one-elements-icon .one-elements-icon__content_icon > *',
            ]
		];
		// content tab
		parent::_register_controls();// bring elementor icon list controls
		// remove all style controls/sections from original icon list
        $this->remove_control( 'section_icon_list');
        $this->remove_control( 'section_icon_style');
        $this->remove_control( 'section_text_style');
		$this->remove_control( 'divider');
		$this->remove_control( 'divider_style');
		$this->remove_control( 'divider_weight');
		$this->remove_control( 'divider_width');
		$this->remove_control( 'divider_height');
		$this->remove_control( 'divider_color');
		$this->remove_responsive_control( 'icon_size');
		//$this->remove_control( 'view');

        // Add our new style controls
		// style tab
        $this->init_style_list_controls();
        $this->init_style_list_background_controls();
        $this->init_style_list_border_controls();

        $this->init_style_icon_controls($defaults);
        $this->init_style_icon_background_settings($defaults);
        $this->init_style_icon_border_settings($defaults);

	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon_list', 'class', 'one-elements-icon_lists' );
		$this->add_render_attribute( 'list_item', 'class', 'one-elements-element one-elements-icon_list');

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'one-elements-inline-icon-lists' );
			$this->add_render_attribute( 'list_item', 'class', 'one-elements-inline-icon-list-item' );
		}

		$this->add_render_attribute( 'icon_list_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_list_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_list_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'icon_list_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_list_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_list_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		// ==========================================================
		$this->add_render_attribute( 'icon_list_single_element_content', [
			'class' => [
				'one-elements-element__content',
				$settings['icon_list_icon_alignment']
			]
		]);

		// Icon Related Attributes
		$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		?>
        <ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
			<?php
			foreach ( $settings['icon_list'] as $index => $item ) :
				$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );
				$repeater_icon_key = $this->get_repeater_setting_key( 'selected_icon', 'icon_list', $index );// same as implode( '.', [ 'icon_list', $index, 'selected_icon' ] );

				$this->add_render_attribute( $repeater_setting_key, 'class', 'one-elements-icon_list--text' );
				$this->add_inline_editing_attributes( $repeater_setting_key );

				$this->add_render_attribute( $repeater_icon_key, 'class', 'one-elements-element one-elements-icon' );

				// for svg icon
				if ( $item['selected_icon']['library'] == 'svg' ) {
					$this->add_render_attribute( $repeater_icon_key, 'class', 'one-elements-icon__svg' );
				}

				?>
                <li <?php $this->print_render_attribute_string( 'list_item'); ?> >
                    <!-- Regular State Background -->
                    <span <?php $this->print_render_attribute_string('icon_list_regular_state'); ?>>
                        <span class="one-elements-element__state-inner"></span>
                    </span>

	                <?php if ( $settings['icon_list_background_hover_background'] || $settings['icon_list_border_hover_background'] ) : ?>
                        <!-- Hover State Background -->
                        <span <?php $this->print_render_attribute_string('icon_list_hover_state'); ?>>
                            <span class="one-elements-element__state-inner"></span>
                        </span>
	                <?php endif; ?>
					<?php
					if ( ! empty( $item['link']['url'] ) ) {
						$link_key = 'link_' . $index;

						$this->add_render_attribute( $link_key, 'href', $item['link']['url'] );

						if ( $item['link']['is_external'] ) {
							$this->add_render_attribute( $link_key, 'target', '_blank' );
						}

						if ( $item['link']['nofollow'] ) {
							$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
						}

						echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
					}

					?>

                    <span <?php $this->print_render_attribute_string('icon_list_single_element_content'); ?>>

						<?php if ( ! empty( $item['selected_icon']['value'] ) ) : ?>
	                        <!-- Content including Icon -->
	                        <span <?php $this->print_render_attribute_string( $repeater_icon_key ); ?>>

	                            <!-- Regular State Background -->
	                            <span <?php $this->print_render_attribute_string('icon_regular_state'); ?>>
	                                <span class="one-elements-element__state-inner"></span>
	                            </span>

								<?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
	                                <!-- Hover State Background -->
	                                <span <?php $this->print_render_attribute_string('icon_hover_state'); ?>>
	                                    <span class="one-elements-element__state-inner"></span>
	                                </span>
								<?php endif; ?>

	                            <!-- Content including Icon -->
	                            <span class="one-elements-element__content">
	                                <span class="one-elements-icon__content_icon">
	                                    <?php Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
	                                </span>
	                            </span>

	                        </span>
						<?php endif; ?>

						<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>><?php echo esc_html( $item['text']); ?></span>

                    </span>

					<?php if ( ! empty( $item['link']['url'] ) ) {
                        echo '</a>';
                    }

                    // RENDER DIVIDER MARKUP FROM THE TRAIT
                    if ( !empty( $settings['icon_list_divider']) && 'yes' == $settings['icon_list_divider'] ) {
                        $this->render_divider();
                    }

                    ?>

                </li>
			<?php
			endforeach;
			?>
        </ul>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
        <#
        view.addRenderAttribute( 'icon_list', 'class', 'one-elements-icon_lists' );
        view.addRenderAttribute( 'list_item', 'class', 'one-elements-element one-elements-icon_list' );
        if ( 'inline' == settings.view ) {
            view.addRenderAttribute( 'icon_list', 'class', 'one-elements-inline-icon-lists' );
            view.addRenderAttribute( 'list_item', 'class', 'one-elements-inline-icon-list-item' );
        }

        view.addRenderAttribute( 'icon_list_regular_state', 'class', 'one-elements-element__regular-state' );

        if ( settings['icon_list_border_background'] == 'gradient' ) {
        	view.addRenderAttribute( 'icon_list_regular_state', 'class',  'one-elements-element__border-gradient' );
        }

        view.addRenderAttribute( 'icon_list_hover_state', 'class', 'one-elements-element__hover-state' );

        if ( settings['icon_list_border_hover_background'] == 'gradient' ) {
            view.addRenderAttribute( 'icon_list_hover_state', 'class', 'one-elements-element__border-gradient' );
        }

        view.addRenderAttribute( 'icon_list_single_element_content', {
			'class': [
				'one-elements-element__content',
				settings['icon_list_icon_alignment']
			]
        });

        // Icon Related Attributes

        view.addRenderAttribute( 'icon_regular_state', 'class', 'one-elements-element__regular-state' );

        if ( settings['icon_border_background'] == 'gradient' ) {
        view.addRenderAttribute( 'icon_regular_state', 'class',  'one-elements-element__border-gradient' );

        }

        view.addRenderAttribute( 'icon_hover_state', 'class', 'one-elements-element__hover-state' );

        if ( settings['icon_border_hover_background'] == 'gradient' ) {
        view.addRenderAttribute( 'icon_hover_state', 'class', 'one-elements-element__border-gradient' );
        }

        var iconsHTML = {};

        #>
        <# if ( settings.icon_list ) { #>
        <ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>

        <# _.each( settings.icon_list, function( item, index ) {

        var iconTextKey = view.getRepeaterSettingKey( 'text', 'icon_list', index );

        view.addRenderAttribute( iconTextKey, 'class', 'one-elements-icon_list--text' );

        view.addInlineEditingAttributes( iconTextKey );

        if( item.selected_icon && item.selected_icon.library === 'svg'){
            var one_svg_class = 'one-elements-icon__svg';
        }

        #>

        <li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
        <!-- Regular State Background -->
        <span {{{ view.getRenderAttributeString( 'icon_list_regular_state' ) }}}>
            <span class="one-elements-element__state-inner"></span>
        </span>

        <!-- Hover State Background -->
        <span {{{ view.getRenderAttributeString( 'icon_list_hover_state' ) }}}>
            <span class="one-elements-element__state-inner"></span>
        </span>
        <# if ( item.link && item.link.url ) { #>
        <a href="{{ item.link.url }}">
        <# } #>
	    <span {{{ view.getRenderAttributeString( 'icon_list_single_element_content' ) }}}>
            <# if ( item.selected_icon.value ) { #>
	            <span class="one-elements-element one-elements-icon {{one_svg_class}}">

	                <!-- Regular State Background -->
	                <span {{{ view.getRenderAttributeString( 'icon_regular_state' ) }}}>
	                    <span class="one-elements-element__state-inner"></span>
	                </span>
	                <!-- Hover State Background -->
	                <span {{{ view.getRenderAttributeString( 'icon_hover_state' ) }}}>
	                    <span class="one-elements-element__state-inner"></span>
	                </span>

        			<span class="one-elements-element__content">
	                <!-- Content including Icon -->
	                    <span class="one-elements-icon__content_icon">
	                        <#  iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
	                    if ( iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
	                        {{{ iconsHTML[ index ].value }}}
	                    <# } #>
	                    </span>
	                </span>

	            </span>
            <# } #>
        	<span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.text }}}</span>
        </span>
        <# if ( item.link && item.link.url ) { #>
        </a>
        <# } #>


<!--        divider-->
        <# if ( settings['icon_list_divider'] && 'yes' === settings['icon_list_divider'] ) { #>
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
        <# } #>

        </li>
        <#
        } ); //end foreach #>
        </ul>
        <#	} #>

		<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Icon_List() );
