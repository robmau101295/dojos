<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Responsible for managing Background Tour feature
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr-pro
 * @subpackage Wpvr-pro/admin/classes
 */

 class WPVR_Background_Tour {

    /**
	 * Instance of WPVR_Background_Tour class
	 * 
	 * @var object
	 * @since 6.0.0
	 */
	static $instance;


    private function __construct()
	{

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
    public function render_background_tour($postdata)
    {
        $bg_tour_data_display = 'none';
        if (isset($postdata["bg_tour_enabler"])) {
            if ($postdata["bg_tour_enabler"] == 'on') {
                $bg_tour_data_display = 'block';
            }
        }
        ob_start();
        ?>
        <div class="rex-pano-tab background-tour" id="backgroundTour">      
            <h6 class="title"> <?= __('Background Tour Settings : ', 'wpvr-pro');?> </h6>

            <div class="content-wrapper">
                <?php $this->render_content_wrapper($postdata, $bg_tour_data_display) ?>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render background tour content wrapper
     * 
     * @param mixed $postdata
     * @param mixed $bg_tour_data_display
     * 
     * @return void
     * @since 6.0.0
     */
    protected function render_content_wrapper($postdata, $bg_tour_data_display)
    {
        ob_start();
        ?>
        <div class="background-tour-left">
            <!-- bg tour on/off -->
            <div class="single-settings inline-style">
                <span> <?= __('Enable background tour: ', 'wpvr-pro') ?> </span>
                <span class="wpvr-switcher">
                    <input id="wpvr_bg_tour_enabler" class="vr-switcher-check wpvr_bg_tour_enabler" value="<?= $postdata['bg_tour_enabler'];?>" name="wpvr_bg_tour_enabler" type="checkbox" <?php if($postdata['bg_tour_enabler'] == 'on') { echo 'checked'; } else { echo ''; } ?> />
                    <label for="wpvr_bg_tour_enabler"></label>
                </span>
            </div>
            <!-- bg tour on/off -->

            <div class="bgtourdata" style="display: <?=  $bg_tour_data_display ?>;">
                <?php WPVR_Pro_Meta_Field::render_background_tour_data_fields($postdata); ?>
            </div>
        </div>
        <?php
        ob_end_flush();
    }

 }