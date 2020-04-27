<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '512M');
require_once __DIR__.'/config.php';
require_once __DIR__.'/inc/Conn.php';
require_once __DIR__.'/inc/Sync.php';

//require_once "inc/autoload.php";



$env = (isset($_GET['env'])) ? $_GET['env'] : 'local';

//die();
//while (true) {
$sync = new Sync($env);

$sync->getDeleteResources();
//$skip++;
//}
