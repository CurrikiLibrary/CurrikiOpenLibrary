jQuery('document').ready(function(){
    
    console.log("groups-tab ===> ");
    
    $("#groups-tab").click(function(e){
        e.preventDefault();
        /*
        alert(".....");
        window.open(
          "http://www.curriki.org/search/",
          '_blank'
        );
        */
       $("#site-search-form #type").val("Group");
       $("#search-button-btn").trigger("click");
    });
    $("#members-tab").click(function(e){
        e.preventDefault();
        /*
        alert(".....");
        window.open(
          "http://www.curriki.org/search/",
          '_blank'
        );
        */
       $("#site-search-form #type").val("Member");
       $("#search-button-btn").trigger("click");
    });
    
    $("#resource-tab").click(function(e){
        e.preventDefault();
        /*
        alert(".....");
        window.open(
          "http://www.curriki.org/search/",
          '_blank'
        );
        */
       $("#site-search-form #type").val("Resource");
       $("#search-button-btn").trigger("click");
    });
    
    $("#view-more-resources").click(function(){
        $("#search-button-btn").trigger("click");
    });
    /*
    jQuery(".search-tab-other").click(function(e){
        e.preventDefault();                
        if( $(this).hasClass("groups-tab") )
        {
            //$(".searhc-type").val("Group");
            
        }else if($(this).hasClass("members-tab"))
        {
            //$(".searhc-type").val("Member");
        }
        //$("#site-search-form").attr("action",$(this).attr("href"));
        //$("#site-search-form").submit();
        window.open(
          $(this).attr("href")+"&phrase="+$("#phrase-curriki").val(),
          '_blank'
        );

    });
    */
});