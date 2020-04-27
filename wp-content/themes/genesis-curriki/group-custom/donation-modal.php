<?php
/*
 Author: Waqar Muneer
 */

wp_enqueue_style('modals-custom-style', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-style.css');
wp_enqueue_script('modals-custom-script', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-script.js', array('jquery'), false, true);

global $wpdb;
$q = "SELECT * FROM cur_options WHERE option_name='donationmodal'";
$modal_options = $wpdb->get_row($q,OBJECT);

$m_options = json_decode($modal_options->option_value);

?>

<script type="text/javascript">
    
    var ajaxflag = false;    
    var ajaxflagClose = false;    
    var is_modal_opened = false;
    var started_to_close = false;
    var remain_close_for = <?php echo $m_options->remain_close_for; ?>; 
    var remain_open_for = <?php echo $m_options->remain_open_for; ?>*1000;
    var marke_break = false;    
    var modal_mode = <?php echo property_exists($m_options, "modal_mode") ? $m_options->modal_mode : 0; ?>;
    
    jQuery(document).ready(function(){
                
        
        jQuery.fn.stickToBottom = function () {
          var h = jQuery(this).height();
                           
          
                  var w = jQuery(this).width();
                  var wh = jQuery(window).height();
                  var ww = jQuery(window).width();
                  var wst = jQuery(window).scrollTop();
                  var wsl = jQuery(window).scrollLeft();
                  this.css("position", "absolute");
                  var $top = Math.round((wh - h) / 2 + wst);
                  var $left = Math.round((ww - w) / 2 + wsl);
                  
                  $top = $top+200;
                  
                  $left = 30;                                            
                  
                  this.css("top", $top + "px");
                  this.css("left", ($left) + "px");
                  /*this.css("border", "1px solid red");*/
                  return this;
          }
          
        jQuery('#slide-bottom-popup').stickToBottom();
                
        jQuery(window).scroll(function() {  
	    jQuery('#slide-bottom-popup').stickToBottom();
	});
          
        //console.log("go there...");        
        //jQuery("#slide-bottom-popup").stick_in_parent({parent:'body'});
        
        
        
        jQuery("#make-donation").click(function(){            
            window.location = "<?php echo site_url() ?>/about-curriki/donate/";
        });
        
        jQuery("#close-cross").click(function(){            
            //handel_dn_modal_close("closedn"); 
            
            
            
            //==== re init variables =========
            ajaxflag = false;    
            ajaxflagClose = false;    
            is_modal_opened = false;
            started_to_close = false;
            
            
            jQuery('#slide-bottom-popup').hide();
            
            marke_break = true;
            
            
        });
        
        //===============  Donation Modal code ===========
        
        <?php if( (property_exists($m_options, "modal_mode") && $m_options->modal_mode === 2) || !property_exists($m_options, "modal_mode")) { ?>
                    jQuery('#slide-bottom-popup').show();
        <?php } ?>
            
        <?php if( property_exists($m_options, "modal_mode") && $m_options->modal_mode === 1) { ?>
                    setInterval(function() {                        
                
                
                                if(marke_break === false) 
                                {
                                     if(ajaxflag === false && is_modal_opened === false)
                                     {
                                         ajaxflag = true; 
                                         handel_dn_modal();            
                                     }
                                 }
                                 /*else if(is_modal_opened === true){
                                     //console.log("doo close here...");
                                     if(started_to_close === true)
                                     { 
                                         started_to_close = false;
                                         setTimeout(function(){
                                             //console.log("colsed after 5 sec ...");
                                             handel_dn_modal_close();
                                         }, remain_open_for);
                                     }                    
                                 }*/

                         }, 1000); // milliseconds


                         setInterval(function() {                        

                             if(marke_break === false) 
                             {

                               if(is_modal_opened === true){
                                     //console.log("doo close here...");
                                     if(started_to_close === true)
                                     { 
                                         started_to_close = false;
                                         setTimeout(function(){
                                             //console.log("started to close colsed after 5 sec ...");
                                             if(ajaxflagClose === false)
                                             {
                                                 ajaxflagClose = true;
                                                 handel_dn_modal_close();
                                             }                            
                                         }, remain_open_for);
                                     }                    
                                 }
                             }
                         }, 1000); // milliseconds
        <?php } ?>
        
        
        
    });

<?php if( property_exists($m_options, "modal_mode") && $m_options->modal_mode === 1) { ?>
    function handel_dn_modal(act)
    {
        
            
            act = act || "none";
            //console.log("act = " , act);
            args =  jQuery.param({
                        'action': 'dn_modal_handel',                                
                        'act':act,
                    });
            jQuery.ajax({
                url: ajaxurl,
                data:args,
                method:"POST"
                }).done(function(data) {
                    
                    ajaxflag = false;                   
                    
                    
                    var output = JSON.parse(data);

                    //console.log("seceonds_spends: ", output.seceonds_spends);
                    //console.log("minutes_spends: ", output.minutes_spends);

                    //if( output.minutes_spends > 1)
                    if( output.seceonds_spends > remain_close_for)
                    {
                        //jQuery('#slide-bottom-popup').show();
                        jQuery('#slide-bottom-popup').show();
                        is_modal_opened = true;
                        started_to_close = true;
                    }                        
                    
                    //console.log("ajaxflag 2 = " , ajaxflag);
                    
                    marke_break = false;
            });
                
    }
    
    function handel_dn_modal_close(act)
    {
        
            
            act = act || "closedn";
            //console.log("act = " , act);
            args =  jQuery.param({
                        'action': 'dn_modal_handel_close',                                
                        'act':act,
                    });
                    
            jQuery.ajax({
                    url: ajaxurl,
                    data:args,
                    method:"POST"
                    }).done(function(data) { 
                        //console.log("hide = " , data);
                        ajaxflagClose = false;
                        jQuery('#slide-bottom-popup').hide();
                        is_modal_opened = false;   
                        started_to_close = false;
                        
                        
                        marke_break = false;
                        
                        
                    });
                
    }    
    //===============  Donation Modal code ===========
<?php } ?>    
    
    
    
    
</script>

<style type="text/css">
    body.user-dashboard .modal-dn-body p
    {
        font-size: 14px;
    }
    body.user-dashboard .modal-dn-body 
    {
        width: 600px !important;
        background: none repeat scroll 0 0 #ffffff !important;
        border-radius: 8px !important;
        border: 3px solid #d1d1d1 !important;
        padding: 10px !important;
    }
    .donation-heading
    {
        /*color: #7fc41a !important;*/
        color: #53830c !important;
    }
</style>
<div class="modal-dn fade" id="slide-bottom-popup" style="display: none;">
    <div class="modal-dn-body">        
        
        <div style="border: 0px solid red; height: 10px;position: absolute; left: 575px;">
            <p>
                <span>
                    <strong></strong>
                </span>
                <span id="close-cross" style="float: right;cursor: pointer;">
                    <strong>X</strong>
                </span>

            </p>
        </div>
        
        <?php                
                $post_name = "donation-modal-text-2";
                //$post_name = "donation-modal-text-1";
                $dm_obj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}posts WHERE post_name = '{$post_name}'");
                echo htmlspecialchars_decode($dm_obj->post_content);
        ?>       
        
        <div class="dm-donate-btn-wrapper">
            <button id="make-donation" type="button" class="close" data-dismiss="modal" style="height: 40px;">
                <span class="sr-only">Make a Donation</span>
            </button>        
        </div>
        
    </div><!-- /.modal-body -->
</div><!-- /.modal -->



