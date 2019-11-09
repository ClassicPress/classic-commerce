<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Helper Class
 *
 * The main entry-point for all things related to the Helper.
 */
class WC_Helper {
	/**
	 * A log object returned by wc_get_logger().
	 */
	public static $log;

	/**
	 * Loads the helper class, runs on init.
	 */
	public static function load() {
		add_action( 'current_screen', array( __CLASS__, 'current_screen' ) );
		add_filter( 'extra_plugin_headers', array( __CLASS__, 'extra_headers' ) );
		add_filter( 'extra_theme_headers', array( __CLASS__, 'extra_headers' ) );

		do_action( 'woocommerce_helper_loaded' );
	}

	/**
	 * Get a local plugin/theme entry from product_id.
	 *
	 * @param int $product_id The product id.
	 *
	 * @return array|bool The array containing the local plugin/theme data or false.
	 */
	private static function _get_local_from_product_id( $product_id ) {
		$local = wp_list_filter(
			array_merge(
				self::get_local_woo_plugins(),
				self::get_local_woo_themes()
			), array( '_product_id' => $product_id )
		);

		if ( ! empty( $local ) ) {
			return array_shift( $local );
		}

		return false;
	}

	/**
	 * Additional theme style.css and plugin file headers.
	 *
	 * Format: Woo: product_id:file_id
	 */
	public static function extra_headers( $headers ) {
		$headers[] = 'Woo';
		return $headers;
	}

	/**
	 * Obtain a list of locally installed Woo extensions.
	 */
	public static function get_local_woo_plugins() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins     = get_plugins();
		$woo_plugins = array();

		// Backwards compatibility for woothemes_queue_update().
		$_compat = array();
		if ( ! empty( $GLOBALS['woothemes_queued_updates'] ) ) {
			foreach ( $GLOBALS['woothemes_queued_updates'] as $_compat_plugin ) {
				$_compat[ $_compat_plugin->file ] = array(
					'product_id' => $_compat_plugin->product_id,
					'file_id'    => $_compat_plugin->file_id,
				);
			}
		}

		foreach ( $plugins as $filename => $data ) {
			if ( empty( $data['Woo'] ) && ! empty( $_compat[ $filename ] ) ) {
				$data['Woo'] = sprintf( '%d:%s', $_compat[ $filename ]['product_id'], $_compat[ $filename ]['file_id'] );
			}

			if ( empty( $data['Woo'] ) ) {
				continue;
			}

			list( $product_id, $file_id ) = explode( ':', $data['Woo'] );
			if ( empty( $product_id ) || empty( $file_id ) ) {
				continue;
			}

			$data['_filename']        = $filename;
			$data['_product_id']      = absint( $product_id );
			$data['_file_id']         = $file_id;
			$data['_type']            = 'plugin';
			$woo_plugins[ $filename ] = $data;
		}

		return $woo_plugins;
	}

	/**
	 * Get locally installed Woo themes.
	 */
	public static function get_local_woo_themes() {
		$themes     = wp_get_themes();
		$woo_themes = array();

		foreach ( $themes as $theme ) {
			$header = $theme->get( 'Woo' );

			// Backwards compatibility for theme_info.txt
			if ( ! $header ) {
				$txt = $theme->get_stylesheet_directory() . '/theme_info.txt';
				if ( is_readable( $txt ) ) {
					$txt = file_get_contents( $txt );
					$txt = preg_split( '#\s#', $txt );
					if ( count( $txt ) >= 2 ) {
						$header = sprintf( '%d:%s', $txt[0], $txt[1] );
					}
				}
			}

			if ( empty( $header ) ) {
				continue;
			}

			list( $product_id, $file_id ) = explode( ':', $header );
			if ( empty( $product_id ) || empty( $file_id ) ) {
				continue;
			}

			$data = array(
				'Name'        => $theme->get( 'Name' ),
				'Version'     => $theme->get( 'Version' ),
				'Woo'         => $header,

				'_filename'   => $theme->get_stylesheet() . '/style.css',
				'_stylesheet' => $theme->get_stylesheet(),
				'_product_id' => absint( $product_id ),
				'_file_id'    => $file_id,
				'_type'       => 'theme',
			);

			$woo_themes[ $data['_filename'] ] = $data;
		}

		return $woo_themes;
	}

	/**
	 * Whether WooCommerce has an update available.
	 *
	 * @return bool True if a Woo core update is available.
	 */
	private static function _woo_core_update_available() {
		$updates = get_site_transient( 'update_plugins' );
		if ( empty( $updates->response ) ) {
			return false;
		}

		if ( empty( $updates->response['woocommerce/woocommerce.php'] ) ) {
			return false;
		}

		$data = $updates->response['woocommerce/woocommerce.php'];
		if ( version_compare( WC_VERSION, $data->new_version, '>=' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sort subscriptions by the product_name.
	 *
	 * @param array $a Subscription array
	 * @param array $b Subscription array
	 *
	 * @return int
	 */
	public static function _sort_by_product_name( $a, $b ) {
		return strcmp( $a['product_name'], $b['product_name'] );
	}

	/**
	 * Sort subscriptions by the Name.
	 *
	 * @param array $a Product array
	 * @param array $b Product array
	 *
	 * @return int
	 */
	public static function _sort_by_name( $a, $b ) {
		return strcmp( $a['Name'], $b['Name'] );
	}

	/**
	 * Log a helper event.
	 *
	 * @param string $message Log message.
	 * @param string $level Optional, defaults to info, valid levels:
	 *     emergency|alert|critical|error|warning|notice|info|debug
	 */
	public static function log( $message, $level = 'info' ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		if ( ! isset( self::$log ) ) {
			self::$log = wc_get_logger();
		}

		self::$log->log( $level, $message, array( 'source' => 'helper' ) );
	}
}

WC_Helper::load();
