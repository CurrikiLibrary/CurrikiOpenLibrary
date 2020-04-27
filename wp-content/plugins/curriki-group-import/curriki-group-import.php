<?php


class CurrikiGroupImport extends BP_Component {

	/*--------------------------------------------*
	 * Attributes
	 *--------------------------------------------*/
	 
	/**
	 * Initializes the plugin by setting localization, filters, and functions that need to hook into WordPress and BuddyPress.
	 */
	public function __construct() {
		global $bp;

		// $this->group_import = $insta;

		parent::start(
			// Unique component ID
			'curgi_group_import',

			// Used by BP when listing components (eg in the Dashboard)
			__( 'curgi Group Generator', 'bpsi-group_import' )
		);

		// if ( !function_exists('bp_forums_new_forum') ) { echo "crapstop"; exit; }

		// print_r ($bp); exit;

		add_action( 'admin_menu', array( $this, 'curgi_admin_menu' ) );
		add_action( "admin_post_curgi_process_group_member_files", array ( $this, 'curgi_process_group_member_files' ) );	
		add_action( "admin_post_curgi_process_files", array ( $this, 'curgi_process_files' ) );	
		add_action( "admin_post_curgi_process_forum_files", array ( $this, 'curgi_process_forum_files' ) );	

		if ( ! defined('WP_GROUP_IMPORT_PERMISSIONS') ) define("WP_GROUP_IMPORT_PERMISSIONS", "manage_options");

		// register our component as an active component in BP
		$bp->active_components[$this->id] = '1';

		// echo "test";
		// print_r ($bp); exit;

	}


	/**
	 * Set up component data, as required by BP.
	 */
	public function setup_globals( $args = array() ) {
		parent::setup_globals( array(
			'slug' => 'group-imports', // used for building URLs
		) );
	}

	/**
	 * Registers the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function curgi_admin_menu() {
	    	
	    add_menu_page(
	        __("Curriki Group Import : Settings"),
	        __("Curriki Group Import"),
	        WP_GROUP_IMPORT_PERMISSIONS,
	        "curriki-group-import",
	        array( $this, 'curgi_settings_page' )
	    );
    	
	} // end curgi_admin_menu
	
	/**
	 * Renders the settings page for this plugin.
	 */

