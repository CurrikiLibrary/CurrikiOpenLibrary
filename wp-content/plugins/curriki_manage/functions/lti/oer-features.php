<?php

function laasActivityNaviation($resourceid){
    return "<div><center>".laasOerActivityPreviousButton($resourceid)." ".laasOerActivityNextButton($resourceid)."</center></div>";
}

function laasOerActivityNextButton($resourceid){
    $button = '';
    $record = laasOerActivityNext($resourceid);
    if( !is_null($record) ){ 
        global $wpdb;        
        $row = $wpdb->get_row("SELECT * FROM  resources WHERE resourceid = ".$record['sibling'], ARRAY_A);
        $oer_link = site_url('/oer/'.$row['pageurl']);       
        $button = '<button onclick="location.href = \''.$oer_link.'\'" id="next-activity" class="green-button">Next</button>';
    }
    return $button;
}

function laasOerActivityNext($resourceid){
    global $wpdb;
    $query = "
        SELECT ce.resourceid, ce.collectionid as parent_id, ce_sib.resourceid as sibling, ce_sib.displayseqno
        FROM collectionelements ce 
        RIGHT OUTER JOIN collectionelements ce_sib on ce_sib.collectionid = ce.collectionid
        WHERE ce.resourceid = $resourceid AND ce_sib.resourceid > $resourceid
        ORDER BY ce_sib.displayseqno ASC limit 1   
    ";
    return $wpdb->get_row($query, ARRAY_A);
}

function laasOerActivityPreviousButton($resourceid){
    $button = '';
    $record = laasOerActivityPrevious($resourceid);
    if( !is_null($record) ){ 
        global $wpdb;        
        $row = $wpdb->get_row("SELECT * FROM  resources WHERE resourceid = ".$record['sibling'], ARRAY_A);
        $oer_link = site_url('/oer/'.$row['pageurl']);       
        $button = '<button onclick="location.href = \''.$oer_link.'\'" id="previous-activity" class="green-button">Previous</button>';
    }
    return $button;
}

function laasOerActivityPrevious($resourceid){
    global $wpdb;
    $query = "
        SELECT ce.resourceid, ce.collectionid as parent_id, ce_sib.resourceid as sibling, ce_sib.displayseqno
        FROM collectionelements ce 
        RIGHT OUTER JOIN collectionelements ce_sib on ce_sib.collectionid = ce.collectionid
        WHERE ce.resourceid = $resourceid AND ce_sib.resourceid < $resourceid
        ORDER BY ce_sib.displayseqno ASC limit 1
    ;    
    ";
    return $wpdb->get_row($query, ARRAY_A);
}