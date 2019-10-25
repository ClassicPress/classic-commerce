<?php
/**
 * Settings for flat rate shipping.
 *
 * @package WooCommerce/Classes/Shipping
 */

defined( 'ABSPATH' ) || exit;

$cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'classic-commerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', 'classic-commerce' );

$settings = array(
	'title'      => array(
		'title'       => __( 'Method title', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'classic-commerce' ),
		'default'     => __( 'Flat rate', 'classic-commerce' ),
		'desc_tip'    => true,
	),
	'tax_status' => array(
		'title'   => __( 'Tax status', 'classic-commerce' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'taxable',
		'options' => array(
			'taxable' => __( 'Taxable', 'classic-commerce' ),
			'none'    => _x( 'None', 'Tax status', 'classic-commerce' ),
		),
	),
	'cost'       => array(
		'title'             => __( 'Cost', 'classic-commerce' ),
		'type'              => 'text',
		'placeholder'       => '',
		'description'       => $cost_desc,
		'default'           => '0',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_cost' ),
	),
);

$shipping_classes = WC()->shipping->get_shipping_classes();

if ( ! empty( $shipping_classes ) ) {
	$settings['class_costs'] = array(
		'title'       => __( 'Shipping class costs', 'classic-commerce' ),
		'type'        => 'title',
		'default'     => '',
		/* translators: %s: URL for link. */
		'description' => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'classic-commerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) ) {
			continue;
		}
		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			/* translators: %s: shipping class name */
			'title'             => sprintf( __( '"%s" shipping class cost', 'classic-commerce' ), esc_html( $shipping_class->name ) ),
			'type'              => 'text',
			'placeholder'       => __( 'N/A', 'classic-commerce' ),
			'description'       => $cost_desc,
			'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names.
			'desc_tip'          => true,
			'sanitize_callback' => array( $this, 'sanitize_cost' ),
		);
	}

	$settings['no_class_cost'] = array(
		'title'             => __( 'No shipping class cost', 'classic-commerce' ),
		'type'              => 'text',
		'placeholder'       => __( 'N/A', 'classic-commerce' ),
		'description'       => $cost_desc,
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $this, 'sanitize_cost' ),
	);

	$settings['type'] = array(
		'title'   => __( 'Calculation type', 'classic-commerce' ),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'class',
		'options' => array(
			'class' => __( 'Per class: Charge shipping for each shipping class individually', 'classic-commerce' ),
			'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'classic-commerce' ),
		),
	);
}

return $settings;
