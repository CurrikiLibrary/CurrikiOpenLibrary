<?php

/**
 * Description of GeographyHelper
 *
 * @author waqarmuneer
 */
class MembertypeReportHelper {

    public static $student_flag_value_with_noise = array('student', 'Student', 'STUDENT');
    public static $teacher_flag_value_with_noise = array('teacher', 'Teacher', 'TEACHER');
    public static $parent_flag_value_with_noise = array('parent', 'Parent', 'PARENT');

    public static function get_membertype_detail_stats($contributorid, $start_date = null, $end_date = null, $collection_slug = "") {
        global $wpdb;

        $query_vars = array();

        $where_clause_for_main_query = "where r.contributorid = %d";
        $sql_membertype_clause = "and usr.membertype in ('student','teacher','parent')";
        
        $query_vars[0] = intval($contributorid);

        $sql_date_range = "";
        if (!($start_date == null && $end_date == null)) {
            $sql_date_range = "and DATE(rv.viewdate) >= DATE(%s) and DATE(rv.viewdate) <= DATE(%s)";
            $query_vars[1] = $start_date;
            $query_vars[2] = $end_date;
        }

        $sql_union_collection_elements = "";
        /*         * ***** If summary filter by 'collection slug' ******** */
        if (strlen(trim($collection_slug)) > 0) {
            $where_clause_for_main_query = "where r.pageurl = %s";
            $query_vars[0] = trim($collection_slug);

            $sql_union_collection_elements = "  UNION
                                                select 
                                                r.resourceid,
                                                r.title,
                                                r.pageurl,
                                                r.type,												
                                                count(CASE WHEN usr.membertype = 'student' THEN 1 END) as students_views,
                                                count(CASE WHEN usr.membertype = 'teacher' THEN 1 END) as teachers_views,
                                                count(CASE WHEN usr.membertype = 'parent' THEN 1 END) as parents_views
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                                left outer join users usr on rv.userid = usr.userid
                                                where cr.pageurl = %s
                                                {$sql_date_range}
                                                {$sql_membertype_clause}                                                
                                                group by r.resourceid
                                            ";
            if (array_key_exists(1, $query_vars) && array_key_exists(2, $query_vars)) {
                $query_vars[3] = trim($collection_slug);
                $query_vars[4] = $start_date;
                $query_vars[5] = $end_date;
            } else {
                $query_vars[1] = trim($collection_slug);
            }
        }

        /*         * ****** Main query to get unique users ********* */
        $query = "
                    select 
                    r.resourceid,
                    r.title,
                    r.pageurl,
                    r.type,												
                    count(CASE WHEN usr.membertype = 'student' THEN 1 END) as students_views,
                    count(CASE WHEN usr.membertype = 'teacher' THEN 1 END) as teachers_views,
                    count(CASE WHEN usr.membertype = 'parent' THEN 1 END) as parents_views
                    from resources r 
                    left outer join resourceviews rv on r.resourceid = rv.resourceid
                    left outer join users usr on rv.userid = usr.userid
                    {$where_clause_for_main_query}                   
                    {$sql_date_range}
                    {$sql_membertype_clause}                    
                    group by r.resourceid
                    {$sql_union_collection_elements}
            ";

        if (strlen($collection_slug) > 0) {
            $query = "
                        select 
                        resourceid,
                        title,
                        pageurl,
                        type,												
                        sum(students_views) as students_views,
                        sum(teachers_views) as teachers_views,
                        sum(parents_views) as parents_views
                        from ($query) report
                        group by resourceid
                    ";
        }
        $result = $wpdb->get_results($wpdb->prepare($query, $query_vars));
        return $result;
    }

    public static function get_membertype_summary_stats($contributorid, $start_date = null, $end_date = null, $collection_slug = "", $resource_type = "resource") {
        global $wpdb;

        $query_vars = array();

        $where_clause_for_main_query = "where r.contributorid = %d";
        $sql_membertype_clause = "and usr.membertype in ('student','teacher','parent')";
        $sql_resource_type_clause = "and r.type = '$resource_type'";

        $query_vars[0] = intval($contributorid);

        $sql_date_range = "";
        if (!($start_date == null && $end_date == null)) {
            $sql_date_range = "and DATE(rv.viewdate) >= DATE(%s) and DATE(rv.viewdate) <= DATE(%s)";
            $query_vars[1] = $start_date;
            $query_vars[2] = $end_date;
        }

        $sql_union_collection_elements = "";
        /*         * ***** If summary filter by 'collection slug' ******** */
        if (strlen(trim($collection_slug)) > 0) {
            $where_clause_for_main_query = "where r.pageurl = %s";
            $query_vars[0] = trim($collection_slug);

            $sql_union_collection_elements = "  UNION
                                                select 
                                                count(rv.viewdate) as views_count,
                                                usr.membertype,
                                                'secondary' as record_type,
                                                r.type as resource_type
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                                left outer join users usr on rv.userid = usr.userid
                                                where cr.pageurl = %s
                                                {$sql_date_range}
                                                {$sql_membertype_clause}
                                                {$sql_resource_type_clause}
                                                group by usr.membertype
                                            ";
            if (array_key_exists(1, $query_vars) && array_key_exists(2, $query_vars)) {
                $query_vars[3] = trim($collection_slug);
                $query_vars[4] = $start_date;
                $query_vars[5] = $end_date;
            } else {
                $query_vars[1] = trim($collection_slug);
            }
        }

        /*         * ****** Main query to get unique users ********* */
        $query = "
                    select 
                    count(rv.viewdate) as views_count,
                    usr.membertype,
                    'primary' as record_type,
                    r.type as resource_type
                    from resources r 
                    left outer join resourceviews rv on r.resourceid = rv.resourceid
                    left outer join users usr on rv.userid = usr.userid
                    {$where_clause_for_main_query}                   
                    {$sql_date_range}
                    {$sql_membertype_clause}
                    {$sql_resource_type_clause}
                    group by usr.membertype
                    {$sql_union_collection_elements}
            ";

        if (strlen($collection_slug) > 0) {
            $query = "
                        select sum(views_count) as views_count, 
                        membertype, record_type, resource_type
                        from ($query) report
                        group by membertype
                    ";
        }

        $result = $wpdb->get_results($wpdb->prepare($query, $query_vars));
        return self::format_results_for_membertype_report($result);
    }    

