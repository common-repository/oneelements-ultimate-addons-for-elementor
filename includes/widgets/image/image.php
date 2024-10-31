<?php
namespace OneElements\Includes\Widgets\Image;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Plugin;
use Elementor\Widget_Image;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Common_Widget_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * one element logo widget.
 *
 * Elementor widget that displays an site logo into the page.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Image extends Widget_Image {

    use One_Elements_Common_Widget_Trait;

	/**
	 * Get widget name.
	 *
	 * Retrieve site logo widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-image';
	}


	public function get_icon() {
		return 'one-elements-widget-eicon eicon-image';
	}

	public function get_categories() {
		return [ 'one_elements' ];
	}


	protected function init_style_image_overlay_controls() {

		$this->start_controls_section(
			'image_background_overlay',
			[
				'label' => __( 'Overlay', 'one-elements' ),
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
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__regular-state .one-elements-element__state-inner:after',
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
					'{{WRAPPER}} .one-elements-image > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
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
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__hover-state .one-elements-element__state-inner:after',
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
					'{{WRAPPER}} .one-elements-image > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function init_style_image_border_controls() {

		$this->start_controls_section(
			'one_image_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_one_image_border' );

		$this->start_controls_tab(
			'tab_one_image_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'one_image_border',
				'label' => __( 'Image Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-image, {{WRAPPER}} .one-elements-image > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-image > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-image > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'one_image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'label' => __( 'Image Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_one_image_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'one_image_border_hover',
				'label' => __( 'Image Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-image:hover, {{WRAPPER}} .one-elements-image:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-image:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-image:hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_one_image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'label' => __( 'Image Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function init_style_image_style_controls(  ) {
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __( 'Image Style', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'image_style!' => ''
                ]
			]
		);

		$this->add_control(
			'image_style_position',
			[
				'label' => __( 'Style Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top-left' => __( 'Top Left (Default)', 'one-elements' ),
					'top-right' => __( 'Top Right', 'one-elements' ),
					'bottom-left' => __( 'Bottom Left', 'one-elements' ),
					'bottom-right' => __( 'Bottom Right', 'one-elements' ),
				],
				'default' => 'top-left',
				'prefix_class' => 'oee_image--style-position_',
				'condition' => [
					'image_style' =>  [ 'style-1' ]
				]
			]
		);

		$this->add_responsive_control(
			'style_space',
			[
				'label' => __( 'Style Space', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_top-left .one-elements-image-area' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_top-left .one-elements-image-area:before' => 'top: -{{SIZE}}{{UNIT}}; left: -{{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_top-right .one-elements-image-area' => 'margin-top: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_top-right .one-elements-image-area:before' => 'top: -{{SIZE}}{{UNIT}}; right: -{{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_bottom-left .one-elements-image-area' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_bottom-left .one-elements-image-area:before' => 'bottom: -{{SIZE}}{{UNIT}}; left: -{{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_bottom-right .one-elements-image-area' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_image--style-1.oee_image--style-position_bottom-right .one-elements-image-area:before' => 'bottom: -{{SIZE}}{{UNIT}}; right: -{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_style' =>  [ 'style-1' ]
				]
			]
		);

		$this->add_group_control( Group_Control_Gradient_Background::get_type(),
			[
				'name' => 'style_background',
				'label' => __( 'Style Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.oee_image--style-1 .one-elements-image-area:before',
				'condition' => [
					'image_style' =>  [ 'style-1' ]
				],
			]
		);

		$this->add_group_control( Group_Control_Border::get_type(),
			[
				'name' => 'style_border',
				'label' => __( 'Style Border', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.oee_image--style-1 .one-elements-image-area:before',
				'condition' => [
					'image_style' =>  [ 'style-1' ]
				],
			]
		);

		$this->add_responsive_control(
			'style_border_radius',
			[
				'label' => __( 'Style Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}.oee_image--style-1 .one-elements-image-area:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'image_style' =>  [ 'style-1' ]
				],
			]
		);



		$this->end_controls_section();
	}


	/**
	 * Register site logo widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		parent::_register_controls();

		$this->remove_control( 'image_border_border');
		$this->remove_control( 'image_border_width');
		$this->remove_control( 'image_border_color');
		$this->remove_control( 'image_border_radius');
		// remove old shadow
		$this->remove_control( 'image_box_shadow_box_shadow');
		$this->remove_control( 'image_box_shadow_box_shadow_type');
		// remove old css filter
		$this->remove_control( 'css_filters_css_filter');
		$this->remove_control( 'css_filters_blur');
		$this->remove_control( 'css_filters_brightness');
		$this->remove_control( 'css_filters_contrast');
		$this->remove_control( 'css_filters_saturate');
		$this->remove_control( 'css_filters_hue');
		// remove old hover css filter

		$this->remove_control( 'css_filters_hover_css_filter');
		$this->remove_control( 'css_filters_hover_blur');
		$this->remove_control( 'css_filters_hover_brightness');
		$this->remove_control( 'css_filters_hover_contrast');
		$this->remove_control( 'css_filters_hover_saturate');
		$this->remove_control( 'css_filters_hover_hue');


		$this->update_responsive_control( 'width', [
			'selectors' => [
				'{{WRAPPER}} .one-elements-image-area' => 'width: {{SIZE}}{{UNIT}};',
			],
			'render_type' => 'ui'
        ]);
		$this->update_responsive_control( 'space', [
			'size_units' => [ '%', 'px' ],
			'selectors' => [
				'{{WRAPPER}} .one-elements-image-area' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		]);

		$this->start_injection( [
			'of' => 'width',
			'at' => 'before'
		]);

		$this->add_control(
			'image_style',
			[
				'label' => __( 'Style', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'one-elements' ),
					'style-1' => __( 'Style 1', 'one-elements' ),
				],
				'default' => '',
				'prefix_class' => 'oee_image--',
			]
		);

		$this->end_injection();


		$this->start_injection( [
            'of' => 'space',
            'at' => 'after'
        ]);

		$this->add_responsive_control(
			'max_height',
			[
				'label' => __( 'Max Height', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'vh',
				],
				'tablet_default' => [
					'unit' => 'vh',
				],
				'mobile_default' => [
					'unit' => 'vh',
				],
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-image .one-elements-element__content' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => __( 'Image Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => __( 'Top', 'one-elements' ),
					'middle' => __( 'Middle', 'one-elements' ),
					'bottom' => __( 'Bottom', 'one-elements' ),
				],
				'default' => 'middle',
			]
		);
        $this->end_injection();


		$this->update_control( 'opacity', [
			'selectors' => [
				'{{WRAPPER}} .oee__image' => 'opacity: {{SIZE}};',
			],
		]);
		$this->update_control( 'opacity_hover', [
				'selectors' => [
					'{{WRAPPER}} .one-elements-image:hover .oee__image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->update_control( 'background_hover_transition', [
				'selectors' => [
					'{{WRAPPER}} .one-elements-image' =>  'transition-duration: {{SIZE}}s',
				],
			]
		);

        // css filter is a group control so replace it. caption selectors need update
        $this->start_injection( [
            'of' => 'opacity',
            'at' => 'after'
        ]);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filter',
				'selector' => '{{WRAPPER}} .oee__image',
			]
		);
        $this->end_injection();
		$this->start_injection( [
			'of' => 'opacity_hover',
			'at' => 'after'
		]);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filter_hover',
				'selector' => '{{WRAPPER}} .one-elements-image:hover .oee__image',
			]
		);
		$this->end_injection();

		$this->init_style_image_style_controls();
		$this->init_style_image_overlay_controls();
		$this->init_style_image_border_controls();
	}


	/**
	 * Render site logo widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$has_caption = $this->has_caption( $settings );

		$link = $this->get_link_url( $settings );

		if ( $link ) {

			$this->add_render_attribute( 'link', [
				'href' => $link['url'],
				'data-elementor-open-lightbox' => $settings['open_lightbox'],
				'class' => 'one-elements-element__link'
			]);

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'link', [
					'class' => 'elementor-clickable',
				]);
			}

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}

		}

		$this->add_render_attribute( 'one_image_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['one_image_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'one_image_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'one_image_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['one_image_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'one_image_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'image_parent', 'class', 'one-elements-element one-elements-image' );

		$image_position = !empty( $settings['image_position']) ? $settings['image_position'] : 'middle';
		$this->add_render_attribute( 'one-elements-element__content', 'class', "one-elements-element one-elements-element__content one-elements-element__content-back oee__align-{$image_position}" );
		
		if ( !empty( $settings['hover_animation']) ) {
			$this->add_render_attribute( 'image_parent', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		?>
		<div class="one-elements-image-area">

			<div <?php $this->print_render_attribute_string('image_parent'); ?>>

				<!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string('one_image_regular_state'); ?>>
		            <span class="one-elements-element__state-inner"></span>
		        </span>

				<!-- Hover State Background -->
                <span <?php $this->print_render_attribute_string('one_image_hover_state'); ?>>
		            <span class="one-elements-element__state-inner"></span>
		        </span>

				<!-- Content -->
				<div <?php $this->print_render_attribute_string('one-elements-element__content'); ?>>
                    <?php echo $this->get_attachment_image_html( $settings ); // escaped data returned ?>
				</div>

				<?php if ( $link ) : ?>
                    <a <?php $this->print_render_attribute_string( 'link' ); ?>></a>
				<?php endif; ?>

			</div>

			<?php if ( $has_caption ) : ?>
				<!-- Image Caption -->
				<div class="one-elements-image--caption widget-image-caption"><?php echo esc_html( $this->get_caption( $settings )); ?></div>
			<?php endif; ?>

		</div>

	<?php

	}


	/**
	 * Check if the current widget has caption
	 *
	 * @access protected
	 * @since 2.3.0
	 *
	 * @param array $settings
	 *
	 * @return boolean
	 */
	protected function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access protected
	 * @since 2.3.0
	 * @param $settings
	 *
	 * @return string
	 */
	protected function get_caption( $settings ) {
		$caption = '';
		if ( ! empty( $settings['caption_source'] ) ) {
			switch ( $settings['caption_source'] ) {
				case 'attachment':
					$caption = wp_get_attachment_caption( $settings['image']['id'] );
					break;
				case 'custom':
					$caption = ! empty( $settings['caption'] ) ? $settings['caption'] : '';
			}
		}
		return $caption;
	}

	/**
	 * Retrieve image widget link URL.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $settings
	 *
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	protected function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		return [
			'url' => $settings['image']['url'],
		];
	}

	/**
	 * Render site logo widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
	?>
		<#
		if ( !settings.image.url ) { return; }

            const image = {
            id: settings.image.id,
            url: settings.image.url,
            size: settings.image_size,
            dimension: settings.image_custom_dimension,
            model: view.getEditModel()
            };

            const image_url = elementor.imagesManager.getImageUrl( image );

            if ( ! image_url ) {
            return;
            }

            const hasCaption = function() {
                if( ! settings.caption_source || 'none' === settings.caption_source ) {
                return false;
                }
                return true;
            }

            const ensureAttachmentData = function( id ) {
                if ( 'undefined' === typeof wp.media.attachment( id ).get( 'caption' ) ) {
                    wp.media.attachment( id ).fetch().then( function( data ) {
                    view.render();
                    } );
                }
            }

            const getAttachmentCaption = function( id ) {
                if ( ! id ) {
                return '';
                }
                ensureAttachmentData( id );
                return wp.media.attachment( id ).get( 'caption' );
            }

            const getCaption = function() {
                if ( ! hasCaption() ) {
                return '';
                }
                return 'custom' === settings.caption_source ? settings.caption : getAttachmentCaption( settings.image.id );
            }

            const get_link_url = function ( settings ) {
                    if ( 'none' === settings.link_to ) {
                        return false;
                    }

                    if ( 'custom' === settings.link_to ) {
                        if ( !settings.link.url ) {
                            return false;
                        }
                        return settings.link.url;
                    }

                    return { 'url' : settings.image.url };
                }


	        var imgClass = 'oee__image ';

            view.addRenderAttribute( 'image_parent', 'class', 'one-elements-element one-elements-image' );

	        if ( '' !== settings.hover_animation ) {
                view.addRenderAttribute( 'image_parent', 'class',  'elementor-animation-' + settings.hover_animation );
	        }

	        view.addRenderAttribute( 'one_element_regular_state', 'class', 'one-elements-element__regular-state' );

	        if ( settings['one_element_border_background'] === 'gradient' ) {
	        	view.addRenderAttribute( 'one_element_regular_state', 'class',  'one-elements-element__border-gradient' );
	        }

	        view.addRenderAttribute( 'one_element_hover_state', 'class', 'one-elements-element__hover-state' );

	        if ( settings['one_element_border_hover_background'] === 'gradient' ) {
	        	view.addRenderAttribute( 'one_element_hover_state', 'class', 'one-elements-element__border-gradient' );
	        }


        let link = get_link_url( settings );

        if ( link ) {
            view.addRenderAttribute( 'link', 'class', 'one-elements-element__link');
            view.addRenderAttribute( 'link', 'href', link.url);
            view.addRenderAttribute( 'link', 'data-elementor-open-lightbox', settings.open_lightbox);

            if ( link.is_external ) {
                view.addRenderAttribute( 'link', 'target', '_blank' );
            }

            if ( link.nofollow ) {
                view.addRenderAttribute( 'link', 'rel', 'nofollow' );
            }
        }

        let image_position = settings['image_position'] ? settings['image_position'] : 'middle';

		view.addRenderAttribute( 'one-elements-element__content', 'class', 'one-elements-element one-elements-element__content one-elements-element__content-back oee__align-'+image_position );


		#>

		<?php
		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>
			<#
                view.addRenderAttribute( 'link', 'class','elementor-clickable');
			#>
		<?php } ?>

        <div class="one-elements-image-area">

            <div {{{ view.getRenderAttributeString( 'image_parent' ) }}}>

                <!-- Regular State Background -->
                <span {{{ view.getRenderAttributeString( 'one_element_regular_state' ) }}}>
		            <span class="one-elements-element__state-inner"></span>
		        </span>

                <!-- Hover State Background -->
                <span {{{ view.getRenderAttributeString( 'one_element_hover_state' ) }}}>
		            <span class="one-elements-element__state-inner"></span>
		        </span>

                <!-- Content -->
                <div {{{ view.getRenderAttributeString( 'one-elements-element__content' ) }}}>
                    <img src="{{ image_url }}" class="{{ imgClass }}" />
                </div>

                <#  if ( link ) { #>
                <a {{{ view.getRenderAttributeString( 'link' ) }}}></a>
                <# } #>


            </div>

			<# if ( hasCaption() ) { #>
            	<!-- Image Caption -->
                <div class="one-elements-image--caption widget-image-caption">{{{ getCaption() }}}</div>
            <# } #>

        </div>
	<?php
	}


}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Image() );