/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



jQuery( document ).ready(function() {
        
    if (typeof base_url === 'undefined') {        
        var base_url = "";
    }
    
    jQuery('.community-row').find('.button').attr('href', base_url+'/search-page?t=groups');
    jQuery('.resources-row').find('.button').attr('href', base_url+'/search-page');
    jQuery('.standards-row').find('.button').attr('href', base_url+'/search-page');
    
    jQuery('.collection-body-content .more .more-btn').click(function(e){
        e.preventDefault();
        jQuery(this).closest('.collection-description').find('.collection-content').removeClass("hidden");
        jQuery(this).closest('.collection-description').find('.desc').addClass("hidden");
        jQuery(this).addClass("hidden");
    });
   
});

function curriki_ShowHideState(){
    var country = jQuery('#country').val();
    jQuery('#state_select').hide();
    jQuery('#state_select').attr('disabled','disabled');
    jQuery('#state_text').hide();
    jQuery('#state_text').attr('disabled','disabled');
    if(country == 'US'){
        jQuery('#state_select').show();
        jQuery('#state_select').removeAttr('disabled');
    }else{
        jQuery('#state_text').show();
        jQuery('#state_text').removeAttr('disabled');
    }
}