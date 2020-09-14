<?php

/**
 * -----------------------------------------------------------------------------
 * Purpose: Remote client to communicate with the Update Manager plugin.
 * Author: Code Potent
 * Author URI: https://codepotent.com
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright 2020, Code Potent
 * -----------------------------------------------------------------------------
 *           ____          _      ____       _             _
 *          / ___|___   __| | ___|  _ \ ___ | |_ ___ _ __ | |_
 *         | |   / _ \ / _` |/ _ \ |_) / _ \| __/ _ \ '_ \| __|
 *         | |__| (_) | (_| |  __/  __/ (_) | ||  __/ | | | |_
 *          \____\___/ \__,_|\___|_|   \___/ \__\___|_| |_|\__|.com
 *
 * -----------------------------------------------------------------------------
 */

// EDIT: Make this unique. Example: YourDevName\YourPluginName;
namespace ClassicCommerce\ClassicCommerce;

// EDIT: URL where Update Manager is installed; with trailing slash!
const UPDATE_SERVER = 'https://classiccommerce.cc/';

// EDIT: plugin or theme?
const UPDATE_TYPE = 'plugin';

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

/**
 * Remote updater class for ClassicPress plugin and themes.
 *
 * This class is used in conjunction with the Update Manager plugin to create an
 * integrated update path for end-users of your ClassicPress plugins and themes.
 * This class is for plugins and themes that will get updates directly from some
 * remote location such as GitHub or your own site. The Update Manager plugin is
 * what makes your updates look great with all the right images and texts.
 *
 * @author John Alarcon
 */
class UpdateClient {

	// Instance of the object.
	private static $instance = null;

	// Object config data.
	private $config;

	/**
	 * Default CP version.
	 *
	 * This value is used for comparison in the updates list table by core. This
	 * property can be set to 4.9.x (whatever x might be at the time,) if you're
	 * wanting to be exact. The issue with doing that is that you'd have to bump
	 * that number with every new release of 4.9.x to ensure that core indicates
	 * 100% compatibility in the table. If your plugin or theme is compatible to
	 * WordPress 4.9.x, it is compatible with ClassicPress 1.x.x, so, there will
	 * not be a need for this. Setting it to 4.9.99 ensures don't have to update
	 * it again.
	 */
	private $cp_latest_version = '4.9.99';

