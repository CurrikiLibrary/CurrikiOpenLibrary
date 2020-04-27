<?php

remove_action( 'bp_member_header_actions', 'gconnect_member_header' );

add_filter( 'body_class', 'cur_class_names' );
function cur_class_names( $classes ) {
	
	global $post, $bp;

	if ( $post->post_name == "dashboard"  ) {
		$classes[] = 'backend user-dashboard';
		
	}

	// print_r ($bp); exit;

	if ( $bp->current_component == "groups" ) {
		
		// page page-id-46 page-template page-template-page-group page-template-page-group-php header-image full-width-content backend group-page js

		$classes[] = 'group-template page-template page-template-page-group page-template-page-group-php header-image full-width-content';

		$counter = 0;
		foreach ( $classes as $class ) {
			if ( $class == "single-item" ) { unset($classes[$counter]); }
			if ( $class == "home" ) { unset($classes[$counter]); }
			if ( $class == "groups" ) { unset($classes[$counter]); }

			$classes[$counter] = str_replace('members', 'members-bp', $class);
			if ( $class == "members" ) { unset($classes[$counter]); }

			$counter++;
		}
	
	}

	if ( bp_is_member() ) {

		remove_filter( 'genesis_structural_wrap-site-inner', 'curriki_filter_site_inner_structural_wrap', 15, 2);

	}

	// echo 'test';

	// print_r ($classes); exit;
	

	// if ( $post->post_name == "guides" || is_post_type_archive() ) {
	// 	$classes[] = 'page-template-page-viewall-training-php';
	// 	$classes[] = 'header-full-width full-width-content';
	// }


	// if ( bp_current_component() == "members" ) {
	// 	$classes[] = 'page-template-page-viewall-training-php';
	// 	$classes[] = 'header-full-width full-width-content';
	// }

	// if ( $post->post_type == "cp_guide_cpt" ) {
	// 	$classes[] = 'page-template-page-guide-php';
	// }

	// if ( is_archive() ) {
	// 	$classes[] = 'page-template-page_blog-php';
	// }

	// if ( !is_user_logged_in() ) {
	// 	$classes[] = 'not-logged-in';	
	// }
	
	// $classes[] = 'header-image';
	
	return $classes;
}



add_filter ('bp_get_add_friend_button', 'cur_bp_get_add_friend_button', 10, 1);
function cur_bp_get_add_friend_button ( $html ) {
	return; 
	// $html = str_replace ( 'is_friend', 'is_friend green-button ', $html);
	// return $html;
}

add_filter ('bp_get_remove_friend_button', 'cur_bp_get_remove_friend_button', 10, 1);
function cur_bp_get_remove_friend_button ( $html ) {
	return; 
	// $html = str_replace ( 'class="', 'class="green-button ', $html);
	// return $html;
}

add_filter ('bp_follow_get_add_follow_button', 'cur_bp_follow_get_add_follow_button', 10, 1);
function cur_bp_follow_get_add_follow_button ( $html ) {
	// print_r ($html); exit;
	$html['link_class'] = $html['link_class'] . ' green-button';
	return $html;
}

add_filter ('bp_get_send_public_message_button', 'cur_bp_get_send_public_message_button', 10, 1);
function cur_bp_get_send_public_message_button ( $html ) {
	$html = str_replace ( 'activity-button', 'activity-button green-button', $html);
	return $html;
}

add_filter ('bp_get_send_private_message_link', 'cur_bp_get_send_private_message_link', 10, 1);
function cur_bp_get_send_private_message_link ( $html ) {
	$html = str_replace ( 'class="', 'class="green-button ', $html);
	return $html;
}


add_filter ('bp_get_loggedin_user_avatar', 'cur_bp_get_loggedin_user_avatar', 10, 1);
function cur_bp_get_loggedin_user_avatar ( $html ) {
	$html = str_replace ( 'class="', 'class="circle ', $html);
	return $html;
}

