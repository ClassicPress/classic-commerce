<?php
/**
 * Tax settings.
 *
 * @package Settings.
 */

defined( 'ABSPATH' ) || exit;

$settings = array(

	array(
		'title' => __( 'Tax options', 'classic-commerce' ),
		'type'  => 'title',
		'desc'  => '',
		'id'    => 'tax_options',
	),

	array(
		'title'    => __( 'Prices entered with tax', 'classic-commerce' ),
		'id'       => 'woocommerce_prices_include_tax',
		'default'  => 'no',
		'type'     => 'radio',
		'desc_tip' => __( 'This option is important as it will affect how you input prices. Changing it will not update existing products.', 'classic-commerce' ),
		'options'  => array(
			'yes' => __( 'Yes, I will enter prices inclusive of tax', 'classic-commerce' ),
			'no'  => __( 'No, I will enter prices exclusive of tax', 'classic-commerce' ),
		),
	),

	array(
		'title'    => __( 'Calculate tax based on', 'classic-commerce' ),
		'id'       => 'woocommerce_tax_based_on',
		'desc_tip' => __( 'This option determines which address is used to calculate tax.', 'classic-commerce' ),
		'default'  => 'shipping',
		'type'     => 'select',
		'class'    => 'wc-enhanced-select',
		'options'  => array(
			'shipping' => __( 'Customer shipping address', 'classic-commerce' ),
			'billing'  => __( 'Customer billing address', 'classic-commerce' ),
			'base'     => __( 'Shop base address', 'classic-commerce' ),
		),
	),

	'shipping-tax-class' => array(
		'title'    => __( 'Shipping tax class', 'classic-commerce' ),
		'desc'     => __( 'Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves.', 'classic-commerce' ),
		'id'       => 'woocommerce_shipping_tax_class',
		'css'      => 'min-width:150px;',
		'default'  => 'inherit',
		'type'     => 'select',
		'class'    => 'wc-enhanced-select',
		'options'  => array( 'inherit' => __( 'Shipping tax class based on cart items', 'classic-commerce' ) ) + wc_get_product_tax_class_options(),
		'desc_tip' => true,
	),

	array(
		'title'   => __( 'Rounding', 'classic-commerce' ),
		'desc'    => __( 'Round tax at subtotal level, instead of rounding per line', 'classic-commerce' ),
		'id'      => 'woocommerce_tax_round_at_subtotal',
		'default' => 'no',
		'type'    => 'checkbox',
	),

	array(
		'title'    => __( 'Additional tax classes', 'classic-commerce' ),
		'desc_tip' => __( 'List additional tax classes below (1 per line). This is in addition to the default "Standard rate".', 'classic-commerce' ),
		'id'       => 'woocommerce_tax_classes',
		'css'      => 'width:100%; height: 65px;',
		'type'     => 'textarea',
		/* Translators: %s New line char. */
		'default'  => sprintf( __( 'Reduced rate%sZero rate', 'classic-commerce' ), PHP_EOL ),
	),

	array(
		'title'   => __( 'Display prices in the shop', 'classic-commerce' ),
		'id'      => 'woocommerce_tax_display_shop',
		'default' => 'excl',
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'options' => array(
			'incl' => __( 'Including tax', 'classic-commerce' ),
			'excl' => __( 'Excluding tax', 'classic-commerce' ),
		),
	),

	array(
		'title'   => __( 'Display prices during cart and checkout', 'classic-commerce' ),
		'id'      => 'woocommerce_tax_display_cart',
		'default' => 'excl',
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'options' => array(
			'incl' => __( 'Including tax', 'classic-commerce' ),
			'excl' => __( 'Excluding tax', 'classic-commerce' ),
		),
	),

	array(
		'title'       => __( 'Price display suffix', 'classic-commerce' ),
		'id'          => 'woocommerce_price_display_suffix',
		'default'     => '',
		'placeholder' => __( 'N/A', 'classic-commerce' ),
		'type'        => 'text',
		'desc_tip'    => __( 'Define text to show after your product prices. This could be, for example, "inc. Vat" to explain your pricing. You can also have prices substituted here using one of the following: {price_including_tax}, {price_excluding_tax}.', 'classic-commerce' ),
	),

	array(
		'title'    => __( 'Display tax totals', 'classic-commerce' ),
		'id'       => 'woocommerce_tax_total_display',
		'default'  => 'itemized',
		'type'     => 'select',
		'class'    => 'wc-enhanced-select',
		'options'  => array(
			'single'   => __( 'As a single total', 'classic-commerce' ),
			'itemized' => __( 'Itemized', 'classic-commerce' ),
		),
		'autoload' => false,
	),

	array(
		'type' => 'sectionend',
		'id'   => 'tax_options',
	),

);

if ( ! wc_shipping_enabled() ) {
	unset( $settings['shipping-tax-class'] );
}

return apply_filters( 'woocommerce_tax_settings', $settings );
