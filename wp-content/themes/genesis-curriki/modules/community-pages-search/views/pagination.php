<?php
global $search;
$size = $search->request['size'];
$start = $search->request['start'];
$found = $search->status['found'] > 10000 ? 10000 : $search->status['found'];
$page = $start ? round($start / $size) : 0;
$pages = $found / $size;

$startPagination = 0;
$endPagination = 10;
$totalPagination = 10;

if ($page - round($totalPagination / 2) < 0) {
    $startPagination = 0;
} else {
    $startPagination = $page - round($totalPagination / 2);
}
if ($page + round($totalPagination / 2) > $pages) {
    $endPagination = $pages;
} else {
    $endPagination = $page + round($totalPagination / 2);
}

$url = get_bloginfo('url') . "/" . $_SERVER['REQUEST_URI'];
if (!strpos($url, "&start=")) {
    $url .= "&start=0";
    $search->request['start'] = 0;
}
$firstURL = str_replace("&start=" . $search->request['start'], "&start=0", $url);
$prevURL = str_replace("&start=" . $search->request['start'], "&start=" . intval($start - $totalPagination), $url);
$nextURL = str_replace("&start=" . $search->request['start'], "&start=" . intval($start + $totalPagination), $url);
$lastURL = str_replace("&start=" . $search->request['start'], "&start=" . intval($found - $totalPagination), $url);
?>
<div class="pagination" >
    <a class="pagination-first" href="<?php echo $firstURL; ?>" ><span class="fa fa-angle-double-left"></span></a>
    <a class="pagination-previous" href="<?php echo $prevURL; ?>" ><span class="fa fa-angle-left"></span> Previous</a>
    <?php
    for ($i = $startPagination; $i < $endPagination; $i++) {
        $pageURL = str_replace("&start=" . $search->request['start'], "&start=" . intval($i * $size), $url);
        echo '<a class="pagination-num ' . ($page == $i ? 'current disabled' : '' ) . '" href="' . $pageURL . '"> ' . intval($i + 1) . ' </a>';
    }
    ?>
    <a class="pagination-next" href="<?php echo $nextURL; ?>" >Next <span class="fa fa-angle-right"></span></a>
    <a class="pagination-last" href="<?php echo $lastURL; ?>" ><span class="fa fa-angle-double-right"></span></a>
</div>