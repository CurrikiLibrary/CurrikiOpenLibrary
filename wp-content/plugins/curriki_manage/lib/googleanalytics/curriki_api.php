<?php

function ga_records_slugs($startdate, $enddate , $current_paging){     
    require_once __DIR__ . '/GoogleAnalyticsClient.php';
    $ga_records_slugs = array();
    if(function_exists("fetch_ga_records")){
        try{
            $ga_data = fetch_ga_records($startdate, $enddate , $current_paging);
            $ga_records = $ga_data['ga_records'];
            $paging_info = $ga_data['paging_info'];
            $ga_records_slugs['paging_info'] = $paging_info;        
            $ga_slugs = array();
            if($ga_records === null){
                $ga_slugs = array();
            }else{            
                $ga_slugs = ga_cur_format_api_result($ga_records);            
            }
            $ga_records_slugs['ga_records_slugs'] = $ga_slugs;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    return $ga_records_slugs;
}

/****[start] fetching/formating slugs from Google-Analytics-API to compare with resources.pagurl [/start]****/
function ga_cur_format_api_result($ga_records){
    $ga_records_slugs = array();
    $slugs_and_views_bag = array();
    $slugs_and_country_bag = array();
    foreach ($ga_records as $record) {
        $pageTitle = $record[0];
        $slugString = strip_tags($record[1]);
        $country = $record[2];
        $pageviews = intval($record[3]);

        $slugArr = explode('/', $slugString);
        if(count($slugArr) >= 3){
            $slug = $slugArr[2];

            /***storing/setting page views***/
            if(array_key_exists($slug, $slugs_and_views_bag)){
                $slugs_and_views_bag[$slug] = $slugs_and_views_bag[$slug] + $pageviews;
            }else{
                $slugs_and_views_bag[$slug] = $pageviews;
            }

            /***storing/setting page views***/
            $slugs_and_country_bag[$slug][$country]['pageviews'][] = $pageviews;

            $ga_records_slugs[$slug] = array("pageTitle"=>$pageTitle,"slug"=>$slug,"pageviews"=>$slugs_and_views_bag[$slug], "countries"=>$slugs_and_country_bag[$slug]);
        }
    }    
    return $ga_records_slugs;
}
/****[end] fetching/formating slugs from Google-Analytics-API to compare with resources.pagurl [/end]****/