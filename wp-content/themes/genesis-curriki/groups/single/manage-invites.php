<style type="text/css">
    .inv_wrapper .submit
    {
        margin-right: 160px !important;
    }
    .inv_wrapper
    {
        width: 100% !important;                
    }
    .activity-para
    {
        max-width: 220px !important;
        margin-bottom: 5px !important;
        margin-left:5px;
    }
    .activity-thumb
    {
        padding-top: 7px !important;
        border: 2px solid #d6d6d6;
        max-width: 235px !important;
    }    
    .activity-thumb img.avatar
    {
        margin-left: 5px !important;
    }
    .activity-thumb a.action
    {
        display: inline !important;
    }
    .activity-thumb div.action
    {        
        min-height: 100% !important;
        width: 100% !important;
        text-align: right !important;
        padding-right: 10px !important;
    }
    
    .thumb-wrapper
    {        
        margin-bottom: 20px !important;
        min-height: 200px;
    }
    
    div.check-box
    {
       text-align: center;
       width: 100%; 
    }
    #invite-anyone-invite-list li:nth-child(1)
    {
        margin-left: 0px !important;
    }
    .bold-txt
    {
        font-weight: bold !important;
    }

    #invite-anyone-invite-list  li {
        float: left !important;        
    }
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
    window.selected_users = [];
    window.selected_emails = [];
    jQuery(document).ready(function(){
        j = jQuery;
        j("#invite-anyone-invite-list").on( 'click', 'li a.remove', function(e) {
            
                e.preventDefault();                
                var friend_id = j(this).prop('id');

                friend_id = friend_id.split('-');
                friend_id = friend_id[1];

                j.post( ajaxurl, {
                        action: 'invite_anyone_groups_invite_user',
                        'friend_action': 'uninvite',
                        'cookie': encodeURIComponent(document.cookie),
                        '_wpnonce': j("input#_wpnonce_invite_uninvite_user").val(),
                        'friend_id': friend_id,
                        'group_id': j("input#group_id").val()
                },
                function(response)
                {
                        j('#invite-anyone-invite-list li#uid-' + friend_id).remove();
                        j('#invite-anyone-member-list input#f-' + friend_id).prop('checked', false);
                        ia_refresh_submit_button_state();
                });

                return false;
        });
        
        
        jQuery("#check-box-email-check-all").change(function(){
            var check_box_email_check_all = jQuery(this);
            
            jQuery(".check-box-email").each(function(i,obj){
                if(check_box_email_check_all.prop("checked") === true)
                {
                    if( jQuery(obj).prop("checked") === false)
                    {
                        jQuery(obj).trigger("click");
                    }
                }else if(check_box_email_check_all.prop("checked") === false)
                {
                    if( jQuery(obj).prop("checked") === true)
                    {
                        jQuery(obj).trigger("click");
                    }
                }
            });                        
        });
        
        jQuery(".check-box-fld").change(function(){
            
            if(jQuery(this).prop( "checked" ) === true)
            {
                window.selected_users.push( jQuery(this).val() );
            }else if(jQuery(this).prop( "checked" ) === false)
            {
                var index = window.selected_users.indexOf( jQuery(this).val() );
                window.selected_users.splice(index, 1);
            }
        });
        
        jQuery(".check-box-email").change(function(){
            
            if(jQuery(this).prop( "checked" ) === true)
            {
                window.selected_emails.push( jQuery(this).val() );
            }else if(jQuery(this).prop( "checked" ) === false)
            {
                var index = window.selected_emails.indexOf( jQuery(this).val() );
                window.selected_emails.splice(index, 1);                
            }
            
            console.log("emailllll = ",window.selected_emails);
            
        });
        
        
        jQuery("#resend-email-invites").click(function(){
            
            if(window.selected_emails.length === 0)
            {
                alert("Please Select Email(s)!");
            }else{
                jQuery.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: { action: "cur_resend_email_invites", selected_emails: window.selected_emails , group_id:jQuery("#group_id").val() }
                  }).done(function( response ) {
                      window.selected_emails = [];
                      jQuery("#msg-para").text("Group invites sent.");                      
                      jQuery("#check-box-email-check-all").prop("checked" , false);
                      jQuery(".check-box-email").prop("checked" , false);                      
                      jQuery("#message").show().delay(5000).queue(function(n) {
                        jQuery(this).hide(); n();
                      });
                  });
            }
            
        });
        
        jQuery("#resend").click(function(){
            
            if(window.selected_users.length === 0)
            {
                alert("Please Select User(s) !");
            }else{
                
                jQuery.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: { action: "cur_resend_invites", selected_users: window.selected_users }
                  }).done(function( response ) {
                      window.selected_users = [];
                      jQuery("#msg-para").text("Group invites sent.");                      
                      jQuery(".check-box-fld").prop("checked" , false);                      
                      jQuery("#message").show().delay(5000).queue(function(n) {
                        jQuery(this).hide(); n();
                      });
                  });                
            }
        });
        
        jQuery("a.inv-nav").click(function(e){
            
            jQuery("a.inv-nav").removeClass("bold-txt");
            
            if(jQuery(this).hasClass("member-inv"))
            {
                jQuery(".member-inv-wrapper").show();
                jQuery(".email-inv-wrapper").hide();
                if(!jQuery(this).hasClass("bold-txt"))
                {
                    jQuery(this).addClass("bold-txt")                    
                }
                
            }else if(jQuery(this).hasClass("email-inv"))
            {
                jQuery(".email-inv-wrapper").show();
                jQuery(".member-inv-wrapper").hide();                
                
                if(!jQuery(this).hasClass("bold-txt"))
                {
                    jQuery(this).addClass("bold-txt")                    
                }
            }
        });
    });
