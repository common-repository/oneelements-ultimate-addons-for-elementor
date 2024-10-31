<?php
namespace OneElements\Includes\Widgets\Icon;

use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Icon;
use OneElements\Includes\Traits\One_Elements_Icon_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon widget.
 *
 * Elementor widget that displays a icon with the ability to control every
 * aspect of the icon design.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Icon extends Widget_Icon {
    use One_Elements_Icon_Trait;
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
		return 'one-elements-icon';
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
		return 'one-elements-widget-eicon eicon-favorite';
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

		$this->init_content_icon_settings();

		$this->init_style_icon_settings();

		$this->init_style_icon_background_settings();

		$this->init_style_icon_background_overlay_settings();

		$this->init_style_icon_border_settings();

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

        // for svg icon
        if ( $settings['icon']['library'] == 'svg' ) {
	        $this->add_render_attribute( 'icon', 'class', 'one-elements-icon__svg' );
        }

		$this->add_render_attribute(
			'wrapper',
			[
		        'class' => [
			        'one-elements-icon__wrapper',
			        $settings['_css_classes']
		        ]
            ]
		);

		
		$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__regular-state' );

		if ( $settings['icon_border_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_regular_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__hover-state' );

		if ( $settings['icon_border_hover_background'] == 'gradient' ) {
			$this->add_render_attribute( 'icon_hover_state', 'class', 'one-elements-element__border-gradient' );
		}


		$this->add_render_attribute( 'icon', 'class', 'one-elements-element one-elements-icon' );

		if ( ! empty( $settings['icon_css_id'] ) ) {
			$this->add_render_attribute( 'icon', 'id', $settings['icon_css_id'] );
		}

		if ( $settings['icon_hover_animation'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['icon_hover_animation'] );
		}

		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <span <?php $this->print_render_attribute_string( 'icon' ); ?>>

                <!-- Regular State Background -->
                <span <?php $this->print_render_attribute_string('icon_regular_state'); ?>>
	            	<span class="one-elements-element__state-inner"></span>
	            </span>

	            <?php if ( $settings['icon_background_hover_background'] || $settings['icon_border_hover_background'] ) : ?>
	                <!-- Hover State Background -->
	                <span <?php $this->print_render_attribute_string('icon_hover_state'); ?>>
		            	<span class="one-elements-element__state-inner"></span>
		            </span>
		        <?php endif; ?>

                <!-- Content including Icon -->
                <span class="one-elements-element__content">
		            <span class="one-elements-icon__content_icon">
						<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
		        </span>
            </span>
        </div>
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
	protected function _content_template() {
		?>
        <#
        let iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		
		view.addRenderAttribute( 'wrapper', {
			'class': [ 'one-elements-icon__wrapper', settings._css_classes ]
		});

		view.addRenderAttribute( 'icon', {
			'class': [ 'one-elements-element', 'one-elements-icon']
		});

		view.addRenderAttribute( 'icon_regular_state', {
			'class': 'one-elements-element__regular-state'
		});

		if ( settings.icon.library == 'svg' ) {

			view.addRenderAttribute( 'icon', {
				'class': 'one-elements-icon__svg'
			});

		}

		if ( ! settings.icon_border_width_classic && settings.icon_border_background == 'gradient' ) {

			view.addRenderAttribute( 'icon_regular_state', {
				'class': 'one-elements-element__border-gradient'
			});

		}

		view.addRenderAttribute( 'icon_hover_state', {
			'class': 'one-elements-element__hover-state'
		});

		if ( ! settings.icon_border_hover_width_classic && settings.icon_border_hover_background == 'gradient' ) {

			view.addRenderAttribute( 'icon_hover_state', {
				'class': 'one-elements-element__border-gradient'
			});

		}



		if ( settings.icon_css_id ) {

			view.addRenderAttribute( 'icon', {
				'id': settings.icon_css_id
			});

		}

		if ( settings.icon_hover_animation ) {

			view.addRenderAttribute( 'icon', {
				'class': 'elementor-animation-' + settings.icon_hover_animation
			});

		}

        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}} >

            <div {{{ view.getRenderAttributeString( 'icon' ) }}} >

                <!-- Regular State Background -->
                <span {{{ view.getRenderAttributeString( 'icon_regular_state' ) }}}>
                	<span class="one-elements-element__state-inner"></span>
                </span>

                <!-- Hover State Background -->
                <span {{{ view.getRenderAttributeString( 'icon_hover_state' ) }}}>
                	<span class="one-elements-element__state-inner"></span>
                </span>

                <!-- Icon Content -->
                <span class="one-elements-element__content">

					<span class="one-elements-icon__content_icon">
                        {{{iconHTML.value}}}
					</span>

                </span>

            </div>

        </div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Icon() );