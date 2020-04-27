
function setViewSummaryLink() {
    window.contributor_slug_sm_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=summary" + window.contributor_slug_in_sm_link;
    jQuery("#contributor_slug_in_sm").attr("href", window.contributor_slug_sm_link);
}
function setViewGALink() {
    window.contributor_slug_ga_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=ga" + window.contributor_slug_in_ga_link;
    jQuery("#contributor_slug_in_ga").attr("href", window.contributor_slug_ga_link);
}

jQuery(document).ready(function () {
    window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_geography_report").val();
    setViewSummaryLink();

    window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_geography_report").val();
    setViewGALink();

    window.paged = 1;
    window.total_pages = 0;

    jQuery("#user-filter-geography-report").submit(function (e) {
        e.preventDefault();
        
        window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_geography_report").val();
        setViewSummaryLink();

        window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_geography_report").val();
        setViewGALink();


        jQuery("#get_csv").attr("disabled", "disabled");
        jQuery("#get_summary_by_contributor").attr("disabled", "disabled");
        jQuery("#download_links_container").addClass("hidden");
        
        if (window.paged === 1) {
            window.geoReportDataTable.clear().draw();  
            reportRemoveFooter(window.geoReportDataTable);
            window.usDetailDataTable.clear().draw();
            reportRemoveFooter(window.usDetailDataTable);
        }
        
        renderLoading();
        var data = jQuery(e.target).serialize() +
                "&" + jQuery("#get_geography_by_contributor").attr("name") + "=" + jQuery("#get_geography_by_contributor").attr("value") +
                "&" + jQuery("#get_csv").attr("name") + "=" + (jQuery("#get_csv").prop('checked') ? 1 : 0);
       
        if (window.paged > 1)
        {
            data = data + "&paged=" + window.paged;
        }
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {action: "process_geography_report", data: data}
        }).done(function (response) {                        
            var res = JSON.parse(response);                                            
            /******* Populating Country Summary **********/
            window.countrySummaryFooterData = res.country_summary.total;
            var newDS = [];
            jQuery(res.country_summary.records).each(function (i, obj) {
                var newRow = [];
                newRow = [obj.country_name, obj.resources_pageviews, obj.collections_pageviews, obj.pageviews];
                newDS.push(newRow);
            });                    
            addRows(newDS);                                
            
            /******* Populating Country Summary **********/
            window.usDetailFooterData = res.us_details.total;
            var newDsUSDetail = [];
            jQuery(res.us_details.records).each(function (i, obj) {
                var newRowUS = [];
                newRowUS = [obj.region, obj.resources_pageviews, obj.collections_pageviews, obj.pageviews];
                newDsUSDetail.push(newRowUS);
            });                    
            addRowsUSDetailTable(newDsUSDetail);                    
            
            
            jQuery(".spinner_box").addClass("hide");
            jQuery("#get_csv").removeAttr("disabled");
            jQuery("#get_summary_by_contributor").removeAttr("disabled");
            //**** processing CSV ****
            if (jQuery("#get_csv").prop('checked') ) {
                var csvPathArr = jQuery("#csv_download_link").val().split("/");                
                csvPathArr.splice(csvPathArr.length-1,1);
                var downloadPath = csvPathArr.join('/');
                jQuery("#download_links_container").removeClass("hidden");                
                jQuery("#country_summary_geography").attr("href",downloadPath+"/"+res.country_summary.other.file+"?hash=" + Date.now());
                jQuery("#us_detail_geography").attr("href",downloadPath+"/"+res.us_details.other.file + "?hash=" + Date.now());
            }
        });

    });

    
    window.geoReportDataTable = jQuery('#country_summary_report_dt').DataTable({
        ordering: false,
        pageLength: 5,
        searching: false,
        lengthChange: false,        
        columns: [
            {title: "Country"},
            {title: "Resource Pageviews"},
            {title: "Collection Pageviews"},
            {title: "Total"}            
        ],
        columnDefs: [            
            {"targets": [0, 1 , 2, 3], "width": '19%'}
        ],        
        fnFooterCallback: function(row, data, start, end, display) {
            setReportFooter(window.countrySummaryFooterData,this);
        }
    });
    
    window.usDetailDataTable = jQuery('#us_detail_report_dt').DataTable({
        ordering: false,
        pageLength: 5,
        searching: false,
        lengthChange: false,
        columns: [
            {title: "State"},
            {title: "Resource Pageviews"},
            {title: "Collection Pageviews"},
            {title: "Total"}            
        ],
        columnDefs: [
            {"targets": [0, 1 , 2, 3], "width": '19%'}
        ],
        fnFooterCallback: function(row, data, start, end, display) {
            setReportFooter(window.usDetailFooterData,this);
        }       
    });
    
});

function addRows(newRows) {
    window.geoReportDataTable.rows.add(newRows).draw();
}
function addRowsUSDetailTable(newRows) {
    window.usDetailDataTable.rows.add(newRows).draw();
}

function renderLoading() {
    jQuery(".spinner_box").removeClass("hide");
}

function reportRemoveFooter(report_instance){
    if( jQuery(report_instance.table().node()).find('tfoot').get().length > 0 ){                
        jQuery(report_instance.table().node()).find('tfoot').remove();
    }
}
            
function setReportFooter(footerData , this_dt){    
    if( footerData !== null && typeof footerData !== 'undefined'){        
        jQuery(this_dt).find('tfoot').remove();
        var tFooter = jQuery('<tfoot><tr></tr></tfoot>');
        var footer = jQuery(this_dt).append(tFooter);
        this_dt.api().columns().every(function (i) {
            var column = this;                
            var footerText = "";                        
                    switch (i){
                        case 0:
                            footerText = "Total";
                            break;
                        case 1:
                            footerText = footerData.resources_pageviews;
                            break;
                        case 2:
                            footerText = footerData.collections_pageviews;
                            break;
                        case 3:
                            footerText = footerData.pageviews;
                            break;
                        default:
                            footerText = "---";
                    }                                                
            jQuery(tFooter).find('tr').append('<th><div id="foot-col-'+i+'">'+footerText+'</div></th>');
        });
    }
}