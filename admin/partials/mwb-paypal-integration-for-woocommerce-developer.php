<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to list all the hooks and filter with their descriptions.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $mpifw_mwb_mpifw_obj;
$mpifw_developer_admin_hooks = 
// desc - Developers tab data.
apply_filters( 'mpifw_developer_admin_hooks_array', array() );
$count_admin                  = filtered_array( $mpifw_developer_admin_hooks );
$mpifw_developer_public_hooks = 
// desc - List public hooks on developers tab.
apply_filters('mpifw_developer_public_hooks_array', array());
$count_public = filtered_array($mpifw_developer_public_hooks);
?>
<!--  template file for admin settings. -->
<div class="mpifw-section-wrap">
	<div class="mwb-col-wrap">
		<div id="admin-hooks-listing" class="table-responsive mdc-data-table">
			<table class="mwb-mpifw-table mdc-data-table__table mwb-table"  id="mwb-mpifw-wp">
				<thead>
				<tr><th class="mdc-data-table__header-cell"><?php esc_html_e( 'Admin Hooks', 'mwb-paypal-integration-for-woocommerce' ); ?></th></tr>
				<tr>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Type of Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Hooks', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Hooks description', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
				</tr>
				</thead>
				<tbody class="mdc-data-table__content">
				<?php
				if ( !empty( $count_admin) ) {
					foreach ( $count_admin as $k => $v) {
						if ( isset( $v['action_hook'] )) {
							?>
						<tr class="mdc-data-table__row"><td class="mdc-data-table__cell"><?php esc_html_e( 'Action Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['action_hook'] ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['desc'] ); ?></td></tr>
						<?php
						} else {
							?>
							<tr class="mdc-data-table__row"><td class="mdc-data-table__cell"><?php esc_html_e( 'Filter Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['filter_hook'] ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['desc'] ); ?></td></tr>
							<?php
						}
					}
				} else {
					?>
					<tr class="mdc-data-table__row"><td><?php esc_html_e( 'No Hooks Found', 'mwb-paypal-integration-for-woocommerce' ); ?><td></tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="mwb-col-wrap">
		<div id="public-hooks-listing" class="table-responsive mdc-data-table">
			<table class="mwb-mpifw-table mdc-data-table__table mwb-table" id="mwb-mpifw-sys">
				<thead>
				<tr><th class="mdc-data-table__header-cell"><?php esc_html_e( 'Public Hooks', 'mwb-paypal-integration-for-woocommerce' ); ?></th></tr>
				<tr>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Type of Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Hooks', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
					<th class="mdc-data-table__header-cell"><?php esc_html_e( 'Hooks description', 'mwb-paypal-integration-for-woocommerce' ); ?></th>
				</tr>
				</thead>
				<tbody class="mdc-data-table__content">
				<?php
				if ( !empty( $count_public)) {
					foreach ( $count_public as $k => $v) {
						if ( isset( $v['action_hook'] )) {
							?>
						<tr class="mdc-data-table__row"><td class="mdc-data-table__cell"><?php esc_html_e( 'Action Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['action_hook'] ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['desc'] ); ?></td></tr>
						<?php
						} else {
							?>
							<tr class="mdc-data-table__row"><td class="mdc-data-table__cell"><?php esc_html_e( 'Filter Hook', 'mwb-paypal-integration-for-woocommerce' ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['filter_hook'] ); ?></td><td class="mdc-data-table__cell"><?php echo esc_html( $v['desc'] ); ?></td></tr>
							<?php
						}
					}
				} else {
					?>
					<tr class="mdc-data-table__row"><td><?php esc_html_e( 'No Hooks Found', 'mwb-paypal-integration-for-woocommerce' ); ?><td></tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php 
$mwb_tracking_fields_array =
// desc - Add tracking fields.
apply_filters( 'mwb_tracking_fields_array', array(
	array(
		'title'        => __( 'Enable Tracking', 'mwb-paypal-integration-for-woocommerce' ),
		'type'         => 'radio-switch',
		'description'  => __( 'Allow usage of this plugin to be tracked', 'mwb-paypal-integration-for-woocommerce' ),
		'id'           => 'mpifw_enable_tracking',
		'value'        => get_option( 'mpifw_enable_tracking' ),
		'class'        => 'mpifw-radio-switch-class',
		'options'      => array(
			'yes' => __( 'YES', 'mwb-paypal-integration-for-woocommerce' ),
			'no'  => __( 'NO', 'mwb-paypal-integration-for-woocommerce' ),
		),
	),
	array(
		'type'        => 'button',
		'id'          => 'mpifw_button_demo',
		'button_text' => __('Save', 'mwb-paypal-integration-for-woocommerce'),
		'class'       => 'mpifw-button-class',
	),
) );
?>

<form action="" method="POST" class="mwb-mpifw-gen-section-form">
	<div class="mpifw-secion-wrap">
		<?php
		$mpifw_tracking_html = $mpifw_mwb_mpifw_obj->mwb_mpifw_plug_generate_html( $mwb_tracking_fields_array );
		echo esc_html( $mpifw_tracking_html );
		wp_nonce_field( 'admin_save_data', 'mwb_tabs_nonce' ); 
		?>
	</div>
</form>

<?php
/**
 * Collected filtered hooks name and desc.
 *
 * @param array $argu array containing hooks data.
 * @since 1.0.0
 * @return array
 */
function filtered_array( $argu ) {
	$count_admin = array();
	foreach ( $argu as $key => $value) {
		foreach ( $value as $k => $originvalue) {
			if ( isset( $originvalue['action_hook'] )) {
				$val                            = str_replace(' ', '', $originvalue['action_hook']);
				$val                            = str_replace("do_action('", '', $val );
				$val                            = str_replace("');", '', $val);
				$count_admin[$k]['action_hook'] = $val;
			}
			if ( isset( $originvalue['filter_hook'] )) {
				$val                            = str_replace(' ', '', $originvalue['filter_hook']);
				$val                            = str_replace("apply_filters('", '', $val );
				$val                            = str_replace("',array());", '', $val);
				$count_admin[$k]['filter_hook'] = $val;
			}
			$vale                    = str_replace('// desc - ', '', $originvalue['desc'] );
			$count_admin[$k]['desc'] = $vale;
		}
	}
	return $count_admin;
}
