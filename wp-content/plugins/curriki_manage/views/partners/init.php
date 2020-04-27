<?php
//error_reporting(E_ALL); ini_set('display_errors', 1); 
add_action('admin_menu', 'init_partners_admin_menus');
function init_partners_admin_menus() {
    add_submenu_page('curriki_admin', __('Partners and Terms'), 'Partners and Terms', 'curriki_admin', 'curriki_partners', 'curriki_partners_view');
}

function curriki_partners_view(){
    $partnerTerms = new PartnerTerms();
    $partnerTerms->partners_view();
}

class PartnerTerms{
    public function partners_view() {
        wp_enqueue_style('curriki_partners_styles',  plugin_dir_url( __FILE__ ).'style.css');
        global $wpdb;


        $partners = array();

        $paged = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
        $limit = 10;
        $offset = ($paged - 1) * $limit;


        $dir = __DIR__;
        if(!isset($_REQUEST['action'])){
            $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
            $forbidden_partners_implode = implode(",", $forbidden_partners);

            if(isset($_GET['partner'])){
                $partners_arr = $wpdb->get_results( 
                    $wpdb->prepare("
                                SELECT * FROM partners
                                WHERE name like %s
                                AND partnerid NOT IN($forbidden_partners_implode)
                                LIMIT $limit OFFSET $offset
                                ",
                            '%'.$wpdb->esc_like($_REQUEST['partner']).'%'
                            ) 
                );
            } else{
                $partners_arr = self::getPartners();
            } 
            $partners = [];
            foreach($partners_arr as $partner){
                $partner->uploaded_terms_count = self::uploadedTermsCount($partner->partnerid);
                $contributor = self::getContributorDetails($partner->contributorid);
                $partner->contributor = $contributor->user_login . ' - ' . $contributor->userid;
                $partners[] = $partner;
            }
    //        echo "<pre>";
    //        print_r($partners);
    //        die();
            $view = 'curriki_partners';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'add_partner'){
            if(isset($_REQUEST['submit'])){
                $data = self::postSubmitAddPartner();
            }

            $view = 'add_partner';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'edit_partner'){
            if(isset($_REQUEST['submit'])){
                $data = self::postSubmitEditPartner();
            }


            $partner = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT * FROM partners
                                WHERE partnerid = %d
                                ",
                            $_REQUEST['edit']
                            ) 
                );
            $terms = self::getTerms($_REQUEST['edit']);
    //        var_dump($terms);
    //        die();
            $user = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT * FROM users
                                WHERE userid = %d
                                ",
                            $partner->contributorid
                            ) 
                );
    //        echo "<pre>";
    //        print_r($partner);
    //        die();
            $partner->contributorid_field = $user->user_login;
            $view = 'edit_partner';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'delete_partner'){
            $data = self::getSubmitDeletePartner();
            $partners = self::getPartners();
            $view = 'curriki_partners';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'terms'){

            if(isset($_GET['term'])){
                $terms = $wpdb->get_results( 
                    $wpdb->prepare("
                                SELECT searchterms.*, partners.name as partnername
                                FROM searchterms
                                LEFT JOIN partners
                                ON searchterms.partnerid = partners.partnerid
                                WHERE searchterms.term like %s
                                ",
                            '%'.$wpdb->esc_like($_REQUEST['term']).'%'
                            ) 
                );
            } else{
                $terms = self::getTerms();
            }
            $view = 'terms';
    
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'add_term'){
            if(isset($_REQUEST['submit'])){
                $data = self::postSubmitAddTerm();
            }

            $view = 'add_term';
            $partner = null;
            
            //TODO - finding partners to populate in select list
            $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
            $forbidden_partners_implode = implode(",", $forbidden_partners);
            $partners = $wpdb->get_results( 
                        $wpdb->prepare( 
                    "
                        SELECT partnerid as id, CONCAT(name, ' - ', partnerid) as text
                        FROM partners
                        WHERE partnerid NOT IN ($forbidden_partners_implode)
                        "
                        )
                );
            if($_REQUEST['partnerid']){
                $partner = $wpdb->get_row( 
                        $wpdb->prepare( 
                    "
                        SELECT partnerid as id, CONCAT(name, ' - ', partnerid) as text
                        FROM partners
                        WHERE partnerid = %d
                        ",
                                $_REQUEST['partnerid']
                        )
                );
                $_REQUEST['partnerid_field'] = $partner->text;
            }

            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'edit_term'){
            if(isset($_REQUEST['submit'])){
                $data = self::postSubmitEditTerm();
    //            echo "<pre>";
    //            print_r($_REQUEST);
    //            die();
                $term = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT * FROM searchterms
                                WHERE partnerid = %d
                                AND term = %s
                                AND active = %s
                                ",
                            $_REQUEST['old_partnerid'],
                            $_REQUEST['old_term'],
                            $_REQUEST['old_active']
                            ) 
                );
            } else{
                $term = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT * FROM searchterms
                                WHERE partnerid = %d
                                AND term = %s
                                AND active = %s
                                ",
                            $_REQUEST['partnerid'],
                            $_REQUEST['term'],
                            $_REQUEST['active']
                            ) 
                );
            }
        //TODO - finding partners to populate in select list
            $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
            $forbidden_partners_implode = implode(",", $forbidden_partners);
            $partners = $wpdb->get_results( 
                        $wpdb->prepare( 
                    "
                        SELECT partnerid as id, CONCAT(name, ' - ', partnerid) as text
                        FROM partners
                        WHERE partnerid NOT IN ($forbidden_partners_implode)
                        "
                        )
                );

            $partner = $wpdb->get_row( 
                        $wpdb->prepare( 
                    "
                        SELECT partnerid as id, CONCAT(name, ' - ', partnerid) as text
                        FROM partners
                        WHERE partnerid = %d
                        ",
                                $_REQUEST['partnerid']
                        )
                );
    //        echo "<pre>";
    //        print_r($partner);
    //        die();
            $term->partnerid_field = $partner->text;
    //        $user = $wpdb->get_row( 
    //                $wpdb->prepare("
    //                            SELECT * FROM users
    //                            WHERE userid = %d
    //                            ",
    //                        $partner->contributorid
    //                        ) 
    //            );
            $view = 'edit_term';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }
        elseif($_REQUEST['action'] == 'delete_term'){
            $data = self::getSubmitDeleteTerm();
            $terms = self::getTerms();
            $view = 'terms';
            @include_once($dir . DIRECTORY_SEPARATOR  . $view . '.php');
        }

    }
    
    public static function postSubmitAddPartner(){
//        if (preg_match("/^[(http|https)]+:\/\/[a-zA-Z0-9\.]+\.(curriki.org\/members)\/([a-zA-Z0-9\-\_]+)/", $_REQUEST['contributorid_field'], $matches)) {
//            echo "Match was found <br />";
//            echo $matches[0];
//        }
//        echo "<pre>";
//        print_r($_REQUEST);
//        die();
        global $wpdb;
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        //validation
        if($_REQUEST['name'] == ""){
            $data['validation_errors'][] = 'Name is required';
        }
        if($_REQUEST['active'] == ""){
            $data['validation_errors'][] = 'Active is required';
        }
        if($_REQUEST['active'] != "T" && $_REQUEST['active'] != "F"){
            $data['validation_errors'][] = 'Active value is invalid';
        }
        if($_REQUEST['termsnumber'] == ""){
            $data['validation_errors'][] = 'Terms Number is required';
        } else if(!is_numeric($_REQUEST['termsnumber'])){
            $data['validation_errors'][] = 'Terms Number should be of type number';
        } elseif($_REQUEST['termsnumber'] < 0){
            $data['validation_errors'][] = 'Terms Number cannot be of negative';
        }

        if($_REQUEST['searchenddate'] == ""){
            $data['validation_errors'][] = 'Search Start Date is required';
        } 
        if($_REQUEST['searchenddate'] == ""){
            $data['validation_errors'][] = 'Search End Date is required';
        }
        /*
        if($_REQUEST['contributorid'] == ""){
            if(is_numeric($_REQUEST['contributorid_field'])){
                $_REQUEST['contributorid'] = $_REQUEST['contributorid_field'];
            } else {
                $data['validation_errors'][] = 'contributorid is required';
            }
        } else if(!is_numeric($_REQUEST['contributorid'])){
            $data['validation_errors'][] = 'contributorid should be of type number';
        } elseif($_REQUEST['contributorid'] < 0){
            $data['validation_errors'][] = 'contributorid cannot be of negative';
        }
         * 
         */
        
        if($_REQUEST['contributorid_field'] == ""){
            $data['validation_errors'][] = 'contributorid is required';
        } else{
            $contributor = $wpdb->get_row( 
                    $wpdb->prepare("
                            SELECT * FROM users WHERE user_login LIKE BINARY %s
                            ", $_REQUEST['contributorid_field']
                        )
                );
            if($contributor){
                $_REQUEST['contributorid'] = $contributor->userid;
            } else {
                $data['validation_errors'][] = 'Invalid contributor';
            }
        }
        
        if(count($data['validation_errors']) == 0){
            $contributor_already_present = $wpdb->get_results( 
                        $wpdb->prepare("
                            SELECT * FROM partners
                            WHERE 
                            contributorid = %d
                                    ",
                                $_REQUEST['contributorid']
                                ) 
                     );
            if(count($contributor_already_present) > 0){
                $data['validation_errors'][] = 'This contributor is already associated with some partner. ';
                return $data;
            }
            $wpdb->insert( 
                    'partners', 
                    array( 
                            'name' => $_REQUEST['name'], 
                            'active' => $_REQUEST['active'] ,
                            'apiversion' => '2.0' ,
                            'termsnumber' => $_REQUEST['termsnumber'],
                            'searchstartdate' => $_REQUEST['searchstartdate'],
                            'searchenddate' => $_REQUEST['searchenddate'],
                            'contributorid' => $_REQUEST['contributorid'],
                    ), 
                    array( 
                            '%s', 
                            '%s',
                            '%s', 
                            '%d',
                            '%s', 
                            '%s',
                            '%d',
                    ) 
            );
            $data['success_message'][] = 'Partner Added successfully';
            $_REQUEST = [];
        }

        return $data;
    }


    public static function postSubmitEditPartner(){
        global $wpdb;
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        //validation
        if($_REQUEST['name'] == ""){
            $data['validation_errors'][] = 'Name is required';
        }
        if($_REQUEST['active'] == ""){
            $data['validation_errors'][] = 'Active is required';
        }
        if($_REQUEST['active'] != "T" && $_REQUEST['active'] != "F"){
            $data['validation_errors'][] = 'Active value is invalid';
        }
        if($_REQUEST['termsnumber'] == ""){
            $data['validation_errors'][] = 'Terms Number is required';
        } else if(!is_numeric($_REQUEST['termsnumber'])){
            $data['validation_errors'][] = 'Terms Number should be of type number';
        } elseif($_REQUEST['termsnumber'] < 0){
            $data['validation_errors'][] = 'Terms Number cannot be of negative';
        }

        if($_REQUEST['searchenddate'] == ""){
            $data['validation_errors'][] = 'Search Start Date is required';
        } 
        if($_REQUEST['searchenddate'] == ""){
            $data['validation_errors'][] = 'Search End Date is required';
        }
        /*
        if($_REQUEST['contributorid'] == ""){
            $data['validation_errors'][] = 'contributorid is required';
        } else if(!is_numeric($_REQUEST['contributorid'])){
            $data['validation_errors'][] = 'contributorid should be of type number';
        } elseif($_REQUEST['contributorid'] < 0){
            $data['validation_errors'][] = 'contributorid cannot be of negative';
        }
        */
        if($_REQUEST['contributorid_field'] == ""){
            $data['validation_errors'][] = 'contributorid is required';
        } else{
            $contributor = $wpdb->get_row( 
                    $wpdb->prepare("
                            SELECT * FROM users WHERE user_login LIKE BINARY %s
                            ", $_REQUEST['contributorid_field']
                        )
                );
            if($contributor){
                $_REQUEST['contributorid'] = $contributor->userid;
            } else {
                $data['validation_errors'][] = 'Invalid contributor';
            }
        }
        if(count($data['validation_errors']) == 0){
            $searchtermcount = $wpdb->get_row( 
                    $wpdb->prepare("
                            SELECT COUNT(*) as searchtermcount
                            FROM searchterms
                            WHERE searchterms.partnerid = %d
                            ", $_REQUEST['edit']
                        )
                );
    //        echo "<pre>";
    //        print_r($searchtermcount);
    //        die();
            if($searchtermcount->searchtermcount > $_REQUEST['termsnumber']){
                $data['validation_errors'][] = 'Please delete already added terms before reducing terms number';
                return $data;
            }
            $wpdb->update( 
                    'partners', 
                    array( 
                            'name' => $_REQUEST['name'], 
                            'active' => $_REQUEST['active'] ,
                            'termsnumber' => $_REQUEST['termsnumber'],
                            'searchstartdate' => $_REQUEST['searchstartdate'],
                            'searchenddate' => $_REQUEST['searchenddate'],
                            'contributorid' => $_REQUEST['contributorid'],
                    ), 
                    array( 'partnerid' => $_REQUEST['edit'] ), 
                    array( 
                            '%s', 
                            '%s',
                            '%s',
                            '%s', 
                            '%s',
                            '%d',
                    ),
                    array( '%d' ) 
            );
            $data['success_message'][] = 'Partner Updated successfully';
    //        $_REQUEST = [];
        }

        return $data;
    }



    public static function getPartners(){
        $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
        $forbidden_partners_implode = implode(",", $forbidden_partners);
        global $wpdb;
        $partners = $wpdb->get_results( 
                "
                            SELECT * FROM partners
                            WHERE partnerid NOT IN($forbidden_partners_implode)
                            "
                        
            );
        return $partners;
    }
    public static function getTerms($partnerid = null){
        global $wpdb;
        $terms = [];
        if($partnerid){
            $terms_arr = $wpdb->get_results( 
                $wpdb->prepare("
                            SELECT searchterms.*, partners.name as partnername
                            FROM searchterms
                            INNER JOIN partners
                            ON searchterms.partnerid = partners.partnerid
                            WHERE searchterms.partnerid = %d
                            ", $partnerid
                        ) 
            );
            foreach($terms_arr as $term){
                $searchcount = $wpdb->get_row( 
                    $wpdb->prepare("
                            SELECT COUNT(*) as searchcount
                            FROM searches
                            WHERE searches.term = %s
                            ", $term->term
                        )
                );
                $term->searchcount = $searchcount->searchcount;
                $terms[] = $term;
            }
    //        echo "<pre>";
    //        print_r($searchcount);
    //        die();
        } else {
            $terms_arr = $wpdb->get_results( 
                "
                            SELECT searchterms.*, partners.name as partnername
                            FROM searchterms
                            INNER JOIN partners
                            ON searchterms.partnerid = partners.partnerid
                            "
                        
            );
            foreach($terms_arr as $term){
                $searchcount = $wpdb->get_row( 
                    $wpdb->prepare("
                            SELECT COUNT(*) as searchcount
                            FROM searches
                            WHERE searches.term = %s
                            ", $term->term
                        )
                );
                $term->searchcount = $searchcount->searchcount;
                $terms[] = $term;
            }
        }

        return $terms;
    }
    public static function getSubmitDeletePartner(){
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        global $wpdb;
        $partnerid = isset($_REQUEST['delete'])? $_REQUEST['delete']: 0;
        $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
        if(in_array($partnerid, $forbidden_partners) ){
            $data['validation_errors'][] = 'This partner is forbidden to be deleted';
        } else{

            $terms_deleted = $wpdb->delete( 'searchterms', array( 'partnerid' => $partnerid ) );

            $deleted = $wpdb->delete( 'partners', array( 'partnerid' => $partnerid ) );
            if($deleted) {
                $data['success_message'][] = 'Partner deleted successfully';
            } else {
                $data['validation_errors'][] = 'Error deleting Partner';
            }
        }
        return $data;
    }
    public static function getSubmitDeleteTerm(){
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        global $wpdb;
        $partnerid = isset($_REQUEST['partnerid'])? $_REQUEST['partnerid']: '';
        $term = isset($_REQUEST['term'])? $_REQUEST['term']: '';
        $active = isset($_REQUEST['active'])? $_REQUEST['active']: '';

        if($partnerid == '' || $term == '' || $active == ''){
            $data['validation_errors'][] = 'Invalid values';
        } else {
            $deleted = $wpdb->delete( 'searchterms', array( 'partnerid' => $partnerid, 'term'=>$term, 'active'=>$active ), array('%d', '%s', '%s') );
            if($deleted) {
                $data['success_message'][] = 'Term deleted successfully';
            } else {
                $data['validation_errors'][] = 'Error deleting Term';
            }
        }

        return $data;
    }

    public static function postSubmitAddTerm(){
        global $wpdb;
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        //validation
        if($_REQUEST['term'] == ""){
            $data['validation_errors'][] = 'Term is required';
        }
        if($_REQUEST['active'] == ""){
            $data['validation_errors'][] = 'Active is required';
        }
        if($_REQUEST['active'] != "T" && $_REQUEST['active'] != "F"){
            $data['validation_errors'][] = 'Active value is invalid';
        }

        if($_REQUEST['termstartdate'] == ""){
            $data['validation_errors'][] = 'Term Start Date is required';
        } 
        if($_REQUEST['termenddate'] == ""){
            $data['validation_errors'][] = 'Term End Date is required';
        }
        if($_REQUEST['partnerid'] == ""){
            
            if(is_numeric($_REQUEST['partnerid_field'])){
                $_REQUEST['partnerid'] = $_REQUEST['partnerid_field'];
            } else {
                $data['validation_errors'][] = 'partnerid is required';
            }
            
        } else if(!is_numeric($_REQUEST['partnerid'])){
            $data['validation_errors'][] = 'partnerid should be of type number';
        } elseif($_REQUEST['partnerid'] < 0){
            $data['validation_errors'][] = 'partnerid cannot be of negative';
        }
        if(count($data['validation_errors']) == 0){

            $term_already_present = $wpdb->get_results( 
                        $wpdb->prepare("
                            SELECT * FROM searchterms
                            WHERE 
                            searchterms.partnerid = %d
                            AND searchterms.term = %s
                                    ",
                                $_REQUEST['partnerid'],
                                $_REQUEST['term']
                                ) 
                     );
            if(count($term_already_present) > 0){
                $data['validation_errors'][] = 'Term is already added for this partner. ';
                return $data;
            }
            $term_already_present_for_this_timeperiod = $wpdb->get_results( 
                        $wpdb->prepare("
                            SELECT * FROM searchterms
                            WHERE 
                            searchterms.term = %s AND
                            searchterms.active = %s AND
                            (
                            (%s BETWEEN termstartdate AND termenddate) OR (%s BETWEEN termstartdate AND termenddate) OR
                            (termstartdate BETWEEN  %s AND %s) OR (termenddate BETWEEN %s AND %s)
                            )
                                    ",
                                $_REQUEST['term'],
                                $_REQUEST['active'],
                                $_REQUEST['termstartdate'],
                                $_REQUEST['termenddate'],
                                $_REQUEST['termstartdate'],
                                $_REQUEST['termenddate'],
                                $_REQUEST['termstartdate'],
                                $_REQUEST['termenddate']
                                ) 
                     );

            if(count($term_already_present_for_this_timeperiod)> 0){
                // finding the quota of partner
                $data['validation_errors'][] = 'This term is already active in this time period. Please add another starting and ending dates';
                return $data;
            } else {
                $partner_row = $wpdb->get_row( 
                        $wpdb->prepare("
                                    SELECT termsnumber FROM partners
                                    WHERE partnerid = %d
                                    ",
                                $_REQUEST['partnerid']
                                ) 
                    );
                $num_of_uploaded_terms = self::uploadedTermsCount($_REQUEST['partnerid']);
        //        echo "<pre>";
        //        print_r($searchterms_count);
        //        die();
                if($partner_row->termsnumber <= $num_of_uploaded_terms){
                    $data['validation_errors'][] = 'This partner terms quota is full';
                    return $data;
                }


                $wpdb->insert( 
                        'searchterms', 
                        array( 
                                'term' => $_REQUEST['term'], 
                                'active' => $_REQUEST['active'] ,
                                'termstartdate' => $_REQUEST['termstartdate'],
                                'termenddate' => $_REQUEST['termenddate'],
                                'partnerid' => $_REQUEST['partnerid'],
                        ), 
                        array( 
                                '%s', 
                                '%s',
                                '%s',
                                '%s',
                                '%d',
                        ) 
                );
                $data['success_message'][] = 'Term Added successfully';
                $_REQUEST = [];
            }



        }

        return $data;
    }
    public static function postSubmitEditTerm(){
    //    echo "<pre>";
    //    print_r($_REQUEST);
    //    die();
        global $wpdb;
        $data = [];
        $data['validation_errors'] = [];
        $data['success_message'] = [];

        //validation
        if($_REQUEST['term'] == ""){
            $data['validation_errors'][] = 'Term is required';
        }
        if($_REQUEST['active'] == ""){
            $data['validation_errors'][] = 'Active is required';
        }
        if($_REQUEST['active'] != "T" && $_REQUEST['active'] != "F"){
            $data['validation_errors'][] = 'Active value is invalid';
        }

        if($_REQUEST['termstartdate'] == ""){
            $data['validation_errors'][] = 'Term Start Date is required';
        } 
        if($_REQUEST['termenddate'] == ""){
            $data['validation_errors'][] = 'Term End Date is required';
        }
        if($_REQUEST['partnerid'] == ""){
            $data['validation_errors'][] = 'partnerid is required';
        } else if(!is_numeric($_REQUEST['partnerid'])){
            $data['validation_errors'][] = 'partnerid should be of type number';
        } elseif($_REQUEST['partnerid'] < 0){
            $data['validation_errors'][] = 'partnerid cannot be of negative';
        }
        if(count($data['validation_errors']) == 0){
            if($_REQUEST['term'] != $_REQUEST['old_term']){
                $term_already_present = $wpdb->get_results( 
                            $wpdb->prepare("
                                SELECT * FROM searchterms
                                WHERE 
                                searchterms.partnerid = %d
                                AND searchterms.term = %s
                                        ",
                                    $_REQUEST['partnerid'],
                                    $_REQUEST['term']
                                    ) 
                         );
                if(count($term_already_present) > 0){
        //            echo "<pre>";
        //            print_r($_REQUEST);
        //            die();
                    $data['validation_errors'][] = 'Term is already added for this partner. ';
                    return $data;
                }
            } else{
                $term_already_present_for_this_timeperiod = $wpdb->get_results( 
                            $wpdb->prepare("
                                SELECT * FROM searchterms
                                WHERE 
                                searchterms.term = %s AND
                                searchterms.active = %s AND
                                (
                                (%s BETWEEN termstartdate AND termenddate) OR (%s BETWEEN termstartdate AND termenddate) OR
                                (termstartdate BETWEEN  %s AND %s) OR (termenddate BETWEEN %s AND %s)
                                )
                                AND searchterms.partnerid != %d
                                        ",
                                    $_REQUEST['term'],
                                    $_REQUEST['active'],
                                    $_REQUEST['termstartdate'],
                                    $_REQUEST['termenddate'],
                                    $_REQUEST['termstartdate'],
                                    $_REQUEST['termenddate'],
                                    $_REQUEST['termstartdate'],
                                    $_REQUEST['termenddate'],
                                    $_REQUEST['old_partnerid']
                                    ) 
                         );

                if(count($term_already_present_for_this_timeperiod)> 0){
                    // finding the quota of partner
                    $data['validation_errors'][] = 'This term is already active in this time period. Please add another starting and ending dates';
                    return $data;
                } 
            }






            $wpdb->update( 
                    'searchterms', 
                    array( 
                            'term' => $_REQUEST['term'], 
                            'active' => $_REQUEST['active'] ,
                            'termstartdate' => $_REQUEST['termstartdate'],
                            'termenddate' => $_REQUEST['termenddate'],
                            'partnerid' => $_REQUEST['partnerid'],
                    ), 
                    array( 'partnerid' => $_REQUEST['old_partnerid'],
                        'term' => $_REQUEST['old_term'],
                        'active' => $_REQUEST['old_active']
                    ), 
                    array( 
                            '%s', 
                            '%s',
                            '%s',
                            '%s',
                            '%d',
                    ) ,
                    array( '%d', '%s', '%s' ) 
            );
    //        echo "<pre>";
    //        print_r($_REQUEST);
    //        echo "</pre>";
    //        echo $wpdb->last_query;
    //        die();

            $url ='admin.php?page=curriki_partners&action=edit_term&partnerid='.$_REQUEST['partnerid'].'&term='.$_REQUEST['term'].'&active='.$_REQUEST['active'];

            $_SESSION['success_message'] = 'Term Updated successfully';
    //        die('test');
    //        $data['success_message'][] = 'Term Updated successfully';
            wp_redirect($url);
            exit;
    //        $_REQUEST = [];
        }

        return $data;
    }
    
    


    public static function uploadedTermsCount($partnerid){
        global $wpdb;
        $searchterms_count = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT count(*) as num_of_uploaded_terms FROM searchterms
                                WHERE partnerid = %d
                                ",
                            $partnerid
                            ) 
                );
        return $searchterms_count->num_of_uploaded_terms;
    }
    public static function getContributorDetails($partnerid){
        global $wpdb;
        $contributor = $wpdb->get_row( 
                    $wpdb->prepare("
                                SELECT * from users
                                WHERE userid = %d
                                ",
                            $partnerid
                            ) 
                );
        return $contributor;
    }
}



