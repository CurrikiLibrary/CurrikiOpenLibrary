<?php
class TpUiHelper 
{        
    public static function includeScript($data=null) 
    {
        $includes = false;
        $time_stamp = time();
        $css_includes = "
            <link rel='stylesheet' id='curriki-custom-style-alpha-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/curriki-custom-style-alpha.css?ver=4.6.1' type='text/css' media='all' />
            <link rel='stylesheet' id='curriki-tooltip-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/curriki-customized/css/jquery.tooltip.css?ver=4.6.1' type='text/css' media='all' />
            <link rel='stylesheet' id='curriki-custom-style-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/css/curriki-custom-style.css?ver=4.6.1' type='text/css' media='all' />
            <link rel='stylesheet' id='gconnect-bp-css'  href='https://www.curriki.org/wp-content/plugins/genesis-connect-for-buddypress/css/buddypress.css?ver=4.6.1' type='text/css' media='all' />
            <link rel='stylesheet' id='bbp-default-css'  href='https://www.curriki.org/wp-content/plugins/bbpress/templates/default/css/bbpress.css?ver=2.5.9-6017' type='text/css' media='screen' />
            <link rel='stylesheet' id='admin-bar-style-css'  href='https://www.curriki.org/wp-content/plugins/wp-analytify-pro/css/admin_bar_styles.css?ver=1.3.5' type='text/css' media='all' />
            <link rel='stylesheet' id='wpml-cms-nav-css-css'  href='https://www.curriki.org/wp-content/plugins/wpml-cms-nav/res/css/navigation.css?ver=1.4.18' type='text/css' media='all' />
            <link rel='stylesheet' id='cms-navigation-style-base-css'  href='https://www.curriki.org/wp-content/plugins/wpml-cms-nav/res/css/cms-navigation-base.css?ver=1.4.18' type='text/css' media='screen' />
            <link rel='stylesheet' id='cms-navigation-style-css'  href='https://www.curriki.org/wp-content/plugins/wpml-cms-nav/res/css/cms-navigation.css?ver=1.4.18' type='text/css' media='screen' />
            <link rel='stylesheet' id='misc-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/css/misc.css?ver=4.6.1' type='text/css' media='all' />
            <link rel='stylesheet' id='tablepress-default-css'  href='https://www.curriki.org/wp-content/plugins/tablepress/css/default.min.css?ver=1.7' type='text/css' media='all' />
            <link rel='stylesheet' id='fontawesome-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/css/font-awesome.min.css?ver=4.3.0' type='text/css' media='all' />
            <link rel='stylesheet' id='curriki-stylesheet-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/style.css?ver=4.6.1' type='text/css' media='all' />            
            
            <link rel='stylesheet' id='curriki-tool-style-css'  href='https://www.curriki.org/lti/css/tool-style.css?t={$time_stamp}' type='text/css' media='all' />
            <link href='https://www.curriki.org/lti/css/rating.css?t={$time_stamp}' media='screen' rel='stylesheet' type='text/css' />
            <link href='https://www.curriki.org/lti/app/modules/search/css/style.css?t={$time_stamp}' media='screen' rel='stylesheet' type='text/css' />
            <link rel='stylesheet' id='qtip-css-css'  href='https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.6.3&t={$time_stamp}' type='text/css' media='all' />
            ";
        $js_includes = "
            <script type='text/javascript' src='https://www.curriki.org/wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
            <script type='text/javascript' src='https://www.curriki.org/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.4.1'></script>
            <script src='js/jquery.min.js' type='text/javascript'></script>            
            <script type='text/javascript' src='https://www.curriki.org/wp-content/themes/genesis-curriki/js/qtip2_v2.2.1/jquery.qtip.min.js?ver=4.6.3&t={$time_stamp}'></script>
            <script src='js/app.js?t={$time_stamp}' type='text/javascript'></script>            
            <script src='https://www.curriki.org/lti/app/modules/search/js/script.js?t={$time_stamp}' type='text/javascript'></script>                        
                ";        
            //<script src='https://www.curriki.org/lti/app/modules/search/js/widget.js?t={$time_stamp}' type='text/javascript'></script>            
        $includes = $css_includes.$js_includes;
        return $includes;
    }
    public static function headHtml($data=null) {
        $html = "<head>";
        
        $html .= '<meta charset="UTF-8" />
                    <title>'. (isset($data["page_title"])? $data["page_title"]: "Curriki") .'</title>';        
        $html .= self::includeScript();
        $html .= "</head>";        
        return $html;
    }
    public static function bodyStartHtml($data=null) {
        $unser_info = unserialize($data["unser_info"]);                                                                
        $has_user_context_roles = $data['has_user_context_roles'];
        $user_current_role = $data['user_current_role'];
        $logout_url = isset($data['logout_url']) ? $data['logout_url'] : null;
        
        $html = "<body class='header-image full-width-content backend' itemtype='http://schema.org/WebPage' itemscope='itemscope'>
                    <div class='site-container'>";
                    
                            //*** header ***
                            $html.= '<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" role="banner" class="site-header">
                                            <div class="wrap">
                                                <div class="title-area">
                                                    <p itemprop="headline" class="site-title">
                                                        <a href="http://www.curriki.org/">Curriki</a>
                                                    </p>
                                                </div>
                                                <aside class="widget-area header-widget-area">                                                                                                
                                                    <li class="widget widget_nav_menu" id="nav_menu-7">
                                                        <nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation" class="nav-header">
                                                            <ul class="menu genesis-nav-menu" id="menu-header-loggedin">
                                                                <li class="class-header-menu-logout donate-link menu-item menu-item-type-custom menu-item-object-custom menu-item-6032" id="menu-item-6032">                                                                    
                                                                    <div class="profile-info-top">
                                                                ';                                                                
                                                                        $html.= $unser_info->userDisplayName;
                                                                        if($has_user_context_roles)
                                                                        {
                                                                            $html.= isset($user_current_role)? " <i class='role-label'>($user_current_role)</i>" : "";
                                                                        }
                                                                        if( $logout_url && (isset($_SESSION["is_admin_loggedin"]) && $_SESSION["is_admin_loggedin"] === 1) )
                                                                        {
                                                                            $html.= ' <a class="logout-link-lti" href="'.$logout_url.'">Logout</a>';
                                                                        }
                                                                        $html.='
                                                                    </div>
                                                                </li>
                                                                <li class="donate-link menu-item menu-item-type-post_type menu-item-object-page menu-item-7968" id="menu-item-7968">                                                                    
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                    </li>
                                                </aside>    
                                                <style type="text/css">
                                                    .card{
                                                        min-width: 0px !important;
                                                    }
                                                </style>
                                            </div>
                                        </header>';
                            //**** nav ********                            
                            $html.='<nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" role="navigation" class="nav-primary">
                                        <div class="wrap">
                                            <ul class="menu genesis-nav-menu menu-primary" id="menu-primary-nav">
                                                <li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-6015 current_page_item menu-item-has-children menu-item-6034" id="menu-item-6034">
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                    ';
                            $html.='
                                <div class="site-inner">
                                    <div class="container_12">
                                        <div class="content-sidebar-wrap">
                                            <main class="content" itemprop="mainContentOfPage" role="main">
                                                <div class="resource-content clearfix">
                                                    <div class="wrap container_12">
                                                        <div class="dashboard">                                            
                                    ';                    
        
        return $html;
    }
    public static function bodyEndHtml() {
        $html = "<body class='header-image full-width-content backend' itemtype='http://schema.org/WebPage' itemscope='itemscope'>
                    <div class='site-container'>";
                    
                            //*** header ***
                            $html.= '    
                                                        </div>
                                                    </div>
                                                </div>
                                            </main>
                                        </div>
                                    </div>
                                </div>
                                    ';
                    $html.='
                    </div>
                </body>';
        
        return $html;
    }
    
}
