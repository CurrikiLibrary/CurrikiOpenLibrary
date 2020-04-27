var lti_lms_modal_add_lms_button_clicked = false;
var lms_select_link_widget_clicked = false;
var lti_form_saved = false;

jQuery.fn.do_center_lti_lms_modal = function () {
   
    var h = jQuery(this).height();
    var w = jQuery(this).width();
    var wh = jQuery(window).height();
    var ww = jQuery(window).width();
    var wst = jQuery(window).scrollTop();
    var wsl = jQuery(window).scrollLeft();
    this.css("position", "absolute");
    var $top = Math.round((wh - h) / 2 + wst);
    var $left = Math.round((ww - w) / 2 + wsl);
    this.css("top", ($top-200) + "px");
    this.css("left", ($left - 50) + "px");
    
    //console.log("me from there == ",$top);
  
    return this;
}

jQuery("document").ready(function(){
    initLMSLinks();
    jQuery("#add_lms_lti").on("click",function(){
        jQuery("#lti-lms-popup-2").html("");
        if(lti_lms_modal_add_lms_button_clicked === false || lms_select_link_widget_clicked===true || lti_form_saved === true)
        {
            jQuery(".loader-pick").clone().show().appendTo("#lti-lms-popup-2");
            lti_lms_modal_add_lms_button_clicked = true;
            jQuery('#lti-lms-popup').do_center_lti_lms_modal();
            jQuery('#lti-lms-popup').addClass("add_lms_lti_show");
            jQuery('#lti-lms-popup').show();
            var t = new Date().valueOf();
            
            jQuery.ajax({
                method: "POST",
                url: base_url+"/manage-lti/?t="+t                
            }).done(function( html ) {
                jQuery("#lti-lms-popup-2").html(html);
                //initLMSLinks();
            });                       
        }
    });
    
    //[start]========== scrolling profile modal =============
        var element = jQuery('#lti-lms-popup'),
        originalY = element.offset().top;
        // Space between element and top of screen (when scrolling)
        var topMargin = 120;
        // Should probably be set in CSS; but here just for emphasis
        element.css('position', 'relative');
        jQuery(window).on('scroll', function (event) {
            var scrollTop = jQuery(window).scrollTop();
                    element.stop(false, false).animate({
            top: scrollTop < originalY ? 0 : scrollTop - originalY + topMargin
            }, 300);
        });
    //[end]========== scrolling profile modal =============    
    
    jQuery("#close-cross-lms-modal").click(function(){        
        jQuery('#lti-lms-popup').hide();        
        jQuery("#lti-lms-popup-2").html("");
        lti_lms_modal_add_lms_button_clicked = false;
        lms_select_link_widget_clicked = false;
        lti_form_saved = false;
    });
});

function initLMSLinks()
{
    
    jQuery(document).on("click",".lms-select-link",function(e){        
        e.preventDefault();
        
        jQuery("#lti-lms-popup-2").html("");
        jQuery(".loader-pick").clone().show().appendTo("#lti-lms-popup-2");
        
        if( jQuery(this).hasClass("lms-select-link-widget") )
        {        
            lti_lms_modal_add_lms_button_clicked = true;
            lms_select_link_widget_clicked = true;
            jQuery('#lti-lms-popup').do_center_lti_lms_modal();
            jQuery('#lti-lms-popup').addClass("add_lms_lti_show");
            jQuery('#lti-lms-popup').show();
        }
        
        //jQuery("#lti-lms-popup-2").html(loader_html.show());
        
        var url_lms_link = jQuery(this).attr("href");
        //alert(url);
        jQuery.ajax({
            method: "POST",
            url: url_lms_link 
        }).done(function( html ) {            
            //jQuery("#lti-lms-popup-2 h2").css("text-align","center");
            jQuery("#lti-lms-popup-2").html(html);
            //initLMSFormSubmit();
        });
    });
    
    jQuery(document).on("click",".lti-go-to-save",function(e){
        e.preventDefault();
        var url_go_to_save = jQuery(this).attr("href");        
        var t = new Date().valueOf();
        url_go_to_save += "&t="+t;        
        //alert(url_go_to_save);
        jQuery("#lti-lms-popup-2").html("");
        jQuery(".loader-pick").clone().show().appendTo("#lti-lms-popup-2");
        
        jQuery.ajax({
            method: "POST",
            url: url_go_to_save 
        }).done(function( html ) {            
            //jQuery("#lti-lms-popup-2 h2").css("text-align","center");
            jQuery("#lti-lms-popup-2").html(html);
            initLMSFormSubmit();
        });
        
    });
}

function initLMSFormSubmit()
{

    jQuery( "#lti-form-save" ).submit(function( event ) {
        event.preventDefault();
        //console.log( "Handler for .submit() called." , jQuery(this).attr("action"));
        //console.log( "Handler for .submit() called." , jQuery(this).serializeArray());
        jQuery.ajax({
            method: "POST",
            url: jQuery(this).attr("action"),
            data: jQuery(this).serializeArray() 
        }).done(function( data ) {                        
            var rtn = JSON.parse(data);            
            if(rtn.done===0)
            {                
                jQuery("#lti_res_message").html(rtn.message).show();
            }else if(rtn.done===1){
                jQuery("#lti_res_message").html(rtn.message).show();
                jQuery("#lms_modal_links_wrapper").html(rtn.lms_modal_links);
                lti_form_saved = true;
                //initLMSLinks();
                setTimeout(function(){
                    jQuery("#close-cross-lms-modal").trigger("click");
                }, 1000);                
            }            
        });
                
    });
}