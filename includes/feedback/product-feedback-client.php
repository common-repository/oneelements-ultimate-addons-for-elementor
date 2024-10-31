<?php
namespace OneElements\Includes\Feedback;
/**
 * This is the class that sends all the data back to the home site
 * It also handles opting in and deactivation
 * @version 1.0.0
 */
if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly.

class ProductFeedbackClient
{

	private $txc_insight_version = '1.0.0';
	private $home_url = 'https://txc.local/wp-json/product-feedback/v1/create'; // @TODO; remove after testing
	private $plugin_file = '';

	private $plugin_slug;

	/**
	 * Class constructor
	 *
	 * @param string $_home_url                The URL to the site we're sending data to
	 * @param string $_plugin_file               The file path for this plugin

	 */
	public function __construct( $_plugin_file, $_home_url) {
		$this->plugin_file = $_plugin_file;
		$this->home_url = trailingslashit($_home_url);
		$this->plugin_slug = basename($this->plugin_file, '.php');

		// Deactivation hook
		register_deactivation_hook($this->plugin_file, array($this, 'deactivate_this_plugin'));

		// Deactivation
		add_filter('plugin_action_links_' . plugin_basename($this->plugin_file), array($this, 'filter_action_links'));
		add_action('admin_footer-plugins.php', array($this, 'goodbye_ajax_form'));
		add_action('wp_ajax_goodbye_form_' . esc_attr($this->plugin_slug), array($this, 'goodbye_form_callback'));

	}




	/**
	 * This is our function to get everything going
	 * Check that user has opted in
	 * Collect data
	 * Then send it back
	 *
	 * @param bool $force Force tracking if it's not time
	 *
	 * @since 1.0.0
	 */
	public function do_tracking($force = false) {
		if (!$this->home_url) return;
		// Check to see if it's time to track
		if (!$this->get_is_time_to_track() && !$force) return; // @TODO: uncomment after test
		// Get our data
		$body = $this->get_tracking_data();
		// Send the data
		$this->send_tracking_data($body);
	}

	/**
	 * Send the data to the home site
	 *
	 * @param $body
	 *
	 * @return bool|\WP_Error
	 * @since 1.0.0
	 */
	public function send_tracking_data($body)
	{
		$response = wp_remote_post( esc_url($this->home_url),
			array(
				'method' => 'POST',
				'timeout' => 30,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking' => false,
				'body' => $body,
				'user-agent' => 'ProductFeedback/1.0.0; ' . get_bloginfo('url'),
			)
		);

		$this->set_last_time_tracked();

		if (is_wp_error($response)) {
			return $response;
		}
		return true;

	}

