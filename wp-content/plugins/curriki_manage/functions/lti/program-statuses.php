<?php

function laasGetProgramEntity($resourceid){
    global $wpdb;
    $query = "
        select rs.resourceid, rs.title, rs.pageurl, 0 as parent_id, rs.resourceid as program_id, 'program' as laas_entity, ex_mod.id as external_module_id
        from resources rs
        INNER JOIN wcl_external_module as ex_mod on ex_mod.external_id = rs.resourceid
        where rs.resourceid=$resourceid
        AND ex_mod.enable_user_registration = 1
        UNION ALL
        select rs.resourceid, rs.title, rs.pageurl, ce_p.collectionid as parent_id, ce_p.collectionid as program_id, 'playlist' as laas_entity, ex_mod.id as external_module_id
        from resources rs
        INNER JOIN collectionelements as ce_p on ce_p.resourceid = rs.resourceid
        INNER JOIN wcl_external_module as ex_mod on ex_mod.external_id = ce_p.collectionid
        where rs.resourceid=$resourceid
        AND ex_mod.enable_user_registration = 1
        UNION ALL
        select rs.resourceid, rs.title, rs.pageurl, ce_p.collectionid as parent_id, ce_p_of_p.collectionid as program_id, 'activity' as laas_entity, ex_mod.id as external_module_id
        from resources rs
        INNER JOIN collectionelements as ce_p on ce_p.resourceid = rs.resourceid
        INNER JOIN collectionelements as ce_p_of_p on ce_p_of_p.resourceid = ce_p.collectionid
        INNER JOIN wcl_external_module as ex_mod on ex_mod.external_id = ce_p_of_p.collectionid
        where rs.resourceid=$resourceid
        AND ex_mod.enable_user_registration = 1
    ";
    return $wpdb->get_row($query, ARRAY_A);
}

function laasGetProgramEntityLogoutStatus($resourceid){
    $program_entity = laasGetProgramEntity($resourceid);
    $status = "";
    if( !is_null($program_entity) ){
        if($program_entity['laas_entity'] === 'program'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $login_button = '<a class="btn btn-primary class-header-menu-login" id="oer-login-program">Log In</a>';
            $signup_button = '<a class="btn btn-yellow class-header-menu-signup" id="oer-signup-program">Create an Account</a>';
            $status.= '<div class="button-group">'.$login_button.' '.$signup_button.'</div>';
        }elseif($program_entity['laas_entity'] === 'playlist'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $program_link = '<a href="'.site_url('/oer/'.$program['pageurl']).'">'.$program['title'].'</a>';
            $status.= "<p>This Playlist is part of $program_link</p>";
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $login_button = '<a class="btn btn-primary class-header-menu-login" id="oer-login-program">Log In</a>';
            $signup_button = '<a class="btn btn-yellow class-header-menu-signup" id="oer-signup-program">Create an Account</a>';
            $status.= '<div class="button-group">'.$login_button.' '.$signup_button.'</div>';
        }elseif($program_entity['laas_entity'] === 'activity'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $program_link = '<a href="'.site_url('/oer/'.$program['pageurl']).'">'.$program['title'].'</a>';
            $status.= "<p>This Activity is part of $program_link</p>";
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $login_button = '<a class="btn btn-primary class-header-menu-login" id="oer-login-program">Log In</a>';
            $signup_button = '<a class="btn btn-yellow class-header-menu-signup" id="oer-signup-program">Create an Account</a>';
            $status.= '<div class="button-group">'.$login_button.' '.$signup_button.'</div>';
        }
    }
    return $status;
}

function laasGetProgramEntityLoginNotEnrolStatus($resourceid, $program_entity){
    //$program_entity = laasGetProgramEntity($resourceid);
    global $laas_program_name;
    global $laas_program_slug;
    $status = "";
    if( !is_null($program_entity) ){
        if($program_entity['laas_entity'] === 'program'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $laas_program_name = $program['title'];
            $laas_program_slug = $program['pageurl'];
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $status.= '<div class="button-group"><a class="btn btn-primary" id="oer-register-program" onclick="oerRegisterProgram('.$program_entity['external_module_id'].');">Enroll Now</a></div>';
        }elseif($program_entity['laas_entity'] === 'playlist'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $laas_program_name = $program['title'];
            $laas_program_slug = $program['pageurl'];
            $program_link = '<a href="'.site_url('/oer/'.$program['pageurl']).'">'.$program['title'].'</a>';
            $status.= "<p>This Playlist is part of $program_link</p>";
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $status.= '<div class="button-group"><a class="btn btn-primary" id="oer-register-program" onclick="oerRegisterProgram('.$program_entity['external_module_id'].');">Enroll Now</a></div>';
        }elseif($program_entity['laas_entity'] === 'activity'){
            global $wpdb;
            $query = "select * from resources where resourceid = ".$program_entity['program_id'];
            $program = $wpdb->get_row($query, ARRAY_A);
            $laas_program_name = $program['title'];
            $laas_program_slug = $program['pageurl'];
            $program_link = '<a href="'.site_url('/oer/'.$program['pageurl']).'">'.$program['title'].'</a>';
            $status.= "<p>This Activity is part of $program_link</p>";
            $status.= "<p>By enrolling in the \"".$program['title']."\" program you can track your progress and earn a statement of participation, all for free.</p>";
            $status.= '<div class="button-group"><a class="btn btn-primary" id="oer-register-program" onclick="oerRegisterProgram('.$program_entity['external_module_id'].');">Enroll Now</a></div>';
        }
    }
    return $status;
}


function laasGetEnrollStatus($resourceid, $user_id){
    $status_not_enroll = "";
    $program_entity = laasGetProgramEntity($resourceid);        
    if( !is_null($program_entity) ){
        $program_id = $program_entity['program_id'];
        $user_registration_to_program = oerIsUserRegisterToProgram($program_id, $user_id);
        if( is_null($user_registration_to_program) ){
            $status_not_enroll = laasGetProgramEntityLoginNotEnrolStatus($resourceid, $program_entity);
        }
    }
    return $status_not_enroll;
}