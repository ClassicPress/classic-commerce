<?php
/**
 * Email Addresses (plain)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/email-addresses.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo "\n" . esc_html( wc_strtoupper( __( 'Billing address', 'classic-commerce' ) ) ) . "\n\n";
echo preg_replace( '#<br\s*/?>#i', "\n", $order->get_formatted_billing_address() ) . "\n"; // WPCS: XSS ok.

if ( $order->get_billing_phone() ) {
	echo $order->get_billing_phone() . "\n"; // WPCS: XSS ok.
}

if ( $order->get_billing_email() ) {
	echo $order->get_billing_email() . "\n"; // WPCS: XSS ok.
}

if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) {
	$shipping = $order->get_formatted_shipping_address();

	if ( $shipping ) {
		echo "\n" . esc_html( wc_strtoupper( __( 'Shipping address', 'classic-commerce' ) ) ) . "\n\n";
		echo preg_replace( '#<br\s*/?>#i', "\n", $shipping ) . "\n"; // WPCS: XSS ok.
	}
}
