<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/onboarding/templates
 */

global $mpifw_mwb_mpifw_obj;
$mpifw_onboarding_form_fields = 
// desc - Onboarding form fields.
apply_filters('mwb_mpifw_on_boarding_form_fields', array());
?>

<?php if ( ! empty( $mpifw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable
		<?php
		echo 
		// desc - filter for trial.
		wp_kses_post( apply_filters( 'mwb_stand_dialog_classes', 'mwb-paypal-integration-for-woocommerce' ) );
		?>
		"
		>
		<div class="mwb-mpifw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-mpifw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-mpifw-on-boarding-close-btn">
						<a href="#"><span class="mpifw-close-form material-icons mwb-mpifw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>
					<h3 class="mwb-mpifw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to MakeWebBetter', 'mwb-paypal-integration-for-woocommerce' ); ?> </h3>
					<p class="mwb-mpifw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'mwb-paypal-integration-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="mwb-mpifw-on-boarding-form">
						<?php 
						$mpifw_onboarding_html = $mpifw_mwb_mpifw_obj->mwb_mpifw_plug_generate_html( $mpifw_onboarding_form_fields );
						echo esc_html( $mpifw_onboarding_html );
						?>
						<div class="mwb-mpifw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-mpifw-on-boarding-form-submit mwb-mpifw-on-boarding-form-verify ">
								<input type="submit" class="mwb-mpifw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-mpifw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-mpifw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'mwb-paypal-integration-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
