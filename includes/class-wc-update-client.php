<?php

/**
 * -----------------------------------------------------------------------------
 * Purpose: Remote client to communicate with the Update Manager plugin.
 * Package: CodePotent\UpdateManager
 * Version: 1.0.0
 * Author: Code Potent
 * Author URI: https://codepotent.com
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright © 2019 - CodePotent
 * -----------------------------------------------------------------------------
 *           ____          _      ____       _             _
 *          / ___|___   __| | ___|  _ \ ___ | |_ ___ _ __ | |_
 *         | |   / _ \ / _` |/ _ \ |_) / _ \| __/ _ \ '_ \| __|
 *         | |__| (_) | (_| |  __/  __/ (_) | ||  __/ | | | |_
 *          \____\___/ \__,_|\___|_|   \___/ \__\___|_| |_|\__|.com
 *
 * -----------------------------------------------------------------------------
 */

// EDIT HERE: Make this unique. Ex: YourDevName\YourPluginName;
namespace ClassicCommerce\ClassicCommerce;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

/**
 * Remote updater class for ClassicPress plugins.
 *
 * This class is used in conjunction with the Update Manager plugin to create an
 * integrated update path for end-users of your ClassicPress plugins. This class
 * is intended for plugins that will be receiving updates directly from a remote
 * server, such as GitHub or your own site. The Update Manager ensures that your
 * updates look great with all the right images and texts. If you need some more
 * context, skip to: https://codepotent.com/classicpress/plugins/update-manager/
 *
 * @author John Alarcon
 */
class UpdateClient {

	// Object config data.
	private $config;

	// Default value for CP latest version.
	private $cp_latest_version = '4.9.99';

	// Instance of the object.
	private static $instance = null;

	/**
	 * Constructor.
	 *
	 * The constructor simply sets up the object properties.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		// Configure the update object.
		$this->config = [
			// EDIT HERE: The URL where your Update Manager plugin is installed.
			'server' => 'https://classiccommerce.cc/',
			// Leave as-is.
			'type' => 'plugin',
			// Leave as-is. This is plugin-dir/plugin-file.php
			'id' => basename(dirname(__DIR__)).'/'.basename(dirname(__DIR__)).'.php',
			// Leave as-is.
			'api' => '1.0.0',
			// Leave as-is – unless you really know what you're doing. ;)
			'post' => [],
		];

		$this->cp_latest_version = get_option('cp_latest_version', '');

		// Hook the plugin into the system.
		$this->init();

	}

	/**
	 * Get instance of object.
	 *
	 * Returns the current instance of the object. Or, returns a new instance of
	 * the object if it hasn't yet been instantiated.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return object Current instance of the object.
	 */
	public static function get_instance() {

		// Check for existing instance or get a new one.
		if (self::$instance===null) {
			self::$instance = new self;
		}

		// Return the object.
		return self::$instance;

	}

	/**
	 * Initialize the plugin.
	 *
	 * Hook in actions and filters.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 */
	private function init() {

		// Print footer scripts; see comments on the method.
		add_action('admin_print_footer_scripts', [$this, 'print_admin_scripts']);

		// Filter plugin update data into the transient before saving.
		add_filter('pre_set_site_transient_update_plugins', [$this, 'filter_plugin_update_transient']);

		// Filter the plugin install API results.
		add_filter('plugins_api_result', [$this, 'filter_plugins_api_result'], 10, 3);

		// Filter after-install process.
		add_filter('upgrader_post_install', [$this, 'filter_upgrader_post_install'], 11, 3);

	}

