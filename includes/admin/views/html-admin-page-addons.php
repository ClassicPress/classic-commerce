<?php
/**
 * Admin View: Page - Addons
 *
 * @var string $view
 * @var object $addons
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap woocommerce wc_addons_wrap">

	<h2><?php esc_html_e( 'Extensions for Classic Commerce', 'classic-commerce' ); ?></h2>

	<hr>

	<p><?php esc_html_e( 'This is a message and disclaimer about compatability of WooCoomerce extensions with Classic Commerce.', 'classic-commerce' ); ?></p>

	<p><?php printf( __( 'Extensions that work with WooCoomerce %1$s in Classic Commerce %2$s' , 'classic-commerce'), '<strong>still work</strong>', '<strong>however the shop owner needs to check carefully for compatibility.</strong>' ); ?></p>

	<p><?php printf( __( 'Click the link to get a <a href="%s">catalog of WooCommerce Extensions</a>', 'woocommerce' ), 'https://woocommerce.com/product-category/woocommerce-extensions/' ); ?></p>

	<p><?php esc_html_e( 'Please note that Classic Commerce does not include JetPack or WooCommerce Services integration.', 'classic-commerce' ); ?></p>

</div>


