<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//error_reporting(0);
//ini_set('display_errors', 0);

if (isset($_REQUEST['test']) && !function_exists('Shutdown_Handler')) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    ini_set('display_errors', 1);

    function Shutdown_Handler() {
        // getting the last occured errors
        $Last_Error = error_get_last();
        //if ($Last_Error['type'] && !in_array(intval($Last_Error['type']), array(8)))
        {
            // E_ERROR means Fatal Error
            $msg = "------------------------------------------<br />";
            $msg .= "Error Occured in Your Code<br />";
            $msg .= "-----------------------------------------<br />";
            $msg .= "\n[Type]->" . $Last_Error['type'] . "<br />";
            $msg .= "\n[File]->" . $Last_Error['file'] . "<br />";
            $msg .= "\n[MSG ]->" . $Last_Error['message'] . "<br />";
            $msg .= "\n[Line]->" . $Last_Error['line'] . "<br />";
            $msg .= "-----------------------------------<br />";
            $msg .= "Back Trace is as follows<br />";
            $msg .= "-----------------------------------<br />";

            echo "<pre>";
            print($msg);
            echo "</pre>";
        }
    }

    register_shutdown_function('Shutdown_Handler');
}

//**************Helper Functions ***************//
function print_array($arr = array(), $ex = 0) {
    echo '<pre>--Print Array Start--<br/>';
    print_r($arr);
    echo '<br/>---Print Array End---</pre>';
    if ($ex)
        exit;
}

function print_xml($xml, $ex = 0) {
    echo '<code>--Print XML Start--<br/>';
    echo htmlentities($xml);
    echo '<br/>---Print XML End---</code>';
    if ($ex)
        exit;
}

include_once(__DIR__ . '/config.php');

//* * **************Start Uploading Process File and amazon S3 and Video transcoding *************** */

function deleteFileS3($file) {
    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');

    $dir = pathinfo($file['uniquename'], PATHINFO_FILENAME);
    if (file_exists($vars['upload_path'] . $file['folder'] . $file['uniquename'])) {
        unlink($vars['upload_path'] . $file['folder'] . $file['uniquename']);
    }
    if (file_exists($vars['upload_path'] . $file['folder'] . $dir)) {
        delete_directory($vars['upload_path'] . $file['folder'] . $dir);
    }

    if ($vars['s3_client']->doesObjectExist($vars['awsBucket'], $file['folder'] . $file['uniquename'])) {
        try {
            $delete = $vars['s3_client']->deleteObject(array(
                'Bucket' => $vars['awsBucket'],
                'Key' => $file['folder'] . $file['uniquename']
            ));
        } catch (Exception $ex) {
            
        }
    }

    if ($vars['s3_client']->doesObjectExist($vars['awsBucket'], $file['folder'] . $dir)) {
        try {
            $delete = $vars['s3_client']->deleteObject(array(
                'Bucket' => $vars['awsBucket'],
                'Key' => $file['folder'] . $dir
            ));
        } catch (Exception $ex) {
            
        }
    }

    if ($vars['s3_client']->doesObjectExist($vars['awsWorkBucket'], $file['folder'] . $file['uniquename'])) {
        try {
            $delete = $vars['s3_client']->deleteObject(array(
                'Bucket' => $vars['awsWorkBuckets'],
                'Key' => $file['folder'] . $file['uniquename']
            ));
        } catch (Exception $ex) {
            
        }
    }
}

