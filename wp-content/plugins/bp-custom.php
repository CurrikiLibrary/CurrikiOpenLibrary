<?php
// curriki buddypress related modifications will go here

$req_uri = $_SERVER['REQUEST_URI'];
$uri_arr = explode("/", $_SERVER['REQUEST_URI']);
$uri_arr_size = sizeof($uri_arr) - 1;
$curr_permalink = $uri_arr[$uri_arr_size];
if($curr_permalink == '')$curr_permalink = $uri_arr[sizeof($uri_arr) - 2];

if ($curr_permalink == 'library') {
    define('BP_LIBRARY_SLUG', 'library');
    locate_template( array( 'members/single/library.php' ), true );
}
function remove_public_message_button() {
remove_filter( 'bp_member_header_actions','bp_send_public_message_button', 20);

}
add_action( 'bp_member_header_actions', 'remove_public_message_button' );
