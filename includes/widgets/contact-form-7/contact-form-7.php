<?php
namespace OneElements\Includes\Widgets\ContactForm7;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;


class Widget_OneElements_Counter_Contact_Form_7 extends Widget_Base
{
	/**
	 * Retrieve contact form 7 widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'one-elements-contact-form-7';

	}

	/**
	 * Retrieve contact form 7 widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('Contact Form 7', 'one-elements');
	}

	/**
	 * Retrieve the list of categories the contact form 7 widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return [ 'one_elements' ];
	}

	public function get_style_depends() {
		return [ 'elementor-icons-fa-regular', 'elementor-icons-fa-solid', 'elementor-icons-fa-brands' ];
	}

	/**
	 * Retrieve contact form 7 widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'one-elements-widget-eicon eicon-envelope';
	}

	/**
	 * Get Contact Form 7 [ if exists ]
	 */
	public function one_elements_get_contact_form_list()
	{
		$options = array();

		if (function_exists('wpcf7')) {
			$wpcf7_form_list = get_posts(array(
				'post_type' => 'wpcf7_contact_form',
				'showposts' => 999,
			));
			$options[0] = esc_html__('Select a Contact Form', 'one-elements');
			if (!empty($wpcf7_form_list) && !is_wp_error($wpcf7_form_list)) {
				foreach ($wpcf7_form_list as $post) {
					$options[$post->ID] = $post->post_title;
				}
			} else {
				$options[0] = esc_html__('Create a Form First', 'one-elements');
			}
		}
		return $options;
	}

	/**
	 * Register contact form 7 widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls()
	{
		/*-----------------------------------------------------------------------------------*/
		/*    CONTENT TAB
		/*-----------------------------------------------------------------------------------*/
		if (!function_exists('wpcf7')) {
			$this->init_content_cf7_not_installed_controls();
		} else {
			$this->init_content_form_controls();
			$this->init_content_errors_success_controls();
		}

