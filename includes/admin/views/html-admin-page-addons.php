<?php
/**
 * Admin View: Page - Addons
 *
 * @var string $view
 * @var object $addons
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap woocommerce wc_addons_wrap">

	<h2><?php esc_html_e( 'Extensions for Classic Commerce', 'classic-commerce' ); ?></h2>

	<hr>

	<p><?php printf( __( '<strong>Important Note: </strong>Although Classic Commerce is a fork of WooCommerce version 3.5.3, all JetPack and WooCommerce Services integration have been removed.', 'classic-commerce' ) ); ?></p>

	<p><?php esc_html_e( 'Many extensions or plugins designed for WooCommerce will still work with Classic Commerce provided they do not rely on Jetpack or WooCommerce Services.', 'classic-commerce' ); ?></p>

	<p><strong><?php esc_html_e( 'The end user is entirely responsible for choosing, installing, testing and monitoring any extensions or plugins that are needed to provide extra functionality to the Classic Commerce core.', 'classic-commerce' ); ?></strong></p>

	<p><?php esc_html_e( 'Before installing and using any extensions or plugins we strongly recommend that you first work in a test environment. If you are working on a live site please ensure that you have a recent backup.', 'classic-commerce' ); ?></p>

	<p><?php printf( __( 'For discussion and help with finding compatible Classic Commerce addons, use the <a href="%s">ClassicPress community forum</a>.', 'woocommerce' ), 'https://forums.classicpress.net/c/support/classic-commerce/' ); ?></p>

	<p><?php printf( __( 'For problems with the Classic Commerce core files please raise an issue via <a href="%s">Github issues</a>.', 'woocommerce' ), 'https://github.com/ClassicPress-research/classic-commerce/issues/' ); ?></p>

	<hr />

	<p><strong><?php esc_html_e( 'GNU General Public License', 'classic-commerce' ); ?></strong></p>

	<p><?php esc_html_e( 'This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.', 'classic-commerce' ); ?></p>

	<p><?php esc_html_e( 'This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.', 'classic-commerce' ); ?></p>

	<p><?php printf( __( 'You should have received a copy of the GNU General Public License along with this program. If not, see <a href="%1s">www.gnu.org/licenses</a>.', 'woocommerce' ), 'https://www.gnu.org/licenses/' ); ?></p>

</div>


