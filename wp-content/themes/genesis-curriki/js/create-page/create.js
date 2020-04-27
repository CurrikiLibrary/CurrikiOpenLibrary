var ajaxurl = create_js_vars.ajaxurl;

// Attach a submit handler to the form
jQuery("#demoform").submit(function (event) {
    // Stop form from submitting normally
    event.preventDefault();
    jQuery('#spinnerModalCenter').modal('show');

    // Get some values from elements on the page:
    var $form = jQuery(this),
        fname = $form.find("input[name='fname']").val(),
        lname = $form.find("input[name='lname']").val(),
        name = fname + ' ' + lname,
        email = $form.find("input[name='email']").val();
        phone = $form.find("input[name='phone']").val();
        organization = $form.find("input[name='organization']").val();
        description = $form.find("textarea[name='description']").val();

    var url = ajaxurl + "?&t=" + new Date().getTime();

    // Send the data using post
    var posting = jQuery.post(url, {
        action: 'cur_ajax_curriki_demo',
        name: name,
        email: email,
        phone: phone,
        organization: organization,
        description: description,
        source: 'create'
    });
    // Put the results in a div

    posting.done(function (data) {
        jQuery('#spinnerModalCenter').modal('toggle');
        if (data.indexOf('demo-done') == -1) {
            jQuery('#message-heading').html('SORRY');
            jQuery('#message-text').html(data);
            jQuery('#notsuremodalsuccess').modal('toggle');
        } else {
            jQuery('#message-heading').html('THANK YOU');
            jQuery('#message-text').html('We will be in touch soon!');
            jQuery('#notsuremodalsuccess').modal('toggle');
        }
    });

    return false;
});