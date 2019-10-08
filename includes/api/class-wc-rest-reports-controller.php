<?php
/**
 * REST API Reports controller
 *
 * Handles requests to the reports endpoint.
 *
 * @package WooCommerce/API
 * @since   2.6.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API Reports controller class.
 *
 * @package WooCommerce/API
 * @extends WC_REST_Reports_V2_Controller
 */
class WC_REST_Reports_Controller extends WC_REST_Reports_V2_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Get reports list.
	 *
	 * @since 3.5.0
	 * @return array
	 */
	protected function get_reports() {
		$reports = parent::get_reports();

		$reports[] = array(
			'slug'        => 'orders/totals',
			'description' => __( 'Orders totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'products/totals',
			'description' => __( 'Products totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'customers/totals',
			'description' => __( 'Customers totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'coupons/totals',
			'description' => __( 'Coupons totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'reviews/totals',
			'description' => __( 'Reviews totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'categories/totals',
			'description' => __( 'Categories totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'tags/totals',
			'description' => __( 'Tags totals.', 'classic-commerce' ),
		);
		$reports[] = array(
			'slug'        => 'attributes/totals',
			'description' => __( 'Attributes totals.', 'classic-commerce' ),
		);

		return $reports;
	}
}
