/* 
 Created on : Mar 21, 2016, 8:58:41 PM
 Author     : furqanaziz
 Purpose    : to manage javascript of search module
 */

var $scope = [];
var previousWidth = jQuery(document).width();

function clearSearch() {
    jQuery("#language").val('');
    jQuery("#query").val('');
    jQuery('input[type="checkbox"]').attr('checked', false);
}

function advance($type) {

    console.log($type);
    switch ($type) {
        case 'close':
            if (jQuery(document).width() > 430)
                jQuery('.standards-search').show();

            jQuery('.advance-search').show();
            jQuery('.close-button').hide();

            jQuery('.search-slide').slideUp('slow', function () {
                if (jQuery(".advanced-slide").is(":hidden")) {
                    jQuery('.search-options').removeClass('toggled');
                }
            });
            break;
        case 'advanced':
            jQuery('.advance-search').hide();
            jQuery('.standards-search').hide();
            jQuery('.close-button').show();
            jQuery('.advanced-slide').slideDown('slow');
            jQuery('.search-options').addClass('toggled');
            break;
        case 'standard':
            jQuery('.advance-search').hide();
            jQuery('.standards-search').hide();
            jQuery('.close-button').show();
            jQuery('.standards-slide').slideDown('slow');
            jQuery('.search-options').addClass('toggled');
            break;
    }
}

function uncheck_subject_areas($this, sub) {
    if (!jQuery($this).is(':checked')) {
        jQuery('.' + sub).attr('checked', false);
        jQuery('.' + sub).hide();
    } else {
        jQuery('.' + sub).show();
    }
}

function check_subject($this, sub) {
    if (jQuery($this).is(':checked') && !jQuery('#' + sub).is(':checked')) {
        jQuery('#' + sub).click();
    }
}

function showHoverSubjects(subjectArea, subject) {
    if (jQuery(document).width() > 541) {
        jQuery('.subjectarea').hide();
        jQuery('.subject-optionset li.hover').removeClass('hover');

        jQuery('.' + subjectArea).show();
        jQuery(subject).addClass('hover');
    }
}

jQuery(document).ready(function () {

    activeItem = jQuery("#standards-accordion li:first");
    if (activeItem.length) {
        jQuery(activeItem).addClass("active");
        jQuery("#standards-accordion li .standards-tab-header").click(function () {
            jQuery(activeItem).removeClass("active");
            activeItem = jQuery(this).parent();
            jQuery(activeItem).addClass("active");
        });
        jQuery(activeItem).click();
    }
    
    console.log("tootip = " , tootip_ml_obj);
    
    jQuery('.search-tool-tip').qtip({// Grab some elements to apply the tooltip to
        content: {
            title: {
                text: tootip_ml_obj.tootip_heading,
                button: true
            },
            text: jQuery('.search-tool-tip-text').html()

        },
        style: {
            classes: 'qtip-blue qtip-shadow toolTipCustomClass',
        },
        position: {
            my: 'top right', // Position my top left...
            at: 'bottom left', // at the bottom right of...
            target: jQuery('.search-tool-tip') // my target
        },
        show: 'click',
        hide: {
            event: 'click',
            event: 'blur',
                    //inactive: 6000
        }
    });

    handleResizeSubjectAreas();

    jQuery('.bottomsheet .close').click(function () {
        jQuery('.bottomsheet').hide();
    });
    // Auto submitting on selecting sort dropdown search
    jQuery('#sort, #size, #compact').change(function(){
        jQuery('#search_form').submit();
    });
    jQuery("#search_form .search-button").click(function(e){
//        var testsite = getUrlParameter('testsite');
        if(jQuery('#branding').val() !='common'){
            var icon = jQuery(this).find('.search-button-icon');
            icon.removeClass('fa-search');
            icon.addClass('fa-spinner fa-spin');
            searchesTableEntry();
        }
        else{
            jQuery('#search_form').submit();
        }
        e.preventDefault();
        
    });


    jQuery(document).on('click', '.share-dropdown', function (e) {
        e.stopPropagation();
    });

    jQuery('.share-dropdown .close').on('click', function () {
        jQuery(this).parent().parent().toggleClass('open');
    });
});

