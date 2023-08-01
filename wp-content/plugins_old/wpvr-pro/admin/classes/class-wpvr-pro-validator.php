<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Manage all validation requirements and messages
 *
 * @link       http://rextheme.com/
 * @since      6.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin
 */

class WPVR_Pro_Validator {
   
  /**
   * Store all erros
   * 
   * @var array
   * @since 6.0.0
   */
  public $errors = array();


  /**
   * Show error message while street view source is empty
   * 
   * @param mixed $streetviewurl
   * 
   * @return void
   * @since 6.0.0
   */
  public function empty_streetview_url_validation($streetviewurl)
  {
    if($streetviewurl == ''){
      $this->add_error('no_url', '<span class="pano-error-title">No Street View Found!</span> <p>You haven\'t set the link of a Google Street View. Please Set a Street View link to see the Preview.</p>');
    }
  }


  /**
   * Add validation messages to errors array
   * 
   * @param string $key
   * @param string $value
   * 
   * @return void
   * @since 6.0.0
   */
  public function add_error($key, $value)
  {
    $this->errors[$key] = $value;
    $this->display_errors();
  }


  /**
   * Display validation message or sending back responses
   * 
   * @return wp_send_json_error
   * @since 6.0.0
   */
  private function display_errors()
  {
    foreach($this->errors as $error){
        wp_send_json_error($error);
        die();
    }
  }
}