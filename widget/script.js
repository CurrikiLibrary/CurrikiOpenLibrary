!function(d,s,id){
    window.onload = function(){
        var wdg = d.getElementById(id);
        if(wdg){
            var wdg_url = "http://cg.curriki.org/curriki/search-widget-page/?src=ifr&partnerid="+csw_partnerid;
            var iframe = document.createElement('iframe');        
            iframe.setAttribute("src", wdg_url);
            iframe.style.width = csw_width;
            iframe.style.height = csw_height;
            wdg.appendChild(iframe);
        }
    };    
}(document,"script","csw-container");

