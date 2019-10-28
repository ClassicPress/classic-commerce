<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package Classic Commerce/Templates
 * @version WC-3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
