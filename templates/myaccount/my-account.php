<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/myaccount/my-account.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since WC-2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>

<div class="woocommerce-MyAccount-content">
	<?php
		/**
		 * My Account content.
		 *
		 * @since WC-2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
</div>
