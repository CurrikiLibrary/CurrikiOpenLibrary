<?php
/**
 * Initializes Deleting comments from resources section
 * 
 * Makes singleton of Resource Comments. Enqueues CSS, JS. Adds Menu link. Makes tabs. Registers ajax to delete comments
 *
 * @author     Ali Mehdi
 */



class CMPResourceComments {
	private static $cmp_instance;

	private function __construct() {
            
		
		add_action( 'admin_enqueue_scripts', array( $this, 'load_cusom_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_css' ) );
			
		if ( is_admin() ) {
			require_once( CMP_PATH . 'functions/resource_comments/includes/admin/admin-pages.php' );
			require_once( CMP_PATH . 'functions/resource_comments/includes/admin/admin-functions.php' );
			
			/** Settings Pages **/
			add_action( 'admin_menu', array( $this, 'cmp_setup_admin_menu' ), 1000, 0 );
                        
                        add_action('wp_ajax_nopriv_resource_comments', array( $this, 'loadResourceCommentsAjax' ));
                        add_action('wp_ajax_resource_comments', array( $this, 'loadResourceCommentsAjax' ));
		
                        add_action('wp_ajax_nopriv_delete_comment', array( $this, 'deleteResourceCommentsAjax' ));
                        add_action('wp_ajax_delete_comment', array( $this, 'deleteResourceCommentsAjax' ));
		
                        
		}
	}

	/**
	 * Get the singleton instance of our plugin
	 * @return class The Instance
	 * @access public
	 */
	public static function getInstance() {
		if ( !self::$cmp_instance ) {
			self::$cmp_instance = new CMPResourceComments();
		}

		return self::$cmp_instance;
	}
	

	/**
	 * Queue up the JavaScript file for the admin page, only on our admin page
	 * @param  string $hook The current page in the admin
	 * @return void
	 * @access public
	 */
	public function load_cusom_js( $hook ) {
		if ( 'curriki-admin_page_resource_comments' != $hook )
			return;
                wp_enqueue_script( 'cmp_angular_js', CMP_URL.'/assets/bower_components/angular/angular.min.js', 'jquery', CMP_VERSION, true );
		wp_enqueue_script( 'cmp_foundation_js', CMP_URL.'/assets/foundation/js/vendor/foundation.min.js', 'jquery', CMP_VERSION, true );
		wp_enqueue_script( 'cmp_bootstrap_js', CMP_URL.'/functions/resource_comments/includes/scripts/ui-bootstrap-tpls-0.3.0.min.js', 'jquery', CMP_VERSION, true );
                wp_enqueue_script( 'cmp_core_custom_js', CMP_URL.'/functions/resource_comments/includes/scripts/cmp_custom.js', 'jquery', CMP_VERSION, true );
		
	}
        
        /**
         * Loading up the css on Resource Comments section
         * @param string $hook The current page in the admin
         */
        public function load_custom_css($hook){
            if ( 'curriki-admin_page_resource_comments' != $hook )
			return;
            wp_enqueue_style( 'cmp_foundation_css', CMP_URL.'/assets/foundation/css/foundation.min.css', array(), CMP_VERSION );
            wp_enqueue_style( 'cmp_css', CMP_URL.'/css/styles.css', array(), CMP_VERSION );
            wp_enqueue_style( 'cmp_jquery_ui_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), CMP_VERSION );
            wp_enqueue_style( 'cmp_datepicker_css', CMP_URL.'/assets/datepicker.css', array(), CMP_VERSION );
        }

	/**
	 * Add the Resource Comments item to the Settings menu
	 * @return void
	 * @access public
	 */
	public function cmp_setup_admin_menu() {
            add_submenu_page('curriki_admin', __( 'Resource Comments', CMP_CORE_TEXT_DOMAIN ), __( 'Resource Comments', CMP_CORE_TEXT_DOMAIN ), 'curriki_admin', 'resource_comments', array( $this, 'determine_tab' ));
	}

	/**
	 * Determines what tab is being displayed, and executes the display of that tab
	 * @return void
	 * @access public
	 */
	public function determine_tab() {
            
		?>
		<div id="icon-options-general" class="icon32"></div><h2><?php _e( 'Resource Comments', CMP_CORE_TEXT_DOMAIN ); ?></h2>
		<?php
		$current = ( !isset( $_GET['tab'] ) ) ? 'comments' : $_GET['tab'];
		$default_tabs = array(
				'comments' => __( 'Comments', CMP_CORE_TEXT_DOMAIN ),
			);

		

		$tabs = apply_filters( 'cmp_settings_tabs', $default_tabs );

		?><h2 class="nav-tab-wrapper"><?php
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=resource_comments&tab=$tab'>$name</a>";
		}
		?>
		</h2>
		<div class="wrap">
		<?php
		if ( !isset( $_GET['tab'] ) || $_GET['tab'] == 'comments' ) {
			cmp_admin_page();
		} else {
			// Extension Devs - Your function that shows the tab content needs to be prefaced with 'cmp_display_' in order to work here.
			$tab_function = 'cmp_display_'.$_GET['tab'];
			$tab_function();
		}
		?>
		</div>
		<?php
	}
        
