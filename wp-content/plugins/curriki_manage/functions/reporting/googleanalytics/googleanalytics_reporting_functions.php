<?php

add_action('wp_ajax_process_ga_report', 'process_ga_report');
add_action('wp_ajax_nopriv_process_ga_report', 'process_ga_report');

function process_ga_report() {

    $data_arr = [];
    parse_str($_REQUEST["data"], $data_arr);
    if (isset($data_arr['paged'])) {
        $_REQUEST['paged'] = $data_arr['paged'];
    }

    $current_paging = array();
    if (is_array($_REQUEST['next_paging_info'])) {
        $current_paging = $_REQUEST['next_paging_info'];
    } else {
        $current_paging['start-index'] = 1;
    }

    $gaProcessClassPath = __DIR__ . "/GAProcess.php";

    try 
    {
        if (file_exists($gaProcessClassPath)) {
            require_once( $gaProcessClassPath );
            $do_download_csv = false;
            $contributorid = 0;

            $gaProcess = new GAProcess(); //Create an instance of our package class...                     

            if (isset($data_arr['contributor_slug']) && strlen($data_arr['contributor_slug']) > 0 && isset($data_arr['get_ga_by_contributor']) && $data_arr['get_ga_by_contributor'] === "GO") {
                $user = get_user_by("login", urldecode($data_arr['contributor_slug']));

                if ($user) {
                    $contributorid = intval($user->ID);
                    $do_download_csv = (isset($data_arr['get_csv_ga']) && $data_arr['get_csv_ga'] == 1) ? true : false;
                } else {
                    $contributorid = -1;
                }
            } else {
                $contributorid = -1;
            }

            $start_date = isset($data_arr['startdate']) && strlen($data_arr['startdate']) > 0 ? $data_arr['startdate'] : '';
            $end_date = isset($data_arr['startdate']) && strlen($data_arr['enddate']) > 0 ? $data_arr['enddate'] : '';

            if (strlen($start_date) > 0 || strlen($end_date) > 0) {
                $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
                $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;
            }

            $curriki_ga_api = realpath(__DIR__ . '/../../..') . '/lib/googleanalytics/curriki_api.php';
            $resources = array();
            $next_paging_info = array('max-results' => null, 'start-index' => null, 'total_results' => null);

            if (file_exists($curriki_ga_api) && ( $contributorid > -1 || strlen(trim($data_arr['collection_slug_ga_report'])) > 0 )) {

                require_once $curriki_ga_api;

                $ga_response = ga_records_slugs($start_date, $end_date, $current_paging);
                $ga_records_slugs = $ga_response['ga_records_slugs'];
                $paging_info = $ga_response['paging_info'];
                $nextLinkVars = $paging_info['nextLinkVars'];
                $resources = $gaProcess->get_records($contributorid, $start_date, $end_date, $ga_records_slugs, $data_arr['collection_slug_ga_report']);
                foreach ($resources as $key => $resource) {
                    $link = site_url() . "/oer/{$resource->pageurl}";

                    //*** set 'url' in output *****
                    $resources[$key]->url = "<a href='{$link}' target='__blank'>{$resource->pageurl}</a>";

                    /** set 'ga_pageviews' in output * */
                    if (array_key_exists($resource->pageurl, $ga_records_slugs)) {
                        $resources[$key]->ga_pageviews = $ga_records_slugs[$resource->pageurl]['pageviews'];
                    } else {
                        $resources[$key]->ga_pageviews = 0;
                    }

                    /** set 'countries_aggregate' in output * */
                    $resources[$key]->countries = $ga_records_slugs[$resource->pageurl]['countries'];
                    $resources[$key]->countries_aggregate = cur_ga_countries_aggregate($ga_records_slugs[$resource->pageurl]['countries']);

                    /** set 'views count for USA and International' in output * */
                    $resources[$key]->count_for_percent_in_usa = cur_ga_countries_percent_in_usa($resources[$key]->countries_aggregate);
                    $resources[$key]->count_for_percent_in_intl = cur_ga_countries_percent_in_intl($resources[$key]->countries_aggregate);


                    /** set 'views percent for USA and International' in output * */
                    $ga_pageviews = intval($resources[$key]->ga_pageviews);
                    $ga_pageviews = $ga_pageviews === 0 ? 1 : $ga_pageviews;
                    $resources[$key]->percent_val_for_percent_in_usa = round((intval($resources[$key]->count_for_percent_in_usa) / $ga_pageviews) * 100, 2) . '%';
                    $resources[$key]->percent_val_for_percent_in_intl = round((intval($resources[$key]->count_for_percent_in_intl) / $ga_pageviews) * 100, 2) . '%';

                    /** set views for unknows * */
                    $count_for_unkown_location = $resources[$key]->ga_pageviews - ($resources[$key]->count_for_percent_in_usa + $resources[$key]->count_for_percent_in_intl);
                    $resources[$key]->percent_val_for_unknown_location = round((intval($count_for_unkown_location) / $ga_pageviews) * 100, 2) . '%';
                }
                $next_paging_info['max-results'] = $nextLinkVars['max-results'];
                $next_paging_info['start-index'] = $nextLinkVars['start-index'];
                $next_paging_info['total_results'] = $paging_info['totalResults'];
            }

            $csv_file = "";
            if ($do_download_csv && count(intval($current_paging['start-index'])) > 0) {

                $data_for_csv = prepare_csv_data($resources);
                $mode = "";
                if (intval($current_paging['start-index']) === 1) {
                    $mode = "write";
                    $roport_for = "";
                    if (strlen(trim($data_arr['collection_slug_ga_report'])) > 0) {
                        $roport_for = 'Collection: ' . $data_arr['collection_slug_ga_report'];
                    } else {
                        $roport_for = 'Contributor: ' . $data_arr['contributor_slug'];
                    }
                    array_unshift($data_for_csv, array('Resource Title', 'Url', 'Type', 'Page Views', '% visitors unknown', '% in US (based on GA)', '% international (based on GA)'));
                    array_unshift($data_for_csv, array(' '));
                    array_unshift($data_for_csv, array($roport_for, 'Start Date: ' . $start_date, 'End Date: ' . $end_date));
                    array_unshift($data_for_csv, array(' '));
                    array_unshift($data_for_csv, array('Google Analytics Report'));
                } else {
                    $mode = "append";
                }

                $csv_file = make_report_csv_ga($data_for_csv, "google_analytics", $mode);
            }


            echo json_encode(array(
                'response_status' => 'success',
                'resources' => $resources,
                'next_paging_info' => $next_paging_info,
                'other' => array('csv_file' => $csv_file)
            ));
            
        } else {
            echo json_encode(array('response_status' => 'fail', 'message' => 'some thing went wrong', 'detail' => null));
        }
        
    } catch (Exception $ex) {        
        echo json_encode(array(
            'response_status' => 'fail', 
            'message' => 'some thing went wrong', 
            'detail' => json_decode($ex->getMessage())
            ));        
    }
    wp_die();
}

