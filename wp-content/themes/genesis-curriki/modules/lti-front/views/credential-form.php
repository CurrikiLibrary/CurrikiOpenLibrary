<?php 
$update_consumer = new ToolConsumer(); 
$user_credentials = $update_consumer->getByUserId();
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
        
    
    
    <?php
    // Check for any messages to be displayed
  if (isset($_SESSION['error_message'])) {
?>
    <p style="font-weight: bold; color: #f00;">ERROR: <?php echo $_SESSION['error_message']; ?></p>
<?php
    unset($_SESSION['error_message']);
  }

  if (isset($_SESSION['lti_res_message'])) {
?>
    <p style="font-weight: bold; color: #00f;"><?php echo $_SESSION['lti_res_message']; ?></p>
<?php    
    unset($_SESSION['lti_res_message']);
  }

    // Display form for adding/editing a tool consumer
    $update = '';
    $lti2 = '';
    $secret = '';
    $mode = null;
    
    if ($user_credentials) {
      $mode = 'Update';
    } else {      
      $mode = 'Add';
      $update = ' disabled="disabled"';      
    }
    $name = htmlentities($update_consumer->name);
    
    if ($mode === 'Add' && strlen($key)===0) {        
        $key = TPMiscHelper::getRandomString(20);
        $secret = TPMiscHelper::getRandomString(32);
    }else if($mode === 'Update'){
        $key = $user_credentials->consumer_key256;
        $secret = $user_credentials->secret;
    }    
    
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
     
    <h2><a name="edit"><?php echo $mode; ?> Credential</a></h2>

    <form action="<?php echo site_url() ?>/manage-lti/?t=<?php echo time(); ?>" method="post">
    <div class="box">
        <span class="label">Name:<span class="required" title="required"></span></span>&nbsp;<?php echo strlen($user_credentials->name) > 0 ? $user_credentials->name : "----" ; ?><br />
      <span class="label">Key:<span class="required" title="required">*</span></span>&nbsp;<input name="key" type="text" size="75" value="<?php echo $key; ?>" /><br />
      <span class="label">Secret:<span class="required" title="required">*</span></span>&nbsp;<input name="secret" type="text" size="75" value="<?php echo $secret; ?>"<?php echo $lti2; ?> /><br />      
      <br />            
      &nbsp;
      <input type="submit" name="savelti" value="Save" />
    <?php
        if (isset($update_consumer->created)) {
    ?>
        &nbsp;<input type="reset" value="Cancel" onclick="location.href='./?'+(new Date().valueOf());" />    
    <?php
    }
    ?>
    </div>
        
    </form>
    <p>
        Note: Canvas users use Config Url - https://curriki.org/lti/config.xml along with credential given above
    <p>
</div>

