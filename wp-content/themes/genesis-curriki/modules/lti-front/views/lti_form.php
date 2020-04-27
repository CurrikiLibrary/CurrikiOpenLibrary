<?php 
if( !(isset($_GET["mode"]) && ( $_GET["mode"]==="Update" || $_GET["mode"]==="Add" )) )
{
    echo "Invalid Request.";
    die();
}

$lms_list = array("canvas","moodle","blackboard","sakai","desire2learn");
if( !isset($_GET["lms"]) || isset($_GET["lms"]) && !in_array($_GET["lms"], $lms_list) )
{    
    wp_redirect(site_url()."/manage-lti/?action=select_lms&t=".time());
    wp_die();
}

$update_consumer = new ToolConsumer(); 
$user_credentials = null;
if( isset($_GET["key"]) &&  isset($_GET["mode"]) && $_GET["mode"] === "Update")
{    
    $update_consumer->setKey( TPMiscHelper::cleanSpecialCharacters( trim($_GET["key"]) ) );
    $user_credentials = $update_consumer->getByKey();
}

$q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
$me = $wpdb->get_row($q_me);

$lms_list_name = array("canvas"=>"Canvas","moodle"=>"Moodle","blackboard"=>"Blackboard","sakai"=>"Sakai","desire2learn"=>"D2L");

$mode_label = "";
if ($user_credentials) {
    $mode_label = 'Update';
} else {      
    $mode_label = 'Add';
}
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
    
    jQuery(document).ready(function () {        
        jQuery("#backlti").click(function(e){
            e.preventDefault();
            window.location = "<?php echo site_url() ?>/manage-lti/?action=select_lms&t=<?php echo time(); ?>";
        });
    });    
</script>
    
<!-- Page Heading-->
<div class="resources grid_12">    
    <h2 style="color:#7DA941;text-align:center;margin-top: 8px;"><?php echo $mode_label; ?> LTI Credential For <?php echo $lms_list_name[$_GET["lms"]]; ?> LMS</h2> <!-- Page Title -->
    <?php
    // TO SHOW THE PAGE CONTENTS
    while (have_posts()) : the_post();
        ?> <!--Because the_content() works only inside a WP Loop -->
        <p class="desc"><?php the_content(); ?></p> <!-- Page Content -->
        <?php
    endwhile; //resetting the page loop    
    wp_reset_query(); //resetting the page query
    ?>
</div>
<div style="clear:both"></div>
<!-- /Page Heading-->

<div class="tp-admin-wrapper lms-popup-additional">
        
    
    
    <?php
    // Check for any messages to be displayed
  if (isset($_SESSION['error_message'])) {
?>
    <p style="font-weight: bold; color: #f00;">ERROR: <?php echo $_SESSION['error_message']; ?></p>
<?php
    unset($_SESSION['error_message']);
  }

?>
    <p id="lti_res_message" style="font-weight: bold; color: #00f;display: none;"><?php echo $_SESSION['lti_res_message']; ?></p>
<?php    


    // Display form for adding/editing a tool consumer
    $update = '';
    $lti2 = '';
    $secret = '';
    $mode = null;
    if( isset($_GET["mode"]) )
    {
        $mode = $_GET["mode"];
    } else {
        echo "invalid request.";die();
    }
    
    if ($user_credentials) {
      $mode = 'Update';
    } else {      
      $mode = 'Add';
      $update = ' disabled="disabled"';      
    }
    $name = htmlentities($update_consumer->name);
    $key="";
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
    
    <form id="lti-form-save" action="<?php echo site_url() ?>/manage-lti/?action=lti_form&lms=<?php echo $_GET["lms"] ?>&t=<?php echo time(); ?>" method="post">
    <div class="box">
      <input type="hidden" name="lms" value="<?php echo $_GET["lms"]; ?>" />      
      <span class="label">Name:<span class="required" title="required"></span></span>&nbsp;<?php echo is_object($user_credentials) && strlen($user_credentials->name) > 0 ? $user_credentials->name : ( is_object($me) ? "{$me->firstname} {$me->lastname}" : '----' ) ; ?><br />
      <span class="label">Key:<span class="required lti-form-text-field" title="required">*</span></span>&nbsp;<input name="key" type="text" size="75" value="<?php echo $key; ?>" /><br />
      <span class="label">Secret:<span class="required lti-form-text-field" title="required">*</span></span>&nbsp;<input name="secret" type="text" size="75" value="<?php echo $secret; ?>"<?php echo $lti2; ?> /><br />      
      <br />            
      &nbsp;
      <input type="hidden" name="savelti_action" value="Save" />
      <input type="hidden" name="mode_val" value="<?php echo $mode; ?>" />
      <input type="hidden" name="cpk" value="<?php echo isset($user_credentials) ? $user_credentials->consumer_pk : 0; ?>" />
      <?php $save_create_label = ($mode === "Add" ? "Create Now!":"Save"); ?>
      <input type="submit" name="savelti" id="savelti" value="<?php echo $save_create_label; ?>" />

    <?php
        if (isset($update_consumer->created)) {
    ?>

    <?php
    }
    ?>
    </div>
        
    </form>
    <?php if($mode === "Update"){ ?>
        <p>
            <strong>Warning:</strong> Updating the credentials might break the search tool if LMS in not configured for updated credentials.
        <p>
    <?php } ?>

    <?php if($_GET["lms"] === $lms_list[0]) { ?>
        <p>
            <strong>Note:</strong> Canvas configuration Url - https://curriki.org/lti/config.xml
        </p>
    <?php } else if($_GET["lms"] === $lms_list[1]) { ?>
        <p>
            <strong>Note:</strong> Moodle configuration Url - https://curriki.org/lti/config.xml
        </p>
    <?php } else if($_GET["lms"] === $lms_list[2]) { ?>
        <p>
            <strong>Note:</strong> Blackboard configuration Url - https://curriki.org/lti/config.xml
        </p>
    <?php } else if($_GET["lms"] === $lms_list[3]) { ?>
        <p>
            <strong>Note:</strong> Sakai configuration Url - https://curriki.org/lti/config.xml
        </p>
    <?php } else if($_GET["lms"] === $lms_list[4]) { ?>
        <p>
            <strong>Note:</strong> D2L configuration Url - https://curriki.org/lti/config.xml
        </p>
    <?php } ?>

     
</div>

