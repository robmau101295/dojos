<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      6.0.0
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/includes
 * @author     Rextheme <support@rextheme.com>
 */
class Wpvr_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    6.0.0
	 * @access   protected
	 * @var      Wpvr_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    6.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    6.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    6.0.0
	 */
	public function __construct() {
		if ( defined( 'WPVR_PRO_VERSION' ) ) {
			$this->version = WPVR_PRO_VERSION;
		} else {
			$this->version = '6.0.0';
		}
		$this->plugin_name = 'wpvr-pro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpvr_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Wpvr_Pro_i18n. Defines internationalization functionality.
	 * - Wpvr_Pro_Admin. Defines all hooks for the admin area.
	 * - Wpvr_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    6.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
         * The class responsible for auto loading all files of the core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpvr-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpvr-pro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpvr-pro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpvr-pro-public.php';

		$this->loader = new Wpvr_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpvr_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    6.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpvr_Pro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    6.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpvr_Pro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//== License Management Hooks ==//
		$this->loader->add_action( 'wpvr_pro_license_page', $plugin_admin, 'wpvr_pro_license_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpvr_edd_register_option' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpvr_edd_activate_license' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wpvr_edd_deactivate_license' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpvr_edd_admin_notices' );
		$this->loader->add_action( 'wpvr_license_check', $plugin_admin, 'wpvr_license_check_callback');

		//== WPVR Activate or Premium Version Management Hooks ==//
		$this->loader->add_action( 'delete_post', $plugin_admin, 'wpvr_delete_export_data', 10 );
		$this->loader->add_action( 'admin_action_wpvr_duplicate_post_as_draft', $plugin_admin, 'wpvr_duplicate_post_as_draft' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'wpvr_duplicate_post_link', 10, 2 );
		$this->loader->add_filter( 'is_wpvr_pro_active', $plugin_admin, 'is_wpvr_pro_active' );
		$this->loader->add_filter( 'is_wpvr_premium', $plugin_admin, 'is_wpvr_premium' );
		$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'wpvr_json_mime_types' );
		$this->loader->add_filter( 'map_meta_cap', $plugin_admin, 'wpvr_unrestricted_upload_filter', 0, 2 );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'wpvr_weekly_cron_schedule');

		$this->loader->add_filter('change_asset_url', $plugin_admin, 'update_asset_url_pro_version');
		$this->loader->add_filter('check_pro_license_status', $plugin_admin, 'check_wpvr_edd_license_status');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    6.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpvr_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    6.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     6.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     6.0.0
	 * @return    Wpvr_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     6.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
