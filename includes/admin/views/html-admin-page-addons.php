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

		<h1>Extensions for Classic Commerce</h1>

		<p>This is a message and disclaimer about compatability of WooCoomerce extensions with Classic Commerce.</p>

		<p>They <strong>probably</strong> should still work (?) but you will need to check carefully.</p>

		<p>Note that Classic Commerce does not include JetPack integration.</p>

		<p>Maybe include a list here of basic extensions that we know are compatible?</p>

		<p><?php printf( __( 'A catalog of WooCommerce Extensions can be found here: <a href="%s">WooCommerce Extensions Catalog</a>', 'woocommerce' ), 'https://woocommerce.com/product-category/woocommerce-extensions/' ); ?></p>

</div>


