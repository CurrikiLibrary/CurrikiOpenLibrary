<?php
$start_date = "";
$end_date = "";
$csv_download_link = "";

$result_summary = array();
if (isset($_POST['get_summary_by_contributor']) && $_POST['get_summary_by_contributor'] === "GO") {
    require_once 'classes/SummaryReport.php';
    if (class_exists("SummaryReport")) {
        $user = get_user_by("login", urldecode($_POST['contributor_slug']));        
        $start_date = isset($_REQUEST['startdate']) && strlen($_REQUEST['startdate']) > 0 ? $_REQUEST['startdate'] : '';
        $end_date = isset($_REQUEST['startdate']) && strlen($_REQUEST['enddate']) > 0 ? $_REQUEST['enddate'] : '';

        if (strlen($start_date) > 0 || strlen($end_date) > 0) {
            $start_date = strlen($start_date) === 0 ? date("Y-m-d") : $start_date;
            $end_date = strlen($end_date) === 0 ? date("Y-m-d") : $end_date;
        }

        $result_summary = SummaryReport::get_records($user->ID, $start_date, $end_date, $_POST["collection_slug_summary_report"]);        

        $csv_download_link = "";
        // csv generation                    
        if (count($result_summary) > 0) {
            $roport_for = "";
            if(strlen($_POST['collection_slug_summary_report']) > 0){
                $roport_for = 'Collection: '.$_POST['collection_slug_summary_report'];
            }else{
                $roport_for = 'Contributor: '.$_POST['contributor_slug'];
            }
            $data_for_csv = [];
            array_unshift($data_for_csv, array("All Resources", " ", "resources", $result_summary["resources"]["unique_users"], $result_summary["resources"]["number_of_resource_views"], $result_summary["resources"]["number_of_downloads"], $result_summary["resources"]["percent_visitor_unknown"], $result_summary["resources"]["percent_us"], $result_summary["resources"]["percent_international"]));
            array_unshift($data_for_csv, array("All Collections", " ", "collection", $result_summary["collections"]["unique_users"], $result_summary["collections"]["number_of_resource_views"], $result_summary["collections"]["number_of_downloads"], $result_summary["collections"]["percent_visitor_unknown"], $result_summary["collections"]["percent_us"], $result_summary["collections"]["percent_international"]));
            array_unshift($data_for_csv, array('Resource Title', 'Url', 'Type', 'Unique Users', 'Number of Resource Views', 'Number of downloads', '% Percent Visitor Unknown', '% in the US (based on visits.ip)', '% international (based on visits.ip)'));
            array_unshift($data_for_csv, array(' '));
            array_unshift($data_for_csv, array($roport_for, 'Start Date: ' . $start_date, 'End Date: ' . $end_date));
            array_unshift($data_for_csv, array(' '));
            array_unshift($data_for_csv, array('Summary Report'));
            $csv_file = make_report_csv($data_for_csv, "summary");
            $csv_download_link = home_url() . "/wp-admin/images/$csv_file"."?hash=". time();
        }
    }
}
?>
<h2>Summary Report</h2>

<div class="reporting_nav">
    <a href="<?php echo admin_url() . "admin.php?page=reporting{$contributor_slug_in_sm}" ?>"  > <strong>Detailed Report</strong> </a>
    <strong>&nbsp;|&nbsp;</strong>
    <a href="<?php echo admin_url() . "admin.php?page=reporting{$contributor_slug_in_sm}&rpt=geo" ?>"  > <strong>Geography Report</strong> </a>
    <strong>&nbsp;|&nbsp;</strong>
    <a href="<?php echo admin_url() . "admin.php?page=reporting{$contributor_slug_in_sm}&rpt=ga" ?>"  > <strong>Google Analytics Report</strong> </a>
    <strong>&nbsp;|&nbsp;</strong>
    <a id="contributor_slug_in_membertype" href="<?php echo admin_url() . "admin.php?page=reporting&rpt=membertype" ?>"  > <strong>Member Type Report</strong> </a> 
</div>

<form id="user-filter" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="summary-report-table">
        <tbody>
            <tr>                    
                <td>
                    <p><strong>Enter Contributor's Slug</strong></p>
                    <p><input class="large-text" type="text" name="contributor_slug" name="contributor_slug" value="<?php echo isset($_REQUEST['contributor_slug']) ? $_REQUEST['contributor_slug'] : ""; ?>"></p>
                </td>
                <td>
                    <p><strong>Enter Collection's Slug</strong></p>
                    <p><input class="large-text" type="text" name="collection_slug_summary_report" name="collection_slug_summary_report" value="<?php echo isset($_REQUEST['collection_slug_summary_report']) ? $_REQUEST['collection_slug_summary_report'] : ""; ?>"></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>Date Range</strong></p>
                    <p>
                        <input type="text" name="startdate" id="startdate" placeholder="Start Date" value="<?php echo strlen($start_date) > 0 ? $start_date : ""; ?>" />
                    </p>
                </td>
                <td>
                    <p><strong>&nbsp;</strong></p>
                    <p>
                        <input type="text" name="enddate" id="enddate" placeholder="End Date" value="<?php echo strlen($end_date) > 0 ? $end_date : ""; ?>" />
                    </p>
                </td>                    
                <td>
                    <p><strong>&nbsp;</strong></p>
                    <p><input type="submit" class="button tagadd" id="get_summary_by_contributor" name="get_summary_by_contributor" value="GO"></p>
                </td>
            </tr>
            <tr>
                <td>                        
                    <p>
                        &nbsp;&nbsp; <?php echo strlen($csv_download_link) > 0 ? '<a href="' . $csv_download_link . '">Download CSV</a>' : "<i>(Apply filter to get CSV download link!)</i>" ?>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>            
