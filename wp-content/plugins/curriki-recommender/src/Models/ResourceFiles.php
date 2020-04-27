<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * ResourceFiles model is used for database operations
 *
 * @author waqarmuneer
 */

class ResourceFiles extends Model{
    
    private $table = "resourcefiles";
    
    public function save($resourcefiles_list) {
                
        $table = $this->table . $this->table_postfix;
        $value_query_part = [];
        foreach($resourcefiles_list as $data){            
            $value_query_part[] = "("
                    . "{$data['fileid']}, "
                    . "{$data['resourceid']}, "
                    . "'{$data['filename']}', "
                    . "'{$data['uploaddate']}', "                    
                    . "'{$data['uniquename']}', "
                    . "'{$data['ext']}', "
                    . "'{$data['active']}', "
                    . "'{$data['tempactive']}', "
                    . "'{$data['folder']}',"
                    . "'{$data['s3path']}',"
                    . "'{$data['SDFstatus']}', "
                    . "'{$data['transcoded']}', "                    
                    . "'{$data['lodestar']}', "
                    . "'{$data['archive']}')";                             
        }  
        
        $query = "INSERT INTO {$table} 
            (\"fileid\", 
            \"resourceid\",             
            \"filename\", 
            \"uploaddate\",             
            \"uniquename\", 
            \"ext\", 
            \"active\", 
            \"tempactive\", 
            \"folder\", 
            \"s3path\", 
            \"sdfstatus\", 
            \"transcoded\",             
            \"lodestar\", 
            \"archive\") 
            VALUES ". implode(',', $value_query_part);        
        
        return $this->saveDataQueryAsync($query, $this->table);
    }
    
    public function getLast(){
        $table = $this->table . $this->table_postfix;
        $query = "SELECT rf.* 
                FROM $table rf                 
                ORDER BY rf.fileid desc limit 1";
        $result = $this->getResults($query);
        return is_array($result) && count($result) > 0 ? $result[0] : null;
    }

    /**
     * delete
     *
     * Delete ResourceFiles
     *
     *
     * @param array $resourceIds Ids of resource files to delete
     * @return boolean
     */
    public function delete($resourceIds){
        $table = $this->table . $this->table_postfix;
        $query = "
            DELETE FROM {$table}
            WHERE resourceid IN ({$resourceIds})
        ";
        return $this->saveDataQuery($query);
    }
}        