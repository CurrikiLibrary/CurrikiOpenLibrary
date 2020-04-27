<?php
/* 
 * 
 */

require_once('../../../wp-load.php');
include_once(__DIR__ . '/../functions.php');

// Replace docx download link to embed from google docs in resource content
$embedDocx = new EmbedDocxCron();
$embedDocx->replace();

/**
 * EmbedDocxCron
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */
class EmbedDocxCron
{
    /**
     * replace
     * 
     * Replace docx download link to embed from google docs in resource content
     *
     * @return string response of replace
     */
    public function replace()
    {
        global $wpdb;

        $docxDownloadResources = $wpdb->get_results( 
            '
            SELECT resourceid, content 
            FROM resources 
            WHERE 
                content LIKE "%.docx%"
                AND content LIKE "%asset-display-download%"
            '
        );

        if ($docxDownloadResources) {
            $this->replaceDownloadWithEmbedDocx($docxDownloadResources);
        }
    }

    /**
     * replaceDownloadWithEmbedDocx
     *
     * Replace Download With Embed Docx
     *
     *
     * @param array $docxDownloadResources Resources with docx download links
     * @return void
     */
    public function replaceDownloadWithEmbedDocx($docxDownloadResources)
    {
        foreach ($docxDownloadResources as $docxDownloadResource) 
        {
            $processedContent = $this->processContent($docxDownloadResource->content);

            if (!empty($processedContent)) {
                $this->updateContent($docxDownloadResource->resourceid, $processedContent);
            }
        }
    }

    /**
     * processContent
     *
     * Replace Download Button In Content With Embed Docx
     *
     *
     * @param array $content Content of resource with download button
     * @return string response of process content
     */
    public function processContent($content)
    {
        $content = str_replace(array("\n", "\t", "\r"), '', $content);

        $newDom = new DOMDocument();
        libxml_use_internal_errors(TRUE); //disable libxml errors

        @$newDom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $content = $newDom->saveHTML();

        $main_xpath = new DOMXPath(@$newDom);
        if (!$main_xpath) {
            return '';
        }

        $contentReplacements = [];

        $elements = $main_xpath->query("//div[@class='asset-display-download-inner']");

        if (!is_null($elements)) {
            foreach ($elements as $element) {
                $nodes = $element->childNodes;
                foreach ($nodes as $node) {
                    $childNodes = $node->childNodes;
                    if ($childNodes->length > 0) {
                        foreach ($childNodes as $childNode) {
                            if (stripos($childNode->nodeValue, '.docx') !== false) {
                                $childNodesUrl = $childNode->getAttribute('href');

                                $newHtml = '<div class="oembedall-container" style="display: block; margin-left: auto; margin-right: auto; text-align:center;width:100%"><span class="oembedall-closehide">↓</span>';
                                $newHtml .= '<a href="' . $childNodesUrl . '" data-mce-href="' . $childNodesUrl . '" target="_blank">' . $childNode->nodeValue  . '</a><br/> ';
                                $newHtml .= '<iframe src="https://docs.google.com/viewer?embedded=true&url=' . urlencode($childNodesUrl) . '" width="98%" height="600" style="border: none;"></iframe>';
                                $newHtml .= '</div> <br/> ';

                                $oldHtml = $newDom->saveHTML($element->parentNode);
                                $oldHtml = str_replace(array("\n", "\t", "\r"), '', $oldHtml);
                                $oldHtml = mb_convert_encoding($oldHtml, 'HTML-ENTITIES', 'UTF-8');

                                $contentReplacements[] = [$oldHtml, $newHtml];
                            }
                        }
                    }
                }
            }
        }

        if (empty($contentReplacements))
            return '';

        foreach ($contentReplacements as $contentReplacement) {
            $content = str_replace($contentReplacement[0], $contentReplacement[1], $content);
        }

        return $content;
    }

    /**
     * inactivateResources
     *
     * Inactivate Resources
     *
     * @param array $resourceId Id of resource with content
     * @param array $content Content of resource to be replaced
     * @return void
     */
    public function updateContent($resourceId, $content)
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE resources
                 SET content=%s
                 WHERE resourceid = $resourceId
                 ",
                $content
            )
        );
    }
}
