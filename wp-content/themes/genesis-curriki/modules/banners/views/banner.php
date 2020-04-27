<?php
$current_language = "eng";
$current_language_slug = "";
if( defined('ICL_LANGUAGE_CODE') )
{
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 
    $current_language_slug = ( ICL_LANGUAGE_CODE == "en" ? "":"/".ICL_LANGUAGE_CODE );
}
?>
<div class="site-banner">
    <div class="banner-row">            
                <img class="banner-img" src="https://cdn.curriki.org/uploads/2016/07/13132952/student-hat-logo.png" width="55"> 
                <p class="banner-para">
                    <?php //echo __('Curriki is proud to be a 2016 CODiE Award Finalist for Best Source for Reference or Education Resources!','curriki'); ?> 
                    <?php echo __('NEW! Introduction to Computational Thinking Professional Development: Self-paced Professional Development (PD)','curriki'); ?> 
                    <br /><a href="<?php echo site_url().$current_language_slug ?>/introduction-to-computational-thinking-pd/" class="banner-readmore-cls"><?php echo __('Read More...','curriki'); ?></a>
                </p>
            
    </div>
</div>