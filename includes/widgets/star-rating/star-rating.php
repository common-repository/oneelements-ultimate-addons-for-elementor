<?php
namespace OneElements\Includes\Widgets\StarRating;

use Elementor\Plugin;
use Elementor\Widget_Star_Rating;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Elementor star rating widget.
 *
 * Elementor widget that displays star rating.
 *
 * @since 2.3.0
 */
class Widget_OneElements_Star_Rating extends Widget_Star_Rating {

	/**
	 * Get widget name.
	 *
	 * Retrieve star rating widget name.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'one-elements-star-rating';
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
		return 'one-elements-widget-eicon eicon-rating';
	}

	/**
	 * Register star rating widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.0
	 * @access protected
	 */
	protected function _register_controls() {
		parent::_register_controls();
		// remove some controls from parents controls
		$this->remove_control( 'title_color');
		$this->remove_control( 'stars_color');
		$this->remove_control( 'stars_unmarked_color');
		// add new controls
		$this->start_injection( [
			'type' => 'section', // we can inject at a control/section position
			'of' => 'section_title_style', // control/section name
			'at' => 'start', // if type = control, use after/before. if type == section, use start/end
		]);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'rating_title_color',
				'label' => __( 'Text Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-star-rating__title',
			]
		);
        $this->end_injection();

        $this->start_injection( [
                'type' => 'control', // we can inject at a control/section position
                'of' => 'icon_space', // control/section name
                'at' => 'after', // if type = control, use after/before. if type == section, use start/end
        ]);
		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'rating_stars_color',
				'label' => __( 'Stars Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-star-rating i:before',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Gradient::get_type(),
			[
				'name' => 'rating_stars_unmarked_color',
				'label' => __( 'Unmarked Stars Color', 'one-elements' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-star-rating i',
				'separator' => 'after',
			]
		);

		$this->end_injection();

	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_OneElements_Star_Rating() );
