<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Report_List_Table extends WP_List_Table {
    
    public $aStatus = array(
      'T' => '<span style="color:green">Yes</span>',
      'F' => '<span style="color:red">No</span>'
    );

  function __construct() {
    global $status, $page;
    parent::__construct(array(
        'singular' => 'reporting', //singular name of the listed records
        'plural' => 'reportings', //plural name of the listed records
        'ajax' => true        //does this table support ajax?
    ));
  }

  function get_bulk_actions() {
    return array();
    $actions = array(
        'delete' => 'Delete'
    );
    return $actions;
  }

  function process_bulk_action() {
    return array();
    //Detect when a bulk action is being triggered...
    if ('delete' === $this->current_action()) {
      wp_die('Items deleted (or they would be if we had items to delete)!');
    }
  }

  function get_columns() {
    
    $columns = array(
        'title' => 'Resource Title',
        'url' => 'Url',
        'type' => 'Type',
        'unique_users' => 'Unique Users',
        'number_of_resource_views' => 'Number of Views (Resource / Collection)',
        'number_of_resource_views_1' => 'Number of Views (Children)',
        'number_of_downloads' => 'Number of downloads',
        'percent_visitor_unknown' => '% Percent Visitor Unknown',
        'percent_us' => '% in the US (based on visits.ip)',
        'percent_international' => '% international (based on visits.ip)'
    );
    
    return $columns;
  }
  
    
  function get_sortable_columns() {
    $sortable_columns = array(
        'title' => array('title', false), //true means it's already sorted       
        'type' => array('type', false), //true means it's already sorted       
    );
    return $sortable_columns;
  }

  function get_hidden_columns() {
    return array(
        "communityid" => "communityid"
    );
  }

  function column_default($item, $column_name) {
    switch ($column_name) {      
      case 'title':
        return $item[$column_name];
        break;
      case 'url':
        return $item[$column_name];
        break;
      case 'type':
        return $item[$column_name];
        break;     
      case 'unique_users':
        return $item[$column_name];
        break;      
      case 'number_of_resource_views':
        return $item[$column_name];
        break;      
      case 'number_of_resource_views_1':
        return $item[$column_name];
        break;      
      case 'number_of_downloads':
        return $item[$column_name];
        break;      
      case 'percent_visitor_unknown':
        return $item[$column_name];
        break;      
      case 'percent_us':
        return $item[$column_name];
        break;      
      case 'percent_international':
        return $item[$column_name];
        break;      
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  function column_name($item) {

    //Build row actions
    $actions = array(
         'edit' => sprintf('<a href="%s/wp-admin/admin.php?page=reportings&action=edit&communityid=%s">Edit</a>', site_url(), $item['communityid']),
         'view' => sprintf('<a href="%s/community/%s" target="_blank">View</a>', site_url(), $item['url']),
         'delete' => sprintf('<a href="%s/wp-admin/admin.php?page=reportings&action=delete&communityid=%s">Delete</a>', site_url(), $item['communityid'])
     );

    //Return the title contents
    return sprintf('%1$s <span style="color:silver"></span>%3$s',
            /* $1%s */ sprintf('<a href="%s/community/%s" target="_blank">%s</a>', site_url(), $item['url'], $item['name']),
            /* $2%s */ $item['name'],
            /* $3%s */ $this->row_actions($actions)
    );
  }

  function column_user_name($item) {
    echo 'test';
    //Return the title contents
    return sprintf('%1$s %2$s ',
            /* $1%s */ $item['firstname'],
            /* $2%s */ $item['lastname']
    );
  }

  function column_cb($item) {
    return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', /* $1%s */ $this->_args['singular'], /* $2%s */ $item['ID']);
  }

  function prepare_items($contributorid = -1 ,$start_date = '' , $end_date = '' , $collection_slug) {
    
    global $wpdb; //This is used only if making any database queries

    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->process_bulk_action();
    
    $per_page = 25;    
    $total_items = $this->record_count($contributorid,$start_date,$end_date, $collection_slug);
    $current_page = $this->get_pagenum();
    
    $this->set_pagination_args(array(
        'total_items' => $total_items, //WE have to calculate the total number of items
        'per_page' => $per_page //WE have to determine how many items to show on a page
    ));

    $this->items = $this->get_records($per_page, $current_page , $contributorid , $start_date, $end_date,$collection_slug);
  }

  public static function record_count($contributorid = -1, $start_date = '' , $end_date = '', $collection_slug = '') {
    global $wpdb;

    $sql = "";
    if( strlen($start_date) > 0 || strlen($end_date) > 0 )
    {
        $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
        $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;
        
        $sql_contributorid_clause = "resources.contributorid IN ({$contributorid})";
        $sql_collection_clause = "";        
        
        if(strlen($collection_slug) > 0) {
            $sql_contributorid_clause = "";
            $sql_collection_clause = "resources.pageurl = '$collection_slug'"; 
            $sql_union_collection_elements = "
                                                UNION
                                                select count(collectionelements.resourceid) as count
                                                FROM collectionelements
                                                JOIN resources ON (collectionelements.resourceid = resources.resourceid)
                                                JOIN resources as  r_of_re on (collectionelements.collectionid = r_of_re.resourceid)
                                                where r_of_re.pageurl = '$collection_slug'
                                            ";
            
        }
        
        $sql .= "select 
                    count(DISTINCT resourceviews.resourceid)
                    from resourceviews
                    join resources on resources.resourceid = resourceviews.resourceid
                    where 
                    {$sql_contributorid_clause}
                    {$sql_collection_clause}
                    and DATE(resourceviews.viewdate) >= DATE('{$start_date}') and DATE(resourceviews.viewdate) <= DATE('{$end_date}')
                    {$sql_union_collection_elements}
                ";             
    }else{
        //$sql_collection_clause = strlen($collection_slug) > 0 ? "and resources.pageurl = '$collection_slug'" : "";
        
        $sql_contributorid_clause = "resources.contributorid IN ({$contributorid})";
        $sql_collection_clause = "";        
        $sql_union_collection_elements = ""; 
        if(strlen($collection_slug) > 0) {
            $sql_contributorid_clause = "";
            $sql_collection_clause = "resources.pageurl = '$collection_slug'";
            $sql_union_collection_elements = "
                                                UNION
                                                select count(collectionelements.resourceid) as count
                                                FROM collectionelements
                                                JOIN resources ON (collectionelements.resourceid = resources.resourceid)
                                                JOIN resources as  r_of_re on (collectionelements.collectionid = r_of_re.resourceid)
                                                where r_of_re.pageurl = '$collection_slug'
                                            ";
        }
        
        $sql .= "select count(resourceid) as count from resources";
        $sql .= " where 
                {$sql_contributorid_clause}
                {$sql_collection_clause}    
                {$sql_union_collection_elements}";    
    }
              
    $result = $wpdb->get_results($sql);    
    $count = intval($result[0]->count) + intval($result[1]->count);    
    return $count;
    
  }
  
  public static function findChildren($collectionid, $startdate = '', $enddate = '', &$return = array(), &$count = -1, &$leafcount = 0, &$leafarr = array(), &$sum_of_pageviews = 0 , &$processed_collection_ids) {
        global $wpdb; // this is how you get access to the database
        $temp_parentresourceid = null;
        
        if(in_array($collectionid, $processed_collection_ids))
        {           
            return false;
        }
        
        $processed_collection_ids[]= $collectionid;
                        
        if ($count >= 0):
            $children_res = $wpdb->get_results($wpdb->prepare(
                            "SELECT collectionelements.collectionid as parentresourceid, 
                collectionelements.resourceid as resourceid,
                resources.pageurl as parentpageurl
                FROM collectionelements
                INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
                WHERE collectionelements.collectionid = %d", $collectionid
            ));
        else:
            $children_res = $wpdb->get_results($wpdb->prepare(
                            "SELECT collectionelements.collectionid as parentresourceid, 
                collectionelements.resourceid as resourceid,
                resources.pageurl as parentpageurl
                FROM collectionelements
                INNER JOIN resources ON collectionelements.collectionid=resources.resourceid
                WHERE resources.pageurl = %s", $collectionid
            ));
        endif;
        $current_user = wp_get_current_user();
        if ($current_user->user_login == 'wpadmin') {
            if ($wpdb->num_rows == 0) {
                if (!in_array($collectionid, $leafarr)) {
                    $leafarr[] = $collectionid;
                }

    
            }    
        }
        
        // $c is the counter
        $c = 0;
        $cou = 0;
        $temp_arr = array();
        foreach ($children_res as $child) {
            // Get Page Views Count
            if ($startdate != '' && $enddate != '') {
                $res_page_views = $wpdb->get_row($wpdb->prepare(
                                "SELECT COUNT(resourceid) as pageviews FROM resourceviews
                                WHERE resourceid = %d
                                and (viewdate BETWEEN %s AND DATE_ADD(%s, INTERVAL 1 DAY))
                                ", $child->resourceid, $startdate, $enddate
                ));
            } else {
                $res_page_views = $wpdb->get_row($wpdb->prepare(
                                "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", $child->resourceid
                ));
            }

            // Select child resources to get their details
            $child_res_data = $wpdb->get_row($wpdb->prepare(
                            "SELECT * FROM resources 
                                WHERE resourceid = %d
                                ", $child->resourceid
            ));


            $pageurl = $child_res_data->pageurl;
            $pageviews = $res_page_views->pageviews;


            // Logic 
            if ($temp_parentresourceid != $child->parentresourceid):
                $count++;
            endif;

            $parentresourceid = $child->parentresourceid;
            $resourceid = $child->resourceid;
            $parentpageurl = $child->parentpageurl;

            $return[$count]['counter'] = $count + 1;
            $return[$count]['parentresourceid'] = $child->parentresourceid;
            $return[$count]['parentpageurl'] = $child->parentpageurl;
            $return[$count]['leafcount'] = count($leafarr);
            $sum_of_pageviews += $pageviews;
            $return[$count]['sum_of_pageviews'] = $sum_of_pageviews;
            

            $children = array(
                'resourceid' => $child->resourceid,
                'childpageurl' => $pageurl,
                'pageviews' => $pageviews,
            );

            $return[$count]['children'][$c] = $children;

            $temp_parentresourceid = $child->parentresourceid;

            // $temp_arr to be loop through resource ids
            $temp_arr[$c++]['resourceid'] = $child->resourceid;
        }
        foreach ($temp_arr as $ar):
            self::findChildren($ar['resourceid'], $startdate, $enddate, $return, $count, $leafcount, $leafarr , $sum_of_pageviews , $processed_collection_ids);
        endforeach;

        return $return;
    }
  
public static function get_records($per_page = 5, $page_number = 1 , $contributorid = 0 , $start_date = '' , $end_date = '', $collection_slug) {
    global $wpdb;
    $result = array();
    
    
    $geo_ip_q = "SELECT * FROM geodb_country where country_code = 'US'";        
    $geo_ip_q_rcds = $wpdb->get_results( $geo_ip_q );        
    
    $sql = "";
    if( strlen($start_date) > 0 || strlen($end_date) > 0 )
    {
        $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
        $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;
        
        //$sql_collection_clause = strlen($collection_slug) > 0 ? "and resources.pageurl = '$collection_slug'" : "";
        $sql_contributorid_clause = "resources.contributorid IN ({$contributorid})";
        $sql_collection_clause = "";        
        $sql_union_collection_elements = "";
        
        if(strlen($collection_slug) > 0) {
            $sql_contributorid_clause = "";
            $sql_collection_clause = "resources.pageurl = '$collection_slug'";        
            $sql_union_collection_elements = "
                                                UNION
                                                select resources.*
                                                FROM collectionelements
                                                JOIN resources ON (collectionelements.resourceid = resources.resourceid)
                                                JOIN resources as  r_of_re on (collectionelements.collectionid = r_of_re.resourceid)
                                                where r_of_re.pageurl = '$collection_slug'
                                            ";
        }
        
        $sql .= "
                    select * from resources
                    where resourceid IN (
                        select 
                        DISTINCT resourceviews.resourceid
                        from resourceviews
                        join resources on resources.resourceid = resourceviews.resourceid
                        where 
                        {$sql_contributorid_clause}
                        {$sql_collection_clause}
                        and DATE(resourceviews.viewdate) >= DATE('{$start_date}') and DATE(resourceviews.viewdate) <= DATE('{$end_date}')                                                
                    )
                    {$sql_union_collection_elements}
                ";                                                
    }else{
        
        $sql_contributorid_clause = "resources.contributorid IN ({$contributorid})";
        $sql_collection_clause = "";        
        $sql_union_collection_elements = "";
        if(strlen($collection_slug) > 0) {
            $sql_contributorid_clause = "";
            $sql_collection_clause = "resources.pageurl = '$collection_slug'";
            $sql_union_collection_elements = "
                                                UNION
                                                select resources.*
                                                FROM collectionelements
                                                JOIN resources ON (collectionelements.resourceid = resources.resourceid)
                                                JOIN resources as  r_of_re on (collectionelements.collectionid = r_of_re.resourceid)
                                                where r_of_re.pageurl = '$collection_slug'
                                            ";
        }
        
        
        $sql .= "select * from resources";
        $sql .= " where
                {$sql_contributorid_clause}
                {$sql_collection_clause}
                {$sql_union_collection_elements}
                ";
        
    }
    
    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }  else {
        $sql .= ' ORDER BY type ASC , resourceid ASC';
    }
    //=== Setting limit offset for pagination ===
    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
    
    $resources = $wpdb->get_results($sql, 'ARRAY_A');    
        
    $processed_collection_ids = array();
    
    $result = array();
    foreach ($resources as $resource) {        
        
        $startdate = ''; $enddate = '';
        
        /** Unique Users **/
        $unique_users = $wpdb->get_var( $wpdb->prepare( "SELECT count(DISTINCT userid) as unique_users FROM resourceviews where resourceid = %d", intval($resource['resourceid']) ) );
        
        /** Number  of Resource Views **/        
        if (strlen($startdate) > 0 && strlen($enddate) > 0) {
            /*$res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(resourceid) as pageviews FROM resourceviews
                            WHERE resourceid = %d
                            and (viewdate BETWEEN %s AND DATE_ADD(%s, INTERVAL 1 DAY))
                            ", $child->resourceid, $startdate, $enddate
            ));*/
        } else {
            
            $number_of_resource_views_val = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", intval($resource['resourceid']) ));
            
            if($resource['type'] === 'collection')
            {                
                $count = -1; $leafcount = 0; $leafarr = array(); $sum_of_pageviews = 0; $return = array();                
                $collection_children = self::findChildren($resource['pageurl'], $startdate, $enddate , $return , $count, $leafcount, $leafarr , $sum_of_pageviews ,$processed_collection_ids);
                
                $collection_children_record = count($collection_children) > 0 ? $collection_children[count($collection_children)-1] : 0;
                $collection_s_resources_views = $collection_children_record["sum_of_pageviews"];
                $collection_s_resources_views = $collection_s_resources_views > 0 ? $collection_s_resources_views : 0;
                
                $number_of_resource_views = $number_of_resource_views_val;
                $number_of_resource_views_1 = $collection_s_resources_views; // children values
            }else{
                
                $number_of_resource_views = $number_of_resource_views_val;
                $number_of_resource_views_1 = null; // in case have resource where there are no children
            }
        }
        
        /** Number of downloads **/
        $number_of_downloads_qry = "SELECT count(filedownloads.downloadid) FROM resources 
                                    inner join resourcefiles on resourcefiles.resourceid = resources.resourceid
                                    right join filedownloads on resourcefiles.fileid = filedownloads.fileid
                                    where resources.resourceid IN (%d)
                                    order by resources.resourceid asc";        
        $number_of_downloads = $wpdb->get_var( $wpdb->prepare( $number_of_downloads_qry, intval($resource['resourceid']) ) );
        
        
        /******** [start] Handle IPs *******/
        $resourceviews_visits_q = "select * from resourceviews 
                                    join visits on visits.visitsid = resourceviews.visitid
                                    where resourceviews.resourceid IN (%d)";
        $resourceviews_visits = $wpdb->get_results( $wpdb->prepare( $resourceviews_visits_q, intval($resource['resourceid']) ) );
        $single_resourceview_visits_in_us = 0;
        $single_resourceview_visits_in_intl = 0;
        foreach ($resourceviews_visits as $rs_vis) {
            foreach ($geo_ip_q_rcds as $geo_ip_record) {
                if($geo_ip_record->ip_from <= $rs_vis->ip_value && $geo_ip_record->ip_to >= $rs_vis->ip_value)
                {
                    $single_resourceview_visits_in_us++;
                }                
            }
        }
        $single_resourceview_visits_in_intl = count($resourceviews_visits) - $single_resourceview_visits_in_us;
        /******** [end] Handle IPs *******/
        
        /*** % of visitors unknown  ***/        
        $percent_visitor_unknown = intval($number_of_resource_views) - count($resourceviews_visits);
                
        /** percentage calculations **/
        $percent_visitor_unknown_pr = round( (intval($percent_visitor_unknown) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";
        $single_resourceview_visits_in_us_pr = round( (intval($single_resourceview_visits_in_us) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";
        $single_resourceview_visits_in_intl_pr = round( (intval($single_resourceview_visits_in_intl) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";
        
        $url_href = site_url()."/oer/{$resource['pageurl']}";
        $result[] = array("title" => $resource['title'] , "url" => "<a target='__blank' href='{$url_href}'>{$resource['pageurl']}</a>" , "type" => $resource["type"] , "unique_users" => $unique_users , "number_of_resource_views" => $number_of_resource_views , "number_of_resource_views_1" => $number_of_resource_views_1 , "number_of_downloads" => $number_of_downloads , "percent_visitor_unknown" => $percent_visitor_unknown_pr , "percent_us" => $single_resourceview_visits_in_us_pr, "percent_international"=>$single_resourceview_visits_in_intl_pr);
    }  
    
    return $result;
  }
  
public function get_csv_data($contributorid = 0 , $start_date = '' , $end_date = '', $collection_slug='') {
    global $wpdb;
    $result = array();
    
    
    $geo_ip_q = "SELECT * FROM geodb_country where country_code = 'US'";        
    $geo_ip_q_rcds = $wpdb->get_results( $geo_ip_q );        
    
    $sql = "";
    if( strlen($start_date) > 0 || strlen($end_date) > 0 )
    {
        $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
        $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;
        
        $sql_collection_clause = strlen($collection_slug) > 0 ? "and resources.pageurl = $collection_slug" : "";
        $sql .= "
                    select * from resources
                    where resourceid IN (
                        select 
                        DISTINCT resourceviews.resourceid
                        from resourceviews
                        join resources on resources.resourceid = resourceviews.resourceid
                        where resources.contributorid IN ({$contributorid}) 
                        and DATE(resourceviews.viewdate) >= DATE('{$start_date}') and DATE(resourceviews.viewdate) <= DATE('{$end_date}')
                        {$sql_collection_clause}
                    )
                ";
    }else{
        $sql_collection_clause = strlen($collection_slug) > 0 ? "and resources.pageurl = $collection_slug" : "";
        $sql .= "select * from resources";
        $sql .= " where contributorid IN ({$contributorid})
                  {$sql_collection_clause}";
    }    
  

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }  else {
        $sql .= ' ORDER BY type ASC , resourceid ASC';
    }
    
    
    $resources = $wpdb->get_results($sql, 'ARRAY_A');    
    
    $processed_collection_ids = array();
    
    $result = array();
    foreach ($resources as $resource) {        
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        $startdate = ''; $enddate = '';
        
        /** Unique Users **/
        $unique_users = $wpdb->get_var( $wpdb->prepare( "SELECT count(DISTINCT userid) as unique_users FROM resourceviews where resourceid = %d", intval($resource['resourceid']) ) );
        
        /** Number  of Resource Views **/        
        if (strlen($startdate) > 0 && strlen($enddate) > 0) {
            /*$res_page_views = $wpdb->get_row($wpdb->prepare(
                            "SELECT COUNT(resourceid) as pageviews FROM resourceviews
                            WHERE resourceid = %d
                            and (viewdate BETWEEN %s AND DATE_ADD(%s, INTERVAL 1 DAY))
                            ", $child->resourceid, $startdate, $enddate
            ));*/
        } else {
            
            $number_of_resource_views_val = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) as pageviews FROM resourceviews WHERE resourceid = %d", intval($resource['resourceid']) ));
            
            if($resource['type'] === 'collection')
            {                
                $count = -1; $leafcount = 0; $leafarr = array(); $sum_of_pageviews = 0; $return = array();                
                $collection_children = self::findChildren($resource['pageurl'], $startdate, $enddate , $return , $count, $leafcount, $leafarr , $sum_of_pageviews ,$processed_collection_ids);
                
                $collection_children_record = count($collection_children) > 0 ? $collection_children[count($collection_children)-1] : 0;
                $collection_s_resources_views = $collection_children_record["sum_of_pageviews"];
                $collection_s_resources_views = $collection_s_resources_views > 0 ? $collection_s_resources_views : 0;

                //$number_of_resource_views = "C($number_of_resource_views_val) R($collection_s_resources_views)";                
                $number_of_resource_views = $number_of_resource_views_val;
                $number_of_resource_views_1 = $collection_s_resources_views; // children values
            }else{
                
                //$number_of_resource_views = $number_of_resource_views_val;
                
                $number_of_resource_views = $number_of_resource_views_val;
                $number_of_resource_views_1 = null; // in case have resource where there are no children
            }
        }
        
        /** Number of downloads **/
        $number_of_downloads_qry = "SELECT count(filedownloads.downloadid) FROM resources 
                                    inner join resourcefiles on resourcefiles.resourceid = resources.resourceid
                                    right join filedownloads on resourcefiles.fileid = filedownloads.fileid
                                    where resources.resourceid IN (%d)
                                    order by resources.resourceid asc";        
        $number_of_downloads = $wpdb->get_var( $wpdb->prepare( $number_of_downloads_qry, intval($resource['resourceid']) ) );
        
        
        /******** [start] Handle IPs *******/
        $resourceviews_visits_q = "select * from resourceviews 
                                    join visits on visits.visitsid = resourceviews.visitid
                                    where resourceviews.resourceid IN (%d)";
        $resourceviews_visits = $wpdb->get_results( $wpdb->prepare( $resourceviews_visits_q, intval($resource['resourceid']) ) );
        $single_resourceview_visits_in_us = 0;
        $single_resourceview_visits_in_intl = 0;
        foreach ($resourceviews_visits as $rs_vis) {
            foreach ($geo_ip_q_rcds as $geo_ip_record) {
                if($geo_ip_record->ip_from <= $rs_vis->ip_value && $geo_ip_record->ip_to >= $rs_vis->ip_value)
                {
                    $single_resourceview_visits_in_us++;
                }                
            }
        }
        $single_resourceview_visits_in_intl = count($resourceviews_visits) - $single_resourceview_visits_in_us;
        /******** [end] Handle IPs *******/
        
        /*** % of visitors unknown  ***/        
        $percent_visitor_unknown = intval($number_of_resource_views) - count($resourceviews_visits);
                
        /** percentage calculations **/
        $percent_visitor_unknown_pr = round( (intval($percent_visitor_unknown) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";
        $single_resourceview_visits_in_us_pr = round( (intval($single_resourceview_visits_in_us) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";
        $single_resourceview_visits_in_intl_pr = round( (intval($single_resourceview_visits_in_intl) / intval($number_of_resource_views == 0 ? 1 : $number_of_resource_views) ) * 100  , 2) . "%";

        $url_href = site_url()."/oer/{$resource['pageurl']}";
        $result[] = array("title" => $resource['title'] , "url" => $url_href , "type" => $resource["type"] , "unique_users" => $unique_users , "number_of_resource_views" => $number_of_resource_views , "number_of_resource_views_1" => $number_of_resource_views_1 , "number_of_downloads" => $number_of_downloads , "percent_visitor_unknown" => $percent_visitor_unknown_pr , "percent_us" => $single_resourceview_visits_in_us_pr, "percent_international"=>$single_resourceview_visits_in_intl_pr);
    }
            
    return $result;
  }

  public function no_items() {
    _e('No Record Found.', 'sp');
  }

}