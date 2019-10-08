<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2 class="wc-shipping-zones-heading">
	<?php _e( 'Shipping zones', 'classic-commerce' ); ?>
	<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=shipping&zone_id=new' ); ?>" class="page-title-action"><?php esc_html_e( 'Add shipping zone', 'classic-commerce' ); ?></a>
</h2>
<p><?php echo __( 'A shipping zone is a geographic region where a certain set of shipping methods are offered.', 'classic-commerce' ) . ' ' . __( 'WooCommerce will match a customer to a single zone using their shipping address and present the shipping methods within that zone to them.', 'classic-commerce' ); ?></p>
<table class="wc-shipping-zones widefat">
	<thead>
		<tr>
			<th class="wc-shipping-zone-sort"><?php echo wc_help_tip( __( 'Drag and drop to re-order your custom zones. This is the order in which they will be matched against the customer address.', 'classic-commerce' ) ); ?></th>
			<th class="wc-shipping-zone-name"><?php esc_html_e( 'Zone name', 'classic-commerce' ); ?></th>
			<th class="wc-shipping-zone-region"><?php esc_html_e( 'Region(s)', 'classic-commerce' ); ?></th>
			<th class="wc-shipping-zone-methods"><?php esc_html_e( 'Shipping method(s)', 'classic-commerce' ); ?></th>
		</tr>
	</thead>
	<tbody class="wc-shipping-zone-rows"></tbody>
	<tbody>
		<tr data-id="0" class="wc-shipping-zone-worldwide">
			<td width="1%" class="wc-shipping-zone-worldwide"></td>
			<td class="wc-shipping-zone-name">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping&zone_id=0' ) ); ?>"><?php esc_html_e( 'Locations not covered by your other zones', 'classic-commerce' ); ?></a>
				<div class="row-actions">
					<a href="admin.php?page=wc-settings&amp;tab=shipping&amp;zone_id=0"><?php _e( 'Manage shipping methods', 'classic-commerce' ); ?></a>
				</div>
			</td>
			<td class="wc-shipping-zone-region"><?php _e( 'This zone is <b>optionally</b> used for regions that are not included in any other shipping zone.', 'classic-commerce' ); ?></td>
			<td class="wc-shipping-zone-methods">
				<ul>
					<?php
					$worldwide = new WC_Shipping_Zone( 0 );
					$methods   = $worldwide->get_shipping_methods();
					uasort( $methods, 'wc_shipping_zone_method_order_uasort_comparison' );

					if ( ! empty( $methods ) ) {
						foreach ( $methods as $method ) {
							$class_name = 'yes' === $method->enabled ? 'method_enabled' : 'method_disabled';
							echo '<li class="wc-shipping-zone-method ' . esc_attr( $class_name ) . '">' . esc_html( $method->get_title() ) . '</li>';
						}
					} else {
						echo '<li class="wc-shipping-zone-method">' . __( 'No shipping methods offered to this zone.', 'classic-commerce' ) . '</li>';
					}
					?>
				</ul>
			</td>
		</tr>
	</tbody>
</table>

<script type="text/html" id="tmpl-wc-shipping-zone-row-blank">
	<?php if ( 0 === $method_count ) : ?>
		<tr>
			<td class="wc-shipping-zones-blank-state" colspan="4">
				<p class="main"><?php _e( 'A shipping zone is a geographic region where a certain set of shipping methods and rates apply.', 'classic-commerce' ); ?></p>
				<p><?php _e( 'For example:', 'classic-commerce' ); ?></p>
				<ul>
					<li><?php _e( 'Local zone = California ZIP 90210 = Local pickup', 'classic-commerce' ); ?>
					<li><?php _e( 'US domestic zone = All US states = Flat rate shipping', 'classic-commerce' ); ?>
					<li><?php _e( 'Europe zone = Any country in Europe = Flat rate shipping', 'classic-commerce' ); ?>
				</ul>
				<p><?php _e( 'Add as many zones as you need &ndash; customers will only see the methods available for their address.', 'classic-commerce' ); ?></p>
				<a class="button button-primary wc-shipping-zone-add" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=shipping&zone_id=new' ) ); ?>"><?php _e( 'Add shipping zone', 'classic-commerce' ); ?></a>
			</td>
		</tr>
	<?php endif; ?>
</script>

<script type="text/html" id="tmpl-wc-shipping-zone-row">
	<tr data-id="{{ data.zone_id }}">
		<td width="1%" class="wc-shipping-zone-sort"></td>
		<td class="wc-shipping-zone-name">
			<a href="admin.php?page=wc-settings&amp;tab=shipping&amp;zone_id={{ data.zone_id }}">{{ data.zone_name }}</a>
			<div class="row-actions">
				<a href="admin.php?page=wc-settings&amp;tab=shipping&amp;zone_id={{ data.zone_id }}"><?php _e( 'Edit', 'classic-commerce' ); ?></a> | <a href="#" class="wc-shipping-zone-delete"><?php _e( 'Delete', 'classic-commerce' ); ?></a>
			</div>
		</td>
		<td class="wc-shipping-zone-region">
			{{ data.formatted_zone_location }}
		</td>
		<td class="wc-shipping-zone-methods">
			<div><ul></ul></div>
		</td>
	</tr>
</script>

<script type="text/template" id="tmpl-wc-modal-add-shipping-method">
	<div class="wc-backbone-modal">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<h1><?php _e( 'Add shipping method', 'classic-commerce' ); ?></h1>
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text"><?php _e( 'Close modal panel', 'classic-commerce' ); ?></span>
					</button>
				</header>
				<article>
					<form action="" method="post">
						<div class="wc-shipping-zone-method-selector">
							<p><?php esc_html_e( 'Choose the shipping method you wish to add. Only shipping methods which support zones are listed.', 'classic-commerce' ); ?></p>

							<select name="add_method_id">
								<?php
								foreach ( WC()->shipping->load_shipping_methods() as $method ) {
									if ( ! $method->supports( 'shipping-zones' ) ) {
										continue;
									}
									echo '<option data-description="' . esc_attr( wp_kses_post( wpautop( $method->get_method_description() ) ) ) . '" value="' . esc_attr( $method->id ) . '">' . esc_attr( $method->get_method_title() ) . '</li>';
								}
								?>
							</select>
							<input type="hidden" name="zone_id" value="{{{ data.zone_id }}}" />
						</div>
					</form>
				</article>
				<footer>
					<div class="inner">
						<button id="btn-ok" class="button button-primary button-large"><?php _e( 'Add shipping method', 'classic-commerce' ); ?></button>
					</div>
				</footer>
			</section>
		</div>
	</div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>
