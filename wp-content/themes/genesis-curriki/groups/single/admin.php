<?php
$current_language = "eng";
if( defined('ICL_LANGUAGE_CODE') )
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/nprogress.css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.min.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/nprogress.js"></script>

<?php
    wp_register_script( 'ng-ctrlr', get_stylesheet_directory_uri() . '/js/angular_controllers.js?v=1' );
    $translation_array = cur_angular_controllers_translations();
    wp_localize_script( 'ng-ctrlr', 'ml_obj', $translation_array );
    wp_enqueue_script("ng-ctrlr");  
?>

<style type="text/css">
    .internal-page #bpsubnav ul li
    {
        float: left;
    }
    .bp-widget ul li
    {
        float: none !important;        
    }
    
    ul#members-list li
    {
        max-width: none !important;
    }
    .bp-widget ul li h5 .member-name-cls a
    {
        padding: 0px !important;
    }
    .bp-widget ul li img.avatar
    {
        /*
        float: left;
        margin-right: 10px;
        max-width: none;
        */
    }
    
    .omega ul.subject-ul li label
    {        
        display: initial !important;
    }    
    .omega ul.subject-ul li
    {
        display: block !important;        
    }
    
    .omega ul.subjectareas-ul li label
    {             
        border: 1px solid #f1f2f2;
        min-width: 188px !important;        
    }    
    .omega ul.subjectareas-ul li
    {
        float: left !important;
    }
    .info ul li
    {
        display: block !important;
    }
    
</style>

<script type="text/javascript">    
    
    jQuery(document).ready(function(){
        jQuery(".subject-ul li label").mouseover(function(){                
            jQuery("#subject-areas-of").text( "<?php echo __('of','curriki'); ?> '" + jQuery(this).text() + "'" );
        });
    });
    
    
    
    function uncheck_subject_areas($this, sub) 
    {
      if (!jQuery($this).is(':checked')) {
        jQuery('.' + sub).attr('checked', false);
      }
    }
    function check_subject($this, sub)
    {
      if (jQuery($this).is(':checked') && !jQuery('#' + sub).is(':checked')) {
        jQuery('#' + sub).click();
      }
    }
</script>

