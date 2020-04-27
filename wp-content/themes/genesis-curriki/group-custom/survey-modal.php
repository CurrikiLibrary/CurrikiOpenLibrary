<?php
/*
  Author: Waqar Muneer
 */

global $wpdb;    

$current_language = "eng";
$current_language_slug = "";
if( defined('ICL_LANGUAGE_CODE') )
{
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
    $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
}


wp_enqueue_style('modals-custom-style', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-style.css');
wp_enqueue_script('modals-custom-script', get_stylesheet_directory_uri() . '/js/modals-custom-script/modals-custom-script.js', array('jquery'), false, true);

global $wpdb;
$q = "SELECT * FROM cur_options WHERE option_name='survey-modal'";
$modal_options = $wpdb->get_row($q, OBJECT);

$m_options = json_decode($modal_options->option_value);
if( property_exists($m_options, "is_active") && $m_options->is_active === 1 )
{   
    wp_enqueue_style('survey-modal', get_stylesheet_directory_uri()."/group-custom/css/survey-modal.css");
    wp_enqueue_script('survey-modal', get_stylesheet_directory_uri()."/group-custom/js/survey-modal.js" , array('jquery'), false, true);
    render_survey_modal($m_options);
}
?>

<?php function render_survey_modal($m_options){ 
        global $wpdb;
        $current_language = "eng";
        if( defined('ICL_LANGUAGE_CODE') )
            $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

    ?>
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
<form method="post" action="" enctype="multipart/form-data" id="survey-form">
    
    <div class="modal-dn fade" id="survey-popup" style="display: none;">
        <div class="modal-dn-body">        

            <div style="border: 0px solid red; height: 10px;position: absolute; left: 575px;">
                <p>
                    <span>
                        <strong></strong>
                    </span>
                    <span id="close-cross-srv" style="float: right;cursor: pointer;">
                        <strong>X</strong>
                    </span>

                </p>
            </div>
            
            <div class="hrt-para-wrapper-survey">
                <p class="center-para-survey heading-para-survey hrt-para-wrapper">
                    Your Planbook - Love it or Hate it?
                </p>                                    
            </div>
            
            <div class="dm-donate-btn-wrapper-survey">
                <button id="survey-go" type="button" class="close" data-dismiss="modal" style="height: 40px;" go-link="http://svy.mk/2dlJ4UM">
                    <span class="sr-only">GO</span>
                </button>        
            </div>
            <?php 
                $http_str = is_ssl() ? "https":"http";
                $actual_link = "$http_str://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
            ?>
            <input type="hidden" name="current-url-visit-for-survey" id="current-url-visit-for-survey" value="<?php echo urlencode($actual_link) ; ?>" />

        </div><!-- /.modal-body -->    
    </div><!-- /.modal -->
    
    <div class="survey-form-msg" style="display: none"><?php echo __('Please complete form','curriki'); ?></div>
</form>
<?php 
}

?>


