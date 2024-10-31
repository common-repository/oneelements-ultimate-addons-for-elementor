<?php

namespace OneElements\Includes\Traits;


use Elementor\Controls_Manager;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;

if ( ! trait_exists( 'One_Elements_Carousel_Trait' ) ) {

	trait One_Elements_Carousel_Trait {

		public function get_script_depends() {
			return [ 'jquery-slick' ];
		}

		public function get_style_depends() {
			return [
				'elementor-icons-fa-regular',
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			];
		}

		public function get_carousel_options() {

			$settings = $this->get_settings_for_display();

			return array_filter( $settings, function ( $k ) {
				return false !== strpos( $k, $this->carousel_option_key );
			}, ARRAY_FILTER_USE_KEY );

		}

		public function is_carousel_enabled( $card_style ) {

			if ( $this->get_settings_for_display( 'display_type' ) !== 'carousel' ) {
				return false;
			}

			// All Classic Card excepts Carousel
			if ( strpos( $card_style, 'modern-card' ) !== false ) {
				return false;
			}

			return true;

		}

		public function set_carousel_nav_type( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_nav_type';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( $carousel_options[ $option_name ] == 'none' ) {

				$return_options['arrows'] = false;
				$return_options['dots']   = false;

			} else {

				if ( $carousel_options[ $option_name ] == 'nav' ) {
					$return_options['arrows'] = true;
					$return_options['dots']   = false;
				}

				if ( $carousel_options[ $option_name ] == 'dot' ) {
					$return_options['arrows'] = false;
					$return_options['dots']   = true;
				}

				if ( $carousel_options[ $option_name ] == 'both' ) {
					$return_options['arrows'] = true;
					$return_options['dots']   = true;
				}

			}


			return $return_options;

		}

		public function set_carousel_display_items( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_item_per_slide';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( $carousel_options[ $option_name ] ) {
				$return_options['slidesToShow'] = $carousel_options[ $option_name ];
			}

			return $return_options;

		}

		public function set_carousel_slides_visibility( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_slides_visibility';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( isset( $carousel_options[ $option_name ] ) ) {
				$return_options['slidesVisibility'] = $carousel_options[ $option_name ];
			}

			return $return_options;

		}

		public function set_carousel_slide_speed( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_slide_speed';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( isset($carousel_options[ $option_name ]) && isset($carousel_options[ $option_name ]['size']) && is_numeric($carousel_options[ $option_name ]['size']) ) {
				$return_options['speed'] = (int) $carousel_options[ $option_name ]['size'];
			}

			return $return_options;

		}

		public function set_carousel_auto_play( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_autoplay';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( isset( $carousel_options[ $option_name ] ) ) {
				$return_options['autoplay'] = wp_validate_boolean( $carousel_options[ $option_name ] );
			}

			return $return_options;

		}

		public function set_carousel_auto_play_speed( $carousel_options, $return_options, $device = '' ) {
			$option_name = $this->carousel_option_key . '_autoplay_speed';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;
			if ( isset($carousel_options[ $option_name ]) && isset($carousel_options[ $option_name ]['size']) && is_numeric($carousel_options[ $option_name ]['size']) ) {
				$return_options['autoplaySpeed'] = (int) $carousel_options[ $option_name ]['size'];
			}
			return $return_options;
		}

		public function set_carousel_infinite( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_infinite_loop';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( isset( $carousel_options[ $option_name ] ) ) {
				$return_options['infinite'] = wp_validate_boolean( $carousel_options[ $option_name ] );
			}

			return $return_options;

		}

		public function set_carousel_pause_on_hover( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_pause_on_hover';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( isset( $carousel_options[ $option_name ] ) ) {
				$return_options['pauseOnHover'] = wp_validate_boolean( $carousel_options[ $option_name ] );
			}

			return $return_options;

		}

		public function set_carousel_slides_to_scroll( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_slides_to_scroll';
			$option_name = empty( $device ) ? $option_name : $option_name . '_' . $device;

			if ( ! empty( $carousel_options[ $option_name ] ) ) {
				$return_options['slidesToScroll'] = $carousel_options[ $option_name ];
			}

			return $return_options;

		}

		public function set_carousel_nav_icons( $carousel_options, $return_options ) {

			$prev_icon = $this->carousel_option_key . '_prev_icon';
			$next_icon = $this->carousel_option_key . '_next_icon';

			if ( ! empty( $carousel_options[ $prev_icon ] ) ) {
				$return_options['prevArrowIcon'] = $carousel_options[ $prev_icon ];
			}

			if ( ! empty( $carousel_options[ $next_icon ] ) ) {
				$return_options['nextArrowIcon'] = $carousel_options[ $next_icon ];
			}

			return $return_options;

		}

		public function set_carousel_unslick( $carousel_options, $return_options, $device = '' ) {

			$option_name = $this->carousel_option_key . '_disable_carousel';

			if ( empty( $device ) ) {
				return $return_options;
			}

			if ( $device == 'tablet' && $carousel_options[ $option_name ] == 'from_tablet' ) {
				$return_options = 'unslick';
			}

			if ( $device == 'mobile' && ( $carousel_options[ $option_name ] == 'from_mobile' || $carousel_options[ $option_name ] == 'from_tablet' ) ) {
				$return_options = 'unslick';
			}

			return $return_options;

		}

		public function prepare_carousel_options( $carousel_options ) {

			$carousel_passing_options = [];

			$carousel_passing_options = $this->set_carousel_nav_type( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_display_items( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_slides_visibility( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_auto_play( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_slide_speed( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_auto_play_speed( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_pause_on_hover( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_slides_to_scroll( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_infinite( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->set_carousel_nav_icons( $carousel_options, $carousel_passing_options );

			return $carousel_passing_options;

		}

		public function prepare_carousel_options_tablet( $carousel_options, $carousel_passing_options ) {

			if ( ! isset( $carousel_passing_options['responsive'] ) ) {
				$carousel_passing_options['responsive'] = [];
			}

			$settings = [];
			$settings = $this->set_carousel_nav_type( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_display_items( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_slides_to_scroll( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_slide_speed( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_auto_play_speed( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_unslick( $carousel_options, $settings, 'tablet' );

			$carousel_passing_options['responsive'][] = [
				'breakpoint' => 1025,
				'settings'   => $settings,
			];

			return $carousel_passing_options;

		}

		public function prepare_carousel_options_mobile( $carousel_options, $carousel_passing_options ) {

			if ( ! isset( $carousel_passing_options['responsive'] ) ) {
				$carousel_passing_options['responsive'] = [];
			}

			$settings = [];
			$settings = $this->set_carousel_nav_type( $carousel_options, $settings, 'mobile' );
			$settings = $this->set_carousel_display_items( $carousel_options, $settings, 'mobile' );
			$settings = $this->set_carousel_slides_to_scroll( $carousel_options, $settings, 'mobile' );
			$settings = $this->set_carousel_slide_speed( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_auto_play_speed( $carousel_options, $settings, 'tablet' );
			$settings = $this->set_carousel_unslick( $carousel_options, $settings, 'mobile' );

			$carousel_passing_options['responsive'][] = [
				'breakpoint' => 768,
				'settings'   => $settings,
			];

			return $carousel_passing_options;

		}

		public function start_carousel_markup() {

			$carousel_options         = $this->get_carousel_options();
			$carousel_passing_options = $this->prepare_carousel_options( $carousel_options );
			$carousel_passing_options = $this->prepare_carousel_options_tablet( $carousel_options, $carousel_passing_options );
			$carousel_passing_options = $this->prepare_carousel_options_mobile( $carousel_options, $carousel_passing_options );


			?>
            <div class="one-elements__carousel" data-carousel-options='<?php echo json_encode( $carousel_passing_options ); ?>'>
			<?php
		}

		public function end_carousel_markup() { ?>
            </div>
			<?php
		}

		protected function get_content_carousel_controls( $excludes = [] ) {

			//Carousel SECTION
			if ( ! in_array( $this->carousel_option_key . '_section', $excludes ) ) {
				$this->start_controls_section( $this->carousel_option_key . '_section', [
					'label'     => __( 'Carousel Options', 'one-elements' ),
					'condition' => [
						'display_type'            => 'carousel',
						'one_elements_fetch_type' => [ 'multiple' ],
					],
				] );
			}


			$this->get_basic_controls( $excludes );
			$this->get_nav_controls( $excludes );
			$this->get_nav_color_controls();

			if ( ! in_array( $this->carousel_option_key . '_section', $excludes ) ) {
				$this->end_controls_section();
			}

		}

		private function get_nav_controls( $excludes = [] ) {
			if ( ! in_array( $this->carousel_option_key . '_nav_type', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_type', [
					'label'        => __( 'Navigation Type', 'one-elements' ),
					'description'  => __( 'Choose a Navigation Type. Choose None to hide carousel navigation.', 'one-elements' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'dot',
					'options'      => [
						'none' => __( 'None', 'one-elements' ),
						'nav'  => __( 'Nav', 'one-elements' ),
						'dot'  => __( 'Dot', 'one-elements' ),
						'both' => __( 'Nav & Dot', 'one-elements' ),
					],
					'prefix_class' => 'carousel%s-nav_type-',
					//here %s = mobile/tablet/desktop. eg. elementor-{mobile}-align-{value}
					'render_type'  => 'ui',

				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_position', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_position', [
					'label'        => __( 'Nav Position', 'one-elements' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => [
						'bottom'     => __( 'Bottom', 'one-elements' ),
						'both_left'  => __( 'Both Left', 'one-elements' ),
						'both_right' => __( 'Both Right', 'one-elements' ),
						'left_right' => __( 'Left Right', 'one-elements' ),
					],
					'prefix_class' => 'carousel%s-nav_position-',
					//here %s = mobile/tablet/desktop. eg. elementor-{mobile}-align-{value}
					'default'      => 'bottom',
					'condition'    => [
						$this->carousel_option_key . '_nav_type!' => 'none',
					],
					'render_type'  => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_dot_alignment', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_dot_alignment', [
					'label'        => __( 'Dot Alignment', 'one-elements' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => [
						'center' => __( 'Center', 'one-elements' ),
						'left'   => __( 'Left', 'one-elements' ),
						'right'  => __( 'Right', 'one-elements' ),
					],
					'prefix_class' => 'carousel%s-dot_alignment-',
					//here %s = mobile/tablet/desktop. eg. elementor-{mobile}-align-{value}
					'default'      => 'center',
					'render_type'  => 'ui',
					'separator'    => 'after',
					'conditions'   => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'dot' ),
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_heading', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_nav_heading', [
					'label'      => __( 'Nav Customization', 'one-elements' ),
					'type'       => Controls_Manager::HEADING,
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			$this->get_all_prev_nav_controls( $excludes );
			$this->get_all_next_nav_controls( $excludes );

			if ( ! in_array( $this->carousel_option_key . '_nav_icon_size', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_icon_size', [
					'label'      => __( 'Nav Icon Size', 'one-elements' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'%',
					],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],

				] );
			}
			if ( ! in_array( $this->carousel_option_key . '_nav_icon_box_size', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_icon_box_size', [
					'label'      => __( 'Nav Icon Box Size', 'one-elements' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'%',
					],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .slick-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			$this->get_nav_border_controls( $excludes );
			$this->get_nav_box_shadow_controls( $excludes );
			$this->get_nav_dots_controls( $excludes );

		}

		private function get_nav_border_controls( $excludes = [] ) {
			if ( ! in_array( $this->carousel_option_key . '_nav_border', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_nav_border', [
					'label'     => _x( 'Nav Border Style', 'Border Control', 'one-elements' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'none',
					'options'   => [
						'none'   => __( 'None', 'one-elements' ),
						'solid'  => _x( 'Solid', 'Border Control', 'one-elements' ),
						'double' => _x( 'Double', 'Border Control', 'one-elements' ),
						'dotted' => _x( 'Dotted', 'Border Control', 'one-elements' ),
						'dashed' => _x( 'Dashed', 'Border Control', 'one-elements' ),
						'groove' => _x( 'Groove', 'Border Control', 'one-elements' ),
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements__carousel .slick-arrow' => 'border-style: {{VALUE}};',
					],
					'condition'    => [
						$this->carousel_option_key . '_nav_type!' => 'none',
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_border_width', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_border_width', [
					'label'     => _x( 'Width', 'Border Control', 'one-elements' ),
					'type'      => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .one-elements__carousel .slick-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'    => [
						$this->carousel_option_key . '_nav_type!' => 'none',
						$this->carousel_option_key . '_nav_border!' => 'none',

					],

				] );
			}

		}

		private function get_nav_box_shadow_controls( $excludes = [] ) {

			if ( ! in_array( $this->carousel_option_key . '_nav_box_shadow', $excludes ) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $this->carousel_option_key . '_nav_box_shadow',
						'label'     => _x( 'Nav Box Shadow', 'Box Shadow Control', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__carousel .slick-arrow',
						'condition'    => [
							$this->carousel_option_key . '_nav_type!' => 'none',
						]
					]
				);
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_box_shadow_hover', $excludes ) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $this->carousel_option_key . '_nav_box_shadow_hover',
						'label'     => _x( 'Nav Box Shadow Hover', 'Box Shadow Control', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__carousel .slick-arrow:hover',
						'condition'    => [
							$this->carousel_option_key . '_nav_type!' => 'none',
						],
					]
				);
			}

		}

		private function get_nav_dots_controls( $excludes = [] ) {
			if ( ! in_array( $this->carousel_option_key . '_dot_heading', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_dot_heading', [
					'label'     => __( 'Dot Customization', 'one-elements' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}
			if ( ! in_array( $this->carousel_option_key . '_nav_dot_size', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_dot_size', [
					'label'      => __( 'Dot Size', 'one-elements' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'%',
					],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_dot_inner_spacing', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_dot_inner_spacing', [
					'label'      => __( 'Dot Inner Spacing', 'one-elements' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'%',
					],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .slick-dots li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_nav_dot_top_spacing', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_nav_dot_top_spacing', [
					'label'      => __( 'Dot Top Spacing', 'one-elements' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'%',
					],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
						'%'  => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

		}

		private function get_all_next_nav_controls( $excludes = [] ) {
			// Next Nav Button Starts
			if ( ! in_array( $this->carousel_option_key . '_next_icon', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_icon', [
					'label'       => __( 'Next Nav Icon', 'one-elements' ),
					'type'        => Controls_Manager::ICON,
					'default' => [
						'value' => 'fas fa-chevron-right',
						'library' => 'fa-solid',
					],
					'render_type' => 'ui',
					'conditions'  => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

		}

		private function get_all_prev_nav_controls( $excludes = [] ) {
			if ( ! in_array( $this->carousel_option_key . '_prev_icon', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_icon', [
					'label'       => __( 'Prev Nav Icon', 'one-elements' ),
					'type'        => Controls_Manager::ICON,
					//@TODO; update icon control
					//@TODO; include only nav type icons later.
					'default' => [
						'value' => 'fas fa-chevron-left',
						'library' => 'fa-solid',
					],
					'conditions'  => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
					'render_type' => 'ui',
				] );
			}
		}

		private function get_basic_controls( $excludes = [] ) {

			if ( ! in_array( $this->carousel_option_key . '_item_per_slide', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_item_per_slide', [
					'label'       => __( 'Items per slide', 'one-elements' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 1,
					'max'         => 12,
					'step'        => 1,
					'default'     => 3,
					'render_type' => 'ui',
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_slides_to_scroll', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_slides_to_scroll', [
					'label'       => __( 'Slides To Scroll', 'one-elements' ),
					'type'        => Controls_Manager::NUMBER,
					'min'         => 1,
					'max'         => 12,
					'step'        => 1,
					'default'     => 1,
					'render_type' => 'ui',
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_slides_visibility', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_slides_visibility', [
					'label'       => __( 'Slides Visibility', 'one-elements' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'hide_none'  => __( 'Default', 'one-elements' ),
						'hide_left'  => __( 'Hide Left', 'one-elements' ),
						'hide_right' => __( 'Hide Right', 'one-elements' ),
						'hide_both'  => __( 'Hide Both', 'one-elements' ),
					],
					'default'     => 'hide_none',
					'render_type' => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_slide_speed', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_slide_speed', [
					'label'       => __( 'Slide Speed', 'one-elements' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'ms' ],
					'range'       => [
						'ms' => [
							'min'  => 100,
							'max'  => 3000,
							'step' => 50,
						],
					],
					'default'     => [
						'unit' => 'ms',
						'size' => 1000,
					],
					'render_type' => 'ui',
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_autoplay', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_autoplay', [
					'label'        => __( 'Auto Play', 'one-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'one-elements' ),
					'label_off'    => __( 'No', 'one-elements' ),
					'return_value' => '1',
					'default'      => '1',
					'render_type'  => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_autoplay_speed', $excludes ) ) {
				$this->add_responsive_control( $this->carousel_option_key . '_autoplay_speed', [
					'label'       => __( 'Auto Play Speed', 'one-elements' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'ms' ],
					'range'       => [
						'ms' => [
							'min'  => 250,
							'max'  => 10000,
							'step' => 50,
						],
					],
					'default'     => [
						'unit' => 'ms',
						'size' => 3000,
					],
					'condition'   => [
						$this->carousel_option_key . '_autoplay' => '1',
					],
					'render_type' => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_infinite_loop', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_infinite_loop', [
					'label'        => __( 'Infinite Loop', 'one-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'one-elements' ),
					'label_off'    => __( 'No', 'one-elements' ),
					'return_value' => '1',
					'default'      => '1',
					'render_type'  => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_pause_on_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_pause_on_hover', [
					'label'        => __( 'Pause On Hover', 'one-elements' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'one-elements' ),
					'label_off'    => __( 'No', 'one-elements' ),
					'return_value' => '1',
					'default'      => '1',
					'render_type'  => 'ui',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_disable_carousel', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_disable_carousel', [
					'label'        => __( 'Disable Carousel', 'one-elements' ),
					'type'         => Controls_Manager::SELECT,
					'return_value' => '1',
					'options'      => [
						'none'        => __( 'Do Not Disable', 'one-elements' ),
						'from_tablet' => __( 'On Tablet', 'one-elements' ),
						'from_mobile' => __( 'On Mobile', 'one-elements' ),
					],
					'prefix_class' => 'carousel-disable--',
					'default'      => 'none',
					'render_type'  => 'ui',
				] );
			}

		}

		protected function get_nav_color_controls( $excludes = [] ) {
			if ( ! in_array( $this->carousel_option_key . '_color_tabs', $excludes ) ) {
				$this->start_controls_tabs( $this->carousel_option_key . '_color_tabs', [
					'conditions' => [
						'relation' => 'or',
						'terms'    => [
							[
								'name'     => $this->carousel_option_key . '_nav_type',
								'operator' => 'in',
								'value'    => [
									'nav',
									'both',
									'dot',
								],
							],
							[
								'name'     => $this->carousel_option_key . '_nav_type_tablet',
								'operator' => 'in',
								'value'    => [
									'nav',
									'both',
									'dot',
								],
							],
							[
								'name'     => $this->carousel_option_key . '_nav_type_mobile',
								'operator' => 'in',
								'value'    => [
									'nav',
									'both',
									'dot',
								],
							],
						],
					],
					'separator' => 'before',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_color_normal_tab', $excludes ) ) {
				// Normal State Tab
				$this->start_controls_tab( $this->carousel_option_key . '_color_normal_tab', [ 'label' => esc_html__( 'Normal', 'one-elements' ) ] );
			}

			if ( ! in_array( $this->carousel_option_key . '_next_color_h', $excludes ) ) {
				// Next nav color customization
				$this->add_control( $this->carousel_option_key . '_next_color_h', [
					'label'      => __( 'Next Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::HEADING,
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_next_color', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_color', [
					'label'      => __( 'Next Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next' => 'color: {{VALUE}}',
						//@todo; update nav class
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_next_bg', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_bg', [
					'label'      => __( 'Next Nav BG Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_next_border_color', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_border_color', [
					'label'      => __( 'Next Nav border', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next' => 'border-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
					'separator'  => 'after',
				] );
			}

			// prev nav color
			if ( ! in_array( $this->carousel_option_key . '_prev_color_h', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_color_h', [
					'label'      => __( 'Prev Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::HEADING,
					'separator'  => 'before',
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_prev_color', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_color', [
					'label'      => __( 'Prev Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev' => 'color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_prev_bg', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_bg', [
					'label'      => __( 'Prev Nav BG Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_prev_br_color', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_br_color', [
					'label'      => __( 'Prev Nav border', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev' => 'border-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
					'separator'  => 'after',
				] );
			}

			// Dots Color
			if ( ! in_array( $this->carousel_option_key . '_dot_color_h', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_dot_color_h', [
					'label'     => __( 'Dot Color', 'one-elements' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_dot_bg', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_dot_bg', [
					'label'     => __( 'Dot color', 'one-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .one-elements__carousel .slick-dots li button' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_color_normal_tab', $excludes ) ) {
				$this->end_controls_tab(); // end normal tab
			}


			if ( ! in_array( $this->carousel_option_key . '_color_hover_tab', $excludes ) ) {
				// Hover State Tab
				$this->start_controls_tab( $this->carousel_option_key . '_color_hover_tab', [ 'label' => esc_html__( 'Hover', 'one-elements' ) ] );
			}

			if ( ! in_array( $this->carousel_option_key . '_next_color_hover_h', $excludes ) ) {
				// Next nav hover color customization
				$this->add_control( $this->carousel_option_key . '_next_color_hover_h', [
					'label'      => __( 'Next Nav Hover Color', 'one-elements' ),
					'type'       => Controls_Manager::HEADING,
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_next_color_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_color_hover', [
					'label'      => __( 'Next Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next:hover' => 'color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_next_bg_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_bg_hover', [
					'label'      => __( 'Next Nav BG Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next:hover' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_next_border_color_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_next_border_color_hover', [
					'label'      => __( 'Next Nav border', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-next:hover' => 'border-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
					'separator'  => 'after',
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_prev_color_hover_h', $excludes ) ) {
				// prev nav color
				$this->add_control( $this->carousel_option_key . '_prev_color_hover_h', [
					'label'      => __( 'Prev Nav Hover Color ', 'one-elements' ),
					'type'       => Controls_Manager::HEADING,
					'separator'  => 'before',
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_prev_color_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_color_hover', [
					'label'      => __( 'Prev Nav Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev:hover' => 'color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_prev_bg_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_bg_hover', [
					'label'      => __( 'Prev Nav BG Color', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev:hover' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_prev_br_color_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_prev_br_color_hover', [
					'label'      => __( 'Prev Nav border', 'one-elements' ),
					'type'       => Controls_Manager::COLOR,
					'selectors'  => [
						'{{WRAPPER}} .one-elements__carousel .one-elements__carousel-prev:hover' => 'border-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms'    => $this->get_condition_terms_for_nav( 'nav' ),
					],
					'separator'  => 'after',
				] );
			}


			if ( ! in_array( $this->carousel_option_key . '_dot_nav_color_hover_h', $excludes ) ) {
				// Dots Color
				$this->add_control( $this->carousel_option_key . '_dot_nav_color_hover_h', [
					'label'     => __( 'Dot Hover Color', 'one-elements' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_dot_bg_hover', $excludes ) ) {
				$this->add_control( $this->carousel_option_key . '_dot_bg_hover', [
					'label'     => __( 'Dot color', 'one-elements' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .one-elements__carousel .slick-dots li:hover button'        => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .one-elements__carousel .slick-dots li.slick-active button' => 'background-color: {{VALUE}}',
					],
					'conditions' => [
						'relation' => 'or',
						'terms' => $this->get_condition_terms_for_nav('dot')
					],
				] );
			}

			if ( ! in_array( $this->carousel_option_key . '_color_hover_tab', $excludes ) ) {
				$this->end_controls_tab(); // end hover tab
			}

			if ( ! in_array( $this->carousel_option_key . '_color_tabs', $excludes ) ) {
				$this->end_controls_tabs(); // end all tabs
			}

		}

		protected function get_condition_terms_for_nav( $dot_or_nav = 'nav' ) {
			return [
				[
					'name'     => $this->carousel_option_key . '_nav_type',
					'operator' => '===',
					'value'    => $dot_or_nav,
				],
				[
					'name'     => $this->carousel_option_key . '_nav_type',
					'operator' => '===',
					'value'    => 'both',
				],
				[
					'name'     => $this->carousel_option_key . '_nav_type_tablet',
					'operator' => '===',
					'value'    => $dot_or_nav,
				],
				[
					'name'     => $this->carousel_option_key . '_nav_type_tablet',
					'operator' => '===',
					'value'    => 'both',
				],
				[
					'name'     => $this->carousel_option_key . '_nav_type_mobile',
					'operator' => '===',
					'value'    => $dot_or_nav,
				],
				[
					'name'     => $this->carousel_option_key . '_nav_type_mobile',
					'operator' => '===',
					'value'    => 'both',
				],
			];

		}

	}
}