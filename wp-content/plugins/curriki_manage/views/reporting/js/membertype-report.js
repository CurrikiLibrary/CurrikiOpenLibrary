
function setViewSummaryLink() {
    window.contributor_slug_sm_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=summary" + window.contributor_slug_in_sm_link;
    jQuery("#contributor_slug_in_sm").attr("href", window.contributor_slug_sm_link);
}
function setViewGALink() {
    window.contributor_slug_ga_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=ga" + window.contributor_slug_in_ga_link;
    jQuery("#contributor_slug_in_ga").attr("href", window.contributor_slug_ga_link);
}

jQuery(document).ready(function () {
    window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_membertype_report").val();
    setViewSummaryLink();

    window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_membertype_report").val();
    setViewGALink();

    window.paged = 1;
    window.total_pages = 0;

    jQuery("#get_membertype_userdata").click(function (e) {
        if( jQuery("#contributor_slug_membertype_report").val().length > 0 || jQuery("#collection_slug_membertype_report").val().length > 0 ){
            jQuery("#form_aciton").val("get_membertype_userdata");
            jQuery("#user-filter-membertype-report").trigger("submit");
        }                
    });
    
    jQuery("#user-filter-membertype-report").submit(function (e) {
        e.preventDefault();
        
        window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_membertype_report").val();
        setViewSummaryLink();

        window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_membertype_report").val();
        setViewGALink();


        jQuery("#get_csv").attr("disabled", "disabled");
        jQuery("#get_membertype_by_contributor").attr("disabled", "disabled");
        jQuery("#download_links_container").addClass("hidden");
        
        window.membertypeSummaryReportDataTable.clear().draw();  
        window.membertypeReportDataTable.clear().draw();  
        
        renderLoading();
        var data = jQuery(e.target).serialize() +
                "&" + jQuery("#get_membertype_by_contributor").attr("name") + "=" + jQuery("#get_membertype_by_contributor").attr("value") +
                "&" + jQuery("#get_csv").attr("name") + "=" + (jQuery("#get_csv").prop('checked') ? 1 : 0);                       
       
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {action: "process_membertype_report", data: data}
        }).done(function (response) {                        
            var res = JSON.parse(response);                                            
            /****** user data report *****/            
            if('userdata_csv_file' in res){
                jQuery("#form_aciton").val("show_report");
                jQuery(".spinner_box").addClass("hide");
                jQuery("#get_csv").removeAttr("disabled");
                jQuery("#get_membertype_by_contributor").removeAttr("disabled");
                window.location = window.location.protocol+ "//" + window.location.hostname + "/wp-admin/images/" + res.userdata_csv_file;
                return false;
            }
            
            /******* Summary Report **********/                              
            addRowsToSummaryReport(res.summary_stats);
            
            /******* Detail Report **********/
            //window.DetailFooterData = res.us_details.total;
            var newDsDetail = [];
            jQuery(res.resoruces_stats).each(function (i, obj) {
                var newRowDetail = [];
                var title_url = window.location.protocol+ "//" + window.location.hostname + "/oer/"+obj.pageurl;                                
                var title_link = '<a href="'+title_url+'" target="_blank">'+obj.title+'</a>';
                newRowDetail = [title_link, obj.type, obj.teachers_views, obj.students_views,obj.parents_views];
                newDsDetail.push(newRowDetail);
            });                    
            addRowsToDetailReport(newDsDetail);
        
            jQuery(".spinner_box").addClass("hide");
            jQuery("#get_csv").removeAttr("disabled");
            jQuery("#get_membertype_by_contributor").removeAttr("disabled");
            //**** processing CSV ****
            if (jQuery("#get_csv").prop('checked') ) {
                var csvPathArr = jQuery("#csv_download_link").val().split("/");                
                csvPathArr.splice(csvPathArr.length-1,1);
                var downloadPath = csvPathArr.join('/');
                jQuery("#download_links_container").removeClass("hidden");                
                jQuery("#summary_membertype_download").attr("href",downloadPath+"/"+res.other.summary_csv_file + "?hash=" + Date.now() );
                jQuery("#detail_membertype_download").attr("href",downloadPath+"/"+res.other.detail_csv_file  + "?hash=" + Date.now() );
            }
                                   
            
        });

    });

    
    window.membertypeSummaryReportDataTable = jQuery('#membertype_summary_report_dt').DataTable({
        ordering: false,
        pageLength: 5,
        searching: false,
        lengthChange: false,        
        columns: [
            {title: "Summary (Resources & Collections)"},
            {title: "Teachers"},
            {title: "Students"},
            {title: "Parents"}            
        ],
        columnDefs: [            
            {"targets": [0, 1 , 2, 3], "width": '19%'}
        ],        
        fnFooterCallback: function(row, data, start, end, display) {
            //setReportFooter(window.countrySummaryFooterData,this);
        }
    });
    
    window.membertypeReportDataTable = jQuery('#membertype_report_dt').DataTable({
        ordering: false,
        pageLength: 5,
        searching: false,
        lengthChange: false,        
        columns: [
            {title: "Individual (Resources & Collections)"},
            {title: "Type (resource/collection)"},
            {title: "Teachers"},
            {title: "Student"},
            {title: "Parents"}            
        ],
        columnDefs: [            
            {"targets": [2, 3 , 4], "width": '19%'}
        ],        
        fnFooterCallback: function(row, data, start, end, display) {
            //setReportFooter(window.countrySummaryFooterData,this);
        }
    });
 
    
});

function addRowsToSummaryReport(newRows) {    
    window.membertypeSummaryReportDataTable.rows.add(newRows).draw();
}
function addRowsToDetailReport(newRows) {    
    window.membertypeReportDataTable.rows.add(newRows).draw();
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