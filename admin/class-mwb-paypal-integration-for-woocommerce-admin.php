<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/admin
 */
class Mwb_Paypal_Integration_For_Woocommerce_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function mpifw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if (isset($screen->id) && 'makewebbetter_page_mwb_paypal_integration_for_woocommerce_menu' === $screen->id ) {

			// Multistep form css.
			if ( ! mwb_paypal_integration_for_woocommerce_check_multistep() ) {
				$style_url = MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'build/style-index.css';
				wp_enqueue_style(
					'mwb-admin-react-styles',
					$style_url,
					array(),
					time(),
					false
				);
				return;
			}

			wp_enqueue_style('mwb-mpifw-select2-css', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-paypal-integration-for-woocommerce-select2.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mpifw-meterial-css', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mpifw-meterial-css2', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mpifw-meterial-lite', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all');
			wp_enqueue_style('mwb-mpifw-meterial-icons-css', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all');
			wp_enqueue_style('mwb-admin-min-css', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/mwb-admin.min.css', array(), $this->version, 'all');
			wp_enqueue_style('mwb-datatable-css', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all');
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @param string $hook The plugin page slug.
	 */
	public function mpifw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if (isset( $screen->id ) && 'makewebbetter_page_mwb_paypal_integration_for_woocommerce_menu' === $screen->id ) {
			if ( ! mwb_paypal_integration_for_woocommerce_check_multistep() ) {
				// Js for the multistep from.
				$script_path       = '../../build/index.js';
				$script_asset_path = MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'build/index.asset.php';
				$script_asset      = file_exists( $script_asset_path )
					? require $script_asset_path
					: array(
						'dependencies' => array(
							'wp-hooks',
							'wp-element',
							'wp-i18n',
							'wc-components',
						),
						'version'      => filemtime( $script_path ),
				);
				$script_url        = MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'build/index.js';
				wp_register_script(
					'react-app-block',
					$script_url,
					$script_asset['dependencies'],
					$script_asset['version'],
					true
				);
				wp_enqueue_script( 'react-app-block' );
				wp_localize_script(
					'react-app-block',
					'frontend_ajax_object',
					array(
						'ajaxurl'            => admin_url( 'admin-ajax.php' ),
						'mwb_standard_nonce' => wp_create_nonce( 'ajax-nonce' ),
						'redirect_url'       => admin_url( 'admin.php?page=mwb_paypal_integration_for_woocommerce_menu' ),
					)
				);
				return;
			}

			wp_enqueue_script('mwb-mpifw-select2', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/mwb-paypal-integration-for-woocommerce-select2.js', array( 'jquery' ), time(), false);
			
			wp_enqueue_script('mwb-mpifw-metarial-js', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mpifw-metarial-js2', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mpifw-metarial-lite', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mpifw-datatable', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/js/jquery.dataTables.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mpifw-datatable-btn', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/dataTables.buttons.min.js', array(), time(), false);
			wp_enqueue_script('mwb-mpifw-datatable-btn-2', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datatables.net/buttons/buttons.html5.min.js', array(), time(), false);
			wp_register_script($this->plugin_name . 'admin-js', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-paypal-integration-for-woocommerce-admin.js', array( 'jquery', 'mwb-mpifw-select2', 'mwb-mpifw-metarial-js', 'mwb-mpifw-metarial-js2', 'mwb-mpifw-metarial-lite' ), $this->version, false);
			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'mpifw_admin_param',
				array(
					'ajaxurl'                    => admin_url('admin-ajax.php'),
					'mwb_standard_nonce'         => wp_create_nonce( 'ajax-nonce' ),
					'reloadurl'                  => admin_url('admin.php?page=mwb_paypal_integration_for_woocommerce_menu'),
					'mpifw_gen_tab_enable'       => get_option('mpifw_radio_switch_demo'),
					'mpifw_admin_param_location' => ( admin_url('admin.php') . '?page=mwb_paypal_integration_for_woocommerce_menu&mpifw_tab=mwb-paypal-integration-for-woocommerce-general' ),
				)
			);
			wp_enqueue_script($this->plugin_name . 'admin-js');
			wp_enqueue_script('mwb-admin-min-js', MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'admin/js/mwb-admin.min.js', array(), time(), false);
		}
	}

	/**
	 * Adding settings menu for MWB PayPal Integration for WooCommerce.
	 *
	 * @since 1.0.0
	 */
	public function mpifw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( 'MakeWebBetter', 'MakeWebBetter', 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_URL . 'admin/image/MWB_Grey-01.svg', 15 );
			$mpifw_menus = 
			// desc - Add plugin menu.
			apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $mpifw_menus ) && ! empty( $mpifw_menus ) ) {
				foreach ( $mpifw_menus as $mpifw_key => $mpifw_value ) {
					add_submenu_page('mwb-plugins', $mpifw_value['name'], $mpifw_value['name'], 'manage_options', $mpifw_value['menu_link'], array( $mpifw_value['instance'], $mpifw_value['function'] ));
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since 1.0.0
	 */
	public function mwb_mpifw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}


	/**
	 * MWB PayPal Integration for WooCommerce mpifw_admin_submenu_page.
	 *
	 * @param array $menus Marketplace menus.
	 * @since 1.0.0
	 */
	public function mpifw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => 'MWB PayPal Integration for WooCommerce',
			'slug'      => 'mwb_paypal_integration_for_woocommerce_menu',
			'menu_link' => 'mwb_paypal_integration_for_woocommerce_menu',
			'instance'  => $this,
			'function'  => 'mpifw_options_menu_html',
		);
		return $menus;
	}

	/**
	 * MWB PayPal Integration for WooCommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = 
		// desc - Add plugin menu.
		apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			include MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * MWB PayPal Integration for WooCommerce admin menu page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mpifw_options_menu_html() {
		include_once MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-paypal-integration-for-woocommerce-admin-dashboard.php';
	}

	/**
	 * Developer_admin_hooks_listing.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function mwb_developer_admin_hooks_listing() {
		$admin_hooks = array();
		$val         = self::mwb_developer_hooks_function(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/');
		if (!empty($val['hooks'])) {
			$admin_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js'!== $v && 'images' !== $v ) {
				$helo = self::mwb_developer_hooks_function(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/' . $v . '/');
				if (!empty($helo['hooks'])) {
					$admin_hooks[] = $helo['hooks'];
					unset($helo['hooks']);
				}
				if (! empty($helo)) {
					$data[] = $helo;
				}
			}
		}
		return $admin_hooks;
	}

	/**
	 * Developer_public_hooks_listing.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function mwb_developer_public_hooks_listing() {

		$public_hooks = array();
		$val          = self::mwb_developer_hooks_function(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'public/');
		
		if (!empty($val['hooks'])) {
			$public_hooks[] = $val['hooks'];
			unset($val['hooks']);
		}
		$data = array();
		foreach ( $val['files'] as $v ) {
			if ( 'css' !== $v && 'js'!== $v && 'images' !== $v ) {
				$helo = self::mwb_developer_hooks_function(MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'public/' . $v . '/');
				if (! empty($helo['hooks']) ) {
					$public_hooks[] = $helo['hooks'];
					unset($helo['hooks']);
				}
				if (! empty($helo)) {
					$data[] = $helo;
				}
			}
		}
		return $public_hooks;
	}

	/**
	 * Developer_hooks_function.
	 *
	 * @param string $path path for the file to scan the hook.
	 * @since 1.0.0
	 * @return array
	 */
	public static function mwb_developer_hooks_function( $path ) {
		$all_hooks = array();
		$scan      = scandir($path);
		$response  = array();
		foreach ($scan as $file) {
			if (strpos($file, '.php') ) {
				$myfile = file($path . $file);
				foreach ( $myfile as $key => $lines ) {
					if (preg_match('/do_action/i', $lines) && ! strpos($lines, 'str_replace') && ! strpos($lines, 'preg_match') ) {
						$all_hooks[$key]['action_hook'] = $lines;
						$all_hooks[$key]['desc']        = $myfile[$key-1];
					}
					if (preg_match('/apply_filters/i', $lines) && ! strpos($lines, 'str_replace') && ! strpos($lines, 'preg_match') ) {
						$all_hooks[$key]['filter_hook'] = $lines;
						$all_hooks[$key]['desc']        = $myfile[$key-1];
					}
				}
			} elseif ( empty( strpos( $file, '.' ) ) && strpos( $file, '.' ) !== 0 ) {
				$response['files'][] = $file;
			}
		}
		if (! empty($all_hooks)) {
			$response['hooks'] = $all_hooks;
		}
		return $response;
	}

	/**
	 * MWB PayPal Integration for WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mpifw_admin_save_tab_settings() {
		global $mpifw_mwb_mpifw_obj;
		if ( isset( $_POST['mpifw_button_demo'] ) && ( ! empty( $_POST['mwb_tabs_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_tabs_nonce'] ) ), 'admin_save_data' ) ) ) {
			$mwb_mpifw_gen_flag     = false;
			$mpifw_genaral_settings = 
			// desc - general setting tab fields.
			apply_filters('mpifw_general_settings_array', array());
			$mpifw_button_index = array_search( 'submit', array_column( $mpifw_genaral_settings, 'type' ), true );
			if ( isset( $mpifw_button_index ) && empty( $mpifw_button_index ) ) {
				$mpifw_button_index = array_search( 'button', array_column( $mpifw_genaral_settings, 'type' ), true );
			}
			if (isset($mpifw_button_index) && '' !== $mpifw_button_index ) {
				unset($mpifw_genaral_settings[$mpifw_button_index]);
				if (is_array($mpifw_genaral_settings) && ! empty($mpifw_genaral_settings) ) {
					foreach ( $mpifw_genaral_settings as $mpifw_genaral_setting ) {
						if (isset($mpifw_genaral_setting['id']) && '' !== $mpifw_genaral_setting['id'] ) {
							if (isset($_POST[$mpifw_genaral_setting['id']]) ) {
								update_option($mpifw_genaral_setting['id'], is_array( $_POST[ $mpifw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $mpifw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $mpifw_genaral_setting['id'] ] ) ) );
							} else {
								update_option($mpifw_genaral_setting['id'], '');
							}
						} else {
							$mwb_mpifw_gen_flag = true;
						}
					}
				}
				if ($mwb_mpifw_gen_flag ) {
					$mwb_mpifw_error_text = esc_html__('Id of some field is missing', 'mwb-paypal-integration-for-woocommerce');
					$mpifw_mwb_mpifw_obj->mwb_mpifw_plug_admin_notice($mwb_mpifw_error_text, 'error');
				} else {
					$mwb_mpifw_error_text = esc_html__('Settings saved !', 'mwb-paypal-integration-for-woocommerce');
					$mpifw_mwb_mpifw_obj->mwb_mpifw_plug_admin_notice($mwb_mpifw_error_text, 'success');
				}
			}
		}
	}
}
