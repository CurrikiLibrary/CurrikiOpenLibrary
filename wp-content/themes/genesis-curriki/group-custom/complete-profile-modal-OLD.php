<?php
/*
  Author: Waqar Muneer
 */

use Aws\Common\Aws;

global $wpdb;    

$current_language = "eng";
$current_language_slug = "";
if( defined('ICL_LANGUAGE_CODE') )
{
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
    $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
}
  
if( isset( $_POST["update_profile"] ) && $_POST["update_profile"] == 1 )
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    global $wpdb;
    $my_id = get_current_user_id();
        
    
        $user_table_fields = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],                        
            'city' => $_POST['city'],
            'country' => $_POST['country'],
            'indexrequired' => 'T',
            'indexrequireddate' => date('Y-m-d H:i:s'),            
         );
      
        $wpdb->update(
            'users', $user_table_fields , array('userid' => $my_id), array('%s', '%s', '%s', '%s'), array('%d')
        );
                
        if(isset($_POST["gender"]))
        {
            $profile = get_user_meta(get_current_user_id(),"profile",true);    
            $profile = isset($profile) ? json_decode($profile) : null;            
            
            if(isset($profile))
            {
                $profile->gender = $_POST["gender"];
                update_user_meta(get_current_user_id(), "profile", json_encode($profile));
            }else{
                $profile = new stdClass();
                $profile->gender = $_POST["gender"];
                add_user_meta(get_current_user_id(), "profile", json_encode($profile));
            }
        }
        
        if ($_FILES['my_photo']['tmp_name']) {
            
            
            $upload_folder = '/uploads/tmp/';
            $MaxSizeUpload = 5242880; //Bytes

            $sub_dir = dirname( strtok( $_SERVER['REQUEST_URI'] , '?') );                        
            if( $current_language != "eng" )
            {
                $sub_dir = dirname( strtok( $sub_dir , 'eng') );                
            }
            $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');                                    
            
            require_once($wp_contents . '/libs/aws_sdk/aws-autoloader.php');

            $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
            $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder
            //**********************
            //Allowed extensions
            //**********************

            $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Images
            $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Videos
            //$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
            //$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
            //$ext_misc = array('zip', 'rar', 'gzip'); //Archives
            //$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //allowed extensions

            $ds = DIRECTORY_SEPARATOR;
                        
            
            $aws = Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
            $s3_client = $aws->get('S3');           
            $bucket = DBI_AWS_S3_BUCKET;
            $ext = pathinfo($_FILES['my_photo']['name'], PATHINFO_EXTENSION);
            $name = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($_FILES['my_photo']['name'], PATHINFO_FILENAME))) . time() . rand();
            $tempFile = $_FILES['my_photo']['tmp_name'];
            $targetFile = $current_path . $name . '.' . $ext;                        
            move_uploaded_file($_FILES['my_photo']['tmp_name'], $targetFile);

            if (file_exists($targetFile)) {
                $pic = uniqid();
                $upload = $s3_client->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => $bucket,
                            'Key' => 'avatars/' . $pic . '.' . $ext,
                            'Body' => fopen($targetFile, 'r+')
                        ))->toArray();
                
                $wpdb->update(
                    'users', array(
                        'uniqueavatarfile' => $pic . '.' . $ext,
                    ), array('userid' => $my_id), array('%s'), array('%d')
                );
            }
        }     
}

