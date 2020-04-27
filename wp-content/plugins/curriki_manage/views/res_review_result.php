<div style="display:none;">
  <div id="oerdialog" title="Resource Rating">
    <style>
      .ui-dialog {z-index: 5;}
      .ui-dialog .ui-icon-closethick{display:none !important;}
      .ui-dialog .ui-button-text{height: 16px;width: 16px;}
      .comment-popup {max-width: 500px !important;}
    </style>
    <?php
    $url = plugins_url() . '/curriki_manage/';
    $keys = array('0', '1', '2', '3', '-1');
    $rating = array('0', '1', '2', '3', '-1' => 'N/A');
    $classes = array('' => 'nr', '-1' => 's5', '0' => 's4', '1' => 's3', '2' => 's2', '3' => 's1');
    ?>
    <div class="evaluation-tool authenticated">
      <div class="evaluation-results" >
        <figure>
          <figcaption> Results </figcaption>
          <table>
            <thead>
              <tr>
                <th width="40%">Rubric</th>
                <th width="20%">Your Score</th>
                <th width="40%">Comments</th>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td class="first no-border" >Degree of Alignment</td>
                <td>
                  <span class="<?php echo $classes[$data->Standardsalignment]; ?>"><?php echo $rating[$data->Standardsalignment]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Standardsalignmentcomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Quality of Explanation of the Subject Matter</td>
                <td>
                  <span class="<?php echo $classes[$data->Subjectmatter]; ?>"><?php echo $rating[$data->Subjectmatter]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Subjectmattercomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Utility of Materials Designed to Support Teaching</td>
                <td>
                  <span class="<?php echo $classes[$data->Supportsteaching]; ?>"><?php echo $rating[$data->Supportsteaching]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Supportsteachingcomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Quality of Assessments</td>
                <td>
                  <span class="<?php echo $classes[$data->Assessmentsquality]; ?>"><?php echo $rating[$data->Assessmentsquality]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Assessmentsqualitycomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Quality of Technological Interactivity</td>
                <td>
                  <span class="<?php echo $classes[$data->Interactivityquality]; ?>"><?php echo $rating[$data->Interactivityquality]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Interactivityqualitycomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Quality of Instructional and Practice Exercises</td>
                <td>
                  <span class="<?php echo $classes[$data->Instructionalquality]; ?>"><?php echo $rating[$data->Instructionalquality]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Instructionalqualitycomment; ?>
                </td>
              </tr>
              <tr>
                <td class="first" >Opportunities for Deeper Learning</td>
                <td>
                  <span class="<?php echo $classes[$data->Deeperlearning]; ?>"><?php echo $rating[$data->Deeperlearning]; ?></span>
                </td>
                <td style="text-align: left">
                  <?php echo $data->Deeperlearningcomment; ?>
                </td>
              </tr>

              <?php
              $sum = 0;
              $count = 0;
              if ($data->Standardsalignment != null && $data->Standardsalignment >= 0) {
                $sum += $data->Standardsalignment;
                $count ++;
              }
              if ($data->Subjectmatter != null && $data->Subjectmatter >= 0) {
                $sum += $data->Subjectmatter;
                $count ++;
              }
              if ($data->Supportsteaching != null && $data->Supportsteaching >= 0) {
                $sum += $data->Supportsteaching;
                $count ++;
              }
              if ($data->Assessmentsquality != null && $data->Assessmentsquality >= 0) {
                $sum += $data->Assessmentsquality;
                $count ++;
              }
              if ($data->Interactivityquality != null && $data->Interactivityquality >= 0) {
                $sum += $data->Interactivityquality;
                $count ++;
              }
              if ($data->Instructionalquality != null && $data->Instructionalquality >= 0) {
                $sum += $data->Instructionalquality;
                $count ++;
              }
              if ($data->Deeperlearning != null && $data->Deeperlearning >= 0) {
                $sum += $data->Deeperlearning;
                $count ++;
              }

              $reviewRating = $count ? round($sum / $count, 2) : 0;
              ?>
              <tr>
                <td class="first" >Review Rating</td>
                <td>
                  <span class="<?php echo $classes[round($reviewRating)]; ?>"><?php echo $reviewRating; ?></span>
                </td>
                <td style="text-align: left">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </figure>
        <form id="finalize-form" method="post" class="formatted" action="?action=review_finalize"></form>
        <div class="footer">
          <a class="back btn btn-gray-plain" href="<?php echo site_url().'/oer/'.$_GET['pageurl']; ?>?action=review_resource"> &nbsp;&nbsp;Go Back &amp; Change Your Scores&nbsp;&nbsp;</a>
          <a class="finalize btn btn-orange" href="<?php echo site_url().'/oer/'.$_GET['pageurl']; ?>?action=review_finalize"> &nbsp;&nbsp;Finalize OER Review&nbsp;&nbsp; </a>
        </div>
      </div>

    </div>
  </div>

  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/layouts.css" type="text/css" charset="utf-8" />
  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/reset.css" type="text/css" media="all" charset="utf-8" />

  <?php
  wp_enqueue_script('modernizr', $url . "assets/modernizr.custom.js", array('jquery'), false, true);
  wp_enqueue_script('tmpl', $url . "assets/jquery.tmpl.js", array('qtip'), false, true);
  wp_enqueue_script('EvaluationTool', $url . "assets/oer-evaluation-tool.js", array('tmpl'), false, true);
  ?>

  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var baseurl = '<?php echo get_bloginfo('url'); ?>/';

    var open_review_dialog = function () {
      jQuery("#oerdialog").dialog({
        modal: true,
        width: 630,
        height: 650
      });
    }

    jQuery(open_review_dialog);

  </script>
</div>