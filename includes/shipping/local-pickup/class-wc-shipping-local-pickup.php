<?php
/**
 * Class WC_Shipping_Local_Pickup file.
 *
 * @package ClassicCommerce\Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Local Pickup Shipping Method.
 *
 * A simple shipping method allowing free pickup as a shipping method.
 *
 * @class       WC_Shipping_Local_Pickup
 * @version     2.6.0
 * @package     ClassicCommerce/Classes/Shipping
 */
class WC_Shipping_Local_Pickup extends WC_Shipping_Method {

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'local_pickup';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'Local pickup', 'classic-commerce' );
		$this->method_description = __( 'Allow customers to pick up orders themselves. By default, when using local pickup store base taxes will apply regardless of customer address.', 'classic-commerce' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();
	}

	/**
	 * Initialize local pickup.
	 */
	public function init() {

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title      = $this->get_option( 'title' );
		$this->tax_status = $this->get_option( 'tax_status' );
		$this->cost       = $this->get_option( 'cost' );

		// Actions.
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Calculate local pickup shipping.
	 *
	 * @param array $package Package information.
	 */
	public function calculate_shipping( $package = array() ) {
		$this->add_rate(
			array(
				'label'   => $this->title,
				'package' => $package,
				'cost'    => $this->cost,
			)
		);
	}

	/**
	 * Init form fields.
	 */
	public function init_form_fields() {
		$this->instance_form_fields = array(
			'title'      => array(
				'title'       => __( 'Title', 'classic-commerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'classic-commerce' ),
				'default'     => __( 'Local pickup', 'classic-commerce' ),
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
				'title'       => __( 'Cost', 'classic-commerce' ),
				'type'        => 'text',
				'placeholder' => '0',
				'description' => __( 'Optional cost for local pickup.', 'classic-commerce' ),
				'default'     => '',
				'desc_tip'    => true,
			),
		);
	}
}
