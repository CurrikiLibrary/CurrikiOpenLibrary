<?php
if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap">
  <div style="height: 50px; padding-top: 20px;">
      <h2 style="display:inline;"><?php _e('Premium Landing Page Desgns', 'wp-landing-pages'); ?></h2>
      <div style="float:right;"><a href="http://intensewp.com/cc_wp-landing-pages" target="_blank"><?php _e('Upgrade to Pro & Get 30+ premium Landing page designs', 'wp-landing-pages'); ?></a></div>
  </div>
  <div class="theme-browser rendered">
      <div class="themes">

          <?php
          foreach (glob(plugin_dir_path(__FILE__) . "llp_tpl/premium/*.jpg") as $llp_row) {
              $img_name = pathinfo($llp_row);
              ?>
              <div class="theme">
                  <div class="theme-screenshot"> <img src="<?php echo plugins_url('/wp-landing-pages/llp_tpl/premium/' . $img_name['basename']); ?>" alt=""> </div>
                  <span class="more-details"><?php _e('Premium Template', 'wp-landing-pages'); ?></span>
                  <div class="theme-author"><?php _e('By ', 'wp-landing-pages'); ?>99robots</div>

                  <div class="theme-actions"> <a target="_blank" class="button button-primary customize load-customize hide-if-no-customize" href="http://intensewp.com/cc_wp-landing-pages"><?php _e('Use this template', 'wp-landing-pages'); ?></a> </div>
              </div>
              <?php
          }
          ?>
      </div>
      <br class="clear">
  </div>
</div>
