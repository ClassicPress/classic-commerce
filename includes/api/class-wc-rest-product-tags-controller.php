<?php
/**
 * REST API Product Tags controller
 *
 * Handles requests to the products/tags endpoint.
 *
 * @package ClassicCommerce/API
 * @since   WC-2.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Product Tags controller class.
 *
 * @package ClassicCommerce/API
 * @extends WC_REST_Product_Tags_V2_Controller
 */
class WC_REST_Product_Tags_Controller extends WC_REST_Product_Tags_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';
}
