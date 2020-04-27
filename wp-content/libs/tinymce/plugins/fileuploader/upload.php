<?php

//echo '{"status":"1","error":"","url":"https:\/\/archivecurrikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-360p.mp4","url_alt1":"https:\/\/archivecurrikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-360p.webm","poster":"https:\/\/archivecurrikicdn.s3-us-west-2.amazonaws.com\/posters\/55537700b227b-00001.png","filename":"Blaze_test1_WMVWMV9MP_CBR_320x240_AR4to3_15fps_512kbps_WMA92L2_32kbps_44100Hz_Mono","uniquename":"55537700b227b","folder":"videos\/","ext":"wmv","mime":"video\/x-ms-asf","time":1431533312,"url_alt2":"https:\/\/archivecurrikicdn.s3-us-west-2.amazonaws.com\/videos\/55537700b227b-MB1\/video.m3u8"}';
//exit;
include_once($_SERVER['DOCUMENT_ROOT'] . dirname(dirname(dirname(dirname($_SERVER['REQUEST_URI'])))) . '/functions.php');

$response = array();
validateUploadFile('file', $_REQUEST['type'], $response);
if ($response['status']) {
  uploadFileS3($response);
}

echo json_encode(getEmbedHTML($response));
exit;


/*

 * Creating Directories on s3bucket 

  if (!isset($vars['s3_client']))
  $vars['s3_client'] = $vars['aws']->get('S3');

  echo '<pre>';
  print_r($vars['s3_client']->putObject(array(
  'Bucket' => $vars['awsBucket'],
  'Key' => 'resourceswfs/',
  'Body' => ''
  )));

  print_r($vars['s3_client']->putObject(array(
  'Bucket' => $vars['awsBucket'],
  'Key' => 'resourcedocs/',
  'Body' => ''
  )));

 */


/* echo 'test';
  exit;
  error_reporting(0);

  $upload_folder = '/uploads/currikicdn/';
  $MaxImageSize = 5242880; //Bytes
  $MaxVideoSize = 500000000; //Bytes
  $MaxFileSize = 500000000;  //Bytes

  $ds = DIRECTORY_SEPARATOR;
  //$sub_dir = ;
  $wp_contents = $_SERVER['DOCUMENT_ROOT'] . $sub_dir;
  $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
  $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder

  $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Allowed Extensions Images
  $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Allowed Extensions Videos
  $ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Allowed Extensions Files

  $bucket = 'archivecurrikicdn';
  $pipelineID = '1421535183552-zbqh2y';
  $cdn_url = 'https://archivecurrikicdn.s3-us-west-2.amazonaws.com/';

  $folders = array(
  'sourceVideos' => 'sourcevideos/',
  'videos' => 'videos/',
  'posters' => 'posters/',
  'resourceImgs' => 'resourceimgs/',
  'resourceFiles' => 'resourcefiles/',
  'linkImages' => 'linkimages/'
  );

  require_once $wp_contents . '/libs/aws_sdk/aws-autoloader.php';

  use Aws\Common\Aws;

  $aws = Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
  $s3_client = $aws->get('S3');

  /* foreach ($folders as $index => $key) {
  $add_folder = $s3_client->putObject(array(
  'Bucket' => $bucket,
  'Key' => $key,
  'Body' => ''
  ));
  echo '<pre>';
  print_r($add_folder);
  echo '</pre>';
  }
  exit; */

if (!empty($_FILES)) {
  $uniquename = uniqid();
  $tempFile = $_FILES['file']['tmp_name'];
  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $filename = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($_FILES['file']['name'], PATHINFO_FILENAME)));

  /* Validating */
  switch ($_REQUEST['type']) {
    case 'image':
      if (!in_array($ext, $ext_img)) {
        $response['error'] = 'Error : File type not supported';
      } elseif ($_FILES['file']['size'] > $MaxImageSize) {
        $response['error'] = 'Error : Too much large file';
      } else {
        $targetFolder = $folders['resourceImgs'];
      }
      break;
    case 'video':
      if (!in_array($ext, $ext_video)) {
        $response['error'] = 'Error : File type not supported';
      } elseif ($_FILES['file']['size'] > $MaxVideoSize) {
        $response['error'] = 'Error : Too much large file';
      } else {
        $targetFolder = $folders['sourceVideos'];
      }
      break;
    case 'file':
      if (!in_array($ext, $ext_file)) {
        $response['error'] = 'Error : File type not supported';
      } elseif ($_FILES['file']['size'] > $MaxFileSize) {
        $response['error'] = 'Error : Too much large file';
      } else {
        $targetFolder = $folders['resourceFiles'];
      }
      break;
  }
  /* Uploading */
  if ($targetFolder) {
    $targetFile = $current_path . $targetFolder . $uniquename . '.' . $ext;
    move_uploaded_file($tempFile, $targetFile);

    if (file_exists($targetFile)) {
      $upload = $s3_client->putObject(array(
                  'ACL' => 'public-read',
                  'Bucket' => $bucket,
                  'Key' => $targetFolder . $uniquename . '.' . $ext,
                  'Body' => fopen($targetFile, 'r+')
              ))->toArray();

      /* Video Transcoding */
      if ($_REQUEST['type'] == 'video') {
        $et_client = $aws->get('ElasticTranscoder');
        $transcodeJob = $et_client->createJob(array(
            'PipelineId' => $pipelineID,
            'Input' => array(
                'Key' => $folders['sourceVideos'] . $uniquename . '.' . $ext,
            ),
            'Outputs' => array(
                array(
                    'Key' => $folders['videos'] . $uniquename . '-360p.mp4',
                    'ThumbnailPattern' => $folders['posters'] . $uniquename . '-{count}',
                    'PresetId' => '1351620000001-000040', //MP4
                ),
                array(
                    'Key' => $folders['videos'] . $uniquename . '-320x240.mp4',
                    'ThumbnailPattern' => $folders['posters'] . $uniquename . '-{count}',
                    'PresetId' => '1351620000001-000061', //MP4
                ),
                array(
                    'Key' => $folders['videos'] . $uniquename . '-360p.webm',
                    'ThumbnailPattern' => $folders['posters'] . $uniquename . '-{count}',
                    'PresetId' => '1421855116216-y3xpr1', //webm
                ),
            ),
        ));

        $existResult = $s3_client->waitUntilObjectExists(array(
            'Bucket' => $bucket,
            'Key' => $folders['videos'] . $uniquename . '-360p.mp4',
        ));
      }

      /* Preparing results */
      if ($upload['ObjectURL']) {
        $response['error'] = '';
        $response['status'] = '1';
        $response['url'] = $upload['ObjectURL'];
        $response['filename'] = $filename . '.' . $ext;
        $response['uniquename'] = $uniquename . '.' . $ext;
        $response['ext'] = $ext;
        $response['folder'] = $targetFolder;

        if ($_REQUEST['type'] == 'video') {
          $response['url'] = $cdn_url . $folders['videos'] . $uniquename . '-320x240.mp4';
          //$response['url_alt1'] = $cdn_url . $folders['videos'] . $uniquename . '-360p.webm';
          $response['poster'] = $cdn_url . $folders['posters'] . $uniquename . '-00001.png';
        }
      } else
        $response['error'] = 'System Error, Try again later';
    } else
      $response['error'] = 'System Error, Try again later';
  }
} else
  $response['error'] = 'System Error, Try again later';

echo json_encode($response);
exit;
?>
