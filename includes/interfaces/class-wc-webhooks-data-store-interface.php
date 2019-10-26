<?php
/**
 * Webhook Data Store Interface
 *
 * @version  3.2.0
 * @package  WooCommerce/Interface
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Webhook data store interface.
 */
interface WC_Webhook_Data_Store_Interface {

	/**
	 * Get API version number.
	 *
	 * @since  WC-3.2.0
	 * @param  string $api_version REST API version.
	 * @return int
	 */
	public function get_api_version_number( $api_version );

	/**
	 * Get all webhooks IDs.
	 *
	 * @since  WC-3.2.0
	 * @return int[]
	 */
	public function get_webhooks_ids();
}
