<?php
set_time_limit(0);

if (is_admin()) {
    if (!empty($_REQUEST['userid'])) {
        echo '<br/><div id="message" class="updated"><p><strong>User Data Saved Successfuly </strong>.</p></div><br/><br/>';
    }
    ?>
    <div class="wrap">
        <div id="icon-users" class="icon32"><br/></div>
        <h1><strong>Reporting</strong></h1>

        <?php
        if (isset($_GET["rpt"]) && $_GET["rpt"] === "ga") {
            require_once __DIR__ . '/list_google_analytics_report.php';
            wp_enqueue_script('ga-script', plugins_url() . "/curriki_manage/views/reporting/js/ga-report.js", array('jquery'), false, true);
        } elseif (isset($_GET["rpt"]) && $_GET["rpt"] === "summary") {
            require_once __DIR__ . '/list_summary_report.php';
        } elseif (isset($_GET["rpt"]) && $_GET["rpt"] === "geo") {
            require_once __DIR__ . '/list_geography_report.php';
            wp_enqueue_script('reporting-script', plugins_url() . "/curriki_manage/views/reporting/js/geography-report.js", array('jquery'), false, true);
        } elseif (isset($_GET["rpt"]) && $_GET["rpt"] === "membertype") {
            require_once __DIR__ . '/list_member_type_report.php';
            wp_enqueue_script('membertype-reporting-script', plugins_url() . "/curriki_manage/views/reporting/js/membertype-report.js", array('jquery'), false, true);
        } else {
            require_once __DIR__ . '/list_detailed_report.php';
        }
        ?>
    </div>
<?php
}
?>

<?php
wp_enqueue_style("jquery-ui", "//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css");
//wp_enqueue_style("datepicker", plugins_url('curriki_manage/assets/datepicker.css'));
wp_enqueue_style('reporting-datatables-css', 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css', null, false, 'all');
wp_enqueue_style('reporting-style', plugins_url() . "/curriki_manage/views/reporting/css/style.css", null, false, 'all');
wp_enqueue_script('jquery-ui-reporting-datatables-js', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), false, true);
wp_enqueue_script('reporting-datatables-js', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array('jquery'), false, true);
wp_enqueue_script('reporting-script', plugins_url() . "/curriki_manage/views/reporting/js/script.js", array('jquery'), false, true);
$csv_download_link = home_url() . "/wp-admin/images/report_detailed.csv";
?>

<input type="hidden" name="csv_download_link" id="csv_download_link" value="<?php echo $csv_download_link; ?>" />
<input type="hidden" name="admin_url" id="admin_url" value="<?php echo admin_url(); ?>" />