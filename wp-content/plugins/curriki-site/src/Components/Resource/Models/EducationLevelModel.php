<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of EducationLevel
 *
 * @author waqarmuneer
 */

class EducationLevelModel {
    
    public function getByResourceId($resourceid = 0) {
        
        if($resourceid <= 0)
            return [];
        
        global $wpdb;        
        $query = 'SELECT
                 e.`levelid`, 
                 e.`displayname` 
                 FROM 
                 `resource_educationlevels` AS el 
                 LEFT JOIN `educationlevels` AS e ON (el.`educationlevelid` = e.`levelid`) 
                 WHERE el.`resourceid` = %d';
        $result = $wpdb->get_results( $wpdb->prepare($query,$resourceid));
        return is_array($result) ? $result : [];
        
    }
    
}
