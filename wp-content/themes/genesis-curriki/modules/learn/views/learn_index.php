 <style>
    .full-width-content .content {        
        padding-top: 0px !important;
    }
 </style>
<?php 
global $view_data,$program_anchors,$program_collections,$program_groups; 
// $program = $view_data["program"];

// $program_anchors = $view_data["program_anchors"];
// $program_collections = $view_data["program_collections"];
// $program_groups = $view_data["program_groups"];
// $banner = strlen($program->image)>0 ? $program->image : "banner.jpg";
?>
<!-- <div class="banner" id="program-page-banner" style="background-image: url(<?php // echo "http://cdn.curriki.org/program_pages/{$banner}"; ?>);background-repeat: repeat-y;">
    <div class="container">
        
        <?php /*if(strlen($program->logo)>0 && $program->logo!="in porgress..."){ ?>
                <div class="program-pages-logo-wrapper">          
                        <img class="program-pages-logo" src="<?php echo "http://cdn.curriki.org/program_pages/{$program->logo}"; ?>">              
                </div>
        <?php } */?>
        
        <h1><?php // echo str_replace("\\","",$program->name); ?></h1>        
        <p><?php  // echo str_replace("\\","",$program->tagline); ?></p>
        <div class="banner-search">
            <div class="banner-search-wrap">
                <form action="<?php // echo get_site_url(); ?>/program-pages-search" method="get">
                    <input type="hidden" name="type" value="Resource" />
                    <input type="hidden" name="start" value="0" />
                    <input type="hidden" name="partnerid" value="1" />
                    <input type="hidden" name="branding" value="common" />
                    <input type="hidden" name="sort" value="rank1+desc" />
                    <input type="hidden" name="programid" value="<?php echo $program->programid ?>" />
                    <div class="select">
                        <select class="c-select">
                            <option>program Resource</option>
                        </select>
                    </div>
                    <input type="text" class="form-control" id="cp_search" placeholder="Start Searching" name="phrase">
                    <button type="submit"><i class="fa fa-search"></i><span>Search</span></button>
                </form>
            </div></div>
    </div>
</div> -->

<!-- 
<div class="secondary-nav">
    <div class="container-nav container">
        <nav class="navbar navbar-default">
            
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">Choose Options</button>
            </div>
            
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="program-page-nav">                    
                    <?php /*foreach ($program_anchors as $anchor) {
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
                    <?php }*/ ?>                    
                </ul>
            </div>
        </nav>
    </div>
</div> -->


<div class="wrapper">
    <div class="container">            
        <?php render_program_collecitons(); ?>        
    </div>
</div>



<a class="cd-top cd-is-visible" id="program-page-go-top" href="#program-page-banner" style="display: none;"> <span class="fa fa-chevron-up"></span> </a>

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
function render_program_collecitons(){ 
    global $program_collections,$book_mark_link;
?>                        
    <div class="collection program-pages-wrapper" id="<?php echo $book_mark_link; ?>">        
        <h2>My Programs</h2>        
        <div class="row">                
            <?php               
            foreach ($program_collections as $collection)
            {
            ?>
                <div class="col-sm-6 col-md-3">
                    <div class="collection-box">
                        <div class="collection-img"><div class="overlay"><a target="__blank" href="<?php echo site_url()."/oer/".$collection['pageurl']; ?>">View Collection</a></div><img class="collection_img_style" src="<?php echo "http://cdn.curriki.org/community_pages/".( strlen($collection['image'])===0 ? "collection_thumb.jpg":$collection['image'] ); ?>" alt="" /></div>
                        <div class="collection-content">
                            <div class="cp-header-wrapper">
                                <h4 class="coleql_height"><?php echo strlen($collection['title'])>50 ? substr($collection['title'], 0, 50)." ..." : $collection['title']; ?></h4>
                            </div>                            
                            <p><i class="fa fa-book"></i> MY PROGRESS: <?php echo $collection['progress'] ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>                
        </div>
    </div>
<?php  } ?>