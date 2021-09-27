<?php

namespace ClassicCommerce\ClassicCommerce;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}


class UpdateClientConfig {

	public function __construct() {

		// Deal with privacy options.
		add_filter( 'codepotent_update_manager_filter_classic-commerce/classic-commerce.php_client_request',	array( $this, 'client_request' ) );

		// Deal with images in a custom location.
		add_filter( 'codepotent_update_manager_classic-commerce/classic-commerce.php_image_path', 				array( $this, 'patch_paths' ) );
		add_filter( 'codepotent_update_manager_classic-commerce/classic-commerce.php_image_url' , 				array( $this, 'patch_paths' ) );

	}

	public function client_request( $config ) {
		if ( 'no' === get_option( 'cc_usage_tracking', 'no' ) ) {
			$config['sfum'] = 'no-log';
		}
		return $config;
	}

	public function patch_paths( $path ) {
		return preg_replace( '/images$/', 'assets/images', $path );
	}

}

new UpdateClientConfig;
