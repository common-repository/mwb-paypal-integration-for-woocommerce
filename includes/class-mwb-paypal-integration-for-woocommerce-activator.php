<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes
 */
class Mwb_Paypal_Integration_For_Woocommerce_Activator {

	/**
	 * Activaor main function.
	 *
	 * Will update default value at plugin installation time.
	 *
	 * @since 1.0.0
	 */
	public static function mwb_paypal_integration_for_woocommerce_activate() {
		wp_clear_scheduled_hook( 'makewebbetter_tracker_send_event' );
		wp_schedule_event( time() + 10, apply_filters( 'makewebbetter_tracker_event_recurrence', 'daily' ), 'makewebbetter_tracker_send_event' );
	}

}