add_filter ('bp_get_displayed_user_avatar', 'cur_bp_get_displayed_user_avatar', 10, 1);
function cur_bp_get_displayed_user_avatar ( $html ) {
	$html = str_replace ( 'class="avatar', 'class="circle aligncenter', $html);
	return $html;
}

add_filter ('bp_get_group_avatar', 'cur_bp_get_group_avatar', 10, 1);
function cur_bp_get_group_avatar ( $html ) {
	$html = str_replace ( 'class="avatar', 'class="circle aligncenter', $html);
	return $html;
}

add_filter ('bp_get_group_join_button', 'cur_bp_get_group_join_button', 10, 1);
function cur_bp_get_group_join_button ( $html ) {
	$html = str_replace ( 'group-button', 'group-button green-button', $html);
	return $html;
}

add_filter ('bp_get_activity_css_class', 'cur_bp_get_activity_css_class', 10, 1);
function cur_bp_get_activity_css_class ( $html ) {
	$html = str_replace ( 'groups', 'groups-bp', $html);
	return $html;
}



function cur_get_user_joined ( $user_id ) {
	global $wpdb;

	$sql = 'SELECT user_registered FROM cur_users WHERE ID = ' . $user_id;

	$result = $wpdb->get_var( $sql );

	return $result;
}

function cur_get_user_lang ( $user_id ) {
	global $wpdb;

	// $user_id = 62184;

	$sql = "select lang.displayname AS displayname, lang.language AS language
			from languages lang
			inner join users on lang.language = users.language
			where users.userid = ".$user_id."
			order by lang.displayname;";

	$result = $wpdb->get_results( $sql );

	return $result;
}

function cur_get_user_subjectareas ( $user_id , $current_language=null ) {
	global $wpdb;
	/*$sql = "select concat(s.displayname, ' > ', sa.displayname) AS item
			from user_subjectareas us
			inner join subjectareas sa on us.subjectareaid = sa.subjectareaid
			inner join subjects s on sa.subjectid = s.subjectid
			where us.userid = ".$user_id."
			order by s.displayname, sa.displayname;";*/
	$sql = "select distinct concat(sml.displayname, ' > ', saml.displayname) AS item
			from user_subjectareas us
			inner join subjectareas sa on us.subjectareaid = sa.subjectareaid
			inner join subjectareas_ml saml on sa.subjectareaid = saml.subjectareaid
			inner join subjects s on sa.subjectid = s.subjectid
			inner join subjects_ml sml on s.subjectid = sml.subjectid
                            where us.userid = ".$user_id."
                            AND saml.language = '$current_language'
                            AND sml.language = '$current_language'
			order by s.displayname, sa.displayname;";
        
        
	$result = $wpdb->get_results( $sql );

	return $result;
}


function cur_get_user_nonwp_data ( $user_id, $field = false ) {
        if( strlen($user_id)>0 && strlen($field)>0 && $field != false)
        {
            global $wpdb;
            $sql = 'SELECT ' . $field . ' FROM users WHERE userid = ' . $user_id;
            $result = $wpdb->get_var( $sql );
            return $result;
        }else{
            return null;
        }	
}

/* RESOURCES */

function cur_get_resource_total_from_group ( $group_id ) {
	global $wpdb;

	if ( !$group_id ) { return; }

	$sql = 'SELECT COUNT(*) from resources r inner join group_resources gr on r.resourceid = gr.resourceid WHERE groupid = ' . $group_id;

	$result = $wpdb->get_var( $sql );

	return $result;
}

function cur_get_resource_total_from_member ( $member_id ) {
	global $wpdb;

	$sql = 'select count(*) from resources where contributorid = ' . $member_id;

	$result = $wpdb->get_var( $sql );

	return $result;
}

function cur_get_total_members_group ( $group_id ) {
	global $wpdb;

	$sql = 'select count(*), gm.userid from resources r inner join (select user_id userid FROM cur_bp_groups_members where group_id = '.$group_id.') gm on gm.userid = r.contributorid group by gm.userid order by count(*) desc';

	$result = $wpdb->get_var( $sql );

	return $result;
}


