/*
* Child Theme Name: Curriki Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/

This file should contain any js scripts you want to add to the site.
You can add it in the Genesis admin too, but sometimes that can
get a bit jumbled and it's tough once you have a lot going on.
Use this file to better manage your scripts.

*/

// as the page loads, call these scripts
jQuery(document).ready(function($) {

    /*
    Responsive jQuery is a tricky thing.
    There's a bunch of different ways to handle
    it so, be sure to research and find the one
    that works for you best.
    */
    
    /* getting viewport width */
    var responsive_viewport = $(window).width();
    
    /* if is below 481px */
    if (responsive_viewport < 481) {
    
    } /* end smallest screen */
    
    /* if is larger than 481px */
    if (responsive_viewport > 481) {
        
    } /* end larger than 481px */
    
    /* if is below 980px */
    if (responsive_viewport < 980) {
    
        /* load gravatars */
        $('.comment img[data-gravatar]').each(function(){
            $(this).attr('src',$(this).attr('data-gravatar'));
        });


        /* Load Sidr Menu */
        (function( $ ) {
            "use strict";
            $(function() {
            // Slide-Out Menu

                /* prepend menu icon */
                $('.header-widget-area').first().prepend('<div id="mobile-header"><a class="responsive-menu-button fa fa-bars" href="#sidr"></a></div>');
                
                $('.responsive-menu-button').sidr({
                    name: 'sidr',
                    source: '.nav-primary',
                    side: 'right',
                });


            });

        }(jQuery));

        
    }
    $(window).resize(function(){
        $('#mobile-header').remove();
        $('.header-widget-area').first().prepend('<div id="mobile-header"><a class="responsive-menu-button fa fa-bars" href="#sidr"></a></div>');
        $('.responsive-menu-button').sidr({
            name: 'sidr',
            source: '.nav-primary',
            side: 'right',
        });
        if($('#sidr').css('display') != 'none'){
            $('[href=#sidr]').trigger('click');
        }
        
    });
    /* off the bat large screen actions */
    if (responsive_viewport > 1030) {
        
    }
    


      $( "#member-tabs" ).tabs();

      var icons = {
        header: "fa-plus-circle",
        activeHeader: "fa-minus-circle"
      };

      $( "#member-info-accordion" ).accordion({
        collapsible: true,
        icons: icons,
        active: false
      });
      $( "#group-info-accordion" ).accordion({
        collapsible: true,
        icons: icons,
        active: false
      });



      $( "#toggle" ).button().click(function() {
        if ( $( "#member-info-accordion" ).accordion( "option", "icons" ) ) {
          $( "#member-info-accordion" ).accordion( "option", "icons", null );
        } else {
          $( "#member-info-accordion" ).accordion( "option", "icons", icons );
        }
      });

            

        



 
}); /* end of as page load scripts */