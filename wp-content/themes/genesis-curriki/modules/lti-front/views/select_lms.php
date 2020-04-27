<?php 
require_once realpath(__DIR__ . '/../..').'/common.php';

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
<?php
$lms_list = array("canvas","moodle","blackboard","sakai","desire2learn");
?>    
<div class="tp-admin-wrapper">     
    <h2><a name="edit">Select LMS</a></h2>             
    <a class="lms-select-link canvas-link" href="<?php echo site_url() ?>/manage-lti/?action=list_keys&lms=<?php echo $lms_list[0]; ?>&t=<?php echo time(); ?>"><img width="130" srcset="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161922/canvas-photo-300x300.jpg 300w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161922/canvas-photo-150x150.jpg 150w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161922/canvas-photo-768x768.jpg 768w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161922/canvas-photo.jpg 900w" alt="canvas-photo" src="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161922/canvas-photo-300x300.jpg" /></a>
    <a class="lms-select-link moodle-link" href="<?php echo site_url() ?>/manage-lti/?action=list_keys&lms=<?php echo $lms_list[1]; ?>&t=<?php echo time(); ?>">
        <div class="crop-icn-moodle">
            <img width="200" srcset="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161954/moodle-logo-300x77.png 300w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161954/moodle-logo-768x196.png 768w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161954/moodle-logo-1024x261.png 1024w" alt="moodle-logo" src="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10161954/moodle-logo-300x77.png" />
        </div>
    </a>
    <a class="lms-select-link blackboard-link" href="<?php echo site_url() ?>/manage-lti/?action=list_keys&lms=<?php echo $lms_list[2]; ?>&t=<?php echo time(); ?>">
        <div class="crop-icn-blackboard">
            <img height="195" width="195" sizes="(max-width: 195px) 100vw, 195px" srcset="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10162032/blackboard-logo.jpg 195w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/10162032/blackboard-logo-150x150.jpg 150w" alt="blackboard-logo" src="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/10162032/blackboard-logo.jpg" />
        </div>        
    </a>
    <a class="lms-select-link d2l-link" href="<?php echo site_url() ?>/manage-lti/?action=list_keys&lms=<?php echo $lms_list[4]; ?>&t=<?php echo time(); ?>">
        <div class="crop-icn-d2l">
            <img width="140" src="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161659/d2l-300x158.jpg" class="attachment-medium size-medium" alt="d2l" srcset="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161659/d2l-300x158.jpg 300w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161659/d2l-768x403.jpg 768w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161659/d2l-1024x538.jpg 1024w, <?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161659/d2l.jpg 1200w" sizes="(max-width: 300px) 100vw, 300px">
        </div>
    </a>
    <a class="lms-select-link sakai-link" href="<?php echo site_url() ?>/manage-lti/?action=list_keys&lms=<?php echo $lms_list[3]; ?>&t=<?php echo time(); ?>">
        <div class="crop-icn-sakai">
            <img width="130" src="<?php echo CDN_UPLOAD_DIR; ?>/2017/03/22161705/sakai-300x182.png" class="attachment-medium size-medium" alt="sakai">
        </div>        
    </a>
</div>

