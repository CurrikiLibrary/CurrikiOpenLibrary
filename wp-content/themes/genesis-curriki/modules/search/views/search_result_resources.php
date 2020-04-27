<?php
global $search;
global $wpdb;
$theme_url = get_stylesheet_directory_uri();

$current_language = "eng";
if( defined('ICL_LANGUAGE_CODE') )
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

if (!function_exists("userGrades")) {

    function userGrades($level) {
        if (!is_array($level))
            return;
        global $search;
        $return = array();
        foreach ($search->educationlevels as $key => $value) {
            $levels = explode("|", $value['levelidentifiers']);
            foreach ($level as $k => $v) {
                if (in_array($v, $levels) && !in_array($v, $return)) {
                    $return[] = $value['title'];
                }
            }
        }
        return implode(", ", array_unique($return));
    }

}

if (!function_exists("showLicense")) {

    function showLicense($row, $type) {
        if ($type == 'link') {
            return $row['licenseurl'];
        } else if ($type == 'image') {
            return site_url() . '/wp-content/themes/genesis-curriki/images/licenses/' . str_replace(" ", "-", $row['license']) . '.png';
        }
    }

}

if (!function_exists("showDescription")) {

    function showDescription($row) {
        global $search;
        $return = stripslashes($row['description'] ? $row['description'] : $row['title']);
       if (strlen($return) > 300)
           $return = substr($return, 0, 300) . ' <a class="underline" href="' . $search->OER_page_url . $row['url'] . '" target="_blank">more</a>';
        return $return;
    }

}
if (!function_exists("showContent")) {

    function showContent($row) {
        global $search;
        $return = stripslashes($row['content'] ? $row['content'] : $row['title']);
        $doc = new DOMDocument();
        $return  = mb_convert_encoding($return , 'HTML-ENTITIES', 'UTF-8');
        $doc->loadHTML($return);
        $return = $doc->saveHTML();
//        if (strlen($return) > 300)
//            $return = substr($return, 0, 300) . '<a href="' . $search->OER_page_url . $row['url'] . '" target="_blank"> [More]</a>';
        return $return;
    }

}
?>
<div class="category-list make-<?php echo (isset($search->request['compact']) && $search->request['compact'] == 'true')? 'compact':'list' ?>">
    <!---- Collection Card ----->
    <?php foreach ($search->response as $row) {
        $title = '';
        $style = '';
        if (isset($search->current_user->caps['administrator'])) {
            $style = 'background:#FFF';
            $title = 'title="Approved Resource"';
            if ($row['currentApprovalStatus'] != '' && $row['currentApprovalStatus'] != $row['approvalstatus']) {
                $style = 'border:5px dashed #7fc41a;'; //green border
                $title = 'title="Scheduled For Removal"';
            } else if ($row['approvalstatus'] == 'rejected') {
                $style = 'background:#ffbfbf'; //pink background
                $title = 'title="Rejected Resource"';
            }
            if ($row['approvalstatus'] == 'pending') {
                $style = 'background:#c9f1ff'; // sky blue background
                $title = 'title="Pending Resource"';
            }
        }

        $resourceThumbImage = $theme_url . '/images/subjects/Arts/General.jpg';
        $resourceSubject = '';
        $resourceSubjectArea = '';
        $resourceSubjectAreaExt = 'png';
        if (isset($row['subsubjectarea'])) {
//            echo "<pre>";
//            print_r($row);
//            die();
            $resourceSubjectAreaArray = explode(' > ', $row['subsubjectarea'][0]);
//            print_r($resourceSubjectAreaArray);
//            die();
            $resourceSubject = preg_replace('/\PL/u', '', $resourceSubjectAreaArray[0]);
            $resourceSubjectArea = preg_replace('/\PL/u', '', $resourceSubjectAreaArray[1]);

            if ($resourceSubject == 'Arts' || $resourceSubject == 'CareerTechnicalEducation') {
                $resourceSubjectAreaExt = 'jpg';
            }

            $resourceThumbImage = $theme_url . '/images/subjects/' . $resourceSubject . '/' . $resourceSubjectArea . '.' . $resourceSubjectAreaExt;
        }
    ?>

        <div class="post">
            <?php if($row['thumb_image']): ?>
                <a href="<?php echo $search->OER_page_url . $row['url']; ?>" target="_blank">
                    <img class="post-thumbnail" src="<?php echo urldecode($row['thumb_image']); ?>" width="136" height="136" alt="thumbnail">
                </a>
            <?php else: ?>
                <a href="<?php echo $search->OER_page_url . $row['url']; ?>" target="_blank">
                    <img class="post-thumbnail" src="<?php echo $resourceThumbImage; ?>" width="136" height="136" alt="thumbnail">
                </a>
            <?php endif; ?>
            <div class="post-content">
                <div class="post-content-top">
                    <h4 class="post-title">
                        <a href="<?php echo $search->OER_page_url . $row['url']; ?>" target="_blank">
                            <?php
                                echo $row['title'] ? $row['title'] : "Go To Resource";

                                if (isset($_GET['score']) && $_GET['score'] == 'true') {
                                    echo $row['_score'] ? " (Score: ".$row['_score'].") " : "";
                                    echo $row['rank1'] ? "(Rank: ".$row['rank1'].")" : "";
                                    echo $row['partner'] ? "(Partner: ".$row['partner'].")" : "";
                                    echo $row['topofsearch'] ? "(Topofsearch: ".$row['topofsearch'].")" : "";
                                    echo $row['reviewrating'] ? " (reviewrating: ".$row['reviewrating'].")" : "";
                                    echo $row['memberrating'] ? " (memberrating: ".$row['memberrating'].")" : "";
                                }
                            ?>
                        </a>
                    </h4>
                    <ul class="post-action">
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-share-alt"></i> <?php echo __('Share','curriki'); ?>
                            </a>
                            <div class="dropdown-menu share-dropdown">
                                <button type="button" class="close">x</button>
                                <h4><?php echo __('Share this link via','curriki'); ?></h4>
                                <ul class="social-icons">
                                    <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_bloginfo('url'); ?>/<?php echo $row['url']; ?>%23.VVSq-YDZN0Y.facebook"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" href="https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! <?php echo $row['title']; ?>-OER via @Curriki&url=<?php echo get_bloginfo('url'); ?>/<?php echo $row['url']; ?>"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="email" href="mailto:?subject=See this article&amp;body=<?php echo get_bloginfo('url'); ?>/<?php echo $row['url']; ?>"><i class="fa fa-envelope-o"></i></a></li>
                                    <!-- li><a class="users" href="#"><i class="fa fa-users"></i></a></li -->
                                </ul>
                                <div class="divider"></div>
                                <p><?php echo __('Or copy and paste this link','curriki'); ?></p>
                                <input class="form-control" type="text" readonly value="<?php echo get_bloginfo('url'); ?>/<?php echo $row['url']; ?>">
                            </div>
                        </li>
                        <?php
                            //  if (is_user_logged_in()) {
                            if (in_array("content_creator", $search->current_user->roles)) {
                        ?>
                            <li><a class="add-to-library" href="javascript:void(0)" onclick="addToMyLibrary(<?php echo $row['id']; ?>)" rid-fld="<?php echo $row['id']; ?>"><i class="fa fa-folder"></i> <?php echo __('Add to My Library','curriki'); ?></a></li>
                        <?php } ?>
                        <?php 
                            if (isset($search->current_user->caps['administrator']) || (in_array("content_creator", $search->current_user->roles) && ($search->current_user->ID == $row['contributorid']))) {
                        ?>
                            <li><a href="<?php echo get_bloginfo('url'); ?>/create-resource/?resourceid=<?php echo $row['id']; ?>"><i class="fa fa-pencil"></i> Edit</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="post-author">
                    by <a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-popover-content="#<?php echo $row['usernicename']; ?>"><?php echo $row['fullname'] ? $row['fullname'] : "N/A"; ?></a>
                    <div id="<?php echo $row['usernicename']; ?>" class="hidden">
                        <div class="popover-body">
                            <div class="user-details">
                                <button type="button" class="popover-close close">x</button>
                                <?php if ($row['avatarfile']) { ?>
                                    <img class="img-circle" src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/<?php echo $row['avatarfile']; ?>" width="78" height="78" alt="<?php echo $row['fullname'] ? $row['fullname'] : "N/A"; ?>">
                                <?php } else { ?>
                                    <img class="img-circle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/user-icon-sample.png" width="78" height="78" alt="<?php echo $row['fullname'] ? $row['fullname'] : "N/A"; ?>">
                                <?php } ?>
                                <span class="fn"><?php echo $row['fullname'] ? $row['fullname'] : "N/A"; ?></span>
                                <span class="location"><?php echo $row['userlocation']; ?></span>
                                <span class="member-role">I’m a <?php echo $row['usermembertype']; ?></span>
                                <p><a class="underline" href="<?php echo get_bloginfo('url'); ?>/user-library/?user=<?php echo $row['usernicename']; ?>"><?php echo __('More from this member','curriki'); ?></a></p>
                                <button class="btn btn-outline" type="button">Follow</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-body">
                    <p><?php echo showDescription($row); ?></p>
                </div>
                <div class="post-meta">
                    <div class="post-meta-item">
                        <span class="meta-label"><?php echo __('Member Rating','curriki'); ?></span>
                        <span class="rating-stars">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $row['memberrating'])
                                echo '<i class="fa fa-star"></i>';
                            else
                                echo '<i class="fa fa-star-o"></i>';
                        }
                        ?>
                        </span>
                    </div>
                    <div class="post-meta-item">
                        <span class="meta-label"><?php echo __('Curriki Rating','curriki'); ?></span>
                        <?php
                        if (isset($row['reviewstatus']) && $row['reviewstatus'] == 'reviewed' && $row['reviewrating'] != null && $row['reviewrating'] >= 0) {
                            echo '<span class="rating-points">' . $row['reviewrating'] . '</span>';
                        } elseif (isset($row['partner']) && $row['partner'] == 'T') {
                            echo '<span class="rating-points">P</span>';
                        } elseif (isset($row['partner']) && $row['partner'] == 'C') {
                            echo '<span class="rating-points">C</span>';
                        } else {
                            echo '<span class="rating-points">NR</span>';
                        }
                        ?>
                    </div>
                    <div class="post-meta-item">
                        <button class="more-info" data-toggle="modal" data-target="#m-<?php echo $row['id']; ?>">
                            <?php echo __('More Info','curriki'); ?> <i class="fa fa-angle-down"></i>
                        </button>
                        <div class="modal modal-collection fade" id="m-<?php echo $row['id']; ?>"
                            tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="collection-info">
                                            <div class="collection-info-header">
                                                <button type="button" class="close" data-dismiss="modal">×</button>
                                                <h4 class="collection-info-title">
                                                    <?php echo $row['title']; ?> - More Info
                                                </h4>
                                                <ul class="collection-views">
                                                    <li><?php echo intval($row['resourceviews']); ?> Views</li>
                                                    <li><?php echo intval($row['collections']); ?> Collection(s)</li>
                                                </ul>
                                            </div>
                                            <div class="collection-info-body">
                                                <?php if (isset($row['subsubjectarea']) && !empty($row['subsubjectarea'])) { ?>
                                                    <div class="collection-body-row">
                                                        <span class="collection-body-label"><?php echo __('Subjects','curriki'); ?>:</span>
                                                        <div class="collection-body-desc">
                                                            <?php
                                                                foreach ($row['subsubjectarea'] as $index => $rec)
                                                                {
                                                                    $rec_arr = explode(">", $rec);
                                                                    $subject_txt = trim($rec_arr[0]);
                                                                    $subjectarea_txt = trim($rec_arr[1]);

                                                                    $subject = $wpdb->get_var( cur_subjects_by_displayname_query($current_language,$subject_txt) );
                                                                    $subjectarea = $wpdb->get_var( cur_subjectareas_by_displayname_query($current_language,$subjectarea_txt) );

                                                                    $subsubjectarea = $subject . " > " . $subjectarea;
                                                                    echo "<p>" . $subsubjectarea . "</p>";
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php } if (isset($row['educationlevel']) && is_array($row['educationlevel'])) { ?>
                                                    <div class="collection-body-row">
                                                        <span class="collection-body-label"><?php echo __('Grade Levels','curriki'); ?>:</span>
                                                        <div class="collection-body-desc">
                                                            <div class="collection-levels">
                                                                <div class="levels-left">
                                                                    <ul>
                                                                        <?php
                                                                            foreach ($row['educationlevel'] as $index => $rec) {
                                                                                if ($index % 2 == 0)
                                                                                    echo "<li>" . $rec . "</li>";
                                                                            }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="levels-right">
                                                                    <ul>
                                                                        <?php
                                                                            foreach ($row['educationlevel'] as $index => $rec) {
                                                                                if ($index % 2 != 0)
                                                                                    echo "<li>" . $rec . "</li>";
                                                                            }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } if (isset($row['instructiontype']) && is_array($row['instructiontype'])) { ?>
                                                    <div class="collection-body-row">
                                                        <span class="collection-body-label"><?php echo __('Types','curriki'); ?>:</span>
                                                        <div class="collection-body-desc">
                                                            <?php
                                                                foreach ($row['instructiontype'] as $index => $rec)
                                                                {
                                                                    $instructiontype = $wpdb->get_var( cur_instructiontypes_by_name_query($current_language,$rec) );
                                                                    echo "<p>" . $instructiontype . "</p>";
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="collection-info-footer">
                                                <p class="text-bold">
                                                    <a target="_blank" href="<?php echo showLicense($row, 'link'); ?>">
                                                        <img class="cc-img" src="<?php echo showLicense($row, 'image'); ?>" width="88" height="31" alt="cc by">
                                                        Attribution-NonCommercial
                                                        4.0 International (CC BY-NC
                                                        4.0)
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!---- End Collection Card ----->
</div>