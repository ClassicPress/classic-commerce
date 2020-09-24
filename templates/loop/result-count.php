<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @author  WooThemes
 * @package ClassicCommerce/Templates
 * @version WC-3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="woocommerce-result-count">
	<?php
	if ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( 'Showing the single result', 'Showing all %d results', $total, 'classic-commerce' ), $total );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'classic-commerce' ), $first, $last, $total );
	}
	?>
</p>
