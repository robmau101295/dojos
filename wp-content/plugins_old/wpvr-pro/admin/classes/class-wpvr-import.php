<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Responsible for managing Tour Import feature
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr-pro
 * @subpackage Wpvr-pro/admin/classes
 */

class WPVR_Import {

    /**
     * Prepare tour importing feature 
     * 
     * @return void
     * @since 6.0.0
     */
    public static function prepare_tour_import_feature()
    {
      set_time_limit(20000000000000000);
      self::wpvr_delete_temp_file();

      if ($_POST['fileurl']) {
        WP_Filesystem();
        $file_save_url = wp_upload_dir();
        $attachment_id = $_POST['data_id'];
        $zip_file_path = get_attached_file($attachment_id);
        $unzipfile = unzip_file($zip_file_path, $file_save_url['basedir'] . '/wpvr/temp/');

        if (is_wp_error($unzipfile)) {
          self::wpvr_delete_temp_file();
          wp_send_json_error('Failed to unzip file');
        }

        $result = glob($file_save_url["basedir"] . '/wpvr/temp/*.json');
        if (!$result) {
          self::wpvr_delete_temp_file();
          wp_send_json_error('Tour json file not found');
        }
        
        $tour_json = $result[0];
        $arrContextOptions = array(
          "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
          ),
        );
        $getfile = file_get_contents($tour_json, false, stream_context_create($arrContextOptions));
        
        $file_content = json_decode($getfile, true);

        $new_title = $file_content['title'];
        $new_data = $file_content['data'];

        $new_post_id = wp_insert_post(array(
          'post_title'    => $new_title,
          'post_type'     => 'wpvr_item',
          'post_status'     => 'publish',
        ));
        if ($new_post_id) {
          if ($new_data['panoid']) {
            $new_data['panoid'] = 'pano' . $new_post_id;
          }
          if ($new_data['preview']) {
            $new_data['preview'] = self::prepare_preview_file_to_import($file_save_url, $new_post_id, $new_data);
          }

          if ($new_data['cpLogoImg']) {
            $new_data['cpLogoImg'] = self::prepare_company_log_image_to_import($new_data['cpLogoImg'], $file_save_url, $new_post_id, $new_data);
          }

          if ($new_data['bg_music_url']) {
            $new_data['bg_music_url'] = self::prepare_bg_music_url_to_import($new_data, $file_save_url, $new_post_id);
          }
          if ($new_data['panodata']) {

            if ($new_data['panodata']["scene-list"]) {

              foreach ($new_data['panodata']["scene-list"] as $key => $panoscenes) {

                if ($panoscenes['scene-type'] == 'cubemap') {

                  // face 0
                  if ($panoscenes["scene-attachment-url-face0"]) {
                    $cube_name = '_face0.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }

                  // face 1
                  if ($panoscenes["scene-attachment-url-face1"]) {
                    $cube_name = '_face1.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }

                  // face 2
                  if ($panoscenes["scene-attachment-url-face2"]) {
                    $cube_name = '_face2.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }

                  // face 3
                  if ($panoscenes["scene-attachment-url-face0"]) {
                    $cube_name = '_face3.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }

                  // face 4
                  if ($panoscenes["scene-attachment-url-face4"]) {
                    $cube_name = '_face4.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }

                  // face 5
                  if ($panoscenes["scene-attachment-url-face5"]) {
                    $cube_name = '_face5.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }
                } else {
                  if ($panoscenes["scene-attachment-url"]) {
                    $cube_name = '.jpg';
                    $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = self::prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key);
                  }
                }
              }
            }
            update_post_meta($new_post_id, 'panodata', $new_data);
            self::wpvr_delete_temp_file();
          }
        }
      } else {
        self::wpvr_delete_temp_file();
        wp_send_json_error('No file found to import');
      }
      die();
    }


    /**
     * Prepare tour import feature, if tour has preview file
     * 
     * @param mixed $file_save_url
     * @param mixed $new_post_id
     * @param mixed $new_data
     * 
     * @return void
     * @since 6.0.0
     */
    private static function prepare_preview_file_to_import($file_save_url, $new_post_id, $new_data)
    {
      $preview_url = $file_save_url['baseurl'] . '/wpvr/temp/scene_preview.jpg';
      $media_get = self::wpvr_handle_media_import($preview_url, $new_post_id);
      if ($media_get['status'] == 'error') {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error($media_get['message']);
      } elseif ($media_get['status'] == 'success') {
        return $new_data['preview'] = $media_get['message'];
      } else {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error('Media transfer process failed');
      }
    }


    /**
     * Prepare tour import feature, if tour has company logo
     * 
     * @param mixed $logo
     * @param mixed $file_save_url
     * @param mixed $new_post_id
     * @param mixed $new_data
     * 
     * @return void
     * @since 6.0.0
     */
    private static function prepare_company_log_image_to_import($logo, $file_save_url, $new_post_id, $new_data)
    {
      $get_logo_format = explode(".", $logo);
      $logo_format = end($get_logo_format);
      $logo_img = $file_save_url['baseurl'] . '/wpvr/temp/logo_img.' . $logo_format;
      $media_get = self::wpvr_handle_media_import($logo_img, $new_post_id);
      if ($media_get['status'] == 'error') {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error($media_get['message']);
      } elseif ($media_get['status'] == 'success') {
        return $new_data['cpLogoImg'] = $media_get['message'];
      } else {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error('Media transfer process failed');
      }
    }


    /**
     * Prepare tour import feature, if tour has background music url
     * 
     * @param mixed $new_data
     * @param mixed $file_save_url
     * @param mixed $new_post_id
     * 
     * @return array
     * @since 6.0.0
     */
    private static function prepare_bg_music_url_to_import($new_data, $file_save_url, $new_post_id)
    {
      $music_url = $new_data['bg_music_url'];
      $get_music_format = explode(".", $music_url);
      $music_format = end($get_music_format);
      $music_url = $file_save_url['baseurl'] . '/wpvr/temp/music_url.' . $music_format;
      $media_get = self::wpvr_handle_media_import($music_url, $new_post_id);
      if ($media_get['status'] == 'error') {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error($media_get['message']);
      } elseif ($media_get['status'] == 'success') {
        return $new_data['bg_music_url'] = $media_get['message'];
      } else {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error('Media transfer process failed');
      }
    }


    /**
     * Prepare scene attachment url to import
     * 
     * @param mixed $panoscenes
     * @param mixed $cube_name
     * @param mixed $file_save_url
     * @param mixed $new_post_id
     * @param mixed $key
     * 
     * @return array
     * @since 6.0.0
     */
    private static function prepare_scene_attachment_url_to_import($panoscenes, $cube_name, $file_save_url, $new_post_id, $key)
    {
      $scene_id = $panoscenes['scene-id'];
      $url = $file_save_url['baseurl'] . '/wpvr/temp/' . $scene_id . $cube_name;
      $media_get = self::wpvr_handle_media_import($url, $new_post_id);
      if ($media_get['status'] == 'error') {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error($media_get['message']);
      } elseif ($media_get['status'] == 'success') {
        return $new_data['panodata']["scene-list"][$key]['scene-attachment-url'] = $media_get['message'];
      } else {
        wp_delete_post($new_post_id, true);
        self::wpvr_delete_temp_file();
        wp_send_json_error('Media transfer process failed');
      }
    }


    /**
     * Handle media importing
     * 
     * @param mixed $url
     * @param mixed $post_id
     * 
     * @return array
     * @since 6.0.0
     */
    public static function wpvr_handle_media_import($url, $post_id)
    {
      add_filter('https_ssl_verify', '__return_false');
      add_filter('https_local_ssl_verify', '__return_false');

      $tmp = download_url($url);
      $file_array = array(
          'name' => basename($url),
          'tmp_name' => $tmp
      );

      if (is_wp_error($tmp)) {
          @unlink($file_array['tmp_name']);
          return (array(
              'status' => 'error',
              'message' => $tmp->get_error_message()
          ));
      }

      $id = media_handle_sideload($file_array, $post_id);

      if (is_wp_error($id)) {
          @unlink($file_array['tmp_name']);
          return (array(
              'status' => 'error',
              'message' => $tmp->get_error_message()
          ));
      }
      remove_filter('https_ssl_verify', '__return_false');
      remove_filter('https_local_ssl_verify', '__return_false');

      $value = wp_get_attachment_url($id);
      return (array(
          'status' => 'success',
          'message' => $value
      ));
    }


    /**
     * Delete temporary files
     * 
     * @return void
     * @since 6.0.0
     */
    public static function wpvr_delete_temp_file()
    {
      $filesToDelete = array();
      $file_save_url = wp_upload_dir();
      $rootPath = realpath($file_save_url['basedir'] . '/wpvr/temp/');
      $files = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($rootPath),
          RecursiveIteratorIterator::LEAVES_ONLY
      );
      foreach ($files as $name => $file) {
          if (!$file->isDir()) {
              $filePath = $file->getRealPath();
              $filesToDelete[] = $filePath;
          }
      }
  
      foreach ($filesToDelete as $file) {
          unlink($file);
      }
    }

}
