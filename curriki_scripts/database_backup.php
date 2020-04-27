<?php
 $dbhost = 'localhost:3036';
 $dbuser = 'curriki';
 $dbpass = 'C9H21hGUaV2WbBY9';

 $conn = mysql_connect($dbhost, $dbuser, $dbpass);

 if(! $conn ) {
  die('Could not connect: ' . mysql_error());
 }

 $table_name = "resources";
 $backup_file  = "/curriki_scripts/backups/chatee.sql";
 $sql = "SELECT * INTO OUTFILE '$backup_file' FROM $table_name";

 mysql_select_db('wp_curriki');
 $retval = mysql_query( $sql, $conn );

 if(! $retval ) {
  die('Could not take data backup: ' . mysql_error());
 }

 echo "Backedup  data successfully\n";

 mysql_close($conn);
?>