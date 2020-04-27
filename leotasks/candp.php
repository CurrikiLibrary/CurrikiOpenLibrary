<?php
// Backup DB connection
$bck_conn_host 	= "localhost";
$bck_conn_user 	=  "material";
$bck_conn_pass 	=   "material";
$bck_conn_db 	=	"wp_curriki";
$db_backup = @mysqli_connect($bck_conn_host,$bck_conn_user,$bck_conn_pass, $bck_conn_db);

// Prod DB connection
$prd_conn_host 	= "curriki.sftp.wpengine.com";
$prd_conn_user 	= "curriki";
$prd_conn_pass 	= "C9H21hGUaV2WbBY9";
$prd_conn_db	= "wp_curriki";
$db_prod = @mysqli_connect($prd_conn_host,$prd_conn_user,$prd_conn_pass, $prd_conn_db);
var_dump($db_prod);