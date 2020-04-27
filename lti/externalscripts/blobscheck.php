<?php
/**
 * Template Name: Blob Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//set_time_limit(0);

/*
$dbhost = '127.0.0.1';
$dbuser = 'curriki';
$dbpass = 'C9H21hGUaV2WbBY9';
$db = "wp_curriki";
*/

define('DB_NAME', 'mysql:dbname=wp_curriki;host=127.0.0.1');  // e.g. 'mysql:dbname=MyDb;host=localhost' or 'sqlite:php-rating.sqlitedb'
define('DB_USERNAME', 'curriki');
define('DB_PASSWORD', 'C9H21hGUaV2WbBY9');
  
$db = NULL;
try {
  $db = new PDO(DB_NAME, DB_USERNAME, DB_PASSWORD);
} catch(PDOException $e) {
  $db = FALSE;
  echo  "Database error {$e->getCode()}: {$e->getMessage()}";
  die;
}

//$db;
if($db)
{
    
    if( isset($_GET["readids"]) )
    {
        $sql = "select resourceid as ids
                from resources r
                where r.xwd_id is not null";
        $query = $db->prepare($sql);
        $query->execute();

        //$row = $query->fetchObject();
        $row = $query->fetchAll(PDO::FETCH_OBJ);    
        //echo "<pre>";
        //var_dump($row->ids);        
        $ids_arr = array();
        foreach ($row as $r)
        {
            $ids_arr[] = $r->ids;
        }        
        $content = implode(",", $ids_arr);
        $fp = fopen("ids.txt","wb");
        fwrite($fp,$content);
        fclose($fp);
        die;
    }elseif( isset($_GET["fetchsingleresource"]) )
    {
        $sql = "select r.resourceid,r.xwd_id, r.title, rf.fileid, rf.filename,rf.uniquename,rf.folder,rf.s3path
                from resources r
                left join resourcefiles rf on r.resourceid = rf.resourceid
                where r.resourceid = ".$_GET["rsid"];
        $query = $db->prepare($sql);
        $query->execute();

        //$row = $query->fetchObject();
        $row = $query->fetchObject();           
        echo serialize($row);
        //echo json_decode($row);
        die;
    }elseif( isset($_GET["fetch_resource_files"]) )
    {
        $sql = "select *
                from resourcefiles                
                where resourceid = ".$_GET["rsid"];
        $query = $db->prepare($sql);
        $query->execute();

        //$row = $query->fetchObject();
        $resourcefiles = $query->fetchAll( PDO::FETCH_OBJ );    
        echo json_encode($resourcefiles);
        die;
    }else if( isset($_GET["fetchids"]) ){
        
        $fh = fopen('ids.txt','r');
        while ($line = fgets($fh)) {
            // <... Do your work with the line ...>
           //$ids = explode(",", trim($line));
           //var_dump($ids);
           //echo count($ids);
           $ids = trim($line);
           echo $ids;
           die;
        }
        fclose($fh);
    }else{
        echo "Not Fetched...";
    }
    
}else{
    echo "Error selecting db......";
    die();
}
exit();


$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();    
    exit();
}

$sql = 'select GROUP_CONCAT(resourceid)
        from resources r
        where r.xwd_id is not null
        limit 1000';
        //echo "\n".$sql."\n\n";            
        
$retval = mysqli_query( $conn , $sql );
if(! $retval )
{
  die('Could not get data: ' . mysqli_error($conn));
}

$record = mysqli_fetch_object($retval);

echo "<pre>";
var_dump($record);
die;

/*
while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC))
{

}
 * 
 */