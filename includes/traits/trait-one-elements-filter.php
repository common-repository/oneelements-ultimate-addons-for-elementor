<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use OneElements\Includes\Controls\Control_One_Element_Select2;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;

if ( !trait_exists( 'One_Elements_Filter_Trait') ) {
	
	trait One_Elements_Filter_Trait {

		public function get_script_depends() {
			return [ 'one-elements--isotope' ];
		}

		public function get_filter_options() {

			$settings =  $this->get_settings_for_display();
			return array_filter( $settings, function( $k ) {
				return false !== strpos( $k, $this->filter_option_key );
			}, ARRAY_FILTER_USE_KEY );

		}
		public function is_filter_enabled( $card_style, $filter_tax ) {
			if (
			! $this->is_pro_active
			|| $this->get_settings_for_display( 'display_type' ) !== 'filter'
			|| ($this->get_settings_for_display( "{$this->query_option_key}_post_type" ) == 'by_id')
			|| empty( $filter_tax)
			|| ( strpos( $card_style, 'modern-card' ) !== false )
			) { return false; }

			return true;

		}
		public function get_filter_lists( $filters ) {

			// Include show all
			$show_all = $this->get_settings_for_display( $this->filter_option_key.'_show_all' );

			if ( wp_validate_boolean( $show_all ) ) {
				$show_all_text = $this->get_settings_for_display( $this->filter_option_key.'_all_text' );
				$filters = array_merge( [ '*' => $show_all_text ], $filters );
			}

			$filter_bar_align = $this->get_settings_for_display( $this->filter_option_key.'_filter_bar_align' );

			$this->add_render_attribute( 'one-elements__filter-lists', 'class', [
				'one-elements__filter-lists',
				'oee__content-' . $filter_bar_align
			] );

			?>

			<div class="one-elements-element one-elements__filter-header">

				<span class="one-elements-element__regular-state">
					<span class="one-elements-element__state-inner"></span>
				</span>

				<div class="one-elements-element__content">

					<ul <?php $this->print_render_attribute_string( 'one-elements__filter-lists' ); ?>>

					<?php foreach ( $filters as $filter => $value ): ?>
						<?php $filter = $filter === '*' ? $filter : '.' . $filter; ?>
						<li data-filter="<?php echo esc_attr($filter); ?>"><?php echo esc_html( $value); ?></li>
					<?php endforeach ?>

					</ul>
					
				</div>

			</div>

			<?php

		}

		public function set_filter_initial_source( $filter_options, $return_options, $device = '' ) {
		    if(!empty( $this->term_slug)){
			    $return_options['initialFilterTax'] = $this->taxonomy;
			    $return_options['initialFilter'] = $this->term_slug;

		    }else{
			    $tax_for_filter_nav = !empty( $filter_options["{$this->filter_option_key}_initial_{$this->post_type}_tax"]) ? $filter_options["{$this->filter_option_key}_initial_{$this->post_type}_tax"] : '';
			    $init_filter_term = !empty( $filter_options["{$this->filter_option_key}_initial_{$tax_for_filter_nav}_id"]) ? $filter_options["{$this->filter_option_key}_initial_{$tax_for_filter_nav}_id"]: '';


			    if ( ! empty($init_filter_term) && !empty( $tax_for_filter_nav) ) {
				    $return_options['initialFilterTax'] = $init_filter_term;
				    $return_options['initialFilter'] = $init_filter_term;
			    }
		    }



			return $return_options;

		}

		public function set_ajax_filtering( $filter_options, $return_options, $device = '' ) {

			$option_name = $this->filter_option_key.'_enable_ajax';

			if ( wp_validate_boolean( $filter_options[$option_name] ) ) {

				//$settings =  $this->get_settings_for_display();

				$return_options['ajaxFilter'] = wp_validate_boolean( $filter_options[$option_name] );
				$return_options['post_type'] = $this->post_type;
				$return_options['taxonomy'] = $this->taxonomy;
				$return_options['load_more_template_id'] = $this->load_more_template_id;
				$return_options['load_more_items'] = $this->post_per_page;

			}

			return $return_options;

		}

		public function prepare_filter_options( $filter_options ) {

			$filter_passing_options = array();

			$filter_passing_options = $this->set_filter_initial_source( $filter_options, $filter_passing_options );
			$filter_passing_options = $this->set_ajax_filtering( $filter_options, $filter_passing_options );

			return $filter_passing_options;

		}

		public function prepare_filter_options_tablet( $filter_options, $filter_passing_options ) {

			if ( ! isset($filter_passing_options['responsive']) ) {
				$filter_passing_options['responsive'] = array();
			}

			$settings = array();

			$filter_passing_options['responsive'][] = array(
				'breakpoint' => 1024,
				'settings' => $settings
			);

			return $filter_passing_options;

		}

		public function prepare_filter_options_mobile( $filter_options, $filter_passing_options ) {

			if ( ! isset($filter_passing_options['responsive']) ) {
				$filter_passing_options['responsive'] = array();
			}

			$settings = array();

			$filter_passing_options['responsive'][] = array(
				'breakpoint' => 768,
				'settings' => $settings
			);

			return $filter_passing_options;

		}
		public function filter_header_markup( $taxonomy='category', $hide_empty=1 ) {

			$pCats = get_categories([
				'taxonomy' => $taxonomy,
				'hide_empty' => $hide_empty
			]);

			$pCats = wp_list_pluck( $pCats, 'name', 'slug' );

			if ( ! empty($pCats) ) $this->get_filter_lists( $pCats );

		}
		public function start_filter_markup() {
			$filter_options = $this->get_filter_options();
			$filter_passing_options = $this->prepare_filter_options( $filter_options );
			?>
			<div class="one-elements__filter" data-filter-options='<?php echo json_encode($filter_passing_options); ?>'>
			<?php
		}

		public function end_filter_markup() { ?>
			</div>
			<?php
		}

		protected function get_content_filter_controls($excludes=[], $post_types=[]) {

			//FILTER SECTION
			if ( !in_array( $this->filter_option_key.'_section', $excludes) ){
				$this->start_controls_section( $this->filter_option_key.'_section',
					[
						'label' => __( 'Filter Options', 'one-elements' ),
						'condition' => [
							'display_type' => 'filter',
							//'one_elements_fetch_type' => [ 'multiple' ],
							$this->query_option_key.'_post_type!' => 'by_id', // do not show filter for manually selected posts.
						],
					]
				);
			}

			$this->get_filter_cats_and_ajax_controls($excludes, $post_types);
			$this->get_filter_bar_controls($excludes);
			$this->init_filter_item_controls($excludes);
			$this->get_intelligent_nav_color_controls($excludes);
			if ( !in_array( $this->filter_option_key.'_section', $excludes) ){
				$this->end_controls_section();
			}

		}

		protected function init_filter_item_controls( $excludes=[] ) {

			if ( !in_array( $this->filter_option_key.'_items_h', $excludes) ){
				$this->add_control( $this->filter_option_key.'_items_h', [
					'label' => __( 'Filter Items Options', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]);
		    }

			if ( !in_array( $this->filter_option_key.'_typography', $excludes) ){
				$this->add_group_control( Group_Control_Typography::get_type(),
					[
						'name' => $this->filter_option_key,
						'label' => __( 'Typography', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li',
						'exclude' => ['text_decoration']
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_padding_filter_item', $excludes) ){
				$this->add_responsive_control( $this->filter_option_key.'_padding_filter_item', [
					'label' => __( 'Item Padding', 'one-elements' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [
						'px',
						'em',
						'%',
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]);
			}

			if ( !in_array( $this->filter_option_key.'_inner_spacing', $excludes) ){
				$this->add_responsive_control( $this->filter_option_key.'_inner_spacing', [
					'label' => __( 'Item Inner Spacing', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'desktop_default' => [
						'unit' => 'em',
					],
					'tablet_default' => [
						'unit' => 'em',
					],
					'mobile_default' => [
						'unit' => 'em',
					],
					'range' => [
						'px' => [
							'min' => 1,
						],
					],
					'size_units' => [ 'px', 'em' ],
					'selectors'  => [
						'body:not(.rtl) {{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
						'body.rtl {{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]);
			}

			$this->get_filter_item_color_border_shadow_controls($excludes);

		}

		protected function get_filter_cats_and_ajax_controls( $excludes=[], $post_types=[] ) {

			$post_types = !empty( $post_types) ? $post_types : one_elements_get_post_types();// post_name| posttype title
			$pa= array_keys( $post_types );// posttype1, posttype2 etc.
			$taxonomies = get_object_taxonomies( $pa, 'objects');

			if ( !in_array( $this->filter_option_key.'_enable_ajax', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_enable_ajax', [
					'label'      => __( 'Enable Ajax Filtering', 'Filter Control', 'one-elements' ),

					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'one-elements' ),
					'label_off' => __( 'No', 'one-elements' ),
					'return_value' => '1',
					'default' => '',
				]);

			}

			if ( !in_array( $this->filter_option_key.'_show_all', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_show_all', [
					'label'      => __( 'Enable Show All', 'Filter Control', 'one-elements' ),
					'description'      => __( 'You can show "All" in the filter nav or hide it.', 'Filter Control', 'one-elements' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'one-elements' ),
					'label_off' => __( 'No', 'one-elements' ),
					'return_value' => '1',
					'default' => '1',
					'condition' => [
						$this->filter_option_key.'_enable_ajax' => '',
					],
				]);

			}

			if ( !in_array( $this->filter_option_key.'_all_text', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_all_text', [
					'label'      => __( 'Text for filter All', 'Filter Control', 'one-elements' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'All', 'one-elements'),
					'placeholder' => __( 'Eg. All', 'one-elements'),
					'condition' => [
						$this->filter_option_key.'_show_all' => '1',
						$this->filter_option_key.'_enable_ajax' => '',
					],
				]);

			}

			if ( !in_array( $this->filter_option_key.'_initial_source_type', $excludes) ) {

				foreach ($post_types as $p_type => $p_label) {
				    // make a list of the current post type's taxonomies
					$tax_options  = [];

					foreach ($taxonomies as $tax_name => $obj){
					    if (!$obj->show_in_nav_menus || 'post_format' === $tax_name) continue;
				        if(in_array( $p_type, $obj->object_type)){
				            $tax_options[$tax_name] = $obj->label;
				        }
				    }

				    if ( empty($tax_options) ) {

					    $this->add_control(
						    'note_about_filter_'.$p_type,
						    [
							    'label' => sprintf( '<span style="color:red; display: block; padding-bottom: 5px;">%s <i class="fas fa-info-circle"></i></span>',__( 'Note about filter', 'one-elements' ) ),
							    'type' => Controls_Manager::RAW_HTML,
							    'raw' => __( 'Your selected content type does not have any taxonomy to filter its content', 'one-elements' ),
							    'condition' => [
								    $this->query_option_key.'_post_type' => $p_type,
							    ],
						    ]
					    );

				    } else {

	                    switch ($p_type){
	                        case 'post':
		                        $default_tax = 'category';
		                        break;
	                        case 'download':
		                        $default_tax = 'download_category';
	                            break;
		                    case 'practice':
			                    $default_tax = 'practice-category';
			                    break;
	                        case 'case-study':
			                    $default_tax = 'case-study-category';
			                    break;
	                        default:
		                        $default_tax = '';

	                    }

					    $this->add_control( $this->filter_option_key."_initial_{$p_type}_tax", [
						    'label' => __( "Filter By", 'one-elements' ),
						    'description' => sprintf( __( 'Select the taxonomy type to filter %s', 'one-elements' ), $p_label),
						    'type' => Controls_Manager::SELECT2,
						    'default' => $default_tax,
						    'options' => $tax_options,
						    'condition' => [
							    $this->query_option_key.'_post_type' => $p_type,
						    ],
					    ]);
				    }

				}

			}

			if ( !in_array( $this->filter_option_key.'_initial_category', $excludes) ) {

				foreach ($taxonomies as $taxonomy => $obj) {

					if ( !$obj->show_in_nav_menus || 'post_format' === $taxonomy ) continue;
					// prepare terms for each taxonomy
					$terms_options = [];
					$terms = get_terms( $taxonomy );

					foreach ( $terms as $term ) {
						$terms_options[ $term->slug ] = $term->name;
					}

					$p_type = !empty( $obj->object_type[0]) ? $obj->object_type[0] : '';
					// Add tax control for initial filter term
					$this->add_control( "{$this->filter_option_key}_initial_{$taxonomy}_slug", [
						'label' => __( 'Initial Filter Term', 'one-elements' ),
						'description' => sprintf( __( 'Select one of the terms of %s to filter all content on initial page load.', 'one-elements' ), $obj->labels->singular_name),
						'type' => Control_One_Element_Select2::SELECT2,
						'options' => "one-elements-select2/{$taxonomy}/term_list",//Rest API end point
						'condition' => [
							$this->query_option_key.'_post_type' => $p_type,
							$this->filter_option_key."_initial_{$p_type}_tax" => $taxonomy,
						]
					]);

				}

			}

		}

		protected function get_filter_bar_controls( $excludes=[] ) {

			$this->add_control( $this->filter_option_key.'_filter_bar', [
				'label' => __( 'Filter Bar Options', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]);

			if ( !in_array( $this->filter_option_key.'_filter_bar_align', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_filter_bar_align', [
						'label' => __( 'Align', 'one-elements' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'center',
						'options' => [
							'left' => __( 'Left', 'one-elements' ),
							'center' => __( 'Center', 'one-elements' ),
							'right' => __( 'Right', 'one-elements' ),
						]
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_filter_bar_bg', $excludes) ) {

				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $this->filter_option_key.'_filter_bar_bg',
						'label' => __('Background', 'one-elements' ),
						'types' => ['classic', 'gradient'],
						'selector' => '{{WRAPPER}} .one-elements__filter-header .one-elements-element__regular-state .one-elements-element__state-inner',
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_border_filter_bar', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_border_filter_bar',
						'label' => __('Border', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__filter-header .one-elements-element__regular-state',
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_filter_bar_shadow', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $this->filter_option_key.'_filter_bar_shadow',
						'label' => __( 'Shadow', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__filter-header',
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_padding_filter_bar', $excludes) ) {

				$this->add_responsive_control( $this->filter_option_key.'_padding_filter_bar', [
					'label' => __( 'Padding', 'one-elements' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [
						'px',
						'em',
						'%',
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__filter-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]);

			}

			if ( !in_array( $this->filter_option_key.'_border_radius_filter_bar', $excludes) ) {

				$this->add_responsive_control( $this->filter_option_key.'_border_radius_filter_bar', [
					'label' => __( 'Border Radius', 'one-elements' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [
						'px',
						'em',
						'%',
					],
					'selectors'  => [
						'{{WRAPPER}} .one-elements__filter-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]);

			}

			if ( !in_array( $this->filter_option_key.'_bottom_spacing', $excludes) ) {

				$this->add_responsive_control( $this->filter_option_key . '_bottom_spacing', [
					'label' => __( 'Bottom Spacing', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'desktop_default' => [
						'unit' => 'em',
					],
					'tablet_default' => [
						'unit' => 'em',
					],
					'mobile_default' => [
						'unit' => 'em',
					],
					'range' => [
						'px' => [
							'min' => 1,
						],
					],
					'size_units' => [
						'px',
						'em'
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements__filter-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				] );

			}

		}

		protected function get_filter_item_color_border_shadow_controls($excludes=[]) {

			// add filter colors options and border color gradient for nav.
			if ( !in_array( $this->filter_option_key.'_color_shadow_h', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_color_shadow_h', [
					'label' => __( 'Colors, Border & Shadow', 'one-elements' ),
					'type' => Controls_Manager::RAW_HTML,
					'separator' => 'before',
					'condition' => [
						'one_elements_fetch_type' => [ 'multiple' ],
						'display_type'            => [ 'filter' ],
					]
				]);

			}

			if ( !in_array( $this->filter_option_key.'_color_tabs', $excludes) ) {

				$this->start_controls_tabs( $this->filter_option_key.'_color_tabs' , [
					'condition' => [
						'one_elements_fetch_type' => [ 'multiple' ],
						'display_type'            => [ 'filter' ],
					]
				]);

			}

			if ( !in_array( $this->filter_option_key.'_color_tab_normal', $excludes) ) {
				// Normal State Tab
				$this->start_controls_tab( $this->filter_option_key.'_color_tab_normal', [ 'label' => esc_html__( 'Normal', 'one-elements' ) ]);
			}

			if ( !in_array( $this->filter_option_key.'_item_color', $excludes) ) {
				$this->add_control( $this->filter_option_key.'_item_color',
					[
						'type' => Controls_Manager::COLOR,
						'label' => __( 'Color', 'one-elements' ),
						'selectors' => [
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li' => 'color: {{VALUE}};',
						],
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_item_color_bg', $excludes) ) {
				$this->add_control( $this->filter_option_key.'_item_color_bg',
					[
						'type' => Controls_Manager::COLOR,
						'label' => __( 'Background', 'one-elements' ),
						'selectors' => [
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li' => 'background-color: {{VALUE}};',
						],
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_border_filter_item', $excludes) ){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_border_filter_item',
						'label' => __('Border', 'one-elements' ),
						'types' => ['classic','extra'],
						'selector' => '{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li',
					]
				);

				$this->update_control( $this->filter_option_key.'_border_filter_item_background', [
					'type' => Controls_Manager::HIDDEN,
					'default' => 'classic',
					'types' => ['classic','extra'],
				]);
			}
			if ( !in_array( $this->filter_option_key.'_filter_item_shadow', $excludes) ){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $this->filter_option_key.'_filter_item_shadow',
						'label' => __( 'Shadow', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li',
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_color_tab_normal', $excludes) ){
				$this->end_controls_tab(); // end normal tab
			}

			// Hover State Tab
			if ( !in_array( $this->filter_option_key.'_color_tab_hover', $excludes) ){
				$this->start_controls_tab( $this->filter_option_key.'_color_tab_hover', [ 'label' => esc_html__( 'Hover', 'one-elements' ) ]);
			}
			if ( !in_array( $this->filter_option_key.'_item_color_hover', $excludes) ){
				$this->add_control( $this->filter_option_key.'_item_color_hover',
					[
						'type' => Controls_Manager::COLOR,
						'label' => __( 'Color', 'one-elements' ),
						'selectors' => [
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li.is-active' => 'color: {{VALUE}};',
						],
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_item_color_bg_hover', $excludes) ){
				$this->add_control( $this->filter_option_key.'_item_color_bg_hover',
					[
						'type' => Controls_Manager::COLOR,
						'label' => __( 'Background', 'one-elements' ),
						'selectors' => [
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li.is-active' => 'background-color: {{VALUE}};',
						],
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_border_filter_item_hover', $excludes) ){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_border_filter_item_hover',
						'label' => __('Border', 'one-elements' ),
						'types' => ['classic','extra'],
						'selector' => '{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:hover, {{WRAPPER}} .one-elements__filter .one-elements__filter-lists li.is-active',
					]
				);

				$this->update_control( $this->filter_option_key.'_border_filter_item_hover_background', [
					'type' => Controls_Manager::HIDDEN,
					'default' => 'classic',
					'types' => ['classic','extra'],
				]);
			}
			if ( !in_array( $this->filter_option_key.'_filter_item_shadow_hover', $excludes) ){
				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
					[
						'name' => $this->filter_option_key.'_filter_item_shadow_hover',
						'label' => __( 'Shadow', 'one-elements' ),
						'selector' => '{{WRAPPER}} .one-elements__filter .one-elements__filter-lists li:hover, {{WRAPPER}} .one-elements__filter .one-elements__filter-lists li.is-active',
					]
				);
			}

			if ( !in_array( $this->filter_option_key.'_color_tab_hover', $excludes) ){
				$this->end_controls_tab(); // end hover tab
			}
			if ( !in_array( $this->filter_option_key.'_color_tabs', $excludes) )
			{
				$this->end_controls_tabs(); // end filter color tabs
			}

		}

		protected function get_intelligent_nav_color_controls($excludes=[]) {

			// add filter colors options and border color gradient for nav.
			if ( !in_array( $this->filter_option_key.'_int_nav_color_h', $excludes) ) {

				$this->add_control( $this->filter_option_key.'_int_nav_color_h', [
					'label' => __( 'Intelligent Nav Color Customization', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'one_elements_fetch_type' => [ 'multiple' ],
						'display_type'            => [ 'filter' ],
						$this->filter_option_key.'_intelligent_carousel' => '1',
					]
				]);

			}

			if ( !in_array( $this->filter_option_key.'_int_nav_color_tabs', $excludes) ) {

				$this->start_controls_tabs( $this->filter_option_key.'_int_nav_color_tabs',  [
					'condition' => [
						'one_elements_fetch_type' => [ 'multiple' ],
						'display_type'            => [ 'filter' ],
						$this->filter_option_key.'_intelligent_carousel' => '1',
					]
				]);

			}

			if ( !in_array( $this->filter_option_key.'_int_nav_color_tab_normal', $excludes) ) {

				// Normal State Tab
				$this->start_controls_tab( $this->filter_option_key.'_int_nav_color_tab_normal',  [ 'label' => esc_html__( 'Normal', 'one-elements' ) ]);

			}

			if ( !in_array( $this->filter_option_key.'_nav_next_c_h', $excludes) ) {

				// nav next color customization
				$this->add_control( $this->filter_option_key.'_nav_next_c_h', [
					'label' => __( 'Next Nav Color', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
				]);

			}

			if ( !in_array( $this->filter_option_key.'_int_next_nav_color', $excludes) ) {

				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_nav_color',
						'label' => __( 'Next Nav Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						]
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_next_nav_bg', $excludes) ) {

				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_nav_bg',
						'label' => __( 'Next Nav BG Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						]
					]
				);

			}


			if ( !in_array( $this->filter_option_key.'_int_next_nav_br_color', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_nav_br_color',
						'label' => __( 'Next Nav border', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update filter class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						],
						'separator' => 'after',
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_nav_prev_color_h', $excludes) ) {

				// prev nav color customization
				$this->add_control( $this->filter_option_key.'_int_nav_prev_color_h', [
					'label' => __( 'Prev Nav Color', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]);

			}


			if ( !in_array( $this->filter_option_key.'_int_prev_nav_color', $excludes) ) {

				// prev nav color
				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_prev_nav_color',
						'label' => __( 'Prev Nav Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_nav_bg', $excludes) ) {

				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $this->filter_option_key.'_int_prev_nav_bg',
						'label' => __( 'Prev Nav BG Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_border_color', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' =>$this->filter_option_key.'_int_prev_border_color',
						'label' => __( 'Prev Nav border', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update filter class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_nav_color_tab_normal', $excludes) ) {
				$this->end_controls_tab(); // Normal State
			}


			if ( !in_array( $this->filter_option_key.'int_nav_color_hover_tab', $excludes) ) {
				// Hover State Tab
				$this->start_controls_tab( $this->filter_option_key.'int_nav_color_hover_tab', [ 'label' => esc_html__( 'Hover', 'one-elements' ) ]);
			}


			if ( !in_array( $this->filter_option_key.'_int_next_color_h_hover', $excludes) ) {

				// nav next color customization
				$this->add_control( $this->filter_option_key.'_int_next_color_h_hover', [
					'label' => __( 'Next Nav Hover Color', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
				]);

			}

			if ( !in_array( $this->filter_option_key.'_int_next_nav_color_hover', $excludes) ) {

				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_nav_color_hover',
						'label' => __( 'Next Nav Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						]
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_next_bg_hover', $excludes) ) {

				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_bg_hover',
						'label' => __( 'Next Nav BG Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						]
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_next_br_color_hover', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_next_br_color_hover',
						'label' => __( 'Next Nav border', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update filter class
						'condition' => [
							$this->filter_option_key.'_intelligent_carousel' => '1',
						],
						'separator' => 'after',
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_color_h_hover', $excludes) ) {

				// prev nav color customization
				$this->add_control( $this->filter_option_key.'_int_prev_color_h_hover', [
					'label' => __( 'Prev Nav Hover Color', 'one-elements' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_color_hover', $excludes) ) {

				// prev nav color
				$this->add_group_control(
					Group_Control_Text_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_prev_color_hover',
						'label' => __( 'Prev Nav Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_bg_hover', $excludes) ) {

				$this->add_group_control(
					Group_Control_Gradient_Background::get_type(),
					[
						'name' => $this->filter_option_key.'_int_prev_bg_hover',
						'label' => __( 'Prev Nav BG Color', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update nav class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'_int_prev_b_color_hover', $excludes) ) {

				$this->add_group_control(
					Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
					[
						'name' => $this->filter_option_key.'_int_prev_b_color_hover',
						'label' => __( 'Prev Nav border', 'one-elements' ),
						'types' => [ 'classic', 'gradient' ],
						'selector' => '{{WRAPPER}} .nav',//@todo; update filter class
					]
				);

			}

			if ( !in_array( $this->filter_option_key.'int_nav_color_hover_tab', $excludes) ) {
				$this->end_controls_tab(); // Hover State
			}

			if ( !in_array( $this->filter_option_key.'_nav_color_tabs', $excludes) ) {
				$this->end_controls_tabs(); // end all tabs
			}

		}
	}
}