add_action('wp_ajax_nopriv_partner_partnerid', 'ajax_partner_partnerid');
add_action('wp_ajax_partner_partnerid', 'ajax_partner_partnerid');

function ajax_partner_partnerid() {
    global $wpdb; 

    check_ajax_referer('terms', 'security');

    $partnerid_field = isset($_REQUEST['partnerid_field']) ? $_REQUEST['partnerid_field'] : '';

    $forbidden_partners = [1, 2, 3, 4, 1540, 1541, 40001];
    $forbidden_partners_implode = implode(",", $forbidden_partners);

    $partners = $wpdb->get_results( 
            $wpdb->prepare( 
        "
            SELECT partnerid as id, CONCAT(name, ' - ', partnerid) as text
            FROM partners
            WHERE ( name like %s 
            OR partnerid like %s )
            AND (partnerid NOT IN ($forbidden_partners_implode) )
            LIMIT 10
            ",
                    '%' . $wpdb->esc_like($partnerid_field) . '%',
                    '%' . $wpdb->esc_like($partnerid_field) . '%'
            )
    );
//    $wpdb->last_query;

    echo json_encode($partners);
    wp_die();
}

/*
* Finding contributorid for partners
*/

add_action('wp_ajax_nopriv_partner_contributorid', 'ajax_partner_contributorid');
add_action('wp_ajax_partner_contributorid', 'ajax_partner_contributorid');

function ajax_partner_contributorid() {
   global $wpdb; 

   check_ajax_referer('partners', 'security');

   $contributorid_field = isset($_REQUEST['contributorid_field']) ? $_REQUEST['contributorid_field'] : '';


   $contributors = $wpdb->get_results( 
       "
           SELECT userid as id, CONCAT(user_login, ' - ', userid) as text
           FROM users
           WHERE user_login like '%".$contributorid_field."%' 
                   OR userid like '%".$contributorid_field."%' 
           LIMIT 20
           "
   );

   echo json_encode($contributors);
   wp_die();
}