<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://makewebbetter.com/
 * @since   1.0.0
 * @package Mwb_PayPal_Integration_for_WooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:       MWB PayPal Integration for WooCommerce
 * Plugin URI:        https://makewebbetter.com/mwb-paypal-integration-for-woocommerce
 * Description:       Adds PayPal as a payment gateway so that customers will have an option to pay for their orders using PayPal.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/?utm_source=MWB-paypal-backend&utm_medium=MWB-org-backend&utm_campaign=MWB-paypal-backend
 * Text Domain:       mwb-paypal-integration-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least:    4.6
 * Tested up to:         5.8.1
 * WC requires at least: 4.0.0
 * WC tested up to:      5.8.0
 * Stable tag:           1.0.0
 * Requires PHP:         7.2
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (! defined('ABSPATH') ) {
	die;
}
if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true ) || ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
	/**
	 * Define plugin constants.
	 *
	 * @since 1.0.0
	 */
	function define_mwb_paypal_integration_for_woocommerce_constants() {
		mwb_paypal_integration_for_woocommerce_constants( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
		mwb_paypal_integration_for_woocommerce_constants( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		mwb_paypal_integration_for_woocommerce_constants( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		mwb_paypal_integration_for_woocommerce_constants( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
		mwb_paypal_integration_for_woocommerce_constants( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'MWB PayPal Integration for WooCommerce' );
	}

	if ( ! function_exists( 'mwb_paypal_integration_for_woocommerce_check_multistep' ) ) {

		/**
		 * Check if default form is filled by user.
		 *
		 * @since 1.0.0
		 * @return boolean
		 */
		function mwb_paypal_integration_for_woocommerce_check_multistep() {
			$bool = false;
			$mwb_paypal_integration_for_woocommerce_check = get_option( 'mpifw_plugin_standard_multistep_done', false );
			if ( ! empty( $mwb_paypal_integration_for_woocommerce_check) ) {
				$bool = true;
			}
			$bool = apply_filters( 'mwb_paypal_integration_for_woocommerce_multistep_done', $bool );
			return $bool;
		}
	}

	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param String $key   Key for contant.
	 * @param String $value value for contant.
	 * @since 1.0.0
	 */
	function mwb_paypal_integration_for_woocommerce_constants( $key, $value ) {
		if ( ! defined( $key ) ) {
			define($key, $value);
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-mwb-paypal-integration-for-woocommerce-activator.php
	 */
	function activate_mwb_paypal_integration_for_woocommerce() {

		include_once plugin_dir_path(__FILE__) . 'includes/class-mwb-paypal-integration-for-woocommerce-activator.php';
		Mwb_Paypal_Integration_For_Woocommerce_Activator::mwb_paypal_integration_for_woocommerce_activate();
		$mwb_mpifw_active_plugin = get_option('mwb_all_plugins_active', false);
		if (is_array($mwb_mpifw_active_plugin) && ! empty($mwb_mpifw_active_plugin) ) {
			$mwb_mpifw_active_plugin['mwb-paypal-integration-for-woocommerce'] = array(
				'plugin_name' => __( 'MWB PayPal Integration for WooCommerce', 'mwb-paypal-integration-for-woocommerce'),
				'active'      => '1',
			);
		} else {
			$mwb_mpifw_active_plugin = array();
			$mwb_mpifw_active_plugin['mwb-paypal-integration-for-woocommerce'] = array(
				'plugin_name' => __( 'MWB PayPal Integration for WooCommerce', 'mwb-paypal-integration-for-woocommerce' ),
				'active'      => '1',
			);
		}
		update_option( 'mwb_all_plugins_active', $mwb_mpifw_active_plugin );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-mwb-paypal-integration-for-woocommerce-deactivator.php
	 */
	function deactivate_mwb_paypal_integration_for_woocommerce() {
		include_once plugin_dir_path(__FILE__) . 'includes/class-mwb-paypal-integration-for-woocommerce-deactivator.php';
		Mwb_Paypal_Integration_For_Woocommerce_Deactivator::mwb_paypal_integration_for_woocommerce_deactivate();
		$mwb_mpifw_deactive_plugin = get_option('mwb_all_plugins_active', false);
		if (is_array($mwb_mpifw_deactive_plugin) && ! empty($mwb_mpifw_deactive_plugin) ) {
			foreach ( $mwb_mpifw_deactive_plugin as $mwb_mpifw_deactive_key => $mwb_mpifw_deactive ) {
				if ( 'mwb-paypal-integration-for-woocommerce' === $mwb_mpifw_deactive_key ) {
					$mwb_mpifw_deactive_plugin[ $mwb_mpifw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option('mwb_all_plugins_active', $mwb_mpifw_deactive_plugin);
	}

	register_activation_hook( __FILE__, 'activate_mwb_paypal_integration_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_mwb_paypal_integration_for_woocommerce' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-mwb-paypal-integration-for-woocommerce.php';



	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 1.0.0
	 */
	function run_mwb_paypal_integration_for_woocommerce() {
		define_mwb_paypal_integration_for_woocommerce_constants();
		$mpifw_plugin_standard = new Mwb_PayPal_Integration_For_WooCommerce();
		$mpifw_plugin_standard->mpifw_run();
		$GLOBALS['mpifw_mwb_mpifw_obj'] = $mpifw_plugin_standard;

	}
	run_mwb_paypal_integration_for_woocommerce();


	// Add settings link on plugin page.
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mwb_paypal_integration_for_woocommerce_settings_link');

	/**
	 * Settings link.
	 *
	 * @since 1.0.0
	 * @param Array $links Settings link array.
	 */
	function mwb_paypal_integration_for_woocommerce_settings_link( $links ) {
		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=mwb_paypal_integration_for_woocommerce_menu' ) . '">' . __( 'Settings', 'mwb-paypal-integration-for-woocommerce' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}

	/**
	 * Adding custom setting links at the plugin activation list.
	 *
	 * @param  array  $links_array      array containing the links to plugin.
	 * @param  string $plugin_file_name plugin file name.
	 * @return array
	 */
	function mwb_paypal_integration_for_woocommerce_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
		if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
			$links_array[] = '<a href="https://demo.makewebbetter.com/get-personal-demo/mwb-paypal-integration-for-woocommerce?utm_source=MWB-paypal-backend&utm_medium=MWB-demo-org&utm_campaign=MWB-paypal-backend" target="_blank"><img src="' . esc_html(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Demo.svg" class="mwb-info-img" alt="Demo image">' . __('Demo', 'mwb-paypal-integration-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://docs.makewebbetter.com/mwb-paypal-integration-with-woocommerce/?utm_source=MWB-paypal-backend&utm_medium=MWB-doc-org&utm_campaign=MWB-paypal-backend" target="_blank"><img src="' . esc_html(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Documentation.svg" class="mwb-info-img" alt="documentation image">' . __('Documentation', 'mwb-paypal-integration-for-woocommerce') . '</a>';
			$links_array[] = '<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-paypal-backend&utm_medium=MWB-support-org&utm_campaign=MWB-paypal-backend" target="_blank"><img src="' . esc_html(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL) . 'admin/image/Support.svg" class="mwb-info-img" alt="support image">' . __('Support', 'mwb-paypal-integration-for-woocommerce') . '</a>';
		}
		return $links_array;
	}
	add_filter( 'plugin_row_meta', 'mwb_paypal_integration_for_woocommerce_custom_settings_at_plugin_tab', 10, 2 );

	add_action( 'activated_plugin', 'mwb_pifw_redirect_on_settings' );
	if ( ! function_exists( 'mwb_pifw_redirect_on_settings' ) ) {
		/**
		 * Redirect to form filling area.
		 *
		 * @param string $plugin current plugin name.
		 * @since 1.0.0
		 * @return void
		 */
		function mwb_pifw_redirect_on_settings ( $plugin ) {
			if ( plugin_basename( __FILE__ ) === $plugin ) {
				$general_settings_url = admin_url( 'admin.php?page=mwb_paypal_integration_for_woocommerce_menu' );
				wp_safe_redirect( esc_url( $general_settings_url ) );
				exit(); 
			}
		}
	}
} else {
	mwb_paypal_integration_for_woocommerce_dependency_checkup();
}

/**
 * Checking dependency for woocommerce plugin.
 *
 * @return void
 */
function mwb_paypal_integration_for_woocommerce_dependency_checkup() {
	add_action( 'admin_init', 'mwb_mpifw_deactivate_child_plugin' );
	add_action( 'admin_notices', 'mwb_mpifw_show_admin_notices' );
}

/**
 * Deactivating child plugin.
 *
 * @return void
 */
function mwb_mpifw_deactivate_child_plugin() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Showing admin notices.
 *
 * @return void
 */
function mwb_mpifw_show_admin_notices() {
	$mwb_mpifw_child_plugin  = __( 'MWB PayPal Integration for WooCommerce', 'mwb-paypal-integration-for-woocommerce' );
	$mwb_mpifw_parent_plugin = __( 'WooCommerce', 'mwb-paypal-integration-for-woocommerce' );
	echo '<div class="notice notice-error is-dismissible"><p>'
		/* translators: %s: dependency checks */
		. sprintf( esc_html__( '%1$s requires %2$s to function correctly. Please activate %2$s before activating %1$s. For now, the plugin has been deactivated.', 'mwb-paypal-integration-for-woocommerce' ), '<strong>' . esc_html( $mwb_mpifw_child_plugin ) . '</strong>', '<strong>' . esc_html( $mwb_mpifw_parent_plugin ) . '</strong>' )
		. '</p></div>';
	if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
		unset( $_GET['activate'] ); //phpcs:ignore
	}
}
