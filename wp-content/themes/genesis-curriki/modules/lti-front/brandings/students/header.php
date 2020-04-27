<?php global $search; ?>
<style>
    /*main.content{padding: 0px  !important;}*/
    /*.search-page .search-content{padding-top: 30px !important;}*/
    .site-inner , .site-inner > .container_12{width:100%;max-width:100%;}
    .search-bar{min-height: 290px;}
    .search-row-overlay {
        background: url('https://www.curriki.org/wp-content/uploads/2015/11/janetpinto_final_mod.png') transparent;
        background-size:     cover;
        background-repeat:   no-repeat;
        background-position: center center;
    }
    .wrap {padding-top: 45px;}
    .search-widget{max-width: 880px;display: block;margin: 0 auto;}
    @media screen and (max-width: 640px) {
        .search-row{text-align: left;height: auto;}
    }

</style>

<header class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
    <div class="wrap">
        <div class="title-area">
            <p class="site-title" itemprop="headline">
                <a href="<?php echo site_url(); ?>">Curriki</a>
            </p>
        </div>
    </div>
</header>

<div class="content-sidebar-wrap">
    <div class="home-row search-row" style="min-height: 475px !important;">
        <div class="search-row-overlay" style="height: 475px !important;"></div>