	/**
	 * Here we collect most of the data
	 *
	 * @since 1.0.0
	 */
	public function get_tracking_data()
	{
		global $wpdb;

		// Use this to pass error messages back if necessary
		$body['message'] = '';

		// Use this array to send data back
		$body = array(
			'hash' => '2c67e23b1c755f2aa323b6873518ea95bf732d8f', // this is a basic sha1 security hash to prevent abuse
			'product_slug' => sanitize_text_field($this->plugin_slug),
			'url' => get_bloginfo('url'),
			'admin_email' => get_option( 'admin_email' ),
			'site_name' => $this->get_site_name(),
			'site_language' => get_bloginfo('language'),
			'charset' => get_bloginfo('charset'),
			'txc_insight_version' => $this->txc_insight_version,
			'php_version' => phpversion(),
			'mysql_version' => $wpdb->db_version(),
			'php_max_upload_size'  => size_format( wp_max_upload_size() ),
			'wp_version' => get_bloginfo( 'version' ),
			'wp_memory_limit' => WP_MEMORY_LIMIT,
			'wp_debug' => ( defined('WP_DEBUG') && WP_DEBUG ),
			'multisite' => is_multisite(),
			'ip_address' => $this->get_user_ip_address(),
			'deactivated_timestamp' => get_option( 'txc_insight_deactivated_timestamp_' . $this->plugin_slug),
			'deactivation_reason' => get_option( 'txc_insight_deactivation_reason_' . $this->plugin_slug),
			'deactivation_details' => get_option( 'txc_insight_deactivation_details_' . $this->plugin_slug),
		);

		$body['server'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';

		// Retrieve current plugin information
		if (!function_exists('get_plugins')) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins = array_keys(get_plugins());
		$active_plugins = get_option('active_plugins', array());

		foreach ($plugins as $key => $plugin) {
			if (in_array($plugin, $active_plugins)) {
				// Remove active plugins from list so we can show active and inactive separately
				unset($plugins[$key]);
			}
		}

		$body['active_plugins'] = $active_plugins;
		$body['inactive_plugins'] = $plugins;

		$plugin = $this->plugin_data();
		if (empty($plugin)) {
			// We can't find the plugin data
			// Send a message back to our home site
			$body['message'] .= __('We can\'t detect any plugin information. This is most probably because you have not included the code in the plugin main file.', 'product-feedback-client');
			$body['status'] = 'Data not found'; // Never translated
		} else {
			if (isset($plugin['Name'])) {
				$body['product_name'] = sanitize_text_field($plugin['Name']);
			}
			if (isset($plugin['Version'])) {
				$body['product_version'] = sanitize_text_field($plugin['Version']);
			}
			$body['status'] = 'Active'; // Never translated
		}

		/**
		 * Get our theme data
		 * Currently we grab theme name and version
		 * @since 1.0.0
		 */
		$theme = wp_get_theme();
		if ($theme->Name) {
			$body['theme'] = sanitize_text_field($theme->Name);
		}
		if ($theme->Version) {
			$body['theme_version'] = sanitize_text_field($theme->Version);
		}

		// Return the data
		return $body;

	}

	/**
	 * Get user IP Address
	 */
	private function get_user_ip_address() {
		$response = wp_remote_get( 'https://api.ipify.org' ); // or https://icanhazip.com

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$ip = trim( wp_remote_retrieve_body( $response ) );

		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return '';
		}

		return $ip;
	}

	/**
	 * Get site name
	 */
	private function get_site_name() {
		$site_name = get_bloginfo( 'name' );

		if ( empty( $site_name ) ) {
			$site_name = get_bloginfo( 'description' );
			$site_name = wp_trim_words( $site_name, 3, '' );
		}

		if ( empty( $site_name ) ) {
			$site_name = get_bloginfo( 'url' );
		}

		return $site_name;
	}

