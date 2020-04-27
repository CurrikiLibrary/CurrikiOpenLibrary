function remove_collection(resourceid, collectionid, this_obj) {
    if (resourceid == 0 || collectionid == 0) {
        alert("Contact Administrator!");
    } else {
        //jQuery(this_obj).parents(".library-asset").css("border","1px solid red");
        NProgress.start();
        var ajaxurl = page_user_library_js_vars.ajaxurl;
        jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: { action: 'delete_resource_collection', resourceid: resourceid, collectionid: collectionid }
        }).done(function (data) {
            NProgress.done();
            if (data == "1") {
                var crnt_url = page_user_library_js_vars.crnt_url;
                window.location = crnt_url;
            } else {
                alert("Contact Administrator!");
            }
        });
    }
}