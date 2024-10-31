<?php

namespace OneElements\Includes\Widgets\Button;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Button;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Traits\One_Elements_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor button widget.
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 * @since 1.0.0
 */
class Widget_OneElements_Button extends Widget_Button {

	use One_Elements_Button_Trait;
	/**
	 * Prefix for trait's control.
	 * @return string Prefix for trait's control.
	 * @since 1.0.0
	 */
	protected $prefix = ''; // this prefix is intentionally empty string.

	/**
	 * Get widget name.
	 * Retrieve button widget name.
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'one-elements-button';
	}

	/**
	 * Get widget categories.
	 * Retrieve the list of categories the button widget belongs to.
	 * Used to determine where to display the widget in the editor.
	 * @return array Widget categories.
	 * @since  2.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ 'one_elements' ];
	}

	/**
	 * Get widget icon.
	 * Retrieve social icons widget icon.
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'one-elements-widget-eicon eicon-button';
	}

	/**
	 * Register button widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _register_controls() {


		$this->init_content_button_settings();

		$this->start_injection( [
			'type' => 'section',
			'at'   => 'start',
			'of'   => 'section_content_button',
		] );
		// we need this hidden field for condition
		$this->add_control( 'show_button', [
				'label'   => __( 'Show Button', 'one-elements' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'yes',
			] );
		$this->end_injection();
		$this->init_content_button_icon_settings();
		$this->init_content_custom_actions_settings();

		$this->init_style_button_settings();

		$this->init_style_button_background_settings();

		$this->init_style_button_overlay_settings();

		$this->init_style_button_border_settings();

		$this->init_style_button_underline_settings();

		$this->init_style_button_icon_settings();

		$this->init_style_button_icon_background_settings();

		$this->init_style_button_icon_border_settings();

		$this->init_style_special_effects_settings();

	}

	protected function init_content_custom_actions_settings() {

		$this->start_controls_section( 'button_custom_actions_section', [
				'label' => __( 'Custom Actions', 'one-elements' ),
			] );

		$this->add_control( 'btn_ca_event_type', [
				'label'   => __( 'Event', 'one-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'click' => __( 'Click', 'one-elements' ),
					'hover' => __( 'Hover', 'one-elements' ),
				],
				'default' => 'click',
			] );
		$this->add_control( 'btn_ca_action_type', [
				'label'   => __( 'Action Type', 'one-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'slider' => __( 'Slider', 'one-elements' ),
					'custom' => __( 'Custom', 'one-elements' ),
				],
			] );


		$this->add_control( 'btn_ca_slider_id', [
				'label'     => __( 'Slider ID', 'one-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'btn_ca_action_type' => [ 'slider' ],
				],
			] );

		$this->add_control( 'btn_ca_slider_action', [
				'label'     => __( 'Action', 'one-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'next' => __( 'Next', 'one-elements' ),
					'prev' => __( 'Prev', 'one-elements' ),
				],
				'default'   => 'next',
				'condition' => [
					'btn_ca_slider_id!'  => '',
					'btn_ca_action_type' => [ 'slider' ],
				],
			] );

		$this->add_control( 'btn_ca_custom_function', [
				'label'       => __( 'Custom Function', 'one-elements' ),
				'description' => __( 'Enter a Custom Function that will handle the event', 'one-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '', 'one-elements' ),
				'placeholder' => __( 'Enter a custom function name', 'one-elements' ),
				'condition'   => [
					'btn_ca_action_type' => [ 'custom' ],
				],
			] );

		$this->end_controls_section();

	}

	protected function init_style_button_settings() {

		$this->start_controls_section( 'section_button_style', [
				'label' => __( 'Button', 'one-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
				'name'      => 'typography',
				'selector'  => '{{WRAPPER}} .one-elements-button .one-elements-button__content_text',
				'condition' => [
					'button_type!' => [ 'circle' ],
				],
			] );

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab( 'tab_button_normal', [
				'label' => __( 'Normal', 'one-elements' ),
			] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
				'name'      => 'text_color',
				'label'     => __( 'Text Color', 'one-elements' ),
				'types'     => [
					'classic',
					'gradient',
				],
				'selector'  => '{{WRAPPER}} .one-elements-button .one-elements-button__content_text',
				'condition' => [
					'button_type!' => [ 'circle' ],
				],
			] );

		$this->add_responsive_control( 'text_padding', [
				'label'      => __( 'Padding', 'one-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [
					'{{WRAPPER}} .one-elements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_type!' => [
						'flat',
						'circle',
					],
				],
			] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_button_hover', [
				'label' => __( 'Hover', 'one-elements' ),
			] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
				'name'      => 'hover_text_color',
				'label'     => __( 'Text Color', 'one-elements' ),
				'types'     => [
					'classic',
					'gradient',
				],
				'condition' => [
					'button_type!' => [ 'circle' ],
				],
				'selector'  => '{{WRAPPER}} .one-elements-button:hover .one-elements-button__content_text',
			] );

		$this->add_responsive_control( 'hover_text_padding', [
				'label'      => __( 'Padding', 'one-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [
					'px',
					'em',
					'%',
				],
				'selectors'  => [
					'{{WRAPPER}} .one-elements-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'button_type!' => [
						'flat',
						'circle',
					],
				],
			] );

		$this->add_control( 'button_hover_animation', [
				'label' => __( 'Hover Animation', 'one-elements' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
				//'prefix_class' => 'elementor-animation-' // it would a class in the parent div.
			] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'button_transition', [
				'label'     => __( 'Transition Speed', 'one-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .one-elements-button' => 'transition-duration: {{SIZE}}s;',
				],
			] );

		$this->end_controls_section();

	}

	protected function init_style_special_effects_settings() {

		$this->start_controls_section( 'button_icon_special_effects_section', [
				'label' => __( 'Special Effects', 'one-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			] );

		$this->add_control( 'button_icon_effects', [
				'label'        => __( 'Effects', 'one-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					''           => __( 'None', 'one-elements' ),
					'pulse'      => __( 'Pulse', 'one-elements' ),
					'2x-pulse'   => __( '2x Pulse', 'one-elements' ),
					'2x-pulse-2' => __( '2x Pulse 2', 'one-elements' ),
					'3x-pulse'   => __( '3x Pulse', 'one-elements' ),
					'3x-pulse-2' => __( '3x Pulse 2', 'one-elements' ),
				],
				'prefix_class' => 'oee-button-effect--',
				'condition'    => [// 's_style_event_type' => 'click',
				],
				'description' => __('This effect will work only if you have set a button icon.', 'one-elements')

		] );

		$this->add_control( 'button_icon_effects_color', [
				'label'     => __( 'Effect Color', 'one-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:not(.oee-button-effect--3x-pulse-2) .one-elements-button.one-elements-button__type-circle:before,
	                {{WRAPPER}}:not(.oee-button-effect--3x-pulse-2) .one-elements-button:not(.one-elements-button__type-circle) .one-elements-icon:before,

					{{WRAPPER}} .one-elements-button.one-elements-button__type-circle:after,
					{{WRAPPER}} .one-elements-button:not(.one-elements-button__type-circle) .one-elements-icon:after,

					{{WRAPPER}} .one-elements-button.one-elements-button__type-circle > .one-elements-element__content:before,
					{{WRAPPER}} .one-elements-button:not(.one-elements-button__type-circle) .one-elements-icon > .one-elements-element__content:before,

					{{WRAPPER}} .one-elements-button.one-elements-button__type-circle > .one-elements-element__content:after,
					{{WRAPPER}} .one-elements-button:not(.one-elements-button__type-circle) .one-elements-icon > .one-elements-element__content:after' => ' border-color: {{value}}'
				],
				'condition' => [
					'button_icon_effects!' => "",
				],
			] );

		$this->add_control( 'button_icon_effects_color2', [
				'label'     => __( 'Secondary Effect Color', 'one-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.oee-button-effect--3x-pulse-2 .one-elements-button.one-elements-button__type-circle:before,
					{{WRAPPER}}.oee-button-effect--3x-pulse-2 .one-elements-button:not(.one-elements-button__type-circle) .one-elements-icon:before' => 'background-color: {{value}}',
				],
				'condition' => [
					'button_icon_effects' => [
						'3x-pulse-2',
					],
				],
			] );

		$this->end_controls_section();

	}

	/**
	 * Render button widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings         = $this->get_settings_for_display();
		$data_event       = ! empty( $settings['btn_ca_event_type'] ) ? $settings['btn_ca_event_type'] : null; //click/hover
		$data_action_type = ! empty( $settings['btn_ca_action_type'] ) ? $settings['btn_ca_action_type'] : null; //slider/custom
		$ca_slider_id     = ! empty( $settings['btn_ca_slider_id'] ) ? $settings['btn_ca_slider_id'] : null;//slider id:number
		$ca_slider_action = ! empty( $settings['btn_ca_slider_action'] ) ? $settings['btn_ca_slider_action'] : null; // slider action: next or prev
		$cf               = ! empty( $settings['btn_ca_custom_function'] ) ? $settings['btn_ca_custom_function'] : null;
		$data_action      = 'slider' == $data_action_type ? $ca_slider_action : $cf;

		$button_atts = '';
		if ( ! empty( $data_event ) && ! empty( $data_action_type ) && ! empty( $data_action ) ) {

			$this->add_render_attribute( $this->prefix . 'button', 'class', 'oee__has_custom_event' );

			$custom_event_data = [];

			$custom_event_data['event']       = esc_attr( $data_event );
			$custom_event_data['actionType']  = esc_attr( $data_action_type );
			$custom_event_data['eventAction'] = esc_attr( $data_action );

			if ( $data_action_type == 'slider' ) {
				$custom_event_data['sliderID'] = esc_attr( $ca_slider_id );
			}

			$custom_event_data = json_encode( $custom_event_data );

			// build additional button attribute
			$button_atts = " data-custom-event='" . $custom_event_data . "'";

		}

		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( $this->prefix . 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );

		}


		$this->add_render_attribute( $this->prefix . 'wrapper', [
				'class' => [
					'one-elements-button__wrapper',
					$settings['_css_classes'],
				],
			] );

		if ( isset( $settings['button_css_id']) && '' !== $settings['button_css_id'] ) {
			$this->add_render_attribute( $this->prefix . 'button', 'id', $settings['button_css_id'] );
		}

		?>
        <div <?php $this->print_render_attribute_string( $this->prefix . 'wrapper' ); ?>>
			<?php
			$this->render_button( compact( 'settings', 'button_atts' ) ); ?>
        </div>
		<?php
	}


	/**
	 * Render button widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 * @since  1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
        <#
        let iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );

        let openInLb = ( settings.open_in_lightbox && 'yes' === settings.open_in_lightbox );
        let linkAvailable = ( settings.button_link && settings.button_link.url );
        let btnLink = linkAvailable ? settings.button_link.url : '';
        let lbContentType = settings.lightbox_content_type ? settings.lightbox_content_type : 'image';
        let lbContentHostedOn = settings.content_hosted_on ? settings.content_hosted_on : '';

        if ( openInLb && btnLink ) {
			
			let dataElementorLightBox = {};

			if ( lbContentType == 'video' ) {
				
				dataElementorLightBox.type = 'video';
				dataElementorLightBox.videoType = lbContentHostedOn
				dataElementorLightBox.url = lbContentHostedOn == 'hosted' ? btnLink : ''; // @todo make an AJAX call to get_embed_link method to get the desired link

	            // reset link availability to prevent outputting a tag for lightbox. we will print button tag instead for lightbox
	            linkAvailable= false;
			}

            view.addRenderAttribute( 'button', {
	            'data-elementor-open-lightbox' : "yes",
	            'data-elementor-lightbox' : JSON.stringify(dataElementorLightBox)
            });
        }

        view.addRenderAttribute( 'wrapper', {
            'class': [ 'one-elements-button__wrapper', settings._css_classes ]
        });

        view.addRenderAttribute( 'button', {
            'class': [
                'one-elements-element one-elements-button',
                'one-elements-button__type-' + settings.button_type,
                'one-elements-button__icon-' + settings.icon_position
            ]
        });


        view.addRenderAttribute( 'button_regular_state', {
            'class': 'one-elements-element__regular-state'
        });

        if ( ! settings.button_border_width_classic && settings.button_border_background == 'gradient' ) {

            view.addRenderAttribute( 'button_regular_state', {
                'class': 'one-elements-element__border-gradient'
            });

        }

        view.addRenderAttribute( 'button_hover_state', {
            'class': 'one-elements-element__hover-state'
        });

        if ( ! settings.button_border_hover_width_classic && settings.button_border_hover_background == 'gradient' ) {

        view.addRenderAttribute( 'button_hover_state', {
            'class': 'one-elements-element__border-gradient'
        });

        }


        view.addRenderAttribute( 'icon_regular_state', {
            'class': 'one-elements-element__regular-state'
        });

        if ( ! settings.icon_border_width_classic && settings.icon_border_background == 'gradient' ) {

        view.addRenderAttribute( 'icon_regular_state', {
            'class': 'one-elements-element__border-gradient'
        });

        }

        view.addRenderAttribute( 'icon_hover_state', {
            'class': 'one-elements-element__hover-state'
        });

        if ( ! settings.icon_border_hover_width_classic && settings.icon_border_hover_background == 'gradient' ) {

            view.addRenderAttribute( 'icon_hover_state', {
                'class': 'one-elements-element__border-gradient'
            });

        }


        if (linkAvailable ) {

            view.addRenderAttribute( 'button', {
                'href': settings.button_link.url,
                'class': 'one-elements-button__link'
            });

            if ( settings.button_link.is_external ) {
                view.addRenderAttribute( 'button', {
                'target': '_blank'
                });
            }

            if ( settings.button_link.nofollow ) {
                view.addRenderAttribute( 'button', {
                'rel': 'nofollow'
                });
            }

        }

        if ( settings.button_css_id ) {
            view.addRenderAttribute( 'button', {
                'id': settings.button_css_id
            });

        }

        if ( settings.button_size ) {
            view.addRenderAttribute( 'button', {
            'class': 'one-elements-button__size-' + settings.button_size
            });

        }

        if ( settings.button_size_tablet ) {

            view.addRenderAttribute( 'button', {
            'class': 'one-elements-button__tablet-size-' + settings.button_size_tablet
            });

        }

        if ( settings.button_size_mobile ) {

            view.addRenderAttribute( 'button', {
            'class': 'one-elements-button__mobile-size-' + settings.button_size_mobile
            });

        }

        if ( settings.button_hover_animation ) {

            view.addRenderAttribute( 'button', {
            'class': 'elementor-animation-' + settings.button_hover_animation
            });

        }


        view.addRenderAttribute( 'button_icon', {
            'class': 'one-elements-element one-elements-icon'
        });


        if ( settings.icon && settings.icon.library == 'svg' ) {

        view.addRenderAttribute( 'button_icon', {
            'class': 'one-elements-icon__svg'
        });


        }

        view.addRenderAttribute( 'button_text', 'class', 'one-elements-button__content_text' );

        view.addInlineEditingAttributes( 'button_text', 'none' );


        #>
        <div {{{
             view.getRenderAttributeString( 'wrapper' ) }}} >

        <# if ( linkAvailable ) { #>
        <a {{{
           view.getRenderAttributeString( 'button' ) }}} >
        <# } else { #>
        <button {{{
                view.getRenderAttributeString( 'button' ) }}} >
        <# } #>

        <!-- Regular State Background -->
        <span {{{
              view.getRenderAttributeString( 'button_regular_state' ) }}}>
        <span class="one-elements-element__state-inner"></span>
        </span>

        <!-- Hover State Background -->
        <span {{{
              view.getRenderAttributeString( 'button_hover_state' ) }}}>
        <span class="one-elements-element__state-inner"></span>
        </span>

        <!-- Button Content -->
        <span class="one-elements-element__content">
					
					<# if ( ( settings.button_type == 'circle' || settings.enable_button_icon == 'yes' ) && settings.icon.value ) { #>
						
						<span {{{
                              view.getRenderAttributeString( 'button_icon' ) }}}>


							<# if ( settings.button_type !== 'circle' ) { #>

            <!-- Regular State Background -->
                				<span {{{
                                      view.getRenderAttributeString( 'icon_regular_state' ) }}}>
									<span class="one-elements-element__state-inner"></span>
								</span>

        <!-- Hover State Background -->
        <span {{{
              view.getRenderAttributeString( 'icon_hover_state' ) }}}>
        <span class="one-elements-element__state-inner"></span>
        </span>

        <# } #>

        <!-- Content including Icon -->
        <span class="one-elements-element__content">

							    <span class="one-elements-icon__content_icon">
                                     {{{iconHTML.value}}}
								</span>
							    
							</span>

        </span>

        <# } #>

        <# if ( settings.button_type !== 'circle' ) { #>

        <span {{{
              view.getRenderAttributeString( 'button_text' ) }}}>

        {{{ settings.button_text }}}

        <# if ( settings.button_type == 'flat' ) { #>

        <span class="one-elements-button__underline"></span>

        <# } #>

        </span>

        <# } #>

        </span>

        <# if ( settings.button_link && settings.button_link.url ) { #>
        </a>
        <#  } else { #>
        </button>
        <#  } #>

        </div>
		<?php
	}

	/**
	 * Render button text.
	 * Render button widget text.
	 * @since  1.5.0
	 * @access protected
	 */
	protected function render_text() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'button_icon', 'class', 'one-elements-element one-elements-icon' );
		$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		?>
        <!-- Button Content -->
        <span class="one-elements-element__content">
            
            <?php if ( ( $settings['button_type'] == 'circle' || $settings['enable_button_icon'] == 'yes' ) && ( ! empty( $settings['icon']['value'] ) ) ) : ?>

                <span <?php $this->print_render_attribute_string( 'button_icon' ); ?>>

					<?php if ( $settings['button_type'] !== 'circle' ) : ?>

                        <!-- Regular State Background -->
                        <span <?php $this->print_render_attribute_string( 'button_icon_regular_state' ); ?>>
							<span class="one-elements-element__state-inner"></span>
						</span>
						
						<?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
                            <!-- Hover State Background -->
                            <span <?php $this->print_render_attribute_string( 'button_icon_hover_state' ); ?>>
								<span class="one-elements-element__state-inner"></span>
							</span>
						<?php endif; ?>

					<?php endif; ?>
					

					<!-- Content including Icon -->
					<span class="one-elements-element__content">
					    <span class="one-elements-icon__content_icon">
                            <?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>
					</span>

				</span>

            <?php endif; ?>

			<?php if ( $settings['button_type'] !== 'circle' ) : ?>

                <span class="one-elements-button__content_text">

					<?php echo esc_html( $settings['button_text'] ); ?>

					<?php if ( $settings['button_type'] == 'flat' ) : ?>
                        <span class="one-elements-button__underline"></span>
					<?php endif; ?>

				</span>

			<?php endif; ?>
            
        </span>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Button() );