jQuery(document).ready(function($){
    
    var single_resource_reject_dialog = jQuery( "#single-resource-reject-dialog" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
      width: 500,
      modal: true,
    });
    var bulk_resource_reject_dialog = jQuery( "#bulk-resource-reject-dialog" ).dialog({
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
    jQuery('.single-moderate-form').submit(function(e){
        if(jQuery(this).find('select').val() == 'Reject'){
            var resourceid = jQuery(this).find('[name=resourceid]').val()
            jQuery('.single-moderate-form-dialog [name=resourceid]').val(resourceid);
            single_resource_reject_dialog.dialog( "open" );
            return false;
        }
        
    });
    jQuery('.bulk-moderate-form').submit(function(e){
        if(jQuery(this).find('select').val() == 'Reject'){
            bulk_resource_reject_dialog.dialog( "open" );
            return false;
        }
        
    });
});