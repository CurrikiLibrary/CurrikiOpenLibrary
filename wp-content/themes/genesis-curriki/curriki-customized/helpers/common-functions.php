<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function cur_browser_user_agent_words_to_deny()
{
    return array("bot", "Bot", "crawler", "Crawler", "Spider", "spider", "curl", 
                  "SiteDown", "AddThis", "addthis", "WordPress", "Slurp", "slurp", 
                  "Amazon", "Ruby", "FeedBurner", "SimplePie", "PRurl", "Blackboard", 
                  "PubSub", "NewsBlur", "omgili", "adscanner", "Siteimprove", "ltx71","AlertSite",
                  "Trident","Sphider", "qwant", "expo9","sysomos", "jaunty","Jakarta", 
                    "SNAPSHOT","grammarly", "monitoring", "Mediametric", 
                    "Knowledge", "AI", "python", "weborama"
                );
}
function cur_bot_names_to_block()
{
    return array("ahrefs", "baidu", "googlebot", "yandex", "poneytelecom", "ip-51-254-143");
}
function cur_get_my_library_resources_query($library_sorting_get_val,$userid, $library_search_phrase)
{
    $order_by = "order by 1 desc, 5";
    
    if(empty($library_sorting_get_val)){
        $library_sorting_get_val = "mcf";
        $order_by = "order by 1 asc, 5";
    }
    //elseif($library_sorting_get_val == 'displayseqno')  $order_by = "order by displayseqno ASC";
    elseif($library_sorting_get_val == 'oldest')        $order_by = "order by contributiondate ASC";
    elseif($library_sorting_get_val == 'newest')        $order_by = "order by contributiondate DESC";
    elseif($library_sorting_get_val == 'rtc')           $order_by = "order by type DESC";
    elseif($library_sorting_get_val == 'ctr')           $order_by = "order by type ASC";
    elseif($library_sorting_get_val == 'mcf')           $order_by = "order by 1 asc, 5";
    elseif($library_sorting_get_val == 'mff')           $order_by = "order by 1 desc, displayseqno asc, 5";
    elseif($library_sorting_get_val == 'aza')           $order_by = "order by title ASC";
    elseif($library_sorting_get_val == 'azd')           $order_by = "order by title DESC";
    elseif($library_sorting_get_val == 'ru')           $order_by = "order by lasteditdate DESC";

        $user = wp_get_current_user();                                        
        $isAdmin = is_array($user->roles) && in_array('administrator', $user->roles) ? true : false;        
        
        $query_resource_active_clause = "and r.active = 'T'";
        if($isAdmin){
            $query_resource_active_clause = "";
        }

        $query_where_clause_1 = '';
        $query_where_clause_2 = '';

        if (!empty($library_search_phrase)) {
            $query_where_clause_1 = " AND r2.title LIKE '%{$library_search_phrase}%'";
            $query_where_clause_2 = " AND r.title LIKE '%{$library_search_phrase}%'";
        }

        $q_resources = "select 'Favorite', firstname, lastname, uniqueavatarfile, city, state, country, r2.lasteditdate, ce.resourceid, REPLACE(r2.title,'\\\','') as title, r2.type, displayseqno, r2.memberrating, r2.reviewrating, r2.createdate, r2.contributorid, r2.contributiondate,r.partner, if(ifnull(e.resourceid, 'F') = 'F', 'F', 'T') as editable, r.active
            from resources r
            inner join collectionelements ce on ce.collectionid = r.resourceid
            inner join resources r2 on ce.resourceid = r2.resourceid
            left join users u on u.userid = r2.contributorid
            left outer join (select r.resourceid resourceid
                            from cur_bp_groups_members cgm
                            inner join group_resources gr on gr.groupid = cgm.group_id
                            inner join cur_bp_groups_members cgm2 on cgm.group_id = cgm2.group_id
                            inner join resources r on r.resourceid = gr.resourceid
                            where cgm.user_id = '".$userid."'
                            and r.contributorid = cgm2.user_id
                            and r.contributorid <> '".$userid."') e on e.resourceid = ce.resourceid
            where r.type = 'collection'
            and r.title = 'Favorites'
            {$query_resource_active_clause}
            and r.contributorid = '".$userid."'
            {$query_where_clause_1}
            Union
            select 'Contributions', firstname, lastname, uniqueavatarfile, city, state, country, lasteditdate, r.resourceid, REPLACE(title,'\\\','') as title, type, NULL, r.memberrating, r.reviewrating, r.createdate, r.contributorid, r.contributiondate, r.partner, 'T', r.active
            from resources r left join users u on u.userid = r.contributorid
            where contributorid = '".$userid."'
            {$query_resource_active_clause}
            and not (r.type = 'collection' and r.title = 'Favorites')
            {$query_where_clause_2}
        ".$order_by;            
        return $q_resources;
}