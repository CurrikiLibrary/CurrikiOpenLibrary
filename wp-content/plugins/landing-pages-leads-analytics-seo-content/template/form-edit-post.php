<div style="margin-bottom: 20px;">
    <div class="clear">
        <div class="lp-form-post-title">
            <strong>Landing page title</strong>   
        </div>
        <div class="lp-form-post-desc">
            <input type="text" style="background-color:#fffbcc;" value="<?php echo (isset($res['title']) && !empty($res['title']) ? $res['title'] : get_the_title($post->ID) );?>" name="title-landing-page">
        </div>
    </div>
    <div class="clear">
        <div class="lp-form-post-title">
            <strong>Text message (to display in the order form)</strong> 
        </div>
        <div class="lp-form-post-desc">
            <input type="text" style="background-color:#fffbcc;" value="<?php echo (isset($res['text']) && !empty($res['text']) ? $res['text'] : 'I want to order %landing-page-title% for...' );?>" name="text-message-landing-page">
            <span style="font: 12px; color: #888;"><strong>%landing-page-title%</strong> - <i>this variable can be used in each landing page as order text. It make the target order by your customer much easier.</i></span>
        </div>
    </div>
    <div class="clear">
        <div class="lp-form-post-title">
            <strong>Invisible notes for admin</strong> 
        </div>
        <div class="lp-form-post-desc">
            <input type="text" value="<?php echo (isset($res['desc']) && !empty($res['desc']) ? $res['desc'] : "");?>" name="description-landing-page"><br />
        </div>
    </div>
    <div class="clear">
        <div class="lp-form-post-title">
            <input type="hidden" value="1" name="landing-page-post">
            <input type="submit" value="Save landing page changes" style="width: 220px" class="button button-primary button-large">
        </div>
    </div>
    <div class="clear"></div>
</div>
