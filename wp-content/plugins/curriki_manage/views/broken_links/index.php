<?php

global $wpdb;

if (isset($_GET["action"]) && $_GET["action"] === "deleted") {

    $subjects = array();

    $sql = "SELECT s.displayname subjectname, sa.displayname subjectareaname, count(dblrs.resourceid) deletedresources
            FROM deleted_broken_links_resources_subjectareas dblrs
            INNER JOIN subjectareas sa ON dblrs.subjectareaid = sa.subjectareaid
            INNER JOIN subjects s ON sa.subjectid = s.subjectid
            GROUP BY dblrs.subjectareaid
            ORDER BY subjectname ASC, subjectareaname ASC";

    $subjects = $wpdb->get_results($sql, 'ARRAY_A');

    $subjectsData = array();

    foreach ($subjects as $subject) {
        if(isset($subjectsData[$subject['subjectname']])) {
            $subjectsData[$subject['subjectname']][0] = $subjectsData[$subject['subjectname']][0] + $subject['deletedresources'];
            $subjectsData[$subject['subjectname']][1][] = [$subject['subjectareaname'], (int) $subject['deletedresources']];
        }
        else {
            $subjectsData[$subject['subjectname']] = [
                $subject['deletedresources'],
                [
                    [$subject['subjectname'], 'Deleted Resources'],
                    [$subject['subjectareaname'], (int) $subject['deletedresources']]
                ]
            ];
        }
    }

    $subjectsChart = array();
    $subjectsChart[] = ['Subject', 'Deleted Resources'];

    foreach ($subjectsData as $subjectName => $subjectData) {
        $subjectsChart[] = [$subjectName, (int) $subjectData[0]];
    }

    $educationlevels = array();

    $sql = "SELECT elp.displayname parentname, el.displayname levelname, count(dblre.resourceid) deletedresources
            FROM deleted_broken_links_resources_educationlevels dblre
            INNER JOIN educationlevels el ON dblre.levelid = el.levelid
            INNER JOIN educationlevels elp ON el.parentid = elp.levelid
            GROUP BY dblre.levelid
            ORDER BY parentname ASC, levelname ASC";

    $educationlevels = $wpdb->get_results($sql, 'ARRAY_A');

    $educationlevelsData = array();

    foreach ($educationlevels as $educationlevel) {
        if(isset($educationlevelsData[$educationlevel['parentname']])) {
            $educationlevelsData[$educationlevel['parentname']][0] = $educationlevelsData[$educationlevel['parentname']][0] + $educationlevel['deletedresources'];
            $educationlevelsData[$educationlevel['parentname']][1][] = [$educationlevel['levelname'], (int) $educationlevel['deletedresources']];
        }
        else {
            $educationlevelsData[$educationlevel['parentname']] = [
                $educationlevel['deletedresources'],
                [
                    [$educationlevel['parentname'], 'Deleted Resources'],
                    [$educationlevel['levelname'], (int) $educationlevel['deletedresources']]
                ]
            ];
        }
    }

    $educationlevelsChart = array();
    $educationlevelsChart[] = ['Education Level', 'Deleted Resources'];

    foreach ($educationlevelsData as $educationlevelName => $educationlevelData) {
        $educationlevelsChart[] = [$educationlevelName, (int) $educationlevelData[0]];
    }

    require_once 'deleted.php';
} else {
    require_once 'list.php';
}