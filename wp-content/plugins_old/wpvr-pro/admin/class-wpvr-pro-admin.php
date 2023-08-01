<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpvr_Pro
 * @subpackage Wpvr_Pro/admin
 * @author     Rextheme <support@rextheme.com>
 */
class Wpvr_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    6.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    6.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
	 * Instance of WPVR_Pro_Meta_Field class
	 *
	 * @since    6.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $meta_field;

    /**
     * Instance of WPVR_Background_Tour class
     * 
     * @var object
     * @since 6.0.0
     */
    protected $background_tour;

    /**
     * Instance of WPVR_Street_View class
     * 
     * @var object
     * @since 6.0.0
     */
    protected $street_view;


    /**
     * Instance of WPVR_Export class
     * 
     * @var object
     * @since 6.0.0
     */
    protected $export;
    /**
     * Instance of WPVR_Floor_Plan class
     *
     * @var object
     * @since 6.2.0
     */
    protected $floor_plan;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    6.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->meta_field = new WPVR_Pro_Meta_Field;
        $this->background_tour = WPVR_Background_Tour::getInstance();
        $this->floor_plan = WPVR_Floor_Plan::getInstance();
        $this->street_view = WPVR_Street_View::getInstance();
        $this->export = WPVR_Export::getInstance();
        $this->run_pro_version_admin_hooks();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    6.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpvr_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpvr_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpvr-pro-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    6.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpvr_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpvr_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_editor();
        wp_enqueue_script("jquery-ui-draggable");
		wp_enqueue_script( 'wpvr-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpvr-pro-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script($this->plugin_name, 'admin_url', admin_url());

	}


    /**
     * Initialize all the hooks when pro version is active
     * 
     * @since 6.0.0
     */
    private function run_pro_version_admin_hooks()
    {
        add_action( 'include_background_tour_meta_content',      array($this, 'initialize_background_tour_class'), 10, 1 );
        add_action( 'include_floor_plan_meta_content',           array($this, 'initialize_floor_plan_class'), 10, 1 );
        add_action( 'include_street_view_meta_content',          array($this, 'initialize_street_view_class'), 10, 1 );
        add_filter( 'prepare_scene_pano_array_with_pro_version', array($this, 'add_pro_meta_fields_to_scene_pano_array'), 10, 3 );
    }


    /**
     * Responsible for rendering background tour with free version
     * 
     * @param mixed $postdata
     * 
     * @return void
     * @since 6.0.0
     */
    public function initialize_background_tour_class($postdata)
    {
        $this->background_tour->render_background_tour($postdata);
    }

    /**
     * Responsible for rendering floor plan with pro version
     *
     * @param mixed $postdata
     *
     * @return void
     * @since 6.2.0
     */
    public function initialize_floor_plan_class($postdata)
    {
        $this->floor_plan->render_floor_plan_tour($postdata);
    }


    /**
     * Responsible for rendering street view with free version
     * 
     * @param mixed $postdata
     * 
     * @return void
     * @since 6.0.0
     */
    public function initialize_street_view_class($postdata)
    {
        $this->street_view->render_street_view($postdata);
    }


    public function add_pro_meta_fields_to_scene_pano_array($old_pano_array, $post, $advanced_control)
    {

        //=== Floor plan ==//
        $floor_plan         = isset($_POST['wpvr_floor_plan_enabler']) ? $_POST['wpvr_floor_plan_enabler'] : 'off';
        $floor_plan_image   = isset($_POST['wpvr_floor_plan_imager']) ? $_POST['wpvr_floor_plan_imager'] : null;
        //=== Floor plan ==//
        $floor_plan_data_list = isset($_POST['floor_list_data']) ?  $_POST['floor_list_data'] : null ;
        $floor_plan_data_list = $this->prepare_floor_plan_data($floor_plan_data_list);
        $floor_plan_pointer_position = isset($_POST['floor_list_pointer_position']) ?  $_POST['floor_list_pointer_position'] : null ;
        $floor_plan_pointer_position = $this->prepare_floor_plan_pointer_position($floor_plan_pointer_position);
        $floor_plan_custom_color = isset($post['floor_plan_custom_color']) ? $post['floor_plan_custom_color'] : '';

        //===background tour ===//
        $bg_tour_enabler = sanitize_text_field($post['wpvr_bg_tour_enabler']);
        $bg_tour_title = sanitize_text_field($post['bg_tour_title']);
        $bg_tour_subtitle = sanitize_text_field($post['bg_tour_subtitle']);
        $new_pano_array = array(
            __("keyboardzoom")              => $advanced_control['keyboardzoom'],
            __("diskeyboard")               => $advanced_control['diskeyboard'],
            __("draggable")                 => $advanced_control['draggable'],
            __("mouseZoom")                 => $advanced_control['mouseZoom'],
            __("gyro")                      => $advanced_control['gyro'], 
            __("deviceorientationcontrol")  => $advanced_control['deviceorientationcontrol'],
            __("compass")                   => $advanced_control['compass'],
            __("vrgallery")                 => $advanced_control['vrgallery'], 
            __("vrgallery_title")           => $advanced_control['vrgallery_title'], 
            __("vrgallery_icon_size")       => $advanced_control['vrgallery_icon_size'],
            __("vrgallery_display")         => $advanced_control['vrgallery_display'],
            __("bg_music")                  => $advanced_control['bg_music'], 
            __("bg_music_url")              => $advanced_control['bg_music_url'], 
            __("autoplay_bg_music")         => $advanced_control['autoplay_bg_music'], 
            __("loop_bg_music")             => $advanced_control['loop_bg_music'],
            __("cpLogoSwitch")              => $advanced_control['cpLogoSwitch'], 
            __("cpLogoImg")                 => $advanced_control['cpLogoImg'], 
            __("cpLogoContent")             => $advanced_control['cpLogoContent'], 
            __("hfov")                      => $advanced_control['hfov'], 
            __("maxHfov")                   => $advanced_control['maxHfov'], 
            __("minHfov")                   => $advanced_control['minHfov'],
            __("bg_tour_enabler")           => $bg_tour_enabler,
            __("bg_tour_title")             => $bg_tour_title, 
            __("bg_tour_subtitle")          => $bg_tour_subtitle,
            __("explainerSwitch")           => $advanced_control['explainerSwitch'], 
            __("explainerContent")          => $advanced_control['explainerContent'],

            'floor_plan_tour_enabler'       => $floor_plan,
            'floor_plan_attachment_url'     => $floor_plan_image,
            'floor_plan_data_list'          => $floor_plan_data_list,
            'floor_plan_pointer_position'   => $floor_plan_pointer_position,
            'floor_plan_custom_color'       => $floor_plan_custom_color,
          );
        return array_merge($old_pano_array, $new_pano_array);
        //===background tour end ===//
    }

    /**
     * Prepare Floor plan data list
     * @param $floor_plan_data
     * @return array
     */

    public function prepare_floor_plan_data($floor_plan_data)
    {
        $floor_plan = stripslashes($floor_plan_data);
        $floor_plan_data_list = (array)json_decode($floor_plan);

        return $floor_plan_data_list;


    }

    /**
     * Floor Plan Pointer list
     * @param $floor_plan_pointer_position
     * @return array
     */

    public function prepare_floor_plan_pointer_position($floor_plan_pointer_position)
    {
        $floor_plan = stripslashes($floor_plan_pointer_position);
        $floor_plan_pointer = (array)json_decode($floor_plan);

        return $floor_plan_pointer;


    }
	/**
	 * Add sub menus after WPVR Pro version is activated 
	 * 
	 * @return void
	 * @since 6.0.0
	 */
	function wpvr_pro_license_menu() {
        add_submenu_page( 'wpvr', 'WP VR Add-Ons', 'Add-Ons','manage_options', 'wpvr-addons', array($this, 'wpvr_pro_addons_page'));
        add_submenu_page( 'wpvr', 'WP VR', 'WP VR License','manage_options', 'wpvrpro', array($this, 'wpvr_pro_license_page'));
	}


	/**
	 * Add WPVR license page 
	 * 
	 * @return void
	 * @since 6.0.0
	 */
	function wpvr_pro_license_page() {
	    require_once plugin_dir_path( __FILE__ ) . 'partials/wpvr_license.php';
    }


    public function check_wpvr_edd_license_status($status)
    {
        return get_option('wpvr_edd_license_status');
    }


	/**
	 * Add WPVR addons page
	 * 
	 * @return void
	 * @since 6.0.0
	 */
	function wpvr_pro_addons_page() {
	    require_once plugin_dir_path( __FILE__ ) . 'partials/wpvr_addons.php';
    }


    public function update_asset_url_pro_version($url)
    {
        $pro_asset_url = plugin_dir_url( __FILE__ );
        return $pro_asset_url;
    }


	/**
	 * Return plugin pro version as active
	 * 
	 * @return bool
	 * @since 6.0.0
	 */
	public function is_wpvr_pro_active() {
		return true;
   }


   /**
	 * Edd setup
	 * 
	 * @since 6.0.0
	 */
	public function wpvr_edd_register_option() {
	    register_setting('wpvr_edd_license', 'wpvr_edd_license_key', array($this, 'wpvr_edd_sanitize_license'));

	    // add-on license settings
        $addons = apply_filters('wpvr_addons_list', array());
        if(count($addons)) {
            foreach ($addons as $addon) {
                register_setting("wpvr_{$addon}_license", "wpvr_{$addon}_license_key");
            }
        }

	}


	/**
     * sanitize license key
     *
     * @param $new
     * @return mixed
	 * @since 6.0.0
     */
	private function wpvr_edd_sanitize_license( $new ) {
	    $old = get_option( 'wpvr_edd_license_key' );
	    if( $old && $old != $new ) {
	        delete_option( 'wpvr_edd_license_status' );
	    }
	    return $new;
	}


	/**
	 * Checking license
	 * 
     * @param $item_id
     * @param $license_key
	 * 
     * @return mixed|string|void
	 * @since 6.0.0
     */
    private function license_check( $item_id, $license_key ) {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license_key,
            'item_id'    => $item_id, // The ID of the item in EDD
            'url'        => home_url()
        );

        $response = wp_remote_post( WPVR_EDD_SL_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => http_build_query($api_params) ) );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {	
            return ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __( 'An error occurred, please try again.' );
        }else {
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            return $license_data;
        }
    }


	/**
     * Activate license
	 * 
     *@since 6.0.0
     */
	public function wpvr_edd_activate_license() {
	    if( isset( $_POST['wpvr_edd_license_activate'] ) ) {
	        if( ! check_admin_referer( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ) )
	            return;

	        $license = trim( get_option( 'wpvr_edd_license_key' ) );
            $license_data = $this->license_check( WPVR_EDD_SL_ITEM_ID, $license );

            if(is_object($license_data)) {
                if ( false === $license_data->success ) {
                    switch( $license_data->error ) {
                        case 'expired' :
                            $message = sprintf(
                                __( 'Your license key expired on %s.' ),
                                date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                            );
                            break;
                        case 'revoked' :
                            $message = __( 'Your license key has been disabled.','wpvr' );
                            break;
                        case 'missing' :
                            $message = __( 'Invalid license.' );
                            break;
                        case 'invalid' :
                        case 'site_inactive' :
                            $message = __( 'Your license is not active for this URL.','wpvr' );
                            break;
                        case 'item_name_mismatch' :
                            $message = sprintf( __( 'This appears to be an invalid license key for %s.' ), 'wpvr' );
                            break;
                        case 'no_activations_left':
                            $message = __( 'Your license key has reached its activation limit.','wpvr' );
                            break;
                        default :
                            $message = __( 'An error occurred, please try again.','wpvr' );
                            break;
                    }
                }
            }
            else {
                $message =  $license_data;
            }

	        if ( ! empty( $message ) ) {
	            $base_url = admin_url( 'admin.php?page=wpvrpro' );
	            $redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
	            wp_redirect( $redirect );
	            exit();
	        }

	        	update_option( 'wpvr_edd_license_status', $license_data->license );
            update_option( "wpvr_edd_license_data", serialize(json_decode(json_encode($license_data), true)));
            if ($license_data->success) {
                update_option('wpvr_is_premium', 'yes');
            }
	        wp_redirect( admin_url( 'admin.php?page=wpvrpro') );
	        exit();
	    }
	}


	/**
     * Deactivate license
     *
	 * @since 6.0.0
     */
    public function wpvr_edd_deactivate_license() {
        if( isset( $_POST['wpvr_edd_license_deactivate'] ) ) {
            if( ! check_admin_referer( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ) )
                return;
            $license = trim( get_option( 'wpvr_edd_license_key' ) );
            $license_data = $this->license_check(WPVR_EDD_SL_ITEM_ID, $license);
            if(is_object($license_data)) {
                if (false === $license_data->success) {
                    switch ($license_data->license) {
                        case 'deactivated':
                            $message = __( 'Your license successfully deactivate.' );
                            break;
                        case 'failed':
                            $message = __( 'Your license deactivation failed.' );
                            break;

                    }
                }
            }else {
                $message =  $license_data;
            }

            if ( ! empty( $message ) ) {
                $base_url = admin_url( 'admin.php?page=wpvrpro' );
                $redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
                wp_redirect( $redirect );
                exit();
            }
            update_option( 'wpvr_edd_license_status', '' );
            update_option( 'wpvr_is_premium', 'no' );
            update_option( "wpvr_edd_license_data", serialize(json_decode(json_encode($license_data), true)));
            wp_redirect( admin_url( 'admin.php?page=wpvrpro' ) );
            exit();
        }
    }


	/**
     * Admin notices
     *
	 * @since 6.0.0
     */
	public function wpvr_edd_admin_notices() {
	    if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
	        switch( $_GET['sl_activation'] ) {
	            case 'false':
	                $message = urldecode( $_GET['message'] );
	                ?>
	                <div class="error">
	                    <p><?php echo $message; ?></p>
	                </div>
	                <?php
	                break;
	            case 'true':
	            default:

	            	break;
	        }
	    }
        $status  = get_option( 'wpvr_edd_license_status', 'no');
        if($status === '' || $status === 'no') {
            $msg = __( 'Please %1$sactivate your license%2$s key to enjoy the pro features for %3$s.', 'wpvr-pro' );
            $msg = sprintf( $msg, '<a href="' . admin_url( 'admin.php?page=wpvrpro">'), '</a>', '<strong>WPVR PRO</strong>' );
            echo '<div class="notice notice-error is-dismissible"><p>'.$msg.'</p></div>';
        }
	}


	/**
     * license check call back
     *
	 * @since 6.0.0
     */
    public function wpvr_license_check_callback() {

        $pro_license = get_option( 'wpvr_edd_license_key' );
        if($pro_license) {
            $license_data = $this->license_check( WPVR_EDD_SL_ITEM_ID, $pro_license );
            if(is_object($license_data)) {
                update_option('wpvr_is_premium', $license_data->license == 'valid' ? 'yes' : 'no');
                update_option( "wpvr_edd_license_status", $license_data->license );
                update_option( "wpvr_edd_license_data", serialize(json_decode(json_encode($license_data), true)));
            } else {
                update_option( "wpvr_is_premium", 'no' );
                update_option( "wpvr_edd_license_status", '' );
            }
        }
    }


	/**
     * Delete export data
     *
     * @param $id
	 * 
	 * @return void
	 * @since 6.0.0
     */
	public function wpvr_delete_export_data($id) {
	    $file_save_url = wp_upload_dir();
	    if (file_exists($file_save_url['basedir'].'/wpvr/wpvr_'.$id.'.zip')) {
	        unlink($file_save_url['basedir'].'/wpvr/wpvr_'.$id.'.zip');
	    }
	}


	/**
     * Duplicate posts as draft
	 * 
     *@since 6.0.0
     */
	public function wpvr_duplicate_post_as_draft() {
        global $wpdb;
        if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wpvr_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
        wp_die('No post to duplicate has been supplied!');
        }

        if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
        return;
        $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
        $post = get_post( $post_id );
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        if (isset( $post ) && $post != null) {
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        $new_post_id = wp_insert_post( $args );
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
            $meta_key = $meta_info->meta_key;
            if( $meta_key == '_wp_old_slug' ) continue;
            $meta_value = addslashes($meta_info->meta_value);
            $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
        } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
	}


	/**
     * duplicate post link for wpvr-item
     *
     * @param $actions
     * @param $post
     * @return mixed
	 * 
	 * @since 6.0.0
     */
	function wpvr_duplicate_post_link( $actions, $post ) {
        if($post->post_type == "wpvr_item") {
            $user = wp_get_current_user();
            if ( in_array( 'administrator', (array) $user->roles ) ) {
                if (current_user_can('edit_posts')) {
                    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=wpvr_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
                }
            }
        }
        return $actions;
	}


	/**
     * is the plugin licensed?
     *
     * @return bool
	 * @since 6.0.0
     */
	public function is_wpvr_premium () {
		//===rextheme modification ===//
        $premium = get_option( 'wpvr_is_premium' );
        if ($premium == 'yes') {
            return true;
        }
        return true;
	}


	/**
     * json mime types
     *
     * @param $mimes
     * @return mixed
	 * 
	 * @since 6.0.0
     */
	public function wpvr_json_mime_types( $mimes ) {
		$mimes['json'] = 'json';
		return $mimes;
	}


	/**
	 * Making upload filter unrestricted
     *
     * @param $caps
     * @param $cap
     * @return array
	 * 
	 * @since 6.0.0
     */
	public function wpvr_unrestricted_upload_filter($caps, $cap) {
		if ($cap == 'unfiltered_upload') {
		  $caps = array();
		  $caps[] = $cap;
		}
		return $caps;
	}


	/**
     * Monthly cron schedule
     *
     * @param $schedules
     * @return mixed
	 * 
	 * @since 6.0.0
     */
	public	function wpvr_weekly_cron_schedule($schedules) {
	    $schedules[ 'monthly' ] = array(
	        'interval' => 2635200, # Monthly in seconds
	        'display' => __( 'monthly' ) );
	    return $schedules;
	}

}
