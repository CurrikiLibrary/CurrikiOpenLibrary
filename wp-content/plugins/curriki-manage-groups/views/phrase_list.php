<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Resource_List_Table extends WP_List_Table {

 
  function __construct() {
    global $status, $page;
    parent::__construct(array(
        'singular' => 'censorphrases', //singular name of the listed records
        'plural' => 'censorphrases', //plural name of the listed records
        'ajax' => false        //does this table support ajax?
    ));
  }
  /*
  function extra_tablenav($which) {
    if ($which == "top") {
      foreach ($this->resourCecheckTypes as $i => $t)
        printf('<label style="margin-right: 20px;font-size:14px;"><input type="checkbox" name="type[%s]" value="%s" %s />%s</label>', $i, $i, (isset($_POST['type'][$i]) ? 'checked="checked"' : (($i == 'F' AND ! isset($_POST['type'])) ? 'checked="checked"' : '')), $t);
      echo '<input class="button button-primary" type="submit" value="Filter"/>';
    }
    return;
    if ($which == "bottom") {
      //The code that goes after the table is there
      echo"Hi, I'm after the table";
    }
  }
  */
  
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
        /* 'cb' => '<input type="checkbox" />', //Render a checkbox instead of text */
        'phraseid' => 'Phrase Id',
        'phrase' => 'Phrase',        
        'addeddate' => 'Added Date'        
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
        'phraseid' => array('phraseid', false), //true means it's already sorted
        'phrase' => array('phrase', false),
        'addeddate' => array('addeddate', false)        
    );
    return $sortable_columns;
  }

  function get_hidden_columns() {
    return array();
  }

  function column_default($item, $column_name){
        switch($column_name){
            case 'phraseid':
                return $item[$column_name];
            case 'phrase':
                return $item[$column_name];
            case 'addeddate':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

  function column_phrase($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&phraseid=%s">Edit</a>',$_REQUEST['page'],'edit',$item['phraseid']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&phraseid=%s">Delete</a>',$_REQUEST['page'],'delete',$item['phraseid']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%3$s',
            /*$1%s*/ $item['phrase'],
            /*$2%s*/ $item['phraseid'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

  function column_cb($item) {
    return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', /* $1%s */ $this->_args['singular'], /* $2%s */ $item['phraseid']);
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

    
    $data = $this->get_records($per_page, $current_page);
            
     $total_items = count($data);
     
    $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
    $this->items = $data;
    
    
    $this->set_pagination_args(array(
        'total_items' => $total_items, //WE have to calculate the total number of items
        'per_page' => $per_page, //WE have to determine how many items to show on a page
        'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
    ));

    
  }

  public static function record_count() {
    global $wpdb;

    $where = '';
    if ($_POST['type'])
      $where = " resourcechecked IN ( '" . implode("','", $_POST['type']) . "' ) AND contributorid not in (86158,123653) ";
    else
      $where = " resourcechecked IN ( 'F' ) ";

    //$sql = "SELECT count(resourceid) FROM censorphrases WHERE {$where} ";
    $sql = "SELECT count(resourceid) FROM censorphrases ";

    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      $sql .= " AND (title LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR resourcechecknote LIKE '%{$_REQUEST[s]}%' ) ";
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
    /*
    $where = '';
    if ($_POST['type'])
      $where = " resourcechecked IN ( '" . implode("','", $_POST['type']) . "' ) ";
    else
      $where = " resourcechecked IN ( 'F' ) ";

    //$sql = "SELECT * from file_check_view  WHERE {$where} AND contributorid not in (86158,123653) ";
    $sql = "SELECT * from censorphrases  WHERE {$where} ";


    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      $sql .= " AND (title LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR resourcechecknote LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR contributor LIKE '%{$_REQUEST[s]}%' ) ";
    }

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
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
    */
    $sql = "SELECT * from censorphrases";    
    
    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      $sql .= " WHERE phrase LIKE '%{$_REQUEST['s']}%' ";      
    }
    
    //$sql .= " LIMIT $per_page";
    //$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
    
     $result = $wpdb->get_results($sql, 'ARRAY_A');
    return $result;
  }

  public function no_items() {
    _e('No Resources avaliable to be File Checked.', 'sp');
  }

}

if (is_admin()) {

  if (!empty($_REQUEST['resourceid'])) {
    echo '<br/><div id="message" class="updated"><p><strong>Fiel Check Completed Successfuly </strong>.</p></div><br/><br/>';
  }

  if (isset($_REQUEST['test']))
    $t1 = time();
  //** * ************************** RENDER TEST PAGE ********************************/
  $resourceListTable = new Resource_List_Table(); //Create an instance of our package class...
  $resourceListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
  ?>
  <div class="wrap">
    <div id="icon-resources" class="icon32"><br/></div>
    <h2>Censor Phrases</h2>

    <form id="resource-filter" method="post" action="">
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
      <?php $resourceListTable->search_box('search', 'search_id'); ?>
      <?php $resourceListTable->display() ?>
    </form>

  </div>
  <?php
  if (isset($_REQUEST['test'])) {
    $t2 = time();
    echo '<br/><strong>PAGE_TIME: ' . intval($t2 - $t1) . ' Sec</strong><br/>';
  }
}