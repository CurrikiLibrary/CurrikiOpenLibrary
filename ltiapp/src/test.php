<?php
phpinfo();
die;

define('DB_NAME', 'mysql:dbname=wp_curriki;host=127.0.0.1');  // e.g. 'mysql:dbname=MyDb;host=localhost' or 'sqlite:php-rating.sqlitedb'
  define('DB_USERNAME', 'curriki');
  define('DB_PASSWORD', 'C9H21hGUaV2WbBY9!');
  define('DB_TABLENAME_PREFIX', '');
 
try {
        echo DB_NAME . " *** ".DB_USERNAME. " **** " .DB_PASSWORD. " >>> ";
        
      //$db = new PDO(DB_NAME, DB_USERNAME, DB_PASSWORD);
      $db = new PDO(DB_NAME, DB_USERNAME, DB_PASSWORD);
      echo " me there...";
    } catch(PDOException $e) {
      $db = FALSE;
      $_SESSION['error_message'] = "Database error {$e->getCode()}: {$e->getMessage()}";
    }

    
    var_dump($db);    
    echo $_SESSION['error_message'];
    die;
