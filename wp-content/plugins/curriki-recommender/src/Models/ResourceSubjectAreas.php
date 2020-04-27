<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * ResourceSubjectAreas model is used for database operations
 *
 * @author waqarmuneer
 */

class ResourceSubjectAreas extends Model {
    private $table = 'resource_subjectareas';
    
    public function save($resource_subjectareas_list){                                        
        $values_query_part = [];
        foreach($resource_subjectareas_list as $key => $resource_subjectarea){
            $values_query_part[] = "(" . $resource_subjectarea['resourceid'] . ',' . $resource_subjectarea['subjectareaid'] . ")";
        }                
        
        $table = $this->table . $this->table_postfix;
        $query = "
            INSERT INTO {$table} 
            (\"resourceid\",\"subjectareaid\")
            VALUES " . implode(',', $values_query_part);            
        return $this->saveDataQuery($query, $this->table);
    }

    /**
     * delete
     *
     * Delete ResourceSubjectAreas
     *
     *
     * @param array $resourceIds Ids of resource whose subject areas to delete
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
