<html>
<head>
    <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
    <meta charset="UTF-8" />
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
require_once 'vendor/autoload.php';
define("APPKEY","AIzaSyCM4dkAV04CcScsRohPnJuCWvXBpMns3gE");

try 
{
    $client = new Google_Client();
    $client->setApplicationName("curriki");
    $client->setDeveloperKey(APPKEY);
    
    $service = new Google_Service_Translate($client);
    
    //array get_object_vars ( object $object )
    //get_class_methods
    
    /*
     ** $service->translations     
     Class = Google_Service_Translate_Resource_Translations
     METHODS          
            array(4) {
            [0]=>
            string(16) "listTranslations"
            [1]=>
            string(11) "__construct"
            [2]=>
            string(4) "call"
            [3]=>
            string(16) "createRequestUri"
          }
     
     **$service->languages
     Class = Google_Service_Translate_Resource_Languages
     METHODS
        array(4) {
          [0]=>
          string(13) "listLanguages"
          [1]=>
          string(11) "__construct"
          [2]=>
          string(4) "call"
          [3]=>
          string(16) "createRequestUri"
        }
     
     **$service->detections
        Class = Google_Service_Translate_Resource_Detections
        METHODS
        array(4) {
            [0]=>
            string(14) "listDetections"
            [1]=>
            string(11) "__construct"
            [2]=>
            string(4) "call"
            [3]=>
            string(16) "createRequestUri"
          }     
     */    
    $src_txt = '
        <div id="sourceText">
        <p><img height="150" width="150" sizes="(max-width: 150px) 100vw, 150px" srcset="http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-150x150.jpg 150w, http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-300x300.jpg 300w, http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy.jpg 1000w" alt="Scott McNealy" src="http://cdn.curriki.org/uploads/2015/04/06112017/Scott-McNealy-150x150.jpg" class="alignleft size-thumbnail wp-image-7028">Scott McNealy (@ScottMcNealy)<br>
            Co-Founder, Chairman of the Board, and CEO, Sun Microsystems, Inc.<br>
            Co-Founder, Board Member, Curriki<br>
            Co-Founder, Chairman of the Board, Wayin<br>
            Board Member, San Jose Sharks Sports and Entertainment</p>        
        <p>Scott McNealy, Co-Founded Sun Microsystems in 1982 and served as CEO and Chairman of the Board for 22 years during which he piloted the company from startup to legendary Silicon Valley giant in computing infrastructure, network computing, and open source software. Under his watch, Sun Microsystems employed approximately 235,000 worldwide. Sun was sold to Oracle in 2010 for $7.4 billion.</p>
        <p>McNealy is committed to innovation in technology and education and is an outspoken advocate for personal liberty and responsibility, small government, and free-market competition. He is heavily involved in advisory roles for companies that range from startup stage to large corporations. McNealy believes in the philosophy that “Without choice, you have no innovation. Without innovation, you have nothing.”</p>
    </div>
            ';    
    
    
    $optParams = array("source"=>"en","format"=>"html");
    $rs =  $service->translations->listTranslations($src_txt , 'es' ,$optParams);        
    $translation = $rs->data['translations'][0]["translatedText"];
    
    echo $translation;
    
    
} catch (Exception $e) {
    echo '<pre>';
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
//echo "aaaaaa";
//die;

/*
$service = new Google_Service_Books($client);
$optParams = array('filter' => 'free-ebooks');
$results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);

foreach ($results as $item) {
  echo $item['volumeInfo']['title'], "<br /> \n";
}
 * 
 */
?>
</body>
</html>