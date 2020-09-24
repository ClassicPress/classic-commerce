<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
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

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message" role="alert">
		<?php
			echo wc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>
