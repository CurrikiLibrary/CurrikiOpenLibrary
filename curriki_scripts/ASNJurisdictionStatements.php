<?php
die();
$redirect_uri = "http://".$_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']."?env=prod&skip=";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '8192M');

require_once "inc/autoload.php";

//$env = (isset($argv[1])) ? $argv[1] : 'local';
$env = (isset($_GET['env'])) ? $_GET['env'] : 'local';
$skip = (isset($_GET['skip'])) ? $_GET['skip'] : 0;

$sync = new Sync($env, $skip);

echo "<pre>";
$sync->ASNJurisdictionStatements();


?>
<html>
<head>
  <meta http-equiv="refresh" content="1;URL='<?php echo $redirect_uri; ?><?php echo ++$skip; ?>'">
</head>
<body></body>
</html>
