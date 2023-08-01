<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpvr_Fluent_Form_Addon
 * @subpackage Wpvr_Fluent_Form_Addon/admin
 * @author     RexTheme <support@rextheme.com>
 */
class Wpvr_Fluent_Form_Addon_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpvr-fluent-form-addon-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpvr-fluent-form-addon-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function wpvr_addons_list($add_ons) {
        $add_ons[WPVR_FLUENT_FORMS_ITEM_ID] = 'fluent_forms';
        return $add_ons;
    }


    /**
     * fluent form active/not
     *
     * @return bool
     */
	public function is_fluent_forms_addon_active() {
	    return true;
    }


    /**
     * @param $types
     * @return array
     */
    public function wpvr_hotspot_types( $types ) {

        if(apply_filters('is_wpvr_premium', false)) {
            $types['fluent_form'] = __('Fluent Forms', 'wpvr-fluent-form-addon');
            return $types;
        }
        return $types;
    }


    /**
     * Return the selected type
     *
     * @param $type
     * @param $hotspot_type
     * @return string
     */
    public function wpvr_hotspot_type( $type, $hotspot_type ) {
        if(apply_filters('is_wpvr_premium', false)) {
            return $hotspot_type;
        }else {
            if( $hotspot_type === 'fluent_form' ) {
                return $type;
            }
            return $hotspot_type;
        }
    }


    /**
     * Info types
     *
     * @param $info_types
     * @return array
     */
    public function wpvr_hotspot_info_types($info_types) {
        $info_types[] = 'fluent_form';
        return $info_types;
    }


    /**
     * before hotspot hover contents
     *
     * @param $type
     * @param $pano_hotspot
     */
    public function hotspot_info_before_hover_content( $type, $pano_hotspot) {
        $html = '';
        $fluent_form_id = 0;
        if(defined('FLUENTFORM')) {
            global $wpdb;
            $fluent_form_table = $wpdb->prefix.'fluentform_forms';
            $forms = $wpdb->get_results("SELECT * FROM {$fluent_form_table}");

            if(isset($pano_hotspot['fluent-form-id'])) {
                $fluent_form_id = $pano_hotspot['fluent-form-id'];
            }

            $fluent_forms_display = $type === "fluent_form" ? '' : 'display:none;';
            $html .= '<div class="hotspot-fluent-forms" style=' . $fluent_forms_display . '>';
            $html .= '<label for="hotspot-fluent-form"> ' . __('Select your form: ', 'wpvr-fluent-form-addon') . '</label>';
            $html .= '<select class="wpvr-fluent-forms" name="fluent-form-id" id="hotspot-fluent-form">';
            $html .= '<option value="0">Select a form</option>';
            foreach ($forms as $form) {
                $html .= '<option value="' . esc_attr($form->id) . '"' . selected($fluent_form_id, $form->id, false) . '>' . wp_kses_post($form->title) . '</option>';
            }
            $html .= '</select>';
            $html .= '</div>';

        }
        echo $html;
    }

}
