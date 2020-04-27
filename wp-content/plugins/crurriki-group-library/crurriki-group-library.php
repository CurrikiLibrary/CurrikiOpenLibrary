<?php
/**
 * Plugin Name:       Curriki Group Library
 * Plugin URI:        http://curriki.org
 * Description:       Plugin to manage Curriki Group Library
 * Version:           1.0
 * Author:            Waqar Muneer
 * Author URI:        http://curriki.org
 */
//if ( !defined( 'ABSPATH' ) ) exit;

//if($_GET['bptes'] == 'true')
//{   
    function curr_group_library_init() {        
        if (!class_exists( 'BP_Group_Extension' )) 
            return;
        
         class Crurriki_group_library extends BP_Group_Extension {
           
            public function __construct() {                
                $args = array(
                    'slug' => 'library',
                    'name' => 'Group Library',
                );
                parent::init( $args );                                
            }
          
            public function display( $group_id = NULL ) {                
                
                bp_core_load_template("groups/library/index");                
            }
                      
            /**
             * settings_screen() is the catch-all method for displaying the content
             * of the edit, create, and Dashboard admin panels
             */
            /*
            function settings_screen( $group_id = NULL ) {
                $setting = groups_get_groupmeta( $group_id, 'group_extension_example_1_setting' );
                ?>
                Save your plugin setting here: <input type="text" name="group_extension_example_1_setting" value="<?php echo esc_attr( $setting ) ?>" />
                <?php
            }
            */
            /**
             * settings_sceren_save() contains the catch-all logic for saving
             * settings from the edit, create, and Dashboard admin panels
             */
            /*
            function settings_screen_save( $group_id = NULL ) {
                $setting = '';

                if ( isset( $_POST['group_extension_example_1_setting'] ) ) {
                    $setting = $_POST['group_extension_example_1_setting'];
                }

                groups_update_groupmeta( $group_id, 'group_extension_example_1_setting', $setting );
            }
            */
        }
        bp_register_group_extension( 'Crurriki_group_library' );
    }        
    add_action('bp_init', 'curr_group_library_init');       
//}
