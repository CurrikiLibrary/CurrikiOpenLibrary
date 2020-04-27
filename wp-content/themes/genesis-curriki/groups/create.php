<?php gconnect_get_header(); ?>
<?php wp_enqueue_style('group-custom-style', get_stylesheet_directory_uri() . '/js/group-custom-script/group-custom-style.css'); ?>
<?php

//if(isset($_POST) && count($_POST) > 0 ){
//    echo "<pre>";
//    var_dump($_POST);
//    die();
//}
$current_language = "eng";
if( defined('ICL_LANGUAGE_CODE') )
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/nprogress.css" />

<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/nprogress.js"></script>

<?php
    wp_register_script( 'ng-ctrlr', get_stylesheet_directory_uri() . '/js/angular_controllers.js?v=1' );
    $translation_array = cur_angular_controllers_translations();
    wp_localize_script( 'ng-ctrlr', 'ml_obj', $translation_array );
    wp_enqueue_script("ng-ctrlr");  
    wp_enqueue_style('bootstrap-css',  get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 'jquery', '2.1.5');
    wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', null, false, true);
    
?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jcrop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/jcrop/jquery.Jcrop.min.css" />
<script type='text/javascript' src='<?php echo get_site_url(); ?>/wp-content/plugins/invite-anyone/group-invites/jquery.autocomplete/jquery.autocomplete-min.js?ver=4.1.1'></script>

<script type="text/javascript">       
    function uncheck_subject_areas($this, sub) {
      if (!$($this).is(':checked')) {
        $('.' + sub).attr('checked', false);
      }
    }

    function check_subject($this, sub) {
      if ($($this).is(':checked') && !$('#' + sub).is(':checked')) {
        $('#' + sub).click();
      }
    }
    function go_to_dashboard() {
      window.location.href = '<?php echo get_site_url(); ?>/dashboard/';
    }
    function view_resource() {
      window.location.href = '<?php echo get_site_url(); ?>/oer/?rid=' + $('#resourceid').val();
    }
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var baseurl = '<?php echo get_bloginfo('url'); ?>/';
  </script>
  
<?php
    global $wpdb;
    
    $education_levels = array(
        array('title' => __('Preschool (Ages 0-4)','curriki'), 'levels' => '8|9', 'arlevels' => array(8, 9)),
        array('title' => __('Kindergarten-Grade 2 (Ages 5-7) ','curriki'), 'levels' => '3|4', 'arlevels' => array(3, 4)),
        array('title' => __('Grades 3-5 (Ages 8-10)','curriki'), 'levels' => '5|6|7', 'arlevels' => array(5, 6, 7)),
        array('title' => __('Grades 6-8 (Ages 11-13)','curriki'), 'levels' => '11|12|13', 'arlevels' => array(11, 12, 13)),
        array('title' => __('Grades 9-10 (Ages 14-16)','curriki'), 'levels' => '15|16', 'arlevels' => array(15, 16)),
        array('title' => __('Grades 11-12 (Ages 16-18)','curriki'), 'levels' => '17|18', 'arlevels' => array(17, 18)),
        array('title' => __('College & Beyond','curriki'), 'levels' => '23|24|25', 'arlevels' => array(23, 24, 25)),
        array('title' => __('Professional Development','curriki'), 'levels' => '19|20', 'arlevels' => array(19, 20)),
        array('title' => __('Special Education','curriki'), 'levels' => '26|21', 'arlevels' => array(26, 21)),
    );
    
    $educationlevels = $wpdb->get_results("select levelid,displayname from educationlevels where active = 'T' and displayseqno is not null order by displayseqno asc", ARRAY_A);    
    $q_subjects = cur_subjects_query($current_language);
    $subjects = $wpdb->get_results($q_subjects,ARRAY_A);
        
    $q_subjectareas = cur_subjectareas_query($current_language,null);              
    $subjectareas = $wpdb->get_results($q_subjectareas,ARRAY_A);   
    
    $current_step = "";
    $current_form_step = "";
        
    if(bp_action_variable( 1 ) == "forum")
    {
        $current_step = "Photo";
    }
    
    if(bp_action_variable( 1 ) == "invite-anyone")
    {
        $current_form_step = "Finish";
    }
    
    
    
    $group_subjectareas = array();
    $group_educationlevels = array();
    if(bp_get_new_group_id() > 0)
    {        
         $group_subjectareas = $wpdb->get_results( "SELECT * FROM group_subjectareas LEFT JOIN subjectareas on group_subjectareas.subjectareaid = subjectareas.subjectareaid WHERE groupid= ".bp_get_new_group_id(), OBJECT );
         $group_educationlevels = $wpdb->get_results( "SELECT * FROM group_educationlevels WHERE groupid= ".bp_get_new_group_id(), OBJECT );
    }          
    
    $subject_ids = array();
    
    $subjectarea_ids = array();    
    foreach ($group_subjectareas as $subjectarea)
    {
        array_push($subjectarea_ids, $subjectarea->subjectareaid);
        array_push($subject_ids, $subjectarea->subjectid);
    }    
    $subject_ids = array_unique($subject_ids);
    
    $educationlevel_ids = array();
    foreach ($group_educationlevels as $educationlevel)
    {
        array_push($educationlevel_ids, $educationlevel->educationlevelid);
    }
