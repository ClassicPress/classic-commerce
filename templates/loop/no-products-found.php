<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package Classic Commerce/Templates
 * @version WC-2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<p class="woocommerce-info"><?php _e( 'No products were found matching your selection.', 'classic-commerce' ); ?></p>
