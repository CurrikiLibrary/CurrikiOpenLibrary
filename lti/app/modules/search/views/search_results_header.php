<?php global $search; ?>
<div class="search-results-showing grid_12 clearfix" id="search_results_pointer">

    <div class="search-term grid_8 alpha">
        <h4><?php echo __('Showing results for','curriki'); ?> "<?php echo trim(stripslashes(htmlspecialchars($search->request['phrase'])),'"'); ?>"</h4>
        <?php
        if (!empty($search->request['suggestedPhrase']) && $search->request['suggestedPhrase'] != $search->request['phrase']) {
            echo '<a href="' . $search->request['suggestedPhraseURL'] . '" target="_top"> Did you mean to search: ' . stripslashes(htmlspecialchars($search->request['suggestedPhrase'])) . ' ?</a><br/>';
        }
        ?>
        <span> <?php echo isset($search->status['found']) && $search->status['found'] > 0 ? number_format($search->status['found'], 0) . " ".__("Results Found","curriki") : __("No Result Found","curriki"); ?></span>
    </div>
    <?php if (isset($search->status['found']) && $search->status['found'] > 0) { ?>
        <div class="search-dropdown grid_4 omega sort-dropdown">
            <strong><?php echo __('Sort by:','curriki'); ?> </strong>
            <select name="sort" id="sort">
                <option value="rank1 desc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'rank1 desc') ? 'selected="selected"' : ''; ?>><?php echo __('All Records','curriki'); ?></option>
                <option value="title asc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'title asc') ? 'selected="selected"' : ''; ?>><?php echo __('Title [A-Z]','curriki'); ?></option>
                <option value="title desc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'title desc') ? 'selected="selected"' : ''; ?>><?php echo __('Title [Z-A]','curriki'); ?></option>
                <option value="createdate desc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'createdate desc') ? 'selected="selected"' : ''; ?>><?php echo __('Newest first','curriki'); ?></option>
                <option value="createdate asc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'createdate asc') ? 'selected="selected"' : ''; ?>><?php echo __('Oldest first','curriki'); ?></option>
                <?php if ($search->request['type'] == 'Resource') { ?>
                    <option value="memberrating desc,title asc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'memberrating desc,title asc') ? 'selected="selected"' : ''; ?>><?php echo __('Member rating','curriki'); ?></option>
                    <option value="reviewrating desc,title asc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'reviewrating desc,title asc') ? 'selected="selected"' : ''; ?>><?php echo __('Curriki rating','curriki'); ?></option>
                    <option value="aligned desc,title asc" <?php echo (isset($search->request['sort']) && urldecode($search->request['sort']) == 'aligned desc,title asc') ? 'selected="selected"' : ''; ?>><?php echo __('Standards aligned','curriki'); ?></option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>

</div>