<?php 

global $search;

$current_language = "eng";
if( defined('ICL_LANGUAGE_CODE') )
    $current_language = cur_get_current_language(ICL_LANGUAGE_CODE); 

$language = "";
if($current_language !== "eng")
{
    $language = $current_language;
}
    
?>
<div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn <?php echo (!isset($search->request['type']) || $search->request['type'] == 'Resource') ? '' : 'collapsed'; ?>" data-toggle="collapse" data-target="#accordion-one" aria-expanded="false" aria-controls="accordion-one"><?php echo __('SEARCH LIBRARY','curriki'); ?>: <i class="icon"></i></span>
    </div>
    <div id="accordion-one" class="collapse <?php echo (!isset($search->request['type']) || $search->request['type'] == 'Resource') ? 'in' : ''; ?>">
        <div class="panel-body">
            <?php
                // if ($search->branding != 'search' && $search->branding != 'studentsearch' && $search->branding != 'students')
                //     get_template_part('modules/search/views/search_title_widget');
            ?>
                <div class="form-group">
                    <input class="form-control" type="text" name="phrase" placeholder="<?php echo __('Enter Keyword','curriki'); ?>" value="<?php if (isset($search->request['phrase'])) echo stripslashes(htmlspecialchars($search->request['phrase'])); ?>">
                </div>
                <div class="form-group">
                    <label>
                        <input id="studentfacing" type="checkbox" name="studentfacing" value="<?php echo isset($_REQUEST['studentfacing']) ? $_REQUEST['studentfacing'] : 'T'; ?>" <?php echo isset($_REQUEST['studentfacing']) && $_REQUEST['studentfacing'] == 'T' ? 'checked="checked"' : ''; ?>/>
                        Student Facing
                    </label>
                </div>
                <!-- div class="form-group">
                    <select class="form-control">
                        <option value="Select Subject">Select Subject</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control">
                        <option value="Select Grade Range">Select Grade Range</option>
                    </select>
                </div -->
                <div class="form-group">
                    <!-- Hidden Fields -->

                    <input type="hidden" name="type" id="type" value="<?php echo isset($search->request['type']) ? stripslashes(htmlspecialchars($search->request['type'])) : 'Resource'; ?>"/>
                    <input type="hidden" name="start" id="start" value="<?php echo isset($search->request['start']) ? stripslashes(htmlspecialchars($search->request['start'])) : "0"; ?>"/>
                    <input type="hidden" name="partnerid" id="partnerid" value="<?php echo $search->partnerid ? stripslashes(htmlspecialchars($search->partnerid)) : '1'; ?>" />
                    <input type="hidden" name="searchall" id="searchall" value="<?php echo isset($search->request['searchall']) ? stripslashes(htmlspecialchars($search->request['searchall'])) : ""; ?>"/>
                    <input type="hidden" name="viewer" id="viewer" value="<?php echo isset($search->request['viewer']) ? stripslashes(htmlspecialchars($search->request['viewer'])) : ""; ?>"/>
                    <?php
                    if (isset($search->request['search_target']) AND $search->request['search_target'] == 'curriki')
                        $search->branding = 'curriki';
                    ?>
                    <input type="hidden" name="branding" id="branding" value="<?php echo stripslashes(htmlspecialchars($search->branding)); ?>"/>

                    <input id="sortfield" type="hidden" name="sort" value="<?php echo isset($search->request['sort']) ? stripslashes(htmlspecialchars($search->request['sort'])) : 'rank1 desc'; ?>"/>
                    <input id="approvalstatusfield" type="hidden" name="approvalstatus" value="<?php echo isset($_REQUEST['approvalstatus']) ? $_REQUEST['approvalstatus'] : ''; ?>"/>
                    <input id="resourcetypefield" type="hidden" name="resourcetype" value="<?php echo isset($search->request['resourcetype']) ? $search->request['resourcetype'] : ''; ?>"/>
                    <!-- /Hidden Fields -->

                    <button class="btn btn-search" type="submit"><i class="fa fa-search"></i> <?php echo __('SEARCH','curriki'); ?></button>
                </div>
        </div>
    </div>
</div>

<h4 class="text-blue-alt">Refine your search</h4>
<?php get_template_part('modules/search/views/search_advance_options_slide'); ?>

<!-- div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn collapsed" data-toggle="collapse" data-target="#accordion-three" aria-expanded="false" aria-controls="accordion-three">MEMBERS (624137) <i class="icon"></i></span>
    </div>
    <div id="accordion-three" class="collapse">
        <div class="panel-body">
            <p>Members Content</p>
        </div>
    </div>
</div -->
<div class="panel-buttons">
    <a class="btn" href="<?php echo stripslashes(htmlspecialchars($search->newSearchURL)); ?>" target="_top"><i class="fa fa-search"></i> <?php echo __('New Search','curriki'); ?></a>
    <div class="dropdown search-tip-dropdown">
        <button class="btn btn-tip" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-info-circle"></i> <?php echo __('Search Tips','curriki'); ?></button>
        <div class="dropdown-menu search-tip">
            <button type="button" class="close">x</button>
            <h4>Search Tips & Advance Features</h4>
            <p class="text-bold">For clearer results, try some of
                these easy techniques in the search entry box.</p>
            <ul>
                <li><i class="fa fa-angle-right"></i> By default,
                    when AND,OR,NOT or comma or + / - / ? / * are
                    not included in the search text we search for
                    the complete phrase entered.</li>
                <li><i class="fa fa-angle-right"></i> You can use
                    double quotes to search for an exact phrase, as
                    in "The Sun Also Rises" or "George Washington"
                    and combine this with other operators.</li>
                <li><i class="fa fa-angle-right"></i> A comma also
                    acts as an OR. 'George, Washington' will return
                    resources that contain either 'George' or
                    'Washington'.</li>
                <li><i class="fa fa-angle-right"></i> You can use an
                    AND if searching for both words. George AND
                    Washington will return resources that contain
                    BOTH 'George' and 'Washington'. You must use all
                    caps as in 'AND'.</li>
                <li><i class="fa fa-angle-right"></i> Not sure of
                    the exact word? Crop it to a shorter form and
                    use an asterisk (*). For example, Read* will
                    return Reads, Reader, Reading, etc.</li>
                <li><i class="fa fa-angle-right"></i> Not sure of
                    the spelling? Use a question mark (?) to replace
                    a single letter. For example, Read? will return
                    Reads, Ready, etc. You can even use multiple
                    questions marks. For example, P??r will return
                    Pour, Poor, Pear, Peer, etc.</li>
                <li><i class="fa fa-angle-right"></i> Not happy with
                    crossover results? Reduce the number of returns
                    by using a minus symbol (-). For example,
                    "George Washington" -Carver will remove any
                    returns with the word Carver.</li>
                <li><i class="fa fa-angle-right"></i> Use the word
                    "OR" to broaden a search. For example, England
                    OR Britain will find resources with either one,
                    but not necessarily both.</li>
                <li><i class="fa fa-angle-right"></i> Or, you can
                    combine any of the above.</li>
            </ul>
        </div>
    </div>
</div>
<hr>