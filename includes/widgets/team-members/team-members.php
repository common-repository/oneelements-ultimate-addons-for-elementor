<?php
namespace OneElements\Includes\Widgets\TeamMembers;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Traits\One_Elements_Carousel_Trait;
use OneElements\Includes\Traits\One_Elements_Common_Widget_Trait;
use OneElements\Includes\Traits\One_Elements_Icon_Trait;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Team_Member extends Widget_Base {
	protected $carousel_option_key = 'one_elements_team_c';
	/**
     * It stores the type of the source we display members data from. Eg. editor or dynamic
	 * @var string
	 */
	protected $source;

	use One_Elements_Common_Widget_Trait;
	use One_Elements_Icon_Trait;
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
		return 'one-elements-team';
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
		return ['team', 'member', 'attorney', 'team-member', 'person', 'man', 'worker','people','staff', 'employee', 'speaker','speakers','keynote speaker'];
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
		return 'one-elements-widget-eicon eicon-person';
	}

	/**
     * Get the Widget title
	 * @return string
	 */
	public function get_title() {
		return __( 'Team Members', 'one-elements' );
	}

	protected function get_recommended_icons() {
		return [
			'fa-brands' => [
				'android',
				'apple',
				'behance',
				'bitbucket',
				'codepen',
				'delicious',
				'deviantart',
				'digg',
				'dribbble',
				'one-elements',
				'facebook',
				'flickr',
				'foursquare',
				'free-code-camp',
				'github',
				'gitlab',
				'globe',
				'google-plus',
				'houzz',
				'instagram',
				'jsfiddle',
				'linkedin',
				'medium',
				'meetup',
				'mixcloud',
				'odnoklassniki',
				'pinterest',
				'product-hunt',
				'reddit',
				'shopping-cart',
				'skype',
				'slideshare',
				'snapchat',
				'soundcloud',
				'spotify',
				'stack-overflow',
				'steam',
				'stumbleupon',
				'telegram',
				'thumb-tack',
				'tripadvisor',
				'tumblr',
				'twitch',
				'twitter',
				'viber',
				'vimeo',
				'vk',
				'weibo',
				'weixin',
				'whatsapp',
				'wordpress',
				'xing',
				'yelp',
				'youtube',
				'500px',
			],
			'fa-solid' => [
				'envelope',
				'link',
				'rss',
			],
		];
	}

	protected function get_default_icons() {
		return [
			'fab fa-facebook',
			'fab fa-twitter',
			'fab fa-linkedin',
			'fab fa-github',
			'fab fa-wordpress',
		];
	}

	protected function get_icon_control_default_args() {
		return [
			'excludes' => ['section_icon', 'icon', 'icon_css_id', 'icon_align', 'section_icon_style', 'view'],// dont use icon align and icon id from the icon trait for button icon
			'conditions' => [
				'icon_box_size' => ['show_team_icon' => 'yes', ],
				'icon_size' => ['show_team_icon' => 'yes',],
				'icon_color' => ['show_team_icon' => 'yes',],
				'icon_hover_color' => ['show_team_icon' => 'yes',],
				'icon_hover_animation' => ['show_team_icon' => 'yes',],
				'icon_transition' => ['show_team_icon' => 'yes',],
				'icon_background_section' => ['show_team_icon' => 'yes',],
				'icon_border_section' => ['show_team_icon' => 'yes',],
			],
			'selectors' => [
				'icon_border_radius' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-icon, {{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			    // fix hover state
				'icon_hover_color' => '{{WRAPPER}} .one-elements-team_member .one-elements-icon:hover .one-elements-icon__content_icon > *',
				'icon_background_hover' => '{{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',
				'icon_border_hover' => '{{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__hover-state',
				'icon_border_radius_hover' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-icon:hover, {{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .one-elements-team_member .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'icon_hover_shadow' => '{{WRAPPER}} .one-elements-team_member .one-elements-icon:hover',
			],
			'labels' => [
				'icon_background_section' =>  __( 'S. Icons Background', 'one-elements' ),
				'icon_border_section' =>  __( 'S. Icons Border & Shadow', 'one-elements' ),
			]
		];
	}

	protected function get_layout_card_styles() {

		return apply_filters('one_elements_team_layout_styles', [
			'' => __( 'Select a style', 'one-elements' ),
			'classic-card-1'   => __( 'Classic Card 1', 'one-elements' ),
			'post-card-1'   => __( 'Post Card 1', 'one-elements' ),
			'post-card-2'   => __( 'Post Card 2', 'one-elements' ),
		]);

	}

	protected function get_content_positions() {
		return apply_filters('one_elements_team_content_positions', [
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
			'mixed' => __( 'Mixed', 'one-elements' ),
		]);

	}

	protected function init_content_team_members_controls() {
		$this->start_controls_section( 'oe_section_team_content', [
				'label' => __( 'Team Members', 'one-elements' ),
			]
		);
		// we need this hidden field so that carousel trait works out of the box.
		$this->add_control( 'one_elements_fetch_type', [
			'label'       => __( 'Data Type', 'one-elements' ),
			'type'        => Controls_Manager::HIDDEN,
			'default'   => 'multiple',
		]);
		$this->add_control(
			'content_source',
			[
				'label'       => __( 'Content Source', 'one-elements' ),
				'description'       => __( 'You can show dynamic team member from the Team Member Custom Post. (You need to enable Team Member Custom Post in the Dashboard -> OneElements settings page). You can also create team member from this editor.', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => [
					'editor'   => __( 'Editor - Static', 'one-elements' ),
					'dynamic'   => __( 'Custom Post - Dynamic', 'one-elements' ),
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'       => __( 'Number of members to display', 'one-elements' ),
				'description'       => __( 'You can specify how many team members to display from custom post', 'one-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'condition' => [
					'content_source' => 'dynamic'
				]
			]
		);
		$member = new Repeater();

		$member->add_control( 'name', [
				'label' => __( 'Name', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Kan Doe', 'one-elements' ),
				'default' => __( 'Von Doe', 'one-elements' ),
			]
		);

		$member->add_control(
			'designation',
			[
				'label' => __( 'Designation', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Senior Officer', 'one-elements' ),
				'default' => __( 'Software Engineer', 'one-elements' ),
			]
		);


		$member->add_control(
			'image',
			[
				'label' => __( 'Image', 'one-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$member->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'		=> 'image',
				'default'	=> 'thumbnail',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$member->add_control( 'member_unique_bg_n_border_color',
			[
				'label' => __( 'Color', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .oee_u_bg_color {{CURRENT_ITEM}} .one-elements-element__content-inner,
					{{WRAPPER}} .oee_u_bg_color_h {{CURRENT_ITEM}} .one-elements-element__content-inner:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .oee_u_border_color {{CURRENT_ITEM}} .one-elements-element__content-inner,
					{{WRAPPER}} .oee_u_border_color_h {{CURRENT_ITEM}}:hover .one-elements-element__content-inner' => 'border-color: {{VALUE}}'
				]
			]
		);
		$member->add_control(
			'enable_social',
			[
				'label' => __( 'Enable Social Profiles', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'one-elements' ),
				'label_on' => __( 'Yes', 'one-elements' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		// Add 5 Social icons
		foreach ( $this->get_default_icons() as $index => $default_icon )
		{
			$index++;
			$member->add_control(
				'social_icon'.$index,
				[
					'label' => sprintf(__( '#%d Social Icon', 'one-elements' ), $index),
					'type' => Controls_Manager::ICONS,
					'label_block' => true,
					'default' => [
						'value' => $default_icon,
						'library' => 'fa-brands',
					],
					'recommended' => $this->get_recommended_icons(),
					'condition' => [
						'enable_social' => 'yes'
					],
				]
			);
			$member->add_control(
				'social_link'.$index,
				[
					'label' => sprintf(__( '#%d Social Link', 'one-elements' ), $index),
					'type' => Controls_Manager::URL,
					'label_block' => true,
					'placeholder' => __( 'https://your-link.com', 'one-elements' ),
					'condition' => [
						'enable_social' => 'yes'
					],
				]
			);
		}

		$this->add_control(
			'one_elements_members',
			[
				'label' => __( 'Members Content', 'one-elements' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $member->get_controls(),
				'default' => [
					[
						'name' => __( 'John Doe', 'one-elements' ),
						'designation' => __( 'Software Engineer', 'one-elements' ),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '<div style="display: flex; justify-content: flex-start; align-items: center;"><img style="padding-right: 15px; max-width: 50px !important; max-height: 100% !important;" src="{{{ image[\'url\'] }}}" > {{{ name }}} </div>',
				'condition' => [
				    'content_source' => 'editor'
                ]
			]
		);

		$this->end_controls_section();
	}

	protected function init_style_team_members_controls() {
		$this->start_controls_section(
			'section_style_team_members',
			[
				'label' => __( 'Team Members', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->_init_style_typography_controls();

		$this->_init_style_spacing_controls();

		$this->_init_style_color_controls();

		$this->end_controls_section();
	}

	private function _init_style_color_controls() {

		$this->add_control(
			'style_team_colors_heading',
			[
				'label' => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'team_colors' );

		// normal tab
		$this->start_controls_tab( 'team_colors_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'team_name_color',
			'label' => __( 'Name', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .team-member-name .one-elements-element__content',
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'team_designation_color',
			'label' => __( 'Designation', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .team-member-designation .one-elements-element__content',
		]);

		$this->end_controls_tab();

		// hover tab
		$this->start_controls_tab( 'team_colors_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'team_name_color_hover',
			'label' => __( 'Name', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-team_member:hover .team-member-name .one-elements-element__content',
		]);

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name' => 'team_designation_color_hover',
			'label' => __( 'Designation', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-team_member:hover .team-member-designation .one-elements-element__content',
		]);

		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs

	}

	private function _init_style_typography_controls() {

		$this->add_control(
			'style_team_typography_heading',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'team_name_typography',
				'label' => __( 'Name', 'one-elements' ),
				'selector' => '{{WRAPPER}} .team-member-name .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'team_designation_typography',
				'label' => __( 'Designation', 'one-elements' ),
				'selector' => '{{WRAPPER}} .team-member-designation .one-elements-element__content',
			]
		);

	}

	private function _init_style_spacing_controls() {

		// SPACING
		$this->add_control(
			'style_team_spacing_heading',
			[
				'label' => __( 'Spacing', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'team_padding',
			[
				'label' => __( 'Single Team Padding', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'team_margin',
			[
				'label' => __( 'Single Team Margin', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'team_image_margin',
			[
				'label' => __( 'Member Image', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
				    'layout_style' => ['post-card-1', 'post-card-2']
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
					'{{WRAPPER}} .one-elements-team_member .team-member-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .one-elements-team_member .team-member-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'team_icon_margin',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-social_icons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'show_team_icon' => 'yes',
				]
			]
		);

		//Transition
		$this->add_control(
			'team_transition',
			[
				'label' => __( 'Transition Speed', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);
	}

	protected function init_content_layout_controls() {
		$this->start_controls_section( 'oe_section_team_layout', [
				'label' => __( 'Layout Settings', 'one-elements' ),
			]
		);
		$this->add_control(
			'layout_style',
			[
				'label' => __( 'Layout Style', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_layout_card_styles(),
				'default' => 'classic-card-1',
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

		$this->add_control('layout_height', [
			'label'       => __( 'Height', 'one-elements' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''          => __( 'Default', 'one-elements' ),
				'ratio'    => __( 'Ratio', 'one-elements' ),
				'fixed'    => __( 'Fixed', 'one-elements' ),
			],
			'condition' => [
				'layout_style!' => [
					'post-card-1'
				],
			],
		]);

		$this->add_responsive_control(
			'layout_height_ratio',
			[
				'label' => __( 'Height Ratio', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member.oee__dynamic-height:before' => 'padding-bottom: {{SIZE}}%;'
				],
				'condition' => [
					'layout_height' => 'ratio',
					'layout_style!' => [
						'post-card-1'
					],
				]
			]
		);

		$this->add_responsive_control(
			'layout_height_fixed',
			[
				'label' => __( 'Height Fixed', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member' => 'height: {{SIZE}}px;'
				],
				'condition' => [
					'layout_height' => 'fixed',
					'layout_style!' => [
						'post-card-1'
					],
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

		$this->add_control( 'show_team_icon', [
			'label' => __( 'Enable Social Profiles', 'one-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'one-elements' ),
			'label_on' => __( 'Yes', 'one-elements' ),
			'default' => 'yes',
			'separator' => 'before',
		]);

		$this->end_controls_section();
	}

	protected function init_style_icon_controls($options = []) {
		$options = !empty( $options) ? $options : $this->get_icon_control_default_args();
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Social Links', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['show_team_icon' => 'yes', ]
			]
		);

		$this->add_control(
			'social_links_visibility',
			[
				'label'       => __( 'Social Links Visibility', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'   => __( 'Always Show', 'one-elements' ),
					'on_hover'   => __( 'Show on Hover', 'one-elements' ),

				],
			]
		);

		$this->add_responsive_control(
			'social_profile_margin_bottom',
			[
				'label' => __( 'Social Profile Bottom ', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 0,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-social_icons' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'social_links_visibility' => ['on_hover', 'on_hover_2'],
				]
			]
		);

		$this->init_content_icon_settings($options);
		
		$this->add_responsive_control(
			'team_icon_inner_margin',
			[
				'label' => __( 'Icon Inner Space', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'max' => 50,
						'step' => 1
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-social_icons__single' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',//NOT last not First- this type selector should be used later
				],
				'condition' => [
					'show_team_icon' => 'yes',
				]
			]
		);

		$this->init_style_icon_settings($options);

		$this->end_controls_section();

	}

	protected function init_style_team_background_controls() {

		// Background Overlay Section
		$this->start_controls_section( 'one_elements_team_background_overlay', [
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
			'selector' => '{{WRAPPER}} .one-elements-team_member > .one-elements-element__regular-state .one-elements-element__state-inner:after',
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
				'{{WRAPPER}} .one-elements-team_member > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
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
			'selector' => '{{WRAPPER}} .one-elements-team_member > .one-elements-element__hover-state .one-elements-element__state-inner:after',
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
				'{{WRAPPER}} .one-elements-team_member > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
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

	}

	protected function init_style_team_border_n_shadow_controls() {

		$this->start_controls_section(
			'team_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_team_border' );

		$this->start_controls_tab(
			'tab_team_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'team_border',
				'label' => __( 'Box Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-team_member > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'team_border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member, {{WRAPPER}} .one-elements-team_member > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-team_member > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'team_box_shadow',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-team_member',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_team_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'team_border_hover',
				'label' => __( 'Box Border', 'one-elements' ),
				'selector' => '{{WRAPPER}}:hover .one-elements-team_member > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'team_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member:hover, {{WRAPPER}} .one-elements-team_member:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-team_member:hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_team_box_shadow',
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}}:hover .one-elements-team_member:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

    protected function init_style_team_profile_controls() {
		$this->start_controls_section(
			'style_team_profile_section',
			[
				'label' => __( 'Profile Box', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
	    $this->add_responsive_control(
		    'team_profile_spacing',
		    [
			    'label' => __( 'Box Padding', 'one-elements' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],

		    ]
	    );
	    $this->add_group_control( Group_Control_ONE_ELEMENTS_Border::get_type(),
		    [
			    'name' => 'team_profile_border',
			    'label' => __( 'Box Border', 'one-elements' ),
			    'selector' => '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner',
		    ]
	    );
	    $this->add_responsive_control(
		    'team_profile_box_radius',
		    [
			    'label' => __( 'Box Border Radius', 'one-elements' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
			    ],
			    'separator' => 'after'
		    ]
	    );
	    $this->add_control(
		    'style_team_profile_colors_shadow_heading',
		    [
			    'label' => __( 'Box Colors & Shadow', 'one-elements' ),
			    'type' => Controls_Manager::HEADING,
		    ]
	    );

	    $this->start_controls_tabs( 'team_profile_box_colors' );
		// normal tab
		$this->start_controls_tab( 'team_profile_box_colors_normal', [
			'label' => __( 'Normal', 'one-elements' ),
		]);
	    $this->add_responsive_control( 'team_profile_box_position', [
		    'label'     => __( 'Box Position', 'one-elements' ),
		    'type'      => Controls_Manager::SLIDER,
		    'range'     => [
			    'px' => [
				    'max'  => 200,
				    'step' => 1,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner' => 'transform: translateY({{SIZE}}{{UNIT}});',
		    ],

	    ]);
		$this->add_control( 'enable_unique_bg_color', [
			'label' => __( 'Use Unique Background Color', 'one-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'one-elements' ),
			'label_on' => __( 'Yes', 'one-elements' ),
			'default' => '',
		]);
		$this->add_group_control( Group_Control_Gradient_Background::get_type(), [
			'name' => 'team_profile_bg',
			'label' => __( 'Background Color', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner',
			'condition' => [
				'enable_unique_bg_color!' => 'yes'
			],
		]);
		$this->add_control( 'enable_unique_border_color', [
			'label' => __( 'Use Unique Border Color', 'one-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'one-elements' ),
			'label_on' => __( 'Yes', 'one-elements' ),
			'default' => '',
		]);
		$this->add_control( 'team_profile_border_color', [
			'label' => __( 'Border Color', 'one-elements' ),
			'description' => __( 'This color will be used for Team Profile Border', 'one-elements' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
			'{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner' => 'border-color:{{VALUE}};'
			],
			'condition' => [
				'enable_unique_border_color!' => 'yes'
			],
		]);
		$this->add_group_control( Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'one_profile_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-team_member .one-elements-element__content-inner',
			]
		);
		$this->end_controls_tab();

		// hover tab
		$this->start_controls_tab( 'team_profile_box_colors_hover', [
			'label' => __( 'Hover', 'one-elements' ),
		]);
	    $this->add_responsive_control( 'team_profile_box_position_hover', [
		    'label'     => __( 'Box Position', 'one-elements' ),
		    'type'      => Controls_Manager::SLIDER,
		    'range'     => [
			    'px' => [
				    'max'  => 200,
				    'step' => 1,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .one-elements-team_member:hover .one-elements-element__content-inner' => 'transform: translateY({{SIZE}}{{UNIT}});',
		    ],

	    ]);
		$this->add_control( 'enable_unique_bg_color_hover', [
			'label' => __( 'Use Unique Background Color', 'one-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'one-elements' ),
			'label_on' => __( 'Yes', 'one-elements' ),
			'default' => '',
		]);
		$this->add_group_control( Group_Control_Gradient_Background::get_type(), [
			'name' => 'team_profile_bg_hover',
			'label' => __( 'Team Profile Background', 'one-elements' ),
			'types' => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .one-elements-team_member:hover .one-elements-element__content-inner',
			'condition' => [
				'enable_unique_bg_color_hover!' => 'yes'
			],

		]);
		$this->add_control( 'enable_unique_border_color_hover', [
			'label' => __( 'Use Unique Border Color', 'one-elements' ),
			'type' => Controls_Manager::SWITCHER,
			'label_off' => __( 'No', 'one-elements' ),
			'label_on' => __( 'Yes', 'one-elements' ),
			'default' => '',
		]);
		$this->add_control( 'team_profile_border_color_hover', [
			'label' => __( 'Team Profile Border Color', 'one-elements' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => ['{{WRAPPER}} .one-elements-team_member:hover .one-elements-element__content-inner' => 'border-color:{{VALUE}};'],
			'condition' => [
				'enable_unique_border_color_hover!' => 'yes'
			],
		]);
		$this->add_group_control( Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'one_profile_box_shadow_hover',
				'exclude' => [
					'box_shadow_position',
				],
				'label' => __( 'Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-team_member:hover .one-elements-element__content-inner',
			]
		);
		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs
		$this->end_controls_section();

	}

	protected function init_style_image_border_settings() {

		$this->start_controls_section(
			'one_image_border_section',
			[
				'label' => __( 'Image Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
				    'layout_style' => ['post-card-1', 'post-card-2']
                ]
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
				'selector' => '{{WRAPPER}} .one-elements-team_member .one-elements-image > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member .one-elements-image, {{WRAPPER}} .one-elements-team_member .one-elements-image > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member .one-elements-image > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-team_member .one-elements-image > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control( Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'one_image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'label' => __( 'Image Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-team_member img.one-elements-image',
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
				'selector' => '{{WRAPPER}} .one-elements-team_member .one-elements-image > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-team_member:hover .one-elements-image, {{WRAPPER}} .one-elements-team_member:hover .one-elements-image > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-team_member:hover .one-elements-image > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-team_member:hover .one-elements-image >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
				'selector' => '{{WRAPPER}} .one-elements-team_member:hover img.one-elements-image',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}
	/**
	 * Register team member widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->init_content_team_members_controls();
		$this->init_content_layout_controls();
		$this->get_content_carousel_controls();

		$this->init_style_team_members_controls();
		$this->init_style_team_background_controls();
		$this->init_style_team_border_n_shadow_controls();
		$this->init_style_image_border_settings();
		$this->init_style_team_profile_controls();

		$this->init_style_icon_controls( $this->get_icon_control_default_args() );
		$this->init_style_icon_background_settings( $this->get_icon_control_default_args() );
		$this->init_style_icon_border_settings( $this->get_icon_control_default_args() );

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
		//error_log( print_r( $settings, 1));
		$this->source = !empty( $settings['content_source']) ? $settings['content_source'] : 'editor';
		$card_style = $settings['layout_style'];
		$social_profile_style = isset( $settings['social_links_visibility']) ? "social-display-{$settings['social_links_visibility']}" : 'social-display-default';
		$is_carousel_enabled = $this->is_carousel_enabled( $card_style );
		$items_gap_class = $this->get_items_gap_class( $settings['items_gap'] );
		$card_style_class = 'card_style--' . $card_style;

		$this->add_render_attribute( 'team_cards_parent', 'class', 'oee__row oee__row-wrap oee__align-middle' );
		$this->add_render_attribute( 'team_cards_parent', 'class', $card_style_class );
		$this->add_render_attribute( 'team_cards_parent', 'class', $social_profile_style );
		$this->add_render_attribute( 'team_cards_parent', 'class', $items_gap_class );

		if ( strpos( $card_style, 'classic-card' ) !== false ) $this->add_render_attribute( 'team_cards_parent', 'class', 'card_style--classic-card' );
		if ( strpos( $card_style, 'modern-card' ) !== false ) $this->add_render_attribute( 'team_cards_parent', 'class', 'card_style--modern-card' );
		if ( strpos( $card_style, 'post-card' ) !== false ) $this->add_render_attribute( 'team_cards_parent', 'class', 'card_style--post-card' );

		$this->add_render_attribute( 'team_cards_single', 'class', 'oee__item' );

		if ( in_array( $card_style, ['modern-card-1', 'modern-card-2', 'modern-card-3', 'modern-card-4', 'modern-card-5'] ) ) {
			$this->add_render_attribute( 'team_cards_single', 'class', 'oee__item--sm' );
		}


		$this->add_render_attribute( 'one-elements-team_member', 'class', 'one-elements-element one-elements-team_member');

		$dynamic_height_enabled = empty( $settings['layout_height'] ) ? false : true;
		$layout_content_position = empty( $settings['layout_content_position'] ) ? false : true;

		if ( $dynamic_height_enabled ) {
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__dynamic-height' );
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__dynamic-height-' . $settings['layout_height'] );
		} elseif ( $card_style == 'classic-card-1' ) {
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__dynamic-height oee__dynamic-height-ratio' );
		}

		if ( $layout_content_position ) {
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__align-' . $settings['layout_content_position'] );
		} elseif ( $card_style == 'classic-card-1') {
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__align-bottom-left' );
		} elseif ( $card_style == 'post-card-1' || $card_style == 'post-card-2' ) {
			$this->add_render_attribute( 'one-elements-team_member', 'class', 'oee__align-bottom-center' );
		}


		// single member background related
		$this->add_render_attribute( 'team_member_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['team_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'team_member_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'team_member_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['team_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'team_member_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		// image related
		$this->add_render_attribute( 'one_image_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['one_image_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'one_image_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'one_image_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['one_image_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'one_image_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		//Social Icon BG AND BORDER Related
		$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( $is_carousel_enabled ) {
			$this->add_render_attribute( 'team_cards_parent', 'class', 'one-elements__carousel-inner' );
			$this->start_carousel_markup();
		}

		if ( $settings['enable_unique_bg_color'] == 'yes' ) {
			$this->add_render_attribute( 'team_cards_parent', 'class', 'oee_u_bg_color' );
		}

		if ( $settings['enable_unique_bg_color_hover'] == 'yes' ) {
			$this->add_render_attribute( 'team_cards_parent', 'class', 'oee_u_bg_color_h' );
		}

		if ( $settings['enable_unique_border_color'] == 'yes' ) {
			$this->add_render_attribute( 'team_cards_parent', 'class', 'oee_u_border_color' );
		}

		if ( $settings['enable_unique_border_color_hover'] == 'yes' ) {
			$this->add_render_attribute( 'team_cards_parent', 'class', 'oee_u_border_color_h' );
		}

		?>
		<div class="<?php echo esc_attr($this->get_name());?>">
			<div class="oee__column-gap--reset">
				<div class="<?php echo esc_attr($this->get_name());?>--inner">
					<div <?php $this->print_render_attribute_string( 'team_cards_parent' ); ?>>
					    <?php $this->print_all_members($settings, $card_style); ?>
					</div> <!--ends .oee_row-->
				</div><!--ends .one-elements-team--inner-->
			</div><!--ends .oee_column-gap--reset-->
		</div> <!--ends .one-elements--team-->
		<?php
		if ( $is_carousel_enabled ) $this->end_carousel_markup();
	}

	protected function print_all_members( $settings = [], $card_style ='' ) {
	    if (empty( $settings)) $settings = $this->get_settings_for_display();

		if ( 'dynamic' === $this->source ) {
			// get dynamic members from custom post
			$result = new WP_Query( [
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => !empty( $settings['posts_per_page']) ? (int) $settings['posts_per_page']: 3,
			] );
			$members = $result->get_posts();
			if (is_wp_error( $members) || empty( $members)) return;

		}else{
			// get static members from the editor
			if (empty( $settings['one_elements_members']) || !is_array( $settings['one_elements_members'])) return;
			$members =  $settings['one_elements_members'];
		}

		foreach ( $members as $member ) { ?>
            <!--Team member 1,2,3-->
            <div <?php $this->print_render_attribute_string( 'team_cards_single' ); ?>>
				<?php $this->print_single_member( $member, $card_style, $settings ); ?>
            </div> <!--ends .oee_item-->
		<?php }
	}

	protected function print_single_member__classic_card_1( $member, $card_style, $settings ) {
        $id = 'dynamic' === $this->source ? $member->ID : $member['_id'];
		$this->add_render_attribute( 'one-elements-element__state-inner', 'class', 'one-elements-element__state-inner', true );
            
        $this->add_render_attribute( 'one-elements-team_member', 'class', [
			'elementor-repeater-item-' . $id
		]);

		if ( $thumbnail = $this->get_thumbnail_url( $member ) ) {
			$this->add_render_attribute( 'one-elements-element__state-inner', 'style', "background-image: url('".$thumbnail."')", true);
		}
		?>
        <div <?php $this->print_render_attribute_string( 'one-elements-team_member' ); ?>>
            <!-- Regular Background -->
            <span <?php $this->print_render_attribute_string('team_member_regular_state'); ?>>
                <span <?php $this->print_render_attribute_string('one-elements-element__state-inner'); ?>></span>
            </span>

            <!-- Hover Background -->
            <span <?php $this->print_render_attribute_string('team_member_hover_state'); ?>>
                <span <?php $this->print_render_attribute_string('one-elements-element__state-inner'); ?>></span>
            </span>

            <!-- Member Content -->
            <div class="one-elements-element__content">
            	<div class="one-elements-element__content-inner">
	                <?php
	                    $this->print_name( $member );
	                    $this->print_designation( $member );
	                    $this->print_social_icons( $member, $settings );
	                ?>
            	</div>
            </div> <!-- Ends .one-elements-element__content-->
        </div> <!--ends .one-elements-team_member-->
		<?php

		$this->remove_render_attribute( 'one-elements-team_member', 'class', [
			'elementor-repeater-item-' . $id
		]);

	}


	protected function print_single_member__post_card_1( $member, $card_style, $settings ) {
		?>
        <div <?php $this->print_render_attribute_string( 'one-elements-team_member' ); ?>>
            <!-- Member Content -->
            <div class="one-elements-element__content">
            	<div class="one-elements-element__content-inner">
	                <?php
	                    $this->print_image( $member, $settings );
	                    $this->print_name( $member, $settings );
	                    $this->print_designation( $member, $settings );
	                ?>
            	</div>
            </div> <!-- Ends .one-elements-element__content-->
        </div> <!--ends .one-elements-team_member-->
		<?php
	}

	protected function print_single_member__post_card_2( $member, $card_style, $settings ) {
		$this->print_single_member__post_card_1( $member, $card_style, $settings );
	}

	protected function print_single_member( $member, $card_style, $settings ) {
		if ( $card_style == 'classic-card-1' ) {
			$this->print_single_member__classic_card_1( $member, $card_style, $settings );
		}

		if ( $card_style == 'post-card-1' ) {
			$this->print_single_member__post_card_1( $member, $card_style, $settings );
		}

		if ( $card_style == 'post-card-2' ) {
			$this->print_single_member__post_card_2( $member, $card_style, $settings );
		}

	}

	protected function print_image( $member, $settings = [] ) {
	    $image_url = $this->get_thumbnail_url( $member );
		if ( ! $image_url ) return;
		 ?>
        <!-- IMAGE -->
        <div class="one-elements-element one-elements-image oee__dynamic-height oee__dynamic-height-ratio">

            <!-- Regular State Background -->
            <span <?php $this->print_render_attribute_string('one_image_regular_state'); ?>>
                <span class="one-elements-element__state-inner"></span>
            </span>

            <!-- Hover State Background -->
            <span <?php $this->print_render_attribute_string('one_image_hover_state'); ?>>
                <span class="one-elements-element__state-inner"></span>
            </span>
        	
            <!-- Content -->
            <div class="one-elements-element__content one-elements-element__content-back">
				<?php
				if ( $member instanceof \WP_Post) {
                    echo "<img src='".esc_attr(esc_url( $image_url ))."' alt='".$member->post_title."'/>";
				}else{
					echo $this->get_attachment_image_html( $member ); // escaped data returned
				}

				 ?>
            </div>
        	<?php
            // do not print social icons if it is keynote speakers
            if ('one-elements-team' === $this->get_name()) {
            ?>
            <!-- Content -->
            <div class="one-elements-element__content">
				<?php $this->print_social_icons( $member, $settings ); ?>
            </div>
                <?php } ?>
        </div>
        <?php
	}

	protected function print_name( $member, $settings = [] ) {
	    $name='';
        if ('dynamic' === $this->source){
            $name = $member->post_title;
        }elseif ( !Utils::is_empty( $member['name'] ) ){
            $name = $member['name'];
        }
		if (Utils::is_empty( $name) ) return;
	    ?>
            <!-- Name -->
            <div class="one-elements-element one-elements-heading team-member-name">
                <h3 class="one-elements-element__content"><?php echo esc_html( $name ); ?></h3>
            </div>
		<?php
	}

	protected function print_designation( $member, $settings = [] ) {
		$designation = null;
		if ($member instanceof \WP_Post){
			$designation = get_post_meta( $member->ID, '_designation', true);
		} elseif (!Utils::is_empty( $member['designation'] ) ){
			$designation = $member['designation'];
		}

		if (Utils::is_empty( $designation) ) return; ?>
            <!-- Designation -->
            <div class="one-elements-element one-elements-heading team-member-designation">
                <h3 class="one-elements-element__content"><?php echo esc_html( $designation ); ?></h3>
            </div>
		<?php
	}

	protected function is_social_enabled( $member, $settings = [] ) {
	    $show_icon = ( !empty($settings['show_team_icon']) && 'yes' == $settings['show_team_icon'] );
		if ( 'dynamic' === $this->source ) {
            return $show_icon;
		}
		return $show_icon && ( !empty( $member['enable_social']) && 'yes' == $member['enable_social'] );

	}

	protected function get_thumbnail_url( $member ) {
		if ( 'dynamic' === $this->source ) {
            $url = get_the_post_thumbnail_url($member->ID, 'post-thumbnail'); // @TODO; register proper custom image size for team later.
            if (!empty( $url )) return $url;
			return false;
		}

		if ( ! empty( $member['image']['url'] ) ) {
			return $member['image']['url'];
		}
		return false;
	}

	protected function print_social_icons( $member, $settings ) {
		if ( ! $this->is_social_enabled( $member, $settings ) ) return;
		$dynamic_si__markup = '';// dynamic social icons from the custom post
		if ( 'dynamic' === $this->source ) {
			$socials = ['facebook', 'twitter', 'linkedin', 'instagram', 'github'];
			$metas = get_post_meta( $member->ID);
			ob_start();
			foreach ( $socials as $social_icon_name ) {
				if (!empty( $metas[$social_icon_name][0])) {
					$link['url'] = $metas[$social_icon_name][0];
					$this->print_single_social($member, $link, $social_icon_name);
				}
			}
			$dynamic_si__markup = ob_get_clean();
			if (empty( $dynamic_si__markup)) return; // dont print wrapper div if social icons are not set.
		}

		 ?>
        <!-- Icon Content -->
        <div class="one-elements-social_icons">
			<?php
			if ( 'dynamic' === $this->source ) {
				echo $dynamic_si__markup;
			}else{
            // for static source, we need to check 5 icons, so lets runs a loop for 5 items
				for ($i=1; $i <=5; $i++){
					$key = "social_icon{$i}";
					$link_key = "social_link{$i}";
					// vail early if icon link is empty
					if (empty( $member[$link_key]) || empty( $member[$link_key]['url'])) continue;

					$link = $member[$link_key];
					// vail early if icon is empty
					if (empty( $member[$key]) || empty( $member[$key]['value']) ) continue;
					$social_icon_name = '';
					if ( 'svg' !== $member[$key]['library'] ) {
						$social_icon_name = explode( ' ', $member[$key]['value'], 2 );
						if ( empty( $social_icon_name[1] ) ) {
							$social_icon_name = '';
						} else {
							$social_icon_name = str_replace( 'fa-', '', $social_icon_name[1] );
						}
					}
					$this->print_single_social($member, $link, $social_icon_name, $key);
				}
			}

			 ?>
        </div> <!--end social icons wrapper-->
		<?php
	}

	protected function print_single_social($member, $link, $social_icon_name, $key='') {
        ?>
        <a class="one-elements-social_icons__single one-elements-social_icon-<?php echo esc_attr($social_icon_name); ?>" href="<?php echo esc_attr(esc_url( $link['url']));?>" target="<?php echo !empty( $link['is_external']) ? '_blank' : '_self'; ?>" >
            <span class="one-elements-element one-elements-icon one-elements__stop-parent-hover">

                <!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string('icon_regular_state'); ?>>
                    <span class="one-elements-element__state-inner">
                        <span class="one-elements-element__state-background"></span>
                    </span>
                </span>

                <!-- Hover State Background -->
                <span <?php $this->print_render_attribute_string('icon_hover_state'); ?>>
                    <span class="one-elements-element__state-inner">
                        <span class="one-elements-element__state-background"></span>
                    </span>
                </span>

                <!-- Content including Icon -->
                <span class="one-elements-element__content">
                    <span class="one-elements-icon__content_icon">
                        <?php
                        if ( 'dynamic' === $this->source ) {
                            echo "<i class='fab fa-{$social_icon_name}'></i>";
                        }else{
	                        Icons_Manager::render_icon( $member[$key], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </span>
            	</span>
            </span>
        </a>
        <?php
	}

	/**
	 * Render icon widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Team_Member() );