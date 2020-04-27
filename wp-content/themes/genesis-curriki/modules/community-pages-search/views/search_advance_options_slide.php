<?php global $search; ?>
<div class="search-slide advanced-slide"  style="display: none;">
    <div class="optionset subject-optionset">
        <div class="optionset-title" ><?php echo __('Subject','curriki'); ?></div>
        <ul class="subjects">
            <?php
            foreach ($search->subjects as $sub)
                echo '<li onmouseover="showHoverSubjects(\'subjectarea_' . $sub['subjectid'] . '\',this)" subjectid="' . $sub['subjectid'] . '">'
                . '<label style="display:block"><input name="subject[' . $sub['subjectid'] . ']" type="checkbox" value="' . $sub['subject'] . '" id="subject_' . $sub['subjectid'] . '"  onclick="uncheck_subject_areas(this, \'subjectarea_' . $sub['subjectid'] . '\')" ' . (in_array($sub['subject'], $search->request['subject']) ? 'checked' : '') . '>' . $sub['displayname'] . '</label>'
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
            foreach ($search->subjectareas as $sub)
                echo '<li class="subjectarea subjectarea_' . $sub['subjectid'] . '" subjectid="' . $sub['subjectid'] . '" >'
                . '<label><input name="subsubjectarea[' . $sub['subjectid'] . '-' . $sub['subjectareaid'] . ']" type="checkbox" value="' . $sub['subsubjectarea'] . '" class="subjectarea_' . $sub['subjectid'] . '" onclick="check_subject(this,\'subject_' . $sub['subjectid'] . '\')" ' . (in_array($sub['subsubjectarea'], $search->request['subsubjectarea']) ? 'checked' : '') . '>' . $sub['displayname'] . '</label></li>';
            ?>
        </ul>
    </div>

    <div class="optionset">
        <div class="optionset-title"> <?php echo __('Education Level','curriki'); ?> </div>
        <ul><?php
            foreach ($search->educationlevels as $ind => $level)
                echo '<li><label><input name="educationlevel[' . $ind . ']" type="checkbox" value="' . $level['levelidentifiers'] . '" ' . (in_array($level['levelidentifiers'], $search->request['educationlevel']) ? 'checked' : '') . '>' . $level['title'] . '</label></li>';
            ?>
        </ul>
        <?php if (!isset($search->request['type']) || $search->request['type'] == 'Resource') { ?>
            <div class="optionset-title" ><?php echo __('Rating','curriki'); ?></div>
            <ul >
                <li><label><input name="partner" type="checkbox" value="T" <?php if (isset($search->request['partner'])) echo 'checked'; ?>><?php echo __('Partners','curriki'); ?></label></li>
                <li><label><input name="reviewrating" type="checkbox" value="[2.0 TO *]" <?php if (isset($search->request['reviewrating'])) echo 'checked'; ?>><?php echo __('Top Rated by Curriki','curriki'); ?></label></li>
                <li><label><input name="memberrating" type="checkbox" value="[4.0 TO *]" <?php if (isset($search->request['memberrating'])) echo 'checked'; ?>><?php echo __('Top Rated by Members','curriki'); ?></label></li>
            </ul>
        <?php } ?>
    </div>

    <div class="optionset">
        <div class="optionset-title"><?php echo __('Type','curriki'); ?></div>

        <ul><?php
            foreach ($search->instructiontypes as $type)
                echo '<li><label><input name="instructiontype[' . $type['instructiontypeid'] . ']" type="checkbox" value="' . $type['name'] . '" ' . (in_array($type['name'], $search->request['instructiontype']) ? 'checked' : '') . '>' . $type['displayname'] . '</label></li>';
            ?>
        </ul>
    </div>

    <div class="clearfix"></div>
</div>