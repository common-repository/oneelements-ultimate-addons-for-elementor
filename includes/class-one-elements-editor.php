<?php
namespace OneElements\Includes;

use OneElements\Includes\Controls\Control_One_Element_Box_Shadow;
use OneElements\Includes\Controls\Control_One_Element_Image_Choose;
use OneElements\Includes\Controls\Control_One_Element_Select2;
use OneElements\Includes\Controls\Group\Group_Control_Gradient_Background;
use OneElements\Includes\Controls\Group\Group_Control_Text_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Border_Gradient;
use OneElements\Includes\Controls\Group\Group_Control_ONE_ELEMENTS_Box_Shadow;
use OneElements\Includes\Controls\Group\ONE_ELEMENTS_Posts_Group_Control;
use OneElements\Includes\Extending\OneElementsExtension;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'One_Elements_Editor') ):

class One_Elements_Editor {

	public function __construct() {

		add_action('elementor/init', [$this, 'init_elementor_pro_related_stuff']);
		// Custom contact methods.
		add_filter( 'user_contactmethods', array( $this, 'contact_methods' ), 10, 1 );
		// register elementor category
		add_action( 'elementor/elements/categories_registered', [ $this, 'one_elements_add_elementor_widget_categories' ] );
		// register custom elementor controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_custom_elementor_control' ] );
		// register our enhanced widget
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_custom_elementor_widget' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

		$this->load_xyz_stuffs();

		$ext = new OneElementsExtension();
		$ext->init();

	}

	public function init_elementor_pro_related_stuff() {

		if ( !defined('ELEMENTOR_PRO_VERSION') ) {

			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/classes/elementor-post-query.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/classes/elementor-related-query.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/controls/query.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/controls/group-control-query.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/controls/group-control-posts.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/controls/group-control-related.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'query-control/module.php';

			include_once ONE_ELEMENTS_WIDGET_PATH . 'dynamic-tags/module.php';
			include_once ONE_ELEMENTS_WIDGET_PATH . 'custom-css/module.php';

		}
	}

	/**
	 * It registers custom elementor controls
	 *
	 * @param \Elementor\Controls_Manager $controls_manager
	 */
	public function register_custom_elementor_control( $controls_manager ) {

		$this->_includes();

		$controls_manager->register_control(  'one-elements-select2', new Control_One_Element_Select2());
		$controls_manager->add_group_control( 'one_elements_text_gradient', new Group_Control_Text_Gradient() );
		$controls_manager->add_group_control( 'one_elements_border_gradient', new Group_Control_ONE_ELEMENTS_Border_Gradient() );
		$controls_manager->add_group_control( 'one_elements_border', new Group_Control_ONE_ELEMENTS_Border() );
		$controls_manager->add_group_control( 'one-elements-box-shadow', new Group_Control_ONE_ELEMENTS_Box_Shadow() );
		$controls_manager->add_group_control( 'one-elements-background-gradient', new Group_Control_Gradient_Background() );

		$controls_manager->add_group_control( 'one-elements-posts', new ONE_ELEMENTS_Posts_Group_Control() );
		$controls_manager->register_control(  'image_choose', new Control_One_Element_Image_Choose());
		$controls_manager->register_control(  'oee_box_shadow', new Control_One_Element_Box_Shadow());

	}

	private function load_xyz_stuffs() {
		include_once ONE_ELEMENTS_INC_PATH . 'extending/class-extending-elementor.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/one-elements-hover-animations.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/one-elements-entrance-animations.php';

	}

	private function _includes() {

		include_once ONE_ELEMENTS_INC_PATH . 'controls/one-elements-select2.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-text-gradient.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-border-gradient.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-border-control.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-box-shadow.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-gradient-background.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/group/one-elements-posts-control.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/one-elements-image-chooser.php';
		include_once ONE_ELEMENTS_INC_PATH . 'controls/one-elements-box-shadow.php';

	}

