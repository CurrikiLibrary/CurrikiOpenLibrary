<?php
add_action( 'wp_ajax_process_membertype_report', 'process_membertype_report' );
add_action( 'wp_ajax_nopriv_process_membertype_report', 'process_membertype_report' );
function process_membertype_report(){
    
    $data_arr = [];    
    parse_str($_REQUEST["data"] , $data_arr);
    if( isset($data_arr['paged']) ){
        $_REQUEST['paged'] = $data_arr['paged'];
    }            
    
    require_once 'MembertypeReportHelper.php';    
    
    if($data_arr['form_aciton'] === 'get_membertype_userdata'){        
        $user_data_export = process_userdata_csv($data_arr);
        echo json_encode($user_data_export);        
        wp_die();
    }
    
    $do_download_csv = false;
    if(isset($data_arr['get_csv']) && $data_arr['get_csv'] == 1){
        $do_download_csv = true;                  
    }
    
    $contributorid = 0;      
    if( isset($data_arr['contributor_slug']) && strlen($data_arr['contributor_slug']) > 0 && isset($data_arr['get_membertype_by_contributor']) && $data_arr['get_membertype_by_contributor'] === "GO" ){        
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
        
    $all_resources = MembertypeReportHelper::get_membertype_summary_stats($contributorid,$start_date,$end_date, $data_arr['collection_slug_membertype_report'],'resource');
    $all_collections = MembertypeReportHelper::get_membertype_summary_stats($contributorid,$start_date,$end_date, $data_arr['collection_slug_membertype_report'],'collection');
    $summary_stats = array();
    $summary_stats[] = array("All Collections", $all_collections['teachers_stats']['views_count'], $all_collections['students_stats']['views_count'], $all_collections['parents_stats']['views_count']);
    $summary_stats[] = array("All Resources", $all_resources['teachers_stats']['views_count'], $all_resources['students_stats']['views_count'], $all_resources['parents_stats']['views_count']);
    
    $resoruces_stats = MembertypeReportHelper::get_membertype_detail_stats($contributorid,$start_date,$end_date, $data_arr['collection_slug_membertype_report']);
    
    $file_summary = $file_detail = null;
    if($do_download_csv){
        $columns_summary = array('Summary (Resources & Collections)','Teachers','Student','Parents');
        $file_summary = generate_csv_membertype('MemberType Summary Report', $summary_stats,$columns_summary,$data_arr,$start_date,$end_date);
        $columns_detail = array('Individual (Resources & Collections)','Type (resource/collection)','Teachers','Students','Parents');
        $file_detail = generate_csv_membertype('Individual Resources and Collections MemberType Report', $resoruces_stats,$columns_detail,$data_arr,$start_date,$end_date);        
    }
    
    echo json_encode( array(
        "summary_stats" => $summary_stats , 'resoruces_stats' => $resoruces_stats,
        "other" => array(
            'summary_csv_file' => $file_summary,
            'detail_csv_file' => $file_detail
        )
    ) );
    
    wp_die();            
}

function process_userdata_csv($data_arr){
    
    $data_arr = [];    
    parse_str($_REQUEST["data"] , $data_arr);
    if( isset($data_arr['paged']) ){
        $_REQUEST['paged'] = $data_arr['paged'];
    }                
    
    require_once 'MembertypeReportHelper.php';      
    
    $contributorid = 0;      
    if( isset($data_arr['contributor_slug']) && strlen($data_arr['contributor_slug']) > 0 && isset($data_arr['get_membertype_by_contributor']) && $data_arr['get_membertype_by_contributor'] === "GO" ){        
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
    
    $resoruces_stats = MembertypeReportHelper::get_users_data($contributorid,$start_date,$end_date, $data_arr['collection_slug_membertype_report']);       
    $columns_detail = array('ID','First Name','Last Name','User Login','Member Type','Email');
    $file_detail = generate_csv_memberdata('MemberType Users Data', $resoruces_stats,$columns_detail,$data_arr,$start_date,$end_date);        
    return array('userdata_csv_file' => $file_detail);
        
}


function generate_csv_memberdata($report_name,$report_data,$columns,$data_arr,$start_date,$end_date){
    $roport_for = "";
    if(strlen($data_arr['collection_slug_membertype_report']) > 0){
        $roport_for = 'Collection: '.$data_arr['collection_slug_membertype_report'];
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
       
    return  make_report_csv_membertype_report( $data_for_csv,$report_name,$mode);
}


function get_report_total_membertype($report_records){
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


function generate_csv_membertype($report_name,$report_data,$columns,$data_arr,$start_date,$end_date){
    $roport_for = "";
    if(strlen($data_arr['collection_slug_membertype_report']) > 0){
        $roport_for = 'Collection: '.$data_arr['collection_slug_membertype_report'];
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
       
    return  make_report_csv_membertype_report( $data_for_csv,$report_name,$mode);
}

function make_report_csv_membertype_report($data,$report_name,$mode = "write"){   
    
    $report_name = implode( "_" , explode(" ", strtolower($report_name)) )."_membertype";
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
        if($report_name === 'individual_resources_and_collections_membertype_report_membertype'){
            $value = csv_column_order_membertype($key , $value , $report_name);
        }        
        fputcsv($fp, $value);
    }
    fclose($fp);
    
    return $file;
}

function csv_column_order_membertype($key , $row , $report_name){
    
    $row_arranged = $row;    
    if($key > 4)
    {
        $row_arranged = array(
            'Title' => $row['title'],
            'Type' => $row['type'],
            'Teachers' => $row['teachers_views'],
            'Students' => $row['students_views'],
            'Parents' => $row['parents_views'],
        );
    }
    
    return $row_arranged;
}
