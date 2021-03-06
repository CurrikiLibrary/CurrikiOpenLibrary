<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResourceProcessorByRegion
 *
 * @author waqarmuneer
 */

class ResourceProcessorByRegion {
    
    public static $collecitons_to_process_child = [];
    public static $country_regions_stats = [];
    
    public static function processCollectionResource($start_date = null, $end_date = null, $collection_slug = "" , $country_code = null) {
        
        global $wpdb;
        $sql_date_range = "";
        if( !($start_date == null && $end_date == null) ){
            $sql_date_range = "and DATE(rv.viewdate) >= DATE($start_date) and DATE(rv.viewdate) <= DATE($end_date)";            
        }
        
        $sql_collection_elements = "select v.visitsid as v_visitsid , 
                                    r.type as r_type , 'secondary' as record_type , r.type as resource_type, r.pageurl,
                                    count(v.visitsid) as pageviews,
                                    countries.displayname as country_name,
                                    v.country as country_code,
                                    v.region as region,
                                    count(CASE WHEN r.type = 'resource' THEN 1 END) AS resources_pageviews,
                                    count(CASE WHEN r.type = 'collection' THEN 1 END) AS collections_pageviews
                                    FROM collectionelements ce
                                    left outer join resources r on ce.resourceid = r.resourceid
                                    left outer join resources cr on ce.collectionid = cr.resourceid
                                    left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                    left outer join visits v on v.visitsid = rv.visitid
                                    left outer join countries on countries.country = v.country
                                    where cr.pageurl = '$collection_slug'
                                    and v.country = '$country_code'
                                    {$sql_date_range}
                                    group by v.region";
                                                
        $query = "select v_visitsid, record_type, resource_type, pageurl,
                  sum(pageviews) AS pageviews,
                  country_name,country_code,region,
                  ifnull(sum(CASE WHEN r_type = 'resource' THEN resources_pageviews END),0) AS resources_pageviews,
                  ifnull(sum(CASE WHEN r_type = 'collection' THEN collections_pageviews END),0) AS collections_pageviews
                  from ($sql_collection_elements) geo 
                  group by region
                  order by pageviews desc";
        $result = $wpdb->get_results($query);                
        
        self::updateCountryRegionStats($result);
        
        $childCollections = self::getCollectionsToProcessChild($result);
        
        if(count($childCollections) > 0){
            foreach ($childCollections as $collection_pageurl){
                self::processCollectionResource($start_date, $end_date, $collection_pageurl, $country_code);
            }
        }                        
    }
    
    
    public static function getCollectionsToProcessChild($data) {
        $collections = [];                
        foreach ($data as $row) {            
            $collections[] = $row->pageurl;
        }                 
        return array_unique($collections);                
    }
    
    public static function setCollectionsToProcessChild($data) {
        $collections = [];                
        foreach ($data as $row) {
            if($row->resource_type === 'collection'){
                $collections[] = $row->pageurl;
            }
        }                 
        self::$collecitons_to_process_child =  array_unique($collections);                
    }
    
    public static function setCountryRegionStats($data) {
        $region_stats = [];
        foreach ($data as $row) {
            $stats = new stdClass();
            $stats->country_code = $row->country_code;
            $stats->region = $row->region;
            $stats->resources_pageviews = $row->resources_pageviews;
            $stats->collections_pageviews = $row->collections_pageviews;
            $stats->pageviews = $row->pageviews;
            $region_stats[] = $stats;
        }
        self::$country_regions_stats = $region_stats;
    }
    
    public static function updateCountryRegionStats($data) {                
        for($i = 0; $i < count(self::$country_regions_stats); $i++){
            foreach ($data as $row) {
                if($row->country_code === self::$country_regions_stats[$i]->country_code && $row->region === self::$country_regions_stats[$i]->region){                    
                    self::$country_regions_stats[$i]->resources_pageviews += intval($row->resources_pageviews);
                    self::$country_regions_stats[$i]->collections_pageviews += intval($row->collections_pageviews);                    
                    self::$country_regions_stats[$i]->pageviews += intval($row->pageviews);                    
                }
            }
        }        
    }
    
    
}
