<?php
/**
 * Linked product options.
 *
 * @package ClassicCommerce/admin
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="linked_product_data" class="panel woocommerce_options_panel hidden">

	<div class="options_group show_if_grouped">
		<p class="form-field">
			<label for="grouped_products"><?php esc_html_e( 'Grouped products', 'classic-commerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="grouped_products" name="grouped_products[]" data-sortable="true" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'classic-commerce' ); ?>" data-action="woocommerce_json_search_products" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
				$product_ids = $product_object->is_type( 'grouped' ) ? $product_object->get_children( 'edit' ) : array();

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
					}
				}
				?>
			</select> <?php echo wc_help_tip( __( 'This lets you choose which products are part of this group.', 'classic-commerce' ) ); // WPCS: XSS ok. ?>
		</p>
	</div>

	<div class="options_group">
		<p class="form-field">
			<label for="upsell_ids"><?php esc_html_e( 'Upsells', 'classic-commerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="upsell_ids" name="upsell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'classic-commerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
				$product_ids = $product_object->get_upsell_ids( 'edit' );

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
					}
				}
				?>
			</select> <?php echo wc_help_tip( __( 'Upsells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'classic-commerce' ) ); // WPCS: XSS ok. ?>
		</p>

		<p class="form-field hide_if_grouped hide_if_external">
			<label for="crosssell_ids"><?php esc_html_e( 'Cross-sells', 'classic-commerce' ); ?></label>
			<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="crosssell_ids" name="crosssell_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'classic-commerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
				<?php
				$product_ids = $product_object->get_cross_sell_ids( 'edit' );

				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
					}
				}
				?>
			</select> <?php echo wc_help_tip( __( 'Cross-sells are products which you promote in the cart, based on the current product.', 'classic-commerce' ) ); // WPCS: XSS ok. ?>
		</p>
	</div>

	<?php do_action( 'woocommerce_product_options_related' ); ?>
</div>