</form>


<?php
$contributor_slug_in_sm = isset($_REQUEST['contributor_slug']) && strlen($_REQUEST['contributor_slug']) > 0 ? "&contributor_slug=" . $_REQUEST['contributor_slug'] : "";
?>    

<table class="wp-list-table widefat fixed striped reportings">
    <thead>
        <tr>
            <th scope="col" id="title" class="manage-column column-title column-primary">Resource Title</th><th scope="col" id="url" class="manage-column column-url">Url</th><th scope="col" id="type" class="manage-column column-type">Type</th><th scope="col" id="unique_users" class="manage-column column-unique_users">Unique Users</th><th scope="col" id="number_of_resource_views" class="manage-column column-number_of_resource_views">Number of Resource Views</th><th scope="col" id="number_of_downloads" class="manage-column column-number_of_downloads">Number of downloads</th><th scope="col" id="percent_us" class="manage-column percent_visitor_unknown">% Percent Visitor Unknown</th><th scope="col" id="percent_us" class="manage-column column-percent_us">% in the US (based on visits.ip)</th><th scope="col" id="percent_international" class="manage-column column-percent_international">% international (based on visits.ip)</th>	
        </tr>
        <?php
        if (count($result_summary) > 0) {
            ?>      
            <tr>
                <td class="title column-title has-row-actions column-primary" data-colname="Resource Title">All Collections<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>
                <td class="url column-url" data-colname="Url"></td>
                <td class="type column-type" data-colname="Type">collection</td>
                <td class="unique_users column-unique_users" data-colname="Unique Users"><?php echo $result_summary["collections"]["unique_users"]; ?></td>
                <td class="number_of_resource_views column-number_of_resource_views" data-colname="Number of Resource Views"><?php echo $result_summary["collections"]["number_of_resource_views"]; ?></td>
                <td class="number_of_downloads column-number_of_downloads" data-colname="Number of downloads"><?php echo $result_summary["collections"]["number_of_downloads"]; ?></td>
                <td class="percent_visitor_unknown column-percent_visitor_unknown" data-colname="% Percent Visitor Unknown"><?php echo $result_summary["collections"]["percent_visitor_unknown"]; ?></td>
                <td class="percent_us column-percent_us" data-colname="% in the US (based on visits.ip)"><?php echo $result_summary["collections"]["percent_us"]; ?></td>
                <td class="percent_international column-percent_international" data-colname="% international (based on visits.ip)"><?php echo $result_summary["collections"]["percent_international"]; ?></td>
            </tr>   
            
            <tr>
                <td class="title column-title has-row-actions column-primary" data-colname="Resource Title">All Resources<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>
                <td class="url column-url" data-colname="Url"></td>
                <td class="type column-type" data-colname="Type">resource</td>
                <td class="unique_users column-unique_users" data-colname="Unique Users"><?php echo $result_summary["resources"]["unique_users"]; ?></td>
                <td class="number_of_resource_views column-number_of_resource_views" data-colname="Number of Resource Views"><?php echo $result_summary["resources"]["number_of_resource_views"]; ?></td>
                <td class="number_of_downloads column-number_of_downloads" data-colname="Number of downloads"><?php echo $result_summary["resources"]["number_of_downloads"]; ?></td>
                <td class="percent_visitor_unknown column-percent_visitor_unknown" data-colname="% Percent Visitor Unknown"><?php echo $result_summary["resources"]["percent_visitor_unknown"]; ?></td>
                <td class="percent_us column-percent_us" data-colname="% in the US (based on visits.ip)"><?php echo $result_summary["resources"]["percent_us"]; ?></td>
                <td class="percent_international column-percent_international" data-colname="% international (based on visits.ip)"><?php echo $result_summary["resources"]["percent_international"]; ?></td>
            </tr>                    
            
            <?php
        } else {
            ?>
            <tr><td class="title column-title has-row-actions column-primary" colspan="8"><p style="text-align: center;">No Record Found!</p></td></tr>
            <?php
        }
        ?>
    </thead>
</table>