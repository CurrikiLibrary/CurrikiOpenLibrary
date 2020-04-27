<?php
/*
* CHILD SETUP FUNCTIONS
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*
* Don't delete or edit this file unless you know
* what you're doing. If you mess something up in
* here, you'll break the theme.
*/


/************* SCRIPTS & ENQEUEING *************/


// loading modernizr and jquery, and reply script
function curriki_scripts_and_styles() {

   	// register our stylesheet
	wp_register_style( 'curriki-stylesheet', get_stylesheet_directory_uri() . '/style.css', array(), '', 'all' );

    // Font Awesome http://fortawesome.github.io/Font-Awesome
    wp_register_style( 'fontawesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' , array(), '4.3.0', 'all' );
    wp_enqueue_style( 'fontawesome' );
	
    // adding scripts file in the footer
    wp_register_script( 'curriki-js', get_stylesheet_directory_uri() . '/js/scripts.min.js', array( 'jquery' ), '', true );
    wp_register_script( 'curriki-sidr', get_stylesheet_directory_uri() . '/js/jquery.sidr.min.js', array( 'jquery' ), '', true );

    // now let's enqueue the scripts and styles into the wp_head function.
    wp_enqueue_style( 'curriki-stylesheet' );
    wp_enqueue_script( 'curriki-js' );
	wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'curriki-sidr' );

    // deregister the superfish scripts
    wp_deregister_script( 'superfish' );
    wp_deregister_script( 'superfish-args' );

} /* end scripts and styles function */

function curriki_typekit_load() {
    echo '<script src="//use.typekit.net/krr8zdy.js"></script>';
    echo '<script>try{Typekit.load();}catch(e){}</script>';
}


/************* OTHER SETUP FUNCTIONS *************/

/*
Fix for, if you name your child theme something that already exists in the
wordpress repo, then you may get an alert offering a "theme update"
for a theme that's not even yours.

credit: Mark Jaquith
http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
*/
function curriki_dont_update( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

?>