<?php
namespace CurrikiRecommender\Core;
/**
 * Description of Connection
 *
 * @author waqarmuneer
 */
class Connection {
    
    public $connection = null;
    public $statement = null;
    public $result = null;
            
    public function open() {
                
        $host        = "host = " . REDSHIFT_HOST;
        $port        = "port = " . REDSHIFT_PORT;
        $dbname      = "dbname = " . REDSHIFT_DB;
        $credentials = "user = ". REDSHIFT_USER. " password=" . REDSHIFT_PASSWORD;
        
        $this->connection = pg_connect( "$host $port $dbname $credentials"  );
        if (!$this->connection) {
            throw new \Exception("AWS Redshift Connection failed: ");            
        }
    }
    
    public function close() {
        pg_close($this->connection);
        if($this->result){
            pg_free_result($this->result);
        }
    }
    
}
