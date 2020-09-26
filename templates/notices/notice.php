<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/notices/notice.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates
 * @version WC-3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! $messages ) {
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info">
		<?php
			echo wc_kses_notice( $message );
		?>
	</div>
<?php endforeach; ?>