		/*-----------------------------------------------------------------------------------*/
		/*    STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		$this->init_style_form_container_controls();

		$this->init_style_title_description_controls();

		$this->init_style_input_textarea_controls();

		$this->init_style_labels_controls();

		$this->init_style_placeholder_controls();

		// $this->init_style_radio_checkbox_controls(); @todo Will implement later

		$this->init_style_submit_controls();

		$this->init_style_loader_controls();

		$this->init_style_errors_success_controls();
	}

	protected function init_content_cf7_not_installed_controls() {
		$this->start_controls_section(
			'one_elements_global_warning',
			[
				'label' => __('Warning!', 'one-elements'),
			]
		);

		$this->add_control(
			'one_elements_global_warning_text',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(__('%1$s Contact Form 7 %2$s is not installed/activated on your site. Please install and activate %1$s Contact Form 7 %2$s first.', 'one-elements'), '<strong>', '</strong>'),
				'content_classes' => 'one-elements-warning',
			]
		);

		$this->end_controls_section();
	}

	protected function init_content_form_controls() {
		$this->start_controls_section(
			'section_info_box',
			[
				'label' => __('Contact Form', 'one-elements'),
			]
		);

		$this->add_control(
			'contact_form_list',
			[
				'label' => esc_html__('Select Form', 'one-elements'),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options' => $this->one_elements_get_contact_form_list(),
				'default' => '0',
			]
		);

		$this->add_control(
			'form_title',
			[
				'label' => __('Form Title', 'one-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'one-elements'),
				'label_off' => __('Off', 'one-elements'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'form_title_text',
			[
				'label' => esc_html__('Title', 'one-elements'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
				'condition' => [
					'form_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'form_description',
			[
				'label' => __('Form Description', 'one-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'one-elements'),
				'label_off' => __('Off', 'one-elements'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'form_description_text',
			[
				'label' => esc_html__('Description', 'one-elements'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'condition' => [
					'form_description' => 'yes',
				],
			]
		);



		$this->end_controls_section();
	}

	protected function init_content_errors_success_controls() {
		$this->start_controls_section(
			'section_errors',
			[
				'label' => __('Errors | Success', 'one-elements'),
			]
		);
		$this->add_control(
			'validation_errors',
			[
				'label' => __('Validation Errors', 'one-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __('Show', 'one-elements'),
					'hide' => __('Hide', 'one-elements'),
				],
				'selectors_dictionary' => [
					'show' => 'block',
					'hide' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors' => 'display: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'error_messages',
			[
				'label' => __('Error Messages', 'one-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __('Show', 'one-elements'),
					'hide' => __('Hide', 'one-elements'),
				],
				'selectors_dictionary' => [
					'show' => 'block',
					'hide' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid-tip, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng' => 'display: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'success_messages',
			[
				'label' => __('Success Messages', 'one-elements'),
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __('Show', 'one-elements'),
					'hide' => __('Hide', 'one-elements'),
				],
				'selectors_dictionary' => [
					'show' => 'block',
					'hide' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok' => 'display: {{VALUE}} !important;',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function init_style_form_container_controls() {
		$this->start_controls_section(
			'section_container_style',
			[
				'label' => __('Form Container', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'one_elements_contact_form_background',
				'label' => __('Background', 'plugin-domain'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .one-elements-contact-form',
			]
		);

		$this->add_responsive_control(
			'one_elements_contact_form_alignment',
			[
				'label' => esc_html__('Form Alignment', 'one-elements'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __('Default', 'one-elements'),
						'icon' => 'fa fa-ban',
					],
					'left' => [
						'title' => esc_html__('Left', 'one-elements'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'one-elements'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'one-elements'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'default',
			]
		);

		$this->add_responsive_control(
			'one_elements_contact_form_max_width',
			[
				'label' => esc_html__('Form Max Width', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', '%'],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7-wrapper form' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'one_elements_contact_form_margin',
			[
				'label' => esc_html__('Form Margin', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'one_elements_contact_form_padding',
			[
				'label' => esc_html__('Form Padding', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'one_elements_contact_form_border_radius',
			[
				'label' => esc_html__('Border Radius', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'one_elements_contact_form_border',
				'selector' => '{{WRAPPER}} .one-elements-contact-form',
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'one_elements_contact_form_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form',
			]
		);

		$this->end_controls_section();
	}

	protected function init_style_title_description_controls() {
		$this->start_controls_section(
			'section_fields_title_description',
			[
				'label' => __('Title & Description', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'heading_alignment',
			[
				'label' => __('Alignment', 'one-elements'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'one-elements'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'one-elements'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'one-elements'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-heading' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label' => __('Title', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-title',
			]
		);
		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __('Spacing', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'description_heading',
			[
				'label' => __('Description', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-description',
			]
		);
		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __('Spacing', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .one-elements-contact-form-7-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function init_style_input_textarea_controls() {
		$this->start_controls_section(
			'section_fields_style',
			[
				'label' => __('Input & Textarea', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_fields_style');

		$this->start_controls_tab(
			'tab_fields_normal',
			[
				'label' => __('Normal', 'one-elements'),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-list-item-label',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_bg',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-list-item-label' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'input_spacing',
			[
				'label' => __('Spacing', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '20',
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form p:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __('Padding', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_indent',
			[
				'label' => __('Text Indent', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value' => 'text-indent: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'input_width',
			[
				'label' => __('Input Width', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'textarea_width',
			[
				'label' => __('Textarea Width', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'label' => __('Border', 'one-elements'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text,{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__dropdown',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_radius',
			[
				'label' => __('Border Radius', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__option--value',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_focus',
			[
				'label' => __('Focus', 'one-elements'),
			]
		);

		$this->add_control(
			'field_bg_focus',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select:focus, {{WRAPPER}} .one-elements-contact-form-7 .custom-select.custom-select--active .custom-select__option--value' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'field_text_color_focus',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select:focus, {{WRAPPER}} .one-elements-contact-form-7 .custom-select.custom-select--active .custom-select__option--value' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border_focus',
				'label' => __('Border', 'one-elements'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select:focus, {{WRAPPER}} .one-elements-contact-form-7 .custom-select.custom-select--active .custom-select__option--value, {{WRAPPER}} .one-elements-contact-form-7 .custom-select .custom-select__dropdown',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'focus_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-text:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-date:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-textarea:focus, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control.wpcf7-select:focus, {{WRAPPER}} .one-elements-contact-form-7 .custom-select.custom-select--active .custom-select__option--value',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function init_style_labels_controls() {

		$this->start_controls_section(
			'section_label_style',
			[
				'label' => __('Labels', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color_label',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form label' => 'color: {{VALUE}}',
				],

			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __('Spacing', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form label .wpcf7-form-control, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .custom-select' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_label',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form label',

			]
		);

		$this->end_controls_section();
	}

	protected function init_style_placeholder_controls() {
		$this->start_controls_section(
			'section_placeholder_style',
			[
				'label' => __('Placeholder', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'placeholder_switch',
			[
				'label' => __('Show Placeholder', 'one-elements'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Yes', 'one-elements'),
				'label_off' => __('No', 'one-elements'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'text_color_placeholder',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
				],
				'condition' => [
					'placeholder_switch' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_placeholder',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder',
				'condition' => [
					'placeholder_switch' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	// @todo Will implement later
	// protected function init_style_radio_checkbox_controls() {
	// 	$this->start_controls_section(
	// 		'section_radio_checkbox_style',
	// 		[
	// 			'label' => __('Radio & Checkbox', 'one-elements'),
	// 			'tab' => Controls_Manager::TAB_STYLE,
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'custom_radio_checkbox',
	// 		[
	// 			'label' => __('Custom Styles', 'one-elements'),
	// 			'type' => Controls_Manager::SWITCHER,
	// 			'label_on' => __('Yes', 'one-elements'),
	// 			'label_off' => __('No', 'one-elements'),
	// 			'return_value' => 'yes',
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'radio_checkbox_size',
	// 		[
	// 			'label' => __('Size', 'one-elements'),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'default' => [
	// 				'size' => '15',
	// 				'unit' => 'px',
	// 			],
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 80,
	// 					'step' => 1,
	// 				],
	// 			],
	// 			'size_units' => ['px', 'em', '%'],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->start_controls_tabs('tabs_radio_checkbox_style');

	// 	$this->start_controls_tab(
	// 		'radio_checkbox_normal',
	// 		[
	// 			'label' => __('Normal', 'one-elements'),
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'radio_checkbox_color',
	// 		[
	// 			'label' => __('Color', 'one-elements'),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]' => 'background: {{VALUE}}',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_responsive_control(
	// 		'radio_checkbox_border_width',
	// 		[
	// 			'label' => __('Border Width', 'one-elements'),
	// 			'type' => Controls_Manager::SLIDER,
	// 			'range' => [
	// 				'px' => [
	// 					'min' => 0,
	// 					'max' => 15,
	// 					'step' => 1,
	// 				],
	// 			],
	// 			'size_units' => ['px'],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]' => 'border-width: {{SIZE}}{{UNIT}}',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'radio_checkbox_border_color',
	// 		[
	// 			'label' => __('Border Color', 'one-elements'),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]' => 'border-color: {{VALUE}}',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'checkbox_heading',
	// 		[
	// 			'label' => __('Checkbox', 'one-elements'),
	// 			'type' => Controls_Manager::HEADING,
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'checkbox_border_radius',
	// 		[
	// 			'label' => __('Border Radius', 'one-elements'),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => ['px', 'em', '%'],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'radio_heading',
	// 		[
	// 			'label' => __('Radio Buttons', 'one-elements'),
	// 			'type' => Controls_Manager::HEADING,
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'radio_border_radius',
	// 		[
	// 			'label' => __('Border Radius', 'one-elements'),
	// 			'type' => Controls_Manager::DIMENSIONS,
	// 			'size_units' => ['px', 'em', '%'],
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->start_controls_tab(
	// 		'radio_checkbox_checked',
	// 		[
	// 			'label' => __('Checked', 'one-elements'),
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->add_control(
	// 		'radio_checkbox_color_checked',
	// 		[
	// 			'label' => __('Color', 'one-elements'),
	// 			'type' => Controls_Manager::COLOR,
	// 			'default' => '',
	// 			'selectors' => [
	// 				'{{WRAPPER}} .one-elements-custom-radio-checkbox input[type="checkbox"]:checked:before, {{WRAPPER}} .one-elements-custom-radio-checkbox input[type="radio"]:checked:before' => 'background: {{VALUE}}',
	// 			],
	// 			'condition' => [
	// 				'custom_radio_checkbox' => 'yes',
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_tab();

	// 	$this->end_controls_tabs();

	// 	$this->end_controls_section();

	// }

	protected function init_style_submit_controls() {
		$this->start_controls_section(
			'section_submit_button_style',
			[
				'label' => __('Submit Button', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __('Alignment', 'one-elements'),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => __('Left', 'one-elements'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'one-elements'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'one-elements'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form p:nth-last-of-type(1)' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'display:inline-block;',
				],
			]
		);


		$this->add_responsive_control(
			'button_width',
			[
				'label' => __('Width', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'one-elements'),
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border_normal',
				'label' => __('Border', 'one-elements'),
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __('Border Radius', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __('Padding', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __('Margin Top', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'one-elements'),
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' => __('Border Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-form .wpcf7-submit:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function init_style_loader_controls() {
		$this->start_controls_section(
			'section_loader_style',
			[
				'label' => __('Ajax Loader', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'loader_margin',
			[
				'label' => esc_html__('Margin', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .ajax-loader' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'loader_size',
			[
				'label' => __('Size', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .ajax-loader' => 'height: {{SIZE}}{{UNIT}}; width:{{SIZE}}{{UNIT}}; ',
				],
			]
		);

		$this->add_control(
			'enable_custom_loader',
			[
				'label' => __('Custom Loader', 'one-elements'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('On', 'one-elements'),
				'label_off' => __('Off', 'one-elements'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'custom_loader',
			[
				'label' => __( 'Upload Loader', 'one-elements' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'enable_custom_loader' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .ajax-loader' => 'background-image: url("{{URL}}");',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function init_style_errors_success_controls() {
		$this->start_controls_section(
			'section_error_style',
			[
				'label' => __('Errors | Success', 'one-elements'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_error_messages_style');

		$this->start_controls_tab(
			'tab_error_messages_alert',
			[
				'label' => __('Messages', 'one-elements'),
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		/*
		 * ERROR ALERTS
		 * ----------------------------------
		 */
		$this->init_style_es__error_alert_controls();

