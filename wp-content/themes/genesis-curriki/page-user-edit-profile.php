<?php
/*
 * Template Name: User Edit Profile Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */
use Aws\Common\Aws;

$resources_model = new Resources_Model();

$errors = array();
if (isset($_POST['edit_profile']) && $_POST['edit_profile'] == 'yes') {
    global $wpdb;
   
        $my_id = get_current_user_id();
        
        $wpdb->update(
            'cur_users', array(
            'user_email' => $_POST['user_email'],
                ), array('ID' => $my_id), array('%s'), array('%d')
        );
        
        $user_table_fields = array(
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],            
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'country' => $_POST['country'],
            'bio' => $_POST['bio'],
            'facebookurl' => $_POST['facebookurl'],
            'twitterurl' => $_POST['twitterurl'],
            'organization' => $_POST['organization'],
            'blogs' => $_POST['blogs'],
            'showemail' => $_POST['showemail'],
            'indexrequired' => 'T',
            'indexrequireddate' => date('Y-m-d H:i:s'),
            'language' => $_POST['language'],
            'school' => ( isset($_POST['school'])?$_POST['school']:"" )
                );
        
          //'membertype' => $_POST['membertype'],
        if(isset($_POST['membertype']) && strlen($_POST['membertype']) > 0)
        {
            $user_table_fields["membertype"] = $_POST['membertype'];
        }        
          
        if(!empty( $_POST['zipcode'] ))
        {
            $zip = $_POST['zipcode'];
            if(strlen($zip)<=6 && ctype_digit($zip)) {
                //valid            
                $user_table_fields["postalcode"] = $_POST['zipcode'];
            } else {
               //invalid            
                $errors[] = "Enter valid Zip/Postal code.";                
            }
        }
        
        $wpdb->update(
            'users', $user_table_fields , array('userid' => $my_id), array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'), array('%d')
        );
        
        $wpdb->update(
            $wpdb->prefix.'users', array(
            'display_name' => $_POST['firstname'].' '.$_POST['lastname']
                ), array('ID' => $my_id), array('%s'), array('%d')
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
        
        
        $wpdb->delete('user_subjectareas', array('userid' => $my_id), array('%d'));
        if(!empty($_POST['subjectarea']))foreach ($_POST['subjectarea'] as $sa) {
            $wpdb->query($wpdb->prepare(
                            "
                            INSERT INTO user_subjectareas
                            ( userid, subjectareaid )
                            VALUES ( %d, %d )
                    ", $my_id, $sa
            ));
        }
        $wpdb->delete('user_educationlevels', array('userid' => $my_id), array('%d'));
        if(!empty($_POST['educationlevel']))
        foreach ($_POST['educationlevel'] as $el) {
            $wpdb->query($wpdb->prepare(
                            "
                            INSERT INTO user_educationlevels
                            ( userid, educationlevelid )
                            VALUES ( %d, %d )
                    ", $my_id, $el
            ));
        }
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        //$ignore_img_ext = array("docx","doc","ppt","pdf","xls","txt","csv","mp3","mp4","flv");
        $allow_img_ext = array("jpg","jpeg","png","gif");
        
        $clear_ext_check = in_array(pathinfo($_FILES['my_photo']['name'],PATHINFO_EXTENSION), $allow_img_ext);
        if($_FILES['my_photo']['tmp_name'] && !$clear_ext_check)
        {
            $errors[] = "Invalid Profile image extension ( ".pathinfo($_FILES['my_photo']['name'],PATHINFO_EXTENSION)." ).";
        }        
        
        if ( $_FILES['my_photo']['tmp_name'] && $clear_ext_check ) {
            $upload_folder = '/uploads/tmp/';
            $MaxSizeUpload = 5242880; //Bytes

            //$sub_dir = dirname($_SERVER['REQUEST_URI']);
            $sub_dir = "";                        
            
            $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');            
            
            require_once $wp_contents . '/libs/aws_sdk/aws-autoloader.php';

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

            $bucket = 'currikicdn';

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
        
        
        //==== Checking Spam Data and Setting User as spam ============     
        $cnsr_arr  = $wpdb->get_results("SELECT phrase FROM censorphrases");
        $censorphrases  = count($cnsr_arr) > 0 ? $cnsr_arr : array();
        if( stripo_spam($user_table_fields["firstname"], $censorphrases, 1) || stripo_spam($user_table_fields["lastname"] , $censorphrases, 1) || stripo_spam($user_table_fields["bio"] , $censorphrases, 1) || stripo_spam($user_table_fields["blogs"] , $censorphrases, 1) || stripo_spam($user_table_fields["organization"] , $censorphrases, 1) ){           
            //when spam
            $wpdb->update('users', array(
                                            'spam' => 'T',                                            
                                            'indexrequired' => 'T',
                                            'indexrequireddate' => current_time( 'mysql' ),
                                            'active' => 'F',
                                        ),
                                    array(
                                        "userid"=> get_current_user_id(),
                                    ),
                                    array("%s","%s","%s","%s"),
                                    array("%d")
                         );
            
            $wpdb->update('cur_users', array(                                            
                                            'user_status' => 1
                                        ),
                                    array(
                                        "ID"=> get_current_user_id(),
                                    ),
                                    array("%d"),
                                    array("%d")
                         );
            
            $reources_update_fields = array(
                                    'spam' => 'T',                                            
                                    'remove' => 'T',                                            
                                    'indexrequired' => 'T',
                                    'indexrequireddate' => current_time( 'mysql' ),
                                    'active' => 'F',
                                  );
            $resources_model->update_resource_on_user_spam(get_current_user_id() , $reources_update_fields);
            
            
        }else{
            //when not spam
            $wpdb->update('users', array(
                                            'spam' => 'F',                                            
                                            'indexrequired' => 'T',
                                            'indexrequireddate' => current_time( 'mysql' ),
                                            'active' => 'T',
                                        ),
                                    array(
                                        "userid"=> get_current_user_id(), 
                                    ),
                                    array("%s","%s","%s","%s"),
                                    array("%d")
                         );
            $wpdb->update('cur_users', array(                                            
                                            'user_status' => 0
                                        ),
                                    array(
                                        "ID"=> get_current_user_id(),
                                    ),
                                    array("%d"),
                                    array("%d")
                         );
            
            $reources_update_fields = array(
                                    'spam' => 'F',                                            
                                    'remove' => 'F',                                            
                                    'indexrequired' => 'T',
                                    'indexrequireddate' => current_time( 'mysql' ),
                                    'active' => 'T',
                                  );
            $resources_model->update_resource_on_user_spam(get_current_user_id() , $reources_update_fields);
            
        }
        
        
//        if(count($errors) == 0)
//        {
//            wp_redirect(get_bloginfo('url').'/dashboard');
//            die();
//        }
        
    }
if (!is_user_logged_in() and function_exists('curriki_redirect_login')) {
    curriki_redirect_login();
    die;
}
        
// Add custom body class to the head
add_filter('body_class', 'curriki_user_edit_profile_add_body_class');

function curriki_user_edit_profile_add_body_class($classes) {
    $classes[] = 'backend user-edit';
    return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_user_edit_profile_loop');

function curriki_custom_user_edit_profile_loop() {
    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
    remove_action('genesis_loop', 'genesis_do_loop');

    add_action('genesis_loop', 'curriki_user_edit_profile_body', 15);
}

function curriki_user_edit_profile_body() {
    global $wpdb;    
    $current_language = "eng";
    if( defined('ICL_LANGUAGE_CODE') )
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
                                
    $q_me = "SELECT * FROM users where userid = '" . get_current_user_id() . "'";
    $me = $wpdb->get_row($q_me);
    
    $profile = get_user_meta(get_current_user_id(),"profile",true);    
    $profile = isset($profile) ? json_decode($profile) : null;    
    
    ?>

<style type="text/css">
    .profile-img
    {
        margin-bottom: 10px;
        margin-left: 55px;
    }
    
    .error_para{
        border: 1px solid #ff3333 !important;
        color: #ff3333 !important;
        font-size: 15px !important;
        font-weight: bold !important;
        padding: 8px !important;
    }
    
    .error-bar-para{
        border: 1px solid #ff3333 !important;        
        font-size: 12px !important;        
        padding: 4px !important;
        color: #D8000C;
        background-color: #FFBABA;        
    }
    .error-bar
    {
        width: 70% !important;
    }
    
    .lbl-cls
    {
        color: #393938 !important;
        font-family: "proxima-nova",sans-serif !important;
        font-size: 14px !important;
        font-weight: normal !important;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        
        
                
        jQuery("#edit-form").submit(function(){
            var rtnVal = true;
            
            var errors = new Array();
            
            
            if( jQuery("#firstname").val().length  === 0 )
            {
                errors.push("<?php echo __('Please Enter','curriki'); ?> \"<?php echo __('First Name','curriki'); ?>\"");
                rtnVal = false;
            }
            if( jQuery("#lastname").val().length  === 0 )
            {
                errors.push("<?php echo __('Please Enter','curriki'); ?> \"<?php echo __('Last Name','curriki'); ?>\"");
                rtnVal = false;  
            }
            
            if(errors.length > 0 && !rtnVal)
            {
                jQuery(".edit-section-msg").html("");
                var error_wrapper = jQuery('<div></div>').addClass("error-bar");

                var error_para = jQuery('<p></p>').addClass("error-bar-para");
                for(i=0; i < errors.length; i++)
                {                    
                    jQuery(error_wrapper).append( jQuery(error_para).clone().text(errors[i]) );
                }
                jQuery(".edit-section-msg").prepend(error_wrapper);
                jQuery('html, body').animate({
                    scrollTop: jQuery(".edit-section-msg").offset().top + (-150)
                }, 1000);
            }            
            return rtnVal;
        });
    });
</script>
<h3 class="title"><?php echo __('Profile','curriki'); ?></h3>
<form method="post" action="" enctype="multipart/form-data" id="edit-form">
    <input type="hidden" name="edit_profile" value="yes" />
    <div class="edit-profile-content clearfix"><div class="wrap container_12">
            <div class="grid_10">
                <div class="edit-section-msg">                    
                </div>
                <div class="edit-section">                    
                    <?php 
                        global $errors;
                        if(count( $errors ) > 0)
                        {
                    ?>
                            <div class="error-bar">
                                <p class="error-bar-para">
                                    <?php                             
                                    foreach ($errors as $error) { ?>
                                        <?php echo $error; ?> <br />
                                    <?php } ?>
                                </p>
                            </div>
                    <?php } ?>
                    <div class="edit-profile-image">
                        <?php if($me->uniqueavatarfile){?>
                            <img class="circle border-grey profile-img" src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/<?php echo $me->uniqueavatarfile ?>" alt="member-name" width="150" />
                        <?php }else{
                                $profile = get_user_meta(get_current_user_id(),"profile",true);    
                                $profile = isset($profile) ? json_decode($profile) : null; 
                                $gender_img = isset($profile) ? "-".$profile->gender : "";
                         ?>
                            <img class="circle border-grey profile-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/user-icon-sample<?php echo $gender_img; ?>.png" alt="member-name" width="103" />
                        <?php }?>
                        <input type="file" name="my_photo" />
                        <div style="font-weight: normal;margin-bottom: 6px;margin-left: 4px;">
                            <a href="#" id="change-password-link"><?php echo __("Change Password","curriki"); ?></a>
                        </div>
                    </div>
                    
<!--                    <div>
                        <a href="#" id="change-password-link">Change Password</a>
                    </div>-->
                    
                    <div class="edit-name-social">
                        <div class="edit-name">
                            <span class="member-name name">
                                <input type="text" id="firstname" name="firstname" value="<?php echo $me->firstname ?>" placeholder="<?php echo __('First Name','curriki'); ?>"  required="required"/> 
                                <input type="text" id="lastname" name="lastname" value="<?php echo $me->lastname ?>" placeholder="<?php echo __('Last Name','curriki'); ?>" required="required" />
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="gender" id="gender-male" value="male" <?php echo isset($profile) && $profile->gender == "male" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-male"><?php echo __('Male','curriki'); ?></label>
                                <input type="radio" name="gender" id="gender-female" value="female" <?php echo isset($profile) && $profile->gender == "female" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-female"><?php echo __('Female','curriki'); ?></label>
                                <input type="radio" name="gender" id="gender-other" value="other" <?php echo isset($profile) && $profile->gender == "other" ? 'checked="checked"' : '' ?> /> <label class="lbl-cls" for="gender-other"><?php echo __('Other','curriki'); ?></label>
                                
                            </span>
                            
                            <span class="occupation">
                                <select name="membertype" required="required">
                                    <option value="">---<?php echo __('Member Type','curriki'); ?>---</option>
                                    <option value="professional"<?php if($me->membertype == 'professional')echo ' selected="selected"'; ?>><?php echo __('Professional','curriki'); ?></option>
                                    <option value="student"<?php if($me->membertype == 'student')echo ' selected="selected"'; ?>><?php echo __('Student','curriki'); ?></option>
                                    <option value="parent"<?php if($me->membertype == 'parent')echo ' selected="selected"'; ?>><?php echo __('Parent','curriki'); ?></option>
                                    <option value="teacher"<?php if($me->membertype == 'teacher')echo ' selected="selected"'; ?>><?php echo __('Teacher','curriki'); ?></option>
                                    <option value="administration"<?php if($me->membertype == 'administration')echo ' selected="selected"'; ?>><?php echo __('School/District Administrator','curriki'); ?></option>
                                    <option value="nonprofit"<?php if($me->membertype == 'nonprofit')echo ' selected="selected"'; ?>><?php echo __('Non-profit Organization','curriki'); ?></option>
                                </select>
                            </span><span class="location">                                
                                  <?php
                                    $q_usa_ml = cur_countries_query($current_language,"US");
                                    $usa_ml_obj = $wpdb->get_row($q_usa_ml);                     
                                  ?>
                                <select name="country" id="country" onchange="curriki_ShowHideState();">                                    
                                    <option value="US"><?php echo cur_convert_to_utf_to_html($usa_ml_obj->displayname); ?></option>
                                    <?php
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
                                <input type="text" name="city" value="<?php echo $me->city ?>" placeholder="<?php echo __('City','curriki'); ?>" />,                                 
                                <select name="state" id="state_select" required="required">
                                <?php 
                                    $q_states = cur_states_query($current_language);
                                    $states = $wpdb->get_results($q_states);
                                    foreach ($states as $state) {
                                        $selected = '';
                                        if ($me->state == $state->state_name_orignal)
                                            $selected = 'selected="selected"';
                                        echo '<option value="' . $state->state_name_orignal . '" ' . $selected . '>' . $state->state_name . '</option>';
                                    }
                                ?>
                                </select>
                                <input type="text" id="state_text" name="state" value="<?php echo $me->state; ?>" placeholder="<?php echo __('State','curriki'); ?>"<?php if(strtoupper($me->country) == 'US' or strtoupper($me->country) == '')echo ' disabled="disabled" style="display:none;"';?> required="required" />
                                
                                <input type="text" name="zipcode" value="<?php echo $me->postalcode ?>" placeholder="<?php echo __('Zip/Postal Code','curriki'); ?>" />
                                <input type="text" name="school" value="<?php echo $me->school ?>" placeholder="<?php echo __('School','curriki'); ?>" required="required" />
                                </span>
                        </div>
                        <div class="edit-social share-icons">
                            <input type="text" name="facebookurl" placeholder="<?php echo __('Facebook','curriki'); ?>" value="<?php echo $me->facebookurl; ?>" />
                            <input type="text" name="twitterurl" placeholder="<?php echo __('Twitter','curriki'); ?>" value="<?php echo $me->twitterurl; ?>" />
                            <input type="email" name="user_email" placeholder="<?php echo __('Email','curriki'); ?>" value="<?php echo $wpdb->get_var("SELECT user_email FROM cur_users where ID='".get_current_user_id()."'"); ?>" required="required" />
                        </div>
                        <div><input type="checkbox" name="showemail" value="Never" <?php if($me->showemail=='Never')echo ' checked="checked"';?> /> <?php echo __('Do not show my email address on my public profile.','curriki'); ?></div>
                    <h5><?php echo __('Language','curriki'); ?></h5>
                    <?php
                        $q_languages_single = cur_languages_query($current_language,"eng");
                        $language_single = $wpdb->get_row($q_languages_single);                                                
                    ?>
                    <select name="language">                                                
                        <option value="<?php echo $language_single->language; ?>"><?php echo $language_single->displayname; ?></option>
                        <?php 
                        //$languages = $wpdb->get_results("select * from languages order by displayname");
                        $q_languages = cur_languages_query($current_language);
                        $languages = $wpdb->get_results($q_languages);
                        foreach($languages as $lang){
                            $sel = '';
                            if($lang->language == $me->language)$sel = ' selected="selected"';
                            echo '<option value="'.$lang->language.'"'.$sel.'>'.$lang->displayname.'</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <div class="edit-section user-bio">
                    <h5><?php echo __('Bio','curriki'); ?></h5>
                    <textarea name="bio"><?php echo $me->bio ?></textarea>
                    <button type="submit" class="small-button green-button save"><?php echo __('Save','curriki'); ?></button><button class="small-button grey-button cancel" type="reset" onclick='javascript:window.location = "<?php echo get_bloginfo('url') . '/dashboard'; ?>";'><?php echo __('Cancel','curriki'); ?></button>
                </div>
                <div class="clearfix"></div>
                <div class="edit-section">
                    <div class="grid_3"><div class="profile-section">
                            <div class="profile-section-content">
                                <h4><?php echo __('Subjects of Interest:','curriki'); ?></h4>
                                <ul>
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
                                            echo '<li><span id="subject_' . $s->subjectid . '" class="showhide_subjectareas"><span class="subjectareas_plus"> </span></span> ' . $s->displayname . '<ul id="children_subject_' . $s->subjectid . '" style="display:none;">';
                                            
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
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            jQuery(".showhide_subjectareas").click(function () {
                                if (jQuery(this).html() == '<span class="subjectareas_plus"> </span>') {
                                    $id = this.id;
                                    jQuery('#children_' + $id).show('slow');
                                    jQuery(this).html('<span class="subjectareas_minus"> </span>');
                                } else {
                                    $id = this.id;
                                    jQuery('#children_' + $id).hide('slow');
                                    jQuery(this).html('<span class="subjectareas_plus"> </span>');
                                }
                            });
                        });
                    </script>
                    <div class="grid_3"><div class="profile-section"><div class="profile-section-content"><h4><?php echo __('Education Levels of Interest:','curriki'); ?></h4>
                                <ul>
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
                            </div></div></div>
                    <div class="grid_3"><div class="profile-section"><div class="profile-section-content"><h4><?php echo __('Organization:','curriki'); ?></h4><ul><li><input type="text" name="organization" placeholder="<?php echo __('Organization','curriki'); ?>" value="<?php echo $me->organization ?>" style="width:220px;" /></li></ul></div></div><div class="profile-section"><div class="profile-section-content"><h4><?php echo __('Website/Blogs:','curriki'); ?></h4><ul><li><input type="text" name="blogs" placeholder="<?php echo __('Blogs','curriki'); ?>" value="<?php echo $me->blogs ?>" style="width:220px;" /></li></ul></div></div></div>
                    <div class="grid_3"><div class="profile-section"><div class="profile-section-content"><h4><?php echo __('Joined:','curriki'); ?></h4><ul><li><?php echo $my_registerdate = date("M d, Y", strtotime($me->registerdate)); ?></li></ul></div></div>
                        <?php 
                        $q_lastlogin = "select max(logindate) from logins where userid = " . get_current_user_id() . " and sitename = 'curriki';";
                        $last_login = $wpdb->get_var($q_lastlogin);
                        if ($last_login != ''){?><div class="profile-section"><div class="profile-section-content"><h4><?php echo __('Last Activity:','curriki'); ?></h4><ul><li><?php echo $last_login;?></li></ul></div></div><?php }?></div>
                </div>
            </div>
        </div></div>
    </form>
    <?php
}

genesis();
