<?php
/**
 * Plugin Name: Classic Commerce
 * Plugin URI: https://github.com/ClassicPress-research/classic-commerce
 * Description: A simple, powerful and independent e-commerce platform. Sell anything with ease.
 * Version: 1.0.0-alpha.2
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
 * Shows an error message when WooCommerce is detected as currently active.
 *
 * WooCommerce and Classic Commerce cannot both be active at once.
 *
 * @return void
 */
function cc_wc_already_active_notice() {
	echo '<div class="notice error is-dismissible">';
	echo '<p style="font-weight: bold">';
	echo esc_html__( 'You must deactivate WooCommerce before activating Classic Commerce.', 'classic-commerce' );
	echo '</p>';
	echo '<p>';
	echo esc_html__( 'Classic Commerce has been deactivated.', 'classic-commerce' );
	echo '</p>';
	echo '</div>';
}

if (
	file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) &&
	// Make sure we are really looking at WooCommerce and not the compatibility plugin!
	file_exists( WP_PLUGIN_DIR . '/woocommerce/includes/class-woocommerce.php' ) &&
	is_plugin_active( 'woocommerce/woocommerce.php' )
) {

	// WooCommerce is already active. Show an admin notice.
	add_action( 'admin_notices', 'cc_wc_already_active_notice' );

	// Deactivate Classic Commerce.
	deactivate_plugins( array( 'classic-commerce/classic-commerce.php' ) );

	// Avoid showing a "Plugin activated" message in the admin screen.
	// See src/wp-admin/plugins.php in core
	unset( $_GET['activate'] );

	// Do not proceed further with Classic Commerce loading.

} else {

	////////////////////////////////////////////
	// BEGIN CLASSIC COMMERCE LOADING PROCESS //
	////////////////////////////////////////////

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

	//////////////////////////////////////////
	// END CLASSIC COMMERCE LOADING PROCESS //
	//////////////////////////////////////////

}

// Do not add any new code here!  All code required to load Classic Commerce
// must go inside the "CLASSIC COMMERCE LOADING PROCESS" block above.
