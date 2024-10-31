<?php
namespace OneElements\Includes\Controls;

use Elementor\Control_Base_Multiple;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor box shadow control.
 *
 * A base control for creating box shadows control. Displays input fields for
 * horizontal shadow, vertical shadow, shadow blur, shadow spread and shadow
 * color.
 *
 * @since 1.0.0
 */
class Control_One_Element_Box_Shadow extends Control_Base_Multiple {

	/**
	 * Get box shadow control type.
	 *
	 * Retrieve the control type, in this case `box_shadow`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'oee_box_shadow';
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
		wp_register_script( 'oee_box_shadow', ONE_ELEMENTS_ADMIN_JS_URL.'controls/box-shadow.js', [ 'jquery' ] );
		wp_enqueue_script( 'oee_box_shadow' );
	}




	/**
	 * Get box shadow control sliders.
	 *
	 * Retrieve the sliders of the box shadow control. Sliders are used while
	 * rendering the control output in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control sliders.
	 */
	public function get_sliders() {
		return [
			'horizontal' => [
				'label' => __( 'Horizontal', 'one-elements' ),
				'min' => -100,
				'max' => 100,
			],
			'vertical' => [
				'label' => __( 'Vertical', 'one-elements' ),
				'min' => -100,
				'max' => 100,
			],
			'blur' => [
				'label' => __( 'Blur', 'one-elements' ),
				'min' => 0,
				'max' => 100,
			],
			'spread' => [
				'label' => __( 'Spread', 'one-elements' ),
				'min' => -100,
				'max' => 100,
			],
		];
	}

	/**
	 * Render box shadow control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
        <div class="elementor-shadow-box">
			<div class="elementor-control-field elementor-color-picker-wrapper">
				<label class="elementor-control-title"><?php echo __( 'Color', 'one-elements' ); ?></label>
				<div class="elementor-control-input-wrapper elementor-control-unit-1">
					<div class="elementor-color-picker-placeholder"></div>

				</div>
			</div>
			<?php
			foreach ( $this->get_sliders() as $slider_name => $slider ) :
				$control_uid = $this->get_control_uid( $slider_name );
				?>
				<div class="elementor-shadow-slider elementor-control-type-slider">
					<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title"><?php echo $slider['label']; ?></label>
					<div class="elementor-control-input-wrapper">
						<div class="elementor-slider" data-input="<?php echo esc_attr( $slider_name ); ?>"></div>
						<div class="elementor-slider-input elementor-control-unit-2">
							<input id="<?php echo esc_attr( $control_uid ); ?>" type="number" min="<?php echo esc_attr( $slider['min'] ); ?>" max="<?php echo esc_attr( $slider['max'] ); ?>" data-setting="<?php echo esc_attr( $slider_name ); ?>"/>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
