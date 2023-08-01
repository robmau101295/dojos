<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rextheme.com
 * @since             1.1.1
 * @package           Wpvr_Fluent_Form_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       WP VR Fluent Forms Add-on
 * Plugin URI:        https://rextheme.com/wpvr/
 * Description:       Hotspot type to select published forms & display in a popup to the visitors.
 * Version:           1.1.1
 * Author:            RexTheme
 * Author URI:        https://rextheme.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpvr-fluent-form-addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPVR_FLUENT_FORM_ADDON_VERSION', '1.1.1' );
define('WPVR_FF_STORE_URLS', 'https://rextheme.com/');
define('WPVR_FLUENT_FORMS_ITEM_ID', 39435);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpvr-fluent-form-addon-activator.php
 */
function activate_wpvr_fluent_form_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpvr-fluent-form-addon-activator.php';
	Wpvr_Fluent_Form_Addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpvr-fluent-form-addon-deactivator.php
 */
function deactivate_wpvr_fluent_form_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpvr-fluent-form-addon-deactivator.php';
	Wpvr_Fluent_Form_Addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpvr_fluent_form_addon' );
register_deactivation_hook( __FILE__, 'deactivate_wpvr_fluent_form_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpvr-fluent-form-addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpvr_fluent_form_addon() {

	$plugin = new Wpvr_Fluent_Form_Addon();
	$plugin->run();

    new Wpvr_Fform_Dependency_Checker( 'wpvr-pro/wpvr-pro.php', __FILE__, '3.8.0', 'wpvr-fluent-form-addon' );

}
run_wpvr_fluent_form_addon();
