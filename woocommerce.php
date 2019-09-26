<?php
/**
 * Plugin Name: Classic Commerce
 * Plugin URI: https://github.com/ClassicPress-research/classic-commerce
 * Description: Built for selling.
 * Version: 0.1.0
 * Author: ClassicPress Research Team
 * Author URI: https://github.com/ClassicPress-research/classic-commerce
 * Text Domain: woocommerce
 * Domain Path: /i18n/languages/
 *
 * @package ClassicCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WC_PLUGIN_FILE.
if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
	define( 'WC_PLUGIN_FILE', __FILE__ );
}

// Include the main WooCommerce class.
if ( ! class_exists( 'WooCommerce' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-woocommerce.php';
}

/**
 * Main instance of ClassicCommerce.
 *
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @since  WC-2.1
 * @return WooCommerce
 */
function wc() {
	return WooCommerce::instance();
}

// Global for backwards compatibility.
$GLOBALS['woocommerce'] = wc();
