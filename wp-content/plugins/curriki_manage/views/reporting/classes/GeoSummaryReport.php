<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GeoSummaryReport
 *
 * @author waqarmuneer
 */
class GeoSummaryReport {
            
    public static function get_geoip($contributorid, $start_date = null, $end_date = null, $collection_slug = "") {
        global $wpdb;        
        
        $query_vars = array();                
        $where_clause_for_main_query = "where r.contributorid = %d";
        $query_vars[0] = intval($contributorid);
        
        $sql_date_range = "";
        if( !($start_date == null && $end_date == null) ){
            $sql_date_range = "and DATE(rv.viewdate) >= DATE(%s) and DATE(rv.viewdate) <= DATE(%s)";
            $query_vars[1] = $start_date;
            $query_vars[2] = $end_date;
        }
        
        $sql_union_collection_elements = "";        
        /******* If summary filter by 'collection slug' *********/
        if( strlen(trim($collection_slug)) > 0 )
        {
            $where_clause_for_main_query = "where r.pageurl = %s";
            $query_vars[0] = trim($collection_slug);
            
            $sql_union_collection_elements = "  UNION
                                                select rv.*, v.*,v.visitsid as v_visitsid , r.type as r_type , 'secondary' as record_type
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                                left outer join visits v on v.visitsid = rv.visitid
                                                where cr.pageurl = %s
                                                {$sql_date_range}
                                            ";
            if( array_key_exists(1, $query_vars) && array_key_exists(2, $query_vars)){
                $query_vars[3] = trim($collection_slug);
                $query_vars[4] = $start_date;
                $query_vars[5] = $end_date;
            }else{
                $query_vars[1] = trim($collection_slug);
            }
        }
        
        /******** Main query to get unique users **********/
        $query = "
                    select rv.*, v.*,v.visitsid as v_visitsid , r.type as r_type , 'primary' as record_type
                    from resources r
                    left outer join resourceviews rv on rv.resourceid = r.resourceid
                    left outer join visits v on v.visitsid = rv.visitid
                    {$where_clause_for_main_query}
                    {$sql_date_range}                    
                    {$sql_union_collection_elements}
                ";        
        $result = $wpdb->get_results( $wpdb->prepare($query, $query_vars) );                         
        $geo_splited_data = self::split_resources_and_collections_records($result);
        $geo_data_resources = self::calculate_percentages_for_visits($geo_splited_data['ga_resources_records']);
        $geo_data_collections = self::calculate_percentages_for_visits($geo_splited_data['ga_collections_records']);            
        return array('geo_data_resources' => $geo_data_resources , 'geo_data_collections' => $geo_data_collections);            
    }
    
    public static function split_resources_and_collections_records($result){
        $ga_resources_arr = array();
        $ga_collections_arr = array();
        foreach ($result as $record) {
            if ($record->r_type === 'resource') {
                $ga_resources_arr[] = $record;
            } elseif ($record->r_type === 'collection') {
                $ga_collections_arr[] = $record;
            }
        }
        return array('ga_resources_records' => $ga_resources_arr , 'ga_collections_records' => $ga_collections_arr);
    }
    public function calculate_percentages_for_visits($result) {
        $unknown_visits_arr = array();
        $known_visits_arr = array();
        $all_visits = count($result);
        $all_visits_divider = $all_visits === 0 ? 1 : $all_visits;
        
        $resourceview_visits_in_us = 0;
        foreach ($result as $geoip_data) {            
            if($geoip_data->v_visitsid === null){
                $unknown_visits_arr[] = $geoip_data;
            }else{  
                $known_visits_arr[] = $geoip_data;
                if($geoip_data->country === 'US'){
                    $resourceview_visits_in_us++;
                }                
            }                     
        }        
        $percent_us = $resourceview_visits_in_us / $all_visits_divider * 100;        
        $resourceview_visits_in_intl = count($known_visits_arr) - $resourceview_visits_in_us;        
        $percent_international = $resourceview_visits_in_intl / $all_visits_divider * 100;        
        $percent_visitor_unknown = count($unknown_visits_arr) / $all_visits_divider * 100;
        
        return array(
            'percent_visitor_unknown' => round($percent_visitor_unknown) ,
            'percent_us' => round($percent_us),
            'percent_international' => round($percent_international),
        );        
    }
    
    public function calculate_percentages_for_visits_by_collection($result) {
        
        $collecitons_arr = array();
        $resources_arr = array();
            
        foreach ($result as $row) {
                        
            if($row->record_type === 'primary'){
                $collecitons_arr[] = $row;
            }elseif($row->record_type === 'secondary'){
                $resources_arr[] = $row;
            }            
            
        }
        
        $geo_data_collections = self::calculate_percentages_for_visits($collecitons_arr);
        $geo_data_resources = self::calculate_percentages_for_visits($resources_arr);        
        return array('geo_data_collections' => $geo_data_collections , 'geo_data_resources' => $geo_data_resources);        
    }
    
}
