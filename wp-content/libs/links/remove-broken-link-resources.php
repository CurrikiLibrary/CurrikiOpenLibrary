<?php
/* 
 * 
 */

require_once('../../../wp-load.php');
include_once(__DIR__ . '/../functions.php');

// Remove inactive resources for broken links older then 45 days.
$linkResource = new LinkResourceCron();
$linkResource->remove();

/**
 * LinkResourceCron
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */
class LinkResourceCron
{
    /**
     * remove
     * 
     * Remove inactive resources for broken links older then 45 days.
     *
     * @return string response of remove
     */
    public function remove()
    {
        global $wpdb;

        $previousBrokenLinkResource = $wpdb->get_row("SELECT resourceid AS last_resourceid FROM deleted_broken_links_resources WHERE id = 0");
        $previousBrokenLinkResourceId = $previousBrokenLinkResource->last_resourceid;

        $nextBrokenLinkResourceId = $previousBrokenLinkResourceId + 500;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE deleted_broken_links_resources
                SET resourceid = %s
                WHERE id = 0
                ",
                $nextBrokenLinkResourceId
            )
        );

        $brokenLinkResources = $wpdb->get_results( 
            "
            SELECT DISTINCT resourceid
            FROM resources
            WHERE
                active = 'F'
                AND resourceid IN (
                    SELECT DISTINCT resourceid
                    FROM broken_links
                    WHERE resourceid > $previousBrokenLinkResourceId AND resourceid <= $nextBrokenLinkResourceId
                )
            ORDER BY resourceid ASC;
            "
        );

        if ($brokenLinkResources) {
            $this->removeInactivateResources($brokenLinkResources);
        }
    }

    /**
     * removeInactivateResources
     *
     * Remove Inactivate Resources
     *
     *
     * @param array $brokenLinkResources Resources with broken links
     * @return void
     */
    public function removeInactivateResources($brokenLinkResources)
    {
        $brokenLinkResourcesIds = [];
        $resourcesQuery = '';

        foreach ($brokenLinkResources as $brokenLinkResource) 
        {
            if (!empty($resourcesQuery))
                $resourcesQuery .= ',';

                $resourcesQuery .= "($brokenLinkResource->resourceid)";

            $brokenLinkResourcesIds[] = $brokenLinkResource->resourceid;
        }

        $this->saveDeletedBrokenLinksResources($brokenLinkResourcesIds, $resourcesQuery);

        deleteResourcesData($brokenLinkResourcesIds);
    }

    /**
     * saveDeletedBrokenLinksResources
     *
     * Save Deleted Broken Links Resources
     *
     *
     * @param array $brokenLinkResourceIds Ids of deleted broken links resources
     * @param string $resourcesQuery Resources subjectareas query
     * @return void
     */
    public function saveDeletedBrokenLinksResources($brokenLinkResourceIds, $resourcesQuery)
    {
        global $wpdb;
        $brokenLinkResourceIdsString = implode(",", $brokenLinkResourceIds);
        $queryJoiner = '';
        $resourcesQueryJoiner = '';

        $this->saveResourceSubjectareasQuery($brokenLinkResourceIdsString);

        $this->saveResourceEducationlevelsQuery($brokenLinkResourceIdsString);

        if($resourcesQuery) {
            $wpdb->query("INSERT INTO deleted_broken_links_resources
                (resourceid)
                VALUES
                $resourcesQuery");
        }
    }

    /**
     * saveResourceSubjectareasQuery
     *
     * Save Resource Subjectareas
     *
     *
     * @param string $brokenLinkResourceIdsString Comma separated Ids of deleted broken links resources
     * @return void
     */
    public function saveResourceSubjectareasQuery($brokenLinkResourceIdsString)
    {
        global $wpdb;
        $resourcesSubjectareasQuery = '';

        $resourcesSubjectareas = $wpdb->get_results(
            "
            SELECT DISTINCT resourceid, subjectareaid
            FROM resource_subjectareas
            WHERE
                resourceid IN ($brokenLinkResourceIdsString)
            ORDER BY resourceid ASC;
            "
        );

        if ($resourcesSubjectareas) {
            foreach ($resourcesSubjectareas as $resourcesSubjectarea) {
                if (!empty($resourcesSubjectareasQuery))
                    $resourcesSubjectareasQuery .= ',';

                $resourcesSubjectareasQuery .= "($resourcesSubjectarea->resourceid,$resourcesSubjectarea->subjectareaid)";
            }
        }

        if($resourcesSubjectareasQuery) {
            $wpdb->query("INSERT INTO deleted_broken_links_resources_subjectareas
                (resourceid, subjectareaid)
                VALUES
                $resourcesSubjectareasQuery");
        }
    }

    /**
     * saveResourceEducationlevelsQuery
     *
     * Save Resource Educationlevels
     *
     *
     * @param string $brokenLinkResourceIdsString Comma separated Ids of deleted broken links resources
     * @return void
     */
    public function saveResourceEducationlevelsQuery($brokenLinkResourceIdsString)
    {
        global $wpdb;
        $resourcesEducationlevelsQuery = '';

        $resourcesEducationlevels = $wpdb->get_results(
            "
            SELECT DISTINCT resourceid, educationlevelid
            FROM resource_educationlevels
            WHERE
                resourceid IN ($brokenLinkResourceIdsString)
            ORDER BY resourceid ASC;
            "
        );
        
        if ($resourcesEducationlevels) {
            foreach ($resourcesEducationlevels as $resourcesEducationlevel) {
                if (!empty($resourcesEducationlevelsQuery))
                    $resourcesEducationlevelsQuery .= ',';

                $resourcesEducationlevelsQuery .= "($resourcesEducationlevel->resourceid,$resourcesEducationlevel->educationlevelid)";
            }
        }

        if($resourcesEducationlevelsQuery) {
            $wpdb->query("INSERT INTO deleted_broken_links_resources_educationlevels
                (resourceid, levelid)
                VALUES
                $resourcesEducationlevelsQuery");
        }
    }
}
