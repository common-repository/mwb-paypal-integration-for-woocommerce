<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/package/rest-api/version1
 */
class Mwb_Paypal_Integration_For_Woocommerce_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var   string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    1.0.0
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the Mwb_Paypal_Integration_For_Woocommerce_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function mwb_mpifw_add_endpoint() {
		register_rest_route(
			'mpifw-route/v1',
			'/mpifw-dummy-data/',
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'mwb_mpifw_default_callback' ),
				'permission_callback' => array( $this, 'mwb_mpifw_default_permission_check' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function mwb_mpifw_default_permission_check( $request ) {
		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $mwb_mpifw_response   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function mwb_mpifw_default_callback( $request ) {

		require_once MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'package/rest-api/version1/class-mwb-paypal-integration-for-woocommerce-api-process.php';
		$mwb_mpifw_api_obj     = new Mwb_Paypal_Integration_For_Woocommerce_Api_Process();
		$mwb_mpifw_resultsdata = $mwb_mpifw_api_obj->mwb_mpifw_default_process( $request );
		if ( is_array( $mwb_mpifw_resultsdata ) && isset( $mwb_mpifw_resultsdata['status'] ) && 200 === (int) $mwb_mpifw_resultsdata['status'] ) {
			unset( $mwb_mpifw_resultsdata['status'] );
			$mwb_mpifw_response = new WP_REST_Response( $mwb_mpifw_resultsdata, 200 );
		} else {
			$mwb_mpifw_response = new WP_Error( $mwb_mpifw_resultsdata );
		}
		return $mwb_mpifw_response;
	}
}
