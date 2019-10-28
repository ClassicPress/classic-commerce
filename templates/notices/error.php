<?php
/**
 * Show error messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/error.php.
 *
 * Template Overrides: https://docs.woocommerce.com/document/template-structure/#section-1
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Classic Commerce/Templates
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
