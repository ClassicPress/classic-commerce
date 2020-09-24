<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dl class="variation">
	<?php foreach ( $item_data as $data ) : ?>
		<dt class="<?php echo sanitize_html_class( 'variation-' . $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>:</dt>
		<dd class="<?php echo sanitize_html_class( 'variation-' . $data['key'] ); ?>"><?php echo wp_kses_post( wpautop( $data['display'] ) ); ?></dd>
	<?php endforeach; ?>
</dl>
