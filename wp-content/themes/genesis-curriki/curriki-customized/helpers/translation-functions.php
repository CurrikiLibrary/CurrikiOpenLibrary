<?php

function cur_angular_controllers_translations()
{
    $translation_array = array(
            'topic_area_ml' => __('Select a Topic Area', 'curriki'),
            'alignment_tag_ml' => __('Select Alignment Tag', 'curriki'),
            'loading_ml' => __('Please Wait.... Loading......', 'curriki')
    );
    return $translation_array;
}

function cur_get_current_language($ICL_LANGUAGE_CODE)
{
  $current_language = "eng";
  if( isset($ICL_LANGUAGE_CODE) )
  {
        switch ($ICL_LANGUAGE_CODE)
        {
            case 'es':
            $current_language = "spa";
            break;
            case 'ja':
            $current_language = "jpn";
            break;
            case 'fi':
            $current_language = "fin";
            break;        
        }
  }
  return $current_language;
}

function cur_instructiontypes_query($current_language)
{
    $q_instructiontypes = "
                            SELECT 
                                instructiontypes.instructiontypeid AS instructiontypeid,
                                instructiontypes_ml.displayname AS displayname
                            FROM instructiontypes 
                                INNER JOIN instructiontypes_ml ON instructiontypes_ml.instructiontypeid = instructiontypes.instructiontypeid
                                WHERE instructiontypes_ml.language = '$current_language'
                            order by instructiontypes.displayname
                          ";    
    return $q_instructiontypes;
}
function cur_instructiontypes_for_search_query($current_language)
{
    $q_instructiontypes = "
                            SELECT 
                                instructiontypes.instructiontypeid AS instructiontypeid,
                                instructiontypes.name AS name,
                                instructiontypes_ml.displayname AS displayname
                            FROM instructiontypes 
                                INNER JOIN instructiontypes_ml ON instructiontypes_ml.instructiontypeid = instructiontypes.instructiontypeid
                                WHERE instructiontypes_ml.language = '$current_language'
                            order by instructiontypes.displayname
                          ";    
    return $q_instructiontypes;
}

function cur_instructiontypes_by_name_query($current_language,$name)
{
    $q_instructiontypes = "
                            SELECT                                 
                                instructiontypes_ml.displayname AS displayname
                            FROM instructiontypes 
                                INNER JOIN instructiontypes_ml ON instructiontypes_ml.instructiontypeid = instructiontypes.instructiontypeid
                                WHERE instructiontypes_ml.language = '$current_language'
                                    AND instructiontypes.name = '$name'                            
                          ";    
    return $q_instructiontypes;
}

function cur_languages_query($current_language, $language = null)
{
    $q_languages = "";
    if($language!=null)
    {
        $q_languages = "
                        SELECT
                            languages.language AS language_orignal,
                            languages.displayname AS displayname_orignal,
                            languages.active AS active,
                            languages_ml.language AS language,
                            languages_ml.displaylanguage AS displaylanguage,
                            languages_ml.displayname AS displayname
                        FROM languages 
                            INNER JOIN languages_ml ON languages.language = languages_ml.language
                            WHERE languages_ml.displaylanguage = '$current_language'
                            AND languages_ml.language = '$language'
                        ORDER BY displayname_orignal
                      ";    
    }else{
       $q_languages = "
                        SELECT
                            languages.language AS language_orignal,
                            languages.displayname AS displayname_orignal,
                            languages.active AS active,
                            languages_ml.language AS language,
                            languages_ml.displaylanguage AS displaylanguage,
                            languages_ml.displayname AS displayname
                        FROM languages 
                            INNER JOIN languages_ml ON languages.language = languages_ml.language
                            WHERE languages_ml.displaylanguage = '$current_language'
                        ORDER BY displayname_orignal
                      "; 
    }
    
    return $q_languages;
}

