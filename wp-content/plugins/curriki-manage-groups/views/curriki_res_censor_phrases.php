<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_REQUEST['location'])) {
  $locations = array(
      'quote' => 'Featured Quotes',
      'dashboarduser' => 'Dashboard Featured Members',
      'partner' => 'Featured Partners',
      'homepageresource' => 'Home Page Featured Resources',
      'homepagealigned' => 'Home Page Featured Aligned Resources',
      'homepagemember' => 'Home Page Featured Members',
      'dashboardresource' => 'Dashboard Featuerd Resources',
      'dashboardgroup' => 'Dashboard Featured Groups',
  );
  $msg = $locations[$_REQUEST['location']] . ' Saved Successfully';
  ?>
  <h2>Done......</h2>
  <div id="message" class="updated below-h2">
    <p>
      <?php echo $msg; ?>
    </p>
  </div>
  <p></p>
<?php } ?>

<?php 

global $wpdb; 

if(isset($_POST["save_censor_phrase"]) && $_POST["save_censor_phrase"] == "Save")
{        
    if( intval($_POST["phraseid"]) > 0 )
    {
        $wpdb->query("UPDATE censorphrases SET phrase = '{$_POST["phrase"]}' where phraseid = {$_POST["phraseid"]}");       
    }elseif(intval($_POST["phraseid"]) == 0)
    {
        $wpdb->query("INSERT INTO censorphrases (phrase) VALUES ('{$_POST["phrase"]}')");
    }
}

$censorphrase = null;
if(isset($_GET["action"]))
{
    switch ($_GET["action"])
    {
        case 'edit':
            $censorphrase = $wpdb->get_row("SELECT * FROM censorphrases WHERE phraseid={$_GET["phraseid"]}");            
            break;
        case 'delete':            
            $wpdb->query("DELETE FROM censorphrases WHERE phraseid={$_GET["phraseid"]}");            
            break;           
    }
}
?>
  
  
<!--  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
-->

<input type="hidden" name="base_url" id="base_url" value="<?php echo site_url() ?>" />
<div class="wrap">

<?php
    if(isset($censorphrase))
    {
?>    
        <h1>Edit '<?php echo $censorphrase->phrase ?>' Phrase</h1>
        <a href="<?php echo admin_url()."options-general.php?page=curriki-censor-phrases" ?>"><strong>ADD NEW PHRASE</strong></a>
<?php }else{?>
        <h1>Add New Censor Phrase</h1>
<?php }?>

<form name="add_censor_form" id="add_censor_form" action="" method="post">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="blogname">Censor Phrase</label>
                </th>
            <td>
             <td>
                 <input type="text" class="regular-text" id="phrase" name="phrase" value="<?php echo isset($censorphrase) ? $censorphrase->phrase : "";?>" />
                 <input type="hidden" id="phraseid" name="phraseid" value="<?php echo isset($censorphrase) ? $censorphrase->phraseid : 0;?>" />
             </td>
            </tr>
            <tr>
                <th scope="row">

                </th>
            <td>
             <td>
                 <input type="submit" value="Save" class="button" id="save-censor-phrase" name="save_censor_phrase" />
             </td>
            </tr>
        </tbody>
    </table>
</form>
</div>

<?php
@require_once 'phrase_list.php';
?>