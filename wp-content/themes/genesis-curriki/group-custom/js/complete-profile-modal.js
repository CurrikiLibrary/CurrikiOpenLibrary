window.modal_ids_to_toggle = ["slide-bottom-popup"];
jQuery.fn.do_center_profile_modal = function () {
    
    var h = jQuery(this).height();
    var w = jQuery(this).width();
    var wh = jQuery(window).height();
    var ww = jQuery(window).width();
    var wst = jQuery(window).scrollTop();
    var wsl = jQuery(window).scrollLeft();
    this.css("position", "absolute");
    var $top = Math.round((wh - h) / 2 + wst);
    var $left = Math.round((ww - w) / 2 + wsl);
    this.css("top", ($top-20) + "px");
    this.css("left", ($left - 50) + "px");
    
    console.log("me from there == ",$top);
    
    return this;
}
jQuery.fn.do_center_thankyou_modal = function () {
    
    var h = jQuery(this).height();
    var w = jQuery(this).width();
    var wh = jQuery(window).height();
    var ww = jQuery(window).width();
    var wst = jQuery(window).scrollTop();
    var wsl = jQuery(window).scrollLeft();
    this.css("position", "absolute");
    var $top = Math.round((wh - h) / 2 + wst);
    var $left = Math.round((ww - w) / 2 + wsl);
    this.css("top", ($top-100) + "px");
    this.css("left", ($left - 30) + "px");
    
    console.log("me from there == ",$top);
    
    return this;
}

var mark_break = false;

function init_data_onload()
{
    window.current_popup = 1;
}

