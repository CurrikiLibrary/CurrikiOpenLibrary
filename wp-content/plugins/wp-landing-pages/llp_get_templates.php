<?php
if (!defined('WPINC')) {
    die;
}
?>
<div class="wrap">
  <div style="padding-top: 20px;">
      <h2 style="display:inline;"><?php _e('Select Landing Page Template', 'wp-landing-pages'); ?></h2>
      <div style="float:right;"><a href="http://intensewp.com/cc_wp-landing-pages" target="_blank"><?php _e('Upgrade to Pro & Get 30 premium Landing page designs', 'wp-landing-pages'); ?></a></div>
      <p> <strong>Note: While Editing, Make sure you click the <u>Preview</u> button BEFORE clicking <u>Save</u> button, that is how it will save the changes in the database.</strong> </p>
  </div>
  <?php
  $tpl_folders = llp_read_tpl_dir();
  ?>

  <div class="theme-browser rendered">
      <div class="themes">

          <?php
          if ($tpl_folders) {
              foreach ($tpl_folders as $llp_row) {
                  $tpl_data = llp_get_lp_data($llp_row . '/index.php');
                  if ($tpl_data) {
                      ?>
                      <div class="theme">
                          <div class="theme-screenshot"> <img src="<?php echo llp_URL . '/llp_tpl/' . $tpl_data['Template File'] . '/images/' . $tpl_data['Preview Image']; ?>" alt=""> </div>
                          <span class="more-details"><?php echo $tpl_data['Landing Page Name']; ?></span>
                          <div class="theme-author"><?php _e('By ', 'wp-landing-pages'); ?>99robots</div>
                          <h3 class="theme-name"><?php echo $tpl_data['Landing Page Name']; ?></h3>
                          <div class="theme-actions"> <a target="_blank" class="button button-primary customize load-customize hide-if-no-customize" href="<?php echo home_url(); ?>/?tpl_id=<?php echo $tpl_data['Template File']; ?>&do=create"><?php _e('Use this template', 'wp-landing-pages'); ?></a> </div>
                      </div>
                      <?php
                  }
              }
          }
          ?>
      </div>
      <br class="clear">
  </div>
</div>
