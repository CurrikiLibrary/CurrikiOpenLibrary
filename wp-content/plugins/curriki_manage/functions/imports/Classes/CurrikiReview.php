<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CurrikiReview
 *
 * @author waqarmuneer
 */
class CurrikiReview {
    public static $resource = null;
    public static $resource_in_proccess = null;
    public static $resource_collections = [];
    public static $wpdb = null;
    public static $not_reviewd = null;

    public static function clearData() {
        self::$resource = null;
        self::$resource_in_proccess = null;
        self::$resource_collections = [];
        self::$wpdb = null;
        self::$not_reviewd = null;
    }
    
    public static function udpateResource($pageurl = "",$partner_val = "") {
        
        global $wpdb;
        self::$wpdb = $wpdb;
        $query = "select 
                r.resourceid,
                r.pageurl,
                r.partner,
                r.reviewstatus,
                r.reviewrating
                from resources r 
                where r.pageurl='$pageurl'";                
        self::$resource = self::$wpdb->get_row($query);                 
        //self::$resource_in_proccess = self::$resource;
        
        $not_reviewd_condition = !(self::$resource->reviewstatus == 'reviewed' && self::$resource->reviewrating != null && self::$resource->reviewrating >= 0);
        if(self::$resource && self::$resource->partner !== $partner_val && $not_reviewd_condition){
        //if(self::$resource){
            //echo "** do Update Parent | ".self::$resource->pageurl." ";
            self::updateResource(self::$resource->resourceid, $partner_val);
        }
        
        if(self::$resource){
            self::updateChildren(self::$resource->pageurl,$partner_val);
        }
    }
    
    public static function updateChildren($page_url,$partner_val){     
        //echo ">> ". $page_url . "<br />";
        $query = "
                select 
                r.resourceid as r_resourceid,
                r.pageurl as r_pageurl,
                r.partner as r_partner,				
                c.resourceid as c_resourceid,
                c.type as c_type,
                c.pageurl as c_pageurl,
                c.partner as c_partner,
                c.reviewstatus as c_reviewstatus,
                c.reviewrating as c_reviewrating
                from resources r 
                left outer join collectionelements ce on r.resourceid = ce.collectionid
                left outer join resources c on c.resourceid = ce.resourceid
                where 
                r.pageurl='".$page_url."'
                ";
        $children_resources = self::$wpdb->get_results($query);
        foreach( $children_resources as $children_resource){
            //echo ">>>> ". $children_resource->c_pageurl . "<br />";            
            $not_reviewd_condition = !($children_resource->c_reviewstatus == 'reviewed' && $children_resource->c_reviewrating != null && $children_resource->c_reviewrating >= 0);
            if($children_resource->c_partner !== $partner_val && $not_reviewd_condition){
                //echo "**** do Update Child ".$children_resource->c_pageurl." ";
                self::updateResource($children_resource->c_resourceid, $partner_val);
            }
            
            if($children_resource->c_type === 'collection'){                
                self::updateChildren($children_resource->c_pageurl,$partner_val);
            }            
        }
    }
    
    public static function updateResource($resourceid, $partner_val){
        
        if($partner_val === 'P'){
            $partner_val = 'T';
        }        
        
        global $wpdb;
        self::$wpdb = $wpdb;
        self::$wpdb->update( 
                    'resources', 
                    array(
                        'partner' => $partner_val,
                        'indexrequired' => 'T',
                        'indexrequireddate' => date('Y-m-d H:i:s'),
                    ),                 
                    array( 'resourceid' => $resourceid ),                 
                    array('%s','%s','%s'),                 
                    array('%d')
                );
        
    }
}
