<?php
/**
 * Settings for PayPal Gateway.
 *
 * @package ClassicCommerce/Classes/Payment
 */

defined( 'ABSPATH' ) || exit;

return array(
	'enabled'               => array(
		'title'   => __( 'Enable/Disable', 'classic-commerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable PayPal Standard', 'classic-commerce' ),
		'default' => 'no',
	),
	'title'                 => array(
		'title'       => __( 'Title', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'classic-commerce' ),
		'default'     => __( 'PayPal', 'classic-commerce' ),
		'desc_tip'    => true,
	),
	'description'           => array(
		'title'       => __( 'Description', 'classic-commerce' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'classic-commerce' ),
		'default'     => __( "Pay via PayPal; you can pay with your credit card if you don't have a PayPal account.", 'classic-commerce' ),
	),
	'email'                 => array(
		'title'       => __( 'PayPal email', 'classic-commerce' ),
		'type'        => 'email',
		'description' => __( 'Please enter your PayPal email address; this is needed in order to take payment.', 'classic-commerce' ),
		'default'     => get_option( 'admin_email' ),
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com',
	),
	'advanced'              => array(
		'title'       => __( 'Advanced options', 'classic-commerce' ),
		'type'        => 'title',
		'description' => '',
	),
	'testmode'              => array(
		'title'       => __( 'PayPal sandbox', 'classic-commerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable PayPal sandbox', 'classic-commerce' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a <a href="%s">developer account</a>.', 'classic-commerce' ), 'https://developer.paypal.com/' ),
	),
	'debug'                 => array(
		'title'       => __( 'Debug log', 'classic-commerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'classic-commerce' ),
		'default'     => 'no',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Log PayPal events, such as IPN requests, inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'classic-commerce' ), '<code>' . WC_Log_Handler_File::get_log_file_path( 'paypal' ) . '</code>' ),
	),
	'ipn_notification'      => array(
		'title'       => __( 'IPN Email Notifications', 'classic-commerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable IPN email notifications', 'classic-commerce' ),
		'default'     => 'yes',
		'description' => __( 'Send notifications when an IPN is received from PayPal indicating refunds, chargebacks and cancellations.', 'classic-commerce' ),
	),
	'receiver_email'        => array(
		'title'       => __( 'Receiver email', 'classic-commerce' ),
		'type'        => 'email',
		'description' => __( 'If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com',
	),
	'identity_token'        => array(
		'title'       => __( 'PayPal identity token', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Optionally enable "Payment Data Transfer" (Profile > Profile and Settings > My Selling Tools > Website Preferences) and then copy your identity token here. This will allow payments to be verified without the need for PayPal IPN.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => '',
	),
	'invoice_prefix'        => array(
		'title'       => __( 'Invoice prefix', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'classic-commerce' ),
		'default'     => 'WC-',
		'desc_tip'    => true,
	),
	'send_shipping'         => array(
		'title'       => __( 'Shipping details', 'classic-commerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Send shipping details to PayPal instead of billing.', 'classic-commerce' ),
		'description' => __( 'PayPal allows us to send one address. If you are using PayPal for shipping labels you may prefer to send the shipping address rather than billing. Turning this option off may prevent PayPal Seller protection from applying.', 'classic-commerce' ),
		'default'     => 'yes',
	),
	'address_override'      => array(
		'title'       => __( 'Address override', 'classic-commerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable "address_override" to prevent address information from being changed.', 'classic-commerce' ),
		'description' => __( 'PayPal verifies addresses therefore this setting can cause errors (we recommend keeping it disabled).', 'classic-commerce' ),
		'default'     => 'no',
	),
	'paymentaction'         => array(
		'title'       => __( 'Payment action', 'classic-commerce' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'classic-commerce' ),
		'default'     => 'sale',
		'desc_tip'    => true,
		'options'     => array(
			'sale'          => __( 'Capture', 'classic-commerce' ),
			'authorization' => __( 'Authorize', 'classic-commerce' ),
		),
	),
	'page_style'            => array(
		'title'       => __( 'Page style', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the name of the page style you wish to use. These are defined within your PayPal account. This affects classic PayPal checkout screens.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'image_url'             => array(
		'title'       => __( 'Image url', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Optionally enter the URL to a 150x50px image displayed as your logo in the upper left corner of the PayPal checkout pages.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'api_details'           => array(
		'title'       => __( 'API credentials', 'classic-commerce' ),
		'type'        => 'title',
		/* translators: %s: URL */
		'description' => sprintf( __( 'Enter your PayPal API credentials to process refunds via PayPal. Learn how to access your <a href="%s">PayPal API Credentials</a>.', 'classic-commerce' ), 'https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#create-an-api-signature' ),
	),
	'api_username'          => array(
		'title'       => __( 'Live API username', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'api_password'          => array(
		'title'       => __( 'Live API password', 'classic-commerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'api_signature'         => array(
		'title'       => __( 'Live API signature', 'classic-commerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'sandbox_api_username'  => array(
		'title'       => __( 'Sandbox API username', 'classic-commerce' ),
		'type'        => 'text',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'sandbox_api_password'  => array(
		'title'       => __( 'Sandbox API password', 'classic-commerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
	'sandbox_api_signature' => array(
		'title'       => __( 'Sandbox API signature', 'classic-commerce' ),
		'type'        => 'password',
		'description' => __( 'Get your API credentials from PayPal.', 'classic-commerce' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Optional', 'classic-commerce' ),
	),
);
