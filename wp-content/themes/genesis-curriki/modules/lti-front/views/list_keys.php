<?php 
/*
$lms_list = array("canvas","moodle","blackboard","sakai","desire2learn");
if( !isset($_GET["lms"]) || isset($_GET["lms"]) && !in_array($_GET["lms"], $lms_list) )
{    
    wp_redirect(site_url()."/manage-lti/?action=select_lms&t=".time());
    wp_die();
}
* 
*/

$update_consumer = new ToolConsumer(); 
$user_credentials = $update_consumer->getAllByUserIdAndLMS($_GET["lms"]);

$lms_list = array("canvas","moodle","blackboard","sakai","desire2learn");
$lms_list_name = array("canvas"=>"Canvas","moodle"=>"Moodle","blackboard"=>"Blackboard","sakai"=>"Sakai","desire2learn"=>"D2L");

$mode_label = "";
if ($user_credentials) {
    $mode_label = 'Update';
} else {      
    $mode_label = 'Add';
}
?>
 
<!-- Page Heading-->
<div class="resources grid_12">    
    <h2 style="color:#7DA941;text-align:center;margin-top: 8px;">LTI Credentials For <?php echo $lms_list_name[$_GET["lms"]]; ?> LMS</h2> <!-- Page Title -->
    
</div>
<div style="clear:both"></div>
<!-- /Page Heading-->

<div class="tp-admin-wrapper lms-popup-additional" style="padding-left:2px !important">    
    
    <div class="keys-link" style="padding-left: 7px">
            <a class="lti-go-to-save" href="<?php echo site_url() ?>/manage-lti/?action=lti_form&mode=Add&lms=<?php echo $_GET["lms"]; ?>" style="display: flex">
                <span class="fa fa-plus" style="padding-top: 4px"></span>&nbsp&nbsp <span class="name lms-key-text"><strong>Add New</strong></span></a></div>
    
        
        
        <div class="lti-lms-container rounded-borders-full border-grey-lti">
            <div class="scrollbar-wrapper-lti">
                <ul class="discussion">
                    <li class="group">
                        <div class="keys-link">  <span class="namex lms-key-text lms-key-label"><strong>Key</strong></span> <span class="namex lms-key-text lms-secret-label"><strong>Secret</strong></span></div>
                    </li>

                    <?php
                        if( !is_array($user_credentials) || (is_array($user_credentials) && count($user_credentials)==0) )
                        {
                    ?>
                            <li class="group">                        
                                <div class="keys-link">  <span class="namex lms-key-text lms-key-label"><strong>---</strong></span> <span class="namex lms-key-text lms-secret-label"><strong>---</strong></span></div>
                            </li>
                    <?php
                        }
                    ?>

                    <?php
                    foreach ($user_credentials as $user_credentials) {
                        $key = $user_credentials->consumer_key256;
                        $secret = $user_credentials->secret;
                    ?>                
                        <li class="group">
                            <div class="keys-link"><a class="lti-go-to-save" href="<?php echo site_url() ?>/manage-lti/?action=lti_form&mode=Update&lms=<?php echo $_GET["lms"]; ?>&key=<?php echo $key; ?>"><!-- <span class="fa fa-edit"></span> --></a> <span class="name lms-key-text lms-key-label"><?php echo $key; ?></span>  <span class="name lms-key-text lms-secret-label"><?php echo $secret; ?></span> </div>
                        </li>
                    <?php
                    }
                    ?>                                

                </ul>
            </div>
        </div>        
</div>

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
    

