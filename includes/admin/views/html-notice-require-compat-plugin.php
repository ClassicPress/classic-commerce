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
<div id="message" class="updated woocommerce-message wc-connect">

	<p><?php esc_html_e( 'Are you installing WooCommerce specific extensions? You might require the', 'classic-commerce' ); ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-addons#cc-compat' ) ); ?>"><?php esc_html_e( 'compatibility plugin.', 'classic-commerce' ); ?></a></p>

	<p class="submit"><a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( 'https://github.com/Classic-Commerce/cc-compat-woo/releases' ); ?>" class="button-primary"><?php esc_html_e( 'Download compatibility plugin.', 'classic-commerce' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'require_compat_plugin' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'classic-commerce' ); ?></a></p>
	
</div>
