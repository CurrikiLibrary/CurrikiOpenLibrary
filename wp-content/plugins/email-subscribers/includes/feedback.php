<?php

/**
 * Get additional system & plugin specific information for feedback
 *
 */
if ( ! function_exists( 'ig_es_get_additional_info' ) ) {

	function ig_es_get_additional_info( $additional_info, $system_info = false ) {
		global $ig_es_tracker;

		$additional_info['version'] = ES_PLUGIN_VERSION;

		if ( $system_info ) {

			$additional_info['active_plugins']   = implode( ', ', $ig_es_tracker::get_active_plugins() );
			$additional_info['inactive_plugins'] = implode( ', ', $ig_es_tracker::get_inactive_plugins() );
			$additional_info['current_theme']    = $ig_es_tracker::get_current_theme_info();
			$additional_info['wp_info']          = $ig_es_tracker::get_wp_info();
			$additional_info['server_info']      = $ig_es_tracker::get_server_info();

			// ES Specific information
			$additional_info['plugin_meta_info'] = ES_Common::get_ig_es_meta_info();
		}

		return $additional_info;
	}

}


add_filter( 'ig_es_additional_feedback_meta_info', 'ig_es_get_additional_info', 10, 2 );


function ig_es_render_feedback_widget() {

	if ( is_admin() ) {
		global $ig_es_feedback;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		$show_on_screens = array(
			'email-subscribers_page_es_subscribers',
			'email-subscribers_page_es_forms',
			'email-subscribers_page_es_campaigns',
			'email-subscribers_page_es_reports',
			'email-subscribers_page_es_settings',
		);

		if ( ! in_array( $screen_id, $show_on_screens, true ) ) {
			return;
		}

		if ( ! $ig_es_feedback->can_show_feedback_widget() ) {
			return;
		}

		$event = 'plugin.experience';
		if ( ! $ig_es_feedback->is_event_transient_set( $ig_es_feedback->event_prefix . $event ) ) {

			$total_contacts = ES_DB_Contacts::get_total_subscribers();

			if ( $total_contacts > 10 ) {

				$params = array(
					'type'              => 'emoji',
					'event'             => $event,
					'title'             => "How's your experience with Email Subscribers?",
					'position'          => 'top-end',
					'width'             => 300,
					'confirmButtonText' => __( 'Send', 'email-subscribers' )
				);

				ES_Common::render_feedback_widget( $params );
			}
		}

	}

}

function ig_es_render_general_feedback_widget() {

	if ( is_admin() ) {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		global $ig_es_feedback;

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		$show_on_screens = array(
			'toplevel_page_es_dashboard',
			'email-subscribers_page_es_subscribers',
			'email-subscribers_page_es_lists',
			'email-subscribers_page_es_forms',
			'email-subscribers_page_es_campaigns',
			'email-subscribers_page_es_reports',
			'email-subscribers_page_es_settings',
			'email-subscribers_page_es_general_information',
			'email-subscribers_page_es_pricing'
		);

		if ( ! in_array( $screen_id, $show_on_screens ) ) {
			return;
		}

		$event = 'plugin.feedback';

		$params = array(
			'type'              => 'feedback',
			'event'             => $event,
			'title'             => "Have feedback or question for us?",
			'position'          => 'center',
			'width'             => 600,
			'force'             => true,
			'confirmButtonText' => __( 'Send', 'email-subscribers' ),
			'consent_text'      => __( sprintf( 'By clicking on send button you are agree to our <a href="%s" target="_blank">Privacy Policy</a>', "https://www.icegram.com/privacy-policy/" ), 'email-subscribers' ),
			'email'             => get_option( 'admin_email' ),
			'name'              => ''
		);

		ES_Common::render_feedback_widget( $params );
	}
}

//add_action( 'admin_footer', 'ig_es_render_feedback_widget' );
add_action( 'admin_footer', 'ig_es_render_general_feedback_widget' );