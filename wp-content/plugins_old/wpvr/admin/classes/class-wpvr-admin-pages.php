<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Admin_Page {

	/**
	 * Instance of WPVR_Admin_Page class
	 * 
	 * @var object
	 * @since 8.0.0
	 */
	static $instance;


	private function __construct()
	{
		// Register WPVR menu
		add_action('admin_menu', array($this, 'wpvr_add_admin_pages'));
		// Display confirmation alert
		add_action('admin_footer', array($this, 'vpvr_confirmation_alert_display'));
	}


	/**
	 * Declared to overwrite magic method __clone()
	 * In order to prevent object cloning	
	 * 
	 * @return void
	 * @since 8.0.0
	 */
	private function __clone()
	{
		// Do nothing
	}


	/**
	 * Create instance of this class
	 * 
	 * @return object
	 * @since 8.0.0
	 */
	public static function getInstance()
	{
		if(!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Admin page setup is specified in this area.
	 * 
	 * @since 8.0.0
	 */
	function wpvr_add_admin_pages() {
		add_menu_page( 'WP VR', 'WP VR', 'manage_options', 'wpvr', array( $this, 'wpvr_admin_doc'),plugins_url(). '/wpvr/images/icon.png' , 25);

		add_submenu_page( 'wpvr', 'WP VR', 'Get Started','manage_options', 'wpvr', array( $this, 'wpvr_admin_doc'));

		add_submenu_page( 'wpvr', 'WP VR', 'Tours','manage_options', 'edit.php?post_type=wpvr_item', NULL);

		add_submenu_page( 'wpvr', 'WP VR', 'Add New Tour','manage_options', 'post-new.php?post_type=wpvr_item', NULL);


        do_action('wpvr_pro_license_page');

        add_submenu_page( 'wpvr', 'WP VR', 'Setup Wizard','manage_options', 'wpvr-setup-wizard', array($this,'wpvr_setup_wizard'));

        if(!is_plugin_active('wpvr-pro/wpvr-pro.php')){
            add_submenu_page('wpvr', '', '<span class="dashicons dashicons-star-filled" style="font-size: 17px; color:#1fb3fb;"></span> ' . __( 'Go Pro', 'wpvr' ), 'manage_options', 'go_wpvr_pro', array($this,'wpvr_redirect_to_pro'));
        }
    }

    public static function wpvr_redirect_to_pro() {
        $url = 'https://rextheme.com/wpvr/#pricing';
        $url = filter_var( $url, FILTER_SANITIZE_URL );
        exit( esc_url( wp_redirect( $url ) ) );
    }

    /**
     * Provide setup wizard area view for the plugin
     *
     * @since 8.0.0
     */
    function wpvr_setup_wizard(){
        require_once plugin_dir_path(__FILE__) . '../partials/wpvr_setup_wizard.php';
    }

	/**
	 * Provide a admin area view for the plugin
	 * 
	 * @since 8.0.0
	 */
	function wpvr_admin_doc() {
        require_once plugin_dir_path(__FILE__) . '../partials/wpvr_documentation.php';
	}


	/**
	 * Provide license key submission or plugin activition page
	 * 
	 * @since 8.0.0
	 */
	function wpvr_pro_admin_doc() {
        require_once plugin_dir_path(__FILE__) . '../partials/wpvr_license.php';
	}
	

	/**
	 * Provide cofiramtion alert for events
	 * 
	 * @since 8.0.0
	 */
	function vpvr_confirmation_alert_display() {
		require_once plugin_dir_path(__FILE__) . '../partials/wpvr_confirmation_alert.php';
	}

}
