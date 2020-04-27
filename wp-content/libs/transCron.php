<pre>
  <?php
  set_time_limit(-1);
  require_once dirname(__FILE__) . '/functions.php';


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
  ?>

<head>
  <meta http-equiv="refresh" content="3; ,URL=&t=<?php echo time(); ?>">
</head>