function delete_directory($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    delete_directory($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function uploadLodeStarS3($directory) {
    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');

    if (isset($directory['lodestar']) && !empty($directory['lodestar']) && file_exists($vars['upload_path'] . $directory['folder'] . $directory['uniquename'])) {
        $dir = pathinfo($directory['uniquename'], PATHINFO_FILENAME);
        delete_directory($vars['upload_path'] . $directory['folder'] . $dir);

        $zip = new ZipArchive;
        if ($zip->open($vars['upload_path'] . $directory['folder'] . $directory['uniquename']) === TRUE) {
            $zip->extractTo($vars['upload_path'] . $directory['folder'] . $dir);
            $zip->close();

            $fi = new FilesystemIterator($vars['upload_path'] . $directory['folder'] . $dir . '/', FilesystemIterator::SKIP_DOTS);
            if (iterator_count($fi) == 1) {
                $fi->rewind();
                $dir2 = $fi->getFilename();
                if ($dir2 != $directory['lodestar']) {
                    $oldfolder = $vars['upload_path'] . $directory['folder'] . $dir . "/" . $dir2;
                    $newfolder = $vars['upload_path'] . $directory['folder'] . $dir;
                    rename($oldfolder, $newfolder . '_temp');
                    delete_directory($oldfolder);
                    rename($newfolder . '_temp', $newfolder);
                }
            }

            $vars['s3_client']->uploadDirectory($vars['upload_path'] . $directory['folder'] . $dir, $vars['awsBucket'], $directory['folder'] . $dir);
        }
        //
    }
}

/**
 * @param type $name : will needed to get file fron $_FILES resouce
 * @param type $type : Needed tod determine what kind of file validation is needed. Currently Image, Video and File is accepted
 * @param type $response : Will carry values from passed variable and change by reference. If response is not set then will generate byself
 */
function validateUploadFile($name = 'file', $type = 'file', &$response) {
    global $vars;
    if (empty($response))
        $response = array(
            'status' => '0', //0 if uploading or validation is halted or not successfull otherwise 1
            'error' => '', //Error message why it is failed
            'url' => $vars['base_url'], //S3 bucket Object URL for uplaoded file
            'url_alt1' => '', //Aleternative resource for file
            'poster' => '', //Poster of resource for file
            'filename' => '', //Original file name of uploaded file. it will be used only to show user
            'uniquename' => '', //File will be savedo on /wp-contents/uploads folder as well as on S3 with this name
            'folder' => '', //Folder name of file on S3 as well as /wp-contents/uploads folder
            'bucket' => $vars['awsBucket'],
            'ext' => '', //Extenssion of uploaded file
            'type' => $type,
            'transcoded' => 'T',
            'SDFstatus' => 'Source has an unsupported content-type',
            'html' => '<p>Error: File Not Exists</p>',
            'time' => time());

    if (!isset($_FILES[$name]) OR empty($_FILES)) {
        var_dump($_FILES);
        die();
        $response['error'] = 'Error: File Not Exists';
    } elseif ($_FILES[$name]['error'] != UPLOAD_ERR_OK) {
        $response['error'] = codeToMessage($_FILES[$name]['error']);
    } else {
        $response['ext'] = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
        $response['filename'] = preg_replace("/[^a-zA-Z0-9\-\._]/", "", pathinfo($_FILES[$name]['name'], PATHINFO_FILENAME));
        $response['uniquename'] = uniqid();

        if (strtolower($response['ext']) == 'swf' && $_FILES[$name]['size'] < $vars['resMaxSWFSize']) {
            $response['type'] = 'swf';
            $response['folder'] = $vars['resResourceSwfs'];
        } elseif (in_array(strtolower($response['ext']), explode(',', $vars['resAllowedImg'])) && $_FILES[$name]['size'] < $vars['resMaxImageSize']) {
            $response['type'] = 'image';
            $response['folder'] = $vars['resResourceImgs'];
        } elseif (in_array(strtolower($response['ext']), explode(',', $vars['resAllowedVideo'])) && $_FILES[$name]['size'] < $vars['resMaxVideoSize']) {
            $response['type'] = 'video';
            $response['folder'] = $vars['resSourceVideos'];
            $response['bucket'] = $vars['awsWorkBucket'];
            $response['transcoded'] = 'F';
        } elseif (in_array(strtolower($response['ext']), explode(',', $vars['resAllowedDocs'])) && $_FILES[$name]['size'] < $vars['resMaxDocSize']) {
            $response['type'] = 'document';
            $response['SDFstatus'] = '';
            $response['folder'] = $vars['resResourceDocs'];
        } else if ($_FILES[$name]['size'] < $vars['resMaxFileSize']) {
            $response['type'] = 'file';
            $response['SDFstatus'] = '';
            $response['folder'] = $vars['resResourceFiles'];
        } else {
            $response['error'] = 'Error : Too much large file';
        }

        if (!$response['error']) {
            /* Uploading */
            $targetFile = $vars['upload_path'] . $response['folder'] . $response['uniquename'] . '.' . $response['ext'];
            if ( ! is_dir($vars['upload_path'] . $response['folder'])) {
                mkdir($vars['upload_path'] . $response['folder'], 0777, true);
            }
            
            move_uploaded_file($_FILES[$name]['tmp_name'], $targetFile);

            /* checking if uploaded successfully */
            if (file_exists($targetFile)) {
                $response['status'] = 1;
                $response['error'] = 'Success: File Uploaded Successfully.';
                $response['url'] = $vars['base_url'] . $vars['resUploadFolder'] . $response['folder'] . $response['uniquename'] . '.' . $response['ext'];
            } else {
                $response['status'] = 0;
                $response['error'] = 'Error: System Error. Please Try Again Later.';
            }
        }
    }

    return $response;
}

/**
 * 
 * @param type $response
 */
function uploadFileS3(&$response) {
    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');
    $targetFile = $vars['upload_path'] . $response['folder'] . $response['uniquename'] . '.' . $response['ext'];

    if (file_exists($targetFile)) {
        $upload = $vars['s3_client']->putObject(array(
                    'ACL' => 'public-read',
                    'Bucket' => $response['bucket'],
                    'Key' => $response['folder'] . $response['uniquename'] . '.' . $response['ext'],
                    'Body' => fopen($targetFile, 'r.')
                ))->toArray();

        if ($upload['ObjectURL']) {
            $response['error'] = '';
            $response['status'] = '1';
            $response['url'] = $upload['ObjectURL'];

            if ($response['folder'] == $vars['resSourceVideos']) {
                videoTranscode($response);
                $response['folder'] = $vars['resResourceVideos'];
                $response['url'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '-480p.mp4';
                //$response['url_alt1'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '-360p.webm';
                $response['poster'] = $vars['awsCDNUrl'] . $vars['resResourcePosters'] . $response['uniquename'] . '-00001.png';
                $response['transcoded'] = 'T';
            }
            $response['uniquename'] .='.' . $response['ext'];
        } else {
            $response['status'] = '0';
            $response['error'] = 'Error: File not uploaded correctly to bucket.';
        }
    } else {
        $response['status'] = '0';
        $response['error'] = 'Error: File does not exist.';
    }
}

/**
 * 
 * @param type $response
 */
function videoTranscode(&$response, $wait = 1) {
    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');
    if (!isset($vars['et_client']))
        $vars['et_client'] = $vars['aws']->get('ElasticTranscoder');
    /* $transcodeJob = $vars['et_client']->createJob(array(
      'PipelineId' => $vars['awsTransCodePipeLine'],
      'Input' => array(
      'Key' => $vars['resSourceVideos'] . $response['uniquename'] . '.' . $response['ext'],
      ),
      'Outputs' => array(
      array(
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-360p.mp4',
      'ThumbnailPattern' => $vars['resResourcePosters'] . $response['uniquename'] . '-{count}',
      'PresetId' => '1351620000001-000040', //MP4
      ),
      array(
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-320x240.mp4',
      'ThumbnailPattern' => $vars['resResourcePosters'] . $response['uniquename'] . '-{count}',
      'PresetId' => '1351620000001-000061', //MP4
      ),
      array(
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-360p.webm',
      'ThumbnailPattern' => $vars['resResourcePosters'] . $response['uniquename'] . '-{count}',
      'PresetId' => '1421855116216-y3xpr1', //webm
      ),
      array(
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-MB2/video',
      'PresetId' => '1351620000001-200010', //HLS v3 (Apple HTTP Live Streaming), 2 megabits/second  1351620000001-200010
      'SegmentDuration' => '1',
      ),
      array(
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-MB1/video',
      'PresetId' => '1351620000001-200030', //HLS v3 (Apple HTTP Live Streaming), 1 megabit/second  1351620000001-200030
      'SegmentDuration' => '1',
      ),
      ),
      ));

      if ($wait) {
      $existResult = $vars['s3_client']->waitUntilObjectExists(array(
      'Bucket' => $vars['awsBucket'],
      'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-360p.mp4',
      ));

     */

    $transcodeJob = $vars['et_client']->createJob(array(
        'PipelineId' => $vars['awsTransCodePipeLine'],
        'Input' => array(
            'Key' => $vars['resSourceVideos'] . $response['uniquename'] . '.' . $response['ext'],
        ),
        'Outputs' => array(
            array(
                'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-480p.mp4',
                'ThumbnailPattern' => $vars['resResourcePosters'] . $response['uniquename'] . '-{count}',
                'PresetId' => '1351620000001-000040', //MP4
            ),
            array(
                'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-KB400/video',
                'PresetId' => '1351620000001-200050', //HLS v3 (Apple HTTP Live Streaming), 400 kilobits/second 1351620000001-200050
                'SegmentDuration' => '10',
            ),
            array(
                'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-MB1/video',
                'PresetId' => '1351620000001-200030', //HLS v3 (Apple HTTP Live Streaming), 1 megabit/second  1351620000001-200030
                'SegmentDuration' => '10',
            ),
            array(
                'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-MB2/video',
                'PresetId' => '1351620000001-200010', //HLS v3 (Apple HTTP Live Streaming), 2 megabits/second  1351620000001-200010
                'SegmentDuration' => '10',
            ),
        ),
        'Playlists' => array(
            array(
                'Name' => $vars['resResourceVideos'] . $response['uniquename'],
                'Format' => 'HLSv3',
                'OutputKeys' => array(
                    $vars['resResourceVideos'] . $response['uniquename'] . '-KB400/video',
                    $vars['resResourceVideos'] . $response['uniquename'] . '-MB1/video',
                    $vars['resResourceVideos'] . $response['uniquename'] . '-MB2/video',
                ),
            ),
        ),
    ));

    //print_r($transcodeJob);
    $response['url'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '-480p.mp4';
    $response['poster'] = $vars['awsCDNUrl'] . $vars['resResourcePosters'] . $response['uniquename'] . '-00001.png';
    $response['url_alt1'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '.m3u8';
    //$response['url_alt2'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '-MB1/video.m3u8';
    //$response['url_alt3'] = $vars['awsCDNUrl'] . $vars['resResourceVideos'] . $response['uniquename'] . '-MB2/video.m3u8';

    if ($wait) {
        try {
            $existResult = $vars['s3_client']->waitUntilObjectExists(array(
                'Bucket' => $vars['awsBucket'],
                'Key' => $vars['resResourceVideos'] . $response['uniquename'] . '-480p.mp4',
            ));
        } catch (Exception $ex) {
            //echo 'Exception Handled';
        }
    }
    return $transcodeJob;
}

function AWSS3ObjExist($bucket, $key) {
    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');
    $existResult = $vars['s3_client']->waitUntilObjectExists(array(
        'Bucket' => $bucket,
        'Key' => $key,
    ));
    return $existResult;
}

function getEmbedHTML(&$response) {
    global $vars;
    $randid = uniqid();
    switch ($response['type']) {
        case 'external':
            $width = 450;
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
//            $response['html'] .= '<a href="' . $response['url_alt1'] . '" data-mce-href="' . $response['url_alt1'] . '" target="_blank">' . $response['filename'] . '.' . $response['ext'] . ' ';
            $response['html'] .= '<a href="' . $response['url_alt1'] . '" data-mce-href="' . $response['url_alt1'] . '" target="_blank">' . $response['filename'] . ' ';
            $response['html'] .= '<br/><img width="' . $width . '" src="' . $response['url'] . '" style="display: block; margin-left: auto; margin-right: auto;"/></a>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'swf':
            $info = getimagesize($response['url']);
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
//            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . '.' . $response['ext'] . ' ';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . ' ';
            $response['html'] .= '<br/></a> <object ' . $info[3] . ' data="' . $response['url'] . '"></object> ';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'image':
            $info = getimagesize($response['url']);
            $width = $info['width'] > 1000 ? '100%' : $info['width'];
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] .  ' ';
            $response['html'] .= '<br/><img width="' . $width . '" src="' . $response['url'] . '" style="display: block; margin-left: auto; margin-right: auto;"/></a>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'video':
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . '</a><br/> ';
            $response['html'] .= '<div id="' . $randid . '" class="jwplayer_video" style="text-align:center">' .
                    '<video width="640" ' . ($response['poster'] ? ' poster="' . $response['poster'] . '"' : '') . ' controls="controls">' .
                    '<source src="' . $response['url_alt1'] . '" type="video/webm" />' .
                    '<source src="' . $response['url'] . '" type="video/mp4" />' .
                    '</video></div> ';

            $response['html'] .= '<script src="JWURL"></script>' .
                    '<script type="text/javascript">jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>' .
                    '<script type="text/javascript">' .
                    'jwplayer("' . $randid . '").setup({' .
                    'sources: [{file: "' . $response['url_alt1'] . '"},{file: "' . $response['url'] . '"}]' .
                    ($response['poster'] ? ',image: "' . $response['poster'] . '"' : '') .
                    ',width: "100%", aspectratio: "16:9",backcolor: "transparent",wmode: "transparent",primary: "flash"}); </script>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'document':
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename']  . '</a><br/> ';
            $response['html'] .= '<iframe src="https://docs.google.com/viewer?embedded=true&url=' . urlencode($response['url']) . '" width="98%" height="600" style="border: none;"></iframe>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'file':
            $response['html'] = ' <br/><div class="asset-display-download">' .
                    '<div class="asset-display-download-inner">' .
                    '<span class="tooltip" title="You can save the File by clicking on the link or Download button below."></span>' .
                    '<a class="icon-link" target="_blank" href="' . $response['url'] . '">Download</a>' .
                    '<p class="text-link">' .
                    '<a target="_blank" href="' . $response['url'] . '">' . $response['filename'] . '</a>' .
                    '</p>' .
                    '<a href="' . $response['url'] . '" target="_blank"><button class="button-link button-link-download" title="Download This file" >Download</button></a>' .
                    '<!-- startButton -->' .
                    '</div></div><br/> ';
            break;
    }
    return $response;
}


function getQuestionsEmbedHTML(&$response) {
    global $vars;
    $randid = uniqid();
    switch ($response['type']) {
        /*
        case 'external':
            $width = 450;
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
//            $response['html'] .= '<a href="' . $response['url_alt1'] . '" data-mce-href="' . $response['url_alt1'] . '" target="_blank">' . $response['filename'] . '.' . $response['ext'] . ' ';
            $response['html'] .= '<a href="' . $response['url_alt1'] . '" data-mce-href="' . $response['url_alt1'] . '" target="_blank">' . $response['filename'] . ' ';
            $response['html'] .= '<br/><img width="' . $width . '" src="' . $response['url'] . '" style="display: block; margin-left: auto; margin-right: auto;"/></a>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'swf':
            $info = getimagesize($response['url']);
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
//            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . '.' . $response['ext'] . ' ';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . ' ';
            $response['html'] .= '<br/></a> <object ' . $info[3] . ' data="' . $response['url'] . '"></object> ';
            $response['html'] .= '</div> <br/> ';
            break;
         * 
         */
        case 'image':
            $info = getimagesize($response['url']);
            $width = $info['width'] > 1000 ? '100%' : $info['width'];
            $response['html'] = '<span><img width="' . $width . '" src="' . $response['url'] . '"/></span>';
            break;
        /*
        case 'video':
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename'] . '</a><br/> ';
            $response['html'] .= '<div id="' . $randid . '" class="jwplayer_video" style="text-align:center">' .
                    '<video width="640" ' . ($response['poster'] ? ' poster="' . $response['poster'] . '"' : '') . ' controls="controls">' .
                    '<source src="' . $response['url_alt1'] . '" type="video/webm" />' .
                    '<source src="' . $response['url'] . '" type="video/mp4" />' .
                    '</video></div> ';

            $response['html'] .= '<script src="JWURL"></script>' .
                    '<script type="text/javascript">jwplayer.key="XlnEnswS1k0cpvBFXqJYwsnzSWECBaplnchsHRncTA4=";</script>' .
                    '<script type="text/javascript">' .
                    'jwplayer("' . $randid . '").setup({' .
                    'sources: [{file: "' . $response['url_alt1'] . '"},{file: "' . $response['url'] . '"}]' .
                    ($response['poster'] ? ',image: "' . $response['poster'] . '"' : '') .
                    ',width: "100%", aspectratio: "16:9",backcolor: "transparent",wmode: "transparent",primary: "flash"}); </script>';
            $response['html'] .= '</div> <br/> ';
            break;
        case 'document':
            $response['html'] = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
            $response['html'] .= '<a href="' . $response['url'] . '" data-mce-href="' . $response['url'] . '" target="_blank">' . $response['filename']  . '</a><br/> ';
            $response['html'] .= '<iframe src="https://docs.google.com/viewer?embedded=true&url=' . urlencode($response['url']) . '" width="98%" height="600" style="border: none;"></iframe>';
            $response['html'] .= '</div> <br/> ';
            break;
         * 
         */
        case 'file':
            $response['html'] = ' <br/><div class="asset-display-download">' .
                    '<div class="asset-display-download-inner">' .
                    '<span class="tooltip" title="You can save the File by clicking on the link or Download button below."></span>' .
                    '<a class="icon-link" target="_blank" href="' . $response['url'] . '">Download</a>' .
                    '<p class="text-link">' .
                    '<a target="_blank" href="' . $response['url'] . '">' . $response['filename'] . '</a>' .
                    '</p>' .
                    '<a href="' . $response['url'] . '" target="_blank"><button class="button-link button-link-download" title="Download This file" >Download</button></a>' .
                    '<!-- startButton -->' .
                    '</div></div><br/> ';
            break;
    }
    return $response;
}
//* * **************End Uploading Process File and amazon S3 and Video transcoding *************** */
//* * **************Start Amazone Cloud Synching Cron jobs*************** */

function awsCloudSearchUpload($file) {
    global $vars;
    if (!isset($vars['csd']))
        $vars['csd'] = $vars['aws']->get('CloudSearchDomain', array('base_url' => $vars['awsSearchEndPoint']));

    return $vars['csd']->uploadDocuments(array('documents' => file_get_contents($file), 'contentType' => 'application/xml'));
}
function awsCloudSearchUploadXML($xml) {
    global $vars;
    if (!isset($vars['csd']))
        $vars['csd'] = $vars['aws']->get('CloudSearchDomain', array('base_url' => $vars['awsSearchEndPoint']));

    return $vars['csd']->uploadDocuments(array('documents' => $xml, 'contentType' => 'application/xml'));
}

function awsPrepareXmlDel() {
    global $vars;
    $xmlFile = '/tmp/' . time() . rand() . '.xml';
    file_put_contents($xmlFile, '<?xml version = "1.0" encoding = "UTF-8"
    ?><batch>');
    //$contents = file_get_contents($vars['awsSearchEndPoint'] . '2013-01-01/search?q.parser=structured&size=10000&q=' . urlencode('(and matchall)'));
    //$contents = file_get_contents(AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(and.type%3A%27Resource%27)&start=0&size=5000');
    $resources = json_decode($contents);
    foreach ($resources->hits->hit as $res) {
        $res_xml = "<delete id='$res->id'></delete>";
        file_put_contents($xmlFile, $res_xml, FILE_APPEND);
    }
    file_put_contents($xmlFile, '</batch>', FILE_APPEND);
    return $xmlFile;
}

function convertSdfFiles() {
    global $db, $vars;
    $resources = $db->select("SELECT distinct resourceid from resourcefiles where uniquename is not null and (SDFstatus is null or SDFstatus = '') limit 5");

    foreach ($resources as $res) {
        print_array(getSdfFilesContent($res['resourceid']));
        echo '<hr/>';
    }
}

function convertSdfFile($params) {
    $response = array('status' => 0, 'csimport' => '');
    if (!empty($params['filename'])) {

        global $vars;
        $vars['s3_client'] = isset($vars['s3_client']) ? $vars['s3_client'] : $vars['aws']->get('S3');
        $params = array_merge(array('src_bucket' => $vars['awsBucket'], 'dest_bucket' => $vars['awsWorkBucket'], 'folder' => 'resourcedocs/', 'filename' => ''), $params);

        $response['csimport'] = exec('./cs_import_document.sh  s3://' . $params['src_bucket'] . '/' . $params['folder'] . $params['filename'] . ' s3://' . $params['dest_bucket'] . '/SDF/' . $params['filename'] /* . ' 2>&1' */);
        $response['status'] = (strpos(' ' . $response['csimport'], 'Converted field name') AND $vars['s3_client']->doesObjectExist($params['dest_bucket'], '/SDF/' . $params['filename'] . '1.json'));
    }
    return $response;
}

function getSdfFilesContent($resourceid) {
    if (empty($resourceid))
        return '';

    $contents = '';
    global $db, $vars;

    $vars['s3_client'] = isset($vars['s3_client']) ? $vars['s3_client'] : $vars['aws']->get('S3');
    $files = $db->select("SELECT * FROM resourcefiles where uniquename is not null and uniquename != '' and resourceid = " . intval($resourceid));
    if ($files)
        foreach ($files as $file) {
            $response = array(
                'status' => (strpos(' ' . $file['SDFstatus'], 'Converted field name') AND $vars['s3_client']->doesObjectExist($vars['awsWorkBucket'], '/SDF/' . $file['uniquename'] . '1.json')),
                'csimport' => $file['SDFstatus']);

            if (empty($file['SDFstatus'])) {
                $response = (array) json_decode(getWithCurl(sprintf('http://cg.curriki.org/curriki/wp-content/libs/cloud_search/sdfConvert.php?folder=%s&filename=%s', ($file['folder'] ? $file['folder'] : 'resourcefiles/'), $file['uniquename'])));
                $db->update('resourcefiles', array('SDFstatus' => $db->mySQLSafe($response['csimport'])), 'fileid = ' . $file['fileid']);
            }

            if ($response['status']) {
                $result = $vars['s3_client']->getObject(array('Bucket' => $vars['awsWorkBucket'], 'Key' => 'SDF/' . $file['uniquename'] . '1.json'));
                $json = json_decode($result['Body']);

                $contents .= $json[0]->fields->content;
            }
        }

    return trim(substr(safeAWSstring(implode(" ", array_filter(array_unique(explode(" ", $contents))))), 0, 950000));
}

function safeAWSstring($str) {
    //echo '<br/><strong>utf8_encode : </strong>' .
    //$str = utf8_encode($str);
    //echo '<br/><strong>preg_replace : </strong>' .
    //$str = preg_replace('/[[:^print:]]/', '', $str);
    // strip out cdata
    //$html = preg_replace("'<!\[CDATA\[(.*?)\]\]>'is", '', $html);
    //// strip out comments
    //$html = preg_replace("'<!--(.*?)-->'is", '', $html);
    //// strip out <script> tags
    //$html = preg_replace("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is", '', $html);
    //$html = preg_replace("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is", '', $html);
    //// strip out <style> tags
    //$html = preg_replace("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is", '', $html);
    //$html = preg_replace("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is", '', $html);
    //$html = preg_replace("'style=\"(.*?)\"'is", '', $html);
    //// strip out preformatted tags
    //$html = preg_replace("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is", '', $html);
    //// strip out server side scripts
    //$html = preg_replace("'(<\?)(.*?)(\? >)'s", '', $html); <-- added space between ? and >
    //// strip smarty scripts
    //$html = preg_replace("'(\{\w)(.*?)(\})'s", '', $html);
    //
  //
  //echo '<br/><strong>strip_tags : </strong>' .
//    $str = strip_tags($str);
    //echo '<br/><strong>htmlspecialchars : </strong>' .
    $str = htmlspecialchars($str);
    //echo '<br/><strong>Spaces reducing : </strong>' .
    $str = preg_replace('!\s+!', ' ', $str);
    $str = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $str); // removing unicode characters
    //Remove Ajeeb Ajeeb characters
    $str = str_replace(array('', '', '', '', '', '', '', '', '', '', ''), '', $str); //Remove Hidden Characters
    //$str = str_replace(array('&'),array('&'),$str);
    //echo '<br/><strong>trim : </strong>' .
    $str = trim($str);
    //exit;
    return $str;
}

function safeAWSUrl($str) {
    return urlencode(trim($str));
}

function safeAWSBit($str) {
    $str = trim($str);
    return (($str == 'T' OR $str == 1 ) ? 'T' : 'F');
}

function safeAWSDouble($str) {
    return doubleval(str_replace(array(",", " "), "", trim($str)));
}

function safeAWSInt($str) {
    return intval(str_replace(array(",", " "), "", trim($str)));
}

function safeAWSDate($str) {
    return date('Y-m-d\TH:i:s.u\Z', ((empty($str) OR $str == '0000-00-00 00:00:00') ? time() :strtotime($str) ));
}

function awsPrepareXmlUp($type = 'resources', $limit = 10) {
    $hascommunity = false;
    $xmlFile = '/tmp/' . time() . rand() . '.xml';
    
    file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');
    
    global $db;
    $updateIds = array();
    $indexed = array(
        'indexed' => $db->mySQLSafe('T'),
        'lastindexdate' => $db->mySQLSafe(date('Y:m:d H:i:s')),
        'indexrequired' => $db->mySQLSafe('F'),
        'indexrequireddate' => 'NULL',
    );

    
    switch ($type) {
        case 'resources':

            $elR = $db->select("SELECT * FROM educationlevels ");
            $el_arr = array();
            foreach ($elR as $e)
                $el_arr[$e['levelid']] = $e;

            $resources = $db->select("SELECT res.resourceid,res.access,res.active,res.aligned,res.content,res.currikilicense,res.description,
        res.generatedkeywords,res.keywords,res.language,l.name as license ,res.mediatype, res.memberrating,
        res.partner, res.resourcechecked,res.type as resourcetype,res.reviewrating,res.reviewstatus,res.studentfacing,
        res.title,res.pageurl,res.approvalStatus,u.firstname as contributofirstname,u.lastname as contributolastname,res.contributiondate,res.remove,
        res.contributorid,res.topofsearch,u.uniqueavatarfile, rt.thumb_image
        FROM resources as res 
        Left JOIN licenses as l on l.licenseid = res.licenseid
        Left JOIN users as u on u.userid = res.contributorid
        LEFT JOIN resource_thumbs AS rt ON res.resourceid = rt.resourceid 
        WHERE res.indexrequired = 'T' and ( ( title is not null and trim(title) != '') OR res.remove = 'T' )
        ORDER BY res.resourceid DESC
        limit $limit");

            if (!$resources)
                echo '<br/><strong>No Resource Require Indexing</strong><br/>';
            else
                foreach ($resources as $row) {
                    $xml = '';
                    $resourceid = $row['resourceid'];
                    $updateIds[] = $resourceid;
                    echo $resourceid . ',';

                    if ($row['remove'] == 'T') {
                        file_put_contents($xmlFile, "<delete id='resource-{$resourceid}'></delete>", FILE_APPEND);
                        continue;
                    }

                    $education_levels = $db->select("SELECT rel.educationlevelid
            FROM resource_educationlevels rel 
            WHERE rel.resourceid = $resourceid  limit 1000");

                    $instruction_types = $db->select("SELECT inst.name
            FROM resource_instructiontypes as rin
            JOIN instructiontypes as inst on rin.instructiontypeid = inst.instructiontypeid
            WHERE rin.resourceid = $resourceid  limit 1000");

                    $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM resource_subjectareas as rsa
            JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE rsa.resourceid = $resourceid  limit 1000");

                    $standards = $db->select("select distinct st.statementid,st.resourceidentifier,s.title as standard
            FROM standards s
            INNER JOIN statements st on s.standardid = st.standardid
            INNER JOIN resource_statements rs on rs.statementid = st.statementid
            WHERE rs.resourceid = $resourceid limit 1000");

                    $group_resources = $db->select("select * from group_resources WHERE resourceid = $resourceid limit 1000");
                    
                    $community_collections = $db->select("select * from communities
                                                    inner join community_collections
                                                    ON communities.communityid = community_collections.communityid
                                                    where community_collections.resourceid = $resourceid limit 1000");
                    $community_resources = $db->select("select * from community_collections where resourceid IN (SELECT collectionid FROM collectionelements where collectionelements.collectionid = 308610 or collectionelements.resourceid = $resourceid)");
                    
                    $group_community_resources = $db->select("select * from community_groups
                                                            inner join group_resources
                                                            on community_groups.groupid = group_resources.groupid
                                                            where group_resources.resourceid = $resourceid;");

                    $xml = "<add id='resource-{$resourceid}'>" .
                            '<field name="id">' . $resourceid . '</field>' .
                            '<field name="site">curriki</field>' .
                            '<field name="type">Resource</field>' .
                            '<field name="resourcetype">' . ($row['resourcetype'] == 'collection' ? 'collection' : 'resource') . '</field>' .
                            //*******************************************//
                            '<field name="active">' . safeAWSBit($row['active']) . '</field>' .
                            '<field name="topofsearch">' . safeAWSBit($row['topofsearch']) . '</field>' .
                            '<field name="topofsearchint">' . ($row['topofsearch']=='T'?1:0) . '</field>' .
                            '<field name="aligned">' . safeAWSBit($row['aligned']) . '</field>' .
                            '<field name="currikilicense">' . safeAWSBit($row['currikilicense']) . '</field>' .
                            '<field name="partner">' . safeAWSBit($row['partner']) . '</field>' .
                            '<field name="partnerint">' . (($row['partner']=='T' || $row['partner']=='C')?1:0) . '</field>' .
                            '<field name="resourcechecked">' . safeAWSBit($row['resourcechecked']) . '</field>' .
                            '<field name="studentfacing">' . safeAWSBit($row['studentfacing']) . '</field>' .
                            //*******************************************//
                            '<field name="title">' . safeAWSstring($row['title']) . '</field>' .
                            '<field name="description">' . safeAWSstring($row['description']) . '</field>' .
                            '<field name="content">' . safeAWSstring($row['content']) . '</field>' .
                            '<field name="keywords">' . safeAWSstring($row['keywords']) . '</field>' .
                            '<field name="generatedkeywords">' . safeAWSstring($row['generatedkeywords']) . '</field>' .
                            '<field name="firstname">' . safeAWSstring($row['contributofirstname']) . '</field>' .
                            '<field name="lastname">' . safeAWSstring($row['contributolastname']) . '</field>' .
                            '<field name="fullname">' . safeAWSstring($row['contributofirstname'] . ' ' . $row['contributolastname']) . '</field>' .
                            //*******************************************//
                            '<field name="contributorid">' . safeAWSInt($row['contributorid']) . '</field>' .
                            '<field name="memberrating">' . safeAWSDouble($row['memberrating']) . '</field>' .
                            '<field name="reviewrating">' . safeAWSDouble($row['reviewrating']) . '</field>' .
                            //*******************************************//
                            '<field name="url">oer/' . safeAWSUrl($row['pageurl']) . '</field>' .
                            '<field name="approvalstatus">' . safeAWSstring($row['approvalStatus']) . '</field>' .
                            '<field name="avatarfile">' . safeAWSUrl($row['uniqueavatarfile']) . '</field>' .
                            '<field name="thumb_image">' . safeAWSUrl($row['thumb_image']) . '</field>' .
                            //*******************************************//
                            '<field name="access">' . trim($row['access'] ? $row['access'] : 'public' ) . '</field>' .
                            '<field name="language">' . trim($row['language']) . '</field>' .
                            '<field name="license">' . trim($row['license']) . '</field>' .
                            '<field name="mediatype">' . trim($row['mediatype']) . '</field>' .
                            '<field name="reviewstatus">' . trim($row['reviewstatus']) . '</field>' .
                            //*******************************************//
                            '<field name="createdate">' . safeAWSDate($row['contributiondate']) . '</field>' .
                            '<field name="filecontent">' . getSdfFilesContent($row['resourceid']) . '</field>';
                    //*******************************************//

                    if ($education_levels)
                        foreach ($education_levels as $level) {
                            $xml .= '<field name="educationlevel">' . trim($el_arr[$level['educationlevelid']]['identifier']) . '</field>';
                        }

                    if ($instruction_types)
                        foreach ($instruction_types as $typ) {
                            $xml .= '<field name="instructiontype">' . trim($typ['name']) . '</field>';
                        }

                    if ($subjects)
                        foreach ($subjects as $subject) {
                            $xml .= '<field name="subject">' . trim($subject['subject']) . '</field>';
                            $xml .= '<field name="subjectarea">' . trim($subject['subjectarea']) . '</field>';
                            $xml .= '<field name="subsubjectarea">' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</field>';
                        }

                    if ($standards)
                        foreach ($standards as $standard) {
                            $xml .= '<field name="standard">' . safeAWSstring($standard['standard']) . '</field>';
                            $xml .= '<field name="standardidentifier">' . safeAWSstring($standard['resourceidentifier']) . '</field>';
                            $xml .= '<field name="statementid">' . $standard['statementid'] . '</field>';
                        }

                    if ($group_resources)
                        foreach ($group_resources as $row) {
                            $xml .= '<field name="resourcegroups">' . safeAWSInt($row['groupid']) . '</field>';
                        }
                        
                    if ($community_resources){
                        $hascommunity = true;
                        foreach ($community_resources as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if ($community_collections){
                        $hascommunity = true;
                        foreach ($community_collections as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if ($group_community_resources){
                        $hascommunity = true;
                        foreach ($group_community_resources as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if($hascommunity){
                        $xml .= '<field name="hascommunity">1</field>';
                    }

                    $xml .= '</add>';
                    file_put_contents($xmlFile, $xml, FILE_APPEND);
                    
                    if (strlen(file_get_contents($xmlFile)) >= 4900000) {
                        file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                        if (isset($_REQUEST['test']))
                            print_xml(file_get_contents($xmlFile));
                        print_array(awsCloudSearchUpload($xmlFile));
                        $db->update('resources', $indexed, 'resourceid in ( ' . implode(',', $updateIds) . ')');
                        $updateIds = array();
                        file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');
                    }
                }

            if (strlen(file_get_contents($xmlFile)) > 60) {
                file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                if (isset($_REQUEST['test']))
                    print_xml(file_get_contents($xmlFile));
                print_array(awsCloudSearchUpload($xmlFile));
                $db->update('resources', $indexed, 'resourceid in ( ' . implode(',', $updateIds) . ')');
            }

            break;

//**************************************************************************************************//
        case 'groups':
            //OLD query
            /*
            $groups = $db->select("SELECT cbg.id as groupid, g.language as language, cbg.description,cbg.name,cbg.slug,cbg.status,cbg.date_created ,g.remove
        FROM cur_bp_groups cbg
        JOIN groups as g  ON g.groupid = cbg.id
        WHERE g.indexrequired = 'T'

        UNION ALL

        SELECT g.groupid,'','','','','','',g.remove FROM groups as g
        WHERE g.indexrequired = 'T' and g.remove = 'T'
        limit $limit");
             * 
             */
            
            //NEW query
            $groups = $db->select("SELECT g.groupid as groupid, g.language as language, g.description,g.displaytitle as name,g.url as slug, g.access as status, g.createdate as date_created ,g.remove, g.spam
        FROM groups g
        WHERE g.indexrequired = 'T' limit $limit");
            

            /* OR g.remove = 'T' */

            if (!$groups)
                echo '<br/><strong>No Groups Require Indexing</strong><br/>';
            else
                foreach ($groups as $row) {
                    $xml = '';
                    $meta = array();
                    $groupid = $row['groupid'];
                    $language = $row['language'];
                    
                    $updateIds[] = $groupid;
                    echo $groupid . ',';

                    if ($row['remove'] == 'T') {
                        file_put_contents($xmlFile, "<delete id='group-{$groupid}'></delete>", FILE_APPEND);
                        continue;
                    }

                    $metaR = $db->select("select * from cur_bp_groups_groupmeta WHERE meta_key in ('cur_access','cur_licenseid','cur_welcome') AND group_id = '{$groupid}'");
                    if ($metaR)
                        foreach ($metaR as $m)
                            $meta[$m['meta_key']] = $m['meta_value'];

                    if (isset($meta['cur_licenseid']) && $meta['cur_licenseid']) {
                        $license = $db->select("SELECT name as license from licenses where licenseid = '" . $meta['cur_licenseid'] . "'");
                        $meta['license'] = $license[0]['license'];
                    } else
                        $meta['license'] = '';

                    $education_levels = $db->select("SELECT el.identifier
            FROM group_educationlevels gel 
            JOIN educationlevels el on el.levelid = gel.educationlevelid
            WHERE gel.groupid = {$groupid}  limit 1000");

                    $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM group_subjectareas as gsa
            JOIN subjectareas as sub on sub.subjectareaid = gsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE gsa.groupid ={$groupid}  limit 1000");

                    $xml = "<add id='group-{$groupid}'>" .
                            '<field name="id">' . $groupid . '</field>' .
                            '<field name="language">'.$language.'</field>' .
                            '<field name="site">curriki</field>' .
                            '<field name="type">Group</field>' .
                            '<field name="active">T</field>' .
                            //*******************************************//
                            '<field name="access">' . (isset($meta['cur_access']) ? $meta['cur_access'] : 'public' ) . '</field>' .
                            '<field name="title">' . safeAWSstring($row['name']) . '</field>' .
                            '<field name="description">' . safeAWSstring($row['description']) . '</field>' .
                            '<field name="welcome">' . safeAWSstring(isset($meta['cur_welcome']) ? $meta['cur_welcome'] : '') . '</field>' . //
                            '<field name="groupprivacy">' . trim($row['status']) . '</field>' .
                            '<field name="license">' . trim($meta['license']) . '</field>' . //
                            '<field name="url">groups/' . safeAWSUrl($row['slug']) . '</field>' .
                            '<field name="createdate">' . safeAWSDate($row['date_created']) . '</field>'.
                            '<field name="groupspam">' . safeAWSstring($row['spam']) . '</field>';

                    //*******************************************//
                    if ($education_levels)
                        foreach ($education_levels as $level) {
                            $xml .= '<field name="educationlevel">' . trim($level['identifier']) . '</field>';
                        }
                    if ($subjects)
                        foreach ($subjects as $subject) {
                            $xml .= '<field name="subject">' . trim($subject['subject']) . '</field>';
                            $xml .= '<field name="subjectarea">' . trim($subject['subjectarea']) . '</field>';
                            $xml .= '<field name="subsubjectarea">' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</field>';
                        }
                    $xml .= '</add>';
                    file_put_contents($xmlFile, $xml, FILE_APPEND);

                    if (strlen(file_get_contents($xmlFile)) >= 4900000) {
                        file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                        if (isset($_REQUEST['test']))
                            print_xml(file_get_contents($xmlFile));
                        print_array(awsCloudSearchUpload($xmlFile));
                        $db->update('groups', $indexed, 'groupid in ( ' . implode(',', $updateIds) . ')');
                        $updateIds = array();
                        file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');
                    }
                }

            if (strlen(file_get_contents($xmlFile)) > 60) {
                file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                if (isset($_REQUEST['test']))
                    print_xml(file_get_contents($xmlFile));
                print_array(awsCloudSearchUpload($xmlFile));
                $db->update('groups', $indexed, 'groupid in ( ' . implode(',', $updateIds) . ')');
            }
            break;

//**************************************************************************************************//
        case 'members':
            $members = $db->select("SELECT userid,user_login,active,country,bio,language,showprofile,membertype,
        city,state,postalcode,organization,blogs,firstname,lastname,registerdate,uniqueavatarfile, spam, remove 
        FROM `users` WHERE indexrequired = 'T' limit $limit");

            if (!$members)
                echo '<br/><strong>No Members Require Indexing</strong><br/>';
            else
                foreach ($members as $row) {
                    $xml = '';
                    $userid = $row['userid'];
                    $updateIds[] = $userid;
                    echo $userid . ',';
                    if ($row['remove'] == 'T') {
                        file_put_contents($xmlFile, "<delete id='user-{$userid}'></delete>", FILE_APPEND);
                        continue;
                    }
                    $education_levels = $db->select("SELECT el.identifier
            FROM user_educationlevels rel 
            JOIN educationlevels el on el.levelid = rel.educationlevelid
            WHERE rel.userid = $userid  limit 1000");
                    //print_array($education_levels);

                    $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM user_subjectareas as rsa
            JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE rsa.userid = $userid  limit 1000");

                    $cur_user = $db->select("SELECT user_nicename	
            FROM cur_users as u
            WHERE u.ID = $userid  limit 1000");

                    $firstname = trim($row['firstname']);
                    $lastname = trim($row['lastname']);
                    $title = trim($firstname . ' ' . $lastname);
                    if (!$title)
                        $title = $firstname = trim($row['user_login']);

                    $xml = "<add id='user-{$userid}'>" .
                            '<field name="id">' . $userid . '</field>' .
                            '<field name="site">curriki</field>' .
                            '<field name="type">Member</field>' .
                            //*******************************************//
                            '<field name="active">' . safeAWSBit($row['active']) . '</field>' .
                            '<field name="title">' . safeAWSstring($title) . '</field>' .
                            '<field name="description">' . safeAWSstring($row['bio']) . '</field>' .
                            '<field name="memberpolicy">' . safeAWSstring($row['showprofile']) . '</field>' .
                            '<field name="membertype">' . safeAWSstring($row['membertype']) . '</field>' .
                            '<field name="organization">' . safeAWSstring($row['organization']) . '</field>' .
                            '<field name="city">' . safeAWSstring($row['city']) . '</field>' .
                            '<field name="state">' . safeAWSstring($row['state']) . '</field>' .
                            '<field name="country">' . safeAWSstring($row['country']) . '</field>' .
                            '<field name="postalcode">' . safeAWSstring($row['postalcode']) . '</field>' .
                            '<field name="blogs">' . safeAWSstring($row['blogs']) . '</field>' .
                            '<field name="contributorid">' . safeAWSInt($row['userid']) . '</field>' .
                            '<field name="firstname">' . safeAWSstring($firstname) . '</field>' .
                            '<field name="lastname">' . safeAWSstring($lastname) . '</field>' .
                            '<field name="fullname">' . safeAWSstring($title) . '</field>' .
                            '<field name="url">members/' . safeAWSUrl($cur_user[0]['user_nicename'] ? $cur_user[0]['user_nicename'] : $row['user_login']) . '</field>' .
                            '<field name="avatarfile">' . safeAWSUrl($row['uniqueavatarfile']) . '</field>' .
                            '<field name="language">' . trim($row['language']) . '</field>' .
                            '<field name="createdate">' . safeAWSDate($row['registerdate']) . '</field>';

                    //*******************************************//

                    if ($education_levels)
                        foreach ($education_levels as $level)
                            $xml .= '<field name="educationlevel">' . trim($level['identifier']) . '</field>';

                    if ($subjects)
                        foreach ($subjects as $subject)
                    {
                            $xml .= '<field name="subject">' . trim($subject['subject']) . '</field>';
                            $xml .= '<field name="subjectarea">' . trim($subject['subjectarea']) . '</field>';
                            $xml .= '<field name="subsubjectarea">' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</field>';
                    }

                    $xml .= '</add>';
                    file_put_contents($xmlFile, $xml, FILE_APPEND);
                    if (strlen(file_get_contents($xmlFile)) >= 4900000) {
                        file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                        if (isset($_REQUEST['test']))
                            print_xml(file_get_contents($xmlFile));
                        print_array(awsCloudSearchUpload($xmlFile));
                        $db->update('users', $indexed, 'userid in ( ' . implode(',', $updateIds) . ')');
                        $updateIds = array();
                        file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');
                    }
                }

            if (strlen(file_get_contents($xmlFile)) > 60) {
                file_put_contents($xmlFile, '</batch>', FILE_APPEND);
                if (isset($_REQUEST['test']))
                    print_xml(file_get_contents($xmlFile));
                
                print_array(awsCloudSearchUpload($xmlFile));
                $db->update('users', $indexed, 'userid in ( ' . implode(',', $updateIds) . ')');
            }
            break;
    }

    $body = '<br/>Time:' . date('Y-m-d H:i:s') . '<br/>Type:' . $type . '<br/>File:' . $xmlFile . '<br/>Ids:' . implode(',', $updateIds) . '<br/>Limit:' . $limit /* . '<br/>Request:' . print_r($_REQUEST, true) */;
    if (!isset($_REQUEST['PHPSESSID']))
    //@mail('furqan.curriki@nxvt.com', 'Resource Uploading Cron Job [' . date('Y-m-d H:i:s') . ']', str_replace('<br/>', "\r\n", $body));
        echo $body;
}


function awsResourceStatusUpdateCloudSearch($resourceid, $approvalStatus, $type = 'resources') {
    $hascommunity = false;
//    $xmlFile = '/tmp/' . time() . rand() . '.xml';
    
    $html = '<?xml version="1.0" encoding="UTF-8"?><batch>';
    
    global $db;
    $updateIds = array();
    $indexed = array(
        'indexed' => $db->mySQLSafe('T'),
        'lastindexdate' => $db->mySQLSafe(date('Y:m:d H:i:s')),
        'indexrequired' => $db->mySQLSafe('F'),
        'indexrequireddate' => 'NULL',
        'approvalStatus' => $approvalStatus,
    );

    
    switch ($type) {
        case 'resources':

            $elR = $db->select("SELECT * FROM educationlevels ");
            $el_arr = array();
            foreach ($elR as $e)
                $el_arr[$e['levelid']] = $e;
            
            $sql = "SELECT res.resourceid,res.access,res.active,res.aligned,res.content,res.currikilicense,res.description,
        res.generatedkeywords,res.keywords,res.language,l.name as license ,res.mediatype, res.memberrating,
        res.partner, res.resourcechecked,res.type as resourcetype,res.reviewrating,res.reviewstatus,res.studentfacing,
        res.title,res.pageurl,res.approvalStatus,u.firstname as contributofirstname,u.lastname as contributolastname,res.contributiondate,res.remove,
        res.contributorid,res.topofsearch,u.uniqueavatarfile, rt.thumb_image
        FROM resources as res 
        Left JOIN licenses as l on l.licenseid = res.licenseid
        Left JOIN users as u on u.userid = res.contributorid
        LEFT JOIN resource_thumbs AS rt ON res.resourceid = rt.resourceid 
        WHERE res.resourceid = $resourceid";
//            return $sql;
            $resources = $db->select($sql);
//            return json_encode($resources);
            if (!$resources){
                echo '<br/><strong>No Resource Require Indexing</strong><br/>';
//                return json_encode("No Resource");
            }
            else
                foreach ($resources as $row) {
                    
                    $xml = '';
                    $resourceid = $row['resourceid'];
                    $updateIds[] = $resourceid;
//                    echo $resourceid . ',';

                    if ($row['remove'] == 'T') {
                        $html .= "<delete id='resource-{$resourceid}'></delete>";
                        continue;
                    }

                    $education_levels = $db->select("SELECT rel.educationlevelid
            FROM resource_educationlevels rel 
            WHERE rel.resourceid = $resourceid  limit 1000");

                    $instruction_types = $db->select("SELECT inst.name
            FROM resource_instructiontypes as rin
            JOIN instructiontypes as inst on rin.instructiontypeid = inst.instructiontypeid
            WHERE rin.resourceid = $resourceid  limit 1000");

                    $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM resource_subjectareas as rsa
            JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE rsa.resourceid = $resourceid  limit 1000");

                    $standards = $db->select("select distinct st.statementid,st.resourceidentifier,s.title as standard
            FROM standards s
            INNER JOIN statements st on s.standardid = st.standardid
            INNER JOIN resource_statements rs on rs.statementid = st.statementid
            WHERE rs.resourceid = $resourceid limit 1000");

                    $group_resources = $db->select("select * from group_resources WHERE resourceid = $resourceid limit 1000");
                    
                    $community_collections = $db->select("select * from communities
                                                    inner join community_collections
                                                    ON communities.communityid = community_collections.communityid
                                                    where community_collections.resourceid = $resourceid limit 1000");
                    $community_resources = $db->select("select * from community_collections where resourceid IN (SELECT collectionid FROM collectionelements where collectionelements.collectionid = 308610 or collectionelements.resourceid = $resourceid)");
                    
                    $group_community_resources = $db->select("select * from community_groups
                                                            inner join group_resources
                                                            on community_groups.groupid = group_resources.groupid
                                                            where group_resources.resourceid = $resourceid;");
                    
                    if($approvalStatus == 'approved'){
                        $row['active'] = 'T';
                    }
                    $xml = "<add id='resource-{$resourceid}'>" .
                            '<field name="id">' . $resourceid . '</field>' .
                            '<field name="site">curriki</field>' .
                            '<field name="type">Resource</field>' .
                            '<field name="resourcetype">' . ($row['resourcetype'] == 'collection' ? 'collection' : 'resource') . '</field>' .
                            //*******************************************//
                            '<field name="active">' . safeAWSBit($row['active']) . '</field>' .
                            '<field name="topofsearch">' . safeAWSBit($row['topofsearch']) . '</field>' .
                            '<field name="topofsearchint">' . ($row['topofsearch']=='T'?1:0) . '</field>' .
                            '<field name="aligned">' . safeAWSBit($row['aligned']) . '</field>' .
                            '<field name="currikilicense">' . safeAWSBit($row['currikilicense']) . '</field>' .
                            '<field name="partner">' . safeAWSBit($row['partner']) . '</field>' .
                            '<field name="partnerint">' . (($row['partner']=='T' || $row['partner']=='C')?1:0) . '</field>' .
                            '<field name="resourcechecked">' . safeAWSBit($row['resourcechecked']) . '</field>' .
                            '<field name="studentfacing">' . safeAWSBit($row['studentfacing']) . '</field>' .
                            //*******************************************//
                            '<field name="title">' . safeAWSstring($row['title']) . '</field>' .
                            '<field name="description">' . safeAWSstring($row['description']) . '</field>' .
                            '<field name="content">' . safeAWSstring($row['content']) . '</field>' .
                            '<field name="keywords">' . safeAWSstring($row['keywords']) . '</field>' .
                            '<field name="generatedkeywords">' . safeAWSstring($row['generatedkeywords']) . '</field>' .
                            '<field name="firstname">' . safeAWSstring($row['contributofirstname']) . '</field>' .
                            '<field name="lastname">' . safeAWSstring($row['contributolastname']) . '</field>' .
                            '<field name="fullname">' . safeAWSstring($row['contributofirstname'] . ' ' . $row['contributolastname']) . '</field>' .
                            //*******************************************//
                            '<field name="contributorid">' . safeAWSInt($row['contributorid']) . '</field>' .
                            '<field name="memberrating">' . safeAWSDouble($row['memberrating']) . '</field>' .
                            '<field name="reviewrating">' . safeAWSDouble($row['reviewrating']) . '</field>' .
                            //*******************************************//
                            '<field name="url">oer/' . safeAWSUrl($row['pageurl']) . '</field>' .
                            '<field name="approvalstatus">' . safeAWSstring($approvalStatus) . '</field>' .
                            '<field name="avatarfile">' . safeAWSUrl($row['uniqueavatarfile']) . '</field>' .
                            '<field name="thumb_image">' . safeAWSUrl($row['thumb_image']) . '</field>' .
                            //*******************************************//
                            '<field name="access">' . trim($row['access'] ? $row['access'] : 'public' ) . '</field>' .
                            '<field name="language">' . trim($row['language']) . '</field>' .
                            '<field name="license">' . trim($row['license']) . '</field>' .
                            '<field name="mediatype">' . trim($row['mediatype']) . '</field>' .
                            '<field name="reviewstatus">' . trim($row['reviewstatus']) . '</field>' .
                            //*******************************************//
                            '<field name="createdate">' . safeAWSDate($row['contributiondate']) . '</field>' .
                            '<field name="filecontent">' . getSdfFilesContent($row['resourceid']) . '</field>';
                    //*******************************************//

                    if ($education_levels)
                        foreach ($education_levels as $level) {
                            $xml .= '<field name="educationlevel">' . trim($el_arr[$level['educationlevelid']]['identifier']) . '</field>';
                        }

                    if ($instruction_types)
                        foreach ($instruction_types as $typ) {
                            $xml .= '<field name="instructiontype">' . trim($typ['name']) . '</field>';
                        }

                    if ($subjects)
                        foreach ($subjects as $subject) {
                            $xml .= '<field name="subject">' . trim($subject['subject']) . '</field>';
                            $xml .= '<field name="subjectarea">' . trim($subject['subjectarea']) . '</field>';
                            $xml .= '<field name="subsubjectarea">' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</field>';
                        }

                    if ($standards)
                        foreach ($standards as $standard) {
                            $xml .= '<field name="standard">' . safeAWSstring($standard['standard']) . '</field>';
                            $xml .= '<field name="standardidentifier">' . safeAWSstring($standard['resourceidentifier']) . '</field>';
                            $xml .= '<field name="statementid">' . $standard['statementid'] . '</field>';
                        }

                    if ($group_resources)
                        foreach ($group_resources as $row) {
                            $xml .= '<field name="resourcegroups">' . safeAWSInt($row['groupid']) . '</field>';
                        }
                        
                    if ($community_resources){
                        $hascommunity = true;
                        foreach ($community_resources as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if ($community_collections){
                        $hascommunity = true;
                        foreach ($community_collections as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if ($group_community_resources){
                        $hascommunity = true;
                        foreach ($group_community_resources as $row) {
                            $xml .= '<field name="communityid">' . safeAWSInt($row['communityid']) . '</field>';
                        }
                    }
                    if($hascommunity){
                        $xml .= '<field name="hascommunity">1</field>';
                    }

                    $xml .= '</add>';
                    $html .= $xml;
                    
                    if (strlen($html) >= 4900000) {
                        /*
                        $html .= '</batch>';
                        if (isset($_REQUEST['test']))
                            print_xml($html);
                        $cloudsearchXML = awsCloudSearchUploadXML($html);
                        $db->update('resources', $indexed, 'resourceid in ( ' . implode(',', $updateIds) . ')');
                        $updateIds = array();
                        $html = '<?xml version="1.0" encoding="UTF-8"?><batch>';
                         * 
                         */
                    }
                }
                
            global $wpdb;    
            if (strlen($html) > 60) {
                $html .= '</batch>';
                if (isset($_REQUEST['test']))
                    print_xml($html);
                $cloudsearchXML = awsCloudSearchUploadXML($html);
//                return json_encode($updateIds);
                $resource_updates = ['approvalStatus'=>$approvalStatus,
                    'indexed' => 'T', 
                    'lastindexdate' => date('Y:m:d H:i:s'),
                    'indexrequired' => 'F',
                    'indexrequireddate'=>date('Y:m:d H:i:s')];
                
                if($approvalStatus == 'approved'){
                    $resource_updates['active'] = 'T';
                }
                
                $wpdb->update('resources', $resource_updates, ['resourceid'=>$resourceid]);
            }
            return json_encode(['updated'=>true]);

            break;
    }

//    $body = '<br/>Time:' . date('Y-m-d H:i:s') . '<br/>Type:' . $type . '<br/>File:' . $xmlFile . '<br/>Ids:' . implode(',', $updateIds) . '<br/>Limit:' . $limit /* . '<br/>Request:' . print_r($_REQUEST, true) */;
//    if (!isset($_REQUEST['PHPSESSID']))
    //@mail('furqan.curriki@nxvt.com', 'Resource Uploading Cron Job [' . date('Y-m-d H:i:s') . ']', str_replace('<br/>', "\r\n", $body));
//        echo $body;
}
/**
 * awsPrepareXmlDeleteResources
 *
 * AWS Prepare XML Delete Resources
 *
 *
 * @param array $resourceIds Ids of resources to delete
 * @return void
 */
function awsPrepareXmlDeleteResources($resourceIds)
{
    $xmlFile = '/tmp/' . time() . rand() . '.xml';

    file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');

    foreach ($resourceIds as $resourceId) {
        file_put_contents($xmlFile, "<delete id='resource-{$resourceId}'></delete>", FILE_APPEND);
    }

    if (strlen(file_get_contents($xmlFile)) > 60) {
        file_put_contents($xmlFile, '</batch>', FILE_APPEND);
        print_array(awsCloudSearchUpload($xmlFile));
    }
}

/**
 * awsPrepareXmlDeleteUsers
 *
 * AWS Prepare XML Delete Users
 *
 *
 * @param array $userIds Ids of users to delete
 * @return void
 */
function awsPrepareXmlDeleteUsers($userIds)
{
    $xmlFile = '/tmp/' . time() . rand() . '.xml';

    file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><batch>');

    foreach ($userIds as $userId) {
        file_put_contents($xmlFile, "<delete id='user-{$userId}'></delete>", FILE_APPEND);
    }

    if (strlen(file_get_contents($xmlFile)) > 60) {
        file_put_contents($xmlFile, '</batch>', FILE_APPEND);
        print_array(awsCloudSearchUpload($xmlFile));
    }
}

function synchIndexing($type = 'Resource') {
    global $db, $vars;
    $indexed = array(
        'indexed' => $db->mySQLSafe('T'),
        'indexrequired' => $db->mySQLSafe('F'),
    );

    for ($i = 0; $i < 100000; $i.=10000) {
        $updateIds = array();
        echo $url = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=%28and.type%3A%27' . $type . '%27%28range.field%3Did.[' . $i . '%2C' . intval($i . 10000) . ']%29%29&return=id&sort=id.asc&start=0&size=10000';
        echo '<br/>';
        $json = json_decode(file_get_contents($url));
        if ($json->hits->found) {
            foreach ($json->hits->hit as $hit) {
                $updateIds[] = $hit->fields->id;
            }
        }
        switch ($type) {
            case 'Resource':
                $db->update('resources', $indexed, 'resourceid in ( ' . implode(',', $updateIds) . ')');
                break;
            case 'Group':
                $db->update('groups', $indexed, 'groupid in ( ' . implode(',', $updateIds) . ')');
                break;
            case 'Member':
                $db->update('users', $indexed, 'userid in ( ' . implode(',', $updateIds) . ')');
                break;
        }
    }
}

//* * **************End Amazone Cloud Synching Cron jobs*************** */
function codeToMessage($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;
        default:
            $message = "Unknown upload error";
            break;
    }
    return $message;
}

function fileSizeM($path) {
    $bytes = filesize($path);
    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = array('B', 'KB', 'MB', 'GB');

        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }

    return $bytes;
}

function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getWithCurl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13');
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if (isset($_REQUEST['correctSDF'])) {
    $vars['s3_client'] = isset($vars['s3_client']) ? $vars['s3_client'] : $vars['aws']->get('S3');

    $result = $vars['s3_client']->listObjects(array('Bucket' => $vars['awsWorkBucket'], 'Prefix' => 'SDF5', 'MaxKeys' => 5000,))->toArray();

    foreach ($result['Contents'] as $i => $object) {
        if (!strpos($object['Key'], '/')) {
            try {
                $copy = $vars['s3_client']->copyObject(array(
                    'Bucket' => $vars['awsWorkBucket'],
                    'CopySource' => $vars['awsWorkBucket'] . '/' . $object['Key'],
                    'Key' => str_replace('SDF', 'SDF/', $object['Key']),
                ));
            } catch (Exception $ex) {
                echo 'Copy Exception : ' . $object['Key'] . "<br/>";
            }

            try {
                $delete = $vars['s3_client']->deleteObject(array(
                    'Bucket' => $vars['awsWorkBucket'],
                    'Key' => $object['Key']
                ));
            } catch (Exception $ex) {
                echo 'Delete Exception : ' . $object['Key'] . "<br/>";
            }
        }
        echo $i . '-' . str_replace('SDF', 'SDF/', $object['Key']) . "<br/>";
    }
    echo '<head><meta http-equiv="refresh" content="1"></head>';
}


if (isset($_REQUEST['correctResourceFiles'])) {
    $vars['s3_client'] = isset($vars['s3_client']) ? $vars['s3_client'] : $vars['aws']->get('S3');
    $files = $db->select("select * from resourcefiles where ext is null and uniquename is not null and trim(uniquename) != '' limit 10 ");

    $folders = array('');
    $folders[] = $vars['resResourceSwfs'];
    $folders[] = $vars['resResourceImgs'];
    $folders[] = $vars['resSourceVideos'];
    $folders[] = $vars['resResourceDocs'];
    $folders[] = $vars['resResourceFiles'];

    $buckets = array($vars['awsBucket'], $vars['awsWorkBucket']);

    if ($files)
        foreach ($files as $file) {
            print_array($file);
            //Setting Up dummy data array
            $data = array(
                'ext' => pathinfo($file['uniquename'], PATHINFO_EXTENSION),
                'folder' => '',
                's3path' => '',
                'SDFstatus' => 'Source has an unsupported content-type',
                'transcoded' => 'F',
            );

            //Setting Up Correct folder
            if (strtolower($data['ext']) == 'swf') {
                $data['folder'] = $vars['resResourceSwfs'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedImg']))) {
                $data['folder'] = $vars['resResourceImgs'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedVideo']))) {
                $data['folder'] = $vars['resSourceVideos'];
                $data['transcoded'] = 'F';
                $bucket = $vars['awsWorkBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedDocs']))) {
                $data['folder'] = $vars['resResourceDocs'];
                $data['SDFstatus'] = $file['SDFstatus'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } else {
                $data['folder'] = $vars['resResourceFiles'];
                $data['SDFstatus'] = $file['SDFstatus'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            }

            //Checking if object do exist in right location
            $objExists = $vars['s3_client']->doesObjectExist($bucket, ($data['folder'] ? $data['folder'] : $vars['resResourceFiles']) . $file['uniquename']);
            if (!$objExists) {

                foreach ($buckets as $b)
                    foreach ($folders as $f) { //Locat Object
                        $objExists = $vars['s3_client']->doesObjectExist($b, $f . $file['uniquename']);
                        if ($objExists) {
                            try {
                                $copy = $vars['s3_client']->copyObject(array(
                                    'Bucket' => $bucket,
                                    'CopySource' => $b . '/' . $f . $file['uniquename'],
                                    'Key' => $data['folder'] . $file['uniquename'],
                                ));
                            } catch (Exception $ex) {
                                echo 'Copy Exception : ' . $b . '/' . $f . $file['uniquename'] . "<br/>";
                            }

                            try {
                                $delete = $vars['s3_client']->deleteObject(array(
                                    'Bucket' => $b,
                                    'Key' => $f . $file['uniquename']
                                ));
                            } catch (Exception $ex) {
                                echo 'Delete Exception : ' . $b . '/' . $f . $file['uniquename'] . "<br/>";
                            }
                            break 2;
                        }
                    }
            }
            if ($objExists)
                $data['s3path'] = $vars['s3_client']->getObjectUrl($bucket, $data['folder'] . $file['uniquename']);

            //Checking if Video is transcoded already or not
            if ($data['transcoded'] == 'F')
                $data['transcoded'] = $vars['s3_client']->doesObjectExist($vars['awsBucket'], $vars['resResourceVideos'] . pathinfo($file['uniquename'], PATHINFO_FILENAME) . '-480p.mp4') ? 'T' : 'F';

            //Checking if SDF converstion status
            if (!empty($data['SDFstatus']) AND ! strpos(' ' . $data['SDFstatus'], 'Source has an unsupported content-type') AND ! $vars['s3_client']->doesObjectExist($vars['awsWorkBucket'], '/SDF/' . $file['uniquename'] . '1.json'))
                $data['SDFstatus'] = '';

            foreach ($data as $ind => $d)
                $data[$ind] = $db->mySQLSafe($d);

            print_array($data);
            $db->update('resourcefiles', $data, 'fileid = ' . $file['fileid']);
        }
}


if (isset($_REQUEST['correctImageScrapFiles'])) {
    $vars['s3_client'] = isset($vars['s3_client']) ? $vars['s3_client'] : $vars['aws']->get('S3');
    $files = $db->select("select * from imagescrap_files where ext is null and uniquename is not null and trim(uniquename) != '' limit 50 ");

    $folders = array('');
    $folders[] = $vars['resResourceSwfs'];
    $folders[] = $vars['resResourceImgs'];
    $folders[] = $vars['resSourceVideos'];
    $folders[] = $vars['resResourceDocs'];
    $folders[] = $vars['resResourceFiles'];

    $buckets = array($vars['awsBucket']/* , $vars['awsWorkBucket'] */);

    if ($files)
        foreach ($files as $file) {
            //print_array($file);
            //Setting Up dummy data array
            $data = array(
                'ext' => pathinfo($file['uniquename'], PATHINFO_EXTENSION),
                'folder' => '',
                's3path' => '',
                'SDFstatus' => 'Source has an unsupported content-type',
                'transcoded' => 'F',
            );

            //Setting Up Correct folder
            if (strtolower($data['ext']) == 'swf') {
                $data['folder'] = $vars['resResourceSwfs'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedImg']))) {
                $data['folder'] = $vars['resResourceImgs'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedVideo']))) {
                $data['folder'] = $vars['resSourceVideos'];
                $data['transcoded'] = 'F';
                $bucket = $vars['awsWorkBucket'];
            } elseif (in_array(strtolower($data['ext']), explode(',', $vars['resAllowedDocs']))) {
                $data['folder'] = $vars['resResourceDocs'];
                $data['SDFstatus'] = $file['SDFstatus'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            } else {
                $data['folder'] = $vars['resResourceFiles'];
                $data['SDFstatus'] = $file['SDFstatus'];
                $data['transcoded'] = 'T';
                $bucket = $vars['awsBucket'];
            }

            //Checking if object do exist in right location
            $objExists = $vars['s3_client']->doesObjectExist($bucket, ($data['folder'] ? $data['folder'] : $vars['resResourceFiles']) . $file['uniquename']);
            if (!$objExists) {

                foreach ($buckets as $b)
                    foreach ($folders as $f) { //Locat Object
                        $objExists = $vars['s3_client']->doesObjectExist($b, $f . $file['uniquename']);
                        if ($objExists) {
                            try {
                                $copy = $vars['s3_client']->copyObject(array(
                                    'Bucket' => $bucket,
                                    'CopySource' => $b . '/' . $f . $file['uniquename'],
                                    'Key' => $data['folder'] . $file['uniquename'],
                                ));
                            } catch (Exception $ex) {
                                echo 'Copy Exception : ' . $b . '/' . $f . $file['uniquename'] . "<br/>";
                            }

                            try {
                                $delete = $vars['s3_client']->deleteObject(array(
                                    'Bucket' => $b,
                                    'Key' => $f . $file['uniquename']
                                ));
                            } catch (Exception $ex) {
                                echo 'Delete Exception : ' . $b . '/' . $f . $file['uniquename'] . "<br/>";
                            }
                            break 2;
                        }
                    }
            }
            if ($objExists)
                $data['s3path'] = $vars['s3_client']->getObjectUrl($bucket, $data['folder'] . $file['uniquename']);

            //Checking if Video is transcoded already or not
            if ($data['transcoded'] == 'F')
                $data['transcoded'] = $vars['s3_client']->doesObjectExist($vars['awsBucket'], $vars['resResourceVideos'] . pathinfo($file['uniquename'], PATHINFO_FILENAME) . '-480p.mp4') ? 'T' : 'F';

            //Checking if SDF converstion status
            if (!empty($data['SDFstatus']) AND ! strpos(' ' . $data['SDFstatus'], 'Source has an unsupported content-type') AND ! $vars['s3_client']->doesObjectExist($vars['awsWorkBucket'], '/SDF/' . $file['uniquename'] . '1.json'))
                $data['SDFstatus'] = '';

            foreach ($data as $ind => $d)
                $data[$ind] = $db->mySQLSafe($d);

            //print_array($data);
            $db->update('imagescrap_files', $data, 'fileid = ' . $file['fileid']);
        }
}


if (isset($_REQUEST['checkEmpty'])) {
    global $db, $vars;
    $indexed = array('indexrequired' => $db->mySQLSafe('T'));
    $updateIds = array();
    for ($i = 0; $i < 110000; $i+=10000) {
        echo $url = AWS_CLOUDSEARCH_END_POINT . '/2013-01-01/search?q.parser=structured&q=(and%20type%3A%27Resource%27%20(range%20field%3Did%20%5B' . $i . '%2C' . intval($i + 10000) . '%5D))&return=id,title,url&sort=id%20asc&start=0&size=10000';
        echo '<br/>';
        $json = json_decode(file_get_contents($url));
        if ($json->hits->found)
            foreach ($json->hits->hit as $hit)
                if (!$hit->fields->title OR ! $hit->fields->url)
                    $updateIds[] = $hit->fields->id;


        /* switch ($type) {
          case 'Resource':
          $db->update('resources', $indexed, 'resourceid in ( ' . implode(',', $updateIds) . ')');
          break;
          case 'Group':
          $db->update('groups', $indexed, 'groupid in ( ' . implode(',', $updateIds) . ')');
          break;
          case 'Member':
          $db->update('users', $indexed, 'userid in ( ' . implode(',', $updateIds) . ')');
          break;
          } */
    }
    echo 'Total:' . count($updateIds);
    print_array($updateIds, 1);
}



if (isset($_REQUEST['uploadToS3Test'])) {

    global $vars;
    if (!isset($vars['s3_client']))
        $vars['s3_client'] = $vars['aws']->get('S3');

    $response['bucket'] = DBI_AWS_S3_BUCKET;
    $response['folder'] = 'resourceswfs/';
    $response['uniquename'] = 'AP_Cal01';
    $response['ext'] = 'xml';

    $targetFile = $vars['upload_path'] . $response['folder'] . $response['uniquename'] . '.' . $response['ext'];
    $upload = $vars['s3_client']->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => $response['bucket'],
                'Key' => $response['folder'] . $response['uniquename'] . '.' . $response['ext'],
                'Body' => fopen($targetFile, 'r.')
            ))->toArray();
}





if (isset($_REQUEST['getResourcesXML'])) {
//    die();
    global $db;
    
    $xmlFile = dirname(getcwd()) . '/wp-content/uploads/xml/Renaissance-' . $_REQUEST['topic'] . '-' . date('Ymd') . '.xml';
    echo '<br/>XML File Creation Started:' . $xmlFile . '<br/>';

    
    file_put_contents($xmlFile, '<?xml version="1.0" encoding="UTF-8"?><currikiSearchResult xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="renaissance-xml.xsd">');
    
    $elR = $db->select("SELECT * FROM educationlevels ");
    
    $el_arr = array();
    foreach ($elR as $e)
        $el_arr[$e['levelid']] = $e;
    
    /*
    $resources = $db->select("SELECT res.resourceid,res.description,res.keywords, res.memberrating,res.reviewrating,res.mediatype,res.fullname,
        res.title,res.pageurl,u.firstname as contributofirstname,u.lastname as contributolastname,res.lasteditdate,res.studentfacing
        FROM resources as res Left JOIN users as u on u.userid = res.contributorid
        WHERE res.resourceid in (SELECT resourceid FROM xml_resources_" . $_REQUEST['topic'] . " )");
     * 
     */
    
    // For xml_resources_individualmath
    
    $resources = $db->select("SELECT xml.resourceid,res.description,res.keywords, res.memberrating,res.reviewrating,res.mediatype,res.fullname,
        res.title,res.pageurl,u.firstname as contributofirstname,u.lastname as contributolastname,res.lasteditdate,res.studentfacing, xml.parentresourceid, xml.parentpageurl
        FROM resources as res 
        Left JOIN users as u on u.userid = res.contributorid
        Left JOIN xml_resources_individualmath as xml on xml.resourceid = res.resourceid
        WHERE res.resourceid in (SELECT resourceid FROM xml_resources_" . $_REQUEST['topic'] . " )");
     
     
     
    
    // For xml_resources_individualmathresourcesnotincollections
    /*
    $resources = $db->select("SELECT res.resourceid,res.description,res.keywords, res.memberrating,res.reviewrating,res.mediatype,res.fullname,
        res.title,res.pageurl,u.firstname as contributofirstname,u.lastname as contributolastname,res.lasteditdate,res.studentfacing
        FROM resources as res 
        Left JOIN users as u on u.userid = res.contributorid
        WHERE res.resourceid in (SELECT resourceid FROM xml_resources_" . $_REQUEST['topic'] . " )");
     * 
     */
     
     
    
//    $resources = $db->select("");
//    var_dump($resources);
//    die();
    if (!$resources)
        echo '<br/><strong>No Resource Found for XML file</strong><br/>';
    else {
        //copy foreach
        
        foreach ($resources as $row) {
            $xml = '';
            $resourceid = $row['resourceid'];
            echo $resourceid . ',';

            //****************************************************************************************************************//
            $xml = "<doc id='resource-{$resourceid}'>" .
                    '<url>http://www.curriki.org/oer/' . safeAWSUrl($row['pageurl']) . '?viewer=embed</url>' .
                    '<fullname>' . strip_tags($row['fullname']) . '</fullname>' .
                    '<title>' . safeAWSstring($row['title']) . '</title>' .
                    '<category>' . safeAWSstring($row['mediatype']) . '</category>' .
                    '<creator>' . safeAWSstring($row['contributofirstname'] . ' ' . $row['contributolastname']) . '</creator>' .
                    '<modificationDate>' . safeAWSDate($row['lasteditdate']) . '</modificationDate>' .
                    '<currikiReview>' . safeAWSDouble($row['reviewrating']) . '</currikiReview>' .
                    '<userRating>' . ($row['memberrating'] ? safeAWSDouble($row['memberrating']) : '') . '</userRating>' .
                    '<description>' . safeAWSstring($row['description']) . '</description>' .
                    '<keywords>' . safeAWSstring($row['keywords']) . '</keywords>' .
                    '<studentfacing>' . safeAWSBit($row['studentfacing']) . '</studentfacing>'.
                    '<parentresourceid>' . safeAWSstring($row['parentresourceid']) . '</parentresourceid>'.
                    '<parentpageurl>' . "http://www.curriki.org/oer/".safeAWSstring($row['parentpageurl']) . '</parentpageurl>';


            //****************************************************************************************************************//
            $instruction_types = $db->select("SELECT inst.name
            FROM resource_instructiontypes as rin
            JOIN instructiontypes as inst on rin.instructiontypeid = inst.instructiontypeid
            WHERE rin.resourceid = $resourceid");

            $xml .= '<icts type="arr">';
            if ($instruction_types)
                foreach ($instruction_types as $typ)
                    $xml .= '<ict>' . trim($typ['name']) . '</ict>';
            $xml .= '</icts>';

            //****************************************************************************************************************//
            $education_levels = $db->select("SELECT rel.educationlevelid
            FROM resource_educationlevels rel 
            WHERE rel.resourceid = $resourceid");

            $xml .= '<levels type="arr">';
            if ($education_levels)
                foreach ($education_levels as $level)
                    $xml .= '<level>' . trim($el_arr[$level['educationlevelid']]['identifier']) . '</level>';
            $xml .= '</levels>';

            //****************************************************************************************************************//
            $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM resource_subjectareas as rsa
            JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE rsa.resourceid = $resourceid");

            $xml .= '<subjects type="arr">';
            if ($subjects)
                foreach ($subjects as $subject)
                    $xml .= '<subject>' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</subject>';
            $xml .= '</subjects>';

            //****************************************************************************************************************//
            $xml .= '</doc>';
            file_put_contents($xmlFile, $xml, FILE_APPEND);
        }
        
        
        
        //copy foreach
        $ids = array(87463, 88398, 88227, 70596, 57349, 58012);
        $resources = $db->select("SELECT res.resourceid,res.description,res.keywords, res.memberrating,res.reviewrating,res.mediatype,res.fullname,
        res.title,res.pageurl,u.firstname as contributofirstname,u.lastname as contributolastname,res.lasteditdate,res.studentfacing
        FROM resources as res Left JOIN users as u on u.userid = res.contributorid
        WHERE res.resourceid in (".  implode(",", $ids).")");
//        var_dump($resources);
//        die();
        foreach ($resources as $row) {
            $xml = '';
            $resourceid = $row['resourceid'];
            echo $resourceid . ',';

            //****************************************************************************************************************//
            $xml = "<doc id='resource-{$resourceid}'>" .
                    '<url>http://www.curriki.org/oer/' . safeAWSUrl($row['pageurl']) . '?viewer=embed</url>' .
                    '<fullname>' . strip_tags($row['fullname']) . '</fullname>' .
                    '<title>' . safeAWSstring($row['title']) . '</title>' .
                    '<category>' . safeAWSstring($row['mediatype']) . '</category>' .
                    '<creator>' . safeAWSstring($row['contributofirstname'] . ' ' . $row['contributolastname']) . '</creator>' .
                    '<modificationDate>' . safeAWSDate($row['lasteditdate']) . '</modificationDate>' .
                    '<currikiReview>' . safeAWSDouble($row['reviewrating']) . '</currikiReview>' .
                    '<userRating>' . ($row['memberrating'] ? safeAWSDouble($row['memberrating']) : '') . '</userRating>' .
                    '<description>' . safeAWSstring($row['description']) . '</description>' .
                    '<keywords>' . safeAWSstring($row['keywords']) . '</keywords>' .
                    '<studentfacing>' . safeAWSBit($row['studentfacing']) . '</studentfacing>';
//                    '<parentresourceid>' . safeAWSstring($row['parentresourceid']) . '</parentresourceid>'.
//                    '<parentpageurl>' . "http://www.curriki.org/oer/".safeAWSstring($row['parentpageurl']) . '</parentpageurl>';


            //****************************************************************************************************************//
            $instruction_types = $db->select("SELECT inst.name
            FROM resource_instructiontypes as rin
            JOIN instructiontypes as inst on rin.instructiontypeid = inst.instructiontypeid
            WHERE rin.resourceid = $resourceid");

            $xml .= '<icts type="arr">';
            if ($instruction_types)
                foreach ($instruction_types as $typ)
                    $xml .= '<ict>' . trim($typ['name']) . '</ict>';
            $xml .= '</icts>';

            //****************************************************************************************************************//
            $education_levels = $db->select("SELECT rel.educationlevelid
            FROM resource_educationlevels rel 
            WHERE rel.resourceid = $resourceid");

            $xml .= '<levels type="arr">';
            if ($education_levels)
                foreach ($education_levels as $level)
                    $xml .= '<level>' . trim($el_arr[$level['educationlevelid']]['identifier']) . '</level>';
            $xml .= '</levels>';

            //****************************************************************************************************************//
            $subjects = $db->select("SELECT sb.subject, sub.subjectarea
            FROM resource_subjectareas as rsa
            JOIN subjectareas as sub on sub.subjectareaid = rsa.subjectareaid
            JOIN subjects as sb on sub.subjectid = sb.subjectid
            WHERE rsa.resourceid = $resourceid");

            $xml .= '<subjects type="arr">';
            if ($subjects)
                foreach ($subjects as $subject)
                    $xml .= '<subject>' . trim($subject['subject'] . ':' . $subject['subjectarea']) . '</subject>';
            $xml .= '</subjects>';

            //****************************************************************************************************************//
            $xml .= '</doc>';
            file_put_contents($xmlFile, $xml, FILE_APPEND);
        }

        //Finishing XML File Creation
        file_put_contents($xmlFile, '</currikiSearchResult>', FILE_APPEND);
        if (isset($_REQUEST['test']))
            echo '<br/>XML File Creation Done:' . $xmlFile . '<br/>';
        print_xml(file_get_contents($xmlFile));
    }
    exit;
}


if (isset($_REQUEST['compareXML'])) {

    $old_xml_file = dirname(getcwd()) . '/wp-content/uploads/xml/' . $_REQUEST['old_xml'];
    $new_xml_file = dirname(getcwd()) . '/wp-content/uploads/xml/' . $_REQUEST['new_xml'];
    $comp_xml_file = dirname(getcwd()) . '/wp-content/uploads/xml/' . str_replace('.xml', '-compared.xml', $_REQUEST['new_xml']);

    if (file_exists($old_xml_file) && file_exists($new_xml_file)) {
        $old_xml = simplexml_load_file($old_xml_file);
        $new_xml = simplexml_load_file($new_xml_file);

        $i = 0;
        foreach ($new_xml->doc as $new_doc) {
            foreach ($old_xml->doc as $old_doc) {
                if (strtolower(urldecode((string) $old_doc->fullname[0])) == strtolower(urldecode((string) $new_doc->fullname[0]))) {
                    echo 'Matched: ' . $old_doc->fullname[0];
                    //print_array($new_xml->doc[$i], 1);
                    unset($new_xml->doc[$i]);
                }
            }
            $i ++;
        }
        file_put_contents($comp_xml_file, $new_xml->asXML());
    } else {
        exit('Failed to open files you mentioned.');
    }
    exit;
}

/**
 * deleteResourcesData
 *
 * Delete Resources Data
 *
 *
 * @param array $brokenLinkResourceIds Ids of resources to delete data of
 * @return void
 */
function deleteResourcesData($brokenLinkResourceIds)
{
    global $wpdb;

    $brokenLinkResourceIdsString = implode(",", $brokenLinkResourceIds);

    $brokenLinkResourceFiles = $wpdb->get_results(
        "
        SELECT fileid
        FROM resourcefiles
        WHERE
            resourceid IN ($brokenLinkResourceIdsString)
        "
    );

    $brokenLinkResourcesFileIds = [];

    foreach ($brokenLinkResourceFiles as $brokenLinkResourceFile)
    {
        $brokenLinkResourcesFileIds[] = $brokenLinkResourceFile->fileid;
    }

    $brokenLinkResourceFileIdsString = implode(",", $brokenLinkResourcesFileIds);

    $brokenLinkResourceQuestions = $wpdb->get_results(
        "
        SELECT questionid
        FROM resourcequestions
        WHERE
            resourceid IN ($brokenLinkResourceIdsString)
        "
    );

    $brokenLinkResourceQuestionIds = [];

    foreach ($brokenLinkResourceQuestions as $brokenLinkResourceQuestion)
    {
        $brokenLinkResourceQuestionIds[] = $brokenLinkResourceQuestion->questionid;
    }

    $brokenLinkResourceQuestionIdsString = implode(",", $brokenLinkResourceQuestionIds);


    if ($brokenLinkResourceQuestions) {
        $brokenLinkResourceQuestionAnswers = $wpdb->get_results(
            "
            SELECT answerid
            FROM resourcequestion_answers
            WHERE
                questionid IN ($brokenLinkResourceQuestionIdsString)
            "
        );

        $brokenLinkResourceQuestionAnswerIds = [];

        foreach ($brokenLinkResourceQuestionAnswers as $brokenLinkResourceQuestionAnswer)
        {
            $brokenLinkResourceQuestionAnswerIds[] = $brokenLinkResourceQuestionAnswer->answerid;
        }

        $brokenLinkResourceQuestionAnswerIdsString = implode(",", $brokenLinkResourceQuestionAnswerIds);
    }

    // begin transaction
    $wpdb->query('START TRANSACTION');

    awsPrepareXmlDeleteResources($brokenLinkResourceIds);

    /*
    $currikiRecommender = loadCurrikiRecommenderPlugin();
    $currikiRecommender->resource_repository->delete($brokenLinkResourceIdsString);
    $currikiRecommender->resource_repository->deleteSubjectAreas($brokenLinkResourceIdsString);
    $currikiRecommender->resource_repository->deleteEducationLevels($brokenLinkResourceIdsString);
    if ($brokenLinkResourceFiles)
        $currikiRecommender->resource_repository->deleteFileDownloads($brokenLinkResourceFileIdsString);
    $currikiRecommender->resource_repository->deleteResourceFiles($brokenLinkResourceIdsString);
    $currikiRecommender->resource_repository->deleteResourceViews($brokenLinkResourceIdsString);
    $currikiRecommender->resource_repository->deleteResourceComments($brokenLinkResourceIdsString);
    */

    $wpdb->query("DELETE FROM resource_subjectareas WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM resource_educationlevels WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM resource_instructiontypes WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM resource_statements WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM group_resources WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM collectionelements WHERE collectionid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM collectionelements WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM community_collections WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM broken_links WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM resourceviews WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM resource_content_link_logs WHERE resourceid IN ($brokenLinkResourceIdsString)");
    $wpdb->query("DELETE FROM comments WHERE resourceid IN ($brokenLinkResourceIdsString)");

    $brokenLinkResourceFiles = $wpdb->get_results(
        "
        SELECT *
        FROM resourcefiles
        WHERE
            resourceid IN ($brokenLinkResourceIdsString)
        ",
        ARRAY_A
    );

    if ($brokenLinkResourceFiles) {
        foreach ($brokenLinkResourceFiles as $brokenLinkResourceFile)
        {
            deleteFileS3((array) $brokenLinkResourceFile);
        }
    }

    $wpdb->query("DELETE FROM resourcefiles WHERE resourceid IN ($brokenLinkResourceIdsString)");
    if ($brokenLinkResourceFiles)
        $wpdb->query("DELETE FROM filedownloads WHERE fileid IN ($brokenLinkResourceFileIdsString)");

    if ($brokenLinkResourceQuestions) {
        if ($brokenLinkResourceQuestionAnswers)
            $wpdb->query("DELETE FROM user_resourcequestions WHERE answerid IN ($brokenLinkResourceQuestionAnswerIdsString)");

        $wpdb->query("DELETE FROM resourcequestion_answers WHERE questionid IN ($brokenLinkResourceQuestionIdsString)");
    }

    $wpdb->query("DELETE FROM resourcequestions WHERE resourceid IN ($brokenLinkResourceIdsString)");

    $wpdb->query("DELETE FROM resources WHERE resourceid IN ($brokenLinkResourceIdsString)");

    // commit transaction
    $wpdb->query('COMMIT');
}

/**
 * deleteUsersData
 *
 * Delete Users Data
 *
 *
 * @param array $userIds Ids of users to delete data of
 * @return void
 */
function deleteUsersData($userIds)
{
    global $wpdb;

    $userIdsString = implode(",", $userIds);

    $userResources = $wpdb->get_results(
        "
        SELECT resourceid
        FROM resources
        WHERE
            contributorid IN ($userIdsString)
        "
    );

    $userResourceIds = [];

    foreach ($userResources as $userResource)
    {
        $userResourceIds[] = $userResource->resourceid;
    }

    $userPartners = $wpdb->get_results(
        "
        SELECT partnerid
        FROM partners
        WHERE
            contributorid IN ($userIdsString)
        "
    );

    $userPartnerIds = [];

    foreach ($userPartners as $userPartner)
    {
        $userPartnerIds[] = $userPartner->partnerid;
    }

    // begin transaction
    $wpdb->query('START TRANSACTION');

    awsPrepareXmlDeleteUsers($userIds);

    deleteResourcesData($userResourceIds);

    $wpdb->query("DELETE FROM featureditems WHERE itemidtype = 'user' AND itemid IN ($userIdsString)");
    $wpdb->query("DELETE FROM group_users WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM icontactupdates WHERE source = 'users' AND sourceid IN ($userIdsString)");
    $wpdb->query("DELETE FROM logins WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM lti_consumer_user WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM s3_updated_avatars WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM searches WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM user_educationlevels WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM user_resourcequestions WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM user_subjectareas WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM visits WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM wcl_lti_submission WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM filedownloads WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM resourceviews WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM resource_statements WHERE userid IN ($userIdsString)");
    $wpdb->query("DELETE FROM emailoptions WHERE userid IN ($userIdsString)");

    if ($userPartners)
        $wpdb->query("DELETE FROM searchterms WHERE partnerid IN ($userPartnerIds)");

    $wpdb->query("DELETE FROM partners WHERE contributorid IN ($userIdsString)");
    $wpdb->query("UPDATE resources SET lasteditorid = contributorid WHERE lasteditorid IN ($userIdsString)");
    $wpdb->query("DELETE FROM users WHERE userid IN ($userIdsString)");

    foreach ($userIds as $userId)
    {
        wp_delete_user($userId);
    }

    // commit transaction
    $wpdb->query('COMMIT');
}

/**
 * updateUserData
 *
 * Update User Data
 *
 *
 * @param array $userData User data to be updated
 * @return array
 */
function updateUserData($userData)
{
    global $wpdb, $vars;

    $errors = array();
    $my_id = get_current_user_id();

    /*
    $wpdb->update(
        'cur_users',
        array(
            'user_email' => $userData['user_email'],
        ),
        array('ID' => $my_id),
        array('%s'),
        array('%d')
    );
    */

    $user_table_fields = array(
        'firstname' => $userData['firstname'],
        'lastname' => $userData['lastname']
        /*
        'city' => $userData['city'],
        'state' => $userData['state'],
        'country' => $userData['country'],
        'bio' => $userData['bio'],
        'facebookurl' => $userData['facebookurl'],
        'twitterurl' => $userData['twitterurl'],
        'organization' => $userData['organization'],
        'blogs' => $userData['blogs'],
        'showemail' => $userData['showemail'],
        'indexrequired' => 'T',
        'indexrequireddate' => date('Y-m-d H:i:s'),
        'language' => $userData['language'],
        'school' => (isset($userData['school']) ? $userData['school'] : "")
        */
    );

    //'membertype' => $userData['membertype'],
    /*
    if (isset($userData['membertype']) && strlen($userData['membertype']) > 0) {
        $user_table_fields["membertype"] = $userData['membertype'];
    }

    if (!empty($userData['zipcode'])) {
        $zip = $userData['zipcode'];
        if (strlen($zip) <= 6 && ctype_digit($zip)) {
            //valid
            $user_table_fields["postalcode"] = $userData['zipcode'];
        } else {
            //invalid
            $errors[] = "Enter valid Zip/Postal code.";
        }
    }
    */

    $wpdb->update(
        'users',
        $user_table_fields,
        array('userid' => $my_id),
        // array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
        array('%s', '%s'),
        array('%d')
    );

    $wpdb->update(
        $wpdb->prefix . 'users',
        array(
            'display_name' => $userData['firstname'] . ' ' . $userData['lastname']
        ),
        array('ID' => $my_id),
        array('%s'),
        array('%d')
    );

    /*
    if (isset($userData["gender"])) {
        $profile = get_user_meta(get_current_user_id(), "profile", true);
        $profile = isset($profile) ? json_decode($profile) : null;

        if (isset($profile)) {
            $profile->gender = $userData["gender"];
            update_user_meta(get_current_user_id(), "profile", json_encode($profile));
        } else {
            $profile = new stdClass();
            $profile->gender = $userData["gender"];
            add_user_meta(get_current_user_id(), "profile", json_encode($profile));
        }
    }
    */

    $wpdb->delete('user_subjectareas', array('userid' => $my_id), array('%d'));
    if (!empty($userData['subjectarea'])) {
        foreach ($userData['subjectarea'] as $sa) {
            $wpdb->query($wpdb->prepare(
                "
                INSERT INTO user_subjectareas
                ( userid, subjectareaid )
                VALUES ( %d, %d )
                ",
                $my_id,
                $sa
            ));
        }
    }

    $wpdb->delete('user_educationlevels', array('userid' => $my_id), array('%d'));
    if (!empty($userData['educationlevel'])) {
        foreach ($userData['educationlevel'] as $el) {
            $wpdb->query($wpdb->prepare(
                "
                INSERT INTO user_educationlevels
                ( userid, educationlevelid )
                VALUES ( %d, %d )
                ",
                $my_id,
                $el
            ));
        }
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //$ignore_img_ext = array("docx","doc","ppt","pdf","xls","txt","csv","mp3","mp4","flv");
    $allow_img_ext = array("jpg", "jpeg", "png", "gif");

    $clear_ext_check = in_array(pathinfo($_FILES['my_photo']['name'], PATHINFO_EXTENSION), $allow_img_ext);
    if ($_FILES['my_photo']['tmp_name'] && !$clear_ext_check) {
        $errors[] = "Invalid Profile image extension ( " . pathinfo($_FILES['my_photo']['name'], PATHINFO_EXTENSION) . " ).";
    }

    if ($_FILES['my_photo']['tmp_name'] && $clear_ext_check) {
        $upload_folder = '/uploads/tmp/';
        $MaxSizeUpload = 5242880; //Bytes

        //$sub_dir = dirname($_SERVER['REQUEST_URI']);
        $sub_dir = "";

        $wp_contents = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $sub_dir . '/wp-content');

        require_once $wp_contents . '/libs/aws_sdk/aws-autoloader.php';

        $base_url = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sub_dir . $upload_folder;
        $current_path = $wp_contents . $upload_folder; // relative path from filemanager folder to upload files folder
        //**********************
        //Allowed extensions
        //**********************

        $ext_img = array('jpg', 'jpeg', 'pjpeg', 'png', 'gif', 'bmp', 'tiff', 'tif'); //Images
        $ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'wmv'); //Videos
        //$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'ppt', 'pptx'); //Files
        //$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
        //$ext_misc = array('zip', 'rar', 'gzip'); //Archives
        //$ext = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); //allowed extensions

        $ds = DIRECTORY_SEPARATOR;

        // $aws = Aws::factory($wp_contents . '/libs/aws_sdk/config.php');
        $s3_client = $vars['aws']->get('S3');

        $bucket = DBI_AWS_S3_BUCKET;

        $ext = pathinfo($_FILES['my_photo']['name'], PATHINFO_EXTENSION);
        $name = preg_replace("/[^a-zA-Z0-9_]+/", "", str_replace(" ", '_', pathinfo($_FILES['my_photo']['name'], PATHINFO_FILENAME))) . time() . rand();
        $tempFile = $_FILES['my_photo']['tmp_name'];

        $targetFile = $current_path . $name . '.' . $ext;
        move_uploaded_file($_FILES['my_photo']['tmp_name'], $targetFile);

        if (file_exists($targetFile)) {
            $pic = uniqid();
            $upload = $s3_client->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => $bucket,
                'Key' => 'avatars/' . $pic . '.' . $ext,
                'Body' => fopen($targetFile, 'r+')
            ))->toArray();

            $wpdb->update(
                'users',
                array(
                    'uniqueavatarfile' => $pic . '.' . $ext,
                ),
                array('userid' => $my_id),
                array('%s'),
                array('%d')
            );
        }
    }


    //==== Checking Spam Data and Setting User as spam ============
    $cnsr_arr  = $wpdb->get_results("SELECT phrase FROM censorphrases");
    $censorphrases  = count($cnsr_arr) > 0 ? $cnsr_arr : array();
    if (stripo_spam($user_table_fields["firstname"], $censorphrases, 1) || stripo_spam($user_table_fields["lastname"], $censorphrases, 1) || stripo_spam($user_table_fields["bio"], $censorphrases, 1) || stripo_spam($user_table_fields["blogs"], $censorphrases, 1) || stripo_spam($user_table_fields["organization"], $censorphrases, 1)) {
        //when spam
        $wpdb->update(
            'users',
            array(
                'spam' => 'T',
                'indexrequired' => 'T',
                'indexrequireddate' => current_time('mysql'),
                'active' => 'F',
            ),
            array(
                "userid" => get_current_user_id(),
            ),
            array("%s", "%s", "%s", "%s"),
            array("%d")
        );

        $wpdb->update(
            'cur_users',
            array(
                'user_status' => 1
            ),
            array(
                "ID" => get_current_user_id(),
            ),
            array("%d"),
            array("%d")
        );

        $reources_update_fields = array(
            'spam' => 'T',
            'remove' => 'T',
            'indexrequired' => 'T',
            'indexrequireddate' => current_time('mysql'),
            'active' => 'F',
        );

        $wpdb->update('resources', $reources_update_fields,
                                   array(
                                       "contributorid"=> get_current_user_id(),
                                   ),
                                   array("%s","%s","%s","%s","%s"),
                                   array("%d")
                        );
    } else {
        //when not spam
        $wpdb->update(
            'users',
            array(
                'spam' => 'F',
                'indexrequired' => 'T',
                'indexrequireddate' => current_time('mysql'),
                'active' => 'T',
            ),
            array(
                "userid" => get_current_user_id(),
            ),
            array("%s", "%s", "%s", "%s"),
            array("%d")
        );
        $wpdb->update(
            'cur_users',
            array(
                'user_status' => 0
            ),
            array(
                "ID" => get_current_user_id(),
            ),
            array("%d"),
            array("%d")
        );

        $reources_update_fields = array(
            'spam' => 'F',
            'remove' => 'F',
            'indexrequired' => 'T',
            'indexrequireddate' => current_time('mysql'),
            'active' => 'T',
        );

        $wpdb->update('resources', $reources_update_fields,
                                   array(
                                       "contributorid"=> get_current_user_id(),
                                   ),
                                   array("%s","%s","%s","%s","%s"),
                                   array("%d")
                        );
    }


    //        if(count($errors) == 0)
    //        {
    //            wp_redirect(get_bloginfo('url').'/dashboard');
    //            die();
    //        }

    return $errors;
}

/**
 * stripo_spam
 *
 * Check Spam Data Censorphrases
 *
 *
 * @param str $haystack String to check for
 * @param array $needles Censorphrases to check
 * @param int $offset Offset
 * @return void
 */
function stripo_spam($haystack, $needles=array(), $offset=0)
{
    $chr = array();
    foreach($needles as $needle) {
        if (stripos(strtolower($haystack), $needle->phrase) !== false) {
            $chr[] = $needle->phrase;
        }
    }
    if(empty($chr))
        return false;
    else
        return true;
}

use CurrikiRecommender\Recommender;

/**
 * loadCurrikiRecommenderPlugin
 *
 * Load Curriki Recommender Plugin
 *
 *
 * @return object
 */
function loadCurrikiRecommenderPlugin() {
    $curriki_recommender_dir = WP_PLUGIN_DIR . '/curriki-recommender';
    $curriki_recommender_path = $curriki_recommender_dir.'/curriki-recommender.php';
    if(file_exists($curriki_recommender_path)){
        require_once $curriki_recommender_dir.'/vendor/autoload.php';

        Recommender::getInstance()->pluginSetup();
        return $GLOBALS['curriki_recommender'];
    }
}