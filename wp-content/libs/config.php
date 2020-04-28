<?php

//***************** Preparing Amazone Domain variables ******************//
require_once dirname(__FILE__) . '/aws_sdk/aws-autoloader.php';
require_once dirname(__FILE__) . '/db.php';

require_once( dirname(dirname(dirname(__FILE__))) . '/wp-load.php' );
use Aws\Common\Aws;

global $vars, $db;
$vars = array();

if ($_SERVER['HTTP_HOST'] == 'cg.curriki.org') {
  $db_host = 'localhost';
  $db_user = 'root';
  $db_pass = 'currikimonitor!';
  $db_name = 'curriki4';
  $vars['wp_contents'] = '/curriki/wp-content';
} else if ($_SERVER['HTTP_HOST'] == 'localhost') {
  $db_host = 'localhost';
  $db_user = 'root';
  $db_pass = 'password';
  $db_name = 'curriki3';
  $vars['wp_contents'] = '/curriki/wp-content';
} else {
  $db_host = DB_HOST;
  $db_user = DB_USER;
  $db_pass = DB_PASSWORD;
  $db_name = DB_NAME;
  $vars['wp_contents'] = '/wp-content';
}

// $db = new db($db_host, $db_user, $db_pass, $db_name);

// $result = $db->select("SELECT * from cur_options where option_name in ('resSourceVideos' ,'resResourceVideos', 'resResourcePosters',
// 'resResourceSwfs','resResourceImgs','resResourceDocs','resResourceFiles' , 
// 'resLinkImages','awsCDNUrl','awsTransCodePipeLine','awsBucket','resUploadFolder',
// 'resMaxSWFSize','resMaxImageSize' , 'resMaxVideoSize', 'resMaxDocSize','resMaxFileSize',
// 'resAllowedImg','resAllowedVideo','resAllowedDocs','siteurl','home','resFiles','awsWorkBucket','awsSearchDomain','awsSearchEndPoint')");


// foreach ($result as $r) {
//   $vars[$r['option_name']] = $r['option_value'];
// }

$vars = [
  'awsBucket'=>DBI_AWS_S3_BUCKET,
  'awsCDNUrl'=>'https://' . DBI_AWS_S3_CDN_URL . '/',
  'awsTransCodePipeLine'=>DBI_AWS_TRANSCODE_PIPELINE_ID,
  'home'=>'http://localhost/curriki/',
  'resAllowedDocs'=>'doc,docx,pdf,xls,xlsx,txt,csv,html,psd,sql,log,fla,xml,ade,adp,ppt,pptx',
  'resAllowedImg'=>'jpg,jpeg,pjpeg,png,gif,bmp,tiff,tif',
  'resAllowedVideo'=>'mov,mpeg,mp4,avi,mpg,wma,flv,wmv',
  'resLinkImages'=>'linkimages/',
  'resMaxFileSize'=>500000000,
  'resMaxImageSize'=>5242880,
  'resMaxVideoSize'=>500000000,
  'resResourcePosters'=>'posters/',
  'resResourceFiles'=>'resourcefiles/',
  'resResourceImgs'=>'resourceimgs/',
  'resSourceVideos'=>'sourcevideos/',
  'resUploadFolder'=>'/uploads/currikicdn/',
  'resResourceVideos'=>'videos/',
  'siteurl'=>'http://localhost/curriki/',
];

$vars['base_url'] = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $vars['wp_contents'];
$vars['current_path'] = $_SERVER['DOCUMENT_ROOT'] . $vars['wp_contents']; // relative path from filemanager folder to upload files folder
$vars['upload_path'] = $vars['current_path'] . $vars['resUploadFolder']; // relative path from filemanager folder to upload files folder
$vars['aws'] = Aws::factory($vars['current_path'] . '/libs/aws_sdk/config.php');
unset($result);
unset($r);
extract($vars);

/*[awsBucket] => currikicdn
  [awsCDNUrl] => https://archivecurrikicdn.s3-us-west-2.amazonaws.com/
  [awsTransCodePipeLine] => 1421535183552-zbqh2y
  [home] => http://localhost/curriki/
  [resAllowedDocs] => doc,docx,pdf,xls,xlsx,txt,csv,html,psd,sql,log,fla,xml,ade,adp,ppt,pptx
  [resAllowedImg] => jpg,jpeg,pjpeg,png,gif,bmp,tiff,tif
  [resAllowedVideo] => mov,mpeg,mp4,avi,mpg,wma,flv,wmv
  [resLinkImages] => linkimages/
  [resMaxFileSize] => 500000000
  [resMaxImageSize] => 5242880
  [resMaxVideoSize] => 500000000
  [resResourcePosters] => posters/
  [resResourceFiles] => resourcefiles/
  [resResourceImgs] => resourceimgs/
  [resSourceVideos] => sourcevideos/
  [resUploadFolder] => /uploads/currikicdn/
  [resResourceVideos] => videos/
  [siteurl] => http://localhost/curriki/
 * 
 */


//$s3_client = $aws->get('S3');