<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '8192M');

require_once "inc/autoload.php";

$env = isset($_GET['env']) ? $_GET['env'] : 'local';
$cursor = isset($_GET['cursor']) ? $_GET['cursor'] : 'initial';


$sync = new Sync($env);

$sync->getCloudSearchResources($cursor);