jQuery(document).ready(function () {
    init_data_onload();
    
    
    //[start]========== scrolling profile modal =============
    var element = jQuery('#complete-profile-popup'),
       originalY = element.offset().top;
        // Space between element and top of screen (when scrolling)
        var topMargin = 170;
        // Should probably be set in CSS; but here just for emphasis
        element.css('position', 'relative');
        jQuery(window).on('scroll', function (event) {
            var scrollTop = jQuery(window).scrollTop();
                    element.stop(false, false).animate({
            top: scrollTop < originalY ? 0 : scrollTop - originalY + topMargin
            }, 300);
        });
    //[end]========== scrolling profile modal =============
      
    //[start]========== scrolling TY modal =============
    jQuery(window).scroll(function(){
        jQuery('#thank-you-modal').do_center_thankyou_modal();
    });
    //[end]========== scrolling TY modal =============
      

    jQuery("#close-ty").click(function(){
        jQuery('#thank-you-modal').hide();
    });
    jQuery("#close-cross-pe,#close-cross-pe-btn").click(function(){
        //handel_dn_modal_close("closedn"); 
        jQuery('#complete-profile-popup').hide();
        mark_break = true;            
        jQuery(window.modal_ids_to_toggle).each(function(i,obj){
            jQuery("#"+obj).show();
        });
    });

    //[start]=============== Profile Modal Render  ===========    
    jQuery('#complete-profile-popup').do_center_profile_modal();
    jQuery('#complete-profile-popup').fadeIn(function(){                       
        var timeStamp = Math.floor(Date.now() / 1000);
        var ajax_url = ajaxurl+"?tm="+timeStamp;
        jQuery.ajax({
            method: "POST",
            url: ajax_url ,
            data : {'action':'cur_set_profile_complete_profile_modal_display'}
        })
        .done(function( data ) {
            //var data = JSON.parse(data);                        
            console.log("rtn >> " , data);
        });            
    });
    //[end]=============== Profile Modal Render  ===========    
    
    
    
    jQuery(window.modal_ids_to_toggle).each(function(i,obj){
        jQuery("#"+obj).hide();
    });
    jQuery("#firstname").focus();
        
        
    jQuery("body").on("click","#back-button",function(e){
        e.preventDefault();        
        enable_popup_1();
        window.current_popup = 1;
        
        if(jQuery("#membertype").val() === "teacher")
        {
            enable_next_button();
        }else{
            enable_save_and_finish_button();
        }   
        jQuery(this).addClass("hidden");
    });
    
    jQuery("body").on("click","#next-complete-profile-btn",function(e){                
        e.preventDefault();
        //if( do_validations_popup1() )
        //{
            enable_popup_2();            
            enable_save_and_finish_button();
            jQuery("#back-button").removeClass("hidden");
            window.current_popup = 2;
        //}        
    });    
    jQuery("body").on("click","#save-profile",function(e){        
        e.preventDefault();        
        /*
        if( window.current_popup === 1 && do_validations_popup1() )
        {            
            jQuery("#profile-complete-form").submit();            
        }else if( window.current_popup === 2 && do_validations_popup2() ){            
             jQuery("#profile-complete-form").submit();
        }else{
            
        }
        */
       //alert("gooooooo");
       //jQuery("#profile-complete-form").submit();
       jQuery(this).parents().filter("#profile-complete-form").trigger("submit");
       
    });
    
    jQuery('#profile-complete-form').on('submit', function(e){
        var url = ajaxurl+"?&t=" + new Date().getTime();
        
        var formData = new FormData(jQuery(this)[0]);
        formData.append('action', 'cur_ajax_profile_complete_modal');
        
        jQuery(".saving-label").css("display","block");
        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formData,            
            contentType: false,
            processData: false,
            success: function (data) {
                jQuery(".saving-label").fadeOut("slow");
                
                console.log("data === ", data);
                
                var result = JSON.parse(data);
                console.log("result === ",result);
                console.log("result === ",result.errors.length);
                if(result.errors.length > 0)
                {
                    complete_porfile_error_on_save(result.errors);
                }else if(result.success.length > 0)
                {
                    jQuery("#close-cross-pe").trigger("click");
                    
                    jQuery('#thank-you-modal').do_center_profile_modal();
                    jQuery('#thank-you-modal').fadeIn();
                    
                    window.setTimeout(function(){
                        window.location.href = window.location.href;
                    }, 2000);
                }                
            }            
        });
        
        e.preventDefault();
    });

    jQuery("body").on("change","#membertype",function(e){
        if(jQuery(this).val() === "teacher")
        {
            enable_next_button();
        }else{
            enable_save_and_finish_button();
        }
    });
    jQuery("#membertype").trigger("change");
    
    //jQuery(".showhide_subjectareas").click(function (e) {
    jQuery("ul.subject-areas-ul-complete-profile li").click(function (e) {
        e.stopPropagation();
        //if (jQuery(this).html() == '<span class="subjectareas_plus"> </span>') {        
        if (jQuery(this).find(".toggle-span").hasClass("subjectareas_plus")) {            
            $id = jQuery(this).find(".showhide_subjectareas").attr("id");            
            jQuery('#children_' + $id).show('slow');            
            jQuery(this).find(".toggle-span").removeClass("subjectareas_plus").addClass("subjectareas_minus");
        } else {
            $id = jQuery(this).find(".showhide_subjectareas").attr("id");
            jQuery('#children_' + $id).hide('slow');            
            jQuery(this).find(".toggle-span").removeClass("subjectareas_minus").addClass("subjectareas_plus");
        }
    });
    
});


function enable_save_and_finish_button()
{
    jQuery(".complete-profile-btn").addClass("hidden");
    jQuery("#save-profile").removeClass("hidden");    
}
function enable_next_button()
{
    jQuery(".complete-profile-btn").addClass("hidden");
    jQuery("#next-complete-profile-btn").removeClass("hidden");
}



function enable_popup_1()
{
    jQuery(".complete-profile-popup-cls").addClass("hidden");
    jQuery("#complete-profile-popup-1").removeClass("hidden");
}
function enable_popup_2()
{
    jQuery(".complete-profile-popup-cls").addClass("hidden");
    jQuery("#complete-profile-popup-2").removeClass("hidden");
}

