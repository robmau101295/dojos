<?php
    $add_ons_list = array(
        array(
            'prefix' => 'fluent_forms',
            'title' => __('WP Fluent Forms', 'wpvr-pro'),
            'description' => __('Add Hotspot type to select published Fluent Forms from a dropdown & display the form in a popup on the front-end.', 'wpvr-pro'),
            'icons' => WPVR_PRO_PLUGIN_DIR_URL . 'images/fluent-form.png',
            'url' => 'https://rextheme.com/wpvr/?#annual-price',
            'released' => true,
            'is_active' => apply_filters('is_fluent_forms_addon_active', false),
            'status' => get_option('wpvr_fluent_forms_license_status', ''),
            'license_key' => get_option('wpvr_fluent_forms_license', ''),
            'license_data' => get_option('wpvr_fluent_forms_license_data', ''),
            'item_id'   => 39435,
        ),
        array(
            'prefix' => 'wc',
            'title' => __('WooCommerce Integration', 'wpvr-pro'),
            'description' => __('Add Hotspot type to add WooCommerce Products. Visitors will be able to view products and add to cart right from the Hotspot.', 'wpvr-pro'),
            'icons' => WPVR_PRO_PLUGIN_DIR_URL . 'images/woo.png',
            'url' => 'https://rextheme.com/wpvr/?#annual-price',
            'released' => true,
            'is_active' => apply_filters('is_wc_addon_active', false),
            'status' => get_option('wpvr_wc_license_status', ''),
            'license_key' => get_option('wpvr_wc_license', ''),
            'license_data' => get_option('wpvr_wc_license_data', ''),
            'item_id'   => 39446,
        ),
        array(
            'prefix' => 'vr_embed',
            'title' => __('WPVR Embed', 'wpvr-pro'),
            'description' => __('Allow to embed WPVR tour using iframe to a non WordPress site.', 'wpvr-pro'),
            'icons' => WPVR_PRO_PLUGIN_DIR_URL . 'images/iframe.png',
            'url' => 'https://rextheme.com/wpvr/?#annual-price',
            'released' => true,
            'is_active' => apply_filters('is_embed_addon_active', false),
            'status' => get_option('wpvr_embed_license_status', ''),
            'license_key' => get_option('wpvr_embed_license', ''),
            'license_data' => get_option('wpvr_embed_license_data', ''),
            'item_id'   => '',
        ),
        array(
            'prefix' => 'flat_image',
            'title' => __('Flat Image Support', 'wpvr-pro'),
            'description' => __('Add Hotspot on Flat Image with Drag &  Drop capability.', 'wpvr-pro'),
            'icons' => WPVR_PRO_PLUGIN_DIR_URL . 'images/flat-image.png',
            'url' => 'https://rextheme.com/wpvr/?#annual-price',
            'released' => false,
            'is_active' => apply_filters('is_flat_image_addon_active', false),
            'status' => get_option('wpvr_flat_image_license_status', ''),
            'license_key' => get_option('wpvr_flat_image_license', ''),
            'license_data' => get_option('wpvr_flat_image_license_data', ''),
            'item_id'   => '',
        ),
    );

?>

<div class="wpvr-addons">
    <div class="section-title">
        <h1><?php echo __('WP VR Add-Ons', 'wpvr-pro') ?></h1>
        <p><?php echo __('The following are available add-ons to extend WP VR functionality.', 'wpvr-pro') ?></p>
    </div>

    <div class="wpvr-addons-wrapper">
        <?php foreach ($add_ons_list as $add_on) {
            $messages = array();
            ?>
            <div class="single-col">
                <span class="title-wrapper">
                    <img src="<?php echo $add_on['icons']; ?>" alt="logo">
                    <h4 class="title"><?php echo $add_on['title']; ?></h4>
                </span>

                <p class="addon-description"><?php echo $add_on['description']; ?></p>
                <?php if($add_on['released']) {
                    if($add_on['is_active']) {?>
                        <a href="#" class="wpvr-btn" disabled="true">Installed</a>
                    <?php }else {
                        echo sprintf('<a href="%s" class="wpvr-btn" target="_blank">Get now</a>', $add_on['url']);
                    }
                }else {
                    echo sprintf('<a href="%s" class="wpvr-btn">Coming Soon</a>', $add_on['url']);
                }?>
            </div>
        <?php } ?>
    </div>
</div>

