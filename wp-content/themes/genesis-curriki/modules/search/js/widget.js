window.onload = function () {

    var script = document.querySelector("script[partnerid]");
    var url = window.location.href.split('?');

    var options = {
        script: script,
        partnerid: script.getAttribute("partnerid") || 1,
        width: script.getAttribute("width") || "100%",
        height: script.getAttribute("height") || "600px",
        style: script.getAttribute("style") || "",
        class: script.getAttribute("class") || "",
        curriki_search_URL: "https://www.curriki.org/search/?",
        partner_search_URL: url[0],
        search_target: script.getAttribute("search_target") || "self",
        query: url[1] || "",
        params: url[2] || "",
    };
    console.log(options);

    if (options.query.toLowerCase().indexOf('oer/') >= 0) {
        options.curriki_search_URL = "https://www.curriki.org/";
    } else {
        if (options.query.indexOf('partnerid') < 0)
            options.query += "&partnerid=" + options.partnerid;

        if (options.query.indexOf('search_target') < 0)
            options.query += "&search_target=" + options.search_target;

        if (options.query.indexOf('partner_search_URL') < 0)
            options.query += "&partner_search_URL=" + options.partner_search_URL;

        if (options.query.indexOf('viewer') < 0)
            options.query += "&viewer=embed";
    }

    var iframe = '<iframe ' +
            'src="' + options.curriki_search_URL + options.query + (options.params ? "?" + options.params : "") + '" ' +
            'class="' + options.class + '" style="' + options.style + '" ' +
            'width="' + options.width + '" height="' + options.height + '" ' +
            'onload="searchIframeLoaded(this)"></iframe>';
    console.log(iframe);

    script.insertAdjacentHTML('afterend', iframe);

};

var searchIframeLoaded = function (iframe) {
    console.log("iframe Loaded");
}