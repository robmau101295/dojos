<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Responsible for managing Floor Plan feature
 *
 * @link       http://rextheme.com/
 * @since      6.2.0
 *
 * @package    Wpvr-pro
 * @subpackage Wpvr-pro/admin/classes
 */

class WPVR_Floor_Plan {

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
     * Render Floor Plan main content
     *
     * @param mixed $floor_panl_enable
     * @param mixed $floor_plan_data_display
     *
     * @return void
     * @since 6.0.0
     */
    public function render_floor_plan_tour($postdata)
    {
        $floor_plan_data_display = 'none';
        if (isset($postdata["floor_plan_tour_enabler"])) {
            if ($postdata["floor_plan_tour_enabler"] == 'on') {
                $floor_plan_data_display = 'block';
            }
        }
        ob_start();
        ?>
        <div class="rex-pano-tab floor-plan" id="floorPlan">
            <h6 class="title"> <?= __('Floor Plan Settings : ', 'wpvr-pro');?> </h6>

            <div class="content-wrapper">
                <?php $this->render_content_wrapper($postdata, $floor_plan_data_display) ?>
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
    protected function render_content_wrapper($postdata, $floor_plan_data_display)
    {
        ob_start();
        ?>
        <div class="floor-plan-left">
            <!-- bg tour on/off -->
            <div class="single-settings inline-style">
                <span> <?= __('Enable Floor Plan: ', 'wpvr-pro') ?> </span>
                <span class="wpvr-switcher">
                    <input id="wpvr_floor_plan_enabler" class="vr-switcher-check wpvr_floor_plan_enabler" value="<?= $postdata['floor_plan_tour_enabler'];?>" name="wpvr_floor_plan_enabler" type="checkbox" <?php if($postdata['floor_plan_tour_enabler'] == 'on') { echo 'checked'; } else { echo ''; } ?> />
                    <label for="wpvr_floor_plan_enabler"></label>
                </span>

            </div>
            <!-- bg tour on/off -->
            <div class="floor-plan-flex">
                <div class="floorPlanData" style="display: <?=  $floor_plan_data_display ?>;">
                    <?php WPVR_Pro_Meta_Field::render_floor_plan_data_fields($postdata); ?>
                </div>
            </div>
        </div>

        <div class="floor-plan-right" style="display: <?= $floor_plan_data_display?>">
            <div class="floor-plan-pointer-list" >
                <h4 class="floor-plan-pointer-list-title">
                    Pointers
                    <div class="field-tooltip">
                        <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'?>" alt="icon" />
                        <span><?= __('On tour preview, click on any position on the floor plan to set a pointer. You can set multiple pointers, drag them to change their position, and connect the pointers to scenes.', 'wpvr') ?></span>
                    </div>
                </h4>

                <ul>
                    <?php WPVR_Pro_Meta_Field::render_floor_plan_data_list_fields($postdata) ?>
                </ul>
            </div>
            <div class="floor-plan-background-color">
                <h4 class="floor-plan-background-color-title"> Pointer Color

                    <div class="field-tooltip">
                        <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'?>" alt="icon" />
                        <span><?= __('Set any color you want to the pointers.', 'wpvr') ?></span>
                    </div>
                </h4>
                <input type="color" class="floor-plan-background-custom-color" name="floor-plan-background-custom-color" value="<?php echo isset($postdata['floor_plan_custom_color']) ? $postdata['floor_plan_custom_color'] : '#cca92c'?>">
            </div>
        </div>
        <?php
        ob_end_flush();
    }

}