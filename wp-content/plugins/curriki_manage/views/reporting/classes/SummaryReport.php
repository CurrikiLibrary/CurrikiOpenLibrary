<?php
require_once 'UniqueUsersSummaryReport.php';
require_once 'ResourceDownloadsSummaryReport.php';
require_once 'ResourceViewsSummaryReport.php';
require_once 'GeoSummaryReport.php';

/**
 * Description of SummaryReport
 *
 * @author waqarmuneer
 */
class SummaryReport {
    
    public static function get_records($contributorid, $start_date, $end_date , $collection_slug = "") {        
        
        $unique_users = UniqueUsersSummaryReport::get_unique_users($contributorid, $start_date, $end_date , $collection_slug);        
        $number_of_downloads = ResourceDownloadsSummaryReport::get_number_of_downloads($contributorid, $start_date, $end_date , $collection_slug);        
        $number_of_resource_views = ResourceViewsSummaryReport::get_pageviews($contributorid, $start_date, $end_date , $collection_slug);                        
        $geo_data = GeoSummaryReport::get_geoip($contributorid, $start_date, $end_date , $collection_slug);
        $geo_data_resources = $geo_data['geo_data_resources'];
        $geo_data_collections = $geo_data['geo_data_collections'];       
            
        return array(
            'resources' => array(
                'unique_users' => $unique_users["resources_unique_users_sum"],
                'number_of_resource_views' => $number_of_resource_views["resources_pageviews_sum"],
                'number_of_downloads' => $number_of_downloads["resources_downloads_sum"],
                'percent_visitor_unknown' => $geo_data_resources["percent_visitor_unknown"],
                'percent_us' => $geo_data_resources["percent_us"],
                'percent_international' => $geo_data_resources["percent_international"]
            ),
            'collections' => array(
                'unique_users' => $unique_users["collections_unique_users_sum"],
                'number_of_resource_views' => $number_of_resource_views["collections_pageviews_sum"],
                'number_of_downloads' => $number_of_downloads["collections_downloads_sum"],
                'percent_visitor_unknown' => $geo_data_collections["percent_visitor_unknown"],
                'percent_us' => $geo_data_collections["percent_us"],
                'percent_international' => $geo_data_collections["percent_international"]
            ),
        );
    }
    
}
