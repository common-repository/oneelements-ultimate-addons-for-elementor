<?php
namespace OneElements\Includes\Widgets\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use OneElements\Includes\Widgets\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_ID extends Tag {
	public function get_name() {
		return 'post-id';
	}

	public function get_title() {
		return __( 'Post ID', 'one-elements' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo get_the_ID();
	}
}
