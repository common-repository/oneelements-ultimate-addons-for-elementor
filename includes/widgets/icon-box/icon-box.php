<?php
namespace OneElements\Includes\Widgets\IconBox;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Icon_Box;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Button_Trait;
use OneElements\Includes\Traits\One_Elements_Divider_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Elementor icon box widget.
 *
 * Elementor widget that displays an icon, a headline and a text.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Icon_Box extends Widget_Icon_Box {
	use One_Elements_Button_Trait;
	use One_Elements_Divider_Trait;

	// we do not need icon trait if we use the button trait, because button has the icon trait

	/**
	 * Prefix for trait's control.
	 *
	 * @since 1.0.0
	 *
	 * @return string Prefix for trait's control.
	 */
	protected $prefix = 'button_';

	/**
	 * Content Position
	 *
	 * @since 1.0.0
	 *
	 * @return array ordered content
	 */
    protected $content_position = array();
	/**
	 * @var bool
	 */
	protected $enable_floating_text;
	/**
	 * @var string
	 */
	protected $floating_text;

	/**
	 * Get widget name.
	 *
	 * Retrieve icon box widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-icon-box';
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon box widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'one-elements-widget-eicon eicon-icon-box';
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


	private static function get_order_numbers($limit = 4) {
		if ( !empty( $limit) && is_int( $limit) ) {
		    $a =[];
			for ($i=1; $i <= $limit; $i++){
			    $a[$i] = $i;
			}
			return $a;
	    }
		return [];


	}

	/**
     * We are using button from the traits. So, we need to provide some default to make the trait compatible with this widget
     * Get default args for button controls and rendering. We need this to override trait's default value.
	 * @param string $prefix we need to pass prefix to avoid conflict in control names. For example, button widget can be used twice to print secondary and primary button but their control id needs to be different.
	 *
	 * @return array
	 */
	private function get_button_control_default_args($prefix='button_' )
	{
		return [
	        'prefix' => $prefix,
	        'excludes' => ['button_align', 'button_css_id', 'icon_css_id', 'icon_align',],
	        'includes' => [],
	        'selectors'=>[
		        'icon_box_size' => [
			        '{{WRAPPER}} .one-elements-button .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
		        ],

		        'icon_size' => [
			        '{{WRAPPER}} .one-elements-button .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
			        '{{WRAPPER}} .one-elements-button .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
			        '{{WRAPPER}} .one-elements-button .one-elements-icon__svg .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};'
		        ],

		        'icon_color' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-icon__content_icon > *',

		        'hover_icon_color' => '{{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-icon__content_icon > *',

		        'icon_background' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',

		        'icon_background_hover' => '{{WRAPPER}} .one-elements-button .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',

		        'icon_border' => '{{WRAPPER}} .one-elements-icon .one-elements-element__regular-state',

		        'icon_border_hover' => '{{WRAPPER}} .one-elements-icon .one-elements-element__hover-state',

		        'icon_border_radius' => [
			        '{{WRAPPER}} .one-elements-icon, {{WRAPPER}} .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		        ],

		        'icon_border_radius_hover' =>  [
			        '{{WRAPPER}} .one-elements-button:hover .one-elements-icon, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .one-elements-button:hover .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
		        ],

            ],
	        'labels' => [],
	        'conditions' => [
		        'section_icon' => [ $prefix . 'enable_button_icon' => 'yes'],
		        'section_icon_style' => [
			        $prefix . 'enable_button_icon' => 'yes',
			        $prefix . 'icon[value]!' => '',
		        ],
		        'icon_background_section' => [
			        $prefix . 'enable_button_icon' => 'yes',
			        $prefix . 'icon[value]!' => '',
		        ],
		        'icon_border_section' => [
			        $prefix . 'enable_button_icon' => 'yes',
			        $prefix . 'icon[value]!' => '',
		        ],
		        'icon_align' => [ $prefix . 'button_type!' => ['circle']],
		        'button_link' => [ 'link_whole_box!' => 'yes'],
	        ],
        ];
    }

	/**
	 * We are using icon from the traits. So, we need to provide some default to make the trait compatible with this widget
	 * Get default args for icon controls and rendering. We need this to override trait's default value.
	 * @param string $prefix we need to pass prefix to avoid conflict in control names. Because the trait is used by different inner component like icon of this icon box, and button icon etc.
	 *
	 * @return array
	 */
	private function get_icon_control_default_args( $prefix='' )
	{
		return [
			'prefix' => $prefix,
			'excludes' => ['icon_css_id', 'icon_align'],
			'includes' => [],
			'selectors'=>[
				'icon_box_size' => [
					'{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			        '{{WRAPPER}} .one-elements-icon_box__layout_2' => 'padding-top: calc({{SIZE}}{{UNIT}}/2);',
			        '{{WRAPPER}} .one-elements-icon_box__layout_7' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
			        '{{WRAPPER}} .one-elements-icon_box__layout_8' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);'
				],

				'icon_size' => [
					'{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon__content_icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon__svg .one-elements-icon__content_icon svg' => 'width: {{SIZE}}{{UNIT}};'
				],

				'icon_color' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-icon__content_icon > *',

				'hover_icon_color' => '{{WRAPPER}} .icon_box_icon__wrapper:hover .one-elements-icon .one-elements-icon__content_icon > *',

				'icon_background' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__regular-state .one-elements-element__state-inner',

				'icon_background_hover' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__hover-state .one-elements-element__state-inner',

				'icon_border' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__regular-state',

				'icon_border_hover' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__hover-state',
				'icon_border_radius' => [
					'{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon, {{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'icon_border_radius_hover' =>  [
					'{{WRAPPER}} .icon_box_icon__wrapper:hover .one-elements-icon, {{WRAPPER}} .icon_box_icon__wrapper:hover .one-elements-icon .one-elements-element__regular-state, {{WRAPPER}} .icon_box_icon__wrapper:hover .one-elements-icon .one-elements-element__hover-state, {{WRAPPER}} .icon_box_icon__wrapper:hover .one-elements-icon .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'icon_shadow' => '{{WRAPPER}} .icon_box_icon__wrapper .one-elements-icon',


			],
			'labels' => [],
			'conditions' => [
				'section_icon' => [ $prefix . 'enable_icon' => 'yes' ],
				'section_icon_style' => [
					$prefix . 'enable_icon' => 'yes',
					$prefix . 'icon[value]!' => '',
				],
				'icon_background_section' => [
                    $prefix . 'enable_icon' => 'yes',
                    $prefix . 'icon[value]!' => '',
				 ],
				'icon_border_section' => [
                    $prefix . 'enable_icon' => 'yes',
                    $prefix . 'icon[value]!' => '',
				 ],
			],
		];
	}

	private function init_content_icon_box_settings() {

		$this->start_controls_section(
			'section_icon_box',
			[
				'label' => __( 'Icon Box', 'one-elements' ),
			]
		);

		$this->add_control(
			'enable_icon',
			[
				'label' => __( 'Enable Icon', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'return_value' => 'yes',
				'default' => 'yes'

			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Show Divider', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'one-elements' ),
				'label_off' => __( 'Hide', 'one-elements' ),
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'button_show_button',
			[
				'label' => __( 'Show Button', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'one-elements' ),
				'label_off' => __( 'Hide', 'one-elements' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'enable_floating_text',
			[
				'label' => __( 'Enable Floating Text', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'return_value' => 'yes',

			]
		);

		$this->add_control( 'link_whole_box', [
				'label' => __( 'Make Full Box Linkable', 'one-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'one-elements' ),
				'label_off' => __( 'No', 'one-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'box_link',
			[
				'label' => __( 'Link Box To', 'one-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'one-elements' ),
				'default' => [
					'url' => '',
				],
				'condition' =>  [
				    'link_whole_box' => 'yes'
                ],
			]
		);

		$this->end_controls_section();

	}

	private function init_content_content_controls() {

		//start Content
		$this->start_controls_section(
			'content_content_section',
			[
				'label' => __( 'Content', 'one-elements' ),
			]
		);
		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Title of the icon box', 'one-elements' ),
				'placeholder' => __( 'Title of the icon box', 'one-elements' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'header_tag',
			[
				'label' => __( 'Title HTML Tag', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p'
				],
				'default' => 'h3'
			]
		);
		$this->add_control(
			'description_text',
			[
				'label' => __( 'Content', 'one-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'default' => sprintf( "<p>%s</p>", __( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis', 'one-elements' )),
				'placeholder' => __( 'Enter your description', 'one-elements' ),
				'rows' => 10,
				'show_label' => false,
			]
		);

		$this->add_control(
			'floating_text',
			[
				'label' => __( 'Floating Text', 'one-elements' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'placeholder' => __( 'Text for Floating Content', 'one-elements' ),
				'condition' => [
				    'enable_floating_text' => 'yes',
                ]
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'one-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'one-elements' ),
				//'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	private function init_content_layout_settings() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'one-elements' ),
			]
		);
		$this->add_control( 'icon_box_layout', [
				'label' => __( 'Layout', 'one-elements' ),
				'type' => 'image_choose',
				'display_per_row' => 4,
				'options' => [
					'1' => [
						'title' => __( 'Layout 1', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_2.png',
					],
					'2' => [
						'title' => __( 'Layout 2', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_5.png',
					],
					'3' => [
						'title' => __( 'Layout 3', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_7.png',
					],
					'4' => [
						'title' => __( 'Layout 4', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_8.png',
					],
					'5' => [
						'title' => __( 'Layout 5', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_9.png',
					],
					'6' => [
						'title' => __( 'Layout 6', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_10.png',
					],
					'7' => [
						'title' => __( 'Layout 7', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_11.png',
					],
					'8' => [
						'title' => __( 'Layout 8', 'one-elements' ),
						'image' => ONE_ELEMENTS_ADMIN_ASSET_URL.'img/icon-box/layout_12.png',
					],
				],
				'default' => '1'
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
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'one-elements' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'one-elements' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
				    '{{WRAPPER}} .one-elements-element__content' => "text-align: {{VALUE}}"
                ],
                'condition' => [
                    'icon_box_layout' => ['1', '2']
                ]
			]
		);
		$this->add_control( 'content_order_heading',
			[
				'label' => __( 'Content Order', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->add_control( 'icon_order',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 1,
				'condition' => [
					'icon_box_layout' => ['1', '2'],
				],
			]
		);
		$this->add_control( 'title_order',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 2,
				'condition' => [
					'icon_box_layout' => ['1', '2'],
				],
			]
		);
		$this->add_control( 'description_order',
			[
				'label' => __( 'Description', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 3,
				'condition' => [
					'icon_box_layout' => ['1', '2'],
				],
			]
		);

		$this->add_control( 'button_order',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(),
				'default' => 4,
				'condition' => [
					'icon_box_layout' => ['1', '2'],
				],
			]
		);

		// for layout 3 and 4, description order will be 1/2, default 1.
		$this->add_control( 'description_order_l34',
			[
				'label' => __( 'Description', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(2),
				'default' => 1,
				'condition' => [
					'icon_box_layout' => ['3', '4'],
				],
			]
		);
		// for layout 3 and 4, button order will be 1/2, default 2.
		$this->add_control( 'button_order_l34',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(2),
				'default' => 2,
				'condition' => [
					'icon_box_layout' => ['3', '4'],
				],
			]
		);

		// for layout 5 to 8, description order will be 1/3, default 2.
		$this->add_control( 'title_order_l58',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 1,
				'condition' => [
					'icon_box_layout' => ['5', '6', '7', '8'],
				],
			]
		);
		$this->add_control( 'description_order_l58',
			[
				'label' => __( 'Description', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 2,
				'condition' => [
					'icon_box_layout' => ['5', '6', '7', '8'],
				],
			]
		);

		// for layout 5 to 8, button order will be 1/3, default 3.
		$this->add_control( 'button_order_l58',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_order_numbers(3),
				'default' => 3,
				'condition' => [
					'icon_box_layout' => ['5', '6', '7', '8'],
				],
			]
		);


		$this->add_control(
			'divider_position',
			[
				'label' => __( 'Divider Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
				'after_title' => __( 'After Title', 'one-elements' ),
				'before_title' => __( 'Before Title', 'one-elements' ),
                ],
				'default' => 'after_title',
				'separator' => 'before',
				'condition' => ['show_divider' => 'yes']
			]
		);
		$this->end_controls_section();
	}

	protected function init_style_floating_text() {

		$this->start_controls_section(
			'style_section_floating_text',
			[
				'label' => __( 'Floating Text', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_floating_text' => 'yes',
				],
			]
		);
		$this->add_control(
			'floating_text_position_x',
			[
				'label' => __( 'Horizontal Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				],
				'default' => 'right',
				'prefix_class' => ' oee_ft-position_x_',
			]
		);
		$this->add_control(
			'floating_text_position_y',
			[
				'label' => __( 'Vertical Position', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top' => 'Top',
					'center' => 'Center',
					'bottom' => 'Bottom',
				],
				'default' => 'center',
				'prefix_class' => ' oee_ft-position_y_',
			]
		);
		$this->start_controls_tabs( 'icon_box_ft_tabs' );
		// normal tab
		$this->start_controls_tab(
			'icon_box_ft_tab_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);

		$this->add_responsive_control(
			'floating_text_rotate',
			[
				'label' => __( 'Rotate', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .oee__floating-text span' => 'transform: rotate({{SIZE}}deg);',// rotation does not work if the element is not a block level element @TODO; move display inline block code to css file
				],
			]
		);

		$this->add_responsive_control(
			'floating_text_offset_x',
			[
				'label' => __( 'Offset X', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.oee_ft-position_x_left .oee__floating-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_ft-position_x_right .oee__floating-text' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'floating_text_offset_y',
			[
				'label' => __( 'Offset Y', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .oee__floating-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab(); // end normal tab

		// hover tab
		$this->start_controls_tab(
			'icon_box_ft_tab_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);
		$this->add_responsive_control(
			'floating_text_rotate_hover',
			[
				'label' => __( 'Rotate', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box:hover .oee__floating-text span' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);

		$this->add_responsive_control(
			'floating_text_offset_x_hover',
			[
				'label' => __( 'Offset X', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.oee_ft-position_x_left .one-elements-icon_box:hover .oee__floating-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.oee_ft-position_x_right .one-elements-icon_box:hover .oee__floating-text' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'floating_text_offset_y_hover',
			[
				'label' => __( 'Offset Y', 'one-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box:hover .oee__floating-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs(); // end tab container
		$this->end_controls_section();

	}

	private function init_style_icon_box_controls() {

		$this->start_controls_section(
			'style_icon_box_section',
			[
				'label' => __( 'Icon Box', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		//COLORS
		$this->add_control(
			'style_icon_box_colors_heading',
			[
				'label' => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		$this->start_controls_tabs( 'icon_box_colors' );
        // normal tab
		$this->start_controls_tab(
			'icon_box_colors_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_color',
				'label' => __( 'Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-icon_box__content_icon .one-elements-icon__content_icon > *'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_box_title_color',
				'label' => __( 'Title', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-heading .one-elements-element__content',
				'condition' => [
					'title!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_box_content_color',
				'label' => __( 'Content', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-text-editor',
				'condition' => [
					'description_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'btn_text_color',
				'label' => __( 'Button Text', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-button .one-elements-button__content_text',
				'condition' => [
					$this->prefix.'button_type!' => ['circle']
				]
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'button_icon_color',
				'label' => __( 'Button Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					$this->prefix.'enable_button_icon' => 'yes',
					'button_icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-button .one-elements-icon__content_icon > *'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'floating_text_color',
				'label' => __( 'Floating Text', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .oee__floating-text span',
				'condition' => [
					'enable_floating_text' => 'yes',
					'floating_text!' => '',
				],
			]
		);

		$this->end_controls_tab();
        // hover tab
		$this->start_controls_tab(
			'icon_box_colors_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'hover_icon_color',
				'label' => __( 'Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .one-elements-icon_box__content_icon .one-elements-icon__content_icon > *',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_box_title_color_hover',
				'label' => __( 'Title', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .one-elements-heading .one-elements-element__content',
				'condition' => [
					'title!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'icon_box_content_color_hover',
				'label' => __( 'Content', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .elementor-text-editor',
				'condition' => [
					'description_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'hover_btn_text_color',
				'label' => __( 'Button Text', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					$this->prefix.'button_type!' => ['circle']
				],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .one-elements-button__content_text',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'hover_button_icon_color',
				'label' => __( 'Button Icon', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'condition' => [
					$this->prefix.'enable_button_icon' => 'yes',
					'button_icon[value]!' => '',
				],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .one-elements-button .one-elements-icon__content_icon > *',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'hover_floating_text_color',
				'label' => __( 'Floating Text', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover .oee__floating-text span',
				'condition' => [
					'enable_floating_text' => 'yes',
					'floating_text!' => '',
				],
			]
		);

		$this->end_controls_tab(); // end hover tab
		$this->end_controls_tabs();  // end all tabs

        // TYPOGRAPHY
		$this->add_control(
			'style_icon_box_typography_heading',
			[
				'label' => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading .one-elements-element__content',
				'condition' => [
					'title!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Content', 'one-elements' ),
				'selector' => '{{WRAPPER}} .elementor-text-editor',
				'condition' => [
					'description_text!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'floating_text_typography',
				'label' => __( 'Floating Text', 'one-elements' ),
				'selector' => '{{WRAPPER}} .oee__floating-text span',
				'condition' => [
					'enable_floating_text' => 'yes',
					'floating_text!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Button Text', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-button .one-elements-button__content_text',
				'condition' => [
					$this->prefix.'button_type!' => ['circle']
				]
			]
		);

		// SPACING
		$this->add_control(
			'style_icon_box_spacing_heading',
			[
				'label' => __( 'Spacing', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'icon_box_icon_margin',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box .one-elements-icon_box__content_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'enable_icon' => 'yes',
					'icon[value]!' => '',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Title', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-heading .one-elements-element__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'title!' => '',
				],
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
				'condition' => [
					'description_text!' => '',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_button_margin',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		// PADDING
		$this->add_control(
			'style_icon_box_padding_heading',
			[
				'label' => __( 'Padding', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'tabs_iconbox_padding' );
		// normal padding
		$this->start_controls_tab(
			'tab_iconbox_padding_normal',
			[
				'label' => __( 'Normal', 'one-elements' ),
			]
		);
		$this->add_responsive_control(
			'icon_box_padding',
			[
				'label' => __( 'Icon Box', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box > .one-elements-element__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'btn_text_padding',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->prefix.'button_type!' => ['flat', 'circle']
				]
			]
		);
		$this->end_controls_tab();
        // hover : padding
		$this->start_controls_tab(
			'tab_iconbox_padding_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);
		$this->add_responsive_control(
			'hover_icon_box_padding',
			[
				'label' => __( 'Icon Box', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box:hover > .one-elements-element__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'hover_btn_text_padding',
			[
				'label' => __( 'Button', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box:hover .one-elements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->prefix.'button_type!' => ['flat', 'circle']
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		//Transition
		$this->add_control(
			'icon_box_transition',
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
					'{{WRAPPER}} .one-elements-icon_box' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);
		$this->end_controls_section();

	}

	private function init_style_icon_box_background_settings() {

		$this->start_controls_section(
			'icon_box_background_section',
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
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__regular-state .one-elements-element__state-inner',
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
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__hover-state .one-elements-element__state-inner',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_icon_box_overlay_settings() {

		$this->start_controls_section(
			'icon_box_background_overlay',
			[
				'label' => __( 'Background Overlay', 'one-elements' ),
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
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__regular-state .one-elements-element__state-inner:after',
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
					'{{WRAPPER}} .one-elements-icon_box > .one-elements-element__regular-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
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
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__hover-state .one-elements-element__state-inner:after',
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
					'{{WRAPPER}} .one-elements-icon_box:hover > .one-elements-element__hover-state .one-elements-element__state-inner:after' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function init_style_icon_box_border_settings() {

		$this->start_controls_section(
			'icon_box_border_section',
			[
				'label' => __( 'Border & Shadow', 'one-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_icon_box_border' );

		$this->start_controls_tab(
			'tab_icon_box_border_normal',
			[
				'label' => __( 'Normal', 'one-elements' )
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'icon_box_border',
				'label' => __( 'Icon Box Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__regular-state',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box, {{WRAPPER}} .one-elements-icon_box > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon_box > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon_box > .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_box_shadow',
				'label' => __( 'Icon Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_box_border_hover',
			[
				'label' => __( 'Hover', 'one-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Border_Gradient::get_type(),
			[
				'name' => 'icon_box_border_hover',
				'label' => __( 'Icon Box Border', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_box > .one-elements-element__hover-state',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-icon_box:hover, {{WRAPPER}} .one-elements-icon_box:hover > .one-elements-element__regular-state, {{WRAPPER}} .one-elements-icon_box:hover > .one-elements-element__hover-state, {{WRAPPER}} .one-elements-icon_box:hover >  .one-elements-element__border-gradient .one-elements-element__state-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'hover_icon_box_box_shadow',
				'label' => __( 'Icon Box Shadow', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-icon_box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}


	/**
	 * Register icon box widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$divider_options = [
			'conditions' => [
				'one_divider' => ['show_divider' => 'yes'],
			]
		];
        // Content -> Widget
	    $this->init_content_icon_box_settings();
	    $this->init_content_content_controls();

		// Content -> Divider
		$this->init_content_divider_controls($divider_options);
		$this->init_content_secondary_divider_controls($divider_options);

		// Content -> Icon
	    $this->init_content_icon_settings($this->get_icon_control_default_args());

		// Content -> Button
		$this->init_content_button_settings($this->get_button_control_default_args($this->prefix));

		// Content -> Button Icon
		$this->init_content_button_icon_settings($this->get_button_control_default_args($this->prefix));

		// Content -> Widget Layouts
	    $this->init_content_layout_settings();

		// Style -> Widget
		$this->init_style_icon_box_controls();
		$this->init_style_floating_text();
		$this->init_style_icon_box_background_settings();
		$this->init_style_icon_box_overlay_settings();
		$this->init_style_icon_box_border_settings();

		// Style > Icon
		$this->init_style_icon_background_settings($this->get_icon_control_default_args());
		$this->init_style_icon_border_settings($this->get_icon_control_default_args());

		// Style > Button
		$this->init_style_button_background_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_border_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_underline_settings($this->get_button_control_default_args($this->prefix));

		// Style > Button Icon
		$this->init_style_button_icon_background_settings($this->get_button_control_default_args($this->prefix));
		$this->init_style_button_icon_border_settings($this->get_button_control_default_args($this->prefix));

	}

	public function get_item_by_position( $pos ) {
		$pos = (int) $pos;
		--$pos;

		if ( $pos < 0 ) {
			$pos = 0;
		}

		if ( $pos > 3 ) {
			$pos = 3;
		}

		$settings = $this->get_settings_for_display();
        $layout = $settings['icon_box_layout'];
		if ( empty($this->content_position) ) {

			$content_position = array(
				array(
					'content_name' => 'icon',
					'position' => $settings['icon_order']
				),
				array(
					'content_name' => 'title',
					'position' => $settings['title_order']
				),
				array(
					'content_name' => 'description',
					'position' => $settings['description_order']
				),
				array(
					'content_name' => 'button',
					'position' => $settings['button_order']
				)
			);


			if ( in_array( $layout, [3, 4 ]) ) {
				$content_position = [
					[
						'content_name' => 'description',
						'position' => $settings['description_order_l34']
					],
					[
						'content_name' => 'button',
						'position' => $settings['button_order_l34']
					]
                ];

			} elseif ( in_array( $layout, [5,6,7,8 ]) ) {
				$content_position = [
					[
						'content_name' => 'title',
						'position' => $settings['title_order_l58']
					],
					[
						'content_name' => 'description',
						'position' => $settings['description_order_l58']
					],
					[
						'content_name' => 'button',
						'position' => $settings['button_order_l58']
					]
				];

			}


			
			usort($content_position, function($a, $b) {
				return $a['position'] - $b['position'];
			});

			$this->content_position = $content_position;

		}

		$content_fun = 'render_icon_box_content_' . $this->content_position[ $pos ]['content_name'];

		call_user_func( array( $this, $content_fun ), $settings );

	}

	public function get_content_by_layout( $layout ) {
		$layout = (int) $layout;
		if ( $layout < 1 ) {
			$layout = 1;
		}

		if ( $layout > 8 ) {
			$layout = 8;
		}

		$layout_fun = 'get_content_by_layout_' . $layout;

		call_user_func( array( $this, $layout_fun ) );

	}

	public function get_content_by_layout_1() {
		?>
		<div class="one-elements-element__content">
			<?php $this->render_icon_box_content_floating_text();
                 $this->get_item_by_position(1);
			     $this->get_item_by_position(2);
			     $this->get_item_by_position(3);
			     $this->get_item_by_position(4);
             ?>
		</div>
		<?php
	}


	public function get_content_by_layout_2() {
		$this->get_content_by_layout_1();
	}

	public function get_content_by_layout_3() {
	    $settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row oee__align-middle">
				<div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php $this->render_icon_box_content_title($settings); ?>
				</div>
			</div>
			<?php
            // @TODO; update position number to 1, 2 and add layout 3 check inside the get_item_by_position() method.
			$this->get_item_by_position(1); $this->get_item_by_position(2); ?>
		</div>
		<?php
	}

	public function get_content_by_layout_4() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content elementor-align-right">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row oee__align-middle oee__content-right oee__row-reverse">

                <div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
                </div>
                <div class="oee__column">
					<?php $this->render_icon_box_content_title($settings); ?>
                </div>

			</div>
			<?php $this->get_item_by_position(1); $this->get_item_by_position(2); ?>
		</div>
		<?php
	}

	public function get_content_by_layout_5() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row">
				<div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_content_by_layout_6() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content elementor-align-right">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row oee__content-right oee__row-reverse">
				<div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
                </div>
				<div class="oee__column">
					<?php

					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);

					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_content_by_layout_7() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row oee__align-middle">
				<div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_content_by_layout_8() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="one-elements-element__content elementor-align-right">
			<?php $this->render_icon_box_content_floating_text(); ?>
			<div class="oee__row oee__align-middle oee__content-right oee__row-reverse">
				<div class="oee__column">
					<?php $this->render_icon_box_content_icon($settings); ?>
				</div>
				<div class="oee__column">
					<?php
					$this->get_item_by_position(1);
					$this->get_item_by_position(2);
					$this->get_item_by_position(3);
					?>
				</div>
			</div>
		</div>
		<?php
	}

	private function render_icon_box_content( $settings ) {
		$layout = $settings['icon_box_layout'];
		$this->add_render_attribute( 'description_text', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ] );
		$this->add_inline_editing_attributes( 'description_text', 'advanced' );
        // Icon Box Content
		$this->get_content_by_layout( $layout );
	}

	private function render_icon_box_content_icon( $settings ) {

		if ( $settings['enable_icon'] !== 'yes' ||  empty($settings['icon']['value']) ) return;

		$this->add_render_attribute( 'icon_box_icon', 'class', 'one-elements-element one-elements-icon one-elements-icon_box__content_icon' );
		$this->add_render_attribute( 'icon_box_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_gradient_type'] ) {
			$this->add_render_attribute( 'icon_box_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'icon_box_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'icon_box_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( $settings['icon']['library'] == 'svg' ) {
			$this->add_render_attribute( 'icon_box_icon', 'class', 'one-elements-icon__svg' );
		}
		?>
		<div class="icon_box_icon__wrapper">
	        <span <?php $this->print_render_attribute_string( 'icon_box_icon' ); ?>>
				<!-- Regular State Background -->
				<span <?php $this->print_render_attribute_string( 'icon_box_icon_regular_state' ); ?>>
					<span class="one-elements-element__state-inner"></span>
				</span>

				<?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
	                <!-- Hover State Background -->
	                <span <?php $this->print_render_attribute_string( 'icon_box_icon_hover_state' ); ?>>
						<span class="one-elements-element__state-inner"></span>
					</span>
				<?php endif; ?>

				<!-- Icon Content -->
				<span class="one-elements-element__content">

		            <span class="one-elements-icon__content_icon">
						<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
				</span>
			</span>
		</div>
		<?php
	}

	private function render_icon_box_content_title( $settings ) {

		if ( !isset($settings['title']) || '' === $settings['title']) return;

		if ( $settings['divider_position'] == 'before_title' ) {
			$this->render_icon_box_content_divider( $settings );
		}

		$this->add_inline_editing_attributes( 'title' );

		$this->add_render_attribute( 'title', 'class', 'one-elements-element__content' );
		?>
		<div class="one-elements-element one-elements-heading">
			<?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_tag'], $this->get_render_attribute_string( 'title' ), $settings['title'] ); ?>
		</div>
		<?php
		if ( $settings['divider_position'] == 'after_title' ) {
			$this->render_icon_box_content_divider( $settings );
		}
	}

	private function render_icon_box_content_divider( $settings ) {
		if (isset( $settings['show_divider']) && $settings['show_divider'] !== 'yes' ) return;
		$this->render_divider($settings);

	}

	private function render_icon_box_content_description( $settings ) {

		if ( !isset($settings['description_text']) || '' === $settings['description_text']) return;
		?>
		<div <?php $this->print_render_attribute_string( 'description_text' ); ?>>
			<?php echo $this->parse_text_editor( $settings['description_text'] ); ?>
		</div>
       <?php
	}

	private function render_icon_box_content_button($settings) {
	    $this->render_button( compact('settings'));
	}
	private function render_icon_box_content_floating_text() {
		if ( 'yes' === $this->enable_floating_text && isset( $this->floating_text) && '' !== $this->floating_text ) { ?>
            <span class="oee__floating-text"><span><?php echo esc_html($this->floating_text); ?></span></span>
		<?php }
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access private
	 *
	 */
	private function render_button_text() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'button_icon', 'class', 'one-elements-element one-elements-icon' );
		$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings[$this->prefix.'icon_border_gradient_type'] ) {
			$this->add_render_attribute( 'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings[$this->prefix.'icon_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}
		?>
        <!-- Button Content -->
        <span class="one-elements-element__content">
            <?php if ( ( $settings[$this->prefix.'button_type'] == 'circle' || $settings[$this->prefix.'enable_button_icon'] == 'yes' )
            && ( !empty($settings[$this->prefix.'icon']) || !empty($settings[$this->prefix.'button_icon_svg']) ) ) { ?>
                <span <?php $this->print_render_attribute_string( 'button_icon' ); ?>>
					<?php if ( $settings[$this->prefix.'button_type'] !== 'circle' ) { ?>

                        <!-- Regular State Background -->
                        <span <?php $this->print_render_attribute_string( 'button_icon_regular_state' ); ?>>
							<span class="one-elements-element__state-inner"></span>
						</span>

						<?php if ( $settings[$this->prefix.'icon_background_hover_background'] || $settings[$this->prefix.'icon_border_hover_background'] ) { ?>
                            <!-- Hover State Background -->
                            <span <?php $this->print_render_attribute_string( 'button_icon_hover_state' ); ?>>
								<span class="one-elements-element__state-inner"></span>
							</span>
						<?php } ?>

					<?php } ?>
					<!-- Content including Button Icon -->
					<span class="one-elements-element__content">

					    <span class="one-elements-icon__content_icon">
						    <?php Icons_Manager::render_icon( $settings[$this->prefix.'icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</span>

					</span>
				</span>
            <?php } ?>
			<?php if ( $settings[$this->prefix.'button_type'] !== 'circle' ) : ?>
                <span class="one-elements-button__content_text">
					<?php if (isset( $settings[$this->prefix.'button_text']) && '' !== $settings[$this->prefix.'button_text'] ) { echo esc_html( $settings[$this->prefix.'button_text']); } ?>
					<?php if ( $settings[$this->prefix.'button_type'] == 'flat' ) : ?>
                        <span class="one-elements-button__underline"></span>
					<?php endif; ?>
				</span>
			<?php endif; ?>
        </span>
		<?php
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$this->enable_floating_text = isset( $settings['enable_floating_text']) ? $settings['enable_floating_text'] : false; // yes or false
		$this->floating_text = isset( $settings['floating_text']) ? $settings['floating_text'] : ''; // yes or false
		$this->add_render_attribute( 'wrapper', [
				'class' => [
					'one-elements-icon_box__wrapper',
					'one-elements-icon_box__layout_' . $settings['icon_box_layout'],
					$settings['_css_classes']
				]
			]
		);

		$this->add_render_attribute( 'icon_box', [
				'class' => [
					'one-elements-element one-elements-icon_box',
				]
			]
		);

		$this->add_render_attribute( 'icon_box_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_box_border_gradient_type'] ) {
			$this->add_render_attribute( 'icon_box_regular_state', 'class', 'one-elements-element__border-gradient' );
		}

		$this->add_render_attribute( 'icon_box_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_box_border_hover_gradient_type'] ) {
			$this->add_render_attribute( 'icon_box_hover_state', 'class', 'one-elements-element__border-gradient' );
		}

		if ( ! empty( $settings['icon_box_css_id'] ) ) {
			$this->add_render_attribute( 'icon_box', 'id', $settings['icon_box_css_id'] );
		}
		//@todo; ask if we need to hove animation as it is missing in control but used below?
		if ( !empty( $settings['hover_animation']) ) {
			$this->add_render_attribute( 'icon_box', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		// box linking
		$box_link = !empty( $settings['box_link']['url']) ? $settings['box_link'] : false;
		if ( $box_link && 'yes' === $settings['link_whole_box']) {

			$this->add_render_attribute( 'box_link', [
				'href' => $box_link['url'],
				'class' => 'one-elements-element__link'
			]);

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'box_link', [
					'class' => 'elementor-clickable',
				]);
			}

			if ( ! empty( $box_link['is_external'] ) ) {
				$this->add_render_attribute( 'box_link', 'target', '_blank' );
			}

			if ( ! empty( $box_link['nofollow'] ) ) {
				$this->add_render_attribute( 'box_link', 'rel', 'nofollow' );
			}

		}

		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

            <div <?php $this->print_render_attribute_string( 'icon_box' ); ?>>

                <!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string( 'icon_box_regular_state' ); ?>>
                	<span class="one-elements-element__state-inner"></span>
                </span>

				<?php if ( $settings['background_hover_background'] || $settings['icon_box_border_hover_background'] ) : ?>
                    <!-- Hover State Background -->
                    <span <?php $this->print_render_attribute_string( 'icon_box_hover_state' ); ?>>
	                	<span class="one-elements-element__state-inner"></span>
	                </span>
				<?php endif; ?>

                <!-- Content -->
				<?php $this->render_icon_box_content( $settings ); ?>

	            <?php if ( $box_link ) : ?>
                    <a <?php $this->print_render_attribute_string( 'box_link' ); ?>></a>
	            <?php endif; ?>
            </div>

        </div>
		<?php
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
	?>
    <#
        var IBHandlers={};
        let ib_prefix = 'button_'; // define prefix for button related stuff

        //Defining all attributes
        view.addRenderAttribute( 'wrapper', 'class', [
            'one-elements-icon_box__wrapper',
            'one-elements-icon_box__layout_' + settings['icon_box_layout'],
            settings['_css_classes']
        ] );

        view.addRenderAttribute( 'icon_box', 'class', 'one-elements-element one-elements-icon_box' );
        view.addRenderAttribute( 'icon_box_regular_state', 'class', 'one-elements-element__regular-state' );
        if ( settings['icon_box_border_gradient_type'] ) {
            view.addRenderAttribute( 'icon_box_regular_state', 'class', 'one-elements-element__border-gradient' );
        }
        view.addRenderAttribute( 'icon_box_hover_state', 'class', 'one-elements-element__hover-state' );

        if ( settings['icon_box_border_hover_gradient_type'] ) {
            view.addRenderAttribute( 'icon_box_hover_state', 'class', 'one-elements-element__border-gradient' );
        }

        if ( settings['icon_box_css_id'] ) {
            view.addRenderAttribute( 'icon_box', 'id', settings['icon_box_css_id'] );
        }
        //@todo; ask if we need to hove animation as it is missing in control but used in render()?
        if ( settings['hover_animation'] ) {
            view.addRenderAttribute( 'icon_box', 'class', 'elementor-animation-' + settings['hover_animation'] );
        }



        // define render related functions
        // add all dynamic layout functions in to IBHandlers for cleaner dynamic call without messing with global object

        //---- DISPLAY FLOATING TEXT-------------
        IBHandlers.render_icon_box_content_floating_text = function () {
            if ( settings.enable_floating_text && 'yes' === settings.enable_floating_text && settings.floating_text ){
            #>
                <span class="oee__floating-text"><span>{{{settings.floating_text}}}</span></span>
            <#
            }
        };


        //---- DISPLAY ICON-------------
        IBHandlers.render_icon_box_content_icon = function () {

            if ( 'yes' !== settings['enable_icon']  ||  !settings['icon']['value'] ) return;
        	let iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
            view.addRenderAttribute( 'icon_box_icon', 'class', 'one-elements-element one-elements-icon one-elements-icon_box__content_icon' );

            view.addRenderAttribute( 'icon_box_icon_regular_state', 'class', 'one-elements-element__regular-state' );

            if ( settings['icon_border_gradient_type'] ) {
                view.addRenderAttribute( 'icon_box_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
            }

            view.addRenderAttribute( 'icon_box_icon_hover_state', 'class', 'one-elements-element__hover-state' );

            if ( settings['icon_border_hover_gradient_type'] ) {
            	view.addRenderAttribute( 'icon_box_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
            }

            if ( settings['icon'] && 'svg' === settings['icon']['library'] ) {
	            view.addRenderAttribute( 'icon_box_icon', 'class', 'one-elements-icon__svg' );
            }
            
            view.addRenderAttribute( 'icon_box_fa_icon', 'class', settings['icon'] );

            #>
            <div class="icon_box_icon__wrapper">
                <span {{{ view.getRenderAttributeString( 'icon_box_icon' ) }}}>

                    <!-- Regular State Background -->
                    <span {{{ view.getRenderAttributeString( 'icon_box_icon_regular_state' ) }}}>
                        <span class="one-elements-element__state-inner"></span>
                    </span>
                    <!-- Hover State Background -->
                    <span {{{ view.getRenderAttributeString('icon_box_icon_hover_state') }}}>
                        <span class="one-elements-element__state-inner"></span>
                    </span>

                    <!-- Icon Content -->
                    <span class="one-elements-element__content">
                        <span class="one-elements-icon__content_icon">
                            {{{iconHTML.value}}}
                        </span>
                    </span>
                </span>
            </div>
            <#
        };


        //---- DISPLAY DIVIDER-------------
        IBHandlers.render_icon_box_content_divider = function () {
        if ( 'yes' !== settings['show_divider'] ) return;
        #>
		<?php $this->_content_template_divider(); ?>
        <#
        }



        //---- DISPLAY TITLE-------------
        IBHandlers.render_icon_box_content_title = function () {
            if ( !settings['title'] ) return;

            if ( 'before_title' === settings['divider_position'] ) {
                IBHandlers.render_icon_box_content_divider();
            }

            view.addInlineEditingAttributes( 'title' );

            view.addRenderAttribute( 'title', 'class', 'one-elements-element__content' );
            #>
            <div class="one-elements-element one-elements-heading">
                <{{{settings['header_tag']}}} {{{ view.getRenderAttributeString( 'title' ) }}}>
                    {{{settings['title']}}}
                </{{{settings['header_tag']}}}>
            </div>
            <#
            if ( 'after_title' === settings['divider_position'] ) {
                IBHandlers.render_icon_box_content_divider();
            }
        };



        //---- DISPLAY DESCRIPTION-------------
        IBHandlers.render_icon_box_content_description = function () {
        if ( !settings['description_text'] ) return;
            #>
            <div {{{ view.getRenderAttributeString( 'description_text' ) }}}>
                {{{settings['description_text']}}}
            </div>
            <#
        };


        //---- DISPLAY BUTTON-------------
        IBHandlers.render_icon_box_content_button = function () {
            if ( settings[ib_prefix+'show_button'] !== 'yes' ) return;
        let BtnIconHTML = elementor.helpers.renderIcon( view, settings[ib_prefix+ 'icon'], { 'aria-hidden': true }, 'i' , 'object' );
            let html_tag = 'button';

            view.addRenderAttribute( 'button','class', [
                    'one-elements-element one-elements-button',
                    'one-elements-button__type-' + settings[ib_prefix+'button_type'],
                    'one-elements-button__icon-' + settings[ib_prefix+'icon_position']
                ]
            );

            view.addRenderAttribute( 'button_regular_state', 'class', 'one-elements-element__regular-state' );

            if ( settings[ib_prefix+'button_border_gradient_type'] ) {
                view.addRenderAttribute( 'button_regular_state', 'class', 'one-elements-element__border-gradient' );
            }

            view.addRenderAttribute( 'button_hover_state', 'class', 'one-elements-element__hover-state' );

            if ( settings[ib_prefix+'button_border_hover_gradient_type'] ) {
                view.addRenderAttribute( 'button_hover_state', 'class', 'one-elements-element__border-gradient' );
            }

            if ( settings[ib_prefix+'button_link'] && settings[ib_prefix+'button_link']['url'] ) {
                html_tag = 'a';

                view.addRenderAttribute( 'button', 'href', settings[ib_prefix+'button_link']['url'] );
                view.addRenderAttribute( 'button', 'class', 'one-elements-button__link' );

                if ( settings[ib_prefix+'button_link']['is_external'] ) {
                    view.addRenderAttribute( 'button', 'target', '_blank' );
                }

                if ( settings[ib_prefix+'button_link']['nofollow'] ) {
                    view.addRenderAttribute( 'button', 'rel', 'nofollow' );
                }
            }


            if ( settings[ib_prefix+'button_size'] ) {
                view.addRenderAttribute( 'button', 'class', 'one-elements-button__size-' + settings[ib_prefix+'button_size'] );
            }

            if ( settings[ib_prefix+'button_size_tablet'] ) {
                view.addRenderAttribute( 'button', 'class', 'one-elements-button__tablet-size-' + settings[ib_prefix+'button_size_tablet'] );
            }

            if ( settings[ib_prefix+'button_size_mobile'] ) {
                view.addRenderAttribute( 'button', 'class', 'one-elements-button__mobile-size-' + settings[ib_prefix+'button_size_mobile'] );
            }

            if ( settings[ib_prefix+'hover_animation'] ) {
                view.addRenderAttribute( 'button', 'class', 'elementor-animation-' + settings[ib_prefix+'hover_animation'] );
            }

            if ( settings[ib_prefix+'icon'] && 'svg' === settings[ib_prefix+'icon']['library'] ) {
                view.addRenderAttribute( 'button_icon', 'class', 'one-elements-icon__svg' );
            }

            view.addInlineEditingAttributes( 'button_text', 'none' );
            // btn text n icon output related
            view.addRenderAttribute(  'button_icon', 'class', 'one-elements-element one-elements-icon' );
            view.addRenderAttribute(  'button_icon_regular_state', 'class', 'one-elements-element__regular-state' );

            if ( settings[ib_prefix+'icon_border_gradient_type'] ) {
                view.addRenderAttribute(  'button_icon_regular_state', 'class', 'one-elements-element__border-gradient' );
            }

            view.addRenderAttribute(  'button_icon_hover_state', 'class', 'one-elements-element__hover-state' );

            if ( settings[ib_prefix+'icon_border_hover_gradient_type'] ) {
                view.addRenderAttribute(  'button_icon_hover_state', 'class', 'one-elements-element__border-gradient' );
            }
            #>
            <{{{html_tag}}} {{{ view.getRenderAttributeString( 'button' ) }}}>

            <!-- Regular State Background -->
                <span {{{ view.getRenderAttributeString( 'button_regular_state' ) }}}>
                    <span class="one-elements-element__state-inner"></span>
                </span>

                <!-- Hover State Background -->
                <span {{{ view.getRenderAttributeString( 'button_hover_state' ) }}}>
                    <span class="one-elements-element__state-inner"></span>
                </span>

            <!-- Content including Button Icon -->
            <!-- Button Content -->
            <span class="one-elements-element__content">
                    <# if (
                    ( settings[ib_prefix+'button_type'] === 'circle' || settings[ib_prefix+'enable_button_icon'] === 'yes' )
                    &&
                    ( settings[ib_prefix+'icon'] || settings[ib_prefix+'icon_svg'] )
                    ) { #>

                <span {{{ view.getRenderAttributeString( 'button_icon' ) }}}>
                    <# if ( settings[ib_prefix+'button_type'] !== 'circle' ) { #>
                        <!-- Regular State Background -->
                        <span {{{ view.getRenderAttributeString( 'button_icon_regular_state' ) }}}>
                            <span class="one-elements-element__state-inner"></span>
                        </span>
                        <!-- Hover State Background -->
                        <span {{{ view.getRenderAttributeString( 'button_icon_hover_state' ) }}}>
                            <span class="one-elements-element__state-inner"></span>
                        </span>
                    <# } #>

                <!-- Content including Button Icon -->
                    <span class="one-elements-element__content">
                        <span class="one-elements-icon__content_icon">
                            {{{BtnIconHTML.value}}}
                        </span>
                    </span>
                </span>
            <# } #>

                <# if ( settings[ib_prefix+'button_type'] !== 'circle' ) { #>
                    <span class="one-elements-button__content_text">
                        {{{settings[ib_prefix+'button_text']}}}
                        <# if ( settings[ib_prefix+'button_type'] == 'flat' ) { #>
                            <span class="one-elements-button__underline"></span>
                        <# } #>
                    </span>
                <# } #>
            </span>

            </{{{html_tag}}}>
            <#
        };



        function get_item_by_position(pos) {
            pos = parseInt(pos, 10);
            --pos;
            if ( pos < 0 ) {pos = 0;}
            if ( pos > 3 ) {pos = 3;}

            let layout = parseInt(settings.icon_box_layout, 10);
            if(!IBHandlers.content_position) {
                let content_position = [
                    { content_name: 'icon', position: settings['icon_order'] },
                    { content_name: 'title', position: settings['title_order'] },
                    { content_name: 'description', position: settings['description_order'] },
                    { content_name: 'button', position: settings['button_order'] }
                ];

                if( [3,4].includes(layout) ) {
                    content_position = [
                        { content_name: 'description', position: settings['description_order_l34'] },
                        { content_name: 'button', position: settings['button_order_l34'] }
                    ];
                } else if ([5,6,7,8].includes(layout)) {
                    content_position = [
                        { content_name: 'title', position: settings['title_order_l58'] },
                        { content_name: 'description', position: settings['description_order_l58'] },
                        { content_name: 'button', position: settings['button_order_l58'] }
                    ];
                }

                content_position.sort((a, b) => (a.position > b.position) ? 1 : -1);
                IBHandlers.content_position = content_position;
            }

            const content_fun = 'render_icon_box_content_' + IBHandlers.content_position[ pos ]['content_name'];

            if( typeof IBHandlers[content_fun] === 'function'){
                IBHandlers[content_fun](); // call content related function dynamically
            }
        }

        IBHandlers.get_content_by_layout_1 = function (){
            #>
            <div class="one-elements-element__content">
            	<#
            	IBHandlers.render_icon_box_content_floating_text();
            	get_item_by_position(1);
            	get_item_by_position(2);
            	get_item_by_position(3);
            	get_item_by_position(4);
            	#>
            </div>
            <#
        };

        IBHandlers.get_content_by_layout_2 = function (){
            IBHandlers.get_content_by_layout_1();
        };

        IBHandlers.get_content_by_layout_3 = function (){
            #>
            <div class="one-elements-element__content">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row oee__align-middle">

                    <div class="oee__column">
                        <#  IBHandlers.render_icon_box_content_icon(); #>
                    </div>

                    <div class="oee__column">
                        <# IBHandlers.render_icon_box_content_title(); #>
                    </div>

                </div>
                <# get_item_by_position(1); #>
                <# get_item_by_position(2); #>
            </div>
            <#
        };
        IBHandlers.get_content_by_layout_4 = function (){
            #>
            <div class="one-elements-element__content elementor-align-right">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row oee__align-middle oee__content-right oee__row-reverse">

                    <div class="oee__column">
                        <#  IBHandlers.render_icon_box_content_icon(); #>
                    </div>

                    <div class="oee__column">
                        <# IBHandlers.render_icon_box_content_title(); #>
                    </div>

                </div>
                <# get_item_by_position(1); #>
                <# get_item_by_position(2); #>
            </div>
            <#
        };
        IBHandlers.get_content_by_layout_5 = function (){
            #>
            <div class="one-elements-element__content">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row">
                    <div class="oee__column">
                        <#  IBHandlers.render_icon_box_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };
        IBHandlers.get_content_by_layout_6 = function (){
            #>
            <div class="one-elements-element__content elementor-align-right">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row oee__content-right oee__row-reverse">
                    <div class="oee__column">
                        <# IBHandlers.render_icon_box_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };
        IBHandlers.get_content_by_layout_7 = function (){
            #>
            <div class="one-elements-element__content">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row oee__align-middle">
                    <div class="oee__column">
                        <#  IBHandlers.render_icon_box_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };
        IBHandlers.get_content_by_layout_8 = function (){
            #>
            <div class="one-elements-element__content elementor-align-right">
                <# IBHandlers.render_icon_box_content_floating_text(); #>
                <div class="oee__row oee__align-middle oee__content-right oee__row-reverse">
                    <div class="oee__column">
                        <# IBHandlers.render_icon_box_content_icon(); #>
                    </div>
                    <div class="oee__column">
                        <#
                        get_item_by_position(1);
                        get_item_by_position(2);
                        get_item_by_position(3);
                        #>
                    </div>
                </div>
            </div>
            <#
        };

        function get_content_by_layout(layout){
            layout = parseInt(layout, 10);
            if ( layout < 1 )  layout = 1;
            if ( layout > 8 ) layout = 8;
            let layout_fun = 'get_content_by_layout_' + layout;

            if( typeof IBHandlers[layout_fun] === 'function'){
                IBHandlers[layout_fun](); // call layout function dynamically
            }
        }


        // define render content function
        function render_icon_box_content(){
            const layout = settings['icon_box_layout'];
            view.addRenderAttribute( 'description_text', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ] );
            view.addInlineEditingAttributes( 'description_text' );
            get_content_by_layout( layout );

        }

        let box_link = settings.box_link.url ? settings.box_link : false;

        if ( box_link && 'yes' === settings.link_whole_box) {
            view.addRenderAttribute( 'box_link', 'class', 'one-elements-element__link');
            view.addRenderAttribute( 'box_link', 'href', box_link.url);

            if ( box_link.is_external ) {
                view.addRenderAttribute( 'box_link', 'target', '_blank' );
            }

            if ( box_link.nofollow ) {
                view.addRenderAttribute( 'box_link', 'rel', 'nofollow' );
            }
        }

    #>

		<?php
		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>
            <# view.addRenderAttribute( 'box_link', 'class','elementor-clickable'); #>
		<?php } ?>
    <!-- main wrapper STARTS-->
    <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
        <!-- icon_box STARTS-->
        <div {{{ view.getRenderAttributeString( 'icon_box' ) }}}>
        <!-- Regular State Background -->
            <span  {{{ view.getRenderAttributeString( 'icon_box_regular_state' ) }}}>
                <span class="one-elements-element__state-inner"></span>
            </span>

            <!-- Hover State Background -->
            <span {{{ view.getRenderAttributeString( 'icon_box_hover_state' ) }}} >
                <span class="one-elements-element__state-inner"></span>
            </span>


            <!-- Content -->
            <# render_icon_box_content(); #>
            <#  if ( box_link ) { #>
            <a {{{ view.getRenderAttributeString( 'box_link' ) }}}></a>
            <# } #>
        </div> <!-- icon_box ENDS-->
    </div> <!-- main wrapper ENDS-->
	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Icon_Box() );