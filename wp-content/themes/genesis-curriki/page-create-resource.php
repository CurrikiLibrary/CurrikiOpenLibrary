<?php
/*
 * Template Name: Create Resource Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 * 
 * 
 */
// Add custom body class to the head
if (!is_user_logged_in() and function_exists('curriki_redirect_login')) {
    curriki_redirect_login();
    die;
}

add_filter('body_class', 'curriki_create_resource_add_body_class');

function curriki_create_resource_add_body_class($classes) {
    $classes[] = 'backend create-resource';
    return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_create_resource_loop');

function curriki_custom_create_resource_loop() {
    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
    remove_action('genesis_loop', 'genesis_do_loop');

    add_action('genesis_before', 'curriki_create_resource_scripts');
    add_action('genesis_loop', 'curriki_create_resource_body', 15);
}

function curriki_create_resource_scripts() {
    global $wpdb;
    $current_user = wp_get_current_user();
    // Enqueue JQuery Tab scripts
    wp_enqueue_script('jquery-ui-tabs');

    wp_enqueue_style('resource-font-awesome-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('resource-legacy-css', get_stylesheet_directory_uri() . '/css/legacy.css');

    wp_enqueue_script('angular', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js', array('jquery'), false, true); // Not using imagesLoaded? :( Okay... then this.
    wp_enqueue_script('angular-sanitize', '//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.min.js', array('angular'), false, true);

    wp_enqueue_style('nprog-css', get_stylesheet_directory_uri() . '/css/nprogress.css', null, false, 'all'); // Add the styles first, in the <head> (last parameter false, true = bottom of page!)
    wp_enqueue_script('nprog-js', get_stylesheet_directory_uri() . '/js/nprogress.js', array('angular'), false, true); // Not using imagesLoaded? :( Okay... then this.
    //qtip Plugin Loaded
    wp_enqueue_style('jquery-qtip-css', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css?ver=4.2.2');
    wp_enqueue_script('jquery-qtip-js', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js?ver=4.2.2', 'jquery.qtip', '4.2.2');


    wp_enqueue_style('questions-css', get_stylesheet_directory_uri() . '/css/questions_tinymce.css');

    //bootstrap css
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/css/questions/bootstrap.css');
    //bootstrap js
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 'jquery', '2.1.5');

    //fancybox Plugin Loaded
    wp_enqueue_style('jquery-fancybox-css', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.css?v=2.1.5');
    
    wp_enqueue_script('jquery-fancybox-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.fancybox.pack.js?v=2.1.5', 'jquery.fancybox', '2.1.5');

    wp_enqueue_script('jquery-mousewheel-js', get_stylesheet_directory_uri() . '/js/fancybox_v2.1.5/jquery.mousewheel-3.0.6.pack.js', 'jquery.mousewheel', '3.0.6');

    //Angular and tinymce plugin Loaded
    wp_enqueue_script('tinymce', get_stylesheet_directory_uri() . '/js/tinymce_4.3.2_jquery/tinymce.min.js', array('ng-ctrlr'), false, true);

    //Angular and Custom JS Script Loaded
    wp_register_script('ng-ctrlr', get_stylesheet_directory_uri() . '/js/angular_controllers.js?v=1');
    $translation_array = cur_angular_controllers_translations();
    wp_localize_script('ng-ctrlr', 'ml_obj', $translation_array);
    wp_enqueue_script("ng-ctrlr");

    $tinymce_lang = "en";
    if (defined('ICL_LANGUAGE_CODE'))
        $tinymce_lang = ICL_LANGUAGE_CODE;

    wp_register_script('page-create-resource', get_stylesheet_directory_uri() . '/js/page-create-resource.js?v=1', array('ng-ctrlr'));
    $ml_arr_page_create_resource = array(
        'description_ml' => __('Enter descriptions to help others discover your work.', 'curriki'),
        'education_level_ml' => __('Select only the education levels that apply to your resource to help others discover your work.', 'curriki'),
        'keywords_ml' => __('Enter comma separated keywords to help others discover your work.'),
        'resource_type_ml' => __('Select the type of resource you are sharing.'),
        'alignment_ml' => __('Select alignments for your resource.'),
        'privileges_ml' => __('Select Private to keep your material in "draft" until it is ready to be released into the repository for general use.'),
        'license_ml' => __('Please be sure you have read and understand the Terms of Service and that you have the rights to contribute this content.'),
        'language_ml' => __('Select language for the content you added.'),
        'settings_ml' => __('Additional Information about your resource.', 'curriki'),
        'tinymce_lang' => $tinymce_lang
    );
    wp_localize_script('page-create-resource', 'pcr_ml_obj', $ml_arr_page_create_resource);
    wp_enqueue_script('page-create-resource');
    ?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var baseurl = '<?php echo get_bloginfo('url'); ?>/';
        var trusted = '<?php echo $wpdb->get_var("select trusted from users where userid = '" . $current_user->ID . "'"); ?>';
    </script>
    <script>
        var external_tool = '';
        <?php
        if(get_current_user_id() > 0){
            $current_user = wp_get_current_user();
            if($current_user->user_login == "eprofessor"){
                ?>
                    external_tool = 'external_tool';
                <?php
            }
        }
            
        ?>
    </script>
    <style>
        .standards-alignment-box select{ width: 100%; max-width: 100%; font-size: 14px;}
        .standards-alignment-box option{ padding:2px;}
        .qtipCustomClass{border-color: #0E9236 !important;background-color: #99c736 !important;}
        .qtipCustomClass .qtip-content{font-size: 12px !important;color: #FFF !important;}
        .tooltip:hover{cursor: help !important;}    
        .forceZIndexQtip{
            z-index: 99999999999999 !important;
        }
        
    </style>
    <?php
    wp_enqueue_script('jquery-ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), false, true);
    wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', null, false, true);
    
}

function curriki_create_resource_body() {
    global $wpdb;
    $current_language = "eng";
    if (defined('ICL_LANGUAGE_CODE'))
        $current_language = cur_get_current_language(ICL_LANGUAGE_CODE);

    $current_user = wp_get_current_user();
    $resourcefiles = array();
    $resource_subjects = array();
    $resource_statements = array();
    $resource_subjectareas = array();
    $resource_educationlevels = array();
    $resource_instructiontypes = array();
    $_REQUEST['type'] = (isset($_REQUEST['type']) && $_REQUEST['type'] == 'collection') ? $_REQUEST['type'] : 'resource';

    if (isset($_REQUEST['resourceid'])) {
//        $resource = $wpdb->get_row("select * from resources  where resourceid = " . $_REQUEST['resourceid'], ARRAY_A);    //prepared statement added
        $resource = $wpdb->get_row( $wpdb->prepare(      
                "
                    select * from resources  where resourceid = %d
                ", 
                $_REQUEST['resourceid']
        ) , ARRAY_A );
        
        //prepared statement added
        /*
        $can_edit = $wpdb->get_row('SELECT r.resourceid
            FROM resources r
            where (r.contributorid = "' . $resource['contributorid'] . '"
              or exists (select r2.resourceid
                from resources r2
                inner join group_resources gr on gr.resourceid = r2.resourceid
                inner join cur_bp_groups_members gm on gm.group_id = gr.groupid
                inner join cur_bp_groups_members gm2 on gm2.group_id = gm.group_id
                where gm.user_id = "' . $resource['contributorid'] . '"
                and r2.contributorid = gm2.user_id
              )
            )
            and r.resourceid = "' . $_REQUEST['resourceid'] . '"', ARRAY_A);
        */
        
        $can_edit = $wpdb->get_row( $wpdb->prepare(      
                "
                    SELECT r.resourceid
            FROM resources r
            where (r.contributorid = %d
              or exists (select r2.resourceid
                from resources r2
                inner join group_resources gr on gr.resourceid = r2.resourceid
                inner join cur_bp_groups_members gm on gm.group_id = gr.groupid
                inner join cur_bp_groups_members gm2 on gm2.group_id = gm.group_id
                where gm.user_id = %d
                and r2.contributorid = gm2.user_id
              )
            )
            and r.resourceid = %d
                ", 
                $resource['contributorid'], $resource['contributorid'], $_REQUEST['resourceid']
        ) , ARRAY_A );
        
        if (!$resource)
            $msg = 'Error: Resource Not Found.';
        elseif (!$current_user->caps['administrator'] AND ! $can_edit['resourceid']) {
            $msg = 'Error: You cannot edit this resource, only the owner or Group member can perform edits.';
            unset($_REQUEST['resourceid']);
            unset($resource);
        } else {
            $_REQUEST['type'] = $resource['type'];
//            $resourcefiles = $wpdb->get_results('select * from resourcefiles where resourceid = ' . $_REQUEST['resourceid'], ARRAY_A);    //prepared statement added
            $resourcefiles = $wpdb->get_results( $wpdb->prepare(      
                    "
                        select * from resourcefiles where resourceid = %d
                    ", 
                    $_REQUEST['resourceid']
            ) , ARRAY_A );
            
            //prepared statement added
            /*
            $resource_statements = $wpdb->get_results('select st.statementid as aligntagid,std.title, st.notation
        FROM resource_statements as rst 
        JOIN statements as st on rst.statementid = st.statementid
        JOIN standards as std on std.standardid = st.standardid
        where rst.resourceid = ' . $_REQUEST['resourceid'], ARRAY_A);
             * 
             */
            $resource_statements = $wpdb->get_results( $wpdb->prepare(      
                    "
                        select st.statementid as aligntagid,std.title, st.notation
                        FROM resource_statements as rst 
                        JOIN statements as st on rst.statementid = st.statementid
                        JOIN standards as std on std.standardid = st.standardid
                        where rst.resourceid = %d
                    ", 
                    $_REQUEST['resourceid']
            ) , ARRAY_A );

//            $result = $wpdb->get_results('select s.subjectareaid,s.subjectid from resource_subjectareas rs join subjectareas s on s.subjectareaid = rs.subjectareaid and rs.resourceid = ' . $_REQUEST['resourceid'], ARRAY_A);   //prepared statement added
            
            $result = $wpdb->get_results( $wpdb->prepare(      
                    "
                        select s.subjectareaid,s.subjectid from resource_subjectareas rs join subjectareas s on s.subjectareaid = rs.subjectareaid and rs.resourceid = %d
                    ", 
                    $_REQUEST['resourceid']
            ) , ARRAY_A );
            
            
            if (isset($result) and count($result) > 0)
                foreach ($result as $r) {
                    $resource_subjectareas[$r['subjectareaid']] = $r['subjectareaid'];
                    $resource_subjects[$r['subjectid']] = $r['subjectid'];
                }

            $result = $wpdb->get_results('select * from resource_educationlevels where resourceid = ' . $_REQUEST['resourceid'], ARRAY_A);
            if (isset($result) and count($result) > 0)
                foreach ($result as $r)
                    $resource_educationlevels[] = $r['educationlevelid'];

            $result = $wpdb->get_results('select * from resource_instructiontypes where resourceid = ' . $_REQUEST['resourceid'], ARRAY_A);
            if (isset($result) and count($result) > 0)
                foreach ($result as $r)
                    $resource_instructiontypes[] = $r['instructiontypeid'];
        }
    }

    $licenses = $wpdb->get_results("select * from `licenses` where active = 'T' order by displayname", ARRAY_A);
    $language = $wpdb->get_results("select * from `languages` where active = 'T' order by displayname", ARRAY_A);
    
    
    $resource_thumb = $wpdb->get_row( $wpdb->prepare(      
                "
                    select * from resource_thumbs  where resourceid = %d
                ", 
                $_REQUEST['resourceid']
        ) , ARRAY_A );

    $q_instructiontypes = cur_instructiontypes_query($current_language);
    $instructiontypes = $wpdb->get_results($q_instructiontypes, ARRAY_A);

    $education_levels = array(
        array('title' => __('Preschool (Ages 0-4)', 'curriki'), 'levels' => '8|9', 'arlevels' => array(8, 9)),
        array('title' => __('Kindergarten-Grade 2 (Ages 5-7) ', 'curriki'), 'levels' => '3|4', 'arlevels' => array(3, 4)),
        array('title' => __('Grades 3-5 (Ages 8-10)', 'curriki'), 'levels' => '5|6|7', 'arlevels' => array(5, 6, 7)),
        array('title' => __('Grades 6-8 (Ages 11-13)', 'curriki'), 'levels' => '11|12|13', 'arlevels' => array(11, 12, 13)),
        array('title' => __('Grades 9-10 (Ages 14-16)', 'curriki'), 'levels' => '15|16', 'arlevels' => array(15, 16)),
        array('title' => __('Grades 11-12 (Ages 16-18)', 'curriki'), 'levels' => '17|18', 'arlevels' => array(17, 18)),
        array('title' => __('College & Beyond', 'curriki'), 'levels' => '23|24|25', 'arlevels' => array(23, 24, 25)),
        array('title' => __('Professional Development', 'curriki'), 'levels' => '19|20', 'arlevels' => array(19, 20)),
        array('title' => __('Special Education', 'curriki'), 'levels' => '26|21', 'arlevels' => array(26, 21)),
    );

    $standards = $wpdb->get_results("select standardid,title from `standards` where standardid in (99, 100 ,444)", ARRAY_A);
    //$educationlevels = $wpdb->get_results("select levelid,displayname from educationlevels where active = 'T' and displayseqno is not null order by displayseqno asc", ARRAY_A);
    $q_educationlevels = cur_educationlevels_query($current_language);
    $educationlevels = $wpdb->get_results($q_educationlevels, ARRAY_A);

    $q_subjects = cur_subjects_query($current_language);
    $subjects = $wpdb->get_results($q_subjects, ARRAY_A);

    $q_subjectareas = cur_subjectareas_query($current_language, null);
    $subjectareas = $wpdb->get_results($q_subjectareas, ARRAY_A);
    ?>
    <div id="resource-tabs" class="container_12" ng-app="ngApp" ng-controller="createResourceCtrl" ng-init="baseurl = '<?php echo get_bloginfo('url'); ?>/';
                        ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';">

        <span style="display:none;">
            <div id="submit-resource-popup" >
                <div class = "submit-card grid_8 card center" style="width: 600px; text-align: center;">
                    <h4><?php echo __('What would you like to do next?', 'curriki'); ?></h4>
                    <div class = "my-library-actions grid_10" style="margin:0 auto">
                        <?php if (!empty($_SERVER['HTTP_REFERER'])) { ?>
                            <button class = "resource-button small-button grey-button" onclick="window.location.href = '<?php echo $_SERVER['HTTP_REFERER']; ?>'" style="width: 220px;"><?php echo __('Back to what I was doing', 'curriki'); ?></button>
                        <?php } ?>
                        <button class = "resource-button small-button green-button" ng-click="viewResource()" style="width: 220px;"><?php echo __('View ' . $_REQUEST['type'], 'curriki'); ?></button>
                        <?php
                        $type = isset($_REQUEST['type']) ? $_REQUEST['type']: 'Resource';
                        $type_get = ($type == 'collection') ? '?type=collection': '';
                        ?>
                        <a href="<?php echo get_site_url(); ?>/create-resource<?php echo $type_get; ?>" class = "resource-button small-button red-button" style="width: 220px;color: #FFFFFF;text-transform: capitalize;border-radius: 8px;"><?php echo __('Submit Another '. $type , 'curriki'); ?></a>
                    </div>
                </div>
            </div>
            <a id="fancyBoxInline" href="#submit-resource-popup"></a>
        </span>

        <form action="" method="" id="create_resource_form" >
            <input type="hidden" name="preview_resource_id" value="0" />
            <input type="hidden" name="action" value="create_resource" />
            <input type="hidden" name="groupid" value="<?php if (isset($_GET['groupid'])) echo $_GET['groupid'] ?>" />
            <input type="hidden" name="prid" value="<?php if (isset($_GET['prid'])) echo $_GET['prid'] ?>" />
            <input type="hidden" name="mediatype" value="<?php echo (isset($resource['mediatype'])) ? $resource['mediatype'] : 'text'; ?>" id="frmmediatype" />
            <input type="hidden" name="resource_type" value="<?php echo strtolower($_REQUEST['type']); ?>" />
            <input type="hidden" name="resourceid" id="resourceid"  value="{{resourceid}}" ng-model="resourceid" ng-init="resourceid = '<?php echo (!isset($_REQUEST['copy']) && isset($resource['resourceid'])) ? $resource['resourceid'] : ''; ?>'"/>

            <?php
            if (isset($resourcefiles) and count($resourcefiles) > 0)
                foreach ($resourcefiles as $file)
                    echo '<input name="resourcefiles[]" value="' . htmlspecialchars(json_encode($file)) . '" type="hidden">';

            if (!empty($msg))
                echo '<span style="color: red;background-color: #FFDDDD;padding: 5px 15px;border: solid 2px red;margin-bottom: 20px;border-radius: 5px;" class="grid_12">' . $msg . '</span>';
            ?>

            <div class="create-resource-tabs page-tabs grid_12 clearfix">
                <ul>
                    <li class="rounded-borders-left"><a href="#create">1. <?php echo __('Create', 'curriki'); ?></a></li>
                    <li ><a href="#describe">2. <?php echo __('Describe', 'curriki'); ?></a></li>
                    <li ><a href="#access">3. <?php echo __('Access', 'curriki'); ?></a></li>
                    <li class="rounded-borders-right" ng-click="createResource()" ><a href="#access">4. <?php echo __('Submit', 'curriki'); ?></a></li>
                </ul>
            </div>

            <div class="create-resource-content resource-content clearfix"><div class="wrap grid_12">
                    <!-- Create -->
                    <div id="create" class="tab-contents">
                        <h3 class="section-header">
                            <?php
                            $cedit = 'Create or Upload';
                            if (isset($resource['resourceid']))
                                $cedit = 'Edit';
                            if (isset($_REQUEST['copy']))
                                $cedit = 'Duplicate';


                            if ($_REQUEST['type'] == 'collection') {
                                if (isset($_GET['prid']))
                                    $cedit .= ' a Folder for this Collection';
                                else
                                    $cedit .= ' a Collection';
                            } else {
                                if (isset($_GET['prid']))
                                    $cedit .= ' a Resource to this Collection';
                                else
                                    $cedit .= ' a Resource';
                            }
                            echo __($cedit, 'curriki');
                            ?>
                        </h3>
                        <?php
                        if (isset($_GET['prid']) && $_REQUEST['type'] == 'collection') {
                            echo '<p class = "desc">' . __('You can add new content to this folder when you are finished.', 'curriki') . '</p>';
                        }
                        $_REQUEST['type'] = ucwords($_REQUEST['type']);
                        ?>
                        <div class = "create-edit-section">
                            <!--Resource Title -->
                            <div class = "resource-content-section" ng-non-bindable><h4><?php echo __('Title', 'curriki'); ?></h4>
                                <p class = "desc">
                                    <?php
                                    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en') {
                                        echo __('What details will best describe and present this in search results and other listings?', 'curriki');
                                    } else {
                                        ?>        
                                        What details will best describe and present this <?php if (isset($_REQUEST['type'])) echo $_REQUEST['type']; ?> in search results and other listings?
                                        <?php
                                    }
                                    ?>                                    
                                </p>
                                <?php
                                $placeholder_title = "Enter " . ( isset($_REQUEST['type']) ? $_REQUEST['type'] : "" ) . " Title";
                                ?>
                                <input type = "text" class = "resource-title" id = "resource-title"  style="max-width: 100%" name = "title" autofocus placeholder = "<?php echo __($placeholder_title, 'curriki'); ?>" value="<?php if (isset($resource['title'])) echo $resource['title']; ?>" />
                                <!--Resource Description -->
                                <h4><?php echo __('Abstract', 'curriki'); ?></h4><div class = "tooltip fa fa-question-circle" id = "resource-description"></div>
                                <p>
                                    <?php
                                    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en') {
                                        echo __('If you copied this, update this field to indicate how it is different from the original.', 'curriki');
                                    } else {
                                        ?>        
                                        If you copied this <?php if (isset($_REQUEST['type'])) echo $_REQUEST['type']; ?>, update this field to indicate how it is different from the original.
                                        <?php
                                    }
                                    ?>                                     
                                </p>
                                
                                <textarea name = "description" id="description" ><?php if (isset($resource)) echo $resource['description']; ?></textarea>
                                <?php
                                    // if(get_current_user_id() > 0){
                                    //     $current_user = wp_get_current_user();
                                    //     if($current_user->user_login == "eprofessor"){
                                            ?>
                                                <h4><?php echo __('Upload Thumbnail (optional)', 'curriki'); ?></h4>
                                                <div>
                                                    Upload a thumbnail image. The image must be 300 x 169 pixels, and in the JPG, PNG or GIF format. 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="file" name="resource_thumb" id="resource_thumb" class="form-control"  />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <img src="<?php echo get_stylesheet_directory_uri();?>/images/loader2.gif" id="file-loader-icon" style="display:none; width: 30px;height:30px" />
                                                    </div>
                                                </div>


                                                </div>
                                                <div id="resource_thumb_img">
                                                    <?php 
                                                    if($resource_thumb){ ?>
                                                        <img src='<?php echo $resource_thumb['thumb_image']; ?>' style='max-width:150px;max-height:150px;' />
                                                        <input type='hidden' name='resource_thumb_hidden' value='<?php echo $resource_thumb['thumb_image']; ?>' />
                                                    <?php } ?>
                                                </div>
                                            <?php
                                    //     }
                                    // }

                                ?>
                                
                                <h4><?php echo __('Contents', 'curriki'); ?></h4>
                                <p><?php echo __('Enter your lesson, student material, etc. in the editor below.', 'curriki'); ?></p>
                                <textarea id = "elm1" name = "content" ><?php if (isset($resource['content'])) echo $resource['content']; ?></textarea>
                            </div>
                        </div>
                        <div class = "create-edit-steps">
                            <button class = "resource-button small-button green-button next-step" onclick = "change_tab('describe');" style="width: 210px;"><?php echo __('Next Step', 'curriki'); ?>: <strong><?php echo __('Describe', 'curriki'); ?> ></strong></button>
                            <button class = "resource-but   ton small-button grey-button cancel" onclick = "go_to_dashboard()"><?php echo __('Cancel', 'curriki'); ?></button>
                            <button class="resource-button small-button green-button preview" ng-click="previewResource()"><?php echo __('Preview', 'curriki'); ?></button>
                        </div>
                    </div>

                    <!--Describe -->
                    <div id = "describe" class = "tab-contents">
                        <h3 class = "section-header">                            
                            <?php
                            $hd_type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : "";
                            $heading = "Describe Your " . $hd_type;
                            echo __($heading, "curriki");
                            ?>
                        </h3>
                        <div class = "create-edit-section">
                            <!--Resource Subject & Education Level -->
                            <div class = "grid_12 alpha omega">
                                <div class = "grid_9 alpha">

                                    <div class = "optionset">
                                        <div class = "optionset-title"><?php echo __("Subject", "curriki"); ?> </div>

                                        <ul>
                                            <?php
                                            if (isset($subjects) and count($subjects) > 0)
                                                foreach ($subjects as $sub) {
                                                    echo '<li ng-mouseover="subject_hover(' . $sub['subjectid'] . ')" style="max-width:206px;"><label><input name="subject[]" type="checkbox" value="' . $sub['subject'] . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this,\'subjectarea_' . $sub['subjectid'] . '\')" ' . (in_array($sub['subjectid'], $resource_subjects) ? 'checked="checked"' : '') . '>' . $sub['displayname'] . '</label></li>';
                                                }
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="optionset two-col grey-border" style="max-width: 63%;min-width: 63%;min-height: 320px; float: right">
                                        <div class="optionset-title"><?php echo __('Subject Areas', 'curriki'); ?></div>
                                        <ul>
                                            <?php
                                            if (isset($subjectareas) and count($subjectareas) > 0)
                                                foreach ($subjectareas as $sub) {
                                                    echo '<li ng-show="is_subject_hover(' . $sub['subjectid'] . ')"><label><input name="subjectarea[]" type="checkbox" value="' . $sub['subjectareaid'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')"' . (in_array($sub['subjectareaid'], $resource_subjectareas) ? 'checked="checked"' : '') . '>' . $sub['displayname'] . '</label></li>';
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="grid_3 omega">
                                    <div class="resource-content-section"><h4><?php echo __('Education Level', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-education-level"></div>

                                        <ul>
                                            <?php
                                            if (isset($education_levels) and count($education_levels) > 0)
                                                foreach ($education_levels as $l) {
                                                    echo '<li><label><input type="checkbox" id="resource-education-level" value="' . $l['levels'] . '"'
                                                    . (count(array_intersect($resource_educationlevels, $l['arlevels'])) ? 'checked="checked"' : '')
                                                    . ' name="education_levels[]" />' . $l['title'] . '</label></li>';
                                                }
                                            ?>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <!-- Keywords -->
                            <div class="resource-content-section"><h4><?php echo __('Keywords', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-keywords"></div>
                                <input type="text" id="resource-keywords" name="keywords" value="<?php if (isset($resource['keywords'])) echo $resource['keywords']; ?>" />
                            </div>
                            <!-- Type -->
                            <div class="resource-content-section"><h4><?php echo __('Type', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-type"></div>

                                <ul class="three-col">
                                    <?php
                                    if (isset($instructiontypes) and count($instructiontypes) > 0)
                                        foreach ($instructiontypes as $t) {
                                            echo '<li><label><input type="checkbox" id="resource-type" value="' . $t['instructiontypeid'] . '" '
                                            . (in_array($t['instructiontypeid'], $resource_instructiontypes) ? 'checked="checked"' : '')
                                            . 'name="instructiontypes[]" />' . $t['displayname'] . '</label></li>';
                                        }
                                    ?>
                                </ul>

                            </div>
                            <!-- Align to Standards -->
                            <div class="resource-content-section" 
                                 ng-init="standardBoxs = <?php echo str_replace('"', "'", json_encode($resource_statements)); ?>"
                                 ><h4><?php echo __('Align to Standards', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-standards"></div>

                                <div class="clearfix"></div>

                                <div class="grid_6">
                                    <div class="standards-alignment-box rounded-borders-full">
                                        <h4><?php echo __('Add a Standard', 'curriki'); ?></h4>
                                        <select name="standardid" ng-model="standardid" ng-change="populateStatements()">
                                            <option ng-selected="true"><?php echo __('Select a Standard', 'curriki'); ?></option>
                                            <?php
                                            if (isset($standards) and count($standards) > 0)
                                                foreach ($standards as $st) {
                                                    echo '<option value="' . $st['standardid'] . '">' . $st['title'] . '</option>';
                                                }
                                            ?>
                                        </select>

                                        <select name="levelid" ng-model="levelid" ng-change="populateStatements()">
                                            <option ng-selected="true"><?php echo __('Select a Grade Level', 'curriki'); ?></option>
                                            <?php
                                            if (isset($education_levels) and count($education_levels) > 0)
                                                foreach ($educationlevels as $el) {
                                                    echo '<option value="' . $el['levelid'] . '">' . $el['displayname'] . '</option>';
                                                }
                                            ?>
                                        </select>

                                        <select name="statementid" ng-model="statementid" ng-change="populateAlignTag()" >
                                            <option ng-repeat="row in statements" value="{{row.statementid}}">{{row.description}}</option>
                                        </select>
                                        <select name="aligntagid" ng-model="aligntagid" ng-change="populateAlignBox()" >
                                            <option ng-repeat="row in aligntags" value="{{row.statementid}}">{{row.description}}</option>
                                        </select>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="standards-alignment-box rounded-borders-full" ng-show="standardBox.title">
                                        <h4>{{standardBox.title}}</h4>
                                        <p><strong>Parent:</strong> {{standardBox.parent}}</p>
                                        <p><strong>Notation:</strong> {{standardBox.notation}}</p>
                                        <p><strong>Standard:</strong> {{standardBox.description}}</p>
                                        <div class="standards-alignment-actions"><button class="small-button green-button save" ng-click="addStandard()">Add Standard</button><button class="small-button grey-button cancel" ng-click="clearStandard()">Cancel</button></div>
                                    </div>
                                </div>

                                <div class="grid_6">
                                    <h4><?php echo __('Your Alignments', 'curriki'); ?></h4>
                                    <div class="standards-alignment-box rounded-borders-full" ng-repeat="standard in  standardBoxs track by $index">
                                        <input type="hidden" name="statements[]" value="{{standard.aligntagid}}" />
                                        <h4>{{standard.title}}</h4>
                                        <!--p><strong>Parent:</strong> {{standard.parent}}</p-->
                                        <p><strong>Notation:</strong> {{standard.notation}}</p>
                                        <!--p><strong>Standard:</strong> {{standard.description}}</p-->
                                        <div class="standards-alignment-actions"><button class="small-button grey-button cancel" ng-click="removeStandard($index)">Remove</button></div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                            </div>
                        </div>
                        <div class="create-edit-steps">
                            <button class="resource-button small-button green-button next-step" onclick="change_tab('access');"><?php echo __('Next Step', 'curriki'); ?>: <strong><?php echo __('Access', 'curriki'); ?> ></strong></button>
                            <button class="resource-button small-button green-button submit" ng-click="createResource()"><strong><?php echo __('Submit Now', 'curriki'); ?></strong></button>
                            <button class="resource-button small-button grey-button cancel" onclick="go_to_dashboard()"><?php echo __('Cancel', 'curriki'); ?></button>
                            <button class="resource-button small-button green-button preview" ng-click="previewResource()"><?php echo __('Preview', 'curriki'); ?></button>
                        </div>
                    </div>

                    <!-- Access -->
                    <div id="access" class="tab-contents">
                        <h3 class="section-header"><?php echo __('Access Settings', 'curriki'); ?></h3>
                        <div class="create-edit-section">

                            <p><?php echo __('Review and update the fields below as needed.', 'curriki'); ?></p>

                            <div class="resource-content-section">
                                <h4><?php echo __('Display Settings', 'curriki'); ?></h4>
                                <div class="tooltip fa fa-question-circle" id="resource-display-settings"></div>
                                <ul>
                                    <li><label><input type="checkbox" id="resource-studentfacing" name="studentfacing" value="T" <?php echo (isset($resource) and $resource['studentfacing'] == 'T') ? 'checked="checked"' : ''; ?>/> <?php echo __('Select if the material can be directly used by a student.', 'curriki'); ?></label></li>
                                    <?php if ($current_user->caps['administrator']) { ?>
                                        <li><label><input type="checkbox" id="resource-active" name="active" value="T" <?php echo (isset($resource) and $resource['active'] == 'T') ? 'checked="checked"' : ''; ?> <?php echo (!isset($resource)) ? 'checked="checked"' : ''; ?>  /> <?php echo __('Active', 'curriki'); ?></label></li>
                                        <li><label><input type="checkbox" id="resource-topofsearch" name="topofsearch" value="T" <?php echo (isset($resource) and $resource['topofsearch'] == 'T') ? 'checked="checked"' : ''; ?>/> <?php echo __('Top of Search', 'curriki'); ?></label></li>
                                        <li><label><input type="checkbox" id="resource-partner" name="partner" value="T" <?php echo (isset($resource) and $resource['partner'] == 'T') ? 'checked="checked"' : ''; ?>/> <?php echo __('Partner', 'curriki'); ?></label></li>
                                    <?php } ?>
                                </ul>
                                <p class="desc"><?php echo __('Checked will taken as true and unchecked will be taken as false.', 'curriki'); ?></p>
                            </div>


                            <!-- Access Privileges -->
                            <div class="resource-content-section"><h4><?php echo __('Access Privileges', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-privileges"></div>
                                <ul>
                                    <li><label><input type="radio" id="resource-privileges" name="access" value="public" <?php echo (isset($resource) and ! in_array($resource['access'], array('protected', 'private'))) ? 'checked="checked"' : ''; ?> checked="checked" /> <?php echo __('Public: Available to anyone', 'curriki'); ?></label></li>
                    <!--                  <li><label><input type="radio" id="resource-privileges" name="access" value="protected" <?php echo (isset($resource) and $resource['access'] == 'protected') ? 'checked="checked"' : ''; ?>/> Protected: Available to anyone but only you (or your group) can edit this copy</label></li>-->
                                    <li><label><input type="radio" id="resource-privileges" name="access" value="private" <?php echo (isset($resource) and $resource['access'] == 'private') ? 'checked="checked"' : ''; ?>/> <?php echo __('Private: Only you (or your group) can view or edit', 'curriki'); ?></label></li>
                                </ul>
                                <?php
                                if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en') {
                                    echo __('Select Private to keep your material in "draft" until it is ready to be released into the repository for general use.', 'curriki');
                                } else {
                                    ?>        
                                    <p class="desc">Select <strong>Private</strong> to keep your material in "draft" until it is ready to be released into the repository for general use.</p>
                                    <?php
                                }
                                ?>                                
                            </div>

                            <!-- License -->
                            <div class="resource-content-section"><h4><?php echo __('License', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-license"></div>
                                <p class="desc">
                                    <?php echo __('Please be sure you have read and understand the Terms of Service and that you have the rights to contribute this content. The default Curriki license is "Creative Commons Attribution Non-Commercial". To learn about all the open licenses', 'curriki'); ?>, 
                                    <?php
                                    if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en') {
                                        ?>
                                        <a href='https://creativecommons.org/licenses/'><?php echo __('please click here', 'curriki'); ?></a>.
                                        <?php
                                    } else {
                                        ?>        
                                        please <a href='https://creativecommons.org/licenses/'>click here</a>.
                                        <?php
                                    }
                                    ?>                                

                                </p>


                                <select class="whide-select" name="licenseid" style="max-width: 400px;width: 400px;">
                                    <?php
                                    if (isset($licenses) and count($licenses) > 0)
                                        foreach ($licenses as $l) {
                                            echo '<option value="' . $l['licenseid'] . '" '
                                            . ((isset($resource) && $resource['licenseid'] == $l['licenseid']) ? 'selected' : ($l['licenseid'] == '1' ? 'selected' : ''))
                                            . '>' . $l['displayname'] . '</option>';
                                        }
                                    ?>
                                </select>
                                <label><input type="checkbox" id="resource-privileges" checked /><?php echo __('By leaving this box checked, you are granting a commercial license to Curriki to help support its mission as set forth in the Terms of Service.', 'curriki'); ?></label>
                            </div>
                            <!-- Language -->
                            <div class="resource-content-section"><h4><?php echo __('Language', 'curriki'); ?></h4><div class="tooltip fa fa-question-circle" id="resource-language"></div>
                                <select class="whide-select" name="language" style="max-width: 400px;width: 400px;">
                                    <?php
                                    if (isset($licenses) and count($licenses) > 0)
                                        foreach ($language as $l) {
                                            echo '<option value="' . $l['language'] . '" ' . ($l['language'] == 'eng' ? 'selected' : '') . '>' . $l['displayname'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <?php
                                $current_user = wp_get_current_user();                                 
                                if(strtolower($_REQUEST['type']) === 'collection' && $current_user->user_login === "eprofessor"){
                                    $userRegistrationCheck = "";
                                    $ex_mod = getExternalModule($_GET['resourceid']);
                                    if($ex_mod["enable_user_registration"] === 1){
                                        $userRegistrationCheck = 'checked="checked"';
                                    }
                                    $curriki_learn_link = site_url("/about-curriki-learn")                                                                        
                            ?>
                                <div class="resource-content-section">
                                    <h4>
                                        <label>                                           
                                            <input type="checkbox" id="enable-user-registration" name="enable-user-registration" value='1' <?php echo $userRegistrationCheck ?> />
                                            <?php echo __('PUBLISH THIS COLLECTION ON CURRIKILEARN (<a href="'.$curriki_learn_link.'" target="_blank">CLICK HERE TO LEARN MORE</a>)', 'curriki'); ?>
                                        </label>
                                    </h4>
                                </div>
                            <?php } ?>
                        </div>
                        <div class = "create-edit-steps">
                            <button class = "resource-button small-button green-button submit" ng-click="createResource()"><strong><?php echo __('Submit Now', 'curriki'); ?></strong></button>
                            <button class = "resource-button small-button grey-button cancel" onclick="go_to_dashboard()"><?php echo __('Cancel', 'curriki'); ?></button>
                            <button class="resource-button small-button green-button preview" ng-click="previewResource()"><?php echo __('Preview', 'curriki'); ?></button>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="modal fade " id="recaptcha-msg" tabindex="-1" role="dialog" aria-labelledby="recaptcha-msg-label" aria-hidden="true" style="width:333px;overflow: visible;margin-top: 30px;height:150px;margin-left:auto;margin-right:auto;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: center;">
                        <h4><strong><?php echo __('Thank You', 'curriki'); ?></strong></h4>
                        <p><?php echo __('A member of our Team will review your submission shortly and let you know as soon as we make it available on Curriki.', 'curriki'); ?></p>
                        <h3><?php echo __('SECURITY CHECK', 'curriki'); ?></h3>
                        <div id="dialog" class="g-recaptcha" data-sitekey="<?php echo GOOGLE_RECAPTCHA_SITE_KEY; ?>" data-callback="createRes"></div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
        </form>
    </div>

    <!-- Question Modal -->

    <div class="modal fade" id="QuestionModal" tabindex="-1" role="dialog" aria-labelledby="QuestionModalLabel"  data-backdrop="static" data-keyboard="false" style="width:70%;margin:0 auto;margin-top: 70px;margin-bottom: 70px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 20px;right: 20px;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Insert Question</h4>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div id="tabs" class="questions-tabs">
                            <ul>
                                <li><a href="#mcqs">MCQ</a></li>
                                <li><a href="#true-false">True / False</a></li>
                            </ul>
                            <div id="mcqs">
                                <form  id="mcq_question_form" class="mcq_question_form">
                                    <div class="mcqs_wrapper">
                                        <div class="row" id="question_statement_wrapper">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="question_statement">Question:</label>
                                                    <textarea id = "question_statement" class="question_model" name = "question_statement" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5" id="answer_1_wrapper">
                                                <div class="form-group">
                                                    <label for="answer_1">Answer 1:</label>
                                                    <textarea id = "answer_1" class="question_model" name = "answer_1" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5" id="answer_selection_1_wrapper">
                                                <div class="form-group">
                                                    <label for="answer_selection_1">Response 1:</label>
                                                    <textarea id = "answer_selection_1" class="question_model" name = "answer_selection_1" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="correct_answer_wrapper">
                                                <div class="form-group">
                                                    <input type="radio" name="correct_answer" id="answer_model_correct_answer_1" value="1">
                                                    <label for="answer_model_correct_answer_1">Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5" id="answer_2_wrapper">
                                                <div class="form-group">
                                                    <label for="answer_2">Answer 2:</label>
                                                    <textarea id = "answer_2" class="question_model" name = "answer_2" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5" id="answer_selection_2_wrapper">
                                                <div class="form-group">
                                                    <label for="answer_selection_2">Response 2:</label>
                                                    <textarea id = "answer_selection_2" class="question_model" name = "answer_selection_2" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="correct_answer" id="answer_model_correct_answer_2" value="2">
                                                    <label for="answer_model_correct_answer_2">Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_3">Answer 3:</label>
                                                    <textarea id = "answer_3" class="question_model" name = "answer_3" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_selection_3">Response 3:</label>
                                                    <textarea id = "answer_selection_3" class="question_model" name = "answer_selection_3" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="correct_answer" id="answer_model_correct_answer_3" value="3">
                                                    <label for="answer_model_correct_answer_3">Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_4">Answer 4:</label>
                                                    <textarea id = "answer_4" class="question_model" name = "answer_4" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_selection_4">Response 4:</label>
                                                    <textarea id = "answer_selection_4" class="question_model" name = "answer_selection_4" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="correct_answer" id="answer_model_correct_answer_4" value="4">
                                                    <label for="answer_model_correct_answer_4">Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_5">Answer 5:</label>
                                                    <textarea id = "answer_5" class="question_model" name = "answer_5" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_selection_5">Response 5:</label>
                                                    <textarea id = "answer_selection_5" class="question_model" name = "answer_selection_5" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="correct_answer" id="answer_model_correct_answer_5" value="5">
                                                    <label for="answer_model_correct_answer_5">Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="submit" name="submit" class="btn btn-primary" value="Save changes" />
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="question_type" value="mcq" />
                                </form>
                            </div>
                            <div id="true-false">
                                <form  id="truefalse_question_form" class="truefalse_question_form">
                                    <div class="true_false_wrapper">
                                        <div class="row" id="truefalse_question_statement_wrapper">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="truefalse_question_statement">Question:</label>
                                                    <textarea id = "truefalse_question_statement" class="question_model" name = "truefalse_question_statement" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="truefalse_answer_selection_1_wrapper">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="truefalse_answer_1">Answer 1:</label>
                                                    <strong>True</strong>
                                                    <input type="hidden" name="truefalse_answer_1" value="True" />
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="truefalse_answer_selection_1">Response 1:</label>
                                                    <textarea id = "truefalse_answer_selection_1" class="question_model" name = "truefalse_answer_selection_1" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="truefalse_correct_answer_wrapper">
                                                <div class="form-group">
                                                    <label>
                                                    <input type="radio" name="truefalse_correct_answer" value="1">
                                                    Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="truefalse_answer_selection_2_wrapper">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="answer_2">Answer 2:</label>
                                                    <strong>False</strong>
                                                    <!--<textarea id = "answer_2" class="question_model" name = "answer_2" ></textarea>-->
                                                    <input type="hidden" name="truefalse_answer_2" value="False" />
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="truefalse_answer_selection_2">Response 2:</label>
                                                    <textarea id = "truefalse_answer_selection_2" class="question_model" name = "truefalse_answer_selection_2" ></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>
                                                    <input type="radio" name="truefalse_correct_answer" value="2">
                                                    Correct</label>
                                                    <span class="question-tooltip tooltip fa fa-question-circle" data-hasqtip="9">&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="submit" name="submit" class="btn btn-primary" value="Save changes" />
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="truefalse_question_type" value="true_false" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(function () {
                jQuery(".questions-tabs").tabs();
            });
            jQuery('.question-tooltip').qtip({
                content: {
                    text: "If an incorrect answer is selected the text you enter as 'Response to selecting this answer.' will display followed by 'Your answer was incorrect. Please try again.\n<br /><br />\
        If a correct answer is selected the text you enter as 'Response to selecting this answer.' will display followed by 'Congratulations. You selected the correct answer.'"
                },
                style: {classes: 'qtipCustomClass forceZIndexQtip'}
            });
        });
    </script>
    <?php
}

genesis();
