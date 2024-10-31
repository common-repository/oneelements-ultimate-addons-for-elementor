<?php
namespace OneElements\Includes\Widgets\Logos;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Carousel_Trait;
use OneElements\Includes\Traits\One_Elements_Common_Widget_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Widget_OneElements_Logos extends Widget_Base {
	protected $carousel_option_key = 'one_elements_logos_c';
	use One_Elements_Common_Widget_Trait;

	use One_Elements_Carousel_Trait;
	/**
	 * Get widget name.
	 *
	 * Retrieve icon widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-logos';
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

	public function get_keywords() {
		return ['logo', 'brands', 'clients', 'carousel', 'slider', 'grid', 'image'];
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
		return 'one-elements-widget-eicon eicon-logo';
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return __( 'Logos & Brands', 'one-elements' );
	}


	/**
	 * Register icon widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// CONTENT
		$this->init_content_logos_controls();
		$this->init_content_layout_controls();
		$this->get_content_carousel_controls();

		// STYLE
		$this->get_style_logos_controls();
		$this->init_style_logos_background_settings();
		$this->init_style_border_n_shadow_settings();

	}

	private function init_content_logos_controls() {

		$this->start_controls_section( 'one_elements_section_logo_content', [
			'label' => __( 'Logos', 'one-elements' ),
		]);

		$slide = new Repeater();

		$slide->add_control( 'logo', [
			'label' => __( 'Logo', 'one-elements' ),
			'type' => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		]);

		$slide->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'		=> 'logo',
			'default'	=> 'xlaw-option-thumb',
			'condition' => [
				'logo[url]!' => '',
			],
		]);

		$slide->add_control( 'title', [
			'label' => __( 'Title', 'one-elements' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Client', 'one-elements' ),
			'placeholder' => __( 'Enter client title', 'one-elements' ),
		]);

		$slide->add_control( 'link', [
			'label' => __( 'Link', 'one-elements' ),
			'description' => __( 'You can link the current logo to this URL or keep it blank', 'one-elements' ),
			'type' => Controls_Manager::URL,
			'placeholder' => __( 'https://your-link.com', 'one-elements' ),
			'show_external' => true,
			'default' => [
				'url' => '',
				'is_external' => false,
				'nofollow' => false,
			],
		]);

		$this->add_control( 'one_elements_logos', [
			'label' => __( 'Logo items', 'one-elements' ),
			'type' => Controls_Manager::REPEATER,
			'fields' => $slide->get_controls(),
			'default' => [
				[
					'logo' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'link'=> [
						'url' => '',
						'is_external' => false,
						'nofollow' => false,
					]

				],
			],
			//@TODO; title field image markup may need to improve later. just outputting the image in title.
			'title_field' => '<img style="max-width: 40px !important; max-height: 40px !important;" src="{{{ logo[\'url\'] }}}" > {{title}} ',
		]);

		$this->end_controls_section();

	}

	private function init_content_layout_controls() {

		$this->start_controls_section( 'one_logo_layout_section', [
			'label' => __( 'Layout Settings', 'one-elements' ),
		]);

		// we need this hidden field so that carousel trait works out of the box.
		$this->add_control( 'one_elements_fetch_type', [
			'label'       => __( 'Data Type', 'one-elements' ),
			'type'        => Controls_Manager::HIDDEN,
			'default'   => 'multiple',
		]);

		$this->add_control( 'layout', [
			'label'       => __( 'Layout', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'layout-default',
			'options' => [
				'layout-default'   => __( 'Default', 'one-elements' ),
				'layout-1'   => __( 'Layout 1', 'one-elements' ),
			],
			'prefix_class' => 'oee-logos--',
			'render_type' => 'template'
		]);

		$this->add_control( 'display_type', [
			'label'       => __( 'Display Type', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'carousel',
			'options' => [
				'grid'   => __( 'Grid', 'one-elements' ),
				'carousel'   => __( 'Carousel', 'one-elements' ),
			],
			'render_type' => 'template'
		]);

		$this->init_content_grid_controls();

		$this->end_controls_section();
	}

	private function init_content_grid_controls() {

		$this->add_responsive_control( 'items_per_row', [
			'label' => __( 'Items Per Row', 'one-elements' ),
			'type' => Controls_Manager::NUMBER,
			'default' => 5,
			'min' => 1,
			'max' => 12,
			'step' => 1,
			'condition' => [
				'display_type' => 'grid'
			],
			'selectors' => [
				'{{WRAPPER}} .one-elements-logos--inner > .oee__row > .oee__item' => 'width: calc(100%/{{VALUE}});',
			],
		]);

		$this->add_control( 'items_gap', [
			'label'       => __( 'Items Gap', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''          => __( 'Default', 'one-elements' ),
				'no-gap'    => __( 'No Gap', 'one-elements' ),
				'narrow'    => __( 'Narrow', 'one-elements' ),
				'extended'    => __( 'Extended', 'one-elements' ),
				'wide'    => __( 'Wide', 'one-elements' ),
				'wider'    => __( 'Wider', 'one-elements' ),

			],
			'condition' => [
				'display_type' => 'grid'
			]
		]);

	}

	private function get_style_logos_controls() {

		$this->start_controls_section( 'style_logo_section', [
			'label' => __( 'Logo', 'one-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]);

		$this->add_control( 'logos_box_style_heading', [
			'label'       => __( 'Item Box Style', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
		]);

		$this->add_responsive_control( 'logo_box_height', [
			'label' => __( 'Logo Box Height', 'one-elements' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'range' => [
				'px' => [
					'min' => 1,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .one-elements-single-logo' => 'height: {{SIZE}}{{UNIT}};',
			],
		]);

		$this->add_responsive_control( 'logo_box_padding', [
			'label' => __( 'Logo Box Padding', 'one-elements' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors' => [
				'{{WRAPPER}} .one-elements-single-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]);

		$this->add_control( 'logos_title_style_heading', [
			'label'       => __( 'Title Style', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'layout' => 'layout-1'
			],
			'separator' => 'before'
		]);

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'typography',
			'label' => __( 'Title Typography', 'one-elements' ),
			'selector'  => '{{WRAPPER}} .one-elements-single-logo .one-elements-heading',
			'condition' => [
				'layout' => 'layout-1'
			],
		]);

		$this->add_responsive_control( 'title_margin', [
			'label'      => __( 'Title Margin', 'one-elements' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 50,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .one-elements-single-logo .one-elements-heading' => 'margin-top: {{SIZE}}{{UNIT}};'
			],
			'condition' => [
				'layout' => 'layout-1'
			],
		]);

		$this->start_controls_tabs( 'tabs_title_color', [
			'condition' => [
				'layout' => 'layout-1'
			],
		]);

		$this->start_controls_tab( 'tab_title_color_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'title_color',
			'label' => __( 'Title Color', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-single-logo .one-elements-heading'
		]);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_title_color_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'title_color_hover',
			'label' => __( 'Title Color', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-single-logo:hover .one-elements-heading'
		]);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_logos_background_settings() {

		$this->start_controls_section(
			'logo_background_section',
			[
				'label' => __( 'Background', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Background', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-single-logo > .one-elements-element__regular-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'selector' => '{{WRAPPER}} .one-elements-single-logo > .one-elements-element__hover-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_border_n_shadow_settings() {

		$this->start_controls_section(
			'logo_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_logo_border' );

		$this->start_controls_tab(
			'tab_logo_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'logo_border',
				'label' => __( 'Logo Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-single-logo > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-single-logo, {{WRAPPER}} .one-elements-single-logo > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-single-logo > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-single-logo > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'logo_box_shadow',
				'label' => __( 'Logo Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-single-logo',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_logo_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'logo_border_hover',
				'label' => __( 'Logo Border', 'one-elements' ),
				'selector' => '{{WRAPPER}}:hover .one-elements-single-logo > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-single-logo:hover, {{WRAPPER}} .one-elements-single-logo:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-single-logo:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-single-logo:hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_logo_box_shadow',
				'label' => __( 'Logo Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}}:hover .one-elements-single-logo:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	public function is_carousel_enabled() {
		return $this->get_settings_for_display('display_type') == 'carousel';
	}

	public function print_single_logo_layout( $logo ) {

		$layout = $this->get_settings_for_display('layout');

		?>

		<div <?php $this->print_render_attribute_string( 'single_logo' ); ?>>

			<div class="one-elements-element one-elements-single-logo">

				<!-- Regular Background -->
				<span <?php $this->print_render_attribute_string('logo_regular_state'); ?>>
					<span class="one-elements-element__state-inner"></span>
				</span>
				
				<!-- Hover Background -->
				<span <?php $this->print_render_attribute_string('logo_hover_state'); ?>>
					<span class="one-elements-element__state-inner"></span>
				</span>
				
				<!-- Logo Content -->
				<div class="one-elements-element__content">
					
					<?php $this->print_image( $logo ); ?>
					<?php if ( $layout == 'layout-1' ) $this->print_title( $logo ); ?>

				</div>
				
				<!-- Whole Element Linking -->
				<?php $this->print_link( $logo ); ?>

			</div>

		</div>

		<?php

	}

	/**
	 * Render icon widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty($settings['one_elements_logos']) ) return; // vail early if no logo found

		$items_gap_class = $this->get_items_gap_class( $settings['items_gap'] );

		$this->add_render_attribute( 'logos_parent', 'class', 'oee__row oee__row-wrap oee__align-middle' );
		$this->add_render_attribute( 'logos_parent', 'class', $items_gap_class );
		$this->add_render_attribute( 'single_logo', 'class', 'oee__item' );

		if ( $this->is_carousel_enabled() ) {
			$this->add_render_attribute( 'logos_parent', 'class', 'one-elements__carousel-inner' );
			$this->start_carousel_markup();
		}

		$this->add_render_attribute( 'logo_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['logo_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'logo_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'logo_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['logo_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'logo_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		?>

        <div class="one-elements--logos">

        	<div class="oee__column-gap--reset">

        		<div class="one-elements-logos--inner">

        			<div <?php $this->print_render_attribute_string( 'logos_parent' ); ?>>
		
						<?php foreach ( $settings['one_elements_logos'] as $logo ) : ?>
						
							<?php if (empty( $logo['logo']['url'])) continue; ?>

							<?php $this->print_single_logo_layout( $logo ); ?>

						<?php endforeach; ?>

					</div>

				</div>

			</div>

        </div>

		<?php

		if ( $this->is_carousel_enabled() ) $this->end_carousel_markup();

	}

	private function print_image( $logo ) {

		$image = $logo['logo'];
		$img_ext = pathinfo( $image['url'], PATHINFO_EXTENSION);

		$image_size_atts = '';

		if ( 'svg' == strtolower( $img_ext) ) {
			$size = one_get_image_size( $logo['logo_size'] );
			if ($size) $image_size_atts = " height='auto' width='{$size['width']}px' ";
		}

		$src = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'logo', $logo );
        if (empty( $src )) return;
		$alt_text = isset( $logo['title'] ) && '' !==  $logo['title'] ? $logo['title'] :__( 'Client', 'one-elements' );

		?>

        <div class="one-elements-image">
			<?php echo sprintf( "<img src='%s' alt='%s' %s />", $src, $alt_text, $image_size_atts ); ?>
        </div>

		<?php

	}

	private function print_title( $logo ) {

		if ( empty($logo['title']) ) return;

		?>

        <div class="one-elements-heading"><?php echo $logo['title']; ?></div>

		<?php

	}

	private function print_link( $logo ) {

		// link related stuff
		$href = !empty( $logo['link']['url'] ) ? esc_attr( esc_url($logo['link']['url']) ) : '';
		$target = !empty( $logo['link']['is_external'] ) ? '_blank' : '_self';

		if ( !empty($logo['link']['url']) ) {
			echo "<a class='one-elements-element__link' href='{$href}' target='{$target}'></a>";
		}

	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Logos() );
