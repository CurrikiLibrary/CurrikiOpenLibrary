<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Link_List_Table extends WP_List_Table
{

    public $resourCecheckTypes = array(
        '300 AND 399' => '300s',
        '400 AND 499' => '400s',
        '500 AND 599' => '500s',
    );

    function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => 'resource', //singular name of the listed records
            'plural' => 'resources', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function extra_tablenav($which)
    {
        if ($which == "top") {
            foreach ($this->resourCecheckTypes as $i => $t)
                printf('<label style="margin-right: 20px;font-size:14px;"><input type="checkbox" name="type[%s]" value="%s" %s />%s</label>', $t, $i, (isset($_REQUEST['type'][$t]) ? 'checked="checked"' : (($t == '400s' and !isset($_REQUEST['type'])) ? 'checked="checked"' : '')), $t);
            echo '<input class="button button-primary" type="submit" value="Filter"/>';
        }
        return;
        if ($which == "bottom") {
            //The code that goes after the table is there
            echo "Hi, I'm after the table";
        }
    }

    function get_bulk_actions()
    {
        return array();
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        return array();
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
    }

    function get_columns()
    {
        $columns = array(
            /* 'cb' => '<input type="checkbox" />', //Render a checkbox instead of text */
            'title' => 'Resource Title',
            'url' => 'URL',
            'status' => 'Status',
            'created_at' => 'Created At',
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array('title', false), //true means it's already sorted
            'url' => array('url', false),
            'status' => array('status', false),
            'created_at' => array('created_at', false),
        );
        return $sortable_columns;
    }

    function get_hidden_columns()
    {
        return array();
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'title':
            case 'url':
            case 'status':
                return $item[$column_name];
                break;
            case 'created_at':
                if ($item[$column_name])
                    return date(" Y-m-d H:i", strtotime($item[$column_name]));
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_title($item)
    {
        //Build row actions
        // $actions = array(
        //     'edit' => sprintf('<a href="%s/resource-review/?action=file_check&rid=%s">Edit</a>', site_url(), $item['resourceid'])
        // );

        //Return the title contents
        return sprintf(
            '%1$s <span style="color:silver">(id:%2$s)</span>',
            /* $1%s */
            sprintf('<a href="%s/oer/%s" target="_blank">%s</a>', site_url(), $item['pageurl'], $item['title']),
            /* $2%s */
            $item['resourceid']
        );
    }

    function column_url($item)
    {
        //Return the url contents
        return sprintf('<a href="%1$s" target="_blank">%1$s</a>', $item['url']);
    }

    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', /* $1%s */ $this->_args['singular'], /* $2%s */ $item['resourceid']);
    }

    function prepare_items()
    {
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

    public static function record_count()
    {
        global $wpdb;

        $where = '';
        if (isset($_REQUEST['type']))
            $where = " ( bl.status BETWEEN " . implode(" ) OR ( bl.status BETWEEN ", $_REQUEST['type']) . " )";
        else
            $where = " ( bl.status BETWEEN 400 and 499 ) ";

        $sql = "SELECT count(bl.id)
                FROM broken_links bl";

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " LEFT JOIN resources r ON bl.resourceid = r.resourceid ";
            $sql .= " WHERE {$where} ";
            $sql .= " AND (r.title LIKE '%{$_REQUEST[s]}%' )";
        } else {
            $sql .= " WHERE {$where} ";
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

    public static function get_records($per_page = 5, $page_number = 1)
    {
        global $wpdb;
        $result = array();

        $where = '';
        if (isset($_REQUEST['type']))
            $where = " ( bl.status BETWEEN " . implode(" ) OR ( bl.status BETWEEN ", $_REQUEST['type']) . " )";
        else
            $where = " ( bl.status BETWEEN 400 and 499 ) ";

        $sql = "SELECT bl.*, r.title, r.pageurl
                FROM broken_links bl
                LEFT JOIN resources r ON bl.resourceid = r.resourceid
                WHERE {$where}
                ";

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " AND (r.title LIKE '%{$_REQUEST[s]}%' )";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

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

    public function no_items()
    {
        _e('No broken links avaliable.', 'sp');
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
    $linkListTable = new Link_List_Table(); //Create an instance of our package class...
    $linkListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
    ?>
    <div class="wrap">
        <div id="icon-resources" class="icon32"><br /></div>
        <h2>Broken Links</h2>

        <form id="resource-filter" method="get">
            <?php $linkListTable->search_box('search', 'search_id'); ?>
            <a href="<?php echo admin_url() ?>admin.php?page=broken_links&action=deleted" style="font-size: 18px;margin-top: 12px;text-decoration: none;float: left;" > <strong>View Delete Analytics</strong> </a>
            <br/><br/>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $linkListTable->display() ?>
        </form>

    </div>
    <?php
    if (isset($_REQUEST['test'])) {
        $t2 = time();
        echo '<br/><strong>PAGE_TIME: ' . intval($t2 - $t1) . ' Sec</strong><br/>';
    }
}
