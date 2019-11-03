<?php
/**
 * REST API Product Attributes controller
 *
 * Handles requests to the products/attributes endpoint.
 *
 * @package ClassicCommerce/API
 * @since   WC-2.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Product Attributes controller class.
 *
 * @package ClassicCommerce/API
 * @extends WC_REST_Product_Attributes_V2_Controller
 */
class WC_REST_Product_Attributes_Controller extends WC_REST_Product_Attributes_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';
}