//searches table entry
//var getUrlParameter = function getUrlParameter(sParam) {
//    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
//        sURLVariables = sPageURL.split('&'),
//        sParameterName,
//        i;
//
//    for (i = 0; i < sURLVariables.length; i++) {
//        sParameterName = sURLVariables[i].split('=');
//
//        if (sParameterName[0] === sParam) {
//            return sParameterName[1] === undefined ? true : sParameterName[1];
//        }
//    }
//};

var searchesTableEntry = function(){
    /*
    jQuery.post(
        ajaxurl,
        jQuery.param({'action': 'search_analytics', 'branding':jQuery('#branding').val(), 'selectedstandardid': 'test'}),
        function (data) {
            console.log(data);
            jQuery('#search_form').submit();
        });
        */
}

jQuery(window).resize(function () {
    handleResizeSubjectAreas();
});

var handleResizeSubjectAreas = function () {
    if (jQuery(document).width() < 542 && jQuery(".subjectareas li.subjectarea").length) {
        jQuery('.subjectarea').hide();
        listItems = jQuery(".subjectareas li.subjectarea").detach();
        listItems.each(function (index, value) {
            subjectid = jQuery(value).attr('subjectid');
            jQuery('.subjects li[subjectid="' + subjectid + '"] ul').append(value);
            if (jQuery('#subject_' + subjectid).is(':checked'))
                jQuery(value).show();
        });
        console.log('Moved to subjects');
    } else if (jQuery(document).width() > 541 && jQuery(".subjects li.subjectarea").length) {
        listItems = jQuery(".subjects li.subjectarea").detach();
        jQuery(".subjectareas").append(listItems);
        jQuery('.subjectarea').hide();
        console.log('Moved back to subjects areas');
    }
    previousWidth = jQuery(document).width();
}

var collectionMoreInfo = function ($id) {
    jQuery($id + ' .collection-more-info').toggle();
    jQuery($id + ' .more-collection-info').hide();
    jQuery($id + ' .less-collection-info').hide();
    jQuery($id + ' .collection-share').hide();

    if (jQuery($id + ' .collection-more-info').is(':visible'))
        jQuery($id + ' .less-collection-info').css('display', 'inline-block');
    else
        jQuery($id + ' .more-collection-info').css('display', 'inline-block');


}

var collectionShare = function ($id) {
    jQuery($id + ' .collection-share').toggle();
    jQuery($id + ' .collection-more-info').hide();
}

var addToMyLibrary = function ($id) {
    console.log(baseurl);
    console.log($id);
    jQuery.post(
            baseurl + '?add_to=my_library&r_id=' + $id,
            '',
            function (data) {
                load_add_to_lib_modal($id);
            });
}

var currikiRateThis = function (id, title) {
    jQuery('#rate_resource-dialog').show();
    jQuery('#review-resource-id').val(id);
    jQuery('.curriki-review-title').html(title);
    setInterval(function () {
        jQuery('#rate_resource-dialog').centerx()
    }, 1);
}

var getDocumentTitle = function () {
    jQuery('#standardtitle option').hide();
    jQuery('#standardtitle option[jurisdictioncode="' + jQuery('#jurisdictioncode').val() + '"]').show();
    jQuery('#standards-accordion li:eq(1) .standards-tab-header').click();
}


