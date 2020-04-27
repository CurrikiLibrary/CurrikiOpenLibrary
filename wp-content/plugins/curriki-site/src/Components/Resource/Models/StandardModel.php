<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of Standard
 *
 * @author waqarmuneer
 */

class StandardModel {
    
    function getByResourceId($resourceid) {
        global $wpdb;
        $query = 'select 
            s.notation, 
            st.title, 
            s.description 
            from 
            resource_statements rs 
            inner join statements s on rs.statementid = s.statementid 
            inner join standards st on s.standardid = st.standardid 
            where resourceid = %d';
        $result = array();
        $results = $wpdb->get_results($wpdb->prepare($query,$resourceid));
        if (count($results) > 0)
        {
            foreach ($results AS $res){
                $result[] = array('notation' => $res->notation, 'title' => $res->title, 'description' => $res->description);
            }            
        }
          
        return $result;
  }
  
}
