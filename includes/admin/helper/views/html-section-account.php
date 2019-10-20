<?php defined( 'ABSPATH' ) or exit(); ?>

<a class="button button-update" href="<?php echo esc_url( $refresh_url ); ?>"><span class="dashicons dashicons-image-rotate"></span> <?php _e( 'Update', 'classic-commerce' ); ?></a>
<div class="user-info">
	<header>
		<p><?php printf( __( 'Connected to WooCommerce.com', 'classic-commerce' ) ); ?> <span class="chevron dashicons dashicons-arrow-down-alt2"></span></p>
	</header>
	<section>
		<p><?php echo get_avatar( $auth_user_data['email'], 48 ); ?> <?php echo esc_html( $auth_user_data['email'] ); ?></p>
		<div class="actions">
			<a class="" href="https://woocommerce.com/my-account/my-subscriptions/" target="_blank"><span class="dashicons dashicons-admin-generic"></span> <?php _e( 'My Subscriptions', 'classic-commerce' ); ?></a>
			<a class="" href="<?php echo esc_url( $disconnect_url ); ?>"><span class="dashicons dashicons-no"></span> <?php _e( 'Disconnect', 'classic-commerce' ); ?></a>
		</div>
	</section>
</div>
