<div style="display:none;">
  <div id="oerdialog" title="Resource Rating">
    <style>
      .ui-dialog {z-index: 5;}
      .ui-dialog .ui-icon-closethick{display:none !important;}
      .ui-dialog .ui-button-text{height: 16px;width: 16px;}
      .comment-popup {max-width: 500px !important;}
    </style>
    <div class="evaluation-tool authenticated">
      <h2 class="modal-title">Your rating has been saved.</h2>
      <br/>
      <div class="center">
        <div class="my-library-actions">
          <a class="button-cancel rounded-borders-full" href = '<?php echo get_bloginfo('url') . '/oer/' . $_REQUEST['pageurl']; ?>'>Go to Resource</a>
          <a class="button-save rounded-borders-full" href = '<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=curriki_res_review' >Review another resource</a>
        </div>
      </div>

    </div>
  </div>

  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/layouts.css" type="text/css" charset="utf-8" />
  <link rel="stylesheet" href="<?php echo plugins_url(); ?>/curriki_manage/assets/reset.css" type="text/css" media="all" charset="utf-8" />
  <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    var baseurl = '<?php echo get_bloginfo('url'); ?>/';

    var open_review_dialog = function () {
      jQuery("#oerdialog").dialog({
        modal: true,
        width: 630,
        height: 150
      });
    }

    jQuery(open_review_dialog);
  </script>

</div>