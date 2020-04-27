<?php
namespace CurrikiSite\Components\Resource\Models;

/**
 * Description of ResourceViews
 *
 * @author waqarmuneer
 */

class ResourceFilesModel {
       
    public function getOnwardById($fileid) {
        global $wpdb;
        $query = "SELECT rf.* 
                FROM resourcefiles rf                 
                WHERE rf.fileid > $fileid";
        return $wpdb->get_results($query);        
    }
    
    public function fileDownloadOnwards($downloaddate) {
        global $wpdb;
        $query = "SELECT fd.* 
                FROM filedownloads fd                 
                WHERE fd.downloaddate > $downloaddate";
        return $wpdb->get_results($query);  
    }
        
}
