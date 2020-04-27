<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of ResourceViews
 *
 * @author waqarmuneer
 */

class ResourceViewsModel {
    
    public function getOnwardByResourceId($resourceid) {
        global $wpdb;
        $query = "select rv.* from resources r
                join resourceviews rv on rv.resourceid = r.resourceid
                where r.resourceid > $resourceid";
        return $wpdb->get_results($query);        
    }
    
    public function getAllViewsByDate($viewdate) {
        global $wpdb;
        $query = "select rv.* from resourceviews rv                
                where rv.viewdate > '$viewdate'";        
        return $wpdb->get_results($query);        
    }
    
    public function getVisitsOnwardByResourceId($resourceid) {
        global $wpdb;
        $query = "select rv.* from resources r
                    join resourceviews rv on rv.resourceid = r.resourceid
                    join visits v on rv.visitid = v.visitsid
                    where r.resourceid > $resourceid";
        return $wpdb->get_results($query);        
    }
        
}
