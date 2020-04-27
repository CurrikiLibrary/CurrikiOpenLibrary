<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class Email_General.
 *
 * @since 4.0
 */
Class ES_Common {

	public function __construct() {

	}

	public static function get_list_id_details_map() {
		global $wpdb;

		$es_list_table = IG_LISTS_TABLE;
		$results       = $wpdb->get_results( "SELECT * FROM $es_list_table", ARRAY_A );

		$groups = array();

		if ( count( $results ) > 0 ) {
			foreach ( $results as $result ) {
				$groups[ $result['id'] ] = array(
					'name' => $result['name'],
					// 'status' => $result['status']
				);
			}
		}

		return $groups;

	}

	/**
	 * Convert email subscribe templates.
	 *
	 * @param string $template Get email subscribe templates.
	 * @param string $name Get subscriber name.
	 * @param string $email Get subscriber email.
	 *
	 * @return string $convert_template
	 * @since 4.0
	 *
	 */
	public static function convert_es_templates( $template, $name, $email, $es_templ_id = 0 ) {
		$convert_template = str_replace( "{{NAME}}", $name, $template );
		$convert_template = str_replace( "{{EMAIL}}", $email, $convert_template );

		return $convert_template;
	}

	public static function es_process_template_body( $content, $tmpl_id = 0 ) {
		$content = convert_chars( convert_smilies( wptexturize( $content ) ) );
		if ( isset( $GLOBALS['wp_embed'] ) ) {
			$content = $GLOBALS['wp_embed']->autoembed( $content );
		}

		$content         = wpautop( $content );
		$content         = do_shortcode( shortcode_unautop( $content ) );
		$data['content'] = $content;
		$data['tmpl_id'] = $tmpl_id;
		$data            = apply_filters( 'es_after_process_template_body', $data );
		$content         = $data['content'];
		//total contacts
		$total_contacts = ES_DB_Contacts::count_active_subscribers_by_list_id();
		$content        = str_replace( "{{TOTAL-CONTACTS}}", $total_contacts, $content );
		//blog title
		$blog_name = get_option( 'blogname' );
		$content   = str_replace( "{{SITENAME}}", $blog_name, $content );
		// site url
		$site_url = home_url( '/' );
		$content  = str_replace( "{{SITEURL}}", $site_url, $content );

		return $content;
	}

	/**
	 * Get html content type.
	 *
	 * @return string
	 * @since 4.0
	 *
	 */
	public static function es_set_html_content_type() {
		return 'text/html';
	}

	public static function get_statuses_key_name_map( $reverse = false ) {

		$statuses = array(
			// 'confirmed'     => __( 'Confirmed', 'email-subscribers' ),
			'subscribed'   => __( 'Subscribed', 'email-subscribers' ),
			'unconfirmed'  => __( 'Unconfirmed', 'email-subscribers' ),
			'unsubscribed' => __( 'Unsubscribed', 'email-subscribers' ),
			// 'single_opt_in' => __( 'Single Opt In', 'email-subscribers' ),
			// 'double_opt_in' => __( 'Double Opt In', 'email-subscribers' )
		);

		if ( $reverse ) {
			$statuses = array_flip( $statuses );
		}

		return $statuses;
	}


	public static function get_email_sent_type_key_name_map() {
		$options = array(
			'0' => __( 'Disable email notification', 'email-subscribers' ),
			'1' => __( 'Send email immediately', 'email-subscribers' ),
			'2' => __( 'Send email via cron job', 'email-subscribers' )
		);

		return $options;
	}

	public static function prepare_statuses_dropdown_options( $selected = '', $default_label = 'Select Status' ) {

		// $default_status = array(
		// 	'' => __( 'Select Status', 'email-subscribers' ),

		// );
		$default_status[0] = __( $default_label, 'email-subscribers' );

		$statuses = self::get_statuses_key_name_map();
		$statuses = array_merge( $default_status, $statuses );

		$dropdown = '';
		foreach ( $statuses as $key => $status ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$status}</option>";
		}

		return $dropdown;
	}

	public static function prepare_list_dropdown_options( $selected = '', $default_label = 'Select List' ) {


		$default_option[0] = __( $default_label, 'email-subscribers' );

		$lists = ES_DB_Lists::get_list_id_name_map();
		$lists = $default_option + $lists;

		$dropdown = '';
		foreach ( $lists as $key => $list ) {
			$dropdown .= "<option value='{$key}'";

			if ( $selected == $key ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$list}</option>";
		}

		return $dropdown;
	}

	public static function prepare_form_dropdown_options( $selected = '', $default_label = 'Select Form' ) {

		$lists = ES_DB_Forms::get_forms_id_name_map();
		if ( ! is_null( $default_label ) ) {
			$default_option[0] = __( $default_label, 'email-subscribers' );
			$lists             = $default_option + $lists;
		}

		$dropdown = '';
		foreach ( $lists as $key => $list ) {
			$dropdown .= "<option value='{$key}'";

			if ( $selected == $key ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$list}</option>";
		}

		return $dropdown;
	}


	public static function prepare_list_checkbox( $selected = array() ) {
		$lists = ES_DB_Lists::get_list_id_name_map();

		$html = '';
		foreach ( $lists as $key => $list ) {
			if ( in_array( $key, $selected ) ) {
				$checked = "checked='checked'";
			} else {
				$checked = "";
			}
			$html .= '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input type="checkbox" ' . $checked . ' value="' . $key . '" id="list_ids[]" name="list_ids[]">' . $list . '</td></tr>';
		}

		return $html;
	}

	public static function generate_guid( $length = 6 ) {

		$str        = 'abcdefghijklmnopqrstuvwxyz';
		$random_str = array();
		for ( $i = 1; $i <= 5; $i ++ ) {
			$random_str[] = substr( str_shuffle( $str ), 0, $length );
		}

		$guid = implode( '-', $random_str );

		return $guid;
	}

	public static function es_handle_error( $errno, $errstr, $errfile, $errline ) {

		if ( $errno === E_USER_NOTICE ) {

			$message = 'You have an error notice: "%s" in file "%s" at line: "%s".';
			$message = sprintf( $message, $errstr, $errfile, $errline );
		}
	}

	public static function es_generate_stack_trace() {

		$e = new \Exception();

		$trace = explode( "\n", $e->getTraceAsString() );

		// reverse array to make steps line up chronologically

		$trace = array_reverse( $trace );

		array_shift( $trace ); // remove {main}
		array_pop( $trace ); // remove call to this method

		$length = count( $trace );
		$result = array();

		for ( $i = 0; $i < $length; $i ++ ) {
			$result[] = ( $i + 1 ) . ')' . substr( $trace[ $i ], strpos( $trace[ $i ], ' ' ) ); // replace '#someNum' with '$i)', set the right ordering
		}

		$result = implode( "\n", $result );
		$result = "\n" . $result . "\n";

		return $result;
	}

	public static function prepare_list_statuses_dropdown_options( $selected = '' ) {

		$statuses = array(
			''        => __( 'Select Status', 'email-subscribers' ),
			'enable'  => __( 'Enable', 'email-subscribers' ),
			'disable' => __( 'Disable', 'email-subscribers' )
		);

		$dropdown = '';
		foreach ( $statuses as $key => $status ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$status}</option>";
		}

		return $dropdown;
	}

	public static function prepare_notification_send_type_dropdown_options( $selected = '' ) {
		$options  = ES_Common::get_email_sent_type_key_name_map();
		$dropdown = '';
		foreach ( $options as $key => $option ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$option}</option>";
		}

		return $dropdown;
	}


	public static function prepare_templates_dropdown_options( $type = 'newsletter', $selected = '' ) {

		$default_template_option = new stdClass();

		$default_template_option->ID         = '';
		$default_template_option->post_title = __( 'Select Template', 'email-subscribers' );

		$default_template_option = array( $default_template_option );

		$templates = self::get_templates( $type );

		if ( is_array( $templates ) ) {
			$templates = array_merge( $default_template_option, $templates );
		}


		$dropdown = '';
		foreach ( $templates as $key => $template ) {
			$es_templ_thumbnail = ( ! empty( $template->ID ) ) ? get_the_post_thumbnail( $template->ID, array( '200', '200' ) ) : '<img src="' . EMAIL_SUBSCRIBERS_URL . 'images/envelope.png" />';
			$dropdown           .= "<option data-img='" . $es_templ_thumbnail . "' value='{$template->ID}'";

			if ( absint( $selected ) === absint( $template->ID ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$template->post_title}</option>";
		}

		return $dropdown;

	}

	public static function prepare_status_dropdown_options( $selected ) {
		$statuses = array(
			'1' => __( 'Active', 'email-subscribers' ),
			'0' => __( 'Inactive', 'email-subscribers' )
		);

		$dropdown = '';
		foreach ( $statuses as $key => $status ) {
			$dropdown .= "<option value='{$key}'";

			if ( strtolower( $selected ) === strtolower( $key ) ) {
				$dropdown .= "selected = selected";
			}

			$dropdown .= ">{$status}</option>";
		}

		return $dropdown;
	}

	public static function get_templates( $type = 'newsletter' ) {

		$es_args = array(
			'posts_per_page'   => - 1,
			'post_type'        => 'es_template',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'meta_query'       => array(
				array(
					'key'     => 'es_template_type',
					'value'   => $type,
					'compare' => '='
				)
			)
		);

		$es_templates = get_posts( $es_args );

		return $es_templates;

	}

	public static function prepare_categories_html( $category_names = array() ) {
		$categories = get_terms( array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
		) );

		if ( ! is_array( $category_names ) ) {
			$category_names = array();
		}

		$category_html = '';
		foreach ( $categories as $category ) {

			$category_str = wp_specialchars_decode( $category->name, ENT_QUOTES );

			if ( in_array( $category_str, $category_names ) ) {
				$checked = "checked='checked'";
			} else {
				$checked = "";
			}

			$category_html .= '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input type="checkbox" ' . $checked . ' value="' . htmlspecialchars( $category->name, ENT_QUOTES ) . '" id="es_note_cat[]" name="es_note_cat[]">' . $category->name . '</td></tr>';
		}

		return $category_html;
	}

	public static function prepare_custom_post_type_checkbox( $custom_post_types ) {
		$args       = array( 'public' => true, 'exclude_from_search' => false, '_builtin' => false );
		$output     = 'names';
		$operator   = 'and';
		$post_types = get_post_types( $args, $output, $operator );
		if ( ! empty( $post_types ) ) {
			$custom_post_type_html = '';
			foreach ( $post_types as $post_type ) {
				$post_type_search = '{T}' . $post_type . '{T}';
				if ( is_array( $custom_post_types ) && in_array( $post_type_search, $custom_post_types ) ) {
					$checked = "checked='checked'";
				} else {
					$checked = "";
				}
				$custom_post_type_html .= '<tr><td style="padding-top:4px;padding-bottom:4px;padding-right:10px;"><input type="checkbox" ' . $checked . ' value="{T}' . $post_type . '{T}" id="es_note_cat[]" name="es_note_cat[]">' . $post_type . '</td></tr>';
			}

		} else {
			$custom_post_type_html = '<tr>' . __( 'No Custom Post Types Available', 'email-subscribers' ) . '</tr>';
		}

		return $custom_post_type_html;
	}

	public static function get_email_sending_type() {

		$types = array(
			'wp_html_mail'       => __( 'HTML Email Using WordPress', 'email-subsribers' ),
			'wp_plaintext_mail'  => __( 'Plain Text Email Using WordPress', 'email-subsribers' ),
			'php_html_mail'      => __( 'HTML Email Using PHP mail()', 'email-subsribers' ),
			'php_plaintext_mail' => __( 'Plain Text Email Ising PHP mail()', 'email-subsribers' )
		);

		return $types;
	}

	public static function get_optin_types() {

		$types = array(
			'single_opt_in' => __( 'Single Opt-In', 'email-subscribers' ),
			'double_opt_in' => __( 'Double Opt-In', 'email-subscribers' )
		);

		return $types;
	}

	public static function get_image_sizes() {
		$sizes = array(
			'full'      => __( 'Full Size', 'email-subscribers' ),
			'medium'    => __( 'Medium Size', 'email-subscribers' ),
			'thumbnail' => __( 'Thumbnail', 'email-subscribers' )
		);

		return $sizes;
	}

	public static function get_ig_option( $option ) {

		if ( empty( $option ) ) {
			return null;
		}

		$option_prefix = 'ig_es_';

		return get_option( $option_prefix . $option, null );

	}

	public static function set_ig_option( $option, $value ) {

		if ( empty( $option ) ) {
			return null;
		}

		$option_prefix = 'ig_es_';

		return update_option( $option_prefix . $option, $value, false );

	}

	public static function convert_categories_array_to_string( $categories = array() ) {

		$categories_str = '';
		if ( is_array( $categories ) && count( $categories ) > 0 ) {
			$categories_str = "##" . implode( '##', $categories ) . "##";
			$categories_str = wp_specialchars_decode( $categories_str, ENT_QUOTES );
		}

		return $categories_str;
	}

	public static function convert_categories_string_to_array( $categories_str = '' ) {

		$categories = array();
		if ( strlen( $categories_str ) > 0 ) {
			$categories_str = trim( $categories_str, '##' );
			$categories     = explode( '##', $categories_str );
		}

		return $categories;
	}

	public static function prepare_category_string( $category = '' ) {
		$category_str = '';
		if ( ! empty( $category ) ) {
			$category     = wp_specialchars_decode( $category, ENT_QUOTES );
			$category_str = "##" . $category . "##";
		}

		return $category_str;
	}

	public static function prepare_custom_post_type_string( $post_type = '' ) {
		$post_type_str = '';
		if ( ! empty( $post_type ) ) {
			$post_type_str = "##{T}" . $post_type . "{T}##";
		}

		return $post_type_str;
	}

	public static function prepare_first_name_last_name( $name = '' ) {
		$result = array(
			'first_name' => '',
			'last_name'  => ''
		);

		if ( ! empty( $name ) ) {
			// Find out first name and last name
			$name_parts = explode( ' ', $name );
			$last_name  = '';
			if ( count( $name_parts ) > 1 ) {
				$first_name = array_shift( $name_parts );
				$last_name  = implode( ' ', $name_parts );
			} else {
				$first_name = array_shift( $name_parts );
			}

			$result['first_name'] = trim( $first_name );
			$result['last_name']  = trim( $last_name );
		}


		return $result;
	}

	public static function send_signup_notification_to_admins( $data ) {

		$ig_es_notifyadmin = get_option( 'ig_es_notify_admin', 'no' );

		if ( 'yes' === $ig_es_notifyadmin ) {

			$admin_email_addresses = get_option( 'ig_es_admin_emails', '' );

			if ( ! empty( $admin_email_addresses ) ) {

				$admin_emails = explode( ',', $admin_email_addresses );

				if ( count( $admin_emails ) > 0 ) {
					$subject = ES_Mailer::prepare_admin_signup_subject( $data );
					$content = ES_Mailer::prepare_admin_signup_email( $data );
					foreach ( $admin_emails as $admin_email ) {
						$email = trim( $admin_email );
						ES_Mailer::send( $email, $subject, $content );
					}

					return true;
				}
			}
		}

		return false;
	}

	public static function prepare_name_from_first_name_last_name( $first_name = '', $last_name = '' ) {
		$first_name = trim( $first_name );
		$last_name  = trim( $last_name );

		return trim( $first_name . ' ' . $last_name );
	}

	public static function get_cron_url( $self = false, $pro = false, $campaign_hash = '' ) {

		$cron_url = get_option( 'ig_es_cronurl', '' );

		$result = array();
		if ( ! empty( $cron_url ) ) {
			parse_str( $cron_url, $result );
		}

		$cron_url = add_query_arg( 'es', 'cron', site_url() );
		if ( empty( $result['guid'] ) ) {
			$guid = ES_Common::generate_guid();
		} else {
			$guid = $result['guid'];
		}

		$cron_url = add_query_arg( 'guid', $guid, $cron_url );
		update_option( 'ig_es_cronurl', $cron_url );

		if ( ! empty( $campaign_hash ) ) {
			$cron_url = add_query_arg( 'campaign_hash', $campaign_hash, $cron_url );
		}

		if ( $self ) {
			$cron_url = add_query_arg( 'self', true, $cron_url );
		}

		if ( $pro ) {
			$cron_url = add_query_arg( 'es_pro', true, $cron_url );
		}

		return $cron_url;
	}

	public static function get_name_from_email( $email ) {
		$name = strstr( $email, '@', true );

		return trim( $name );
	}


	/**
	 * Check if Classic Editor plugin is active.
	 *
	 * @return bool
	 */
	public static function is_classic_editor_plugin_active() {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			return true;
		}

		return false;
	}

	public static function migrate_widgets() {
		global $wpdb;
		/**
		 * Option - 1 -> ES < 3.x email-subscribers
		 * Option - 2 -> ES < 4.0.4 email_subscriber_widget
		 * Option - 3 -> ES > 4.0.5 email-subscribers-form
		 *
		 *
		 *   - Fetch Option 1 from options table
		 *   - Create a form
		 *   - Set a new option with Option - 3 (title, form_id)
		 *
		 *   - Fetch Option 2 from options table
		 *   - Create a form with data
		 *   - Set a new option with Option - 3 (title, form_id)
		 *
		 *   - Fetch sidebar_widgets option from options table
		 *   - Change Option 1 with Option 3
		 *   - Change Option 2 with Option 3
		 */

		$es_3_widget_option   = 'widget_email-subscribers';
		$es_4_widget_option   = 'widget_email_subscriber_widget';
		$latest_widget_option = 'widget_email-subscribers-form';

		$es_3_widget_option_data = get_option( $es_3_widget_option, '' );
		if ( ! empty( $es_3_widget_option_data ) ) {
			$es_3_widget_option_data = maybe_unserialize( $es_3_widget_option_data );

			if ( is_array( $es_3_widget_option_data ) ) {
				foreach ( $es_3_widget_option_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 && isset( $data['es_title'] ) && isset( $data['es_desc'] ) && isset( $data['es_name'] ) && isset( $data['es_group'] ) ) {
						$title        = ! empty( $data['es_title'] ) ? $data['es_title'] : '';
						$name_visible = ( ! empty( $data['es_name'] ) && 'yes' === strtolower( $data['es_name'] ) ) ? 'yes' : 'no';
						$desc         = ! empty( $data['es_desc'] ) ? $data['es_desc'] : '';
						$group        = ! empty( $data['es_group'] ) ? $data['es_group'] : '';

						$list = ES_DB_Lists::get_list_by_name( $group );

						$list_id = 1;
						if ( ! empty( $list ) ) {
							$list_id = $list['id'];
						}

						$name = 'Widget - ' . $title;

						$data = array(
							'name'          => $name,
							'desc'          => $desc,
							'name_visible'  => $name_visible,
							'name_required' => 'no',
							'list_visible'  => 'no',
							'lists'         => array( $list_id ),
							'af_id'         => 0
						);

						$prepared_form_data = ES_Forms_Table::prepare_form_data( $data );

						$inserted_form_id = ES_DB_Forms::add_form( $prepared_form_data );

						$data_to_set = array(
							'title'   => $title,
							'form_id' => $inserted_form_id
						);

						$es_3_widget_option_data[ $key ] = $data_to_set;
					}

				}

				update_option( $latest_widget_option, $es_3_widget_option_data );
			}
		}

		$es_4_widget_option_data = get_option( $es_4_widget_option, '' );
		if ( ! empty( $es_4_widget_option_data ) ) {
			$es_4_widget_option_data = maybe_unserialize( $es_4_widget_option_data );

			if ( is_array( $es_4_widget_option_data ) ) {
				foreach ( $es_4_widget_option_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 && isset( $data['title'] ) && isset( $data['short_desc'] ) && isset( $data['display_name'] ) && isset( $data['subscribers_group'] ) ) {
						$title        = ! empty( $data['title'] ) ? $data['title'] : '';
						$name_visible = ( ! empty( $data['display_name'] ) && 'yes' === strtolower( $data['display_name'] ) ) ? 'yes' : 'no';
						$desc         = ! empty( $data['short_desc'] ) ? $data['short_desc'] : '';
						$list_id      = ! empty( $data['subscribers_group'] ) ? $data['subscribers_group'] : '';

						if ( empty( $list_id ) ) {
							$list_id = 1;
						}

						$name = 'Widget - ' . $title;

						$data = array(
							'name'          => $name,
							'desc'          => $desc,
							'name_visible'  => $name_visible,
							'name_required' => 'no',
							'list_visible'  => 'no',
							'lists'         => array( $list_id ),
							'af_id'         => 0
						);

						$prepared_form_data = ES_Forms_Table::prepare_form_data( $data );

						$logger = get_ig_logger();
						$logger->info( 'Widges DAta.....' . print_r( $prepared_form_data, true ) );

						$inserted_form_id = ES_DB_Forms::add_form( $prepared_form_data );

						$data_to_set = array(
							'title'   => $title,
							'form_id' => $inserted_form_id
						);

						$es_4_widget_option_data[ $key ] = $data_to_set;
					}
				}

				update_option( $latest_widget_option, $es_4_widget_option_data );
			}
		}

		//Update sidebars_widgets options.
		$sidebars_widgets = get_option( 'sidebars_widgets', '' );
		if ( ! empty( $sidebars_widgets ) ) {
			$widgets_data = maybe_unserialize( $sidebars_widgets );

			if ( is_array( $widgets_data ) && count( $widgets_data ) > 0 ) {
				foreach ( $widgets_data as $key => $data ) {
					if ( is_array( $data ) && count( $data ) > 0 ) {
						foreach ( $data as $k => $v ) {
							if ( strstr( $v, 'email-subscribers-' ) ) {
								$v                          = str_replace( 'email-subscribers-', 'email-subscribers-form-', $v );
								$widgets_data[ $key ][ $k ] = $v;
							}

							if ( strstr( $v, 'email_subscriber_widget-' ) ) {
								$v                          = str_replace( 'email_subscriber_widget-', 'email-subscribers-form-', $v );
								$widgets_data[ $key ][ $k ] = $v;
							}
						}
					}
				}

				update_option( 'sidebars_widgets', $widgets_data );
			}

		}

	}

	public static function temp_filter_category( $category ) {
		return trim( trim( $category, '##' ), '' );
	}

	public static function show_message( $message = '', $status = 'success', $is_dismissible = true ) {

		$class = 'notice notice-success';
		if ( 'error' === $status ) {
			$class = 'notice notice-error';
		}

		if ( $is_dismissible ) {
			$class .= ' is-dismissible';
		}

		echo "<div class='{$class}'><p>{$message}</p></div>";
	}

	public static function prepare_main_header_navigation( $navigation_tabs ) {

		foreach ( $navigation_tabs as $key => $navigation ) {
			$action          = ! empty( $navigation['action'] ) ? $navigation['action'] : '';
			$action_label    = ! empty( $navigation['label'] ) ? $navigation['label'] : '';
			$url             = ! empty( $navigation['url'] ) ? $navigation['url'] : '';
			$indicator_class = ! empty( $navigation['indicator_type'] ) ? 'ig-es-indicator-' . $navigation['indicator_type'] : 'ig-es-indicator-new';
			$show_indicator  = ! empty( $navigation['indicator_option'] ) ? ( ( get_option( $navigation['indicator_option'], 'yes' ) === 'yes' ) ? true : false ) : false;
			$indicator_label = ! empty( $navigation['indicator_label'] ) ? $navigation['indicator_label'] : '';
			$is_imp          = ! empty( $navigation['is_imp'] ) ? $navigation['is_imp'] : false;
			?>

            <a href="<?php echo $url; ?>" class="page-title-action<?php if ( $is_imp ) {
				echo " es-imp-button";
			} ?>"><?php echo $action_label; ?>
				<?php if ( $show_indicator ) { ?>
                    <span class="ig-es-indicator <?php echo $indicator_class; ?>">
                            <?php echo $indicator_label ?>
                        </span>

				<?php } ?>
            </a>
		<?php }
	}

	public static function prepare_information_box( $info, $content_html ) {

		$default_args = array(
			'type'       => 'info',
			'center'     => true,
			'box_shadow' => true,
			'show_icon'  => true
		);

		$info = wp_parse_args( $info, $default_args );

		$type          = $info['type'];
		$show_icon     = $info['show_icon'];
		$is_center     = $info['center'];
		$is_box_shadow = $info['box_shadow'];

		$div_class = 'ig-es-information-box';
		if ( $is_center ) {
			$div_class .= ' ig-es-center';
		}

		if ( $is_box_shadow ) {
			$div_class .= ' ig-es-box-shadow';
		}

		if ( $type ) {
			$div_class .= ' ig-es-' . $type;
		}

		?>

        <div class="<?php echo $div_class; ?>">
            <div class="ig-vertical-align">
				<?php if ( $show_icon ) { ?>
                    <div class="ig-es-icon text-center">
                        <span class="dashicons ig-es-icon-<?php echo $type; ?>"></span>
                    </div>
				<?php } ?>
                <div class="ig-es-info-message">
					<?php echo $content_html; ?>
                </div>
            </div>
        </div>

		<?php
	}

	/**
	 * Render Quick Feedback Widget
	 *
	 * @param $params
	 *
	 */
	public static function render_feedback_widget( $params ) {
		global $ig_es_feedback;

		$feedback = $ig_es_feedback;

		$default_params = array(
			'set_transient' => true,
			'force'         => false
		);

		$params = wp_parse_args( $params, $default_params );

		if ( ! empty( $params['event'] ) ) {

			$event = $feedback->event_prefix . $params['event'];
			$force = ! empty( $params['force'] ) ? $params['force'] : false;

			$can_show = false;

			if ( $force ) {
				$can_show = true;
			} else {
				if ( ! $feedback->is_event_transient_set( $event ) ) {
					$can_show = true;

					$feedback_data = $feedback->get_event_feedback_data( $feedback->plugin_abbr, $event );
					if ( count( $feedback_data ) > 0 ) {
						$feedback_data          = array_reverse( $feedback_data );
						$last_feedback_given_on = $feedback_data[0]['created_on'];
						if ( strtotime( $last_feedback_given_on ) > strtotime( '-45 days' ) ) {
							$can_show = false;
						}
					}
				}
			}

			if ( $can_show ) {
				if ( 'star' === $params['type'] ) {
					$feedback->render_stars( $params );
				} elseif ( 'emoji' === $params['type'] ) {
					$feedback->render_emoji( $params );
				} elseif ( 'feedback' === $params['type'] ) {
					$feedback->render_general_feedback( $params );
				}
			}
		}

	}

	public static function get_all_settings() {

		global $wpdb;

		$query = "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'ig_es_%' AND option_name != 'ig_es_managed_blocked_domains' ";

		$results = $wpdb->get_results( $query, ARRAY_A );

		$options_name_value_map = array();
		if(count($results) > 0 ) {
		    foreach ($results as $result) {
			    $options_name_value_map[$result['option_name']] = $result['option_value'];
            }
        }

		return $options_name_value_map;
	}

	public static function get_ig_es_meta_info() {

		$total_contacts           = ES_DB_Contacts::get_total_subscribers();
		$total_lists              = ES_DB_Lists::count_lists();
		$total_newsletters        = ES_DB_Campaigns::get_total_campaigns_by_type( 'newsletter' );
		$total_post_notifications = ES_DB_Campaigns::get_total_campaigns_by_type( 'post_notification' );

		$meta_info = array(
			'total_contacts'           => $total_contacts,
			'total_lists'              => $total_lists,
			'total_newsletters'        => $total_newsletters,
			'total_post_notifications' => $total_post_notifications,
			'settings'                 => self::get_all_settings()
		);

		return $meta_info;
	}

}