function cur_educationlevels_query($current_language)
{
    $q_educationlevels = "
                    SELECT 
                    educationlevels.levelid as levelid_orignal,
                    educationlevels.parentid as parentid,
                    educationlevels.identifier as identifier,
                    educationlevels.active as active,
                    educationlevels.displayseqno as displayseqno,
                    educationlevels.displayname as displayname_orignal,
                    educationlevels_ml.levelid as levelid,
                    educationlevels_ml.language as language,
                    educationlevels_ml.displayname as displayname 
                    FROM educationlevels 
                    INNER JOIN educationlevels_ml ON
                    educationlevels.levelid = educationlevels_ml.levelid
                    WHERE 
                    educationlevels.displayseqno != '' 
                    AND educationlevels.active = 'T' 
                    AND educationlevels_ml.language = '$current_language'
                    ORDER BY educationlevels.displayseqno ASC
                  ";
    return $q_educationlevels;
}


function cur_subjectareas_for_search_query($current_language)
{
    $q_subjects =" 
                        SELECT 
                        subjectareas.subjectareaid as subjectareaid_orignal,
                        subjectareas.subjectid as subjectid,
                        subjectareas.subjectarea as subject,
                        subjectareas.displayname as displayname_orignal,
                        subjectareas_ml.subjectareaid as subjectareaid,
                        subjectareas_ml.displayname as displayname,
                        subjectareas_ml.language as language,
                        CONCAT(subjects.subject,':',subjectareas.subjectarea) subsubjectarea
                        FROM subjectareas
                        JOIN subjects on subjects.subjectid = subjectareas.subjectid
                        INNER JOIN subjectareas_ml
                        ON subjectareas.subjectareaid = subjectareas_ml.subjectareaid
                          WHERE                             
                            subjectareas_ml.language = '$current_language'
                            AND subjects.subjectid IN (1,2,7,9,10,11,13)
                        ORDER BY subjectareas.displayname asc
                      ";
    return $q_subjects;
}

function cur_subjectareas_query($current_language,$subjectid=null)
{
        
    $q_subjects = "";
    if($subjectid!=null)
    {
        $q_subjects =" 
                        SELECT 
                        subjectareas.subjectareaid as subjectareaid_orignal,
                        subjectareas.subjectid as subjectid,
                        subjectareas.subjectarea as subject,
                        subjectareas.displayname as displayname_orignal,
                        subjectareas_ml.subjectareaid as subjectareaid,
                        subjectareas_ml.displayname as displayname,
                        subjectareas_ml.language as language
                        FROM subjectareas
                        INNER JOIN subjectareas_ml
                        ON subjectareas.subjectareaid = subjectareas_ml.subjectareaid
                          WHERE 
                            subjectareas.subjectid = $subjectid AND
                            subjectareas_ml.language = '$current_language'
                        ORDER BY subjectareas.displayname asc
                      ";
    }else{
        $q_subjects =" 
                        SELECT 
                        subjectareas.subjectareaid as subjectareaid_orignal,
                        subjectareas.subjectid as subjectid,
                        subjectareas.subjectarea as subject,
                        subjectareas.displayname as displayname_orignal,
                        subjectareas_ml.subjectareaid as subjectareaid,
                        subjectareas_ml.displayname as displayname,
                        subjectareas_ml.language as language
                        FROM subjectareas
                        INNER JOIN subjectareas_ml
                        ON subjectareas.subjectareaid = subjectareas_ml.subjectareaid
                          WHERE                             
                            subjectareas_ml.language = '$current_language'
                        ORDER BY subjectareas.displayname asc
                      ";
    }
    
    return $q_subjects;
}

function cur_subjectareas_by_displayname_query($current_language,$displayname)
{
 
    $displayname = trim($displayname);
    $q_subjects =" 
                    SELECT                     
                    distinct(subjectareas_ml.displayname) as displayname                    
                    FROM subjectareas
                    INNER JOIN subjectareas_ml
                    ON subjectareas.subjectareaid = subjectareas_ml.subjectareaid
                      WHERE                             
                        subjectareas_ml.language = '$current_language'
                            AND subjectareas.displayname = '$displayname'                    
                  ";       
    return $q_subjects;
}