?>  
<div class="wrap container_12" ng-app="ngApp" ng-controller="createResourceCtrl">
    <div class="content">
	<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" class="standard-form" enctype="multipart/form-data">
                <div class="item-list-tabs no-ajax" id="group-create-tabs" role="navigation">
                        <!--<a class="button" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() ); ?>"><?php _e( 'My Groups', 'buddypress' ); ?></a>-->
                        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<?php 
                                //bp_group_creation_tabs(); 
                                cur_groups_creation_tabs();
                                ?>
			</ul>
			<div class="clear"></div>
		</div>
            
                <div class="create-resource-content resource-content clearfix">
                    <div class="wrap grid_12">
                        <div id="create" class="tab-contents ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false">                            
                            
                            <h3 class="section-header"><?php _e( 'Create Group', 'buddypress' ); ?> &nbsp;</h3>
                            
                            <?php do_action( 'bp_before_create_group' ); ?>



                            <?php do_action( 'template_notices' ) ?>

                            <div class="item-body" id="group-create-body">

                                    <?php if ( bp_is_group_creation_step( 'group-details' ) ) : ?>

                                            <?php do_action( 'bp_before_group_details_creation_step' ); ?>

                                            <label for="group-name" class="group-lbl"><?php _e( 'Name', 'buddypress' ); ?> <span>(<?php echo __('required','curriki'); ?>)</span></label>                                            
                                            <div id="resource-description-help-name" class="tooltip-group-name tooltip fa fa-question-circle"></div>                                            
                                            <input type="text" name="group-name" id="group-name"  aria-required="true" value="<?php bp_new_group_name(); ?>" placeholder="<?php echo __('Enter Group Ttile','curriki'); ?>" />

                                            <label class="group-lbl" for="group-desc"><?php _e( 'Description', 'buddypress' ) ?> <span>(<?php echo __('required','curriki'); ?>)</span></label>
                                            <div id="resource-description-help-description" class="tooltip-group-description tooltip fa fa-question-circle"></div>
                                            <textarea name="group-desc" id="group-desc" aria-required="true" ><?php bp_new_group_description(); ?></textarea>
                                            <br />
                                            <h3 class="section-header"><?php echo __('Describe Your Group','curriki'); ?></h3>
                                            <div class = "grid_12 alpha omega">
                                                <div class = "grid_9 alpha">

                                                  <div class = "optionset">
                                                    <div class = "optionset-title"><?php echo __('Subject','curriki'); ?></div>

                                                    <ul class="subject-ul">
                                                      <?php
                                                      foreach ($subjects as $sub) {
                                                        echo '<li ng-mouseover="subject_hover(' . $sub['subjectid'] . ')" ng-click="subject_hover(' . $sub['subjectid'] . ')" ><label><input name="subject[]" type="checkbox" value="' . $sub['subject'] . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this,\'subjectarea_' . $sub['subjectid'] . '\')" ' . (in_array($sub['subjectid'], $subject_ids) ? 'checked="checked"' : '') . '>' . $sub['displayname'] . '</label></li>';
                                                      }
                                                      ?>
                                                    </ul>
                                                  </div>

                                                  <div class="optionset two-col grey-border" style="max-width: 63%;min-width: 63%;min-height: 320px; float: right">
                                                      <div class="optionset-title"><?php echo __('Subject Areas','curriki'); ?> <span id="subject-areas-of"></span></div>
                                                    <ul class="subjectareas-ul">
                                                      <?php
                                                      foreach ($subjectareas as $sub) {
                                                        echo '<li ng-show="is_subject_hover(' . $sub['subjectid'] . ')"><label><input name="subjectarea[]" type="checkbox" value="' . $sub['subjectareaid'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')"' . (in_array($sub['subjectareaid'], $subjectarea_ids) ? 'checked="checked"' : '') . '>' . $sub['displayname'] . '</label></li>';
                                                      }
                                                      ?>
                                                    </ul>
                                                  </div>
                                                </div>
                                                <div class="grid_3 omega">
                                                  <div class="resource-content-section"><h4><?php echo __('Education Level','curriki'); ?></h4><div class="tooltip-group-educationlevels tooltip fa fa-question-circle" id="resource-education-level"></div>

                                                      <ul class="educationlevel-ul">
                                                      <?php
                                                      foreach ($education_levels as $l) {
                                                        echo '<li><label><input type="checkbox" id="resource-education-level" value="' . $l['levels'] . '"'
                                                        . (count(array_intersect($educationlevel_ids, $l['arlevels'])) ? 'checked="checked"' : '')
                                                        . ' name="education_levels[]" />' . $l['title'] . '</label></li>';                                                        
                                                      }
                                                      ?>
                                                    </ul>

                                                  </div>
                                                </div>
                                              </div>

                                            
                                            <?php do_action( 'bp_after_group_details_creation_step' ); do_action( 'groups_custom_group_fields_editable' ); wp_nonce_field( 'groups_create_save_group-details' ); ?>

                                    <?php elseif ( bp_is_group_creation_step( 'group-settings' ) ) : ?>

                                            <?php do_action( 'bp_before_group_settings_creation_step' ); ?>

                                            <?php if ( bp_is_active( 'forums' ) ) : ?>
                                                    <?php if ( bp_forums_is_installed_correctly() ) : ?>
                                                            <div class="checkbox">
                                                                    <label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php checked( bp_get_new_group_enable_forum(), true, true ); ?> /> <?php _e( 'Enable discussion forum', 'buddypress' ); ?></label>
                                                            </div>
                                                    <?php elseif ( is_super_admin() ) : ?>
                                                                    <div class="checkbox">
                                                                            <label><input type="checkbox" disabled="disabled" name="disabled" id="disabled" value="0" /> <?php printf( __( '<strong>Attention Site Admin:</strong> Group forums require the <a href="%s">correct setup and configuration</a> of a bbPress installation.', 'buddypress' ), bp_get_root_domain() . '/wp-admin/admin.php?page=bb-forums-setup' ); ?></label>
                                                                    </div>
                                                    <?php endif; ?>
                                            <?php endif; ?>

                                            <hr />

                                            <h4><?php _e( 'Privacy Options', 'buddypress' ); ?></h4>
                                             <?php
                                                $current_step = "Forum";
                                             ?>
                                            <div id="group_create_privacy_options" class="radio">
                                                    <label><input type="radio" name="group-status" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
                                                            <strong><?php _e( 'This is a public group', 'buddypress' ); ?></strong>
                                                            <ul>
                                                                    <li><?php _e( 'Any site member can join this group.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'This group will be listed in the groups directory and in search results.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'Group content and activity will be visible to any site member.', 'buddypress' ); ?></li>
                                                            </ul>
                                                    </label>

                                                    <label><input type="radio" name="group-status" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
                                                            <strong><?php _e( 'This is a private group', 'buddypress' ); ?></strong>
                                                            <ul>
                                                                    <li><?php _e( 'Only users who request membership and are accepted can join the group.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'This group will be listed in the groups directory and in search results.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'Group content and activity will only be visible to members of the group.', 'buddypress' ); ?></li>
                                                            </ul>
                                                    </label>

                                                    <label><input type="radio" name="group-status" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
                                                            <strong><?php _e('This is a hidden group', 'buddypress'); ?></strong>
                                                            <ul>
                                                                    <li><?php _e( 'Only users who are invited can join the group.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'This group will not be listed in the groups directory or search results.', 'buddypress' ); ?></li>
                                                                    <li><?php _e( 'Group content and activity will only be visible to members of the group.', 'buddypress' ); ?></li>
                                                            </ul>
                                                    </label>
                                            </div>

                                            <hr />

                                            <h4><?php _e( 'Group Invitations', 'buddypress' ); ?></h4> 

                                            <p><?php _e( 'Which members of this group are allowed to invite others?', 'buddypress' ); ?></p>

                                            <div class="radio"> 
                                                    <label> 
                                                            <input type="radio" name="group-invite-status" value="members"<?php bp_group_show_invite_status_setting( 'members' ); ?> />
                                                            <strong><?php _e( 'All group members', 'buddypress' ); ?></strong>
                                                    </label> 

                                                    <label> 
                                                            <input type="radio" name="group-invite-status" value="mods"<?php bp_group_show_invite_status_setting( 'mods' ); ?> />
                                                            <strong><?php _e( 'Group admins and mods only', 'buddypress' ); ?></strong>
                                                    </label>

                                                    <label> 
                                                            <input type="radio" name="group-invite-status" value="admins"<?php bp_group_show_invite_status_setting( 'admins' ); ?> />
                                                            <strong><?php _e( 'Group admins only', 'buddypress' ); ?></strong>
                                                    </label> 
                                            </div> 

                                            <hr /> 

                                            <?php do_action( 'bp_after_group_settings_creation_step' ); wp_nonce_field( 'groups_create_save_group-settings' ); ?>

                                    <?php elseif ( bp_is_group_creation_step( 'group-avatar' ) ) : ?>

                                            <?php do_action( 'bp_before_group_avatar_creation_step' ); ?>

                                            <?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
                                            <?php
                                                $current_step = "Invites";                                                
                                             ?>
                                                    <div class="left-menu">
                                                            <?php bp_new_group_avatar(); ?>
                                                    </div><!-- .left-menu -->

                                                    <div class="main-column">
                                                            <p><?php _e( "Upload an image to use as an avatar for this group. The image will be shown on the main group page, and in search results.", 'buddypress' ); ?></p>
                                                            <p>
                                                                    <input type="file" name="file" id="file" />
                                                                    
                                                                    <!--<input type="submit" name="upload" id="upload" value="<?php _e( 'Upload Image', 'buddypress' ); ?>" />-->
                                                                    <button name="upload" id="upload" class="resource-button small-button-extended green-button next-step" onclick="change_tab()">Upload Image</button>
                                                                    
                                                                    <input type="hidden" name="action" id="action" value="bp_avatar_upload" />
                                                            </p>
                                                            <p><?php _e( 'To skip the avatar upload process, hit the "Next Step" button.', 'buddypress' ); ?></p>
                                                    </div><!-- .main-column -->

                                            <?php elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
                                                    <script type="text/javascript">
                                                        $(window).load(function(){
                                                            console.log( "==>" , jQuery('#avatar-to-crop') );
                                                        });
                                                    </script>
                                                    <h4><?php _e( 'Crop Group Avatar', 'buddypress' ); ?></h4>

                                                    <img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />

                                                    <div id="avatar-crop-pane">
                                                            <img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
                                                    </div>

                                                    <input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Crop Image', 'buddypress' ); ?>" />

                                                    <input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
                                                    <input type="hidden" name="upload" id="upload" />
                                                    <input type="hidden" id="x" name="x" />
                                                    <input type="hidden" id="y" name="y" />
                                                    <input type="hidden" id="w" name="w" />
                                                    <input type="hidden" id="h" name="h" />

                                            <?php endif; ?>

                                            <?php do_action( 'bp_after_group_avatar_creation_step' ); wp_nonce_field( 'groups_create_save_group-avatar' ); ?>

                                    <?php elseif ( bp_is_group_creation_step( 'group-invites' ) ) : ?>

                                            <?php do_action( 'bp_before_group_invites_creation_step' ); ?>

                                            <?php if ( bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
                                                    <div class="left-menu">
                                                            <div id="invite-list">
                                                                    <ul>
                                                                            <?php bp_new_group_invite_friend_list(); ?>
                                                                    </ul>
                                                                    <?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>
                                                            </div>
                                                    </div><!-- .left-menu -->

                                                    <div class="main-column">
                                                            <div id="message" class="info">
                                                                    <p><?php _e('Select people to invite from your friends list.', 'buddypress'); ?></p>
                                                            </div>
                                                            <ul id="friend-list" class="item-list" role="main">

                                                            <?php if ( bp_group_has_invites() ) : ?>

                                                                    <?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

                                                                            <li id="<?php bp_group_invite_item_id(); ?>">
                                                                                    <?php bp_group_invite_user_avatar(); ?>

                                                                                    <h4><?php bp_group_invite_user_link(); ?></h4>
                                                                                    <span class="activity"><?php bp_group_invite_user_last_active(); ?></span>

                                                                                    <div class="action">
                                                                                            <a class="remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', 'buddypress' ); ?></a>
                                                                                    </div>
                                                                            </li>

                                                                    <?php endwhile; ?>
                                                            <?php endif; ?>

                                                            </ul>
                                                    </div><!-- .main-column -->
                                            <?php else : ?>
                                                    <div id="message" class="info">
                                                            <p><?php _e( 'Once you have built up friend connections you will be able to invite others to your group. You can send invites any time in the future by selecting the "Send Invites" option when viewing your new group.', 'buddypress' ); ?></p>
                                                    </div>
                                            <?php endif; ?>
                                            <?php do_action( 'bp_after_group_invites_creation_step' ); wp_nonce_field( 'groups_create_save_group-invites' ); ?>
                                    <?php endif; ?>

                                    <?php do_action( 'groups_custom_create_steps' ); do_action( 'bp_before_group_creation_step_buttons' ); ?>

                                    <?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>
                                            <div class="submit" id="previous-next">
                                                <div class="create-edit-steps">
                                                    <?php if ( !bp_is_first_group_creation_step() ) : ?>
                                                            <!--<input type="button" value="<?php _e( 'Back to Previous', 'buddypress' ); ?>" id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'" class="resource-button small-button-extended grey-button cancel" id="group-creation-create" />-->
                                                            <button id="group-creation-previous" name="previous" link="<?php bp_group_creation_previous_link(); ?>" class="go_back resource-button small-button-extended grey-button cancel" id="group-creation-create"><?php echo __('Back','curriki'); ?></button>
                                                    <?php endif; ?>
                                                    <?php if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>
                                                            
                                                            <button onclick="change_tab();" id="group-creation-next" name="save" class="resource-button small-button-extended green-button next-step" id="group-creation-create" ><?php echo __('Next Step','curriki'); ?>: <strong><?php echo __($current_step,'curriki'); ?> &gt;</strong></button>
                                                    <?php endif;?>
                                                    <?php if ( bp_is_first_group_creation_step() ) : ?>
                                                            <!-- First Step - Create Group -->
                                                            
                                                            <input type="hidden" name="save_fld" value="save" />
                                                            <button onclick="change_tab('settings');" class="resource-button small-button-extended green-button next-step" id="recaptcha-btn"  name="save"><?php echo __('Next Step','curriki'); ?>: <strong><?php echo __('Settings','curriki'); ?> &gt;</strong></button>
                                                            <!--<button onclick="change_tab('settings');" id="group-creation-create" name="save"><?php echo __('Next Step','curriki'); ?>: <strong><?php echo __('Settings','curriki'); ?> &gt;</strong></button>-->
                                                            <button id="cancel-btn" class="resource-button small-button-extended grey-button cancel"><?php echo __('Cancel','curriki'); ?></button>
                                                    <?php endif; ?>
                                                    <?php if ( bp_is_last_group_creation_step() ) : ?>
                                                            <!--<input type="submit" value="<?php //_e( 'Finish', 'buddypress' ); ?>" id="group-creation-finish" name="save" />-->
                                                            <?php if( bp_get_new_group_id() > 0 ) { ?>
                                                            <div class="invite-and-finish">
                                                                <p><?php _e( 'Invite someone to the group who is not yet a Curriki member?', 'invite-anyone' ) ?>                                                                
                                                                    <a href="<?php echo bp_loggedin_user_domain() . BP_INVITE_ANYONE_SLUG . '/invite-new-members/group-invites/' . bp_get_new_group_id() ?>"> <strong><?php _e( 'Send invitations by email & Finish', 'invite-anyone' ) ?></strong> </a>
                                                                </p>
                                                            </div>
                                                            <?php } ?>
                                                            <input type="hidden" name="save_fld" value="<?php echo $current_form_step; ?>" />
                                                            <button id="group-creation-finish" name="save" onclick="change_tab('settings');" class="resource-button small-button-extended green-button next-step"><?php echo __('Finish','buddypress'); ?></button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                    <?php endif;?>

                                    <input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />

                                    <?php do_action( 'bp_after_group_creation_step_buttons' ); do_action( 'bp_directory_groups_content' ); ?>
                            </div><!-- .item-body -->

                            <?php do_action( 'bp_after_create_group' ); ?>
                        </div>
                    </div>
                </div>
            <?php
            global $captcha;
            if($captcha){
            ?>
                <div class="modal fade " id="recaptcha-msg" tabindex="-1" role="dialog" aria-labelledby="recaptcha-msg-label" aria-hidden="true" style="width:333px;overflow: visible;margin-top: 30px;height:150px;margin-left:auto;margin-right:auto;">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                        </div>
                        <div class="modal-body">
                            <div style="text-align: center;">
                            <h4><?php echo __('SECURITY CHECK', 'curriki'); ?></h4>
                            <div id="dialog" class="g-recaptcha" data-sitekey="<?php echo GOOGLE_RECAPTCHA_SITE_KEY; ?>" data-callback="checkRecaptcha"></div>
                            </div>
                        </div>
                      </div>
                    </div>
              </div>
                <script>
                    $(document).ready(function(){
                        jQuery('#recaptcha-msg').modal('show');
                    });
                </script>
            <?php
            }
            ?>
	</form>
    </div>
</div>
  
  <style type="text/css">      
    .directory .item-list-tabs ul li, .internal-page #content ul li
    {
        display: block;
    }
    .bp-template-notice{
        width: 95% !important;
    }
    .qtipCustomClass{
        border-color: #0E9236 !important;
        background-color: #99c736 !important;
    }
    .qtipCustomClass .qtip-content{
        font-size: 12px !important;
        color: #FFF !important;
    }
    .tooltip:hover
    {
        cursor: help !important;
    }    
  </style>
  
  <?php
  wp_enqueue_style('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, false);
  wp_enqueue_script('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true);
  ?>
  <script type="text/javascript">
      function change_tab(next_step){
          next_step = next_step ? next_step : "";
          //$("#create-group-form").submit();
      }
      function checkRecaptcha(){
          // We'll pass this variable to the PHP function validate_recaptcha
            

            // This does the ajax request
            $.ajax({
                url: ajaxurl, // or example_ajax_obj.ajaxurl if using on frontend
                dataType: "json",
                data: {
                    'action': 'validate_recaptcha',
                    'recaptcha_val' : jQuery('#g-recaptcha-response').val()
                },
                success:function(data) {
                    // This outputs the result of the ajax request
                    if(data.success){
                        jQuery('#recaptcha-msg').modal('hide');
                    } 
                },
                error: function(errorThrown){
                }
            });  
//          jQuery('#group-creation-create').trigger('click');
      }
      /*
      function go_back(url){          
          location.href = url;
      }
      */
      $(document).ready(function(){
//          $('#recaptcha-btn').click(()=>{
//              if (jQuery('#group-name').val() != '' && jQuery('#group-desc').val() != '')
//            {
//                  jQuery('#recaptcha-msg').modal('show');
//                  return false;
//            }
//          });
           $('#cancel-btn').click(function(event) {
                window.location.href = "<?php echo get_site_url(); ?>/groups";
                event.preventDefault();  
            });
            
           $('.go_back').click(function(event) {               
                location.href = $(this).attr("link");
                event.preventDefault();
            });
            
            
            jQuery('.tooltip-group-name').qtip({ 
                content: {
                    text: '<?php echo __('You are required to enter Group Title','curriki'); ?>'
                },
                style: { classes: 'qtipCustomClass' }
            });
            
            jQuery('.tooltip-group-description').qtip({
                content: {
                    text: '<?php echo __('You are required to enter Group Description','curriki'); ?>'
                },
                style: { classes: 'qtipCustomClass' }
            });
            
            jQuery('.tooltip-group-educationlevels').qtip({
                content: {
                    text: '<?php echo __('Select Education Level(s)','curriki'); ?>'
                },
                style: { classes: 'qtipCustomClass' }
            });
            
            jQuery(".subject-ul li label").mouseover(function(){                
                jQuery("#subject-areas-of").text( "<?php echo __('of','curriki');?> '" + jQuery(this).text() + "'" );
            });
            
            //jQuery("body.group-create #group-create-tabs .ui-tabs-nav li span").parent().addClass("gray-border");
            
      });
       
  </script>
  
<?php


function cur_groups_creation_tabs()
{
    $bp = buddypress();

	if ( !is_array( $bp->groups->group_creation_steps ) ) {
		return false;
	}

	if ( !bp_get_groups_current_create_step() ) {
		$keys = array_keys( $bp->groups->group_creation_steps );
		$bp->groups->current_create_step = array_shift( $keys );
	}

	$counter = 1;

	foreach ( (array) $bp->groups->group_creation_steps as $slug => $step ) {
		$is_enabled = bp_are_previous_group_creation_steps_complete( $slug ); ?>

		<li<?php if ( bp_get_groups_current_create_step() == $slug ) : ?> class="current"<?php endif; ?>><?php if ( $is_enabled ) : ?><a href="<?php bp_groups_directory_permalink(); ?>create/step/<?php echo $slug ?>/"><?php else: ?><span><?php endif; ?><?php echo $counter ?>. <?php echo __($step['name'],'curriki') ?><?php if ( $is_enabled ) : ?></a><?php else: ?></span><?php endif ?></li><?php
		$counter++;
	}

	unset( $is_enabled );
	
}

gconnect_get_footer();