</script>

<div class="item-list-tabs no-ajax nav-bar-common" id="bpsubnav" role="navigation">
        <!--<ul><?php //bp_group_admin_tabs(); ?></ul>-->
        <ul>
            <li class="<?php echo $settings_uri === "edit-details" ? "current selected" : ""; ?>" id="edit-details-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/admin/edit-details/" id="edit-details">Details</a></li>
            <li class="<?php echo $settings_uri == "group-settings" ? "current selected" : ""; ?>" id="group-settings-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/admin/group-settings/" id="group-settings">Settings</a></li>
            <li class="<?php echo $settings_uri === "group-avatar" ? "current selected" : ""; ?>" id="group-avatar-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/admin/group-avatar/" id="group-avatar">Photo</a></li>
            <li class="<?php echo $settings_uri === "manage-members" ? "current selected" : ""; ?>" id="manage-members-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/admin/manage-members/" id="manage-members">Members</a></li>
            <li class="<?php echo $settings_uri === "forum" ? "current selected" : ""; ?>" id="forum-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/admin/forum/" id="forum">Forum</a></li>            
            <li class="current selected" id="forum-groups-li"><a href="<?php echo site_url() ; ?>/groups/<?php echo bp_group_slug() ?>/invite-anyone/?manage=1" id="forum">Invites</a></li>
        </ul>
                
	<div class="clear"></div>
</div><!-- .item-list-tabs -->

