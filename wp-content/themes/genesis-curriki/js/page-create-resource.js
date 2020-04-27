jQuery(document).ready(function () {
    jQuery("#resource-tabs").tabs();

    jQuery("#fancyBoxInline").fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': true
    });

    jQuery('#resource-description').qtip({
        content: {
            text: pcr_ml_obj.description_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-education-level').qtip({
        content: {
            text: pcr_ml_obj.education_level_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-keywords').qtip({
        content: {
            text: pcr_ml_obj.keywords_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-type').qtip({
        content: {
            text: pcr_ml_obj.resource_type_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-standards').qtip({
        content: {
            text: pcr_ml_obj.alignment_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-privileges').qtip({
        content: {
            text: pcr_ml_obj.privileges_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-license').qtip({
        content: {
            text: pcr_ml_obj.license_ml
        },
        style: {classes: 'qtipCustomClass'}
    });


    jQuery('#resource-language').qtip({
        content: {
            text: pcr_ml_obj.language_ml
        },
        style: {classes: 'qtipCustomClass'}
    });

    jQuery('#resource-display-settings').qtip({
        content: {
            text: pcr_ml_obj.settings_ml
        },
        style: {classes: 'qtipCustomClass'}
    });
    jQuery(document ).on('change','#resource_thumb' , function(){ 
        uploadFile();
    });
});

function uploadFile(){
    jQuery("#file-loader-icon").show();
    var file_data = jQuery('#resource_thumb')[0].files[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    form_data.append('action', 'resource_thumb_ajax');
    jQuery.ajax({
        type: 'POST',
        dataType : "json",
        url : ajaxurl,
        cache: false,
        contentType: false,
        processData: false, 
        data : form_data,
        success: function(result){
            if(result.success){
                if(result.response.status == "1"){
                    jQuery("#resource_thumb_img").empty();
                    var imgtag = "<img src='"+result.response.url+"' style='max-width:150px;max-height:150px;' />";
                    var hiddenThumb = "<input type='hidden' name='resource_thumb_hidden' value='"+result.response.url+"' />";
                    jQuery("#resource_thumb_img").append(imgtag);
                    jQuery("#resource_thumb_img").append(hiddenThumb);
                } else {
                    alert("There is some error while uploading image.. Please try again later");
                }
                
            } else {
                alert(result.msg);
            }
            jQuery("#file-loader-icon").hide();
            
        },
        error: function(){
            jQuery("#file-loader-icon").hide();
        }
    });
}

tinymce.init({
    setup:function(ed) {
       ed.on('change', function(e) {
//           console.log('the event object ', e);
//           console.log('the editor object ', ed);
//           console.log('the content ', ed.getContent());
       });
   },
    language: pcr_ml_obj.tinymce_lang,
    selector: "#question_statement, #answer_1, #answer_2, #answer_3, #answer_4, #answer_5, #answer_selection_1, #answer_selection_2, #answer_selection_3, #answer_selection_4, #answer_selection_5, #truefalse_question_statement, #truefalse_answer_selection_1, #truefalse_answer_selection_2",
    menubar: false,
    theme: "modern",
    height: '100',
    width: '99.5%',
    subfolder: "",
    enableLodeStar: trusted,
    relative_urls: false,
    statusbar: true,
    plugins: [
        /*gdocsviewer video*/
        "fileuploaderquestions",
        external_tool
    ],
    content_css: [
        'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/jquery.tooltip.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/bbpress/templates/default/css/bbpress.css?ver=2.5.8-5815',
        'https://www.curriki.org/wp-content/plugins/buddypress/bp-activity/css/mentions.min.css?ver=2.3.4',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/misc.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/tablepress/css/default.min.css?ver=1.6.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/font-awesome.min.css?ver=4.3.0',
        
        'https://www.curriki.org/wp-content/plugins/jetpack/_inc/genericons/genericons/genericons.css?ver=3.1',
        'https://www.curriki.org/wp-content/plugins/jetpack/css/jetpack.css?ver=3.7.2',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/src/skin-win7/ui.fancytree.css',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/lib/prettify.css',
//        'https://www.curriki.org/wp-content/plugins/addthis/css/output.css?ver=4.3.1',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5&ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/curriki-custom-style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/questions_tinymce.css?ver=4.3.1',
    ],
    toolbar1: "oembed image video gdoc lodestar"
});

tinymce.init({
    setup:function(ed) {
       ed.on('change', function(e) {
//           console.log('the event object ', e);
//           console.log('the editor object ', ed);
//           console.log('the content ', ed.getContent());
       });
   },
    language: pcr_ml_obj.tinymce_lang,
    selector: "textarea#elm1",
    theme: "modern",
    width: '99.5%',
    height: '600',
    subfolder: "",
    enableLodeStar: trusted,
    relative_urls: false,
    statusbar: false,
    extended_valid_elements : 'a[accesskey|charset|class|contenteditable|contextmenu|coords|dir|download|draggable|dropzone|hidden|href|hreflang|id|lang|media|name|rel|rev|shape|spellcheck|style|tabindex|target|title|translate|type|onclick|onfocus|onblur],button[onclick|class|title],pre',
    plugins: [
        /*gdocsviewer video*/
//        oembed
        "noneditable fileuploader quiz "+external_tool,
        'advlist autolink lists charmap print preview hr anchor pagebreak spellchecker',
        'searchreplace wordcount visualblocks visualchars fullscreen',
        'insertdatetime nonbreaking save table contextmenu directionality',
        'emoticons paste textcolor colorpicker textpattern imagetools '


                /*
                 "advlist autolink lists charmap print hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars fullscreen insertdatetime nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor"
                 */
    ],
    content_css: [
//        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
//        baseurl + 'wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css',
//        baseurl + 'wp-content/themes/genesis-curriki/css/misc.css',
//        baseurl + 'wp-content/themes/genesis-curriki/css/font-awesome.min.css',
//        baseurl + 'wp-content/themes/genesis-curriki/style.css',
//        baseurl + 'wp-content/themes/genesis-curriki/css/legacy.css',
//        baseurl + 'wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css',
//        baseurl + 'wp-content/plugins/bbpress/templates/default/css/bbpress.css',
//        baseurl + 'wp-content/plugins/buddypress/bp-activity/css/mentions.min.css',
//        baseurl + 'wp-content/plugins/tablepress/css/default.min.css',
//        baseurl + 'wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.4.2',
//        baseurl + 'wp-content/themes/genesis-curriki/css/curriki-custom-style.css'
        'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/jquery.tooltip.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/bbpress/templates/default/css/bbpress.css?ver=2.5.8-5815',
        'https://www.curriki.org/wp-content/plugins/buddypress/bp-activity/css/mentions.min.css?ver=2.3.4',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/misc.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/tablepress/css/default.min.css?ver=1.6.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/font-awesome.min.css?ver=4.3.0',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/plugins/jetpack/_inc/genericons/genericons/genericons.css?ver=3.1',
        'https://www.curriki.org/wp-content/plugins/jetpack/css/jetpack.css?ver=3.7.2',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/src/skin-win7/ui.fancytree.css',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancytree/lib/prettify.css',
//        'https://www.curriki.org/wp-content/plugins/addthis/css/output.css?ver=4.3.1',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5&ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/js/oer-custom-script/oer-custom-style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/curriki-custom-style.css?ver=4.3.1',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/legacy.css?ver=4.3.1',
//        'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
        'https://www.curriki.org/wp-content/themes/genesis-curriki/css/questions_tinymce.css?ver=4.3.1',
    ],
    toolbar1: "oembed image video gdoc lodestar quiz "+external_tool+" | embed emoticons insertdatetime | newdocument undo redo |  cut copy paste searchreplace | spellchecker fullscreen print preview visualblocks visualchars|",
    toolbar2: "styleselect fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ltr rtl "
});





function change_tab($tab) {
    jQuery('a[href="#' + $tab + '"]').first().click();
    jQuery('html, body').animate({scrollTop: 0}, 'slow', function () {
    });
}

function uncheck_subject_areas($this, sub) {
    if (!jQuery($this).is(':checked')) {
        jQuery('.' + sub).attr('checked', false);
    }
}

function check_subject($this, sub) {
    if (jQuery($this).is(':checked') && !jQuery('#' + sub).is(':checked')) {
        jQuery('#' + sub).click();
    }
}

function go_to_dashboard() {
    window.location.href = baseurl + 'dashboard/';
}
