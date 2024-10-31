<?php
namespace OneElements\Includes\Widgets\DynamicTags;

use Elementor\Modules\DynamicTags\Module as TagsModule;
//use OneElements\Includes\Widgets\DynamicTags\ACF;
//use OneElements\Includes\Widgets\DynamicTags\Toolset;
//use OneElements\Includes\Widgets\DynamicTags\Pods;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$tags_path = ONE_ELEMENTS_WIDGET_PATH . 'dynamic-tags/tags/';
include_once $tags_path . 'archive-description.php';
include_once $tags_path . 'archive-meta.php';
include_once $tags_path . 'archive-title.php';
include_once $tags_path . 'archive-url.php';
include_once $tags_path . 'author-info.php';
include_once $tags_path . 'author-meta.php';
include_once $tags_path . 'author-name.php';
include_once $tags_path . 'author-profile-picture.php';
include_once $tags_path . 'author-url.php';
include_once $tags_path . 'comments-number.php';
include_once $tags_path . 'comments-url.php';
include_once $tags_path . 'contact-url.php';
include_once $tags_path . 'current-date-time.php';
include_once $tags_path . 'featured-image-data.php';
include_once $tags_path . 'internal-url.php';
include_once $tags_path . 'lightbox.php';
include_once $tags_path . 'page-title.php';
include_once $tags_path . 'post-custom-field.php';
include_once $tags_path . 'post-date.php';
include_once $tags_path . 'post-excerpt.php';
include_once $tags_path . 'post-featured-image.php';
include_once $tags_path . 'post-gallery.php';
include_once $tags_path . 'post-id.php';
include_once $tags_path . 'post-terms.php';
include_once $tags_path . 'post-time.php';
include_once $tags_path . 'post-title.php';
include_once $tags_path . 'post-url.php';
include_once $tags_path . 'request-parameter.php';
include_once $tags_path . 'shortcode.php';
include_once $tags_path . 'site-logo.php';
include_once $tags_path . 'site-tagline.php';
include_once $tags_path . 'site-title.php';
include_once $tags_path . 'site-url.php';


class Module extends TagsModule {

	const AUTHOR_GROUP = 'author';

	const POST_GROUP = 'post';

	const COMMENTS_GROUP = 'comments';

	const SITE_GROUP = 'site';

	const ARCHIVE_GROUP = 'archive';

	const REQUEST_GROUP = 'request';

	const MEDIA_GROUP = 'media';

	const ACTION_GROUP = 'action';

	public function __construct() {
		parent::__construct();
		//@TODO; later integrate ACF and Pods
		//// ACF 5 and up
		//if ( class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
		//	$this->add_component( 'acf', new ACF\Module() );
		//}
		//
		//if ( function_exists( 'wpcf_admin_fields_get_groups' ) ) {
		//	$this->add_component( 'toolset', new Toolset\Module() );
		//}
		//
		//if ( function_exists( 'pods' ) ) {
		//	$this->add_component( 'pods', new Pods\Module() );
		//}
	}

	public function get_name() {
		return 'tags';
	}

	public function get_tag_classes_names() {
		return [
			'Archive_Description',
			'Archive_Meta',
			'Archive_Title',
			'Archive_URL',
			'Author_Info',
			'Author_Meta',
			'Author_Name',
			'Author_Profile_Picture',
			'Author_URL',
			'Comments_Number',
			'Comments_URL',
			'Page_Title',
			'Post_Custom_Field',
			'Post_Date',
			'Post_Excerpt',
			'Post_Featured_Image',
			'Post_Gallery',
			'Post_ID',
			'Post_Terms',
			'Post_Time',
			'Post_Title',
			'Post_URL',
			'Site_Logo',
			'Site_Tagline',
			'Site_Title',
			'Site_URL',
			'Internal_URL',
			'Current_Date_Time',
			'Request_Parameter',
			'Lightbox',
			'Featured_Image_Data',
			'Shortcode',
			'Contact_URL',
		];
	}

	public function get_groups() {
		return [
			self::POST_GROUP => [
				'title' => __( 'Post', 'one-elements' ),
			],
			self::ARCHIVE_GROUP => [
				'title' => __( 'Archive', 'one-elements' ),
			],
			self::SITE_GROUP => [
				'title' => __( 'Site', 'one-elements' ),
			],
			self::MEDIA_GROUP => [
				'title' => __( 'Media', 'one-elements' ),
			],
			self::ACTION_GROUP => [
				'title' => __( 'Actions', 'one-elements' ),
			],
			self::AUTHOR_GROUP => [
				'title' => __( 'Author', 'one-elements' ),
			],
			self::COMMENTS_GROUP => [
				'title' => __( 'Comments', 'one-elements' ),
			],
		];
	}
}

new Module();