	public function curgi_settings_page() {
	
		global $bp;

		$bbp = bbpress();

		if ( !function_exists('groups_new_group_forum') ) { echo "crapstop"; exit; }

		$redirect = urlencode( remove_query_arg( 'msg', $_SERVER['REQUEST_URI'] ) );
        $redirect = urlencode( $_SERVER['REQUEST_URI'] );
        
        $action_name = "curgi_process_files";
        $nonce_name = "curriki-group-import";
	
	    echo '
	    <div class="wrap">
	        <div id="icon-options-general" class="icon32"><br /></div>
	        <h2>'.__("Curriki Group Importer").'</h2>
	        <br />'; ?>
	        
	        <?php /* $max_id = esc_attr( get_option( 'curgi_twitter_gallery_max_id' ) ); ?>
	        <p>Currently, max_id of twitter is <?php echo $max_id; ?></p> */ ?>
	        
	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">

				<table class="form-table">
					<tbody>
						<tr>
							<p>This import will take a starting ID and an ending ID then parse the 'group' table and import that data into BP groups.</p>
							<p>Note: Be careful here and make sure you have upped PHP timeouts and memory requirements.</strong></p>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Starting ID', 'aok_group_import'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Starting ID', 'aok_group_import'); ?></span>
									</legend>
									<label for="csv_import">
										<input id="start_id" name="start_id" type="text" />
										<?php // _e( 'Description Of Setting Here', 'aok_group_import' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Ending ID', 'aok_group_import'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Ending ID', 'aok_group_import'); ?></span>
									</legend>
									<label for="csv_import">
										<input id="end_id" name="end_id" type="text" />
										<?php // _e( 'Description Of Setting Here', 'aok_group_import' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Admin ID', 'aok_group_import'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Admin ID', 'aok_group_import'); ?></span>
									</legend>
									<label for="csv_import">
										<input id="admin_id" name="admin_id" type="text" />
										<?php _e( 'For Overrides Only - Otherwise We Use The \'creatorid\' in the database.', 'aok_group_import' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>

	            <input type="hidden" name="action" value="<?php echo $action_name; ?>">
	            <?php wp_nonce_field( $action_name, $nonce_name . '_nonce', FALSE ); ?>
	            <input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
	            <?php // do_settings_sections( 'curriki-group-import-stats' ); ?>
	            <?php submit_button( 'Import Groups' ); ?>
	        </form>
	        
	        <?php

		        $action_name = "curgi_process_group_member_files";
		        $nonce_name = "curriki-group-member-import";

	        ?>

	        <hr/>

	        <h2>Group / Member Importer</h2>

			<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">

	            <input type="hidden" name="action" value="<?php echo $action_name; ?>">
	            <?php wp_nonce_field( $action_name, $nonce_name . '_nonce', FALSE ); ?>
	            <input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
	            <?php // do_settings_sections( 'curriki-group-import-stats' ); ?>
	            <?php submit_button( 'Import Groups Members (All)' ); ?>
	        </form>

	        <?php

		        $action_name = "curgi_process_forum_files";
		        $nonce_name = "curriki-group-forum-import";

	        ?>

	        <hr/>

	        <h2>Forum Importer</h2>

			<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">

	            <input type="hidden" name="action" value="<?php echo $action_name; ?>">
	            <?php wp_nonce_field( $action_name, $nonce_name . '_nonce', FALSE ); ?>
	            <input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
	            <?php // do_settings_sections( 'curriki-group-import-stats' ); ?>
	            <?php submit_button( 'Import Forums (All)' ); ?>
	        </form>



	        <!--<form action="options.php" method="POST">
	            <?php settings_fields( 'plugin-options-group' ); ?>
	            <?php do_settings_sections( 'curriki-group-import-options' ); ?>
	            <?php submit_button(); ?>
	        <?php echo '</form>-->
	    </div>';
	}


	public function curgi_process_forum_files() {

		global $wpdb, $bp; // , $bbdb;

		$bbdb = bbpress();

		if ( !$_POST ) { return; }

		// check nonce
        if ( ! wp_verify_nonce( $_POST[ 'curriki-group-forum-import' . '_nonce' ], 'curgi_process_forum_files' ) )
            die( 'Invalid nonce.' . var_export( $_POST, true ) );

        if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
            $this->log['error'][] = 'You don\'t have the permissions to do this. Please contact the blog\'s administrator.';
            $this->print_messages();
            return;
        }

        $forum_results = $wpdb->get_results ( "SELECT d.* FROM discussions d " );

		if ( !$forum_results ) {
            $this->log['error'][] = 'No rows found in the group table, aborting.';
            $this->print_messages();
            return;
		}

		$topics_added = 0;
		$replies_added = 0;

		// print_r ($forum_results); exit;

