<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://rextheme.com/
 * @since             5.6.7
 * @package           Wpvr_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       WP VR PRO
 * Plugin URI:        https://rextheme.com/wpvr/
 * Description:       WP VR PRO is an extension of WP VR with premium features.
 * Version:           6.3.1
 * Author:            Rextheme
 * Author URI:        http://rextheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpvr-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 5.6.7 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WPVR_PRO_VERSION', '6.3.1');
define("WPVR_PRO_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));
define('WPVR_EDD_SL_STORE_URL', 'https://rextheme.com/');

/**
 * ID of your product in EDD
 */
define('WPVR_EDD_SL_ITEM_ID', 5075);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpvr-pro-activator.php
 */
function activate_wpvr_pro()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpvr-pro-activator.php';
    Wpvr_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpvr-pro-deactivator.php
 */
function deactivate_wpvr_pro()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpvr-pro-deactivator.php';
    Wpvr_Pro_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wpvr_pro');
register_deactivation_hook(__FILE__, 'deactivate_wpvr_pro');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wpvr-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    5.6.7
 */
function run_wpvr_pro()
{

    $plugin = new Wpvr_Pro();
    $plugin->run();
    new Wpvr_Pro_Dependency_Check('wpvr/wpvr.php', __FILE__, '8.3.1', 'wpvr-pro');
}
run_wpvr_pro();


/**
 * Weekly license check schedule
 * 
 * @since 5.6.7
 */
add_action('admin_init', 'wpvr_license_check_schedule_weekly');

function wpvr_license_check_schedule_weekly()
{
    if (!wp_next_scheduled('wpvr_license_check')) {
        wp_schedule_event(time(), 'weekly', 'wpvr_license_check');
    }
}



/**
 * Pro plugin update checker
 * 
 * @since 5.6.7
 */
add_action('admin_init', 'wpvr_pro_plugin_updater_check');

function wpvr_pro_plugin_updater_check()
{
    $license_key = trim(get_option('wpvr_edd_license_key'));

    $wpvr_updater = new WPVR_PRO_Plugin_Updater(
        WPVR_EDD_SL_STORE_URL,
        __FILE__,
        array(
            'version' => WPVR_PRO_VERSION,         // current version number
            'license' => $license_key,             // license key (used get_option above to retrieve from DB)
            'item_id' => WPVR_EDD_SL_ITEM_ID,     // id of this product in EDD
            'author' => 'Rextheme',             // author of this plugin
            'url' => home_url()
        )
    );
}


if (file_exists(plugin_dir_path(__DIR__) . 'wpvr/public/class-wpvr-public.php')) {

    require_once plugin_dir_path(__DIR__) . 'wpvr/public/class-wpvr-public.php';
    class Wpvrpropublic extends Wpvr_Public
    {
        public function enqueue_scripts()
        {
            $wp_version = get_bloginfo('version');
            if ($wp_version < "5.6") {
                wp_enqueue_script('jquery', 'http://code.jquery.com/jquery-3.5.1.js', array(), true);
                wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), true);
            }

            $notice = '';
            $wpvr_frontend_notice = get_option('wpvr_frontend_notice');
            if ($wpvr_frontend_notice) {
                $notice = get_option('wpvr_frontend_notice_area');
            }
            global $wp;
            $wpvr_script_control = get_option('wpvr_script_control');
            $wpvr_script_list = get_option('wpvr_script_list');
            $allowed_pages_modified = array();
            $allowed_pages = explode(",", $wpvr_script_list);
            foreach ($allowed_pages as $value) {
                $allowed_pages_modified[] = untrailingslashit($value);
            }

            $wpvr_video_script_control = get_option('wpvr_video_script_control');
            $wpvr_video_script_list = get_option('wpvr_video_script_list');
            $allowed_video_pages_modified = array();
            $allowed_video_pages = explode(",", $wpvr_video_script_list);
            foreach ($allowed_video_pages as $vdvalue) {
                $allowed_video_pages_modified[] = untrailingslashit($vdvalue);
            }

            $current_url = home_url(add_query_arg(array(
                $_GET
            ), $wp->request));
            if ($wpvr_script_control == 'true') {
                foreach ($allowed_pages_modified as $value) {
                    if ($value) {
                        if (strpos($current_url, $value) !== false) {
                            wp_enqueue_script('panellium-js', plugin_dir_url(__FILE__) . 'public/lib/pannellum/src/js/pannellum.js', array(), true);
                            wp_enqueue_script('panelliumlib-js', plugin_dir_url(__FILE__) . 'public/lib/pannellum/src/js/libpannellum.js', array(), true);
                            wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'admin/lib/video.js', array(), true); // commented for video js VR
                            // wp_enqueue_script('videojs-js', 'https://vjs.zencdn.net/7.18.1/video.min.js', array(), true);
                            wp_enqueue_script('videojsvr-js', plugin_dir_url(__FILE__) . 'admin/lib/videojs-vr/videojs-vr.js', array(), true); //video js vr
                            wp_enqueue_script('panelliumvid-js', plugin_dir_url(__FILE__) . 'admin/lib/pannellum/src/js/videojs-pannellum-plugin.js', array(), true);
                            wp_enqueue_script('owl', plugin_dir_url(__FILE__) . 'admin/js/owl.carousel.js', array(
                                'jquery'
                            ), false);
                            wp_enqueue_script('jquery_cookie', plugin_dir_url(__FILE__) . 'admin/js/jquery.cookie.js', array('jquery'), true);
                            wp_enqueue_script('wpvr', plugin_dir_url(__FILE__) . 'admin/js/wpvr-public.js', array(
                                'jquery', 'jquery_cookie'
                            ), false);
                            wp_localize_script('wpvr', 'wpvr_public', array(
                                'notice_active' => $wpvr_frontend_notice,
                                'notice' => $notice,
                            ));
                        }
                    }
                }
            } else {
                wp_enqueue_script('panellium-js', plugin_dir_url(__FILE__) . 'public/lib/pannellum/src/js/pannellum.js', array(), true);
                wp_enqueue_script('panelliumlib-js', plugin_dir_url(__FILE__) . 'public/lib/pannellum/src/js/libpannellum.js', array(), true);
                wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'admin/lib/video.js', array(), true); // commented for video js VR
                // wp_enqueue_script('videojs-js', 'https://vjs.zencdn.net/7.18.1/video.min.js', array(), true);
                wp_enqueue_script('videojsvr-js', plugin_dir_url(__FILE__) . 'admin/lib/videojs-vr/videojs-vr.js', array(), true); //video js vr
                wp_enqueue_script('panelliumvid-js', plugin_dir_url(__FILE__) . 'admin/lib/pannellum/src/js/videojs-pannellum-plugin.js', array(), true);
                wp_enqueue_script('owl', plugin_dir_url(__FILE__) . 'admin/js/owl.carousel.js', array(
                    'jquery'
                ), false);
                wp_enqueue_script('jquery_cookie', plugin_dir_url(__FILE__) . 'admin/js/jquery.cookie.js', array('jquery'), true);
                wp_enqueue_script('wpvr', plugin_dir_url(__FILE__) . 'admin/js/wpvr-public.js', array(
                    'jquery', 'jquery_cookie'
                ), false);
                wp_localize_script('wpvr', 'wpvr_public', array(
                    'notice_active' => $wpvr_frontend_notice,
                    'notice' => $notice,
                ));
            }

            $match_found = false;
            if ($wpvr_video_script_control == 'true') {
                foreach ($allowed_video_pages_modified as $value) {
                    if (strpos($current_url, $value) !== false && strpos($current_url, $value) !== 0) {
                        $match_found = true;
                        wp_enqueue_script('videojs-js', plugin_dir_url(__FILE__) . 'admin/js/video.js', array(), true);
                    }
                }

                if (!$match_found) {
                    wp_dequeue_script('videojs-js');
                }
            }
        }
    }
}