<?php
    global $bp,$wpdb;    
    $settings_uri = $bp->unfiltered_uri[count($bp->unfiltered_uri)-1];        
    
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
    
    //$subjects = $wpdb->get_results("SELECT * FROM subjects order by displayname", ARRAY_A);
    $q_subjects = cur_subjects_query($current_language);
    $subjects = $wpdb->get_results($q_subjects,ARRAY_A);
    
    //$subjectareas = $wpdb->get_results("SELECT * FROM subjectareas order by subjectid,displayname", ARRAY_A);    
    $q_subjectareas = cur_subjectareas_query($current_language,null);              
    $subjectareas = $wpdb->get_results($q_subjectareas,ARRAY_A);   

    $group_subjectareas = array();
    $group_educationlevels = array();    
    $group_subjectareas = $wpdb->get_results( "SELECT * FROM group_subjectareas LEFT JOIN subjectareas on group_subjectareas.subjectareaid = subjectareas.subjectareaid WHERE groupid= ".  bp_get_group_id(), OBJECT );
    $group_educationlevels = $wpdb->get_results( "SELECT * FROM group_educationlevels WHERE groupid= ".  bp_get_group_id(), OBJECT );    
    
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
  
    $lang_in_slug = "";
    if( defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en')
    {
        $lang_in_slug = '/'.ICL_LANGUAGE_CODE;
    }
?>
<div class="item-list-tabs no-ajax nav-bar-common" id="bpsubnav" role="navigation">
        <!--<ul><?php //bp_group_admin_tabs(); ?></ul>-->
        <ul>
            <li class="<?php echo $settings_uri === "edit-details" ? "current selected" : ""; ?>" id="edit-details-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/admin/edit-details/" id="edit-details"><?php echo __('Details','curriki'); ?></a></li>
            <li class="<?php echo $settings_uri == "group-settings" ? "current selected" : ""; ?>" id="group-settings-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/admin/group-settings/" id="group-settings"><?php echo __('Settings','curriki'); ?></a></li>
            <li class="<?php echo $settings_uri === "group-avatar" ? "current selected" : ""; ?>" id="group-avatar-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/admin/group-avatar/" id="group-avatar"><?php echo __('Photo','curriki'); ?></a></li>
            <li class="<?php echo $settings_uri === "manage-members" ? "current selected" : ""; ?>" id="manage-members-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/admin/manage-members/" id="manage-members"><?php echo __('Members','curriki'); ?></a></li>
            <li class="<?php echo $settings_uri === "forum" ? "current selected" : ""; ?>" id="forum-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/admin/forum/" id="forum"><?php echo __('Forum','curriki'); ?></a></li>
            <li id="forum-groups-li"><a href="<?php echo site_url().$lang_in_slug ; ?>/groups/<?php echo bp_group_slug() ?>/invite-anyone/?manage=1" id="forum"><?php echo __('Invites','curriki'); ?></a></li>
        </ul>
                
	<div class="clear"></div>
</div><!-- .item-list-tabs -->

<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">

<?php
do_action( 'bp_before_group_admin_content' );

if ( bp_is_group_admin_screen( 'edit-details' ) ) :

	do_action( 'bp_before_group_details_admin' );
?>
	<label for="group-name"><?php _e( 'Group Name (required)', 'buddypress' ); ?></label>
	<input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" />

	<label for="group-desc"><?php _e( 'Group Description (required)', 'buddypress' ); ?></label>
	<textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>

        <label for="group-desc"><?php _e( 'Group Detail', 'buddypress' ); ?></label>
        
        <div class = "grid_12 alpha omega" ng-app="ngApp" ng-controller="createResourceCtrl">
            <div class = "grid_9 alpha">

                <div class = "optionset">
                  <div class = "optionset-title"><?php echo __('Subject','curriki'); ?></div>
                  <ul class="subject-ul">
                    <?php
                    foreach ($subjects as $sub) {
                      echo '<li ng-mouseover="subject_hover(' . $sub['subjectid'] . ')" style="max-width:197px;"><label><input name="subject[]" type="checkbox" value="' . $sub['subject'] . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this,\'subjectarea_' . $sub['subjectid'] . '\')" ' . (in_array($sub['subjectid'], $subject_ids) ? 'checked="checked"' : '') . '>' . $sub['displayname'] . '</label></li>';
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
        
	<?php do_action( 'groups_custom_group_fields_editable' ); ?>
	<p>
		<label for="group-notifiy-members"><?php _e( 'Notify group members of changes via email', 'buddypress' ); ?></label>
		<input type="radio" name="group-notify-members" value="1" /> <?php _e( 'Yes', 'buddypress' ); ?>&nbsp;
		<input type="radio" name="group-notify-members" value="0" checked="checked" /> <?php _e( 'No', 'buddypress' ); ?>&nbsp;
	</p>
	<?php do_action( 'bp_after_group_details_admin' ); wp_nonce_field( 'groups_edit_group_details' ); ?>

	<p><input type="submit" value="<?php _e( 'Save Changes', 'buddypress' ); ?>" id="save" name="save" /></p>

<?php
elseif ( bp_is_group_admin_screen( 'group-settings' ) ) :

	do_action( 'bp_before_group_settings_admin' );

	if ( bp_is_active( 'forums' ) && bp_forums_is_installed_correctly() ) :
?>
		<div class="checkbox">
			<label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php bp_group_show_forum_setting(); ?> /> <?php _e( 'Enable discussion forum', 'buddypress' ); ?></label>
		</div>
		<hr />
	<?php endif; ?>

	<h4><?php _e( 'Privacy Options', 'buddypress' ); ?></h4>

	<div class="radio">
		<label>
			<input type="radio" name="group-status" value="public"<?php bp_group_show_status_setting( 'public' ); ?> />
			<strong><?php _e( 'This is a public group', 'buddypress' ); ?></strong>
			<ul>
				<li><?php _e( 'Any site member can join this group.', 'buddypress' ); ?></li>
				<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'buddypress' ); ?></li>
				<li><?php _e( 'Group content and activity will be visible to any site member.', 'buddypress' ); ?></li>
			</ul>
		</label>
		<label>
			<input type="radio" name="group-status" value="private"<?php bp_group_show_status_setting( 'private' ); ?> />
			<strong><?php _e( 'This is a private group', 'buddypress' ); ?></strong>
			<ul>
				<li><?php _e( 'Only users who request membership and are accepted can join the group.', 'buddypress' ); ?></li>
				<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'buddypress' ); ?></li>
				<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'buddypress' ); ?></li>
			</ul>
		</label>
		<label>
			<input type="radio" name="group-status" value="hidden"<?php bp_group_show_status_setting( 'hidden' ); ?> />
			<strong><?php _e( 'This is a hidden group', 'buddypress' ); ?></strong>
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

	<?php do_action( 'bp_after_group_settings_admin' ); ?>

	<p><input type="submit" value="<?php _e( 'Save Changes', 'buddypress' ); ?>" id="save" name="save" /></p>
