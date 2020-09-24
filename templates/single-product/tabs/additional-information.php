<?php
/**
 * Additional Information tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/additional-information.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = esc_html( apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional information', 'classic-commerce' ) ) );

?>

<?php if ( $heading ) : ?>
	<h2><?php echo $heading; ?></h2>
<?php endif; ?>

<?php do_action( 'woocommerce_product_additional_information', $product ); ?>