		foreach ( $forum_results as $result ) {

			if ( !$result->discussionid ) { continue; }

			$topic_data = false;
			$topic_meta = false;

			print_r ($result);

			// figure out NEW forum_id from OLD database

			$group_id = $result->groupid;
			echo "group_id: "; print_r ($group_id); echo "\n";			
			$forum_id_results = $wpdb->get_var('SELECT meta_value FROM cur_bp_groups_groupmeta WHERE group_id = ' . $group_id . ' AND meta_key = "forum_id" ');
			$forum_id_results = unserialize($forum_id_results);
			$forum_id = $forum_id_results[0];
			
			echo "forum_id: "; print_r ($forum_id); echo "\n";

			if ( !$forum_id ) { continue; }

				/* ADD THE TOPICS */

				// Parse arguments against default values
				$topic_data = bbp_parse_args( $topic_data, array(
				'post_parent'    => $forum_id, // forum ID
				'post_status'    => bbp_get_public_status_id(),
				'post_type'      => bbp_get_topic_post_type(),
				'post_author'    => $result->userid,
				'post_password'  => '',
				'post_content'   => $result->description,
				'post_title'     => $result->title,
				'comment_status' => 'closed',
				'menu_order'     => 0,
				), 'insert_topic' );

				// Insert topic
				$topic_id   = wp_insert_post( $topic_data );

				if ( !$topic_id ) { continue; }

				$topics_added++;

				// Parse arguments against default values
				$topic_meta = bbp_parse_args( $topic_meta, array(
				'author_ip'          => bbp_current_author_ip(),
				'forum_id'           => $forum_id,
				'topic_id'           => $topic_id,
				'voice_count'        => 1,
				'reply_count'        => 0,
				'reply_count_hidden' => 0,
				'last_reply_id'      => 0,
				'last_active_id'     => $topic_id,
				'last_active_time'   => get_post_field( 'post_date', $topic_id, 'db' ),
				), 'insert_topic_meta' );

				// Insert topic meta
				foreach ( $topic_meta as $meta_key => $meta_value ) {
				update_post_meta( $topic_id, '_bbp_' . $meta_key, $meta_value );
				}

				update_post_meta( $topic_id, 'cur_fullname', $result->fullname );


				// Update the forum
				$forum_id = bbp_get_topic_forum_id( $topic_id );
				if ( !empty( $forum_id ) ) {
				bbp_update_forum( array( 'forum_id' => $forum_id ) );
				}


				/* ADD THE REPLIES */
				$reply_results = $wpdb->get_results ( "SELECT dr.* FROM discussionresponses dr WHERE discussionid = " . $result->discussionid );

				if ( !$reply_results ) { continue; }

				foreach ( $reply_results as $reply ) {

					// Forum
					$reply_data = bbp_parse_args( $reply_data, array(
					'post_parent'    => $topic_id, // topic ID
					'post_status'    => bbp_get_public_status_id(),
					'post_type'      => bbp_get_reply_post_type(),
					'post_author'    => $reply->userid,
					'post_password'  => '',
					'post_content'   => $reply->response,
					'post_title'     => '',
					'menu_order'     => 0,
					'comment_status' => 'closed'
					), 'insert_reply' );

					// Insert reply
					$reply_id = wp_insert_post( $reply_data );

					// Bail if no reply was added
					if ( empty( $reply_id ) ) {
					return false;
					}

					$replies_added++;

					// Forum meta
					$reply_meta = bbp_parse_args( $reply_meta, array(
					'author_ip' => bbp_current_author_ip(),
					'forum_id'  => $forum_id,
					'topic_id'  => $topic_id,
					), 'insert_reply_meta' );

					// Insert reply meta
					foreach ( $reply_meta as $meta_key => $meta_value ) {
						update_post_meta( $reply_id, '_bbp_' . $meta_key, $meta_value );
					}

					// Update the topic
					$topic_id = bbp_get_reply_topic_id( $reply_id );
					if ( !empty( $topic_id ) ) {
						bbp_update_topic( $topic_id );
					}

				}
				

		}

		echo "topics added - " . $topics_added; 
		echo "<BR>";
		echo "replies added - " . $replies_added; 
	
