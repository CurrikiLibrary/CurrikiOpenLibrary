<?php /*?>
<?php 
if(!empty($_POST['curriki_errors'])){
    echo "<ul>";
    foreach($_POST['curriki_errors'] as $error){
        echo '<li>'.$error.'</li>';
    }
    echo "</ul>";
}?>
<?php global $wpdb;?>
<div class="join-oauth-modal modal border-grey rounded-borders-full grid_8" style="width: 100%">
    <h3 class="modal-title">Sign Up</h3>
    <div class="join-login-section grid_5"><div class="signup-form">
            <form method="post" action="" id="signupform" name="signupform">
                <input name="signup" value="yes" type="hidden" />
                <input type="text" name="username" placeholder="Username" value="<?php echo $_POST['username']?>">
                <input type="text" name="email" placeholder="Email" value="<?php echo $_POST['email']?>">
                <input type="password" name="pwd" placeholder="Password">
                <input type="password" name="confirm_pwd" placeholder="Confirm Password">
                <select name="member_type">
                    <option value="">Member Type</option>
                    <option value="professional" <?php if($_POST['member_type'] == 'professional'){echo 'selected="selected"';}?>>Professional</option>
                    <option value="student" <?php if($_POST['member_type'] == 'student'){echo 'selected="selected"';}?>>Student</option>
                    <option value="parent" <?php if($_POST['member_type'] == 'parent'){echo 'selected="selected"';}?>>Parent</option>
                    <option value="teacher" <?php if($_POST['member_type'] == 'teacher'){echo 'selected="selected"';}?>>Teacher</option>
                    <option value="administration" <?php if($_POST['member_type'] == 'administration'){echo 'selected="selected"';}?>>School/District Administrator</option>
                    <option value="nonprofit" <?php if($_POST['member_type'] == 'nonprofit'){echo 'selected="selected"';}?>>Non-profit Organization</option>
                </select>
                <select name="country">
                    <option value="US">United States</option>
                    <?php 
                    $q_countries = "SELECT * FROM countries ORDER BY displayname asc";
                    $countries = $wpdb->get_results($q_countries, ARRAY_A);
                    foreach($countries as $country){
                        $selected = "";
                        if($_POST['country'] == $country['country']){
                            $selected = "selected='selected'";
                        }
                        echo "<option value='".$country['country']."' $selected>".$country['displayname']."</option>";
                    }?>
                </select>
                <input type="submit" class="small-button green-button login" value="Sign Up">
                <a href="<?php echo bloginfo('url');?>/forgot-password">Forgot Username or Password?</a>
                <a href="#">Didn't work or on a school network?</a>
            </form>
        </div></div>
    <div class="modal-split grid_2">Or</div><div class="join-login-section grid_5">
        <div class="signup-oauth"><p>
            <?php do_action('oa_social_login'); ?>
            </p></div>
    </div>
    <div class="join-login-bottom rounded-borders-bottom"></div>
    <div class="close"><span class="fa fa-close"></span></div>
</div><?php */?>