<?php
/**
 * Addons Page
 *
 * @author   WooThemes
 * @category Admin
 * @package  WooCommerce/Admin
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Admin_Addons Class.
 */
class WC_Admin_Addons {

	/**
	 * Handles output of the addons page in admin.
	 */
	public static function output() {

		/**
		 * Addon page view.
		 */
		include_once dirname( __FILE__ ) . '/views/html-admin-page-addons.php';
	}

}
