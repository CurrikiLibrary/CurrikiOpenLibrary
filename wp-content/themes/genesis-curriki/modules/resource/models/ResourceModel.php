<?php

/**
 * Description of ResourceModel
 *
 * @author waqarmuneer
 */
class ResourceModel {

    function getResource($resourceid){
        global $wpdb;
        $query = 'SELECT '
                . 'r.*'
                . 'FROM `resources` AS r '                
                . 'WHERE r.`resourceid` = "' . $resourceid . '"';
        return $wpdb->get_row($query);
    }
            
    function getSubjectAreasIds($resourceid) {

        global $wpdb;

        $query = 'SELECT                  
                  sa.subjectareaid, 
                  sa.subjectid
                  FROM 
                  `resource_subjectareas` AS rs 
                  LEFT JOIN `subjectareas` AS sa ON (rs.`subjectareaid` = sa.`subjectareaid`) 
                  inner join subjects s on sa.subjectid = s.subjectid 
                  WHERE rs.`resourceid` = ' . $resourceid . '';
        $results = $wpdb->get_results($query);
        $subject_ids = [];
        $subjectareas_ids = [];

        foreach ($results AS $res) {
            $subject_ids[] = $res->subjectid;
            $subjectareas_ids[] = $res->subjectareaid;
        }

        return array(
            "subject_ids" => array_unique($subject_ids),
            "subjectareas_ids" => array_unique($subjectareas_ids)
        );
    }

    function getEducationLevelIds($resourceid) {
        global $wpdb;
        $query = 'SELECT '
                . 'e.`levelid`'
                . 'FROM `resource_educationlevels` AS el '
                . 'LEFT JOIN `educationlevels` AS e ON (el.`educationlevelid` = e.`levelid`) '
                . 'WHERE el.`resourceid` = "' . $resourceid . '"';
        $results = $wpdb->get_results($query);
        $educationlevelid = [];
        foreach ($results AS $res) {
            $educationlevelid[] = $res->levelid;
        }
        return $educationlevelid;
    }

}
