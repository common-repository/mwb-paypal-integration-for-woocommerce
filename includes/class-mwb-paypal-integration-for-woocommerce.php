<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 *
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_PayPal_Integration_for_WooCommerce
 * @subpackage Mwb_PayPal_Integration_for_WooCommerce/includes
 */
class Mwb_PayPal_Integration_For_WooCommerce {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 1.0.0
	 * @var   Mwb_Paypal_Integration_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var   string    $mpifw_onboard    To initializsed the object of class onboard.
	 */
	protected $mpifw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( defined( 'MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'mwb-paypal-integration-for-woocommerce';

		$this->mwb_paypal_integration_for_woocommerce_dependencies();
		$this->mwb_paypal_integration_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->mwb_paypal_integration_for_woocommerce_admin_hooks();
		} else {
			$this->mwb_paypal_integration_for_woocommerce_public_hooks();
		}
		$this->mwb_paypal_integration_for_woocommerce_common_hooks();

		$this->mwb_paypal_integration_for_woocommerce_api_hooks();

	}

	/**
	 * Load the required dependencies with this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Paypal_Integration_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Paypal_Integration_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Mwb_Paypal_Integration_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Mwb_Paypal_Integration_For_Woocommerce_Common. Defines all hooks for the common area.
	 * - Mwb_Paypal_Integration_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mwb-paypal-integration-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mwb-paypal-integration-for-woocommerce-i18n.php';

		if (is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			include_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-mwb-paypal-integration-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if (is_dir(plugin_dir_path(dirname(__FILE__)) . 'onboarding') && ! class_exists('Mwb_Paypal_Integration_For_Woocommerce_Onboarding_Steps') ) {
				include_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-mwb-paypal-integration-for-woocommerce-onboarding-steps.php';
			}

			if (class_exists('Mwb_Paypal_Integration_For_Woocommerce_Onboarding_Steps') ) {
				$mpifw_onboard_steps = new Mwb_Paypal_Integration_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			include_once plugin_dir_path(dirname(__FILE__)) . 'public/class-mwb-paypal-integration-for-woocommerce-public.php';

		}

		include_once plugin_dir_path(dirname(__FILE__)) . 'package/rest-api/class-mwb-paypal-integration-for-woocommerce-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path(dirname(__FILE__)) . 'common/class-mwb-paypal-integration-for-woocommerce-common.php';

		$this->loader = new Mwb_Paypal_Integration_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Paypal_Integration_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_locale() {

		$plugin_i18n = new Mwb_Paypal_Integration_For_Woocommerce_I18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}

	/**
	 * Define the name of the hook to save admin notices for this plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_saved_notice_hook_name() {
		$mwb_plugin_name                            = ! empty(explode('/', plugin_basename(__FILE__))) ? explode('/', plugin_basename(__FILE__))[0] : '';
		$mwb_plugin_settings_saved_notice_hook_name = $mwb_plugin_name . '_settings_saved_notice';
		return $mwb_plugin_settings_saved_notice_hook_name;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_admin_hooks() {
		$mpifw_plugin_admin = new Mwb_Paypal_Integration_For_Woocommerce_Admin( $this->mpifw_get_plugin_name(), $this->mpifw_get_version() );

		$this->loader->add_action('admin_enqueue_scripts', $mpifw_plugin_admin, 'mpifw_admin_enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $mpifw_plugin_admin, 'mpifw_admin_enqueue_scripts');

		// Add settings menu for MWB PayPal Integration for WooCommerce.
		$this->loader->add_action('admin_menu', $mpifw_plugin_admin, 'mpifw_options_page');
		$this->loader->add_action('admin_menu', $mpifw_plugin_admin, 'mwb_mpifw_remove_default_submenu', 50);

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $mpifw_plugin_admin, 'mpifw_admin_submenu_page', 15 );

		// Saving tab settings.
		$this->loader->add_action( 'mwb_mpifw_settings_saved_notice', $mpifw_plugin_admin, 'mpifw_admin_save_tab_settings');

		//Developer's Hook Listing.
		$this->loader->add_action( 'mpifw_developer_admin_hooks_array', $mpifw_plugin_admin, 'mwb_developer_admin_hooks_listing' );
		$this->loader->add_action( 'mpifw_developer_public_hooks_array', $mpifw_plugin_admin, 'mwb_developer_public_hooks_listing' );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_common_hooks() {
		$mpifw_plugin_common = new Mwb_Paypal_Integration_For_Woocommerce_Common($this->mpifw_get_plugin_name(), $this->mpifw_get_version());
		$this->loader->add_action('wp_enqueue_scripts', $mpifw_plugin_common, 'mpifw_common_enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $mpifw_plugin_common, 'mpifw_common_enqueue_scripts');
		$this->loader->add_action('admin_enqueue_scripts', $mpifw_plugin_common, 'mpifw_common_enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $mpifw_plugin_common, 'mpifw_common_enqueue_scripts');
		
		// Save ajax request for the plugin's multistep.
		$this->loader->add_action('wp_ajax_mwb_standard_save_settings_filter', $mpifw_plugin_common, 'mwb_standard_save_settings_filter');
		$this->loader->add_action('wp_ajax_nopriv_mwb_standard_save_settings_filter', $mpifw_plugin_common, 'mwb_standard_save_settings_filter');
		if ( self::is_enbale_usage_tracking() ) {
			$this->loader->add_action('makewebbetter_tracker_send_event', $mpifw_plugin_common, 'mpifw_makewebbetter_tracker_send_event');
		}

		// WC Payment gateway extend.
		$this->loader->add_action( 'plugins_loaded', $mpifw_plugin_common, 'mpifw_extend_wc_payment_gateway_class' );
		$this->loader->add_filter( 'woocommerce_payment_gateways', $mpifw_plugin_common, 'mpifw_load_wc_payment_gateway_extended_class' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_public_hooks() {
		$mpifw_plugin_public = new Mwb_Paypal_Integration_For_Woocommerce_Public($this->mpifw_get_plugin_name(), $this->mpifw_get_version());
		$this->loader->add_action('wp_enqueue_scripts', $mpifw_plugin_public, 'mpifw_public_enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $mpifw_plugin_public, 'mpifw_public_enqueue_scripts');
		$this->loader->add_action('wp_enqueue_scripts', $mpifw_plugin_public, 'mpifw_public_enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 */
	private function mwb_paypal_integration_for_woocommerce_api_hooks() {
		$mpifw_plugin_api = new Mwb_Paypal_Integration_For_Woocommerce_Rest_Api($this->mpifw_get_plugin_name(), $this->mpifw_get_version());
		$this->loader->add_action('rest_api_init', $mpifw_plugin_api, 'mwb_mpifw_add_endpoint');
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function mpifw_run() {
		$this->loader->mpifw_run();
	}

	/**
	 * Check is usage tracking is enable 
	 * 
	 * @version 1.0.0
	 * @name is_enbale_usage_tracking
	*/
	public static function is_enbale_usage_tracking() {
		$check_is_enable = get_option( 'mpifw_enable_tracking', false );
		return !empty( $check_is_enable ) ? true : false;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
	public function mpifw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Mwb_Paypal_Integration_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function mpifw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Mwb_Paypal_Integration_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function mpifw_get_onboard() {
		return $this->mpifw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function mpifw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_mpifw_plug tabs.
	 *
	 * @return Array       An key=>value pair of MWB PayPal Integration for WooCommerce tabs.
	 */
	public function mwb_mpifw_plug_default_tabs() {
		$mpifw_default_tabs = array();

		$mpifw_default_tabs['mwb-paypal-integration-for-woocommerce-gateway-setting'] = array(
			'title'       => esc_html__('PayPal Gateway Settings', 'mwb-paypal-integration-for-woocommerce'),
			'name'        => 'mwb-paypal-integration-for-woocommerce-gateway-setting',
			'file_path'   => MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-paypal-integration-for-woocommerce-gateway-setting.php'
		);

		$mpifw_default_tabs['mwb-paypal-integration-for-woocommerce-developer'] = array(
			'title'       => esc_html__('Developer', 'mwb-paypal-integration-for-woocommerce'),
			'name'        => 'mwb-paypal-integration-for-woocommerce-developer',
			'file_path'   => MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/mwb-paypal-integration-for-woocommerce-developer.php'
		);

		$mpifw_default_tabs = 
		// desc - Add settings tab.
		apply_filters('mwb_mpifw_plugin_standard_admin_settings_tabs', $mpifw_default_tabs);

		return $mpifw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since 1.0.0
	 * @param string $path   path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_mpifw_plug_load_template( $path, $params = array() ) {

		// $mpifw_file_path = MWB_PAYPAL_INTEGRATION_FOR_WOOCOMMERCE_DIR_PATH . $path;
		
		if (file_exists($path) ) {

			include $path;
		} else {

			/* translators: %s: file path */
			$mpifw_notice = sprintf(esc_html__('Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'mwb-paypal-integration-for-woocommerce'), $path);
			$this->mwb_mpifw_plug_admin_notice($mpifw_notice, 'error');
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param string $mpifw_message Message to display.
	 * @param string $type        notice type, accepted values - error/update/update-nag.
	 * @since 1.0.0
	 */
	public static function mwb_mpifw_plug_admin_notice( $mpifw_message, $type = 'error' ) {

		$mpifw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$mpifw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$mpifw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$mpifw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$mpifw_classes .= 'notice-error is-dismissible';
		}

		$mpifw_notice  = '<div class="' . esc_attr($mpifw_classes) . '">';
		$mpifw_notice .= '<p>' . esc_html($mpifw_message) . '</p>';
		$mpifw_notice .= '</div>';
		echo wp_kses_post( $mpifw_notice );
	}

	/**
	 * Generate html components.
	 *
	 * @param string $mpifw_components html to display.
	 * @since 1.0.0
	 */
	public function mwb_mpifw_plug_generate_html( $mpifw_components = array() ) {
		if ( is_array( $mpifw_components ) && ! empty( $mpifw_components ) ) {
			foreach ( $mpifw_components as $mpifw_component ) {
				if ( ! empty( $mpifw_component['type'] ) && ! empty( $mpifw_component['id'] ) ) {
					switch ( $mpifw_component['type'] ) {

						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
							<div class="mwb-form-group mwb-mpifw-<?php echo esc_attr($mpifw_component['type']); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr($mpifw_component['id']); ?>" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
											<?php if ( 'number' !== $mpifw_component['type'] ) { ?>
												<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $mpifw_component['placeholder'] ) ? esc_attr( $mpifw_component['placeholder'] ) : '' ); ?></span>
											<?php } ?>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
										class="mdc-text-field__input <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>" 
										name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
										id="<?php echo esc_attr($mpifw_component['id']); ?>"
										type="<?php echo esc_attr($mpifw_component['type']); ?>"
										value="<?php echo ( isset($mpifw_component['value']) ? esc_attr($mpifw_component['value']) : '' ); ?>"
										placeholder="<?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?>"
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'password':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr( $mpifw_component['id'] ); ?>" class="mwb-form-label"><?php echo ( isset( $mpifw_component['title'] ) ? esc_html( $mpifw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input 
										class="mdc-text-field__input <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?> mwb-form__password" 
										name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
										id="<?php echo esc_attr($mpifw_component['id']); ?>"
										type="<?php echo esc_attr($mpifw_component['type']); ?>"
										value="<?php echo ( isset($mpifw_component['value']) ? esc_attr($mpifw_component['value']) : '' ); ?>"
										placeholder="<?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?>"
										>
										<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'textarea':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label class="mwb-form-label" for="<?php echo esc_attr($mpifw_component['id']); ?>"><?php echo ( isset( $mpifw_component['title'] ) ? esc_html( $mpifw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"      for="text-field-hero-input">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<span class="mdc-floating-label"><?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?></span>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<span class="mdc-text-field__resizer">
											<textarea class="mdc-text-field__input <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>" id="<?php echo esc_attr($mpifw_component['id']); ?>" placeholder="<?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?>"><?php echo ( isset($mpifw_component['value']) ? esc_textarea($mpifw_component['value']) : '' ); ?></textarea>
										</span>
									</label>
								</div>
							</div>
							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label class="mwb-form-label" for="<?php echo esc_attr($mpifw_component['id']); ?>"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<div class="mwb-form-select">
										<select id="<?php echo esc_attr($mpifw_component['id']); ?>" name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?><?php echo ( 'multiselect' === $mpifw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr($mpifw_component['id']); ?>" class="mdl-textfield__input <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>" <?php echo 'multiselect' === $mpifw_component['type'] ? 'multiple="multiple"' : ''; ?> >
										<?php
										foreach ( $mpifw_component['options'] as $mpifw_key => $mpifw_val ) {
											?>
											<option value="<?php echo esc_attr($mpifw_key); ?>"
												<?php
												if ( is_array( $mpifw_component['value']) ) {
													selected( in_array( (string) $mpifw_key, $mpifw_component['value'], true ), true );
												} else {
													selected( $mpifw_component['value'], (string) $mpifw_key);
												}
												?>
												>
												<?php echo esc_html($mpifw_val); ?>
											</option>
											<?php
										}
										?>
										</select>
										<label class="mdl-textfield__label" for="<?php echo esc_attr($mpifw_component['id']); ?>"><?php echo esc_html($mpifw_component['description']); ?><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></label>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'checkbox':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr($mpifw_component['id']); ?>" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control mwb-pl-4">
									<div class="mdc-form-field">
										<div class="mdc-checkbox">
											<input 
											name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
											id="<?php echo esc_attr($mpifw_component['id']); ?>"
											type="checkbox"
											class="mdc-checkbox__native-control <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>"
											value="<?php echo ( isset($mpifw_component['value']) ? esc_attr($mpifw_component['value']) : '' ); ?>"
											<?php checked($mpifw_component['value'], '1'); ?>
											/>
											<div class="mdc-checkbox__background">
												<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
													<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
												</svg>
												<div class="mdc-checkbox__mixedmark"></div>
											</div>
											<div class="mdc-checkbox__ripple"></div>
										</div>
										<label for="checkbox-1"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></label>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr($mpifw_component['id']); ?>" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control mwb-pl-4">
									<div class="mwb-flex-col">
									<?php
									foreach ( $mpifw_component['options'] as $mpifw_radio_key => $mpifw_radio_val ) {
										?>
										<div class="mdc-form-field">
											<div class="mdc-radio">
												<input
												name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
												value="<?php echo esc_attr($mpifw_radio_key); ?>"
												type="radio"
												class="mdc-radio__native-control <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>"
												<?php checked($mpifw_radio_key, $mpifw_component['value']); ?>
												>
												<div class="mdc-radio__background">
													<div class="mdc-radio__outer-circle"></div>
													<div class="mdc-radio__inner-circle"></div>
												</div>
												<div class="mdc-radio__ripple"></div>
											</div>
											<label for="radio-1"><?php echo esc_html($mpifw_radio_val); ?></label>
										</div>    
										<?php
									}
									?>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio-switch':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label">
									<label for="" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<div>
										<div class="mdc-switch">
											<div class="mdc-switch__track"></div>
											<div class="mdc-switch__thumb-underlay">
												<div class="mdc-switch__thumb"></div>
												<input
												name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
												type="checkbox"
												id="<?php echo esc_html($mpifw_component['id']); ?>"
												value="on"
												class="mdc-switch__native-control <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>"
												role="switch"
												aria-checked="<?php echo esc_attr( ( 'on' === $mpifw_component['value'] ) ? 'true' : 'false' ); ?>"
												<?php checked($mpifw_component['value'], 'on'); ?>
												>
											</div>
										</div>
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'button':
							?>
							<div class="mwb-form-group">
								<div class="mwb-form-group__label"></div>
								<div class="mwb-form-group__control">
									<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
										id="<?php echo esc_attr($mpifw_component['id']); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>"><?php echo ( isset($mpifw_component['button_text']) ? esc_html($mpifw_component['button_text']) : '' ); ?></span>
									</button>
								</div>
							</div>
							<?php
							break;

						case 'multi':
							?>
							<div class="mwb-form-group mwb-mpifw-<?php echo esc_attr($mpifw_component['type']); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr($mpifw_component['id']); ?>" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
									</div>
									<div class="mwb-form-group__control">
										<?php
										foreach ( $mpifw_component['value'] as $component ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
													<?php if ( 'number' !== $component['type'] ) { ?>
														<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?></span>
													<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>" 
												name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
												id="<?php echo esc_attr($component['id']); ?>"
												type="<?php echo esc_attr($component['type']); ?>"
												value="<?php echo ( isset($mpifw_component['value']) ? esc_attr($mpifw_component['value']) : '' ); ?>"
												placeholder="<?php echo ( isset($mpifw_component['placeholder']) ? esc_attr($mpifw_component['placeholder']) : '' ); ?>"
												<?php echo esc_attr(( 'number' === $component['type'] ) ? 'max=10 min=0' : ''); ?>
												>
											</label>
										<?php } ?>
										<div class="mdc-text-field-helper-line">
											<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></div>
										</div>
									</div>
								</div>
								<?php
							break;

						case 'color':
						case 'date':
						case 'file':
							?>
							<div class="mwb-form-group mwb-mpifw-<?php echo esc_attr($mpifw_component['type']); ?>">
								<div class="mwb-form-group__label">
									<label for="<?php echo esc_attr($mpifw_component['id']); ?>" class="mwb-form-label"><?php echo ( isset($mpifw_component['title']) ? esc_html($mpifw_component['title']) : '' ); ?></label>
								</div>
								<div class="mwb-form-group__control">
									<label>
										<input 
										class="<?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>" 
										name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
										id="<?php echo esc_attr($mpifw_component['id']); ?>"
										type="<?php echo esc_attr($mpifw_component['type']); ?>"
										value="<?php echo ( isset($mpifw_component['value']) ? esc_attr($mpifw_component['value']) : '' ); ?>"
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo ( isset($mpifw_component['description']) ? esc_attr($mpifw_component['description']) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'submit':
							?>
							<tr valign="top">
								<td scope="row">
									<input type="submit" class="button button-primary" 
									name="<?php echo ( isset($mpifw_component['name']) ? esc_html($mpifw_component['name']) : esc_html($mpifw_component['id']) ); ?>"
									id="<?php echo esc_attr($mpifw_component['id']); ?>"
									class="<?php echo ( isset($mpifw_component['class']) ? esc_attr($mpifw_component['class']) : '' ); ?>"
									value="<?php echo esc_attr($mpifw_component['button_text']); ?>"
									/>
								</td>
							</tr>
							<?php
							break;

						default:
							break;
					}
				}
			}
		}
	}
}