add_filter('wp_handle_upload', 'wpvr_compress_media_handler', 10, 2 );
function wpvr_compress_media_handler( $upload, $context ){
    $wpvr_webp_conversion = get_option('wpvr_webp_conversion');
    if($wpvr_webp_conversion == "true") {
        $referer = wp_get_referer();
        parse_str(parse_url($referer)['query'], $params);

        if(!isset($params['post'])) {
            return $upload;
        }
        if ( get_post_type( $params['post'] ) == 'wpvr_item' ) {
            if($upload['type'] == 'image/png') {
                $image = imagecreatefrompng($upload['file']);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                $newPath = preg_replace("/\.png$/", ".webp", $upload['file']);
                $newUrl = preg_replace("/\.png$/", ".webp", $upload['url']);
                $result = imagewebp($image, $newPath, 100);
                if (false === $result) {
                    return $upload;
                }
                imagedestroy($image);
                unlink($upload['file']);
                $upload['file'] = $newPath;
                $upload['url'] = $newUrl;
                $upload['type'] == 'image/webp';
                return $upload;
            }
            elseif($upload['type'] == 'image/jpeg') {
                $image = imagecreatefromjpeg($upload['file']);
                $newPath = preg_replace("/\.jpg$/", ".webp", $upload['file']);
                $newUrl = preg_replace("/\.jpg$/", ".webp", $upload['url']);
                $result = imagewebp($image, $newPath, 100);
                if (false === $result) {
                    return $upload;
                }
                imagedestroy($image);
                unlink($upload['file']);
                $upload['file'] = $newPath;
                $upload['url'] = $newUrl;
                $upload['type'] == 'image/webp';
                return $upload;
            }
            return $upload;
        }
        
        return $upload;
    }
    return $upload;
}
