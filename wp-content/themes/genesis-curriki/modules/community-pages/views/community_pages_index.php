<?php 
global $view_data,$community_anchors,$community_collections,$community_groups; 
$community = $view_data["community"];

$community_anchors = $view_data["community_anchors"];
$community_collections = $view_data["community_collections"];
$community_groups = $view_data["community_groups"];
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
                    <input type="hidden" name="communityid" value="<?php echo $community->communityid ?>" />
                    <div class="select">
                        <select class="c-select">
                            <option>Community Resource</option>
                        </select>
                    </div>
                    <input type="text" class="form-control" id="cp_search" placeholder="Start Searching" name="phrase">
                    <button type="submit"><i class="fa fa-search"></i><span>Search</span></button>
                </form>
            </div></div>
    </div>
</div>


<div class="secondary-nav">
    <div class="container-nav container">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">Choose Options</button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="community-page-nav">                    
                    <?php foreach ($community_anchors as $anchor) {
                        $book_mark_link = Misc::slugify($anchor->title);
                        if(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "groups")
                        {
                            $book_mark_link = "groups-{$anchor->anchorid}";
                        }elseif(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "collections")
                        {
                            $book_mark_link = "collections-{$anchor->anchorid}";
                        }
                    ?>
                            <li><a href="#<?php echo $book_mark_link; ?>"><?php echo $anchor->title; ?></a></li>
                    <?php } ?>                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </div>
</div>


<div class="wrapper">
    <div class="container">        
        <?php foreach ($community_anchors as $anchor) {
            global $book_mark_link;
            $book_mark_link = Misc::slugify($anchor->title);
            if(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "groups")
            {
                $book_mark_link = "groups-{$anchor->anchorid}";
            }elseif(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "collections")
            {
                $book_mark_link = "collections-{$anchor->anchorid}";
            }
        ?>
            
                
                
                <?php
                if(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "groups")
                {
                    render_community_groups($anchor);
                }elseif(isset($anchor->type) && strlen($anchor->type) > 0 && $anchor->type === "collections")
                {
                    render_community_collecitons($anchor);
                }else{  
                ?>              
                <!--border-grey card rounded-borders-full-->
                <div class="community-pages-content community-pages-wrapper" id="<?php echo $book_mark_link; ?>">
                    <h2><?php echo $anchor->tagline; ?></h2>
                    <p><?php echo $anchor->content; ?></p>
                </div>
                <?php
                }
                ?>
            
        <?php } ?>
    </div>
</div>



<a class="cd-top cd-is-visible" id="community-page-go-top" href="#community-page-banner" style="display: none;"> <span class="fa fa-chevron-up"></span> </a>

<style type="text/css">
    .cd-top.cd-is-visible {
    /* the button becomes visible */
    visibility: visible;
    opacity: 1;
  }
  .cd-top.cd-fade-out {
    /* if the user keeps scrolling down, the button is out of focus and becomes less visible */
    opacity: .5;
  }
</style>



<?php 
function render_community_collecitons($anchor){ 
    global $community_collections,$book_mark_link;
?>                        
    <div class="collection community-pages-wrapper" id="<?php echo $book_mark_link; ?>">        
        <h2><?php echo $anchor->tagline?></h2>
        <p><?php echo $anchor->content ?></p>
        <div class="row">                
            <?php               
            foreach ($community_collections as $collection)
            {
            ?>
                <div class="col-sm-6 col-md-3">
                    <div class="collection-box">
                        <div class="collection-img"><div class="overlay"><a target="__blank" href="<?php echo site_url()."/oer/".$collection->url; ?>">View Collection</a></div><img class="collection_img_style" src="<?php echo "http://cdn.curriki.org/community_pages/".( strlen($collection->image)===0 ? "collection_thumb.jpg":$collection->image ); ?>" alt="" /></div>
                        <div class="collection-content">
                            <div class="cp-header-wrapper">
                                <h4 class="coleql_height"><?php echo strlen($collection->name)>50 ? substr($collection->name, 0, 50)." ..." : $collection->name; ?></h4>
                            </div>                            
                            <p><i class="fa fa-book"></i><?php echo $collection->no_of_resources ?> Resources</p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>                
        </div>
    </div>
<?php  } ?>

<?php 
function render_community_groups($anchor){ 
    global $wpdb,$community_groups,$book_mark_link;
?>
    <div class="groups community-pages-wrapper" id="<?php echo $book_mark_link; ?>">
        <h2><?php echo $anchor->tagline?></h2>
        <p><?php echo $anchor->content ?></p>
        <div class="row">                
            <?php
                foreach ($community_groups as $group_cm) {                        
                    $group = groups_get_group( array( "group_id" => $group_cm->groupid ) );                        
                    $member_count = groups_get_total_member_count ( $group->id );

                    $forum_ids = groups_get_groupmeta( $group->id, 'forum_id', true );
                    if( !(is_array($forum_ids) && count( $forum_ids ) > 0) )
                    {
                        $forum_ids = array();
                    }                        
                    $forum_id = count($forum_ids) > 0 ? $forum_ids[0] : 0;
                    $forum_count = $wpdb->get_var("SELECT count(ID) FROM {$wpdb->prefix}posts where post_type = 'topic' AND post_status = 'publish' AND post_parent = $forum_id");                        
                    $resources_for_group = cur_get_resource_total_from_group ( $group->id );

            ?> 
                <div class="col-sm-6 col-md-3">
                    <div class="group">
                        <div class="group-head">
                            <div class="overlay"><a target="__blank" href="<?php echo site_url()."/groups/".$group->slug ?>">View Group</a></div>

                            <?php                                
                            $avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );                                
                            $g_avatar = bp_core_fetch_avatar($avatar_options);                                

                            ?>

                            <div class="group-img"><img src="<?php echo $g_avatar; ?><?php //echo get_stylesheet_directory_uri() . '/modules/community-pages/assets/images/thumb-1.jpg' ?>" alt=""></div>
                            <div class="cp-header-wrapper-group">
                                <h4><?php echo strlen($group->name) > 50 ? substr($group->name, 0,50)." ..." :  $group->name; ?></h4>
                            </div>
                        </div>
                        <ul>
                            <li><i class="fa fa-user"></i><?php echo $member_count; ?></li>
                            <li><i class="fa fa-comments"></i><?php echo $forum_count; ?></li>
                            <li><i class="fa fa-book"></i><?php echo $resources_for_group; ?></li>
                        </ul>
                        <div class="group-info">
                            <p><?php echo strlen($group->description) > 90 ? substr($group->description, 0,90)." ..." :  $group->description; ?></p>
                        </div>
                        <a target="__blank" href="<?php echo site_url()."/groups/".$group->slug ?>" class="join-btn">Join Group</a>
                    </div>
                </div>
            <?php } ?>                
        </div>
    </div>
<?php } ?>