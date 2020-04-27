<?php 
if(!function_exists("__") )
{
    function __($s)
    {
        return $s;
    }
}

global $search;
$current_language = "eng";
$language = "";    
?>
<div class="search-bar grid_12">
    
    
    <span class="search-widget">
        <div class="search-tabs">

            <!-- Search Tabs-->
            <a id="resource-tab" class="resource-tab tab rounded-borders-top selected" href="<?php echo $search->resourcesTabURL; ?>" target="_top"><span class="tab-icon fa fa-book strong"></span><span class="tab-text"><strong><?php echo __('Resources','curriki'); ?> </strong><span></span></span></a>
            <?php if ($search->partnerid == 1) { ?>
            <!--<a id="groups-tab" class="tab rounded-borders-top" href="#"><span class="tab-icon fa fa-users strong"></span><span class="tab-text"><strong><?php echo __('Groups','curriki'); ?></strong></span></a>
                <a id="members-tab" class="tab rounded-borders-top" href="#"><span class="tab-icon fa fa-user strong"></span><span class="tab-text"><strong><?php echo __('Members','curriki'); ?></strong></span></a>-->
            <?php } ?>
            <!-- /Search Tabs-->

            <!-- Search Tips-->

            <div class="search-tips search-tool-tip search-text"><a><?php echo __('Search Tips','curriki'); ?></a></div>
<!--                        
            <div class="search-tips">
                <a href="<?php echo $search->newSearchURL; ?>" target="_top"><?php echo __('New Search','curriki'); ?></a>
                <span class="search-text">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            </div>
 -->
            <div class="search-tool-tip-text" style="display:none">
                <Strong><?php echo __('For clearer results, try some of these easy techniques in the search entry box.','curriki'); ?></strong>
                <ol style="list-style: disc">
                    <li><?php echo __('By default, when AND,OR,NOT or comma or + / - / ? / * are not included in the search text we search for the complete phrase entered.','curriki'); ?></li>
                    <li><?php echo __('You can use double quotes to search for an exact phrase, as in "The Sun Also Rises" or "George Washington" and combine this with other operators.','curriki'); ?></li>
                    <li><?php echo __("A comma also acts as an OR. 'George, Washington' will return resources that contain either 'George' or 'Washington'.",'curriki'); ?></li>
                    <li><?php echo __("You can use an AND if searching for both words.  George AND Washington will return resources that contain BOTH 'George' and 'Washington'.  You must use all caps as in 'AND'.",'curriki'); ?></li>
                    <li><?php echo __("Not sure of the exact word? Crop it to a shorter form and use an asterisk (*). For example, Read* will return Reads, Reader, Reading, etc.",'curriki'); ?></li>
                    <li><?php echo __("Not sure of the spelling? Use a question mark (?) to replace a single letter. For example, Read? will return Reads, Ready, etc. You can even use multiple questions marks. For example, P??r will return Pour, Poor, Pear, Peer, etc.",'curriki'); ?></li>
                    <li><?php echo __('Not happy with crossover results? Reduce the number of returns by using a minus symbol (-). For example, "George Washington" -Carver will remove any returns with the word Carver.','curriki'); ?></li>
                    <li><?php echo __('Use the word "OR" to broaden a search. For example, England OR Britain will find resources with either one, but not necessarily both.','curriki'); ?></li>
                    <li><?php echo __('Or, you can combine any of the above.','curriki'); ?> </li>
                </ol>
            </div>
           
            <!-- /Search Tips-->
        </div>
        <form id="site-search-form" action="<?php echo $search->search_form_url?>" method="get" target="_blank">
        <input type="hidden" name="type" id="type" value="<?php echo 'Resource'; ?>"/>                 
        <!-- Search Input field-->
        <div class="search-input">
            <div class="search-field">
                <!--<input type="hidden" name="sort" value="topofsearch desc,partner desc,reviewrating desc,memberrating desc" />-->
                <input id="phrase-curriki" class="rounded-borders-left" placeholder="<?php echo __('Start Searching...','curriki'); ?>" type="text" name="phrase" value="<?php echo isset($_SESSION['custom_search_term']) && strlen($_SESSION['custom_search_term'])>0 ? $_SESSION['custom_search_term']:"" ?>" >
            </div>
            <div class="search-button"><button id="search-button-btn" type="submit" class="rounded-borders-right"><span class="search-button-icon fa fa-search"></span><span class="search-text"><?php echo __('Search','curriki'); ?></span></button></div>
        </div>
        
        <!-- /Search Input field-->

        <?php
        if(isset($_SESSION['isContentItem']) && $_SESSION['isContentItem']===false)
        {
        ?>
        <!-- Advance Options -->
        <span id="resources-tab" class="tab-container">
            <div class="search-options rounded-borders-bottom border-grey">

                <?php //if ( $_SESSION["context_title"] === "Computer" ) {                        
                    ?>
                    <select name="language" id="language" class="search-dropdown" style="margin-bottom: 0px; height: 25px !important">
                        <option value=""><?php echo __('Language','curriki'); ?></option>
                        <?php                  
                        foreach ($search->languages as $l) {
                            $l = (array)$l;
                            echo '<option value="' . $l['language'] . '">' . $l['displayname'] . '</option>';
                        }
                        ?>
                    </select>
                                

                        <div class="show-hide-options close-button" onclick="advance('close')" style="display: none;" ><?php echo __('Close','curriki'); ?> <span class="show-hide-icon fa fa-times-circle-o" ></span></div>
                        <?php //if (!isset($search->request['type']) || $search->request['type'] == 'Resource') { ?>
                                <!-- <div class="show-hide-options standards-search advance-options-text" onclick="advance('standard')" ng-hide="search_type != ''"><span class="show-hide-icon fa fa-plus-circle"></span><?php echo __('Search by Standard','curriki'); ?></div>-->
                        <?php //} ?>
                        <div class="show-hide-options advance-search" onclick="advance('advanced')"><span class="show-hide-icon fa fa-plus-circle" ></span><?php echo __('More','curriki'); ?><span class="advance-options-text"> <?php echo __('options','curriki'); ?></span></div>
                        <div style="clear:both"></div>
                        
                        <?php require_once 'search_advance_options_slide.php'; ?>
                        <?php require_once 'search_by_standards_slide.php'; ?>
                <?php //} ?>
                        
            </div>
        </span>
        <!-- /Advance Options -->
        <?php
        }
        ?>
        
        </form>
        
        <!-- Hidden Fields -->
        
        <input type="hidden" name="start" id="start" value="<?php echo isset($search->request['start']) ? $search->request['start'] : "0"; ?>"/>
        <input type="hidden" name="partnerid" id="partnerid" value="<?php echo $search->partnerid ? $search->partnerid : '1'; ?>" />
        <input type="hidden" name="searchall" id="searchall" value="<?php echo isset($search->request['searchall']) ? $search->request['searchall'] : ""; ?>"/>
        <input type="hidden" name="viewer" id="viewer" value="<?php echo isset($search->request['viewer']) ? $search->request['viewer'] : ""; ?>"/>        
        <?php if(!isset($search->request['sort'])): ?>
            <input type="hidden" name="sort" value="<?php echo isset($search->request['sort']) ? $search->request['sort'] : 'rank1 desc'; ?>"/>
        <?php endif; ?>
        <!-- /Hidden Fields -->

    </span>
</div>