<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/admin/partials
 */

if (! defined('ABSPATH') ) {

	exit(); // Exit if accessed directly.
}
?>
<div class="mwb-gateway-setting-redirect-url">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=mwb_paypal' ) ); ?>"><?php esc_html_e( 'Go to Gateway Setting', 'mwb-paypal-integrtaion-for-woocommerce' ); ?></a>
</div>
