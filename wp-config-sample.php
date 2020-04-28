<?php
if(isset($_GET['testali'])){
    print_r($_SERVER);
    die();
}
# Multidomain settings
$subdomain = current(explode('.', $_SERVER['HTTP_HOST']));
$req_uri = explode('?', $_SERVER['REQUEST_URI']);
$page_name = current(explode('/', trim($req_uri[0], '/')));

define('WP_USE_THEMES', true);
define('SUBDOMAIN', in_array($subdomain, array('www', 'curriki', 'cg')) ? '' : $subdomain);
define('WP_HOME', ($_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://') . ($subdomain == 'curriki' ? 'www.' : '') . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', ($_SERVER['SERVER_PORT'] == 80 ? 'http://' : 'https://') . ($subdomain == 'curriki' ? 'www.' : '') . $_SERVER['HTTP_HOST']);

if (SUBDOMAIN == 'search' && empty($page_name)) {
    $_SERVER['REQUEST_URI'] = '/search' . (empty($req_uri[1]) ? '' : '?' . $req_uri[1]);
    $page_name = 'search-page';
}
elseif(SUBDOMAIN == 'students' && empty($page_name)){
    $_SERVER['REQUEST_URI'] = '/search' . (empty($req_uri[1]) ? '' : '?' . $req_uri[1]);
    $page_name = 'search-page';
}
elseif(SUBDOMAIN == 'studentsearch' && empty($page_name)){
    $_SERVER['REQUEST_URI'] = '/search' . (empty($req_uri[1]) ? '' : '?' . $req_uri[1]);
    $page_name = 'search-page';
}
if((SUBDOMAIN =='studentsearch' || SUBDOMAIN =='students') && ($page_name != "" && $page_name !="search" && $page_name != "oer" && $page_name != 'wp-admin')){
    $_SERVER['REQUEST_URI'] = '/search' . (empty($req_uri[1]) ? '' : '?' . $req_uri[1]);
    $page_name = 'search-page';
}
define('PAGENAME', $page_name);
define( 'WP_DEBUG', false);
define( 'WP_USE_EXT_MYSQL', 0);
# Search Page Configurations 
define( 'BP_SEARCH_SLUG', 'find' );


# Database Configuration
define( 'DB_NAME', 'database_name_here' );
define( 'DB_USER', 'username_here' );
define( 'DB_PASSWORD', 'password_here' );
define( 'DB_HOST', 'localhost' );
define( 'DB_HOST_SLAVE', 'localhost' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'cur_';

/** AWS REDSHIFT Credentials for Recomendation Engine Plugin ***/
define('REDSHIFT_HOST', '');
define('REDSHIFT_PORT', '');
define('REDSHIFT_DB', '');
define('REDSHIFT_USER', '');
define('REDSHIFT_PASSWORD', '');

/** Stripe Credentials for Donations ***/
define('STRIPE_SECRET_KEY', '');
define('STRIPE_PUBLISHABLE_KEY', '');
define('STRIPE_WEBHOOK_SECRET', '');
define('STRIPE_CURRENCY', 'usd');

# Security Salts, Keys, Etc
define('AUTH_KEY',         'r7!$C.{ZWzvNw7as[hw]D,ceO3pL[?K;D<.NwB0v$4}.w51(p2n|1b4Q:1`:s+xu');
define('SECURE_AUTH_KEY',  '3o5lnJLiLR=kahQp 13wqhA@GH|~V.[yV~v;IlxE9YyMu6M=tG]8JvN)BEc3GSM*');
define('LOGGED_IN_KEY',    'U/3Bg`xPQ&OafT1g(9DXQ5VZVC(unYKrD(K48?KW[:+qCEflgI9iPe)EiRuG0xR]');
define('NONCE_KEY',        'RD80<h:xfkJhi(cB?Dn3{gZ@0ynR=Ay%:!mOhuyQ:5qRgq?&cxO.-d X5$T@=l<i');
define('AUTH_SALT',        'ZOn_$y)GLqYzjG6l-`EY4y@|[QI5)Rn@i5NtAe-a+PHf@|--TI5+IE~EI4{a%5Bj');
define('SECURE_AUTH_SALT', '?{]Qd+y8z8&LSA|-=J5EuH+RXu.+u4tI,3*JDx)YS< R|}gOi*I6sSGc#y]aXHuw');
define('LOGGED_IN_SALT',   '*}r<S3?bz#[_$y:BHM,@Jc2(pP1Ao}AG@z=N=u-NDLSi~3r7*gis],*j4//xVmD-');
define('NONCE_SALT',       'T^nC7<_E_,]gWm3t+Ltg[nu6;m&GDB:?/7A*HFPX^b>OGEd%S#)+VcBSt+ZQ-ELC');
define('AES_KEY',          'xDl=&y|F hF@&/5R[[z+0q4h(4 4/p|4z*P6:Kf0>p|F1@3W5>1(!Efb+]f5vP1H');

/*### JWT Authentication for WP REST API START ###*/
define('JWT_AUTH_SECRET_KEY', '');
define('JWT_AUTH_CORS_ENABLE', true);
/*### JWT Authentication for WP REST API END ###*/

/* * * Amazon Web Services - Plugin ** */
define('DBI_AWS_ACCESS_KEY_ID', '');
define('DBI_AWS_SECRET_ACCESS_KEY', '');
define('DBI_AWS_REGION', '');
define('DBI_AWS_S3_BUCKET', '');
define('DBI_AWS_S3_CDN_URL', '');
define('DBI_AWS_TRANSCODE_PIPELINE_ID', '');

/*### AWS CLOUDSEARCH START ###*/
define('AWS_CLOUDSEARCH_END_POINT', '');
/*### AWS CLOUDSEARCH END ###*/

/*### GOOGLE RECAPTCHA START ###*/
define('GOOGLE_RECAPTCHA_SITE_KEY', '');
define('GOOGLE_RECAPTCHA_SECRET_KEY', '');
/*### GOOGLE RECAPTCHA END ###*/

if ($_GET['test']) {
    define('SAVEQUERIES', true);
}
# Localized Language Stuff

define('WP_USE_EXT_MYSQL', true);

//define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'curriki' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '' );

define( 'WPE_FOOTER_HTML', "" );

define( 'WPE_CLUSTER_ID', '' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '104.239.173.100' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/* SSLSTART */ if (isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'])
    $_SERVER['HTTPS'] = 'on'; /* SSLEND */

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'curriki.org', 1 => 'www.curriki.org', );

$wpe_varnish_servers=array ( 0 => '', );

$wpe_special_ips=array ( 0 => '', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/
define('WPLANG', '');
define('ICL_LANGUAGE_CODE', '');
define('ICL_LANGUAGE_NAME_EN', '');
$_SERVER['HTTP_X_FORWARDED_FOR'] = $_SERVER['REMOTE_ADDR'];

# WP Engine ID
# WP Engine Settings
# That's It. Pencils down
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');

$_wpe_preamble_path = null;
if (false) {
    
}

$_wpe_preamble_path = null; if(false){}

