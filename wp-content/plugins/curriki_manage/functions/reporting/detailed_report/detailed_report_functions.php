<?php

add_action( 'wp_ajax_process_detailed_report', 'process_detailed_report' );
add_action( 'wp_ajax_nopriv_process_detailed_report', 'process_detailed_report' );
function process_detailed_report() {
    
    $data_arr = [];    
    parse_str($_REQUEST["data"] , $data_arr);
    if( isset($data_arr['paged']) ){
        $_REQUEST['paged'] = $data_arr['paged'];
    }    
    
    require_once __DIR__ . '/Report_List_Table.php';    
    
    $do_download_csv = false;
    if(isset($data_arr['get_csv']) && $data_arr['get_csv'] == 1){
        $do_download_csv = true;                  
    }
    
    $contributorid = 0;
    $userListTable = new Report_List_Table(); //Create an instance of our package class...                     
    if( isset($data_arr['contributor_slug']) && strlen($data_arr['contributor_slug']) > 0 && isset($data_arr['get_summary_by_contributor']) && $data_arr['get_summary_by_contributor'] === "GO" ){
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
        
    
    $userListTable->prepare_items($contributorid,$start_date,$end_date, $data_arr['collection_slug_detailed_report']);
    
    ob_start();
    //$this->display_rows();
    $rows = ob_get_clean();

    $response = array( 'rows' => $rows );
    /* Prepared data for lazy loading */
    $page_records = $userListTable->items;
    $current_page = $userListTable->get_pagenum();
    $total_pages = $userListTable->get_pagination_arg('total_pages');    
     
    if($do_download_csv && count($page_records) > 0){
        $data_for_csv = $page_records;            
        
        $mode = "";
        if($current_page == 1){
            $mode = "write";            
            $roport_for = "";
            if(strlen($data_arr['collection_slug_detailed_report']) > 0){
                $roport_for = 'Collection: '.$data_arr['collection_slug_detailed_report'];
            }else{
                $roport_for = 'Contributor: '.$data_arr['contributor_slug'];
            }            
            array_unshift($data_for_csv , $userListTable->get_columns());            
            array_unshift( $data_for_csv , array(' ') );
            array_unshift( $data_for_csv , array($roport_for , 'Start Date: '.$start_date , 'End Date: '.$end_date) );                        
            array_unshift( $data_for_csv , array(' ') );
            array_unshift( $data_for_csv , array('Detailed Report') ); 
        }else{
            $mode = "append";
        }
        
        $csv_file = make_report_csv($data_for_csv,"detailed",$mode);
    }     
    
    echo json_encode( array("records" => $page_records , "current_page" => $current_page , "total_pages" => $total_pages) );
    //echo json_encode( $page_records );
    die(); // this is required to terminate immediately and return a proper response
}

function make_report_csv($data,$report_type = "detailed" , $mode = "write"){    
    $file = "report_$report_type.csv";
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
        $cntr++;
        if( isset($value['url']) )
        {
            $value["url"] = strip_tags($value['url']);
        }
        fputcsv($fp, $value);
    }
    fclose($fp);
    
    return $file;
}