	/**
	 * Print admin scripts.
	 *
	 * A jQuery one-liner is required to swap version numbers dynamically in the
	 * modal windows. Also, a few styles are required to removed the rating area
	 * that is autolinked to the WordPress repository.
	 *
	 * Note that scripts and styles should be enqueued with the proper hooks and
	 * not printed directly (as is done here) unless there is a valid reason for
	 * doing so. In this case, the valid reason is simply that this update class
	 * is intended to be a single-file addition to your plugin; if you enqueue a
	 * file, it must be an actual file – this would add needless complication to
	 * implementing the class.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 */
	public function print_admin_scripts() {

		// Grab the current screen.
		$screen = get_current_screen();

		// Only need this JS/CSS on the plugin admin page and updates page.
		if ($screen->base === 'plugins' || $screen->base === 'plugin-install') {
			// Swap "Compatible up to: 4.9.99" with "Compatible up to: 1.1.1".
			echo '<script>jQuery(document).ready(function($){$("ul li:contains(4.9.99)").html("<strong>'.esc_html__('Compatible up to:').'</strong> '.$this->cp_latest_version.'");$(".fyi h3:contains(Reviews)").hide();$(".fyi p:contains(Read all reviews)").hide();});</script>'."\n";
			// Styles for the modal window.
			echo '<style>'."\n";
			// Hide the ratings text and links to WP.org reviews.
			echo '.fyi .counter-container {display:none;}'."\n";
			// Testing note, when shown.
			echo '.plugin_testing_notice > p {margin:0 0 10px;padding:25px;border:1px solid #f00;}'."\n";
			// Ensure wider images do not break the layout.
			echo '#plugin-information-content img{max-width:100%;}'."\n";
			// Modal window header image.
			echo '#plugin-information-title.with-banner{background-size:100% 100%;background-repeat:no-repeat;background-position-x:center;background-position-y:center;background-color:#333;}'."\n";
			// Add space above stars.
			echo '#plugin-information #section-holder #section-reviews .star-rating {margin:15px 0 0 0;}'."\n";
			// Add space below review text.
			echo '#section-reviews p{margin:0;padding-bottom:25px;}'."\n";
			// Add border between reviews.
			echo '#section-reviews p:not(:last-child){border-bottom:1px solid #f2f2f2;}'."\n";
			// And close up.
			echo '</style>'."\n";
		}

	}

	/**
	 * Filter plugin update transient.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param object $value
	 * @return object $value
	 */
	public function filter_plugin_update_transient($value) {

		// Is there a response?
		if (isset($value->response)) {

			// Update the database with the latest version number.
			update_option('cp_latest_version', $this->get_latest_version_number());

			// Get the installed plugins.
			$installed_plugins = $this->get_plugin_data('query_plugins');

			// Iterate over installed plugins.
			foreach($installed_plugins as $plugin=>$data) {

				// Is there a new version?
				if (isset($data['new_version'], $data['slug'], $data['plugin'])) {

					// If icons are found, add their urls to the $data.
					$icons = [];
					//$plugin = dirname($this->config['id']);
					if (!empty($icons = $this->get_plugin_images('icon', dirname($plugin)))) {
						$data['icons'] = $icons;
					}

					// If banners are found, add their urls to the $data.
					$banners = [];
					if (!empty($banners = $this->get_plugin_images('banner', dirname($plugin)))) {
						$data['banners'] = $banners;
					}

					// If screenshots are found, add their urls to the $data.
					$screenshots = [];
					if (!empty($screenshots = $this->get_plugin_images('screenshot', dirname($plugin)))) {
						$data['screenshots'] = $screenshots;
					}

					// Add the update data to the response.
					$value->response[$plugin] = (object)$data;

				} else {

					// If no new version, no update. Unset the entry.
					unset($value->response[$plugin]);

				}

			}

		}

		// Return the updated transient value.
		return $value;

	}

	/**
	 * Filter plugins API result.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param object $res
	 * @param string $action
	 * @param object $args
	 * @return object $res
	 */
	public function filter_plugins_api_result($res, $action, $args) {

		// If needed args are missing, just return the result.
		if (empty($args->slug) || $action !== 'plugin_information') {
			return $res;
		}

		// Create an array of the plugin, ie, 'example'=>'example/example.php'
		$list_plugins = [
			dirname($this->config['id']) => $this->config['id'],
		];

		// Check if plugin exists
		if (!array_key_exists($args->slug, $list_plugins)) {
			return $res;
		}

		// Get the plugin's information.
		$info = $this->get_plugin_data($action, $list_plugins[$args->slug]);

		// If the response has all the right properties, cast $info to object.
		if (isset($info['name'], $info['slug'], $info['external'], $info['sections'])) {
			$res = (object)$info;
		}

		// Return response.
		return $res;

	}

