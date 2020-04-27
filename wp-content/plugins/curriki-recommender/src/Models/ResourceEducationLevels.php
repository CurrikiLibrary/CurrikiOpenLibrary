<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * ResourceEducationLevels model is used for database operations
 *
 * @author waqarmuneer
 */

class ResourceEducationLevels extends Model {
    private $table = 'resource_educationlevels';
    
    public function save($resource_educationlevels_list){                                        
        $table = $this->table . $this->table_postfix;
        $values_query_part = [];
        foreach($resource_educationlevels_list as $key => $resource_educationlevel){
            $values_query_part[] = "(" . $resource_educationlevel['resourceid'] . ',' . $resource_educationlevel['educationlevelid'] . ")";
        }                
        
        $query = "
            INSERT INTO {$table} 
            (\"resourceid\",\"educationlevelid\")
            VALUES " . implode(',', $values_query_part);            
        return $this->saveDataQueryAsync($query, $this->table);
    }

    /**
     * delete
     *
     * Delete ResourceEducationLevels
     *
     *
     * @param array $resourceIds Ids of resource whose education levels to delete
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
