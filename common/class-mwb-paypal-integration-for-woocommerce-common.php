<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/common
 */

/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace mwb_paypal_integration_for_woocommerce_common.
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/common
 */
class Mwb_Paypal_Integration_For_Woocommerce_Common {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mpifw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'common/css/mwb-paypal-integration-for-woocommerce-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function mpifw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'common/js/mwb-paypal-integration-for-woocommerce-common.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name . 'common', 'mpifw_common_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'copied' => __( 'copied', 'mwb-paypal-integration-for-woocommerce' ) ) );
		wp_enqueue_script( $this->plugin_name . 'common' );
	}

	/**
	 * Function is used for the sending the track data.
	 * 
	 * @param boolean $override mpifw_makewebbetter_tracker_send_event.
	 * @since 1.0.0
	 * @return void
	*/
	public function mpifw_makewebbetter_tracker_send_event( $override = false ) {
		require WC()->plugin_path() . '/includes/class-wc-tracker.php';

		$last_send = get_option('makewebbetter_tracker_last_send');
		if ( ! apply_filters( 'makewebbetter_tracker_send_override', $override ) ) {
			// Send a maximum of once per week by default.
			$last_send = $this->mwb_mpifw_last_send_time();
			if ( $last_send && $last_send > apply_filters( 'makewebbetter_tracker_last_send_interval', strtotime( '-1 week' ) ) ) {
				return;
			}
		} else {
			// Make sure there is at least a 1 hour delay between override sends, we don't want duplicate calls due to double clicking links.
			$last_send = $this->mwb_mpifw_last_send_time();
			if ( $last_send && $last_send > strtotime( '-1 hours' ) ) {
				return;
			}
		}
		// Update time first before sending to ensure it is set.
		update_option( 'makewebbetter_tracker_last_send', time() );
		$params  = WC_Tracker::get_tracking_data();
		$params  = apply_filters( 'makewebbetter_tracker_params' , $params );
		$api_url = 'http://demo.makewebbetter.com/wordpress-testing/wp-json/mpifw-route/v1/mpifw-testing-data/';
		$sucess  = wp_safe_remote_post(
			$api_url,
			array(
				'method'      => 'POST',
				'body'        => wp_json_encode( $params ),
			)
		);
	}

	/**
	 * Get the updated time.
	 * 
	 * @since 1.0.0
	 * @return void
	*/
	public function mwb_mpifw_last_send_time() {
		return apply_filters( 'makewebbetter_tracker_last_send_time', get_option( 'makewebbetter_tracker_last_send', false ) );
	}

	/**
	 * Update the option for settings from the multistep form.
	 * 
	 * @name mwb_standard_save_settings_filter
	 * @since 1.0.0
	*/
	public function mwb_standard_save_settings_filter() {
		check_ajax_referer( 'ajax-nonce', 'nonce' );

		$term_accpted = ! empty( $_POST['consetCheck'] ) ? sanitize_text_field( wp_unslash( $_POST['consetCheck'] ) ) : ' ';
		if ( ! empty( $term_accpted ) && 'yes' === $term_accpted ) {
			update_option( 'mpifw_enable_tracking', 'on' );
		}

		//settings fields.
		$first_name = ! empty( $_POST['firstName'] ) ? sanitize_text_field( wp_unslash( $_POST['firstName'] ) ) : '';
		update_option( 'firstname', $first_name );

		$email = ! empty( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		update_option( 'email', $email );

		$desc = ! empty( $_POST['desc'] ) ? sanitize_text_field( wp_unslash( $_POST['desc'] ) ) : '';
		update_option( 'desc', $desc );

		$age = ! empty( $_POST['age'] ) ? sanitize_text_field( wp_unslash( $_POST['age'] ) ) : '';
		update_option( 'age', $age );

		$first_checkbox = ! empty( $_POST['FirstCheckbox'] ) ? sanitize_text_field( wp_unslash( $_POST['FirstCheckbox'] ) ) : '';
		update_option( 'first_checkbox', $first_checkbox );

		$checked_first_switch = ! empty( $_POST['checkedA'] ) ? sanitize_text_field( wp_unslash( $_POST['checkedA'] ) ) : '';
		if ( ! empty( $checked_first_switch ) && $checked_first_switch ) {
			update_option( 'mpifw_radio_switch_demo', 'on' );
		}

		$checked_second_switch = ! empty( $_POST['checkedB'] ) ? sanitize_text_field( wp_unslash( $_POST['checkedB'] ) ) : '';
		if ( ! empty( $checked_second_switch ) &&  $checked_second_switch ) {
			update_option( 'mpifw_radio_reset_license', 'on' );
		}

		update_option( 'mpifw_plugin_standard_multistep_done', 'yes' );
		wp_send_json( 'yes' );
	}

	/**
	 * Extending main WC_Payment_Gateway class.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mpifw_extend_wc_payment_gateway_class() {
		require_once MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'includes/paypal/class-wc-gateway-mwb-paypal-integration.php';
	}

	/**
	 * Load custom payment gateway class.
	 *
	 * @param array $methods array containing the payment methods in WooCommerce.
	 * @since 1.0.0
	 * @return array
	 */
	public function mpifw_load_wc_payment_gateway_extended_class( $methods ) {
		$methods[] = 'WC_Gateway_Mwb_Paypal_Integration';
		return $methods;
	}
}
