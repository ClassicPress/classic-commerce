<?php
/**
 * Admin View: Notice - Update
 *
 * @package ClassicCommerce\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$update_url = wp_nonce_url(
	add_query_arg( 'do_update_woocommerce', 'true', admin_url( 'admin.php?page=wc-settings' ) ),
	'wc_db_update',
	'wc_db_update_nonce'
);

?>
<div id="message" class="updated woocommerce-message wc-connect">
	<p>
		<strong><?php esc_html_e( 'WooCommerce data update', 'classic-commerce' ); ?></strong> &#8211; <?php esc_html_e( 'We need to update your store database to the latest version.', 'classic-commerce' ); ?>
	</p>
	<p class="submit">
		<a href="<?php echo esc_url( $update_url ); ?>" class="wc-update-now button-primary">
			<?php esc_html_e( 'Run the updater', 'classic-commerce' ); ?>
		</a>
	</p>
</div>
<script type="text/javascript">
	jQuery( '.wc-update-now' ).click( 'click', function() {
		return window.confirm( '<?php echo esc_js( __( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'classic-commerce' ) ); ?>' ); // jshint ignore:line
	});
</script>
