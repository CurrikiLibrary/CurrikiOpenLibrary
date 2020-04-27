<?php global $search; ?>
<div class="sort-div">
    <div class="container_12 wrap">
        <div class="row row-no-gutters">
            <div class="grid_5">
                <div class="show-result">
                    <?php
                        if (isset($search->status['found']) && $search->status['found'] > 0) {
                    ?>
                            <?php echo __('Showing','curriki'); ?> <?php echo number_format($search->status['found'], 0); ?> <?php echo __('results for','curriki'); ?>
                    <?php
                        } else {
                    ?>
                            <?php echo __("No Result Found for ","curriki"); ?>
                    <?php
                        }
                    ?>
                    <span class="text-blue">"<?php echo trim(stripslashes(htmlspecialchars($search->request['phrase'])),'"'); ?>"</span>
                    <?php
                        if (!empty($search->request['suggestedPhrase']) && $search->request['suggestedPhrase'] != $search->request['phrase']) {
                            echo '<a href="' . $search->request['suggestedPhraseURL'] . '" target="_top"> Did you mean to search: ' . stripslashes(htmlspecialchars($search->request['suggestedPhrase'])) . ' ?</a>';
                        }
                    ?>
                </div>
            </div>
            <?php if (isset($search->status['found']) && $search->status['found'] > 0) { ?>
                <div class="grid_7">
                    <div class="sort-result-right">
                        <?php if(isset($search->request['type']) && $search->request['type'] == 'Resource') { ?>
                            <?php // if (isset($search->current_user->caps['administrator'])) { ?>
                                <div class="sort-label">
                                    <?php echo __('View:','curriki'); ?>:
                                    <div class="listing-view-action">
                                        <span class="compact-view <?php echo (isset($search->request['compact']) && urldecode($search->request['compact']) == 'true') ? 'active' : ''; ?>"><i class="fa fa-th-list"></i></span>
                                        <span class="list-view <?php echo (!isset($search->request['compact']) || urldecode($search->request['compact']) == 'false') ? 'active' : ''; ?>"><i class="fa fa-th"></i></span>
                                    </div>
                                </div>
                            <?php // } ?>
                        <?php } ?>
                        <?php if (isset($search->current_user->caps['administrator'])) { ?>
                            <?php if(isset($search->request['type']) && $search->request['type'] == 'Resource') { ?>
                                <div class="sort-label"><?php echo __('Status:','curriki'); ?>:
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo isset($_REQUEST['approvalstatus']) && !empty($_REQUEST['approvalstatus']) ? ucfirst($_REQUEST['approvalstatus']) :'All'; ?>
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#" onclick="setResourceField('approvalstatusfield', '')">All</a>
                                            <a class="dropdown-item" href="#" onclick="setResourceField('approvalstatusfield', 'approved')">Approved</a>
                                            <a class="dropdown-item" href="#" onclick="setResourceField('approvalstatusfield', 'rejected')">Rejected</a>
                                            <a class="dropdown-item" href="#" onclick="setResourceField('approvalstatusfield', 'pending')">Pending</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <div class="sort-label"><?php echo __('Sort by:','curriki'); ?>:
                            <div class="dropdown">
                                <span class="dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php
                                        if ($search->request['sort']) {
                                            switch (urldecode($search->request['sort'])) {
                                                case "rank1 desc":
                                                    echo __('All Records','curriki');
                                                    break;
                                                case "title asc":
                                                    echo __('Title [A-Z]','curriki');
                                                    break;
                                                case "title desc":
                                                    echo __('Title [Z-A]','curriki');
                                                    break;
                                                case "createdate desc":
                                                    echo __('Newest first','curriki');
                                                    break;
                                                case "createdate asc":
                                                    echo __('Oldest first','curriki');
                                                    break;
                                                case "memberrating desc,title asc":
                                                    echo __('Member rating','curriki');
                                                    break;
                                                case "reviewrating desc,title asc":
                                                    echo __('Curriki rating','curriki');
                                                    break;
                                                case "aligned desc,title asc":
                                                    echo __('Standards aligned','curriki');
                                                    break;
                                            }
                                        }
                                    ?>
                                    <i class="fa fa-angle-down"></i>
                                </span>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'rank1 desc')"><?php echo __('All Records','curriki'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'title asc')"><?php echo __('Title [A-Z]','curriki'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'title desc')"><?php echo __('Title [Z-A]','curriki'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'createdate desc')"><?php echo __('Newest first','curriki'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'createdate asc')"><?php echo __('Oldest first','curriki'); ?></a>
                                    <?php if ($search->request['type'] == 'Resource') { ?>
                                        <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'memberrating desc,title asc')"><?php echo __('Member rating','curriki'); ?></a>
                                        <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'reviewrating desc,title asc')"><?php echo __('Curriki rating','curriki'); ?></a>
                                        <a class="dropdown-item" href="#" onclick="setResourceField('sortfield', 'aligned desc,title asc')"><?php echo __('Standards aligned','curriki'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($search->request['type']) && $search->request['type'] == 'Resource') { ?>
                            <?php // if (isset($search->current_user->caps['administrator'])) { ?>
                                <div class="sort-label"><?php echo __('Type:','curriki'); ?>:
                                    <div class="dropdown">
                                        <span class="dropdown-toggle" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo isset($search->request['resourcetype']) && !empty($search->request['resourcetype']) ? ucfirst($search->request['resourcetype']) :'All'; ?>
                                            <i class="fa fa-angle-down"></i>
                                        </span>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                            <a class="dropdown-item" href="#" onclick="setResourceField('resourcetypefield', '')">All</a>
                                            <a class="dropdown-item" href="#" onclick="setResourceField('resourcetypefield', 'resource')">Resource</a>
                                            <a class="dropdown-item" href="#" onclick="setResourceField('resourcetypefield', 'collection')">Collection</a>
                                        </div>
                                    </div>
                                </div>
                            <?php // } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
