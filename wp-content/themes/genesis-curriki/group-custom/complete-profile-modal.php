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

  
if( isset( $_POST["update_profile"] ) && $_POST["update_profile"] == 1 && isset($_GET["req"]) && $_GET["req"]=== "ajax" )
{
    
    global $wpdb;
    $my_id = get_current_user_id();
        
    
        $user_table_fields = array(
            'membertype' => $_POST['membertype'],            
            'city' => $_POST['city'],            
            'country' => $_POST['country'],
            'indexrequired' => 'T',
            'indexrequireddate' => date('Y-m-d H:i:s'),            
         );
        $ref_arr = array('%s','%s', '%s', '%s', '%s');
        if($user_table_fields["country"] === "US")
        {
            $user_table_fields["state"] = $_POST['state'];
            $ref_arr[] = '%s';
        }        
        if($user_table_fields["membertype"] === "Teacher")
        {
            $user_table_fields["school"] = $_POST['school']; 
            $ref_arr[] = '%s';
        }
        
        $wpdb->update(
            'users', $user_table_fields , array('userid' => $my_id), $ref_arr , array('%d')
        );
        
        
        if( $user_table_fields["membertype"] === "Teacher" && !empty($_POST['subjectarea']) )
        {
            foreach ($_POST['subjectarea'] as $sa) {
                $wpdb->query($wpdb->prepare(
                                "
                                INSERT INTO user_subjectareas
                                ( userid, subjectareaid )
                                VALUES ( %d, %d )
                        ", $my_id, $sa
                ));
            }
        }
        
        if($user_table_fields["membertype"] === "Teacher" && !empty($_POST['educationlevel']) )
        {            
            $wpdb->delete('user_educationlevels', array('userid' => $my_id), array('%d'));
             if(!empty($_POST['educationlevel']))
             {
                foreach ($_POST['educationlevel'] as $el) {
                    $wpdb->query($wpdb->prepare(
                                    "
                                    INSERT INTO user_educationlevels
                                    ( userid, educationlevelid )
                                    VALUES ( %d, %d )
                            ", $my_id, $el
                    ));
                }
             }
        }
        
                
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
   
    global $wpdb;
    $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
    $me = $wpdb->get_row($q_me);        
    $profile = get_user_meta(get_current_user_id(),"profile",true);    
    $profile = isset($profile) ? json_decode($profile) : null;                    
    if(!isset($profile))
    {
        $profile = new stdClass();
        $profile->gender = "male";
        add_user_meta(get_current_user_id(), "profile", json_encode($profile));
    }
    
    
    //$days_interval = get_days_from_date($me);    
    $display_days_span = "";
    
    $last_display = get_user_meta(get_current_user_id(),"complete_profile_modal_display",true);
    
    
    
    $failed_cases = array();
    if($me->uniqueavatarfile===false || strlen($me->uniqueavatarfile)===0)
    {
        $failed_cases[] = "uniqueavatarfile";
    }    
    if(($me->country===false || strlen($me->country)===0))
    {
        $failed_cases[] = "country";
    }    
    if( $me->country === "US" && (strlen($me->state)===0 || $me->state==="US") )
    {
        $failed_cases[] = "US Country and state";
    }
    if(($me->city===false || strlen($me->city)===0))
    {        
        $failed_cases[] = "city";
    }    
    if(($me->membertype===false || strlen($me->membertype)===0))
    {
        $failed_cases[] = "membertype";
    }     
    $q_my_subjectareas_vld = "SELECT * FROM user_subjectareas WHERE userid='" . get_current_user_id() . "';";
    $my_sas_vld = $wpdb->get_results($q_my_subjectareas_vld);
    
    $usb_exp = $my_sas_vld===null || ( is_array($my_sas_vld) && count($my_sas_vld)===0 );    
    if( $me->membertype==="Teacher" && $usb_exp )
    {
        $failed_cases[] = "No user_subjectareas";
    }     
    if($me->membertype==="Teacher" && ($me->school===false || strlen($me->school)===0) )
    {
        $failed_cases[] = "school";
    } 
    
    $q_my_educationlevels_vld = "SELECT * FROM user_educationlevels WHERE userid='" . get_current_user_id() . "';";
    $my_els_vld = $wpdb->get_results($q_my_educationlevels_vld);
    
    $edu_level_exp = $my_els_vld===null || ( is_array($my_els_vld) && count($my_els_vld)===0 );    
    
    if( $me->membertype==="Teacher" && $edu_level_exp )
    {            
        $failed_cases[] = "No user_education level";
    }
    
    if( count($failed_cases)>0 )
    {
        
        if($last_display)
        {
            $display_days_span = get_days_from_date($last_display);
            //echo "users = ".get_current_user_id() . "<br />";
            //echo "DaysSinceLastDisplay = ". $m_options->DaysSinceLastDisplay . "<br />";
            //echo "calculated day span = ". $display_days_span . "<br />";
            //echo " last display = ".$last_display . "<br />";
            if((intval($display_days_span) >= intval($m_options->DaysSinceLastDisplay) ))
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

<script type="text/javascript">
    jQuery(document).ready(function () {            
        
        toggle_city_field( jQuery("#country") );            
        
        jQuery("#country").change(function(){            
            toggle_city_field(jQuery(this));            
        });
        
    });
    
    function toggle_city_field(this_var)
    {
        if(this_var.val() === "US")
        {
            jQuery("#state-row").show();
        }else{
            jQuery("#state-row").hide();
            jQuery("#state").val("");
        }
    }
</script>
<form method="post" action="" enctype="multipart/form-data" id="profile-complete-form">
    
    <input type="hidden" name="update_profile" value="1" />
    
    <div class="modal-dn fade" id="complete-profile-popup" style="display: none;">
        <div class="modal-dn-body modal-profile-complete rounded-borders-full modal">        

            <div style="border: 0px solid red; height: 10px;position: absolute; left: 610px;">
                <p>
                    <span>
                        <strong></strong>
                    </span>
                    <div class="close">
                        <span id="close-cross-pe" class="fa fa-close" style="float: right;cursor: pointer;">
                            <!--<strong>X</strong>-->
                        </span>
                    </div>                    
                </p>
            </div>
            
            <div class="popup-heading-wrapper">
                <h4 class="modal-title-format"><?php echo __('Please complete your Curriki Community Profile','curriki'); ?></h4>
                <span class="member-name saving-label"><strong>Saving....</strong></span>
            </div>            
            
            <div class="edit-section-msg">                   
                
            </div>
            
            <div id="complete-profile-popup-1" class="complete-profile-popup-cls">
                <div class="edit-profile-row-wrap-img">
                    <div class="edit-profile-row">
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

                            <span class="member-name name-modal"><?php echo ($me->firstname ? $me->firstname : "FirstName") ?> <?php echo ($me->lastname ? $me->lastname : "LastName") ?></span>


                            <?php
                                   $avatar_class =  !$is_avatar_uploaded ? 'class="avatar-required" ' : '';
                                   if(!$is_avatar_uploaded)
                                   {
                            ?>
                                        <input type="file" name="my_photo" id="my_photo" <?php echo $avatar_class; ?> />                        
                                   <?php                                
                                   } ?>
                    </div>
                </div>
                
                <div class="edit-profile-row-wrap">
                    <div class="edit-profile-row edit-profile-row-setting">                
                        <div class="side-label">I am a: <span class="lable-asterisk">*</span></div>
                        <span class="occupation">
                            <select name="membertype" id="membertype">
                                <option value="">-- <?php echo __('Member Type','curriki'); ?> --</option>
                                <option value="professional"<?php if($me->membertype == 'professional')echo ' selected="selected"'; ?>><?php echo __('Professional','curriki'); ?></option>
                                <option value="student"<?php if($me->membertype == 'student')echo ' selected="selected"'; ?>><?php echo __('Student','curriki'); ?></option>
                                <option value="parent"<?php if($me->membertype == 'parent')echo ' selected="selected"'; ?>><?php echo __('Parent','curriki'); ?></option>
                                <option value="teacher"<?php if($me->membertype == 'teacher')echo ' selected="selected"'; ?>><?php echo __('Teacher','curriki'); ?></option>
                                <option value="administration"<?php if($me->membertype == 'School/District Administrator')echo ' selected="selected"'; ?>><?php echo __('School/District Administrator','curriki'); ?></option>
                                <option value="nonprofit"<?php if($me->membertype == 'nonprofit')echo ' selected="selected"'; ?>><?php echo __('Non-profit Organization','curriki'); ?></option>
                            </select>
                        </span>
                    </div>
                
                    <div class="edit-profile-row edit-profile-row-setting">                
                        <div class="side-label">I live in: <span class="lable-asterisk">*</span></div>
                        <span class="occupation">
                            <select name="country" id="country" >
                                <option value="">-- Select Country --</option>
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
                            </select> 
                        </span>
                    </div>
                </div>
                
                <div class="edit-profile-row-wrap-fld-only">
                    <div id="state-row" class="edit-profile-row edit-profile-row-setting-fld-only">
                        <!--<div class="side-label">&nbsp;&nbsp;&nbsp;</div>-->
                        <select name="state" id="state">
                        <?php 
                            $q_states = cur_states_query($current_language);
                            $states = $wpdb->get_results($q_states);
                            echo '<option value="" ' . $selected . '>' . "-- Select State --" . '</option>';
                            foreach ($states as $state) {
                                $selected = '';
                                if ($me->state == $state->state_name_orignal)
                                    $selected = 'selected="selected"';
                                echo '<option value="' . $state->state_name_orignal . '" ' . $selected . '>' . $state->state_name . '</option>';
                            }
                        ?>
                        </select>
                    </div>

                    <div id="city-row" class="edit-profile-row edit-profile-row-setting-fld-only">
                        <!--<div class="side-label">&nbsp;&nbsp;&nbsp;</div>-->
                        <input id="city" name="city" type="text" placeholder="City" value="<?php echo (isset($me->city))?$me->city:""; ?>" />
                    </div>
                </div>
                
                
            </div>
            
            <div id="complete-profile-popup-2" class="complete-profile-popup-cls hidden">                
                
                <div class="edit-profile-row-wrap">
                    <div id="city-row" class="edit-profile-row edit-profile-row-setting-one-field">                    
                        <div class="side-label">I teach at:<span class="lable-asterisk">*&nbsp;</span></div>
                        <input id="school" name="school" type="text" placeholder="School" value="<?php echo (isset($me->school))?$me->school:""; ?>" />
                    </div>
                </div>
                
                <div class="edit-profile-row-wrap-sbj-grd">
                    <div id="city-row" class="edit-profile-row edit-profile-row-setting">                    
                        <div class="side-label">Subjects I teach:<span class="lable-asterisk">*</span></div>                            
                        <!--<div class="my-library-folders rounded-borders-full border-grey scrollbar">-->
                            <ul class="subject-areas-ul-complete-profile my-library-folders rounded-borders-full border-grey scrollbar">
                                <?php
                                $q_my_subjectareas = "SELECT * FROM user_subjectareas WHERE userid='" . get_current_user_id() . "';";
                                $my_sas = $wpdb->get_results($q_my_subjectareas);
                                $my_subjectareas = array();
                                foreach ($my_sas as $my_sa) {
                                    $my_subjectareas[] = $my_sa->subjectareaid;
                                }

                                $q_subjects = cur_subjects_query($current_language);
                                $subjects = $wpdb->get_results($q_subjects);
                                if (count($subjects) > 0)
                                    foreach ($subjects as $s) {
                                        echo '<li><span id="subject_' . $s->subjectid . '" class="showhide_subjectareas"><span class="toggle-span subjectareas_plus"> </span></span> ' . $s->displayname . '<ul class="subjects-ul" id="children_subject_' . $s->subjectid . '" style="display:none;">';

                                        //$q_subjectareas = "SELECT * FROM subjectareas WHERE subjectid = '" . $s->subjectid . "' ORDER BY displayname ASC;";
                                        $q_subjectareas = cur_subjectareas_query($current_language,$s->subjectid);
                                        $subjectareas = $wpdb->get_results($q_subjectareas);
                                        if (count($subjectareas) > 0)
                                            foreach ($subjectareas as $subjectarea) {
                                                $checked = '';
                                                if (in_array($subjectarea->subjectareaid, $my_subjectareas))
                                                    $checked = ' checked="checked"';
                                                echo '<li> <input type="checkbox" name="subjectarea[]" value="' . $subjectarea->subjectareaid . '"' . $checked . '> ' . $subjectarea->displayname . '</li>';
                                            }
                                        echo '</ul></li>';
                                    }
                                ?>
                            </ul>      
                        <!--</div>-->

                    </div>                
                    <div id="city-row" class="edit-profile-row edit-profile-row-setting">                    
                        <div class="side-label">Grades I teach:<span class="lable-asterisk"></span></div>                            
                        <ul class="education-level-ul-complete-profile my-library-folders rounded-borders-full border-grey scrollbar">
                            <?php
                            $q_my_educationlevels = "SELECT * FROM user_educationlevels WHERE userid='" . get_current_user_id() . "';";
                            $my_els = $wpdb->get_results($q_my_educationlevels);
                            $my_educationlevels = array();
                            foreach ($my_els as $my_el) {
                                $my_educationlevels[] = $my_el->educationlevelid;
                            }
                            //$q_educationlevels = "SELECT * FROM educationlevels WHERE displayseqno != '' and active = 'T' ORDER BY displayseqno ASC;";
                            $q_educationlevels = cur_educationlevels_query($current_language);
                            $educationlevels = $wpdb->get_results($q_educationlevels);
                            if (count($educationlevels) > 0)
                                foreach ($educationlevels as $educationlevel) {
                                    $checked = '';
                                    if (in_array($educationlevel->levelid, $my_educationlevels))
                                        $checked = ' checked="checked"';
                                    echo '<li> <input type="checkbox" name="educationlevel[]" value="' . $educationlevel->levelid . '"' . $checked . '> ' . $educationlevel->displayname . '</li>';
                                }
                            ?>
                        </ul>

                    </div>
                </div>
                
            </div>
            
            <div class="dm-donate-btn-wrapper">            

            <!--                
                <button id="save-profile" type="button" class="close" data-dismiss="modal" style="height: 40px;">
                    <span class="sr-only"><?php echo __('Save','curriki'); ?></span>
                </button>        

                 <button id="close-cross-pe-btn" type="button" class="close" data-dismiss="modal" style="height: 40px;">
                    <span class="sr-only"><?php echo __('Close','curriki'); ?></span>
                </button>        -->

            
            <div class="card-button">                                    
                <button id="back-button" class="modal-button green-button complete-profile-btn hidden"><?php echo __('Back','curriki'); ?></button>
                <button id="save-profile" class="modal-button white-button complete-profile-btn"><?php echo __('Save & Finish','curriki'); ?></button>                
                <button id="next-complete-profile-btn" class="modal-button white-button hidden complete-profile-btn"><?php echo __('Next','curriki'); ?></button>                
            </div>                

<!--            <button id="save-profile" class="card-button complete-profile-btn"><?php echo __('Save & Finish','curriki'); ?></button>-->
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

<div id="thank-you-modal" class="my-library-modal modal border-grey rounded-borders-full grid_6" style="display: none;">
    <h3 class="modal-title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Thank You! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
    <div class="close">
        <span id="close-ty" class="fa fa-close"></span>
    </div>
</div>
