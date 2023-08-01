<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/includes
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
 * @since      1.0.0
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/includes
 * @author     RexTheme <support@rextheme.com>
 */
class Wpvr_Fluent_Form_Addon {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpvr_Fluent_Form_Addon_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPVR_FLUENT_FORM_ADDON_VERSION' ) ) {
			$this->version = WPVR_FLUENT_FORM_ADDON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpvr-fluent-form-addon';

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
	 * - Wpvr_Fluent_Form_Addon_Loader. Orchestrates the hooks of the plugin.
	 * - Wpvr_Fluent_Form_Addon_i18n. Defines internationalization functionality.
	 * - Wpvr_Fluent_Form_Addon_Admin. Defines all hooks for the admin area.
	 * - Wpvr_Fluent_Form_Addon_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpvr-fluent-form-addon-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpvr-fluent-form-addon-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpvr-fluent-form-addon-admin.php';

        /**
         * Check the integrations dependencies
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpvr-fform-dependancy.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpvr-fluent-form-addon-public.php';

		$this->loader = new Wpvr_Fluent_Form_Addon_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpvr_Fluent_Form_Addon_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpvr_Fluent_Form_Addon_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpvr_Fluent_Form_Addon_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

//        $this->loader->add_filter( 'wpvr_addons_list', $plugin_admin, 'wpvr_addons_list');
        $this->loader->add_filter( 'is_fluent_forms_addon_active', $plugin_admin, 'is_fluent_forms_addon_active');


		// hotspot hooks
        $this->loader->add_action( 'hotspot_info_before_hover_content', $plugin_admin, 'hotspot_info_before_hover_content', 10, 2 );
        $this->loader->add_filter( 'wpvr_hotspot_types', $plugin_admin, 'wpvr_hotspot_types' );
        $this->loader->add_filter( 'wpvr_hotspot_type', $plugin_admin, 'wpvr_hotspot_type', 10, 3 );
        $this->loader->add_filter( 'wpvr_hotspot_info_types', $plugin_admin, 'wpvr_hotspot_info_types');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpvr_Fluent_Form_Addon_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


        $this->loader->add_action( 'wpvr_hotspot_content', $plugin_public, 'wpvr_hotspot_content' );
        $this->loader->add_action( 'wpvr_hotspot_content', $plugin_public, 'wpvr_hotspot_content' );
        $this->loader->add_action( 'wpvr_hotspot_tweak_contents', $plugin_public, 'wpvr_hotspot_tweak_contents' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpvr_Fluent_Form_Addon_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
