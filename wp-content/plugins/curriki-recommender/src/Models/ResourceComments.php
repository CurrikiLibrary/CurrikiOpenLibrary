<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * ResourceComments model is used for database operations
 *
 * @author waqarmuneer
 */

class ResourceComments extends Model{
    private $table = "comments";
    
    public function save($data) {
        $table = $this->table . $this->table_postfix;
        $query = "INSERT INTO {$table} (\"resourceid\", \"userid\", \"comment\", \"rating\", \"commentdate\")
        VALUES ({$data['resourceid']}, {$data['userid']}, '{$data['comment']}', {$data['rating']}, '{$data['commentdate']}')";                
        return $this->saveDataQuery($query, $this->table);
    }
    
    public function getLast(){
        $table = $this->table . $this->table_postfix;
        $query = "SELECT cmnt.* 
                FROM $table cmnt                 
                ORDER BY cmnt.commentdate desc limit 1";
        $result = $this->getResults($query);
        return is_array($result) && count($result) > 0 ? $result[0] : null;
    }

    /**
     * delete
     *
     * Delete ResourceComments
     *
     *
     * @param array $resourceIds Ids of resources whose comments to delete
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
