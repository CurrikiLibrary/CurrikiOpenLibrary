<?php
add_action( 'wp_ajax_process_geography_report', 'process_geography_report' );
add_action( 'wp_ajax_nopriv_process_geography_report', 'process_geography_report' );
function process_geography_report(){
    
    $data_arr = [];    
    parse_str($_REQUEST["data"] , $data_arr);
    if( isset($data_arr['paged']) ){
        $_REQUEST['paged'] = $data_arr['paged'];
    }    
    require_once 'GeographyHelper.php';    
    
    $do_download_csv = false;
    if(isset($data_arr['get_csv']) && $data_arr['get_csv'] == 1){
        $do_download_csv = true;                  
    }
    
    $contributorid = 0;      
    if( isset($data_arr['contributor_slug']) && strlen($data_arr['contributor_slug']) > 0 && isset($data_arr['get_geography_by_contributor']) && $data_arr['get_geography_by_contributor'] === "GO" ){        
        $user = get_user_by( "login", urldecode($data_arr['contributor_slug']) );                        
        if($user){
            $contributorid = intval($user->ID);                                            
        }else{
            $contributorid = -1;
        }
    }else{
        $contributorid = -1;
    }

    $start_date = isset($data_arr['startdate']) && strlen($data_arr['startdate']) > 0 ? $data_arr['startdate'] : '';
    $end_date = isset($data_arr['startdate']) && strlen($data_arr['enddate']) > 0 ? $data_arr['enddate'] : '';

    if( strlen($start_date) > 0 || strlen($end_date) > 0 )
    {
        $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
        $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;        
    }
    
    $us_details = GeographyHelper::get_details_by_country($contributorid,$start_date,$end_date, $data_arr['collection_slug_geography_report'],'US');                
    $country_summary = GeographyHelper::get_country_summary($contributorid,$start_date,$end_date, $data_arr['collection_slug_geography_report']);        
    
    $file_cs = $file_us = null;
    if($do_download_csv){
        $columns_cs = array('Country','Resource Pageviews','Collection Pageviews','Total');
        $file_cs = generate_csv('Country Summary', $country_summary,$columns_cs,$data_arr,$start_date,$end_date);                        
    
        $columns_us = array('State','Resource Pageviews','Collection Pageviews','Total');
        $file_us = generate_csv('US Detail', $us_details,$columns_us,$data_arr,$start_date,$end_date);                        
    }
   
    $country_summary_data = array("records" => $country_summary , "total" => get_report_total($country_summary) , 'other' => array('file'=>$file_cs));
    $us_details_data = array("records" => $us_details , "total" => get_report_total($us_details) , 'other' => array('file'=>$file_us));
    
    echo json_encode( array("country_summary" => $country_summary_data , 'us_details' => $us_details_data) );
    die();
    
}

function get_report_total($report_records){
    $total = array(
        'resources_pageviews' => 0,
        'collections_pageviews' => 0,
        'pageviews' => 0
    );    
    foreach ($report_records as $record) {
        $total["resources_pageviews"] += $record->resources_pageviews;
        $total["collections_pageviews"] += $record->collections_pageviews;
        $total["pageviews"] += $record->pageviews;
    }    
    return $total;
}


function generate_csv($report_name,$report_data,$columns,$data_arr,$start_date,$end_date){
    $roport_for = "";
    if(strlen($data_arr['collection_slug_geography_report']) > 0){
        $roport_for = 'Collection: '.$data_arr['collection_slug_geography_report'];
    }else{
        $roport_for = 'Contributor: '.$data_arr['contributor_slug'];
    }
    
    $data_for_csv = $report_data;
    $mode = "write";
    array_unshift($data_for_csv , $columns);
    array_unshift( $data_for_csv , array(' ') );
    array_unshift( $data_for_csv , array($roport_for , 'Start Date: '.$start_date , 'End Date: '.$end_date) );
    array_unshift( $data_for_csv , array(' ') );
    array_unshift( $data_for_csv , array($report_name) ); 
    
    //*** Adding Total to DataSet ****
    $total = get_report_total($data_for_csv);
    array_unshift($total, "Total");
    $data_for_csv[] = array("","","","");    
    $data_for_csv[] = $total;    
    
    return  make_report_csv_geo_report( $data_for_csv,$report_name,$mode);
}

function make_report_csv_geo_report($data,$report_name,$mode = "write"){   
    
    $report_name = implode( "_" , explode(" ", strtolower($report_name)) )."_geography";
    $file = "$report_name.csv";
    $file_path =  ABSPATH.'wp-admin/images/';
    $csv_file = $file_path.$file;    
    if( file_exists($csv_file) ){
        //unlink($csv_file);
    }
    $list = $data;    
    $write_mode = "";
    if($mode === "write")
        $write_mode = 'w+';
    else
        $write_mode = 'a';
    
    $fp = fopen($csv_file, $write_mode);

    gettype($fp);
    $cntr = 0;
    foreach ($list as $key => $value) {
        $value = (array) $value;
        $cntr++;
        /*if( isset( $value['url'] ) )
        {
            $value["url"] = strip_tags($value['url']);
        }*/        
        fputcsv($fp, csv_column_order($key , $value , $report_name));
    }
    fclose($fp);
    
    return $file;
}

function csv_column_order($key , $row , $report_name){
    $keys_for_rearrange = false;
    if( key_exists("country_name", $row) || key_exists("region", $row) ){
        $keys_for_rearrange = true;
    }
    
    $row_arranged = $row;    
    if($key > 4 && $keys_for_rearrange)
    {    
        $col_0 = $report_name==='country_summary_geography'?$row['country_name']: $row['region'];
        $row_arranged = array($col_0,
                            $row['resources_pageviews'],
                            $row['collections_pageviews'],
                            $row['pageviews']);        
    }    
    
    return $row_arranged;
}
