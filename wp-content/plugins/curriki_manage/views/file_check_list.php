<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Resource_List_Table extends WP_List_Table {

    public $resourCecheckTypes = array(
        'T' => 'Checked',
        'F' => 'Not Checked',
        'Q' => 'Check requested',
        'I' => 'Improvement',
        'R' => 'Rejected',
    );

    function __construct() {
        global $status, $page;
        parent::__construct(array(
            'singular' => 'resource', //singular name of the listed records
            'plural' => 'resources', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function extra_tablenav($which) {
        if ($which == "top") {
            foreach ($this->resourCecheckTypes as $i => $t)
                printf('<label style="margin-right: 20px;font-size:14px;"><input type="checkbox" name="type[%s]" value="%s" %s />%s</label>', $i, $i, (isset($_REQUEST['type'][$i]) ? 'checked="checked"' : (($i == 'F' AND ! isset($_REQUEST['type'])) ? 'checked="checked"' : '')), $t);
            echo '<input class="button button-primary" type="submit" value="Filter"/>';
        }
        return;
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
            'title' => 'Resource Title',
            'contributor' => 'Contributor Name',
            'resourcechecked' => 'Type',
            'createdate' => 'Date Created',
            'resourcechecknote' => 'Check Notes',
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title' => array('title', false), //true means it's already sorted
            'contributor' => array('contributor', false),
            'resourcechecked' => array('resourcechecked', false),
            'createdate' => array('createdate', false),
            'resourcechecknote' => array('resourcechecknote', false),
        );
        return $sortable_columns;
    }

    function get_hidden_columns() {
        return array();
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
            case 'contributor':
            case 'resourcechecknote':
                return $item[$column_name];
                break;
            case 'createdate':
                if ($item[$column_name])
                    return date(" Y-m-d H:i", strtotime($item[$column_name]));
                break;
            case 'resourcechecked':
                return $this->resourCecheckTypes[$item[$column_name]];
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_title($item) {
        //Build row actions
        $actions = array(
            'edit' => sprintf('<a href="%s/resource-review/?action=file_check&rid=%s">Edit</a>', site_url(), $item['resourceid']),
            'view' => sprintf('<a href="%s/oer/%s" target="_blank">View</a>', site_url(), $item['pageurl'])
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
                /* $1%s */ sprintf('<a href="%s/oer/%s" target="_blank">%s</a>', site_url(), $item['pageurl'], $item['title']),
                /* $2%s */ $item['resourceid'],
                /* $3%s */ $this->row_actions($actions)
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

        $where = '';
        if ($_REQUEST['type'])
            $where = " resourcechecked IN ( '" . implode("','", $_REQUEST['type']) . "' ) AND contributorid not in (86158,123653) ";
        else
            $where = " resourcechecked IN ( 'F' ) ";

        $sql = "SELECT count(resourceid) FROM resources WHERE {$where} ";

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

        $where = '';
        if ($_REQUEST['type'])
            $where = " resourcechecked IN ( '" . implode("','", $_REQUEST['type']) . "' ) ";
        else
            $where = " resourcechecked IN ( 'F' ) ";

        $sql = "SELECT * from file_check_view  WHERE {$where} AND contributorid not in (86158) and not (title = '' and contributorid = 10000) and public = 'T' ";


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

        return $result;
    }

    public function no_items() {
        _e('No Resources avaliable to be File Checked.', 'sp');
    }

}

if (is_admin()) {

    if (!empty($_REQUEST['resourceid'])) {
        if ($_REQUEST['status'] == 'I')
            echo '<br/><div id="message" class="updated"><p><strong>Resource is requested for improvement with your comments to contributor</strong>.</p></div><br/><br/>';
        else
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
        <h2>Resources</h2>

        <form id="resource-filter" method="get">
            <?php $resourceListTable->search_box('search', 'search_id'); ?>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $resourceListTable->display() ?>
        </form>

    </div>
    <?php
    if (isset($_REQUEST['test'])) {
        $t2 = time();
        echo '<br/><strong>PAGE_TIME: ' . intval($t2 - $t1) . ' Sec</strong><br/>';
    }
}