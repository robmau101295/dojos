(function ($) {
    'use strict';
    var j = 1;
    var color = '#00b4ff';
    var blink = 'on';
    var owl;
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
    $(document).ready(function () {
        $(".vrowl-carousel").owlCarousel({
            margin: 10,
            autoWidth: true,
        });

        var primary_markng = $('#scene-1').find('input.sceneid').val();
        $('.owl' + primary_markng).parents('.owl-item').addClass('marked');

        $('.video-setting').hide();

    });

    $(document).on("change", "input.vr-switcher-check", function (event) {
        if (this.checked) {
            $(this).val('on');
        } else {
            $(this).val('off');
        }
    });


    $(document).on("click", ".scene-nav ul li span", function (event) {
        $('.owl-item').removeClass('marked');
        var target = $(this).attr("data-index");
        var data = $('#scene-' + target).find('input.sceneid').val();
        if (data) {
            $('.owl' + data).parents('.owl-item').addClass('marked');
        }
    });

    $(document).on("change", "input[type=checkbox][name=globalzoom]", function (event) {
        if ($(this).val() == 'on') {
            $('.wpvr_czscenedata').css('display', 'flex');
        } else {
            $('.wpvr_czscenedata').css('display', 'none');
        }
    });


    jQuery(document).ready(function ($) {
        var globalzoom = $("input[type=checkbox][name=globalzoom]").val();
        if (globalzoom == 'on') {
            $('.wpvr_czscenedata').css('display', 'flex');
        } else {
            $('.wpvr_czscenedata').css('display', 'none');
        }
    });


    $(document).on("change", ".wpvr_bg_tour_enabler", function (event) {
        if ($(this).val() == 'on') {
            $('.bgtourdata').css('display', 'block');
        } else {
            $('.bgtourdata').css('display', 'none');
        }
    });
    $(document).on("change", ".wpvr_floor_plan_enabler", function (event) {
        if ($(this).val() == 'on') {
            $('.floorPlanData').css('display', 'block');
            $('.floor-plan-right').css('display', 'block');
        } else {
            $('.floorPlanData').css('display', 'none');
            $('.floor-plan-right').css('display', 'none');
        }
    });
    function isDark( color ) {
        var match = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
        return parseFloat(match[1])
            + parseFloat(match[2])
            + parseFloat(match[3])
            < 3 * 256 / 2;
    }
    $(document).on("change",".floor-plan-background-custom-color" ,function (event){
        $(".floor-plan-pointer").css('background',$(this).val())
        $(".floor-plan-pointer").css("color", isDark($(".floor-plan-pointer").css("background-color")) ? 'black' : 'white');

    });

    jQuery(document).ready(function ($) {
        j = $('#scene-1').find('.hotspot-nav li').eq(-2).find('span').attr('data-index');
        var ajaxurl = wpvr_obj.ajaxurl;
        $('.panolenspreview').on('click', function (e) {
            e.preventDefault();
            $('.wpvr-loading').show();
            var postid = $("#post_ID").val();
            var autoload = $("input[name='autoload']").is(':checked') ? 'on' : 'off';
            var compass = $("input[name='compass']").is(':checked') ? 'on' : 'off';
            var mouseZoom = $("input[name='mouseZoom']").is(':checked') ? 'on' : 'off';
            var draggable = $("input[name='draggable']").is(':checked') ? 'on' : 'off';
            var diskeyboard = $("input[name='diskeyboard']").is(':checked') ? 'on' : 'off';
            var keyboardzoom = $("input[name='keyboardzoom']").is(':checked') ? 'on' : 'off';
            var control = $("input[name='controls']").is(':checked') ? 'on' : 'off';
            var defaultscene = $("input[name='default-scene-id']").val();
            var preview = $("input[name='preview-attachment-url']").val();
            var scenefadeduration = $("input[name='scene-fade-duration']").val();
            var rotation = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';
            var autorotation = $("input[name='auto-rotation']").val();

            var videourl = $("input[name='video-attachment-url']").val();
            var vidautoplay = $("input[name='playvideo']:checked").val();
            var vidcontrol = $("input[name='playcontrol']:checked").val();
            var panovideo = $("input[name='panovideo']:checked").val();
            // floor plan //

            var wpvr_floor_plan_enabler = $("input[name='wpvr_floor_plan_enabler']").is(':checked') ? 'on' : 'off';
            var wpvr_floor_plan_image = $("input[name='floor-plan-attachment-url']").val();


            //streetview//
            var streetview = $("input[name='wpvrStreetView']:checked").val();
            var streetviewurl = $("input[name='streetview-attachment-url']").val();

            if(panovideo == 'on'){
                streetview = 'off'
            }

            var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
            var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

            var panodata = $('.scene-setup').repeaterVal();

            var panolist = JSON.stringify(panodata);
            var previewtext = $('.previewtext').val();
            var gzoom = $('.globalzoom').is(':checked') ? 'on' : 'off';
            var dzoom = '';
            var maxzoom = '';
            var minzoom = '';
            if ('on' == gzoom) {
                dzoom = $('.default-global-zoom').val();
                maxzoom = $('.max-global-zoom').val();
                minzoom = $('.min-global-zoom').val();
            }
            var pointer_position = [];
            $(".floor-plan-pointer").each(function() {
                var get_id =   $(this).attr('id');
                var data_top =   $(this).attr('data-top');
                var data_left =   $(this).attr('data-left');
                var style =   $(this).attr('style');
                var text =   $(this).text();
                pointer_position.push({
                    id  : get_id,
                    text  : text,
                    data_top : data_top,
                    data_left : data_left,
                    style : style
                });
            });
            var floor_list_pointer_position = pointer_position;
            var floor_plan_custom_color_preview = $("input[name='floor-plan-background-custom-color']").val();

            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvr_preview",
                    nonce : wpvr_obj.ajax_nonce,
                    postid: postid,
                    compass: compass,
                    mouseZoom: mouseZoom,
                    draggable: draggable,
                    diskeyboard: diskeyboard,
                    keyboardzoom: keyboardzoom,
                    control: control,
                    autoload: autoload,
                    panodata: panolist,
                    defaultscene: defaultscene,
                    rotation: rotation,
                    autorotation: autorotation,
                    autorotationinactivedelay: autorotationinactivedelay,
                    autorotationstopdelay: autorotationstopdelay,
                    preview: preview,
                    scenefadeduration: scenefadeduration,
                    gzoom: gzoom,
                    dzoom: dzoom,
                    maxzoom: maxzoom,
                    minzoom: minzoom,
                    panovideo: panovideo,
                    videourl: videourl,
                    vidautoplay: vidautoplay,
                    vidcontrol: vidcontrol,
                    streetview: streetview,
                    wpvr_floor_plan_enabler: wpvr_floor_plan_enabler,
                    wpvr_floor_plan_image: wpvr_floor_plan_image,
                    streetviewurl: streetviewurl,
                },

                success: function (response) {
                    $('.wpvr-loading').hide();
                    if (response.success == true) {
                        if(response.data[2] == 'off'){
                            $('#error_occured').hide();
                            $('#error_occuredpub').hide();
                            $('#' + response.data[0]["panoid"]).empty();
                            var scenes = response.data[1];
                            var floor_plan = response.data[3]
                            if (scenes) {
                                $.each(scenes.scenes, function (i) {
                                    $.each(scenes.scenes[i]['hotSpots'], function (key, val) {
                                        if (val["clickHandlerArgs"] != "") {
                                            val["clickHandlerFunc"] = wpvrhotspot;
                                        }
                                        if (val["createTooltipArgs"] != "") {
                                            val["createTooltipFunc"] = wpvrtooltip;
                                        }
                                    });
                                });
                            }
                            if (scenes) {
                                $('.scene-gallery').trigger('destroy.owl.carousel');
                                $('.scene-gallery').empty();
                                $.each(scenes.scenes, function (key, val) {
                                    if (val.type == 'cubemap') {
                                        var img_data = val.cubeMap[0];
                                    }
                                    else {
                                        var img_data = val.panorama;
                                    }
                                    $('.scene-gallery').append('<ul style="width:150px;"><li class="owlscene owl' + key + '">' + key + '</li><li title="Double click to view scene"><img class="scctrl" id="' + key + '_gallery" src="' + img_data  + '"></li></ul>');
                                });
                                $(".vrowl-carousel").owlCarousel({
                                    margin: 10,
                                    autoWidth: true,
                                });
                                var active_owl_target = $('#wpvr_active_scenes').val();
                                var get_owl_target = $('#scene-' + active_owl_target).find('input.sceneid').val();
                                $('.owl' + get_owl_target).parents('.owl-item').addClass('marked');
                            }
                            
                            var panoshow = pannellum.viewer(response.data[0]["panoid"], scenes);
                            if (scenes.autoRotate) {
                                panoshow.on('load', function () {
                                    setTimeout(function () {
                                        panoshow.startAutoRotate(scenes.autoRotate, 0);
                                    }, 3000);
                                });
                                panoshow.on('scenechange', function () {
                                    setTimeout(function () {
                                        panoshow.startAutoRotate(scenes.autoRotate, 0);
                                    }, 3000);
                                });
                            }
                            var touchtime = 0;
                            if (scenes) {
                                $.each(scenes.scenes, function (key, val) {
                                    document.getElementById('' + key + '_gallery').addEventListener('click', function (e) {
                                        if (touchtime == 0) {
                                            touchtime = new Date().getTime();
                                        } else {
                                            if (((new Date().getTime()) - touchtime) < 800) {
                                                panoshow.loadScene(key);
                                                touchtime = 0;
                                            } else {
                                                touchtime = new Date().getTime();
                                            }
                                        }
                                    });
                                });
                            }
                            if(scenes && floor_plan.floor_plan_tour_enabler == 'on'){
                                var floor_pointer = "";
                                for(var i in floor_list_pointer_position) {
                                    floor_pointer +=   '<div class="floor-plan-pointer ui-draggable ui-draggable-handle" id="'+floor_list_pointer_position[i].id+'"  data-top="'+floor_list_pointer_position[i].data_top+'" data-left="'+floor_list_pointer_position[i].data_left+'" style="'+floor_list_pointer_position[i].style+'">'+floor_list_pointer_position[i].text+'</div>';
                                }

                                $('#'+response.data[0]["panoid"]).append('<div class="wpvr-floor-preview outfit">\n' +
                                    '                        <img src="'+floor_plan.floor_plan_attachment_url+'" alt="">'+floor_pointer+
                                    '                    </div>' +
                                    '<button class="flooplan-toggle">\n' +
                                    '                            <i class="far fa-map"></i>\n' +
                                    '                        </button>');
                                $(".floor-plan-pointer").draggable({
                                    containment: ".outfit",
                                    stop: function(event, ui) {
                                        var new_left_perc = parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%";
                                        var new_top_perc = parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%";
                                        var output = 'Top: ' + parseInt(new_top_perc) + '%, Left: ' + parseInt(new_left_perc) + '%';

                                        $(this).css("left", parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                                        $(this).css("top", parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");

                                        $(this).attr('data-top', parseInt($(this).css("top")) / ($(".outfit").height() / 100) + "%");
                                        $(this).attr('data-left', parseInt($(this).css("left")) / ($(".outfit").width() / 100) + "%");
                                    }
                                });
                            }
                            $('html, body').animate({
                                scrollTop: $("#wpvr_item_builder__box").offset().top
                            }, 500);
                            //set preview text
                            if ("" != previewtext) {
                                $('.pnlm-load-button p').text(previewtext);
                            }
                        }else{
                            $('#' + response.data["panoid"]).empty();
                            $('#' + response.data["panoid"]).html(response.data["panodata"]);
                            if (response.data['vidtype'] == 'selfhost') {
                                videojs(response.data["vidid"], {
                                    plugins: {
                                        pannellum: {}
                                    }
                                });
                            }
                            $('html, body').animate({
                                scrollTop: $("#wpvr_item_builder__box").offset().top
                            }, 500);
                        }

                    } else {
                        $('#error_occured').show();
                        $('#error_occured .pano-error-message').html(response.data);
                        $('#error_occuredpub').show();
                        $('#error_occuredpub').html(response.data);
                        $('body').addClass('error-overlay');
                        $('html, body').animate({
                            scrollTop: $("#error_occured").offset().top
                        }, 500);
                    }
                }
            });
        });
    });

    jQuery(document).ready(function ($) {
        var ajaxurl = wpvr_obj.ajaxurl;
        $('#videopreview').on('click', function (e) {
            e.preventDefault();

            var postid = $("#post_ID").val();
            var videourl = $("input[name='video-attachment-url']").val();
            if('' == videourl){
                $('#confirm_text').html("No Video Found!"+"<br>"+"<span class='wpvr-video-alert-text'>"+"You haven't uploaded or set the link to a 360 degree video. Please Upload or Set a video to see the Preview."+"</span>");
                $('.wpvr-delete-alert-wrapper .wpvr-delete-confirm-btn').css('display', 'none');
                $('.wpvr-delete-alert-wrapper').css('display', 'flex');
                $('.wpvr-video-alert-text').css('font-weight', '400');

                $(document).on("click", ".wpvr-delete-alert-wrapper .cross", function (e) {
                    e.preventDefault();
                    $('.wpvr-delete-alert-wrapper').css('display', 'none');
                    $('.wpvr-delete-alert-wrapper .wpvr-delete-confirm-btn').css('display', 'flex');
                    $(".video-setting").show();
                    $(".video_off").prop('checked', false);
                    $(".video_on").prop('checked', true);
                    $("li.general").hide();
                    $("li.scene").hide();
                    $("li.hotspot").hide();
                    $("li.streetview").hide();
                    $("li.background-tour").hide();
                });
                return false;
            }
            var autoplay = $("input[name='autoplay']:checked").val();
            var loop = $("input[name='loop']:checked").val();
            var vidcontrol = $("input[name='playcontrol']:checked").val();
            $('.wpvr-loading').show();
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvrvideo_preview",
                    nonce : wpvr_obj.ajax_nonce,
                    postid: postid,
                    videourl: videourl,
                    autoplay: autoplay,
                    loop: loop,
                    vidcontrol: vidcontrol,
                },

                success: function (response) {
                    $('.wpvr-loading').hide();
                    if (response.success == true) {
                        $('#' + response.data["panoid"]).empty();
                        $('#' + response.data["panoid"]).html(response.data["panodata"]);
                        if (response.data['vidtype'] == 'selfhost') {
                            videojs(response.data["vidid"], {
                                plugins: {
                                    pannellum: {}
                                }
                            });

                        }
                        $('html, body').animate({
                            scrollTop: $("#wpvr_item_builder__box").offset().top
                        }, 500);
                    } else {

                    }
                }
            });
        });
    });

    jQuery(document).ready(function ($) {
        var previewtext = $('.previewtext').val();
        if ("" != previewtext) {
            $('.pnlm-load-button p').text(previewtext);
        }
    });
    jQuery(document).ready(function ($) {
        var ajaxurl = wpvr_obj.ajaxurl;
        $('#streetviewpreview').on('click', function (e) {
            e.preventDefault();

            var postid = $("#post_ID").val();
            var streetview = $("input[name='streetview-attachment-url']").val();

            if('' == streetview){
                $('#confirm_text').html("No Street View Found!"+"<br>"+"<span class='wpvr-streetView-alert-text'>"+"You haven't set the link of a Google Street View. Please Set a Street View link to see the Preview."+"</span>");
                $('.wpvr-delete-alert-wrapper .wpvr-delete-confirm-btn').css('display', 'none');
                $('.wpvr-delete-alert-wrapper').css('display', 'flex');
                $('.wpvr-streetView-alert-text').css('font-weight', '400');

                $(document).on("click", ".wpvr-delete-alert-wrapper .cross", function (e) {
                    e.preventDefault();
                    $('.wpvr-delete-alert-wrapper').css('display', 'none');
                    $('.wpvr-delete-alert-wrapper .wpvr-delete-confirm-btn').css('display', 'flex');
                    $(".wpvrStreetView_off").prop('checked', false);
                    $(".wpvrStreetView_on").prop('checked', true);
                    $(".streetviewcontent").show();
                    $("li.streetview").show();
                    $("li.general").hide();
                    $("li.scene").hide();
                    $("li.hotspot").hide();
                    $("li.video").hide();
                    $("li.background-tour").hide();
                });
                return false;
            }
            $('.wpvr-loading').show();
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvrstreetview_preview",
                    nonce : wpvr_obj.ajax_nonce,
                    postid: postid,
                    streetview: streetview,
                },

                success: function (response) {
                    $('.wpvr-loading').hide();
                    if (response.success == true) {
                        $('#' + response.data["panoid"]).empty();
                        $('#' + response.data["panoid"]).html(response.data["panodata"]);
                        $('html, body').animate({
                            scrollTop: $("#wpvr_item_builder__box").offset().top
                        }, 500);
                    }
                }
            });
        });
    });

    //-- Set Cookie--//

    function setCookie(cName, cValue, expDays) {
        let date = new Date();
        date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
    }
    // Get a cookie

    function getCookie(cName) {
        const name = cName + "=";
        const cDecoded = decodeURIComponent(document.cookie); //to be careful
        const cArr = cDecoded .split('; ');
        let res;
        cArr.forEach(val => {
            if (val.indexOf(name) === 0) res = val.substring(name.length);
        })
        return res;
    }

    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    jQuery(document).ready(function ($) {
        var getRedirectUrl = getCookie("redirectUrl");
        var getCookiePostID = getCookie("postID");

        const urlParams = getParameterByName("active_tab");
        const action = wpvr_global_obj.url_info.param.action;
        let getPostID = wpvr_global_obj.url_info.param.post;

        if(getCookiePostID == getPostID){
            if(urlParams == '' || urlParams == undefined){
                if (action == 'edit' && getRedirectUrl != undefined){
                    window.location.replace(getRedirectUrl)
                    setCookie('postID', '', 1);
                    setCookie('redirectUrl', '', 1);
                }
            }
        }
        var flag_ok = false;
        $('#publish').on('click', function (e) {
            var x = $(this).val();
            let cus = [];

            if (!flag_ok) {
                e.preventDefault();
                $('.wpvr-loading').show();
                var postid = $("#post_ID").val();
                var panovideo = $("input[name='panovideo']:checked").val();
                var videourl = $("input[name='video-attachment-url']").val();
                var autoplay = $("input[name='autoplay']:checked").val();
                var loop = $("input[name='loop']:checked").val();
                //streetview//
                var streetview = $("input[name='wpvrStreetView']:checked").val();
                if(panovideo == 'on'){
                    streetview = 'off'
                }
                var streetviewurl = $("input[name='streetview-attachment-url']").val();

                //streetview//

                var autoload = $("input[name='autoload']").is(':checked') ? 'on' : 'off';
                var control = $("input[name='controls']").is(':checked') ? 'on' : 'off';

                //==Custom control==//
                var customcontrol = [];
                //== Pan Up==//
                var panupSwitch = $("input[name='panupControl']").val();
                customcontrol['panupSwitch'] = panupSwitch;
                var panupColor = $("input[name='panup-customclass-color']").val();
                customcontrol['panupColor'] = panupColor;
                var panupIcon = $('.panup-customclass-pro-select').find(":selected").val();
                customcontrol['panupIcon'] = panupIcon;
                //== Pan Up END==//

                //== Pan Down==//
                var panDownSwitch = $("input[name='panDownControl']").val();
                customcontrol['panDownSwitch'] = panDownSwitch;
                var panDownColor = $("input[name='panDown-customclass-color']").val();
                customcontrol['panDownColor'] = panDownColor;
                var panDownIcon = $('.panDown-customclass-pro-select').find(":selected").val();
                customcontrol['panDownIcon'] = panDownIcon;
                //== Pan Down END==//

                //== Pan Left==//
                var panLeftSwitch = $("input[name='panLeftControl']").val();
                customcontrol['panLeftSwitch'] = panLeftSwitch;
                var panLeftColor = $("input[name='panLeft-customclass-color']").val();
                customcontrol['panLeftColor'] = panLeftColor;
                var panLeftIcon = $('.panLeft-customclass-pro-select').find(":selected").val();
                customcontrol['panLeftIcon'] = panLeftIcon;
                //== Pan Left END==//

                //== Pan Right==//
                var panRightSwitch = $("input[name='panRightControl']").val();
                customcontrol['panRightSwitch'] = panRightSwitch;
                var panRightColor = $("input[name='panRight-customclass-color']").val();
                customcontrol['panRightColor'] = panRightColor;
                var panRightIcon = $('.panRight-customclass-pro-select').find(":selected").val();
                customcontrol['panRightIcon'] = panRightIcon;
                //== Pan Right END==//

                //== Pan Zoom In==//
                var panZoomInSwitch = $("input[name='panZoomInControl']").val();
                customcontrol['panZoomInSwitch'] = panZoomInSwitch;
                var panZoomInColor = $("input[name='panZoomIn-customclass-color']").val();
                customcontrol['panZoomInColor'] = panZoomInColor;
                var panZoomInIcon = $('.panZoomIn-customclass-pro-select').find(":selected").val();
                customcontrol['panZoomInIcon'] = panZoomInIcon;
                //== Pan Zoom In END==//

                //== Pan Zoom Out==//
                var panZoomOutSwitch = $("input[name='panZoomOutControl']").val();
                customcontrol['panZoomOutSwitch'] = panZoomOutSwitch;
                var panZoomOutColor = $("input[name='panZoomOut-customclass-color']").val();
                customcontrol['panZoomOutColor'] = panZoomOutColor;
                var panZoomOutIcon = $('.panZoomOut-customclass-pro-select').find(":selected").val();
                customcontrol['panZoomOutIcon'] = panZoomOutIcon;
                //== Pan Zoom Out END==//

                //== Pan Fullscreen==//
                var panFullscreenSwitch = $("input[name='panFullscreenControl']").val();
                customcontrol['panFullscreenSwitch'] = panFullscreenSwitch;
                var panFullscreenColor = $("input[name='panFullscreen-customclass-color']").val();
                customcontrol['panFullscreenColor'] = panFullscreenColor;
                var panFullscreenIcon = $('.panFullscreen-customclass-pro-select').find(":selected").val();
                customcontrol['panFullscreenIcon'] = panFullscreenIcon;
                //== Pan Fullscreen END==//

                //== Pan gyro==//
                var gyroscopeSwitch = $("input[name='gyroscope']").val();
                customcontrol['gyroscopeSwitch'] = gyroscopeSwitch;
                var gyroscopeColor = $("input[name='gyroscope-customclass-color']").val();
                customcontrol['gyroscopeColor'] = gyroscopeColor;
                var gyroscopeIcon = $('.gyroscope-customclass-pro-select').find(":selected").val();
                customcontrol['gyroscopeIcon'] = gyroscopeIcon;
                //== Pan gyro END==//

                //== backToHome==//
                var backToHomeSwitch = $("input[name='backToHome']").val();
                customcontrol['backToHomeSwitch'] = backToHomeSwitch;
                var backToHomeColor = $("input[name='backToHome-customclass-color']").val();
                customcontrol['backToHomeColor'] = backToHomeColor;
                var backToHomeIcon = $('.backToHome-customclass-pro-select').find(":selected").val();
                customcontrol['backToHomeIcon'] = backToHomeIcon;
                //== backToHome END==//

                //== Explainer button==//
                var explainerSwitch = $("input[name='explainer']").val();
                customcontrol['explainerSwitch'] = explainerSwitch;
                var explainerColor = $("input[name='explainer-customclass-color']").val();
                customcontrol['explainerColor'] = explainerColor;
                var explainerIcon = $('.explainer-customclass-pro-select').find(":selected").val();
                customcontrol['explainerIcon'] = explainerIcon;
                //== Explainer Button END==//

                var customcontroldata = $.extend({}, customcontrol);
                // var customcontroldata = JSON.stringify(customcontrol);
                //==Custom control end==//

                //==cp-logo-integration==//
                var cpLogoSwitch = $("input[name='cpLogoSwitch']").is(':checked') ? 'on' : 'off';
                var cpLogoImg = $("input[name='cp-logo-attachment-url']").val();
                var cpLogoContent = $('textarea#cp-logo-content').val();
                //==cp-logo-integration==//

                //==explainer video integration==//
                var explainerSwitch = $("input[name='explainerSwitch']").val();
                var explainerContent = $('textarea#explaine-content').val();
                //==explainer video integration==//

                //=== background tour ===//
                var wpvr_bg_tour_enabler = $("input[name='wpvr_bg_tour_enabler']").val();
                var wpvr_bg_tour_navmenu_enabler = $("input[name='wpvr_bg_tour_navmenu_enabler']").val();
                var bg_tour_title = $("input[name='bg_tour_title']").val();
                var bg_tour_subtitle = $("input[name='bg_tour_subtitle']").val();
                //=== background tour ===//

                //=== Floor Plan==///
                var wpvr_floor_plan_enabler = $("input[name='wpvr_floor_plan_enabler']").val();
                var wpvr_floor_plan_imager  = $("input[name='floor-plan-attachment-url']").val();
                //=== Floor Plan==///

                var vrgallery = $("input[name='vrgallery']").is(':checked') ? 'on' : 'off';
                var vrgallery_title = $("input[name='vrgallery_title']").is(':checked') ? 'on' : 'off';
                var vrgallery_display = $("input[name='vrgallery_display']").is(':checked') ? 'on' : 'off';
                var vrgallery_icon_size = $("input[name='vrgallery_icon_size']").is(':checked') ? 'on' : 'off';
                var gyro = $("input[name='gyro']:checked").is(':checked') ? 'on' : 'off';
                var deviceorientationcontrol = $("input[name='deviceorientationcontrol']").is(':checked') ? 'on' : 'off';
                var compass = $("input[name='compass']").is(':checked') ? 'on' : 'off';
                var mouseZoom = $("input[name='mouseZoom']").is(':checked') ? 'on' : 'off';
                var draggable = $("input[name='draggable']").is(':checked') ? 'on' : 'off';
                var diskeyboard = $("input[name='diskeyboard']").is(':checked') ? 'on' : 'off';
                var keyboardzoom = $("input[name='keyboardzoom']").is(':checked') ? 'on' : 'off';
                var defaultscene = $("input[name='default-scene-id']").val();
                var preview = $("input[name='preview-attachment-url']").val();
                var rotation = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';
                var autorotation = $("input[name='auto-rotation']").val();
                var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
                var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

                var scenefadeduration = $("input[name='scene-fade-duration']").val();

                //===generic form===//
                var genericform = $("input[name='genericform']").is(':checked') ? 'on' : 'off';
                var genericformshortcode = $("input[name='genericformshortcode']").val();
                //===generic form===//

                //===audio===//
                var bg_music = $("input[name='bg_music']").is(':checked') ? 'on' : 'off';
                var bg_music_url = $("input[name='audio-attachment-url']").val();
                var autoplay_bg_music = $("input[name='autoplay_bg_music']").is(':checked') ? 'on' : 'off';
                var loop_bg_music = $("input[name='loop_bg_music']").is(':checked') ? 'on' : 'off';
                //===audio===//

                var gzoom = $("input[name='globalzoom']").is(':checked') ? 'on' : 'off';
                var dzoom = '';
                var maxzoom = '';
                var minzoom = '';
                if ('on' == gzoom) {
                    dzoom = $('.default-global-zoom').val();
                    maxzoom = $('.max-global-zoom').val();
                    minzoom = $('.min-global-zoom').val();
                }
                if ($('.scene-setup')[0]) {
                    var panodata = $('.scene-setup').repeaterVal();
                    var panolist = JSON.stringify(panodata);
                } else {
                    var panodata = '';
                    var panolist = '';
                }

                var vrgallery = $("input[name='vrgallery']").is(':checked') ? 'on' : 'off';
                var previewtext = $('.previewtext').val();
                // if the type is flat image?
                var flat_image = 'no';
                var flat_image_url = '';
                if ($("#wpvr-flat-image").length) {
                    if ($('#wpvr-flat-image input[name="flatImage"]:checked').val() === 'yes') {
                        flat_image = 'yes';
                        flat_image_url = $('.flat-image-attachment-url').val();
                        var flat_image_hotspot_data = $('#flat-image-hotspot-repeater').repeaterVal();
                        var flat_image_hotspot_list = JSON.stringify(flat_image_hotspot_data);
                    }
                }
                var floor_list = [];
                $(".floor-plan-pointer-list ul li").each(function() {
                    var get_scene =   $(this).find(".floor_plan_scene_option").val();
                    var get_name =   $(this).find(".floor_plan_scene_option").attr('name');
                    var get_id =   $(this).find(".plan-delete").attr('data-id');
                    floor_list.push({
                        id  : get_id,
                        name : get_name,
                        value : get_scene
                    });
                });
                var floor_list_data = '';

                var pointer_position = [];
                $(".floor-plan-pointer").each(function() {
                    var get_id =   $(this).attr('id');
                    var data_top =   $(this).attr('data-top');
                    var data_left =   $(this).attr('data-left');
                    var style =   $(this).attr('style');
                    var text =   $(this).text();
                    pointer_position.push({
                        id  : get_id,
                        text  : text,
                        data_top : data_top,
                        data_left : data_left,
                        style : style
                    });
                });
                var floor_list_pointer_position = '';
                var floor_plan_custom_color = '';
                if(wpvr_floor_plan_enabler == 'on' && wpvr_floor_plan_imager != ''){
                    floor_list_data = JSON.stringify(floor_list);
                    floor_list_pointer_position = JSON.stringify(pointer_position);
                    floor_plan_custom_color = $("input[name='floor-plan-background-custom-color']").val();
                }


                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "wpvr_save",
                        nonce : wpvr_obj.ajax_nonce,
                        postid: postid,
                        panovideo: panovideo,
                        videourl: videourl,
                        autoplay: autoplay,
                        loop: loop,
                        streetview: streetview,
                        streetviewurl: streetviewurl,
                        control: control,
                        customcontrol: customcontroldata,
                        vrgallery: vrgallery,
                        vrgallery_title: vrgallery_title,
                        vrgallery_display: vrgallery_display,
                        vrgallery_icon_size: vrgallery_icon_size,
                        gyro: gyro,
                        deviceorientationcontrol: deviceorientationcontrol,
                        compass: compass,
                        mouseZoom: mouseZoom,
                        draggable: draggable,
                        diskeyboard: diskeyboard,
                        keyboardzoom: keyboardzoom,
                        autoload: autoload,
                        panodata: panolist,
                        defaultscene: defaultscene,
                        preview: preview,
                        rotation: rotation,
                        autorotation: autorotation,
                        autorotationinactivedelay: autorotationinactivedelay,
                        autorotationstopdelay: autorotationstopdelay,
                        genericform: genericform,
                        genericformshortcode: genericformshortcode,
                        scenefadeduration: scenefadeduration,
                        cpLogoSwitch: cpLogoSwitch,
                        cpLogoImg: cpLogoImg,
                        cpLogoContent: cpLogoContent,
                        wpvr_bg_tour_enabler: wpvr_bg_tour_enabler,
                        wpvr_floor_plan_enabler: wpvr_floor_plan_enabler,
                        wpvr_floor_plan_imager: wpvr_floor_plan_imager,
                        floor_list_data: floor_list_data,
                        floor_list_pointer_position: floor_list_pointer_position,
                        wpvr_bg_tour_navmenu_enabler: wpvr_bg_tour_navmenu_enabler,
                        bg_tour_title: bg_tour_title,
                        bg_tour_subtitle: bg_tour_subtitle,
                        bg_music: bg_music,
                        bg_music_url: bg_music_url,
                        autoplay_bg_music: autoplay_bg_music,
                        loop_bg_music: loop_bg_music,
                        explainerSwitch: explainerSwitch,
                        explainerContent: explainerContent,
                        is_flat_image: flat_image,
                        flat_image_url: flat_image_url,
                        flat_image_hotspot_list: flat_image_hotspot_list,
                        floor_plan_custom_color: floor_plan_custom_color,
                        previewtext: previewtext,
                        gzoom: gzoom,
                        dzoom: dzoom,
                        maxzoom: maxzoom,
                        minzoom: minzoom,
                        customColor: cus
                    },

                    success: function (response) {
                        $('.wpvr-loading').hide();

                        if (response.success == false) {
                            $('#error_occured').show();
                            $('#error_occured .pano-error-message').html(response.data);
                            $('#error_occuredpub').show();
                            $('#error_occuredpub').html(response.data);

                            $('body').addClass('error-overlay');
                            $('html, body').animate({
                                scrollTop: $("#error_occured").offset().top
                            }, 500);
                        } else {
                            flag_ok = true;
                            var url_info = wpvr_global_obj.url_info
                            var oldUrl = '';
                            let params = (new URL(document.location)).searchParams;
                            let active_tab = params.get('active_tab');
                            oldUrl = url_info.admin_url+"post.php?"+"post="+postid+"&action=edit"+"&active_tab="+active_tab;
                            if ( 'add' == url_info.screen ){
                                setCookie('redirectUrl', oldUrl, 1);
                                setCookie('postID', postid, 1);
                            }else{
                                setCookie('postID', postid, 1);

                                setCookie('redirectUrl', oldUrl, 1);
                            }
                            $('#publish').trigger('click');
                        }
                    }
                });
            }
        });
    });

    jQuery(document).ready(function ($) {
        $("body, .pano-error-close-btn").on("click", function (e) {
            $("#error_occured").hide();
            $('body').removeClass('error-overlay');
        });

        $(".panolenspreview, #error_occured").on("click", function (e) {
            e.stopPropagation();
        });
    });


    jQuery(document).ready(function ($) {

        var flag_ok = false;
        $('#save-post').on('click', function (e) {
            var x = $(this).val();
            if (!flag_ok) {
                e.preventDefault();
                $('.wpvr-loading').show();
                var postid = $("#post_ID").val();
                var panovideo = $("input[name='panovideo']:checked").val();
                var videourl = $("input[name='video-attachment-url']").val();
                var autoplay = $("input[name='autoplay']:checked").val();
                var loop = $("input[name='loop']:checked").val();
                //streetview//
                var streetview = $("input[name='wpvrStreetView']:checked").val();
                var streetviewurl = $("input[name='streetview-attachment-url']").val();
                //streetview//

                var autoload = $("input[name='autoload']").val();
                var control = $("input[name='controls']").val();

                //==Custom control==//
                var customcontrol = [];
                //== Pan Up==//
                var panupSwitch = $("input[name='panupControl']").val();
                customcontrol['panupSwitch'] = panupSwitch;
                var panupColor = $("input[name='panup-customclass-color']").val();
                customcontrol['panupColor'] = panupColor;
                var panupIcon = $('.panup-customclass-pro-select').find(":selected").val();
                customcontrol['panupIcon'] = panupIcon;
                //== Pan Up END==//

                //== Pan Down==//
                var panDownSwitch = $("input[name='panDownControl']").val();
                customcontrol['panDownSwitch'] = panDownSwitch;
                var panDownColor = $("input[name='panDown-customclass-color']").val();
                customcontrol['panDownColor'] = panDownColor;
                var panDownIcon = $('.panDown-customclass-pro-select').find(":selected").val();
                customcontrol['panDownIcon'] = panDownIcon;
                //== Pan Down END==//

                //== Pan Left==//
                var panLeftSwitch = $("input[name='panLeftControl']").val();
                customcontrol['panLeftSwitch'] = panLeftSwitch;
                var panLeftColor = $("input[name='panLeft-customclass-color']").val();
                customcontrol['panLeftColor'] = panLeftColor;
                var panLeftIcon = $('.panLeft-customclass-pro-select').find(":selected").val();
                customcontrol['panLeftIcon'] = panLeftIcon;
                //== Pan Left END==//

                //== Pan Right==//
                var panRightSwitch = $("input[name='panRightControl']").val();
                customcontrol['panRightSwitch'] = panRightSwitch;
                var panRightColor = $("input[name='panRight-customclass-color']").val();
                customcontrol['panRightColor'] = panRightColor;
                var panRightIcon = $('.panRight-customclass-pro-select').find(":selected").val();
                customcontrol['panRightIcon'] = panRightIcon;
                //== Pan Right END==//

                //== Pan Zoom In==//
                var panZoomInSwitch = $("input[name='panZoomInControl']").val();
                customcontrol['panZoomInSwitch'] = panZoomInSwitch;
                var panZoomInColor = $("input[name='panZoomIn-customclass-color']").val();
                customcontrol['panZoomInColor'] = panZoomInColor;
                var panZoomInIcon = $('.panZoomIn-customclass-pro-select').find(":selected").val();
                customcontrol['panZoomInIcon'] = panZoomInIcon;
                //== Pan Zoom In END==//

                //== Pan Zoom Out==//
                var panZoomOutSwitch = $("input[name='panZoomOutControl']").val();
                customcontrol['panZoomOutSwitch'] = panZoomOutSwitch;
                var panZoomOutColor = $("input[name='panZoomOut-customclass-color']").val();
                customcontrol['panZoomOutColor'] = panZoomOutColor;
                var panZoomOutIcon = $('.panZoomOut-customclass-pro-select').find(":selected").val();
                customcontrol['panZoomOutIcon'] = panZoomOutIcon;
                //== Pan Zoom Out END==//

                //== Pan Fullscreen==//
                var panFullscreenSwitch = $("input[name='panFullscreenControl']").val();
                customcontrol['panFullscreenSwitch'] = panFullscreenSwitch;
                var panFullscreenColor = $("input[name='panFullscreen-customclass-color']").val();
                customcontrol['panFullscreenColor'] = panFullscreenColor;
                var panFullscreenIcon = $('.panFullscreen-customclass-pro-select').find(":selected").val();
                customcontrol['panFullscreenIcon'] = panFullscreenIcon;
                //== Pan Fullscreen END==//

                //== Pan gyro==//
                var gyroscopeSwitch = $("input[name='gyroscope']").val();
                customcontrol['gyroscopeSwitch'] = gyroscopeSwitch;
                var gyroscopeColor = $("input[name='gyroscope-customclass-color']").val();
                customcontrol['gyroscopeColor'] = gyroscopeColor;
                var gyroscopeIcon = $('.gyroscope-customclass-pro-select').find(":selected").val();
                customcontrol['gyroscopeIcon'] = gyroscopeIcon;
                //== Pan gyro END==//

                //== backToHome gyro==//
                var backToHomeSwitch = $("input[name='backToHome']").val();
                customcontrol['backToHomeSwitch'] = backToHomeSwitch;
                var backToHomeColor = $("input[name='backToHome-customclass-color']").val();
                customcontrol['backToHomeColor'] = backToHomeColor;
                var backToHomeIcon = $('.backToHome-customclass-pro-select').find(":selected").val();
                customcontrol['backToHomeIcon'] = backToHomeIcon;
                //== backToHome gyro END==//

                var customcontroldata = $.extend({}, customcontrol);
                // var customcontroldata = JSON.stringify(customcontrol);
                //==Custom control end==//

                //==cp-logo-integration==//
                var cpLogoSwitch = $("input[name='cpLogoSwitch']").is(':checked') ? 'on' : 'off';
                var cpLogoImg = $("input[name='cp-logo-attachment-url']").val();
                var cpLogoContent = $('textarea#cp-logo-content').val();
                //==cp-logo-integration==//

                //=== background tour ===//
                var wpvr_bg_tour_enabler = $("input[name='wpvr_bg_tour_enabler']").val();
                var wpvr_bg_tour_navmenu_enabler = $("input[name='wpvr_bg_tour_navmenu_enabler']").val();
                var bg_tour_title = $("input[name='bg_tour_title']").val();
                var bg_tour_subtitle = $("input[name='bg_tour_subtitle']").val();
                //=== background tour ===//
                //=== Floor Plan==///
                var wpvr_floor_plan_enabler = $("input[name='wpvr_floor_plan_enabler']").val();
                var wpvr_floor_plan_imager = $("input[name='floor-plan-attachment-url']").val();

                //=== Floor Plan==///

                // floor-plan-pointer-list ///

                // floor-plan-pointer-list //
                var vrgallery = $("input[name='vrgallery']").is(':checked') ? 'on' : 'off';
                var vrgallery_title = $("input[name='vrgallery_title']").is(':checked') ? 'on' : 'off';
                var vrgallery_display = $("input[name='vrgallery_display']").is(':checked') ? 'on' : 'off';
                var vrgallery_icon_size = $("input[name='vrgallery_icon_size']").is(':checked') ? 'on' : 'off';
                var gyro = $("input[name='gyro']:checked").is(':checked') ? 'on' : 'off';
                var deviceorientationcontrol = $("input[name='deviceorientationcontrol']").is(':checked') ? 'on' : 'off';
                var compass = $("input[name='compass']").is(':checked') ? 'on' : 'off';
                var mouseZoom = $("input[name='mouseZoom']").is(':checked') ? 'on' : 'off';
                var draggable = $("input[name='draggable']").is(':checked') ? 'on' : 'off';
                var diskeyboard = $("input[name='diskeyboard']").is(':checked') ? 'on' : 'off';
                var keyboardzoom = $("input[name='keyboardzoom']").is(':checked') ? 'on' : 'off';
                var defaultscene = $("input[name='default-scene-id']").val();
                var preview = $("input[name='preview-attachment-url']").val();
                var rotation = $("input[name='autorotation']").val();
                var autorotation = $("input[name='auto-rotation']").val();
                var autorotationinactivedelay = $("input[name='auto-rotation-inactive-delay']").val();
                var autorotationstopdelay = $("input[name='auto-rotation-stop-delay']").val();

                var scenefadeduration = $("input[name='scene-fade-duration']").val();

                //===audio===//
                var bg_music = $("input[name='bg_music']").is(':checked') ? 'on' : 'off';
                var bg_music_url = $("input[name='audio-attachment-url']").val();
                var autoplay_bg_music = $("input[name='autoplay_bg_music']").is(':checked') ? 'on' : 'off';
                var loop_bg_music = $("input[name='loop_bg_music']").is(':checked') ? 'on' : 'off';
                //===audio===//


                if ($('.scene-setup')[0]) {
                    var panodata = $('.scene-setup').repeaterVal();
                    var panolist = JSON.stringify(panodata);
                } else {
                    var panodata = '';
                    var panolist = '';
                }
                var floor_list = [];
                $(".floor-plan-pointer-list ul li").each(function() {
                    var get_scene =   $(this).find(".floor_plan_scene_option").val();
                    var get_name =   $(this).find(".floor_plan_scene_option").attr('name');
                    var get_id =   $(this).find(".plan-delete").attr('data-id');
                    floor_list.push({
                        id  : get_id,
                        name : get_name,
                        value : get_scene
                    });;
                });
                var floor_list_data = JSON.stringify(floor_list);
                var pointer_position = [];
                $(".floor-plan-pointer").each(function() {
                    var get_id =   $(this).attr('id');
                    var data_top =   $(this).attr('data-top');
                    var data_left =   $(this).attr('data-left');
                    var style =   $(this).attr('style');
                    pointer_position.push({
                        id  : get_id,
                        data_top : data_top,
                        data_left : data_left,
                        style : style
                    });
                });
                var floor_list_pointer_position = JSON.stringify(pointer_position);
                var floor_plan_custom_color = '';
                if(wpvr_floor_plan_enabler == 'on' && wpvr_floor_plan_imager != ''){
                    floor_list_data = JSON.stringify(floor_list);
                    floor_list_pointer_position = JSON.stringify(pointer_position);
                    floor_plan_custom_color = $("input[name='floor-plan-background-custom-color']").val();
                }

                jQuery.ajax({

                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "wpvr_save",
                        nonce : wpvr_obj.ajax_nonce,
                        postid: postid,
                        panovideo: panovideo,
                        videourl: videourl,
                        autoplay: autoplay,
                        loop: loop,
                        streetview: streetview,
                        streetviewurl: streetviewurl,
                        control: control,
                        customcontrol: customcontroldata,
                        vrgallery: vrgallery,
                        vrgallery_title: vrgallery_title,
                        vrgallery_display: vrgallery_display,
                        vrgallery_icon_size: vrgallery_icon_size,
                        gyro: gyro,
                        deviceorientationcontrol: deviceorientationcontrol,
                        compass: compass,
                        mouseZoom: mouseZoom,
                        draggable: draggable,
                        diskeyboard: diskeyboard,
                        keyboardzoom: keyboardzoom,
                        autoload: autoload,
                        panodata: panolist,
                        defaultscene: defaultscene,
                        preview: preview,
                        rotation: rotation,
                        autorotation: autorotation,
                        autorotationinactivedelay: autorotationinactivedelay,
                        autorotationstopdelay: autorotationstopdelay,
                        scenefadeduration: scenefadeduration,
                        cpLogoSwitch: cpLogoSwitch,
                        cpLogoImg: cpLogoImg,
                        cpLogoContent: cpLogoContent,
                        wpvr_bg_tour_enabler: wpvr_bg_tour_enabler,
                        wpvr_floor_plan_enabler: wpvr_floor_plan_enabler,
                        wpvr_floor_plan_imager: wpvr_floor_plan_imager,
                        floor_list_data : floor_list_data,
                        floor_list_pointer_position : floor_list_pointer_position,
                        floor_plan_custom_color : floor_plan_custom_color,
                        wpvr_bg_tour_navmenu_enabler: wpvr_bg_tour_navmenu_enabler,
                        bg_tour_title: bg_tour_title,
                        bg_tour_subtitle: bg_tour_subtitle,
                        bg_music: bg_music,
                        bg_music_url: bg_music_url,
                        autoplay_bg_music: autoplay_bg_music,
                        loop_bg_music: loop_bg_music,
                    },

                    success: function (response) {
                        $('.wpvr-loading').hide();
                        if (response.success == false) {
                            $('#error_occured').show();
                            $('#error_occured').html(response.data);
                            $('#error_occuredpub').show();
                            $('#error_occuredpub').html(response.data);

                            $('body').addClass('error-overlay');
                            $('html, body').animate({
                                scrollTop: $("#error_occured").offset().top
                            }, 500);
                        } else {
                            flag_ok = true;
                            $('#save-post').trigger('click');
                        }
                    }
                });
            }
        });
    });


    function initSelect2() {
        $('.wpvr-product-search').each(function (i, obj) {
            if ($(obj).data('select2')) {
                $(obj).select2('destroy');
            }
            $(obj).parent().find('.select2-container').remove();
        });
        $('.wpvr-product-search').each(function (i, obj) {
            $(obj).select2({
                minimumInputLength: 3,
                allowClear: true,
                ajax: {
                    url: wpvr_obj.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            action: 'wpvr_product_search'
                        };
                    },
                    processResults: function (data) {
                        var terms = [];
                        if (data) {
                            $.each(data, function (id, text) {
                                terms.push({
                                    id: id,
                                    text: text
                                });
                            });
                        }
                        return {
                            results: terms
                        };
                    },
                    cache: true
                }
            });
        });
    }

    function wpvrhotspot(hotSpotDiv, args) {
        var argst = args.replace(/\\/g, '');
        $("#custom-ifram").html(argst);
        $("#custom-ifram").fadeToggle();
        $(".iframe-wrapper").toggleClass("show-modal");

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

    jQuery(document).ready(function ($) {

        $("#cross").on("click", function (e) {
            e.preventDefault();
            $("#custom-ifram").fadeOut();
            $(".iframe-wrapper").removeClass("show-modal");
            $('iframe').attr('src', $('iframe').attr('src'));
        });
    });

    jQuery(document).ready(function ($) {

        var i = $('.scene-nav li').eq(-2).find('span').attr('data-index');
        i = parseInt(i);

        $('.scene-setup').repeater({

            defaultValues: {
                'scene-type': 'equirectangular',
                'dscene': 'off',
                'ptyscene': 'off',
                'cvgscene': 'off',
                'chgscene': 'off',
                'czscene': 'off',
            },
            show: function () {
                $('textarea[name*=hotspot-content]').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']],
                    ],
                    callbacks: {
                        onKeyup: function(e) {
                            var getHotspotContent = $(this).val();
                            var getParent        =  $(this).parent();
                            var getMainParent    = getParent.parent()
                            var getClickContent  =  getMainParent.find('.hotspot-url')
                            if(getHotspotContent.length > 0){
                                getClickContent.find('input[name*=hotspot-url').val('')
                                getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                                getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                            }else{
                                getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                            }
                        }
                    }
                });
                $('textarea[name*=hotspot-hover]').summernote({
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']],
                    ],
                });
                if ($(this).parents(".scene-setup").attr("data-limit").length > 0) {

                    if ($(this).parents(".scene-setup").find("div[data-repeater-item]:not(.hotspot-setup div[data-repeater-item])").length <= $(this).parents(".scene-setup").attr("data-limit")) {

                        $(this).slideDown();
                        $(this).removeClass('active');

                        i = i + 1;
                        var scene = 'scene-' + i;

                        $(this).find(".title .scene-num").html(i);

                        $('<li><span data-index="' + i + '" data-href="#' + scene + '"><i class="fa fa-image"></i></span></li>').insertBefore($(this).parent().parent('.scene-setup').find('.scene-nav ul li:last-child'));

                        $(this).attr('id', scene);
                        changehotspotid(i);
                        $(this).siblings('.active').removeClass('active');
                        $(this).addClass('active');
                        // setTimeout(changeicon, 1000);
                        // setTimeout(hotspotblink, 1000);
                    } else {
                        $('.pano-alert .pano-error-message').html('<span class="pano-error-title">Limit Reached</span><p> You can add up to 5 scenes on each tour in the Free version. Upgrade to Pro to create tours with unlimited scenes.</p>');
                        $('body').addClass('error-overlay2');
                        $('.pano-alert').addClass('pano-default-warning').show();
                        $(this).remove();
                    }
                } else {
                    jQuery(this).slideDown();
                    $(this).removeClass('active');

                    i = i + 1;
                    var scene = 'scene-' + i;


                    $(this).find(".title .scene-num").html(i);

                    $('<li><span data-index="' + i + '" data-href="#' + scene + '"><i class="fa fa-image"></i></span></li>').insertBefore($(this).parent().parent('.scene-setup').find('.scene-nav ul li:last-child'));

                    $(this).attr('id', scene);
                    changehotspotid(i);
                }

                $(this).hide();

                //tab active setup
                $('#wpvr_active_scenes').val(i);
                $('#wpvr_active_hotspot').val(1);
            },

            hide: function (deleteElement) {
                initSelect2();
                var hide_id = $(this).attr("id");
                var _hide_id = hide_id.split('-').pop();
                hide_id = "#" + hide_id;

                var current = $(this).attr('id');
                var fchild = $('.single-scene:nth-child(2)').attr('id');

                var elementcontains = $(this).attr("id");
                var str1 = 'scene';
                var str2 = 'hotspot';

                if (elementcontains.indexOf(str1) != -1 && elementcontains.indexOf(str2) == -1) {
                    $('.wpvr-delete-alert-wrapper').addClass('pano-error-color').css('display', 'flex');
                    $('#confirm_text').html(' Are you sure you want to delete this Scene?');
                    var _this = $(this);
                    $('.wpvr-delete-confirm-btn .yes').click(function (e) {
                        e.preventDefault();
                        jQuery(_this).slideUp(deleteElement);
                        if (current == fchild) {
                            $(_this).next().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().children("span").trigger("click");
                        } else {
                            $(_this).prev().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().addClass("active");
                            $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().children("span").trigger("click");
                        }
                        $(_this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").remove();
                        setTimeout(deleteinfodata, 1000);

                        //tab active setup
                        if (parseInt(_hide_id) - 1 > 0) {
                            $('#wpvr_active_scenes').val(parseInt(_hide_id) - 1);
                        } else {
                            $('#wpvr_active_scenes').val(1);
                        }
                        $('#wpvr_active_hotspot').val(1);
                        $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                        $('.wpvr-delete-alert-wrapper').hide();
                    });

                    $(document).on("click", ".wpvr-delete-confirm-btn .cancel, .wpvr-delete-alert-wrapper .cross", function (e) {
                        e.preventDefault();
                        $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                        $('.wpvr-delete-alert-wrapper').hide();
                    });

                    // if (confirm('Are you sure you want to delete?')) {
                    //     jQuery(this).slideUp(deleteElement);
                    //     if (current == fchild) {
                    //         $(this).next().addClass("active");
                    //         $(this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().addClass("active");
                    //         $(this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").next().children("span").trigger("click");

                    //     } else {
                    //         $(this).prev().addClass("active");
                    //         $(this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().addClass("active");
                    //         $(this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").prev().children("span").trigger("click");
                    //     }
                    //     $(this).parent().parent('.scene-setup').find('.scene-nav li span[data-href="' + hide_id + '"]').parent("li").remove();
                    //     setTimeout(deleteinfodata, 1000);

                    //     //tab active setup
                    //     if (parseInt(_hide_id) - 1 > 0) {
                    //         $('#wpvr_active_scenes').val(parseInt(_hide_id) - 1);
                    //     } else {
                    //         $('#wpvr_active_scenes').val(1);
                    //     }
                    //     $('#wpvr_active_hotspot').val(1);
                    // }
                }
            },

            repeaters: [{
                selector: '.hotspot-setup',
                defaultValues: {
                    'hotspot-type': 'info',
                    'hotspot-blink': 'on',
                    'hotspot-customclass-pro': 'none',
                },
                show: function () {
                    $('textarea[name*=hotspot-content]').summernote({
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['codeview', 'help']],
                        ],
                        callbacks: {
                            onKeyup: function(e) {
                                var getHotspotContent = $(this).val();
                                var getParent        =  $(this).parent();
                                var getMainParent    = getParent.parent()
                                var getClickContent  =  getMainParent.find('.hotspot-url')
                                if(getHotspotContent.length > 0){
                                    getClickContent.find('input[name*=hotspot-url').val('')
                                    getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                                    getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                                }else{
                                    getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                                }
                            }
                        }
                    });
                    $('textarea[name*=hotspot-hover]').summernote({
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['codeview', 'help']],
                        ],
                    });
                    if ($(this).parents(".hotspot-setup").attr("data-limit").length > 0) {

                        if ($(this).parents(".hotspot-setup").find("div[data-repeater-item]").length <= $(this).parents(".hotspot-setup").attr("data-limit")) {

                            $(this).slideDown();
                            $(this).removeClass('active');
                            $(this).siblings('.active').removeClass('active');
                            $(this).addClass('active');
                            j = parseInt(j);
                            j = j + 1;
                            var parent_scene = $(this).parent().parent().parent('.single-scene.active').attr('id');
                            var hotspot = parent_scene + '-hotspot-' + j;

                            var replace_string = parent_scene.replace("scene-", "");

                            $(this).find(".title .hotspot-num").html(j);
                            $(this).find(".title .scene-num").html(replace_string);

                            $('<li><span data-index="' + j + '" data-href="#' + hotspot + '"><i class="far fa-dot-circle"></i></span></li>').insertBefore($(this).parent().parent('.hotspot-setup').find('.hotspot-nav ul li:last-child'));

                            $(this).attr('id', hotspot);

                            // setTimeout(changeicon, 1000);
                            // setTimeout(hotspotblink, 1000);
                        } else {
                            $('.pano-alert .pano-error-message').html('<span class="pano-error-title">Limit Reached</span><p> You can add up to 5 hotspots on each scene in the Free version. Upgrade to Pro to add unlimited number of hotspots.</p>');
                            $('body').addClass('error-overlay2');
                            $('.pano-alert').addClass('pano-default-warning').show();
                            $(this).remove();
                        }

                    } else {
                        jQuery(this).slideDown();
                        $(this).removeClass('active');
                        j = parseInt(j);
                        j = j + 1;
                        var parent_scene = $(this).parent().parent().parent('.single-scene.active').attr('id');
                        var hotspot = parent_scene + '-hotspot-' + j;

                        var replace_string = parent_scene.replace("scene-", "");

                        $(this).find(".title .hotspot-num").html(j);
                        $(this).find(".title .scene-num").html(replace_string);

                        $('<li><span data-index="' + j + '" data-href="#' + hotspot + '"><i class="far fa-dot-circle"></i></span></li>').insertBefore($(this).parent().parent('.hotspot-setup').find('.hotspot-nav ul li:last-child'));

                        $(this).attr('id', hotspot);
                    }

                    // init wc options
                    $(this).find('.hotspot-url').show();
                    $(this).find('.hotspot-content').show();
                    $(this).find('.hotspot-hover').show();
                    $(this).find('.hotspot-fluent-forms').hide();
                    var hotspot_products = $(this).find('.hotspot-products');
                    $(this).find('.select2-container').remove();
                    hotspot_products.hide();

                    hotspot_products.find('.wpvr-product-search').select2({
                        minimumInputLength: 3,
                        allowClear: true,
                        ajax: {
                            url: wpvr_obj.ajaxurl,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    term: params.term,
                                    action: 'wpvr_product_search'
                                };
                            },
                            processResults: function (data) {
                                var terms = [];
                                if (data) {
                                    $.each(data, function (id, text) {
                                        terms.push({
                                            id: id,
                                            text: text
                                        });
                                    });
                                }
                                return {
                                    results: terms
                                };
                            },
                            cache: true
                        }
                    });

                    $('#wpvr_active_hotspot').val(j);
                },

                hide: function (deleteElement) {
                    initSelect2();
                    var hotspot_hide_id = $(this).attr("id");
                    var _hide_id = hotspot_hide_id.split('-').pop();
                    hotspot_hide_id = "#" + hotspot_hide_id;


                    var hotspot_current = $(this).attr('id');
                    var hotspot_fchild = $(this).parent().children(":first").attr('id');

                    var hpelementcontains = $(this).attr("id");
                    var hpstr1 = 'scene';
                    var hpstr2 = 'hotspot';
                    if (hpelementcontains.indexOf(hpstr1) != -1 && hpelementcontains.indexOf(hpstr2) != -1) {
                        $('.wpvr-delete-alert-wrapper').addClass('pano-error-color').css('display', 'flex');
                        $('#confirm_text').html(' Are you sure you want to delete this Hotspot?');
                        var _this = $(this);

                        $('.wpvr-delete-confirm-btn .yes').click(function (e) {
                            e.preventDefault();
                            jQuery(_this).slideUp(deleteElement);
                            if (hotspot_current == hotspot_fchild) {
                                $(_this).next().addClass("active");
                                $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").next().addClass("active");

                            } else {
                                $(_this).prev().addClass("active");
                                $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").prev().addClass("active");
                            }


                            $(_this).parent().parent('.hotspot-setup').find('.hotspot-nav li:not(:last-child) span[data-href="' + hotspot_hide_id + '"]').parent("li").remove();

                            //tab active setup
                            if (parseInt(_hide_id) - 1 > 0) {
                                $('#wpvr_active_hotspot').val(parseInt(_hide_id) - 1);
                            } else {
                                $('#wpvr_active_hotspot').val(1);
                            }

                            $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                            $('.wpvr-delete-alert-wrapper').hide();

                        });
                        $(document).on("click", ".wpvr-delete-confirm-btn .cancel, .wpvr-delete-alert-wrapper .cross", function (e) {
                            e.preventDefault();
                            $('.wpvr-delete-alert-wrapper').removeClass('pano-error-color').css('display', 'none');
                            $('.wpvr-delete-alert-wrapper').hide();
                        });

                        // if (confirm('Are you sure you want to deletee?')) {
                        //     jQuery(this).slideUp(deleteElement);
                        //     if (hotspot_current == hotspot_fchild) {
                        //         $(this).next().addClass("active");
                        //         $(this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").next().addClass("active");

                        //     } else {
                        //         $(this).prev().addClass("active");
                        //         $(this).parent().parent('.hotspot-setup').find('.hotspot-nav li span[data-href="' + hotspot_hide_id + '"]').parent("li").prev().addClass("active");
                        //     }


                        //     $(this).parent().parent('.hotspot-setup').find('.hotspot-nav li:not(:last-child) span[data-href="' + hotspot_hide_id + '"]').parent("li").remove();

                        //     //tab active setup
                        //     if (parseInt(_hide_id) - 1 > 0) {
                        //         $('#wpvr_active_hotspot').val(parseInt(_hide_id) - 1);
                        //     } else {
                        //         $('#wpvr_active_hotspot').val(1);
                        //     }
                        // }
                    }

                },

            }]
        });
    });


    var file_frame;
    var parent;
    $(document).on("click", ".scene-upload", function (event) {
        event.preventDefault();
        parent = $(this).parent('.form-group');

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_frame.on('select', function () {

            var attachment = file_frame.state().get('selection').first().toJSON();
            parent.find('.scene-attachment-url').val(attachment.url);
            parent.find('img').attr('src', attachment.url).show();
        });

        file_frame.open();
    });

    var file_frame_fp;
    var fp_parent;
    $(document).on("click", ".floor-plan-upload", function (event) {
        event.preventDefault();
        fp_parent = $(this).parent().parent();
        if (file_frame_fp) {
            file_frame_fp.open();
            return;
        }

        file_frame_fp = wp.media.frames.file_frame = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_frame_fp.on('select', function () {
            var attachment = file_frame_fp.state().get('selection').first().toJSON();
            fp_parent.find('.floor-plan-attachment-url').val(attachment.url);
            fp_parent.find('.img-upload-frame').css('background-image', 'url(' + attachment.url + ')');
            fp_parent.find('.img-upload-frame').addClass('img-uploaded');
            fp_parent.find('.floor-plan-remove-attachment').show();
        });

        file_frame_fp.open();
    });
    //----remove tour preview image----
    $(document).on("click", ".floor-plan-remove-attachment", function (event) {
        $(this).hide();
        parent = $(this).parents('.form-group');

        parent.find('.preview-attachment-url').val('');
        parent.find('.img-upload-frame').css('background-image', '');
        parent.find('.img-upload-frame').removeClass('img-uploaded');
    });

    var file_frames;
    $(document).on("click", ".video-upload", function (event) {
        event.preventDefault();

        parent = $(this).parent('.form-group');

        if (file_frames) {
            file_frames.open();
            return;
        }

        file_frames = wp.media.frames.file_frames = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['video/mp4']
            },
            multiple: false
        });

        file_frames.on('select', function () {
            var attachment = file_frames.state().get('selection').first().toJSON();
            parent.find('.video-attachment-url').val(attachment.url);
        });

        file_frames.open();
    });

    var file_frames;
    $(document).on("click", ".audio-upload", function (event) {
        event.preventDefault();

        parent = $(this).parent('.audio-setting');

        if (file_frames) {
            file_frames.open();
            return;
        }

        file_frames = wp.media.frames.file_frames = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['audio/mpeg']
            },
            multiple: false
        });

        file_frames.on('select', function () {
            var attachment = file_frames.state().get('selection').first().toJSON();
            parent.find('.audio-attachment-url').val(attachment.url);
        });

        file_frames.open();
    });

    var file_fram;
    $(document).on("click", ".preview-upload", function (event) {
        event.preventDefault();

        parent = $(this).parent('.form-group');

        if (file_fram) {
            file_fram.open();
            return;
        }

        file_fram = wp.media.frames.file_fram = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_fram.on('select', function () {
            var attachment = file_fram.state().get('selection').first().toJSON();
            parent.find('.preview-attachment-url').val(attachment.url);
            parent.find('.img-upload-frame').css('background-image', 'url(' + attachment.url + ')');
            parent.find('.img-upload-frame').addClass('img-uploaded');
            parent.find('.remove-attachment').show();
        });

        file_fram.open();
    });

    //----remove tour preview image----
    $(document).on("click", ".remove-attachment", function (event) {
        $(this).hide();
        parent = $(this).parents('.form-group');

        parent.find('.preview-attachment-url').val('');
        parent.find('.img-upload-frame').css('background-image', '');
        parent.find('.img-upload-frame').removeClass('img-uploaded');
    });


    var file_fram;
    $(document).on("click", ".cp-logo-upload", function (event) {
        event.preventDefault();
        parent = $(this).parent('.form-group');

        if (file_fram) {
            file_fram.open();
            return;
        }

        file_fram = wp.media.frames.file_fram = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['image']
            },
            multiple: false
        });

        file_fram.on('select', function () {
            var attachment = file_fram.state().get('selection').first().toJSON();
            parent.find('.cp-logo-attachment-url').val(attachment.url);
            parent.find('img.cp-logo-img').attr('src', attachment.url).show();
            parent.find('.logo-upload-frame').addClass('img-uploaded');
        });

        file_fram.open();
    });


    $(document).on("change", "select[name*=hotspot-type]", function (event) {

        var getparent = $(this).parent();
        var getvalue = $(this).val();
        if (getvalue == 'info') {
            getparent.find('.hotspot-scene').hide();
            getparent.find('.hotspot-scene .hotspotscene').val('none');
            getparent.find('.hotspot-scene input').val('');
            getparent.find('.hotspot-url').show();
            getparent.find('.s_tab').show();
            getparent.find('.hotspot-content').show();
            getparent.find('.hotspot-products').hide();
            getparent.find('.hotspot-products .wpvr-product-search option').remove();
            getparent.find('.hotspot-fluent-forms').hide();
            getparent.find('.hotspot-fluent-forms .wpvr-fluent-forms').val('0');
            getparent.find('input[name*=hotspot-url]').attr("disabled",false)
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", true);
        } else if (getvalue === 'wc_product') {
            getparent.find('.hotspot-scene').hide();
            getparent.find('.hotspot-scene .hotspotscene').val('none');
            getparent.find('.hotspot-scene input').val('');
            getparent.find('.hotspot-url').hide();
            getparent.find('.hotspot-url input').val('');
            getparent.find('.s_tab').hide();
            getparent.find('.s_tab input').val('off');
            getparent.find('.s_tab input').removeAttr('checked');
            getparent.find('.hotspot-content').hide();
            getparent.find('.hotspot-content textarea').val('');
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');
            getparent.find('.hotspot-fluent-forms').hide();
            getparent.find('.hotspot-fluent-forms .wpvr-fluent-forms').val('0');
            getparent.find('.hotspot-products').show();
        } else if (getvalue === 'fluent_form') {
            getparent.find('.hotspot-scene').hide();
            getparent.find('.hotspot-scene .hotspotscene').val('none');
            getparent.find('.hotspot-scene input').val('');
            getparent.find('.hotspot-url').hide();
            getparent.find('.hotspot-url input').val('');
            getparent.find('.s_tab').hide();
            getparent.find('.s_tab input').val('off');
            getparent.find('.s_tab input').removeAttr('checked');
            getparent.find('.hotspot-content').hide();
            getparent.find('.hotspot-content textarea').val('');
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');
            getparent.find('.hotspot-fluent-forms').show();
            getparent.find('.hotspot-products').hide();
            getparent.find('.hotspot-products .wpvr-product-search option').remove();
        } else {
            getparent.find('.hotspot-scene').show();
            // getparent.find('.hotspot-scene input').val('');
            getparent.find('.hotspot-url').hide();
            getparent.find('.hotspot-url input').val('');
            getparent.find('.s_tab').hide();
            getparent.find('.s_tab input').val('off');
            getparent.find('.s_tab input').removeAttr('checked');
            getparent.find('.hotspot-content').hide();
            getparent.find('.hotspot-content textarea').val('');
            getparent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');
            getparent.find('.hotspot-fluent-forms').hide();
            getparent.find('.hotspot-fluent-forms .wpvr-fluent-forms').val('0');
            getparent.find('.hotspot-products').hide();
            getparent.find('.hotspot-products .wpvr-product-search option').remove();
        }
    });

    $(document).ready(function ($){
        $(document).on("keyup","input[name*=hotspot-url]",function (){
            var getHotspotUrl = $(this).val();
            var getParent        =  $(this).parent();
            var getMainParent    = getParent.parent()
            var getClickContent  =  getMainParent.find('.hotspot-content')
            if(getHotspotUrl.length > 0){
                getClickContent.find('textarea').val('')
                getClickContent.find('textarea').attr('placeholder','You can set either a URL or an On-click content')
                getClickContent.find('textarea').attr("disabled",true)
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", false);
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").html('');
            }else{
                getClickContent.find('textarea').attr("disabled",false)
                getClickContent.find('textarea[name*=hotspot-content]').next().find(".note-editable").attr("contenteditable", true);
            }
        })
        $(document).on("keyup","textarea[name*=hotspot-content]",function (){
            var getHotspotContent = $(this).val();
            var getParent        =  $(this).parent();
            var getMainParent    = getParent.parent()
            var getClickContent  =  getMainParent.find('.hotspot-url')
            if(getHotspotContent.length > 0){
                getClickContent.find('input[name*=hotspot-url').val('')
                getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
            }else{
                getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
            }
        })
    })

    $(document).on("change", "input[type=radio][name=panovideo]", function (event) {
        var getvalue = $(this).val();
        if (getvalue == 'on') {
            $('#confirm_text').html('Turning On The Video Option Will Erase Your Virtual Tour Data. Are You Sure?');
            $('.wpvr-delete-alert-wrapper').css('display', 'flex');

        } else {
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $(".video-setting").hide();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.streetview").show();
            $("li.background-tour").show();
            $("li.floor-plan").show();
            $(".wpvrStreetView_on").prop('checked', false);
            $(".wpvrStreetView_off").prop('checked', true);
            $(".streetviewcontent").hide();
            $(".video_on").prop('checked', false);
            $(".video_off").prop('checked', true);
        }

        $(document).on("click", ".wpvr-delete-confirm-btn .cancel,.wpvr-delete-alert-wrapper .cross", function (e) {
            e.preventDefault();

            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".video_on").prop('checked', false);
            $(".video_off").prop('checked', true);
            $("li.video").show();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.streetview").show();
            $("li.background-tour").show();
            $(".video-setting").hide();

        });

        $(document).on("click", ".wpvr-delete-confirm-btn .yes", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".video_off").prop('checked', false);
            $(".video_on").prop('checked', true);
            $(".video-setting").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.streetview").hide();
            $(".rex-pano-tab.streetview").hide();
            $("li.background-tour").hide();
            $("li.videos").show();
            $("li.export").hide();
            $("li.floor-plan").hide();
        });
    });

    $(document).on("change", "input[type=checkbox][name=bg_music]", function (event) {
        var getvalue = $(this).val();

        if (getvalue == 'on') {
            $('.bg-music-content').show();
        } else {
            $('.bg-music-content').hide();
        }
    });

    $(document).on("change", "input[type=radio][name=wpvrStreetView]", function (event) {
        var getvalue = $(this).val();
        if (getvalue == 'on') {
            $('#confirm_text').html('Turning On The StreetView Option Will Erase Your Virtual Tour Data. Are You Sure?');
            $('.wpvr-delete-alert-wrapper').css('display', 'flex');
            $("li.streetview").show();
        } else {
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrStreetView_on").prop('checked', false);
            $(".wpvrStreetView_off").prop('checked', true);

            $(".streetviewcontent").hide();
            $("li.streetview").show();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.videos").show();
            $("li.background-tour").show();
            $("li.floor-plan").show();
            $(".video_on").prop('checked', false);
            $(".video_off").prop('checked', true);
            $(".video-setting").hide();
            $(".streetviewcontent").hide();

        }

        $(document).on("click", ".wpvr-delete-confirm-btn .cancel,.wpvr-delete-alert-wrapper .cross", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrStreetView_on").prop('checked', false);
            $(".wpvrStreetView_off").prop('checked', true);

            $(".streetviewcontent").hide();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.videos").show();
            $("li.background-tour").show();
        });

        $(document).on("click", ".wpvr-delete-confirm-btn .yes", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrStreetView_off").prop('checked', false);
            $(".wpvrStreetView_on").prop('checked', true);

            $(".streetviewcontent").show();

            $("li.streetview").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.videos").hide();
            $("li.background-tour").hide();
            $("li.export").hide();
            $("li.floor-plan").hide();
        });
    });


    $(document).on("click", "input[type=radio][name=wpvrBackgroundTour]", function (event) {
        var getvalue = $(this).val();
        if (getvalue == 'on') {
            $('#confirm_text').html('Turning On The Background Tour Option Will Erase Your Virtual Tour Data. Are You Sure?');
            $('.wpvr-delete-alert-wrapper').css('display', 'flex');

        } else {
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrBackgroundTour_on").prop('checked', false);
            $(".wpvrBackgroundTour_off").prop('checked', true);

            $(".streetviewcontent").hide();
            $(".backgroundTour").show();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.video").show();

        }

        $(document).on("click", ".wpvr-delete-confirm-btn .cancel", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrBackgroundTour_on").prop('checked', false);
            $(".wpvrBackgroundTour_off").prop('checked', true);

            $(".backgroundTour").show();
            $(".streetviewcontent").show();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.video").show();
        });

        $(document).on("click", ".wpvr-delete-confirm-btn .yes", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();

            $(".wpvrBackgroundTour_off").prop('checked', false);
            $(".wpvrBackgroundTour_on").prop('checked', true);

            $(".backgroundTour").show();
            $(".streetviewcontent").hide();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.video").hide();

        });
    });




    jQuery(document).ready(function ($) {
        var vrgallery = $("input[type=checkbox][name=vrgallery]").val();

        if (vrgallery == 'on' || vrgallery == 1) {
            $('.gallery_title').show();
        } else {
            $('.gallery_title').hide();
        }
    });

    $(document).on("change", "input[type=checkbox][name=vrgallery]", function (event) {
        var vrgallery = $(this).val();

        if (vrgallery == 'on') {
            $('.gallery_title').show();
            $('.gallery_display').show();
            $('.vrgallery-gallery-icon-size').show();
        } else {
            $('.gallery_title').show();
            $('.gallery_display').hide();
            $('.vrgallery-gallery-icon-size').hide();
        }
    });

    jQuery(document).ready(function ($) {
        var vrgallery = $("input[name=vrgallery]").val();

        if (vrgallery == 'on' || vrgallery == 1) {
            $('.gallery_title').show();
            $('.gallery_display').show();
            $('.vrgallery-gallery-icon-size').show();
        } else {
            $('.gallery_title').show();
            $('.gallery_display').hide();
            $('.vrgallery-gallery-icon-size').hide();
        }
    });


    jQuery(document).ready(function ($) {
        var vrgyro = $("input[name=gyro]").val();
        if (vrgyro == 'on' || vrgyro == 1) {
            $('.gyro-orientation').show();
        } else {
            $('.gyro-orientation').hide();
        }
    });

    $(document).on("change", "input[name=gyro]", function (event) {
        var vrgyro = $(this).val();

        if (vrgyro == 'on') {
            $('.gyro-orientation').show();
        } else {
            $('.gyro-orientation').hide();
        }
    });


    $(document).on("change", "input[type=checkbox][name=bg_music]", function (event) {
        var getvalue = $(this).val();

        if (getvalue == 'on') {
            $('.bg-music-content').show();
        } else {
            $('.bg-music-content').hide();
        }
    });

    jQuery(document).ready(function ($) {
        var getvalue = $("input[type=checkbox][name=bg_music]").val();
        if (getvalue == 'on') {
            $('.bg-music-content').show();
        } else {
            $('.bg-music-content').hide();
        }
    });



    $(document).on("change", "input[type=radio][name=wpvrStreetView]", function (event) {
        var getvalue = $(this).val();
        if (getvalue == 'on') {
            $(".streetviewcontent").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.video").hide();
        } else {
            // $(".streetviewcontent").hide();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.video").show();
        }
    });

    $(document).on("change", "input[type=radio][name=wpvrBackgroundTour]", function (event) {
        var getvalue = $(this).val();
        if (getvalue == 'on') {
            $(".backgroundTour").hide();
            $(".streetviewcontent").hide();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.video").hide();
        } else {
            $(".streetviewcontent").hide();
            $("li.general").show();
            $("li.scene").show();
            $("li.hotspot").show();
            $("li.video").show();
        }
    });


    jQuery(document).ready(function ($) {
        var vrgallery = $("input[type=checkbox][name=vrgallery]").val();

        if (vrgallery == 'on' || vrgallery == 1) {
            $('.gallery_title').show();
        } else {
            $('.gallery_title').hide();
        }
    });

    $(document).on("change", "input[type=checkbox][name=vrgallery]", function (event) {
        var vrgallery = $(this).val();

        if (vrgallery == 'on') {
            $('.gallery_title').show();
        } else {
            $('.gallery_title').hide();
        }
    });


    jQuery(document).ready(function ($) {
        var vrgyro = $("input[name=gyro]").val();

        if (vrgyro == 'on' || vrgyro == 1) {
            $('.gyro-orientation').show();
        } else {
            $('.gyro-orientation').hide();
        }
    });

    $(document).on("change", "input[name=gyro]", function (event) {
        var vrgyro = $(this).val();

        if (vrgyro == 'on') {
            $('.gyro-orientation').show();
        } else {
            $('.gyro-orientation').hide();
        }
    });


    jQuery(document).ready(function ($) {
        var viddata = $("input[name='panovideo']:checked").val();
        if (viddata == 'on') {
            $("li.scene").removeClass('active');
            $(".rex-pano-tab.wpvr_scene").removeClass('active');
            $("li.general").removeClass('active');
            $(".rex-pano-tab.general").removeClass('active');
            $("li.videos").addClass('active');
            $(".rex-pano-tab.video").addClass('active');
            $(".video-setting").show();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.streetview").hide();
            $("li.background-tour").hide();
            $("li.export").hide();
            $("li.floor-plan").hide();
        }
    });

    //===company logo switch setup===//
    jQuery(document).ready(function ($) {
        var cpSwitch = $("input[name='cpLogoSwitch']").val();


        if (cpSwitch == 'on') {
            $('.company-info-wrapper').show();
        } else {
            $('.company-info-wrapper').hide();
        }
    });

    $(document).on("change", "input[name='cpLogoSwitch']", function (event) {
        var cpSwitch = $(this).val();

        if (this.checked) {
            $('.company-info-wrapper').show();
        } else {
            $('.company-info-wrapper').hide();
        }
    });

    //===explainer logo switch setup===//
    jQuery(document).ready(function ($) {
        var explainerSwitch = $("input[name='explainerSwitch']").val();
        if (explainerSwitch == 'on') {
            $('.explainer-info-wrapper').show();
        } else {
            $('.explainer-info-wrapper').hide();
        }
    });

    $(document).on("change", "input[name='explainerSwitch']", function (event) {
        var explainerSwitch = $(this).val();

        if (this.checked) {
            $('.explainer-info-wrapper').show();
        } else {
            $('.explainer-info-wrapper').hide();
        }
    });

    //===company logo switch setup end===//
    jQuery(document).ready(function ($) {
        var streetviewdata = $("input[name='wpvrStreetView']:checked").val();
        if (streetviewdata == 'on') {
            $("li.general").removeClass('active');
            $(".rex-pano-tab.general").removeClass('active');
            $(".rex-pano-tab.wpvr_scene").removeClass('active');
            $("li.scene").removeClass('active');
            $("li.videos").removeClass('active');
            $(".rex-pano-tab.video").removeClass('active');
            $(".video-setting").hide();
            $("li.videos").hide();
            $("li.background-tour").hide();
            $("li.export").hide();
            $("li.general").hide();
            $("li.scene").hide();
            $("li.hotspot").hide();
            $("li.streetview").addClass('active');
            $(".rex-pano-tab.streetview").addClass('active');
            $(".streetviewcontent").show();
        }
    });


    $(document).on("change", "select[name*=hotspot-customclass-pro]", function (event) {
        var getval = $(this).val();
        $(this).parent('.hotspot-setting').children('span.change-icon').html('<i class="' + getval + '"></i>');

    });

    $(document).on("change", ".hotspot-customclass-color", function (event) {
        var getcolor = $(this).val();
        color = getcolor;
        $(this).parent().find('.hotspot-customclass-color-icon-value').val(getcolor);
        $(this).val(getcolor);
    });


    jQuery(document).ready(function ($) {
        if ($(".icon-found-value")[0]) {
            color = $('.hotspot-customclass-color-icon-value.icon-found-value').val();
        } else {
            color = '#00b4ff';
        }
    });

    function changeicon() {
        $('.hotspot-customclass-color-icon-value').val(color);
        $('.hotspot-customclass-color').val(color);
    }

    $(document).on("change", ".hotspot-blink", function (event) {
        var getblink = $(this).val();
        blink = getblink;
        $(this).val(getblink);
    });

    jQuery(document).ready(function ($) {
        if ($(".blinked")[0]) {
            blink = $('.hotspot-blink.blinked').val();
        } else {
            blink = 'on';
        }
    });

    function hotspotblink() {
        $('.hotspot-blink').val(blink);
    }

    //------------panolens tab js------------------


    $(document).on("click", ".scene-nav ul li:not(:last-child) span", function () {

        var scene_id = $(this).data('index');
        scene_id = '#scene-' + scene_id;

        j = $(scene_id).find('.hotspot-nav li').eq(-2).find('span').attr('data-index');


        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
        $('#wpvr_active_scenes').val($(this).data('index'));
    });

    //add click
    $(document).on("click", ".scene-nav ul li:last-child span", function () {
        var scene_id = $(this).parent('li').prev().children("span").data('index');
        scene_id = '#scene-' + scene_id;
        $(scene_id).removeAttr("style");
        j = $(scene_id).find('.hotspot-nav li').eq(-2).find('span').attr('data-index');
        $('.scene-nav ul li.active').removeClass('active');
        $(this).parent('li').prev().addClass('active');
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
        $('.hotspot-customclass-pro-select').fontIconPicker();
        $('span.change-icon').hide();
    });

    //end add click


    $(document).on("click", ".hotspot-nav ul li:not(:last-child) span", function () {
        $('#wpvr_active_hotspot').val($(this).data('index'));
        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
    });

    $(document).on("click", ".hotspot-nav ul li:last-child span", function () {
        $(this).parent('li').siblings('.active').removeClass('active');
        $(this).parent('li').prev().addClass('active');
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];

            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }

        $('.trtr').trigger('change');
        $('.hotspot-customclass-pro-select').fontIconPicker();
        $('span.change-icon').hide();

    });

    function changehotspotid(id) {
        var scene_id = '#scene-' + id;
        var hotspot_id = 'scene-' + id + '-hotspot-1';
        $(scene_id).find('.hotspot-nav li span').attr('data-href', '#' + hotspot_id + '');
        $(scene_id).find('.single-hotspot').attr('id', hotspot_id);

    }

    $(document).on("click", ".rex-pano-nav-menu.main-nav ul li span", function () {
        var screen = $(this).parent().attr('data-screen');

        var url_info = wpvr_global_obj.url_info
        if ( 'add' == url_info.screen ){
            var newUrl = url_info.admin_url+"post-new.php?"+"post_type="+url_info.param.post_type+"&active_tab="+screen;
        }else{
            var newUrl = url_info.admin_url+"post.php?"+"post="+url_info.param.post+"&action=edit"+"&active_tab="+screen;
        }


        if (window.history != 'undefined' && window.history.pushState != 'undefined') {
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        $('#wpvr_active_tab').val(screen);
        if ('hotspot' == screen) {
            $('.active_scene_id').show();
            var id = $('.single-scene.active').attr('id');
            $('.active_scene_id p').text("Adding Hotspots on Scene: ");
            var scenceID = $('#' + id + ' .sceneid').val();
            var span = '<span>(' + scenceID + ')</span>';
            $('.active_scene_id p').append(span);
            $('.active_scene_id').css({ "background-color": "#E0E1F7", "width": "100%", "text-align": "center", "padding": "10px 15px" });
            $('.active_scene_id p').css({ "color": "black", "font-size": "15px" });
            $('.active_scene_id p span').css({ "color": "#004efa", "font-size": "15px" });

        } else {
            $('.active_scene_id').hide();
        }
        $([$(this).parent()[0], $($(this).data('href'))[0]]).addClass('active').siblings('.active').removeClass('active');
    });

    //-Get Started reloaded -//
    $(document).on("click",".tabs.rex-tabs li a",function(){
        var tab = $(this).attr('href');
        var url = window.location;
        var origin = url.origin;
        var pathname = url.pathname
        var search_perameter = window.location.search.split('&');
        var makeUrl = origin + pathname + search_perameter[0]
        var newUrl = makeUrl + tab;

        if (window.history != 'undefined' && window.history.pushState != 'undefined') {
            window.history.pushState({ path: newUrl }, '', newUrl);
        }
    })

    //----------alert dismiss--------//
    $(document).on("click", "body", function () {
        $('body').removeClass('error-overlay2');
        $('.pano-alert').removeClass('pano-default-warning').hide();
    });
    $(document).on("click", ".pano-alert .pano-error-close-btn", function () {
        $('body').removeClass('error-overlay2');
        $('.pano-alert').removeClass('pano-default-warning').hide();
    });
    $(document).on("click", ".pano-alert, .rex-pano-sub-tabs .rex-pano-tab-nav li.add", function (e) {
        e.stopPropagation();
    });


    $(document).on("click", ".main-nav li.hotspot span", function () {
        $(".hotspot-setup.rex-pano-sub-tabs").show();
        $(".scene-setup > nav.scene-nav").hide();
        $(".scene-setup .single-scene > .scene-content").hide();
        $(".scene-setup .delete-scene").hide();
    });

    $(document).on("click", ".main-nav li.scene span", function () {
        $(".hotspot-setup.rex-pano-sub-tabs").hide();
        $(".scene-setup > nav.scene-nav").show();
        $(".scene-setup .single-scene > .scene-content").show();
        $(".scene-setup .delete-scene").show();
    });

    $(document).on("change", ".dscen", function () {
        var dscene = $(this).val();
        $(".dscen").not(this).each(function () {
            var oth_scene = $(this).val();
            if (dscene == 'on' && oth_scene == 'on') {
                $('#error_occured').show();
                $('#error_occured .pano-error-message').html('<span class="pano-error-title">Default scene has been updated</span>');
                $('body').addClass('error-overlay');
                $('html, body').animate({
                    scrollTop: $("#error_occured").offset().top
                }, 500);
                $(this).val('off');
                // alert('Default scene updated.');
                // $(this).val('off');
            }
        });
    });

    $(document).on("change", ".sceneid", function () {
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
    });

    function deleteinfodata() {
        var sceneinfo = $('.scene-setup').repeaterVal();
        var infodata = sceneinfo['scene-list'];
        $('.hotspotscene').find('option').remove();
        $('.hotspotscene').append("<option value='none'>None</option>");
        for (var i in infodata) {
            var optiondata = infodata[i]['scene-id'];
            if (optiondata != '') {
                $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
            }
        }
    }

    $(document).on("change", ".hotspotscene", function () {

        var chanheghtptpval = $(this).val();
        if (chanheghtptpval != "none") {
            $(this).parent('.hotspot-scene').siblings('.hotspot-scene').children('.hotspotsceneinfodata').val(chanheghtptpval);
        } else {
            $(this).parent('.hotspot-scene').siblings('.hotspot-scene').children('.hotspotsceneinfodata').val('');
        }
    });

    $(document).on("click", ".hotpitch", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var pitchsplit = datacoords[0];
        var pitch = pitchsplit.split(':');
        $(this).parent().parent('.hotspot-setting').children('.hotspot-pitch').val(pitch[1]);
    });

    $(document).on("click", ".hotyaw", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var yawsplit = datacoords[1];
        var yaw = yawsplit.split(':');
        $(this).parent().parent('.hotspot-setting').children('.hotspot-yaw').val(yaw[1]);
    });

    jQuery(document).ready(function ($) {

        if ($(".scene-setup").length > 0) {
            var sceneinfo = $('.scene-setup').repeaterVal();
            var infodata = sceneinfo['scene-list'];
            $('.hotspotscene').find('option').remove();
            $('.hotspotscene').append("<option value='none'>None</option>");
            for (var i in infodata) {
                var optiondata = infodata[i]['scene-id'];
                if (optiondata != '') {
                    $('.hotspotscene').append("<option value='" + optiondata + "'>" + optiondata + "</option>");
                }
            }
        }
    });

    $(document).on("click", ".toppitch", function (event) {
        var datacoords = $('#panodata').text().split(',');
        var pitchsplit = datacoords[0];
        var pitch = pitchsplit.split(':');
        var yawsplit = datacoords[1];
        var yaw = yawsplit.split(':');

        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-pitch').val(pitch[1]);
        $('div.single-scene.rex-pano-tab.active').children('div.hotspot-setup.rex-pano-sub-tabs').children('div.rex-pano-tab-content').children('div.single-hotspot.rex-pano-tab.active.clearfix').find('.hotspot-yaw').val(yaw[1]);
    });
    jQuery(document).ready(function ($) {
        $('.hotspot-customclass-pro-select').fontIconPicker();
        $('.panup-customclass-pro-select').fontIconPicker();
        $('.panDown-customclass-pro-select').fontIconPicker();
        $('.panLeft-customclass-pro-select').fontIconPicker();
        $('.panRight-customclass-pro-select').fontIconPicker();
        $('.panZoomIn-customclass-pro-select').fontIconPicker();
        $('.panZoomOut-customclass-pro-select').fontIconPicker();
        $('.panFullscreen-customclass-pro-select').fontIconPicker();
        $('.gyroscope-customclass-pro-select').fontIconPicker();
        $('.backToHome-customclass-pro-select').fontIconPicker();
        $('.explainer-customclass-pro-select').fontIconPicker();
    });
    jQuery(document).ready(function ($) {
        $('span.change-icon').hide();
    });

    jQuery(document).ready(function ($) {
        var defaultControl = $("input[name='controls']:checked").val();
        if (defaultControl == 'off') {
            $('.controls-wrapper').hide();
        } else {
            $('.controls-wrapper').show();
        }
    });

    $(document).on("change", "input[name='controls']", function (event) {
        var defaultControl = $("input[name='controls']:checked").val();
        if (defaultControl == 'off') {
            $('.controls-wrapper').hide();
        } else {
            $('.controls-wrapper').show();
        }
    });



    jQuery(document).ready(function ($) {

        var autrotateset = $("input[name='autorotation']").is(':checked') ? 'on' : 'off';

        if (autrotateset == 'off') {
            $('.autorotationdata-wrapper').hide();
        } else {
            $('.autorotationdata-wrapper').show();
        }
    });

    $(document).on("change", "input[name='autorotation']", function (event) {
        var autrotateset = $(this).is(':checked') ? 'on' : 'off';

        if (autrotateset == 'on') {
            $('.autorotationdata-wrapper').show();
        } else {
            $('.autorotationdata-wrapper').hide();
        }
    });

    jQuery(document).ready(function ($) {

        var genericform = $("input[name='genericform']").is(':checked') ? 'on' : 'off';

        if (genericform == 'off') {
            $('.generic-form-associates').hide();
        } else {
            $('.generic-form-associates').show();
        }
    });

    $(document).on("change", "input[name='genericform']", function (event) {
        var genericform = $(this).is(':checked') ? 'on' : 'off';

        if (genericform == 'on') {
            $('.generic-form-associates').show();
        } else {
            $('.generic-form-associates').hide();
        }
    });

    $(document).on("change", ".ptyscene", function (event) {
        var ptyscene = $(this).val();

        if (ptyscene == 'off') {
            $(this).parent('.single-settings').siblings('.ptyscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.ptyscenedata').show();
        }
    });


    $(document).on("change", ".cvgscene", function (event) {
        var cvgscene = $(this).val();
        if (cvgscene == 'off') {
            $(this).parent('.single-settings').siblings('.cvgscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.cvgscenedata').show();
        }
    });

    $(document).on("change", ".chgscene", function (event) {
        var chgscenedata = $(this).val();
        if (chgscenedata == 'off') {
            $(this).parent('.single-settings').siblings('.chgscenedata').hide();
        } else {
            $(this).parent('.single-settings').siblings('.chgscenedata').show();
        }
    });

    $(document).on("change", ".czscene", function (event) {
        var czscene = $(this).val();
        if (czscene == 'off') {
            $(this).parent('.single-settings').siblings('.czscenedata').hide();

        } else {
            $(this).parent('.single-settings').siblings('.czscenedata').show();

        }
    });

    var file_imp_frames;
    $(document).on("click", "#wpvr_button_upload", function (e) {
        e.preventDefault();

        var sibling = $(this).siblings('.file-path-wrapper');

        if (file_imp_frames) {
            file_imp_frames.open();
            return;
        }

        file_imp_frames = wp.media.frames.file_imp_frames = wp.media({
            title: $(this).data('uploader_title'),
            button: {
                text: $(this).data('uploader_button_text'),
            },
            library: {
                type: ['zip']
            },
            multiple: false
        });

        file_imp_frames.on('select', function () {
            var attachment = file_imp_frames.state().get('selection').first().toJSON();
            sibling.children('.validate').val(attachment.url);
            sibling.children('.validate').attr('data-value', attachment.id);
        });

        file_imp_frames.open();
    });

    $(document).on("click", "#wpvr_button_submit", function (e) {
        e.preventDefault();
        var ajaxurl = wpvr_obj.ajaxurl;
        $('#wpvr_progress').show();
        $('#wpvr_button_submit').attr('disabled', true);
        var data = $('#wpvr_file_url').val();
        var data_id = $('#wpvr_file_url').attr("data-value");
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            timeout: 100000000000000000000,
            data: {
                action: "wpvr_file_import",
                nonce : wpvr_obj.ajax_nonce,
                fileurl: data,
                data_id: data_id,
            },
            success: function (response) {
                $('#wpvr_progress').hide();
                $('#wpvr_button_submit').attr('disabled', false);

                if (response.success == false) {
                    Materialize.toast(response.data, 10000);
                } else {
                    Materialize.toast('Successfully imported', 10000);
                }
            }
        });
    });

    // $("#wpvr_script_control").change(function() {

    $(document).on("change", "#wpvr_script_control", function (e) {

        if ($('#wpvr_script_control').is(':checked')) {

            $(".wpvr_enqueue_script_list").show();

        } else {

            $(".wpvr_enqueue_script_list").hide();
        }

    });

    $(document).on("change", "#wpvr_video_script_control", function (e) {

        if ($('#wpvr_video_script_control').is(':checked')) {

            $(".wpvr_enqueue_video_script_list").show();

        } else {

            $(".wpvr_enqueue_video_script_list").hide();
        }

    });


    $(document).ready(function (e) {
        if ($('#wpvr_script_control').is(':checked')) {

            $(".wpvr_enqueue_script_list").show();

        } else {

            $(".wpvr_enqueue_script_list").hide();
        }

        /**
         * Sakib
         * Check enable script button is on or not
         * if on then script list field will be show and if off then script list field will be hide
         */
        if ($('#wpvr_video_script_control').is(':checked')) {

            $(".wpvr_enqueue_video_script_list").show();

        } else {

            $(".wpvr_enqueue_video_script_list").hide();
        }

    })




    $(document).on("click", "#wpvr_role_submit", function (e) {
        e.preventDefault();
        var ajaxurl = wpvr_obj.ajaxurl;
        $('#wpvr_role_progress').show();
        $('#wpvr_role_submit').attr('disabled', true);
        var editor = $('#wpvr_editor_active').is(':checked');
        var author = $('#wpvr_author_active').is(':checked');
        var fontawesome = $('#wpvr_fontawesome_disable').is(':checked');
        var cardboard = $('#wpvr_cardboard_disable').is(':checked');
        var wpvr_webp_conversion = $('#wpvr_webp_conversion').is(':checked');
        var mobile_media_resize = $('#mobile_media_resize').is(':checked');
        var wpvr_frontend_notice = $('#wpvr_frontend_notice').is(':checked');
        var wpvr_frontend_notice_area = $('#wpvr_frontend_notice_area').val();
        var wpvr_script_control = $('#wpvr_script_control').is(':checked');
        var wpvr_script_list = $('#wpvr_script_list').val();
        var wpvr_video_script_control = $('#wpvr_video_script_control').is(':checked');
        var wpvr_video_script_list = $('#wpvr_video_script_list').val();
        var high_res_image = $('#high_res_image').is(':checked');
        var woocommerce = $('#wpvr_wc_control').is(':checked');
        var dis_on_hover = $('#dis_on_hover').is(':checked');

        if ( ($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == '') || ($('#wpvr_script_control').is(':checked') && wpvr_script_list == '') ) {
            if(($('#wpvr_script_control').is(':checked') && wpvr_script_list == '')){
                if (confirm('The "List of Allowed Pages To Load WP VR Scripts " Field Is Empty. No Virtual Tours Will Show Up on Your Site.')) {
                    if($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == ''){
                        if (confirm("The 'List of Allowed Pages To Load WPVR Video.js ' Field Is Empty. Any Self-hosted 360-degree videos won't function on your site.")) {
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "wpvr_role_management",
                                    nonce : wpvr_obj.ajax_nonce,
                                    editor: editor,
                                    author: author,
                                    fontawesome: fontawesome,
                                    wpvr_cardboard_disable: cardboard,
                                    wpvr_webp_conversion: wpvr_webp_conversion,
                                    mobile_media_resize: mobile_media_resize,
                                    high_res_image: high_res_image,
                                    dis_on_hover: dis_on_hover,
                                    wpvr_frontend_notice: wpvr_frontend_notice,
                                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                    wpvr_script_control: wpvr_script_control,
                                    wpvr_script_list: wpvr_script_list,
                                    wpvr_video_script_control: wpvr_video_script_control,
                                    wpvr_video_script_list: wpvr_video_script_list,
                                    // woocommerce: woocommerce,
                                },
                                success: function (response) {
                                    $('#wpvr_role_progress').hide();
                                    $('#wpvr_role_submit').attr('disabled', false);

                                    if (response.status == 'success') {
                                        Materialize.toast(response.message, 2000);
                                    }

                                }
                            });
                        } else {
                            $('#wpvr_role_progress').hide();
                            $('#wpvr_role_submit').attr('disabled', false);
                        }
                    }else{
                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: {
                                action: "wpvr_role_management",
                                nonce : wpvr_obj.ajax_nonce,
                                editor: editor,
                                author: author,
                                fontawesome: fontawesome,
                                wpvr_cardboard_disable: cardboard,
                                wpvr_webp_conversion: wpvr_webp_conversion,
                                mobile_media_resize: mobile_media_resize,
                                high_res_image: high_res_image,
                                dis_on_hover: dis_on_hover,
                                wpvr_frontend_notice: wpvr_frontend_notice,
                                wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                wpvr_script_control: wpvr_script_control,
                                wpvr_script_list: wpvr_script_list,
                                wpvr_video_script_control: wpvr_video_script_control,
                                wpvr_video_script_list: wpvr_video_script_list,
                                // woocommerce: woocommerce,
                            },
                            success: function (response) {
                                $('#wpvr_role_progress').hide();
                                $('#wpvr_role_submit').attr('disabled', false);

                                if (response.status == 'success') {
                                    Materialize.toast(response.message, 2000);
                                }

                            }
                        });
                    }
                } else {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                }
            }else if($('#wpvr_video_script_control').is(':checked') && wpvr_video_script_list == ''){

                if (confirm("The 'List of Allowed Pages To Load WPVR Video.js ' Field Is Empty. Any Self-hosted 360-degree videos won't function on your site.")) {
                    if(($('#wpvr_script_control').is(':checked') && wpvr_script_list == '')){
                        if (confirm('The "List of Allowed Pages To Load WP VR Scripts " Field Is Empty. No Virtual Tours Will Show Up on Your Site.')) {
                            jQuery.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: {
                                    action: "wpvr_role_management",
                                    nonce : wpvr_obj.ajax_nonce,
                                    editor: editor,
                                    author: author,
                                    fontawesome: fontawesome,
                                    wpvr_cardboard_disable: cardboard,
                                    wpvr_webp_conversion: wpvr_webp_conversion,
                                    mobile_media_resize: mobile_media_resize,
                                    high_res_image: high_res_image,
                                    dis_on_hover: dis_on_hover,
                                    wpvr_frontend_notice: wpvr_frontend_notice,
                                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                    wpvr_script_control: wpvr_script_control,
                                    wpvr_script_list: wpvr_script_list,
                                    wpvr_video_script_control: wpvr_video_script_control,
                                    wpvr_video_script_list: wpvr_video_script_list,
                                    // woocommerce: woocommerce,
                                },
                                success: function (response) {
                                    $('#wpvr_role_progress').hide();
                                    $('#wpvr_role_submit').attr('disabled', false);

                                    if (response.status == 'success') {
                                        Materialize.toast(response.message, 2000);
                                    }

                                }
                            });
                        }else {
                            $('#wpvr_role_progress').hide();
                            $('#wpvr_role_submit').attr('disabled', false);
                        }
                    }else{
                        jQuery.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: {
                                action: "wpvr_role_management",
                                nonce : wpvr_obj.ajax_nonce,
                                editor: editor,
                                author: author,
                                fontawesome: fontawesome,
                                wpvr_cardboard_disable: cardboard,
                                wpvr_webp_conversion: wpvr_webp_conversion,
                                mobile_media_resize: mobile_media_resize,
                                high_res_image: high_res_image,
                                dis_on_hover: dis_on_hover,
                                wpvr_frontend_notice: wpvr_frontend_notice,
                                wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                                wpvr_script_control: wpvr_script_control,
                                wpvr_script_list: wpvr_script_list,
                                wpvr_video_script_control: wpvr_video_script_control,
                                wpvr_video_script_list: wpvr_video_script_list,
                                // woocommerce: woocommerce,
                            },
                            success: function (response) {
                                $('#wpvr_role_progress').hide();
                                $('#wpvr_role_submit').attr('disabled', false);

                                if (response.status == 'success') {
                                    Materialize.toast(response.message, 2000);
                                }

                            }
                        });
                    }
                } else {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                }
            }

        } else {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "wpvr_role_management",
                    nonce : wpvr_obj.ajax_nonce,
                    editor: editor,
                    author: author,
                    fontawesome: fontawesome,
                    wpvr_cardboard_disable: cardboard,
                    wpvr_webp_conversion: wpvr_webp_conversion,
                    mobile_media_resize: mobile_media_resize,
                    high_res_image: high_res_image,
                    dis_on_hover: dis_on_hover,
                    wpvr_frontend_notice: wpvr_frontend_notice,
                    wpvr_frontend_notice_area: wpvr_frontend_notice_area,
                    wpvr_script_control: wpvr_script_control,
                    wpvr_script_list: wpvr_script_list,
                    wpvr_video_script_control: wpvr_video_script_control,
                    wpvr_video_script_list: wpvr_video_script_list,
                },
                success: function (response) {
                    $('#wpvr_role_progress').hide();
                    $('#wpvr_role_submit').attr('disabled', false);
                    if (response.status == 'success') {
                        Materialize.toast(response.message, 2000);
                    }
                }
            });
        }
    });



    //------general tab's inner tab-------
    jQuery(document).ready(function ($) {
        $('.general-inner-tab .inner-nav li span').on('click', function () {
            var this_id = $(this).attr('data-href');

            $(this).parent('li').addClass('active');
            $(this).parent('li').siblings().removeClass('active');

            $(this_id).show();
            $(this_id).siblings().hide();
        });
    });


    //------active tab scripts-------
    jQuery(document).ready(function($) {
    	function getUrlVars() {
    		var vars = [],
    			hash;
    		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    		for (var i = 0; i < hashes.length; i++) {
    			hash = hashes[i].split('=');
    			vars.push(hash[0]);
    			vars[hash[0]] = hash[1];
    		}
    		return vars;
    	}
    	var activeTab = 'general',
    		vr_main_tab = $('#wpvr-main-nav'),
    		var_main_tab_contents = $('#wpvr-main-tab-contents').find('.rex-pano-tab').not(".single-scene,.single-hotspot"),
    		scene_content = $('.scene-content'),
    		scene_nav = $('.scene-nav'),
    		single_scene = $('.single-scene'),
    		hotspot_content = $('.hotspot-setup'),
    		single_hotspot = $('.single-hotspot'),
    		delete_scene_btn = $('.delete-scene'),
    		delete_hotspot_btn = $('.delete-hotspot');

    	var _q_activeTab = getUrlVars()["active_tab"];
    	var _sceneID = getUrlVars()["scene"];
    	var _hotspotID = getUrlVars()["hotspot"];
    	var default_tabs = ['general', 'scene', 'hotspot', 'video', 'streetview', 'export','backgroundTour','scenes','floorPlan'];
    	if (_q_activeTab) {
    		if (default_tabs.includes(_q_activeTab)) {
                activeTab = _q_activeTab;
                if(activeTab == "backgroundTour" ){
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find(".background-tour").addClass('active');
                }else if( activeTab == "scenes"){
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find(".scene").addClass('active');
                }else if(activeTab == "video" ){
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find(".videos").addClass('active');
                }else if(activeTab == "floorPlan" ){
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find(".floor-plan").addClass('active');
                }
                else{
                    vr_main_tab.find('li:not(:first)').removeClass('active');
                    vr_main_tab.find('.' + activeTab).addClass('active');
                }
                if(activeTab === 'hotspot'){
                    $(".rex-pano-nav-menu.main-nav ul li.hotspot span").trigger('click')
                }

                // scene screens
                var_main_tab_contents.addClass('active');
                if (activeTab === 'scene' || activeTab === 'hotspot') {
                    var_main_tab_contents.not('#scenes').removeClass('active');
                } else if (activeTab === 'export') {
                    var_main_tab_contents.not('#import').removeClass('active');
                } else {
                    var_main_tab_contents.not('#' + activeTab).removeClass('active');
                }

                // scene contents
                if (_sceneID) {
                    var scenesIds = [];
                    var sceneID = '#scene-' + _sceneID;
                    var scene_nav_items = scene_nav.find('li');
                    scene_nav.find('li').each(function() {
                        var index = $(this).find('span').attr('data-index');
                        if (index) {
                            scenesIds.push(index);
                        }
                    });
                    if (scenesIds.includes(_sceneID)) {
                        scene_nav_items.removeClass('active');
                        scene_nav.find('li').each(function() {
                            var index = $(this).find('span').attr('data-index');
                            if (_sceneID == index) {
                                $(this).addClass('active');
                            }
                        });
                        if (activeTab == 'scene' || _sceneID) {
                            single_scene.removeClass('active');
                            $(sceneID).addClass('active');
                        }
                    } else {
                        scene_nav.find('li:first').addClass('active');
                    }

                    if (activeTab === 'scene') {
                        if (scenesIds.includes(_sceneID)) {
                            $(single_scene).removeClass('active');
                            $(sceneID).addClass('active');
                        }
                    } else {
                        $(delete_scene_btn).hide();
                        $(scene_nav).hide();
                        $('.scene-content').hide();
                        $(sceneID).find('.hotspot-setup').show();
                    }

                    //hotspot contents
                    var hotspot_nav = $('.single-scene.active').find('.hotspot-nav');
                    var hotspotIds = [];
                    var hotspot_nav_items = $('.single-scene.active').find('.hotspot-nav').find('li');
                    var activeHotspotId = '#scene-' + _sceneID + 'hotspot-' + _hotspotID;
                    hotspot_nav_items.each(function() {
                        var index = $(this).find('span').attr('data-index');
                        if (index) {
                            hotspotIds.push(index);
                            activeHotspotId = $(this).find('span').attr('data-href');
                        }
                    });
                    if (hotspotIds.includes(_hotspotID)) {
                        hotspot_nav_items.removeClass('active');
                        hotspot_nav.find('li').each(function() {
                            var index = $(this).find('span').attr('data-index');
                            if (_hotspotID === index) {
                                $(this).addClass('active');
                            }
                        });
                        if (activeHotspotId) {
                            $(sceneID).find('.single-hotspot').removeClass('active');
                            $(activeHotspotId).addClass('active');
                        }
                    }
                }
            }
        }

    });

    //------product search-------
    jQuery(document).ready(function ($) {
        $('.wpvr-product-search').select2({
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: wpvr_obj.ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term,
                        action: 'wpvr_product_search'
                    };
                },
                processResults: function (data) {
                    var terms = [];
                    if (data) {
                        $.each(data, function (id, text) {
                            terms.push({
                                id: id,
                                text: text
                            });
                        });
                    }
                    return {
                        results: terms
                    };
                },
                cache: true
            }
        });

        /**
         * Nasim
         * confirmation alert - yes
         */
        $(document).on("click", ".wpvr-delete-confirm-btn .yes", function (e) {
            e.preventDefault();
            $('.wpvr-delete-alert-wrapper').css('display', 'none');
            $('.wpvr-delete-alert-wrapper').hide();
            return false;
        });

        $(document).on("change", "select", function (event) {
            var active_scene = $('.single-scene.rex-pano-tab.active').attr('id');
            var check_scene_selector = $(this).attr('class');

            if (check_scene_selector == 'wpvr-pro-select-scene-type') {
                if ($(this).val() == 'equirectangular') {
                    $('#' + active_scene).find('.cubemap-upload').hide();
                    $('#' + active_scene).find('.equirectangular-upload').show();
                    // $('.cubemap-upload').hide();
                    // $('.equirectangular-upload').show();
                }
                else {
                    $('#' + active_scene).find('.cubemap-upload').show();
                    $('#' + active_scene).find('.equirectangular-upload').hide();
                }

            }
        });
    });
    $(document).ready(function() {
        $('textarea[name*=hotspot-content]').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']],
            ],
            callbacks: {
                onKeyup: function(e) {
                    var getHotspotContent = $(this).val();
                    var getParent        =  $(this).parent();
                    var getMainParent    = getParent.parent()
                    var getClickContent  =  getMainParent.find('.hotspot-url')
                    if(getHotspotContent.length > 0){
                        getClickContent.find('input[name*=hotspot-url').val('')
                        getClickContent.find('input[name*=hotspot-url').attr('placeholder','You can set either a URL or an On-click content')
                        getClickContent.find('input[name*=hotspot-url]').attr("disabled",true)
                    }else{
                        getClickContent.find('input[name*=hotspot-url]').attr("disabled",false)
                    }
                }
            }
        });
        $('textarea[name*=hotspot-hover]').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']],
            ],
        });
    });
    $(document).on("click", ".wpvr_url_open", function (event) {
        if ($(this).val() == 'off') {
            $(this).val('on');
        }
        else {
            $(this).val('off');
        }
    });

    $(document).on("submit", "#trigger-rollback", function (event) {
        event.preventDefault();
        if(confirm('Are you sure?')) {
            let version = $("#trigger-rollback").serialize();
            let redirectUrl = admin_url + 'admin.php?' + version;
            window.location.href = redirectUrl;
        } 
    });

})(jQuery);