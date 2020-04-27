<?php
//* * ************************* LOAD THE BASE CLASS ****************************** */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Resource_List_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
        parent::__construct(array(
            'singular' => 'resource', //singular name of the listed records
            'plural' => 'resources', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function extra_tablenav($which) {
        global $wpdb;
        if ($which == "top") {
                if(isset($_SESSION['message'])){
                    ?>
            <div id="message" class="updated notice notice-success is-dismissible below-h2">
                <p>
                    <?php echo $_SESSION['message'] ?>
                </p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
<?php
                    unset($_SESSION['message']);
                }
            ?>

            
            <div class="alignleft actions">
                <label class="screen-reader-text" for="subject">Subject</label>
                <select name="subject" id="subject" class="postform">
                    <option value="">Any Subject</option>
                    <?php
                    $result = $wpdb->get_results("SELECT * from subjects", OBJECT);
                    foreach ($result as $sub) {
                        echo '<option value="' . $sub->subjectid . '"' . (($sub->subjectid == $_REQUEST['subject']) ? ' selected ' : '') . '>' . $sub->displayname . '</option>';
                    }
                    ?>
                </select>

                <label for="status" class="screen-reader-text">Status</label>
                <select name="status" id="status" onchange="show_extra_filters(jQuery(this).val())">
                    <option value="submitted" <?php echo ($_REQUEST['status'] == 'submitted') ? 'selected' : ''; ?>>Nominated Only</option>
                    <option value="all" <?php echo ($_REQUEST['status'] == 'all') ? 'selected' : ''; ?>>Any Status</option>
                    <option value="reviewed" <?php echo ($_REQUEST['status'] == 'reviewed') ? 'selected' : ''; ?>>Reviewed Already</option>
                </select>

                <span id="lastreviewdate">
                    <label> From: </label><input type="text" class="datepicker" name="startdate" value="<?php echo $_REQUEST['startdate'] ? $_REQUEST['startdate'] : date('Y-m-d', strtotime('-1 month')); ?>" style="width: 100px;" />
                    <label> To: </label><input type="text" class="datepicker" name="enddate" value="<?php echo $_REQUEST['enddate'] ? $_REQUEST['enddate'] : date('Y-m-d'); ?>" style="width: 100px;" />
                </span>
                <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
                <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
                <script>
                    jQuery('#lastreviewdate').hide();
                    jQuery('.datepicker').datepicker({dateFormat: "yy-mm-dd"});
                    function show_extra_filters($val) {
                        jQuery('#lastreviewdate').hide();
                        if ($val == 'reviewed') {
                            jQuery('#lastreviewdate').show();
                        }
                    }
                    show_extra_filters('<?php echo $_REQUEST['status']; ?>');
                </script>
                <input name="filter_action" id="post-query-submit" class="button" value="Filter" type="submit">		
            </div>
            <?php
        }
        //    if ($which == "bottom") {
        //      //The code that goes after the table is there
        //      echo"Hi, I'm after the table";
        //    }
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
            'description' => 'Description',
            'instructiontype' => 'Instruction Types',
            'educationlevel' => 'Education Level',
            'subject' => 'Subjects',
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'title' => array('r.title', false), //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_hidden_columns() {
        return array();
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
            case 'instructiontype':
            case 'subject':
                return $item[$column_name];
                break;
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_title($item) {
        //Build row actions
        $actions = array(
            'edit' => sprintf('<a href="%s/oer/%s?action=review_resource" target="_blank">Review</a>', get_bloginfo('url'), $item['pageurl'], $item['resourceid']),
            'view' => sprintf('<a href="%s/oer/%s" target="_blank">View</a>', site_url(), $item['pageurl']),
            'remove' => sprintf('<a href="%s/wp-admin/admin.php?page=curriki_res_review%s">Remove</a>', site_url(), '&remove='.$item['pageurl'])
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span> %3$s',
                /* $1%s */ sprintf('<a href="%s/oer/%s" target="_blank">%s</a>', site_url(), $item['pageurl'], $item['title']),
                /* $2%s */ $item['resourceid'],
                /* $3%s */ $this->row_actions($actions)
        );
    }

    function column_description($item) {
        $item['description'] = strip_tags($item['description']);
        if (strlen($item['description']) > 200)
            return substr($item['description'], 0, 200) . '...';
        else
            return $item['description'];
    }

    function column_educationlevel($item) {
        global $wpdb;
        return $wpdb->get_var("SELECT GROUP_CONCAT( el.displayname SEPARATOR  ',' ) AS educationlevel "
                        . " FROM resource_educationlevels AS re "
                        . " LEFT JOIN educationlevels AS el ON el.levelid = re.educationlevelid"
                        . " WHERE re.resourceid = " . $item['resourceid']);
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
            'per_page' => $per_page, //WE have to determine how many items to show on a page
        ));

        $this->items = $this->get_records($per_page, $current_page);
    }

    public static function record_count() {
        global $wpdb;

        $sql = "select COUNT(DISTINCT r.resourceid)
            from resources r
            left outer join resource_instructiontypes rit on r.resourceid = rit.resourceid
            left outer join instructiontypes it on it.instructiontypeid = rit.instructiontypeid
            left outer JOIN resource_subjectareas as rs on rs.resourceid = r.resourceid 
            left outer JOIN subjectareas as sub on sub.subjectareaid = rs.subjectareaid 
            left outer JOIN subjects as s on sub.subjectid = s.subjectid 
            where r.reviewstatus = 'submitted' and r.active = 'T' ";

        if (!isset($_REQUEST['status']) || (isset($_REQUEST['status']) && $_REQUEST['status'] != 'all')) {
            $sql .= " AND r.reviewstatus = '" . ($_REQUEST['status'] ? $_REQUEST['status'] : 'submitted') . "' ";
            if ($_REQUEST['status'] == 'reviewed') {
                $sql .= " AND date(r.lastreviewdate) between '" . $_REQUEST['startdate'] . "' AND '" . $_REQUEST['enddate'] . "' ";
            }
        }

        if (isset($_REQUEST['subject']) && !empty($_REQUEST['subject']) && intval($_REQUEST['subject'])) {
            $sql .= " and sub.subjectid = '" . intval($_REQUEST['subject']) . "'";
        }

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " AND ( r.title LIKE '%{$_REQUEST[s]}%' ";
            $sql .= " OR r.description LIKE '%{$_REQUEST[s]}%' ";
            $sql .= " OR s.displayname LIKE '%{$_REQUEST[s]}%' )";
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

        $sql = "select r.resourceid,r.pageurl, r.title ,r.description,group_concat(s.displayname) as subject, group_concat(it.displayname) as instructiontype
            from resources r
            left outer join resource_instructiontypes rit on r.resourceid = rit.resourceid
            left outer join instructiontypes it on it.instructiontypeid = rit.instructiontypeid
            left outer JOIN resource_subjectareas as rs on rs.resourceid = r.resourceid 
            left outer JOIN subjectareas as sub on sub.subjectareaid = rs.subjectareaid 
            left outer JOIN subjects as s on sub.subjectid = s.subjectid 
            where r.reviewstatus = 'submitted' and r.active = 'T' ";

        if (!isset($_REQUEST['status']) || (isset($_REQUEST['status']) && $_REQUEST['status'] != 'all')) {
            $sql .= " AND r.reviewstatus = '" . ($_REQUEST['status'] ? $_REQUEST['status'] : 'submitted') . "' ";
            if ($_REQUEST['status'] == 'reviewed') {
                $sql .= " AND date(r.lastreviewdate) between '" . $_REQUEST['startdate'] . "' AND '" . $_REQUEST['enddate'] . "' ";
            }
        }

        if (isset($_REQUEST['subject']) && !empty($_REQUEST['subject']) && intval($_REQUEST['subject'])) {
            $sql .= " and sub.subjectid = '" . intval($_REQUEST['subject']) . "'";
        }

        if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
            $sql .= " AND ( r.title LIKE '%{$_REQUEST[s]}%' ";
            $sql .= " OR r.description LIKE '%{$_REQUEST[s]}%' ";
            $sql .= " OR s.displayname LIKE '%{$_REQUEST[s]}%' )";
        }

        $sql .= " GROUP BY 1,2,3 ";
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
        _e('No Resources avaliable to be Reviewed.', 'sp');
    }

}

if (is_admin()) {

    if (!empty($_REQUEST['resourceid'])) {
        echo '<br/><div id="message" class="updated"><p><strong>Resource Rating Saved Successfully </strong>.</p></div><br/><br/>';
    }

    if (isset($_REQUEST['test']))
        $t1 = time();
    //** * ************************** RENDER TEST PAGE ********************************/
    $resourceListTable = new Resource_List_Table(); //Create an instance of our package class...
    $resourceListTable->prepare_items(); //Fetch, prepare, sort, and filter our data...
    ?>
    <div class="wrap">
        <div id="icon-resources" class="icon32"><br/></div>
        <h2>Review Queue : Nominated Resources</h2>

        <form id="resource-filter" method="get" >
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
?>