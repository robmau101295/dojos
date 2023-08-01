<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      6.0.0
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/includes
 * @author     Rextheme <support@rextheme.com>
 */
class Wpvr_Pro_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    6.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpvr-pro',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
