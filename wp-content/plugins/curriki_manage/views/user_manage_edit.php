<div id="col-left" class="postbox" style="margin-top:20px;width: 60%; min-width: 500px;">
  <div class="col-wrap">
    <div class="form-wrap">
      <h2>Edit User</h2>
      
      <form id="addtag" method="post" action="<?php echo 'admin.php?page=' . $_REQUEST['page'] . '&time=' . time(); ?>" class="validate">
        <input type="hidden" name="action" value="edited">
        <input type="hidden" name="userid" value="<?php echo $_REQUEST['userid']; ?>">

        <div class="form-field form-required term-name-wrap">
          <label for="email">Email Address</label>
          <input name="email" id="email" type="text" value="<?php echo $data[0]->user_email; ?>" size="400" aria-required="true">
          <p>Email of this user what appears in the application.</p>
        </div>
        
        <div class="form-field form-required term-name-wrap">
          <label for="firstname">First Name</label>
          <input name="firstname" id="firstname" type="text" value="<?php echo $data[0]->firstname; ?>" size="200" aria-required="true">
          <p>First name of this user what appears in the application.</p>
        </div>
        
        <div class="form-field form-required term-name-wrap">
          <label for="lastname">Last Name</label>
          <input name="lastname" id="lastname" type="text" value="<?php echo $data[0]->lastname; ?>" size="200" >
          <p>Last name of this user what appears in the application.</p>
        </div>
        
        <div class="form-field form-required term-name-wrap">
          <label for="active"><input type="checkbox" value="T" <?php if ($data[0]->active == 'T') echo 'checked'; ?> name="active" /> Active User</label>
          <p>If it is checked, its mean user is active and can use system otherwise it is inactive.</p>
        </div>
        
        <div class="form-field form-required term-name-wrap">
          <label for="notify"><input type="checkbox" value="T" name="notify" /> Notify User for changes I have made.</label>
          <p>If it is checked, user will get an email notifications that account details are updated.</p>
        </div>

        <p class="submit">
          <input type="submit" name="submit" id="submit" class="button button-primary" value="Save">
          <a href="<?php echo 'admin.php?page=' . $_REQUEST['page'] . '&time=' . time(); ?>" >Cancel Editing</a>
          <span class="spinner"></span>
        </p>
        
      </form>
    </div>

  </div>
</div>