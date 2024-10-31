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

global $mpifw_mwb_mpifw_obj;
if ( ! mwb_paypal_integration_for_woocommerce_check_multistep() ) {
	?>
	<div id="react-app"></div>
	<?php
	return;
}
$mpifw_active_tab   = isset( $_GET['mpifw_tab'] ) ? sanitize_key( wp_unslash( $_GET['mpifw_tab'] ) ) : 'mwb-paypal-integration-for-woocommerce-gateway-setting'; // phpcs:ignore WordPress.Security.NonceVerification
$mpifw_default_tabs = $mpifw_mwb_mpifw_obj->mwb_mpifw_plug_default_tabs();
?>
<header>
	<?php
		// desc - Setting save notice.
		do_action( 'mwb_mpifw_settings_saved_notice' );
	?>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $mpifw_mwb_mpifw_obj->mpifw_get_plugin_name() ) ) ); ?></h1>
		<a href="https://docs.makewebbetter.com/mwb-paypal-integration-with-woocommerce/?utm_source=MWB-paypal-backend&utm_medium=MWB-doc-org&utm_campaign=MWB-paypal-backend" target="_blank" class="mwb-link"><?php esc_html_e('Documentation', 'mwb-paypal-integration-for-woocommerce'); ?></a>
		<span>|</span>
		<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-paypal-backend&utm_medium=MWB-support-org&utm_campaign=MWB-paypal-backend" target="_blank" class="mwb-link"><?php esc_html_e('Support', 'mwb-paypal-integration-for-woocommerce'); ?></a>
	</div>
</header>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if (is_array($mpifw_default_tabs) && ! empty($mpifw_default_tabs) ) {
				foreach ( $mpifw_default_tabs as $mpifw_tab_key => $mpifw_default_tabs ) {

					$mpifw_tab_classes = 'mwb-link ';
					if (! empty($mpifw_active_tab) && $mpifw_active_tab === $mpifw_tab_key ) {
						$mpifw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr($mpifw_tab_key); ?>" href="<?php echo esc_url(admin_url('admin.php?page=mwb_paypal_integration_for_woocommerce_menu') . '&mpifw_tab=' . esc_attr($mpifw_tab_key)); ?>" class="<?php echo esc_attr($mpifw_tab_classes); ?>"><?php echo esc_html($mpifw_default_tabs['title']); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="mwb-section">
		<div>
			<?php
			// desc - Before general setting form.
			do_action('mwb_mpifw_before_general_settings_form');
			// if submenu is directly clicked on woocommerce.
			if ( empty( $mpifw_active_tab ) ) {
				$mpifw_active_tab = 'mwb-paypal-integration-for-woocommerce-gateway-setting';
			}
			// look for the path based on the tab id in the admin templates.
			$mpifw_default_tabs     = $mpifw_mwb_mpifw_obj->mwb_mpifw_plug_default_tabs();
			$mpifw_tab_content_path = $mpifw_default_tabs[ $mpifw_active_tab ]['file_path'];
			$mpifw_mwb_mpifw_obj->mwb_mpifw_plug_load_template( $mpifw_tab_content_path );
			// desc - After general setting form.
			do_action('mwb_mpifw_after_general_settings_form');
			?>
		</div>
	</section>
