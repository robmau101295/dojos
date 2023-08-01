<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing wpvr pro meta fields
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr-pro/admin/classes
 */

class WPVR_Pro_Meta_Field {

    /**
     * WPVR Pro version license status
     * 
     * @var string
     * @since 6.0.0
     */
    public static $status = null;

    /**
     * Instance of Wpvr_fontawesome_icons class
     * 
     * @var object
     * @since 6.0.0
     */
    protected static $custom_icon_array;


	public function __construct()
	{
        add_filter( 'extend_rex_pano_nav_menu',             array($this, 'add_wpvr_pro_navigation_fields'));
        add_filter( 'extend_primary_meta_fields',           array($this, 'add_wpvr_pro_primary_meta_fields') );
        add_filter( 'extend_video_meta_fields',             array($this, 'add_wpvr_pro_video_meta_fields'), 10, 3 );
        add_filter( 'modify_scene_default_left_fields',     array($this, 'add_wpvr_pro_scene_default_left_fields') );
        add_filter( 'modify_scene_left_fields',             array($this, 'add_wpvr_pro_scene_left_fields'), 10, 2 );
        add_action( 'wpvr_pro_scene_empty_right_fields',    array($this, 'render_wpvr_pro_scene_empty_right_meta_fileds') );
        add_action( 'wpvr_pro_scene_right_fields',          array($this, 'render_wpvr_pro_scene_right_meta_fileds') );
        add_filter( 'modify_hotspot_left_fields',           array($this, 'add_wpvr_pro_hotsopt_left_meta_fields'), 10, 2 );
        add_filter( 'modify_hotspot_right_fields',          array($this, 'add_wpvr_pro_hotsopt_right_meta_fields') );
        add_filter( 'modify_hotspot_setting_scene_fields',  array($this, 'update_hotspot_setting_scene_fields'), 10, 2 );
        add_filter( 'make_is_pro_false',                    array($this, 'update_general_content_navigation_fields') );
        add_filter( 'modify_advanced_control_left_fields',  array($this, 'update_advanced_control_left_meta_fields'), 10, 2  );
        add_filter( 'modify_advanced_control_right_fields', array($this, 'update_advanced_control_right_meta_fields'), 10, 2  );
        add_filter( 'modify_control_button_left_fields',    array($this, 'update_control_button_left_meta_fields'), 10, 2 );
        add_filter( 'modify_control_button_right_fields',   array($this, 'update_control_button_right_meta_fields'), 10, 2 );
	}


    /**
     * Add new elements to meta fields array
     * 
     * @param array $free_fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_navigation_fields($free_fields)
    {
        $pro_fields = $this->get_pro_navigation_fields();
        $fields = array_merge($free_fields, $pro_fields);
        return $fields;
    }


    /**
     * Add new meta fields to free version
     * 
     * @param array $free_meta_fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_primary_meta_fields($free_meta_fields)
    {
        $pro_meta_fields = self::get_pro_primary_meta_fields();
        $meta_fields = array_merge($free_meta_fields, $pro_meta_fields);
        return $meta_fields;
    }


    /**
     * Add new meta fields with free version while pro version is active
     * 
     * @param mixed $free_meta_fields
     * @param mixed $pano_hotspot
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_hotsopt_left_meta_fields($free_meta_fields, $pano_hotspot)
    {
        $new_meta_fileds = array();
        self::$custom_icon_array = new Wpvr_fontawesome_icons();
        self::$status = apply_filters( 'check_pro_license_status', self::$status );

        if(self::$status !== false && self::$status == 'valid'){
            $new_meta_fileds = array(
                'hotspot-customclass-pro' => array(
                    'title'                      => 'Hotspot Custom Icon',
                    'custom_icons'               => self::$custom_icon_array->icon,
                    'type'                       => 'custom_icon',
                    'hotspot_custom_class_pro'   => isset($pano_hotspot['hotspot-customclass-pro']) ? $pano_hotspot['hotspot-customclass-pro'] : 'none',
                ),
                'hotspot-customclass-color-icon-value'  => array(
                    'title' => 'Hotspot Custom Icon Color',
                    'value' => isset($pano_hotspot['hotspot-customclass-color-icon-value']) ? $pano_hotspot['hotspot-customclass-color-icon-value'] : '#00b4ff',
                    'type'  => 'custom_icon_color'
                ),
                'hotspot-blink' => array(
                    'title'     => 'Hotspot Animation',
                    'selected'  => isset($pano_hotspot['hotspot-blink']) ? $pano_hotspot['hotspot-blink'] : 'on',
                    'type'      => 'animation'
                ),
            );
        }else{
            $new_meta_fileds = array(
                'hotspot-blink' => array(
                    'title' => 'Hotspot Animation',
                    'selected'  => $pano_hotspot['hotspot-blink'],
                    'type'  => 'animation'
                ),
            );
        }
        return array_merge($free_meta_fields, $new_meta_fileds);
    }



    /**
     * Merge free and pro version hotspot right meta fields
     * 
     * @param mixed $free_meta_fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_hotsopt_right_meta_fields($free_meta_fields)
    {
        self::$status = apply_filters( 'check_pro_license_status', self::$status );

        if(self::$status !== false && self::$status == 'valid'){
            $new_meta_fileds = array(
                'hotspot-scene-pitch'   => array(
                    'title' => 'Target Scene Pitch',
                    'display'   => 'none',
                    'type'  => 'pro_text'
                ),
                'hotspot-scene-yaw'   => array(
                    'title' => 'Target Scene Yaw',
                    'display'   => 'none',
                    'type'  => 'pro_text'
                ),
            );
        }
        else{
            $new_meta_fileds = array();
        }
        return array_merge($free_meta_fields, $new_meta_fileds);
    }


    /**
     * Merge free and pro version hotspot setting terget scene related fields
     * 
     * @param mixed $free_meta_fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function update_hotspot_setting_scene_fields($free_meta_fields, $pano_hotspot)
    {
        self::$status = apply_filters( 'check_pro_license_status', self::$status );

        if(self::$status !== false && self::$status == 'valid'){
            $new_meta_fileds = array(
                'hotspot-scene-pitch'   => array(
                    'title' => 'Target Scene Pitch',
                    'display'   => 'block',
                    'type'  => 'terget_scene_pro_text',
                    'value' => isset($pano_hotspot['hotspot-scene-pitch']) ? $pano_hotspot['hotspot-scene-pitch'] : null
                ),
                'hotspot-scene-yaw'   => array(
                    'title' => 'Target Scene Yaw',
                    'display'   => 'block',
                    'type'  => 'terget_scene_pro_text',
                    'value' => isset($pano_hotspot['hotspot-scene-yaw']) ? $pano_hotspot['hotspot-scene-yaw'] : null
                ),
            );
        }
        else{
            $new_meta_fileds = array();
        }
        return array_merge($free_meta_fields, $new_meta_fileds);
    }


    /**
     * General content navigation meta fields for pro version
     * 
     * @param array $free_meta_fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function update_general_content_navigation_fields($free_meta_fields)
    {
        return array(
            array(
                'class' => 'gen-basic',
                'active' => 'active',
                'href' => 'gen-basic',
                'isPro' => false,
                'regular_icon' => 'admin/icon/basic-settings-regular.png',
                'hover_icon' => 'admin/icon/basic-settings-hover.png',
                'title' => 'Basic Settings'
            ),
            array(
                'class' => 'gen-advanced',
                'active' => '',
                'href' => 'gen-advanced',
                'isPro' => false,
                'regular_icon' => 'admin/icon/advance-control-regular.png',
                'hover_icon' => 'admin/icon/advance-control-hover.png',
                'title' => 'Advanced Controls'
            ),
            array(
                'class' => 'gen-control',
                'active' => '',
                'href' => 'gen-control',
                'isPro' => false,
                'regular_icon' => 'admin/icon/control-buttons-regular.png',
                'hover_icon' => 'admin/icon/control-buttons-hover.png',
                'title' => 'Control Buttons'
            )
        );
    }


    /**
     * Update advanced control left meta fields for pro version
     * 
     * @param mixed $fields
     * 
     * @return void
     * @since 6.0.0
     */
    public function update_advanced_control_left_meta_fields($fields, $postdata)
    {
        if(self::$status !== false && self::$status == 'valid'){
            return array(
                'diskeyboard' => array(
                    'class' => 'single-settings compass',
                    'title' => 'Disable Keyboard Movement Control',
                    'id'    => 'wpvr_diskeyboard',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => $postdata['diskeyboard']
                ),
                'keyboardzoom' => array(
                    'class' => 'single-settings',
                    'title' => 'Keyboard Zoom Control',
                    'id'    => 'wpvr_keyboardzoom',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => $postdata['keyboardzoom']
                ),
                'draggable' => array(
                    'class' => 'single-settings',
                    'title' => 'Mouse Drag Control',
                    'id' => 'wpvr_draggable',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => empty($postdata['draggable']) ? 'on' : $postdata['draggable']
                ),
                'mouseZoom' => array(
                    'class' => 'single-settings',
                    'title' => 'Mouse Zoom Control',
                    'id' => 'wpvr_mouseZoom',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => empty($postdata['mouseZoom']) ? 'on' : $postdata['mouseZoom']
                ),
                'gyro' => array(
                    'class' => 'single-settings gyro',
                    'title' => 'Gyroscope Control',
                    'id' => 'wpvr_gyro',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => $postdata['gyro']
                ),
                'deviceorientationcontrol' => array(
                    'root_class' => 'gyro-orientation',
                    'class' => 'single-settings orientation',
                    'title' => 'Auto Gyroscope Support',
                    'id' => 'wpvr_deviceorientationcontrol',
                    'have_tooltip' => true,
                    'tooltip_text' => 'If set to true, device orientation control will be used when the panorama is loaded, if the device supports it. If false, device orientation control needs to be activated by pressing a button. Defaults to false. Will work if gyroscope is enabled.',
                    'type' => 'pro_inner_checkbox',
                    'value' => $postdata['deviceorientationcontrol'],
                ),
                'compass' => array(
                    'class' => 'single-settings compass',
                    'title' => 'Compass',
                    'id' => 'wpvr_compass',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => $postdata['compass']
                ),
            );
        } else {
            return array(
                'gyro' => array(
                    'class' => 'single-settings gyro',
                    'title' => 'Gyroscope Control',
                    'id' => 'wpvr_gyro',
                    'have_tooltip' => false,
                    'tooltip_text' => '',
                    'type' => 'pro_checkbox',
                    'value' => $postdata['gyro']
                ),
                'deviceorientationcontrol' => array(
                    'root_class' => 'gyro-orientation',
                    'class' => 'single-settings orientation',
                    'title' => 'Auto Gyroscope Support',
                    'id' => 'wpvr_deviceorientationcontrol',
                    'have_tooltip' => true,
                    'tooltip_text' => 'If set to true, device orientation control will be used when the panorama is loaded, if the device supports it. If false, device orientation control needs to be activated by pressing a button. Defaults to false. Will work if gyroscope is enabled.',
                    'type' => 'pro_inner_checkbox',
                    'value' => $postdata['deviceorientationcontrol'],
                )
            );
        }
    }


