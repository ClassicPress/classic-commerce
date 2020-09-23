<?php
/**
 * Admin View: Notice - Theme Support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div id="message" class="updated woocommerce-message wc-connect">
	<a class="woocommerce-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-hide-notice', 'theme_support' ), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce' ) ); ?>"><?php _e( 'Dismiss', 'classic-commerce' ); ?></a>

	<p><?php printf( __( '<strong>Your theme does not declare Classic Commerce support</strong> &#8211; Please read our <a href="%1s" target="_blank">integration</a>.', 'classic-commerce' ), esc_url( apply_filters( 'woocommerce_docs_url', 'https://classiccommerce.cc/docs/installation-and-setup/theme-compatibility/', 'theme-compatibility' ) ) ); ?></p>
	<p class="submit">
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_docs_url', 'https://classiccommerce.cc/docs/installation-and-setup/theme-compatibility/' ) ); ?>" class="button-secondary" target="_blank"><?php _e( 'Theme integration guide', 'classic-commerce' ); ?></a>
	</p>
</div>
