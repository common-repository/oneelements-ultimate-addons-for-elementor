<?php
namespace OneElements\Includes\Widgets\Testimonials;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Carousel_Trait;
use OneElements\Includes\Traits\One_Elements_Common_Widget_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_OneElements_Testimonials extends Widget_Base {

	protected $carousel_option_key = 'one_elements_testimonials_c';

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
		return 'one-elements-testimonials';
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
		return ['testimonial', 'reviews', 'feedback', 'comment'];
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
		return 'one-elements-widget-eicon eicon-testimonial';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return __( 'Testimonials', 'one-elements' );
	}
	protected function get_layout_card_styles() {

		return apply_filters('one_elements_testimonials_layout_styles', [
			'' => __( 'Select a style', 'one-elements' ),
			'classic-card-1'   => __( 'Classic Card 1', 'one-elements' ),
			'classic-card-2'   => __( 'Classic Card 2', 'one-elements' ),
			'classic-card-3'   => __( 'Classic Card 3', 'one-elements' ),
			// 'classic-card-4'   => __( 'Classic Card 4', 'one-elements' ),
			// 'modern-card-1' => __( 'Modern Card 1', 'one-elements' ),
			// 'modern-card-2' => __( 'Modern Card 2', 'one-elements' ),
			// 'modern-card-3' => __( 'Modern Card 3', 'one-elements' ),
			// 'modern-card-4' => __( 'Modern Card 4', 'one-elements' ),
			// 'modern-card-5' => __( 'Modern Card 5', 'one-elements' ),
		]);

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
		$this->get_content_testimonials_controls();
		$this->get_content_layout_controls();
		$this->get_content_carousel_controls();

		// STYLE
		$this->get_style_testimonials_controls();
		$this->init_style_testimonials_background_settings();
		$this->init_style_border_n_shadow_settings();

	}

	private function get_content_testimonials_controls() {

		$this->start_controls_section(
			'one_elements_section_testimonial_content',
			[
				'label' => __( 'Testimonials', 'one-elements' ),
			]
		);

		$slide = new Repeater();

		$slide->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'one-elements' ),
				'description' => esc_html__( 'Enter the name of the user who gave this testimony', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Von Doe', 'one-elements' ),
				'dynamic' => [ 'active' => true ],//it will allow dynamic user
			]
		);
		$slide->add_control(
			'designation',
			[
				'label' => esc_html__( 'Designation', 'one-elements' ),
				'description' => esc_html__( 'Enter the designation of the testimony user', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'eg. CEO of ThemeXclub', 'one-elements' ),
			]
		);
		$slide->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'one-elements' ),
				'description' => esc_html__( 'You can use a short title to summarize its content. Leave it empty to hide it', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
			]
		);
		$slide->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'one-elements' ),
				'description' => esc_html__( 'Enter your testimonial content here.', 'one-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Add testimonial description here. Edit and place with your own text.', 'one-elements' ),
			]
		);
		$slide->add_control(
			'rating',
			[
				'label' => __( 'Rating', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'default' => 5,
			]
		);
		$slide->add_control(
			'link',
			[
				'label' => __( 'Link', 'one-elements' ),
				'description' => __( 'You can link this testimonial to a URL or keep it blank', 'one-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'one-elements' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);
		$slide->add_control(
			'user_image',
			[
				'label' => __( 'User Image', 'one-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$slide->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'		=> 'user_image',
				'default'	=> '',
				'condition' => [
					'user_image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'one_elements_testimonials',
			[
				'label' => __( 'Testimonial items', 'one-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $slide->get_controls(),
				'default' => [
					[
						'name' => __( 'John Doe', 'elementor' ),
						'designation' => __( 'CEO @ABC Company', 'elementor' ),
						'content' => __( 'This is demo testimonial content. Change this text...', 'elementor' ),
						'rating' => 4,
						'user_image' => [
							'url' => Utils::get_placeholder_image_src(),
						]

					],
				],
				//@TODO; title field image markup may need to improve later. just outputting the image in title.
				'title_field' => '<div style="display: flex; justify-content: flex-start; align-items: center;"><img style="padding-right: 15px; max-width: 50px !important; max-height: 100% !important;" src="{{{ user_image[\'url\'] }}}" > {{{ name }}} </div>',
			]
		);

		$this->end_controls_section();

	}

	private function get_content_visibility_controls() {

		$this->add_control(
			'testimonial_content_visibility',
			[
				'label' => __( 'Content Visibility Settings', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'enable_avatar',
			[
				'label' => esc_html__( 'Display Avatar?', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'enable_designation',
			[
				'label' => esc_html__( 'Display Designation?', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'enable_title',
			[
				'label' => esc_html__( 'Display Title?', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'enable_quote',
			[
				'label' => esc_html__( 'Display testimonial icon?', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
		$this->add_control(
			'enable_rating',
			[
				'label' => esc_html__( 'Display Rating?', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

	}

	private function get_content_layout_rating_controls() {

		$this->add_control(
			'testimonial_rating_heading',
			[
				'label' => __( 'Ratings Settings', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
				    'enable_rating' => 'yes',
                ]
			]
		);
		$this->add_control(
			'rating_scale',
			[
				'label' => __( 'Rating Scale', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'5' => '0-5',
					'10' => '0-10',
				],
				'default' => '5',
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_control(
			'star_style',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'star_fontawesome' => 'Font Awesome',
					'star_unicode' => 'Unicode',
				],
				'default' => 'star_fontawesome',
				'render_type' => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator' => 'before',
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_control(
			'unmarked_star_style',
			[
				'label' => __( 'Unmarked Style', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => __( 'Solid', 'one-elements' ),
						'icon' => 'eicon-star',
					],
					'outline' => [
						'title' => __( 'Outline', 'one-elements' ),
						'icon' => 'eicon-star-o',
					],
				],
				'default' => 'solid',
				'condition' => [
				    'enable_rating' => 'yes',
			    ]
			]
		);

		$this->add_responsive_control(
			'rating_stars_size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'rating_stars_space',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

	}

	private function get_content_layout_controls() {

		$this->start_controls_section(
			'one_testimonial_layout_section',
			[
				'label' => __( 'Layout Settings', 'one-elements' ),
			]
		);

		// we need this hidden field so that carousel trait works out of the box.
		$this->add_control( 'one_elements_fetch_type', [
			'label'       => __( 'Data Type', 'one-elements' ),
			'type'        => Controls_Manager::HIDDEN,
			'default'   => 'multiple',
		]);
		$this->add_control( 'layout_style_heading', [
			'label' => __( 'Layout Style Customization', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
		]);
		$this->add_control(
			'layout_style',
			[
				'label'     => __( 'Layout Style', 'one-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'classic-card-1',
				'options'   => $this->get_layout_card_styles(),
			]
		);

		$this->add_control(
			'display_type',
			[
				'label'       => __( 'Display Type', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'   => __( 'Grid', 'one-elements' ),
					'carousel'   => __( 'Carousel', 'one-elements' ),
				],
			]
		);
		$this->add_responsive_control(
			'items_per_row',
			[
				'label' => __( 'Items Per Row', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'condition' => [
					'display_type!' => 'carousel'
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->get_name().'--inner > .oee__row > .oee__item' => 'width: calc(100%/{{VALUE}});',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'items_gap',
			[
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
			]
		);


		$this->add_control(
			'content_position',
			[
				'label'       => __( 'Content Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''   => __( 'Default', 'one-elements' ),
					'top'   => __( 'Top', 'one-elements' ),
					'bottom'   => __( 'Bottom', 'one-elements' ),
				],
				'condition' => [
					'layout_style' => 'classic-card-1'
				]
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->get_content_visibility_controls();
		$this->get_content_layout_rating_controls();

		$this->end_controls_section();

	}

	private function get_style_testimonials_controls() {

		$this->start_controls_section(
			'style_testimonial_section',
			[
				'label' => __( 'Testimonials', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		//COLORS
		$this->_init_style_color_controls();
		// TYPOGRAPHY
		$this->_init_style_typography_controls();
		//SPACING
		$this->_init_style_spacing_controls();
		//IMAGE
		$this->_init_style_image_controls();
		$this->end_controls_section();

	}

	private function _init_style_color_controls() {

		$this->add_control(
			'style_testimonial_colors_heading',
			[
				'label' => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->start_controls_tabs( 'testimonial_colors' );
		// normal tab
		$this->start_controls_tab(
			'testimonial_colors_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_name_color',
				'label' => __( 'Name', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-name .one-elements-element__content',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_designation_color',
				'label' => __( 'Designation', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-designation .one-elements-element__content',
				'condition' => [
					'enable_designation' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_title_color',
				'label' => __( 'Title', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-title .one-elements-element__content',
				'condition' => [
					'enable_title' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_content_color',
				'label' => __( 'Content', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-text-editor',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_rating_color',
				'label' => __( 'Rating', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-star-rating .elementor-star-full:before',
				'condition' => [
				    'enable_rating' => 'yes'
                ]
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'rating_stars_unmarked_color',
				'label' => __( 'Unmarked Stars Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-star-rating .elementor-star-empty',
				'separator' => 'after',
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_icon_color',
				'label' => __( 'Testimonial Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .oee__testimonial-icon i',
				'condition' => [
					'enable_quote' => 'yes'
				]
			]
		);
		$this->end_controls_tab();
		// hover tab
		$this->start_controls_tab(
			'testimonial_colors_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_name_color_hover',
				'label' => __( 'Name', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-name .one-elements-element__content',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_designation_color_hover',
				'label' => __( 'Designation', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-designation .one-elements-element__content',
				'condition' => [
					'enable_designation' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_title_color_hover',
				'label' => __( 'Title', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-title .one-elements-element__content',
				'condition' => [
					'enable_title' => 'yes'
				]
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name'      => 'testimonial_content_color_hover',
				'label'     => __( 'Content', 'one-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .one-elements-testimonial_single:hover .elementor-text-editor',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_rating_color_hover',
				'label' => __( 'Rating', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .elementor-star-rating .elementor-star-full:before',
				'condition' => [
					'enable_rating' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'rating_stars_unmarked_color_hover',
				'label' => __( 'Unmarked Stars Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .elementor-star-rating .elementor-star-empty',
				'separator' => 'after',
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'testimonial_icon_color_hover',
				'label' => __( 'Testimonial Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .oee__testimonial-icon i',
				'condition' => [
					'enable_quote' => 'yes'
				]
			]
		);

		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs

	}

	private function _init_style_typography_controls() {

		$this->add_control(
			'style_testimonial_typography_heading',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonial_name_typography',
				'label' => __( 'Name', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-name .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonial_designation_typography',
				'label' => __( 'Designation', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-designation .one-elements-element__content',
				'condition' => [
					'enable_designation' => 'yes',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonial_title_typography',
				'label' => __( 'Title', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-title .one-elements-element__content',
				'condition' => [
					'enable_title' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'testimonial_content_typography',
				'label' => __( 'Content', 'one-elements' ),
				'selector' => '{{WRAPPER}} .elementor-text-editor',
			]
		);

	}

	private function _init_style_spacing_controls() {

		$this->add_control(
			'style_testimonial_spacing_heading',
			[
				'label' => __( 'Spacings', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_responsive_control(
			'testimonial_margin',
			[
				'label' => __( 'Testimonial Box Padding', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-testimonial_single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'avatar_margin',
			[
				'label' => __( 'Image', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_avatar' => 'yes',
				]
			]
		);
		$this->add_responsive_control(
			'name_margin',
			[
				'label' => __( 'Name', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'designation_margin',
			[
				'label' => __( 'Designation', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_designation' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_title' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label' => __( 'Content', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-text-editor' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_margin',
			[
				'label' => __( 'Rating', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_rating' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'testimonial_icon_margin',
			[
				'label' => __( 'Testimonial Icon', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .oee__testimonial-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_quote' => 'yes',
				]
			]
		);

	}

	private function _init_style_image_controls() {

		$this->add_control(
			'style_one-elements-image_heading',
			[
				'label' => __( 'Image', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'enable_avatar' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'testimonial_style_image_tabs', [
			'condition' => [
				'enable_avatar' => 'yes'
			]
        ]);

		// Normal tab
		$this->start_controls_tab(
			'testimonial_style_image_tab_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__regular-state',
				'_padding' => '{{WRAPPER}} .one-elements-image',
			]
		);
		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-image, {{WRAPPER}} .one-elements-image .one-elements-element__content *, {{WRAPPER}} .one-elements-image > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-image > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-image > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'image_border_box_shadow',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image',
			]
		);

		$this->end_controls_tab();
		// Hover tab
		$this->start_controls_tab(
			'testimonial_style_image_tab_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'image_border_hover',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-image > .one-elements-element__hover-state',
				'_padding' => '{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image',
			]
		);
		$this->add_responsive_control(
			'image_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image, {{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'image_border_box_shadow_hover',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover .one-elements-image',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	private function init_style_testimonials_background_settings() {

		$this->start_controls_section(
			'testimonial_background_section',
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
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__regular-state .one-elements-element__state-inner',
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
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__hover-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_border_n_shadow_settings() {

		$this->start_controls_section(
			'testimonial_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_testimonial_border' );

		$this->start_controls_tab(
			'tab_testimonial_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'testimonial_border',
				'label' => __( 'Testimonials Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-testimonial_single, {{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'testimonial_box_shadow',
				'label' => __( 'Testimonials Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_testimonial_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'testimonial_border_hover',
				'label' => __( 'Testimonials Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-testimonial_single:hover, {{WRAPPER}} .one-elements-testimonial_single:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-testimonial_single:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-testimonial_single:hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_testimonial_box_shadow',
				'label' => __( 'Testimonials Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-testimonial_single:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function get_rating( $testimonial ) {

		$settings = $this->get_settings_for_display();

		$rating_scale = (int) $settings['rating_scale'];
		$rating = (float) $testimonial['rating'] > $rating_scale ? $rating_scale : $testimonial['rating'];

		return [ $rating, $rating_scale ];

	}

	private function render_stars( $icon, $testimonial ) {

		$rating_data = $this->get_rating($testimonial);
		$rating = $rating_data[0];
		$floored_rating = (int) $rating;
		$stars_html = '';

		for ( $stars = 1; $stars <= $rating_data[1]; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
			}
		}

		return $stars_html;

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

		if ( empty($settings['one_elements_testimonials']) ) return; // vail early if no testimonial found

		$card_style = $settings['layout_style'];
		$is_carousel_enabled = $this->is_carousel_enabled( $card_style );
		$items_gap_class = $this->get_items_gap_class( $settings['items_gap'] );
		$card_style_class = 'card_style--' . $card_style;

		$this->add_render_attribute( 'testimonial_cards_parent', 'class', 'oee__row oee__row-wrap oee__align-middle' );
		$this->add_render_attribute( 'testimonial_cards_parent', 'class', $card_style_class );
		$this->add_render_attribute( 'testimonial_cards_parent', 'class', $items_gap_class );

		$this->add_render_attribute( 'testimonial_cards_single', 'class', 'oee__item' );

		//single testimonial item background
		$this->add_render_attribute( 'testimonial_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['testimonial_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'testimonial_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'testimonial_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['testimonial_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'testimonial_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( $is_carousel_enabled ) {
			$this->add_render_attribute( 'testimonial_cards_parent', 'class', 'one-elements__carousel-inner' );
			$this->start_carousel_markup();
		}

		?>
		<div class="one-elements-testimonials">
            <div class="oee__column-gap--reset">
				<div class="one-elements-testimonials--inner">
					<div <?php $this->print_render_attribute_string( 'testimonial_cards_parent' ); ?>>
                        <?php foreach ( $settings['one_elements_testimonials'] as $index => $testimonial ){ ?>
                        	<div <?php $this->print_render_attribute_string( 'testimonial_cards_single' ); ?>>
                                <?php $this->print_single_testimonial( $testimonial, $card_style, $settings ); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
		if ( $is_carousel_enabled ) $this->end_carousel_markup();

	}

	protected function print_single_testimonial( $testimonial, $card_style='', $settings=[] ) {
		
		?>

        <div class="one-elements-element one-elements-testimonial_single">

            <!-- Regular Background -->
            <span <?php $this->print_render_attribute_string('testimonial_regular_state'); ?>>
                <span class="one-elements-element__state-inner"></span>
            </span>

            <!-- Hover Background -->
            <span <?php $this->print_render_attribute_string('testimonial_hover_state'); ?>>
                <span class="one-elements-element__state-inner"></span>
            </span>

            <!-- Testimonial Content -->
			<?php $this->print_testimonial_contents( $testimonial, $card_style, $settings ); ?>

        </div>

		<?php

	}

	private function is_enabled_avatar() {

		$enable_avatar = $this->get_settings_for_display( 'enable_avatar' );

		return !empty( $enable_avatar ) && 'yes' == $enable_avatar;

	}

	private function print_testimonial_contents__classic_card_1( $testimonial, $card_style, $settings ) {

		$content_position = empty( $settings['content_position'] ) ? 'bottom' : $settings['content_position'];
		
		$enabled_avatar = $this->is_enabled_avatar();

		?>

		<div class="one-elements-element__content">

			<?php if ( $content_position == 'top' ): ?>
				
				<div class="oee__content_row">
					<?php $this->print_title( $testimonial ); ?>
					<?php $this->print_widget_content( $testimonial ); ?>
				</div>
				
			<?php endif; ?>

			<div class="oee__content_row">
				
				<?php if ( $enabled_avatar ): ?>

					<div class="oee__content_column">
						<?php $this->print_image( $testimonial ); ?>
					</div>

					<div class="oee__content_column">
					
				<?php endif; ?>

				<?php $this->print_name( $testimonial ); ?>
				<?php $this->print_designation( $testimonial ); ?>
				<?php $this->print_rating( $testimonial ); ?>
				
				<?php if ( $enabled_avatar ): ?>

					</div>

				<?php endif; ?>

				<?php $this->print_testimonial_icon(); ?>

			</div>

			<?php if ( $content_position == 'bottom' ): ?>

				<div class="oee__content_row">
					<?php $this->print_title( $testimonial ); ?>
					<?php $this->print_widget_content( $testimonial ); ?>
				</div>
				
			<?php endif; ?>

		</div>

		<?php

	}

	private function print_testimonial_contents__classic_card_2( $testimonial, $card_style, $settings ) {
		
		$enabled_avatar = $this->is_enabled_avatar();

		?>

		<div class="one-elements-element__content">
			
			<div class="oee__content_row">
				<?php $this->print_title( $testimonial ); ?>
				<?php $this->print_widget_content( $testimonial ); ?>
			</div>

			<div class="oee__content_row">
				<?php $this->print_name( $testimonial ); ?>
				<?php $this->print_designation( $testimonial ); ?>
				<?php $this->print_rating( $testimonial ); ?>
				<?php $this->print_testimonial_icon(); ?>
			</div>

		</div>
			
		<?php if ( $enabled_avatar ) $this->print_image( $testimonial );

	}

	private function print_testimonial_contents__classic_card_3( $testimonial, $card_style, $settings ) {
		
		$enabled_avatar = $this->is_enabled_avatar();

		?>

		<div class="one-elements-element__content">
				
			<div class="oee__content_row">
				<?php $this->print_testimonial_icon(); ?>
				<?php $this->print_title( $testimonial ); ?>
				<?php $this->print_widget_content( $testimonial ); ?>
			</div>

			<div class="oee__content_row">
				
				<?php if ( $enabled_avatar ): ?>

					<div class="oee__content_column">
						<?php $this->print_image( $testimonial ); ?>
					</div>

					<div class="oee__content_column">
					
				<?php endif; ?>

				<?php $this->print_name( $testimonial ); ?>
				<?php $this->print_designation( $testimonial ); ?>
				<?php $this->print_rating( $testimonial ); ?>
				
				<?php if ( $enabled_avatar ): ?>

					</div>

				<?php endif; ?>

			</div>

		</div>

		<?php

	}

	private function print_testimonial_contents( $testimonial, $card_style, $settings ) {

		if ( $card_style == 'classic-card-1' ) {
			$this->print_testimonial_contents__classic_card_1( $testimonial, $card_style, $settings );
		}

		if ( $card_style == 'classic-card-2' ) {
			$this->print_testimonial_contents__classic_card_2( $testimonial, $card_style, $settings );
		}

		if ( $card_style == 'classic-card-3' ) {
			$this->print_testimonial_contents__classic_card_3( $testimonial, $card_style, $settings );
		}

	}

	private function print_name( $testimonial ) {
		if ( Utils::is_empty($testimonial['name']) ) return;
		?>
        <div class="one-elements-element one-elements-heading one-elements-name">
            <h6 class="one-elements-element__content">
                <?php
                //should we link name?
                if ( !empty($testimonial['link']['url']) ) {
                    $target =  $testimonial['link']['is_external'] ? '_blank' : '_self';
                    echo "<a class='testimonial_link' href='{$testimonial['link']['url']}' target='{$target}'>";
                }
                echo esc_html( $testimonial['name'] );
                if ( !empty($testimonial['link']['url']) ) {
                    echo "</a>";
                }
                ?>
            </h6>
        </div>
		<?php
	}

	private function print_designation( $testimonial ) {
		if ( 'yes' === $this->get_settings_for_display('enable_designation') && !Utils::is_empty($testimonial['designation']) ) : ?>
            <div class="one-elements-element one-elements-heading one-elements-designation">
                <h6 class="one-elements-element__content">
					<?php echo esc_html( $testimonial['designation']); ?>
                </h6>
            </div>
		<?php endif;
	}

	private function print_title( $testimonial ) {
		if ( 'yes' === $this->get_settings_for_display('enable_title') && !Utils::is_empty($testimonial['title']) ) : ?>
            <div class="one-elements-element one-elements-heading one-elements-title">
                <h4 class="one-elements-element__content">
					<?php echo esc_html( $testimonial['title']); ?>
                </h4>
            </div>
		<?php endif;
	}

	private function print_widget_content( $testimonial ) {
	    if (Utils::is_empty($testimonial['content'])) return;
        ?>
        <div class="elementor-text-editor elementor-clearfix">
			<?php echo $this->parse_text_editor( $testimonial['content'] ); ?>
        </div>
        <?php
	}

	private function print_rating( $testimonial ) {

		$settings = $this->get_settings_for_display();

		$show_rating = $settings['enable_rating'];
		if (empty( $show_rating) || 'yes' !== $show_rating) return;

		$rating_data = $this->get_rating($testimonial);
		$textual_rating = $rating_data[0] . '/' . $rating_data[1];
		$icon = '&#xE934;';

		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#xE933;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$this->add_render_attribute( 'testimonial_icon_wrapper', [
			'class' => 'elementor-star-rating',
			'title' => $textual_rating,
			'itemtype' => 'http://schema.org/Rating',
			'itemscope' => '',
			'itemprop' => 'reviewRating',
		]);

		$schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
		$stars_element = '<div ' . $this->get_render_attribute_string( 'testimonial_icon_wrapper' ) . '>' . $this->render_stars( $icon, $testimonial ) . ' ' . $schema_rating . '</div>';
		echo wp_kses( $stars_element, one_elements_allowed_html());

	}

	private function print_image( $testimonial ) {
		if ( ! $this->is_enabled_avatar() ) return;
		?>
        <div class="one-elements-element one-elements-image">
            <div class="one-elements-element__content">
	            <?php echo $this->get_attachment_image_html( $testimonial, 'user_image' ); // sanitized data returned ?>
            </div>
            <span class="one-elements-element__regular-state">
                <span class="one-elements-element__state-inner"></span>
            </span>
            <span class="one-elements-element__hover-state">
                <span class="one-elements-element__state-inner"></span>
            </span>
        </div>
		<?php
	}

	private function print_testimonial_icon() {
		$settings = $this->get_settings_for_display();
		if ( 'yes' === $settings['enable_quote'] ): ?>
			<span class="oee__testimonial-icon">
				<i class="fas fa-quote-left"></i>
			</span>
		<?php endif;
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Testimonials() );