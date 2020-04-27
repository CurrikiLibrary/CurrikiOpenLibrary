<?php
class Resources_Model {

    public function __construct() {		        
    }
    public function insert( $args, $instance ) {    
    }
    public function update_resource_on_user_spam($user_id , $update_fields) {
        global $wpdb;
         $wpdb->update('resources', $update_fields,
                                    array(
                                        "contributorid"=> $user_id,
                                    ),
                                    array("%s","%s","%s","%s","%s"),
                                    array("%d")
                         );
    }
    public function delete( $instance ) {            
    }
}