	/**
	 * Filter post-installer.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param object $response
	 * @param array $hook_extra
	 * @param array $result
	 * @return object
	 */
	public function filter_upgrader_post_install($response, $hook_extra, $result) {

		// Not dealing with a plugin install? Bail.
		if (!isset($hook_extra['plugin'])) {
			return $response;
		}

		// Bring variables into scope.
		global $wp_filesystem, $hook_suffix;

		// Destination for new plugin.
		$destination = trailingslashit($result['local_destination']).dirname($hook_extra['plugin']);

		// Move the plugin to the correct location.
		$wp_filesystem->move($result['destination'], $destination);

		// Match'em up.
		$result['destination'] = $destination;

		// Set destination name.
		$result['destination_name'] = dirname($hook_extra['plugin']);

		// What?! Oh, updating a plugin? Sweet.
		if ($hook_suffix === 'update') {

			// Got both of the needed arguments?
			if (isset($_GET['action'], $_GET['plugin'])) {

				// First argument is good?
				if ($_GET['action'] === 'upgrade-plugin') {

					// Next argument is good?
					if ($_GET['plugin'] === $hook_extra['plugin']) {

						// Activate the plugin.
						activate_plugin($hook_extra['plugin']);

					}

				}

			}

		}

		// Return the response unaltered.
		return $response;

	}

	/**
	 * Get plugin data.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param string $action
	 * @param string $plugin
	 * @return array|array|mixed
	 */
	private function get_plugin_data($action, $plugin='') {

		// Localize the platform version.
		global $cp_version;

		// Initialize the data to be posted.
		$body = $this->config['post'];

		// Get plugin(s) and assign to $body.
		if ($action === 'plugin_information') {
			// If querying a single plugin, assign it to the post body.
			$body['plugin'] =  $plugin;
		} else if ($action === 'query_plugins') {
			// If querying for all plugins, assign them to the post body.
			$body['plugins'] = get_plugins();
		}

		// Site URL; allows for particular URLs to test updates before pushing.
		$body['site_url'] = site_url();

		// Images, if any.
		$body['icon_urls'] = $this->get_plugin_images('icon', dirname($plugin));
		$body['banner_urls'] = $this->get_plugin_images('banner', dirname($plugin));
		$body['screenshot_urls'] = $this->get_plugin_images('screenshot', dirname($plugin));

		// The directory where banners and icons are stored.
		$body['image_url'] = untrailingslashit(WP_PLUGIN_URL).'/'.dirname($this->config['id']).'/images';

		// Assemble args to post back to the Update Manager plugin.
		$options = [
			'user-agent' => 'ClassicPress/'.$cp_version.'; '.get_bloginfo('url'),
			'body'       => $body,
			'timeout'    => 20,
		];

		// Args to append to the endpoint URL.
		$url_args = [
			'update' => $action,
			$this->config['type'] => $this->config['id'],
		];

		// Setup both HTTP and HTTPS endpoint URLs.
		$server = set_url_scheme($this->config['server'], 'http');
		$url = $http_url = add_query_arg($url_args, $server);
		if (wp_http_supports(['ssl'])) {
			$url = set_url_scheme($url, 'https');
		}

		// Try posting the data via HTTPS as a first course.
		$raw_response = wp_remote_post(esc_url_raw($url), $options);

		// If remote post failed, try again over HTTP as a fallback.
		if (is_wp_error($raw_response)) {
			$raw_response = wp_remote_post(esc_url_raw($http_url), $options);
		}

		// Still an error? Hey, you tried. Bail.
		if (is_wp_error($raw_response) || 200 != wp_remote_retrieve_response_code($raw_response)) {
			return [];
		}

		// Get the response body; decode it as an array.
		$data = json_decode(trim(wp_remote_retrieve_body($raw_response)), true);

		// Return the reponse body.
		return is_array($data) ? $data : [];

	}

