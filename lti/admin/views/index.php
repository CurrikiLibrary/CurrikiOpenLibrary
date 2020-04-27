<?php
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php 
echo $head_html; 
echo $body_start_html;
?>    
    <script type="text/javascript">
        //<![CDATA[
        var numSelected = 0;
        function toggleSelect(el) {
          if (el.checked) {
            numSelected++;
          } else {
            numSelected--;
          }
          document.getElementById('delsel').disabled = (numSelected <= 0);
        }
        //]]>
    </script>
    
<div class="tp-admin-wrapper">
        
    <h2><?php echo $title ?></h2>
    
    <?php
    // Check for any messages to be displayed
  if (isset($_SESSION['error_message'])) {
?>
    <p style="font-weight: bold; color: #f00;">ERROR: <?php echo $_SESSION['error_message']; ?></p>
<?php
    unset($_SESSION['error_message']);
  }

  if (isset($_SESSION['message'])) {
?>
    <p style="font-weight: bold; color: #00f;"><?php echo $_SESSION['message']; ?></p>
<?php    
    unset($_SESSION['message']);
  }

  if ($ok) {

    if (count($consumers) <= 0) {
    ?>
    <p>No consumers have been added yet.</p>
    <?php
     } else {
     
    ?>
        <form action="./?do=delete" method="post" onsubmit="return confirm('Delete selected consumers; are you sure?');">
            <table class="items" border="1" cellpadding="3">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>Name</th>
                <th>Key</th>
                <th>Version</th>
                <th>Available?</th>
                <th>Protected?</th>
                <th>Last access</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody>
            <?php
                foreach ($consumers as $consumer) {
                    $trid = urlencode($consumer->getRecordId());
                    if ($consumer->getRecordId() === $id) {
                      $update_consumer = $consumer;
                    }
                    if (!$consumer->getIsAvailable()) {
                      $available = 'cross';
                      $available_alt = 'Not available';
                      $trclass = 'notvisible';
                    } else {
                      $available = 'tick';
                      $available_alt = 'Available';
                      $trclass = '';
                    }
                    if ($consumer->protected) {
                      $protected = 'tick';
                      $protected_alt = 'Protected';
                    } else {
                      $protected = 'cross';
                      $protected_alt = 'Not protected';
                    }
                    if (is_null($consumer->lastAccess)) {
                      $last = 'None';
                    } else {
                      $last = date('j-M-Y', $consumer->lastAccess);
                    }
             ?>
                    <tr class="<?php echo $trclass; ?>">
                        <td><input type="checkbox" name="ids[]" value="<?php echo $trid; ?>" onclick="toggleSelect(this);" /></td>
                        <td><?php echo $consumer->name; ?></td>
                        <td><?php echo $consumer->getKey() ?></td>
                        <td><span title="<?php echo $consumer->consumerGuid; ?>"><?php echo $consumer->consumerVersion; ?></span></td>
                        <td class="aligncentre"><img src="../images/<?php echo $available; ?>.gif" alt="<?php echo $available_alt; ?>" title="<?php echo $available_alt; ?>" /></td>
                        <td class="aligncentre"><img src="../images/<?php echo $protected; ?>.gif" alt="<?php echo $protected_alt; ?>" title="<?php echo $protected_alt; ?>" /></td>
                        <td><?php echo $last; ?></td>
                        <td class="iconcolumn aligncentre">
                            <a href="./?id=<?php echo $trid."&t=".time(); ?>#edit"><img src="../images/edit.png" title="Edit consumer" alt="Edit consumer" /></a>&nbsp;<a href="./?do=delete&amp;id=<?php echo $trid; ?>" onclick="return confirm('Delete consumer; are you sure?');"><img src="../images/delete.png" title="Delete consumer" alt="Delete consumer" /></a>
                        </td>
                    </tr>
            <?php    
                }
            ?>
            </tbody>
            </table>
                <p>
                    <input type="submit" value="Delete selected tool consumers" id="delsel" disabled="disabled" />
                </p>
            </form>

    <?php
     }
     
    // Display form for adding/editing a tool consumer
    $update = '';
    $lti2 = '';
    if (!isset($update_consumer->created)) {
      $mode = 'Add new';
    } else {
      $mode = 'Update';
      $update = ' disabled="disabled"';
      if ($update_consumer->ltiVersion === ToolProvider\ToolProvider::LTI_VERSION2) {
        $lti2 = ' disabled="disabled"';
      }
    }
    $name = htmlentities($update_consumer->name);
    $key = htmlentities($update_consumer->getKey());
    if ($mode === 'Add new' && strlen($key)===0) {        
        $key = TPMiscHelper::getRandomString(20);
    }    
    
    $secret = htmlentities($update_consumer->secret);
    if ($update_consumer->enabled) {
      $enabled = ' checked="checked"';
    } else {
      $enabled = '';
    }
    $enable_from = '';
    if (!is_null($update_consumer->enableFrom)) {
      $enable_from = date('j-M-Y H:i', $update_consumer->enableFrom);
    }
    $enable_until = '';
    if (!is_null($update_consumer->enableUntil)) {
      $enable_until = date('j-M-Y H:i', $update_consumer->enableUntil);
    }
    if ($update_consumer->protected) {
      $protected = ' checked="checked"';
    } else {
      $protected = '';
    }     
    ?>   
     
    <h2><a name="edit"><?php echo $mode; ?> consumer</a></h2>

    <form action="./?t=<?php echo time(); ?>" method="post">
    <div class="box">
      <span class="label">Name:<span class="required" title="required">*</span></span>&nbsp;<input name="name" type="text" size="50" maxlength="50" value="<?php echo $name; ?>" /><br />
      <span class="label">Key:<span class="required" title="required">*</span></span>&nbsp;<input name="key" type="text" size="75" maxlength="50" value="<?php echo $key; ?>"<?php echo $update; ?> /><br />
      <span class="label">Secret:<span class="required" title="required">*</span></span>&nbsp;<input name="secret" type="text" size="75" maxlength="200" value="<?php echo $secret; ?>"<?php echo $lti2; ?> /><br />
      <span class="label">Enabled?</span>&nbsp;<input name="enabled" type="checkbox" value="1"<?php echo $enabled; ?> /><br />
      <span class="label">Enable from:</span>&nbsp;<input name="enable_from" type="text" size="50" maxlength="200" value="<?php echo $enable_from; ?>" /><br />
      <span class="label">Enable until:</span>&nbsp;<input name="enable_until" type="text" size="50" maxlength="200" value="<?php echo $enable_until; ?>" /><br />
      <span class="label">Protected?</span>&nbsp;<input name="protected" type="checkbox" value="1"<?php echo $protected; ?> /><br />
      <br />
      <input type="hidden" name="do" value="add" />
      <input type="hidden" name="id" value="<?php echo $id; ?>" />
      <span class="label"><span class="required" title="required">*</span>&nbsp;=&nbsp;required field</span>&nbsp;<input type="submit" value="<?php echo $mode; ?> consumer" />

    <?php
        if (isset($update_consumer->created)) {
    ?>
        &nbsp;<input type="reset" value="Cancel" onclick="location.href='./?'+(new Date().valueOf());" />    
    <?php
    }
    ?>
    </div>
        <p class="clear">
            NB The launch URL for this instance is <?php echo $launchUrl; ?>
        </p>
    </form>
     
<?php  
} ?>
</div>
<?php
    echo $body_end_html;
?>    
</html>