    /**
     * Modify advanced control right meta fields after activison of pro version
     * 
     * @param mixed $fields
     * @param mixed $postdata
     * 
     * @return array
     * @since 6.0.0
     */
    public function update_advanced_control_right_meta_fields($fields, $postdata)
    {
        return array(
            'vrgallery' => array(
                'class' => 'single-settings gallery',
                'title' => 'Scene Gallery',
                'id'    => 'wpvr_vrgallery',
                'have_tooltip' => true,
                'tooltip_text' => 'Turning it On will display a gallery with all the scenes on your tour. By double clicking on a scene thumbnail on the gallery, you can move to that specific scene. The gallery will only show up on the front end and not on the preview.',
                'type' => 'pro_checkbox',
                'value' => $postdata['vrgallery']
            ),
            'vrgallery_icon_size' => array(
                'root_class' => 'vrgallery-gallery-icon-size',
                'class' => 'single-settings',
                'title' => 'Scene Gallery Icon Size',
                'id'    => 'wpvr_vrgallery_icon_size',
                'have_tooltip' => true,
                'tooltip_text' => 'Turning it On will display a gallery with all the scenes on your tour. By double clicking on a scene thumbnail on the gallery, you can move to that specific scene. The gallery will only show up on the front end and not on the preview.',
                'type' => 'pro_inner_checkbox',
                'value' => $postdata['vrgallery_icon_size']
            ),
            'vrgallery_title' => array(
                'root_class' => 'gallery_title',
                'class' => 'single-settings',
                'title' => 'Scene Titles on Gallery',
                'id' => 'wpvr_vrgallery_title',
                'have_tooltip' => true,
                'tooltip_text' => 'Turning it on will display scene titles on each scene thumbnail inside the Scene Gallery. The Scene IDs will be used as the Scene Title.',
                'type' => 'pro_inner_checkbox',
                'value' => $postdata['vrgallery_title'],
            ),
            'vrgallery_display' => array(
                'root_class' => 'gallery_display',
                'class' => 'single-settings',
                'title' => 'Display Gallery By Default',
                'id' => 'vrgallery_display',
                'have_tooltip' => true,
                'tooltip_text' => 'Turning this On will automatically display the scene gallery when the tour is loaded.',
                'type' => 'pro_inner_checkbox',
                'value' => $postdata['vrgallery_display'],
            ),
            'bg_music' => array(
                'class' => 'single-settings',
                'title' => 'Tour Background Music',
                'id'    => 'wpvr_bg_music',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'pro_checkbox',
                'value' => $postdata['bg_music']
            ),
            'bg_music_content'  => array(
                'type' => 'bg_music_content',
                'inner_fields'  => array(
                    'autoplay_bg_music' => array(
                        'class' => 'single-settings',
                        'title' => 'Autoplay',
                        'id'    => 'wpvr_autoplay_bg_music',
                        'have_tooltip' => false,
                        'tooltip_text' => '',
                        'type' => 'pro_checkbox',
                        'value' => $postdata['autoplay_bg_music']
                    ),
                    'loop_bg_music' => array(
                        'class' => 'single-settings',
                        'title' => 'Loop',
                        'id'    => 'wpvr_loop_bg_music',
                        'have_tooltip' => false,
                        'tooltip_text' => '',
                        'type' => 'pro_checkbox',
                        'value' => $postdata['loop_bg_music']
                    ),
                    'audio-attachment-url'  => array(
                        'title' => 'Upload or Set Audio Link',
                        'type'  => 'upload_audio_link',
                        'value' => $postdata['bg_music_url']
                    ),
                ),
            ),
            'explainerSwitch'   => array(
                'class' => 'single-settings company-info',
                'title' => 'Enable explainer video',
                'id'    => 'wpvr_explainerSwitch',
                'have_tooltip'  => false,
                'tooltip_text'  => '',
                'type'  => 'pro_checkbox',
                'value' => $postdata['explainerSwitch']
            ),
            'explaine-content'  => array(
                'title' => 'Add Iframe',
                'type'  => 'explainer_info_wrapper',
                'value' => $postdata['explainerContent']
            ),
            'cpLogoSwitch'  => array(
                'class' => 'single-settings company-info',
                'title' => 'Add Company Information',
                'id'    => 'wpvr_cpLogoSwitch',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'pro_checkbox',
                'value' => $postdata['cpLogoSwitch']
            ),
            'company_info_wrapper'  => array(
                'type'  => 'company_info_wrapper',
                'value' => $postdata['cpLogoImg'],
                'title' => 'Upload Company Logo',
                'cpLogoContent' => $postdata['cpLogoContent']
            ),
            'globalzoom'  => array(
                'class' => 'single-settings',
                'title' => 'Set Zoom Preferences',
                'id'    => 'wpvr_globalzoom',
                'have_tooltip' => true,
                'tooltip_text' => 'Zoom interval is 50 to 120 degree. You can put any value between 50 to 120. As an example, if you set 100, the scene will display with zoom in 100 degree on each load.',
                'type' => 'pro_checkbox',
                'value' => (isset($postdata['hfov']) && $postdata['hfov'] != '') || (isset($postdata['maxHfov']) && $postdata['maxHfov'] != '') || (isset($postdata['minHfov']) && $postdata['minHfov'] != '') ? 'on' : 'off'
            ),
            'scene-zoom'  => array(
                'class' => 'single-settings czscenedata wpvr_czscenedata',
                'title' => 'Default Zoom',
                'input_class'    => 'scene-zoom default-global-zoom',
                'placeholder'   => 100,
                'have_tooltip' => true,
                'tooltip_text' => 'Zoom interval is 50 to 120 degree. You can put any value between 50 to 120. As an example, if you set 100, the scene will display with zoom in 100 degree on each load.',
                'type' => 'pro_input',
                'value' => $postdata['hfov']
            ),
            'scene-maxzoom'  => array(
                'class' => 'single-settings czscenedata wpvr_czscenedata',
                'title' => 'Max Zoom',
                'input_class'    => 'scene-maxzoom max-global-zoom',
                'placeholder'   => 120,
                'have_tooltip' => true,
                'tooltip_text' => 'you can set maximum zoom out level up to 120 degree. If you set max zoom value lower than or equal to default zoom, Zoom out will permanently stop.',
                'type' => 'pro_input',
                'value' => $postdata['maxHfov']
            ),
            'scene-minzoom'  => array(
                'class' => 'single-settings czscenedata wpvr_czscenedata',
                'title' => 'Min Zoom',
                'input_class'    => 'scene-minzoom min-global-zoom',
                'placeholder'   => 50,
                'have_tooltip' => true,
                'tooltip_text' => 'you can set any value up to 50 degree. If you set min zoom value greater than or equal to default zoom, Zoom in will permanently stop.',
                'type' => 'pro_input',
                'value' => $postdata['minHfov']
            )
        );
    }


