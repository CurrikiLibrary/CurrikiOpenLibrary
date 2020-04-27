jQuery("#cur-translate-with-google").click(function(e){
    e.preventDefault();
    var ed;
    var content_type = (typeof tinyMCE !== 'undefined' && ( ed = tinyMCE.get('content') ) && !ed.isHidden() && ed.hasVisual === true) ? 'rich' : 'html';
    var excerpt_type = (typeof tinyMCE !== 'undefined' && ( ed = tinyMCE.get('excerpt') ) && !ed.isHidden() && ed.hasVisual === true) ? 'rich' : 'html';

    jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: jQuery.param({action: 'cur_fetch_google_translate', post_ID: jQuery('#post_ID').val() , "trid":jQuery("input[name='icl_trid']").val() , 'lang':'en' })
    }).done(function (response) {
//        console.log("data = ", data); 
        var response_obj = JSON.parse(response);
        
        if( response_obj.hasOwnProperty("is_error") && response_obj.is_error === true )
        {
            alert( response_obj.error_message );
        }else{
            var data = response_obj.output_content;        
            if (typeof tinyMCE !== 'undefined' && ( ed = tinyMCE.get('content') ) && !ed.isHidden()) {
                    ed.focus();
                    if (tinymce.isIE) {
                            ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
                    }                
                    tinyMCE.activeEditor.setContent('');
                    ed.execCommand('mceInsertContent', false, data);
            } else {
                    alert("Problem in assigning content!");
            }
            
            if(response_obj.output_title.length > 0)
            {
                jQuery("#title").val(response_obj.output_title);
            }else{
                alert("Problem in assigning Title!");
            }
            
        }        
        
    });
});