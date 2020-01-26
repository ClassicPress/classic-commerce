<?php
/**
 * Classic Commerce Account Settings.
 *
 * @package ClassicCommerce/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_Accounts', false ) ) {
	return new WC_Settings_Accounts();
}

/**
 * WC_Settings_Accounts.
 */
class WC_Settings_Accounts extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'account';
		$this->label = __( 'Accounts &amp; Privacy', 'classic-commerce' );
		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$erasure_text = esc_html__( 'account erasure request', 'classic-commerce' );
		if ( current_user_can( 'manage_privacy_options' ) ) {
			$erasure_text = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'tools.php?page=remove_personal_data' ) ), $erasure_text );
		}

		$settings = apply_filters(
			'woocommerce_' . $this->id . '_settings', array(
				array(
					'title' => 'Accounts',
					'type'  => 'title',
					'id'    => 'account_registration_options',
				),
				array(
					'title'         => __( 'Guest checkout', 'classic-commerce' ),
					'desc'          => __( 'Allow customers to place orders without an account', 'classic-commerce' ),
					'id'            => 'woocommerce_enable_guest_checkout',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),
				array(
					'title'         => __( 'Login', 'classic-commerce' ),
					'desc'          => __( 'Allow customers to log into an existing account during checkout', 'classic-commerce' ),
					'id'            => 'woocommerce_enable_checkout_login_reminder',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
					'autoload'      => false,
				),
				array(
					'title'         => __( 'Account creation', 'classic-commerce' ),
					'desc'          => __( 'Allow customers to create an account during checkout', 'classic-commerce' ),
					'id'            => 'woocommerce_enable_signup_and_login_from_checkout',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),
				array(
					'desc'          => __( 'Allow customers to create an account on the "My account" page', 'classic-commerce' ),
					'id'            => 'woocommerce_enable_myaccount_registration',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => '',
					'autoload'      => false,
				),
				array(
					'desc'          => __( 'When creating an account, automatically generate a username from the customer\'s email address', 'classic-commerce' ),
					'id'            => 'woocommerce_registration_generate_username',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => '',
					'autoload'      => false,
				),
				array(
					'desc'          => __( 'When creating an account, automatically generate an account password', 'classic-commerce' ),
					'id'            => 'woocommerce_registration_generate_password',
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
					'autoload'      => false,
				),
				array(
					'title'         => __( 'Account erasure requests', 'classic-commerce' ),
					'desc'          => __( 'Remove personal data from orders', 'classic-commerce' ),
					/* Translators: %s URL to erasure request screen. */
					'desc_tip'      => sprintf( esc_html__( 'When handling an %s, should personal data within orders be retained or removed?', 'classic-commerce' ), $erasure_text ),
					'id'            => 'woocommerce_erasure_request_removes_order_data',
					'type'          => 'checkbox',
					'default'       => 'no',
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),
				array(
					'desc'          => __( 'Remove access to downloads', 'classic-commerce' ),
					/* Translators: %s URL to erasure request screen. */
					'desc_tip'      => sprintf( esc_html__( 'When handling an %s, should access to downloadable files be revoked and download logs cleared?', 'classic-commerce' ), $erasure_text ),
					'id'            => 'woocommerce_erasure_request_removes_download_data',
					'type'          => 'checkbox',
					'default'       => 'no',
					'checkboxgroup' => 'end',
					'autoload'      => false,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'account_registration_options',
				),

				array(
					'title' => __( 'Usage Tracking', 'classic-commerce' ),
					'type'  => 'title',
					'id'    => 'section_cc_usage_tracking',
					'desc'  => __( 'This section controls the collection of limited encrypted data.', 'classic-commerce'),
				),
				array(
					'title'    => __( 'Anonymous data collection', 'classic-commerce' ),
					'desc'     => __( 'Allow us to collect encrypted data. <strong>We cannot identify you or your website from this data</strong>.', 'classic-commerce' ),
					'desc_tip' => __( 'Classic Commerce collects <strong>anonymized</strong> and encrypted data. This data is important to us as it helps us to keep track of Classic Commerce installations. It includes the timestamp of plugin last update check and URL of the website asking for updates which is sha512 hashed.', 'classic-commerce' ),
					'id'       => 'cc_usage_tracking',
					'type'     => 'checkbox',
					'default'  => 'yes',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'section_cc_usage_tracking',
				),

				array(
					'title' => __( 'Privacy policy', 'classic-commerce' ),
					'type'  => 'title',
					'id'    => 'privacy_policy_options',
					'desc'  => __( 'This section controls the display of your website privacy policy. The privacy notices below will not show up unless a privacy page is first set.', 'classic-commerce' ),
				),

				array(
					'title'    => __( 'Privacy page', 'classic-commerce' ),
					'desc'     => __( 'Choose a page to act as your privacy policy.', 'classic-commerce' ),
					'id'       => 'wp_page_for_privacy_policy',
					'type'     => 'single_select_page',
					'default'  => '',
					'class'    => 'wc-enhanced-select-nostd',
					'css'      => 'min-width:300px;',
					'desc_tip' => true,
				),

				array(
					'title'    => __( 'Registration privacy policy', 'classic-commerce' ),
					'desc_tip' => __( 'Optionally add some text about your store privacy policy to show on account registration forms.', 'classic-commerce' ),
					'id'       => 'woocommerce_registration_privacy_policy_text',
					/* translators: %s privacy policy page name and link */
					'default'  => sprintf( __( 'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our %s.', 'classic-commerce' ), '[privacy_policy]' ),
					'type'     => 'textarea',
					'css'      => 'min-width: 50%; height: 75px;',
				),

				array(
					'title'    => __( 'Checkout privacy policy', 'classic-commerce' ),
					'desc_tip' => __( 'Optionally add some text about your store privacy policy to show during checkout.', 'classic-commerce' ),
					'id'       => 'woocommerce_checkout_privacy_policy_text',
					/* translators: %s privacy policy page name and link */
					'default'  => sprintf( __( 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our %s.', 'classic-commerce' ), '[privacy_policy]' ),
					'type'     => 'textarea',
					'css'      => 'min-width: 50%; height: 75px;',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'privacy_policy_options',
				),
				array(
					'title' => __( 'Personal data retention', 'classic-commerce' ),
					'desc'  => __( 'Choose how long to retain personal data when it\'s no longer needed for processing. Leave the following options blank to retain this data indefinitely.', 'classic-commerce' ),
					'type'  => 'title',
					'id'    => 'personal_data_retention',
				),
				array(
					'title'       => __( 'Retain inactive accounts ', 'classic-commerce' ),
					'desc_tip'    => __( 'Inactive accounts are those which have not logged in, or placed an order, for the specified duration. They will be deleted. Any orders will be converted into guest orders.', 'classic-commerce' ),
					'id'          => 'woocommerce_delete_inactive_accounts',
					'type'        => 'relative_date_selector',
					'placeholder' => __( 'N/A', 'classic-commerce' ),
					'default'     => array(
						'number' => '',
						'unit'   => 'months',
					),
					'autoload'    => false,
				),
				array(
					'title'       => __( 'Retain pending orders ', 'classic-commerce' ),
					'desc_tip'    => __( 'Pending orders are unpaid and may have been abandoned by the customer. They will be trashed after the specified duration.', 'classic-commerce' ),
					'id'          => 'woocommerce_trash_pending_orders',
					'type'        => 'relative_date_selector',
					'placeholder' => __( 'N/A', 'classic-commerce' ),
					'default'     => '',
					'autoload'    => false,
				),
				array(
					'title'       => __( 'Retain failed orders', 'classic-commerce' ),
					'desc_tip'    => __( 'Failed orders are unpaid and may have been abandoned by the customer. They will be trashed after the specified duration.', 'classic-commerce' ),
					'id'          => 'woocommerce_trash_failed_orders',
					'type'        => 'relative_date_selector',
					'placeholder' => __( 'N/A', 'classic-commerce' ),
					'default'     => '',
					'autoload'    => false,
				),
				array(
					'title'       => __( 'Retain cancelled orders', 'classic-commerce' ),
					'desc_tip'    => __( 'Cancelled orders are unpaid and may have been cancelled by the store owner or customer. They will be trashed after the specified duration.', 'classic-commerce' ),
					'id'          => 'woocommerce_trash_cancelled_orders',
					'type'        => 'relative_date_selector',
					'placeholder' => __( 'N/A', 'classic-commerce' ),
					'default'     => '',
					'autoload'    => false,
				),
				array(
					'title'       => __( 'Retain completed orders', 'classic-commerce' ),
					'desc_tip'    => __( 'Retain completed orders for a specified duration before anonymizing the personal data within them.', 'classic-commerce' ),
					'id'          => 'woocommerce_anonymize_completed_orders',
					'type'        => 'relative_date_selector',
					'placeholder' => __( 'N/A', 'classic-commerce' ),
					'default'     => array(
						'number' => '',
						'unit'   => 'months',
					),
					'autoload'    => false,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'personal_data_retention',
				),
			)
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}
}

return new WC_Settings_Accounts();