/* GROUPS */

function bp_excerpt_group_description( $old_description ) {
	// your exceprt code
	$length = 100; 
	$new_description = substr($old_description,0,$length);
	if ( strlen($old_description) > 100 ) { $new_description .= "..."; }
	return $new_description;
}

add_filter( 'bp_get_group_description_excerpt', 'bp_excerpt_group_description');

add_action ( 'groups_created_group', 'cur_groups_created_group', 10, 2 );
function cur_groups_created_group( $group_id, $group ) {
	/* The reason I'm asking is that I need to add 4 attributes to groups that will help us facilitate our search (indexed, lastindexdate, indexrequired and indexrequireddate) and need to figure out how to implement these.

  Once I make that determination, I will ask you to update the indexrequired (set to 'T') and indexrequireddate (set to current datetime) attributes every time a group has been added or updated (not group activity, just the key group attributes). */

  $indexrequired = "T";
  $indexrequireddate = current_time("mysql");
  $indexed = false;
  $lastindexdate = false;

  groups_update_groupmeta( $group_id, 'indexed', $indexed );
  groups_update_groupmeta( $group_id, 'lastindexdate', $lastindexdate );
  groups_update_groupmeta( $group_id, 'indexrequired', $indexrequired );
  groups_update_groupmeta( $group_id, 'indexrequireddate', $indexrequireddate );

}




// Execute custom style guide page
add_action( 'genesis_meta', 'curriki_custom_member_page_loop' );
function curriki_custom_member_page_loop() {

	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	$bp = buddypress();

	// echo bp_current_action();
	// echo bp_current_component(); exit;

	if ( bp_current_action() != "just-me" && bp_current_action() != "my-friends" && bp_current_action() != "my-groups" && bp_current_action() != "my-library" ) { return; }

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_loop', 'genesis_do_loop' );

        if( function_exists("curriki_member_page_scripts") )
            add_action( 'genesis_before', 'curriki_member_page_scripts' );
        if( function_exists("curriki_member_header") )
            add_action( 'genesis_after_header', 'curriki_member_header', 10 );
        if( function_exists("curriki_member_page_body") )
            add_action( 'genesis_after_header', 'curriki_member_page_body', 15 );
}

/**
* Override the logic of recaptcha
*
*/

