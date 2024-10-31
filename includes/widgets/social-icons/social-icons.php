<?php
namespace OneElements\Includes\Widgets\SocialIcons;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Social_Icons;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Traits\One_Elements_Social_Icons_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor social icons widget.
 *
 * Elementor widget that displays icons to social pages like Facebook and Twitter.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Social_Icons extends Widget_Social_Icons {
    use One_Elements_Social_Icons_Trait;
	/**
	 * Get widget name.
	 *
	 * Retrieve social icons widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-social-icons';
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
		return 'one-elements-widget-eicon eicon-social-icons';
	}


	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
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


	/**
	 * Register social icons widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		parent::_register_controls();// bring elementor icon list controls
		$this->remove_control( 'shape');
		$this->remove_control( 'section_social_style');
		$this->remove_control( 'section_social_hover');

		// let's update parents's icon repeater control
		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => __( 'Icon', 'one-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'social',
				'label_block' => true,
				'default' => [
					'value' => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'recommended' => [
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
						'elementor',
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
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'one-elements' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
					'is_external' => 'true',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'one-elements' ),
			]
		);

		$repeater->add_control(
			'item_icon_primary_color',
			[
				'label' => __( 'Custom Color', 'one-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->update_control( 'social_icon_list', [
			'fields' => $repeater->get_controls(),
			'default' => [
				[
					'social_icon' => [
						'value' => 'fab fa-facebook-f',
						'library' => 'fa-brands',
					],
				],
				[
					'social_icon' => [
						'value' => 'fab fa-twitter',
						'library' => 'fa-brands',
					],
				],
				[
					'social_icon' => [
						'value' => 'fab fa-linkedin-in',
						'library' => 'fa-brands',
					],
				],
			],
		]);


        $this->init_style_icon_controls();
        $this->init_style_background_controls();
        $this->init_style_border_controls();
	}

	/**
	 * Render social icons widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if (empty( $settings['social_icon_list']) || !is_array( $settings['social_icon_list'])) return;

		$class_animation = '';

		if ( ! empty( $settings['social_hover_animation'] ) ) {
			$class_animation = ' elementor-animation-' . $settings['social_hover_animation'];
		}

		//Social Icon BG AND BORDER Related
		$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'icon', 'class', 'one-elements-element one-elements-icon one-elements__stop-parent-hover' );

		$this->add_render_attribute( 'social-icons', 'class', 'one-elements-element one-elements-social_icons' );

		?>
        
        <div <?php $this->print_render_attribute_string( 'social-icons' ); ?>>

        	<?php foreach ( $settings['social_icon_list'] as $index => $item ):
		        $social = '';
		        if ( 'svg' !== $item['social_icon']['library'] ) {
			        $social = explode( ' ', $item['social_icon']['value'], 2 );
			        if ( empty( $social[1] ) ) {
				        $social = '';
			        } else {
				        $social = str_replace( 'fa-', '', $social[1] );
			        }
		        }
                $link_key = 'link_' . $index;

                $this->add_render_attribute( $link_key, 'href', $item['link']['url'] );

                if ( $item['link']['is_external'] ) {
                    $this->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $item['link']['nofollow'] ) {
                    $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }


        		?>

				<a class="one-elements-social_icons__single one-elements-social_icon-<?php echo esc_attr( $social); ?>  <?php echo esc_attr( $class_animation); ?>" <?php $this->print_render_attribute_string( $link_key ); ?>>

		            <span <?php $this->print_render_attribute_string( 'icon' ); ?>>

		                <!-- Regular State Background -->
		                <span <?php $this->print_render_attribute_string('icon_regular_state'); ?>>
			            	<span class="one-elements-element__state-inner"></span>
			            </span>

		                <!-- Hover State Background -->
		                <span <?php $this->print_render_attribute_string('icon_hover_state'); ?>>
			            	<span class="one-elements-element__state-inner"></span>
			            </span>

		                <!-- Content including Icon -->
		                <span class="one-elements-element__content">
				            <span class="one-elements-icon__content_icon">
								<?php Icons_Manager::render_icon( $item['social_icon'] ); ?>
							</span>
				        </span>

		            </span>

				</a>

        		
        	<?php endforeach ?>
			

        </div>

		<?php
	}

	/**
	 * Render social icons widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Social_Icons() );
