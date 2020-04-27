<?php

/**
 * Initializes Deleting activation from resources section
 * 
 * Makes singleton of Resource Deactivation. Enqueues CSS, JS. Adds Menu link. Makes tabs. Registers ajax to delete activation
 *
 * @author     Ali Mehdi
 */
class CMPResourceActivation {

    private static $cmp_instance;

    private function __construct() {


        add_action('admin_enqueue_scripts', array($this, 'load_cusom_js'));
        add_action('admin_enqueue_scripts', array($this, 'load_custom_css'));

        if (is_admin()) {
            require_once( CMP_PATH . 'functions/resource_deactivation/includes/admin/admin-pages.php' );

            /** Settings Pages * */
            add_action('admin_menu', array($this, 'cmp_setup_admin_menu'), 1000, 0);

            add_action('wp_ajax_nopriv_resource_deactivation', array($this, 'loadResourceActivationAjax'));
            add_action('wp_ajax_resource_deactivation', array($this, 'loadResourceActivationAjax'));

            add_action('wp_ajax_nopriv_active_inactive_resource', array($this, 'activeInactiveResourceActivationAjax'));
            add_action('wp_ajax_active_inactive_resource', array($this, 'activeInactiveResourceActivationAjax'));
        }
    }

    /**
     * Get the singleton instance of our plugin
     * @return class The Instance
     * @access public
     */
    public static function getInstance() {
        if (!self::$cmp_instance) {
            self::$cmp_instance = new CMPResourceActivation();
        }

        return self::$cmp_instance;
    }

    /**
     * Queue up the JavaScript file for the admin page, only on our admin page
     * @param  string $hook The current page in the admin
     * @return void
     * @access public
     */
    public function load_cusom_js($hook) {
        if ('curriki-admin_page_resource_deactivation' != $hook)
            return;
        wp_enqueue_script('cmp_angular_js', CMP_URL . '/assets/bower_components/angular/angular.min.js', 'jquery', CMP_VERSION, true);
        wp_enqueue_script('cmp_foundation_js', CMP_URL . '/assets/foundation/js/vendor/foundation.min.js', 'jquery', CMP_VERSION, true);
        wp_enqueue_script('cmp_bootstrap_js', CMP_URL . '/functions/resource_deactivation/includes/scripts/ui-bootstrap-tpls-0.3.0.min.js', 'jquery', CMP_VERSION, true);
        wp_enqueue_script('cmp_core_custom_js', CMP_URL . '/functions/resource_deactivation/includes/scripts/cmp_custom.js', 'jquery', CMP_VERSION, true);
    }

