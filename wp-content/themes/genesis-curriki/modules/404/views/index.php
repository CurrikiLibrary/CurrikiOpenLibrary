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
<?php
$lms_list = array("canvas","moodle","blackboard","sakai","desire2learn");
?>    
<div class="tp-admin-wrapper">     
    <h2><a name="edit">No Action Found!</a></h2>                 
</div>

