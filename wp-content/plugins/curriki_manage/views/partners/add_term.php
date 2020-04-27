<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" integrity="sha256-xI/qyl9vpwWFOXz7+x/9WkG5j/SVnSw21viy8fWwbeE=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div class="navigation">
    <ul class="nav nav-pills">
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners">Partners</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_partner">Add New Partner</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=terms">Terms</a></li>
        <li class="active"><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_term">Add New Term</a></li>
    </ul>
    <div class="clear"></div>
</div>
<div class="wrap">
    <h1 class="wp-heading-inline">Add New Term</h1>
</div>

<?php
foreach($data['validation_errors'] as $message){
?>
    <div class="notice notice-error is-dismissible">
        <p><?php echo $message; ?></p>
    </div>
<?php
}
foreach($data['success_message'] as $message){
?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo $message; ?></p>
    </div>
<?php
}
?>

<div class="form-wrap">
    <form id="addparnter" method="POST" action="admin.php?page=curriki_partners&action=add_term" class="validate">
        <input type="hidden" name="action" value="add_term">
        
        
        <div class="form-field form-required term-name-wrap">
            <label for="term">Term</label>
            <input name="term" id="term" type="text" value="<?php echo $_REQUEST['term']; ?>" size="40" aria-required="true" placeholder="Enter Term">
        </div>
        <div class="form-field term-slug-wrap">
            <label for="active">Active</label>
            <select name="active" id="active">
                <option value="T">True</option>
                <option value="F">False</option>
            </select>
        </div>
        <div class="form-field term-description-wrap">
            <label for="termstartdate">Term Start Date</label>
            <input type="text" name="termstartdate" id="termstartdate" value="<?php echo $_REQUEST['termstartdate']; ?>" size="40" aria-required="true" readonly autocomplete="off" style="background-color: #fff;cursor:pointer;" placeholder="Term Start Date" />
        </div>
        <div class="form-field term-description-wrap">
            <label for="termenddate">Term End Date</label>
            <input type="text" name="termenddate" id="termenddate" value="<?php echo $_REQUEST['termenddate']; ?>" size="40" aria-required="true" readonly autocomplete="off" style="background-color: #fff;cursor:pointer;" placeholder="Term End Date" />
        </div>
        <div class="form-field term-description-wrap">
            <label for="partnerid_field">Partner</label>
<!--            <input type="text" name="partnerid_field" id="partnerid_field" value="<?php echo $_REQUEST['partnerid_field']; ?>" size="40" aria-required="true" autocomplete="off" placeholder="Enter Partner Id" />
            <ul class="autofill" id="autofill">
                
            </ul>-->
            <select name="partnerid" id="partnerid">
                <option>Please select...</option>
                <?php foreach($partners as $partner): ?>
                <option value="<?php echo $partner->id; ?>"><?php echo $partner->text; ?></option>
                <?php endforeach; ?>
            </select>
            <!--<input type="hidden" name="partnerid" id="partnerid" value="<?php echo $_REQUEST['partnerid']; ?>" />-->
        </div>
        <input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url('admin-ajax.php'); ?>" />
        <input type="hidden" name="security" id="security" value="<?php echo wp_create_nonce("terms"); ?>" />


        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Term"></p>
    </form>
</div>

<script>
jQuery(document).ready(function(){
    jQuery('#termstartdate, #termenddate').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOn: 'focus',
        showButtonPanel: true,
        closeText: 'Clear', // Text to show for "close" button
        onClose: function () {
            var event = arguments.callee.caller.caller.arguments[0];
            // If "Clear" gets clicked, then really clear it
            if (jQuery(event.delegateTarget).hasClass('ui-datepicker-close')) {
                jQuery(this).val('');
            }
        }
    });
    jQuery.datepicker._gotoToday = function (id) {
        var target = jQuery(id);
        var inst = this._getInst(target[0]);
        if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
            inst.selectedDay = inst.currentDay;
            inst.drawMonth = inst.selectedMonth = inst.currentMonth;
            inst.drawYear = inst.selectedYear = inst.currentYear;
        }
        else {
            var date = new Date();
            inst.selectedDay = date.getDate();
            inst.drawMonth = inst.selectedMonth = date.getMonth();
            inst.drawYear = inst.selectedYear = date.getFullYear();
            // the below two lines are new
            this._setDateDatepicker(target, date);
            this._selectDate(id, this._getDateDatepicker(target));
        }
        this._notifyChange(inst);
        this._adjustDate(target);
    }
    var xhr;
    jQuery('#partnerid_field').keyup(function(){
        delay(function(){
            if(jQuery('#partnerid_field').val() == ''){
                jQuery('#autofill').empty();
            }
            if(jQuery('#partnerid_field').val() != ''){
                xhr = jQuery.ajax({
                    url: jQuery('#ajaxurl').val(),
                    dataType: "json",
                    data:{
                        partnerid_field: jQuery('#partnerid_field').val(),
                        action: 'partner_partnerid',
                        security: jQuery('#security').val(),
                    },
                    success: function(result){
                        $html = '';
                        for(var i = 0; i < result.length; i++){
                            $html += '<li onclick="enterContributorid(\''+result[i].id+'\', \''+result[i].text+'\')">';
                            $html += result[i].text;
                            $html += '</li>';
                        }
                        jQuery('#autofill').empty();
                        jQuery('#autofill').append($html);

                    }
                });
            }
        }, 500 );
        if(xhr){
            xhr.abort();
        }
        
    });
});

function enterContributorid(partnerid, partnertext){
    jQuery('#partnerid').val(partnerid);
    jQuery('#partnerid_field').val(partnertext);
    jQuery('#autofill').empty();
}
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
</script>