    /**
     * Modify control button left meta fields after activision of pro version
     * 
     * @param mixed $fields
     * @param mixed $postdata
     * 
     * @return array
     * @since 6.0.0
     */
    public function update_control_button_left_meta_fields($fields, $postdata)
    {
        $custom_control = $postdata['customcontrol'];
        self::$custom_icon_array = new Wpvr_fontawesome_icons();
        return array(
            'panupControl'  => array(
                'title' => 'Move Up',
                'color_name'   => 'panup-customclass-color',
                'color_value'   => isset($custom_control['panupColor']) ? $custom_control['panupColor'] : '#f7fffb',
                'icon_name' => 'panup-customclass-color-icon-value',
                'icon'    => isset($custom_control['panupIcon']) ? $custom_control['panupIcon'] : 'fas fa-angle-up',
                'id'    => 'wpvr_panupControl',
                'value' => isset($custom_control['panupSwitch']) ? $custom_control['panupSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panup-customclass-pro-select',
                'icon_select_name'  => 'panup-customclass-pro'
            ),
            'panDownControl'  => array(
                'title' => 'Move Down',
                'color_name'   => 'panDown-customclass-color',
                'color_value'   => isset($custom_control['panDownColor']) ? $custom_control['panDownColor'] : '#f7fffb',
                'icon_name' => 'panDown-customclass-color-icon-value',
                'icon'    => isset($custom_control['panDownIcon']) ? $custom_control['panDownIcon'] : 'fas fa-angle-down',
                'id'    => 'wpvr_panDownControl',
                'value' => isset($custom_control['panDownSwitch']) ? $custom_control['panDownSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panDown-customclass-pro-select',
                'icon_select_name'  => 'panDown-customclass-pro'
            ),
            'panLeftControl'  => array(
                'title' => 'Move Left',
                'color_name'   => 'panLeft-customclass-color',
                'color_value'   => isset($custom_control['panLeftColor']) ? $custom_control['panLeftColor'] : '#f7fffb',
                'icon_name' => 'panLeft-customclass-color-icon-value',
                'icon'    => isset($custom_control['panLeftIcon']) ? $custom_control['panLeftIcon'] : 'fas fa-angle-left',
                'id'    => 'wpvr_panLeftControl',
                'value' => isset($custom_control['panLeftSwitch']) ? $custom_control['panLeftSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panLeft-customclass-pro-select',
                'icon_select_name'  => 'panLeft-customclass-pro'
            ),
            'panRightControl'  => array(
                'title' => 'Move Right',
                'color_name'   => 'panRight-customclass-color',
                'color_value'   => isset($custom_control['panRightColor']) ? $custom_control['panRightColor'] : '#f7fffb',
                'icon_name' => 'panRight-customclass-color-icon-value',
                'icon'    => isset($custom_control['panRightIcon']) ? $custom_control['panRightIcon'] : 'fas fa-angle-right',
                'id'    => 'wpvr_panRightControl',
                'value' => isset($custom_control['panRightSwitch']) ? $custom_control['panRightSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panRight-customclass-pro-select',
                'icon_select_name'  => 'panRight-customclass-pro'
            ),
            'panZoomInControl'  => array(
                'title' => 'Zoom In',
                'color_name'   => 'panZoomIn-customclass-color',
                'color_value'   => isset($custom_control['panZoomInColor']) ? $custom_control['panZoomInColor'] : '#f7fffb',
                'icon_name' => 'panZoomIn-customclass-color-icon-value',
                'icon'    => isset($custom_control['panZoomInIcon']) ? $custom_control['panZoomInIcon'] : 'fas fa-plus-circle',
                'id'    => 'wpvr_panZoomInControl',
                'value' => isset($custom_control['panZoomInSwitch']) ? $custom_control['panZoomInSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panZoomIn-customclass-pro-select',
                'icon_select_name'  => 'panZoomIn-customclass-pro'
            ),
        );
    }


    /**
     * Modify control button left meta fields after activision of pro version
     * 
     * @param mixed $fields
     * @param mixed $postdata
     * 
     * @return array
     * @since 6.0.0
     */
    public function update_control_button_right_meta_fields($fields, $postdata)
    {
        $custom_control = $postdata['customcontrol'];
        self::$custom_icon_array = new Wpvr_fontawesome_icons();
        return array(
            'panZoomOutControl'  => array(
                'title' => 'Zoom Out',
                'color_name'   => 'panZoomOut-customclass-color',
                'color_value'   => isset($custom_control['panZoomOutColor']) ? $custom_control['panZoomOutColor'] : '#f7fffb',
                'icon_name' => 'panZoomOut-customclass-color-icon-value',
                'icon'    => isset($custom_control['panZoomOutIcon']) ? $custom_control['panZoomOutIcon'] : 'fas fa-minus-circle',
                'id'    => 'wpvr_panZoomOutControl',
                'value' => isset($custom_control['panZoomOutSwitch']) ? $custom_control['panZoomOutSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panZoomOut-customclass-pro-select',
                'icon_select_name'  => 'panZoomOut-customclass-pro'
            ),
            'panFullscreenControl'  => array(
                'title' => 'Full Screen',
                'color_name'   => 'panFullscreen-customclass-color',
                'color_value'   => isset($custom_control['panFullscreenColor']) ? $custom_control['panFullscreenColor'] : '#f7fffb',
                'icon_name' => 'panFullscreen-customclass-color-icon-value',
                'icon'    => isset($custom_control['panFullscreenIcon']) ? $custom_control['panFullscreenIcon'] : 'fas fa-expand',
                'id'    => 'wpvr_panFullscreenControl',
                'value' => isset($custom_control['panFullscreenSwitch']) ? $custom_control['panFullscreenSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'panFullscreen-customclass-pro-select',
                'icon_select_name'  => 'panFullscreen-customclass-pro'
            ),
            'gyroscope'  => array(
                'title' => 'Gyroscope',
                'color_name'   => 'gyroscope-customclass-color',
                'color_value'   => isset($custom_control['gyroscopeColor']) ? $custom_control['gyroscopeColor'] : '#f7fffb',
                'icon_name' => 'gyroscope-customclass-color-icon-value',
                'icon'    => isset($custom_control['gyroscopeIcon']) ? $custom_control['gyroscopeIcon'] : 'fas fa-dot-circle',
                'id'    => 'wpvr_gyroscope',
                'value' => isset($custom_control['gyroscopeSwitch']) ? $custom_control['gyroscopeSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'gyroscope-customclass-pro-select',
                'icon_select_name'  => 'gyroscope-customclass-pro'
            ),
            'backToHome'  => array(
                'title' => 'Home',
                'color_name'   => 'backToHome-customclass-color',
                'color_value'   => isset($custom_control['backToHomeColor']) ? $custom_control['backToHomeColor'] : '#f7fffb',
                'icon_name' => 'backToHome-customclass-color-icon-value',
                'icon'    => isset($custom_control['backToHomeIcon']) ? $custom_control['backToHomeIcon'] : 'fas fa-home',
                'id'    => 'wpvr_backToHome',
                'value' => isset($custom_control['backToHomeSwitch']) ? $custom_control['backToHomeSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'backToHome-customclass-pro-select',
                'icon_select_name'  => 'backToHome-customclass-pro'
            ),
            'explainer'  => array(
                'title' => 'Explainer',
                'color_name'   => 'explainer-customclass-color',
                'color_value'   => isset($custom_control['explainerColor']) ? $custom_control['explainerColor'] : '#f7fffb',
                'icon_name' => 'explainer-customclass-color-icon-value',
                'icon'    => isset($custom_control['explainerIcon']) ? $custom_control['explainerIcon'] : 'fas fa-video',
                'id'    => 'wpvr_explainer',
                'value' => isset($custom_control['explainerSwitch']) ? $custom_control['explainerSwitch'] : 'off',
                'type'  => 'pro_checkbox',
                'custom_icons' => self::$custom_icon_array->icon,
                'icon_select_class' => 'explainer-customclass-pro-select',
                'icon_select_name'  => 'explainer-customclass-pro'
            )
        );
    }


    /**
     * Add pro version video meta fields to free version
     * 
     * @param mixed $old_meta_fields
     * @param mixed $postdata
     * @param mixed $vidurl
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_video_meta_fields($old_meta_fields, $postdata, $vidurl)
    {
        return array(
            'panovideo' => array(
                'class' => 'single-settings videosetup',
                'title' => 'Enable Video',
                'type' => 'radio_button',
                'lists' =>  array(
                        array(
                        'input_class' => 'styled-radio video_off',
                        'input_id' => 'styled-radio',
                        'value' => 'off',
                        'checked' => isset($postdata['vidid']) ?? $postdata['vidid'],
                        'label_value' => 'Off'
                        ),
                        array(
                            'input_class' => 'styled-radio video_on',
                            'input_id' => 'styled-radio-0',
                            'value' => 'on',
                            'checked' => isset($postdata['vidid']) ?? $postdata['vidid'],
                            'label_value' => 'On'
                        )
                ),
                    
            ),
            'autoplay' => array(
                'class' => 'single-settings video-setting',
                'title' => 'Autoplay',
                'type' => 'pro_radio_button',
                'lists' =>  array(
                        array(
                            'input_class' => 'styled-radio',
                            'input_id' => 'styled-radio-autoplay',
                            'value' => 'off',
                            'checked' => isset($postdata['autoplay']) ? $postdata['autoplay'] : '' ,
                            'label_value' => 'Off'
                        ),
                        array(
                            'input_class' => 'styled-radio',
                            'input_id' => 'styled-radio-0-autoplay',
                            'value' => 'on',
                            'checked' => isset($postdata['autoplay']) ? $postdata['autoplay'] : '',
                            'label_value' => 'On'
                        )
                ),
                    
            ),
            'loop' => array(
                'class' => 'single-settings video-setting',
                'title' => 'Loop',
                'type' => 'pro_radio_button',
                'lists' =>  array(
                        array(
                            'input_class' => 'styled-radio',
                            'input_id' => 'styled-radio-loop',
                            'value' => 'off',
                            'checked' => isset($postdata['loop']) ? $postdata['loop'] : '',
                            'label_value' => 'Off'
                        ),
                        array(
                            'input_class' => 'styled-radio',
                            'input_id' => 'styled-radio-0-loop',
                            'value' => 'on',
                            'checked' => isset($postdata['loop']) ? $postdata['loop'] : '',
                            'label_value' => 'On'
                        )
                ),
                    
            ),
            'video-attachment-url' => array(
                'class' => 'video-setting',
                'title' => 'Upload or Add Link',
                'placeholder' => 'Paste Youtube or Vimeo link or upload',
                'input_class' => 'video-attachment-url',
                'type' => 'video_text_input',
                'value' => $vidurl
            )
        );
    }


    /**
     * Add pro version scene left fields for default rebdering scene 
     * 
     * @param mixed $fields
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_scene_default_left_fields($fields)
    {
        return array(
            'dscene' => array(
                'class' => 'single-settings dscene',
                'title' => 'Set as Default',
                'type' => 'select',
                'select_class' => 'dscen',
                'selected' => 'off'
            ),
            'scene-id' => array(
                'label_for' => 'scene-id',
                'title' => 'Scene ID',
                'input_class' => 'sceneid',
                'type' => 'text',
                'value' => '',
                'disabled' => '',
            ),
            'scene-type' => array(
                'title' => 'Scene Type',
                'type' => 'type_select',
                'options' => array(
                    array(
                        'value' => 'equirectangular',
                        'name'  => 'Equirectangular'
                    ),
                    array(
                        'value' => 'cubemap',
                        'name'  => 'Cubemap'
                    )
                ),
                'selected'  => 'equirectangular'
            ),
            'scene-upload' => array(
                'type' => 'upload_wrapper',
                'wrappers' => array(
                    'equirectangular_upload' => array(
                        'class' => 'equirectangular-upload',
                        'display' => 'block',
                        'img_display' => '',
                        'type' => 'equirectangular_upload',
                        'value' => null,
                    ),
                    'cubemap_upload' => array(
                        'class' => 'cubemap-upload',
                        'display' => 'none',
                        'type' => 'cubemap_upload',
                        'cubemaps' => array(
                            array(
                                'class' => 'face0',
                                'title' => 'Face 0: Left Orientation ',
                                'name' => 'scene-attachment-url-face0',
                                'value' => null
                            ),
                            array(
                                'class' => 'face1',
                                'title' => 'Face 1: Front Orientation ',
                                'name' => 'scene-attachment-url-face1',
                                'value' => null
                            ),
                            array(
                                'class' => 'face2',
                                'title' => 'Face 2: Right Orientation ',
                                'name' => 'scene-attachment-url-face2',
                                'value' => null
                            ),
                            array(
                                'class' => 'face3',
                                'title' => 'Face 3: Back Orientation ',
                                'name' => 'scene-attachment-url-face3',
                                'value' => null
                            ),
                            array(
                                'class' => 'face4',
                                'title' => 'Face 4: Top Orientation ',
                                'name' => 'scene-attachment-url-face4',
                                'value' => null
                            ),
                            array(
                                'class' => 'face5',
                                'title' => 'Face 5: Bottom Orientation ',
                                'name' => 'scene-attachment-url-face5',
                                'value' => null
                            )
                        ),
                    )
                    
                ),
            )
        );
    }


    /**
     * Update scene tab left meta fields while pro version is active
     * 
     * @param mixed $fields
     * @param mixed $pano_scene
     * 
     * @return array
     * @since 6.0.0
     */
    public function add_wpvr_pro_scene_left_fields($fields, $pano_scene)
    {
        return array(
            'dscene' => array(
                'class' => 'single-settings dscene',
                'title' => 'Set as Default',
                'type' => 'select',
                'select_class' => 'dscen',
                'selected' => $pano_scene['dscene']
            ),
            'scene-id' => array(
                'label_for' => 'scene-id',
                'title' => 'Scene ID',
                'input_class' => 'sceneid',
                'type' => 'text',
                'value' => $pano_scene['scene-id'],
                'disabled' => '',
            ),
            'scene-type' => array(
                'title' => 'Scene Type',
                'type' => 'type_select',
                'options' => array(
                    array(
                        'value' => 'equirectangular',
                        'name'  => 'Equirectangular'
                    ),
                    array(
                        'value' => 'cubemap',
                        'name'  => 'Cubemap'
                    )
                ),
                'selected'  => $pano_scene['scene-type']
            ),
            'scene-upload' => array(
                'type' => 'upload_wrapper',
                'wrappers' => array(
                    'equirectangular_upload' => array(
                        'class' => 'equirectangular-upload',
                        'display' => $pano_scene['scene-type'] == 'equirectangular' ? 'block' : 'none',
                        'img_display' => '',
                        'type' => 'equirectangular_upload',
                        'value' => $pano_scene['scene-attachment-url'],
                    ),
                    'cubemap_upload' => array(
                        'class' => 'cubemap-upload',
                        'display' => $pano_scene['scene-type'] == 'cubemap' ? 'block' : 'none',
                        'type' => 'cubemap_upload',
                        'cubemaps' => array(
                            array(
                                'class' => 'face0',
                                'title' => 'Face 0: Left Orientation ',
                                'name' => 'scene-attachment-url-face0',
                                'value' => isset($pano_scene['scene-attachment-url-face0']) ? $pano_scene['scene-attachment-url-face0'] : null
                            ),
                            array(
                                'class' => 'face1',
                                'title' => 'Face 1: Front Orientation ',
                                'name' => 'scene-attachment-url-face1',
                                'value' => isset($pano_scene['scene-attachment-url-face1']) ? $pano_scene['scene-attachment-url-face1'] : null
                            ),
                            array(
                                'class' => 'face2',
                                'title' => 'Face 2: Right Orientation ',
                                'name' => 'scene-attachment-url-face2',
                                'value' => isset($pano_scene['scene-attachment-url-face2']) ? $pano_scene['scene-attachment-url-face2'] : null
                            ),
                            array(
                                'class' => 'face3',
                                'title' => 'Face 3: Back Orientation ',
                                'name' => 'scene-attachment-url-face3',
                                'value' => isset($pano_scene['scene-attachment-url-face3']) ? $pano_scene['scene-attachment-url-face3'] : null
                            ),
                            array(
                                'class' => 'face4',
                                'title' => 'Face 4: Top Orientation ',
                                'name' => 'scene-attachment-url-face4',
                                'value' => isset($pano_scene['scene-attachment-url-face4']) ? $pano_scene['scene-attachment-url-face4'] : null
                            ),
                            array(
                                'class' => 'face5',
                                'title' => 'Face 5: Bottom Orientation  ',
                                'name' => 'scene-attachment-url-face5',
                                'value' => isset($pano_scene['scene-attachment-url-face5']) ? $pano_scene['scene-attachment-url-face5'] : null
                            )
                        ),
                    )
                    
                ),
            )
        );
    }


    /**
     * Render scene right meta fields when scene data is empty
     * 
     * @return void
     * @since 6.0.0
     */
    public function render_wpvr_pro_scene_empty_right_meta_fileds()
    {
        $fields = WPVR_Pro_Meta_Field::get_scene_right_meta_fields_empty_panodata();
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Render scene right meta fields when scene has meta data
     * 
     * @param array $pano_scene
     * 
     * @return void
     * @since 6.0.0
     */
    public function render_wpvr_pro_scene_right_meta_fileds($pano_scene)
    {
        $fields = WPVR_Pro_Meta_Field::get_scene_right_meta_fields_with_panodata($pano_scene);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Return scene right meta fields while pro version is active
     * 
     * @return void
     * @since 6.0.0
     */
    private static function get_scene_right_meta_fields_empty_panodata()
    {
        self::$status = apply_filters( 'check_pro_license_status', self::$status );

        if(self::$status !== false && self::$status == 'valid'){
            return array(
                'scene-ititle' => array(
                    'title' => 'Title',
                    'placeholder'   => '',
                    'tooltip_text' => 'If you want to show location information or scene title, you may add this field.',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block'
                ),
                'scene-author'  => array(
                    'title' => 'Author',
                    'placeholder'   => '',
                    'tooltip_text'  => 'If you want to show author information, you may add this field.',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block',
                ),
                'scene-author-url'  => array(
                    'title' => 'Author URL',
                    'placeholder'   => '',
                    'tooltip_text'  => 'If you fill up this field, displayed author text is hyperlinked to this URL.',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block'
                ),
                'scene-vaov'  => array(
                    'title' => 'Vertical Angle of View',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Default value is 180. If the panorama is not 180 degree horizontally, you can control the window by limiting vertical angel of view',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block'
                ),
                'scene-haov'  => array(
                    'title' => 'Horizontal Angle of View',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Default value is 360. If the panorama is not 360 degree horizontally, you can control the window by limiting horizontal angel of view',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block'
                ),
                'scene-vertical-offset'  => array(
                    'title' => 'Vertical Offset',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Sets the vertical offset of the center of the equirectangular image from the horizon, in degrees. Defaults to 0',
                    'class' => 'scene-setting',
                    'type' => 'scene_text',
                    'display'   => 'block'
                ),
                'ptyscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Set Default Scene Face',
                    'type'  => 'scene_select'
                ),
                'scene-pitch'  => array(
                    'class' => 'scene-setting ptyscenedata',
                    'display'   => 'none',
                    'title' => 'Pitch',
                    'placeholder'    => 0,
                    'tooltip_text'  => 'Click on the preview scene and copy pitch & yaw value. If you set scene pitch & yaw, the scene will load by default facing to that axis.',
                    'type' => 'scene_text'
                ),
                'scene-yaw'  => array(
                    'class' => 'scene-setting ptyscenedata',
                    'display'   => 'none',
                    'title' => 'Yaw',
                    'placeholder'    => 0,
                    'tooltip_text'  => 'Click on the preview scene and copy pitch & yaw value. If you set scene pitch & yaw, the scene will load by default facing to that axis.',
                    'type' => 'scene_text'
                ),
                'cvgscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Limit Vertical Scene Grab',
                    'type'  => 'scene_select'
                ),
                'scene-maxpitch'  => array(
                    'class' => 'scene-setting cvgscenedata',
                    'display'   => 'none',
                    'title' => 'Max Pitch',
                    'placeholder'    => 90,
                    'tooltip_text'  => 'You can limit scene boundary using Pitch interval. Pitch interval is from 90 to -90. As an example, for maxpitch 90 & minpitch -90, you can vertically grab up to 90 degree to the top and -90 degree to the bottom. Setting this field value 1 & -1 will permanently stop vertical grab.',
                    'type' => 'scene_text'
                ),
                'scene-minpitch'  => array(
                    'class' => 'scene-setting cvgscenedata',
                    'display'   => 'none',
                    'title' => 'min Pitch',
                    'placeholder'    => -90,
                    'tooltip_text'  => 'You can limit scene boundary using Pitch interval. Pitch interval is from 90 to -90. As an example, for maxpitch 90 & minpitch -90, you can vertically grab up to 90 degree to the top and -90 degree to the bottom. Setting this field value 1 & -1 will permanently stop vertical grab.',
                    'type' => 'scene_text'
                ),
                'chgscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Limit Horizontal Scene Grab',
                    'type'  => 'scene_select'
                ),
                'scene-maxyaw'  => array(
                    'class' => 'scene-setting chgscenedata',
                    'display'   => 'none',
                    'title' => 'Max Yaw',
                    'placeholder'    => 180,
                    'tooltip_text'  => 'You can limit scene boundary using yaw interval. Yaw interval is from 180 to -180. As an example, for maxyaw 180 & minyaw -180, you can horizontally grab up to 180 degree to -180 degree. Setting this field value 1 & -1 will permanently stop horizontal grab.',
                    'type' => 'scene_text'
                ),
                'scene-minyaw'  => array(
                    'class' => 'scene-setting chgscenedata',
                    'display'   => 'none',
                    'title' => 'Min Yaw',
                    'placeholder'    => -180,
                    'tooltip_text'  => 'You can limit scene boundary using yaw interval. Yaw interval is from 180 to -180. As an example, for maxyaw 180 & minyaw -180, you can horizontally grab up to 180 degree to -180 degree. Setting this field value 1 & -1 will permanently stop horizontal grab.',
                    'type' => 'scene_text'
                ),
                'czscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Customize Scene Zoom',
                    'type'  => 'scene_select'
                ),
                'scene-zoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => 'none',
                    'title' => 'Default Zoom',
                    'placeholder'    => 100,
                    'tooltip_text'  => 'Zoom interval is 50 to 120 degree. You can put any value between 50 to 120. As an example, if you set 100, the scene will display with zoom in 100 degree on each load.',
                    'type' => 'scene_text'
                ),
                'scene-maxzoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => 'none',
                    'title' => 'Max Zoom',
                    'placeholder'    => 120,
                    'tooltip_text'  => 'You can set maximum zoom out level up to 120 degree. If you set max zoom value lower than or equal to default zoom, Zoom out will permanently stop.',
                    'type' => 'scene_text'
                ),
                'scene-minzoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => 'none',
                    'title' => 'Min Zoom',
                    'placeholder'    => 50,
                    'tooltip_text'  => 'You can set any value up to 50 degree. If you set min zoom value greater than or equal to default zoom, Zoom in will permanently stop.',
                    'type' => 'scene_text'
                ),
                
            );
        }
        return array();
    }


    /**
     * Return scene right meta fields while pro version is active
     * 
     * @return void
     * @since 6.0.0
     */
    private static function get_scene_right_meta_fields_with_panodata($pano_scene)
    {
        self::$status = apply_filters( 'check_pro_license_status', self::$status );

        if(self::$status !== false && self::$status == 'valid'){
            return array(
                'scene-ititle' => array(
                    'title' => 'Title',
                    'placeholder'   => '',
                    'tooltip_text' => 'If you want to show location information or scene title, you may add this field.',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-ititle']) ? $pano_scene['scene-ititle'] : null
                ),
                'scene-author'  => array(
                    'title' => 'Author',
                    'placeholder'   => '',
                    'tooltip_text'  => 'If you want to show author information, you may add this field.',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-author']) ? $pano_scene['scene-author'] : null
                ),
                'scene-author-url'  => array(
                    'title' => 'Author URL',
                    'placeholder'   => '',
                    'tooltip_text'  => 'If you fill up this field, displayed author text is hyperlinked to this URL.',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-author-url']) ? $pano_scene['scene-author-url'] : null
                ),
                'scene-vaov'  => array(
                    'title' => 'Vertical Angle of View',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Default value is 180. If the panorama is not 180 degree horizontally, you can control the window by limiting vertical angel of view',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-vaov']) ? $pano_scene['scene-vaov'] : null
                ),
                'scene-haov'  => array(
                    'title' => 'Horizontal Angle of View',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Default value is 360. If the panorama is not 360 degree horizontally, you can control the window by limiting horizontal angel of view',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-haov']) ? $pano_scene['scene-haov'] : null
                ),
                'scene-vertical-offset'  => array(
                    'title' => 'Vertical Offset',
                    'placeholder'   => '',
                    'tooltip_text'  => 'Sets the vertical offset of the center of the equirectangular image from the horizon, in degrees. Defaults to 0',
                    'class' => 'scene-setting',
                    'type' => 'pro_scene_text',
                    'display'   => 'block',
                    'value' => isset($pano_scene['scene-vertical-offset']) ? $pano_scene['scene-vertical-offset'] : null
                ),
                'ptyscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Set Default Scene Face',
                    'type'  => 'pro_scene_select',
                    'selected'  => isset($pano_scene['ptyscene']) ? $pano_scene['ptyscene'] : 'off'
                ),
                'scene-pitch'  => array(
                    'class' => 'scene-setting ptyscenedata',
                    'display'   =>  isset($pano_scene['ptyscene']) ? ($pano_scene['ptyscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Pitch',
                    'placeholder'    => 0,
                    'tooltip_text'  => 'Click on the preview scene and copy pitch & yaw value. If you set scene pitch & yaw, the scene will load by default facing to that axis.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-pitch']) ? $pano_scene['scene-pitch'] : null
                ),
                'scene-yaw'  => array(
                    'class' => 'scene-setting ptyscenedata',
                    'display'   => isset($pano_scene['ptyscene']) ? ($pano_scene['ptyscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Yaw',
                    'placeholder'    => 0,
                    'tooltip_text'  => 'Click on the preview scene and copy pitch & yaw value. If you set scene pitch & yaw, the scene will load by default facing to that axis.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-yaw']) ? $pano_scene['scene-yaw'] : null
                ),
                'cvgscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Limit Vertical Scene Grab',
                    'type'  => 'pro_scene_select',
                    'selected'  => isset($pano_scene['cvgscene']) ? $pano_scene['cvgscene'] : 'off'
                ),
                'scene-maxpitch'  => array(
                    'class' => 'scene-setting cvgscenedata',
                    'display'   => isset($pano_scene['cvgscene']) ? ($pano_scene['cvgscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Max Pitch',
                    'placeholder'    => 90,
                    'tooltip_text'  => 'You can limit scene boundary using Pitch interval. Pitch interval is from 90 to -90. As an example, for maxpitch 90 & minpitch -90, you can vertically grab up to 90 degree to the top and -90 degree to the bottom. Setting this field value 1 & -1 will permanently stop vertical grab.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-maxpitch']) ? $pano_scene['scene-maxpitch'] : null
                ),
                'scene-minpitch'  => array(
                    'class' => 'scene-setting cvgscenedata',
                    'display'   => isset($pano_scene['cvgscene']) ? ($pano_scene['cvgscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'min Pitch',
                    'placeholder'    => -90,
                    'tooltip_text'  => 'You can limit scene boundary using Pitch interval. Pitch interval is from 90 to -90. As an example, for maxpitch 90 & minpitch -90, you can vertically grab up to 90 degree to the top and -90 degree to the bottom. Setting this field value 1 & -1 will permanently stop vertical grab.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-minpitch']) ? $pano_scene['scene-minpitch'] : null
                ),
                'chgscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Limit Horizontal Scene Grab',
                    'type'  => 'pro_scene_select',
                    'selected'  => isset($pano_scene['chgscene']) ? $pano_scene['chgscene'] : 'off'
                ),
                'scene-maxyaw'  => array(
                    'class' => 'scene-setting chgscenedata',
                    'display'   => isset($pano_scene['chgscene']) ? ($pano_scene['chgscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Max Yaw',
                    'placeholder'    => 180,
                    'tooltip_text'  => 'You can limit scene boundary using yaw interval. Yaw interval is from 180 to -180. As an example, for maxyaw 180 & minyaw -180, you can horizontally grab up to 180 degree to -180 degree. Setting this field value 1 & -1 will permanently stop horizontal grab.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-maxyaw']) ? $pano_scene['scene-maxyaw'] : null
                ),
                'scene-minyaw'  => array(
                    'class' => 'scene-setting chgscenedata',
                    'display'   => isset($pano_scene['chgscene']) ? ($pano_scene['chgscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Min Yaw',
                    'placeholder'    => -180,
                    'tooltip_text'  => 'You can limit scene boundary using yaw interval. Yaw interval is from 180 to -180. As an example, for maxyaw 180 & minyaw -180, you can horizontally grab up to 180 degree to -180 degree. Setting this field value 1 & -1 will permanently stop horizontal grab.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-minyaw']) ? $pano_scene['scene-minyaw'] : null
                ),
                'czscene' => array(
                    'class' => 'single-settings',
                    'title' => 'Customize Scene Zoom',
                    'type'  => 'pro_scene_select',
                    'selected'  => isset($pano_scene['czscene']) ? $pano_scene['czscene'] : 'off'
                ),
                'scene-zoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => isset($pano_scene['czscene']) ? ($pano_scene['czscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Default Zoom',
                    'placeholder'    => 100,
                    'tooltip_text'  => 'Zoom interval is 50 to 120 degree. You can put any value between 50 to 120. As an example, if you set 100, the scene will display with zoom in 100 degree on each load.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-zoom']) ? $pano_scene['scene-zoom'] : null
                ),
                'scene-maxzoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => isset($pano_scene['czscene']) ? ($pano_scene['czscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Max Zoom',
                    'placeholder'    => 120,
                    'tooltip_text'  => 'You can set maximum zoom out level up to 120 degree. If you set max zoom value lower than or equal to default zoom, Zoom out will permanently stop.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-maxzoom']) ? $pano_scene['scene-maxzoom'] : null
                ),
                'scene-minzoom'  => array(
                    'class' => 'scene-setting czscenedata',
                    'display'   => isset($pano_scene['czscene']) ? ($pano_scene['czscene'] == 'off' ? 'none' : 'block') : 'none',
                    'title' => 'Min Zoom',
                    'placeholder'    => 50,
                    'tooltip_text'  => 'You can set any value up to 50 degree. If you set min zoom value greater than or equal to default zoom, Zoom in will permanently stop.',
                    'type' => 'pro_scene_text',
                    'value' => isset($pano_scene['scene-minzoom']) ? $pano_scene['scene-minzoom'] : null
                ),
                
            );
        }
        return array();
    }


    /**
     * Render pro version text fields
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 6.0.0
     */
    private static function render_scene_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class ?>" style="display:<?= $display ?>;">
            <label for="<?= $name; ?>"><?= __($title .': ', 'wpvr'); ?></label>
            <input type="text" class="<?= $name; ?>" name="<?= $name; ?>" placeholder="<?= $placeholder?>"  />
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render pro version text fields
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 6.0.0
     */
    private static function render_pro_scene_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class ?>" style="display:<?= $display ?>;">
            <label for="<?= $name; ?>"><?= __($title .': ', 'wpvr'); ?></label>
            <input type="text" class="<?= $name; ?>" name="<?= $name; ?>" placeholder="<?= $placeholder?>" value="<?= $value; ?>"  />
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render scene selection dropdown field in pro version
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 6.0.0
     */
    private static function render_scene_select_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class ?>">
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <select class="<?= $name; ?>" name="<?= $name; ?>">
                <option value="on"> On</option>
                <option value="off" selected > Off</option>
            </select>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render scene selection dropdown field in pro version
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 6.0.0
     */
    private static function render_pro_scene_select_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class ?>">
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <select class="<?= $name; ?>" name="<?= $name; ?>">
                <option value="on" <?php  if($selected == 'on') { echo 'selected'; } ?> > On</option>
                <option value="off" <?php  if($selected == 'off') { echo 'selected'; } ?> > Off</option>
            </select>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Return navigation fields for pro version
     * 
     * @return array
     * @since 6.0.0
     */
    private function get_pro_navigation_fields()
    {
        return array(
            array(
                'class' => 'floor-plan',
                'screen' => 'floorPlan',
                'href' => 'floorPlan',
                'r_src' => 'admin/icon/map.svg',
                'h_src' => 'admin/icon/map.svg',
                'title' => 'Floor Plan',
                'active' => ''
            ),
            array(
                'class' => 'background-tour',
                'screen' => 'backgroundTour',
                'href' => 'backgroundTour',
                'r_src' => 'admin/icon/bg-tour-regular.png',
                'h_src' => 'admin/icon/bg-tour-hover.png',
                'title' => 'Background Tour',
                'active' => ''
            ),
            array(
                'class' => 'streetview',
                'screen' => 'streetview',
                'href' => 'streetview',
                'r_src' => 'admin/icon/street-view-regular.png',
                'h_src' => 'admin/icon/street-view-hover.png',
                'title' => 'Street View',
                'active' => ''
            )
        );
    }


    /**
     * Return primary meta fields for pro version
     * 
     * @return array
     * @since 6.0.0
     */
    private static function get_pro_primary_meta_fields()
    {
        return array(
            'panodata' => array(
                'scene-list' => array(
                    array(
                        'scene-id' => null,
                        'scene-type' => 'equirectangular',
                        'scene-ititle'  => null,
                        'scene-author'  => null,
                        'scene-author-url'  => null,
                        'scene-vaov'    => null,
                        'scene-haov'    => null,
                        'scene-vertical-offset' => null,
                        'scene-pitch'   => null,
                        'scene-yaw' => null, 
                        'scene-maxpitch' => null,
                        'scene-minpitch' => null,
                        'scene-maxyaw' => null,
                        'scene-minyaw' => null,
                        'scene-zoom' => null,
                        'scene-maxzoom' => null,
                        'scene-minzoom' => null,
                        'hotspot-list' => array(
                            array(
                                'hotspot-title' => null,
                                'hotspot-pitch' => null,
                                'hotspot-yaw' => null,
                                'hotspot-customclass' => null,
                                'hotspot-scene' => null,
                                'hotspot-url' => null,
                                'hotspot-content' => null,
                                'hotspot-hover' => null,
                                'hotspot-type' => 'info',
                                'hotspot-scene-list' => 'none',
                                'hotspot-scene-pitch'   => null,
                                'hotspot-scene-yaw' => null,
                                'hotspot-customclass-pro'   => 'none',
                                'hotspot-blink' => 'on',
                                'hotspot-customclass-color-icon-value'  => '#00b4ff'
                            ),
                        ),
                        'dscene' => 'off',
                        'ptyscene' => 'off',
                        'cvgscene' => 'off',
                        'chgscene' => 'off',
                        'czscene' => 'off',
                        'scene-attachment-url' => null,
                        'scene-attachment-url-face0' => null,
                        'scene-attachment-url-face1' => null,
                        'scene-attachment-url-face2' => null,
                        'scene-attachment-url-face3' => null,
                        'scene-attachment-url-face4' => null,
                        'scene-attachment-url-face5' => null
                    ),
                ),
            ),
            'bg_tour_enabler' => 'off',
            'bg_tour_navmenu' => null,
            'bg_tour_title' => null,
            'bg_tour_subtitle' => null,
            'floor_plan_tour_enabler' => 'off',
            'floor_plan_attachment_url' => null,
            'floor_plan_data_list' => array(),
            'streetview' => 'off',
            'streetviewurl' => null,
            'diskeyboard'   => 'off',
            'keyboardzoom'  => 'on',
            'draggable' => 'on',
            'mouseZoom' => 'on',
            'gyro'  => 'off',
            'deviceorientationcontrol'  => 'off',
            'compass'   => 'off',
            'vrgallery' => 'off',
            'vrgallery_title'   => 'off',
            'vrgallery_display' => 'off',
            'vrgallery_icon_size' => 'off',
            'bg_music'  => 'off',
            'autoplay_bg_music' => 'off',
            'loop_bg_music' => 'off',
            'bg_music_url'  => null,
            'cpLogoSwitch'  => 'off',
            'cpLogoImg' => null,
            'cpLogoContent' => null,
            'explainerSwitch'   => 'off',
            'explainerContent'  => null,
            'customcontrol'    => array(
                'panupSwitch' => 'off',
                'panupColor' => '#f7fffb',
                'panupIcon' => 'fas fa-angle-up',
                'panDownSwitch' => 'off',
                'panDownColor' => '#f7fffb',
                'panDownIcon' => 'fas fa-angle-down',
                'panLeftSwitch' => 'off',
                'panLeftColor' => '#f7fffb',
                'panLeftIcon' => 'fas fa-angle-left',
                'panRightSwitch' => 'off',
                'panRightColor' => '#f7fffb',
                'panRightIcon' => 'fas fa-angle-right',
                'panZoomInSwitch' => 'off',
                'panZoomInColor' => '#f7fffb',
                'panZoomInIcon' => 'fas fa-plus-circle',
                'panZoomOutSwitch' => 'off',
                'panZoomOutColor' => '#f7fffb',
                'panZoomOutIcon' => 'fas fa-minus-circle',
                'panFullscreenSwitch' => 'off',
                'panFullscreenColor' => '#f7fffb',
                'panFullscreenIcon' => 'fas fa-expand',
                'gyroscopeSwitch' => 'off',
                'gyroscopeColor' => '#f7fffb',
                'gyroscopeIcon' => 'fas fa-dot-circle',
                'backToHomeSwitch' => 'off',
                'backToHomeColor' => '#f7fffb',
                'backToHomeIcon' => 'fas fa-home',
                'explainerSwitch' => 'off',
                'explainerColor' => '#f7fffb',
                'explainerIcon' => 'fas fa-video',
            )
        );
    }


    /**
     * Initialize background tour data fields
     * 
     * @param array $postdata
     * 
     * @return array
     * @since 6.0.0
     */
    public static function get_background_tour_data_fields($postdata)
    {
        return array(
            'bg_tour_title' => array(
                'class' => 'bg-tour-title',
                'type' => 'bg_input',
                'value' => $postdata['bg_tour_title'],
                'title' => 'Title',
            ),
            'bg_tour_subtitle' => array(
                'class' => 'bg-tour-subtitle',
                'type' => 'bg_input',
                'value' => $postdata['bg_tour_subtitle'],
                'title' => 'Subtitle',
            )
        );
    }


    /**
     * Initialize fields render method
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 6.0.0
     */
    public static function render_background_tour_data_fields($postdata)
    {
        $fields = self::get_background_tour_data_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }

    /**
     * Initialize Floor Plan data fields
     *
     * @param array $postdata
     *
     * @return array
     * @since 6.0.0
     */
    public static function get_floor_plan_data_fields($postdata)
    {
        return array(
            'floor_plan_image' => array(
                'class' => 'floor-plan-image',
                'display' => '',
                'type' => 'fp_input',
                'value' => $postdata['floor_plan_attachment_url'],
                'title' => 'Upload Floor Plan Image',
            ),
        );
    }
    /**
     * Initialize Floor Plan data list
     *
     * @param array $postdata
     *
     * @return array
     * @since 6.0.0
     */
    public static function get_floor_plan_list_data_fields($postdata)
    {
        return array(
            'floor_plan_list_data' => array(
                'class' => 'floor_plan_scene_option',
                'display' => '',
                'type' => 'select_input',
                'value' => $postdata['floor_plan_data_list'],
                'options' => $postdata['panodata']['scene-list'],
            ),
        );
    }


    /**
     * Initialize fields render method
     *
     * @param array $postdata
     *
     * @return void
     * @since 6.0.0
     */
    public static function render_floor_plan_data_fields($postdata)
    {
        $fields = self::get_floor_plan_data_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }
    /**
     * Initialize data list  render method
     *
     * @param array $postdata
     *
     * @return void
     * @since 6.0.0
     */
    public static function render_floor_plan_data_list_fields($postdata)
    {
        $fields = self::get_floor_plan_list_data_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Render BG Tour input fields
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 6.0.0
     */
    public static function render_bg_input_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="single-settings">
            <span> <?= __($title.': ', 'wpvr'); ?> </span>
            <input type="text" class="<?= $class; ?>" name="<?= $name; ?>" value="<?= $value; ?>" />
        </div>
        <?php
        ob_end_flush();
    }
    /**
     * Render BG Tour input fields
     *
     * @param mixed $name
     * @param mixed $val
     *
     * @return void
     * @since 6.0.0
     */
    public static function render_fp_input_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class=floot-plan-setting>
            <label for="floor-plan-upload"><?= __($title .': ', 'wpvr'); ?>

            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'?>" alt="icon" />
                <span><?= __('Click on the Image icon to upload your floor plan image or choose from the media folder. Supported file types are PNG and JPG/JPEG.', 'wpvr') ?></span>
            </div>
            </label>
            <div class="form-group">
                <input type="hidden" name="floor-plan-attachment-url" class="floor-plan-attachment-url" value="<?= $value; ?>">
                <input type="button" class="floor-plan-upload" id="vr-floor-plan-preview-img" data-info="" value="Upload"/>
                <div class="img-upload-frame <?php if(!empty($value)) { echo 'img-uploaded'; } ?>" style="<?= $display ?>background-image: url(<?= $value; ?>)">
                    <span class="floor-plan-remove-attachment">x</span>
                    <label for="vr-floor-plan-preview-img">
                        <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/uplad-icon.png'; ?>" alt="preview img" />
                        <span><?= __('Click to Upload an Image ', 'wpvr'); ?></span>
                    </label>
                </div>
            </div>
        </div>


        <?php
        ob_end_flush();
    }
    /**
     * RenderFloor Plan data list style fields
     *
     * @param mixed $name
     * @param mixed $val
     *
     * @return void
     * @since 6.0.0
     */
    public static function render_select_input_field($name, $val)
    {
        extract( $val );
        ob_start();
        foreach($value as $data){
        ?>
        <li style=""><label for="floor-pointer">Pointer - <?= $data->id ?>:</label>
            <select name="<?= $data->name ?>" class="floor_plan_scene_option">
                <?php
                foreach($options as $option){
                    $selected = '';
                    if($option['scene-id'] == $data->value){
                        $selected = 'selected';
                    }
                    echo '<option value="'.$option['scene-id'].'" '.$selected.'>'.$option['scene-id'].'</option>';
                }
                ?>
            </select>
            <button class="plan-delete" data-id="<?= $data->id ?>"><i class="fas fa-trash-alt"></i></button>
        </li>

        <?php
        }
        ob_end_flush();
    }

}