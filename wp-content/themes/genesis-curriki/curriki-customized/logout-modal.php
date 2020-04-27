<script type="text/javascript">
jQuery(function () {

    jQuery.fn.center_fn = function () {
        var h = jQuery(this).height();
        var w = jQuery(this).width();
        var wh = jQuery(window).height();
        var ww = jQuery(window).width();
        var wst = 0; //jQuery(window).scrollTop();
        var wsl = 0; //jQuery(window).scrollLeft();
        this.css("position", "absolute");
        var $top = Math.round((wh - h) / 2 + wst);
        var $left = Math.round((ww - w) / 2 + wsl);


        this.css("top", $top + "px");
        this.css("left", $left + "px");
        this.css("z-index", "1000");
        return this;
    }   
      
    jQuery(".class-header-menu-logout").click(function () {        
        jQuery("#login-dialog").hide();
        jQuery("#logout-dialog").hide();
        jQuery("#forgotpassword-dialog").hide();
        jQuery("#signup-dialog").hide();
        jQuery("#logout-dialog").show();
        setInterval(function () {
          jQuery("#logout-dialog").center_fn();
        }, 1);
    });
    
});
</script>

<div id="logout-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
    <h3 class="modal-title"><?php echo __('Logout?','curriki'); ?></h3>
    <div class="join-login-section grid_5">
      <div class="signup-form">
        <?php echo __('Are you sure you want to logout?','curriki'); ?>
          <?php
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          ?>
        <input type="reset" onclick="window.location = '<?php echo wp_logout_url($actual_link); ?>';" class="small-button green-button login" value="<?php echo __('Yes','curriki'); ?>">
      </div>
    </div>

    <div class="close"><span class="fa fa-close" onclick="jQuery('#logout-dialog').hide();"></span></div>
</div>
