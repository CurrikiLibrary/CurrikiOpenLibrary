<?php do_action( 'bp_before_group_header' ); ?>

<?php

global $bp; 

$current_language = "eng";
if( defined('ICL_LANGUAGE_CODE') )
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
    
$invite_display = true;
                        
//admins , mods , members                        
$invite_status = groups_get_groupmeta( bp_get_group_id() , 'invite_status' );
$g_u_members = groups_get_group_members(bp_get_group_id());
$g_u_admins = groups_get_group_admins(bp_get_group_id());

//=== filter loggedin user from group member ===
$current_user_is_member = null;
foreach($g_u_members["members"] as $member) {
    if (get_current_user_id() == $member->ID) {
        $current_user_is_member = $member;        
        break;
    }
}

//==== Case to hide invtie button ====
if(isset($current_user_is_member) && $invite_status == "admins")
{
    $invite_display = false;
}


$group_meta = groups_get_groupmeta( bp_get_group_id());
if(get_current_user_id() > 0 && !isset($group_meta["members_visited"]) )
{    
    groups_update_groupmeta(bp_get_group_id(), "members_visited", "");    
}



?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".join-group-btn a").click(function(){
            window.location = jQuery(this).attr("href");
        });
    });
</script>
<div class="group-header page-header">
	<div class="wrap container_12">
		<div class="group-join page-join grid_2">
			<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">
				<?php bp_group_avatar(); ?>
			</a>
			<div id="item-buttons">
				<?php do_action( 'bp_group_header_actions' ); ?>
			</div><!-- #item-buttons -->                        
                        <?php                                   
                            if(is_user_logged_in())
                            {
                                $sent_invite_button_ignores = array('invite-anyone');                                
                                
                                if(in_array("invite-anyone", $bp->unfiltered_uri) && $_GET["manage"] == 1)
                                {
                                   ?>
                                        <a class="send-invite-btn green-button" href="<?php echo get_site_url() . "/groups/" .bp_get_current_group_slug() ?>/invite-anyone/"><?php echo __('Send Invite','curriki'); ?></a>                                   <?php
                                }
                                if(!in_array($bp->current_action, $sent_invite_button_ignores) && $invite_display == true)
                                {
                        ?>          
                        
                                    <?php if( bp_group_is_admin() || groups_is_user_member( get_current_user_id() , bp_get_group_id() ) ) { ?>
<!--                                        <div class="generic-button sent-invite-btn">
                                            <a class="green-button" href="<?php echo get_site_url() . "/groups/" .bp_get_current_group_slug() ?>/invite-anyone/">Send Invite</a>
                                        </div>-->
                                            <a class="send-invite-btn green-button" href="<?php echo get_site_url() . "/groups/" .bp_get_current_group_slug() ?>/invite-anyone/"><?php echo __('Send Invite','curriki'); ?></a>
                                            <?php
                                            
                                                if(!bp_group_is_admin() & (bp_group_is_mod() || bp_group_is_member()))
                                                {
                                                    $leave_link = bp_get_group_leave_confirm_link( groups_get_group(array('group_id'=>  bp_get_group_id() ) ) );
                                                    echo '<a href="'.$leave_link.'" class="green-button">'.__("Leave Group","buddypress").'</a>';
                                                }
                                            ?>
                                    <?php }  else { ?>
                                                <?php if($bp->groups->current_group->user_has_access == false && $bp->groups->current_group->is_pending == 1){ ?>                                                        
                                                        <div class="bp-template-notice updated" id="message" style="width: 100% !important;">
                                                            <p style="font-size: 14px;">Membership Requested!</p>
                                                        </div>
                                                <?php }else{ ?>
                                            <div class="join-group-btn"><?php echo bp_get_group_join_button( groups_get_group(array('group_id'=>  bp_get_group_id() ) ) ); ?></div> 
                                                <?php } ?>
                                    <?php } ?>                        
                                    
                        <?php   }else{ ?> 
                                    <?php 
                                    
                                            
                                            $group_rcd = groups_get_group(array('group_id'=>  bp_get_group_id() ) );                            
                                            if($group_rcd->status == "public")
                                            {
                                                
                                                
                                                if(!bp_group_is_admin() & (bp_group_is_mod() || bp_group_is_member()))
                                                {
                                                    $leave_link = bp_get_group_leave_confirm_link( groups_get_group(array('group_id'=>  bp_get_group_id() ) ) );
                                                    echo '<a href="'.$leave_link.'" class="green-button">'.__('Leave Group','buddypress').'</a>';
                                                }elseif(bp_group_is_admin())
                                                {
                                                    
                                                }else{                                                    
                                                    echo '<div class="join-group-btn">'. bp_get_group_join_button( groups_get_group(array('group_id'=>  bp_get_group_id() ) ) ) . '</div>';
                                                }                                                 
                                            }
                                    ?>
                        <?php   }                                                                    
                            }                            
                        ?>                        
			<!--<img alt="group-name" src="http://curriki.obmdev.com/wp-content/themes/genesis-curriki/images/group-icon-sample.png" class="circle aligncenter">
			<button class="green-button">Join Group</button>-->
		</div>
		<div class="group-info page-info grid_10">
                    <?php 
                        $manage_group = BP_Curr_Manage();                        
                        $manage_group->group_id = bp_get_group_id();
                        $subjects = $manage_group->get_group_subjects($current_language);
                        $education_levels = $manage_group->get_group_education_levels($current_language);
                        $languages = $manage_group->get_group_languages($current_language);
                        $group = groups_get_group( array( 'group_id' => bp_get_group_id() ) );                                                
                        
                        $link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    ?>
			<h3 class="group-title page-title"><?php bp_group_name(); ?></h3>
			<div class="group-link page-link"><?php echo __('Website Address','curriki'); ?>: <a href="<?php echo $link; ?>"><?php echo $link; ?></a></div> 
			<div id="group-info-accordion" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
				<h4 class="group-more-info fa ui-accordion-header ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-id-5" aria-controls="ui-id-6" aria-selected="false" aria-expanded="false" tabindex="0">
					<?php echo __("More Information","curriki"); ?>
				</h4>
				<div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" style="display: none; height: 257px;" id="ui-id-6" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="true">
					<?php bp_group_description(); ?>
					<ul class="info">
						<div class="grid_3">
							<li>
                                                            <?php echo __("Subjects","curriki"); ?>:
                                                            <ul id="subject_list_container">
                                                                <?php if($subjects != null) {?>
                                                                    <?php foreach($subjects as $subject) {?>
                                                                        <li><?php echo $subject->subject_displayname; ?> > <?php echo $subject->subjectarea_displayname; ?></li>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </ul>                                                                                                                          
                                                        </li>
						</div>
						<div class="grid_3">
							<li><?php echo __("Education Levels","curriki"); ?>:
                                                            <ul id="educationlevel_list_container">
                                                             <?php if($education_levels != null) {?>
                                                                <?php foreach($education_levels as $education_level) { ?>
                                                                    <li><?php echo $education_level->displayname; ?></li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </ul>
                                                        </li>
						</div>
						<div class="grid_3">
							<li><?php echo __("Language","curriki"); ?>:
                                                            <ul>
                                                             <?php if($languages != null) {?>
                                                                <?php foreach($languages as $language) { ?>
                                                                    <li><?php echo $language->displayname; ?></li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </ul>
                                                        </li>
						</div>
						<div class="grid_3">
							<li>
                                                            <?php echo __("Created","curriki"); ?>:
                                                            <ul>
                                                                <li><?php echo date('M d, Y g:i a', strtotime($group->date_created) ); ?></li>
                                                            </ul>                                                                
                                                        </li>                                                        
                                                        <li>
                                                            <?php echo __("Last Activity","curriki"); ?>:
                                                            <ul>
                                                                <li><?php echo bp_get_group_last_active(); ?></li>
                                                            </ul>                                                                
                                                        </li>
						</div>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
     
    jQuery( document ).ready(function() {
    
       <?php
       if($current_language === "eng")
       {
       ?>
            jQuery("#lang_sel_list ul li.icl-en a").removeAttr("href");
            jQuery("#lang_sel_list ul li.icl-es a").removeAttr("href");                
            jQuery("#lang_sel_list ul li.icl-en a").attr("href","<?php echo $link ?>");

            <?php
                $link_arr = explode("/", $link);                            
                $lang_code = array("es");
                array_splice( $link_arr, 3, 0, $lang_code );            
                $link = implode("/", $link_arr);            
            ?>
            jQuery("#lang_sel_list ul li.icl-es a").attr("href","<?php echo $link ?>");
       <?php
       }  else {           
       ?>
               <?php
                    $$link_main = $link;
                    $link_arr = explode("/", $link);                            
                    $lang_code = array("en");
                    array_splice( $link_arr, 3, 0, $lang_code );
                  
                    unset($link_arr[4]);
                    $link_arr = array_values($link_arr);
                    
                    $link = implode("/", $link_arr);            
                ?>
                jQuery("#lang_sel_list ul li.icl-en a").attr("href","<?php echo $link ?>");

                <?php
                    
                    $link_arr = explode("/", $link);                            
                    unset($link_arr[3]);
                    $link_arr = array_values($link_arr);
                    $lang_code = array("es");
                    array_splice( $link_arr, 3, 0, $lang_code );            
                                      
                    $link = implode("/", $link_arr);            
                ?>
                jQuery("#lang_sel_list ul li.icl-es a").attr("href","<?php echo $link ?>");

       <?php
       }
       ?>
        
    });
    
</script>
 
<?php 
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );