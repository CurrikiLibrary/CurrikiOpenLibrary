<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Link_List_Table extends WP_List_Table
{

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
            'title' => 'Resource Title',
            'url' => 'URL',
            'user_login' => 'User Login',
            'ipv4' => 'IP Address',
            'created_at' => 'Created At',
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array('title', false), //true means it's already sorted
            'url' => array('url', false),
            'user_login' => array('user_login', false),
            'ipv4' => array('ipv4', false),
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
            case 'ipv4':
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
        //Return the title contents
        return sprintf(
            '%1$s <span style="color:silver">(id:%2$s)</span>',
            /* $1%s */
            sprintf('<a href="%s/oer/%s" target="_blank">%s</a>', site_url(), $item['pageurl'], $item['title']),
            /* $2%s */
            $item['resourceid']
        );
    }

    function column_user_login($item)
    {
        if($item['user_login']) {
            //Return the title contents
            return sprintf(
                '%1$s <span style="color:silver">(id:%2$s)</span>',
                /* $1%s */
                sprintf('<a href="%s/members/%s" target="_blank">%s</a>', site_url(), $item['user_nicename'], $item['user_login']),
                /* $2%s */
                $item['userid']
            );
        } else {
            return 'N/A';
        }
    }

    function column_url($item)
    {
        //Return the url contents
        return sprintf('<a href="%1$s" target="_blank">%1$s</a>', $item['url']);
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

        $sql = "SELECT count(rcll.id)
                FROM resource_content_link_logs rcll";

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " LEFT JOIN resources r ON rcll.resourceid = r.resourceid ";
            $sql .= " WHERE (r.title LIKE '%{$_REQUEST[s]}%' )";
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

        $sql = "SELECT rcll.*, INET_NTOA(rcll.ipv4) AS ipv4, r.title, r.pageurl, u.user_login, u.user_nicename
                FROM resource_content_link_logs rcll
                LEFT JOIN resources r ON rcll.resourceid = r.resourceid
                LEFT JOIN cur_users u ON rcll.userid = u.ID";

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " WHERE (r.title LIKE '%{$_REQUEST[s]}%' )";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY created_at desc';
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
        _e('No link logs avaliable.', 'sp');
    }
}

if (is_admin()) {

    if (isset($_REQUEST['test']))
        $t1 = time();
    //** * ************************** RENDER TEST PAGE ********************************/
    $linkListTable = new Link_List_Table(); //Create an instance of our package class...
    $linkListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
    ?>
    <div class="wrap">
        <div id="icon-resources" class="icon32"><br /></div>
        <h2>Resource Content Link Logs</h2>

        <form id="resource-filter" method="get">
            <?php $linkListTable->search_box('search', 'search_id'); ?>
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
