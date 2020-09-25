<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/plain/customer-reset-password.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '= ' . esc_html( $email_heading ) . " =\n\n";

/* translators: %s: Customer first name */
echo sprintf( esc_html__( 'Hi %s,', 'classic-commerce' ), esc_html( $user_login ) ) . "\n\n";
/* translators: %s: Store name */
echo sprintf( esc_html__( 'Someone has requested a new password for the following account on %s:', 'classic-commerce' ), esc_html( wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) ) . "\n\n";
/* translators: %s: Customer username */
echo sprintf( esc_html__( 'Username: %s', 'classic-commerce' ), esc_html( $user_login ) ) . "\n\n";
echo esc_html__( 'If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'classic-commerce' ) . "\n\n";
echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ) . "\n\n"; // phpcs:ignore
echo esc_html__( 'Thanks for reading.', 'classic-commerce' ) . "\n\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
