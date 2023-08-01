<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$license = get_option( 'wpvr_edd_license_key' );
$status  = get_option( 'wpvr_edd_license_status' );
$license_data  = get_option( 'wpvr_edd_license_data', '');

?>
<!-- This file should display the admin pages -->
<div class="wpvr-license-wrapper">
    <form method="post" action="options.php">
        <div class="wpvr-license-filed">
            <div class="field-area">
                <div class="promo-text-area">
                    <div class="single-area">
                        <span class="icon">
                            <svg width="14px" height="18px" viewBox="0 0 14 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                    <g id="Report--Copy" transform="translate(-1012.000000, -30.000000)" stroke="#201cfe" stroke-width="1.5">
                                        <g id="Group-6" transform="translate(279.000000, 31.000000)">
                                            <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                                <g id="Group-9" transform="translate(199.000000, 0.000000)">
                                                    <polyline id="Stroke-1" points="9 6 12 3 9 0"></polyline>
                                                    <path d="M0,9 L0,5.4725824 C0,4.10699022 1.04467307,3 2.3333903,3 L12,3" id="Stroke-3"></path>
                                                    <polyline id="Stroke-5" points="3 10 0 13.0002224 3 16"></polyline>
                                                    <path d="M12,7 L12,10.5274176 C12,11.8930098 10.9553269,13 9.66701986,13 L0,13" id="Stroke-7"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <h4><?php echo __('Auto update', 'wpvr-pro'); ?></h4>
                        <p><?php echo __('Update the plugin right from your WordPress Dashboard.', 'wpvr-pro'); ?></p>
                    </div>

                    <div class="single-area">
                        <span class="icon">
                            <svg width="18px" height="17px" viewBox="0 0 18 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                                    <g id="Report--Copy" transform="translate(-972.000000, -31.000000)" stroke="#201cfe" stroke-width="1.5">
                                        <g id="Group-6" transform="translate(279.000000, 31.000000)">
                                            <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                                <polygon id="Stroke-1" points="167 13.1256696 171.635405 15.688 170.75 10.2610156 174.5 6.41730801 169.317703 5.62566961 167 0.688 164.682297 5.62566961 159.5 6.41730801 163.25 10.2610156 162.364595 15.688"></polygon>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <h4><?php echo __('Premium Support', 'wpvr-pro'); ?></h4>
                        <p><?php echo __('Supported by professional and courteous staff.', 'wpvr-pro'); ?></p>
                    </div>
                </div>
                <!-- /promo-text-area -->
                
                <div class="input-field-area">
                    <div class="input-field">
                        <label for="wpvr_edd_license_key"><?php _e('License Key','wpvr-pro'); ?></label>
                        <input id="wpvr_edd_license_key" name="wpvr_edd_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
                        <span class="hints">
                            <?php 
                            if( $status != 'valid' ){
                                _e('Enter your license key, save changes and activate.','wpvr-pro'); 
                            }
                            ?>
                            <div class="wpvr-addon-license-data">
                            <?php if(!empty($license_data)) {
                                $license_data = unserialize($license_data);
                                if(is_array($license_data)) {
                                    $license = json_decode(json_encode($license_data));
                                   
                                    $message = array();
                                    $html = '';
                                    if( ! empty( $license ) && is_object( $license ) ) {
                                        if ( false === $license->success ) {
                                            switch( $license->error ) {
                                                case 'expired' :
                                                    $class = 'expired';
                                                    $messages[] = sprintf(
                                                        __( 'Your license key expired on %s. Please <a href="%s" target="_blank">renew your license key</a>.', 'wpvr-pro' ),
                                                        date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
                                                        'https://rextheme.com/your-account/'
                                                    );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'revoked' :
                                                    $class = 'error';
                                                    $messages[] = sprintf(
                                                        __( 'Your license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', 'wpvr-pro' ),
                                                        'https://rextheme.com/your-account/'
                                                    );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'missing' :
                                                    $class = 'error';
                                                    $messages[] = sprintf(
                                                        __( 'Invalid license. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', 'wpvr-pro' ),
                                                        'https://rextheme.com/your-account/'
                                                    );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'invalid' :
                                                case 'site_inactive' :
                                                    $class = 'error';
                                                    $messages[] = sprintf(
                                                        __( 'Your %s is not active for this URL. Please <a href="%s" target="_blank">visit your account page</a> to manage your license key URLs.', 'wpvr-pro' ),
                                                        $args['name'],
                                                        'https://rextheme.com/your-account/'
                                                    );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'item_name_mismatch' :
                                                    $class = 'error';
                                                    $messages[] = sprintf( __( 'This appears to be an invalid license key for %s.', 'wpvr-pro' ), $args['name'] );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'no_activations_left':
                                                    $class = 'error';
                                                    $messages[] = sprintf( __( 'Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', 'wpvr-pro' ),
                                                        'https://rextheme.com/your-account/' );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                case 'license_not_activable':
                                                    $class = 'error';
                                                    $messages[] = __( 'The key you entered belongs to a bundle, please use the product specific license key.', 'wpvr-pro' );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                                default :
                                                    $class = 'error';
                                                    $error = ! empty(  $license->error ) ?  $license->error : __( 'unknown_error', 'wpvr-pro' );
                                                    $messages[] = sprintf( __( 'There was an error with this license key: %s. Please <a href="%s">contact our support team</a>.', 'wpvr-pro' ),
                                                        $error,
                                                        'https://rextheme.com/your-account/'
                                                    );
                                                    $license_status = 'license-' . $class . '-notice';
                                                    break;
                                            }

                                        } else {
                                            switch( $license->license ) {
                                                case 'valid' :
                                                default:
                                                    $class = 'valid';
                                                    $now        = current_time( 'timestamp' );
                                                    $expiration = strtotime( $license->expires, current_time( 'timestamp' ) );
                                                    if( 'lifetime' === $license->expires ) {
                                                        $messages[] = __( 'License key never expires.', 'wpvr-pro' );
                                                        $license_status = 'license-lifetime-notice';
                                                    } elseif( $expiration > $now && $expiration - $now < ( DAY_IN_SECONDS * 30 ) ) {
                                                        $messages[] = sprintf(
                                                            __( 'Your license key expires soon! It expires on %s. <a href="%s" target="_blank">Renew your license key</a>.', 'wpvr-pro' ),
                                                            date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
                                                            'https://rextheme.com/your-account/'
                                                        );
                                                        $license_status = 'license-expires-soon-notice';
                                                    } else {
                                                        $messages[] = sprintf(
                                                            __( 'Your license key expires on %s.', 'wpvr-pro' ),
                                                            date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) )
                                                        );
                                                        $license_status = 'license-expiration-date-notice';
                                                    }
                                                    break;
                                            }
                                        }
                                    }
                                    else {
                                        $class = 'empty';
                                        $messages[] = sprintf(
                                            __( 'To receive updates, please enter your valid %s license key.', 'wpvr-pro' ),
                                            'WP VR PRO'
                                        );
                                        $license_status = null;
                                    }

                                    if ( ! empty( $messages ) ) {
                                        foreach( $messages as $message ) {
                                            $html .= '<div class="wpvr-addon-license-data wpvr-license-' . $class . ' ' . $license_status . '">';
                                            $html .= '<p>' . $message . '</p>';
                                            $html .= '</div>';
                                        }
                                        echo $html;
                                    }
                                }
                            } ?>
                        </div>
                        </span>


                    </div>

                    <div class="btn-area">
                        <?php if( false !== $license ) { ?>
                            <?php //_e('Activate License','wpvr'); ?>
                        
                            <?php if( $status !== false && $status == 'valid' ) { ?>
                                <span class="vr-notice" style="color:green;"><?php _e('active'); ?></span>
                                <?php wp_nonce_field( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ); ?>
                                <input type="submit" class="wpvr-btn" name="wpvr_edd_license_deactivate" value="<?php _e('Deactivate License','wpvr-pro'); ?>"/>
                            <?php } else {?>
                                
                                <?php wp_nonce_field( 'wpvr_edd_nonce', 'wpvr_edd_nonce' ); ?>
                                <input type="submit" class="wpvr-btn" name="wpvr_edd_license_activate" value="<?php _e('Activate License','wpvr-pro'); ?>"/>
                            <?php } ?>
                                
                        <?php } ?>    
                    </div>
                </div> 
            </div>

            <div class="logo-area">
                <div class="wpvr-logo">
                    <img src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/wpvr-logo.png'?>" alt="logo">
                </div>
                <a href="https://rextheme.com/your-account/#purchase" target="_blank" class="wpvr-btn manage-license"><?php echo __('manage license', 'wpvr-pro'); ?></a>
            </div>
        </div>                 
        
        <?php settings_fields('wpvr_edd_license'); ?>
         
        <?php submit_button(); ?>
    </form>
