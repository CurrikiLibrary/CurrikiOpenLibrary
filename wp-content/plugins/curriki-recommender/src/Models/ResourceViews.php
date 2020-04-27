<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * ResourceViews model is used for database operations
 *
 * @author waqarmuneer
 */
class ResourceViews extends Model{
    private $table = "resourceviews";
    
    public function save($data) {                
        $table = $this->table . $this->table_postfix;
        $query = "INSERT INTO {$table} (\"userid\", \"resourceid\", \"viewdate\", \"sitename\", \"visitid\")
                VALUES ({$data['userid']}, {$data['resourceid']}, '{$data['viewdate']}', '{$data['sitename']}', {$data['visitid']})";        
        return $this->saveDataQueryAsync($query, $this->table);
    }
    
    public function getLast(){
        $table = $this->table . $this->table_postfix;
        $query = "SELECT rv.* 
                FROM $table rv                 
                ORDER BY rv.viewdate desc limit 1";                
        return $this->getResults($query);
    }

    /**
     * delete
     *
     * Delete ResourceViews
     *
     *
     * @param array $resourceIds Ids of resources whose views to delete
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
