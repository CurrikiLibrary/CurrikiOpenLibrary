<?php
/*
 * Template Name: Search Resources Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

// Add custom body class to the head
add_filter('body_class', 'curriki_search_resources_page_add_body_class');

function curriki_search_resources_page_add_body_class($classes) {
    if (!isset($_REQUEST['partnerid']) OR empty($_REQUEST['partnerid']))
        $classes[] = 'backend search-page';
    return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_search_resources_page_loop');

function curriki_custom_search_resources_page_loop() {
    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
    remove_action('genesis_loop', 'genesis_do_loop');
    if ((isset($_REQUEST['partnerid']) AND ! empty($_REQUEST['partnerid'])) OR SUBDOMAIN != '') {
        //remove header
        remove_action('genesis_header', 'genesis_header_markup_open', 5);
        remove_action('genesis_header', 'genesis_do_header');
        remove_action('genesis_header', 'genesis_header_markup_close', 15);

        //remove navigation
        remove_action('genesis_after_header', 'genesis_do_nav');
        remove_action('genesis_after_header', 'genesis_do_subnav');

        //Remove footer
        remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
        remove_action('genesis_footer', 'genesis_do_footer');
        remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

        //* Remove the entry footer markup (requires HTML5 theme support)
        remove_action('genesis_before_footer', 'genesis_footer_widget_areas');
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
        remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);
    }
    // add_action( 'genesis_after_header', 'curriki_resource_header', 10 );
    add_action('genesis_before', 'curriki_search_resources_page_scripts');
    add_action('genesis_loop', 'curriki_search_resources_page_body', 15);
}

function curriki_resource_header() {
    $resource_header = '<div class="resource-header page-header">';
    $resource_header .= '<div class="wrap container_12">';
    $resource_header .= '</div>';
    $resource_header .= '</div>';
    echo $resource_header;
}

function curriki_search_resources_page_scripts() {

    //*******Scripts and styles *********//
    wp_enqueue_script('angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js', array('jquery'), false, true); // Not using imagesLoaded? :( Okay... then this.
    wp_enqueue_script('angular-sanitize', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.min.js', array('angular'), false, true); // Not using imagesLoaded? :( Okay... then this.


    wp_enqueue_style('qtip-css', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, 'all'); // Add the styles first, in the <head> (last parameter false, true = bottom of page!)
    wp_enqueue_script('qtip-js', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true); // Not using imagesLoaded? :( Okay... then this.

    wp_enqueue_style('nprog-css', get_stylesheet_directory_uri() . '/css/nprogress.css', null, false, 'all'); // Add the styles first, in the <head> (last parameter false, true = bottom of page!)
    wp_enqueue_script('nprog-js', get_stylesheet_directory_uri() . '/js/nprogress.js', array('angular'), false, true); // Not using imagesLoaded? :( Okay... then this.

    //wp_enqueue_script('ng-ctrlr', get_stylesheet_directory_uri() . '/js/angular_controllers.js', array('angular'), false, true);
    wp_register_script( 'ng-ctrlr', get_stylesheet_directory_uri() . '/js/angular_controllers.js?v=1' );
    $translation_array = cur_angular_controllers_translations();
    wp_localize_script( 'ng-ctrlr', 'ml_obj', $translation_array );
    wp_enqueue_script("ng-ctrlr");
    
    wp_enqueue_script('page-sr', get_stylesheet_directory_uri() . '/js/page-sr.js', array('ng-ctrlr'), false, true);
    ?>

    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                var baseurl = '<?php echo get_bloginfo('url'); ?>/';</script>

    <style>
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {display: none !important;}
        .site-container{min-width: 250px;}
        #language{float:left;}
        .content{padding:0px !important;}
        .subjectarea{display: none;}
        .show-hide-options{cursor: pointer;}
        .optionset {padding: 0px 20px;}
        .collection-actions div {width: 33.2%;cursor: pointer;}
        .search-term span{cursor: pointer;}
        .search-term span:hover{text-decoration: underline;}
        .disabled{cursor: not-allowed !important;}
        #standards-accordion select {border-radius: 0; width: 75%; height: 254px}
        #standards-accordion select option {border-bottom: solid 1px #7d8d96; padding: 5px;}
        .search-tool-tip{cursor: help}
        .toolTipCustomClass{font-size: 14px !important;color:#124C72 !important;width:600px !important;max-width:700px !important;}
        .toolTipCustomClass li{margin-left:20px; list-style: disc}
        .sort-dropdown{ text-align: right;float: right;}
        .library-collection{position: relative;}
        .tab:hover {
            color: #FFF;
            background: none repeat scroll 0% 0% #124C72;
            padding: 5px 0px;
            top: -4px;
            border-width: 1px 1px medium;
            border-style: solid solid none;
            border-color: #124C72 #124C72 -moz-use-text-color;
            -moz-border-top-colors: none;
            -moz-border-right-colors: none;
            -moz-border-bottom-colors: none;
            -moz-border-left-colors: none;
            border-image: none;
            cursor: pointer;
            display: inline-block !important;
        }
        .library-collection .edit{
            position: absolute;
            right: -1px;
            top: -1px;
            width: 30px;
            height: 30px;
            border: 1px solid #D1D1D1;
            border-radius: 0px 0px 0px 7px;
            background: none repeat scroll 0% 0% #FFF;
            color: rgb(165, 197, 70);
            font-size: 22px;
            padding: 2px 5px;
            line-height: 1;
            cursor: pointer;
        }

        .subject-optionset {padding-right: 0px;position: relative;}
        .subject-optionset li input{margin: 5px;}
        .subject-optionset li{padding-right: 20px;margin: 0px -1px 0px 0px;padding-left: 10px;}
        .subject-optionset li.hover{background: #F1F2F2;border-color: #ADADAD; -moz-use-text-color: #ADADAD #ADADAD;border-width: 1px medium 1px 1px;border-style: solid none solid solid;margin: -1px;}
        .subjectarea-optionset{max-width: 38%;min-width: 38%;min-height: 325px;}
        .subjects .optionset{display:none;}

        @media only screen and (max-width: 1250px) {
            .content-sidebar .content-sidebar-wrap, .full-width-content .content-sidebar-wrap, .sidebar-content .content-sidebar-wrap {
                width: 100%;
            }
        }
        @media screen and (max-width: 1223px) {
            .subjectarea-optionset{min-width: 50%;}
        }
        @media screen and (max-width: 1100px) {
            .subjectarea-optionset{min-width: 60%;}
        }
        @media screen and (max-width: 700px) {
            .subjectarea-optionset{min-width: 50%;}
            .optionset.two-col ul {
                -webkit-column-count: 1 !important;
                -moz-column-count: 1 !important;
                column-count: 1 !important;
            }
            .search-text{display:none;}
            .search-input .search-button-icon {margin-right: 0px;}
            .search-input .search-field{width: 90%;}
            .search-input .search-button{width: 10%;}
            .search-bar .search-options .search-dropdown{height: 20px !important; max-height: 20px !important;}
        }
        @media screen and (max-width: 541px)  {
            .subjectarea-optionset{display:none;}
            .subject-optionset li.hover{background: inherit; -moz-use-text-color: inherit;border-style: none;margin: 0px;}
            .subjects .optionset{display:block;min-width: 170px !important;width: 100% !important;}
            .search-input .search-field{width: 85%;}
            .search-input .search-button{width: 15%;}
        }
        @media screen and (max-width: 350px)  {
            .search-input .search-field{width: 80%;}
            .search-input .search-button{width: 20%;}
        }
        @media screen and (max-width: 270px)  {
            .search-input .search-field{width: 75%;}
            .search-input .search-button{width: 25%;}
        }

        @media screen and (max-width: 430px)  {
            .advance-options-text{display:none;}
        }

    </style>
    <?php
}

function curriki_search_resources_page_body() {
    global $wpdb;
    $current_language = "eng";
    if( defined('ICL_LANGUAGE_CODE') )
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

    
    $isParner = (isset($_REQUEST['partnerid']) AND ! empty($_REQUEST['partnerid']));
    $page_name = get_query_var('name');
    $current_user = wp_get_current_user();

    //$subjects = $wpdb->get_results("SELECT * FROM subjects order by displayname", ARRAY_A);
    $q_subjects = cur_subjects_query($current_language);
    $subjects = $wpdb->get_results($q_subjects, ARRAY_A);
    
    //$subjectareas = $wpdb->get_results("SELECT * FROM subjectareas order by subjectid,displayname", ARRAY_A);
    $q_subjectareas = cur_subjectareas_query($current_language);
    $subjectareas = $wpdb->get_results($q_subjectareas, ARRAY_A);
    
    //$instructiontypes = $wpdb->get_results("SELECT name,displayname from instructiontypes order by displayname", ARRAY_A);
    $q_instructiontypes = cur_instructiontypes_query($current_language);
    $instructiontypes = $wpdb->get_results($q_instructiontypes, ARRAY_A);
    
    $q_language = "select distinct l.language,ml.displayname from resources r 
                   inner join languages l on r.language = l.language
                   inner join languages_ml ml on l.language = ml.language                   
                    WHERE ml.displaylanguage = '$current_language'                    
                   ";
    $languages = $wpdb->get_results($q_language, ARRAY_A);
    $resourcCNT = $wpdb->get_results("select count(*) as CNT from resources where ((type = 'collection' and title <> 'Favorites') or type = 'resource') and active = 'T'; ", ARRAY_A);
    $resourcCNT = $resourcCNT[0]['CNT'];
    $groupCNT = $wpdb->get_results("select count(*) as CNT from cur_bp_groups;  ", ARRAY_A);
    $groupCNT = $groupCNT[0]['CNT'];
    $memberCNT = $wpdb->get_results("select count(*) as CNT from users where active = 'T'; ", ARRAY_A);
    $memberCNT = $memberCNT[0]['CNT'];

    $jurisdictioncode = $wpdb->get_results("select distinct jurisdictioncode from `standards` where active = 'T' order by 1 ", ARRAY_A);
    $standardtitles = $wpdb->get_results("select standardid,title,jurisdictioncode from `standards` where active = 'T' order by 2 ", ARRAY_A);

    $education_levels = array(
        array('title' => __('Preschool (Ages 0-4)','curriki'), 'levels' => 'PreKto12|ElementarySchool|Pre-K|K'),
        array('title' => __('Kindergarten-Grade 2 (Ages 5-7) ','curriki'), 'levels' => 'PreKto12|ElementarySchool|1|2'),
        array('title' => __('Grades 3-5 (Ages 8-10)','curriki'), 'levels' => 'PreKto12|ElementarySchool|3|4|5'),
        array('title' => __('Grades 6-8 (Ages 11-13)','curriki'), 'levels' => 'PreKto12|MiddleSchool|6|7|8'),
        array('title' => __('Grades 9-10 (Ages 14-16)','curriki'), 'levels' => 'PreKto12|HighSchool|9|10'),
        array('title' => __('Grades 11-12 (Ages 16-18)','curriki'), 'levels' => 'PreKto12|HighSchool|11|12'),
        array('title' => __('College & Beyond','curriki'), 'levels' => 'HigherEducation|Graduate|Undergraduate-UpperDivision|Undergraduate-LowerDivision'),
        array('title' => __('Professional Development','curriki'), 'levels' => 'ProfessionalEducation-Development|Vocational Training'),
        array('title' => __('Special Education','curriki'), 'levels' => 'SpecialEducation|LifelongLearning'),
    );

    $education_levels2 = array(
        array('title' => 'Preschool (Ages 0-4) ', 'levels' => '8|9', 'arlevels' => array('K', 'Pre-K')),
        array('title' => 'Kindergarten-Grade 2 (Ages 5-7) ', 'levels' => '3|4', 'arlevels' => array('1', '2')),
        array('title' => 'Grades 3-5 (Ages 8-10)', 'levels' => '5|6|7', 'arlevels' => array('3', '4', '5')),
        array('title' => 'Grades 6-8 (Ages 11-13)', 'levels' => '11|12|13', 'arlevels' => array('6', '7', '8')),
        array('title' => 'Grades 9-10 (Ages 14-16)', 'levels' => '15|16', 'arlevels' => array('9', '10')),
        array('title' => 'Grades 11-12 (Ages 16-18)', 'levels' => '17|18', 'arlevels' => array('11', '12')),
        array('title' => 'College & Beyond', 'levels' => '23|24|25', 'arlevels' => array('Graduate', 'Undergraduate-UpperDivision', 'Undergraduate-LowerDivision')),
        array('title' => 'Professional Development', 'levels' => '19|20', 'arlevels' => array('ProfessionalEducation-Development', 'Vocational Training')),
        array('title' => 'Special Education', 'levels' => '26|21', 'arlevels' => array('SpecialEducation', 'LifelongLearning')),
    );
    /* echo'<pre>';
      print_r($_REQUEST);
      echo'</pre>'; */
    branding_open();
    ?>

    <div class="search-content" ng-app="ngApp" ng-controller="searchCtrl" ng-cloak
         ng-init="searchTab = '<?php echo (isset($_GET['t']) ? addslashes($_GET['t']) : 'resource'); ?>';
                                 baseurl = '<?php echo get_bloginfo('url'); ?>/';
                                 ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                                 query = '<?php echo isset($_REQUEST['q']) ? str_replace(array('\"', "\'"), array('&quot;', ''), $_REQUEST['q']) : ''; ?>';
                                 submitted = '<?php echo isset($_REQUEST['q']); ?>';
                                 education_levels2 = <?php echo str_replace('"', '&quot;', json_encode($education_levels2)); ?>;
                                 jurisdictioncodeArr = <?php echo str_replace('"', '&quot;', json_encode($jurisdictioncode)); ?>;
                                 standardtitlesArr = <?php echo str_replace('"', '&quot;', json_encode($standardtitles)); ?>;
                                 language_model = '<?php echo isset($_REQUEST['slanguage']) ? $_REQUEST['slanguage'] : ''; ?>';
                                 init = init();
         " >
        <div class="wrap container_12" >
            <form action="" method="POST" id="search_form">
                <input type="hidden" name="search_type" id="search_type" value="" ng-model="searchTab" />
                <input type="hidden" name="pageNumber" id="pageNumber" value="" ng-model="pagination.current" />
                <input type="hidden" name="partnerid" id="partnerid" value="<?php echo $isParner && !isset($_REQUEST['search_from_all']) ? $_REQUEST['partnerid'] : ''; ?>" />

                <div class="search-bar grid_12">
                    <span class="search-widget">
                        <?php if ($page_name == 'resources-curricula') { ?>
                            <h2 style="color:#7DA941;"><?php echo __("Resource Library","curriki"); ?></h2>
                            <div class="feed">
                                <a href="<?php echo $feed_url = site_url() . "/activity/feed/?act=rs"; ?>" title="<?php _e('RSS', 'buddypress'); ?>"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/rss.png" alt="" class="rss-img-top" /><?php _e('RSS', 'buddypress'); ?></a>
                            </div>
                            <p class="desc"><?php echo __('Curriki supports you with thousands of thoroughly vetted online learning activities in all major K-12 subject areas in many formats.','curriki'); ?></p>
                        <?php } ?>

                        <div class="search-tabs">

                            <div class="resource-tab tab rounded-borders-top selected" ng-click="changeSearchType('resource');"><span class="tab-icon fa fa-book strong"></span><span class="tab-text"><strong><?php echo __('Resources','curriki'); ?></strong> <span ng-hide="<?php echo $isParner; ?>">(<?php echo number_format($resourcCNT); ?>)</span></span></div>
                            <?php if (SUBDOMAIN == '' AND ! $isParner) { ?>
                                <div class="groups-tab tab rounded-borders-top" ng-click="changeSearchType('groups');" ><span class="tab-icon fa fa-users strong"></span><span class="tab-text"><strong><?php echo __('Groups','curriki'); ?></strong> (<?php echo number_format($groupCNT); ?>)</span></div>
                                <div class="members-tab tab rounded-borders-top" ng-click="changeSearchType('members');"><span class="tab-icon fa fa-user strong"></span><span class="tab-text"><strong><?php echo __('Members','curriki'); ?></strong> (<?php echo number_format($memberCNT); ?>)</span></div>
                            <?php } ?>

                            <div class="search-tips search-tool-tip search-text"><a><?php echo __('Search Tips','curriki'); ?></a></div>
                            <div class="search-tips"><a href="javascript:void(0)" ng-click="clearSearch()"><?php echo __('New Search','curriki'); ?></a>
                                <span class="search-text">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            </div>

                            <div class="search-tool-tip-text" style="display:none">
                                <Strong><?php echo __('For clearer results, try some of these easy techniques in the search entry box.','curriki'); ?></strong>
                                <ol style="list-style: disc">
                                    <li><?php echo __("By default, searches are performed using the exact phrase entered: 'George Washington' will search for that phrase.",'curriki'); ?></li>
                                    <li><?php echo __('You can also use quotes to search for an exact phrase, as in "The Sun Also Rises" or "George Washington."','curriki'); ?></li>
                                    <li><?php echo __("A comma acts as an OR.  'George, Washington' will return resources that contain either 'George' or 'Washington'.",'curriki'); ?></li>
                                    <li><?php echo __('Not sure of the exact word? Crop it to a shorter form and use an asterisk (*). For example, Read* will return Reads, Reader, Reading, etc.','curriki'); ?></li>
                                    <li><?php echo __('Not sure of the spelling? Use a question mark (?) to replace a single letter. For example, Read? will return Reads, Ready, etc. You can even use multiple questions marks. For example, P??r will return Pour, Poor, Pear, Peer, etc.','curriki'); ?></li>
                                    <li><?php echo __('Not happy with crossover results? Reduce the number of returns by using a minus symbol (-). For example, "George Washington" -Carver will remove any returns with the word Carver.','curriki'); ?></li>
                                    <li><?php echo __('Use the word "Or" to broaden a search. For example, England or Britain will find resources with either one, but not necessarily both.','curriki'); ?></li>
                                    <li><?php echo __('Or, you can combine any of the above.','curriki'); ?></li>
                                </ol>
                            </div>
                        </div>

                        <div class="search-input">
                            <div class="search-field"><input class="rounded-borders-left" placeholder="Start Searching" type="text" name="query" ng-model="query" value="<?php if (isset($_REQUEST['q'])) echo urldecode($_REQUEST['q']); ?>" ></div>
                            <div class="search-button"><button type="submit" class="rounded-borders-right"  ng-click="btnClickSearch()"><span class="search-button-icon fa fa-search"></span><span class="search-text"><?php echo __('Search','curriki'); ?></span></button></div>
                        </div>

                        <span id="resources-tab"  class="tab-container">
                            <div class="search-options rounded-borders-bottom border-grey">

                                <select name="slanguage" id="language" class="search-dropdown" ng-model="language_model" ng-change="setLanguage()" style="margin-bottom: 0px; height: 25px !important">
                                    <option value=""><?php echo __('Language','curriki'); ?></option>
                                    <?php
                                    foreach ($languages as $l) {
                                        echo '<option value="' . $l['language'] . '">' . $l['displayname'] . '</option>';
                                    }
                                    ?>
                                </select>

                                <div class="show-hide-options close-button" onclick="advance('close')" style="display: none;" >Close <span class="show-hide-icon fa fa-times-circle-o" ></span></div>
                                <div class="show-hide-options standards-search advance-options-text" onclick="advance('standard')" ng-hide="search_type != ''"><span class="show-hide-icon fa fa-plus-circle"></span><?php echo __('Search by Standard','curriki'); ?></div>
                                <div class="show-hide-options advance-search" onclick="advance('advanced')"><span class="show-hide-icon fa fa-plus-circle" ></span><?php echo __('More','curriki'); ?><span class="advance-options-text"> <?php echo __('options','curriki'); ?></span></div>
                                <div style="clear:both"></div>

                                <div class="search-slide advanced-slide"  style="display: none;">
                                    <div class="optionset subject-optionset">
                                        <div class="optionset-title" ><?php echo __('Subject','curriki'); ?></div>
                                        <ul class="subjects">
                                            <?php
                                            foreach ($subjects as $sub)
                                                echo '<li onmouseover="showHoverSubjects(\'subjectarea_' . $sub['subjectid'] . '\',this)" subjectid="' . $sub['subjectid'] . '">'
                                                . '<label style="display:block"><input name="subject[' . $sub['subjectid'] . ']" type="checkbox" value="' . $sub['subject'] . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this, \'subjectarea_' . $sub['subjectid'] . '\')" >' . $sub['displayname'] . '</label>'
                                                . '<div class="optionset two-col grey-border " style="margin-left: 20px;" >'
                                                . '<div class="optionset-title subjectarea subjectarea_' . $sub['subjectid'] . '">Subject Area</div>'
                                                . '<ul style="margin-left: -10px;"></ul></div>'
                                                . '<div style="clear:both"></div></li>';
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="optionset two-col grey-border subjectarea-optionset">
                                        <div class="optionset-title"><?php echo __('Subject Area','curriki'); ?></div>
                                        <ul class="subjectareas">
                                            <?php
                                            foreach ($subjectareas as $sub)
                                                echo '<li class="subjectarea subjectarea_' . $sub['subjectid'] . '" subjectid="' . $sub['subjectid'] . '" >'
                                                . '<label><input name="subjectarea[' . $sub['subjectareaid'] . ']" type="checkbox" value="' . $sub['subjectarea'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')">' . $sub['displayname'] . '</label></li>';
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="optionset">
                                        <div class="optionset-title"> <?php echo __('Education Level','curriki'); ?> </div>
                                        <ul><?php
                                            foreach ($education_levels as $ind => $el)
                                                echo '<li><label><input name="education_level[' . $ind . ']" type="checkbox" value="' . (isset($el['levels']) ? $el['levels'] : "") . '" ' . (in_array($el['levels'], ( isset($_REQUEST['education_level']) ? $_REQUEST['education_level'] : array())) ? 'checked' : '') . '>' . $el['title'] . '</label></li>';
                                            ?>
                                        </ul>
                                        <div class="optionset-title" ><?php echo __('Rating','curriki'); ?></div>
                                        <ul ><li><input name="partners" type="checkbox" value=""><?php echo __('Partners','curriki'); ?></li><li><input name="reviewrating" type="checkbox" value=""><?php echo __('Top Rated by Curriki','curriki'); ?></li><li><input name="memberrating" type="checkbox" value=""><?php echo __('Top Rated by Members','curriki'); ?></li></ul>
                                    </div>

                                    <div class="optionset" s>
                                        <div class="optionset-title"><?php echo __('Type','curriki'); ?></div>

                                        <ul><?php
                                            foreach ($instructiontypes as $type)
                                                echo '<li><label><input name="type[' . (isset($sub['instructiontypeid']) ? $sub['instructiontypeid'] : "") . ']" type="checkbox" value="' . (isset($type['name']) ? $type['name'] : "") . '" ' . (in_array($type['name'], (isset($_REQUEST['type']) ? $_REQUEST['type'] : array())) ? 'checked' : '') . '>' . $type['displayname'] . '</label></li>';
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>

                                <!--            <div class="search-options rounded-borders-bottom border-grey">
                                
                                              <select name="slanguage" id="language" class="search-dropdown" ng-model="language_model" ng-change="setLanguage()" style="margin-bottom: 0px; height: 25px !important">
                                                <option value="">All Languages</option>
                                <?php
                                foreach ($languages as $l) {
                                    echo '<option value="' . $l['language'] . '">' . $l['displayname'] . '</option>';
                                }
                                ?>
                                              </select>
                                
                                              <div class="show-hide-options close-button" onclick="advance('close')" style="display: none;" >Close <span class="show-hide-icon fa fa-times-circle-o" ></span></div>
                                              <div class="show-hide-options advance-search" onclick="advance('advanced')"><span class="show-hide-icon fa fa-plus-circle" ></span>More Search Options</div>
                                              <div class="show-hide-options search-standards" onclick="advance('standard')" ng-hide="search_type != ''"><span class="show-hide-icon fa fa-plus-circle"></span>Search by Standard</div>
                                
                                              <div class="search-slide advanced-slide"  style="display: none;">
                                                <div class="optionset">
                                                  <div class="optionset-title">Subject</div>
                                
                                                  <ul>
                                <?php
                                $request_subject = isset($_REQUEST['subject']) ? $_REQUEST['subject'] : array();
                                foreach ($subjects as $sub) {
                                    echo '<li ng-mouseover="subjectHover(' . ( isset($sub['subjectid']) ? $sub['subjectid'] : "" ) . ')" ><input name="subject[]" type="checkbox" value="' . ( isset($sub['subject']) ? $sub['subject'] : "") . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this,\'subjectarea_' . $sub['subjectid'] . '\')" ' . (isset($sub['subject']) && is_array($request_subject) && in_array($sub['subject'], $request_subject) ? 'checked' : '') . ' >' . $sub['displayname'] . '</li>';
                                }
                                ?>
                                                  </ul>
                                                </div>
                                
                                                <div class="optionset two-col grey-border" style="max-width: 38%;min-width: 38%;min-height: 320px;">
                                                  <div class="optionset-title">Subject Area</div>
                                                  <ul>
                                <?php
                                $requiest_subjectarea = $_REQUEST['subjectarea'] ? $_REQUEST['subjectarea'] : array();
                                foreach ($subjectareas as $sub) {
                                    echo '<li ng-show="isSubjectHover(' . $sub['subjectid'] . ')"><input name="subjectarea[]" type="checkbox" value="' . $sub['subjectarea'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')" ' . (isset($sub['subjectarea']) && is_array($requiest_subjectarea) && in_array($sub['subjectarea'], $requiest_subjectarea) ? 'checked' : '') . '>' . $sub['displayname'] . '</li>';
                                }
                                ?>
                                                  </ul>
                                                </div>
                                
                                                <div class="optionset">
                                                  <div class="optionset-title"> Education Level </div>
                                                  <ul><?php
                                $request_education_level = isset($_REQUEST['education_level']) ? $_REQUEST['education_level'] : array();
                                foreach ($education_levels as $el)
                                    echo '<li><input name="education_level[]" type="checkbox" value="' . $el['levels'] . '" ' . (is_array($request_education_level) && in_array($el['levels'], $request_education_level) ? 'checked' : '') . '>' . $el['title'] . '</li>';
                                ?>
                                                  </ul>
                                                  <div class="optionset-title" ng-hide="search_type != ''">Rating</div>
                                                  <ul ng-hide="search_type != ''">
                                                    <li><input name="partners" type="checkbox" value="" <?php echo isset($_REQUEST['partners']) ? 'checked="checked"' : ''; ?>>Partners</li>
                                                    <li><input name="reviewrating" type="checkbox" value="" <?php echo isset($_REQUEST['reviewrating']) ? 'checked="checked"' : ''; ?>>Top Rated by Curriki</li>
                                                    <li><input name="memberrating" type="checkbox" value="" <?php echo isset($_REQUEST['memberrating']) ? 'checked="checked"' : ''; ?>>Top Rated by Members</li></ul>
                                                </div>
                                
                                                <div class="optionset" ng-hide="search_type != ''">
                                                  <div class="optionset-title">Type</div>
                                
                                                  <ul><?php
                                $request_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : array();
                                foreach ($instructiontypes as $type)
                                    echo '<li><input name="type[]" type="checkbox" value="' . $type['name'] . '" ' . (isset($type['name']) && is_array($request_type) && in_array($type['name'], $request_type) ? 'checked' : '') . '>' . $type['displayname'] . '</li>';
                                ?>
                                                  </ul>
                                                </div>
                                
                                                <div class="clearfix"></div>
                                              </div>-->

                                <div class="search-slide standards-slide"  style="display: none;">

                                    <ul id="standards-accordion" class="border-grey rounded-borders-full">
                                        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3><?php echo __('Jurisdiction/Organization','curriki'); ?></h3></div>
                                            <select class="block" multiple size="9" name="jurisdictioncode[]" id="jurisdictioncode" ng-model="jurisdictioncodeVal" ng-change="getDocumentTitle()"> 
                                                <option value="{{op.jurisdictioncode}}" ng-repeat="op in jurisdictioncodeArr track by $index">{{op.jurisdictioncode}}</option>
                                            </select>
                                        </li>
                                        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3><?php echo __('Document Title','curriki'); ?></h3></div>
                                            <select class="block" multiple size="9" name="standardid[]" id="standardtitles" ng-model="standardtitlesVal" ng-change="getNotation()">
                                                <option value="{{op.standardid}}" data-ng-repeat="op in standardtitlesArr| filter:filterJuris">{{op.title}}</option>
                                            </select>
                                        </li>
                                        <li class="standards-accordion-tab"><div class="standards-tab-header"><h3><?php echo __('Course of Study','curriki'); ?></h3></div>
                                            <select class="block" multiple size="9" name="statementid[]" id="notations" ng-model="notationVal" ng-change="">
                                                <option value="{{op.statementid}}" data-ng-repeat="op in notationArr track by $index">{{op.description}}</option>
                                            </select>
                                        </li>
                                    </ul>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </span>

                    </span>
                </div>

                <span ng-hide="(items.length || items_groups.length || items_members.length)">
                    <?php echo branding_text_under_search(); ?>
                </span>

                <div class="search-results-showing grid_12 clearfix clearfix" id="search_results_pointer"  ng-show="searched_query">

                    <div class="search-term grid_8 alpha">
                        <h4><?php echo isset($_GET["lalag"]) ? "*** " : ""?><?php echo __('Showing results for','curriki'); ?> "{{searched_query}}"</h4>
                        <span ng-show="suggested_query" ng-click="applySuggestedQuery()"> Did you mean to search "{{suggested_query}}" ?</span>
                        <br/>
                        <span ng-hide="resultCoun"> No Result Found</span>
                        <span ng-show="resultCoun"> {{resultCoun}} Results Found</span>
                    </div>

                    <div class="search-dropdown grid_4 omega sort-dropdown">
                        <strong>Sort by: </strong>
                        <select name="sort_by" id="sort_by" ng-model="sort_by_model" ng-change="makeSearch()">
                            <option value="">All Records</option>
                            <option value="title_a_z" >Title [A-Z]</option>
                            <option value="title_z_a">Title [Z-A]</option>
                            <option value="newest" ng-show="search_type != '_members'">Newest first</option>
                            <option value="oldest" ng-hide="search_type != ''">Oldest first</option>
                            <option value="member_rating" ng-hide="search_type != ''">Member rating</option>
                            <option value="curriki_rating" ng-hide="search_type != ''">Curriki rating</option>
                            <option value="aligned" ng-hide="search_type != ''">Standards aligned</option>
                        </select>
                    </div>

                </div>

                <div class="resources grid_12">
                    <!---- Collection Card ----->

                    <div class="collection-card card rounded-borders-full border-grey library-collection" ng-repeat='item in items'>

                        <?php 
                            // if (isset($current_user->caps['administrator'])) { 
                            if (false) {
                        ?>
                            <div class="edit"><a class="fa fa-pencil" href="<?php echo get_bloginfo('url'); ?>/create-resource/?resourceid={{item.fields.id}}" target="_blank"></a></div>
                        <?php } ?>

                        <div class="collection-body">
                            <div class="collection-image">
                                <div class="library-icon-sr">                    
                                    <span ng-show="item.fields.resourcetype == 'collection'" class="fa fa-folder"></span>
                                    <span ng-show="item.fields.resourcetype == 'resource'" class="fa fa-image"></span>
                                </div>
                            </div>

                            <div class="collection-body-inner">
                                <div class="collection-body-title">
                                    <div class="collection-title">
                                        <h3>
                                            <a href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}" ng-show="item.fields.title" target="_blank">{{item.fields.title}}</a>
                                            <a href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}" ng-hide="item.fields.title" target="_blank">Go to Resource</a>
                                            <input type="hidden" name="rid-fld" class="rid-fld" value="{{item.fields.id}}" />
                                        </h3> 
                                        <span class="collection-grade"><strong>{{userGrades(item.fields.educationlevel)}}</strong></span>
                                    </div>

                                    <div class="collection-author">
                                        <img ng-show="item.fields.avatarfile" alt="member-name" class="alignleft" ng-src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/{{item.fields.avatarfile}}" >
                                        <img ng-hide="item.fields.avatarfile" alt="member-name" class="alignleft" ng-src="http://curriki.org/wp-content/themes/genesis-curriki/images/user-icon-sample.png">
                                        <span class="member-name name vertical-align"><a href="javascript:void(0);">{{memberName(item.fields.firstname, item.fields.lastname)}}</a></span>
                                        <span class="more-from-member name vertical-align"><a href="<?php echo get_bloginfo('url'); ?>/user-library/?user={{item.fields.user_nicename}}">More from this member</a></span>
                                    </div>
                                </div>

                                <div class="collection-body-content">
                                    <div class="collection-description" ng-bind-html="item.fields.description || item.fields.title"></div>

                                    <div class="collection-rating rating">
                                        <span class="member-rating-title">Member Rating</span>
                                        <span class="fa" ng-class="item.fields.memberrating >= 1 ? 'fa-star' : '<?php echo "fa-star-o"; ?>'"></span>
                                        <span class="fa" ng-class="item.fields.memberrating >= 2 ? 'fa-star' : '<?php echo "fa-star-o"; ?>'"></span>
                                        <span class="fa" ng-class="item.fields.memberrating >= 3 ? 'fa-star' : '<?php echo "fa-star-o"; ?>'"></span>
                                        <span class="fa" ng-class="item.fields.memberrating >= 4 ? 'fa-star' : '<?php echo "fa-star-o"; ?>'"></span>
                                        <span class="fa" ng-class="item.fields.memberrating >= 5 ? 'fa-star' : '<?php echo "fa-star-o"; ?>'"></span>
                                        <?php if (get_current_user_id() > 0) { ?>
                                            <a href="javascript:;" ng-click="curriki_rateThis(item.id, item.fields.title);">Rate this collection</a>
                                        <?php } ?>                    
                                    </div>

                                    <div class="collection-curriki-rating curriki-rating"> 
                                        <span class="curriki-rating-title">Curriki Rating</span>

                                        <span class="rating-badge" ng-show="item.fields.reviewstatus == 'reviewed' && item.fields.reviewrating != null && roundNum(item.fields.reviewrating) >= 0" >{{roundNum(item.fields.reviewrating)}}</span>
                                        <span class="rating-badge" ng-show="item.fields.reviewstatus == 'reviewed' && item.fields.reviewrating != null && roundNum(item.fields.reviewrating) < 0" > - </span>
                                        <span class="rating-badge" ng-show="(item.fields.reviewstatus != 'reviewed' || item.fields.reviewrating == null) && item.fields.partner == 'T'">P</span>
                                        <span class="rating-badge" ng-show="(item.fields.reviewstatus != 'reviewed' || item.fields.reviewrating == null) && item.fields.partner != 'T'">NR</span>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="collection-more-info" ng-show="is_coll_more(item, 'more')">
                            <div class="collection-views-license">
                                <p>{{item.fields.resourceviews}} Views</p> <p>{{item.fields.collections}} Collection(s)</p> <p><a target="_blank" ng-href="{{showLicense(item.fields.license, 'link')}}"><img ng-src="{{showLicense(item.fields.license, 'image')}}" /></a></p>
                            </div>

                            <div class="collection-more-info-content">
                              <!--<span ng-show="item.fields.alignments != ''">
                                <strong>Alignment:</strong> {{item.fields.alignments}}
                              </span>-->
                                <div class="collection-type">
                                    <strong ng-show="item.fields.standard != ''">Alignment:</strong> 
                                    <ul>
                                        <li ng-repeat="rec in item.fields.standard track by $index">{{rec}}</li>
                                    </ul>
                                </div>
                                <div ng-show="item.fields.resource_collections != '' && item.fields.type == 'collection'" class="collection-resources">
                                    <strong>Resources in Collection:</strong>
                                    <ul>
                                        <li ng-repeat="rec in item.fields.resource_collections">{{rec}}</li>
                                    </ul>
                                </div>

                                <div ng-show="item.fields.subjects != ''" class="collection-subjects">
                                    <strong>Subjects:</strong> {{item.fields.subjects}}
                                </div>

                                <div ng-show="item.fields.educationlevel != ''" class="collection-grades">
                                    <strong>Grade Levels:</strong>
                                    <ul>
                                        <li ng-repeat="rec in item.fields.educationlevel">{{rec}}</li>
                                    </ul>
                                </div>

                                <div class="collection-type">
                                    <strong ng-show="item.fields.instructiontype != ''">Types:</strong> 
                                    <ul>
                                        <li ng-repeat="rec in item.fields.instructiontype">{{rec}}</li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="collection-actions" id="collection-tabs">
                            <div class="more-collection-info" ng-click="coll_more_func('')" ng-show="is_coll_more(item, 'more')"><span class="fa fa-caret-up"></span> Less Info</div>
                            <div class="more-collection-info" ng-click="coll_more_func(item, 'more')" ng-hide="is_coll_more(item, 'more')"><span class="fa fa-caret-down"></span> More Info</div>
                            <div class="share-collection" ng-click="coll_more_func(item, 'share')"><span class="fa fa-share-alt-square"></span> Share </div>
                            <?php if (is_user_logged_in()) { ?><div class="add-to-library" ng-click="add_to_my_library(item.id)"><span class="fa fa-plus-circle"></span> Add <span class="hide-small">to My Library </span></div><?php } ?>
                        </div>

                        <div class="collection-share" ng-show="is_coll_more(item, 'share')">
                            <div class="collection-share-buttons share-icons">
                                <p>Share this link via</p>
                                <a class="share-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}%23.VVSq-YDZN0Y.facebook"><span class="fa fa-facebook"></span></a>
                                <a class="share-twitter" target="_blank" href="https://twitter.com/intent/tweet?text=Check out this great resource I found on Curriki! {{item.fields.title}}-OER via @Curriki&url=<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}"><span class="fa fa-twitter"></span></a>
                                <!--a class="share-pinterest" 
                                   href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}" 
                                   onclick="return addthis_sendto('pinterest_share');"
                                   onblur="if (_ate.maf.key == 9){_ate.maf.key = null; } else{_ate.maf.key = null; addthis_close(); }"
                                   onkeydown="if (!e){var e = window.event || event; }if (e.keyCode){_ate.maf.key = e.keyCode; } else{if (e.which){_ate.maf.key = e.which; }}"
                                   onkeypress="if (!e){var e = window.event || event; }if (e.keyCode){_ate.maf.key = e.keyCode; } else{if (e.which){_ate.maf.key = e.which; }}" 
                                   ><span class="fa fa-pinterest"></span></a-->
                                <a class="share-email" href="mailto:?subject=See this article&amp;body=<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}"><span class="fa fa-envelope-o"></span></a>
                            </div>

                            <div class="collection-share-link"><p>Or copy and paste this link</p><input readonly type="text" value="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}"></div>
                        </div>

                    </div>

                    <!---- End Collection Card ----->
                </div>

                <div class="groups grid_12">
                    <div class="group-card card rounded-borders-full border-grey fixed_grid_3 join-group" ng-repeat='item in items_groups'>
                        <div class="card-header">
                            <div><a href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}/"><img width="100" height="100" title="{{item.fields.title}}" alt="Group logo of {{item.fields.title}}" class="circle aligncenter group-2344-avatar avatar-100 photo" ng-src="{{item.fields.image}}"></a></div>
                            <span class="group-name name"><a href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}/">{{item.fields.title}}</a></span>
                            <br>
                        </div>
                        <div class="card-stats">
                            <span class="stat"><span class="fa fa-users"></span>{{item.fields.groups_users_count}}</span>
                            <span class="stat" ng-show="item.fields.forum_id"><span class="fa fa-comments"></span>{{item.fields.groups_comments_count}}</span>
                            <span class="stat"><span class="fa fa-book"></span>{{item.fields.groups_resources_count}}</span>
                        </div>
                        <div class="card-description"><p>{{item.fields.description}}</p></div>

                        <div class="card-button action">				

                            &nbsp;
                        </div>
                    </div>
                </div>

                <div id="members-dir-list" class="members dir-list">
                    <ul id="members-list" class="item-list" role="main">
                        <li ng-repeat="item in items_members">
                            <div class="member-card card rounded-borders-full border-grey fixed_grid_2 follow">
                                <div class="card-header">
                                    <a href="<?php echo get_bloginfo('url'); ?>/{{item.fields.url}}">
                                        <img ng-hide="item.fields.uniquavatarfile" width="100" height="100" alt="Profile picture of {{item.fields.firstname}} {{item.fields.lastname}}" class="border-grey user-123653-avatar avatar-100 photo" src="http://gravatar.com/avatar/65812993458627eba7458317c3790de4?d=mm&amp;s=100&amp;r=G">
                                        <img ng-show="item.fields.uniquavatarfile" width="100" height="100" alt="Profile picture of {{item.fields.firstname}} {{item.fields.lastname}}" class="border-grey user-123653-avatar avatar-100 photo" ng-src="https://<?php echo DBI_AWS_S3_CDN_URL; ?>/avatars/{{item.fields.uniquavatarfile}}">
                                    </a>
                                    <span class="member-name name"><a href="javascript:void(0);">{{item.fields.firstname}} {{item.fields.lastname}}</a></span>
                                </div>
                                <div class="card-stats">
                                    <span class="stat"><span class="fa fa-users"></span>{{item.fields.groups_total}}</span>
                                    <span class="stat"><span class="fa fa-user"></span>{{item.fields.friends_total}}</span>
                                    <span class="stat"><span class="fa fa-comments"></span>{{item.fields.topics_count}}</span>
                                    <span class="stat"><span class="fa fa-book"></span>{{item.fields.resources_count}}</span>
                                </div>
                                <div class="card-button action"></div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!---- Start Pagination ----->
                <div class="pagination"  ng-show="(items.length || items_groups.length || items_members.length) && pagination.totalPages" ng-init="pagination = {current: 1, totalPages: 9, pageSize: 10, totalItems: 90, pageLinks: [], isPaginationCall: 0}">
                    <a class="pagination-first" href="#" ng-hide="pagination.current == 1" ng-click="setCurrentPage(1)"><span class="fa fa-angle-double-left"></span></a>
                    <a class="pagination-previous" href="#" ng-hide="pagination.current == 1" ng-click="setCurrentPage(1)"><span class="fa fa-angle-left"></span> Previous</a>
                    <a class="pagination-num" 
                       ng-repeat="pageNum in pagination.pageLinks" 
                       ng-click="setCurrentPage(pageNum)"
                       ng-class="{current : pagination.current == pageNum, disabled:(pagination.current == pageNum || pagination.current == - 1)}">
                        {{pageNum}}
                    </a>
                    <a class="pagination-next" href="#" ng-hide="pagination.current == pagination.totalPages" ng-click="setCurrentPage(pagination.current + 1)">Next <span class="fa fa-angle-right"></span></a>
                    <a class="pagination-last" href="#" ng-hide="pagination.current == pagination.totalPages" ng-click="setCurrentPage(pagination.totalPages)"><span class="fa fa-angle-double-right"></span></a>
                </div>
                <!---- End Pagination ----->
            </form>
        </div>
    </div>
    <?php
    branding_close();
}