function groups_action_create_group_override() {
	// If we're not at domain.org/groups/create/ then return false.
	if ( !bp_is_groups_component() || !bp_is_current_action( 'create' ) )
		return false;

	if ( !is_user_logged_in() )
		return false;

	if ( !bp_user_can_create_groups() ) {
		bp_core_add_message( __( 'Sorry, you are not allowed to create groups.', 'buddypress' ), 'error' );
		bp_core_redirect( bp_get_groups_directory_permalink() );
	}

	$bp = buddypress();

	// Make sure creation steps are in the right order.
	groups_action_sort_creation_steps();

	// If no current step is set, reset everything so we can start a fresh group creation.
	$bp->groups->current_create_step = bp_action_variable( 1 );
	if ( !bp_get_groups_current_create_step() ) {
		unset( $bp->groups->current_create_step );
		unset( $bp->groups->completed_create_steps );

		setcookie( 'bp_new_group_id', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		setcookie( 'bp_completed_create_steps', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );

		$reset_steps = true;
		$keys        = array_keys( $bp->groups->group_creation_steps );
		bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . array_shift( $keys ) ) );
	}

	// If this is a creation step that is not recognized, just redirect them back to the first screen.
	if ( bp_get_groups_current_create_step() && empty( $bp->groups->group_creation_steps[bp_get_groups_current_create_step()] ) ) {
		bp_core_add_message( __('There was an error saving group details. Please try again.', 'buddypress'), 'error' );
		bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create' ) );
	}

	// Fetch the currently completed steps variable.
	if ( isset( $_COOKIE['bp_completed_create_steps'] ) && !isset( $reset_steps ) )
		$bp->groups->completed_create_steps = json_decode( base64_decode( stripslashes( $_COOKIE['bp_completed_create_steps'] ) ) );

	// Set the ID of the new group, if it has already been created in a previous step.
	if ( bp_get_new_group_id() ) {
		$bp->groups->current_group = groups_get_group( $bp->groups->new_group_id );

		// Only allow the group creator to continue to edit the new group.
		if ( ! bp_is_group_creator( $bp->groups->current_group, bp_loggedin_user_id() ) ) {
			bp_core_add_message( __( 'Only the group creator may continue editing this group.', 'buddypress' ), 'error' );
			bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create' ) );
		}
	}

	// If the save, upload or skip button is hit, lets calculate what we need to save.
	if ( isset( $_POST['save'] ) ) {
            
		// Check the nonce.
		check_admin_referer( 'groups_create_save_' . bp_get_groups_current_create_step() );

		if ( 'group-details' == bp_get_groups_current_create_step() ) {
                    $submit = true;
                    
                    if(!isset($_SESSION['i-am-human']))
                    {
                          $secret = GOOGLE_RECAPTCHA_SECRET_KEY;
                          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_SESSION['g-recaptcha-response']);
                          $responseData = json_decode($verifyResponse);

                          if($responseData->success)
                          {
                              $submit = true;
                          } else {
                              $submit = false;
                          }
                     }
                    if(!$submit){
                        echo json_encode(['msg'=>'Wrong Data']);
                        wp_die();
                    }
			if ( empty( $_POST['group-name'] ) || empty( $_POST['group-desc'] ) || !strlen( trim( $_POST['group-name'] ) ) || !strlen( trim( $_POST['group-desc'] ) ) ) {
				bp_core_add_message( __( 'Please fill in all of the required fields', 'buddypress' ), 'error' );
				bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
			}

			$new_group_id = isset( $bp->groups->new_group_id ) ? $bp->groups->new_group_id : 0;

			if ( !$bp->groups->new_group_id = groups_create_group( array( 'group_id' => $new_group_id, 'name' => $_POST['group-name'], 'description' => $_POST['group-desc'], 'slug' => groups_check_slug( sanitize_title( esc_attr( $_POST['group-name'] ) ) ), 'date_created' => bp_core_current_time(), 'status' => 'public' ) ) ) {
				bp_core_add_message( __( 'There was an error saving group details. Please try again.', 'buddypress' ), 'error' );
				bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
			}
		}

		if ( 'group-settings' == bp_get_groups_current_create_step() ) {
			$group_status = 'public';
			$group_enable_forum = 1;

			if ( !isset($_POST['group-show-forum']) ) {
				$group_enable_forum = 0;
			}

			if ( 'private' == $_POST['group-status'] )
				$group_status = 'private';
			elseif ( 'hidden' == $_POST['group-status'] )
				$group_status = 'hidden';

			if ( !$bp->groups->new_group_id = groups_create_group( array( 'group_id' => $bp->groups->new_group_id, 'status' => $group_status, 'enable_forum' => $group_enable_forum ) ) ) {
				bp_core_add_message( __( 'There was an error saving group details. Please try again.', 'buddypress' ), 'error' );
				bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
			}

			// Save group types.
			if ( ! empty( $_POST['group-types'] ) ) {
				bp_groups_set_group_type( $bp->groups->new_group_id, $_POST['group-types'] );
			}

			/**
			 * Filters the allowed invite statuses.
			 *
			 * @since 1.5.0
			 *
			 * @param array $value Array of statuses allowed.
			 *                     Possible values are 'members,
			 *                     'mods', and 'admins'.
			 */
			$allowed_invite_status = apply_filters( 'groups_allowed_invite_status', array( 'members', 'mods', 'admins' ) );
			$invite_status	       = !empty( $_POST['group-invite-status'] ) && in_array( $_POST['group-invite-status'], (array) $allowed_invite_status ) ? $_POST['group-invite-status'] : 'members';

			groups_update_groupmeta( $bp->groups->new_group_id, 'invite_status', $invite_status );
		}

		if ( 'group-invites' === bp_get_groups_current_create_step() ) {
			if ( ! empty( $_POST['friends'] ) ) {
				foreach ( (array) $_POST['friends'] as $friend ) {
					groups_invite_user( array(
						'user_id'  => (int) $friend,
						'group_id' => $bp->groups->new_group_id,
					) );
				}
			}

			groups_send_invites( bp_loggedin_user_id(), $bp->groups->new_group_id );
		}

		/**
		 * Fires before finalization of group creation and cookies are set.
		 *
		 * This hook is a variable hook dependent on the current step
		 * in the creation process.
		 *
		 * @since 1.1.0
		 */
		do_action( 'groups_create_group_step_save_' . bp_get_groups_current_create_step() );

		/**
		 * Fires after the group creation step is completed.
		 *
		 * Mostly for clearing cache on a generic action name.
		 *
		 * @since 1.1.0
		 */
		do_action( 'groups_create_group_step_complete' );

		/**
		 * Once we have successfully saved the details for this step of the creation process
		 * we need to add the current step to the array of completed steps, then update the cookies
		 * holding the information
		 */
		$completed_create_steps = isset( $bp->groups->completed_create_steps ) ? $bp->groups->completed_create_steps : array();
		if ( !in_array( bp_get_groups_current_create_step(), $completed_create_steps ) )
			$bp->groups->completed_create_steps[] = bp_get_groups_current_create_step();

		// Reset cookie info.
		setcookie( 'bp_new_group_id', $bp->groups->new_group_id, time()+60*60*24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		setcookie( 'bp_completed_create_steps', base64_encode( json_encode( $bp->groups->completed_create_steps ) ), time()+60*60*24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );

		// If we have completed all steps and hit done on the final step we
		// can redirect to the completed group.
		$keys = array_keys( $bp->groups->group_creation_steps );
		if ( count( $bp->groups->completed_create_steps ) == count( $keys ) && bp_get_groups_current_create_step() == array_pop( $keys ) ) {
			unset( $bp->groups->current_create_step );
			unset( $bp->groups->completed_create_steps );

			setcookie( 'bp_new_group_id', false, time() - 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
			setcookie( 'bp_completed_create_steps', false, time() - 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );

			// Once we completed all steps, record the group creation in the activity stream.
			groups_record_activity( array(
				'type' => 'created_group',
				'item_id' => $bp->groups->new_group_id
			) );

			/**
			 * Fires after the group has been successfully created.
			 *
			 * @since 1.1.0
			 *
			 * @param int $new_group_id ID of the newly created group.
			 */
			do_action( 'groups_group_create_complete', $bp->groups->new_group_id );

			bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) );
		} else {
			/**
			 * Since we don't know what the next step is going to be (any plugin can insert steps)
			 * we need to loop the step array and fetch the next step that way.
			 */
			foreach ( $keys as $key ) {
				if ( $key == bp_get_groups_current_create_step() ) {
					$next = 1;
					continue;
				}

				if ( isset( $next ) ) {
					$next_step = $key;
					break;
				}
			}

			bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . $next_step ) );
		}
	}

	// Remove invitations.
	if ( 'group-invites' === bp_get_groups_current_create_step() && ! empty( $_REQUEST['user_id'] ) && is_numeric( $_REQUEST['user_id'] ) ) {
		if ( ! check_admin_referer( 'groups_invite_uninvite_user' ) ) {
			return false;
		}

		$message = __( 'Invite successfully removed', 'buddypress' );
		$error   = false;

		if( ! groups_uninvite_user( (int) $_REQUEST['user_id'], $bp->groups->new_group_id ) ) {
			$message = __( 'There was an error removing the invite', 'buddypress' );
			$error   = 'error';
		}

		bp_core_add_message( $message, $error );
		bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/group-invites' ) );
	}

	// Group avatar is handled separately.
	if ( 'group-avatar' == bp_get_groups_current_create_step() && isset( $_POST['upload'] ) ) {
		if ( ! isset( $bp->avatar_admin ) ) {
			$bp->avatar_admin = new stdClass();
		}

		if ( !empty( $_FILES ) && isset( $_POST['upload'] ) ) {
			// Normally we would check a nonce here, but the group save nonce is used instead.
			// Pass the file to the avatar upload handler.
			if ( bp_core_avatar_handle_upload( $_FILES, 'groups_avatar_upload_dir' ) ) {
				$bp->avatar_admin->step = 'crop-image';

				// Make sure we include the jQuery jCrop file for image cropping.
				add_action( 'wp_print_scripts', 'bp_core_add_jquery_cropper' );
			}
		}

		// If the image cropping is done, crop the image and save a full/thumb version.
		if ( isset( $_POST['avatar-crop-submit'] ) && isset( $_POST['upload'] ) ) {

			// Normally we would check a nonce here, but the group save nonce is used instead.
			$args = array(
				'object'        => 'group',
				'avatar_dir'    => 'group-avatars',
				'item_id'       => $bp->groups->current_group->id,
				'original_file' => $_POST['image_src'],
				'crop_x'        => $_POST['x'],
				'crop_y'        => $_POST['y'],
				'crop_w'        => $_POST['w'],
				'crop_h'        => $_POST['h']
			);

			if ( ! bp_core_avatar_handle_crop( $args ) ) {
				bp_core_add_message( __( 'There was an error saving the group profile photo, please try uploading again.', 'buddypress' ), 'error' );
			} else {
				/**
				 * Fires after a group avatar is uploaded.
				 *
				 * @since 2.8.0
				 *
				 * @param int    $group_id ID of the group.
				 * @param string $type     Avatar type. 'crop' or 'full'.
				 * @param array  $args     Array of parameters passed to the avatar handler.
				 */
				do_action( 'groups_avatar_uploaded', bp_get_current_group_id(), 'crop', $args );

				bp_core_add_message( __( 'The group profile photo was uploaded successfully.', 'buddypress' ) );
			}
		}
	}

	/**
	 * Filters the template to load for the group creation screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Path to the group creation template to load.
	 */
	bp_core_load_template( apply_filters( 'groups_template_create_group', 'groups/create' ) );
}
remove_action( 'bp_actions', 'groups_action_create_group', 1 );
add_action( 'bp_actions', 'groups_action_create_group_override' );

