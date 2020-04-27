<?php
namespace CurrikiRecommender\Models\Ranks;
use CurrikiRecommender\Core\Singleton;
use CurrikiRecommender\Core\Model;
use CurrikiRecommender\Entities\Measures;

/**
 * ResourceRating model has queries that return analytics
 * These model quries AWS-Redshift/postgresql
 *
 * @author waqarmuneer
 */

class ResourceRating extends Model{
    use Singleton;
    
    /**
     * It return analytical stats for 'ResourceRating' based on $measures
     * 
     * @param Measures $measures
     * @param array $extra_params
     * @return array
     */
    public function getStats(Measures $measures, $extra_params = []){
            
        
        if($measures->isAnyMeaureSet()){
            
            $exclude_resources_ids = isset($extra_params['exclude_resources_ids']) && is_array($extra_params['exclude_resources_ids'])
                    ? $extra_params['exclude_resources_ids'] : [];
            $params_counter = 1;            
            $query_main_clause = "where ";
            $query_joins = "";            
            $and = "";
            
            if( !empty($measures->getSubjectIds()) && !empty($measures->getSubjectareaIds()) ){
                $query_joins .= "                                 
                                 left outer join resource_subjectareas rs_sub on rs_sub.resourceid = r.resourceid	
                                 left outer join subjectareas sa ON (sa.subjectareaid = rs_sub.subjectareaid)
                                 
                                ";                                
                $query_main_clause .= " rs_sub.subjectareaid IN (".$this->param_locate_str($measures->getSubjectareaIds(),$params_counter).") and sa.subjectid IN (".$this->param_locate_str($measures->getSubjectIds(),$params_counter).") ";
                $and = " and ";
            }
            
            if( !empty($measures->getEducationlevelids()) ){                
                $query_joins .= "
                                 left outer join resource_educationlevels rs_el on rs_el.resourceid = r.resourceid
                                 left outer join educationlevels e ON (e.levelid = rs_el.educationlevelid)
                                 
                                 ";
                $query_main_clause .= " $and rs_el.educationlevelid IN (".$this->param_locate_str($measures->getEducationlevelids(),$params_counter).")";                
                $and = " and ";
            }
            
            if( !empty($measures->getKeywords()) ){
                $keywords = [];
                foreach($measures->getKeywords() as $keyword) {
                    $keywords[] = "%".strtolower($keyword)."%";
                }
                $query_main_clause .= " $and lower(r.keywords) SIMILAR TO '".implode('|', $keywords)."'";
                $and = " and ";
            }
            
            $query_exclude_resources_ids = "";
            if( !empty($exclude_resources_ids) ){
                $query_exclude_resources_ids = " $and r.resourceid NOT IN (".$this->param_locate_str($exclude_resources_ids,$params_counter).")";
            }
            
            $query = "
                   select
                    resourceid,
                    resourceid_count,
                    rating_count,
                    rating_sum,
                    rating_avg,
                    DENSE_RANK() OVER (order by rating_avg desc, rating_count desc) rank
                    from (
                            select 
                            resourceid,
                            resourceid_count,
                            rating_count,
                            rating_sum,
                            rating_avg
                            from (
                                    select
                                    cmnt.resourceid,
                                    COUNT(cmnt.resourceid) resourceid_count,
                                    COUNT(cmnt.rating) rating_count,
                                    SUM(cmnt.rating) rating_sum,
                                    AVG(cmnt.rating) as rating_avg	
                                    from
                                    comments cmnt
                                    left outer join resources r on r.resourceid = cmnt.resourceid	

                                    $query_joins
                                    $query_main_clause
                                    $query_exclude_resources_ids
                                        
                                    and r.remove = 'F' and r.spam = 'F'
                                    group by cmnt.resourceid	
                            ) tbl_comments_subjectareas

                    ) tbl_rank_comments_subjectareas
                    where rating_avg > 0
                    limit ".$this->query_limit."
                ";                                                                
            $params = array_merge($measures->getSubjectareaIds(), $measures->getSubjectIds(), $measures->getEducationlevelids(), $exclude_resources_ids);                        
            return $this->getResults($query, $params );
        }else{
            return [];
        }
    }
    