	/**
	 * Cached component data
	 *
	 * As pre_set_site_transient_update_plugins is called twice by ClassicPress,
	 * saving this value will cut an extra HTTP call to the update server.
	 */
	private $component_data = '';

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
			// The URL where your Update Manager plugin is installed.
			'server' => UPDATE_SERVER,
			// Leave as-is; may add support for theme updates later.
			'type' => UPDATE_TYPE,
			// Plugin identifier; ie, plugin-folder/plugin-file.php.
			'id' => $this->get_identifier(),
			// Leave as-is.
			'api' => '2.0.0',
			// Leave as-is – tutorial can be created with enough interest.
			'post' => [],
		];

		// Find and store the latest CP version during update process.
		$this->cp_latest_version = get_option('cp_latest_version', '');

		// Hook the update client into the system.
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
		if (self::$instance === null) {
			self::$instance = new self;
		}

		// Return the object.
		return self::$instance;

	}

	/**
	 * Initialize the update manager client.
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

		// Filter the admin row links.
		add_filter($this->config['type'].'_row_meta', [$this, 'filter_component_row_meta'], 10, 2);

		// Filter update data into the transient before saving.
		add_filter('pre_set_site_transient_update_'.$this->config['type'].'s', [$this, 'filter_component_update_transient']);

		// Filter install API results.
		add_filter($this->config['type'].'s_api_result', [$this, 'filter_components_api_result'], 10, 3);

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
	 * is intended to be a single file in your plugin or theme; if you enqueue a
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
			// This will make the jQuery below work with various languages.
			$text1 = esc_html__('Compatible up to:');
			$text2 = esc_html__('Reviews');
			$text3 = esc_html__('Read all reviews');
			// Swap "Compatible up to: 4.9.99" with "Compatible up to: 1.1.1".
			echo '<script>jQuery(document).ready(function($){$("ul li:contains(4.9.99)").html("<strong>'.$text1.'</strong> '.$this->cp_latest_version.'");$(".fyi h3:contains('.$text2.')").hide();$(".fyi p:contains('.$text3.')").hide();});</script>'."\n";
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
			// Add divider below review text.
			echo '#section-reviews p{margin:0;padding-bottom:25px;border-bottom:1px solid #f2f2f2;}'."\n";
			// Lighten paragraph text for screenshot captions.
			echo '#section-screenshots div{margin:0 0 50px;color:#777;}'."\n";
			// Empasize bold text in screenshot captions.
			echo '#section-screenshots div strong{color:#494949;}'."\n";
			// Add border to screenshots.
			echo '#section-screenshots img{padding:3px;border:1px solid #ccc;}'."\n";
			// And close up.
			echo '</style>'."\n";
		}

	}

	/**
	 * Filter the update transient.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @param object $value
	 * @return object $value
	 */
	public function filter_component_update_transient($value) {

		// Is there a response?
		if (isset($value->response)) {

			// Ensure the latest ClassicPress version number is available.
			$this->get_latest_version_number();

			// Get the installed components.
			$components = $this->get_component_data('query_'.$this->config['type'].'s');

			// Iterate over installed components.
			foreach($components as $component=>$data) {

				// Is there a new version?
				if (isset($data['id'], $data['new_version'], $data['package'])) {

					// Add update data to response.
					if ($this->config['type'] === 'plugin') {

						// Plugin images.
						$icons = $banners = $screenshots = [];
						if (!empty($icons = $this->get_plugin_images('icon', dirname($component)))) {
							$data['icons'] = $icons;
						}
						if (!empty($banners = $this->get_plugin_images('banner', dirname($component)))) {
							$data['banners'] = $banners;
						}
						if (!empty($screenshots = $this->get_plugin_images('screenshot', dirname($component)))) {
							$data['screenshots'] = $screenshots;
						}
						// Cast as object.
						$value->response[$component] = (object)$data;

					} else if ($this->config['type'] === 'theme') {

						// Cast as array.
						$value->response[$component] = (array)$data;

					}

				} else {

					// If no new version, no update. Unset the entry.
					unset($value->response[$component]);

				} // if/else

			} // foreach $components

		} // isset($value->response)

		// Return the updated transient value.
		return $value;

	}

	/**
	 * Filter the update transient.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 2.0.0 Use filter_component_update_transient() method instead.
	 *
	 * @param object $value
	 * @return object $value
	 */
	public function filter_plugin_update_transient($value) {
		return $this->filter_component_update_transient($value);
	}

	/**
	 * Filter the API result.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @param object $res
	 * @param string $action
	 * @param object $args
	 * @return object $res
	 */
	public function filter_components_api_result($res, $action, $args) {

		// If needed args are missing, just return the result.
		if (empty($args->slug) || $action !== $this->config['type'].'_information') {
			return $res;
		}

		// Create an array of the plugin or theme slug and identifier.
		$list_components = [
			dirname($this->config['id']) => $this->config['id'],
		];

		// Check if component exists
		if (!array_key_exists($args->slug, $list_components)) {
			return $res;
		}

		// Get the component's information.
		$info = $this->get_component_data($action, $list_components[$args->slug]);

		// If the response has all the right properties, cast $info to object.
		if (isset($info['name'], $info['slug'], $info['external'], $info['sections'])) {
			$res = (object)$info;
		}

		// Return response.
		return $res;

	}

	/**
	 * Filter plugins API result.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 2.0.0 Use filter_components_api_result() method instead.
	 *
	 * @param object $res
	 * @param string $action
	 * @param object $args
	 * @return object $res
	 */
	public function filter_plugins_api_result($res, $action, $args) {
		return $this->filter_components_api_result($res, $action, $args);
	}

	/**
	 * Filter admin row meta.
	 *
	 * A method to add a "View Details" link to the admin row item.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @param array $component_meta Array of metadata (links, typically)
	 * @param string $component_file Ex: plugin-folder/plugin-file.php
	 * @return array $component_meta with an added link.
	 */
	public function filter_component_row_meta($component_meta, $component_file) {

		// Add the link to the plugin's or theme's row, if not already existing.
		if ($this->identifier === $component_file) {
			$anchors_string = implode('', $component_meta);
			$anchor_text = esc_html__('View details');
			if (!preg_match('|(\<a[ \s\S\d]*)('.$anchor_text.')(<\/a>)|', $anchors_string)) {
				$component_meta[] = '<a class="thickbox" href="'.admin_url('/'.$this->config['type'].'-install.php?tab='.$this->config['type'].'-information&'.$this->config['type'].'='.$this->server_slug.'&TB_iframe=true&width=600&height=550').'">'.$anchor_text.'</a>';
			}
		}

		// Return the maybe amended links.
		return $component_meta;

	}

	/**
	 * Filter plugin row meta.
	 *
	 * A method to add a "View Details" link to the plugin's admin row item.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 2.0.0 Use filter_component_row_meta() method instead.
	 *
	 * @param array $plugin_meta Array of metadata (links, typically)
	 * @param string $plugin_file Ex: plugin-folder/plugin-file.php
	 * @return array $plugin_meta with an added link.
	 */
	public function filter_plugin_row_meta($plugin_meta, $plugin_file) {
		return $this->filter_component_row_meta($plugin_meta, $plugin_file);
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

		// Not dealing with an install? Bail.
		if (!isset($hook_extra[$this->config['type']])) {
			return $response;
		}

		// Bring variables into scope.
		global $wp_filesystem, $hook_suffix;

		// Destination for new component.
		$destination = trailingslashit($result['local_destination']).dirname($hook_extra[$this->config['type']]);

		// Move the component to the correct location.
		$wp_filesystem->move($result['destination'], $destination);

		// Match'em up.
		$result['destination'] = $destination;

		// Set destination name.
		$result['destination_name'] = dirname($hook_extra[$this->config['type']]);

		// Updating a plugin or theme?
		if ($hook_suffix === 'update') {
			// Got both of the needed arguments?
			if (isset($_GET['action'], $_GET[$this->config['type']])) {
				// First argument is good?
				if ($_GET['action'] === 'upgrade-'.$this->config['type']) {
					// Next argument is good?
					if ($_GET[$this->config['type']] === $hook_extra[$this->config['type']]) {
						// Activate the component.
						$function = ($this->config['type'] === 'plugin') ? 'activate_plugin' : 'activate_theme';
						$function($hook_extra[$this->config['type']]);
					}
				}
			}
		}

		// Return the response unaltered.
		return $response;

	}

	/**
	 * Get component identifier.
	 *
	 * A plugin identifier (ie, plugin-folder/plugin-file.php) may possibly have
	 * different locations in different implementations. This method is reliable
	 * in determining the identifier, regardless of where the file may exist. In
	 * the case of themes, the identifier is simply a directory name; the method
	 * works for this, as well.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @return string Component identifier; ie, plugin-folder/plugin-file.php or
	 * 										ie, some-theme-directory
	 */
	private function get_identifier() {

		$identifier = '';

		if (UPDATE_TYPE === 'theme') {

			$path_parts = explode('/', str_replace('\\', '/', __FILE__));
			foreach ($path_parts as $n=>$part) {
				if ($part === 'themes') {
					$this->identifier = $identifier = $path_parts[$n+1];
					break;
				}
			}

		} else if (UPDATE_TYPE === 'plugin') {

			// Gain access the get_plugins() function.
			include_once(ABSPATH.'/wp-admin/includes/plugin.php');

			// Get path to plugin dir and this file; make consistent the slashes.
			$dir = explode('/', str_replace('\\', '/', WP_PLUGIN_DIR));
			$file = explode('/', str_replace('\\', '/', __FILE__));

			// Strip plugin dir parts, leaving this plugin's directory at $diff[0].
			$diff = array_diff($file, $dir);

			// This plugin's directory name.
			$this->server_slug = $dir_name = array_shift($diff);

			// Initialization.
			$identifier = '';

			// Find the plugin id that matches the directory name.
			foreach (array_keys(get_plugins()) as $id) {
				if (strpos($id, $dir_name.'/') === 0) {
					$this->identifier = $identifier = $id;
					break;
				}
			}
		}

		// Return the identifier.
		return $identifier;

	}

	/**
	 * Get plugin identifier.
	 *
	 * The plugin identifier (ie, plugin-folder/plugin-file.php) will differ for
	 * different implementations. This method is a reliable way to determine the
	 * directory name and primary PHP file of the plugin, without any assumption
	 * of where this file may exist.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 2.0.0 Replaced with get_identifier() method.
	 *
	 * @return string Plugin identifier; ie, plugin-folder/plugin-file.php
	 */
	private function get_plugin_identifier() {
		return $this->get_identifier();
	}

	/**
	 * Get component data.
	 *
	 * @author John Alarcon
	 *
	 * @since 2.0.0
	 *
	 * @param string $action May be any of the following:
	 * 							plugin_information
	 * 							theme_information
	 * 							query_plugins
	 * 							query_themes
	 * @param string $component Will be 'plugin' or 'theme'.
	 * @return array|array|mixed Data for the plugin or theme.
	 */
	private function get_component_data($action, $component='') {

		// If component data exists, no need to requery; return that data.
		if (!empty($this->component_data)) {
			return $this->component_data;
		}

		// Localize the platform version.
		global $cp_version;

		// Initialize the data to be posted.
		$body = $this->config['post'];

		if ($action === 'plugin_information') {

			// If querying a single plugin, assign it to the post body.
			$body[$this->config['type']] = $component;

		} else if ($action === 'theme_information') {

			// If querying a single theme, assign it to the post body.
			$body[$this->config['type']] = $component;

		} else if ($action === 'query_plugins') {

			// If querying for all plugins, assign them to the post body.
			$body['plugins'] = get_plugins();

		} else if ($action === 'query_themes') {

			// If querying for all themes, preprocess and assign to post body.
			$themes = [];
			$get_themes = wp_get_themes();
			foreach ($get_themes as $theme) {
				$stylesheet = $theme->get_stylesheet();
				$themes[$stylesheet] = [
					'Name'        => $theme->get('Name'),
					'ThemeURI'    => $theme->get('ThemeURI'),
					'Description' => $theme->get('Description'),
					'Author'      => $theme->get('Author'),
					'AuthorURI'   => $theme->get('AuthorURI'),
					'Version'     => $theme->get('Version'),
					'Template'    => $theme->get('Template'),
					'Status'      => $theme->get('Status'),
					'Tags'        => $theme->get('Tags'),
					'TextDomain'  => $theme->get('TextDomain'),
					'DomainPath'  => $theme->get('DomainPath'),
				];
			}
			$body['themes'] = $themes;

		} else {

			return [];

		}

		// Site URL; allows for particular URLs to test updates before pushing.
		$body['site_url'] = site_url();

		// Images, if any.
		if ($this->config['type'] === 'plugin') {
			$body['icon_urls'] = $this->get_plugin_images('icon', dirname($component));
			$body['banner_urls'] = $this->get_plugin_images('banner', dirname($component));
			$body['screenshot_urls'] = $this->get_plugin_images('screenshot', dirname($component));
		}

		// Assemble args to post back to the Update Manager plugin.
		$options = [
			'user-agent' => 'ClassicPress/'.$cp_version.'; '.get_bloginfo('url'),
			'body'       => $body,
			'timeout'    => apply_filters('codepotent_update_manager_timeout', 5),
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

		// Set retrieved data to the object for reuse elsewhere.
		$this->component_data = is_array($data) ? $data : [];

		// Return the reponse body.
		return $this->component_data;

	}

	/**
	 * Get plugin data.
	 *
	 * @author John Alarcon
	 *
	 * @since 1.0.0
	 *
	 * @deprecated 2.0.0 Replaced with get_component_data() method.
	 *
	 * @param string $action
	 * @param string $plugin
	 * @return array|array|mixed
	 */
	private function get_plugin_data($action, $plugin='') {
		return $this->get_component_data($action, $plugin);
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
		$image_path = untrailingslashit(WP_PLUGIN_DIR).'/'.$plugin.'/assets/images';
		$image_url  = untrailingslashit(WP_PLUGIN_URL).'/'.$plugin.'/assets/images';

		// Allow directory location to be filtered.
		$image_path = apply_filters('codepotent_update_manager_image_path', $image_path);
		$image_url  = apply_filters('codepotent_update_manager_image_url', $image_url);

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
				// This loop doesn't break early, so, it favors png.
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

				} // foreach

			} // inner if/else

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

		// Get current ClassicPress version, if stored.
		$version = get_transient('codepotent_update_manager_cp_version');

		// Return version number, if now known.
		if (!empty($version)) {
			return $version;
		}

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
		} // At this point, $version = 1.1.1.json

		// Get just the version portion of the string.
		if ($version) {
			$version = str_replace('.json', '', $version);
		}

		// A transient ensures the query is not run more than every 10 minutes.
		set_transient('codepotent_update_manager_cp_version', $version, MINUTE_IN_SECONDS * 10);

		// Return the version string.
		return $version;

	}

}

// Run it!
UpdateClient::get_instance();