        /**
	 * Fetching the comments based on pageurl
	 * @return string
	 * @access public
	 */
        public function loadResourceCommentsAjax(){
            
            global $wpdb; // this is how you get access to the database
            
//            check_ajax_referer('resource_comments', 'security');
            
            if (!isset($_REQUEST['pageurl'])) { // if pageurl is empty
                echo json_encode(['success' => true, 'resource'=> [], 'comments' => [], 'msg' => __( 'Please enter Resource URL / Page URL', CMP_CORE_TEXT_DOMAIN )]);
                wp_die();
            }
            
            global $wpdb;
            
            //getting resource by using pageurl
            $resource = $wpdb->get_row( $wpdb->prepare( 
                    "
                           SELECT * FROM resources WHERE pageurl = %s
                    ", 
                    $_REQUEST['pageurl']
            ) );
            
            if($resource){ //if resource present
                $comments = $wpdb->get_results( $wpdb->prepare(  // query for finding comments
                        "
                               SELECT * FROM comments WHERE resourceid = %d
                        ", 
                        $resource->resourceid
                ) );
                if($comments){ // if comments found
                    echo json_encode(['success' => true, 'resource'=> $resource, 'comments' => $comments, 'msg' => __( 'Resource Found', CMP_CORE_TEXT_DOMAIN )]);
                } else { // if no comments on the particular resource
                    echo json_encode(['success' => false, 'resource'=> $resource, 'comments' => [], 'msg' => __( 'No Comments Found', CMP_CORE_TEXT_DOMAIN )]);
                }
                wp_die();
            }
            echo json_encode(['success' => false, 'resource'=> [], 'comments' => [], 'msg' => __( 'No Resource Found', CMP_CORE_TEXT_DOMAIN )]);
            wp_die();

        }
        
        /**
	 * Delete resource comments
	 * @return string
	 * @access public
	 */
        public function deleteResourceCommentsAjax(){
            global $wpdb; // this is how you get access to the database
            
            $to_delete = $_REQUEST['to_delete_comments'];
            $resourceid = '';
            $deleted = false;
            
//            check_ajax_referer('delete_comment', 'security');
            if (!isset($_REQUEST['to_delete_comments'])) { // if resourceid, userid, commentdate is empty
                echo json_encode(['success' => false, 'resource'=> [], 'comments' => [], 'msg' => __( 'Validation Errors', CMP_CORE_TEXT_DOMAIN )]);
                wp_die();
            }
            $to_delete = json_decode(stripslashes($to_delete));
//            var_dump(json_decode(stripslashes($to_delete)));
//            wp_die();
            foreach($to_delete as $delete){
                $resourceid = $delete->resourceid;
//                echo json_encode($delete);
//                wp_die();
                //deleting comment
                $deleted = $wpdb->delete( 'comments', array( 'resourceid' => $delete->resourceid, 'userid'=> $delete->userid, 'commentdate' => $delete->commentdate), array( '%d', '%d', '%s' ) );


                
            }
            
            
            if($resourceid != ''){
                $average_memberrating = $wpdb->get_var( $wpdb->prepare( 
                    "
                           SELECT AVG(rating) FROM `comments` WHERE resourceid = %d
                    ", 
                    $delete->resourceid
                ) );
                $wpdb->update( 
                            'resources', 
                            array( 
                                    'memberrating' => $average_memberrating,
                                    'indexrequired' => 'T',
                                    'indexrequireddate' => 'current_timestamp()'
                            ), 
                            array( 'resourceid' => $resourceid ), 
                            array( 
                                    '%s',
                            ), 
                            array( '%d' ) 
                    );
            }
            if($deleted) {
                echo json_encode(['success' => true, 'msg' => __( 'Your select comments have been deleted ', CMP_CORE_TEXT_DOMAIN )]);
            } else {
                echo json_encode(['success' => false, 'msg' => __( 'Error deleting', CMP_CORE_TEXT_DOMAIN )]);
            }
            
            wp_die();

        }
}

$cmp_loaded = CMPResourceComments::getInstance();
