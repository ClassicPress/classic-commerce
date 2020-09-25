<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/classic-commerce/emails/customer-reset-password.php.
 *
 * @see     https://classiccommerce.cc/docs/installation-and-setup/template-structure/
 * @package ClassicCommerce/Templates/Emails
 * @version WC-3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'classic-commerce' ), esc_html( $user_login ) ); ?>
<?php /* translators: %s: Store name */ ?>
<p><?php printf( esc_html__( 'Someone has requested a new password for the following account on %s:', 'classic-commerce' ), esc_html( wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) ); ?></p>
<?php /* translators: %s Customer username */ ?>
<p><?php printf( esc_html__( 'Username: %s', 'classic-commerce' ), esc_html( $user_login ) ); ?></p>
<p><?php esc_html_e( 'If you didn\'t make this request, just ignore this email. If you\'d like to proceed:', 'classic-commerce' ); ?></p>
<p>
	<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>"><?php // phpcs:ignore ?>
		<?php esc_html_e( 'Click here to reset your password', 'classic-commerce' ); ?>
	</a>
</p>
<p><?php esc_html_e( 'Thanks for reading.', 'classic-commerce' ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
