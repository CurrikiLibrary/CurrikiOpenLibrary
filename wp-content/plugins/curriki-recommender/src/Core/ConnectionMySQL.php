<?php
namespace CurrikiRecommender\Core;
/**
 * Description of Connection
 *
 * @author waqarmuneer
 */
class ConnectionMySQL {
    
    private $connection = null;
    private $statement = null;
            
    public function initConnection() {
                
        $this->connection = mysqli_connect(MARIA_HOST, MARIA_USER, MARIA_PASSWORD, MARIA_DB);
        if (mysqli_connect_errno()) {
            throw new \Exception("Connect failed: ". mysqli_connect_error());            
        }
    }
    
    public function closeConnection() {
        if($this->statement != null && $this->connection != null){
            $this->statement->free_result();
            $this->connection->close();    
        }
    }
    
    public function getResults($query, $param_types = '', $query_params = [] ){
        
        $this->initConnection();                
        $this->statement = $this->connection->prepare($query);
                
        $param_refs = [];        
        $param_refs[] = &$param_types;
        foreach ($query_params as $i => $param) {
            ${'param'.$i} = $param;
            $param_refs[] = &${'param'.$i};
        }          
        call_user_func_array(array($this->statement,'bind_param'), $param_refs);
        
        $this->statement->execute();
        
        $meta_results = $this->statement->result_metadata();
        $fields = [];
        $fields_refs = [];
        foreach($meta_results->fetch_fields() as $field){    
            ${"$field->name"} = null;                        
            $fields_refs[] = &${"$field->name"};
            $fields[] = "$field->name";
        }
        call_user_func_array(array($this->statement,'bind_result'), $fields_refs);
        
        $this->statement->store_result();        
       
        $records = [];
        while($this->statement->fetch()){
            
            $record = new \stdClass();            
            foreach ($fields as $field) {                
                $record->{$field} = ${$field};
            }              
            $records[] = $record;            
        }        
        $this->closeConnection();        
        return $records;        
    }
    
}
