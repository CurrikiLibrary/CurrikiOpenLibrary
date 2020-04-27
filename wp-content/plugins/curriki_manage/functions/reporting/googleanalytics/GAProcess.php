<?php

/**
 * Description of GAProcess
 *
 * @author waqarmuneer
 */
class GAProcess {

    public static function get_records($contributorid, $start_date, $end_date, $ga_records_slugs, $collection_slug) {
        global $wpdb;
        $sql = "";
        if ( (strlen($start_date) > 0 || strlen($end_date) > 0) && strlen(trim($collection_slug)) > 0 ) {            
            $sql = self::sqlCollections($collection_slug, $start_date, $end_date);                                    
        } elseif ( (strlen($start_date) > 0 || strlen($end_date) > 0) && intval($contributorid) > 0 ) {
            $sql = self::sqlContributor($contributorid, $start_date, $end_date);
        } else {
            $sql .= "select resourceid , pageurl, title, type from resources";
            $sql .= " where contributorid IN ($contributorid)";
        }        
        $resources = $wpdb->get_results($sql);
        return self::filterResourcesByGA($resources, $ga_records_slugs);
    }

    public static function sqlContributor($contributorid, $start_date, $end_date) {
        $sql .= "
                    select 
                    resources.resourceid , resources.pageurl, resources.title, resources.type
                    from resourceviews
                    join resources on resources.resourceid = resourceviews.resourceid
                    where resources.contributorid IN ({$contributorid}) 
                    and DATE(resourceviews.viewdate) >= DATE('{$start_date}') and DATE(resourceviews.viewdate) <= DATE('{$end_date}')
                    group by resourceviews.resourceid
                ";
        return $sql;
    }

    public static function sqlCollections($collection_slug, $start_date, $end_date) {
        $sql = "
                    select
                    resources.resourceid , resources.pageurl, resources.title, resources.type
                    from resourceviews
                    join resources on resources.resourceid = resourceviews.resourceid
                    where resources.pageurl = '$collection_slug'
                    and DATE(resourceviews.viewdate) >= DATE('{$start_date}') and DATE(resourceviews.viewdate) <= DATE('{$end_date}')
                    group by resourceviews.resourceid
                    union
                    select
                    r.resourceid , r.pageurl, r.title, r.type
                    from collectionelements ce
                    left outer join resources r on ce.resourceid = r.resourceid
                    left outer join resources cr on ce.collectionid = cr.resourceid
                    left outer join resourceviews rv on ce.resourceid = rv.resourceid
                    left outer join visits v on v.visitsid = rv.visitid
                    where cr.pageurl = '$collection_slug'
                    and DATE(rv.viewdate) >= DATE('{$start_date}') and DATE(rv.viewdate) <= DATE('{$end_date}')
                    group by r.resourceid
                ";
        return $sql;
    }

    public static function filterResourcesByGA($resources, $ga_records_slugs) {
        $matched_resources = array();
        foreach ($resources as $key => $resource) {
            if (array_key_exists($resource->pageurl, $ga_records_slugs)) {
                $matched_resources[] = $resource;
            }
        }
        return $matched_resources;
    }

}
