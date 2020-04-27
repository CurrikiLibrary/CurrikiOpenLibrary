<?php
/*
 * Template Name: Search Widget Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Waqar Muneer
 * Url: https://www.curriki.org/
 */
add_action('genesis_meta', 'curriki_custom_search_resources_page_loop');

function curriki_custom_search_resources_page_loop() {

  add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

  remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
  remove_action('genesis_loop', 'genesis_do_loop');

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

  add_action('genesis_loop', 'curriki_search_resources_page_body', 15);
}

function curriki_search_resources_page_body() {
  global $wpdb;

  $subjects = $wpdb->get_results("SELECT * FROM subjects order by displayname", ARRAY_A);
  $subjectareas = $wpdb->get_results("SELECT * FROM subjectareas order by subjectid,displayname", ARRAY_A);
  $instructiontypes = $wpdb->get_results("SELECT instructiontypeid,name,displayname from instructiontypes order by displayname", ARRAY_A);
  $languages = $wpdb->get_results("select distinct l.language,l.displayname from resources r inner join languages l on r.language = l.language", ARRAY_A);

  $education_levels = array(
      array('title' => 'Preschool (Ages 0-4)', 'levels' => 'PreKto12|ElementarySchool|Pre-K|K'),
      array('title' => 'Kindergarten-Grade 2 (Ages 5-7) ', 'levels' => 'PreKto12|ElementarySchool|1|2'),
      array('title' => 'Grades 3-5 (Ages 8-10)', 'levels' => 'PreKto12|ElementarySchool|3|4|5'),
      array('title' => 'Grades 6-8 (Ages 11-13)', 'levels' => 'PreKto12|MiddleSchool|6|7|8'),
      array('title' => 'Grades 9-10 (Ages 14-16)', 'levels' => 'PreKto12|HighSchool|9|10'),
      array('title' => 'Grades 11-12 (Ages 16-18)', 'levels' => 'PreKto12|HighSchool|11|12'),
      array('title' => 'College & Beyond', 'levels' => 'HigherEducation|Graduate|Undergraduate-UpperDivision|Undergraduate-LowerDivision'),
      array('title' => 'Professional Development', 'levels' => 'ProfessionalEducation-Development|Vocational Training'),
      array('title' => 'Special Education', 'levels' => 'SpecialEducation|LifelongLearning'),
  );

// Add the styles first, in the <head> (last parameter false, true = bottom of page!)
  wp_enqueue_style('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.css', null, false, false);

// Not using imagesLoaded? :( Okay... then this.
  wp_enqueue_script('qtip', get_stylesheet_directory_uri() . '/js/qtip2_v2.2.1/jquery.qtip.min.js', array('jquery'), false, true);
  wp_enqueue_script('page-sr', get_stylesheet_directory_uri() . '/js/page-sr.js', array('jquery'), false, true);
  ?>

  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var baseurl = '<?php echo get_bloginfo('url'); ?>/';
  </script>

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
    .subjectarea-optionset{max-width: 38%;min-width: 38%;min-height: 365px;}
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
    @media screen and (max-width: 245px)  {
      .advance-options-text{display:none;}
    }
  </style>

  <div class="title-area ">
    <p class="site-title" itemprop="headline">
      <a href="http://cg.curriki.org/curriki/">Curriki</a>
    </p>
  </div>

  <div style="clear:both"></div>

  <div class="search-content" >
    <div class="wrap container_12" >
      <form action="<?php echo site_url() . "/search/"; ?>" method="POST" id="search_form" target="_blank">
        <input type="hidden" name="partnerid" id="search_type" value="<?php echo $_REQUEST['partnerid']; ?>" />
        <div class="search-bar grid_12">
          <div class="search-tabs">
            <div class="resource-tab tab rounded-borders-top selected" >
              <span class="tab-icon fa fa-book strong"></span>
              <span class="tab-text"><strong>Resources</strong></span>
            </div>

            <!--            <div class="search-tips search-tool-tip"><a>Search Tips</a></div>-->
            <div class="search-tips"><a href="javascript:void(0)" onclick="clearSearch()">New Search</a>
              <!--              &nbsp;&nbsp;|&nbsp;&nbsp;-->
            </div>

            <!--            <div class="search-tool-tip-text" style="display:none">
                          <Strong>For clearer results, try some of these easy techniques in the search entry box.</strong>
                          <ol style="list-style: disc">
                            <li>By default, searches are performed using the exact phrase entered: 'George Washington' will search for that phrase.</li>
                            <li>You can also use quotes to search for an exact phrase, as in "The Sun Also Rises" or "George Washington."</li>
                            <li>A comma acts as an OR.  'George, Washington' will return resources that contain either 'George' or 'Washington'.</li>
                            <li>Not sure of the exact word? Crop it to a shorter form and use an asterisk (*). For example, Read* will return Reads, Reader, Reading, etc.</li>
                            <li>Not sure of the spelling? Use a question mark (?) to replace a single letter. For example, Read? will return Reads, Ready, etc. You can even use multiple questions marks. For example, P??r will return Pour, Poor, Pear, Peer, etc.</li>
                            <li>Not happy with crossover results? Reduce the number of returns by using a minus symbol (-). For example, "George Washington" -Carver will remove any returns with the word Carver.</li>
                            <li>Use the word "Or" to broaden a search. For example, England or Britain will find resources with either one, but not necessarily both.</li>
                            <li>Or, you can combine any of the above. </li>
                          </ol>
                        </div>-->
          </div>

          <div class="search-input">
            <div class="search-field"><input class="rounded-borders-left" placeholder="Start Searching" type="text" name="q" id="query" ></div>
            <div class="search-button"><button type="submit" class="rounded-borders-right" ><span class="search-button-icon fa fa-search"></span><span class="search-text">Search</span></button></div>
          </div>

          <span id="resources-tab"  class="tab-container"> 
            <div class="search-options rounded-borders-bottom border-grey">

              <select name="slanguage" id="language" class="search-dropdown" style="margin-bottom: 0px; height: 25px !important">
                <option value="">Language</option>
                <?php
                foreach ($languages as $l) {
                  echo '<option value="' . $l['language'] . '">' . $l['displayname'] . '</option>';
                }
                ?>
              </select>

              <div class="show-hide-options close-button" onclick="advance('close')" style="display: none;" >Close <span class="show-hide-icon fa fa-times-circle-o" ></span></div>
              <div class="show-hide-options advance-search" onclick="advance('advanced')"><span class="show-hide-icon fa fa-plus-circle" ></span>More<span class="advance-options-text"> options</span></div>
              <div style="clear:both"></div>

              <div class="search-slide advanced-slide"  style="display: none;">
                <div class="optionset subject-optionset">
                  <ul ><li><label><input name="search_from_all" type="checkbox" value="">Search all of Curriki</label></li></ul>
                  <div class="optionset-title" >Subject</div>
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
                  <div class="optionset-title">Subject Area</div>
                  <ul class="subjectareas">
                    <?php
                    foreach ($subjectareas as $sub)
                      echo '<li class="subjectarea subjectarea_' . $sub['subjectid'] . '" subjectid="' . $sub['subjectid'] . '" >'
                      . '<label><input name="subjectarea[' . $sub['subjectareaid'] . ']" type="checkbox" value="' . $sub['subjectarea'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')">' . $sub['displayname'] . '</label></li>';
                    ?>
                  </ul>
                </div>

                <div class="optionset">
                  <div class="optionset-title"> Education Level </div>
                  <ul><?php
                  foreach ($education_levels as $ind => $el)
                    echo '<li><label><input name="education_level[' . $ind . ']" type="checkbox" value="' . $el['levels'] . '" ' . (in_array($el['levels'], $_REQUEST['education_level']) ? 'checked' : '') . '>' . $el['title'] . '</label></li>';
                    ?>
                  </ul>
                  <div class="optionset-title" >Rating</div>
                  <ul ><li><input name="partners" type="checkbox" value="">Partners</li><li><input name="reviewrating" type="checkbox" value="">Top Rated by Curriki</li><li><input name="memberrating" type="checkbox" value="">Top Rated by Members</li></ul>
                </div>

                <div class="optionset" s>
                  <div class="optionset-title">Type</div>

                  <ul><?php
                  foreach ($instructiontypes as $type)
                    echo '<li><label><input name="type[' . $sub['instructiontypeid'] . ']" type="checkbox" value="' . $type['name'] . '" ' . (in_array($type['name'], $_REQUEST['type']) ? 'checked' : '') . '>' . $type['displayname'] . '</label></li>';
                    ?>
                  </ul>
                </div>

                <div class="clearfix"></div>
              </div>

            </div>

          </span>
        </div>

      </form>
    </div>
  </div>
  <?php
}

add_action('genesis_after', 'curriki_library_scripts');
add_action('genesis_after', 'curriki_addthis_scripts');
genesis();
