<?php 
function wpssi_delete_options(){
        delete_option('wpsocialarrow-enable-plugin');
        delete_option('wpsocialarrow-enable-post');
        delete_option('wpsocialarrow-enable-page');
        delete_option('wpsocialarrow-enable-home');
        delete_option('wpsocialarrow-message-selection');
        delete_option('wpsocialarrow-positioning');
        delete_option('wpsocialarrow-alignment');
        delete_option('wpsocialarrow-facebook');
        delete_option('wpsocialarrow-twitter');
        delete_option('wpsocialarrow-google');
        delete_option('wpsocialarrow-linkedin');
        delete_option('wpsocialarrow-pinterest');
        delete_option('wpsocialarrow-skins');
}


?>