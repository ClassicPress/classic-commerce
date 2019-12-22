<?php
/**
 * Admin View: Notice - Install
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="message" class="updated woocommerce-message wc-connect">

	<p><?php _e( 'Are you installing WooCommerce specific extensions? You might require the', 'classic-commerce' ); ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-addons#cc-compat' ) ); ?>"><?php _e( 'compatibility plugin.', 'classic-commerce' ); ?></a></p>

	<p class="submit"><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( 'https://github.com/Classic-Commerce/cc-compat-woo/releases' ); ?>" class="button-primary"><?php _e( 'Download compatibility plugin.', 'classic-commerce' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'require_compat_plugin' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'classic-commerce' ); ?></a></p>
	
</div>
