<?php
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;
  
global $search;


$current_language = "eng";
//if( defined('ICL_LANGUAGE_CODE') )
//    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

if (!function_exists("userGrades")) {

    function userGrades($level) {
        /*
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
         * 
         */
    }

}

if (!function_exists("showLicense")) {

    function showLicense($row, $type) {
        /*
        if ($type == 'link') {
            return $row['licenseurl'];
        } else if ($type == 'image') {
            return site_url() . '/wp-content/themes/genesis-curriki/images/licenses/' . str_replace(" ", "-", $row['license']) . '.png';
        }
         * 
         */
    }

}

if (!function_exists("showDescription")) {

    function showDescription($row,$search) {
        $return = stripslashes($row['description'] ? $row['description'] : $row['title']);
        if (strlen($return) > 300)
            $return = substr($return, 0, 300) . '<a href="' . $search->OER_page_url . $row['url'] . '" target="_blank"> [More]</a>';
        return $return;        
    }

}
?>

  <?php
        if(!$search->response || ( is_array($search->response) && count($search->response) === 0 ))
        {
    ?>
            <div id="search_results_pointer" class="search-results-showing grid_12 clearfix">
            <div class="search-term grid_8 alpha" style="border: 1px; min-height: 210px; width: 100%; text-align: center;">
                        <span> No Result Found</span>
            </div>                
            </div>
        <?php         
        } ?>

<div class="resources grid_12">
    <?php if( !is_null($search->response) ) { ?>
    <!---- Collection Card ----->
    <?php foreach ($search->response as $row) { 
        $row = (array)$row;        
    ?>
    
    
        <div class="collection-card card rounded-borders-full border-grey library-collection" id="card-<?php echo $row['id']; ?>">

            <!-- Collection Card Visible Area -->
            <div class="collection-body">
                <div class="collection-image">
                    <div class="library-icon-sr">
                        <span class="fa <?php echo $row['resourcetype'] == 'collection' ? "fa-folder" : "fa-image"; ?>"></span>
                    </div>
                </div>

                <div class="collection-body-inner">
                    <div class="collection-body-title">
                        <div class="collection-title">
                            <h3>
                                <a href="<?php echo $search->OER_page_url . $row['url']; ?>" target="_blank"><?php  echo $row['title'] ? $row['title'] : "Go To Resource"; if(isset($_GET['score']) && $_GET['score'] == 'true'){ echo $row['_score'] ? " (Score: ".$row['_score'].") " : "";  echo $row['rank1'] ? "(Rank: ".$row['rank1'].")" : "";echo $row['partner'] ? "(Partner: ".$row['partner'].")" : ""; echo $row['topofsearch'] ? "(Topofsearch: ".$row['topofsearch'].")" : "";echo $row['reviewrating'] ? " (reviewrating: ".$row['reviewrating'].")" : ""; echo $row['memberrating'] ? " (memberrating: ".$row['memberrating'].")" : "";}   ?></a>
<!--                                <input type="hidden" name="rid-fld" class="rid-fld" value="<?php //echo $row['id']; ?>" />-->
                            </h3> 
                            <span class="collection-grade"><strong><?php //echo userGrades($row['educationlevel']); ?></strong></span>
                        </div>

                        <div class="collection-author">
                            <?php if ($row['avatarfile']) { ?>
                                <img alt="member-name" class="alignleft" src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/<?php echo $row['avatarfile']; ?>" />
                            <?php } else { ?>
                                <img alt="member-name" class="alignleft" src="http://curriki.org/wp-content/themes/genesis-curriki/images/user-icon-sample.png" />
                            <?php } ?>
                                <span class="member-name name vertical-align"><a href="javascript:void(0);" target="_blank" ><?php echo $row['fullname'] ? $row['fullname'] : "N/A"; ?></a></span>
<!--                            <span class="more-from-member name vertical-align"><a href="<?php echo $search->site_url; ?>/user-library/?user=<?php echo $row['usernicename']; ?>"><?php echo 'More from this member'; ?></a></span>-->
                        </div>
                    </div>

                    <div class="collection-body-content">
                        <div class="collection-description"><?php echo showDescription($row,$search); ?></div>

                        <div class="collection-rating rating">
                            <span class="member-rating-title"><?php echo 'Member Rating'; ?></span>
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $row['memberrating'])
                                    echo '<span class="fa fa-star"></span>';
                                else
                                    echo '<span class="fa fa-star-o"></span>';
                            }
                            ?>

                        </div>

                        <div class="collection-curriki-rating curriki-rating"> 
                            <span class="curriki-rating-title"><?php echo 'Curriki Rating'; ?></span>
                            <?php
                            if (isset($row['reviewstatus']) && $row['reviewstatus'] == 'reviewed' && $row['reviewrating'] != null && $row['reviewrating'] >= 0) {
                                echo '<span class="rating-badge" >' . $row['reviewrating'] . '</span>';
                            } elseif (isset($row['partner']) && $row['partner'] == 'T') {
                                echo '<span class="rating-badge" >P</span>';
                            } elseif (isset($row['partner']) && $row['partner'] == 'C') {
                                echo '<span class="rating-badge" >C</span>';
                            } else {
                                echo '<span class="rating-badge-nr" >NR</span>';
                            }
                            ?>
                        </div>

                    </div>                    
                </div>
            </div>
            <!-- /Collection Card Visible Area -->

            <!-- Collection Card More Info -->
