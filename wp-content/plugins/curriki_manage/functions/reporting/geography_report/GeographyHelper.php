<?php
require_once 'ResourceProcessorByRegion.php';
require_once 'ResourceProcessorByCountry.php';


/**
 * Description of GeographyHelper
 *
 * @author waqarmuneer
 */

class GeographyHelper {
    
    public static function get_details_by_country($contributorid, $start_date = null, $end_date = null, $collection_slug = "" , $country_code = null) {        
                
        global $wpdb;        
        
        $query_vars = array();                
        $where_clause_for_main_query = "where r.contributorid = %d";
        $order_by_for_main_query = "order by pageviews desc";
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
            $order_by_for_main_query = "";
            $where_clause_for_main_query = "where r.pageurl = %s";
            $query_vars[0] = trim($collection_slug);
            
            $sql_union_collection_elements = "  UNION
                                                select v.visitsid as v_visitsid , 
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
                                                where cr.pageurl = %s
                                                and v.country = '$country_code'
                                                {$sql_date_range}
                                                group by v.region                                                
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
                    select v.visitsid as v_visitsid, 
                    r.type as r_type , 'primary' as record_type , r.type as resource_type, r.pageurl as pageurl,
                    count(v.visitsid) as pageviews,
                    countries.displayname as country_name,
                    v.country as country_code,
                    v.region as region,
                    count(CASE WHEN r.type = 'resource' THEN 1 END) AS resources_pageviews,
                    count(CASE WHEN r.type = 'collection' THEN 1 END) AS collections_pageviews
                    from resources r
                    left outer join resourceviews rv on rv.resourceid = r.resourceid
                    left outer join visits v on v.visitsid = rv.visitid
                    left outer join countries on countries.country = v.country
                    {$where_clause_for_main_query}
                    and v.country = '$country_code'
                    {$sql_date_range}
                    group by v.region                                        
                    {$sql_union_collection_elements}
                    {$order_by_for_main_query}
                ";
                    
        //**** preparing query in case of union and order by ****
        if( strlen($sql_union_collection_elements) > 0 ){
            $query = "
                        select v_visitsid, record_type, resource_type, pageurl,
                        sum(pageviews) AS pageviews,
                        country_name,country_code,region,
                        ifnull(sum(CASE WHEN r_type = 'resource' THEN resources_pageviews END),0) AS resources_pageviews,
                        ifnull(sum(CASE WHEN r_type = 'collection' THEN collections_pageviews END),0) AS collections_pageviews
                        from ($query) geo 
                        group by region
                        order by pageviews desc                             
                    ";
        }
        
        $result = $wpdb->get_results( $wpdb->prepare($query, $query_vars) );
        
        ResourceProcessorByRegion::setCollectionsToProcessChild($result);
        ResourceProcessorByRegion::setCountryRegionStats($result);        
        
        if( is_array(ResourceProcessorByRegion::$collecitons_to_process_child) && count(ResourceProcessorByRegion::$collecitons_to_process_child) > 0 ){
            $collection_slug_index = array_search($collection_slug, ResourceProcessorByRegion::$collecitons_to_process_child);
            unset(ResourceProcessorByRegion::$collecitons_to_process_child[$collection_slug_index]);            
            foreach(ResourceProcessorByRegion::$collecitons_to_process_child as $collection_pageurl){
                ResourceProcessorByRegion::processCollectionResource($start_date, $end_date, $collection_pageurl, $country_code);
            }
        }
        
        return ResourceProcessorByRegion::$country_regions_stats ;
    }
        
    public static function get_country_summary($contributorid, $start_date = null, $end_date = null, $collection_slug = "") {        
        
        global $wpdb;        
        
        $query_vars = array();                
        $where_clause_for_main_query = "where r.contributorid = %d";
        $order_by_for_main_query = "order by pageviews desc";
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
            $order_by_for_main_query = "";
            $where_clause_for_main_query = "where r.pageurl = %s";
            $query_vars[0] = trim($collection_slug);
            
            $sql_union_collection_elements = "  UNION
                                                select v.visitsid as v_visitsid , 
                                                r.type as r_type , 'secondary' as record_type , r.type as resource_type, r.pageurl as pageurl,
                                                count(v.visitsid) as pageviews,
                                                countries.displayname as country_name,
                                                v.country as country_code,
                                                count(CASE WHEN r.type = 'resource' THEN 1 END) AS resources_pageviews,
                                                count(CASE WHEN r.type = 'collection' THEN 1 END) AS collections_pageviews
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                                left outer join visits v on v.visitsid = rv.visitid
                                                left outer join countries on countries.country = v.country
                                                where cr.pageurl = %s
                                                and v.country <> 'null'
                                                {$sql_date_range}
                                                group by v.country                                                
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
                    select v.visitsid as v_visitsid, 
                    r.type as r_type , 'primary' as record_type , r.type as resource_type, r.pageurl as pageurl,
                    count(v.visitsid) as pageviews,
                    countries.displayname as country_name,
                    v.country as country_code,
                    count(CASE WHEN r.type = 'resource' THEN 1 END) AS resources_pageviews,
                    count(CASE WHEN r.type = 'collection' THEN 1 END) AS collections_pageviews
                    from resources r
                    left outer join resourceviews rv on rv.resourceid = r.resourceid
                    left outer join visits v on v.visitsid = rv.visitid
                    left outer join countries on countries.country = v.country
                    {$where_clause_for_main_query}
                    and v.country <> 'null'
                    {$sql_date_range}
                    group by v.country                                        
                    {$sql_union_collection_elements}
                    {$order_by_for_main_query}
                ";
                    
        //**** preparing query in case of union and order by ****
        if( strlen($sql_union_collection_elements) > 0 ){
            $query = "
                        select v_visitsid, record_type, resource_type, pageurl,
                        sum(pageviews) AS pageviews,
                        country_name,country_code,
                        ifnull(sum(CASE WHEN r_type = 'resource' THEN resources_pageviews END),0) AS resources_pageviews,
                        ifnull(sum(CASE WHEN r_type = 'collection' THEN collections_pageviews END),0) AS collections_pageviews
                        from ($query) geo 
                        group by country_code
                        order by pageviews desc                             
                    ";
        }
        
        $result = $wpdb->get_results( $wpdb->prepare($query, $query_vars) );                         
        
        ResourceProcessorByCountry::setCollectionsToProcessChild($result);
        ResourceProcessorByCountry::setCountryStats($result);        
        
        if( is_array(ResourceProcessorByCountry::$collecitons_to_process_child) && count(ResourceProcessorByCountry::$collecitons_to_process_child) > 0 ){
            $collection_slug_index = array_search($collection_slug, ResourceProcessorByCountry::$collecitons_to_process_child);
            unset(ResourceProcessorByCountry::$collecitons_to_process_child[$collection_slug_index]);            
            foreach(ResourceProcessorByCountry::$collecitons_to_process_child as $collection_pageurl){
                ResourceProcessorByCountry::processCollectionResource($start_date, $end_date, $collection_pageurl);
            }
        }
        
        return ResourceProcessorByCountry::$country_countries_stats ;
        
    }
    
}
