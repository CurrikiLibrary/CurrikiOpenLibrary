function showImage () 
{
    disp = jQuery('#images-landing-page').css('display');
    if (disp == 'none') {
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: 'action=get_images',
            success: function(data){
                jQuery('#images-landing-page').css('display', 'block');
                jQuery('#images-landing-page').html(data + '<div class="clear"></div>');
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert('error');
            },
        });

    } else {
        jQuery('#images-landing-page').css('display', 'none');
    }

}
var id_old = ''
function setImageLandingPage(id)
{
    jQuery("#image-landing-page").val(id);
    if (jQuery("#block-image-" + id).length > 0) {
        jQuery("#block-image-" + id).addClass('select-image');
        src = jQuery("#block-image-" + id + " img").attr('src');
        jQuery("#main-image-landing-page").attr('src', src);
        if (id_old != '' && jQuery("#block-image-" + id_old).length > 0) {
            jQuery("#block-image-" + id_old).removeClass('select-image');
        }
        id_old = id;
    }
    showImage();

}