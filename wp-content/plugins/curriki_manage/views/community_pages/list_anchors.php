<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Anchor_List_Table extends WP_List_Table {

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
        'title' => 'Name',
        'tagline' => 'Tagline',
        'anchorid' => 'Anchor Id',                
        'content' => 'Content',        
        'displayseqno' => 'Display Order',        
        'type' => 'Type'        
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
        'title' => array('title', false),
        'type' => array('type', false), 
        'displayseqno' => array('displayseqno', false) 
    );
    return $sortable_columns;
  }

  function get_hidden_columns() {
    return array(
        "anchorid" => "anchorid",
        "communityid" => "communityid"
    );
  }

  function column_default($item, $column_name) {
    switch ($column_name) {      
      case 'title':
        return $item[$column_name];
        break;                          
      case 'tagline':
        return $item[$column_name];
        break;                          
      case 'content':
        return strlen($item[$column_name]) > 50 ? substr($item[$column_name], 0 , 50)." ..." : $item[$column_name];
        break;                          
      case 'displayseqno':        
        return $item[$column_name];
        break;                              
      case 'type':        
        return ucwords($item[$column_name]);
        break;                              
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  function column_title($item) {

    //Build row actions
    $del_url = site_url().$_SERVER["REQUEST_URI"];
    $del_url_arr = parse_url($del_url);
    $query_arr = array();
    parse_str( $del_url_arr["query"] , $query_arr );
    $query_arr["action"] = "deleteanchor";
    $query_arr["anchorid"] = $item['anchorid'];
    $del_url_arr["query"] = http_build_query($query_arr);    
    $del_url = "{$del_url_arr["scheme"]}://{$del_url_arr["host"]}{$del_url_arr["path"]}?{$del_url_arr["query"]}";
    
    $edit_url = site_url().$_SERVER["REQUEST_URI"];
    $edit_url_arr = parse_url($edit_url);
    $query_edit_arr = array();
    parse_str( $edit_url_arr["query"] , $query_edit_arr );
    $query_edit_arr["action"] = "edit";
    $query_edit_arr["anchorid"] = $item['anchorid'];
    $edit_url_arr["query"] = http_build_query($query_edit_arr);    
    $edit_url = "{$edit_url_arr["scheme"]}://{$edit_url_arr["host"]}{$edit_url_arr["path"]}?{$edit_url_arr["query"]}";
    
    
    /*echo "<pre>";        
    var_dump(  ($del_url) );
    die;*/
    
    $actions = array(
        'edit' => sprintf('<a href="'.$edit_url.'">Edit</a>', site_url(), $item['anchorid']),
        'delete' => sprintf('<a href="'.$del_url.'">Delete</a>', site_url(), $item['anchorid'])
    );

    //Return the title contents
    return sprintf('%1$s <span style="color:silver"></span>%3$s',
            /* $1%s */ sprintf('<a href="%s/community/%s" target="_blank">%s</a>', site_url(), $item['content'], $item['title']),
            /* $2%s */ $item['title'],
            /* $3%s */ $this->row_actions($actions)
    );
  }

  function column_anchor_title($item) {
    echo 'test';
    //Return the title contents
    return sprintf('%1$s %2$s ',
            /* $1%s */ $item['anchorid'],
            /* $2%s */ $item['communityid']
    );
  }

  function column_cb($item) {
    return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', /* $1%s */ $this->_args['singular'], /* $2%s */ $item['anchorid']);
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
    $sql = "SELECT count(communityid) FROM community_anchors
            where communityid={$_GET["communityid"]}";
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
            SELECT * FROM community_anchors where
            communityid={$_GET['communityid']}
           ";

    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      /*  
      $sql .= " WHERE u.firstname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.lastname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.anchor_email LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.anchor_login LIKE '%{$_REQUEST[s]}%' ";
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
    _e('No Anchors avaliable.', 'sp');
  }

}

if (is_admin()) {
  if (!empty($_REQUEST['anchorid'])) {
    //echo '<br/><div id="message" class="updated"><p><strong>Anchor Data Saved Successfuly </strong>.</p></div><br/><br/>';
  }
//** * ************************** RENDER TEST PAGE ********************************/
  $anchorListTable = new Anchor_List_Table(); //Create an instance of our package class...
  $anchorListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
  
  global $tab;
  ?>
  <div class="wrap">
    <div id="icon-anchors" class="icon32"><br/></div>    
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
      <?php $anchorListTable->display() ?>    
  </div>

<style type="text/css">
    .paging-input input
    {
        width: auto !important;
    }
</style>

  <?php
}