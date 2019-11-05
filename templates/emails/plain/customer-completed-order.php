<?php
/**
 * Customer completed order email (plain text)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-completed-order.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package ClassicCommerce/Templates/Emails/Plain
 * @version WC-3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '= ' . esc_html( $email_heading ) . " =\n\n";

/* translators: %s: Customer first name */
echo sprintf( esc_html__( 'Hi %s,', 'classic-commerce' ), esc_html( $order->get_billing_first_name() ) ) . "\n\n";
/* translators: %s: Site title */
echo sprintf( esc_html__( 'Your %s order has been marked complete on our side.', 'classic-commerce' ), esc_html( wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) ) . "\n\n";

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since WC-2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

echo esc_html__( 'Thanks for shopping with us.', 'classic-commerce' ) . "\n\n";

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo esc_html( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