</div> 

<div class="wpvr-doc-row">
    <div class="single-col">
        <span class="icon">
            <svg width="15px" height="18px" viewBox="0 0 15 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                    <g id="Report--Copy" transform="translate(-1012.000000, -30.000000)" stroke="#201cfe" stroke-width="1.5">
                        <g id="Group-6" transform="translate(279.000000, 31.000000)">
                            <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                <g id="Group-11" transform="translate(199.000000, 0.000000)">
                                    <polygon id="Stroke-1" points="8.10769503 0 13 4.54311111 13 16 0 16 0 0"></polygon>
                                    <polyline id="Stroke-3" points="8 0 8 5 13 5"></polyline>
                                    <path d="M2,12 L10,12" id="Stroke-5"></path>
                                    <path d="M2,8 L10,8" id="Stroke-7"></path>
                                    <path d="M3,4 L5,4" id="Stroke-9"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </span>
        <h4 class="title"><?php echo __('Documentation', 'wpvr-pro'); ?></h4>
        <p><?php echo __('Before You start, you can check our Documentation to get familiar with WPVR. Build an awesome VR tours for your users with ease.', 'wpvr-pro'); ?></p>
        <a href="https://rextheme.com/docs-category/wp-vr/ " class="wpvr-btn" target="_blank"><?php echo __('Documentation', 'wpvr-pro'); ?></a>
    </div>

    <div class="single-col">
        <span class="icon">
            <svg width="20px" height="18px" viewBox="0 0 20 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                    <g id="Report--Copy" transform="translate(-979.000000, -30.000000)" stroke="#201cfe" stroke-width="1.5">
                        <g id="Group-6" transform="translate(279.000000, 30.000000)">
                            <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                <g id="Group-9" transform="translate(166.000000, 0.500000)">
                                    <path d="M3.768,0.5 L2,0.5 C0.8955,0.5 0,1.43683798 0,2.59232379 L0,11.2330979 C0,12.3885838 0.8955,13.3254217 2,13.3254217 L5.1365,13.3254217 C5.667,13.3254217 6.1755,13.5456388 6.5505,13.9379495 L9,16.5 L11.4485,13.9379495 C11.8235,13.5456388 12.3325,13.3254217 12.8625,13.3254217 L16,13.3254217 C17.1045,13.3254217 18,12.3885838 18,11.2330979 L18,2.59232379 C18,1.43683798 17.1045,0.5 16,0.5 L6.2095,0.5" id="Stroke-1"></path>
                                    <g id="Group-4" transform="translate(5.000000, 7.000000)">
                                        <path d="M8.9355,0.5 C8.9355,0.75 8.7405,0.954 8.4995,0.954 C8.2595,0.954 8.0645,0.75 8.0645,0.5 C8.0645,0.25 8.2595,0.046 8.4995,0.046 C8.7405,0.046 8.9355,0.25 8.9355,0.5 Z" id="Stroke-3"></path>
                                        <path d="M0.935,0.5 C0.935,0.75 0.741,0.954 0.5,0.954 C0.259,0.954 0.065,0.75 0.065,0.5 C0.065,0.25 0.259,0.046 0.5,0.046 C0.741,0.046 0.935,0.25 0.935,0.5 Z" id="Stroke-5"></path>
                                        <path d="M4.935,0.5 C4.935,0.75 4.741,0.954 4.5,0.954 C4.26,0.954 4.065,0.75 4.065,0.5 C4.065,0.25 4.26,0.046 4.5,0.046 C4.741,0.046 4.935,0.25 4.935,0.5 Z" id="Stroke-7"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>                 
        </span>
        <h4 class="title"><?php echo __('Support', 'wpvr-pro'); ?></h4>
        <p><?php echo __('Can’t find solution with our documentation? Just post a ticket. Our professional team is here to solve your problems.', 'wpvr-pro'); ?></p>
        <a href="https://rextheme.com/your-account/?active_tab=support" target="_blank" class="wpvr-btn"><?php echo __('Post A Ticket', 'wpvr-pro'); ?></a>
    </div>

    <div class="single-col">
        <span class="icon">
            <svg width="20px" height="19px" viewBox="0 0 20 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                    <g id="Report--Copy" transform="translate(-1041.000000, -29.000000)" stroke="#201cfe" stroke-width="1.5">
                        <g id="Group-6" transform="translate(279.000000, 30.000000)">
                            <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                <path d="M236.985196,3.42441231 C234.47921,-1.16158153 228.754244,0.0378401827 228.072248,4.2433029 C227.328252,8.83239469 232.480722,13.7426403 236.985196,16.4941333 C241.489669,13.7426403 246.804638,8.85356399 245.898143,4.2433029 C245.077148,0.0646890545 239.491181,-1.16158153 236.985196,3.42441231" id="Stroke-1"></path>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>                   
        </span>
        <h4 class="title"><?php echo __('Show Your Love', 'wpvr-pro'); ?></h4>
        <p><?php echo __('We love to have you in WPVR family. Take your 2 minutes to review  and speed the love to encourage us to keep it going.', 'wpvr-pro'); ?></p>
        <a href="https://wordpress.org/plugins/wpvr/#reviews" class="wpvr-btn"  target="_blank"><?php echo __('Leave a Review', 'wpvr-pro'); ?></a>
    </div>
</div>

