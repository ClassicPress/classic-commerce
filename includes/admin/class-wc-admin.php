<?php
/**
 * Classic Commerce Admin
 *
 * @class    WC_Admin
 * @author   WooThemes
 * @category Admin
 * @package  ClassicCommerce/Admin
 * @version  WC-2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WC_Admin class.
 */
class WC_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditional_includes' ) );
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_action( 'admin_init', array( $this, 'preview_emails' ) );
		add_action( 'admin_init', array( $this, 'prevent_admin_access' ) );
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
		add_action( 'admin_footer', 'wc_print_js', 25 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );

		// Add body class for WP 5.3+ compatibility.
		add_filter( 'admin_body_class', array( $this, 'include_admin_body_class' ), 9999 );

}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 */
	public function buffer() {
		ob_start();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/wc-admin-functions.php';
		include_once dirname( __FILE__ ) . '/wc-meta-box-functions.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-post-types.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-taxonomies.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-menus.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-customize.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-notices.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-assets.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-api-keys.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-webhooks.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-pointers.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-importers.php';
		include_once dirname( __FILE__ ) . '/class-wc-admin-exporters.php';

		// Help Tabs
		if ( apply_filters( 'woocommerce_enable_admin_help_tab', true ) ) {
			include_once dirname( __FILE__ ) . '/class-wc-admin-help.php';
		}

		// Setup/welcome
		if ( ! empty( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case 'wc-setup':
					include_once dirname( __FILE__ ) . '/class-wc-admin-setup-wizard.php';
					break;
			}
		}

		// Importers
		if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
			include_once dirname( __FILE__ ) . '/class-wc-admin-importers.php';
		}

		// Helper
		include_once dirname( __FILE__ ) . '/helper/class-wc-helper-options.php';
		include_once dirname( __FILE__ ) . '/helper/class-wc-helper.php';
	}

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		switch ( $screen->id ) {
			case 'dashboard':
			case 'dashboard-network':
				include 'class-wc-admin-dashboard.php';
				break;
			case 'options-permalink':
				include 'class-wc-admin-permalink-settings.php';
				break;
			case 'plugins':
				include 'plugin-updates/class-wc-plugins-screen-updates.php';
				break;
			case 'update-core':
				include 'plugin-updates/class-wc-updates-screen-updates.php';
				break;
			case 'users':
			case 'user':
			case 'profile':
			case 'user-edit':
				include 'class-wc-admin-profile.php';
				break;
		}
	}

	/**
	 * Handle redirects to setup/welcome page after install and updates.
	 *
	 * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
	 */
	public function admin_redirects() {
		// Nonced plugin install redirects (whitelisted)
		if ( ! empty( $_GET['wc-install-plugin-redirect'] ) ) {
			$plugin_slug = wc_clean( $_GET['wc-install-plugin-redirect'] );

			if ( current_user_can( 'install_plugins' ) && in_array( $plugin_slug, array( 'woocommerce-gateway-stripe' ) ) ) {
				$nonce = wp_create_nonce( 'install-plugin_' . $plugin_slug );
				$url   = self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug . '&_wpnonce=' . $nonce );
			} else {
				$url = admin_url( 'plugin-install.php?tab=search&type=term&s=' . $plugin_slug );
			}

			wp_safe_redirect( $url );
			exit;
		}

		// Setup wizard redirect
		if ( get_transient( '_wc_activation_redirect' ) ) {
			delete_transient( '_wc_activation_redirect' );

			if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'wc-setup' ) ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_woocommerce' ) || apply_filters( 'woocommerce_prevent_automatic_wizard_redirect', false ) ) {
				return;
			}

			// If the user needs to install, send them to the setup wizard
			if ( WC_Admin_Notices::has_notice( 'install' ) ) {
				wp_safe_redirect( admin_url( 'index.php?page=wc-setup' ) );
				exit;
			}
		}
	}

	/**
	 * Prevent any user who cannot 'edit_posts' (subscribers, customers etc) from accessing admin.
	 */
	public function prevent_admin_access() {
		$prevent_access = false;

		if ( apply_filters( 'woocommerce_disable_admin_bar', true ) && ! is_ajax() && isset( $_SERVER['SCRIPT_FILENAME'] ) && basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_FILENAME'] ) ) ) !== 'admin-post.php' ) {
			$has_cap     = false;
			$access_caps = array( 'edit_posts', 'manage_woocommerce', 'view_admin_dashboard' );

			foreach ( $access_caps as $access_cap ) {
				if ( current_user_can( $access_cap ) ) {
					$has_cap = true;
					break;
				}
			}

			if ( ! $has_cap ) {
				$prevent_access = true;
			}
		}

		if ( apply_filters( 'woocommerce_prevent_admin_access', $prevent_access ) ) {
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
			exit;
		}
	}

	/**
	 * Preview email template.
	 */
	public function preview_emails() {

		if ( isset( $_GET['preview_woocommerce_mail'] ) ) {
			if ( ! ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'preview-mail' ) ) ) {
				die( 'Security check' );
			}

			// load the mailer class
			$mailer = WC()->mailer();

			// get the preview email subject
			$email_heading = __( 'HTML email template', 'classic-commerce' );

			// get the preview email content
			ob_start();
			include 'views/html-email-template-preview.php';
			$message = ob_get_clean();

			// create a new email
			$email = new WC_Email();

			// wrap the content with the email template and then add styles
			$message = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $email_heading, $message ) ) );

			// print the preview email
			echo esc_html( $message );
			exit;
		}
	}

	/**
	 * Change the admin footer text on Classic Commerce admin pages.
	 *
	 * @since  WC-2.3
	 * @param  string $footer_text
	 * @return string
	 */
	public function admin_footer_text( $footer_text ) {
		if ( ! current_user_can( 'manage_woocommerce' ) || ! function_exists( 'wc_get_screen_ids' ) ) {
			return $footer_text;
		}
		$current_screen = get_current_screen();
		$wc_pages       = wc_get_screen_ids();

		// Set only WC pages.
		$wc_pages = array_diff( $wc_pages, array( 'profile', 'user-edit' ) );

		// Check to make sure we're on a Classic Commerce admin page.
		if ( isset( $current_screen->id ) && apply_filters( 'woocommerce_display_admin_footer_text', in_array( $current_screen->id, $wc_pages ) ) ) {
				$footer_text = __( 'Thank you for using Classic Commerce.', 'classic-commerce' );
		}

		return $footer_text;
	}

	/**
	 * Include admin classes.
	 *
	 * @since 4.2.0
	 * @param string $classes Body classes string.
	 * @return string
	 */
	public function include_admin_body_class( $classes ) {
		if ( false !== strpos( $classes, 'wc-wp-version-gte-53' ) ) {
			return $classes;
		}

		$raw_version   = get_bloginfo( 'version' );
		$version_parts = explode( '-', $raw_version );
		$version       = count( $version_parts ) > 1 ? $version_parts[0] : $raw_version;

		// Add WP 5.3+ compatibility class.
		if ( $raw_version && version_compare( $version, '5.3', '>=' ) ) {
			$classes .= ' wc-wp-version-gte-53';
		}

		// Add WP 5.5+ compatibility class.
		if ( $raw_version && version_compare( $version, '5.5', '>=' ) ) {
			$classes .= ' wc-wp-version-gte-55';
		}

		return $classes;
	}

}

return new WC_Admin();