		exit;

	}


	public function curgi_process_group_member_files() {

		global $wpdb, $bp; // , $bbdb;

		// $bbdb = bbpress();

		if ( !$_POST ) { return; }

		// check nonce
        if ( ! wp_verify_nonce( $_POST[ 'curriki-group-member-import' . '_nonce' ], 'curgi_process_group_member_files' ) )
            die( 'Invalid nonce.' . var_export( $_POST, true ) );

        if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
            $this->log['error'][] = 'You don\'t have the permissions to do this. Please contact the blog\'s administrator.';
            $this->print_messages();
            return;
        }

        

        $group_member_results = $wpdb->get_results ( "SELECT gu.* FROM group_users gu" );

        // print_r ($group_member_results); exit;

		if ( !$group_member_results ) {
            $this->log['error'][] = 'No rows found in the group table, aborting.';
            $this->print_messages();
            return;
		}

		$members_added = 0;
		$members_failed = 0;
		$members_changed = 0;

		foreach ( $group_member_results as $result ) {
			if ( $result->groupid && $result->userid && $result->admin ) { // need everything
				// check and see if this user is a member
				if ( groups_is_user_member( $result->userid, $result->groupid ) ) {
					// if a member, then update their admin status just in case that changed
					if ( $result->admin == "T" ) {
						$wpdb->get_results( $wpdb->prepare( "UPDATE {$bp->groups->table_name_members} SET is_mod = 1 WHERE user_id = %d AND group_id = %d", $result->userid, $result->groupid ) );
						$members_changed++;
					} else {
						$wpdb->get_results( $wpdb->prepare( "UPDATE {$bp->groups->table_name_members} SET is_mod = 0 WHERE user_id = %d AND group_id = %d", $result->userid, $result->groupid ) );
						$members_changed++;
					}
				} else {
					if ( $result->admin == "T" ) { $is_admin = 1; } else { $is_admin = 0; }

					$new_member                = new BP_Groups_Member;
					$new_member->group_id      = $result->groupid;
					$new_member->user_id       = $result->userid;
					$new_member->inviter_id    = 0;
					$new_member->is_admin      = $is_admin;
					$new_member->user_title    = '';
					$new_member->date_modified = bp_core_current_time();
					$new_member->is_confirmed  = 1;

					if ( !$new_member->save() ) {
						$members_failed++;
						continue;
					} else {
						$members_added++;
					}
						
				}

			} else {
				continue;
			}
		}

		echo "members added - " . $members_added; 
		echo "<BR>";
		echo "members failed - " . $members_failed; 
		echo "<BR>";
		echo "members changed - " . $members_changed; 		
		exit;

	}



	public function curgi_process_files() {

		global $wpdb, $bp, $bbdb;

		$bbdb = bbpress();

		if ( !$_POST ) { return; }

		// check nonce
        if ( ! wp_verify_nonce( $_POST[ 'curriki-group-import' . '_nonce' ], 'curgi_process_files' ) )
            die( 'Invalid nonce.' . var_export( $_POST, true ) );

        if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
            $this->log['error'][] = 'You don\'t have the permissions to do this. Please contact the blog\'s administrator.';
            $this->print_messages();
            return;
        }

		$start_id = intval($_POST['start_id']);
		$end_id = intval($_POST['end_id']);
		$admin_id_override = intval($_POST['admin_id']);

		if ( !$start_id || !$end_id ) { 
            $this->log['error'][] = 'No admin ID, starer ID, or ending ID, aborting.';
            $this->print_messages();
            return;
		}

        $group_results = $wpdb->get_results ( "SELECT * FROM groups WHERE groupid >= " . $start_id . " AND groupid <= " . $end_id );

		if ( !$group_results ) {
            $this->log['error'][] = 'No rows found in the group table, aborting.';
            $this->print_messages();
            return;
		}

		$groups_created_total = 0;

		foreach ( $group_results as $group ) {
			if ( !$admin_id_override ) {
				$admin_id = $group->creatorid; // if there is no override, go with what is in the group database
			} else { 
				$admin_id = $admin_id_override;
			}

			$access = "public"; // default

			if ( $group->access == "closed" ) { $access = "private"; }

			$args = array(
				// 'group_id'     => $group->groupid,
				'creator_id'   => $admin_id,
				'name'         => $group->displaytitle,
				'description'  => $group->description,
				'slug'         => $group->url,
				'status'       => $access,
				'enable_forum' => 0,
				'date_created' => $group->createdate // bp_core_current_time()
			);
			// print_r ($args);
			$group_id = groups_create_group ( $args );

			// we have to change that ID - we can't pass our own ID into the BP function			

			$wpdb->update( 
				'cur_bp_groups', 
				array( 
					'id' => $group->groupid // integer (number) 
				), 
				array( 'id' => $group_id ), 
				array( 
					'%d'
				), 
				array( '%d' ) 
			);

			$wpdb->update( 
				'cur_bp_groups_groupmeta', 
				array( 
					'group_id' => $group->groupid // integer (number) 
				), 
				array( 'group_id' => $group_id ), 
				array( 
					'%d'
				), 
				array( '%d' ) 
			);

			$wpdb->update( 
				'cur_bp_groups_members', 
				array( 
					'group_id' => $group->groupid // integer (number) 
				), 
				array( 'group_id' => $group_id ), 
				array( 
					'%d'
				), 
				array( '%d' ) 
			);


			// echo "stop"; exit;

			if ( $group_id ) {
				// assign that creater user to the group
				// groups_join_group ( $group_id, $admin_id );
				// $new_member                = new BP_Groups_Member;
				// $new_member->group_id      = $bp_group->id;
				// $new_member->user_id       = $admin_id;
				// $new_member->inviter_id    = 0;
				// $new_member->is_admin      = 1;
				// $new_member->user_title    = '';
				// $new_member->date_modified = $group->createdate; // bp_core_current_time();
				// $new_member->is_confirmed  = 1;
				// if ( !$new_member->save() )
				// 	return false;
				
				// add the meta-data
				groups_update_groupmeta( $group->groupid, 'cur_licenseid', $group->licenseid );
				// groups_update_groupmeta( $group->groupid, 'cur_xwd_id', $group->xwd_id );
			
				if ( $group->welcome && $group->welcome != '' ) {
					groups_update_groupmeta( $group->groupid, 'cur_welcome', $group->welcome );
				}
				
				if ( $group->language && $group->sitename != 'curriki' ) {
					groups_update_groupmeta( $group->groupid, 'cur_sitename', $group->sitename );
				}

				if ( $group->language && $group->language != 'eng' ) {
					groups_update_groupmeta( $group->groupid, 'cur_language', $group->language );
				}

				if ( $group->policy && $group->access != 'public' ) {
					groups_update_groupmeta( $group->groupid, 'cur_access', $group->access );
				}

				// for some reason we can't get bbPress to create a new group here, so we have to do this manually. sigh.				
				groups_edit_group_settings ( $group->groupid, true, 'public', 'admins' ); // invite setting set to 'admins' or could be 'members'

				$post = array(
				  'post_content'   => $group->description,
				  'post_name'      => $group->url,
				  'post_title'     => $group->displaytitle,
				  'post_status'    => 'publish',
				  'post_type'      => 'forum',
				  'post_author'    => '1',
				  'ping_status'    => 'open',
				  'post_parent'    => 0,
				  'menu_order'     => 0,
				  'comment_status' => 'closed',
				  // 'post_category'  => [ array(<category id>, ...) ] // Default empty.
				  // 'tags_input'     => [ '<tag>, <tag>, ...' | array ] // Default empty.
				  // 'tax_input'      => [ array( <taxonomy> => <array | string>, <taxonomy_other> => <array | string> ) ] // For custom taxonomies. Default empty.
				); 
				$fourm_post_id = wp_insert_post( $post );

				update_post_meta($fourm_post_id, '_bbp_reply_count', '0');
				update_post_meta($fourm_post_id, '_bbp_topic_count', '0');
				update_post_meta($fourm_post_id, '_bbp_topic_count_hidden', '0');
				update_post_meta($fourm_post_id, '_bbp_total_reply_count', '0');
				update_post_meta($fourm_post_id, '_bbp_total_topic_count', '0');
				update_post_meta($fourm_post_id, '_bbp_last_topic_id', '0');
				update_post_meta($fourm_post_id, '_bbp_last_reply_id', '0');
				update_post_meta($fourm_post_id, '_bbp_last_active_id', '0');
				update_post_meta($fourm_post_id, '_bbp_last_active_time', '0');
				update_post_meta($fourm_post_id, '_bbp_forum_subforum_count', '0');

				$_bbp_group_ids = array( intval($group->groupid) );
				update_post_meta($fourm_post_id, '_bbp_group_ids', $_bbp_group_ids);

				// add the forum post back to BP group meta				
				$fourm_post_ids = array ( intval($fourm_post_id) );
				groups_update_groupmeta( $group->groupid, 'forum_id', $fourm_post_ids );

				$groups_created_total++;
			}
		}

		echo "groups added - " . $groups_created_total; exit;

	}

} // end class

function curgi_group_import_init() {
	buddypress()->curriki_group_import = new CurrikiGroupImport();
}
add_action( 'bp_loaded', 'curgi_group_import_init' );