<?php
	wp_nonce_field( 'groups_edit_group_settings' );

elseif ( bp_is_group_admin_screen( 'group-avatar' ) ) :

	if ( 'upload-image' == bp_get_avatar_admin_step() ) :
?>

			<p><?php _e("Upload an image to use as an avatar for this group. The image will be shown on the main group page, and in search results.", 'buddypress'); ?></p>
                        <div>
				<input type="file" name="file" id="file" />
				<input type="submit" name="upload" id="upload" value="<?php _e( 'Upload Image', 'buddypress' ); ?>" />
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
			</div>

			<?php if ( bp_get_group_has_avatar() ) : ?>
				<p><?php _e( "If you'd like to remove the existing avatar but not upload a new one, please use the delete avatar button.", 'buddypress' ); ?></p>

				<?php bp_button( array( 'id' => 'delete_group_avatar', 'component' => 'groups', 'wrapper_id' => 'delete-group-avatar-button', 'link_class' => 'edit', 'link_href' => bp_get_group_avatar_delete_link(), 'link_title' => __( 'Delete Avatar', 'buddypress' ), 'link_text' => __( 'Delete Avatar', 'buddypress' ) ) ); ?>
<?php
			endif;
			wp_nonce_field( 'bp_avatar_upload' );

	elseif ( 'crop-image' == bp_get_avatar_admin_step() ) :
?>

		<h3><?php _e( 'Crop Avatar', 'buddypress' ); wp_nonce_field( 'bp_avatar_cropstore' ); ?></h3>

		<img src="<?php bp_avatar_to_crop() ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />

		<div id="avatar-crop-pane">
			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
		</div>
		<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Crop Image', 'buddypress' ); ?>" />
		<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />

<?php 
	endif;

elseif ( bp_is_group_admin_screen( 'manage-members' ) ) :

	do_action( 'bp_before_group_manage_members_admin' );

	if ( bp_has_members( '&include='. bp_group_admin_ids() ) ) :
?>

		<div class="bp-widget">
			<h4><?php _e( 'Administrators', 'buddypress' ); ?></h4>
	
			
			<ul id="admins-list" class="item-list single-line">
				
				<?php while ( bp_members() ) : bp_the_member(); ?>
				<li>
					<?php 
                                            //echo bp_core_fetch_avatar( array( 'item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => __( 'Profile picture of %s', 'buddypress' ) ) ); 
                                            echo '<div class="avatar-list-item">'. get_avatar( bp_get_member_user_id() , 20 );
                                        ?>
					<h5>
                                                <span class="member-name-cls">
                                                    <a href="<?php bp_member_permalink(); ?>"> <?php bp_member_name(); ?></a>
                                                </span>
						<span class="small">
							<a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ) ?>"><?php _e( 'Demote to Member', 'buddypress' ); ?></a>
						</span>			
					</h5>		
				</li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php endif; if ( bp_group_has_moderators() ) : ?>
		<div class="bp-widget">
			<h4><?php _e( 'Moderators', 'buddypress' ); ?></h4>
			
			<?php if ( bp_has_members( '&include=' . bp_group_mod_ids() ) ) : ?>
				<ul id="mods-list" class="item-list">
				
					<?php while ( bp_members() ) : bp_the_member(); ?>					
					<li>
						<?php 
                                                    //echo bp_core_fetch_avatar( array( 'item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => __( 'Profile picture of %s', 'buddypress' ) ) );                                                     
                                                    echo '<div class="avatar-list-item">'. get_avatar( bp_get_member_user_id() , 20 );
                                                 ?>
						<h5>
                                                    <span class="member-name-cls">
                                                        <a href="<?php bp_member_permalink(); ?>" class="moderator-name-cls"> <?php bp_member_name(); ?></a>
                                                    </span>
							<span class="small">
								<a href="<?php bp_group_member_promote_admin_link( array( 'user_id' => bp_get_member_user_id() ) ) ?>" class="button confirm mod-promote-to-admin" title="<?php _e( 'Promote to Admin', 'buddypress' ); ?>"><?php _e( 'Promote to Admin', 'buddypress' ); ?></a>
								<a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><?php _e( 'Demote to Member', 'buddypress' ); ?></a>
							</span>		
						</h5>		
					</li>	
					<?php endwhile; ?>			
				
				</ul>
			
			<?php endif; ?>
		</div>
	<?php endif; if ( bp_group_has_members( 'per_page=15&exclude_banned=false' ) ) : ?>

		<div class="bp-widget">
			<h4><?php _e("Members", "buddypress"); ?></h4>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<ul id="members-list" class="item-list single-line">
				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

                                    <li class="<?php bp_group_member_css_class(); ?>">
						<?php 
                                                        //bp_group_member_avatar_mini();                                                         
                                                        echo '<div class="avatar-list-item">'. get_avatar( bp_get_member_user_id() , 20 );
                                                 ?>

						<h5>
                                                    <span class="member-name-cls">
							<?php bp_group_member_link(); ?>
                                                    </span>
							<?php if ( bp_get_group_member_is_banned() ) _e( '(banned)', 'buddypress'); ?>                                                    
							<span class="small">

							<?php if ( bp_get_group_member_is_banned() ) : ?>
								<a href="<?php bp_group_member_unban_link() ?>" class="button confirm member-unban" title="<?php _e( 'Unban this member', 'buddypress' ); ?>"><?php _e( 'Remove Ban', 'buddypress' ); ?></a>
							<?php else : ?>
								<a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban" title="<?php _e( 'Kick and ban this member', 'buddypress' ); ?>"><?php _e( 'Kick &amp; Ban', 'buddypress' ); ?></a>
								<a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod" title="<?php _e( 'Promote to Mod', 'buddypress' ); ?>"><?php _e( 'Promote to Mod', 'buddypress' ); ?></a>
								<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin" title="<?php _e( 'Promote to Admin', 'buddypress' ); ?>"><?php _e( 'Promote to Admin', 'buddypress' ); ?></a>
							<?php endif; ?>
								<a href="<?php bp_group_member_remove_link(); ?>" class="button confirm" title="<?php _e( 'Remove this member', 'buddypress' ); ?>"><?php _e( 'Remove from group', 'buddypress' ); ?></a>

								<?php do_action( 'bp_group_manage_members_admin_item' ); ?>

							</span>
						</h5>
					</li>

				<?php endwhile; ?>
			</ul>
		</div>
	<?php else: ?>
		<div id="message" class="info">
			<p><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
		</div>
	<?php endif; do_action( 'bp_after_group_manage_members_admin' ); ?>

