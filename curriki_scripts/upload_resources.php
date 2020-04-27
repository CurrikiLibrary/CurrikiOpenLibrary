<?php
die("test");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '8192M');

require_once "inc/autoload.php";


$env = (isset($_GET['env'])) ? $_GET['env'] : 'local';
$resource_num = (isset($_GET['resource_num'])) ? $_GET['resource_num'] : 1;

//$env = (isset($argv[1])) ? $argv[1] : 'local';
//$resource_num = (isset($argv[2])) ? $argv[2] : 1;


$sync = new Sync($env);
//$resource_num = 5;
$sync->uploadResources($resource_num);