var getNotation = function () {
    jQuery.post(
            ajaxurl,
            jQuery.param({'action': 'get_notation', 'selectedstandardid': jQuery('#standardtitle').val()}),
            function (data) {
                jQuery('#notations').html(data);
                if (selectednotations != "") {
                    jQuery.each(selectednotations.split(","), function (i, e) {
                        jQuery("#notations option[value='" + e + "']").prop("selected", true);
                    });
                    selectednotations = "";
                }
            });

    jQuery('#notations').html("<option value=''>Please Wait ....</option>");
    jQuery('#standards-accordion li:eq(2) .standards-tab-header').click();
}
jQuery(document).ready(function($){
    
//    var single_resource_reject_dialog = jQuery( "#single-resource-reject-dialog" ).dialog({
//      autoOpen: false,
//      resizable: false,
//      height: "auto",
//      width: 500,
//      modal: true,
//    });
    var bulk_resource_reject_dialog = jQuery( "#bulk-resource-reject-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });
    var bulk_resource_approve_dialog = jQuery( "#bulk-resource-approve-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });
    var bulk_resource_pending_dialog = jQuery( "#bulk-resource-pending-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });
    var bulk_group_spam_dialog = jQuery( "#bulk-group-spam-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });
    var bulk_group_not_spam_dialog = jQuery( "#bulk-group-not-spam-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });

    jQuery('.resourceids_checkboxes').change(function(){
        if(this.checked) {
            $el = '<input type="hidden" name="resourceids[]" value="'+jQuery(this).val()+'" />';
            jQuery('.resourceids').append($el);
        } else {
            $el = '<input type="hidden" name="resourceids[]" value="'+jQuery(this).val()+'" />';
            jQuery('.resourceids').find('[value='+jQuery(this).val()+']').remove();
        } 
    });
    jQuery('.groupids_checkboxes').change(function(){
        if(this.checked) {
            $el = '<input type="hidden" name="groupids[]" value="'+jQuery(this).val()+'" />';
            jQuery('.groupids').append($el);
        } else {
            $el = '<input type="hidden" name="groupids[]" value="'+jQuery(this).val()+'" />';
            jQuery('.groupids').find('[value='+jQuery(this).val()+']').remove();
        } 
    });
//    jQuery('.single-moderate-form').submit(function(e){
//        if(jQuery(this).find('select').val() == 'Reject'){
//            var resourceid = jQuery(this).find('[name=resourceid]').val()
//            jQuery('.single-moderate-form-dialog [name=resourceid]').val(resourceid);
//            single_resource_reject_dialog.dialog( "open" );
//            return false;
//        }
//
//    });
    jQuery('.bulk-moderate-form').submit(function(e){

        if(jQuery(this).find('select').val() == 'Reject'){
            bulk_resource_reject_dialog.dialog( "open" );
            return false;
        } else if(jQuery(this).find('select').val() == 'Approve'){
            bulk_resource_approve_dialog.dialog( "open" );
            return false;
        } else if(jQuery(this).find('select').val() == 'Pending'){
            bulk_resource_pending_dialog.dialog( "open" );
            return false;
        } else {
            return false;
        }

    });
    jQuery('.group-bulk-moderate-form').submit(function(e){

        if(jQuery(this).find('select').val() == 'Spam'){
            bulk_group_spam_dialog.dialog( "open" );
            return false;
        } else if(jQuery(this).find('select').val() == 'NotSpam'){
            bulk_group_not_spam_dialog.dialog( "open" );
            return false;
        } else {
            return false;
        }

    });
    
    jQuery('.bulk-moderate-form-dialog').submit(function(){
        var form = jQuery(this);
        form.parent('.form-buttonset').find('.msg').text('Loading...');
        $.ajax({
            url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
            method: "POST", 
            dataType: "json",
            data: 
                jQuery(this).serialize()
            ,
            success:function(data) {
                form.parent('.form-buttonset').find('.msg').text(' ');
                if(data.msg){
                    form.parent('.form-buttonset').find('.msg').html('<div class="success">'+data.msg+"</div>");
                    setTimeout(()=>{
                        window.location.reload();
                    }, 2000);
                } else {
                    form.parent('.form-buttonset').find('.msg').html("<div class='error'>Please select an option</div>");
                }
                
                
                // This outputs the result of the ajax request
//                if(data.success){
//                    jQuery('#recaptcha-msg').modal('hide');
//                } 
            },
            error: function(errorThrown){
            }
        });  
        return false;
    });
    
    jQuery('[name=approvalstatus], [name=groupspam]').change(function(){
        jQuery('#search_form').submit();
    });

    jQuery("[data-toggle=popover]").popover({
        html : true,
        content: function() {
          var content = jQuery(this).attr("data-popover-content");
          return jQuery(content).children(".popover-body").html();
        }
    });

    jQuery('.list-view').click(function(){
        jQuery(this).addClass('active');
        jQuery('.compact-view').removeClass('active');
        jQuery('.category-list').addClass('make-list').removeClass('make-compact');
    });

    jQuery('.compact-view').click(function(){
        jQuery(this).addClass('active');
        jQuery('.list-view').removeClass('active');
        jQuery('.category-list').addClass('make-compact').removeClass('make-list');
    });

    jQuery('#search_form .custom-control-input').change(function(){
        jQuery('#search_form').submit();
    });
});

function setResourceField(field, value) {
    jQuery('#' + field).val(value);
    jQuery('#search_form').submit();
}
