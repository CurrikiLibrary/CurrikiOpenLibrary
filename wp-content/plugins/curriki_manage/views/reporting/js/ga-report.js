function setViewSummaryLinkInGA() {
    window.contributor_slug_sm_link_in_ga = window.location.origin + "/wp-admin/admin.php?page=reporting&rpt=summary" + window.contributor_slug_in_sm_link;
    jQuery("#contributor_slug_in_sm").attr("href", window.contributor_slug_sm_link_in_ga);
}
function setViewDetailLink() {
    window.contributor_slug_detail_link = window.location.origin + "/wp-admin/admin.php?page=reporting" + window.contributor_slug_in_detail_link;
    jQuery("#contributor_slug_in_detail").attr("href", window.contributor_slug_detail_link);
}

jQuery(document).ready(function () {

    window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_ga_report").val();
    setViewSummaryLinkInGA();

    window.contributor_slug_in_detail_link = '&contributor_slug=' + jQuery("#contributor_slug_ga_report").val();
    setViewDetailLink();
    
    window.next_paging_info = "";            
    window.fetched_rows_count = 0;
    window.fetched_percent = 0;
    window.total_results = 0;
    
    jQuery("#user-google-analytics-report").submit(function (e) {
        e.preventDefault();

        window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_ga_report").val();
        setViewSummaryLinkInGA();

        window.contributor_slug_in_detail_link = '&contributor_slug=' + jQuery("#contributor_slug_ga_report").val();
        setViewDetailLink();


        var validatMessage = "";
        var validateError = false;        
        if (jQuery('#startdate').val().length === 0)
        {
            validatMessage += " Please enter [Start Date] \n";
            validateError = true;
        }
        if (jQuery('#enddate').val().length === 0)
        {
            validatMessage += " Please enter [End Date] \n";
            validateError = true;
        }
        if (validateError) {
            alert(validatMessage)
            return false;
        }
        
        if(window.fetched_rows_count == 0){
            resetRenderLoadingGA();
            window.gaDataTable.clear().draw();
        }
        renderLoadingGA();        
        var data = jQuery(e.target).serialize() +
                "&" + jQuery("#get_ga_by_contributor").attr("name") + "=" + jQuery("#get_ga_by_contributor").attr("value") +
                "&" + jQuery("#get_csv_ga").attr("name") + "=" + (jQuery("#get_csv_ga").prop('checked') ? 1 : 0) +
                "&" + jQuery("#get_csv_ga").attr("name") + "=" + (jQuery("#get_csv_ga").prop('checked') ? 1 : 0);

        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {action: "process_ga_report", data: data , next_paging_info:window.next_paging_info}
        })
                .done(function (response) {
                    var res = JSON.parse(response);

                    if (res.response_status === 'success')
                    {
                        window.next_paging_info = res.next_paging_info;
                        
                        var resources = res.resources;
                        var newDS = [];
                        jQuery(resources).each(function (i, obj) {
                            var newRow = [];                            
                            var percent_us_field_val = obj.percent_val_for_percent_in_usa;
                            var percent_intl_field_val = obj.percent_val_for_percent_in_intl;
                            newRow = [obj.title, obj.url, obj.type, obj.ga_pageviews, obj.percent_val_for_unknown_location, percent_us_field_val, percent_intl_field_val];
                            newDS.push(newRow);
                        });

                        newDS.reverse();
                        window.gaDataTable.rows.add(newDS).draw();
                        
                        if(typeof window.next_paging_info === 'object' && window.next_paging_info['start-index'] !== null){
                            window.fetched_rows_count += parseInt(window.next_paging_info['max-results']);
                            window.total_results = parseInt(window.next_paging_info['total_results']);
                            window.fetched_percent = Math.round((window.fetched_rows_count/window.total_results)*100);
                            renderLoadingGA();
                            jQuery("#user-google-analytics-report").trigger('submit');
                        }else if(typeof window.next_paging_info === 'object' && window.next_paging_info['start-index'] === null){
                            postRenderState();                            
                            if (jQuery("#get_csv_ga").prop('checked') && window.gaDataTable.data().length > 0) {
                                var csvPathArr = jQuery("#csv_download_link").val().split("/");                
                                csvPathArr.splice(csvPathArr.length-1,1);
                                var downloadPath = csvPathArr.join('/');
                                window.location = downloadPath + "/" +res.other.csv_file;
                            }
                        }
                        
                    } else if (res.response_status === 'fail')
                    {
                        postRenderState();
                        alert(res.detail.error.message);
                    }
                });

    });

    window.gaDataTable = jQuery('#ga_table').DataTable({
        ordering: true,
        columns: [
            {title: "Resource Title"},
            {title: "Url"},
            {title: "Type"},
            {title: "Page Views"},
            {title: "% visitors unknown"},
            {title: "% in the US (based on GA Country)"},
            {title: "% international (based on GA Country)"}
        ],
        columnDefs: [
            {className: "text-center", "targets": [3, 4, 5, 6]},
            {"targets": [0, 1], "width": '19%'}
        ]
    });
});

function postRenderState(){
    window.next_paging_info = "";
    window.fetched_percent = 0;
    window.fetched_rows_count = 0;
    window.total_results = 0;
    renderLoadingGA();
    resetRenderLoadingGA();
}

function resetRenderLoadingGA() {        
    jQuery("#loading_span").removeClass("hide");
    jQuery("#loading_box").html(jQuery("#loading_box_copy").html()).addClass("hide");
    jQuery(".spinner_box").addClass("hide");
    
    jQuery("#get_csv_ga").removeAttr("disabled");
    jQuery("#get_ga_by_contributor").removeAttr("disabled");
}
function renderLoadingGA() {
    jQuery("#loading_box").removeClass("hide");
    jQuery(".spinner_box").removeClass("hide");
    var loaded_page = "Loading ....";
    jQuery("#loading_span").text(loaded_page);
    jQuery("#loading_page_span").text(window.fetched_percent + "% of " + window.total_results + " GA records");
    
    jQuery("#get_csv_ga").attr("disabled","disabled");
    jQuery("#get_ga_by_contributor").attr("disabled","disabled");
}