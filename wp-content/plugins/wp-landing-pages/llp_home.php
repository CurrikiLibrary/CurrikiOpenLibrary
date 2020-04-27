<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<style>
.dflex{
  display: flex;
}
.mb10{
  margin-bottom: 10px !important;
}
.mr10{
  margin-right: 10px;
}
.pt3{
  padding-top: 3px;
}
.pull-right{
  float: right;
}
.pull-left{
  float: left;
}
.info-box{
  padding: 20px;
  background: #fff;
  display: inline-block;
  width: 40%;
  border: 1px solid #e5e5e5;
  box-shadow: 0 1px 1px rgba(0,0,0,.04);
  margin-bottom: 10px;
}
.bg-white{
  background: #f5f5f5;
}
.fs16{
  font-size: 16px;
}
.text-green{
  color: green;
}
@media only screen and (max-width: 768px){
  .info-box{
    width: auto;
    float: none !important;
  }
}
</style>
<div class="wrap">
  <h1>Dashboard</h1>
  <div id="welcome-panel" class="welcome-panel">
    <div class="welcome-panel-content">
      <h1>WP Landing Pages - <?php _e('Home', 'wp-landing-pages'); ?></h1>
      <p class="about-description">Here are some details to get you started:</p>
      <div class="welcome-panel-column-container">
        <div class="welcome-panel-column">
          <h3 class="">Get Familiar - </h3>
          <a class="button button-primary button-hero" href="http://www.intensewp.com/wplp-docs-free-link" target="_blank"><i class="far fa-file-alt mr10"></i> Read The Documentation</a>
        </div>
        <div class="welcome-panel-column">
          <h3>Quick Tutorial</h3>
          <ul>
            <li class="dflex"><i class="fas fa-plus pt3 mr10"></i> <a href="<?php echo admin_url('admin.php?page=llp_templates');?>">To Create A Landing Page Click Here</a></li>
            <li class="dflex"><i class="far fa-edit pt3 mr10"></i> Once on the LP creation page, Select a template, Enter the page name, and edit as required. Click Preview, and then save to see how your changes reflected.</li>
            <li class="dflex"><i class="fas fa-pencil-alt pt3 mr10"></i> <a href="<?php echo admin_url('edit.php?post_type=landing-page');?>">To Edit/Customize an existing page/template, you can also go to the Landing Page CPT, where you can also "Edit in Design View". (Recommended Way)</a></li>
            <li class="dflex"></li>
            <li class="dflex"></li>
          </ul>
        </div>
        <div class="welcome-panel-column welcome-panel-last">
          <h3>Support</h3>
          <ul>
            <li class="dflex"><i class="far fa-smile-beam pt3 mr10"></i> <?php
                _e('Thank you for choosing WP Landing Pages plugin. We hope you will like the plugin and enjoy using it. Please leave us a review if you like our plugin.', 'wp-jobs');
                ?>
            </li>
            <li class="dflex"><i class="far fa-question-circle pt3 mr10"></i> <a href="mailto:sales@intensewp.com"><?php _e('If you have any support query or your feedback, please feel free to send an email to sales@intensewp.com', 'wp-landing-pages'); ?></a></li>
            <li class="dflex"><i class="fas fa-headset pt3 mr10"></i> <a href="http://support.intensewp.com/" target="_blank"><?php _e('You can also visit our Support Help Desk by clicking here', 'wp-jobs'); ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div> <!-- Welcome Panel -->
  <div class="info-box pull-left">
    <h3 style="margin-top:0px;"><?php _e('Quick Tutorial', 'wp-landing-pages'); ?></h3>
    <p style="margin-top:0px;"><?php _e('WP Landing Pages auto responder integration with Aweber','wp-landing-pages')?></p>
    <iframe width="100%" height="250" src="https://www.youtube.com/embed/OX7km4x854U" frameborder="0" allowfullscreen></iframe>
  </div>
  <div class="info-box pull-right">
    <h3 style="margin-top:0px;"><?php _e('Quick Tutorial', 'wp-landing-pages'); ?></h3>
    <p style="margin-top:0px;"><?php _e('Connect WP Landing Pages with MailChimp Auto Responder','wp-landing-pages')?></p>
    <iframe width="100%" height="250" src="https://www.youtube.com/embed/5AkN_ntr_cs" frameborder="0" allowfullscreen></iframe>
  </div>
  <!--<h3><?php // _e('Stay updated and get more customized feature updates directly to your inbox', 'wp-landing-pages'); ?></h3>
  <p>-->
      <!-- Begin MailChimp Signup Form -->
      <!--<link href="//cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
  <style type="text/css">
      #mc_embed_signup{clear:left; font:14px Helvetica,Arial,sans-serif; }
      /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
         We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
  </style>
  <div id="mc_embed_signup">
      <form action="http://etechy101.us8.list-manage.com/subscribe/post?u=0feea2b1671b773d914b338e6&amp;id=8ca20a276b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
          <strong><?php // _e('We promise, your email address will not be shared or spammed ever. Leave your email below', 'wp-landing-pages'); ?></strong>
          <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php // _e('email address', 'wp-landing-pages'); ?>" required>
          <!- real people should not fill this in and expect good things - do not remove this or risk form bot signups->
          <div style="position: absolute; left: -5000px;"><input type="text" name="b_b8210f8523d1e31f37518d48e_155e3516fe" value=""></div>
          <div class="clear"><input type="submit" value="<?php // _e('Join', 'wp-landing-pages'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
      </form>
  </div>-->
  <div style="clear:both;"></div>

  <h1 align="center"><?php _e('Go pro - Upgrade to WP Landing Pages Pro', 'wplp'); ?></h1>
  <div><h3 align="center"><?php _e('WP Landing Pages Pro comes with up to 30 fresh and premium landing page designs. Pro version is also updated frequently with new premium landing page designs.', 'wplp'); ?></h3>
      <p align="center">
          <a href="http://intensewp.com/cc_wp-landing-pages" target="_blank"><img class="mb10" src="<?php echo llp_URL; ?>/images/pro-screen-shots.png" /></a>
          <br /><a href="http://intensewp.com/cc_wp-landing-pages" target="_blank"><img src="<?php echo llp_URL; ?>/images/button_upgrade_Now.png" /></a></p></div>
</div> <!--End Wrap -->

  <?php
  define("ENCRYPTION_KEY", "!@#$%^&*");
  $string = "3109275";
  /* echo $encrypted = encrypt($string, ENCRYPTION_KEY);
    echo "<br />";
    echo $decrypted = decrypt($encrypted, ENCRYPTION_KEY);
   */

  /**
   * Returns an encrypted & utf8-encoded
   */
  function encrypt($pure_string, $encryption_key) {
      $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
      return $encrypted_string;
  }

  /**
   * Returns decrypted original string
   */
  function decrypt($encrypted_string, $encryption_key) {
      $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
      return $decrypted_string;
  }
