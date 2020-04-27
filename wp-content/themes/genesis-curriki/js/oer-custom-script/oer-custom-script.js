jQuery(document).ready(function () {
    //toc-collection-folder
    jQuery(".toc-col-hd").click(function(){
        var toc_folder = jQuery(this).find(".toc-col-folder-persist");
        var id_str = jQuery(this).find(".toc-col-folder-persist").attr("id");
        var id = id_str.split("-")[1];
        
        var col_ul = jQuery("#toc-col-ul-"+id);
        
        if(toc_folder.hasClass("fa-folder"))
        {
            toc_folder.removeClass("fa-folder");
            toc_folder.addClass("fa-folder-open");

        }else if(toc_folder.hasClass("fa-folder-open"))
        {
            toc_folder.removeClass("fa-folder-open");
            toc_folder.addClass("fa-folder");
        }
            
        if(col_ul.hasClass("toc-hide"))
        {            
            col_ul.removeClass("toc-hide");
        }else{
            col_ul.addClass("toc-hide");
        }
            
    });
    
    
    //============ BROWSER REDIRECT ON PAGE COUNT==============
//    var timeStamp = Math.floor(Date.now() / 1000);             
//    //var oer_url = ajaxurl+"&="+timeStamp;        
//    var oer_url = ajaxurl;
//    var set_resource_views_data = {'rid': jQuery("#rid_param").val() ,'pageurl':jQuery("#pageurl_param").val() , 'lvid':jQuery("#lvid").val()};
    
//    jQuery.ajax({
//        method: "POST",
//        url: oer_url ,
//        data : {'action':'cur_oer_page_count' , 'set_resource_views_data' : set_resource_views_data}
//    })
//    .done(function( data ) {
//        var data = JSON.parse(data);        
//        if( data.is_redirect == 1 )
//        {
//            window.location = data.redirect_url;
//        }
//    });
        
    jQuery('.curriki-rating-title-text,.rating-badge').qtip({
        content: {
          text: function(event, api){                            
              var qtip_text_new = jQuery(api.elements.target).next().html();
              return qtip_text_new;
          },
        },
        style: {classes: 'qtipCustomClass'}
    });
    
    
    /**** [start] Managing Download button in content area *****/
    var rs_cn_btn_selector = jQuery(".resource-content-content .asset-display-download .button-link-download");
    if(rs_cn_btn_selector[0] !== undefined)
    {        
        rs_cn_btn_selector[0].onclick = null;        
        rs_cn_btn_selector.click(function(e){
            jQuery("#resource-download-link").trigger("click");
        });        
    }
    /**** [end] Managing Download button in content area *****/
    
    jQuery(".resource-content-content .asset-display-download a.icon-link").click(function(e){
        e.preventDefault();
        var url = jQuery(this).attr("href");
        var fileid = jQuery("#fileid").val();                
        resourceFileDownload(fileid, url);        
        return false;
    });
    
    jQuery(".resource-content-content .asset-display-download p.text-link a").click(function(e){
        e.preventDefault();
        var url = jQuery(this).attr("href");
        var fileid = jQuery("#fileid").val();                
        resourceFileDownload(fileid, url);                
        return false;
    });
    
    if(jQuery(".icon-link").get().length > 0 && !jQuery(".icon-link").hasClass("fa-download"))
    {        
        jQuery(".icon-link").addClass("fa fa-download")
    }
    
    var resource_url = jQuery(".resource-url-link").text();
    var resource_url_arr = resource_url.split("/");
    var resource_url_str = resource_url_arr[resource_url_arr.length-1];    
    jQuery("#lang_sel_list ul li.wpml-ls-item a").each(function(i,obj){        
        var href = jQuery(obj).attr("href");
        jQuery(obj).attr("href",href+resource_url_str);                
    });
    
    loadRecommenderWidget();
    initProgressMonitorFancyBoxes();
    initProgressMonitorBars();
});


/*
var libmodalapp = angular.module('libmodalapp', []);

libmodalapp.controller('libModalController', ['$scope','$http',function($scope,$http){
    NProgress.configure({trickleRate: 0.01, trickleSpeed: 10});           
}]);
*/

function post_resource_drop_process()
{
    //console.log("CHECK ******* ", window.new_node);
    //if(window.new_node !== null)
    //{
        var done_btn = jQuery("#done-btn");
        if( jQuery("#done-btn").hasClass("button-save-disable") )
        {
            done_btn.removeClass("button-save-disable");
        }
    //}
}

