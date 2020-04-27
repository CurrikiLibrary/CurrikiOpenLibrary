<?php
/*
Plugin Name: Curriki Cusomized
Plugin URI: http://www.oneall.com/
Description: Curriki functions which are needed to be run before the template loads.
Version: 1.0
Author: Sajid
Author URI: http://www.curriki.com/
Email: sajidtech@outlook.com
*/

if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        curriki_signup_mail($_POST['email']);
    }
}
function curriki_signup_mail($email = "sajid.curriki@nxvt.com"){
    $body = "    Hi there!
<br /><br />
    You are the newest member of Curriki, a community that offers a curated collection of “living” curricula that lets you combine and customize free, high-quality K-12 resources so you can save time and perform at your best.  We’re excited to have you as a member and can’t wait to see what you contribute to the library of educational materials.
<br /><br />
    You have selected ".$_POST['username']." as your member login. Start enjoying Curriki today by <a href='".get_bloginfo('url')."/?modal=login'>logging in</a>.
<br /><br />
    A few tips to get the most out of Curriki:
<br /><br />
             If you haven’t already, <a href='".get_bloginfo('url')."/?modal=newsletter'>sign up for the newsletter</a> for a monthly alert for the newest and most popular timely content. And subscribe to the <a href='".get_bloginfo('url')."/blog/'>Curriki blog</a> for more frequent resource recommendations, insights and tips.
<br /><br />
             In addition to searching for resources by keyword, subject or standard, with your signed in account you can upload resources, build collections of resources that you can share with students or other teachers. 
<br /><br />
       We're constantly working on improving Curriki and we need your input, so visit often and send us lots of feedback!
<br /><br />
    Thank you for creating a Curriki account. You’re all ready to go!
<br /><br />
    Janet and the Curriki Team
<br /><br />
Follow Curriki: <a href='https://www.facebook.com/pages/Curriki/134427817464'>Facebook</a>   <a href='https://twitter.com/curriki'>Twitter</a>   <a href='https://www.pinterest.com/curriki/'>Pinterest</a>";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email, "You’re our newest member!", $body, $headers);
    //wp_mail('sajid.curriki@nxvt.com', "You’re our newest member!", $body, $headers);
}