<?php

class BPML_Compatibility {

	public function add_hooks() {
		add_action( 'bp_init', array( $this, 'buddydrive' ), 5 );
	}

	public function buddydrive() {
		if ( class_exists( 'BuddyDrive' ) ) {
			$bp_current_component = bp_current_component();
			if ( in_array( $bp_current_component, array( 'buddydrive', 'groups' ) ) ) {
				add_filter( 'bpml_redirection_page_id', array( $this, 'buddydrive_redirection_page_filter' ), 10, 4 );
			}
		}
	}

	public function buddydrive_redirection_page_filter( $page_id, $bp_current_component, $bp_current_action, $bp_pages ) {
		if ( $bp_current_component == 'buddydrive'
		     && in_array( $bp_current_action, array( 'files', 'friends', 'members' ) )
		     && isset( $bp_pages->members->id )
		) {
			$page_id = $bp_pages->members->id;
		} elseif ( $bp_current_component == 'groups'
		           && $bp_current_action == 'buddydrive'
		           && isset( $bp_pages->groups->id )
		) {
			$page_id = $bp_pages->groups->id;
		}

		return $page_id;
	}
}