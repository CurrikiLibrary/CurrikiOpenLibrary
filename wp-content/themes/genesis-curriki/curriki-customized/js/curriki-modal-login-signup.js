function getAjxURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
}

function hideshowcenter($id1, $id2) {
    jQuery($id1).hide();
    jQuery($id2).show();

    if (jQuery.fn.center_func == undefined) {
        jq($id2).center_func();
    } else {
        jQuery($id2).center_func();
    }

    //setInterval(function () {jQuery( $id2 ).center()}, 1);
}

jQuery.fn.center_func = function () {
    var h = jQuery(this).height();
    var w = jQuery(this).width();
    var wh = jQuery(window).height();
    var ww = jQuery(window).width();
    var wst = 0; //jQuery(window).scrollTop();
    var wsl = 0; //jQuery(window).scrollLeft();
    this.css("position", "absolute");
    var $top = Math.round((wh - h) / 2 + wst);
    var $left = Math.round((ww - w) / 2 + wsl);


    this.css("top", $top + "px");
    this.css("left", $left + "px");
    this.css("z-index", "1000");
    return this;
};

jQuery(function () {
    if (curriki_modal_login_signup_js_vars.modal == 'login') {
        hideshowcenter('#login-dialog', '#login-dialog');
    }

    jQuery('#curriki-editable-firstname').click(function () {
        update_profile(jQuery(this), 'firstname');
    });

    jQuery('#curriki-editable-lastname').click(function () {
        update_profile(jQuery(this), 'lastname');
    });

    jQuery('#curriki-editable-bio').click(function () {
        update_profile(jQuery(this), 'bio');
    });

    jQuery('#curriki-editable-city').click(function () {
        update_profile(jQuery(this), 'city');
    });

    jQuery('#curriki-editable-state').click(function () {
        update_profile(jQuery(this), 'state');
    });

    function update_profile($this, field) {
        if (jQuery('input[name=' + field + ']').length)
            return;

        var $val = $this.html();
        $this.html('<input type="text" name="' + field + '" value="' + $val + '" />');
        jQuery('input[name=' + field + ']').focus();
        jQuery("input[name=" + field + "]").focusout(function () {
            $_val = jQuery("input[name=" + field + "]").val();
            $this.html($_val);
            var posting = jQuery.post('?curriki_ajax_action=update_profile', { field: field, value: $_val, curriki_ajax_action: 'update_profile' });
        });
    }
    /*jQuery( "#login-dialog" ).dialog({
     autoOpen: false,
     show: {
     effect: "blind",
     duration: 1000
     },
     hide: {
     effect: "explode",
     duration: 1000
     }
     });*/

    jQuery.fn.center = function () {
        var h = jQuery(this).height();
        var w = jQuery(this).width();
        var wh = jQuery(window).height();
        var ww = jQuery(window).width();
        var wst = 0; //jQuery(window).scrollTop();
        var wsl = 0; //jQuery(window).scrollLeft();
        this.css("position", "absolute");
        var $top = Math.round((wh - h) / 2 + wst);
        var $left = Math.round((ww - w) / 2 + wsl);


        this.css("top", $top + "px");
        this.css("left", $left + "px");
        this.css("z-index", "1000");
        return this;
    }
    jQuery(".class-header-menu-login").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();

        jQuery("#login_result").text("");
        jQuery(".dialog_result_div").removeAttr("style");

        jQuery("#login-dialog").show('slow');
        setInterval(function () {
            jQuery("#login-dialog").center();

        }, 1);
        jQuery("#loginform input[name='log']").val("").focus();
        jQuery("#loginform input[name='pwd']").val("");
    });

    jQuery(".class-header-menu-logout").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();
        jQuery("#logout-dialog").show();
        setInterval(function () {
            jQuery("#logout-dialog").center()
        }, 1);
    });
    jQuery(".class-header-menu-signup").click(function () {
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();

        jQuery("#signup_result").text("");
        jQuery(".dialog_result_div").removeAttr("style");

        jQuery("#signup-dialog").show('slow');
        setInterval(function () {
            jQuery("#signup-dialog").center();
        }, 1);
        jQuery("#signupform input[name='firstname']").val("").focus();
    });

    // Attach a submit handler to the form
    jQuery("#loginform").submit(function (event) {
        jQuery("#login_result").empty().append(jQuery("#please-wait-text-login").val());
        jQuery(".dialog_result_div").css('background-color', '#031770');
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
            login = $form.find("input[name='log']").val(),
            password = $form.find("input[name='pwd']").val();


        var found_lang_param_url = false;
        var ajaxurl_arr = ajaxurl.split("?");
        if (ajaxurl_arr.length == 2) {
            var lang_param = ajaxurl_arr[1].indexOf("lang");
            if (lang_param > -1) {
                found_lang_param_url = true;
            }
        }
        //var url = ajaxurl+'?curriki_ajax_action=signup&t=' + new Date().getTime();
        var url = ajaxurl + "?&t=" + new Date().getTime();
        if (found_lang_param_url) {
            url = ajaxurl + '&t=' + new Date().getTime();
        }
        // Send the data using post        
        //var posting = jQuery.post(url, {action:'cur_ajax_curriki_login',log: login, pwd: password});
        var posting = jQuery.post(url, { action: 'cur_ajax_curriki_login', log: login, pwd: password });
        // Put the results in a div

        posting.done(function (data) {
            //if (data.trim() != '1') {            
            if (data.indexOf('login-done') == -1) {
                jQuery("#login_result").empty().append(data);
                jQuery(".dialog_result_div").css('background-color', '#031770');
            } else {
                jQuery("#login_result").empty().append(jQuery("#please-wait-text-login").val());
                jQuery(".dialog_result_div").css('background-color', '#031770');

                if (jQuery("#fwdreq").val() !== undefined && jQuery("#fwdreq").val().length > 0) {
                    location.reload(true);
                    //                window.location = decodeURI( jQuery("#fwdreq").val() );
                } else {
//                    window.location = curriki_modal_login_signup_js_vars.site_url_current_language_slug + "/";
                    window.location = curriki_modal_login_signup_js_vars.site_url_current_language_slug + "/dashboard";
                }
            }
        });
        return false;

    });

    // Attach a submit handler to the form
    jQuery("#signupform").submit(function (event) {

        jQuery("#signup_result").empty().append(jQuery("#please-wait-text").val());
        jQuery(".dialog_result_div").css('background-color', '#031770');
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
            firstname = $form.find("input[name='firstname']").val(),
            lastname = $form.find("input[name='lastname']").val(),
            username = $form.find("input[name='username']").val(),
            email = $form.find("input[name='email']").val(),
            pwd = $form.find("input[name='pwd']").val(),
            confirm_pwd = $form.find("input[name='confirm_pwd']").val(),
            member_type = $form.find("select[name='member_type'] option:selected").val(),
            country = $form.find("select[name='country'] option:selected").val(),
            state = $form.find("select[name='state'] option:selected").val(),
            city = $form.find("input[name='city']").val(),
            zipcode = $form.find("input[name='zipcode']").val(),
            school = $form.find("input[name='school']").val(),
            gdpr_store_info = $form.find(".gdpr_store_info").is(':checked'),
            accept = $form.find("input[name='accept']").attr('checked') ? '1' : '0',
            gender = $form.find("input[name='gender']:checked").val();
        //url = '?curriki_ajax_action=signup&t=' + new Date().getTime();

        var found_lang_param_url = false;
        var ajaxurl_arr = ajaxurl.split("?");
        if (ajaxurl_arr.length == 2) {
            console.log("ajaxurl_arr = ", ajaxurl_arr);
            var lang_param = ajaxurl_arr[1].indexOf("lang");
            if (lang_param > -1) {
                found_lang_param_url = true;
            }
        }

        var url = ajaxurl + '?curriki_ajax_action=signup&t=' + new Date().getTime();
        if (found_lang_param_url) {
            url = ajaxurl + '&curriki_ajax_action=signup&t=' + new Date().getTime();
        }
        // Send the data using post
        var posting = jQuery.post(url, { action: 'cur_ajax_curriki_signup', firstname: firstname, lastname: lastname, username: username, email: email, pwd: pwd, confirm_pwd: confirm_pwd, member_type: member_type, country: country, state: state, city: city, accept: accept, zipcode: zipcode, gender: gender, school: school, gdpr_store_info: gdpr_store_info });
        // Put the results in a div
        posting.done(function (data) {
            if (data.trim() != '1') {
                jQuery("#signup_result").empty().append(data);
                jQuery(".dialog_result_div").css('background-color', '#031770');
            } else {
                jQuery("#signup_result").empty().append(jQuery("#please-wait-text-login").val());
                jQuery(".dialog_result_div").css('background-color', '#031770');
                //window.location="<?php // echo get_permalink(6015); ?>";
                //            window.location = "<?php // echo site_url() ?>/dashboard/?cn=1";
                // window.location = "<?php // echo site_url()."$current_language_slug"; ?>/dashboard/?cn=1";
                if (jQuery("#fwdreq").val() !== undefined && jQuery("#fwdreq").val().length > 0) {
                    location.reload(true);
                    //                window.location = decodeURI( jQuery("#fwdreq").val() );
                } else {
                    window.location = curriki_modal_login_signup_js_vars.cur_ajax_curriki_signup_window_location;
                }

            }
        });

        return false;

    });

    jQuery("#resetform").submit(function (event) {
        jQuery("#resetform_result").empty().append(jQuery("#please-wait-text-login").val());
        jQuery("#resetform_result").show();
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery(this),
            reset_email = $form.find("input[name='reset_email']").val(),
            url = '?curriki_ajax_action=reset_email&t=' + new Date().getTime();
        // Send the data using post
        var posting = jQuery.post(url, { reset_email: reset_email, action: 'curriki_resetpassword' });
        // Put the results in a div
        posting.done(function (data) {
            if (data.trim() != '1') {
                jQuery("#resetform_result").empty().append(data);
                jQuery(".dialog_result_div").css('background-color', '#031770');
            } else {
                jQuery("#resetform_result").empty().append(curriki_modal_login_signup_js_vars.resetform_result);
                jQuery(".dialog_result_div").css('background-color', '#031770');
                setTimeout(function () {
                    jQuery("#forgotpassword-dialog").hide('slow')
                }, 5000);
            }
        });

        return false;

    });

    jQuery('#country').change(function () {
        var $selected_country = jQuery('#country').val();
        if ($selected_country != 'US') {
            jQuery('#state').val("");
            jQuery('#city').val("");
            jQuery('#state').hide();
            jQuery('#city').hide();
        } else {
            jQuery('#state').show();
            jQuery('#city').show();
        }
    });

    if (curriki_modal_login_signup_js_vars.get_a == 'login') {
        jQuery('.class-header-menu-login a').trigger("click");
        jQuery('#login-dialog').find(".modal-title").after('<p class="message_para">Please Log in or Create an Account to continue to access our free Resources</p>');

        //jQuery("#login-dialog").focus();

        jQuery('html, body').animate({
            scrollTop: jQuery("div.site-container").offset().top
        }, 300);
    }
});


jQuery(document).ready(function($){
    
    $('.class-header-menu-login').click(function(e){
        e.preventDefault();
        $('#loginModal').modal({ backdrop: 'static', keyboard: false });
    });
    $('.class-header-menu-signup').click(function(e){
        e.preventDefault();
        $('#signupModal').modal({ backdrop: 'static', keyboard: false });
    });
    $('.forgotPassword').click(function(e){
        e.preventDefault();
        $('#forgetPasswordModal').modal({ backdrop: 'static', keyboard: false });
        $('#loginModal').modal('hide');
        $('#signupModal').modal('hide');
    });
});