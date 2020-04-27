window.modal_ids_to_hide = ["slide-bottom-popup","complete-profile-popup"];

console.log("window.modal_ids_to_hide = ", window.modal_ids_to_hide);

jQuery.fn.stickToBottomSurveyModal = function () {
    var h = jQuery(this).height();
    var w = jQuery(this).width();
    var wh = jQuery(window).height();
    var ww = jQuery(window).width();
    var wst = jQuery(window).scrollTop();
    var wsl = jQuery(window).scrollLeft();
    this.css("position", "absolute");
    var $top = Math.round((wh - h) / 2 + wst);
    var $left = Math.round((ww - w) / 2 + wsl);

    $top = $top + 270;

    $left = 30;

    this.css("top", $top + "px");
    this.css("left", ($left) + "px");
    
    console.log("SRV top", ($top + "px"));
    console.log("SRV left", (($left) + "px"));
    /*this.css("border", "1px solid red");*/
    return this;
}


var mark_break_sr = false;


jQuery(document).ready(function () {
    
    
    //====== [start] survey visit logic ==========
    var url_srvy = ajaxurl+"?&t=" + new Date().getTime();
    jQuery.ajax({
        url: url_srvy,
        type: 'POST',
        data: { action:'cur_ajax_survey_modal', currentUrl:jQuery("#current-url-visit-for-survey").val() },                        
        success: function (data) {
            var result = JSON.parse(data);
            //console.log("srvy result === ",result);            
        }            
    });
    jQuery("#survey-go").click(function (e) {
        e.preventDefault();
        //console.log("go-link === " , jQuery(this).attr("go-link") );
        
        var url = ajaxurl+"?&t=" + new Date().getTime();                
        jQuery.ajax({
            url: url,
            type: 'POST',
            data: { action:'cur_ajax_survey_modal', currentUrl:jQuery("#current-url-visit-for-survey").val() },                        
            success: function (data) {
                
                var result = JSON.parse(data);
                console.log("result === ",result);
                var location = jQuery("#survey-go").attr("go-link");                
                window.location = location;
            }            
        });
        
    });
    //====== [end]survey visit logic ==========
    
    jQuery("#close-cross-srv").click(function () {
        jQuery('#survey-popup').hide();
    });
    
    jQuery('#survey-popup').show();
    jQuery('#survey-popup').stickToBottomSurveyModal();

    jQuery(window).scroll(function () {
        jQuery('#survey-popup').stickToBottomSurveyModal();
    });


    jQuery("#close-cross-pe,#close-cross-pe-btn").click(function(){
        //handel_dn_modal_close("closedn"); 
        jQuery('#survey-popup').hide();
        mark_break_sr = true;            
        jQuery(window.modal_ids_to_toggle).each(function(i,obj){
            jQuery("#"+obj).show();
        });
    });

    
    
    jQuery(window.modal_ids_to_hide).each(function(i,obj){
        jQuery("#"+obj).hide();
    });
        
    jQuery('#survey-form').on('submit', function(e){
        var url = ajaxurl+"?&t=" + new Date().getTime();
        
        var formDataSrv = new FormData(jQuery(this)[0]);
        formDataSrv.append('action', 'cur_ajax_profile_complete_modal');
        
        
        jQuery.ajax({
            url: url,
            type: 'POST',
            data: formDataSrv,            
            contentType: false,
            processData: false,
            success: function (data) {
                jQuery(".saving-label").fadeOut("slow");
                var result = JSON.parse(data);
                console.log("result === ",result);
                console.log("result === ",result.errors.length);
                if(result.errors.length > 0)
                {
                    complete_porfile_error_on_save(result.errors);
                }else if(result.success.length > 0)
                {
                    jQuery("#close-cross-pe").trigger("click");
                    
                    jQuery('#thank-you-modal').do_center_survey_modal();
                    jQuery('#thank-you-modal').fadeIn();
                    
                    window.setTimeout(function(){
                        window.location.href = window.location.href;
                    }, 2000);
                }                
            }            
        });
        
        e.preventDefault();
    });

});