	/**
	 * It registers custom elementor widgets
	 * @param \Elementor\Widgets_Manager $widget_manager
	 */
	public function register_custom_elementor_widget( $widget_manager ) {

		// include helper trait for widgets
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-common-widget.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-divider.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-icon.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-button.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-carousel.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-filter.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-logo.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-social-icons.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-badge.php';
		include_once ONE_ELEMENTS_INC_PATH . 'traits/trait-one-elements-tooltip.php';

		// include widgets
		include_once ONE_ELEMENTS_WIDGET_PATH . 'button/button.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'dual-button/dual-button.php';
		// include_once ONE_ELEMENTS_WIDGET_PATH . 'dual-button/dual-button-new.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'heading/heading.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'text-editor/text-editor.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'multiple-heading/multiple-heading.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'image/image.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'icon/icon.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'star-rating/star-rating.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'icon-list/icon-list.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'counter/counter.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'social-icons/social-icons.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'divider/divider.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'icon-box/icon-box.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'post-grid/post-grid.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'accordion/accordion.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'logos/logos.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'testimonials/testimonials.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'team-members/team-members.php';
		include_once ONE_ELEMENTS_WIDGET_PATH . 'contact-form-7/contact-form-7.php';
	}


	/**
	 * It adds a new category to the elementor editor
	 * @param \Elementor\Elements_Manager $elements_manager
	 */
	function one_elements_add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category( 'one_elements', [
			'title' => __( 'One Elements', 'one-elements' ),
			'icon' => 'fa fa-plugin',
		]);

		$elements_manager->add_category( 'one_elements_single', [
			'title' => __( 'One Elements Single', 'one-elements' ),
			'icon' => 'fa fa-plugin',
		]);

		$elements_manager->add_category( 'one_elements_archive', [
			'title' => __( 'One Elements Archives', 'one-elements' ),
			'icon' => 'fa fa-plugin',
		]);

		$elements_manager->add_category( 'one_elements_wc', [
			'title' => __( 'One Elements WooCommerce', 'one-elements' ),
			'icon' => 'fa fa-woocommerce',
		]);

		$elements_manager->add_category( 'one_elements_wc_single', [
			'title' => __( 'One Elements WC Single Product', 'one-elements' ),
			'icon' => 'fa fa-woocommerce',
		]);

	}

	public function enqueue_editor_styles() {

		$one_elements_editor = 'one-elements-editor';
		$one_elements_editor = ( SCRIPT_DEBUG ) ? $one_elements_editor : $one_elements_editor.'.min';

		wp_enqueue_style( 'one-elements-editor-style', ONE_ELEMENTS_FRONTEND_URL . 'css/'.$one_elements_editor . '.css' );

	}

	/**
	 * Register frontend editor scripts for elementor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_frontend_scripts() {

		$one_elements_editor = 'one-elements-editor';
		$one_elements_editor = ( SCRIPT_DEBUG ) ? $one_elements_editor : $one_elements_editor.'.min';

		wp_register_script('one-elements-editor-js', ONE_ELEMENTS_FRONTEND_URL . 'js/'.$one_elements_editor.'.js', ['jquery'], ONE_ELEMENTS_VERSION,true );

	}

	/**
	 * Enqueue frontend editor scripts for elementor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script( 'one-elements-editor-js');
	}

	/**
	 * Custom contact methods.
	 *
	 * @param  array $methods Old contact methods.
	 *
	 * @return array          New contact methods.
	 */
	public function contact_methods( $methods ) {
		
		// Add new methods.
		$methods['facebook']   = __( 'Facebook', 'one-elements' );
		$methods['twitter']    = __( 'Twitter', 'one-elements' );
		$methods['github']     = __( 'github', 'one-elements' );
		$methods['linkedin']   = __( 'LinkedIn', 'one-elements' );
		$methods['flickr']     = __( 'Flickr', 'one-elements' );
		$methods['tumblr']     = __( 'Tumblr', 'one-elements' );
		$methods['vimeo']      = __( 'Vimeo', 'one-elements' );
		$methods['youtube']    = __( 'YouTube', 'one-elements' );
		$methods['instagram']  = __( 'Instagram', 'one-elements' );
		$methods['pinterest']  = __( 'Pinterest', 'one-elements' );
		$methods['wordpress']  = __( 'WordPress', 'one-elements' );

		return $methods;

	}

}

endif;