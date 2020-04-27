<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Analytify_Settings' ) ) {

	class WP_Analytify_Settings {

		/**
		 * settings sections array
		 *
		 * @var array
		 */
		protected $settings_sections = array();

		/**
		 * Settings fields array
		 *
		 * @var array
		 */
		protected $settings_fields = array();


		public function __construct() {

			if ( current_user_can( 'manage_options' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				add_action( 'admin_init', array( $this, 'admin_init' ) );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_post_analytify_manual_code', array( $this, 'analytify_manual_code' ) );
			add_action( 'admin_post_analytify_delete_cache', array( $this, 'analytify_delete_cache' ) );
		}

		/**
		 * Enqueue scripts and styles
		 */
		function admin_enqueue_scripts() {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_media();
		}

		/**
		 * Set settings sections
		 *
		 * @param array $sections setting sections array
		 */
		function set_sections( $sections ) {
			$this->settings_sections = $sections;

			return $this;
		}

		/**
		 * Add a single section
		 *
		 * @param array $section
		 */
		function add_section( $section ) {
			$this->settings_sections[] = $section;

			return $this;
		}

		/**
		 * Set settings fields
		 *
		 * @param array $fields settings fields array
		 */
		function set_fields( $fields ) {
			$this->settings_fields = $fields;

			return $this;
		}

		/**
		 * [add_field description]
		 *
		 * @param [type] $section [description]
		 * @param [type] $field   [description]
		 */
		function add_field( $section, $field ) {
			$defaults = array(
				'name'  => '',
				'label' => '',
				'desc'  => '',
				'type'  => 'text',
			);

			$arg                                 = wp_parse_args( $field, $defaults );
			$this->settings_fields[ $section ][] = $arg;

			return $this;
		}


		function get_settings_sections() {

			$tabs = array(
				array(
					'id'       => 'wp-analytify-authentication',
					'title'    => __( 'Authentication', 'wp-analytify' ),
					'priority' => '5',
				),
				array(
					'id'       => 'wp-analytify-profile',
					'title'    => __( 'Profile', 'wp-analytify' ),
					'desc'     => 'Select your profiles for front-end and back-end sections.',
					'priority' => '10',
				),

				array(
					'id'       => 'wp-analytify-admin',
					'title'    => __( 'Admin', 'wp-analytify' ),
					'desc'     => 'Following settings will take effect statistics under the posts, custom post types or pages.',
					'priority' => '20',
				),
				array(
					'id'       => 'wp-analytify-advanced',
					'title'    => __( 'Advanced', 'wp-analytify' ),
					'desc'     => 'Configure the following settings for advanced analytics tracking.',
					'priority' => '35',
				),
				array(
					'id'       => 'wp-analytify-help',
					'title'    => __( 'Help', 'wp-analytify' ),
					'priority' => '45',
				),
			);

			$setting_tabs = apply_filters( 'wp_analytify_pro_setting_tabs', $tabs );

			usort(
				$setting_tabs, function( $a, $b ) {
					return $a['priority'] - $b['priority'];
				}
			);

			return $setting_tabs;
		}


		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {

			if ( isset( $_GET['page'] ) && 'analytify-settings' === $_GET['page'] ) {
				$_profile_otions = WP_ANALYTIFY_FUNCTIONS::fetch_profiles_list_summary();
			} else {
				$_profile_otions = array();
			}

			$settings_fields = array(
				'wp-analytify-authentication' => array(
					// array(
					// 	'name'    => 'exclude_users_tracking',
					// 	'label'   => __( 'Exclude users from tracking', 'wp-analytify' ),
					// 	'desc'    => __( 'Don\'t insert the tracking code for the above user roles.', 'wp-analytify' ),
					// 	'type'    => 'chosen',
					// 	'default' => array(),
					// 	'options' => $this->get_current_roles(),
					// ),
				),
				'wp-analytify-profile'        => array(
					array(
						'name'  => 'install_ga_code',
						'label' => __( 'Install Google Analytics tracking code', 'wp-analytify' ),
						'desc'  => apply_filters( 'analytify_install_ga_text', __( 'Insert Google Analytics (GA) JavaScript code between the HEADER tags in your website. Uncheck this option if you have already inserted the GA code.', 'wp-analytify' ) ),
						'type'  => 'checkbox',
					),
					array(
						'name'    => 'exclude_users_tracking',
						'label'   => __( 'Exclude users from tracking', 'wp-analytify' ),
						'desc'    => __( 'Don\'t insert the tracking code for the above user roles.', 'wp-analytify' ),
						'type'    => 'chosen',
						'default' => array(),
						'options' => $this->get_current_roles(),
					),

					array(
						'name'    => 'profile_for_posts',
						'label'   => __( 'Profile for posts (Backend/Front-end)', 'wp-analytify' ),
						'desc'    => __( 'Select your Google Analytics website profile for Analytify front-end/back-end statistics.', 'wp-analytify' ),
						'type'    => 'select_profile',
						'default' => 'Choose profile for posts',
						'options' => $_profile_otions,
						'size'    => '',
					),

					array(
						'name'    => 'profile_for_dashboard',
						'label'   => __( 'Profile for dashboard', 'wp-analytify' ),
						'desc'    => __( 'Select your Google Analytics website profile for Analytify dashboard statistics.', 'wp-analytify' ),
						'type'    => 'select_profile',
						'default' => 'Choose profile for dashboard',
						'options' => $_profile_otions,
					),
					array(
						'name'  => 'hide_profiles_list',
						'label' => __( 'Hide profiles list', 'wp-analytify' ),
						'desc'  => __( 'Hide the selection of profiles for the back-end/front-end dashboard and posts. You might want to do this so clients cannot see other profiles available.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					// array(
					// 'name'              => 'track_user_data',
					// 'label'             => __( 'Allow Usage Tracking?', 'wp-analytify' ),
					// 'desc'              => __( 'Allow Analytify to <a href=\'https://wpbrigade.com/wordpress/plugins/non-sensitive-diagnostic-tracking/\' target=\'_blank\'>non-sensitive diagnostic tracking</a> and help us make the plugin even better.', 'wp-analytify' ),
					// 'type'              => 'checkbox',
					// 'tooltip'           => false,
					// ),
				),
				'wp-analytify-admin'          => array(
					array(
						'name'  => 'disable_back_end',
						'label' => __( 'Disable analytics under posts/pages (wp-admin)', 'wp-analytify' ),
						'desc'  => __( 'Enable if you don\'t want to load statistics on all pages by default. Remember, you can still view statistics under each post/page.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'    => 'show_analytics_roles_back_end',
						'label'   => __( 'Display analytics to roles (posts & pages)', 'wp-analytify' ),
						'desc'    => __( 'Show analytics under posts and pages to the above selected user roles only.', 'wp-analytify' ),
						'type'    => 'chosen',
						'default' => array(),
						'options' => $this->get_current_roles(),
					),

					array(
						'name'    => 'show_analytics_post_types_back_end',
						'label'   => __( 'Analytics on post types', 'wp-analytify' ),
						'desc'    => class_exists( 'WP_Analytify_Pro' ) ? __( 'Show Analytics under the above post types only', 'wp-analytify' ) : sprintf( __( 'Show analytics below these post types only. Buy %1$sPremium%1$s version for Custom Post Types.', 'wp-analytify' ), '<a href="' . analytify_get_update_link() . '" target="_blank">', '</a>' ),
						'type'    => 'chosen',
						'default' => array(),
						'options' => $this->get_current_post_types(),
					),

					array(
						'name'    => 'show_panels_back_end',
						'label'   => __( 'Edit posts/pages analytics panels', 'wp-analytify' ),
						'desc'    => class_exists( 'WP_Analytify_Pro' ) ? __( 'Select which statistic panels you want to display under posts/pages.', 'wp-analytify' ) : sprintf( __( 'Select which statistic panels you want to display under posts/pages. Only "General Stats" will visible in Free Version. Buy %1$sPremium%2$s version to see the full statistics.', 'wp-analytify' ), '<a href="' . analytify_get_update_link() . '" target="_blank">', '</a>' ),
						'type'    => 'chosen',
						'default' => array(),
						'options' => array(
							'show-overall-dashboard'    => __( 'General Stats', 'wp-analytify' ),
							'show-geographic-dashboard' => __( 'Geographic Stats', 'wp-analytify' ),
							'show-system-stats'         => __( 'System Stats', 'wp-analytify' ),
							'show-keywords-dashboard'   => __( 'Keywords Stats', 'wp-analytify' ),
							'show-social-dashboard'     => __( 'Social Media Stats', 'wp-analytify' ),
							'show-referrer-dashboard'   => __( 'Referrers Stats', 'wp-analytify' ),
							'show-what-happen-stats'    => __( 'Entrance Exits Stats', 'wp-analytify' ),
						),
					),
					array(
						'name'    => 'exclude_pages_back_end',
						'label'   => __( 'Exclude analytics on specific pages', 'wp-analytify' ),
						'desc'    => __( 'Enter a comma-separated list of the post/page ID\'s you do not want to display analytics for. For example: 21,44,66', 'wp-analytify' ),
						'type'    => 'text',
						'default' => '0',
					),
				),
				'wp-analytify-advanced'       => array(
					array(
						'name'  => 'user_advanced_keys',
						'label' => __( 'Do you want to use your own API keys ?', 'wp-analytify' ),
						'desc'  => sprintf( __( 'It is highly recommended by Google to use your own API keys. %1$sYou need to create a Project in Google %2$s. %3$sHere is a short %4$svideo guide%5$s to get your own ClientID, Client Secret and Redirect URL and enter them in below inputs.', 'wp-analytify' ), '<br />', '<a target=\'_blank\' href=\'https://console.developers.google.com/project\'>Console</a>', '<br />', '<a target=\'_blank\' href=\'https://analytify.io/custom-api-keys-video\'>', '</a>' ),
						'type'  => 'checkbox',
						'class' => 'user_advanced_keys',
					),

					array(
						'name'              => 'client_id',
						'label'             => __( 'Client ID', 'wp-analytify' ),
						'desc'              => __( 'Your Client ID', 'wp-analytify' ),
						'type'              => 'text',
						'class'             => 'user_keys',
						'sanitize_callback' => 'trim',
					),
					array(
						'name'              => 'client_secret',
						'label'             => __( 'Client secret', 'wp-analytify' ),
						'desc'              => __( 'Your Client Secret', 'wp-analytify' ),
						'type'              => 'text',
						'class'             => 'user_keys',
						'sanitize_callback' => 'trim',
					),
					array(
						'name'              => 'redirect_uri',
						'label'             => __( 'Redirect URL', 'wp-analytify' ),
						'desc'              => sprintf( __( '( Redirect URL is very important when you are using your own keys. Paste this into the above field: %1$s )', 'wp-analytify' ), '<b>' . admin_url( 'admin.php?page=analytify-settings' ) . '</b>' ),
						'type'              => 'text',
						'class'             => 'user_keys',
						'sanitize_callback' => 'trim',
					),

				),
			);

			// if ( get_option( 'pa_google_token' ) ) {
				$advance_setting_fields = array(
					array(
						'name'  => 'anonymize_ip',
						'label' => __( 'Anonymize IP addresses', 'wp-analytify' ),
						'desc'  => sprintf( __( 'Detailed information about IP anonymization in Google Analytics can be found %1$shere%2$s.', 'wp-analytify' ), '<a href=\'https://support.google.com/analytics/answer/2763052\'>', '</a>' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'force_ssl',
						'label' => __( 'Force Analytics Traffic Over SSL', 'wp-analytify' ),
						'desc'  => __( 'Analytics traffic will always be encrypted if your site uses HTTPS. Enable this option if you don’t use HTTPS and want your analytics to be encrypted.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'track_user_id',
						'label' => __( 'Track User ID', 'wp-analytify' ),
						'desc'  => sprintf( __( 'Detailed information about Track User ID in Google Analytics can be found %1$shere%2$s.', 'wp-analytify' ), '<a href=\'https://support.google.com/analytics/answer/3123662\'>', '</a>' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'demographic_interest_tracking',
						'label' => __( 'Demographic & Interest Tracking', 'wp-analytify' ),
						'desc'  => __( 'This allows you to view extra dimensions about users: Age, gender, affinity categories, in-market segments, etc.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => '404_page_track',
						'label' => __( 'Page Not Found (404)', 'wp-analytify' ),
						'desc'  => __( 'Track all 404 pages.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'javascript_error_track',
						'label' => __( 'JavaScript Errors', 'wp-analytify' ),
						'desc'  => __( 'Track all JavaScript errors.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'ajax_error_track',
						'label' => __( 'AJAX Errors', 'wp-analytify' ),
						'desc'  => __( 'Track all AJAX errors.', 'wp-analytify' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'linker_cross_domain_tracking',
						'label' => __( 'Setup Cross-domain Tracking', 'wp-analytify' ),
						'desc'  => sprintf( __( 'This will add the %1$s tag to your tracking code. Read this %2$sguide%3$s for more information.', 'wp-analytify' ), '<code>allowLinker:true</code>', '<a href=\'https:\//analytify.io/doc/setup-cross-domain-tracking-wordpress\'>', '</a>' ),
						'type'  => 'checkbox',
					),
					array(
						'name'  => 'custom_js_code',
						'label' => __( 'Custom JavaScript Code', 'wp-analytify' ),
						'desc'  => __( 'This will add inline tracking code before sending the pageview hit to Google Analytics.', 'wp-analytify' ),
						'type'  => 'textarea',
					),
				);

			foreach ( $advance_setting_fields as $advance_setting_field ) {
				array_push( $settings_fields['wp-analytify-advanced'], $advance_setting_field );
			}
			// }
			$settings_fields = apply_filters( 'wp_analytify_pro_setting_fields', $settings_fields );

			return $settings_fields;
		}


		/**
		 * Initialize and registers the settings sections and fileds to WordPress
		 *
		 * Usually this should be called at `admin_init` hook.
		 *
		 * This function gets the initiated settings sections and fields. Then
		 * registers them to WordPress and ready for use.
		 */
		function admin_init() {

			$this->set_sections( $this->get_settings_sections() );
			$this->set_fields( $this->get_settings_fields() );

			// register settings sections
			// creates our settings in the options table
			foreach ( $this->settings_sections as $section ) {
				register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
			}
		}

		function rendered_settings() {

			foreach ( $this->settings_sections as $section ) {

				if ( false == get_option( $section['id'] ) ) {
					add_option( $section['id'] );
				}

				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
					$callback        = call_user_func( array( $this, 'get_description' ), $section['desc'] );
				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				add_settings_section( $section['id'], '', $callback, $section['id'] );
			}

			  // register settings fields
			foreach ( $this->settings_fields as $section => $field ) {
				foreach ( $field as $option ) {

					$type = isset( $option['type'] ) ? $option['type'] : 'text';

					$args = array(
						'id'                => $option['name'],
						'label_for'         => $args['label_for'] = "{$section}[{$option['name']}]",
						'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
						'name'              => $option['label'],
						'section'           => $section,
						'size'              => isset( $option['size'] ) ? $option['size'] : null,
						'options'           => isset( $option['options'] ) ? $option['options'] : '',
						'std'               => isset( $option['default'] ) ? $option['default'] : '',
						'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
						'class'             => isset( $option['class'] ) ? $option['class'] : '',
						'type'              => $type,
						'tooltip'           => isset( $option['tooltip'] ) ? $option['tooltip'] : true,
					);

					add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], array( $this, 'callback_' . $type ), $section, $section, $args );
				}
			}

		}


		public static function get_current_post_types() {

			$post_types_list = array();

			$args = array(
				'public' => true,

			);

			$post_types = get_post_types( $args );

			foreach ( $post_types as $post_type ) {
				$post_types_list[ $post_type ] = $post_type;
			}

			return $post_types_list;
		}

		/**
		 * get current list of all roles and display in dropdown
		 *
		 * @return array
		 */
		public static function get_current_roles() {

			$roles = array();

			if ( get_editable_roles() > 0 ) {

				foreach ( get_editable_roles() as $role => $name ) {

					$roles[ $role ] = $name['name'];
				}
			} else {
				$roles['empty'] = 'no roles found';
			}

			return $roles;
		}


		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc = '';
			}

			return $desc;
		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_text( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type  = isset( $args['type'] ) ? $args['type'] : 'text';

			$html  = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"/>', $type, $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_button( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'button';
			$type  = isset( $args['type'] ) ? $args['type'] : 'text';

			$html  = sprintf( '<input type="%1$s" class="%2$s button-primary" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"/>', $type, $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_number( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_checkbox( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

			$html  = '<fieldset>';
			$html .= sprintf( '<label for="%1$s[%2$s]"></label>', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );

			$html .= sprintf( '<div class="toggle"><input type="checkbox" class="checkbox" id="%2$s[%3$s]" name="%2$s[%3$s]" value="on" %4$s /><span class="btn-nob"></span><span class="texts"></span><span class="bg"></span></div>', $args['class'], $args['section'], $args['id'], checked( $value, 'on', false ) );
			// $html  .= sprintf( '<input type="checkbox" class="checkbox" id="%2$s[%3$s]" name="%2$s[%3$s]" value="on" %4$s />', $args['class'], $args['section'], $args['id'], checked( $value, 'on', false ) );
			if ( $args['tooltip'] ) {
				$html .= sprintf( '<span class="dashicons dashicons-editor-help setting-more-info" title="%1$s"></span>', $args['desc'] );
			} else {
				$html .= $this->get_field_description( $args );
			}
			$html .= '</fieldset>';
				// $html =$args['desc'];
			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multicheck( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			$html  = '<fieldset>';

			foreach ( $args['options'] as $key => $label ) {
				$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
				$html   .= sprintf( '<label for="wp-analytify-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html   .= sprintf( '<input type="checkbox" class="checkbox" id="wp-analytify-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
				$html   .= sprintf( '%1$s</label><br>', $label );
			}

			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_radio( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			$html  = '<fieldset>';

			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<label for="wp-analytify-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html .= sprintf( '<input type="radio" class="radio" id="wp-analytify-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
				$html .= sprintf( '%1$s</label><br>', $label );
			}

			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_select( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}

			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a multi selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multi_select( $args ) {

			$value =  ( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = '';
			foreach ( $args['options']['main'] as $key => $label ) {
				$html .= '<div class="analytify-multiselect-container">';
				$html .= '<span class="analytify-multiselect-label">' . $label . '</span>';
				$html .= sprintf( '<select class="%1$s" name="%2$s[%3$s]['. $key .']" id="%2$s[%3$s][value]">', $size, $args['section'], $args['id'] );
				foreach ( $args['options']['value'][$key] as $k => $v ) {
					$html .= sprintf( '<option value="%s"%s>%s</option>', $k, selected( $k, $value[$key], false ), $v );
				}
				$html .= sprintf( '</select>' );
				$html .= '</div>';
			}

			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a chosen selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_chosen( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			// var_dump($this->get_option( $args['id'], $args['section'], $args['std'] ));
			$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$html = sprintf( '<select multiple class="%1$s analytify-chosen" name="%2$s[%3$s][]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

			foreach ( $args['options'] as $key => $label ) {
				$selected = in_array( $key, $value ) ? 'selected = selected' : '';
				$html    .= sprintf( '<option value="%s"%s>%s</option>', $key, $selected, $label );
			}

			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_select_profile( $args ) {

			if ( $exception = $GLOBALS['WP_ANALYTIFY']->get_exception() ) {
				if ( $exception[0]['reason'] == 'dailyLimitExceeded' ) {
					$link = 'https://analytify.io/doc/fix-403-daily-limit-exceeded/';
					printf( __( '%5$s%1$sDaily Limit Exceeded:%2$s This Indicates that user has exceeded the daily quota (either per project or per view (profile)). Please %3$sfollow this tutorial%4$s to fix this issue. let us know this issue (if it still doesn\'t work) in the Help tab of Analytify->settings page.%6$s', 'wp-analytify' ), '<b>', '</b>', '<a href="' . $link . '" target="_blank">', '</a>', '<p class="description" style="color:#ed1515">', '</p>' );
					return;
				} elseif ( $exception[0]['reason'] == 'insufficientPermissions' && $exception[0]['domain'] == 'global' ) {
					echo '<p class="description" style="color:#ed1515">No Website is registered with your Email at <a href="https://analytics.google.com/">Google Analytics</a>.<br /> Please setup your site first, Check out this guide <a href="https://analytify.io/setup-account-google-analytics/">here</a> to setup it properly.</p>';
					return;
				}
			}

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$html  = sprintf( '<select class="%1$s analytify-chosen" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

			$_analytify_setting = get_option( 'wp-analytify-profile' );
			if ( isset( $_analytify_setting['hide_profiles_list'] ) && $_analytify_setting['hide_profiles_list'] === 'on' ) {

				$html .= '<option value="' . $value . '" selected>' . WP_ANALYTIFY_FUNCTIONS::search_profile_info( $value, 'websiteUrl' ) . ' (' . WP_ANALYTIFY_FUNCTIONS::search_profile_info( $value, 'name' ) . ')' . '</option>';
			} else {

				if ( isset( $args['options']->items ) ) {

					$html .= '<option value="">' . $args['std'] . '</option>';
					foreach ( $args['options']->getItems() as  $account ) {

						foreach ( $account->getWebProperties() as  $property ) {

							$html .= '<optgroup label=" ' . $property->getName() . ' ">';

							foreach ( $property->getProfiles() as $profile ) {
								$html .= sprintf( '<option value="%1$s" %2$s>%3$s (%4$s)</option>', $profile->getId(), selected( $value, $profile->getId(), false ), $profile->getName(), $property->getId() );

								// Update the UA code in option on setting save for proile_for_posts.
								if ( $value === $profile->getId() && 'profile_for_posts' === $args['id'] ) {
									update_option( 'analytify_ua_code', $property->getId() );
								}
							}
						}

						$html .= '</optgroup>';
					}
				}
				// else{
				// $html .= '<option value="">no profiles found</option>';
				// }
			}

			$html .= sprintf( '</select>' );

			// If no website is registered on Google Analytics for this user, Show this warning message.
			if ( ! $args['options'] ) {
				$html .= '<p class="description" style="color:#ed1515">No Website is registered with your Email at <a href="https://analytics.google.com/">Google Analytics</a>.<br /> Please setup your site first, Check out this guide <a href="https://analytify.io/setup-account-google-analytics/">here</a> to setup it properly.</p>';
			} else {
				// Show description.
				$html .= $this->get_field_description( $args );
			}

			echo $html;
		}


		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_textarea( $args ) {

			$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 * @return string
		 */
		function callback_html( $args ) {
			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_wysiwyg( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

			echo '<div style="max-width: ' . $size . ';">';

			$editor_settings = array(
				'teeny'         => true,
				'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
				'textarea_rows' => 10,
			);

			if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
				$editor_settings = array_merge( $editor_settings, $args['options'] );
			}

			wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

			echo '</div>';

			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a image upload field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_image( $args ) {

			$value   = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size    = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id      = $args['section'] . '[' . $args['id'] . ']';
			$label   = isset( $args['options']['button_label'] ) ?
			$args['options']['button_label'] :
			__( 'Choose Image' );
			$img     = wp_get_attachment_image_src( $value );
			$img_url = $img ? $img[0] : '';
			$html    = sprintf( '<input type="hidden" class="%1$s-text wpsa-image-id" id="%2$s" name="%2$s" value="%3$s"/>', $size, $id, $value );
			$html   .= '<p class="wpsa-image-preview"><img src="' . $img_url . '" /></p>';
			$html   .= '<input type="button" class="button wpsa-image-browse" value="' . $label . '" />';
			$html   .= '<input type="button" class="button analytify_email_clear" value="Remove Logo" />';
			$html   .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_file( $args ) {
			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' );
			$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s" name="%2$s" value="%3$s"/>', $size, $id, $value );
			$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );
			echo $html;
		}


		/**
		 * Displays a password field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_password( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_color( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * [callback_authentication description]
		 *
		 * @return [type] [description]
		 */
		function callback_authentication() {

			$output = '';

			ob_start();

			echo '<div class="inside">Set up a liaison between Analytify and your Google Analytics account.</div>';

			if ( get_option( 'pa_google_token' ) ) { ?>

				<div class="inside"><?php //esc_html_e( 'You have allowed your site to access the data from Google Analytics. Logout below to disconnect it.', 'wp-analytify' ); ?></div>

				<table class="form-table">
					<form action="" method="post">
						<tbody>
							<tr>

								<th scope="row"><label class="pt-20">Google Authentication</label></th>
								<td>
									<input type="submit" class="button-primary" value="Logout" name="wp_analytify_log_out" />
									<p class="description">You have allowed your site to access the data from your Google Analytics account. Click on logout button to disconnect or re-authenticate.</p>
								</td>
							</tr>
						</tbody>
					</form>
				</table>

				<?php
			} else {
				?>


				<table class="form-table">
					<tbody>
						<tr>

							<th scope="row"><label class="pt-20">Google Authentication</label></th>
							<td>
								<a target="_self" title="Log in with your Google Analytics Account" class="button-primary authentication_btn" href="https://accounts.google.com/o/oauth2/auth?<?php echo WP_ANALYTIFY_FUNCTIONS::generate_login_url(); ?>"><?php esc_html_e( 'Log in with your Google Analytics Account', 'wp-analytify' ); ?></a>
								<p class="description">It is required to <a href="https://analytify.io/setup-account-google-analytics/">Set up</a> your account and a website profile at <a href="https://analytics.google.com/">Google Analytics</a> to see Analytify Dashboard reports.</p>
							</td>
						</tr>
					</tbody>
				</table>

			<?php
			}

			?>
			<?php $ua_code = get_option( 'analytify_manual_ua_code' ); ?>
			 <!-- <form class="" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label>Add Manual UA Code</label></th>
							<td>
								<input type="text" class="regular-text"  name="analytify-manual-ua-code" value="<?php // echo $ua_code != '' ? $ua_code : ''; ?>">
								<p class="description">Don't insert the tracking code for the above user roles.</p>
								<?php // echo submit_button(); ?>
							</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" name="action" value="analytify_manual_code">
			 </form> -->
			<?php

			$output .= ob_get_contents();
			ob_end_clean();
			echo $output;
		}


		/**
		 * Help tab content
		 *
		 * @since 2.0
		 */
		function callback_help() {
			?>

		<div class="wrap">

			<?php
			if ( has_action( 'anlytify_pro_support_tab' ) ) {

				do_action( 'anlytify_pro_support_tab' );

			} else {
			?>

						<h3><?php esc_html_e( 'Support', 'wp-analytify' ); ?></h3>

						<p><?php echo sprintf( esc_html__( 'As this is a free plugin, Post all of your questions to the %1$s WordPress.org support forum %2$s. Response time can range from a couple of days to a week as this is a free support.', 'wp-analytify' ), '<a href="http://wordpress.org/support/plugin/wp-analytify/" target="_blank">', '</a>' ); ?></p>

						<p class="upgrade-to-pro"><?php echo sprintf( esc_html__( 'If you want a %1$s timely response via email from a developer %2$s who works on this plugin, %3$s upgrade to WP Analytify Pro %4$s and send us an email.', 'wp-analytify' ), '<strong>', '</strong>', '<a href="' . analytify_get_update_link( 'https://analytify.io/', '?utm_source=analytify-lite&amp;utm_medium=help-tab&amp;utm_content=support-upgrade&amp;utm_campaign=pro-upgrade' ) . '" target="_blank">', '</a>' ); ?></p>

						<p><?php echo sprintf( esc_html__( 'If you\'ve found a bug, please %1$s submit an issue at Github %2$s.', 'wp-analytify' ), '<a href="https://github.com/hiddenpearls/wp-analytify/issues" target="_blank">', '</a>' ); ?></p>

					<?php } ?>

			</div>

			<div class="wp-analytify-delete-cache">
				<form class="" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
					<input type="hidden" name="action" value="analytify_delete_cache">
					<button type="submit" class="wp-analytify-delete-cache-button button">Delete Cache</button>
				</form>
			</div>

			<div class="wp-analytify-debug">
					<h3><?php esc_html_e( 'Diagnostic Information', 'wp-analytify' ); ?></h3>
				<textarea class="debug-log-textarea" autocomplete="off" readonly="" id="debug-log-textarea"></textarea>

				<div class="wp-analytify-view-error-log">
					<a class="button" href="<?php echo admin_url( 'admin.php?page=analytify-logs' ); ?>">View Error Logs</a>
				</div>
			</div>

			<div class="wp-analytify-video-container">
				<h3><?php esc_html_e( 'Videos', 'wp-analytify' ); ?></h3>

				<ul>
					<li>
						<h4>Generate Custom API Keys</h4>
						<p>It is highly recommended by Google to use your own API keys. You need to create a Project in Google Console. Here is a short video guide to get your own ClientID, Client Secret and Redirect URL to use in Advanced Tab.</p>
						<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/UzI_XLlkefY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</li>
					<li>
						<h4>How to Install Pro version</h4>
						<p>Analytify Core version from wordpress.org is the base (required) to use all the addons (Free and Paid) and Analytify Pro version.<br />Check out the Analytify Pro <a href="https://analytify.io/features/">features</a>.</p>
						<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/D02R6eP3olM" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</li>
					<li>
						<h4>View Google Analytics under Posts and Pages in WordPress</h4>
						<p>This video explains how to check the stats of each page in WordPress.</p>
						<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/6BnJiTOgCrE" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</li>
				</ul>

			</div>

			<?php
		}

		function callback_email_form() {
			?>
			<form class="" action="" method="post">
				<input type="submit" name="test_email" class="analytify_test_email_btn" value="<?php _e( 'Test Email', 'wp-analytify' ); ?>" />
				<span class="analytify_setting_note"><?php _e( 'Note: Please save changes before sending a test email.', 'wp-analytify' ); ?></span>
			</form>
			<?php
		}

		/**
		 * Sanitize callback for Settings API
		 */
		function sanitize_options( $options ) {

			if ( ! $options ) {
				return; }

			foreach ( $options as $option_slug => $option_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $option_slug );

				// If callback is set, call it
				if ( $sanitize_callback ) {
					$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
					continue;
				}
			}

			return $options;
		}

		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug
		 *
		 * @return mixed string or bool false
		 */
		function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback
			foreach ( $this->settings_fields as $section => $options ) {
				foreach ( $options as $option ) {
					if ( $option['name'] != $slug ) {
						continue;
					}

					// Return the callback name
					return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
				}
			}

			return false;
		}

		/**
		 * Get the value of a settings field
		 *
		 * @param string $option  settings field name
		 * @param string $section the section name this field belongs to
		 * @param string $default default text if it's not found
		 * @return string
		 */
		function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * [show_tabs description]
		 *
		 * @return [type] [description]
		 */
		function show_tabs() {

			// var_dump(get_option( "wp-analytify-profile" ));
			$html = '<div class="wpa-tab-wrapper" ><h2 class="analytify_nav_tab_wrapper nav-tab-wrapper">';

			foreach ( $this->settings_sections as $tab ) {
				$html .= sprintf( '<a href="#%1$s" class="analytify_nav_tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
			}

			if ( ! class_exists( 'WP_Analytify_Pro' ) ) {
				$html .= sprintf( '<a href="%1$s" class="wp-analytify-premium" target="_blank"><span class="dashicons dashicons-star-filled"></span>%2$s</a>', analytify_get_update_link( '', '?utm_source=analytify-lite&amp;utm_medium=tab&amp;utm_campaign=pro-upgrade' ), 'Upgrade to Pro for More Features' );
			}

			$html .= '</h2></div>';

			echo $html;
		}

		/**
		 * Get Section Description
		 *
		 * @param  string $desc [description]
		 *
		 * @since 2.1.11
		 */
		function get_description( $desc ) {

			return $desc;
		}


		/**
		 * Prints out all settings sections added to a particular settings page
		 *
		 * @since 2.1.11
		 */
		function do_settings_sections( $page ) {
			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections ) || ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
				echo "<h3>{$section['title']}</h3>\n";
				echo $section['callback'];
				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}
				echo '<table class="form-table">';
				do_settings_fields( $page, $section['id'] );
				echo '</table>';
			}
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every sections in a different form
		 */
		function show_forms() {

			$this->process_logout();
			settings_errors();
			$is_authenticate = get_option( 'pa_google_token' );
			?>
			<div class="metabox-holder">
			<?php foreach ( $this->settings_sections as $form ) { ?>
				<?php
				if ( ! $is_authenticate && ( $form['id'] === 'wp-analytify-profile' || $form['id'] === 'wp-analytify-front' || $form['id'] === 'wp-analytify-admin' || $form['id'] === 'wp-analytify-dashboard' ) ) {
					$class = 'analytify_not_authenticate';
				} else {
					$class = '';
				}

				?>

			<div id="<?php echo $form['id']; ?>" class="group <?php echo $class; ?>" style="display: none;">

				<?php

				if ( $form['id'] === 'wp-analytify-authentication' ) {

					$this->callback_authentication();	?>

					<?php

				} elseif ( $form['id'] === 'wp-analytify-license' ) {

					// Call license tab display data from Pro.
					do_action( 'wp_analytify_license_tab' );

				} elseif ( $form['id'] === 'wp-analytify-help' ) {

					$this->callback_help();

				} elseif ( $form['id'] === 'wp-analytify-advanced' ) {
					?>

					<form method="post" action="options.php">
						<?php
							// do_action( 'wsa_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						$this->do_settings_sections( $form['id'] );
							// do_action( 'wsa_form_bottom_' . $form['id'], $form );
						?>
						<div style="padding-left: 10px">
							<?php submit_button(); ?>
						</div>
					</form>

					<?php
				} else {

					if ( ! $is_authenticate && ( $form['id'] === 'wp-analytify-profile' || $form['id'] === 'wp-analytify-front' || $form['id'] === 'wp-analytify-admin' || $form['id'] === 'wp-analytify-dashboard' ) ) {
						echo "<span class='analytify_need_authenticate_first'><a href='#'>You have to Authenticate the Google Analytics first.</a></span>";
					}

					// if ( get_option( 'pa_google_token' ) ) {
						?>

						<form method="post" action="options.php">
							<?php
								// do_action( 'wsa_form_top_' . $form['id'], $form );
							settings_fields( $form['id'] );
							$this->do_settings_sections( $form['id'] );
								// do_action( 'wsa_form_bottom_' . $form['id'], $form );
							?>
							<div style="padding-left: 10px">
								<?php submit_button(); ?>
							</div>
						</form>

						<?php

						if ( $form['id'] === 'wp-analytify-email' ) {
								$this->callback_email_form();
						}

						// }
				}
				?>

			</div>
			<?php } ?>

				</div>
				<?php
				echo '</div>';
				// echo $this->pro_features();
		}

		// unset profiles & hidden check on logout
		function process_logout() {

			if ( isset( $_POST['wp_analytify_log_out'] ) ) {

				$_analytify_profile = get_option( 'wp-analytify-profile' );

				unset( $_analytify_profile['hide_profiles_list'] );
				unset( $_analytify_profile['profile_for_posts'] );
				unset( $_analytify_profile['profile_for_dashboard'] );

				update_option( 'wp-analytify-profile', $_analytify_profile );
				delete_option( 'analytify_profile_exception' );
				delete_option( 'profiles_list_summary_backup' );
				delete_transient( 'analytify_quota_exception' );
			}

		}

		function pro_features() {

			$html = '	<div class="pro-feature-wrapper" >';
			 ob_start();
			?>

				<p > Tweet us <a href="https://twitter.com/analytify" style="text-decoration:none;"> @analytify </a> and Like us <a href="https://fb.com/analytify" style="text-decoration:none;">@analytify</a> </p>
				<table class="wa_feature_table">
					<tbody>
						<tr>
							<th>Features</th>
							<th>Free</th>
							<th>Pro</th>
						</tr>
						<tr>
							<td><strong>Support</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>
						<tr>
							<td><strong>Dashboard</strong></td>
							<td>Yes (limited)</td>
							<td>Yes (Advanced)</td>
						</tr>
						<tr>
							<td><strong>Live Stats</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>
						<tr>
							<td><strong>Comparison Stats</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>
						<tr>
							<td><strong>ShortCodes</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>

						<tr>
							<td><strong>Extensions</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>
						<tr>
							<td><strong>Analytics under Posts (admin)</strong></td>
							<td>Yes (limited)</td>
							<td>Yes (Advanced)</td>
						</tr>
						<tr>
							<td><strong>Analytics under Pages (admin)</strong></td>
							<td>Yes (limited)</td>
							<td>Yes (Advanced)</td>
						</tr>
						<tr>
							<td><strong>Analytics under Custom Post Types (front/admin)</strong></td>
							<td>No</td>
							<td>Yes</td>
						</tr>
					</tbody>
				</table>
				<div class="postbox-container side">
					<div class="metabox-holder">


						<div class="grids_auto_size wpa_side_box" style="width: 100%;">
							<div class="grid_title cen"> UPGRADE to PRO </div>

							<div class="grid_footer cen" style="background-color:white;">
								<a href="http://analytify.io/upgrade-from-free" title="Analytify Support">Buy Now</a> the PRO version of Analytify and get tons of benefits including premium features, support and updates.
							</div>
						</div>
						<div class="grids_auto_size wpa_side_box" style=" width: 100%;">
							<div class="grid_footer cen">
								made with ♥ by <a href="http://wpbrigade.com" title="WPBrigade | A Brigade of WordPress Developers." />WPBrigade</a>
							</div>
						</div>
					</div>
				</div>


			<?php
			$inner_html = ob_get_clean();
			$html      .= apply_filters( 'free-pro-features', $inner_html );
			echo '</div>';
			return $html;
		}

		/**
		 * Save the manuall UA code.
		 *
		 * @since 2.1.23
		 */
		function analytify_manual_code() {
			status_header( 200 );
			$ua = trim( $_REQUEST['analytify-manual-ua-code'] );
			update_option( 'analytify_manual_ua_code', sanitize_text_field( wp_unslash( $ua ) ) );
			wp_redirect( admin_url( 'admin.php?page=analytify-settings' ) );
			die();
		}

		/**
		 * Delete Stats Cache.
		 *
		 * @since 2.1.23
		 */
		function analytify_delete_cache() {
			status_header( 200 );
			delete_transient( 'analytify_quota_exception' );
			global $wpdb;
			$wpdb->query( "DELETE FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE ('%\_analytify_transient\_%')" );
			wp_redirect( admin_url( 'admin.php?page=analytify-settings' ) );
			die();
		}



	}

}