<br />
<div class="inv_wrapper">
    
    <div class="bp-template-notice updated" id="message" style="display: none;width: 100% !important;">
        <p id="msg-para">Group invites sent.</p>
    </div>
    
    <p>
        <a href="#group-info-accordion" class="inv-nav member-inv bold-txt"><?php echo __('Manage Member Invitations','curriki'); ?></a> &nbsp;|&nbsp;<a href="#group-info-accordion" class="inv-nav email-inv"> <?php _e( 'Manage Email Invitations', 'invite-anyone' ) ?> </a>
    </p>    
    
    <div class="member-inv-wrapper">
        <div class="thumb-wrapper">
            <h4><?php _e( 'Sent Member(s) Invites', 'invite-anyone' ); ?></h4>
            
            <input type="hidden" id="group_id" value="<?php echo bp_get_group_id(); ?>" />
            <?php do_action( 'bp_before_group_send_invites_list' ) ?>
            
            <?php if ( bp_group_has_invites() ) : ?>
                <p id="sent-invites-intro">Select user(s) to send invitation.</p>
                <ul id="invite-anyone-invite-list" class="item-list">                
                    <?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

                            <li id="<?php bp_group_invite_item_id() ?>" class="activity-thumb">
                                    <?php
                                        global $invites_template;
                                    ?>
                                    <div class="check-box">
                                        <input type="checkbox" class="check-box-fld" value="<?php echo $invites_template->invite->user->id; ?>" />
                                    </div>
                                    <?php bp_group_invite_user_avatar() ?>
                                    <h4><?php bp_group_invite_user_link() ?></h4>
                                    <p class="activity-para"><?php bp_group_invite_user_last_active() ?></p>

                                    <?php do_action( 'bp_group_send_invites_item' ) ?>

                                    <div class="action">
                                            <a class="remove" href="<?php bp_group_invite_user_remove_invite_url() ?>" id="<?php bp_group_invite_item_id() ?>"><?php _e( 'Remove Invite', 'buddypress' ) ?></a>
                                            <?php do_action( 'bp_group_send_invites_item_action' ) ?>
                                    </div>                                
                            </li>

                    <?php endwhile; ?>            
                </ul>
            <?php else: ?>
                <br />
                <p><strong><?php echo __('No sent invitation(s) found','curriki'); ?></strong></p>
            <?php endif; ?>
            
            
            <?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ) ?>
            <?php do_action( 'bp_after_group_send_invites_list' ) ?>
        </div>
        <div class="submit">
            <input type="submit" value="Resend Invites" id="resend" name="submit" />
        </div>
    </div>        
    
    <div class="email-inv-wrapper" style="display: none;">
        <?php
        function cur_invite_anyone_screen_two_content() {
		global $bp;

		// Load the pagination helper
		if ( !class_exists( 'BBG_CPT_Pag' ) )
			require_once( BP_INVITE_ANYONE_DIR . 'lib/bbg-cpt-pag.php' );
		$pagination = new BBG_CPT_Pag;

		$inviter_id = bp_loggedin_user_id();

		if ( isset( $_GET['sort_by'] ) )
			$sort_by = $_GET['sort_by'];
		else
			$sort_by = 'date_invited';

		if ( isset( $_GET['order'] ) )
			$order = $_GET['order'];
		else
			$order = 'DESC';

		$base_url = $bp->displayed_user->domain . $bp->invite_anyone->slug . '/sent-invites/';

		?>

		<h4><?php _e( 'Sent Email Invites', 'invite-anyone' ); ?></h4>

		<?php $invites = invite_anyone_get_invitations_by_inviter_id( bp_loggedin_user_id(), $sort_by, $order, false, false ) ?>

		<?php //$pagination->setup_query( $invites ) ?>
               
		<?php if ( $invites->have_posts() ) : ?>
			<p id="sent-invites-intro"><?php _e( 'You have sent invitations to the following people.', 'invite-anyone' ) ?></p>
 
			<table class="invite-anyone-sent-invites zebra"
			summary="<?php _e( 'This table displays a list of all your sent invites.
			Invites that have been accepted are highlighted in the listings.
			You may clear any individual invites, all accepted invites or all of the invites from the list.', 'invite-anyone' ) ?>">
				<thead>
					<tr>
                                            <th scope="col" class="col-delete-invite">
                                                <input type="checkbox" id="check-box-email-check-all" /> <a href="#"><?php echo __('Check All','curriki'); ?></a>
                                            </th>
					  <th scope="col" class="col-email<?php if ( $sort_by == 'email' ) : ?> sort-by-me<?php endif ?>"><a class="<?php echo $order ?>" title="Sort column order <?php echo $order ?>" href="<?php echo $base_url ?>?sort_by=email&amp;order=<?php if ( $sort_by == 'email' && $order == 'ASC' ) : ?>DESC<?php else : ?>ASC<?php endif; ?>"><?php _e( 'Invited email address', 'invite-anyone' ) ?></a></th>
					  <!--<th scope="col" class="col-group-invitations"><?php _e( 'Group invitations', 'invite-anyone' ) ?></th>-->
					  <th scope="col" class="col-date-invited<?php if ( $sort_by == 'date_invited' ) : ?> sort-by-me<?php endif ?>"><a class="<?php echo $order ?>" title="Sort column order <?php echo $order ?>" href="<?php echo $base_url ?>?sort_by=date_invited&amp;order=<?php if ( $sort_by == 'date_invited' && $order == 'DESC' ) : ?>ASC<?php else : ?>DESC<?php endif; ?>"><?php _e( 'Sent', 'invite-anyone' ) ?></a></th>
					</tr>
				</thead>

                              
				<tbody>
				<?php while ( $invites->have_posts() ) : $invites->the_post() ?>

				<?php
					$emails = wp_get_post_terms( get_the_ID(), invite_anyone_get_invitee_tax_name() );

					// Should never happen, but was messing up my test env
					if ( empty( $emails ) ) {
						continue;
					}

					// Before storing taxonomy terms in the db, we replaced "+" with ".PLUSSIGN.", so we need to reverse that before displaying the email address.
					$email	= str_replace( '.PLUSSIGN.', '+', $emails[0]->name );

					$post_id = get_the_ID();

					$query_string = preg_replace( "|clear=[0-9]+|", '', $_SERVER['QUERY_STRING'] );

					$clear_url = ( $query_string ) ? $base_url . '?' . $query_string . '&clear=' . $post_id : $base_url . '?clear=' . $post_id;
					$clear_url = wp_nonce_url( $clear_url, 'invite_anyone_clear' );
					$clear_link = '<a class="clear-entry confirm" title="' . __( 'Clear this invitation', 'invite-anyone' ) . '" href="' . $clear_url . '">x<span></span></a>';

					$groups = wp_get_post_terms( get_the_ID(), invite_anyone_get_invited_groups_tax_name() );
                                        
                                        $groups_ids_arr = array();
					if ( !empty( $groups ) ) {
						$group_names = '<ul>';
						foreach( $groups as $group_term ) {
							$group = new BP_Groups_Group( $group_term->name );
                                                        $groups_ids_arr[] = $group->id;
							$group_names .= '<li>' . bp_get_group_name( $group ) . '</li>';
						}
						$group_names .= '</ul>';
					} else {
						$group_names = '-';
					}

					global $post;

					$date_invited = invite_anyone_format_date( $post->post_date );

					$accepted = get_post_meta( get_the_ID(), 'bp_ia_accepted', true );

					if ( $accepted ):
						$date_joined = invite_anyone_format_date( $accepted );
						$accepted = true;
					else:
						$date_joined = '-';
						$accepted = false;
					endif;

					?>
                                        
                                        <?php if(in_array(bp_get_current_group_id(), $groups_ids_arr)) { ?>
                                            <tr <?php if($accepted){ ?> class="accepted" <?php } ?>>
                                                    <td class="col-delete-invite">
                                                        <?php //echo $clear_link ?>
                                                        <input type="checkbox" class="check-box-email" value="<?php echo esc_html( $email ) ?>" />
                                                    </td>
                                                    <td class="col-email"><?php echo esc_html( $email ) ?></td>
                                                    <!--
                                                    <td class="col-group-invitations">
                                                        <?php //echo $group_names ?>                                                       
                                                    </td>
                                                    -->
                                                    <td class="col-date-invited"><?php echo $date_invited ?></td>
                                                    <td class="date-joined col-date-joined"><span></span><?php echo $date_joined ?></td>                                                    
                                            </tr>
                                        <?php } ?>
				<?php endwhile ?>
                                            
                                            <?php                                                    
                                                    if( !in_array(bp_get_current_group_id(), $groups_ids_arr) && count($groups_ids_arr) == 0 )
                                                    {
                                            ?>
                                                        <tr>
                                                            <td colspan="3"> 
                                                                <div style="text-align: center;"> <strong><?php echo __('No sent invitation(s) found','curriki'); ?></strong> </div>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                             ?>
			 </tbody>
			</table>
                                                 
                        <!--
			<div class="ia-pagination">
				<div class="currently-viewing">
					<?php //$pagination->currently_viewing_text() ?>
				</div>

				<div class="pag-links">
					<?php //$pagination->paginate_links() ?>
				</div>
			</div>
                        -->

		<?php else : ?>                            
                        <p id="sent-invites-intro"> <strong><?php _e( "No sent invitation(s) found.", 'invite-anyone' ) ?></strong> </p>

		<?php endif; ?>
	<?php
	}
        cur_invite_anyone_screen_two_content();
        ?>
        <div class="submit">
            <input type="submit" value="<?php echo __('Resend Invites','curriki'); ?>" id="resend-email-invites" name="submit" />
        </div>
    </div>
    
</div>

