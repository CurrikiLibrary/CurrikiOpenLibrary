<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of CommentsModel
 *
 * @author waqarmuneer
 */

class CommentsModel {
    
    function getByResourceId($resourceid = 0) {
        
        if($resourceid <= 0)
            return [];
        
        global $wpdb;
        $query = 'SELECT 
            c.*, 
            cu.display_name, 
            u.uniqueavatarfile 
            FROM 
            `comments` AS c 
            LEFT JOIN `cur_users` AS cu ON (cu.`ID` = c.`userid`) 
            LEFT JOIN `users` AS u ON (u.userid = cu.ID) 
            WHERE c.`resourceid` = ' . $resourceid . '
            ORDER BY c.commentdate DESC';

        $result = array();
        $results = $wpdb->get_results( $wpdb->prepare($query,$resourceid) );
        if (count($results) > 0)
          foreach ($results AS $res)
            $result[] = array('userid' => $res->userid, 'display_name' => $res->display_name, 'uniqueavatarfile' => $res->uniqueavatarfile, 'rating' => $res->rating, 'date' => $res->commentdate, 'comment' => $res->comment);

        return $result;
  }
  
  public function getOnwardByDate($commentdate) {
      global $wpdb;
        $query = "SELECT cmnt.* 
                FROM comments cmnt                 
                WHERE cmnt.commentdate > '$commentdate'";
        return $wpdb->get_results($query);  
  }
  
}
