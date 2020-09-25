<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/notices/error.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>
<ul class="woocommerce-error" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
				echo wc_kses_notice( $message );
			?>
		</li>
	<?php endforeach; ?>
</ul>
