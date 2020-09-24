<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
	<div class="quantity hidden">
		<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	</div>
	<?php
} else {
	/* translators: %s: Quantity. */
	$labelledby = ! empty( $args['product_name'] ) ? sprintf( __( '%s quantity', 'classic-commerce' ), strip_tags( $args['product_name'] ) ) : '';
	?>
	<div class="quantity">
		<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( 'Quantity', 'classic-commerce' ); ?></label>
		<input
			type="number"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="input-text qty text"
			step="<?php echo esc_attr( $step ); ?>"
			min="<?php echo esc_attr( $min_value ); ?>"
			max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $input_value ); ?>"
			title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'classic-commerce' ); ?>"
			size="4"
			pattern="<?php echo esc_attr( $pattern ); ?>"
			inputmode="<?php echo esc_attr( $inputmode ); ?>"
			aria-labelledby="<?php echo esc_attr( $labelledby ); ?>" />
	</div>
	<?php
}
