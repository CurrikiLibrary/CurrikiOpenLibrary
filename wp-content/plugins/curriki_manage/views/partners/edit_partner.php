<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" integrity="sha256-xI/qyl9vpwWFOXz7+x/9WkG5j/SVnSw21viy8fWwbeE=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div class="navigation">
    <ul class="nav nav-pills">
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners">Partners</a></li>
        <li class="active"><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_partner">Add New Partner</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=terms">Terms</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_term">Add New Term</a></li>
    </ul>
    <div class="clear"></div>
</div>
<div class="wrap">
    <h1 class="wp-heading-inline">Edit Partner</h1>
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
    <form id="addparnter" method="POST" action="admin.php?page=curriki_partners&action=edit_partner&edit=<?php echo $_REQUEST['edit']; ?>" class="validate">
        <input type="hidden" name="action" value="edit_partner">
        <input type="hidden" name="edit" value="<?php echo $_REQUEST['edit']; ?>" />
        
        <div class="form-field term-description-wrap" id="contributor_wrap">
            <label for="contributorid_field">Contributor</label>
            <input type="text" name="contributorid_field" id="contributorid_field" value="<?php echo $partner->contributorid_field; ?>" size="40" aria-required="true" autocomplete="off" />
<!--            <ul class="autofill" id="autofill">
                
            </ul>-->
            <!--<input type="hidden" name="contributorid" id="contributorid" value="<?php echo $partner->contributorid; ?>" />-->
        </div>
        <div class="form-field form-required term-name-wrap">
            <label for="name">Name</label>
            <input name="name" id="name" type="text" value="<?php echo $partner->name; ?>" size="40" aria-required="true">
        </div>
        <div class="form-field term-slug-wrap">
            <label for="active">Active</label>
            <select name="active" id="active">
                <option value="T" <?php echo ($partner->active == 'T')? 'selected="selected"':''; ?>>True</option>
                <option value="F" <?php echo ($partner->active == 'F')? 'selected="selected"':''; ?>>False</option>
            </select>
        </div>
        <div class="form-field term-description-wrap">
            <label for="termsnumber">Number of terms</label>
            <input type="text" name="termsnumber" id="termsnumber" size="40" aria-required="true" value="<?php echo $partner->termsnumber; ?>" />
        </div>
        <div class="form-field term-description-wrap">
            <label for="searchstartdate">Search Start Date</label>
            <input type="text" name="searchstartdate" id="searchstartdate" value="<?php echo $partner->searchstartdate; ?>" size="40" aria-required="true" readonly autocomplete="off" style="background-color: #fff;cursor:pointer;" />
        </div>
        <div class="form-field term-description-wrap">
            <label for="searchenddate">Search End Date</label>
            <input type="text" name="searchenddate" id="searchenddate" value="<?php echo $partner->searchenddate; ?>" size="40" aria-required="true" readonly autocomplete="off" style="background-color: #fff;cursor:pointer;" />
        </div>
        <input type="hidden" name="ajaxurl" id="ajaxurl" value="<?php echo admin_url('admin-ajax.php'); ?>" />
        <input type="hidden" name="security" id="security" value="<?php echo wp_create_nonce("partners"); ?>" />


        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Partner"></p>
    </form>
</div>
<div class="terms">
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <td>Term</td>
                <td>Active</td>
                <td>Term Start Date</td>
                <td>Term End Date</td>
                <td>Total Searches</td>
                <td>Partner</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($terms as $term): ?>
                <tr>
                    <td><?php echo $term->term; ?></td>
                    <td><?php echo $term->active; ?></td>
                    <td><?php echo $term->termstartdate; ?></td>
                    <td><?php echo $term->termenddate; ?></td>
                    <td><?php echo $term->searchcount; ?></td>
                    <td><?php echo $term->partnername; ?> (<?php echo $term->partnerid; ?>)</td>
                    <td>
                        <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=edit_term&partnerid=<?php echo $term->partnerid; ?>&term=<?php echo $term->term; ?>&active=<?php echo $term->active; ?>" class="button button-small">Edit</a>
                        <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=delete_term&partnerid=<?php echo $term->partnerid; ?>&term=<?php echo $term->term; ?>&active=<?php echo $term->active; ?>" class="button button-small" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_term&partnerid=<?php echo $_REQUEST['edit'] ?>" class="button button-primary">Add Term</a>
</div>

<script>
jQuery(document).ready(function(){
    jQuery('#searchstartdate, #searchenddate').datepicker({
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
    
    jQuery(document).on("click", function(e){
        if(jQuery(e.target).is('#contributor_wrap, #contributor_wrap *'))return;
        jQuery('#autofill').empty();
    });
    /*
    jQuery('#contributorid_field').keyup(function(){
        delay(function(){
            if(xhr){
                xhr.abort();
            }
            if(jQuery('#contributorid_field').val() == ''){
                jQuery('#autofill').empty();
            }
            if(jQuery('#contributorid_field').val() != ''){
                xhr = jQuery.ajax({
                    url: jQuery('#ajaxurl').val(),
                    dataType: "json",
                    data:{
                        contributorid_field: jQuery('#contributorid_field').val(),
                        action: 'partner_contributorid',
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
        }, 1000 );
        
        
    });
    */
});

function enterContributorid(contributorid, contributortext){
//    jQuery('#contributorid').val(contributorid);
    //jQuery('#contributorid_field').val(contributortext);
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