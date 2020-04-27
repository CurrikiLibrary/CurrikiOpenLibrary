<?php global $search; ?>
<?php 
global $view_data,$community_anchors,$community_collections,$community_groups; 
global $wpdb;

$community = $view_data["community"];

$community_anchors = $view_data["community_anchors"];
$community_collections = $view_data["community_collections"];
$community_groups = $view_data["community_groups"];

$community = $wpdb->get_row( $wpdb->prepare( "select * from communities where communityid = %d", $search->request['communityid'] ) );
if($_REQUEST['testali']){
//    error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE);
//    
//
//    echo "<pre>";
//    var_dump($community);
//    die();
}

$banner = strlen($community->image)>0 ? $community->image : "banner.jpg";
?>
<div class="banner" id="community-page-banner" style="background-image: url(<?php echo "http://cdn.curriki.org/community_pages/{$banner}"; ?>);background-repeat: repeat-y;">
    <div class="container">
        
        <?php if(strlen($community->logo)>0 && $community->logo!="in porgress..."){ ?>
                <div class="community-pages-logo-wrapper">          
                        <img class="community-pages-logo" src="<?php echo "http://cdn.curriki.org/community_pages/{$community->logo}"; ?>">              
                </div>
        <?php } ?>
        
        <h1><?php echo str_replace("\\","",$community->name); ?></h1>        
        <p><?php echo str_replace("\\","",$community->tagline); ?></p>
        <div class="banner-search">
            <div class="banner-search-wrap">
                <form action="<?php echo get_site_url(); ?>/community-pages-search" method="get">
                    <input type="hidden" name="type" value="Resource" />
                    <input type="hidden" name="start" value="0" />
                    <input type="hidden" name="partnerid" value="1" />
                    <input type="hidden" name="branding" value="common" />
                    <input type="hidden" name="sort" value="rank1+desc" />
                    <input type="hidden" name="communityid" value="<?php echo $search->request['communityid']; ?>" />
                    <div class="select">
                        <select class="c-select">
                            <option>Community Resource</option>
                        </select>
                    </div>
                    <input type="text" class="form-control" id="cp_search" placeholder="Start Searching" name="phrase" value="<?php echo $search->request['phrase']; ?>">
                    <button type="submit"><i class="fa fa-search"></i><span>Search</span></button>
                </form>
            </div></div>
    </div>
</div>

<div class="search-results-showing grid_12 clearfix community-container" id="search_results_pointer">

    <div class="search-term grid_8 alpha" style="padding-top: 15px;padding-bottom: 10px;">
        <?php
        
        echo '<h3 style="font-size:20px;">Back to : <a href="' . get_site_url().'/community/'.$community->url . '" style="text-decoration:underline"> ' . $community->name . '</a></h3>';
        
        ?>
        <h4><?php echo __('Showing results for','curriki'); ?> "<?php echo trim(stripslashes(htmlspecialchars($search->request['phrase'])),'"'); ?>"</h4>
        <?php
        if (!empty($search->request['suggestedPhrase']) && $search->request['suggestedPhrase'] != $search->request['phrase']) {
            echo '<a href="' . $search->request['suggestedPhraseURL'] . '" target="_top"> Did you mean to search: ' . stripslashes(htmlspecialchars($search->request['suggestedPhrase'])) . ' ?</a><br/>';
        }
        
        ?>
        <span> <?php echo isset($search->status['found']) && $search->status['found'] > 0 ? number_format($search->status['found'], 0) . " ".__("Results Found","curriki") : __("No Result Found","curriki"); ?></span>
    </div>
    

</div>


