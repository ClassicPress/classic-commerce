<?php
/**
 * Additional Customer Details
 *
 * This is extra customer data which can be filtered by plugins. It outputs below the order item table.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-customer-details.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates/Emails
 * @version WC-2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php if ( ! empty( $fields ) ) : ?>
	<div style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;">
		<h2><?php _e( 'Customer details', 'classic-commerce' ); ?></h2>
		<ul>
			<?php foreach ( $fields as $field ) : ?>
				<li><strong><?php echo wp_kses_post( $field['label'] ); ?>:</strong> <span class="text"><?php echo wp_kses_post( $field['value'] ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
