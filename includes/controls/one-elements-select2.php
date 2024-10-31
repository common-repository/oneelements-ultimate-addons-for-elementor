<?php
namespace OneElements\Includes\Controls;


use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Control_One_Element_Select2 extends Base_Data_Control {
	/**
	 * Custom Ajax Select2 control.
	 */
	const SELECT2 = 'one-elements-select2';
	public function get_api_url(){
		return get_rest_url() . 'one-elements/v1';
	}

	/**
	 * Get select2 control type.
	 *
	 * Retrieve the control type, in this case `select2`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'one-elements-select2';
	}

	/**
	 * Enqueue ontrol scripts and styles.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {
		// Scripts
		wp_register_script( 'one-elements-select2-control', ONE_ELEMENTS_ADMIN_JS_URL.'controls/one-elements-select2.js', [ 'jquery' ] );
		wp_enqueue_script( 'one-elements-select2-control' );
	}

	/**
	 * Get select2 control default settings.
	 *
	 * Retrieve the default settings of the select2 control. Used to return the
	 * default settings while initializing the select2 control.
	 *
	 * @since 1.8.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
			'multiple' => false,
			'select2options' => [],
		];
	}


	/**
	 * Render select2 control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select
					id="<?php echo esc_attr($control_uid); ?>"
					class="elementor-one-elements-select2"
					type="one-elements-select2" {{ multiple }}
					data-setting="{{ data.name }}"
					data-ajax-url="<?php echo esc_attr($this->get_api_url() . '/{{data.options}}/'); ?>"
				>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
