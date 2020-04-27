<?php
/* 
 * 
 */

require_once('../../../wp-load.php');

// Log the broken links in resources content
$resourceLinks = new LinkCron();
$resourceLinks->log();

/**
 * LinkCron
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */
class LinkCron
{
    private $resourceids = '';
    private $brokenLinks = '';

    /**
     * log
     * 
     * Log the broken links in resources content
     *
     * @return string response of log
     */
    public function log()
    {
        global $wpdb;

        $previousBrokenLinkResource = $wpdb->get_row("SELECT url AS last_resourceid FROM broken_links WHERE resourceid = 11413");

        $previousBrokenLinkResourceId = $previousBrokenLinkResource->last_resourceid;
        $nextBrokenLinkResourceId = $previousBrokenLinkResourceId + 500;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE broken_links
                SET `url`= %s
                WHERE resourceid = 11413
                ",
                $nextBrokenLinkResourceId
            )
        );

        $resources = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM resources where resourceid > $previousBrokenLinkResourceId AND resourceid <= %d AND approvalstatus = 'approved' AND active = 'T'",
                $nextBrokenLinkResourceId
            )
        );

        $this->extractUrls($resources);

        if (!empty($this->brokenLinks))
            $this->logBrokenLinks();

        /*
        if (!empty($this->resourceids))
            $this->inactivateResources();
        */
    }

    /**
     * extractUrls
     *
     * Extract link urls from resource content
     *
     *
     * @param array $resources Resources
     * @return void
     */
    public function extractUrls($resources)
    {
        foreach ($resources as $resource) {
            $DOM = new DOMDocument();
            //load the html string into the DOMDocument
            $DOM->loadHTML($resource->content);
            //get a list of all <A> tags
            $a = $DOM->getElementsByTagName('a');
            //loop through all <A> tags
            foreach ($a as $link) {
                //get the href attribute of the <A> tag.
                $url = $link->getAttribute('href');
                $status = $this->checkUrl($url);

                if ($status > 299) {
                    if (!empty($this->resourceids))
                        $this->resourceids .= ',';

                    $this->resourceids .= $resource->resourceid;

                    if (!empty($this->brokenLinks))
                        $this->brokenLinks .= ',';

                    $this->brokenLinks .= "($resource->resourceid, '$url', $status)";
                }
            }
        }
    }

    /**
     * checkUrl
     *
     * Check url status code
     *
     *
     * @param string $url Extracted link url from resource content
     * @return string $status Url status code
     */
    public function checkUrl($url)
    {
        usleep(500000);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Connection:keep-alive',
            'Cache-Control:max-age=0',
            'Upgrade-Insecure-Requests:1',
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate, br',
            'Accept-Language:nl-NL,nl;q=0.9,en-US;q=0.8,en;q=0.7',
        ));

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $httpCode;
    }

    /**
     * logBrokenLinks
     *
     * Log broken links
     *
     *
     * @return void
     */
    public function logBrokenLinks()
    {
        global $wpdb;

        $wpdb->query("INSERT INTO broken_links
            (resourceid, url, status)
            VALUES
            $this->brokenLinks");
    }

    /**
     * inactivateResources
     *
     * Inactivate Resources
     *
     *
     * @return void
     */
    public function inactivateResources()
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE resources
                 SET `indexrequired`='T', `active` = %s
                 WHERE resourceid IN ($this->resourceids) and remove <> 'T'
                 ",
                'F'
            )
        );
    }
}
