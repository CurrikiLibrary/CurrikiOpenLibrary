<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * Resource model is used for database operations
 *
 * @author waqarmuneer
 */

class Resource extends Model{
    
    private $table = 'resources';
    
    public function save($resource){                
        $table = $this->table . $this->table_postfix;
        $data = $resource;         
        $this->fix_zero_in_date_fields($data);                                                    
        $query = "
            INSERT INTO {$table} 
            (\"resourceid\",\"licenseid\",\"description\",\"title\",\"keywords\",\"generatedkeywords\",\"language\",\"content\",\"mediatype\",\"aligned\",\"access\",\"studentfacing\",\"topofsearch\",\"partner\",\"lasteditorid\",\"lasteditdate\",\"active\",\"pageurl\",\"contributorid\",\"contributiondate\",\"createdate\",\"type\") 
            VALUES ({$data['resourceid']},{$data['licenseid']},'{$data['description']}','{$data['title']}','{$data['keywords']}','{$data['generatedkeywords']} ','{$data['language']}','{$data['content']}','{$data['mediatype']}','{$data['aligned']}','{$data['access']}','{$data['studentfacing']}','{$data['topofsearch']}','{$data['partner']}',{$data['lasteditorid']},'{$data['lasteditdate']}','{$data['active']}','{$data['pageurl']}',{$data['contributorid']},'{$data['contributiondate']}','{$data['createdate']}','{$data['type']}')
        ";            
        return $this->saveDataQueryAsync($query, $this->table);
    }

    /**
     * delete
     *
     * Delete Resources
     *
     *
     * @param array $resourceIds Ids of resources to delete
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

    public function getLast(){
        $table = $this->table . $this->table_postfix;
        $query = "SELECT r.* 
                FROM $table r                 
                ORDER BY r.resourceid desc limit 1";
        return $this->getResults($query);
    }
}