function prepare_csv_data($resources) {
    $csv_data = array();
    foreach ($resources as $key => $resource) {
        //if($key > 4){
        $csv_data[] = array('title' => $resource->title,
            'url' => $resource->url,
            'type' => $resource->type,
            'ga_pageviews' => $resource->ga_pageviews,
            'percent_val_for_unknown_location' => $resource->percent_val_for_unknown_location,
            'percent_val_for_percent_in_usa' => $resource->percent_val_for_percent_in_usa,
            'percent_val_for_percent_in_intl' => $resource->percent_val_for_percent_in_intl);
        //}else{
        //    $csv_data[] = $resource;
        //}
    }
    return $csv_data;
}

function make_report_csv_ga($data, $report_type, $mode) {
    $file = "report_$report_type.csv";
    $file_path = ABSPATH . 'wp-admin/images/';
    $csv_file = $file_path . $file;
    if (file_exists($csv_file)) {
        //unlink($csv_file);
    }
    $list = $data;

    $write_mode = "";
    if ($mode === "write")
        $write_mode = 'w+';
    else
        $write_mode = 'a';

    $fp = fopen($csv_file, $write_mode);

    gettype($fp);
    $cntr = 0;
    foreach ($list as $key => $value) {
        $cntr++;
        if (array_key_exists('url', $value) && isset($value['url'])) {
            $value["url"] = strip_tags($value['url']);
        }
        fputcsv($fp, $value);
    }
    fclose($fp);

    return $file;
}

function cur_ga_countries_aggregate($countries) {
    $countries_aggregate = array();
    foreach ($countries as $country_str => $country_data) {
        $countries_aggregate[$country_str]['pageviews_count'] = array_sum($country_data['pageviews']);
    }
    return $countries_aggregate;
}

function cur_ga_countries_percent_in_usa($countries_aggregate) {
    $us_count = 0;

    if (is_array($countries_aggregate) && count($countries_aggregate) > 0) {
        foreach ($countries_aggregate as $country_str => $country_data) {
            $haystack = $country_str;
            $needle_1 = "United States";
            $needle_2 = "U.S";
            if (strpos($haystack, $needle_1) !== false || strpos($haystack, $needle_2) !== false) {
                $us_count = $us_count + intval($country_data['pageviews_count']);
            }
        }
    }

    return $us_count;
}

function cur_ga_countries_percent_in_intl($countries_aggregate) {
    $intl_count = 0;

    if (is_array($countries_aggregate) && count($countries_aggregate) > 0) {
        foreach ($countries_aggregate as $country_str => $country_data) {
            $found_us = false;
            $haystack = $country_str;
            $needle_1 = "United States";
            $needle_2 = "U.S";
            if (strpos($haystack, $needle_1) !== false || strpos($haystack, $needle_2) !== false) {
                $found_us = true;
            }
            if (!$found_us) {
                $intl_count = $intl_count + intval($country_data['pageviews_count']);
            }
        }
    }

    return $intl_count;
}