function cur_subjects_query($current_language)
{
    $q_subjects = "
                    SELECT 
                    subjects.subjectid as subjectid_orignal,
                    subjects.subject as subject,
                    subjects.displayname as displayname_orignal,
                    subjects_ml.subjectid as subjectid,
                    subjects_ml.displayname as displayname,
                    subjects_ml.language as language
                    FROM subjects
                    INNER JOIN subjects_ml
                    ON subjects.subjectid = subjects_ml.subjectid
                    WHERE subjects_ml.language = '$current_language'
                        AND subjects.subjectid IN (1,2,7,9,10,11,13)
                ORDER BY subjects.displayname asc
                    limit 1000
                  ";
    return $q_subjects;
}
function cur_subjects_by_displayname_query($current_language,$displayname)
{
    $displayname = trim($displayname);
    $q_subjects = "
                    SELECT                     
                    subjects_ml.displayname as displayname                    
                    FROM subjects
                    INNER JOIN subjects_ml
                    ON subjects.subjectid = subjects_ml.subjectid
                    WHERE subjects_ml.language = '$current_language'
                        AND subjects.displayname = '$displayname'                
                  ";
    return $q_subjects;
}

function cur_states_query($current_language,$state_abbrv=null)
{
    $q_states = "";            
    if($state_abbrv != null)
    {
        $q_states = "
                  SELECT 
                      states.state_id as state_id_orignal,
                      states.state_name as state_name_orignal,
                      states.state_abbr as state_abbr,                            
                      states_ml.state_id as state_id,
                      states_ml.displayname as state_name
                      FROM states
                      INNER JOIN states_ml
                      ON states.state_id = states_ml.state_id
                      WHERE states_ml.language = '$current_language'
                          AND states.state_name = '$state_abbrv'
                  ORDER BY states.state_name asc
                ";
    }else{
        $q_states = "
                  SELECT 
                      states.state_id as state_id_orignal,
                      states.state_name as state_name_orignal,
                      states.state_abbr as state_abbr,                            
                      states_ml.state_id as state_id,
                      states_ml.displayname as state_name
                      FROM states
                      INNER JOIN states_ml
                      ON states.state_id = states_ml.state_id
                      WHERE states_ml.language = '$current_language'
                  ORDER BY states.state_name asc
                ";
    }
    
    return $q_states;
}

function cur_countries_query($current_language,$country = null)
{
    $q_countries = "";
    if($country == null)
    {
        $q_countries = "
                        SELECT 
                            countries.country as country_orignal,
                            countries.displayname as displayname_orignal,                            
                            countries_ml.country as country,                        
                            countries_ml.displayname as displayname,
                            countries_ml.language as language
                            FROM countries 
                            INNER JOIN countries_ml
                            ON countries.country = countries_ml.country
                            WHERE countries_ml.language = '$current_language'
                        ORDER BY countries.displayname asc
                     ";
    }else{
        $q_countries = "
                        SELECT 
                            countries.country as country_orignal,
                            countries.displayname as displayname_orignal,                            
                            countries_ml.country as country,                        
                            countries_ml.displayname as displayname,
                            countries_ml.language as language
                            FROM countries 
                            INNER JOIN countries_ml
                            ON countries.country = countries_ml.country
                            WHERE countries_ml.language = '$current_language'
                                AND countries.country = '$country'
                        ORDER BY countries.displayname asc
                     ";
    }    
    return $q_countries;
}

function cur_featureditems_ml_query($current_language,$location,$cur_date)
{
    $q_featured_items = "
                        SELECT 
                            featureditems.featureditemid,
                            featureditems.location,
                            featureditems.itemidtype,
                            featureditems.itemid,
                            featureditems.featuredstartdate,
                            featureditems.featuredenddate,
                            featureditems_ml.displaytitle,
                            featureditems_ml.featuredtext,
                            featureditems.image,
                            featureditems.displayseqno,
                            featureditems.active,
                            featureditems.link
                        FROM featureditems
                        INNER JOIN featureditems_ml
                        ON featureditems.featureditemid = featureditems_ml.featureditemid
                        WHERE 
                        featureditems_ml.language = '$current_language'
                        AND featureditems.location = '$location'
                        AND (featureditems.active = 'T' OR featureditems.active = '1') 
                        AND featureditems.featuredstartdate < '$cur_date' AND featureditems.featuredenddate > '$cur_date' AND featureditems.displayseqno != '' ORDER BY featureditems.displayseqno ASC
                        ";
    return $q_featured_items;
}

function cur_convert_to_utf_to_html($str)
{
    return preg_replace('/u([\da-fA-F]{4})/', '&#x\1;', $str);
}