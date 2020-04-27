<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of Subject
 *
 * @author waqarmuneer
 */

class SubjectModel {
    
    public function getWithSubjectAreas($resourceid = 0) {
        
        if($resourceid <= 0)
            return [];
        
        global $wpdb;        
        $query = 'SELECT 
                s.subjectid,
                s.displayname as subject_displayname, 
                sa.subjectareaid,
                sa.displayname as subjectarea_displayname
                FROM `resource_subjectareas` AS rs 
                LEFT JOIN `subjectareas` AS sa ON (rs.`subjectareaid` = sa.`subjectareaid`) 
                inner join subjects s on sa.subjectid = s.subjectid 
                WHERE rs.`resourceid` = %d';        
        $result = $wpdb->get_results( $wpdb->prepare($query,$resourceid) );                
        return is_array($result) ? $result : [];
        
    }
    
}
