<?php
namespace OneElements\Includes\Controls;


use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor choose control.
 *
 * A base control for creating choose control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 1.0.0
 */
class Control_One_Element_Image_Choose extends Base_Data_Control {

	/**
	 * Get choose control type.
	 *
	 * Retrieve the control type, in this case `choose`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'image_choose';
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the image chooser control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
		// Scripts
		wp_register_script( 'image_choose-control', ONE_ELEMENTS_ADMIN_JS_URL.'controls/image-choose.js', [ 'jquery' ] );
		wp_enqueue_script( 'image_choose-control' );
	}
	/**
	 * Render choose control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{value}}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
			<#
				const options_chunk = one_element_chunk(data.options, data.display_per_row);

				_.each(options_chunk, function( ops, value ) {
			 #>
				<div class="elementor-choices one-elements-control__image-choices">
					<# _.each( ops, function( options, value ) { #>
					<input id="<?php echo esc_attr($control_uid); ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
					<label style="width: {{ 100 / data.display_per_row }}%; " class="elementor-choices-label tooltip-target" for="<?php echo esc_attr($control_uid); ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
						<div class="elementor-choices-label__wrapper">
							<img src="{{ options.image }}" aria-hidden="true">
							<span class="elementor-screen-only">{{{ options.title }}}</span>
						</div>
					</label>
					<# } );#>
				</div>
				<# } );#>
			</div>
		</div>

		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	/**
	 * Get choose control default settings.
	 *
	 * Retrieve the default settings of the choose control. Used to return the
	 * default settings while initializing the choose control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'options' => [],
			'toggle' => false,
		];
	}
}
