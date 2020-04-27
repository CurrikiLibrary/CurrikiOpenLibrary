<h1>Member Type Report</h1>

<div class="reporting_nav">
    <a id="contributor_slug_in_sm" href="#"  > <strong>Summary Report</strong> </a>    
    <strong>&nbsp;|&nbsp;</strong>
    <a href="<?php echo admin_url() . "admin.php?page=reporting{$contributor_slug_in_sm}" ?>"  > <strong>Detailed Report</strong> </a>
    <strong>&nbsp;|&nbsp;</strong>
    <a href="<?php echo admin_url() . "admin.php?page=reporting{$contributor_slug_in_sm}&rpt=geo" ?>"  > <strong>Geography Report</strong> </a>
    <strong>&nbsp;|&nbsp;</strong>
    <a id="contributor_slug_in_ga" href="<?php echo admin_url() . "wp-admin/admin.php?page=reporting&rpt=ga" ?>"  > <strong>Google Analytics Report</strong> </a> 
</div>  

<form id="user-filter-membertype-report" method="get">

    <table class="membertype-report-table">
        <tbody>
            <tr>                    
                <td>
                    <p><strong>Enter Contributor's Slug</strong></p>
                    <p><input class="large-text" type="text" id="contributor_slug_membertype_report" name="contributor_slug" value="<?php echo isset($_REQUEST['contributor_slug']) ? $_REQUEST['contributor_slug'] : ""; ?>"></p>
                </td>                                                    
                <td>
                    <p><strong>Enter Collection Slug</strong></p>
                    <p><input class="large-text" type="text" id="collection_slug_membertype_report" name="collection_slug_membertype_report" value="<?php echo isset($_REQUEST['collection_slug_membertype_report']) ? $_REQUEST['collection_slug_membertype_report'] : ""; ?>"></p>
                </td>                    
            </tr>
            <tr>                    
                <td>
                    <p><strong>Date Range</strong></p>
                    <p>
                        <input type="text" name="startdate" id="startdate" placeholder="Start Date" value="<?php //echo strlen($start_date) > 0 ? $start_date : "";  ?>" />
                    </p>
                </td>
                <td>
                    <p>&nbsp;</p>
                    <p>                            
                        <input type="text" name="enddate" id="enddate" placeholder="End Date" value="<?php //echo strlen($end_date) > 0 ? $end_date : "";  ?>" />
                    </p>
                </td>                                        
            </tr>
            <tr>
                <td>
                    <p>
                        <input type="checkbox" name="get_csv" id="get_csv" value="1" <?php echo isset($_REQUEST['get_csv']) && $_REQUEST['get_csv'] == 1 ? "checked=checked" : ""; ?> />
                        <label>Generate CSV</label>
                    </p>
                    <p id="download_links_container" class="hidden">
                        <a href="#" id="summary_membertype_download" target="_blank">Download Summary Report</a>
                        <br />
                        <a href="#" id="detail_membertype_download" target="_blank">Download Detail Report</a>
                    </p>
                </td>
                <td>
                    <p>
                        <input type="hidden" name="form_aciton" id="form_aciton" value="show_report" />
                        <input style="float: left;margin-top: 6px;margin-right: 6px;" type="submit" class="button tagadd" id="get_membertype_by_contributor" name="get_membertype_by_contributor" value="GO" />
                        <input style="float: left;margin-top: 6px;margin-right: 6px;" type="button" class="button tagadd" id="get_membertype_userdata" name="get_membertype_userdata" value="Download User Data" />
                        <span style="float: left" class="spinner_box hide"><img width="40" src="<?php echo home_url() ?>/wp-content/themes/genesis-curriki/images/spinner.gif" /></span>
                    </p>
                </td>
            </tr>
        </tbody>
    </table> 

    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />            
    <div>                
        <div class="report-heading-wrapper">
            <h1> Summary</h1>
        </div>        
        <table id="membertype_summary_report_dt" class="display cell-border" width="100%"></table>
        
        <div class="report-heading-wrapper">
            <h1> Individual Collection/Resource</h1>
        </div>        
        <table id="membertype_report_dt" class="display cell-border" width="100%"></table>        
    </div>      

</form>