add_action('bp_before_group_details_creation_step','testabc');

function testabc(){
    global $captcha;
    

    $captcha = true;
    
//    echo "<pre>";
//    var_dump($_SESSION);
//    die('hehe');
    if(isset($_SESSION['i-am-human']) && !empty($_SESSION['i-am-human']))
    {
//          $secret = '6LcS5IIUAAAAAEXh78HorBQNbCL5a9StqPDcabvf';
//          $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_SESSION['g-recaptcha-response']);
//          $responseData = json_decode($verifyResponse);
//
//          if($responseData->success)
//          {
              $captcha = false;
//          }
     }
}




/*
 * Ajax for checking recaptcha
 */




function validate_recaptcha() {
 
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
     
        $recaptcha_val = $_REQUEST['recaptcha_val'];
         
        
     
        // Now we'll return it to the javascript function
        // Anything outputted will be returned in the response
        if($recaptcha_val){
            $_SESSION['g-recaptcha-response'] = $recaptcha_val;
            $_SESSION['i-am-human'] = $recaptcha_val;
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false]);
        }
        
         
        // If you're debugging, it might be useful to see what was sent in the $_REQUEST
        // print_r($_REQUEST);
     
    }
     
    // Always die in functions echoing ajax content
   die();
}
 
add_action( 'wp_ajax_nopriv_validate_recaptcha', 'validate_recaptcha' );
add_action( 'wp_ajax_validate_recaptcha', 'validate_recaptcha' );