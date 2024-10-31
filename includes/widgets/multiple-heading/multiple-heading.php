<?php
namespace OneElements\Includes\Widgets\MultipleHeading;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Widgets\Heading\Widget_OneElements_Heading;

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
class Widget_OneElements_Multiple_Heading extends Widget_OneElements_Heading {

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
		return 'one-elements-multi-heading';
	}


	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Multi Heading', 'elementor' );
	}
	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.00
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'heading', 'title', 'text', 'multiheading', 'multi-heading', 'multiple', 'double-heading', 'double-text' ];
	}
	public static function get_heading_tags() {
		return [
			'h1' => 'H1',
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
			'p' => 'p',
			'div' => 'div',
		];
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
		$this->remove_control( 'section_title_style');
		$this->update_control('title', [ 'label' => __( 'Title One', 'one-elements' ), ] );
		$this->update_control('header_size', [ 'label' => __( 'Title One', 'one-elements' ), ] );


		// lets add new control to the parent's control section using injection technique
		$this->start_injection( [
			'of' => 'title',// control name of the parent where injection will happen
			'at' => 'after',// injecting after the above control name eg. title
		] );

		$this->add_control(
			'title2',
			[
				'label' => __( 'Title Two', 'one-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your 2nd title', 'one-elements' ),
			]
		);
		$this->add_control(
			'title3',
			[
				'label' => __( 'Title Three', 'one-elements' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your 3rd title', 'one-elements' ),
			]
		);
		$this->add_control(
			'title_tag_heading',
			[
				'label'       => __( 'Heading Tags', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->end_injection();


		$this->start_injection( [
			'of' => 'header_size',
			'at' => 'after',
		] );

		$this->add_control(
			'header_size2',
			[
				'label' => __( 'Title Two', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_heading_tags(),
				'default' => 'h3',
			]
		);

		$this->add_control(
			'header_size3',
			[
				'label' => __( 'Title Three', 'one-elements' ),
				'type' => Controls_Manager::SELECT,
				'options' => self::get_heading_tags(),
				'default' => 'h4',
				'separator' => 'after'
			]
		);


		$this->end_injection();


		$this->start_controls_section(
			'one_section_title_style',
			[
				'label' => __( 'Title', 'one-elements'  ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_colors_heading',
			[
				'label'       => __( 'Colors', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title1_color',
				'label' => __( 'Title One', 'one-elements' ),
				'types' => [ 'picture', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-heading__one .one-elements-element__content',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title2_color',
				'label' => __( 'Title Two', 'one-elements' ),
				'types' => [ 'picture', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-heading__two .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'title3_color',
				'label' => __( 'Title Three', 'one-elements' ),
				'types' => [ 'picture', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .one-elements-heading__three .one-elements-element__content',
			]
		);


		$this->add_control(
			'title_typography_heading',
			[
				'label'       => __( 'Typography', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title1',// it actually means title_typography in group
				'label' => __( 'Title One', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading__one .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title2',// it actually means title_typography in group
				'label' => __( 'Title Two', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading__two .one-elements-element__content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title3',// it actually means title_typography in group
				'label' => __( 'Title Three', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading__three .one-elements-element__content',
			]
		);


		$this->add_control(
			'title_shadow_heading',
			[
				'label'       => __( 'Text Shadows', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Title One', 'one-elements' ),
				'name' => 'title1', // it actually means title_shadow | title_shadow_type in group
				'selector' => '{{WRAPPER}} .one-elements-heading__one .one-elements-element__content',
			]
		);


		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title2',
				'label' => __( 'Title Two', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading__two .one-elements-element__content',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title3',
				'label' => __( 'Title Three', 'one-elements' ),
				'selector' => '{{WRAPPER}} .one-elements-heading__three .one-elements-element__content',
			]
		);

		$this->init_spacing_controls();

		$this->end_controls_section();

	}

	protected function init_spacing_controls(  )
	{

		$this->add_control(
			'title_spacing_heading',
			[
				'label'       => __( 'Spacings', 'one-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		$this->add_responsive_control(
			'title1_margin',
			[
				'label' => __( 'Title One', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-heading__one .one-elements-element__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'title2_margin',
			[
				'label' => __( 'Title Two', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-heading__two .one-elements-element__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title3_margin',
			[
				'label' => __( 'Title Three', 'one-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .one-elements-heading__three .one-elements-element__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

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

		$this->add_render_attribute( 'title', 'class', 'one-elements-element__content' );
		$this->add_render_attribute( 'title2', 'class', 'one-elements-element__content' );
		$this->add_render_attribute( 'title3', 'class', 'one-elements-element__content' );

		$this->add_inline_editing_attributes( 'title');
		$this->add_inline_editing_attributes( 'title2');
		$this->add_inline_editing_attributes( 'title3');
		?>

        <div class="one-elements-multi-heading">

            <?php if ( !Utils::is_empty($settings['title']) ){ ?>
                <div class="one-elements-element one-elements-heading one-elements-heading__one">
                    <?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $settings['title'] ); ?>
                </div>
            <?php }

             if (!Utils::is_empty($settings['title2'])){ ?>
                <div class="one-elements-element one-elements-heading one-elements-heading__two">
                    <?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size2'], $this->get_render_attribute_string( 'title2' ), $settings['title2'] ); ?>
                </div>
		    <?php }

		    if (!Utils::is_empty($settings['title3'])){ ?>
                <div class="one-elements-element one-elements-heading one-elements-heading__three">
                    <?php echo sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size3'], $this->get_render_attribute_string( 'title3' ), $settings['title3'] ); ?>
                </div>
			<?php } ?>

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
        let title_html, title2_html, title3_html;

		view.addRenderAttribute( 'title', 'class', 'one-elements-element__content' );
		view.addRenderAttribute( 'title2', 'class', 'one-elements-element__content' );
		view.addRenderAttribute( 'title3', 'class', 'one-elements-element__content' );

        view.addInlineEditingAttributes( 'title' );
        view.addInlineEditingAttributes( 'title2' );
        view.addInlineEditingAttributes( 'title3' );

        if (settings.title) {
            title_html = '<' + settings.header_size  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + settings.title + '</' + settings.header_size + '>';
        }

        if (settings.title2) {
        title2_html = '<' + settings.header_size2  + ' ' + view.getRenderAttributeString( 'title2' ) + '>' + settings.title2 + '</' + settings.header_size2 + '>';
        }

        if (settings.title2) {
        title3_html = '<' + settings.header_size3  + ' ' + view.getRenderAttributeString( 'title3' ) + '>' + settings.title3 + '</' + settings.header_size3 + '>';
        }
		#>

        <div class="one-elements-multi-heading">

            <# if (settings.title) { #>
            <div class="one-elements-element one-elements-heading one-elements-heading__one">
                <# print( title_html ); #>
            </div>
            <# } #>

            <# if (settings.title2) { #>
            <div class="one-elements-element one-elements-heading one-elements-heading__two">
                <# print( title2_html ); #>
            </div>
            <# } #>

            <# if (settings.title3) { #>
            <div class="one-elements-element one-elements-heading one-elements-heading__three">
                <# print( title3_html ); #>
            </div>
            <# } #>

        </div>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Multiple_Heading() );
