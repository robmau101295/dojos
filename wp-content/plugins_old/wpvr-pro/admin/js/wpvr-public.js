(function($) {
  'use strict';
  var owl;
  /**
   * All of the code for your public-facing JavaScript source
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
  var owl;
  $(document).ready(function() {
    localStorage.setItem("vr_mode", "off");
    // localStorage.setItem("vr_mode_compass", "on");
    // localStorage.setItem("vr_mode_pano_info", "on");
    jQuery(".vr-mode-title").hide();
    owl = $(".vrowl-carousel").owlCarousel({
      margin: 20,
      autoWidth: true,
    });
    $('button.owl-next').click(function() {
      owl.trigger('next.owl.carousel');
    });
    $('button.owl-prev').click(function() {
      owl.trigger('prev.owl.carousel');
    });
  });

  $('.customNextBtn').click(function() {
    owl.trigger('next.owl.carousel');
  });
  $('.owl-prev').click(function() {
    owl.trigger.prev();
  });
})(jQuery);


function wpvrhotspot(hotSpotDiv, args) {
  var target = hotSpotDiv.target;
  var idgetter = jQuery(target).parent().parent().attr('id');
  var pano_id = idgetter.replace('pano','');

  var music = jQuery('#vrAudio'+pano_id).attr('data-autoplay');
  var player = jQuery('#audio_control'+pano_id).attr('data-play');
  if (music == "on") {
      jQuery('#vrAudio'+pano_id).get(0).pause();
      jQuery('.wpvrvolumeicon'+pano_id).removeClass('fas fa-volume-up');
      jQuery('.wpvrvolumeicon'+pano_id).addClass('fas fa-volume-mute');
  }
  else {
    if (player == 'on') {
      jQuery('#vrAudio'+pano_id).get(0).pause();
      jQuery('.wpvrvolumeicon'+pano_id).removeClass('fas fa-volume-up');
      jQuery('.wpvrvolumeicon'+pano_id).addClass('fas fa-volume-mute');
    }
  }

  var argst = args.replace(/\\/g, '');
  jQuery(document).ready(function($) {
    var div = document.createElement('div');
    div.innerHTML = argst.trim();
    if ($(div).find('.fluentform').length) {
      var id = $(div).find('.fluentform form').attr('data-form_id');
      var form_class = '.fluentform_wrapper_' + id + ':first';
      $(hotSpotDiv.target).parent().siblings('.wpvr-hotspot-tweak-contents-wrapper').find('.ff-message-success, .ff-errors-in-stack').show();
      $(hotSpotDiv.target).parent().siblings('.wpvr-hotspot-tweak-contents-wrapper').find('.fluentform').hide();
      $(hotSpotDiv.target).parent().siblings('.wpvr-hotspot-tweak-contents-wrapper').find(form_class).show();
      $(hotSpotDiv.target).parent().siblings('.wpvr-hotspot-tweak-contents-wrapper').fadeToggle();
      $(div).find('.fluentform').show();
    } else {
      $(hotSpotDiv.target).parent().siblings(".custom-ifram-wrapper").find('.custom-ifram').html(argst);
      $(hotSpotDiv.target).parent().siblings(".custom-ifram-wrapper").fadeToggle();

      //------add to cart button------//
      $('.wpvr-product-container p.add_to_cart_inline a.button').wrap('<span class="wpvr-cart-wrap"></span>');
      $('.wpvr-product-container p.add_to_cart_inline a.button').attr("target", "_blank");
    }

    $(hotSpotDiv.target).parent().parent(".pano-wrap").toggleClass("show-modal");

    // Registrations for The Events Calendar – Event Registration Plugin shortcode support
    if ($('.rtec').length && !$('.rtec').hasClass('rtec-initialized')) {
      rtecInit();
    }
  });
}

function wpvrtooltip(hotSpotDiv, args) {
  hotSpotDiv.classList.add('custom-tooltip');
  var span = document.createElement('span');
  args = args.replace(/\\/g, "");
  span.innerHTML = args;
  hotSpotDiv.appendChild(span);
  span.style.marginLeft = -(span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
  span.style.marginTop = -span.scrollHeight - 12 + 'px';
}

jQuery(document).ready(function($) {

  $(".cross").on("click", function(e) {
    e.preventDefault();

    var pano_id = $(this).attr('data-id');
    // var music = jQuery('#audio_control'+pano_id).attr('data-play');
    // if (music == "on") {
    //   jQuery('#vrAudio'+pano_id).get(0).play();
    //   jQuery('.wpvrvolumeicon'+pano_id).removeClass('fas fa-volume-mute');
    //   jQuery('.wpvrvolumeicon'+pano_id).addClass('fas fa-volume-up');
    // }

    $(this).parent('.wpvr-hotspot-tweak-contents-wrapper').find('.ff-message-success, .ff-errors-in-stack').hide();
    $(this).parent('.wpvr-hotspot-tweak-contents-wrapper').fadeOut();
    $(this).parent(".custom-ifram-wrapper").fadeOut();
    $(this).parents(".pano-wrap").removeClass("show-modal");

    $('.vr-iframe').attr('src', '');
    if ($('#wpvr-video').length != 0) {
      $('#wpvr-video').get(0).pause();
    }
  });
  $(".elementor-tab-title").on("click", function(e) {
    $('audio').each(function() {
        $(this).get(0).pause();
    });
  });
});

jQuery(document).ready(function($) {
  $('.cp-logo-ctrl').on('click', function() {
    $(this).toggleClass('show');
  });

  $('.vrbounce').on('click', function() {
    window.dispatchEvent(new Event('resize'));
  });
});

jQuery(document).ready(function($) {
  $('#player_audio').click(function() {
    if (this.paused == false) {
      this.pause();
      alert('music paused');
    } else {
      this.play();
      alert('music playing');
    }
  });
});


jQuery(document).ready(function($) {
  if (typeof wpvr_public != "undefined") {
    var notice_active = wpvr_public.notice_active;
    var notice = wpvr_public.notice;
    if (notice_active == "true") {
      if (!$.cookie("wpvr_mobile_notice")) {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
          if ($(".pano-wrap")[0]) {
            $('body').append("<div class='wpvr-mobile-notice'><p>" + notice + "</p> <span class='notice-close'><i class='fa fa-times'></i></span></div>");
          }
        }
      }
    }

    $('.wpvr-mobile-notice .notice-close').on('click', function() {
      $('.wpvr-mobile-notice').fadeOut();
      $.cookie('wpvr_mobile_notice', 'true');
    });
  }

});
