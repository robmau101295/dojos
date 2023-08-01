(function($) {
    'use strict';
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).on("click", "#wpvr-dismissible", function(e) {

        e.preventDefault();
        var ajaxurl = wpvr_global_obj.ajaxurl;
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "wpvr_notice",
                nonce : wpvr_global_obj.ajax_nonce
            },
            success: function(response) {
                $('#wpvr-warning').hide();
            }
        });
    });
    function wpvr_bf_notice_dismiss(event) {
        event.preventDefault();
        var ajaxurl = wpvr_global_obj.ajaxurl;
        var that = $(this);
        $.ajax({
            type: "post",
            dataType: "json",
            url: ajaxurl,
            data: { action: "wpvr_black_friday_offer_notice_dismiss", nonce : wpvr_global_obj.ajax_nonce },
            success: function(response) {
                if (response.success) {
                    that.fadeOut('slow');
                }
            }
        })
    }
    $(document).on('click', '.wpvr-black-friday-offer .notice-dismiss', wpvr_bf_notice_dismiss);
    // video setup wizard video
    $( document ).on( 'click', '.box-video', function() {
        $('iframe',this)[0].src += "?autoplay=1";
        $(this).addClass('open');
    });
    $(document).on('click','.wpvr-halloween-notice .notice-dismiss',function (){
        var ajaxurl = wpvr_global_obj.ajaxurl;
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "wpvr_notice",
                nonce : wpvr_global_obj.ajax_nonce
            },
        });
    })

})(jQuery);