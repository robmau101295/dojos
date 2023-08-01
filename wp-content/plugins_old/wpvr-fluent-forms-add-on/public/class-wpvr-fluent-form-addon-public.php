<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/public
 * @author     RexTheme <support@rextheme.com>
 */
class Wpvr_Fluent_Form_Addon_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpvr_Fluent_Form_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpvr_Fluent_Form_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpvr-fluent-form-addon-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpvr_Fluent_Form_Addon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpvr_Fluent_Form_Addon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpvr-fluent-form-addon-public.js', array(), $this->version, true );
	}


	/**
	 * render fluent form shortcode
	 *
	 * @param $data
	 */
	public function wpvr_hotspot_content($data) {
		$form_ids = [];
        if ($data["hotspot-type"] === 'fluent_form') {
        	if(isset($data['fluent-form-id'])) {
        		$form_ids[] = $data['fluent-form-id'];
				ob_start();
				echo do_shortcode('[fluentform id="'.$data['fluent-form-id'].'"]');?>
				<?php $hotspot_content = ob_get_clean();
				echo $hotspot_content;
			}
        }
    }


	/**
	 * click contents
	 *
	 * @param $scene_data
	 */
    public function wpvr_hotspot_tweak_contents($scene_data) {
	    $html = '';
        foreach ($scene_data as $scene) {
            $hotspots = $scene['hotSpots'];
            foreach ($hotspots as $hotspot) {
            	if($hotspot['hotspot_type'] === 'fluent_form') {
					$html .= $hotspot['clickHandlerArgs'];
				}
            }
        }
        echo $html;
    }
}
