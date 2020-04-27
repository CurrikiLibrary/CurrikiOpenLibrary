<?php

namespace Buddyboss\BpGdpr;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( '\BuddyBoss\BpGdpr\SettingsExport' ) ) :

	final class SettingsExport extends Export {

		/**
		 * Get the instance of this class.
		 *
		 * @return Controller|null
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new SettingsExport();
				$instance->setup( "bp_settings", __( "Settings", 'buddyboss-bp-gdpr' ) );
			}

			return $instance;
		}


		function process_data( $user, $page, $email_address = false ) {

			if(!$user || is_wp_error($user)) {
				return $this->response(array(),true);
			}

			$export_items = array();


			$group_id    = "bp_settings";
			$group_label = __("Settings",'buddyboss-bp-gdpr');
			$item_id     = "{$this->exporter_name}-{$group_id}";


			/**
			 * Notification Settings
			 */

			$notification_settings = $this->get_notification_settings();						
			
			$notification_data = array();
			
			foreach($notification_settings as $noti_key => $notification_label) {
				
				$value = bp_get_user_meta( $user->ID, $noti_key, true );

				if ( empty( $value ) || $value == "yes" ) {
					if ( $value == "yes" ) {
						$value = __( "Yes", "buddyboss-bp-gdpr" );
					} else {
						$value = __( "Yes (Default)", "buddyboss-bp-gdpr" );
					}
				} else {
					$value = __( "No", "buddyboss-bp-gdpr" );
				}
				
				$notification_data[] = array("name"=> $notification_label, "value" => $value);
				
			}

			$notification_data = apply_filters("buddyboss_bp_gdpr_notification_settings_after_data_prepare",$notification_data,$user);

			$export_items[] = array(
				'group_id'    => $group_id."_notification",
				'group_label' => __( "Notifications Settings", "buddyboss-bp-gdpr" ),
				'item_id'     => "bp_notification_settings",
				'data'        => $notification_data,
			);

			$export_items = apply_filters("buddyboss_bp_gdpr_additional_settings",$export_items,$user);
			
			$done = true;

			return $this->response($export_items,$done);
		}


		function process_erase($user, $page, $email_address) {

			if(!$user || is_wp_error($user)) {
				return $this->response_erase(array(),true);
			}

			$items_removed  = true;
			$items_retained = false;

			$notification_settings = $this->get_notification_settings();

			foreach($notification_settings as $noti_key => $notification_label) {
				bp_delete_user_meta( $user->ID, $noti_key );
			}

			do_action("buddyboss_bp_gdpr_delete_additional_settings",$user);

			$done = true;

			return $this->response_erase($items_removed,$done,array(),$items_retained);
		}

		
		function get_notification_settings() {
			$notification_settings = array();

			if ( bp_is_active( 'friends' ) ) {
				$notification_settings["notification_friends_friendship_request"] = __( 'A member sends you a friendship request', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_friends_friendship_accepted"] = __( 'A member accepts your friendship request', 'buddyboss-bp-gdpr' );
			}
			if ( bp_is_active( 'groups' ) ) {
				$notification_settings["notification_groups_invite"] = __( 'A member invites you to join a group', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_groups_group_updated"] = __( 'Group information is updated', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_groups_admin_promotion"] = __( 'You are promoted to a group administrator or moderator', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_groups_membership_request"] = __( 'A member requests to join a private group for which you are an admin', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_membership_request_completed"] = __( 'Your request to join a group has been approved or denied', 'buddyboss-bp-gdpr' );
			}
			if ( bp_is_active( 'activity' ) ) {
				$notification_settings["notification_activity_new_mention"] = __( 'A member mentions you in an update using "@username"', 'buddyboss-bp-gdpr' );
				$notification_settings["notification_activity_new_reply"] = __( "A member replies to an update or comment you've posted", 'buddyboss-bp-gdpr' );
			}
			if ( bp_is_active( 'messages' ) ) {
				$notification_settings["notification_messages_new_message"] = __( 'A member sends you a new message	', 'buddyboss-bp-gdpr' );
			}
			
			return $notification_settings;
		}

	}

endif;
