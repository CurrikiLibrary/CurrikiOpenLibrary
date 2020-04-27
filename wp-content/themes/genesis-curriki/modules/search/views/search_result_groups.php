<?php global $search; ?>
<?php if (isset($search->current_user->caps['administrator'])) { ?>
<form action="admin.php?page=moderates" method="POST" class="group-bulk-moderate-form">
    <div class="admin-bulk-actions">
        <div class="groupids"></div>
        <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <input type="hidden" name="action" value="bulkupdate" />
                <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                <select name="bulkaction" id="bulk-action-selector-top">
                    <option value="-1">Bulk Actions</option>
                    <option value="Spam" class="hide-if-no-js">Spam</option>
                    <option value="NotSpam">Not Spam</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>
            <br class="clear">
        </div>
    </div>
</form>
<?php } ?>
<div class="groups grid_12" style="padding: 0px;margin:0px;float: none;">
    
    <?php foreach ($search->response as $row) {
        $title = '';
        $style = '';
        if (isset($search->current_user->caps['administrator'])) {
            $style = 'background:#FFF;';
            $title = 'title="Approved Group"';
            if($row['currentGroupSpam'] != $row['groupspam']){
                $style = 'border:5px dashed #7fc41a;'; //green border
                $title = 'title="Scheduled For Removal"';
            } else if($row['groupspam'] == 'T') {
                $style = 'background:#ffbfbf;'; //pink background
                $title = 'title="Spammed group"';
            }
        }
        ?>
        <div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group" style="<?php echo $style; ?>"  <?php echo $title; ?>>
            <?php if (isset($search->current_user->caps['administrator'])) { ?>
                <label class="container">&nbsp;
                    <input type="checkbox" class="groupids_checkboxes" value="<?php echo $row['id']; ?>">
                    <span class="checkmark"></span>
                </label>
            <?php } ?>
            <div class="card-header">
                <div><a href="<?php echo get_bloginfo('url') . '/' . $row['url']; ?>/"><img width="100" height="100" title="<?php echo $row['title']; ?>" alt="Group logo of <?php echo $row['title']; ?>" class="circle aligncenter group-2344-avatar avatar-100 photo" src="<?php echo $row['image']; ?>"></a></div>
                <span class="group-name name"><a href="<?php echo get_bloginfo('url') . '/' . $row['url']; ?>/"><?php echo $row['title']; ?></a></span>
                <br>
            </div>
            <div class="card-stats">
                <span class="stat"><span class="fa fa-users"></span><?php echo $row['groups_users_count']; ?></span>
                <?php if ($row['forum_id']) { ?>
                    <span class="stat"><span class="fa fa-comments"></span><?php echo $row['groups_comments_count']; ?></span>
                <?php } ?>
                <span class="stat"><span class="fa fa-book"></span><?php echo $row['groups_resources_count']; ?></span>
            </div>
            <div class="card-description"><p><?php echo $row['description']; ?></p></div>

            <div class="card-button action">				

                &nbsp;
            </div>
        </div>
    <?php } ?>
</div>
<?php if (isset($search->current_user->caps['administrator'])) { ?>
<div id="bulk-group-spam-dialog" title="Spam Groups?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to spam?</p>
  <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
      <div class="form-buttonset">
        <form id="resource-confirmation" class="bulk-moderate-form-dialog">
            <div class="groupids"></div>
            <input type="hidden" name="action" value="group_bulkaction" />
            <input type="hidden" name="bulkaction" value="Spam" />
            <input type="submit" name="submit"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" value="Yes" />
        </form>
        <div class="msg"></div>
      </div>
  </div>
</div>
<div id="bulk-group-not-spam-dialog" title="Remove Spam from Groups?">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Are you sure you want to remove spam?</p>
  <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
      <div class="form-buttonset">
        <form id="resource-confirmation" class="bulk-moderate-form-dialog">
            <div class="groupids"></div>
            <input type="hidden" name="action" value="group_bulkaction" />
            <input type="hidden" name="bulkaction" value="NotSpam" />
            <input type="submit" name="submit"  class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" value="Yes" />
        </form>
        <div class="msg"></div>
      </div>
  </div>
</div>
<?php } ?>