<?php if (is_array($data)) { ?>
  <div class="wrap">
    <div id="icon-users" class="icon32"><br></div>
    <h2>Uploaded Files Status</h2>

    <form method="post" id="user-filter">
      <table class="wp-list-table widefat fixed striped users">
        <thead><tr>
            <th scope="col" id="user_login" class="column-primary"><span>Unique Name </span></th>
            <th scope="col" id="user_email" ><span>File Name</span></th>
            <th scope="col" id="user_name" ><span>S3 URL</span></th>
            <th scope="col" id="active" ><span>Details</span></th>
            <th scope="col" id="registerdate" ><span>Status</span></th>
            <th scope="col" id="inactivedate" ><span>Preview</span></th>
          </tr></thead>

        <tfoot><tr>
            <th scope="col" id="user_login" class="column-primary"><span>Unique Name </span></th>
            <th scope="col" id="user_email" ><span>File Name</span></th>
            <th scope="col" id="user_name" ><span>S3 URL</span></th>
            <th scope="col" id="active" ><span>Details</span></th>
            <th scope="col" id="registerdate" ><span>Status</span></th>
            <th scope="col" id="inactivedate" ><span>Preview</span></th>
          </tr></tfoot>

        <tbody id="the-list" data-wp-lists="list:files">
          <?php foreach ($data as $name => $file) { ?>
            <tr>
              <td class="column-primary"> <?php echo $file['uniquename']; ?> <span style="color:silver">(file-id:<?php echo $file['fileid']; ?>)</span></td>
              <td ><?php echo $file['filename']; ?></td>
              <td ><?php echo $file['url']; ?></td>
              <td >
                <?php
                echo
                '<strong>File Type:</strong> ' . $file['type'] . '<br/>' .
                '<strong>S3 Bucket:</strong> ' . $file['bucket'] . '<br/>' .
                '<strong>S3 Folder:</strong> ' . $file['folder'] . '<br/>' .
                '<strong>Extenssion:</strong> ' . $file['ext'] . '<br/>' ;
                ?>
              </td>
              <td ><?php echo $file['status'] ? '<span style="color:green">Successful</span>' : '<span style="color:green">' . $file['error'] . '</span>'; ?></td>
              <td style="width:220px;min-width:220px;max-width:220px;" ><?php echo $file['html']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
  </div>
<?php } ?> 



<div id="col-left" class="postbox" style="margin-top:20px;width: 60%; min-width: 500px;">
  <div class="col-wrap">
    <div class="form-wrap">
      <h2>Upload Resource Files</h2>

      <form id="addtag" method="post" action="<?php echo 'admin.php?test=true&page=' . $_REQUEST['page'] . '&time=' . time(); ?>" class="validate" enctype="multipart/form-data">
        <input type="hidden" name="action" value="uploaded">

        <div class="form-field form-required term-name-wrap">
          <label for="resourceid">Resource ID (Optional)</label>
          <input name="resourceid" id="resourceid" type="text" value="" size="400" aria-required="true" style="width: 400px;">
          <p>Resource id against which you want to insert the selected files below.</p>
        </div>

        <div class="form-field form-required term-name-wrap">
          <label for="resourcefiles">Resource Files</label>
          <input name="resourcefiles[]" id="resourcefiles" type="file" aria-required="true" multiple="multiple" >
          <p>You can select multiple files.</p>
        </div>

        <p class="submit">
          <input type="submit" name="submit" id="submit" class="button button-primary" value="Upload">
          <span class="spinner"></span>
        </p>

      </form>
    </div>

  </div>
</div>