function branding_open() {
    switch (SUBDOMAIN) {
        case 'search':
            ?>
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
                    <?php
                    break;
            }
        }

        function branding_close() {
            switch (SUBDOMAIN) {
                case 'search':
                    ?>
                </div>
            </div>
            <?php
            break;
    }
}

function branding_text_under_search() {
    switch (SUBDOMAIN) {
        case 'search':
            ?>
            <span class="grid_6">
                <p><span style="color: #393938;"><span style="font-family: 'Times New Roman', serif;"><span style="font-size: xx-large;">Curriki Curated and Aligned Mathematics Resources</span></span></span></p>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Curriki has curated OERs along a scope and sequence into standards-aligned modules for Pre-algebra, Algebra 1, and Geometry, plus Project-Based Learning Units for Algebra 1 and Geometry.</span></span></span></p>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curated Collections</strong></span></span></span></p>
                <p style="width:100%;vertical-align: text-top">
                    <span style="font-size: medium;display: inline-block;float:left;padding-top: 8px;">Sponsored by</span>
                    <img style="display: inline-block;margin-left: 20px;border: 1px solid rgb(18, 76, 114); border-radius: 4px;padding: 2px;" src="https://www.curriki.org/wp-content/uploads/2015/11/att_hz_rgb_grd_wht_search.jpg"/> 
                </p>
                <span class="entry-content">
                    <ul >
                        <li>
                            <h5 class="western"><a target="_blank" href="<?php echo site_url(); ?>/oer/Prealgebra-aligned-to-CCSS-M-Standards/"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curriki Pre-algebra&nbsp;Collection</strong></span></span></span></a><span style="color: #70706e;"><span style="font-family: Arial, serif;"><span style="font-size: large;">&nbsp; </span></span></span></h5>
                        </li>
                        <li>
                            <h5 class="western"><a target="_blank" href="<?php echo site_url(); ?>/oer/geometry-aligned-to-CCSS-M-Standards/"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curriki Geometry&nbsp;Collection</strong></span></span></span></a><span style="color: #70706e;"><span style="font-family: Arial, serif;"><span style="font-size: large;">&nbsp;</span></span></span></h5>
                        </li>
                        <li>
                            <h5 class="western"><a target="_blank" href="<?php echo site_url(); ?>/oer/Algebra-1-Aligned-to-CCSS-M-Standards/"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curriki Algebra 1&nbsp;Collection</strong></span></span></span></a><span style="color: #70706e;"><span style="font-family: Arial, serif;"><span style="font-size: large;">&nbsp;</span></span></span></h5>
                        </li>
                    </ul>
                </span>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Project Based Learning&nbsp;</strong></span></span></span></p>
                <p style="width:100%;vertical-align: text-top">
                    <span style="font-size: medium;display: inline-block;float:left;padding-top: 8px;">Sponsored by</span>
                    <img style="display: inline-block;margin-left: 20px;border: 1px solid rgb(18, 76, 114); border-radius: 4px;padding: 2px;" src="https://www.curriki.org/wp-content/uploads/2015/11/att_hz_rgb_grd_wht_search.jpg"/> 
                </p>
                <span class="entry-content">
                    <ul >
                        <li>
                            <p><a target="_blank" href="<?php echo site_url(); ?>/groups/GeometryBetaTesters/library/?library_sorting=ctr"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curriki PBL Geometry</strong></span></span></span></a><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>&nbsp;</strong></span></span></span></p>
                        </li>
                        <li>
                            <p><a target="_blank" href="<?php echo site_url(); ?>/oer/Curriki-Algebra--/"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Curriki PBL Algebra</strong></span></span></span></a><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: large;">&nbsp;</span></span></span></p>
                        </li>
                    </ul>
                </span>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Each set of materials includes units with video instruction, exercises, and practice materials &ndash; all aligned to standards. &nbsp;Pre-algebra, Geometry, and Algebra 1 include materials for both teacher and student.</span></span></span></p>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">These curated and aligned course OERs will enable educators, parents, and students to quickly and easily find vetted resources that map to standards and learning objectives. All collections are&nbsp;available free to educators, students and parents.</span></span></span></p>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">In the spirit of OERs, Curriki encourages educators and districts to adapt the materials to meet their own needs. Educators may:</span></span></span></p>
                <span class="entry-content">
                    <ul >
                        <li>
                            <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Use the videos to flip their calculus class</span></span></span></p>
                        </li>
                        <li>
                            <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Integrate the aligned materials into their existing curriculum</span></span></span></p>
                        </li>
                        <li>
                            <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Build a brand new curriculum with the OER as the basis</span></span></span></p>
                        </li>
                    </ul>
                </span>
                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">The collections are&nbsp;designed to accommodate many different students&rsquo; learning styles and levels of comprehension. For example, students struggling with algebra can use the collection for extra instruction and practice. Others can move ahead of the class&rsquo;s pace or brush up for the final exam. Specific resources have been selected to enable students to master mathematics&nbsp;skills and the knowledge to achieve college and career readiness.</span></span></span></p>
                <p><br /><br /></p>
            </span>


            <span class="grid_6">
                <p><span style="color: #393938;"><span style="font-family: 'Times New Roman', serif;"><span style="font-size: xx-large;">Curriki Professional Development </span></span></span></p>
                <span class="entry-content">
                    <ul >
                        <li>
                            <h5 class="western"><a target="_blank" href="https://www.curriki.org/introduction-to-computational-thinking-pd/"><span style="color: #53830c;"><span style="font-family: Arial, serif;"><span style="font-size: large;"><strong>Problem Solving through Computational Thinking for Educators.</strong></span></span></span></a><span style="color: #70706e;"><span style="font-family: Arial, serif;"><span style="font-size: large;">&nbsp; </span></span></span></h5>
                        </li>
                    </ul>
                </span>

                <p><span style="color: #393938;"><span style="font-family: Arial, serif;"><span style="font-size: medium;">Curriki now offers a self-paced Professional Development (PD) course for K-12 teachers interested in learning how to infuse Computational Thinking (CT) into their classes.</span></span></span></p>
                <p style="width:100%;vertical-align: text-top">
                    <span style="font-size: medium;display: inline-block;float:left;padding-top: 8px;">Sponsored by</span>
                    <img style="display: inline-block;margin-left: 20px;border: 1px solid rgb(18, 76, 114); border-radius: 4px;padding: 2px;" src="https://www.curriki.org/wp-content/uploads/2015/11/att_hz_rgb_grd_wht_search.jpg"/> 
                </p>
            </span>
            <?php
            break;
    }
}

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');
genesis();
