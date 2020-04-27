<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * export JAVA_HOME=/opt/jre1.8.0_25
 * export CS_HOME=/srv/www/cg.curriki.org/public_html/curriki/wp-content/libs/cloud_search/csconsole
 * . $CS_HOME/bin/cs-import-documents -c $CS_HOME/account-credentials --source $1 --output $2
 */
set_time_limit(-1);
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('UTC');
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/ ');
include_once(__DIR__ . '/../functions.php');

echo date('Y-m-d H:i:s');
echo '<br/>';

if (isset($_REQUEST['delete'])) {
  echo $xmlFile = awsPrepareXmlDel();
  print_array(awsCloudSearchUpload($xmlFile));
}

if (isset($_REQUEST['SDFConvert'])) {
  print_array(convertSdfFiles());
}

if (isset($_REQUEST['upload'])) {
  awsPrepareXmlUp($_REQUEST['type'], $_REQUEST['limit']);
}

if (isset($_REQUEST['synch'])) {
  synchIndexing($_REQUEST['type']);
}

if (isset($_REQUEST['transcode'])) {
  $resources = $db->select("SELECT rf.*
              from resources r
              inner join resourcefiles rf on r.resourceid = rf.resourceid
              where mediatype in ( 'collection','video','mixed','audio')
              and r.active = 'T'
              and rf.active = 'T'
              and rf.transcoded = 'F' 
              limit 1
              /*and rf.resourceid = '23777'*/");
  foreach ($resources as $r) {

    $response = array(
        'status' => '1', //0 if uploading or validation is halted or not successfull otherwise 1
        'error' => '', //Error message why it is failed
        'url' => $vars['base_url'], //S3 bucket Object URL for uplaoded file
        'url_alt1' => '', //Aleternative resource for file
        'poster' => '', //Poster of resource for file
        'filename' => pathinfo($r['filename'], PATHINFO_FILENAME),
        'uniquename' => pathinfo($r['uniquename'], PATHINFO_FILENAME),
        'folder' => $vars['resSourceVideos'], //Folder name of file on S3 as well as /wp-contents/uploads folder
        'bucket' => $vars['awsBucket'],
        'ext' => pathinfo($r['uniquename'], PATHINFO_EXTENSION),
        'type' => 'video',
        'transcoded' => 'T',
        'SDFstatus' => 'Source has an unsupported content-type',
        'html' => '<p>Error: File Not Exists</p>',
        'time' => time());

    if (in_array(strtolower($response['ext']), explode(',', $vars['resAllowedVideo']))) {
      print_r($response);
      print_r(videoTranscode($response, 1));
    }
    $db->update('resourcefiles', array('transcoded' => $db->mySQLSafe('T')), 'fileid = ' . $r['fileid']);
  }
}
?>

<head>
  <meta http-equiv="refresh" content="0.1">
</head>