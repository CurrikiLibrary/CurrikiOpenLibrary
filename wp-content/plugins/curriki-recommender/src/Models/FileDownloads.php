<?php
namespace CurrikiRecommender\Models;
use CurrikiRecommender\Core\Model;

/**
 * FileDownloads model is used for database operations
 *
 * @author waqarmuneer
 */

class FileDownloads extends Model{
    private $table = "filedownloads";
    
    public function save($data){ 
        $table = $this->table . $this->table_postfix;
        $query = "
            INSERT INTO {$table} 
            (\"downloadid\", \"fileid\", userid, downloaddate)
            VALUES (".$data["downloadid"].", ".$data["fileid"].", ".$data["userid"].", '". $data["downloaddate"]. "')";            
        return $this->saveDataQuery($query, $this->table);
    }
    
    public function getLast(){
        $table = $this->table . $this->table_postfix;
        $query = "SELECT fd.* 
                FROM $table fd                 
                ORDER BY fd.downloaddate desc limit 1";
        $result = $this->getResults($query);
        return is_array($result) && count($result) > 0 ? $result[0] : null;
    }

    /**
     * delete
     *
     * Delete FileDownloads
     *
     *
     * @param array $fileIds Ids of file to delete
     * @return boolean
     */
    public function delete($fileIds){
        $table = $this->table . $this->table_postfix;
        $query = "
            DELETE FROM {$table}
            WHERE fileid IN ({$fileIds})
        ";
        return $this->saveDataQuery($query);
    }

}