		/*
		 * SUCCESS ALERT
		 * -----------------------------
		 */
		$this->init_style_es__success_alert_controls();

		$this->end_controls_tab();


		$this->start_controls_tab(
			'tab_error_messages_fields',
			[
				'label' => __('Fields', 'one-elements'),
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		/*
 * Field ALERTS
 * ----------------------------------
 */

		$this->init_style_es__field_alert_controls();

		$this->add_control(
			'field_style_heading',
			[
				'label' => __('Fields Style', 'one-elements'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'error_field_bg_color',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid' => 'background: {{VALUE}}',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_control(
			'error_field_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid' => 'color: {{VALUE}}',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'error_field_border',
				'label' => __('Border', 'one-elements'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid',
				'separator' => 'before',
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'error_field_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function init_style_es__field_alert_controls() {
		$this->add_control(
			'field_alert_heading',
			[
				'label' => __('Validation Errors', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'validation_errors' => 'show',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_alert_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid-tip',
				'condition' => [
					'validation_errors' => 'show',
				],
			]
		);

		$this->add_control(
			'field_alert_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid-tip' => 'color: {{VALUE}}',
				],
				'condition' => [
					'validation_errors' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'field_alert_spacing',
			[
				'label' => __('Spacing', 'one-elements'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-not-valid-tip' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'validation_errors' => 'show',
				],
			]
		);
	}

	protected function init_style_es__error_alert_controls() {

		$this->add_control(
			'error_messages_heading',
			[
				'label' => __('Error Messages', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'error_messages' => 'show',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'error_alert_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng',
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_control(
			'error_alert_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng' => 'color: {{VALUE}}',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);
		$this->add_control(
			'error_alert_bg_color',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng' => 'background: {{VALUE}}',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);
		$this->add_responsive_control(
			'error_alert_padding',
			[
				'label' => esc_html__('Padding', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);
		$this->add_responsive_control(
			'error_alert_margin',
			[
				'label' => esc_html__('Margin', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'error_alert_border',
				'label' => __('Border', 'one-elements'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng',
				'separator' => 'before',
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'error_alert_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-validation-errors, {{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ng',
				'condition' => [
					'error_messages' => 'show',
				],
			]
		);



	}

	protected function init_style_es__success_alert_controls() {
		$this->add_control(
			'success_messages_heading',
			[
				'label' => __('Success Messages', 'one-elements'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'success_messages' => 'show',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'success_alert_typography',
				'label' => __('Typography', 'one-elements'),
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok',
				'condition' => [
					'success_messages' => 'show',
				],
			]
		);

		$this->add_control(
			'success_alert_text_color',
			[
				'label' => __('Text Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok' => 'color: {{VALUE}}',
				],
				'condition' => [
					'success_messages' => 'show',
				],
			]
		);
		$this->add_control(
			'success_alert_bg_color',
			[
				'label' => __('Background Color', 'one-elements'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok' => 'background: {{VALUE}}',
				],
				'condition' => [
					'success_messages' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'success_alert_padding',
			[
				'label' => esc_html__('Padding', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'success_alert_margin',
			[
				'label' => esc_html__('Margin', 'one-elements'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'success_alert_border',
				'label' => __('Border', 'one-elements'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok',
				'separator' => 'before',
				'condition' => [
					'success_messages' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_ONE_ELEMENTS_Box_Shadow::get_type(),
			[
				'name' => 'success_alert_box_shadow',
				'selector' => '{{WRAPPER}} .one-elements-contact-form-7 .wpcf7-mail-sent-ok',
			]
		);


	}

	/**
	 * @access protected
	 */
	protected function render()
	{
		if (!function_exists('wpcf7')) {
			return;
		}

		$settings = $this->get_settings();

		$this->add_render_attribute('contact-form', 'class', [
			'one-elements-contact-form',
			'one-elements-contact-form-7',
			'one-elements-contact-form-' . esc_attr($this->get_id()),
		]);

		if ($settings['placeholder_switch'] == 'yes') {
			$this->add_render_attribute('contact-form', 'class', 'placeholder-show');
		}

		if (!empty( $settings['custom_radio_checkbox']) && $settings['custom_radio_checkbox'] == 'yes') {
			$this->add_render_attribute('contact-form', 'class', 'one-elements-custom-radio-checkbox');
		}

		if ($settings['one_elements_contact_form_alignment'] == 'left') {
			$this->add_render_attribute('contact-form', 'class', 'one-elements-contact-form-align-left');
		} elseif ($settings['one_elements_contact_form_alignment'] == 'center') {
			$this->add_render_attribute('contact-form', 'class', 'one-elements-contact-form-align-center');
		} elseif ($settings['one_elements_contact_form_alignment'] == 'right') {
			$this->add_render_attribute('contact-form', 'class', 'one-elements-contact-form-align-right');
		} else {
			$this->add_render_attribute('contact-form', 'class', 'one-elements-contact-form-align-default');
		}

		if (!empty($settings['contact_form_list'])) {
			echo '<div class="one-elements-contact-form-7-wrapper">
                <div ' . $this->get_render_attribute_string('contact-form') . '>';
			if ($settings['form_title'] == 'yes' || $settings['form_description'] == 'yes') {
				echo '<div class="one-elements-contact-form-7-heading">';
				if ($settings['form_title'] == 'yes' && $settings['form_title_text'] != '') {
					echo '<h3 class="one-elements-contact-form-title one-elements-contact-form-7-title">
                                    ' . esc_html($settings['form_title_text']) . '
                                </h3>';
				}
				if ($settings['form_description'] == 'yes' && $settings['form_description_text'] != '') {
					echo '<div class="one-elements-contact-form-description one-elements-contact-form-7-description">
                                    ' . $this->parse_text_editor($settings['form_description_text']) . '
                                </div>';
				}
				echo '</div>';
			}
			echo do_shortcode('[contact-form-7 id="' . $settings['contact_form_list'] . '" ]');
			echo '</div>
            </div>';
		}
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Counter_Contact_Form_7() );
