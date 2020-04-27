<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class User_List_Table extends WP_List_Table {

  public $aStatus = array(
      'T' => '<span style="color:green">Yes</span>',
      'F' => '<span style="color:red">No</span>'
  );

  function __construct() {
    global $status, $page;
    parent::__construct(array(
        'singular' => 'user', //singular name of the listed records
        'plural' => 'users', //plural name of the listed records
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
        /* 'cb' => '<input type="checkbox" />', //Render a checkbox instead of text */
        'user_login' => 'User Login',
        'user_email' => 'Email',
        'user_name' => 'Name',
        'active' => 'Active',
        'registerdate' => 'Registered at',
        'inactivedate' => 'Inactive Date',
        'membertype' => 'Member type',
        'sitename' => 'Site Name'
    );
    return $columns;
  }

  function get_sortable_columns() {
    $sortable_columns = array(
        'user_login' => array('wpu.user_login', false), //true means it's already sorted
        'user_email' => array('wpu.user_email', false),
        'user_name' => array('u.firstname', false),
        'active' => array('u.active', false),
        'registerdate' => array('u.registerdate', false),
        'inactivedate' => array('u.inactivedate', false),
        'membertype' => array('u.membertype', false),
        'sitename' => array('u.sitename', false)
    );
    return $sortable_columns;
  }

  function get_hidden_columns() {
    return array();
  }

  function column_default($item, $column_name) {
    switch ($column_name) {
      case 'user_email':
      case 'membertype':
      case 'sitename':
        return $item[$column_name];
        break;
      case 'registerdate':
      case 'inactivedate':
        if ($item[$column_name])
          return date(" Y-m-d H:i", strtotime($item[$column_name]));
        else
          return 'Never';
        break;
      case 'active':
        return $this->aStatus[$item[$column_name]];
        break;
      default:
        return print_r($item, true); //Show the whole array for troubleshooting purposes
    }
  }

  function column_user_login($item) {

    //Build row actions
    $actions = array(
        'edit' => sprintf('<a href="?page=%s&action=%s&userid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['userid'])
    );

    //Return the title contents
    return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /* $1%s */ $item['user_login'],
            /* $2%s */ $item['userid'],
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

  function prepare_items() {
    global $wpdb; //This is used only if making any database queries

    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->process_bulk_action();

    $per_page = 25;
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

    $sql = "select count(u.userid) from users as u";
    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      $sql .= " WHERE u.firstname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.lastname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.membertype LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.sitename LIKE '%{$_REQUEST[s]}%' ";
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

    $sql = "select u.userid, u.firstname, u.lastname, u.registerdate, u.inactivedate, u.sitename, u.membertype, u.active, "
            . "wpu.user_email, wpu.user_login "
            . "from users u join cur_users as wpu on wpu.ID = u.userid ";

    if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
      $sql .= " WHERE u.firstname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.lastname LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.user_email LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR wpu.user_login LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.membertype LIKE '%{$_REQUEST[s]}%' ";
      $sql .= " OR u.sitename LIKE '%{$_REQUEST[s]}%' ";
    }

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .=!empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    //echo $sql;
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
    _e('No Users avaliable.', 'sp');
  }

}

if (is_admin()) {
  if (!empty($_REQUEST['userid'])) {
    echo '<br/><div id="message" class="updated"><p><strong>User Data Saved Successfuly </strong>.</p></div><br/><br/>';
  }
//** * ************************** RENDER TEST PAGE ********************************/
  $userListTable = new User_List_Table(); //Create an instance of our package class...
  $userListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
  ?>
  <div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Users</h2>

    <form id="user-filter" method="get">
      <?php $userListTable->search_box('search', 'search_id'); ?>
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
      <?php $userListTable->display() ?>
    </form>

  </div>
  <?php
}


/*
http://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
http://stackoverflow.com/questions/9278772/extending-wp-list-table-handling-checkbox-options-in-plugin-administration
http://www.smashingmagazine.com/2011/11/native-admin-tables-wordpress/
http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
http://codex.wordpress.org/Class_Reference/WP_List_Table
http://cg.curriki.org/curriki/wp-admin
 * 
 */