<?php
/**
 * Order again button
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-again.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<p class="order-again">
	<a href="<?php echo esc_url( $order_again_url ); ?>" class="button"><?php esc_html_e( 'Order again', 'classic-commerce' ); ?></a>
</p>
