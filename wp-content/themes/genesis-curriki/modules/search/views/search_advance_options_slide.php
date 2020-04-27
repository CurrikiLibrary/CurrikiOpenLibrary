<?php global $search; ?>
<div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn <?php echo (($search->request['subject']) ? '' : 'collapsed'); ?>" data-toggle="collapse" data-target="#accordion-subject" aria-expanded="false" aria-controls="accordion-subject"><?php echo __('Subject','curriki'); ?> <i class="icon"></i></span>
    </div>
    <div id="accordion-subject" class="collapse <?php echo (($search->request['subject']) ? 'in' : ''); ?>">
        <div class="panel-body">
            <?php
                foreach ($search->subjects as $sub) {
            ?>
                <div class="custom-control custom-checkbox">
                    <input 
                        class="custom-control-input <?php echo (in_array($sub['subject'], $search->request['subject']) ? '' : 'collapsed'); ?>" 
                        type="checkbox" 
                        name="subject[<?php echo $sub['subjectid']; ?>]" 
                        value="<?php echo $sub['subject']; ?>" 
                        id="subject_<?php echo $sub['subjectid']; ?>" 
                        <?php echo (in_array($sub['subject'], $search->request['subject']) ? 'checked' : ''); ?>
                        data-toggle="collapse"
                        data-target="#accordion-subject-<?php echo $sub['subjectid']; ?>"
                        aria-expanded="true"
                        aria-controls="accordion-subject-<?php echo $sub['subjectid']; ?>"
                    >
                    <label class="custom-control-label" for="subject_<?php echo $sub['subjectid']; ?>">
                        <?php echo $sub['displayname']; ?> 
                        <i class="fa fa-angle-down"></i>
                    </label>

                    <div id="accordion-subject-<?php echo $sub['subjectid']; ?>" class="collapse <?php echo (in_array($sub['subject'], $search->request['subject']) ? 'in' : ''); ?>">
                        <div class="panel-body">
                        <?php
                            foreach ($search->subjectareas as $subarea) {
                                if ($sub['subjectid'] == $subarea['subjectid']) {
                        ?>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" name="subsubjectarea[<?php echo $subarea['subjectid'] . '-' . $subarea['subjectareaid']; ?>]" value="<?php echo $subarea['subsubjectarea']; ?>" id="subsubjectarea_<?php echo $subarea['subjectid'] . '_' . $subarea['subjectareaid']; ?>" <?php echo (in_array($subarea['subsubjectarea'], $search->request['subsubjectarea']) ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="subsubjectarea_<?php echo $subarea['subjectid'] . '_' . $subarea['subjectareaid']; ?>"><?php echo $subarea['displayname']; ?></label>
                                    </div>
                        <?php
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
<div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn <?php echo (($search->request['educationlevel']) ? '' : 'collapsed'); ?>" data-toggle="collapse" data-target="#accordion-education-level" aria-expanded="false" aria-controls="accordion-education-level"><?php echo __('Education Level','curriki'); ?> <i class="icon"></i></span>
    </div>
    <div id="accordion-education-level" class="collapse <?php echo (($search->request['educationlevel']) ? 'in' : ''); ?>">
        <div class="panel-body">
            <?php
                foreach ($search->educationlevels as $ind => $level) {
            ?>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="educationlevel[<?php echo $ind; ?>]" value="<?php echo $level['levelidentifiers']; ?>" id="educationlevel_<?php echo $ind; ?>" <?php echo (in_array($level['levelidentifiers'], $search->request['educationlevel']) ? 'checked' : ''); ?>>
                    <label class="custom-control-label" for="educationlevel_<?php echo $ind; ?>"><?php echo $level['title']; ?></label>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
<?php if (!isset($search->request['type']) || $search->request['type'] == 'Resource') { ?>
<div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn <?php echo ((($search->request['partner']) || ($search->request['reviewrating']) || ($search->request['memberrating'])) ? '' : 'collapsed'); ?>" data-toggle="collapse" data-target="#accordion-rating" aria-expanded="false" aria-controls="accordion-rating"><?php echo __('Rating','curriki'); ?> <i class="icon"></i></span>
    </div>
    <div id="accordion-rating" class="collapse <?php echo ((($search->request['partner']) || ($search->request['reviewrating']) || ($search->request['memberrating'])) ? 'in' : ''); ?>">
        <div class="panel-body">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="partner" value="T" id="rating_partner" <?php if (isset($search->request['partner'])) echo 'checked'; ?>>
                <label class="custom-control-label" for="rating_partner"><?php echo __('Partners','curriki'); ?></label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="reviewrating" value="[2.0 TO *]" id="rating_reviewrating" <?php if (isset($search->request['reviewrating'])) echo 'checked'; ?>>
                <label class="custom-control-label" for="rating_reviewrating"><?php echo __('Top Rated by Curriki','curriki'); ?></label>
            </div>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="memberrating" value="[4.0 TO *]" id="rating_memberrating" <?php if (isset($search->request['memberrating'])) echo 'checked'; ?>>
                <label class="custom-control-label" for="rating_memberrating"><?php echo __('Top Rated by Members','curriki'); ?></label>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="panel-item">
    <div class="panel-header">
        <span class="panel-btn <?php echo (($search->request['instructiontype']) ? '' : 'collapsed'); ?>" data-toggle="collapse" data-target="#accordion-instructiontype" aria-expanded="false" aria-controls="accordion-instructiontype"><?php echo __('Type','curriki'); ?> <i class="icon"></i></span>
    </div>
    <div id="accordion-instructiontype" class="collapse <?php echo (($search->request['instructiontype']) ? 'in' : ''); ?>">
        <div class="panel-body">
            <?php
                foreach ($search->instructiontypes as $type) {
            ?>
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="instructiontype[<?php echo $type['instructiontypeid']; ?>]" value="<?php echo $type['name']; ?>" id="instructiontype_<?php echo $type['instructiontypeid']; ?>" <?php echo (in_array($type['name'], $search->request['instructiontype']) ? 'checked' : ''); ?>>
                    <label class="custom-control-label" for="instructiontype_<?php echo $type['instructiontypeid']; ?>"><?php echo $type['displayname']; ?></label>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>