wp_enqueue_style('modals-custom-style', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-style.css');
wp_enqueue_script('modals-custom-script', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-script.js', array('jquery'), false, true);

global $wpdb;
$q = "SELECT * FROM cur_options WHERE option_name='complete-profile-modal'";
$modal_options = $wpdb->get_row($q, OBJECT);

$m_options = json_decode($modal_options->option_value);

if( property_exists($m_options, "is_active") && $m_options->is_active === 1 )
{
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    */
    
    global $wpdb;
    $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
    $me = $wpdb->get_row($q_me);        
    $profile = get_user_meta(get_current_user_id(),"profile",true);    
    $profile = isset($profile) ? json_decode($profile) : null;                    
    
    //$days_interval = get_days_from_date($me);    
    $display_days_span = "";
    
    $last_display = get_user_meta(get_current_user_id(),"complete_profile_modal_display",true);
    
    
    if($last_display)
    {
        $display_days_span = get_days_from_date($last_display);        
        //echo "users = ".get_current_user_id() . "<br />";
        //echo "DaysSinceLastDisplay = ". $m_options->DaysSinceLastDisplay . "<br />";
        //echo "calculated day span = ". $display_days_span . "<br />";
        //echo " last display = ".$last_display . "<br />";
        
        if( (intval($display_days_span) >= intval($m_options->DaysSinceLastDisplay) ) && ( strlen($me->firstname)=== 0 ||  strlen($me->lastname)=== 0 || strlen($me->uniqueavatarfile) === 0 || strlen($me->city) === 0 || strlen($me->country) === 0 || $profile === null) )
        {        
            wp_enqueue_style('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/css/complete-profile-modal.css");
            wp_enqueue_script('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/js/complete-profile-modal.js" , array('jquery'), false, true);
            render_complete_profile_modal($m_options,$me,$profile);
        }
    }else{
        wp_enqueue_style('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/css/complete-profile-modal.css");
        wp_enqueue_script('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/js/complete-profile-modal.js" , array('jquery'), false, true);
        render_complete_profile_modal($m_options,$me,$profile);
    }
      
    /*
    if(  ( strlen($me->firstname)=== 0 ||  strlen($me->lastname)=== 0 || strlen($me->uniqueavatarfile) === 0 || strlen($me->city) === 0 || strlen($me->country) === 0 || $profile === null) )
    {        
        wp_enqueue_style('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/css/complete-profile-modal.css");
        wp_enqueue_script('complete-profile-modal', get_stylesheet_directory_uri()."/group-custom/js/complete-profile-modal.js" , array('jquery'), false, true);
        render_complete_profile_modal($m_options,$me,$profile);
    }*/    
}
?>

<?php function render_complete_profile_modal($m_options,$me,$profile){ 
        global $wpdb;
        $current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

    ?>

<form method="post" action="" enctype="multipart/form-data" id="profile-complete-form">
    
    <input type="hidden" name="update_profile" value="1" />
    
    <div class="modal-dn fade" id="complete-profile-popup" style="display: none;b">
        <div class="modal-dn-body">        

            <div style="border: 0px solid red; height: 10px;position: absolute; left: 575px;">
                <p>
                    <span>
                        <strong></strong>
                    </span>
                    <span id="close-cross-pe" style="float: right;cursor: pointer;">
                        <strong>X</strong>
                    </span>

                </p>
            </div>

            <h4 class="sidebar-title"><?php echo __('Complete Profile','curriki'); ?></h4>        
            <!--
            <p>
                <?php echo $m_options->DaysSinceLastDisplay ?> *** <?php echo $me->registerdate; ?> -- <?php echo date("Y-m-d"); ?>
            </p>
            <p>
                <?php 
                    echo get_days_from_date($me);
                ?>
            </p>
            -->
            
            <div class="edit-section-msg">                   
                
            </div>
            <div class="edit-profile-image">
                <?php $is_avatar_uploaded = false; ?>
                <?php if($me->uniqueavatarfile){
                        $is_avatar_uploaded = true;
                    ?>            
                <img class="circle border-grey profile-img" src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/<?php echo $me->uniqueavatarfile ?>" alt="member-name" width="150" style="width: 115px !important" />
                <?php }else{
                        $profile = get_user_meta(get_current_user_id(),"profile",true);    
                        $profile = isset($profile) ? json_decode($profile) : null; 
                        $gender_img = isset($profile) ? "-".$profile->gender : "";
                        $is_avatar_uploaded = false;
                 ?>
                    <img class="circle border-grey profile-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/user-icon-sample<?php echo $gender_img; ?>.png" alt="member-name" width="115" />
                <?php }?>                
                     
                    <?php
                           $avatar_class =  !$is_avatar_uploaded ? 'class="avatar-required" ' : '';
                    ?>
                    <input type="file" name="my_photo" id="my_photo" <?php echo $avatar_class; ?>/>                        
            </div>

            <div class="edit-name-social">
                <div class="edit-name">
                    <span class="member-name name">
                        <input type="text" id="firstname" name="firstname" value="<?php echo $me->firstname ?>" placeholder="<?php echo __('First Name','curriki'); ?>" /> 
                        <input type="text" id="lastname" name="lastname" value="<?php echo $me->lastname ?>" placeholder="<?php echo __('Last Name','curriki'); ?>" />                                                
                    </span>
                </div>
            </div>

            <span class="location">
                  <?php
                    $q_usa_ml = cur_countries_query($current_language,"US");
                    $usa_ml_obj = $wpdb->get_row($q_usa_ml);                     
                  ?>
                <select name="country" id="country" >
                    <option value="US"><?php echo cur_convert_to_utf_to_html($usa_ml_obj->displayname); ?></option>
                    <?php
                    /*$q_countries = "SELECT * FROM countries";
                    $countries = $wpdb->get_results($q_countries);*/
                    $q_countries = cur_countries_query($current_language);
                    $countries = $wpdb->get_results($q_countries);
                    foreach ($countries as $country) {
                        $selected = '';
                        if ($me->country == $country->country)
                            $selected = 'selected="selected"';
                        echo '<option value="' . $country->country . '" ' . $selected . '>' . cur_convert_to_utf_to_html($country->displayname) . '</option>';
                    }
                    ?>
                </select>,
                <input type="text" name="city" id="city" value="<?php echo $me->city ?>" placeholder="<?php echo __('City','curriki'); ?>" />                       
            </span>
            <div class="edit-name-social" style="width: 100%;">                    
                <input type="radio" name="gender" id="gender-male" value="male" <?php echo isset($profile) && $profile->gender == "male" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-male"><?php echo __('Male','curriki'); ?></label>
                <input type="radio" name="gender" id="gender-female" value="female" <?php echo isset($profile) && $profile->gender == "female" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-female"><?php echo __('Female','curriki'); ?></label>
                <input type="radio" name="gender" id="gender-other" value="other" <?php echo isset($profile) && $profile->gender == "other" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-other"><?php echo __('Other','curriki'); ?></label>
            </div>
            
            <div class="dm-donate-btn-wrapper">            

                <button id="save-profile" type="button" class="close" data-dismiss="modal" style="height: 40px;">
                    <span class="sr-only"><?php echo __('Save','curriki'); ?></span>
                </button>        

                 <button id="close-cross-pe-btn" type="button" class="close" data-dismiss="modal" style="height: 40px;">
                    <span class="sr-only"><?php echo __('Close','curriki'); ?></span>
                </button>        


            </div>

        </div><!-- /.modal-body -->
    </div><!-- /.modal -->
    <div class="complete-profile-form-msg" style="display: none"><?php echo __('Please complete form','curriki'); ?></div>
</form>
<?php 
}

function get_days_from_date($past_date) 
{    
    $now = time(); // or your date as well
    $pastdate = strtotime($past_date);
    $datediff = $now - $pastdate;
    return floor($datediff/(60*60*24));
}
?>

<?php if( isset( $_POST["update_profile"] ) && $_POST["update_profile"] == 1 ) { ?>
    <input type="hidden" name="do-redirect" class="do-redirect" id="do-redirect" value="<?php echo isset( $_POST["update_profile"] ) && $_POST["update_profile"] == 1 ? 1 : 0 ?>" />
    <input type="hidden" name="site-url" id="site-url" value="<?php echo site_url() ?>" />        
    
    <script type="text/javascript">        
        jQuery(document).ready(function () {            
            window.location = jQuery("#site-url").val()+"<?php echo $current_language_slug; ?>/dashboard";
        });
    </script>
<?php } ?>


