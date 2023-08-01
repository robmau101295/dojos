<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Responsible for managing Tour Export feature
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr-pro
 * @subpackage Wpvr-pro/admin/classes
 */

class WPVR_Export {

    /**
	 * Instance of WPVR_Export class
	 * 
	 * @var object
	 * @since 6.0.0
	 */
	static $instance;


    private function __construct()
	{
        add_action( 'include_export_meta_content', array($this, 'render_export_meta_content'), 10, 2 );
	}


	/**
	 * Declared to overwrite magic method __clone()
	 * In order to prevent object cloning	
	 * 
	 * @return void
	 * @since 6.0.0
	 */
	private function __clone()
	{
		// Do nothing
	}


    /**
	 * Create instance of this class
	 * 
	 * @return object
	 * @since 6.0.0
	 */
	public static function getInstance()
	{
		if(!(self::$instance instanceof self)) {
			self::$instance = new self();
		}
		return self::$instance;
	}


    /**
     * Render background tour main content
     * 
     * @param mixed $bg_tour_enabler
     * @param mixed $bg_tour_data_display
     * 
     * @return void
     * @since 6.0.0
     */
    public function render_export_meta_content($postdata, $post)
    {
        if (!ini_get('allow_url_fopen')) { 
            $this->render_content_error_message("Please check your server configuration and set allow_url_fopen to 1");
        } elseif (!$this->wpvr_isCurl()) { 
            $this->render_content_error_message("Please enable curl on your server");
        } else {
            if (isset($postdata['panodata'])) {
                $file_save_url = wp_upload_dir();
                $title = get_the_title($post->ID);
                $arrContextOptions = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );
                $import_array = array(
                    'title' => $title,
                    'data' => $postdata,
                );
                $filename = $file_save_url['basedir'] . '/wpvr/temp/wpvr_' . $post->ID . '.json';
                $file_download_url = $file_save_url['baseurl'] . '/wpvr/wpvr_' . $post->ID . '.zip';
                $imported = file_put_contents($filename, json_encode($import_array));

                $warning = '';
                $file_accessible = ini_get('allow_url_fopen');

                //------If preview file found set data or create warning-------//
                if ($postdata['preview']) {

                    $warning = $this->set_warning_for_preview_file($postdata['preview'], $arrContextOptions, $file_accessible, $file_save_url);

                }

                if ($postdata['cpLogoImg']) {
                    
                    $warning = $this->set_warning_for_file($postdata['cpLogoImg'], $file_accessible, $file_save_url, $arrContextOptions);

                }

                if ($postdata['bg_music_url']) {
                    
                    $warning = $this->set_warning_for_file($postdata['bg_music_url'], $file_accessible, $file_save_url, $arrContextOptions);

                }

                //------Scene array and media file transfer to temp file started-------//
                if ($postdata['panodata']["scene-list"]) {
                    
                    $warning = $this->set_warning_for_panodata_scenelist($postdata['panodata']["scene-list"], $file_accessible, $file_save_url, $arrContextOptions);

                }

                //------Begining of zip file creation-------//
                if ($warning == "") {
                    $rootPath = realpath($file_save_url['basedir'] . '/wpvr/temp/');
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive();
                        $zip->open($file_save_url['basedir'] . '/wpvr/wpvr_' . $post->ID . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
                        foreach ($files as $name => $file) {
                            if (!$file->isDir()) {
                                $filePath = $file->getRealPath();
                                $relativePath = substr($filePath, strlen($rootPath) + 1);
                                $zip->addFile($filePath, $relativePath);
                                $filesToDelete[] = $filePath;
                            }
                        }
                        $zip->close();
                        foreach ($filesToDelete as $file) {
                            unlink($file);
                        }
                    } else {
                        $warning = 'Zip extension is not enabled on your php version. Please install <a href="https://www.php.net/manual/en/zip.installation.php">Zip extension</a>';
                    }
                }

                //------Import button added here if zip file created successfully-------//
                ob_start();
                ?>
                <div class="rex-pano-tab export" id="import">
                    <h6 class="title"> <?= __('Export Tour : ', 'wpvr-pro') ?></h6>
                    <?php if ($warning) { ?>
                        <p style="color:red;" ><?php echo $warning; ?></p>
                    <?php } else { ?>
                        <a href="<?= $file_download_url ?>" class="vr-export"><?= __('Download', 'wpvr-pro') ?></a>
                    <?php } ?>
                </div>
                <?php
                ob_end_flush();
            }
        }
        
    }


    /**
     * Render Error messages based on different conditions 
     * 
     * @param mixed $msg
     * 
     * @return void
     * @since 6.0.0
     */
    private function render_content_error_message($msg)
    {
        ob_start();
        ?>
        <div class="rex-pano-tab export" id="import">
                <h6 class="title"> <?= __('Export Tour : ', 'wpvr') ?></h6>
                <p style="color:red;" ><?= $msg ?></p>
            </div>
        <?php
        ob_end_flush();
    }


    /**
     * If preview file found, set data or create warning
     * 
     * @param mixed $preview_url
     * @param mixed $arrContextOptions
     * @param mixed $file_accessible
     * @param mixed $file_save_url
     * 
     * @return string
     * @since 6.0.0
     */
    private function set_warning_for_preview_file($preview_url, $arrContextOptions, $file_accessible, $file_save_url)
    {
        if ($this->wpvr_url_exists($preview_url)) {
            if ($file_accessible == "1") {
                copy($preview_url, $file_save_url['basedir'] . '/wpvr/temp/scene_preview.jpg', stream_context_create($arrContextOptions));
            } else {
                return 'Cannot copy file as allow_url_fopen is disabled in your server. You might enable it with editing your php.ini file.';
            }
        } else {
            return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the preview image url is valid and exists in your media directory or check your ssl certificate is valid and not self signed certificate';
        }
    }


    /**
     * If company logo image or background image url found, set data or create warning
     * 
     * @param mixed $file
     * @param mixed $file_accessible
     * @param mixed $file_save_url
     * @param mixed $arrContextOptions
     * 
     * @return string
     * @since 6.0.0
     */
    private function set_warning_for_file($file, $file_accessible, $file_save_url, $arrContextOptions)
    {
        $get_format = explode(".", $file);
        $format = end($get_format);
        if ($this->wpvr_url_exists($file)) {
            if ($file_accessible == "1") {
                copy($file, $file_save_url['basedir'] . '/wpvr/temp/logo_img.' . $format, stream_context_create($arrContextOptions));
            } else {
                return 'Cannot copy file as allow_url_fopen is disabled in your server. You might enable it with editing your php.ini file.';
            }
        } else {
            return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the preview image url is valid and exists in your media directory or check your ssl certificate is valid and not self signed certificate';
        }
    }


    /**
     * If panodata consists scene_list, then set data or warning 
     * 
     * @param mixed $scene_list
     * @param mixed $file_accessible
     * @param mixed $file_save_url
     * @param mixed $arrContextOptions
     * 
     * @return string
     * @since 6.0.0
     */
    private function set_warning_for_panodata_scenelist($scene_list, $file_accessible, $file_save_url, $arrContextOptions)
    {
        foreach ($scene_list as $panoscenes) {
            $scene_id = $panoscenes['scene-id'];
            if ($scene_id) {

                if ($panoscenes['scene-type'] == 'cubemap') {

                    $url_face0 = $panoscenes["scene-attachment-url-face0"];    
                    if ($this->wpvr_url_exists($url_face0)) {
                        $cube_name = '_face0.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face0, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }

                    $url_face1 = $panoscenes["scene-attachment-url-face1"];    
                    if ($this->wpvr_url_exists($url_face1)) {
                        $cube_name = '_face1.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face1, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }

                    $url_face2 = $panoscenes["scene-attachment-url-face2"];    
                    if ($this->wpvr_url_exists($url_face2)) {
                        $cube_name = '_face2.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face2, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }

                    $url_face3 = $panoscenes["scene-attachment-url-face3"];    
                    if ($this->wpvr_url_exists($url_face3)) {
                        $cube_name = '_face3.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face3, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }

                    $url_face4 = $panoscenes["scene-attachment-url-face4"];    
                    if ($this->wpvr_url_exists($url_face4)) {
                        $cube_name = '_face4.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face4, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }

                    $url_face5 = $panoscenes["scene-attachment-url-face5"];    
                    if ($this->wpvr_url_exists($url_face5)) {
                        $cube_name = '_face5.jpg';
                        return $this->set_warning_for_scene_attachment_url($url_face5, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name);
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }
                }
                else {
                    $url = $panoscenes["scene-attachment-url"];    
                    if ($this->wpvr_url_exists($url)) {
                        if ($file_accessible == "1") {
                            copy($url, $file_save_url['basedir'] . '/wpvr/temp/' . $scene_id . '.jpg', stream_context_create($arrContextOptions));
                        } else {
                            return 'Cannot copy file as allow_url_fopen is disabled in your server. You might enable it with editing your php.ini file.';
                        }
                    } else {
                        return 'Media file not found in your directory or failed to access media file, cannot create export file. Please check the scene image url of scene id: ' . $scene_id . ' exists in your media directory or check your ssl certificate is valid and not self signed certificate';
                    }
                }
            }
        }
    }


    /**
     * Set warning for scene attachment url
     * 
     * @param mixed $url_face
     * @param mixed $file_accessible
     * @param mixed $file_save_url
     * @param mixed $scene_id
     * @param mixed $arrContextOptions
     * @param mixed $cube_name
     * 
     * @return string
     * @since 6.0.0
     */
    private function set_warning_for_scene_attachment_url($url_face, $file_accessible, $file_save_url, $scene_id, $arrContextOptions, $cube_name)
	{
		if ($file_accessible == "1") {
			copy($url_face, $file_save_url['basedir'] . '/wpvr/temp/' . $scene_id . $cube_name, stream_context_create($arrContextOptions));
		} else {
			return 'Cannot copy file as allow_url_fopen is disabled in your server. You might enable it with editing your php.ini file.';
		}
	}


    /**
     * Checking WPVR CURL version
     * 
     * @return bool
     * @since 6.0.0
     */
    private function wpvr_isCurl()
    {
        return function_exists('curl_version');
    }


    /**
     * Checking WPVR URL existance
     * 
     * @param mixed $url
     * 
     * @return bool
     * @since 6.0.0
     */
    private function wpvr_url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

}