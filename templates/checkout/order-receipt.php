<?php
/**
 * Checkout Order Receipt Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-receipt.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<ul class="order_details">
	<li class="order">
		<?php esc_html_e( 'Order number:', 'classic-commerce' ); ?>
		<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
	</li>
	<li class="date">
		<?php esc_html_e( 'Date:', 'classic-commerce' ); ?>
		<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
	</li>
	<li class="total">
		<?php esc_html_e( 'Total:', 'classic-commerce' ); ?>
		<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
	</li>
	<?php if ( $order->get_payment_method_title() ) : ?>
	<li class="method">
		<?php esc_html_e( 'Payment method:', 'classic-commerce' ); ?>
		<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
	</li>
	<?php endif; ?>
</ul>

<?php do_action( 'woocommerce_receipt_' . $order->get_payment_method(), $order->get_id() ); ?>

<div class="clear"></div>