    public static function format_results_for_membertype_report($result) {

        $students_stats = array('views_count' => 0);
        $teachers_stats = array('views_count' => 0);
        $parents_stats = array('views_count' => 0);

        foreach ($result as $key => $record) {
            if (in_array($record->membertype, self::$student_flag_value_with_noise)) {
                $students_stats['views_count'] = intval($record->views_count);
            }
            if (in_array($record->membertype, self::$teacher_flag_value_with_noise)) {
                $teachers_stats['views_count'] = intval($record->views_count);
            }
            if (in_array($record->membertype, self::$parent_flag_value_with_noise)) {
                $parents_stats['views_count'] = intval($record->views_count);
            }
        }

        return array('students_stats' => $students_stats, 'teachers_stats' => $teachers_stats, 'parents_stats' => $parents_stats);
    }
    
    
    public static function get_users_data($contributorid, $start_date = null, $end_date = null, $collection_slug = "") {
        global $wpdb;

        $query_vars = array();

        $where_clause_for_main_query = "where r.contributorid = %d";
        $sql_membertype_clause = "and usr.membertype in ('student','teacher','parent')";
        $sql_order_by_clause = "order by membertype asc";
        
        $query_vars[0] = intval($contributorid);

        $sql_date_range = "";
        if (!($start_date == null && $end_date == null)) {
            $sql_date_range = "and DATE(rv.viewdate) >= DATE(%s) and DATE(rv.viewdate) <= DATE(%s)";
            $query_vars[1] = $start_date;
            $query_vars[2] = $end_date;
        }

        $sql_union_collection_elements = "";
        /*         * ***** If summary filter by 'collection slug' ******** */
        if (strlen(trim($collection_slug)) > 0) {
            $where_clause_for_main_query = "where r.pageurl = %s";
            $sql_order_by_clause = "";
            $query_vars[0] = trim($collection_slug);

            $sql_union_collection_elements = "  UNION
                                                select 
                                                usr.userid,                    
                                                usr.firstname,
                                                usr.lastname,					
                                                cur_users.user_login,
                                                UPPER(usr.membertype) as membertype,
                                                cur_users.user_email
                                                FROM collectionelements ce
                                                left outer join resources r on ce.resourceid = r.resourceid
                                                left outer join resources cr on ce.collectionid = cr.resourceid
                                                left outer join resourceviews rv on ce.resourceid = rv.resourceid
                                                left outer join users usr on rv.userid = usr.userid
                                                left outer join cur_users on cur_users.ID = usr.userid
                                                where cr.pageurl = %s
                                                {$sql_date_range}
                                                {$sql_membertype_clause}                                                
                                                group by usr.userid
                                            ";
            if (array_key_exists(1, $query_vars) && array_key_exists(2, $query_vars)) {
                $query_vars[3] = trim($collection_slug);
                $query_vars[4] = $start_date;
                $query_vars[5] = $end_date;
            } else {
                $query_vars[1] = trim($collection_slug);
            }
        }

        /*         * ****** Main query to get unique users ********* */
        $query = "
                    select 
                    usr.userid,                    
                    usr.firstname,
                    usr.lastname,					
                    cur_users.user_login,
                    UPPER(usr.membertype) as membertype,
                    cur_users.user_email as email
                    from resources r 
                    left outer join resourceviews rv on r.resourceid = rv.resourceid
                    left outer join users usr on rv.userid = usr.userid
                    left outer join cur_users on cur_users.ID = usr.userid
                    {$where_clause_for_main_query}                   
                    {$sql_date_range}
                    {$sql_membertype_clause}                    
                    group by usr.userid 
                    {$sql_order_by_clause}
                    {$sql_union_collection_elements}
            ";

        if (strlen($collection_slug) > 0) {
            $query = "
                        select 
                        userid,                    
                        firstname,
                        lastname,					
                        user_login,
                        UPPER(membertype) as membertype,
                        email
                        from ($query) report
                        group by userid
                        order by membertype asc
                    ";
        }
                        
        $result = $wpdb->get_results($wpdb->prepare($query, $query_vars));        
        return $result;
    }

}
