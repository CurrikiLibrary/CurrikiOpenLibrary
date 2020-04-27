<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'vendor/autoload.php';
define("APPKEY","AIzaSyCM4dkAV04CcScsRohPnJuCWvXBpMns3gE");

$client = new Google_Client();
$client->setApplicationName("curriki");
$client->setDeveloperKey(APPKEY);


function get_google_translated_text($client,$data_to_translate,$target_lang,$source_lang)
{
    $target_lang = "es";
    $source_lang = "en";    
    $src_txt = $data_to_translate;        
    $service = new Google_Service_Translate($client);    
    $optParams = array("source"=>$source_lang,"format"=>"html");
    $rs =  $service->translations->listTranslations($src_txt , $target_lang ,$optParams);
    $translation = $rs->data['translations'][0]["translatedText"];
    return $translation;
}