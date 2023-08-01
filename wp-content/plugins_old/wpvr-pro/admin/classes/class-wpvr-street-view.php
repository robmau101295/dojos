<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Responsible for managing Street View feature
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr-pro
 * @subpackage Wpvr-pro/admin/classes
 */

 class WPVR_Street_View {

    /**
	 * Instance of WPVR_Street_View class
	 * 
	 * @var object
	 * @since 6.0.0
	 */
	static $instance;

    /**
     * Instance of WPVR_Validator class
     * 
     * @var object
     * @since 6.0.0
     */
    protected $pro_validator;


    private function __construct()
	{
        $this->pro_validator = new WPVR_Pro_Validator;
        add_action( 'wpvr_pro_update_street_view', array($this, 'wpvr_pro_update_street_view_tour_data'), 10, 2 );
        add_action( 'wpvr_pro_street_view_preview', array($this, 'wpvr_pro_street_view_tour_preview'), 10, 2 );
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
     * Render Street view
     * @param array $postdata
     * 
     * @return void
     * @since 6.0.0
     */
    public function render_street_view($postdata)
    {
        ob_start();
        ?>
        <div class="rex-pano-tab streetview" id="streetview">
            <h6 class="title"> <?= __('Embed Google Street View : ', 'wpvr-pro'); ?> </h6>
            <div class="single-settings">
                <?php $this->render_street_view_radio_button($postdata) ?>
            </div>
            <div class="streetview-setting streetviewcontent" style="display:none;">
                <?php $this->render_attachment_url($postdata); ?>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render street view enable feature
     * 
     * @param string $streetview
     * 
     * @return void
     * @since 6.0.0
     */
    private function render_street_view_radio_button($postdata)
    {
        ob_start();
        ?>
        <span>Enable Street View: </span>
        <ul>
            <li class="radio-btn">
                <input class="styled-radio wpvrStreetView_off" id="styled-radio-sv-off" type="radio" name="wpvrStreetView" value="off" <?php if($postdata['streetview'] == 'off'){ echo 'checked'; } ?>>
                <label for="styled-radio-sv-off">Off</label>
            </li>

            <li class="radio-btn">
                <input class="styled-radio wpvrStreetView_on" id="styled-radio-sv-on" type="radio" name="wpvrStreetView" value="on" <?php if($postdata['streetview'] == 'on'){ echo 'checked'; } ?>>
                <label for="styled-radio-sv-on">On</label>
            </li>
        </ul>
        <div class="field-tooltip">
            <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'; ?>" alt="icon" />
            <span><?= __('Please only add street view iframe source. For more details check documentation.', 'wpvr-pro'); ?></span>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render street view attachment url input section
     * 
     * @param string $streetviewurl
     * 
     * @return void
     * @since 6.0.0
     */
    private function render_attachment_url($postdata)
    {
        ob_start();
        ?>
        <div class="single-settings">
            <span>Add Source : </span>
            <div class="form-group" style="width:100%;">
                <input type="textarea" style="width:100%; height:50px;" placeholder="" name="streetview-attachment-url" class="streetview-attachment-url" value="<?= $postdata['streetviewurl'] ?>">
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Update street view tour meta data 
     * 
     * @param mixed $postid
     * @param mixed $panoid
     * 
     * @return void
     * @since 6.0.0
     */
    public function wpvr_pro_update_street_view_tour_data($postid, $panoid)
    {
        $html = '';
        if ($_POST['streetview'] == 'on') {
          $streetviewurl = esc_url_raw($_POST['streetviewurl']);
          $this->pro_validator->empty_streetview_url_validation($streetviewurl);

          if ($streetviewurl) {
            $html .= '<iframe src="' . $streetviewurl . '" width="600" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>';
          }
          $streetviewarray = array();
          $streetviewarray = array(
                                    __("panoid") => $panoid, 
                                    __("streetviewdata") => $html, 
                                    __("streetviewurl") => $streetviewurl, 
                                    __("streetview") => $_POST['streetview']
                                );
          update_post_meta($postid, 'panodata', $streetviewarray);
          die();
        }
    }


    public function wpvr_pro_street_view_tour_preview($postid, $panoid)
    {
        $panoid = '';
        $html = '';
        $postid = sanitize_text_field($_POST['postid']);
        $panoid = 'pano' . $postid;
        $randid = rand(1000, 1000000);
        $streetviewid = 'streetview' . $randid;
        if (isset($_POST['streetview']) && $_POST['streetview'] == 'on') {
            $streetviewurl = $_POST['streetviewurl'];
            $this->pro_validator->empty_streetview_url_validation($streetviewurl);
            if ($streetviewurl) {
            $html .= '<iframe src="' . $streetviewurl . '" width="600" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>';
            }

            $response = array();
            $response = array(__("panoid") => $panoid, __("panodata") => $html, __("streetview") => $streetviewid);
            wp_send_json_success($response);
        }
    }
 }