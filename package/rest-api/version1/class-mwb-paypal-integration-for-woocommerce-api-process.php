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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Mwb_Paypal_Integration_For_Woocommerce_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      1.0.0
	 * @package    Mwb_PayPal_Integration_for_WooCommerce
	 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes
	 */
	class Mwb_Paypal_Integration_For_Woocommerce_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    1.0.0
		 * @param   Array $mpifw_request  data of requesting headers and other information.
		 * @return  Array $mwb_mpifw_rest_response    returns processed data and status of operations.
		 */
		public function mwb_mpifw_default_process( $mpifw_request ) {
			$mwb_mpifw_rest_response = array();

			// Write your custom code here.

			$mwb_mpifw_rest_response['status'] = 200;
			$mwb_mpifw_rest_response['data']   = $mpifw_request->get_headers();
			return $mwb_mpifw_rest_response;
		}
	}
}
