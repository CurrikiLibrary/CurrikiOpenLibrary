<?php

require_once( '../../../../../wp-load.php' );
$pageurl = addslashes($_GET['pageurl']);

if (strpos($pageurl, 'WebHome')) {
  $group_q = "select url from groups where name='" . str_replace('/WebHome', '', $pageurl) . "'";
  $group = $wpdb->get_row($group_q);
  $newurl = get_bloginfo('url') . '/groups/' . $group->url;
} elseif (isset($_GET['user'])) {
  $newurl = get_bloginfo('url') . '/members/' . str_replace('XWiki.', '', $_GET['user']);
} else {
  $resource_q = "select pageurl from resources where oldurl = 'http://curriki.org/xwiki/bin/view/" . $pageurl . "'";
  $resource = $wpdb->get_row($resource_q);
  $pageurl_val = is_object($resource) ? $resource->pageurl : "";
  $newurl = get_bloginfo('url') . '/oer/' . $pageurl_val;
}
header("HTTP/1.1 301 Moved Permanently");
header("location:" . $newurl);
