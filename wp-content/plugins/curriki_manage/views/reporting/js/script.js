jQuery(document).ready(function () {

    jQuery("#startdate").datepicker({
        dateFormat: "yy-mm-dd"
    });
    jQuery("#enddate").datepicker({
        dateFormat: "yy-mm-dd"
    });
});


function setViewSummaryLink() {
    window.contributor_slug_sm_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=summary" + window.contributor_slug_in_sm_link;
    jQuery("#contributor_slug_in_sm").attr("href", window.contributor_slug_sm_link);
}
function setViewGALink() {
    window.contributor_slug_ga_link = jQuery("#admin_url").val() + "admin.php?page=reporting&rpt=ga" + window.contributor_slug_in_ga_link;
    jQuery("#contributor_slug_in_ga").attr("href", window.contributor_slug_ga_link);
}

jQuery(document).ready(function () {
    window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_detailed_report").val();
    setViewSummaryLink();

    window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_detailed_report").val();
    setViewGALink();

    window.paged = 1;
    window.total_pages = 0;

    jQuery("#user-filter-detailed-report").submit(function (e) {
        e.preventDefault();

        window.contributor_slug_in_sm_link = '&contributor_slug=' + jQuery("#contributor_slug_detailed_report").val();
        setViewSummaryLink();

        window.contributor_slug_in_ga_link = '&contributor_slug=' + jQuery("#contributor_slug_detailed_report").val();
        setViewGALink();


        jQuery("#get_csv").attr("disabled", "disabled");
        jQuery("#get_summary_by_contributor").attr("disabled", "disabled");

        if (window.paged === 1) {
            window.reportDataTable.clear().draw();
        }
        renderLoading();
        var data = jQuery(e.target).serialize() +
                "&" + jQuery("#get_summary_by_contributor").attr("name") + "=" + jQuery("#get_summary_by_contributor").attr("value") +
                "&" + jQuery("#get_csv").attr("name") + "=" + (jQuery("#get_csv").prop('checked') ? 1 : 0);
        if (window.paged > 1)
        {
            data = data + "&paged=" + window.paged;
        }
        jQuery.ajax({
            method: "POST",
            url: ajaxurl,
            data: {action: "process_detailed_report", data: data}
        })
                .done(function (response) {
                    var res = JSON.parse(response);
                    var newDS = [];
                    jQuery(res.records).each(function (i, obj) {
                        var newRow = [];
                        newRow = [obj.title, obj.url, obj.type, obj.unique_users, obj.number_of_resource_views, obj.number_of_resource_views_1, obj.number_of_downloads, obj.percent_visitor_unknown, obj.percent_us, obj.percent_international];
                        newDS.push(newRow);
                    });
                    //newDS.reverse();
                    addRows(newDS);
                    window.paged++;
                    if (window.paged <= parseInt(res.total_pages)) {
                        window.total_pages = res.total_pages;
                        jQuery("#user-filter-detailed-report").trigger("submit");
                    } else {
                        window.paged = 1;
                        window.total_pages = 0;
                        jQuery("#loading_span").removeClass("hide");
                        jQuery("#loading_box").html(jQuery("#loading_box_copy").html()).addClass("hide");
                        jQuery(".spinner_box").addClass("hide");

                        jQuery("#get_csv").removeAttr("disabled");
                        jQuery("#get_summary_by_contributor").removeAttr("disabled");

                        if (jQuery("#get_csv").prop('checked') && window.reportDataTable.data().length > 0) {
                            window.location = jQuery("#csv_download_link").val() + "?hash=" + Date.now();
                        }
                    }
                });

    });

    window.reportDataTable = jQuery('#detailed_report_dt').DataTable({
        ordering: false,
        columns: [
            {title: "Resource Title"},
            {title: "Url"},
            {title: "Type"},
            {title: "Unique Users"},
            {title: "Number of Views (Resource / Collection)"},
            {title: "Number of Views (Children)"},
            {title: "Number of downloads"},
            {title: "% Percent Visitor Unknown"},
            {title: "% in the US (based on visits.ip)"},
            {title: "% international (based on visits.ip)"},
        ],
        columnDefs: [
            {className: "text-center", "targets": [3, 4, 5, 6, 7, 8, 9]},
            {"targets": [0, 1], "width": '19%'}
        ]
    });
});

function addRows(newRows) {
    window.reportDataTable.rows.add(newRows).draw();
}

function renderLoading() {
    jQuery("#loading_box").removeClass("hide");
    jQuery(".spinner_box").removeClass("hide");
    var loaded_page = "Loading Page ....";
    jQuery("#loading_span").text(loaded_page);
    jQuery("#loading_page_span").text(window.paged);

    if (window.total_pages > 0) {
        jQuery("#loaded_page_span").text("of " + window.total_pages).removeClass("hide");
    }
}

    