	/**
	 * Return plugin data
	 * @since 1.0.0
	 */
	public function plugin_data()
	{
		// Being cautious here
		if (!function_exists('get_plugin_data')) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}
		// Retrieve current plugin information
		return get_plugin_data($this->plugin_file);

	}

	/**
	 * Deactivating plugin
	 * @since 1.0.0
	 */
	public function deactivate_this_plugin()
	{
		delete_option( $this->plugin_slug . '_last_time_tracked');
	}


	/**
	 * Check if it's time to track
	 * @since 1.0
	 */
	public function get_is_time_to_track()
	{
		// Let's see if we're due to track this plugin yet
		$last_send = $this->get_last_time_tracked();
		if ( $last_send && $last_send > strtotime( '-1 week' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Set the time we send tracking data
	 * @since 1.0
	 */
	public function set_last_time_tracked() {
		update_option( $this->plugin_slug . '_last_time_tracked', time() );
	}

	/**
	 * Get the time we send tracking data
	 * @since 1.0
	 */
	public function get_last_time_tracked() {
		return get_option( $this->plugin_slug . '_last_time_tracked' );
	}


	/**
	 * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
	 * @since 1.0.0
	 */
	public function filter_action_links($links)
	{
		if (isset($links['deactivate'])) {
			$deactivation_link = $links['deactivate'];

			// Insert an onClick action to allow form before deactivating
			$deactivation_link = str_replace('<a ', '<div class="txc-insight-goodbye-form-wrapper-' . esc_attr($this->plugin_slug) . '"><div class="txc-insight-goodbye-form-bg-' . esc_attr($this->plugin_slug) . '"></div><span class="txc-insight-goodbye-form" id="txc-insight-goodbye-form-' . esc_attr($this->plugin_slug) . '"></span></div><a onclick="javascript:event.preventDefault();" id="txc-insight-goodbye-link-' . esc_attr($this->plugin_slug) . '" ', $deactivation_link);
			$links['deactivate'] = $deactivation_link;
		}
		return $links;
	}

	/*
	 * Form text strings
	 * These are non-filterable and used as fallback in case filtered strings aren't set correctly
	 * @since 1.0.0
	 */
	public function get_form_text()
	{
		$form = array();
		$form['heading'] = __('Sorry to see you deactivating', 'product-feedback-client');
		$form['body'] = __('Before you deactivate the plugin, would you quickly tell us why you are deactivating?', 'product-feedback-client');

		$form['options'] = array(
			'no-longer-needed' => __('I no longer need the plugin', 'product-feedback-client'),
			'found-better-plugin' =>[
				'label' => __('I found a better plugin', 'product-feedback-client'),
				'extra_field' => __('Please tell us which plugin', 'product-feedback-client'),
			],
			'not-working' =>[
				'label' => __("I couldn't get the plugin to work", 'product-feedback-client'),
				'message' => sprintf( __('Please open a support ticket on your support forum, we will be very happy to assist you. You can open a ticket %s here %s', 'product-feedback-client'), '<a href="https://themexclub.com/support/" target="_blank">', '</a>' ),
				'extra_field' => __('Please tell us a little more what is not working..', 'product-feedback-client'),
				'type' => 'info',
			],
			'temporary-deactivation' => __('It\'s a temporary deactivation', 'product-feedback-client'),
			'missing-a-feature' => [
				'label' => __("It does not have a feature I am looking for.", 'product-feedback-client'),
				'extra_field' => __('Please tell us which feature is missing', 'product-feedback-client'),
				'type' => 'textarea',
			],
			'other' => [
				'label' => __('Other', 'product-feedback-client'),
				'extra_field' => __('Please tell us the reason so we can improve it', 'product-feedback-client'),
				'type' => 'textarea',
			],
		);

		return apply_filters('txc_insight_form_text_' . $this->plugin_slug, $form);
	}


	/**
	 * it prints goodbye html form
	 */
	public function goodbye_ajax_form()
	{
		$form_data = $this->get_form_text();
		// Build the HTML to go in the form
		$html = '<div class="txc-insight-goodbye-form-head"><strong>' . esc_html($form_data['heading']) . '</strong></div>';
		$html .= '<div class="txc-insight-goodbye-form-body"><p class="txc-insight-goodbye-form-caption">' . esc_html($form_data['body']) . '</p>';
		if (is_array($form_data['options'])) {
			$html .= '<div id="txc-insight-' . esc_attr($this->plugin_slug) . '-goodbye-options" class="txc-insight-' . esc_attr($this->plugin_slug) . '-goodbye-options"><ul>';
			foreach ($form_data['options'] as $key => $option) {
				$id = $key . '_' . $this->plugin_slug;
				if (is_array($option)) {
					$html .= '<li class="has-goodbye-extra">';
					$html .= '<input type="radio" name="txc-insight-' . esc_attr($this->plugin_slug) . '-goodbye-options" id="' . $id . '" value="' . esc_attr($key) . '">';
					$html .= '<div><label for="' . $id . '">' . esc_html($option['label']) . '</label>';
					if (isset($option['extra_field'])) {
					    if (!empty( $option['type'])){
					        switch ($option['type']){
                                case 'textarea':
	                                $html .= '<textarea style="display: none" type="text" name="' . $id . '" id="' . str_replace(" ", "", esc_attr($option['extra_field'])) . '" placeholder="' . esc_attr($option['extra_field']) . '"></textarea>';
                                break;
						        case 'info':
							        $html .= '<p style="display: none" class="txc-insight-info">'.one_kses_basic($option['message']).'</p>';
							        break;
                                case 'text':
                                default:
						        $html .= '<input type="text" style="display: none" name="' . $id . '" id="' . str_replace(" ", "", esc_attr($option['extra_field'])) . '" placeholder="' . esc_attr($option['extra_field']) . '">';
						        break;
					        }
					    }else{
						    $html .= '<input type="text" style="display: none" name="' . $id . '" id="' . str_replace(" ", "", esc_attr($option['extra_field'])) . '" placeholder="' . esc_attr($option['extra_field']) . '">';

					    }
					}

					$html .= '</div></li>';
				} else {
					$html .= '<li><input type="radio" name="txc-insight-' . esc_attr($this->plugin_slug) . '-goodbye-options" id="' . $id . '" value="' . esc_attr($key) . '"> <label for="' . $id . '">' . esc_html($option) . '</label></li>';
				}
			}
			$html .= '</ul></div><!-- .txc-insight-' . esc_html($this->plugin_slug) . '-goodbye-options -->';
		}
		$html .= '</div><!-- .txc-insight-goodbye-form-body -->';
		$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __('Please wait a bit! Submitting feedback & deactivating shortly.', 'product-feedback-client') . '</p>';
		?>
		<style type="text/css">
			.txc-insight-form-active-<?php echo esc_attr($this->plugin_slug); ?> .txc-insight-goodbye-form-bg-<?php echo esc_attr($this->plugin_slug); ?> {
				background: rgba( 0, 0, 0, .8 );
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				z-index: 9;
			}
			.txc-insight-goodbye-form-wrapper-<?php echo esc_attr($this->plugin_slug); ?> {
				position: relative;
				display: none;
			}
			.txc-insight-form-active-<?php echo esc_attr($this->plugin_slug); ?> .txc-insight-goodbye-form-wrapper-<?php echo esc_attr($this->plugin_slug); ?> {
				display: flex !important;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
				position: fixed;
				left: 0px;
				top: 0px;
			}
			.txc-insight-goodbye-form {
				display: none;
			}
			.txc-insight-form-active-<?php echo esc_attr($this->plugin_slug); ?> .txc-insight-goodbye-form {
				position: relative !important;
				width: 550px;
				max-width: 80%;
				background: #fff;
				box-shadow: 2px 8px 23px 3px rgba(0,0,0,.2);
				border-radius: 3px;
				white-space: normal;
				overflow: hidden;
				display: block;
				z-index: 999999;
			}
			.txc-insight-goodbye-form-head {
				background: #fff;
				color: #495157;
				padding: 18px;
				box-shadow: 0 0 8px rgba(0,0,0,.1);
				font-size: 15px;
			}
			.txc-insight-goodbye-form .txc-insight-goodbye-form-head strong {
				font-size: 15px;
			}
			.txc-insight-goodbye-form-body {
				padding: 8px 18px;
				color: #333;
			}
			.txc-insight-goodbye-form-body label {
				color: #6d7882;
				padding-left: 5px;
			}
			.txc-insight-goodbye-form-body .txc-insight-goodbye-form-caption {
				font-weight: 500;
				font-size: 15px;
				color: #495157;
				line-height: 1.4;
			}
			.txc-insight-goodbye-form-body #txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options {
				padding-top: 5px;
			}
			.txc-insight-goodbye-form-body #txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options ul > li {
				margin-bottom: 15px;
			}
			.deactivating-spinner {
				display: none;
				padding-bottom: 20px !important;
			}
			.deactivating-spinner .spinner {
				float: none;
				margin: 4px 4px 0 18px;
				vertical-align: bottom;
				visibility: visible;
			}
			.txc-insight-goodbye-form-footer {
				padding: 8px 18px;
				margin-bottom: 15px;
			}
			.txc-insight-goodbye-form-footer > .txc-insight-goodbye-form-buttons {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}
			.txc-insight-goodbye-form-footer .eael-put-submit-btn {
				background-color: #d30c5c;
				-webkit-border-radius: 3px;
				border-radius: 3px;
				color: #fff;
				line-height: 1;
				padding: 15px 20px;
				font-size: 13px;
			}
			.txc-insight-goodbye-form-footer .eael-put-deactivate-btn {
				font-size: 13px;
				color: #a4afb7;
				background: none;
				float: right;
				padding-right: 10px;
				width: auto;
				text-decoration: underline;
			}
			#txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options ul li > div {
				display: inline;
				padding-left: 3px;
			}
			#txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options ul li > div > input, #txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options ul li > div > textarea {
				margin: 10px 18px;
				padding: 8px;
				width: 80%;
			}
		</style>
		<script>
            jQuery(document).ready(function($){
                $("#txc-insight-goodbye-link-<?php echo esc_attr($this->plugin_slug); ?>").on("click",function(){
                    // We'll send the user to this deactivation link when they've completed or dismissed the form
                    let url = document.getElementById("txc-insight-goodbye-link-<?php echo esc_attr($this->plugin_slug); ?>");
                    $('body').toggleClass('txc-insight-form-active-<?php echo esc_attr($this->plugin_slug); ?>');
                    $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?>").fadeIn();
                    $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?>").html( '<?php echo $html; ?>' + '<div class="txc-insight-goodbye-form-footer"><div class="txc-insight-goodbye-form-buttons"><a id="put-submit-form-<?php echo esc_attr($this->plugin_slug); ?>" class="eael-put-submit-btn" href="#"><?php _e('Submit and Deactivate', 'product-feedback-client');?></a>&nbsp;<a class="eael-put-deactivate-btn" href="'+url+'"><?php _e('Just Deactivate', 'product-feedback-client');?></a></div></div>');
                    $('#put-submit-form-<?php echo esc_attr($this->plugin_slug); ?>').on('click', function(e){
                        // As soon as we click, the body of the form should disappear
                        $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?> .txc-insight-goodbye-form-body").fadeOut();
                        $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?> .txc-insight-goodbye-form-footer").fadeOut();
                        // Fade in spinner
                        $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?> .deactivating-spinner").fadeIn();
                        e.preventDefault();
                        let checkedInput = $("input[name='txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options']:checked"),
                            checkedInputVal, details;
                        if( checkedInput.length > 0 ) {
                            checkedInputVal = checkedInput.val();
                            details = $('input[name="'+ checkedInput[0].id +'"], textarea[name="'+ checkedInput[0].id +'"]').val();
                        }

                        if( typeof details === 'undefined' ) {
                            details = '';
                        }
                        if( typeof checkedInputVal === 'undefined' ) {
                            checkedInputVal = 'No Reason';
                        }

                        let data = {
                            'action': 'goodbye_form_<?php echo esc_attr($this->plugin_slug); ?>',
                            'values': checkedInputVal,
                            'details': details,
                            'security': "<?php echo wp_create_nonce('txc_insight_goodbye_form'); ?>",
                            'dataType': "json"
                        };

                        $.post(
                            ajaxurl,
                            data,
                            function(response){
                                // Redirect to original deactivation URL
                                // console.log(url);
                                window.location.href = url; //????@TODO;Ask vai how come it works fine!!!
                            }
                        );
                    });

                    // show the extra feedback input field
                    $('#txc-insight-<?php echo esc_attr($this->plugin_slug); ?>-goodbye-options > ul ').on('click', 'li label, li > input', function( e ){
                        var parent = $(this).parents('li');
                        parent.siblings().find('label').next('input, textarea, p').css('display', 'none');
                        parent.find('label').next('input, textarea, p').css('display', 'block');
                    });
                    // If we click outside the form, the form will close
                    $('.txc-insight-goodbye-form-bg-<?php echo esc_attr($this->plugin_slug); ?>').on('click',function(){
                        $("#txc-insight-goodbye-form-<?php echo esc_attr($this->plugin_slug); ?>").fadeOut();
                        $('body').removeClass('txc-insight-form-active-<?php echo esc_attr($this->plugin_slug); ?>');
                    });
                });


            });
		</script>
	<?php }

	/**
	 * AJAX callback when the form is submitted
	 * @since 1.0.0
	 */
	public function goodbye_form_callback()
	{
		check_ajax_referer('txc_insight_goodbye_form', 'security');
		update_option('txc_insight_deactivated_timestamp_' . $this->plugin_slug, time());

		if (isset($_POST['values'])) {
			$values = $_POST['values'];
			update_option('txc_insight_deactivation_reason_' . $this->plugin_slug, $values);
		}
		if (isset($_POST['details'])) {
			$details = sanitize_text_field($_POST['details']);
			update_option('txc_insight_deactivation_details_' . $this->plugin_slug, $details);
		}
		$this->do_tracking(); // Run this straightaway
		echo 'success';
		wp_die();
	}

}