	/**
	 * Get plugin images.
	 *
	 * This method returns URLs to the plugin's icon and banner images which are
	 * used throughout the update process and screens.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Either 'icon' or 'banner'.
	 * @param string $plugin The name (ie, folder-name) of a plugin.
	 * @return array Array of image URLs or empty array.
	 */
	public function get_plugin_images($type, $plugin) {

		// Initialize.
		$images = [];

		// Need argument missing? Bail.
		if (empty($plugin)) {
			return $images;
		}

		// Not a valid size passed in? Bail.
		if (!in_array($type, ['icon', 'banner', 'screenshot'], true)) {
			return $images;
		}

		// Set path and URL to this plugin's own images directory.
		$image_path = untrailingslashit(WP_PLUGIN_DIR).'/'.$plugin.'/images';
		$image_url  = untrailingslashit(WP_PLUGIN_URL).'/'.$plugin.'/images';

		// Banner and icon images are keyed differently; it's a core thing.
		$image_qualities = [
			'icon'   => ['default', '1x',  '2x'],
			'banner' => ['default', 'low', 'high'],
		];

		// Array of dimensions for bannes and icons.
		$image_dimensions = [
			'icon'   => ['default'=>'128',     '1x'=>'128',      '2x'=>'256'],
			'banner' => ['default'=>'772x250', 'low'=>'772x250', 'high'=>'1544x500'],
		];

		// Handle icon and banner requests.
		if ($type === 'icon' || $type === 'banner') {
			// For SVG banners/icons; one tiny loop handles both.
			if (file_exists($image_path.'/'.$type.'.svg')) {
				foreach ($image_qualities[$type] as $key) {
					$images[$key] = $image_url.'/'.$type.'.svg';
				}
			}
			// Ok, no svg. How about png or jpg?
			else {
				// This loop favors png.
				foreach (['jpg', 'png'] as $ext) {
					// Pop keys off the end of the $images_qualities array.
					$all_keys   = $image_qualities[$type];
					$last_key   = array_pop($all_keys);
					$middle_key = array_pop($all_keys);
					// Normal size images found? Add them.
					if (file_exists($image_path.'/'.$type.'-'.$image_dimensions[$type][$middle_key].'.'.$ext)) {
						foreach ($image_qualities[$type] as $key) {
							$images[$key] = $image_url.'/'.$type.'-'.$image_dimensions[$type][$middle_key].'.'.$ext;
						}
					}
					// Retina image found? Add it.
					if (file_exists($image_path.'/'.$type.'-'.$image_dimensions[$type][$last_key].'.'.$ext)) {
						$images[$last_key] = $image_url.'/'.$type.'-'.$image_dimensions[$type][$last_key].'.'.$ext;
					}

				}

			} // if/else

			// Return icon or banner URLs.
			return $images;

		}

		// Oh, banners? Note these are from current version, not new version.
		if ($type === 'screenshot') {

			// Does /images/ directory exists? Prevent notices.
			if (file_exists($image_path)) {

				// Scan the directory.
				$dir_contents = scandir($image_path);

				// Capture only the screenshot URLs.
				foreach ($dir_contents as $name) {
					if (strpos(strtolower($name), 'screenshot') === 0) {
						$start = strpos($name, '-')+1;
						$for = strpos($name, '.')-$start;
						$screenshot_number = substr($name, $start, $for);
						$images[$screenshot_number] = $image_url.'/'.$name;
					}
				}

				// Proper the sort.
				ksort($images);

			}

		}

		// Return any screenshot URLs.
		return $images;

	}

	/**
	 * Retrieve latest ClassicPress version number.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_latest_version_number() {

		// Initialization.
		$version = '';

		// Make a request to the ClassicPress versions API.
		$response = wp_remote_get('https://api-v1.classicpress.net/upgrade/index.php', ['timeout'=>3]);

		// Problems? Bail.
		if (is_wp_error($response) || empty($response)) {
			return;
		}

		// Get decoded reponse.
		$versions = json_decode(wp_remote_retrieve_body($response));

		// Reverse iterate to find the latest version.
		for ($i=count($versions)-1; $i>0; $i--) {
			if (!strpos($versions[$i], 'nightly')) {
				if (!strpos($versions[$i], 'alpha')) {
					if (!strpos($versions[$i], 'beta')) {
						if (!strpos($versions[$i], 'rc')) {
							$version = $versions[$i];
							break;
						}
					}
				}
			}
		} // ie, $version = 1.1.1.json

		// Get just the version.
		if ($version) {
			$version = str_replace('.json', '', $version);
		}

		// Return the version string.
		return $version;

	}

}

// Run it!
UpdateClient::get_instance();