<?php /*?>
<?php if(!empty($_POST) and !is_user_logged_in() and !is_admin()){echo '<div class="error">Sorry! credentials are not valid.</div>';}?>

<div class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="width: 100%">
    <h3 class="modal-title">Log in to Your Account</h3>
    <div class="join-login-section grid_5"><div class="signup-form">
            <form method="post" action="" id="loginform" name="loginform">
                <input type="text" name="log" placeholder="Username">
                <input type="password" name="pwd" placeholder="Password">
                <input type="submit" class="small-button green-button login" value="Log In">
                <a href="<?php echo bloginfo('url');?>/forgot-password">Forgot Username or Password?</a>
                <a href="#">Didn't work or on a school network?</a>
            </form>
        </div></div>
    <div class="modal-split grid_2">Or</div><div class="join-login-section grid_5">
        <div class="signup-oauth"><p>
            <?php do_action('oa_social_login'); ?>
            </p></div>
    </div>
    <div class="join-login-bottom rounded-borders-bottom"><a href="<?php echo bloginfo('url');?>/sign-up">Don't have an account? Join Now</a></div>
    <div class="close"><span class="fa fa-close"></span></div>
</div><?php */?>