<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Group_List_Table extends WP_List_Table {

  public $aStatus = array(
      'T' => '<span style="color:green">Yes</span>',
      'F' => '<span style="color:red">No</span>'
  );

  function __construct() {
    global $status, $page;
    parent::__construct(array(
        'singular' => 'community_page', //singular name of the listed records
        'plural' => 'community_pages', //plural name of the listed records
        'ajax' => false        //does this table support ajax?
    ));
  }

  function extra_tablenav($which) {
    return;
    if ($which == "top") {
      //The code that goes before the table is here
      echo"Hello, I'm before the table";
    }
    if ($which == "bottom") {
      //The code that goes after the table is there
      echo"Hi, I'm after the table";
    }
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
        'name' => 'Name',
        'groupid' => 'Group Id',                
        'url' => 'Url',        
        'displayseqno' => 'Display Order'        
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
        'name' => array('name', false) //true means it's already sorted       
    );
    return $sortable_columns;
  }

  function get_hidden_columns() {
    return array(
        "groupid" => "groupid",
        "communityid" => "communityid"
    );
  }

  function column_default($item, $column_name) {
    switch ($column_name) {      
      case 'name':
        return $item[$column_name];
        break;                          
      case 'url':
        return $item[$column_name];
        break;                          
      case 'displayseqno':
        //return $item[$column_name]." 0000 ";
        $input_field = '<input class="groups-display-order" name="'.$item['communityid'].'-'.$item['groupid'].'" id="'.$item['communityid'].'-'.$item['groupid'].'" value="'.$item[$column_name].'" />';
        return $input_field;
        break;                              
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  function column_name($item) {

    //Build row actions
    $del_url = site_url().$_SERVER["REQUEST_URI"];
    $del_url_arr = parse_url($del_url);
    $query_arr = array();
    parse_str( $del_url_arr["query"] , $query_arr );
    $query_arr["action"] = "deletegroup";
    $query_arr["groupid"] = $item['groupid'];
    $del_url_arr["query"] = http_build_query($query_arr);    
    $del_url = "{$del_url_arr["scheme"]}://{$del_url_arr["host"]}{$del_url_arr["path"]}?{$del_url_arr["query"]}";
    /*echo "<pre>";        
    var_dump(  ($del_url) );
    die;*/
    
    $actions = array(
        'delete' => sprintf('<a href="'.$del_url.'">Delete</a>', site_url(), $item['groupid'])
    );

    //Return the title contents
    return sprintf('%1$s <span style="color:silver"></span>%3$s',
            /* $1%s */ sprintf('<a href="%s/community/%s" target="_blank">%s</a>', site_url(), $item['url'], $item['name']),
            /* $2%s */ $item['name'],
            /* $3%s */ $this->row_actions($actions)
    );
  }

  function column_group_name($item) {
    echo 'test';
    //Return the title contents
    return sprintf('%1$s %2$s ',
            /* $1%s */ $item['groupid'],
            /* $2%s */ $item['communityid']
    );
  }

  function column_cb($item) {
    return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', /* $1%s */ $this->_args['singular'], /* $2%s */ $item['groupid']);
  }

  function prepare_items() {
    global $wpdb; //This is used only if making any database queries

    $columns = $this->get_columns();    
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->process_bulk_action();

    $per_page = 10;
    $total_items = $this->record_count();
    $current_page = $this->get_pagenum();

    $this->set_pagination_args(array(
        'total_items' => $total_items, //WE have to calculate the total number of items
        'per_page' => $per_page //WE have to determine how many items to show on a page
    ));

    $this->items = $this->get_records($per_page, $current_page);    
  }

  public static function record_count() {
    global $wpdb;
    $sql = "SELECT count(cg.communityid) FROM community_groups cg
            join cur_bp_groups g on cg.groupid = g.id where communityid={$_GET["communityid"]}";
    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
        /*
      $sql .= " WHERE u.firstname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.lastname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.membertype LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.sitename LIKE '%{$_REQUEST[s]}%' ";
         * 
         */
    }

    if (isset($_REQUEST['test'])) {
      echo '<br/>' . $sql;
      $t1 = time();
    }
    $result = $wpdb->get_var($sql);
    if (isset($_REQUEST['test'])) {
      $t2 = time();
      echo '<br/><strong>Get_COUNT_QUERY_TIME: ' . intval($t2 - $t1) . ' Sec</strong><br/>';
    }
    return $result;
  }

  public static function get_records($per_page = 5, $page_number = 1) {
    global $wpdb;
    $result = array();

    $sql = "
            SELECT communityid,groupid,g.name as name,displayseqno,slug as url FROM community_groups cg
            join cur_bp_groups g on cg.groupid = g.id where communityid={$_GET['communityid']}
           ";                        

    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      /*  
      $sql .= " WHERE u.firstname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.lastname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.group_email LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.group_login LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.membertype LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.sitename LIKE '%{$_REQUEST[s]}%' ";
       * 
       */
    }

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }  else {
        $sql .= ' ORDER BY displayseqno ASC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

    if (isset($_REQUEST['test'])) {
      echo '<br/>' . $sql;
      $t1 = time();
    }
   
    $result = $wpdb->get_results($sql, 'ARRAY_A');
    if (isset($_REQUEST['test'])) {
      $t2 = time();
      echo '<br/><strong>Get_RECORDS_QUERY_TIME: ' . intval($t2 - $t1) . ' Sec</strong><br/>';
    }
    return $result;
  }

  public function no_items() {
    _e('No Groups avaliable.', 'sp');
  }

}

if (is_admin()) {
  if (!empty($_REQUEST['groupid'])) {
    echo '<br/><div id="message" class="updated"><p><strong>Group Data Saved Successfuly </strong>.</p></div><br/><br/>';
  }
//** * ************************** RENDER TEST PAGE ********************************/
  $groupListTable = new Group_List_Table(); //Create an instance of our package class...
  $groupListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
  
  global $tab;
  ?>
  <div class="wrap">
    <div id="icon-groups" class="icon32"><br/></div>    
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
      <?php $groupListTable->display() ?>    
  </div>

<style type="text/css">
    .paging-input input
    {
        width: auto !important;
    }
</style>

  <?php
}