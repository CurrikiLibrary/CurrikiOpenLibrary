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
<div class="text-center">
    <ul class="pagination2">
        <li class="page-item"><a class="page-link" href="<?php echo $firstURL; ?>"><i class="fa fa-angle-double-left"></i> </a></li>
        <li class="page-item"><a class="page-link" href="<?php echo $prevURL; ?>"><i class="fa fa-angle-left"></i> Back</a></li>
        <?php
        for ($i = $startPagination; $i < $endPagination; $i++) {
            $pageURL = str_replace("&start=" . $search->request['start'], "&start=" . intval($i * $size), $url);
            echo '<li class="page-item ' . ($page == $i ? 'active' : '' ) . '"><a class="page-link" href="' . $pageURL . '"> ' . intval($i + 1) . ' </a></li>';
        }
        ?>
        <li class="page-item"><span class="page-sep">...</span></li>
        <li class="page-item"><a class="page-link" href="<?php echo $nextURL; ?>">Next <i class="fa fa-angle-right"></i></a></li>
        <li class="page-item"><a class="page-link" href="<?php echo $lastURL; ?>"> <i class="fa fa-angle-double-right"></i></a></li>
    </ul>
</div>