<?php

namespace Buddyboss\BpGdpr;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( '\BuddyBoss\BpGdpr\GroupMembershipExport' ) ) :

	final class GroupMembershipExport extends Export {

		/**
		 * Get the instance of this class.
		 *
		 * @return Controller|null
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new GroupMembershipExport();
				$instance->setup( "bp_group_memberships", __( "Group Memberships", 'buddyboss-bp-gdpr' ) );
			}

			return $instance;
		}


		function process_data( $user, $page, $email_address = false ) {

			if(!$user || is_wp_error($user)) {
				return $this->response(array(),true);
			}

			$export_items = array();

			$data_items = $this->get_data($user,$page);
			
			foreach($data_items["items"] as $item) {

				$group = groups_get_group( $item->group_id );

				$group_id    = "bp_group_membership";
				$group_label = __("Group Membership",'buddyboss-bp-gdpr');
				$item_id     = "{$this->exporter_name}-{$group_id}-{$item->id}";

				$group_permalink = bp_get_group_permalink($group);
			
				$membership_type = false;
				
				if($item->user_id == $user->ID && $item->is_confirmed == "0" && $item->inviter_id == "0") {
					$group_label = __("Group Pending Requests", "buddyboss-bp-gdpr");
					$membership_type = "pending_request";
				}
				elseif ($item->user_id == $user->ID && $item->is_confirmed == "0" && $item->inviter_id != "0") {
					$group_label = __("Group Pending Received Invitation Requests", "buddyboss-bp-gdpr");
					$membership_type = "pending_received_invitation";
				}
				elseif ($item->inviter_id == $user->ID && $item->is_confirmed == "0") {
					$group_label = __("Group Pending Sent Invitation Requests", "buddyboss-bp-gdpr");
					$membership_type = "pending_sent_invitation";
				}
				elseif ($item->user_id == $user->ID && $item->is_confirmed == "1") {
					$group_label = __("Group Membership", "buddyboss-bp-gdpr");
					$membership_type = "membership";
				}

				$group_id .= "_{$membership_type}"; // force to create separate group for each type.
				
				$data = array(
					array("name"=>__('Group Name','buddyboss-bp-gdpr'), "value" => bp_get_group_name( $group )),
					array("name"=>__('Sent Date (GMT)','buddyboss-bp-gdpr'), "value" => $item->date_modified),
					array("name"=>__('Group URL','buddyboss-bp-gdpr'), "value" => $group_permalink),
				);
				
				if($membership_type == "pending_received_invitation") {
					$get_user = get_userdata($item->inviter_id);
					$data[] = array("name"=>__('Sent by','buddyboss-bp-gdpr'), "value" => $get_user->display_name);
				}
				
				if($membership_type == "pending_sent_invitation") {
					$get_user = get_userdata($item->user_id);
					$data[] = array("name"=>__('Sent to','buddyboss-bp-gdpr'), "value" => $get_user->display_name);
				}
				
				if(!empty($item->comments)) {
					$data[] = array("name"=>__('Group Comments','buddyboss-bp-gdpr'), "value" => $item->comments);
				}
				
				$data = apply_filters("buddyboss_bp_gdpr_group_membership_after_data_prepare",$data,$item,$data_items,$membership_type);

				$export_items[] = array(
					'group_id'    => $group_id,
					'group_label' => $group_label,
					'item_id'     => $item_id,
					'data'        => $data,
				);

			}

			$done = $data_items["total"] < $data_items["offset"];

			return $this->response($export_items,$done);
			
		}
		
		/**
		 * Returns the data & count of messages by page and user.
		 * @param $user
		 * @param $page
		 *
		 * @return array
		 */
		function get_data($user,$page) {
			global $wpdb,$bp;

			$wpdb->show_errors(false);
			$group_table = $bp->groups->table_name_members;

			$table = "{$group_table} item";

			$query_select = "item.*";
			$query_select_count = "COUNT(item.id)";
			$query_where = "item.user_id=%d OR item.inviter_id=%d";

			$offset = ( $page - 1 ) * $this->items_per_batch;
			$limit  = "LIMIT {$this->items_per_batch} OFFSET {$offset}";

			$query = "SELECT {$query_select} FROM {$table} WHERE {$query_where} {$limit}";
			$query = $wpdb->prepare($query,$user->ID,$user->ID);
			$query_count = "SELECT {$query_select_count} FROM {$table} WHERE {$query_where}";
			$query_count = $wpdb->prepare($query_count,$user->ID,$user->ID);
			
			$count = (int) $wpdb->get_var($query_count);
			$items = $wpdb->get_results($query);

			return array(
				"total" => $count,
				"offset" => $offset,
				"items" => $items
			);

		}

		

	}

endif;
