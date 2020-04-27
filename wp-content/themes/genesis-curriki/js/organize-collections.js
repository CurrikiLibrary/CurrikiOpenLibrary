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

function curriki_RemoveThisCollectionElement($id) {
    jQuery($id).remove();
    curriki_ArrangeCollectionElements();
}

function curriki_ArrangeCollectionElements() {
    var data = "",
        $collectionid = jQuery("input[name='collectionid']").val();
    jQuery("#sortable-" + $collectionid + " div.library-collection").each(function (i, el) {
        if (data != '') data += ',';
        data += jQuery(el).attr('id') + '=' + i;
    });
    jQuery("#seq-" + $collectionid).val(data);
}

jQuery(document).ready(function () {
    jQuery(".organize-collections-title").click(function () {
        jQuery('.organize-collection-resources').html('Please wait!');
        // hideshowcenter('#organize-collections-dialog', '#organize-collections-dialog');
        jQuery('#modal-collections').modal('show');
        $id = jQuery(this).attr('id');
        $id = $id.substring(27);

        jQuery.ajax({
            method: "POST",
            url: organize_collections_js_vars.url + '/organize-collections-step-2/?' + new Date().getTime(),
            data: {
                collectionid: $id
            }
        })
            .done(function (msg) {
                jQuery('.organize-collection-resources').html(msg);
            });
    });
});