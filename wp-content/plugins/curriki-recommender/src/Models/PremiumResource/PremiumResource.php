<?php
namespace CurrikiRecommender\Models\PremiumResource;

/**
 * PremiumResource model is used to get random premium resources
 *
 * @author waqarmuneer
 */

class PremiumResource {
    
    private $records_limits = 3;
    private $premium_resources_slugs = [
        "High-School-Civics-Course-Curriculum--Curated-by-Curriki", "YE-Academy-by-Youth-Entrepreneurs",
        "Voices-Of-History-by-the-Bill-of-Rights-Institute-307939", "Chevron-STEM-Initiatives", "JPAS-Arts-Adventure-Study-Companions",
        "AP-Computer-Science-Principles-Course", "Oracle-Academy-Workshops", "Oracle-Academy-Courses-308061", "AP-Computer-Science-Principles-Course",
        "Prealgebra-aligned-to-CCSS-M-Standards", "Geometry-Aligned-to-CCSS-M-Standards", "Algebra-1-Aligned-to-CCSS-M-Standards", "Curriki-High-School-Physics-Collection"
    ];    
    private $premium_resourcesid_s = [88227,105550,307961,101166,88398,308684,307317,308061,303523,87463,307939,309232];
    
    /**
     * 
     * 
     * @param $subjectareas_ids
     * @param $educationlevels_ids
     * @return random resources
     */
    public function getRandomBySubjectAreasOrEducationLevels($subjectareas_ids = [], $educationlevels_ids = []){
        global $wpdb;        

        if(empty($subjectareas_ids)) {
            $subjectareas_ids = [0];
        }

        if(empty($educationlevels_ids)) {
            $educationlevels_ids = [0];
        }

        $query = "
            SELECT 
            coll_r.resourceid as resourceid, 
            coll_r.resourceid as coll_r_resourceid, 
            coll_r.pageurl as pageurl, 
            coll_r.title as title, 
            count( DISTINCT if(coll_r_subjectareas.subjectareaid IN (".  implode(',', $subjectareas_ids)."),1,NULL) ) as is_subjectareas_found,
            count( DISTINCT if(coll_r_educationlevels.educationlevelid IN (".  implode(',', $educationlevels_ids)."),1,NULL) ) as is_educationlevels_found,
            group_concat( DISTINCT coll_r_educationlevels.educationlevelid ) as coll_r_educationlevels_str,
            coll_el_res.resourceid as coll_el_res_resourceid,
            coll_el_res.type as coll_el_res_type,
            count( DISTINCT IF(coll_el_res.type = 'collection',1,NULL) ) as has_child_collections,
            count( DISTINCT IF(coll_el_res.type = 'resource',1,NULL) ) as has_child_resources
            FROM resources coll_r
            LEFT OUTER JOIN collectionelements coll_el on coll_el.collectionid = coll_r.resourceid
            LEFT OUTER JOIN resource_subjectareas coll_r_subjectareas on coll_r_subjectareas.resourceid = coll_r.resourceid
            LEFT OUTER JOIN resource_educationlevels coll_r_educationlevels on coll_r_educationlevels.resourceid = coll_r.resourceid
            LEFT OUTER JOIN resources coll_el_res on coll_el_res.resourceid = coll_el.resourceid
            WHERE coll_r.resourceid IN (".  implode(',', $this->premium_resourcesid_s).")
            GROUP BY coll_r.resourceid
            HAVING is_subjectareas_found = 1 OR is_educationlevels_found = 1
            ORDER BY rand() limit {$this->records_limits}
            ";
                        
        return $wpdb->get_results($query); 
    }
    
    /**
     *      
     * @param $collectionid
     * @return random child of resource
     */
    public function getRandomChild($collectionid){
        global $wpdb;
        $query = "
                    select 
                    ce.collectionid as coll_r_resourceid,
                    ce.resourceid as ce_resourceid,
                    ce.resourceid as ce_resourceid,
                    r.resourceid as resourceid,
                    r.resourceid as r_resourceid,
                    r.pageurl as pageurl,
                    r.title as title,
                    r.type as r_type,
                    if(r.type = 'collection',1,0) as has_child_collections
                    from collectionelements ce
                    left outer join resources r on r.resourceid = ce.resourceid
                    where ce.collectionid = $collectionid
                    order by rand() limit 1;
            ";
        return $wpdb->get_row($query);        
    }
}
