<?php
namespace CurrikiRecommender\Core;
use CurrikiRecommender\Core\Connection;
/**
 * Description of Model
 *
 * @author waqarmuneer
 */
class Model {
        
    public $model_connection = null;    
    public $query_limit = 5;
    public $table_postfix = "";
    
    public function __construct() {
        try{            
            $this->model_connection = new Connection();
            $this->setTablePostfix();
        } catch (\Exception $ex) {                        
            throw $ex;
        }
    }
          
    public function saveDataQuery($query, $query_params = [], $param_types = '' ){         
        $this->model_connection->open();          
        if(pg_query($this->model_connection->connection, $query)){
            $this->model_connection->close();         
            return true;
        }else{
            $this->model_connection->close();         
            return false;
        }
    }
    
    public function saveDataQueryAsync($query, $query_params = [], $param_types = '' ){         
        $this->model_connection->open();          
        if(pg_send_query($this->model_connection->connection, $query)){
            $this->model_connection->close();         
            return true;
        }else{
            $this->model_connection->close();         
            return false;
        }
    }
    
    public function getResults($query, $query_params = [], $param_types = '' ){         
        $this->model_connection->open();         
        pg_prepare($this->model_connection->connection, 'get_result_set' , $query);        
        $this->model_connection->result = pg_execute($this->model_connection->connection, 'get_result_set', $query_params);                
        $records = pg_fetch_all($this->model_connection->result) ?: [];
        $this->model_connection->close();        
        return $records;
    }
    
    protected function fix_zero_in_date_fields(&$data) {
        foreach ($data as $key => $value) {
            $data[$key]= $value === "0000-00-00 00:00:00" ? null : $value;
        }
    }
    
    protected function setTablePostfix() {
        if($_SERVER['HTTP_HOST'] !== 'www.curriki.org'){            
            $this->table_postfix = "_temp";
        }
    }
    protected function param_locate_str($param = [], &$params_counter = null){
        
        $flags = [];        
        if($params_counter !== null && is_array($param) && !empty($param)){
            foreach ($param as $val) {
                $flags[] = '$'.$params_counter;
                $params_counter++;
            }
        }else{            
            throw new \Exception("params_counter not set while preparing query");            
         }
         
        return implode(',', $flags);
    }
    
    protected function array_to_questions($param = []){
        $questions = [];
        foreach ($param as $key => $val) {
            $questions[] = '$'.$key;
        }        
        return implode(',', $questions);
    }
    
    protected function array_to_types($param = [], $type){
        $questions = [];
        foreach ($param as $val) {
            $questions[] = $type;
        }        
        return implode('', $questions);
    }
}