    /**
     * Loading up the css on Resource Deactivation section
     * @param string $hook The current page in the admin
     */
    public function load_custom_css($hook) {
        if ('curriki-admin_page_resource_deactivation' != $hook)
            return;
        wp_enqueue_style('cmp_foundation_css', CMP_URL . '/assets/foundation/css/foundation.min.css', array(), CMP_VERSION);
        wp_enqueue_style('cmp_css', CMP_URL . '/css/styles.css', array(), CMP_VERSION);
        wp_enqueue_style('cmp_jquery_ui_css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), CMP_VERSION);
        wp_enqueue_style('cmp_datepicker_css', CMP_URL . '/assets/datepicker.css', array(), CMP_VERSION);
    }

    /**
     * Add the Resource Deactivation item to the Settings menu
     * @return void
     * @access public
     */
    public function cmp_setup_admin_menu() {
        add_submenu_page('curriki_admin', __('Resource Deactivation', CMP_CORE_TEXT_DOMAIN), __('Resource Deactivation', CMP_CORE_TEXT_DOMAIN), 'curriki_admin', 'resource_deactivation', array($this, 'determine_tab'));
    }

    /**
     * Determines what tab is being displayed, and executes the display of that tab
     * @return void
     * @access public
     */
    public function determine_tab() {
        ?>
        <div id="icon-options-general" class="icon32"></div><h2><?php _e('Resource Deactivation', CMP_CORE_TEXT_DOMAIN); ?></h2>
        <?php
        $current = (!isset($_GET['tab']) ) ? 'activation' : $_GET['tab'];
        $default_tabs = array(
            'activation' => __('Activation / Deactivation', CMP_CORE_TEXT_DOMAIN),
        );



        $tabs = apply_filters('cmp_settings_tabs', $default_tabs);
        ?><h2 class="nav-tab-wrapper"><?php
        foreach ($tabs as $tab => $name) {
            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=resource_deactivation&tab=$tab'>$name</a>";
        }
        ?>
        </h2>
        <div class="wrap">
            <?php
            if (!isset($_GET['tab']) || $_GET['tab'] == 'activation') {
                cmp_resource_page();
            } else {
                // Extension Devs - Your function that shows the tab content needs to be prefaced with 'cmp_display_' in order to work here.
                $tab_function = 'cmp_display_' . $_GET['tab'];
                $tab_function();
            }
            ?>
        </div>
            <?php
        }

        /**
         * Fetching the activation based on pageurl
         * @return string
         * @access public
         */
        public function loadResourceActivationAjax() {

            global $wpdb; // this is how you get access to the database
//            check_ajax_referer('resource_deactivation', 'security');

            if (!isset($_REQUEST['pageurl']) || $_REQUEST['pageurl'] == '') { // if pageurl is empty
                echo json_encode(['success' => true, 'resource' => [], 'msg' => __('Please enter Resource URL / Page URL', CMP_CORE_TEXT_DOMAIN)]);
                wp_die();
            }

            global $wpdb;

            //getting resource by using pageurl
            $resource = $wpdb->get_row($wpdb->prepare(
                            "
                           SELECT * FROM resources WHERE pageurl = %s
                    ", $_REQUEST['pageurl']
                    ));

            if ($resource) { //if resource present
                echo json_encode(['success' => true, 'resource' => $resource, 'msg' => __('Resource Found', CMP_CORE_TEXT_DOMAIN)]);
                wp_die();
            }
            echo json_encode(['success' => false, 'resource' => [], 'msg' => __('No Resource Found', CMP_CORE_TEXT_DOMAIN)]);
            wp_die();
        }

        /**
         * Make resource active / inactive
         * @return string
         * @access public
         */
        public function activeInactiveResourceActivationAjax() {
            global $wpdb; // this is how you get access to the database
            $resourceid = $_REQUEST['resourceid'];
            $todo = $_REQUEST['todo'];
            
            $updated = false;

//            check_ajax_referer('active_inactive_resource', 'security');
            if (!isset($_REQUEST['resourceid']) || !isset($_REQUEST['todo'] )) { // if resourceid, userid, commentdate is empty
                echo json_encode(['success' => false, 'resource' => [], 'msg' => __('Validation Errors', CMP_CORE_TEXT_DOMAIN)]);
                wp_die();
            }
            
            $active = 'F';
            if($todo == 'active'){
                $active = 'T';
            }
            if ($resourceid) {
                $resource_active = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM resources where resourceid = %d and active = 'T' ",
                        $resourceid
                        ));
                if($resource_active){
                    $wpdb->query($wpdb->prepare(
                                    "
                                Delete from collectionelements
                                Where resourceid = (select resourceid from resources where resourceid = %d);
                        ",
                            $resourceid
                            ));
                    $wpdb->query($wpdb->prepare(
                                    "
                                update resources
                                set active = %s,
                                remove = 'T',
                                indexrequired = 'T',
                                indexrequireddate = current_timestamp(),
                                lasteditdate = current_timestamp(),
                                lasteditorid = %d,
                                resourcechecknote = 'Removal requested'
                                where resourceid = %d;
                        ",
                            $active,
                            get_current_user_id(),
                            $resourceid
                            ));
                    $updated = true;
                }
                
                
            }
            if ($updated) {
                echo json_encode(['success' => true, 'msg' => __('Resource has been updated ', CMP_CORE_TEXT_DOMAIN)]);
            } else {
                echo json_encode(['success' => false, 'msg' => __('Error Updating', CMP_CORE_TEXT_DOMAIN)]);
            }

            wp_die();
        }

    }

    $cmp_loaded = CMPResourceActivation::getInstance();
    