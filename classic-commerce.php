<?php
/**
 * Plugin Name: Classic Commerce
 * Plugin URI: https://github.com/ClassicPress-research/classic-commerce
 * Description: A simple, powerful and independent e-commerce platform. Sell anything with ease.
 * Version: 1.0.0-alpha1
 * Author: ClassicPress Research Team
 * Author URI: https://github.com/ClassicPress-research/classic-commerce
 * Text Domain: classic-commerce
 * Domain Path: /i18n/languages/
 *
 * @package WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Returns error when WooCommerce is detected among the files on the server.
 *
 * @return void
 */
function cc_wc_already_active_notice() {
	echo '<div class="error notice is_dismissible"><p>';
	echo esc_html__( 'You must deactivate WooCommerce before activating Classic Commerce.', 'classic-commerce' );
	echo '</p></div>';
}

if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/includes/class-woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/includes/admin/class-wc-admin.php' ) ) {

	// Woocommerce Files already exist. Show an admin notice.
	add_action( 'admin_notices', 'cc_wc_already_active_notice' );

	// Deactivate Classic Commerce.
	deactivate_plugins( array( 'classic-commerce/classic-commerce.php' ) );

	// Do not proceed further with Classic Commerce loading.
	return;

} else {

	// Load the Update Client to manage Classic Commerce updates.
	include_once dirname( __FILE__ ) . '/includes/class-wc-update-client.php';

	// Define WC_PLUGIN_FILE.
	if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
		define( 'WC_PLUGIN_FILE', __FILE__ );
	}

	// Include the main WooCommerce class.
	if ( ! class_exists( 'WooCommerce' ) ) {
		include_once dirname( __FILE__ ) . '/includes/class-woocommerce.php';
	}

	/**
	 * Main instance of WooCommerce.
	 *
	 * Returns the main instance of WC to prevent the need to use globals.
	 *
	 * @since  2.1
	 * @return WooCommerce
	 */
	function wc() {
		return WooCommerce::instance();
	}

	// Global for backwards compatibility.
	$GLOBALS['woocommerce'] = wc();

}
