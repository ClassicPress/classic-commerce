<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

?>
<div class="woocommerce-form-login-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'classic-commerce' ) ) . ' <a href="#" class="showlogin">' . __( 'Click here to login', 'classic-commerce' ) . '</a>', 'notice' ); ?>
</div>
<?php

woocommerce_login_form(
	array(
		'message'  => __( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'classic-commerce' ),
		'redirect' => wc_get_page_permalink( 'checkout' ),
		'hidden'   => true,
	)
);
