<?php
/**
 * The request class for paypal payments.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes/paypal
 */

/**
 * Handles all api requests for paypal api.
 *
 * @since 1.0.0
 */
class Mwb_Paypal_Requests {

	/**
	 * Client id.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public static $client_id;

	/**
	 * Client secret.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public static $client_secret;

	/**
	 * Either sanbox mode or not.
	 *
	 * @var boolean
	 * @since 1.0.0
	 */
	public static $testmode;

	/**
	 * Current payment method.
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $payment_method;

	/**
	 * Constructor for request class.
	 *
	 * @param object $payment_method payment method.
	 * @since 1.0.0
	 */
	public function __construct( $payment_method ) {
		$this->payment_method = $payment_method;
	}

	/**
	 * Get access token for paypal REST APIs.
	 *
	 * @since  1.0.0
	 * @throws Exception
	 * @return array
	 */
	public static function get_access_token() {
		$endpoint = self::$testmode ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';

		try {
			$response = wp_remote_post(
				$endpoint . '/v1/oauth2/token',
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Accept' => 'application/json',
						'Accept-Language' => 'en_US',
						'Authorization'   => 'Basic ' . base64_encode( self::$client_id . ':' . self::$client_secret ),
					),
					'body' => array(
						'grant_type' => 'client_credentials',
					),
				)
			);
			if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
				$response = json_decode( wp_remote_retrieve_body( $response ) );
				return array(
					'result'       => 'success',
					'access_token' => isset( $response->access_token ) ? $response->access_token : '',
					'app_id'       => isset( $response->app_id ) ? $response->app_id : '',
				);
			}
			throw new Exception( __( 'Unable to generate access token', 'mwb-paypal-integration-for-woocommerce' ) );
			
		} catch ( Exception $e ) {
			return array(
				'result' => 'error',
				'msg'    => $e->getMessage(),
			);
		}		
	}

	/**
	 * Paypal create order.
	 *
	 * @param WC_Order $order current order object.
	 * @since 1.0.0
	 * @throws Exception
	 * @return array
	 */
	public function paypal_create_order( $order ) {
		$access_response = self::get_access_token();
		if ( 'success' !== $access_response['result'] ) {
			return array(
				'result'   => 'error',
				'redirect' => '',
			);
		}

		try {
			$endpoint = self::$testmode ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
			$response = wp_remote_post(
				$endpoint . '/v2/checkout/orders',
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Authorization' => 'Bearer ' . $access_response['access_token'],
						'Content-Type'  => 'application/json',
					),
					'body'        => wp_json_encode(
						array(
							'intent'         => 'CAPTURE',
							'purchase_units' => array(
								$this->get_purchase_details( $order ),
							),
							'application_context' => array(
								'brand_name'          => get_bloginfo( 'name' ),
								'return_url'          => $order->get_checkout_order_received_url(),
								'cancel_url'          => wc_get_checkout_url(),
								'landing_page'        => 'NO_PREFERENCE',
								'shipping_preference' => 'SET_PROVIDED_ADDRESS',
								'user_action'         => 'PAY_NOW',
							),
						)
					)
				)
			);

			if ( ! is_wp_error( $response ) && in_array( (int) wp_remote_retrieve_response_code( $response ), array( 200, 201, 202, 204 ), true ) ) {
				$response = json_decode( wp_remote_retrieve_body( $response ) );
				if ( isset( $response->links ) ) {
					foreach ( $response->links as $link ) {
						if ( 'approve' === $link->rel ) {
							return array(
								'result'   => 'success',
								'redirect' => $link->href,
							);
						}
					}
				}
			}

			throw new Exception( __( 'Unable to create order', 'mwb-paypal-integration-for-woocommerce' ) . wp_remote_retrieve_body( $response ) );
		} catch ( Exception $e ) {
			return array(
				'result'    => 'error',
				'error_msg' => $e->getMessage(),
			);
		}
	}

	/**
	 * Get purchase details.
	 *
	 * @param WC_Order $order current order object.
	 * @since 1.0.0
	 * @return array
	 */
	public function get_purchase_details( $order ) {
		$purchase_details = array(
			'reference_id'                    => 'MwbPaypal',
			'custom_id'                       => $this->limit_length( $order->get_order_number() ),
			'invoice_id'                      => $this->limit_length( $this->payment_method->get_option( 'invoice_prefix' ) . $order->get_order_number() ),
			'payee_payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
			'items'                           => $this->get_item_details( $order ),
			'shipping'                        => $this->get_shipping_details( $order ),
			'amount'                          => array(
				'value'         => $order->get_total(),
				'currency_code' => $order->get_currency(),
				'breakdown'     => array(
					'item_total' => array(
						'value'         => $this->limit_length( $order->get_subtotal(), 32 ),
						'currency_code' => $this->limit_length( $order->get_currency(), 3 ),
					),
					'shipping'   => array(
						'value'         => $this->limit_length( $order->get_shipping_total(), 32 ),
						'currency_code' => $this->limit_length( $order->get_currency(), 3 ),
					),
					'tax_total'  => array(
						'value'         => $this->limit_length( $order->get_total_tax(), 32 ),
						'currency_code' => $this->limit_length( $order->get_currency(), 3 ),
					),
					'discount'   => array(
						'value'         => $this->limit_length( $order->get_total_discount(), 32 ),
						'currency_code' => $this->limit_length( $order->get_currency(), 3 ),
					),
				),
			),
		);

		return $purchase_details;
	}

	/**
	 * Get line item details.
	 *
	 * @param WC_Order $order current order object.
	 * @since 1.0.0
	 * @return array
	 */
	public function get_item_details( $order ) {
		$items        = $order->get_items();
		$item_details = array();
		foreach ( $items as $item ) {
			$item_details[] = array(
				'name'        => $this->limit_length( $item->get_name() ),
				'unit_amount' => array(
					'currency_code' => $order->get_currency(),
					'value'         => ( $item->get_total() ) / ( $item->get_quantity() ),
				),
				'quantity' => $item->get_quantity(),
			);
		}
		return $item_details;
	}

	/**
	 * Get order shipping details to send at paypal.
	 *
	 * @param WC_Order $order current WC_Order order object.
	 * @since 1.0.0
	 * @return array
	 */
	public function get_shipping_details( $order ) {
		return array(
			'name'    => array(
				'full_name' => ! empty( $order->get_formatted_billing_full_name() ) ? $order->get_formatted_billing_full_name() : ( ! empty( $order->get_formatted_billing_full_name() ) ? $order->get_formatted_billing_full_name() : '*******' )
			),
			'type'    => ( 'local_pickup' === $order->get_shipping_method() ) ? 'PICKUP_IN_PERSON' : 'SHIPPING',
			'address' => array(
				'address_line_1' => $this->limit_length( ! empty( $order->get_shipping_address_1() ) ? $order->get_shipping_address_1() : ( ! empty( $order->get_billing_address_1() ) ? $order->get_billing_address_1() : '******' ), 300 ),
				'address_line_2' => $this->limit_length( ! empty( $order->get_shipping_address_2() ) ? $order->get_shipping_address_2() : ( ! empty( $order->get_billing_address_2() ) ? $order->get_billing_address_2() : '******' ), 300 ),
				'admin_area_2'   => $this->limit_length( ! empty( $order->get_shipping_city() ) ? $order->get_shipping_city() : ( ! empty( $order->get_billing_city() ) ? $order->get_billing_city() : '****' ), 120 ),
				'admin_area_1'   => $this->limit_length( ! empty( $order->get_shipping_state() ) ? $order->get_shipping_state() : ( ! empty( $order->get_billing_state() ) ? $order->get_billing_state() : '*****' ), 300 ),
				'postal_code'    => $this->limit_length( ! empty( $order->get_shipping_postcode() ) ? $order->get_shipping_postcode() : ( ! empty( $order->get_billing_postcode() ) ? $order->get_billing_postcode() : '000000' ), 60 ),
				'country_code'   => ! empty( $order->get_shipping_country() ) ? $order->get_shipping_country() : ( ! empty( $order->get_billing_country() ) ? $order->get_billing_country() : '**' ),
			),
		);
	}

	/**
	 * Limit length of the string.
	 *
	 * @param string  $string string to crop.
	 * @param integer $limit limit of the length of the string.
	 * @since 1.0.0
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		$str_limit = $limit - 3;
		if ( function_exists( 'mb_strimwidth' ) ) {
			if ( mb_strlen( $string ) > $limit ) {
				$string = mb_strimwidth( $string, 0, $str_limit ) . '...';
			}
		} else {
			if ( strlen( $string ) > $limit ) {
				$string = substr( $string, 0, $str_limit ) . '...';
			}
		}
		return $string;
	}

	/**
	 * Capture payment for the current order.
	 *
	 * @param object  $order current order object.
	 * @param integer $paypal_order_id paypal order id.
	 * @throws Exception
	 * @since 1.0.0
	 * @return array
	 */
	public static function do_capture( $order, $paypal_order_id ) {
		$access_response = self::get_access_token();
		if ( 'success' !== $access_response['result'] ) {
			return array(
				'result'   => 'error',
				'response' => '',
			);
		}

		try {
			$endpoint = self::$testmode ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
			$response = wp_remote_post(
				$endpoint . '/v2/checkout/orders/' . $paypal_order_id . '/capture',
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(
						'Authorization' => 'Bearer ' . $access_response['access_token'],
						'Content-Type'  => 'application/json',
					),
				)
			);
			if ( ! is_wp_error( $response ) && in_array( (int) wp_remote_retrieve_response_code( $response ), array( 200, 201 ), true ) ) {
				return array(
					'result'   => 'success',
					'response' => json_decode( wp_remote_retrieve_body( $response ) ),
				);
			}
			throw new Exception( __( 'Unable to capture the payment : response', 'mwb-paypal-integration-for-woocommerce' ) );

		} catch ( Exception $e ) {
			return array(
				'result'   => 'error',
				'response' => $e->getMessage(),
			);
		}
	}

	/**
	 * Refund order from paypal.
	 *
	 * @param object $order current order object.
	 * @param float  $amount amount to refund.
	 * @param string $reason reason for refund.
	 * @throws Exception
	 * @since 1.0.0
	 * @return array
	 */
	public static function refund_order( $order, $amount, $reason ) {
		$access_response = self::get_access_token();
		if ( 'success' !== $access_response['result'] ) {
			return array(
				'result'   => 'error',
				'response' => '',
			);
		}

		$endpoint = $order->get_meta( '_mwb_paypal_refund_link' );

		try {
			if ( ! empty( $endpoint ) ) {
				$response = wp_remote_post(
					$endpoint,
					array(
						'method'      => 'POST',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(
							'Authorization' => 'Bearer ' . $access_response['access_token'],
							'Content-Type'  => 'application/json',
						),
						'body' => wp_json_encode(
							array(
								'amount' => array(
									'value'         => $amount,
									'currency_code' => $order->get_currency(),
								),
								'note_to_payer' => strlen( $reason ) > 3 ? $reason : '***' ,
							)
						),
					)
				);

				if ( ! is_wp_error( $response ) && in_array( (int) wp_remote_retrieve_response_code( $response ), array( 201, 200 ), true ) ) {
					return array(
						'result'   => 'success',
						'response' => json_decode( wp_remote_retrieve_body( $response ) )
					);
				}

				throw new Exception( __( 'Error', 'mwb-paypal-integration-for-woocommerce' ) . wp_remote_retrieve_body( $response ) );
			}
		} catch ( Exception $e ) {
			return array(
				'result'   => 'error',
				'response' => $e->getMessage(),
			);
		}

		return array(
			'result'   => 'error',
			'response' => '',
		);
	}

	/**
	 * Verify IPN response.
	 *
	 * @param array $ipn_data array containing IPN data.
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function verify_ipn_response( $ipn_data ) {
		$ipn_data['cmd'] = '_notify-validate';
		$endpoint        = self::$testmode ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';
		$response        = wp_remote_post(
			$endpoint,
			array(
				'method'      => 'POST',
				'timeout'     => 60,
				'httpversion' => '1.1',
				'blocking'    => true,
				'compress'    => false,
				'decompress'  => false,
				'headers'     => array(
					'User-Agent' => 'WooCommerce/' . WC()->version,
				),
				'body'        => $ipn_data,
			)
		);

		if ( ! is_wp_error( $response ) && (int) wp_remote_retrieve_response_code( $response ) >= 200 && (int) wp_remote_retrieve_response_code( $response ) < 300 ) {
			return ( 'VERIFIED' === wp_remote_retrieve_body( $response ) ) ? true : false;
		}
		return false;

	}
}