function do_validations_popup2()
{
    jQuery(".error-field").removeClass("error-field");
        jQuery(".edit-section-msg").html("");
        jQuery(".lbl-cls").removeAttr("style");
         
        var is_error = false;
        var errors = new Array();
       
        
        if( jQuery("#school").val().length  === 0 )
        {            
            jQuery("#school").addClass("error-field");
            is_error = true;  
        }
        
        
        var subjectareas = jQuery("input[name='subjectarea[]']:checked").map(function(){return jQuery(this).val();}).get();
        
        if( subjectareas.length  === 0 )
        {            
            jQuery(".subject-areas-ul-complete-profile").addClass("error-field");
            is_error = true;  
        }
        

        if(is_error)
        {
            errors.push(jQuery(".complete-profile-form-msg").text());
            jQuery(".edit-section-msg").html("");
            var error_wrapper = jQuery('<div></div>').addClass("error-bar");

            var error_para = jQuery('<p></p>').addClass("error-bar-para");
            for(i=0; i < errors.length; i++)
            {                    
                jQuery(error_wrapper).append( jQuery(error_para).clone().text(errors[i]) );
            }
            jQuery(".edit-section-msg").prepend(error_wrapper);
            jQuery('html, body').animate({
                scrollTop: jQuery(".edit-section-msg").offset().top + (-150)
            }, 1000);            
        }else{           
            
        }
        
        return (is_error ? false : true);
}

function do_validations_popup1()
{
    jQuery(".error-field").removeClass("error-field");
        jQuery(".edit-section-msg").html("");
        jQuery(".lbl-cls").removeAttr("style");
         
        var is_error = false;
        var errors = new Array();
       
        
        if( jQuery("#membertype").val().length  === 0 )
        {            
            jQuery("#membertype").addClass("error-field");
            is_error = true;  
        }
        if( jQuery("#country").val().length  === 0 )
        {            
            jQuery("#country").addClass("error-field");
            is_error = true;  
        }
        if( jQuery("#city").val().length  === 0 )
        {            
            jQuery("#city").addClass("error-field");
            is_error = true;  
        }
        if( jQuery("#country").val() === "US" && jQuery("#state").val().length  === 0 )
        {            
            jQuery("#state").addClass("error-field");
            is_error = true;  
        }
        
        if( jQuery("#my_photo").hasClass("avatar-required") && jQuery("#my_photo").val().length === 0 )
        {                        
            jQuery("#my_photo").addClass("error-field");
            is_error = true;  
        }

        if(is_error)
        {
            errors.push(jQuery(".complete-profile-form-msg").text());
            jQuery(".edit-section-msg").html("");
            var error_wrapper = jQuery('<div></div>').addClass("error-bar");

            var error_para = jQuery('<p></p>').addClass("error-bar-para");
            for(i=0; i < errors.length; i++)
            {                    
                jQuery(error_wrapper).append( jQuery(error_para).clone().text(errors[i]) );
            }
            jQuery(".edit-section-msg").prepend(error_wrapper);
            jQuery('html, body').animate({
                scrollTop: jQuery(".edit-section-msg").offset().top + (-150)
            }, 1000);            
        }else{           
            
        }
        
        return (is_error ? false : true);
}

function complete_porfile_error_on_save(errors)
{
    jQuery(".error-field").removeClass("error-field");
        jQuery(".edit-section-msg").html("");
        jQuery(".lbl-cls").removeAttr("style");
         
        var is_error = false;
        //var errors = new Array();
       
        
        if(errors.length > 0)
        {
            //errors.push(jQuery(".complete-profile-form-msg").text());
            jQuery(".edit-section-msg").html("");
            var error_wrapper = jQuery('<div></div>').addClass("error-bar");

            var error_para = jQuery('<p></p>').addClass("error-bar-para");
            for(i=0; i < errors.length; i++)
            {                    
                jQuery(error_wrapper).append( jQuery(error_para).clone().text(errors[i]) );
            }
            jQuery(".edit-section-msg").prepend(error_wrapper);
            jQuery('html, body').animate({
                scrollTop: jQuery(".edit-section-msg").offset().top + (-150)
            }, 1000);            
        }else{           
            
        }
        
        return (is_error ? false : true);
}