    public function getBySubjectAreas($subjectarea_ids = array(), $subject_ids = array(), $exclude_resources_ids = array()){  
        if( empty($subject_ids) || empty($subjectarea_ids) ){
            return [];
        }else{
            
            $params_counter = 1;
            
            $query_main_clause = "where rs_sub.subjectareaid IN (".$this->param_locate_str($subjectarea_ids,$params_counter).") and sa.subjectid IN (".$this->param_locate_str($subject_ids,$params_counter).")";
            if( !empty($exclude_resources_ids) ){
                $query_exclude_resources_ids = "and r.resourceid NOT IN (".$this->param_locate_str($exclude_resources_ids,$params_counter).")";
            }
            $query = "
                    select
                    resourceid,
                    resourceid_count,
                    rating_count,
                    rating_sum,
                    rating_avg,
                    DENSE_RANK() OVER (order by rating_avg desc, rating_count desc) rank
                    from (
                            select 
                            resourceid,
                            resourceid_count,
                            rating_count,
                            rating_sum,
                            rating_avg
                            from (
                                    select
                                    cmnt.resourceid,
                                    COUNT(cmnt.resourceid) resourceid_count,
                                    COUNT(cmnt.rating) rating_count,
                                    SUM(cmnt.rating) rating_sum,
                                    AVG(cmnt.rating) as rating_avg	
                                    from
                                    comments cmnt
                                    left outer join resources r on r.resourceid = cmnt.resourceid	

                                    left outer join resource_subjectareas rs_sub on rs_sub.resourceid = cmnt.resourceid	
                                    left outer join subjectareas sa ON (sa.subjectareaid = rs_sub.subjectareaid)                                     
                                    $query_main_clause
                                    $query_exclude_resources_ids
                                    and r.remove = 'F' and r.spam = 'F'
                                    group by cmnt.resourceid	
                            ) tbl_comments_subjectareas

                    ) tbl_rank_comments_subjectareas
                    where rating_avg > 0
                    limit ".$this->query_limit."
                ";            
            $params = array_merge($subjectarea_ids, $subject_ids,$exclude_resources_ids);
            return $this->getResults($query, $params );
        }                     
    }
    
    
    public function getByEducationLevel($educationlevelids = array(), $exclude_resources_ids = array()){  
        if( empty($educationlevelids) ){
            return [];
        }else{
            
            $params_counter = 1;
            
            $query_main_clause = "where rs_el.educationlevelid IN (".$this->param_locate_str($educationlevelids,$params_counter).")";
            if( count($exclude_resources_ids) > 0 ){
                $query_exclude_resources_ids = "and r.resourceid NOT IN (".$this->param_locate_str($exclude_resources_ids,$params_counter).")";
            }
            
            $query = "
                    select 
                    resourceid,
                    resourceid_count,
                    rating_count,
                    rating_sum,
                    rating_avg,
                    DENSE_RANK() OVER (order by rating_avg desc, rating_count desc) rank
                    from (
                            select 
                            resourceid,
                            resourceid_count,
                            rating_count,
                            rating_sum,
                            rating_avg
                            from (
                                    select
                                    cmnt.resourceid,
                                    COUNT(cmnt.resourceid) resourceid_count,
                                    COUNT(cmnt.rating) rating_count,
                                    SUM(cmnt.rating) rating_sum,
                                    AVG(cmnt.rating) as rating_avg	
                                    from
                                    comments cmnt
                                    left outer join resources r on r.resourceid = cmnt.resourceid	

                                    left outer join resource_educationlevels rs_el on rs_el.resourceid = cmnt.resourceid	
                                    left outer join educationlevels e ON (e.levelid = rs_el.educationlevelid)                                                                         
                                    $query_main_clause
                                    $query_exclude_resources_ids
                                    and r.remove = 'F' and r.spam = 'F'
                                    group by cmnt.resourceid			
                            ) tbl_comments_educationlevels
                    ) tbl_rank_comments_educationlevels
                    where rating_avg > 0
                    limit ".$this->query_limit."
                ";
            
            $params = array_merge($educationlevelids,$exclude_resources_ids);                        
            return $this->getResults($query, $params );
        }                     
    }
    
}
