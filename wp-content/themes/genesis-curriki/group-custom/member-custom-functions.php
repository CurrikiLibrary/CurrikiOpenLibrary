<?php
function get_member_education_levels($user_id,$current_language=null) {
    
        global $wpdb;

        $query = "SELECT 
                   el.displayname as displayname_orignal,
                   elml.displayname as displayname
                  FROM user_educationlevels uel
                    LEFT JOIN educationlevels el ON uel.educationlevelid = el.levelid
                    INNER JOIN educationlevels_ml elml ON el.levelid = elml.levelid
                   WHERE userid = $user_id
                    AND elml.language = '$current_language'
                ";        
        $records = $wpdb->get_results($query,OBJECT);

        $educationlevels_arr = array();
        foreach($records as $g_sb){
            $educationlevels_arr[] = $g_sb->displayname;
        }                
        $educationlevels = array_unique($educationlevels_arr);                
        //return $records;
        return $educationlevels;
    
}