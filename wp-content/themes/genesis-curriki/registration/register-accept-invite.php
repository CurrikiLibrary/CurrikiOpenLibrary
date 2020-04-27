<?php
    function register_curriki_modal_login_signup() 
    {          
      ?>
      <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <script src="//code.jquery.com/jquery-1.10.2.js"></script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
      <script type="text/javascript">
        function hideshowcenter($id1, $id2) {
          jQuery($id1).hide();
          jQuery($id2).show();

          if (jQuery.fn.center_func == undefined)
          {
            jq($id2).center_func();
          } else
          {
            jQuery($id2).center_func();
          }

          //setInterval(function () {jQuery( $id2 ).center()}, 1);
        }
        jQuery.fn.center_func = function () {
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
        };

        jQuery(function () {
      <?php
      if (isset($_GET['modal'])) {
        if ($_GET['modal'] == 'login')
          echo "hideshowcenter('#login-dialog', '#login-dialog');";
      }
      ?>

          jQuery('#curriki-editable-firstname').click(function () {
            update_profile(jQuery(this), 'firstname');
          });
          jQuery('#curriki-editable-lastname').click(function () {
            update_profile(jQuery(this), 'lastname');
          });
          jQuery('#curriki-editable-bio').click(function () {
            update_profile(jQuery(this), 'bio');
          });
          jQuery('#curriki-editable-city').click(function () {
            update_profile(jQuery(this), 'city');
          });
          jQuery('#curriki-editable-state').click(function () {
            update_profile(jQuery(this), 'state');
          });
          function update_profile($this, field) {
            if (jQuery('input[name=' + field + ']').length)
              return;
            var $val = $this.html();
            $this.html('<input type="text" name="' + field + '" value="' + $val + '" />');
            jQuery('input[name=' + field + ']').focus();
            jQuery("input[name=" + field + "]").focusout(function () {
              $_val = jQuery("input[name=" + field + "]").val();
              $this.html($_val);
              var posting = jQuery.post('?curriki_ajax_action=update_profile', {field: field, value: $_val, curriki_ajax_action: 'update_profile'});
            });
          }
          /*jQuery( "#login-dialog" ).dialog({
           autoOpen: false,
           show: {
           effect: "blind",
           duration: 1000
           },
           hide: {
           effect: "explode",
           duration: 1000
           }
           });*/


          jQuery.fn.center = function () {
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
          jQuery(".class-header-menu-login").click(function () {
            jQuery("#login-dialog").hide();
            jQuery("#logout-dialog").hide();
            jQuery("#forgotpassword-dialog").hide();
            jQuery("#signup-dialog").hide();
            jQuery("#login-dialog").show('slow');
            setInterval(function () {
              jQuery("#login-dialog").center()
            }, 1);
          });
          jQuery(".class-header-menu-logout").click(function () {
            jQuery("#login-dialog").hide();
            jQuery("#logout-dialog").hide();
            jQuery("#forgotpassword-dialog").hide();
            jQuery("#signup-dialog").hide();
            jQuery("#logout-dialog").show();
            setInterval(function () {
              jQuery("#logout-dialog").center()
            }, 1);
          });
          jQuery(".class-header-menu-signup").click(function () {
            jQuery("#login-dialog").hide();
            jQuery("#logout-dialog").hide();
            jQuery("#forgotpassword-dialog").hide();
            jQuery("#signup-dialog").hide();
            jQuery("#signup-dialog").show('slow');
            setInterval(function () {
              jQuery("#signup-dialog").center()
            }, 1);
          });

          

          // Attach a submit handler to the form
          jQuery("#signupformreg").submit(function (event) {
            
            jQuery("#signup_result").empty().append('Please wait!');
            jQuery(".dialog_result_div").css('background-color', '#031770');
            // Stop form from submitting normally
            event.preventDefault();
            // Get some values from elements on the page:
            var $form = jQuery(this),
                    firstname = $form.find("input[name='firstname']").val(),
                    lastname = $form.find("input[name='lastname']").val(),
                    username = $form.find("input[name='username']").val(),
                    email = $form.find("input[name='email']").val(),
                    pwd = $form.find("input[name='pwd']").val(),
                    confirm_pwd = $form.find("input[name='confirm_pwd']").val(),
                    member_type = $form.find("select[name='member_type'] option:selected").val(),
                    country = $form.find("select[name='country'] option:selected").val(),
                    state = $form.find("select[name='state'] option:selected").val(),
                    city = $form.find("input[name='city']").val(),
                    zipcode = $form.find("input[name='zipcode']").val(),
                    accept = $form.find("input[name='accept']").attr('checked') ? '1' : '0',
                    gender = $form.find("input[name='gender']:checked").val(),
                    submit_source = $form.find("input[name='submit_source']").val(),
                    is_registration_invitation = $form.find("input[name='is-registration-invitation']").val(),
                    url = '?curriki_ajax_action=signup&t=' + new Date().getTime();

            // Send the data using post
            var posting = jQuery.post(url, {firstname: firstname, lastname: lastname, username: username, email: email, pwd: pwd, confirm_pwd: confirm_pwd, member_type: member_type, country: country, state: state, city: city, accept: accept, action: 'curriki_signup', zipcode: zipcode, gender: gender , submit_source:submit_source, is_registration_invitation:is_registration_invitation});
            // Put the results in a div
            posting.done(function (data) {

              if (data.trim() != '1') {
                jQuery("#signup_result").empty().append(data);
                jQuery(".dialog_result_div").css('background-color', '#031770');
              } else {
                jQuery("#signup_result").empty().append('Please wait!');
                jQuery(".dialog_result_div").css('background-color', '#031770');
                //window.location="<?php echo get_permalink(6015); ?>";
                window.location = "<?php echo site_url() ?>/dashboard/?cn=1";
              }
            });

            return false;

          });

          jQuery("#resetform").submit(function (event) {
            jQuery("#resetform_result").empty().append('Please wait!');
            jQuery("#resetform_result").show();
            // Stop form from submitting normally
            event.preventDefault();
            // Get some values from elements on the page:
            var $form = jQuery(this),
                    reset_email = $form.find("input[name='reset_email']").val(),
                    url = '?curriki_ajax_action=reset_email&t=' + new Date().getTime();
            // Send the data using post
            var posting = jQuery.post(url, {reset_email: reset_email, action: 'curriki_resetpassword'});
            // Put the results in a div
            posting.done(function (data) {

              if (data.trim() != '1') {
                jQuery("#resetform_result").empty().append(data);
                jQuery(".dialog_result_div").css('background-color', '#031770');
              } else {
                jQuery("#resetform_result").empty().append('Please check your email.');
                jQuery(".dialog_result_div").css('background-color', '#031770');
                setInterval(function () {
                  jQuery("#forgotpassword-dialog").hide('slow')
                }, 5000);
              }
            });

            return false;

          });
          jQuery('#country').change(function () {
            var $selected_country = jQuery('#country').val();
            if ($selected_country != 'US') {
              jQuery('#state').val("");
              jQuery('#city').val("");
              jQuery('#state').hide();
              jQuery('#city').hide();
            } else {
              jQuery('#state').show();
              jQuery('#city').show();
            }
          });

      <?php
      if (isset($_GET["a"]) && $_GET["a"] == "login") {
        ?>
            jQuery('.class-header-menu-login a').trigger("click");
            jQuery('#login-dialog').find(".modal-title").after('<p class="message_para">Please Log in or Create an Account to continue to access our free Resources</p>');
        <?php
      }
      ?>

        });
      </script>
      <div id="login-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
        <h3 class="modal-title"><?php echo __('Log in to Your Account','curriki'); ?></h3>
        <div class="dialog_result_div"><div id="login_result" class="dialog_result"></div></div>
        <div class="join-login-section grid_5">
          <div class="signup-form">
            <form method="post" id="loginform">
              <input type="text" name="log" placeholder="<?php echo __('Username','curriki'); ?>">
              <input type="password" name="pwd" placeholder="<?php echo __('Password','curriki'); ?>">
              <input type="submit" class="small-button green-button login" value="Log In">          

            </form>
          </div>
        </div>
        <div class="modal-split grid_2">Or</div>
        <div class="join-login-section grid_5">
          <div class="signup-oauth"><p><?php do_action('oa_social_login'); ?></p>
            <?php $app_auth_url = urlencode(get_bloginfo('url')) . "%2Fclever_login%2Foauth"; ?>
              <!--<a href="https://clever.com/oauth/authorize?response_type=code&client_id=e5da6ddd7da309c332b6&redirect_uri=<?php echo $app_auth_url ?>" alt="Log in with Clever"><img src="https://s3.amazonaws.com/assets.clever.com/sign-in-with-clever/sign-in-with-clever-full.png" /></a>-->
          </div>
        </div>
        <div class="join-login-bottom rounded-borders-bottom"><a href="javascript:hideshowcenter('#login-dialog', '#signup-dialog');"><?php echo __("Don't have an account? Join Now",'curriki'); ?></a></div>
        <div class="close"><span class="fa fa-close" onclick="jQuery('#login-dialog').hide();"></span></div>
      </div>
      <div id="logout-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
        <h3 class="modal-title"><?php echo __('Logout?','curriki'); ?></h3>
        <div class="join-login-section grid_5">
          <div class="signup-form">
            <?php echo __('Are you sure you want to logout?','curriki'); ?>
            <input type="reset" onclick="window.location = '<?php echo wp_logout_url(home_url()); ?>';" class="small-button green-button login" value="<?php echo __('Yes','curriki'); ?>">
          </div>
        </div>

        <div class="close"><span class="fa fa-close" onclick="jQuery('#logout-dialog').hide();"></span></div>
      </div>
      <div id="forgotpassword-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="display: none;">
        <h3 class="modal-title"><?php echo __('Please enter your email address and we will email you instructions.','curriki'); ?></h3>
        <div class="dialog_result_div"><div id="resetform_result" class="dialog_result"></div></div>
        <div class="join-login-section grid_5">
          <div class="signup-form">
            <form method="post" id="resetform" name="resetform">
              <input type="text" name="reset_email" placeholder="<?php echo __('Your Email Address','curriki'); ?>">
              <input type="submit" class="small-button green-button login" value="<?php echo __('Send','curriki'); ?>">
            </form>
          </div>
        </div>

        <div class="close"><span class="fa fa-close" onclick="jQuery('#forgotpassword-dialog').hide();"></span></div>
      </div>


      <style type="text/css">
        #login-dialog{
          width: 700px !important;
        }
        #signup-dialog{
          width: 700px !important;
        }
        .join-login-section{
          text-align: left;
        }               
      </style>

      <div id="signup-dialog" class="join-oauth-modal modal border-grey rounded-borders-full grid_8" title="Sign Up">
        <h3 class="modal-title"><?php echo __('Sign Up','curriki'); ?></h3>
        <div class="dialog_result_div"><div id="signup_result" class="dialog_result"></div></div>
        <div class="join-login-section grid_5"><div class="signup-form">
            <form id="signupformreg" name="signupformreg" method="post">
              <input name="signup" value="yes" type="hidden" /> 
              <input type="text" name="firstname" placeholder="<?php echo __('First Name','curriki'); ?>" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname'] ?>" />
              <input type="text" name="lastname" placeholder="<?php echo __('Last Name','curriki'); ?>" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname'] ?>" />
              <input type="text" name="username" placeholder="<?php echo __('Username','curriki'); ?>" value="<?php if (isset($_POST['username'])) echo $_POST['username'] ?>">
              <?php

                $email_val = "";
                if (isset($_GET['email']))
                    $email_val = $_GET["email"];
                if (isset($_POST['email'])) 
                    $email_val = $_POST['email'];
              ?>
              <input type="text" name="email" placeholder="<?php echo __('Email','curriki'); ?>" value="<?php echo $email_val; ?>">
              <input type="password" name="pwd" placeholder="<?php echo __('Password','curriki'); ?>">
              <input type="password" name="confirm_pwd" placeholder="<?php echo __('Confirm Password','curriki'); ?>">
              <?php if (!isset($_POST['member_type'])) $_POST['member_type'] = ""; ?>
              <select name="member_type">
                <option value="">--- <?php echo __('Member Type','curriki'); ?> ---</option>
                <option value="professional" <?php
                if ($_POST['member_type'] == 'professional') {
                  echo 'selected="selected"';
                }
                ?>><?php echo __('Professional','curriki'); ?></option>
                <option value="student" <?php
                if ($_POST['member_type'] == 'student') {
                  echo 'selected="selected"';
                }
                ?>><?php echo __('Student','curriki'); ?></option>
                <option value="parent" <?php
                        if ($_POST['member_type'] == 'parent') {
                          echo 'selected="selected"';
                        }
                        ?>><?php echo __('Parent','curriki'); ?></option>
                <option value="teacher" <?php
                if ($_POST['member_type'] == 'teacher') {
                  echo 'selected="selected"';
                }
                ?>><?php echo __('Teacher','curriki'); ?></option>
                <option value="administration" <?php
                if ($_POST['member_type'] == 'administration') {
                  echo 'selected="selected"';
                }
                ?>><?php echo __('School/District Administrator','curriki'); ?></option>
                <option value="nonprofit" <?php
                if (isset($_POST['member_type']) and $_POST['member_type'] == 'nonprofit') {
                  echo 'selected="selected"';
                }
                ?>><?php echo __('Non-profit Organization','curriki'); ?></option>
              </select>
              <select name="country" id="country">
                <option value="US">United States</option>
      <?php
      global $wpdb;
      $q_countries = "SELECT * FROM countries ORDER BY displayname asc";
      $countries = $wpdb->get_results($q_countries, ARRAY_A);
      foreach ($countries as $country) {
        $selected = "";
        if (isset($_POST['country']) and $_POST['country'] == $country['country']) {
          $selected = "selected='selected'";
        }
        echo "<option value='" . $country['country'] . "' $selected>" . cur_convert_to_utf_to_html($country['displayname']) . "</option>";
      }
      ?>
              </select>
              <select id="state" name="state">
                <option value="US"> --- Select State --- </option>
      <?php
      global $wpdb;
      $q_states = "SELECT * FROM states ORDER BY state_name asc";
      $states = $wpdb->get_results($q_states, ARRAY_A);
      foreach ($states as $state) {
        $selected = "";
        if (isset($_POST['state']) and $_POST['state'] == $country['state']) {
          $selected = "selected='selected'";
        }
        echo "<option value='" . $state['state_name'] . "' $selected>" . $state['state_name'] . "</option>";
      }
      ?>
              </select>
              <input id="city" type="text" name="city" placeholder="<?php echo __('City','curriki'); ?>" />
              <input id="zipcode" type="text" name="zipcode" placeholder="<?php echo __('Zip/Postal Code','curriki'); ?>" />

              <fieldset class="filed-set-radio-cls">                                
                <input type="radio" name="gender" id="gender-male" value="male" checked="checked" /> <label for="gender-male"><?php echo __('Male','curriki'); ?></label>
                <input type="radio" name="gender" id="gender-female" value="female" /> <label for="gender-female"><?php echo __('Female','curriki'); ?></label>
              </fieldset>

              <div><?php echo __("By creating an account I agree to Curriki's",'curriki'); ?> <span style="text-decoration:underline; cursor:pointer;" onclick="window.location = '<?php echo get_permalink('1998'); ?>';"><?php echo __('Privacy Policy','curriki'); ?></span> <?php echo __('and','curriki'); ?> <span style="text-decoration:underline; cursor:pointer;" onclick="window.location = '<?php echo get_permalink('2005'); ?>';"><?php echo __('Terms of Service','curriki'); ?></span></div>
              <input type="submit" class="small-button green-button login" value="<?php echo __('Sign Up','curriki'); ?>">
              <input type="hidden" name="submit_source" id="submit_source" value="register" />
              <input type="hidden" name="is-registration-invitation" id="is-registration-invitation" value="1" />
              <a href="javascript:hideshowcenter('#signup-dialog', '#forgotpassword-dialog');"><?php echo __('Forgot Username or Password','curriki'); ?>?</a>

              <input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />
              <?php do_action( 'bp_after_registration_submit_buttons' ); wp_nonce_field( 'bp_new_signup' ); ?>

            </form>
          </div></div>    
      </div>
      <?php
    }

    register_curriki_modal_login_signup();
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        jQuery("#signup-dialog").show();
        jQuery("#signup-dialog").removeAttr("style");
        /*jQuery("#signup-dialog").css("width","200% !important");*/
        
    });
</script>
<style type="text/css">
    .site-inner .container_12
    {
        margin: 0 auto;
    }
    #content-sidebar-wrap
    {
        margin: 0 auto;
        width: 735px;
    }
    #signup-dialog
    {
        margin-top: 50px; 
        float: none;      
        display: inline-block !important;
        padding-bottom: 20px !important;
    }
    .modal-split, .close
    {
        display: none;
    }    
    
    .join-login-section
    {
        display: inherit !important;
        float: none !important;
        width: 100% !important;        
    }    
    .dialog_result_div
    {
        margin-bottom: 15px
    }
</style>
<?php
//die;
?>