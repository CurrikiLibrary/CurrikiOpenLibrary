<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of Resource
 *
 * @author waqarmuneer
 */

class ResourceModel {
    
    public function getById($resourceid = 0) {        
        
        if($resourceid === 0)
            return null;
        
        global $wpdb;
        $query = 'SELECT r.*,
                r.active as resource_active ,
                u.userid, cu.display_name,
                u.blogs,
                u.city,
                u.state,
                u.country,
                u.organization,
                u.registerdate,
                u.uniqueavatarfile,
                rf.fileid, 
                rf.uniquename,
                rf.folder,
                l.name AS license            
                FROM `resources` AS r 
                LEFT JOIN `users` AS u ON (u.userid = r.contributorid)
                LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid)
                LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid)
                LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid)
                WHERE r.resourceid = %d';
        return $wpdb->get_row( $wpdb->prepare($query , $resourceid) ); 
        
    }
    
    public function getByPageUrl($pageurl = '') {             
        
        global $wpdb;
        $query = 'SELECT r.*,
                r.active as resource_active ,
                u.userid, cu.display_name,
                u.blogs,
                u.city,
                u.state,
                u.country,
                u.organization,
                u.registerdate,
                u.uniqueavatarfile,
                rf.fileid,
                rf.uniquename,
                rf.folder,
                l.name AS license            
                FROM `resources` AS r
                LEFT JOIN `users` AS u ON (u.userid = r.contributorid)
                LEFT JOIN `cur_users` AS cu ON (cu.ID = u.userid)
                LEFT JOIN `licenses` AS l ON (l.licenseid = r.licenseid)
                LEFT JOIN `resourcefiles` AS rf ON (rf.resourceid = r.resourceid)
                WHERE r.pageurl = %s';
        return $wpdb->get_row( $wpdb->prepare($query , $pageurl) );                     
        
    }
    
    public function getLatestByApprovalStatus($status = 'pending') {             
        
        global $wpdb;                
        $query = "SELECT r.* 
                FROM resources r 
                WHERE r.approvalStatus = '$status'
                ORDER BY r.resourceid desc limit 1";
        return $wpdb->get_row( $query );                     
        
    }
    
    public function getAllOnwardFromId($resourceid = 0) {             
        
        if($resourceid < 1)
            return [];
        
        global $wpdb;                
        $query = "SELECT r.* 
                FROM resources r 
                WHERE 
                r.resourceid > $resourceid";
        $results = $wpdb->get_results( $query );                     
        return is_array($results) ? $results : [];
        
    }
    
    
    
}
