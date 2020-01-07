<?php
/**
 * Admin View: Notice to Install Compatibility Plugin
 *
 * @package Classic Commerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="updated woocommerce-message">
	<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'usage_tracking' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'classic-commerce' ); ?></a>

	<p>
	<?php
		echo wp_kses_post( sprintf(
			/* translators: %s: Opt out setting */
			__( 'Classic Commerce collects <strong>anonymized</strong> and encrypted data to help us to keep track of the plugin installations. <a href="%s">Click here to Opt out</a>', 'classic-commerce' ),
			esc_url( admin_url( 'admin.php?page=wc-settings&tab=account' ) )
		) );
	?>
	</p>
</div>