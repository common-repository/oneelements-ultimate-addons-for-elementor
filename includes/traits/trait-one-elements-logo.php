<?php
namespace OneElements\Includes\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;
use Elementor\Utils;

if ( !trait_exists( 'One_Elements_Logo_Trait') )
{
	trait One_Elements_Logo_Trait
	{
		protected $logo;
		protected $logo_dark;
		protected $retina_logo;
		protected $retina_logo_dark;

		protected function init_content_logo_controls($options=[]) {
			if ( 'one-nav-menu' === $this->get_name() ){
				$this->start_controls_section( 'section_content_site_logo', [
						'label' => __( 'Site Logo', 'one-elements' ),
						'condition' => [
							'enable_nav_center_logo' => 'yes'
						],
					]
				);
			}else{
				$this->start_controls_section( 'section_content_site_logo', [
						'label' => __( 'Site Logo', 'one-elements' ),
					]
				);
			}


			$this->add_control(
				'enable_custom_logo',
				[
					'label' => __( 'Use custom logo', 'one-elements' ),
					'description' => __( 'This logo widget uses the global logos from "Theme Option/Customizer" by default. Set this option to "Yes" if you want to override them.', 'one-elements' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'one-elements' ),
					'label_off' => __( 'No', 'one-elements' ),
					'default' => '',
					'return_value' => 'yes',
				]
			);
			$this->add_control(
				'image',
				[
					'label' => __( 'Logo', 'one-elements' ),
					'description' => __( 'This logo will be used for bright screen', 'one-elements' ),
					'type' => Controls_Manager::MEDIA,
					'default' => $this->logo,
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
					'default' => 'full',
					'separator' => 'none',
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);

			$this->add_control(
				'retina_logo',
				[
					'label' => __( 'Retina Logo', 'one-elements' ),
					'description' => __( 'This logo will be used for retina screen', 'one-elements' ),
					'type' => Controls_Manager::MEDIA,
					'default' => $this->retina_logo,
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'retina_logo',
					'default' => 'full',
					'separator' => 'none',
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);

			$this->add_control(
				'logo_dark',
				[
					'label' => __( 'Dark Logo Sticky', 'one-elements' ),
					'description' => __( 'This logo will be used when sticky effect enabled', 'one-elements' ),
					'type' => Controls_Manager::MEDIA,
					'default' => $this->logo_dark,
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'logo_dark',
					'default' => 'full',
					'separator' => 'none',
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);

			$this->add_control(
				'retina_logo_dark',
				[
					'label' => __( 'Retina Logo Sticky', 'one-elements' ),
					'description' => __( 'This logo will be used when sticky effect enabled', 'one-elements' ),

					'type' => Controls_Manager::MEDIA,
					'default' => $this->retina_logo_dark,
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'retina_logo_dark',
					'default' => 'full',
					'separator' => 'none',
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);


			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'one-elements' ),
					'type' => Controls_Manager::CHOOSE,
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
					'prefix_class' => 'elementor%s-align-', //here %s = mobile/tablet/desktop. eg. elementor-{mobile}-align-{value}
					'default' => '',
					// 'selectors' => [
					// 	'{{WRAPPER}} .one-elements-element' => 'text-align: {{VALUE}};',
					// ],
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
					'separator' => 'before',

				]
			);


			$this->add_control(
				'link_to',
				[
					'label' => __( 'Link Logo to', 'one-elements' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'custom',
					'options' => [
						'none' => __( 'None', 'one-elements' ),
						'home' => __( 'Site Home', 'one-elements' ),
						'custom' => __( 'Custom URL', 'one-elements' ),
					],
					'condition' => [
						'enable_custom_logo' => 'yes'
					],
				]
			);

			$this->add_control(
				'link',
				[
					'label' => __( 'Link', 'one-elements' ),
					'type' => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'one-elements' ),
					'condition' => [
						'link_to' => 'custom',
						'enable_custom_logo' => 'yes'
					],
					'default' => [
						'url' =>  home_url( '/' ),
						'is_external' => false,
						'nofollow' => false,
					],
					'show_label' => false,
				]
			);


			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'one-elements' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

		}

		protected function get_site_logo() {

			$url = '';

			$custom_logo_id = get_theme_mod( 'custom_logo' );

			if ( $custom_logo_id ) {
				$url = wp_get_attachment_image_src( $custom_logo_id, 'full' )[0];
			}

			return [
				'id' => $custom_logo_id,
				'url' => $url,
			];

		}

		protected function get_logo_from_redux( $logo_opt_name='logo' ) {

			$ops = get_option( 'xlaw_opts'); // get redux options
			$id = false;
			$url = '';
			
			if ( !empty( $ops[$logo_opt_name]['id']) && !empty( $ops[$logo_opt_name]['url']) ) {
				$id = $ops[$logo_opt_name]['id'];
				$url = $ops[$logo_opt_name]['url'];
			}

			return [
				'id' => $id,
				'url' => $url,
			];

		}

		protected function init_style_logo_controls() {

			if ( 'one-nav-menu' === $this->get_name() ) {

				$this->start_controls_section(
					'section_style_image',
					[
						'label' => __( 'Site Logo', 'one-elements' ),
						'tab'   => Controls_Manager::TAB_STYLE,
						'condition' => [
							'enable_nav_center_logo' => 'yes'
						],

					]
				);

				$this->add_responsive_control(
					'logo_margin',
					[
						'label' => __( 'Logo Space', 'one-elements' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
							'{{WRAPPER}} .one-elements-center-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
						],

					]
				);

			} else {

				$this->start_controls_section(
					'section_style_image',
					[
						'label' => __( 'Site Logo', 'one-elements' ),
						'tab'   => Controls_Manager::TAB_STYLE,
					]
				);
				
			}

			$this->add_responsive_control(
				'width',
				[
					'label' => __( 'Width', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'size_units' => [ '%', 'px', 'vw' ],
					'range' => [
						'%' => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 1000,
						],
						'vw' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-logo' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'space',
				[
					'label' => __( 'Max Width', 'one-elements' ) . ' (%)',
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 1,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-logo' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'separator_panel_style',
				[
					'type' => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);

			$this->start_controls_tabs( 'image_effects' );

			$this->start_controls_tab( 'normal',
				[
					'label' => __( 'Normal', 'one-elements' ),
				]
			);

			$this->add_control(
				'opacity',
				[
					'label' => __( 'Opacity', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 1,
							'min' => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-logo' => 'opacity: {{SIZE}};',
					],
				]
			);


			$this->end_controls_tab();

			$this->start_controls_tab( 'hover',
				[
					'label' => __( 'Hover', 'one-elements' ),
				]
			);

			$this->add_control(
				'opacity_hover',
				[
					'label' => __( 'Opacity', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 1,
							'min' => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-logo:hover' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_control(
				'background_hover_transition',
				[
					'label' => __( 'Transition Duration', 'one-elements' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 3,
							'step' => 0.1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .one-elements-logo' => 'transition-duration: {{SIZE}}s',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

		}

		protected function get_logos(  $use_custom = false , $settings = []  ) {

			if ( $use_custom ) {

				$settings = !empty( $settings) ? $settings : $this->get_settings_for_display();

				$logo  = !empty( $settings['image' ]['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings['image' ]['id'], 'image', $settings ) : '';

				$logo_dark  = !empty( $settings['logo_dark']['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings['logo_dark' ]['id'], 'logo_dark', $settings ) : $logo;

				$retina_logo  = !empty( $settings['retina_logo']['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings['retina_logo']['id'], 'retina_logo', $settings ) : '';

				$retina_dark_logo  = !empty( $settings['retina_logo_dark']['id']) ? Group_Control_Image_Size::get_attachment_image_src( $settings['retina_logo_dark']['id'], 'retina_logo_dark', $settings ) : $retina_logo;

			} else {

				// default logos
				$logo = ($this->logo['url'] && $this->logo['id']) ? $this->logo['url'] : '';
				$logo_dark = ($this->logo_dark['url'] && $this->logo_dark['id']) ? $this->logo_dark['url'] : $logo;
				$retina_logo = ($this->retina_logo['url'] && $this->retina_logo['id']) ? $this->retina_logo['url'] : '';
				$retina_dark_logo = ($this->retina_logo_dark['url'] && $this->retina_logo_dark['id']) ? $this->retina_logo_dark['url'] : $retina_logo;

			}

			return compact( 'logo', 'logo_dark', 'retina_logo', 'retina_dark_logo');

		}

		/**
		 * Retrieve site logo widget link URL.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $settings
		 *
		 * @return array|string|false An array/string containing the link URL, or false if no link.
		 */
		protected function get_link_url( $settings ) {

			switch ($settings['link_to']){
				case 'none':
					return false;
					break;
				case 'home':
					return [
						'url' => home_url('/'),
					];
					break;
				case 'custom':
					if ( empty( $settings['link']['url'] ) ) {
						return false;
					}
					return $settings['link'];
					break;
				default:
					return false;
			}
		}

		/**
		 * Render site logo widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function get_rendered_logo() {

			$settings = $this->get_settings_for_display();

			if ( empty( $this->logo['id'] ) && empty( $settings['image']['id'] )) return;

			$use_custom = (!empty($settings['enable_custom_logo']) && 'yes' == $settings['enable_custom_logo']);

			$this->add_render_attribute( 'wrapper', 'class', 'one-elements-element one-elements-logo' );

			$link = $use_custom  ? $this->get_link_url( $settings ) : [ 'url' => home_url('/') ];

			if ( $link ) {

				$this->add_render_attribute( 'link', [
					'href' => $link['url']
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
			// create/get logos with size: logo, logo_dark, retina_logo, retina_logo_dark
			$logos = $this->get_logos( $use_custom, $settings );

			//extract( $logos );// extract is 2--80% slower than foreach, so use foreach
			foreach ( $logos as $k => $v ){ $$k = $v; }
			ob_start();
			?>

			<span <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php if ( !empty( $logo) ) { ?>
					<span class="one-elements-logo__regular">
						<?php if ( $link ) { ?> <a <?php $this->print_render_attribute_string( 'link' ); ?>> <?php } ?>
							<img src="<?php echo esc_attr(esc_url( $logo));?>" alt="bar" <?php if(!empty( $retina_logo)){ echo "srcset='".esc_attr(esc_url( $retina_logo ))." 2x'";}?> />
							<?php if ( $link ) { ?> </a> <?php } ?>
					</span>
				<?php } ?>

				<?php if ( !empty( $logo_dark) ) { ?>
					<span class="one-elements-logo__sticky">
						<?php if ( $link ) { ?><a <?php $this->print_render_attribute_string( 'link' ); ?>><?php } ?>
							<img src="<?php echo esc_attr(esc_url( $logo_dark));?>" alt="bar" <?php if(!empty( $retina_dark_logo)){ echo "srcset='".esc_attr(esc_url( $retina_dark_logo ))." 2x'";}?> />

							<?php if ( $link ) { ?></a><?php } ?>
					</span>
				<?php } ?>

			</span>

			<?php
			return ob_get_clean();
		}

		protected function print_rendered_logo(  )
		{
            echo $this->get_rendered_logo(); // escaped data returned
		}



	}
}