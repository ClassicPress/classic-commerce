<?php
/**
 * Admin View: Notice - Updated
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="updated woocommerce-message wc-connect woocommerce-message--success">
	<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'update', remove_query_arg( 'do_update_woocommerce' ) ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'classic-commerce' ); ?></a>

	<p><?php _e( 'Classic Commerce data update complete. Thank you for updating to the latest version!', 'classic-commerce' ); ?></p>
</div>
