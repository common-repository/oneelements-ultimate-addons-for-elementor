<?php
namespace OneElements\Includes\Widgets\PostGrid;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Button_Trait;
use OneElements\Includes\Traits\One_Elements_Carousel_Trait;
use OneElements\Includes\Traits\One_Elements_Filter_Trait;
use OneElements\Includes\Traits\One_Elements_Common_Widget_Trait;
use WP_Post;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Posts Grid Widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class Widget_OneElements_PostGrid extends Widget_Base {
	/**
	 * @var WP_Query
	 */
	protected $query = null;
	protected $carousel_option_key = 'one_elements_posts_c';
	protected $filter_option_key = 'one_elements_posts_f';
	protected $query_option_key = 'one_elements_posts_q';
	protected $single_item_name = '';
	protected $post_type = 'post';
	protected $post_types_for_filter_controls = [];
	protected $taxonomy = '';
	protected $term_slug = '';
	protected $post_per_page = -1;
	protected $load_more_template_id;
	protected $show_meta_icon = '';
	/**
	 * Prefix for trait's control.
	 *
	 * @since 1.0.0
	 *
	 * @return string Prefix for trait's control.
	 */
	protected $prefix = 'button_'; // prefix for normal button
	protected $lm_prefix = 'lm_button_';// prefix for load more button related stuff
	protected $read_more_btn_wrap; // read more button wrapper class.
	protected $load_more_btn_wrap;
    protected $is_pro_active = false; //@TODO; later add active licence check too
    protected $show_post_meta_controls = true;
    protected $btn_wrap_post_type = 'posts';
	protected $is_load_more_enabled;
	protected $is_filter_enabled;
	use One_Elements_Common_Widget_Trait,
		One_Elements_Button_Trait,
		One_Elements_Carousel_Trait,
		One_Elements_Filter_Trait {
		One_Elements_Carousel_Trait::get_script_depends insteadof One_Elements_Filter_Trait; //  fixed conflict
		One_Elements_Carousel_Trait::get_script_depends as carousel_get_script_depends;
		One_Elements_Filter_Trait::get_script_depends as filter_get_script_depends;
	}

	public function __construct( array $data = [], array $args = null ) {

		parent::__construct( $data, $args );
		$this->read_more_btn_wrap = ".{$this->prefix}posts"; // eg. button_posts
		$this->load_more_btn_wrap = ".{$this->lm_prefix}posts"; // eg. lm_button_posts

		$this->load_more_template_id = 'load-more-template-' . uniqidReal();
		$this->single_item_name = $this->get_style_class().'_single'; // eg. one-elements-posts_single
        $this->is_pro_active = get_option( 'one_elements_pro_active');
	}

	public function get_script_depends() {
		
	    if ( $this->is_pro_active ) {
	        return array_merge(
			    $this->carousel_get_script_depends(),
			    $this->filter_get_script_depends(),
			    [ 'one-elements--isotope' ]
		    );
	    } else {
		    return array_merge(
			    $this->carousel_get_script_depends(),
			    [ 'one-elements--isotope' ]
		    );
	    }

	}
	/**
	 * Get widget name.
	 *
	 * Retrieve Posts Grid Widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-posts';
	}

	public function get_style_class(  ) {
        return $this->get_name();
    }

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Posts Grid Widget belongs to.
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
		return ['post', 'posts', 'grid', 'carousel', 'blog', 'article'];
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
		return 'one-elements-widget-eicon eicon-posts-grid';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts Grid', 'one-elements' );
	}

	protected function get_layout_card_styles() {

		return apply_filters($this->get_name().'_layout_styles', [
			'' => __( 'Select a style', 'one-elements' ),
			'classic-card-1'   => __( 'Classic Card 1', 'one-elements' ),
			'classic-card-2'   => __( 'Classic Card 2', 'one-elements' ),
			'classic-card-3'   => __( 'Classic Card 3', 'one-elements' ),
			'classic-card-4'   => __( 'Classic Card 4', 'one-elements' ),
			'classic-card-5'   => __( 'Classic Card 5', 'one-elements' ),

			'modern-card-1' => __( 'Modern Card 1', 'one-elements' ),
			'modern-card-2' => __( 'Modern Card 2', 'one-elements' ),
			'modern-card-3' => __( 'Modern Card 3', 'one-elements' ),
			'modern-card-4' => __( 'Modern Card 4', 'one-elements' ),
			'modern-card-5' => __( 'Modern Card 5', 'one-elements' ),

			'post-card-1' => __( 'Post Card 1', 'one-elements' ),
			'post-card-2' => __( 'Post Card 2', 'one-elements' ),
			'post-card-3' => __( 'Post Card 3', 'one-elements' ),
			'post-card-4' => __( 'Post Card 4', 'one-elements' ),
			'post-card-5' => __( 'Post Card 5', 'one-elements' ),

		]);

	}

	protected function get_content_positions() {
		return apply_filters($this->get_name().'_content_positions', [
			'' => __( 'Default', 'one-elements' ),
			'top-left'   => __( 'Top Left', 'one-elements' ),
			'top-center'   => __( 'Top Center', 'one-elements' ),
			'top-right' => __( 'Top Right', 'one-elements' ),

			'center-left' => __( 'Center Left', 'one-elements' ),
			'center-center' => __( 'Center Center', 'one-elements' ),
			'center-right' => __( 'Center Right', 'one-elements' ),

			'bottom-left' => __( 'Bottom Left', 'one-elements' ),
			'bottom-center' => __( 'Bottom Center', 'one-elements' ),
			'bottom-right' => __( 'Bottom Right', 'one-elements' ),

		]);
	}

	protected function get_content_alignments() {
		return apply_filters($this->get_name().'_content_alignments', [
			'top'   => __( 'Top', 'one-elements' ),
			'middle' => __( 'Middle', 'one-elements' ),
			'bottom' => __( 'Bottom', 'one-elements' ),
		]);
	}

	protected function get_display_types()
	{
		$display_types = [
			'grid'     => __( 'Grid', 'one-elements' ),
			'carousel' => __( 'Carousel', 'one-elements' ),
		];

		if ( $this->is_pro_active ){
            $display_types['filter'] = __( 'Filter', 'one-elements' );
		}

		return $display_types;
    }

	protected function get_button_control_default_args( $prefix='button_', $wrapper_post_type='posts' ) {
        $btn_wrapper = $prefix.$wrapper_post_type; // eg. button_posts / lm_button_posts
		return [
			'prefix' => $prefix,
			'excludes' => ['button_align', 'button_css_id', 'icon_css_id', 'icon_align', 'button_link'],
			'includes' => [],
			'selectors'=>[
				'icon_box_size' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.'.one-elements-button__type-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', // this selector added by K.
				],

				'icon_size' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],

				'icon_indent' => [
					'{{WRAPPER}} .one-elements-button__icon-right.'.$btn_wrapper.' .one-elements-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .one-elements-button__icon-left.'.$btn_wrapper.' .one-elements-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],

				/*-------Button Icon Color & Background override-------*/
				'icon_color' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-icon__content_icon > *',

				'hover_icon_color' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-icon__content_icon > *',

				'icon_background' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',

				'icon_background_hover' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',

				/*-------Button Icon Border & Shadow override-------*/
				'icon_border' => '{{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon .one-elements-element__regular-state',

				'icon_border_hover' => '{{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon .one-elements-element__hover-state',

				'icon_border_radius' => [
					'{{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon, {{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .'.$btn_wrapper.' .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'icon_border_radius_hover' =>  [
					'{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon, {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],

				'icon_shadow' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-icon',
				'icon_hover_shadow' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-icon',

				// lets prefix button widget related css classes so that we can use multiple unique button's style like read/load more btn
				/*-------Button background override-------*/
				'button_background' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__regular-state .one-elements-element__state-inner',

				'button_background_hover' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__hover-state .one-elements-element__state-inner',

				/*-------Button Border & Shadow override-------*/
				'button_border' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__regular-state',
				'button_border_radius' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.', {{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'button_box_shadow' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper,


				'button_border_hover' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' > .one-elements-element__hover-state',
				'button_border_radius_hover' => [
					'{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.', {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' > .one-elements-element__regular-state, {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' > .one-elements-element__hover-state, {{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'hover_button_box_shadow' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper,

				/*-------Button Underline override-------*/
				'underline_height' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline' => 'height: {{SIZE}}{{UNIT}};',
				],
				'underline_width' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'underline_radius' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'underline_spacing' => [
					'{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-element__content > *' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'underline_color' => '{{WRAPPER}} .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline',
				//@TODO; Following two selectors need fixing
				'hover_underline_width' => [
					'{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline' => 'width: {{SIZE}}{{UNIT}};',
				],
				'hover_underline_color' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button.'.$btn_wrapper.' .one-elements-button__underline',
			],
			'labels' => [
				'button_background_section' => __('Read More Background', 'one-elements'),
				'section_content_button' => __('Read More Button', 'one-elements'),
				'button_border_section' => __('Read More Border & Shadow', 'one-elements'),
				'button_underline_section' => __('Read More Underline', 'one-elements'),
				'section_icon' => __('Read More Icon', 'one-elements'),
				'section_icon_style' => __('Read More Icon', 'one-elements'),
				'icon_background_section' => __('Read More Icon Background', 'one-elements'),
				'icon_border_section' => __('Read More Icon Border & Shadow', 'one-elements'),
			],
			'conditions' => [
				'section_content_button' => [ $prefix.'show_button' => 'yes', ],
				'section_icon' => [  $prefix .'enable_button_icon' => 'yes', $prefix.'show_button' => 'yes', ],
				'button_background_section' => [ $prefix.'show_button' => 'yes',  $prefix.'button_type!' => ['flat']],
				'button_border_section' => [ $prefix.'show_button' => 'yes',  $prefix.'button_type!' => ['flat']],
				'section_icon_style' => [ $prefix .'enable_button_icon' => 'yes', $prefix.'show_button' => 'yes',  $prefix.'icon[value]!' => '',],
				'icon_background_section' => [
					$prefix.'enable_button_icon' => 'yes',
					$prefix.'show_button' => 'yes',
					$prefix.'icon[value]!' => '',
					$prefix.'button_type!' => 'flat',
				],
				'icon_border_section' => [  $prefix .'enable_button_icon' => 'yes', $prefix.'show_button' => 'yes',  $prefix.'icon[value]!' => '', ]
			],
			'defaults' => [
			    'button_text' => __('Read More', 'one-elements')
            ]
		];

	}

	protected function get_load_more_button_control_default_args( $prefix='lm_button_', $wrapper_post_type='posts' ) {

		$options = $this->get_button_control_default_args($prefix, $wrapper_post_type);

		$options['labels']  = [
			'button_background_section' => __('Load More Background', 'one-elements'),
			'section_content_button' => __('Load More Button', 'one-elements'),
			'button_border_section' => __('Load More Border & Shadow', 'one-elements'),
			'button_underline_section' => __('Load More Underline', 'one-elements'),
			'section_icon' => __('Load More Icon', 'one-elements'),
			'section_icon_style' => __('Load More Icon', 'one-elements'),
			'icon_background_section' => __('Load More Icon Background', 'one-elements'),
			'icon_border_section' => __('Load More Icon Border & Shadow', 'one-elements'),
		];
		// Do not show any load more button options if display type is carousel
		$options['conditions']['section_content_button']['display_type!'] = 'carousel';
		$options['conditions']['section_icon']['display_type!'] = 'carousel';
		$options['conditions']['button_background_section']['display_type!'] = 'carousel';
		$options['conditions']['button_border_section']['display_type!'] = 'carousel';
		$options['conditions']['section_icon_style']['display_type!'] = 'carousel';
		$options['conditions']['icon_background_section']['display_type!'] = 'carousel';
		$options['conditions']['icon_border_section']['display_type!'] = 'carousel';

		// update default button text
		$options['defaults']['button_text'] = __('Load More', 'one-elements');

		return $options;

	}

	/**
	 * Register Posts Grid Widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Query And Layout Controls from traits
		 * @source includes/traits/one-elements-widget-trait.php
		 */
		$this->get_query_controls_for_all_posttypes();
		$this->get_layout_controls();
		$this->get_content_carousel_controls();
		if ( $this->is_pro_active ){
			$this->get_content_filter_controls([], $this->post_types_for_filter_controls);
		}


		// read more button
		$this->init_content_button_settings($this->get_button_control_default_args($this->prefix));
		$this->init_content_button_icon_settings($this->get_button_control_default_args($this->prefix));

		// Load More button
		$this->init_content_button_settings($this->get_load_more_button_control_default_args($this->lm_prefix));
		$this->init_content_button_icon_settings($this->get_load_more_button_control_default_args($this->lm_prefix));

		$this->start_injection( [
			'of' => $this->lm_prefix.'button_size',
			'at' => 'after'
		]);

		$this->add_control(
			$this->lm_prefix.'alignment',
			[
				'label' => __( 'Alignment', 'one-elements' ),
				'type' => Controls_Manager::CHOOSE,
				//'prefix_class' => 'elementor%s-align-',
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
				'selectors' => [
					"{{WRAPPER}} .{$this->lm_prefix}button-wrapper" => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_injection();


		$this->init_style_content_controls();
		$this->get_style_background_controls();
		$this->init_style_border_n_shadow_settings();

		// Style > Read More Button
		$this->init_style_button_background_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_border_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_underline_settings($this->get_button_control_default_args($this->prefix));

		// Style > Read More Button Icon
		$this->init_style_button_icon_background_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_icon_border_settings($this->get_button_control_default_args($this->prefix));

		// Style > Load More Button
		$this->init_style_button_background_settings($this->get_load_more_button_control_default_args($this->lm_prefix));
		$this->init_style_button_border_settings($this->get_load_more_button_control_default_args($this->lm_prefix));
		$this->init_style_button_underline_settings($this->get_load_more_button_control_default_args($this->lm_prefix));

		// Style > Load More Button Icon
		$this->init_style_button_icon_background_settings($this->get_load_more_button_control_default_args($this->lm_prefix));
		$this->init_style_button_icon_border_settings($this->get_load_more_button_control_default_args($this->lm_prefix));

	}

	protected function get_layout_controls() {
		$this->start_controls_section( 'one_elements_section_layout', [
			'label' => __( 'Layout Settings', 'one-elements' ),
		]);
		$this->get_layout_general_controls();
		$this->get_layout_visibility_controls();
		$this->get_layout_load_more_visibility_controls();
		$this->get_layout_thumbnail_controls();
		$this->end_controls_section();
	}

	protected function get_layout_general_controls() {

		$this->add_control( 'layout_style_heading', [
			'label' => __( 'Layout Style', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
		]);

		$this->add_control( 'layout_style', [
			'label' => __( 'Layout Style', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'options' => $this->get_layout_card_styles(),
			'default' => 'classic-card-1',
		]);

		$this->add_control( 'display_type', [
			'label'     => __( 'Display Type', 'one-elements' ),
			'description'     => __( 'Note: Filter type does not work for manual content source or if the selected content source does not have any taxonomy.', 'one-elements' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'grid',
			'options'   => $this->get_display_types(),
			'condition' => [
				'one_elements_fetch_type' => [ 'multiple' ],
				'layout_style' => [ 'classic-card-1', 'classic-card-2', 'classic-card-3', 'classic-card-4', 'post-card-1', 'post-card-2' ],
			],
		]);

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
					'display_type!' => 'carousel',
					'layout_style!' => [ 'modern-card-1', 'modern-card-2', 'modern-card-3', 'modern-card-4', ]
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->get_style_class().'--inner > .oee__row > .oee__item' => 'width: calc(100%/{{VALUE}});',
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

		$this->add_responsive_control(
			'layout_bottom_space',
			[
				'label' => __( 'Bottom Space', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
				    "{{WRAPPER}} .".$this->get_style_class()."--inner > .oee__row > .oee__item" => "margin-bottom: {{SIZE}}px;",
				],
				'condition' => [
					'display_type' => ['grid', 'filter'],
				]
			]
		);

		$this->add_control('layout_height', [

			'label'       => __( 'Height Type', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''          => __( 'Default', 'one-elements' ),
				'ratio'    => __( 'Ratio', 'one-elements' ),
				'fixed'    => __( 'Fixed', 'one-elements' ),
			],
			'condition' => [
				'layout_style!' => [
					'post-card-1',
					'post-card-2',
				],
			],
		]);

		$this->add_responsive_control(
			'layout_height_ratio',
			[
				'label' => __( 'Height', 'one-elements' ),
				'description' => __( 'This control will not work for modern layouts', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_item_name.'.oee__dynamic-height-ratio:before' => 'padding-bottom: {{SIZE}}%;'
				],
				'condition' => [
					'layout_height' => 'ratio',
					'layout_style!' => [
						'post-card-1',
						'post-card-2',
					],
				]
			]
		);

		$this->add_responsive_control(
			'layout_height_fixed',
			[
				'label' => __( 'Height', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_item_name => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .oee__item--lg' => 'height: calc({{SIZE}}px*2);',
					'{{WRAPPER}} .oee__item--md' => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .oee__item--sm' => 'height: {{SIZE}}px;'
				],
				'condition' => [
					'layout_height' => 'fixed',
					'layout_style!' => [
						'post-card-1',
						'post-card-2',
					],
				]
			]
		);

		$this->add_responsive_control(
			'content_max_line',
			[
				'label' => __( 'Content Max Line', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_content_max_lines(),
				'default' => '',
				'condition' => [
					'show_content' => 'yes'
				]

			]
		);
		$this->add_control(
			'layout_content_position',
			[
				'label' => __( 'Content Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_content_positions(),
				'default' => '',
			]
		);

		$this->add_control('layout_content_alignment',
			[
				'label' => __( 'Item Alignment', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_content_alignments(),
				'default' => 'top',
			]
		);
		$this->add_control( $this->get_style_class().'_layout_placeholder', [
			'label'     => esc_html__( 'Necessary Hidden Field For control injection. Because other fields may not be available for injection pointer because if that field relies upon conditional display', 'one-elements' ),
			'type'      => Controls_Manager::HIDDEN,
		]);

	}

	protected function get_layout_visibility_controls() {
	    $this->add_control( 'layout_content_visibility_heading', [
			'label' => __( 'Visibility', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		]);
		//available in all cards
		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
			]
		);
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter custom format', 'one-elements' ),
				'condition' => [
					'show_date' => 'yes'
				],
				'description' => sprintf(
				__('Enter a valid php date format string. Learn more <a href="%s" target="_blank">Here</a> & <a href="%s" target="_blank">Here</a>', 'one-elements' ),
				'https://www.php.net/manual/en/datetime.formats.date.php',
				'https://www.w3schools.com/php/func_date_date_format.asp'
				),
			]
		);
		$this->add_control(
			'show_time',
			[
				'label' => __( 'Show Time', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),

			]
		);
		$this->add_control(
			'show_cats',
			[
				'label' => __( 'Show Post Categories', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'condition' => [
					'layout_style' => ['post-card-5']
				]
			]
		);
		$this->add_control(
			'show_comments',
			[
				'label' => __( 'Show Comments', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'condition' => [
					'layout_style' => ['post-card-5']
				]
			]
		);
		$this->add_control(
			'show_post_meta_icons',
			[
				'label' => __( 'Show Post Meta Icons', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '1',
				'return_value' => '1',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
			]
		);
		$this->add_control(
			'show_author_info',
			[
				'label' => __( 'Show Author Info', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'one-elements' ),
					'image' => __( 'Image', 'one-elements' ),
					'name' => __( 'Name', 'one-elements' ),
					'name_image' => __( 'Name & Image', 'one-elements' ),
				],
				'default' => '',
				'toggle' => true,
				'condition' => [
					'layout_style' => [ 'classic-card-4','classic-card-5', 'post-card-3', 'post-card-4', 'post-card-5']
				]
			]
		);
		$this->add_control(
			'show_content',
			[
				'label' => __( 'Show Content Excerpt', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
			]
		);
		$this->add_control(
			'reverse_content_position',
			[
				'label' => __( 'Reverse Content Position', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'condition' => [
					'show_author_info!' => 'none',
					'posts_content_position' => 'mixed',
					'layout_style' => ['modern-card-1', 'modern-card-2','modern-card-3', 'modern-card-4', 'modern-card-5'] //@TODO; ask about Reverse Content Position
				]
			]
		);
		// Layout Content > Read More
		$this->add_control(
			$this->prefix .'show_button',
			[
				'label' => __( 'Show Read More button', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'condition' => [
					'layout_style!' => [ 'classic-card-4','classic-card-5', ]
				]
			]
		);
		$this->add_control( 'button_animation', [
			'label'     => __( 'Button Animation', 'one-elements' ),
			'description'     => __( 'You may select an animation for the read more buttons', 'one-elements' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				''     => __( 'None', 'one-elements' ),
				'one'     => __( 'Animation One', 'one-elements' ),
				'two'     => __( 'Animation Two', 'one-elements' ),
				'three'     => __( 'Animation Three', 'one-elements' ),
			],
			'condition' => [
				$this->prefix .'show_button' => 'yes',
			],
			'prefix_class' => 'animation-',
		]);
	}

	protected function get_layout_load_more_visibility_controls() {
		$this->add_control(
			$this->lm_prefix .'show_button',
			[
				'label' => __( 'Enable Load More button', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'condition' => [
					'display_type!' => 'carousel'
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'items_to_load',
			[
				'label' => __( 'Items to Load', 'one-elements' ),
				'description' => __( 'Specify how many items to load when you click the load more button. Default -1 which means all items.', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => $this->post_per_page,
				'min' => -1,
				'max' => 20,
				'step' => 1,
				'condition' => [
					$this->lm_prefix .'show_button' => 'yes',
					'display_type!' => 'carousel',
				],
			]
		);

	}

	protected function get_layout_thumbnail_controls() {
		$this->add_control( 'layout_content_thumb_heading', [
			'label' => __( 'Thumbnail', 'one-elements' ),
			'type' => Controls_Manager::HEADING,
			'separator' => 'before',
		]);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
				'exclude' => ['custom'],
			]
		);
	}

	protected function init_style_content_controls() {

		$this->start_controls_section(
			'style_posts_section',
			[
				'label' => $this->get_title(),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		//Spacing
		$this->_init_style_spacing_controls();
		//COLORS
		$this->_init_style_color_controls();
		// TYPOGRAPHY
		$this->_init_style_typography_controls();
		$this->end_controls_section();

	}


	protected function _init_style_spacing_controls(  ) {
		$this->add_control(
			'style_posts_spacing_heading',
			[
				'label' => __( 'Spacing', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'single_post_item_padding',
			[
				'label' => __( 'Padding', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					"{{WRAPPER}} .{$this->single_item_name}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

	}

	protected function _init_style_color_controls() {
		$this->add_control( 'style_posts_colors_heading', [
				'label' => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->start_controls_tabs( 'posts_colors' );
		// normal tab
		$this->start_controls_tab( 'posts_colors_normal', [ 'label' => __( 'Normal', 'one-elements' )] );
            $this->init_style_title_n_content_colors_controls(false);
            $this->init_style_button_colors_controls(false);
            if ( $this->show_post_meta_controls ){
                $this->init_style_post_meta_colors_controls(false);
            }
		$this->end_controls_tab();
		// hover tab
		$this->start_controls_tab( 'posts_colors_hover', [ 'label' => __( 'Hover', 'one-elements' ) ] );
            $this->init_style_title_n_content_colors_controls(true);
            $this->init_style_button_colors_controls(true);
            if ( $this->show_post_meta_controls ){
                $this->init_style_post_meta_colors_controls(true);
            }
		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs

	}
	protected function init_style_post_meta_colors_controls( $is_hover = false ) {
		if ($is_hover){
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_icon_color_hover',
					'label' => __( 'Post Meta Icon', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'condition' => [
						'layout_style' => ['classic-card-1', 'classic-card-2'],
						'show_post_meta_icons' => '1'
					],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .oee_post_meta--single:hover i'
				]
			);

			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_text_color_hover',
					'label' => __( 'Post Meta Text', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .oee_post_meta--single:hover .oee_meta_text'
				]
			);
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_link_color_hover',
					'label' => __( 'Post Meta Link', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .oee_post_meta--single a:hover'
				]
			);
		}else{
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_icon_color',
					'label' => __( 'Post Meta Icon', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'condition' => [
						'layout_style' => ['classic-card-1', 'classic-card-2'],
						'show_post_meta_icons' => '1'
					],
					'selector' => '{{WRAPPER}} .oee_post_meta--info i'
				]
			);

			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_text_color',
					'label' => __( 'Post Meta Text', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .oee_post_meta--info .oee_meta_text'
				]
			);
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'meta_link_color',
					'label' => __( 'Post Meta Link', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .oee_post_meta--info a'
				]
			);
		}
	}
	protected function init_style_title_n_content_colors_controls( $is_hover = false )
	{
		if ($is_hover){
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'posts_title_color_hover',
					'label' => __( 'Title', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-heading .one-elements-element__content',
				]
			);
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'posts_content_color_hover',
					'label' => __( 'Content', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.':hover .elementor-text-editor',
				]
			);
		}else{
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'posts_title_color',
					'label' => __( 'Title', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .one-elements-heading .one-elements-element__content',
				]
			);
			$this->add_group_control(
				Group_Control_Text_Gradient::get_type(),
				[
					'name' => 'posts_content_color',
					'label' => __( 'Content', 'one-elements' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .elementor-text-editor',
				]
			);
		}

	}

	protected function init_style_button_colors_controls( $is_hover = false ) {
        if ($is_hover){
	        // Read More stuff
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'hover_btn_text_color',
			        'label' => __( 'Read More Button Text', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .'.$this->single_item_name.':hover '.$this->read_more_btn_wrap.' .one-elements-button__content_text',
			        'condition' => [
				        $this->prefix.'show_button' => 'yes',
				        $this->prefix.'button_type!' => ['circle']
			        ],
		        ]
	        );
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'hover_button_icon_color',
			        'label' => __( 'Read More Button Icon', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .'.$this->single_item_name.':hover .one-elements-button'.$this->read_more_btn_wrap.' .one-elements-icon__content_icon > *',
			        'condition' => [
				        $this->prefix.'show_button' => 'yes',
				        $this->prefix.'enable_button_icon' => 'yes',
				        $this->prefix.'icon[value]!' => '',
			        ],
		        ]
	        );


	        //Load more stuff
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'hover_lm_btn_text_color',
			        'label' => __( 'Load More Button Text', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button'.$this->load_more_btn_wrap.':hover .one-elements-button__content_text',
			        'condition' => [
				        $this->lm_prefix.'show_button' => 'yes',
				        $this->lm_prefix.'button_type!' => ['circle'],
				        'display_type!' => 'carousel'
			        ],
		        ]
	        );
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'hover_lm_button_icon_color',
			        'label' => __( 'Load More Button Icon', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button'.$this->load_more_btn_wrap.':hover .one-elements-icon__content_icon > *',
			        'condition' => [
				        $this->lm_prefix.'show_button' => 'yes',
				        $this->lm_prefix.'enable_button_icon' => 'yes',
				        $this->lm_prefix.'icon[value]!' => '',
				        'display_type!' => 'carousel'

			        ],
		        ]
	        );

        }else{
	        // Read more
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'btn_text_color',
			        'label' => __( 'Read More Button Text', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button'.$this->read_more_btn_wrap.' .one-elements-button__content_text',
			        'condition' => [
				        $this->prefix.'show_button' => 'yes',
				        $this->prefix .'button_type!' => ['circle']
			        ],
		        ]
	        );
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'button_icon_color',
			        'label' => __( 'Read More Button Icon', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button'.$this->read_more_btn_wrap.' .one-elements-icon__content_icon > *',
			        'condition' => [
				        $this->prefix.'show_button' => 'yes',
				        $this->prefix.'enable_button_icon' => 'yes',
				        $this->prefix.'icon[value]!' => '',
			        ],
		        ]
	        );

	        // Load more stuff
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'lm_btn_text_color',
			        'label' => __( 'Load More Button Text', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button.one-elements-button__load_more .one-elements-button__content_text',
			        'condition' => [
				        $this->lm_prefix.'show_button' => 'yes',
				        $this->lm_prefix .'button_type!' => ['circle'],
				        'display_type!' => 'carousel'

			        ],
		        ]
	        );
	        $this->add_group_control(
		        Group_Control_Text_Gradient::get_type(),
		        [
			        'name' => 'lm_button_icon_color',
			        'label' => __( 'Load More Button Icon', 'one-elements' ),
			        'types' => [ 'classic', 'gradient' ],
			        'selector' => '{{WRAPPER}} .one-elements-button.one-elements-button__load_more .one-elements-icon__content_icon > *',
			        'condition' => [
				        $this->lm_prefix.'show_button' => 'yes',
				        $this->lm_prefix.'enable_button_icon' => 'yes',
				        $this->lm_prefix.'icon[value]!' => '',
				        'display_type!' => 'carousel'
			        ],
		        ]
	        );
        }
	}

	protected function _init_style_typography_controls() {

		$this->add_control(
			'style_posts_typography_heading',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'posts_title_typography',
				'label' => __( 'Title', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'posts_content_typography',
				'label' => __( 'Content', 'one-elements' ),
				'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .elementor-text-editor p',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'posts_typography',
				'label' => __( 'Read More Button Text', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-button'.$this->read_more_btn_wrap.' .one-elements-button__content_text',
				'condition' => [
					$this->prefix.'show_button' => 'yes',
					$this->prefix.'button_type!' => ['circle']

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'posts_lm_btn_typography',
				'label' => __( 'Load More Button Text', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-button'.$this->load_more_btn_wrap.' .one-elements-button__content_text',
				'condition' => [
					$this->lm_prefix.'show_button' => 'yes',
					$this->lm_prefix.'button_type!' => ['circle'],
					'display_type!' => 'carousel'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'posts_meta_text_typo',
				'label' => __( 'Post Meta', 'one-elements' ),
				'selector' => '{{WRAPPER}} .oee_post_meta--info .oee_meta_text, {{WRAPPER}} .oee_post_meta--info a',
			]
		);

	}
	/**
	 * It prints controls for background section of style tab
	 */
	protected function get_style_background_controls() {
		$this->start_controls_section( 'one_elements_section_style', [
			'label' => __( 'Background', 'one-elements' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition' => [
				'layout_style!' => [
					'post-card-1',
					'post-card-2',
				], // show for all modern and icon cards themes only
			],
		]);
		// background tab section starts
		$this->start_controls_tabs( 'posts_tabs_background');

		// normal state
		$this->start_controls_tab( 'posts_tab_background_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);
		$this->add_control(
			'posts_use_featured_image',
			[
				'label' => __( 'Use Featured Image', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' )
			]
		);
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'one_elements_posts_bg',
			'label'    => __( 'Background', 'one-elements' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__regular-state .one-elements-element__state-inner',
			'exclude' => [ 'image' ],
			'condition' => ['posts_use_featured_image' => '']
		]);

		$this->end_controls_tab();

		// hover state
		$this->start_controls_tab( 'posts_tab_background_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);
		$this->add_control(
			'posts_use_featured_image_hover',
			[
				'label' => __( 'Use Featured Image', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' )
			]
		);
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'one_elements_posts_bg_hover',
			'label'    => __( 'Background', 'one-elements' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__hover-state .one-elements-element__state-inner',
			'exclude' => [ 'image' ],
			'condition' => ['posts_use_featured_image_hover' => '']
		]);


		$this->end_controls_tab();

		$this->end_controls_tabs(); // end all tabs

		$this->end_controls_section(); // end background style

		// Background Overlay Section
		$this->start_controls_section( 'one_elements_posts_background_overlay', [
			'label'     => __( 'Background Overlay', 'one-elements' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'layout_style!' => [
					'post-card-1',
					'post-card-2',
				], // show for all modern and icon cards themes only
			],
		]);

		// tab section starts
		$this->start_controls_tabs( 'tabs_background_overlay' );

		// add normal and hover tab
		$this->start_controls_tab( 'tab_background_overlay_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_overlay',
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__regular-state .one-elements-element__state-inner:after',
			'types'    => [
				'classic',
				'gradient',
			],
		]);

		$this->add_control( 'background_overlay_opacity', [
			'label'     => __( 'Opacity', 'one-elements' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'size' => .5,
			],
			'range'     => [
				'px' => [
					'max'  => 1,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'background_overlay_background' => [
					'classic',
					'gradient',
				],
			],
		]);

		$this->end_controls_tab(); // end of normal tab

		$this->start_controls_tab( 'tab_background_overlay_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_overlay_hover',
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__hover-state .one-elements-element__state-inner:after',
		]);

		$this->add_control( 'background_overlay_hover_opacity', [
			'label'     => __( 'Opacity', 'one-elements' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'size' => .5,
			],
			'range'     => [
				'px' => [
					'max'  => 1,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'background_overlay_hover_background' => [
					'classic',
					'gradient',
				],
			],
		]);

		$this->end_controls_tab(); // end hover tab

		$this->end_controls_tabs(); // all tabs end here

		$this->end_controls_section();
		// end of the overlay section

		// Image Overlay Section
		$this->start_controls_section( 'one_elements_posts_image_overlay', [
			'label'     => __( 'Image Overlay', 'one-elements' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'layout_style' => [
					'post-card-1',
					'post-card-2',
				],
			]
		]);

		// tab section starts
		$this->start_controls_tabs( 'tabs_image_overlay' );

		// add normal and hover tab
		$this->start_controls_tab( 'tab_image_overlay_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'image_overlay',
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .one-elements-image > .one-elements-element__regular-state .one-elements-element__state-inner:after',
			'types'    => [
				'classic',
				'gradient',
			],
		]);

		$this->add_control( 'image_overlay_opacity', [
			'label'     => __( 'Opacity', 'one-elements' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'size' => .5,
			],
			'range'     => [
				'px' => [
					'max'  => 1,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .'.$this->single_item_name.' .one-elements-image > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'image_overlay_background' => [
					'classic',
					'gradient',
				],
			],
		]);

		$this->end_controls_tab(); // end of normal tab

		$this->start_controls_tab( 'tab_image_overlay_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'image_overlay_hover',
			'selector' => '{{WRAPPER}} .'.$this->single_item_name.' .one-elements-image > .one-elements-element__hover-state .one-elements-element__state-inner:after',
		]);

		$this->add_control( 'image_overlay_hover_opacity', [
			'label'     => __( 'Opacity', 'one-elements' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'size' => .5,
			],
			'range'     => [
				'px' => [
					'max'  => 1,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .'.$this->single_item_name.' .one-elements-image > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
			],
			'condition' => [
				'image_overlay_hover_background' => [
					'classic',
					'gradient',
				],
			],
		]);

		$this->end_controls_tab(); // end hover tab

		$this->end_controls_tabs(); // all tabs end here

		$this->end_controls_section();
		// end of the overlay section

	}

	protected function init_style_border_n_shadow_settings() {

		$this->start_controls_section( 'posts_border_section', [
			'label' => __( 'Border & Shadow', 'one-elements' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [
				'layout_style!' => [
					'post-card-1',
					'post-card-2',
				], // show for all modern and icon cards themes only
			],
		]);

		$this->start_controls_tabs( 'tabs_posts_border' );

		// Border
		$this->start_controls_tab(
			'tab_posts_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'posts_border',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_item_name.', {{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__regular-state, {{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__hover-state, {{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'posts_box_shadow',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .'.$this->single_item_name,
			]
		);

		$this->end_controls_tab();

		// Border Hover
		$this->start_controls_tab(
			'tab_posts_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'posts_border_hover',
				'label' => __( 'Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .'.$this->single_item_name.' > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_item_name.':hover, {{WRAPPER}} .'.$this->single_item_name.':hover > .one-elements-element__regular-state, {{WRAPPER}} .'.$this->single_item_name.':hover > .one-elements-element__hover-state, {{WRAPPER}} .'.$this->single_item_name.':hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_posts_box_shadow',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .'.$this->single_item_name.':hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}


	/**
	 * It outputs the widget markup to the website.
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		// control the default behaviour of WP excerpt function
		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		// we need to extract rendering attribute from the button's render method in the trait
		// because a class is added multiple times when calling that method inside a loop. add_render_attribute method cache all calls.

        $this->add_button_render_atts( $this->prefix, $settings, $this->btn_wrap_post_type );// add unique render attributes for read more button

		$this->add_button_render_atts( $this->lm_prefix, $settings, $this->btn_wrap_post_type );// add unique render attributes for Load more button


		$this->add_render_attribute( 'posts_single_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['posts_border_gradient_type'] ) {
			$this->add_render_attribute( 'posts_single_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'posts_single_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['posts_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'posts_single_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		$posts = $this->query_posts();
		$card_style = $settings['layout_style'];

		// For no posts found
		if ( empty($posts) ) { //@todo add no posts found settings
		    echo apply_filters('one_elements_posts_not_found_message', sprintf( '<p>%s</p>', esc_html__( 'No posts found.', 'one_elements')));
			return;
		}

		if  ( is_array($posts) ) {
			$this->print_multiple_posts( $posts, $card_style );
		}
		/**
         * This Action lets you out put content after the loop ends and content has printed.
         * It is a good hook to print pagination etc.
         * @param array $posts the array of WP_Post
         */
        do_action('one_elements_posts_grid_footer', $posts);
		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );

	}
	
	protected function add_button_render_atts( $prefix='', $settings = [], $wrapper_post_type = 'posts' ) {

		$settings = !empty( $settings ) ? $settings : $this->get_settings_for_display();
		$btn_wrapper = $prefix. $wrapper_post_type;

		$this->add_render_attribute( $prefix.'button', [
				'class' => [
					'one-elements-element one-elements-button '.$btn_wrapper,
					'one-elements-button__type-' . $settings[$prefix.'button_type'],
					'one-elements-button__icon-' . $settings[$prefix.'icon_position'],
				]
			]
		);

		$this->add_render_attribute( $prefix.'button_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings[$prefix.'button_border_background'] == 'gradient' ) {
			$this->add_render_attribute( $prefix.'button_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( $prefix.'button_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings[$prefix.'button_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( $prefix.'button_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( ! empty( $settings[$prefix.'button_size'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'one-elements-button__size-' . $settings[$prefix.'button_size']);
		}
		//@TODO; Ask do we really need this responsive codes as we did not use responsive control for this control?
		if ( ! empty( $settings[$prefix.'button_size_tablet'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'one-elements-button__tablet-size-' . $settings[$prefix.'button_size_tablet']);
		}

		if ( !empty( $settings[$prefix.'button_size_mobile'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'one-elements-button__mobile-size-' . $settings[$prefix.'button_size_mobile']);
		}

		if ( !empty( $settings[$prefix.'hover_animation'] ) ) {
			$this->add_render_attribute( $prefix.'button', 'class', 'elementor-animation-' . $settings[$prefix.'hover_animation']);
		}

		if ( $settings[$prefix.'icon']['library'] == 'svg' ) {
			$this->add_render_attribute( 'button_icon', 'class', 'one-elements-icon__svg' );
		}

		$this->add_inline_editing_attributes( $prefix.'button_text', 'none' );

		// Button Text and Icon related atts
		$this->add_render_attribute( $prefix.'button_icon', 'class', 'one-elements-element one-elements-icon' );
		$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings[$prefix.'icon_border_gradient_type'] ) {
			$this->add_render_attribute( $prefix.'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings[$prefix.'icon_border_hover_gradient_type'] ) {
			$this->add_render_attribute( $prefix.'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}
		$this->add_render_attribute( $prefix.'button', 'class', 'one-elements-button__link' );

	}

	public function print_multiple_posts( $posts, $card_style ) {
		$settings = $this->get_settings_for_display();
        $this->show_meta_icon = $this->get_settings_for_display('show_post_meta_icons');
        if (!empty( $settings["{$this->query_option_key}_post_type"])){
            $this->post_type =  $settings["{$this->query_option_key}_post_type"];
        }

        $this->post_per_page = !empty( $settings["{$this->query_option_key}_limit"]) ? $settings["{$this->query_option_key}_limit"] : get_option( 'posts_per_page');

		$is_carousel_enabled = $this->is_carousel_enabled( $card_style );
		$this->is_load_more_enabled = $this->is_load_more_enabled( $settings );
		$is_ajax_enabled = $this->is_ajax_enabled($settings);

		$items_gap_class = $this->get_items_gap_class( $settings['items_gap'] );
		$card_style_class = 'card_style--' . $card_style;


		// get all tax of selected post type eg. category and post_tag
		$taxonomies = get_object_taxonomies($this->post_type, 'objects');
		$valid_tax_for_nav = []; //  store all user selected tax that has value, eg. category or post_tag or both

		if ( !empty($taxonomies) && is_array($taxonomies) ) {
			foreach ( $taxonomies as $object ) {
				if ('post_format' == $object->name) continue; // skip post format tax
				if (!empty($settings["{$this->query_option_key}_{$object->name}_slugs"])) {
					$valid_tax_for_nav[] = $object->name;
				}
			}
		}

		if ( !empty($valid_tax_for_nav) ) {
            if ( !in_array($this->taxonomy, $valid_tax_for_nav) ) {
                // user selected tax is not available in the query tax, so use one of the query tax for filter nav
	            $this->taxonomy = $valid_tax_for_nav[0];
            }
		}

		$this->is_filter_enabled = $this->is_filter_enabled( $card_style, $this->taxonomy );

		$load_more_options = $this->get_load_more_options();
        $item_alignment = !empty( $settings['layout_content_alignment']) ? $settings['layout_content_alignment'] : 'top';
		$this->add_render_attribute( 'pg_cards_parent', 'class', "oee__row oee__row-wrap oee__align-{$item_alignment}" );
		$this->add_render_attribute( 'pg_cards_parent', 'class', $card_style_class );
		$this->add_render_attribute( 'pg_cards_parent', 'class', $items_gap_class );

		if ( strpos( $card_style, 'classic-card' ) !== false ) $this->add_render_attribute( 'pg_cards_parent', 'class', 'card_style--classic-card' );
		if ( strpos( $card_style, 'modern-card' ) !== false ) $this->add_render_attribute( 'pg_cards_parent', 'class', 'card_style--modern-card' );
		if ( strpos( $card_style, 'post-card' ) !== false ) $this->add_render_attribute( 'pg_cards_parent', 'class', 'card_style--post-card' );
		if ( strpos( $card_style, 'flip-card' ) !== false ) $this->add_render_attribute( 'pa_cards_parent', 'class', 'card_style--flip-card' );// This line is for practice area extension

		$this->add_render_attribute( 'pg_cards_single', 'class', 'oee__item' );

		if ( in_array( $card_style, ['modern-card-1', 'modern-card-2', 'modern-card-3', 'modern-card-4', 'modern-card-5'] ) ) {
			$this->add_render_attribute( 'pg_cards_single', 'class', 'oee__item--sm' );
		}

		if ( $is_carousel_enabled ) {
			$this->add_render_attribute( 'pg_cards_parent', 'class', 'one-elements__carousel-inner' );
			$this->start_carousel_markup();
		}

		if ( $this->is_filter_enabled ) {
			$this->add_render_attribute( 'pg_cards_parent', 'class', 'one-elements__filter-inner' );
			$this->start_filter_markup();
		}
		?>
        <div class="<?php echo esc_attr($this->get_style_class()); ?>">
			<?php if ( $this->is_filter_enabled ): ?>
				<?php $this->filter_header_markup( $this->taxonomy ); ?>
			<?php endif ?>
            <div class="oee__column-gap--reset">
                <div class="<?php echo esc_attr($this->get_style_class()); ?>--inner">
                    <div <?php $this->print_render_attribute_string( 'pg_cards_parent' ); ?>>
						<?php
						$pg_single_classes = $this->get_render_attributes( 'pg_cards_single', 'class' );
						foreach ( $posts as $index => $p ) :
						    if ($this->is_filter_enabled){
							    $filter_terms = get_the_terms( $p->ID, $this->taxonomy );
							    if (!empty( $filter_terms)){
								    $classes = array_merge( $pg_single_classes, wp_list_pluck( $filter_terms, 'slug' ) );
								    $classes = implode( ' ', $classes );
								    $this->add_render_attribute( 'pg_cards_single', 'class', $classes, true );
							    }else{
								    // fixed old class override
								    $this->add_render_attribute( 'pg_cards_single', 'class', $pg_single_classes, true );
							    }
						    }
							?>
                            <div <?php $this->print_render_attribute_string('pg_cards_single' ); ?>>
								<?php $this->print_single_posts_template( $p, $card_style ); ?>
                            </div>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
			<?php
			if ( $this->is_load_more_enabled ) {
				// add a new class to make button unique for read more styling
				$this->add_render_attribute( $this->lm_prefix.'button', 'class', 'one-elements-button__load_more');

				$this->add_render_attribute( $this->lm_prefix.'button', 'data-load-more-options', json_encode( $load_more_options ) );

				$lm_btn_options = [
					'prefix' => $this->lm_prefix,
					'settings' => $settings,
					'add_render_attribute' => false,
					'button_tag' => 'button',
				];
                echo "<div class='{$this->lm_prefix}button-wrapper'>";
				$this->render_button( $lm_btn_options );
				echo "</div>";
			}
			if ( $is_ajax_enabled ) {
				$this->print_single_posts_template__script( $card_style, $settings );
			}
			?>
        </div>
		<?php
		if ( $is_carousel_enabled ) $this->end_carousel_markup();
		if ( $this->is_filter_enabled ) $this->end_filter_markup();

	}

	protected function get_load_more_options(){
		$load_more_options = array();
		if ( $this->is_load_more_enabled ) {
			$load_more_options['post_type'] = $this->post_type;
			$load_more_options['taxonomy'] = $this->taxonomy;
			$load_more_options['load_more_items'] =  $this->get_settings_for_display('items_to_load');
			$load_more_options['load_more_template_id'] = $this->load_more_template_id;
			if ( 'one-elements-posts' === $this->get_name()){
				$load_more_options['include_comments'] = true;
				$load_more_options['include_author'] = true;
				$load_more_options['include_date'] = true;
				$load_more_options['include_time'] = true;
				$load_more_options['include_taxonomy_links'] = true;
				$load_more_options['date_format'] = $this->get_settings_for_display('date_format');
			}

			if ( $this->is_filter_enabled ) {
				$load_more_options['filter_enabled'] = true;
			}
		}
		return $load_more_options;
	}

	protected function show_comment_count__script( $show = '' ) {
		if ( $show !== 'yes' ) return;
		?>
		<span class="post-comments oee_post_meta--single">
            <span class="post-meta-number oee_meta_text">{%=o.comment_count%}</span>
            <span class="post-meta-title oee_meta_text">{%=o.comment_text%}</span>
        </span>
        <?php
	}

	protected function show_author_info__script( $show = '' ) {
		if ( empty($show) ) return;
		?>
		<span class="author-info oee_post_meta--single">
		<?php if ( $show == 'image' ) : ?>
			<span class='author-avatar'><a href='{%=o.author_link%}'><img src='{%=o.author_avatar%}' alt='Author Image'></a></span>
		<?php endif; ?>
		<?php if ( $show == 'name' ) : ?>
			<span class='author-name'><a href='{%=o.author_link%}'>{%=o.author_display_name%}</a></span>
		<?php endif; ?>
		<?php if ( $show == 'name_image' ) : ?>
			<span class='author-avatar'><a href='{%=o.author_link%}'><img src='{%=o.author_avatar%}' alt='Author Image'></a></span>
			<span class='author-name'><a href='{%=o.author_link%}'>{%=o.author_display_name%}</a></span>
		<?php endif; ?>
		</span>
		<?php
	}

	protected function show_date_info__script( $show = '' ) {
		if ( $show !== 'yes' ) return;
		?>
		{% if (o.display_date) { %}
		<span class="posted-on oee_post_meta--single">
		    <?php if ($this->show_meta_icon): ?>
			<i class="far fa-calendar-alt"></i>
			<?php endif; ?>
			<time class="entry-date published oee_meta_text" datetime="{%=o.iso_date%}">{%=o.display_date%}</time>
		</span>
		{% } %}
        <?php
	}

	protected function show_time_info__script( $show = '' ) {
		if ( $show !== 'yes' ) return;
		?>
		{% if (o.display_time) { %}
		<time class='posted-at oee_post_meta--single'>
		    <?php if ($this->show_meta_icon): ?>
            <i class='far fa-clock'></i>
			<?php endif; ?>
            <span class='oee_meta_text'>{%=o.display_time%}</span>
		</time>
		{% } %}
        <?php
	}

	protected function show_post_category_info__script( $show = '' ) {
		if ( $show !== 'yes' ) return;
		?>
		{% if (o.taxonomies && o.taxonomies.length) { %}
		<span class="post-categories oee_post_meta--single">
			<?php if ($this->show_meta_icon): ?>
                <i class='far fa-folder'></i>
			<?php endif; ?>
			<span class="oee_meta_text">
				{% for (var i=0; i<o.taxonomies.length; i++) { %}
					<a href="{%=o.taxonomies[i].term_link%}" rel="category tag">{%=o.taxonomies[i].name%}</a>
				{% } %}
			</span>
		</span>
		{% } %}
		<?php
	}

	protected function print_single_posts_template__script( $card_style, $settings = [] ) {
		if ( empty($settings) ) $settings = $this->get_settings_for_display();

		$excerpt_length = array(
			'line' => $settings['content_max_line'],
			'tablet_line' => $settings['content_max_line_tablet'],
			'mobile_line' => $settings['content_max_line_mobile']
		);

		$excerpt_length = array_filter( $excerpt_length );
		$excerpt_length = json_encode( $excerpt_length );

		$this->add_render_attribute( 'template_posts_single_regular_state', 'class', 'one-elements-element__regular-state' );
		if ( $settings['posts_border_gradient_type'] ) {
			$this->add_render_attribute( 'template_posts_single_regular_state', 'class', 'one-elements-element__border-gradient' );
		}
		$this->add_render_attribute( 'template_posts_single_hover_state', 'class', 'one-elements-element__hover-state' );
		if ( $settings['posts_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'template_posts_single_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'template_one-elements-element-regular__state-inner', 'class', 'one-elements-element__state-inner');
		$this->add_render_attribute( 'template_one-elements-element-hover__state-inner', 'class', 'one-elements-element__state-inner');
		if ( $settings['posts_use_featured_image'] == 'yes' ) {
			$this->add_render_attribute( 'template_one-elements-element-regular__state-inner', 'style', "background-image: url('{%=o.thumbnail%}')", true);
		}
		if ( $settings['posts_use_featured_image_hover'] == 'yes' ) {
			$this->add_render_attribute( 'template_one-elements-element-hover__state-inner', 'style', "background-image: url('{%=o.thumbnail%}')", true);
		}

		$this->add_render_attribute( 'template_'.$this->single_item_name, [
			'class' => [
				'one-elements-element '.$this->single_item_name.' oee-single-post',
				'oee-single-post--{%=o.post_id%}'
			],
			'data-post-id' => '{%=o.post_id%}'

		], null, true );

		$dynamic_height_enabled = empty( $settings['layout_height'] ) ? false : true;

		if ( $dynamic_height_enabled ) {
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__dynamic-height' );
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__dynamic-height-' . $settings['layout_height'] );
		} else if ( $card_style == 'classic-card-3' ) {
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-ratio' );
		} else if ( $card_style == 'classic-card-4' || $card_style == 'classic-card-5' ) {
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-fixed' );
		} else if ( strpos( $card_style, 'modern-card' ) !== false ) {
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-fixed' );
		}

		if ( ! empty( $settings['layout_content_position'] ) ) {
			$this->add_render_attribute( 'template_'.$this->single_item_name, 'class', 'oee__align-' . $settings['layout_content_position'] );
		}

		$btn_options = [
			'prefix' => $this->prefix,
			'settings' => $settings,
			'add_render_attribute' => true,
			'button_tag' => 'a',
			'button_atts' => ' href="{%=o.guid%}"'
		];


		$show_content = $this->get_settings_for_display('show_content');
		$show_comments = $this->get_settings_for_display('show_comments');
		$show_cats = $this->get_settings_for_display('show_cats');
		$show_date = $this->get_settings_for_display('show_date');
		$show_time = $this->get_settings_for_display('show_time');
		$show_author = $this->get_settings_for_display('show_author_info');

		?>

        <script type="text/x-tmpl" id="<?php echo esc_attr($this->load_more_template_id); ?>">

			<div class="oee__item {%=o.term_classes%}">
				
				<div <?php $this->print_render_attribute_string( 'template_'.$this->single_item_name ); ?>>
					
					<!-- Regular State Background -->
					<span <?php $this->print_render_attribute_string( 'template_posts_single_regular_state' ); ?>>
						<span <?php $this->print_render_attribute_string( 'template_one-elements-element-regular__state-inner' ); ?>></span>
					</span>
					
					<!-- Hover State Background -->
					<span <?php $this->print_render_attribute_string( 'template_posts_single_hover_state' ); ?>>
						<span <?php $this->print_render_attribute_string( 'template_one-elements-element-hover__state-inner' ); ?>></span>
					</span>

					<!-- Content -->
					<div class="one-elements-element__content">

						<div class="one-elements-element__content-inner">

							<?php if ( strpos( $card_style, 'post-card' ) !== false ) : ?>

								<div class="one-elements-element one-elements-image">

								    <!-- Regular State Background -->
								    <span class="one-elements-element__regular-state">
								        <span class="one-elements-element__state-inner"></span>
								    </span>

								    <!-- Hover State Background -->
								    <span class="one-elements-element__hover-state">
								        <span class="one-elements-element__state-inner"></span>
								    </span>

								    <!-- Content -->
								    <div class="one-elements-element__content one-elements-element__content-back">
								        <img src="{%=o.thumbnail%}" alt="{%=o.title%}">
								    </div>
									<?php if ( $card_style == 'post-card-2' ): ?>
										
										<!-- Content Button -->
									    <div class="one-elements-element__content">
											<?php $this->render_button( $btn_options ); ?>
									    </div>
										
									<?php endif ?>

								</div>

							<?php endif; ?>

							<div class="oee_post_meta--info">
						
								<?php if ( $card_style == 'post-card-5' ): ?>
									<?php $this->show_author_info__script( $show_author ); ?>
								<?php endif ?>

								<?php $this->show_date_info__script( $show_date ); ?>
								<?php $this->show_time_info__script( $show_time ); ?>
								
								<?php if ( $card_style == 'post-card-5' ): ?>
									<?php $this->show_comment_count__script( $show_comments ); ?>
									<?php $this->show_post_category_info__script( $show_cats ); ?>
								<?php endif ?>

							</div>

							<div class="one-elements-element one-elements-heading">
								<h3 class="one-elements-element__content">{%=o.post_title%}</h3>
							</div>
							
							<?php if ( $show_content == 'yes' ): ?>

								<div class="elementor-text-editor elementor-clearfix">
									<p class="oee__text_excerpt" data-oee__text_excerpt_config='<?php echo esc_attr($excerpt_length); ?>'>{%=o.post_excerpt%}</p>
								</div>
								
							<?php endif ?>

							<?php if ( ! in_array( $card_style, ['classic-card-4', 'classic-card-5', 'post-card-2'] ) ): ?>
								<!-- Content Button -->
								<?php $this->render_button( $btn_options ); ?>
							<?php endif ?>

						</div>

					</div>

				</div>

			</div>

		</script>

		<?php

	}

	/**
	 * Print the details of a single post
	 *
	 * @param WP_Post $p single posts post
	 * @param $card_style
	 * @param array $settings
	 */
	protected function print_single_posts_template( $p, $card_style, $settings = [] ) {

		if ( empty($p) || !$p instanceof WP_Post ) return;
		if ( empty($settings) ) $settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'one-elements-element-regular__state-inner', 'class', 'one-elements-element__state-inner');
		$this->add_render_attribute( 'one-elements-element-hover__state-inner', 'class', 'one-elements-element__state-inner');

		$thumbnail = null;

		if ( $settings['posts_use_featured_image'] == 'yes' ) {

			$thumbnail = $thumbnail ? $thumbnail : Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail', $settings );
			$this->add_render_attribute( 'one-elements-element-regular__state-inner', 'style', "background-image: url('".$thumbnail."')", true);
		}

		if ( $settings['posts_use_featured_image_hover'] == 'yes' ) {

			$thumbnail = $thumbnail ? $thumbnail : Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'thumbnail', $settings );

			$this->add_render_attribute( 'one-elements-element-hover__state-inner', 'style', "background-image: url('".$thumbnail."')", true);

		}

		$this->add_render_attribute( $this->single_item_name, [

			'class' => [
				'one-elements-element '.$this->single_item_name.' oee-single-post',
				'oee-single-post--' . $p->ID,
				is_sticky( $p->ID ) ? 'sticky' : ''
			],
			'data-post-id' => $p->ID

		], null, true );

		$dynamic_height_enabled = empty( $settings['layout_height'] ) ? false : true;

		if ( $dynamic_height_enabled ) {
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__dynamic-height' );
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__dynamic-height-' . $settings['layout_height'] );
		} else if ( $card_style == 'classic-card-3' ) {
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-ratio' );
		} else if ( $card_style == 'classic-card-4' || $card_style == 'classic-card-5' ) {
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-fixed' );
		} else if ( strpos( $card_style, 'modern-card' ) !== false ) {
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__dynamic-height oee__dynamic-height-fixed' );
		}

		if ( ! empty( $settings['layout_content_position'] ) ) {
			$this->add_render_attribute( $this->single_item_name, 'class', 'oee__align-' . $settings['layout_content_position'] );
		}

		$btn_options = [
			'prefix' => $this->prefix,
			'settings' => $settings,
			'add_render_attribute' => true,
			'button_tag' => 'a',
			'button_atts' => ' href="'.get_the_permalink( $p ).'"'
		];

		?>

        <div <?php $this->print_render_attribute_string( $this->single_item_name ); ?>>

            <!-- Regular State Background -->
            <span <?php $this->print_render_attribute_string( 'posts_single_regular_state' ); ?>>
				<span <?php $this->print_render_attribute_string( 'one-elements-element-regular__state-inner' ); ?>></span>
			</span>

            <!-- Hover State Background -->
            <span <?php $this->print_render_attribute_string( 'posts_single_hover_state' ); ?>>
				<span <?php $this->print_render_attribute_string( 'one-elements-element-hover__state-inner' ); ?>></span>
			</span>

            <!-- Content -->
            <div class="one-elements-element__content">

                <div class="one-elements-element__content-inner">

					<?php $this->show_sticky_markup( $p, $card_style ); ?>
					
					<?php if ( strpos( $card_style, 'post-card' ) !== false ): ?>
						<?php $this->show_featured_image( $p, $card_style, $btn_options ); ?>
					<?php endif ?>
					
					<?php if ( in_array( $card_style, ['classic-card-4', 'classic-card-5'] ) ): ?>
						<?php $this->show_author_info( $p ); ?>
					<?php endif ?>
                		
					<div class="oee_post_meta--info">
						
						<?php if ( $card_style == 'post-card-5' ): ?>
							<?php $this->show_author_info( $p ); ?>
						<?php endif ?>

						<?php $this->show_date_info( $p ); ?>
						<?php $this->show_time_info( $p ); ?>
						
						<?php if ( $card_style == 'post-card-5' ): ?>
							<?php $this->show_comment_count( $p ); ?>
							<?php $this->show_post_category_info( $p ); ?>
						<?php endif ?>

					</div>

					<?php $this->show_title( $p ); ?>
					<?php $this->show_excerpt( $p ); ?>
					
					<?php if ( ! in_array( $card_style, ['classic-card-4', 'classic-card-5', 'post-card-2'] ) ): ?>
						<?php $this->render_button( $btn_options ); ?>
					<?php endif ?>

                </div>

            </div>

        </div>

		<?php

	}

	protected function show_sticky_markup( $post, $card_style = '' ) {

		$show_sticky_indicator = apply_filters( 'oee__show-post-sticky--indicator', false, $post, $card_style );

		if ( ! is_sticky( $post->ID ) || ! $show_sticky_indicator ) return;

		?>

		<div class="post-sticky--indicator">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M25.333 0h-18.666c-0.368 0-0.667 0.299-0.667 0.667v30.666c0 0.269 0.163 0.513 0.412 0.616 0.249 0.104 0.536 0.045 0.727-0.144l8.861-8.863 8.861 8.861c0.128 0.128 0.299 0.196 0.472 0.196 0.085 0 0.172-0.016 0.255-0.051 0.249-0.103 0.412-0.347 0.412-0.616v-30.666c0-0.368-0.299-0.667-0.667-0.667zM22.732 9.812l-3.527 2.563 1.347 4.145c0.089 0.275-0.008 0.576-0.243 0.745-0.117 0.085-0.255 0.128-0.392 0.128s-0.275-0.043-0.392-0.128l-3.525-2.561-3.527 2.561c-0.235 0.171-0.549 0.171-0.784 0-0.233-0.169-0.332-0.471-0.243-0.745l1.347-4.145-3.525-2.563c-0.235-0.169-0.332-0.471-0.243-0.744 0.089-0.275 0.345-0.461 0.635-0.461h4.36l1.347-4.145c0.179-0.549 1.089-0.549 1.268 0l1.347 4.145h4.36c0.289 0 0.545 0.187 0.635 0.461 0.088 0.273-0.009 0.575-0.244 0.744z"></path></svg>
		</div>

		<?php

	}

	protected function show_featured_image( $post, $card_style = '', $btn_options = [] ) {

		$thumbnail =  Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail', $this->get_settings_for_display());

        if ( empty( $thumbnail) ) return;
		
		?>

        <div class="one-elements-element one-elements-image">

            <!-- Regular State Background -->
			<span class="one-elements-element__regular-state">
				<span class="one-elements-element__state-inner"></span>
			</span>

            <!-- Hover State Background -->
			<span class="one-elements-element__hover-state">
				<span class="one-elements-element__state-inner"></span>
			</span>

            <!-- Content -->
            <div class="one-elements-element__content one-elements-element__content-back">
                <img src="<?php echo esc_url( $thumbnail); ?>" alt="<?php echo esc_attr( $post->post_title ); ?>">
            </div>

			<?php if ( $card_style === 'post-card-2' ): ?>
				<!-- Content Button -->
				<div class="one-elements-element__content">
					<?php $this->render_button( $btn_options ); ?>
				</div>

			<?php endif ?>

			<?php if ( $card_style === 'post-card-3' || $card_style === 'post-card-4' ): ?>
				<!-- Content Button -->
				<div class="one-elements-element__content">
					<?php $this->show_author_info( $post ); ?>
				</div>
			<?php endif ?>
        </div>
        <?php

	}

	protected function show_excerpt( $post ) {

		$show_content = $this->get_settings_for_display('show_content');

        if ('yes' !== $show_content) return;

		$excerpt_length = array(
			'line' => $this->get_settings_for_display('content_max_line'),
			'tablet_line' =>$this->get_settings_for_display('content_max_line_tablet'),
			'mobile_line' => $this->get_settings_for_display('content_max_line_mobile'),
		);

		$excerpt_length = array_filter( $excerpt_length );
		$excerpt_length = json_encode( $excerpt_length );
        ?>

        <div class="elementor-text-editor elementor-clearfix">
            <p class="oee__text_excerpt" data-oee__text_excerpt_config='<?php echo esc_attr($excerpt_length); ?>'><?php echo get_the_excerpt( $post ); ?></p>
        </div>

        <?php

	}

	/**
	 * @param WP_Post $post
	 */
	protected function show_title( $post ) {
	    ?>
        <div class="one-elements-element one-elements-heading">
            <h3 class="one-elements-element__content">
                <a href="<?php echo esc_url( get_the_permalink($post->ID));?>">
                <?php echo esc_html( $post->post_title ); ?>
                </a>
            </h3>
        </div>

	    <?php

	}

	protected function show_author_info( $post ) {

		$show_author = $this->get_settings_for_display('show_author_info');

		if ( empty($show_author) ) return;

		$user_id = $post->post_author;
		$author_link = esc_url( get_author_posts_url( $user_id ) );
		$avatar_size = 45;
		$html = '';
		$html .= '<span class="author-info oee_post_meta--single">';

		switch ( $show_author ) {
			case 'image':
				$html .=  "<span class='author-avatar'><a href='{$author_link}'><img src='".get_avatar_url( $user_id, ['size' => $avatar_size] )."' alt='Author Image'></a></span>";
				break;
			case 'name':
				$html .=  "<span class='author-name'><a href='{$author_link}'>".get_the_author_meta( 'display_name', $user_id )."</a></span>";
				break;
			case 'name_image':
				$html .=  "<span class='author-avatar'><a href='{$author_link}'><img src='".get_avatar_url( $user_id, ['size' => $avatar_size] )."' alt='Author Image'></a></span>";
				$html .=  "<span class='author-name'><a href='{$author_link}'>".get_the_author_meta( 'display_name', $user_id)."</a></span>";
				break;
		}

		$html .=  '</span>';

		echo wp_kses( $html, one_elements_allowed_html());

	}

	protected function show_date_info( $post ) {

		$show_date = $this->get_settings_for_display('show_date');
		$date_format = $this->get_settings_for_display('date_format');
		$date_format = !empty($date_format) ? trim($date_format) : '';
		$html = $meta_icon = '';

		if ( 'yes' == $show_date ) {
			if ($this->show_meta_icon){
				$meta_icon = '<i class="far fa-calendar-alt"></i>';
			}
			$html .= sprintf(
				'<span class="posted-on oee_post_meta--single">%1$s<time class="entry-date published oee_meta_text" datetime="%2$s">%3$s</time></span>',
				$meta_icon,
				esc_attr( get_the_date( 'c', $post ) ),
				esc_html( get_the_date($date_format, $post) )
			);
        }

		echo wp_kses( $html , one_elements_allowed_html());

	}

	protected function show_time_info( $post ) {

		$show_time = $this->get_settings_for_display('show_time');
		$html = $meta_icon = '';

		if ( 'yes' == $show_time ) {
		    if ($this->show_meta_icon){
		        $meta_icon = "<i class='far fa-clock'></i>";
		    }
			$html .= sprintf( "<time class='posted-at oee_post_meta--single'>%s<span class='oee_meta_text'>%s</span></time>", $meta_icon, esc_attr( date( 'h:i A', get_post_time('U', false, $post))));
		}

		echo wp_kses( $html , one_elements_allowed_html());

	}

	protected function show_post_category_info( $post ) {

		$show_cats = $this->get_settings_for_display('show_cats');

		if ( 'yes' != $show_cats ) return;

		$cats = get_the_category( $post->ID );

		if ( empty($cats) ) return;
		$meta_icon = '';
		if ($this->show_meta_icon){
			$meta_icon = "<i class='far fa-folder'></i>";
		}
		$html = '<span class="post-categories oee_post_meta--single">';
		$html .= $meta_icon ;
		$html  .= '<span class="oee_meta_text">';

		foreach ( $cats as $cat ){
			$html .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" rel="category tag">' . $cat->name . '</a>';
		}

		$html .= '</span></span>';

		echo wp_kses( $html , one_elements_allowed_html());

	}

	protected function show_comment_count( $post ) {

		$show_comments = $this->get_settings_for_display('show_comments');

        if ( 'yes' != $show_comments ) return;

       	$count = get_comments_number($post->ID);
       	$text  = $count > 1 ? __('Comments', 'one-elements') : __('Comment', 'one-elements');

    	?>

        <span class="post-comments oee_post_meta--single">
            <span class="post-meta-number oee_meta_text"><?php echo number_format_i18n( $count ); ?></span>
            <span class="post-meta-title oee_meta_text"><?php echo esc_html( $text ); ?></span>
        </span>

    	<?php

	}

	/**
	 * Render posts widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_PostGrid() );