<?php elseif ( bp_is_group_admin_screen( 'membership-requests' ) ) : ?>

	<?php do_action( 'bp_before_group_membership_requests_admin' ); ?>

	<?php if ( bp_group_has_membership_requests() ) : ?>

		<ul id="request-list" class="item-list">
		<?php while ( bp_group_membership_requests() ) : bp_group_the_membership_request(); ?>

			<li>
				<?php bp_group_request_user_avatar_thumb(); ?>
				<h4><?php bp_group_request_user_link() ?> <span class="comments"><?php bp_group_request_comment(); ?></span></h4>
				<span class="activity"><?php bp_group_request_time_since_requested(); ?></span>

				<?php do_action( 'bp_group_membership_requests_admin_item' ); ?>

				<div class="action">
					<?php bp_button( array( 'id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_href' => bp_get_group_request_accept_link(), 'link_title' => __( 'Accept', 'buddypress' ), 'link_text' => __( 'Accept', 'buddypress' ) ) ); ?>
					<?php bp_button( array( 'id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_href' => bp_get_group_request_reject_link(), 'link_title' => __( 'Reject', 'buddypress' ), 'link_text' => __( 'Reject', 'buddypress' ) ) ); ?>
					<?php do_action( 'bp_group_membership_requests_admin_item_action' ); ?>
				</div>
			</li>

		<?php endwhile; ?>
		</ul>

	<?php else: ?>
		<div id="message" class="info">
			<p><?php _e( 'There are no pending membership requests.', 'buddypress' ); ?></p>
		</div>
	<?php endif; do_action( 'bp_after_group_membership_requests_admin' ); ?>

<?php elseif ( bp_is_group_admin_screen( 'delete-group' ) ) : ?>

	<?php do_action( 'bp_before_group_delete_admin' ); ?>

	<div id="message" class="info">
		<p><?php _e( 'WARNING: Deleting this group will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'buddypress' ); ?></p>
	</div>
	<label><input type="checkbox" name="delete-group-understand" id="delete-group-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-group-button').disabled = ''; } else { document.getElementById('delete-group-button').disabled = 'disabled'; }" /> <?php _e( 'I understand the consequences of deleting this group.', 'buddypress' ); ?></label>

	<?php do_action( 'bp_after_group_delete_admin' ); wp_nonce_field( 'groups_delete_group' ); ?>

	<div class="submit">
		<input type="submit" disabled="disabled" value="<?php _e( 'Delete Group', 'buddypress' ); ?>" id="delete-group-button" name="delete-group-button" />
	</div>

<?php endif; ?>

	<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />

<?php
do_action( 'groups_custom_edit_steps' );
