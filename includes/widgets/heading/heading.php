<?php
namespace OneElements\Includes\Widgets\Heading;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Heading;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Widget_OneElements_Heading extends Widget_Heading {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-heading';
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
		return 'one-elements-widget-eicon eicon-t-letter';
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
	    parent::_register_controls();
	    $this->remove_control( 'size');
	    $this->remove_control( 'link');
	    $this->remove_control( 'title_color');
	    // remove all parents typography related controls
	    $this->remove_control( 'typography_typography');
	    $this->remove_control( 'typography_font_family');
	    $this->remove_responsive_control( 'typography_font_size');
	    $this->remove_control( 'typography_font_weight');
	    $this->remove_control( 'typography_text_transform');
	    $this->remove_control( 'typography_font_style');
	    $this->remove_control( 'typography_text_decoration');
	    $this->remove_responsive_control( 'typography_line_height');
	    $this->remove_responsive_control( 'typography_letter_spacing');
        // remove all shadow controls from parents
	    $this->remove_control( 'text_shadow_text_shadow_type');
	    $this->remove_control( 'text_shadow_text_shadow');

        $this->update_control( 'blend_mode', [
	        'selectors' => [
		        '{{WRAPPER}} .one-elements-element__content' => 'mix-blend-mode: {{VALUE}}',
	        ],
        ]);

		$this->start_injection( [
			'of' => 'section_title_style',// control name of the parent where injection will happen
			'at' => 'after',// injecting after the above control name eg. title
		] );

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title_color',
				'label' => __( 'Color', 'one-elements' ),
				'types' => [ 'picture', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title',// it actually means title_typography in group
				'label' => __( 'Title Typography', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Title Shadow', 'one-elements' ),
				'name' => 'title', // it actually means title_shadow | title_shadow_type in group
				'selector' => '{{WRAPPER}} .one-elements-element__content',
			]
		);

		$this->end_injection();

	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		if ( !isset( $settings['title'] ) || '' === $settings['title']) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'one-elements-element__content' );

		$this->add_inline_editing_attributes( 'title' );

		?>

		<div class="one-elements-element one-elements-heading">
			<?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $settings['title'] ); ?>
		</div>

		<?php

	}

	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
        <#

        view.addRenderAttribute( 'title', 'class', 'one-elements-element__content' );

        view.addInlineEditingAttributes( 'title' );

        var title_html = '<' + settings.header_size  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + settings.title + '</' + settings.header_size + '>';
        
        #>
		
		<div class="one-elements-element one-elements-heading">
			<# print( title_html ); #>
		</div>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Heading() );