function onCancel()
{
    jQuery("#add-to-lib-dialog").hide();
    jQuery("#done-btn").addClass("button-save-disable");
}

function loadRecommenderWidget(){
    
    jQuery.ajax({
        method: "POST",
        url: ajaxurl ,
        data : {'action':'cur_load_recommender_widget', 'resourceid': parseInt(jQuery("input[name='resourceid']").val())}
    })
    .done(function( data ) {                 
        
        if( data.trim().length > 0 )
        {    
            var widgets = JSON.parse(data);
            
            if( typeof widgets.widget_you_may_like !== 'undefined' && widgets.widget_you_may_like.length > 0 ){                
                jQuery("#container_you_may_like").html(widgets.widget_you_may_like);
            }else{
                jQuery("#container_you_may_like").html("");
            }
            
            
            if( typeof widgets.widget_premium_resources !== 'undefined' && widgets.widget_premium_resources.length > 0 ){                
                jQuery("#container_premium_resources").html(widgets.widget_premium_resources);
            }else{
                jQuery("#container_premium_resources").html("");
            }                        
        }
        
    }).always(function(){
        jQuery(".spinner").remove();
    });
}

function oerRegisterProgram(external_module_id) {

    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: {action: "register_user_for_program", external_module_id: external_module_id}
    }).done(function (msg) {
        //window.location = window.location.origin+"/oer/"+jQuery("#pageurl_param").val();        
        jQuery('#fancyBoxInlineAfterRegister').click();
        var oer_link = window.location.origin+"/oer/"+jQuery("#pageurl_param").val();
        var start_program_button = '<p><center><a href="' + oer_link + '" class = "resource-button small-button red-button" style="width: 220px;color: #FFFFFF;text-transform: capitalize;border-radius: 8px;text-align: center;">START PROGRAM</a></center></p>';
        jQuery('#register-program-data').html(start_program_button);
    });
    
}

function initProgressMonitorBars(){            

    jQuery('.progressbar').each(function(i,el){
        var progressbar = jQuery( this ),
        progressLabel = jQuery(this).find( ".progress-label" );
        var completed_score = jQuery(this).attr('completed');        
        progressbar.progressbar({
          value: false,
          change: function() {
            progressLabel.text( progressbar.progressbar( "value" ) + "%" );
          },
          complete: function() {
            progressLabel.text( progressbar.progressbar( "value" ) + "%" );
          }
        });
        progressbar.progressbar( "value", parseInt(completed_score) );
      });
}

function initProgressMonitorFancyBoxes(){

    jQuery("#fancyBoxInlineAfterRegister").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': true,
        'onClosed': function(){
            window.location = window.location.origin+"/oer/"+jQuery("#pageurl_param").val();
        }
    });
    
    jQuery( "#accordion-program" ).accordion({      
        header: "div.playlist-accordion",
        heightStyle: "content",
        activate: function(event, ui) {
            ui.newHeader.find('.library-icon-laas-modal .fa').removeClass('fa-folder').addClass('fa-folder-open');
            ui.oldHeader.find('.library-icon-laas-modal .fa').removeClass('fa-folder-open').addClass('fa-folder');            
        }
    });

    jQuery("#fancyBoxInlineProgressMonitor").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 400,
        'speedOut': 200,
        'overlayShow': true
    });  

    jQuery('.open-progress-link').click( function(e){ 
        e.preventDefault();             
        jQuery('#fancyBoxInlineProgressMonitor').trigger("click");
    });
    
    jQuery('.oer-login-signup-button').click(function(e) {
        window.scrollTo(0,0);
    });

    jQuery("a.open-new-tab").click(function (e) {        
        window.open(jQuery(this).attr('href'));        
    });

    var is_safari = navigator.userAgent.indexOf("Safari") > -1;
    // Chrome has Safari in the user agent so we need to filter (https://stackoverflow.com/a/7768006/1502448)
    var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
    if ((is_chrome) && (is_safari)) {is_safari = false;}  
    if (is_safari) {
        // See if cookie exists (https://stackoverflow.com/a/25617724/1502448)
        if (!document.cookie.match(/^(.*;)?\s*fixed\s*=\s*[^;]+(.*)?$/)) {
            // Set cookie to maximum (https://stackoverflow.com/a/33106316/1502448)
            document.cookie = 'fixed=fixed; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/';
            window.location.replace("https://learn.curriki.me/?_safari_fix=true");
        }
    }
    
}

function loadProgramProgressMonitor(progress_for, program_id){    
}