<!--            <div class="collection-more-info">
                <div class="collection-views-license">
                    <p><?php echo intval($row['resourceviews']); ?> Views</p> <p><?php echo intval($row['collections']); ?> Collection(s)</p> <p><a target="_blank" href="<?php //echo showLicense($row, 'link'); ?>"><img src="<?php //echo showLicense($row, 'image'); ?>" /></a></p>
                </div>

                <div class="collection-more-info-content">
                    <?php if (is_array($row['standard'])) { ?>
                        <div class="collection-type">

                            <strong ><?php echo 'Alignment'; ?>:</strong> 
                            <ul>
                                <?php
                                foreach ($row['standard'] as $index => $rec)
                                    echo "<li >" . $rec . "</li>";
                                ?>
                            </ul>

                        </div>
                    <?php } if (isset($row['collectionelement']) && is_array($row['collectionelement']) && $row['resourcetype'] == 'collection') { ?>
                        <div class="collection-resources">
                            <strong>Resources in Collection:</strong>
                            <ul>
                                <?php
                                foreach ($row['collectionelement'] as $index => $rec)
                                    echo "<li >" . $rec . "</li>";
                                ?>
                            </ul>
                        </div>
                    <?php } if (isset($row['subsubjectarea']) && !empty($row['subsubjectarea'])) { ?>
                        
                    <div class="collection-subjects">
                            <strong><?php echo 'Subjects'; ?>:</strong>                                                        
                                <ul>
                                    <?php
                                        foreach ($row['subsubjectarea'] as $index => $rec)
                                        {
                                            //echo "<li >" . $rec . "</li>";
                                            /*
                                            $rec_arr = explode(">", $rec);
                                            $subject_txt = trim($rec_arr[0]);
                                            $subjectarea_txt = trim($rec_arr[1]);
                                            
                                            $subject = $wpdb->get_var( cur_subjects_by_displayname_query($current_language,$subject_txt) );
                                            $subjectarea = $wpdb->get_var( cur_subjectareas_by_displayname_query($current_language,$subjectarea_txt) );
                                            
                                            $subsubjectarea = $subject . " > " . $subjectarea;                                            
                                            echo "<li >" . $subsubjectarea . "</li>";                                            
                                             * 
                                             */
                                        }
                                    ?>
                                </ul>                            
                        </div>
                    
                    <?php } if (isset($row['educationlevel']) && is_array($row['educationlevel'])) { ?>
                        <div class="collection-grades">
                            <strong>Grade Levels:</strong>
                            <ul>
                                <?php
                                foreach ($row['educationlevel'] as $index => $rec)
                                    echo "<li >" . $rec . "</li>";
                                ?>
                            </ul>
                        </div>
                    <?php } if (isset($row['instructiontype']) && is_array($row['instructiontype'])) { ?>
                        <div class="collection-type">
                            <strong >Types:</strong> 
                            <ul>
                                <?php                                
                                    foreach ($row['instructiontype'] as $index => $rec)
                                    {
                                        /*
                                        $instructiontype = $wpdb->get_var( cur_instructiontypes_by_name_query($current_language,$rec) );
                                        echo "<li >" . $instructiontype . "</li>";
                                         * 
                                         */
                                    }                                                                
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>-->
            <!-- /Collection Card More Info -->
            
            
            <?php            
            // if( (isset($_SESSION['isContentItem']) && $_SESSION['isContentItem']===true && $_SESSION['request_action'] === "resource_selection") || ( isset($_SESSION['isContentItem']) && $_SESSION['isContentItem']===true && $_SESSION['request_action'] === "resource_selection_editor" ) )
            if(false)
            {                
            ?>
                <div class="collection-actions" id="collection-tabs">                                
                    <?php
                        $resourceUrl = urlencode($search->OER_page_url . $row['url']);
                        $returnUrl = $_SESSION['return_url'];
                        $title = "Curriki Resource: ".($row['title'] ? $row['title'] : "Go To Resource");                        
                        /*$text = showDescription($row,$search);                        
                        $text = urlencode(strip_tags($text));*/
                        $title = urlencode($title);                         
                        $text = $title;
                        
                        $return_type = "lti_launch_url";
                        $additional_params = "";
                        if($_SESSION['request_action'] === "resource_selection_editor")
                        {
                            $return_type = "url";                            
                        }                        
                        $ltiAddResourceLink = $returnUrl."?return_type=$return_type&url=$resourceUrl&title=$title&text=".$text;
                        //$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."&do_ajax=add_resource&ajax=1&title=".$title."&url=".$resourceUrl;
                        $current_url = "https" . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."&do_ajax=add_resource&ajax=1&title=".$title."&url=".$resourceUrl;
                    ?>
                    <div class="share-collection" onclick="LtiAddResourceLink('<?php echo $ltiAddResourceLink; ?>','<?php echo $current_url; ?>', '<?php echo $return_type; ?>' )"><span class="fa fa-plus-circle "></span>&nbsp;Add Resource</div>
                </div>
            <?php
            }
            ?>            
            
            <!-- Collection Card Extra Tabs -->
            <!--
            <div class="collection-actions" id="collection-tabs">
                <div class="less-collection-info" onclick="collectionMoreInfo('#card-<?php //echo $row['id']; ?>')"><span class="fa fa-caret-up"></span> Less Info</div>
                <div class="more-collection-info" onclick="collectionMoreInfo('#card-<?php //echo $row['id']; ?>')"><span class="fa fa-caret-down"></span> More Info</div>
                <div class="share-collection" onclick="collectionShare('#card-<?php //echo $row['id']; ?>')"><span class="fa fa-share-alt-square"></span> Share </div>                
            </div>
            -->
            <!-- /Collection Card Extra Tabs -->

            <!-- Collection Card Share -->
            <!--
            <div class="collection-share" >
                <div class="collection-share-buttons share-icons">
                    <p><?php //echo __('Share this link via','curriki'); ?></p>
                    <a class="share-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $search->site_url; ?>/<?php echo $row['url']; ?>%23.VVSq-YDZN0Y.facebook"><span class="fa fa-facebook"></span></a>
                    <a class="share-twitter" target="_blank" href="https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! <?php echo $row['title']; ?>-OER via @Curriki&url=<?php echo $search->site_url; ?>/<?php echo $row['url']; ?>"><span class="fa fa-twitter"></span></a>
                    a class="share-pinterest" 
                       href="<?php //echo $search->site_url; ?>/<?php //echo $row['url']; ?>" 
                       onclick="return addthis_sendto('pinterest_share');"
                       onblur="if (_ate.maf.key == 9){_ate.maf.key = null; } else{_ate.maf.key = null; addthis_close(); }"
                       onkeydown="if (!e){var e = window.event || event; }if (e.keyCode){_ate.maf.key = e.keyCode; } else{if (e.which){_ate.maf.key = e.which; '];?>"
                       onkeypress="if (!e){var e = window.event || event; }if (e.keyCode){_ate.maf.key = e.keyCode; } else{if (e.which){_ate.maf.key = e.which; '];?>" 
                       ><span class="fa fa-pinterest"></span></a>
                    <a class="share-email" href="mailto:?subject=See this article&amp;body=<?php echo $search->site_url; ?>/<?php echo $row['url']; ?>"><span class="fa fa-envelope-o"></span></a>
                </div>

                <div class="collection-share-link"><p><?php //echo __('Or copy and paste this link','curriki'); ?></p><input readonly type="text" value="<?php echo $search->site_url; ?>/<?php echo $row['url']; ?>"></div>
            </div>
            -->
            <!-- /Collection Card Share -->

        </div>
    <?php } ?>
    <!---- End Collection Card ----->
    <!--
    <div style="width: 100%;text-align: center;">
        <a id="view-more-resources" href="#" class="button create-group-button">View More</a>        
    </div>    
    -->
    
    <?php } ?>
    
</div>