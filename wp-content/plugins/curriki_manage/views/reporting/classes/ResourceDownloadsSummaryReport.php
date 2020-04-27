<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResourceDownloadsSummaryReport
 *
 * @author waqarmuneer
 */
class ResourceDownloadsSummaryReport {
    
    public static function get_number_of_downloads($contributorid, $start_date = null, $end_date = null , $collection_slug = "") {
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
                                                select ifnull(count(fd.downloadid), 0) as downloads , r.resourceid, r.type
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                
                                                left outer join resourcefiles rf on rf.resourceid = ce.resourceid
                                                left outer join filedownloads fd on rf.fileid = fd.fileid
                    
                                                where cr.pageurl = %s
                                                {$sql_date_range}
                                                group by r.resourceid
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
                    SELECT ifnull(count(fd.downloadid), 0) as downloads , r.resourceid, r.type
                    FROM resources r
                    left outer join resourcefiles rf on rf.resourceid = r.resourceid
                    left outer join filedownloads fd on rf.fileid = fd.fileid
                    {$where_clause_for_main_query}
                    {$sql_date_range}
                    group by r.resourceid        
                    {$sql_union_collection_elements}
            ";                      
        $result = $wpdb->get_results($wpdb->prepare($query, $query_vars));
        
        $downloads_resources_arr = array();
        $downloads_collections_arr = array();
        foreach ($result as $record) {
            if ($record->type === 'resource') {
                $downloads_resources_arr[] = $record->downloads;
            } elseif ($record->type === 'collection') {
                $downloads_collections_arr[] = $record->downloads;
            }
        }

        $resources_downloads_sum = array_sum($downloads_resources_arr);
        $collections_downloads_sum = array_sum($downloads_collections_arr);
        return array("resources_downloads_sum" => $resources_downloads_sum, 'collections_downloads_sum' => $collections_downloads_sum);
    }
    
}
