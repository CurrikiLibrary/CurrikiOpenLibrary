<div class="navigation">
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners">Partners</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_partner">Add New Partner</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=terms">Terms</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_term">Add New Term</a></li>
    </ul>
    <div class="clear"></div>
</div>
<!--<div class="wrap">
    <h1 class="wp-heading-inline">Partners</h1>

    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_partner" class="page-title-action">Add New</a>
    <hr class="wp-header-end">
    <br class="clear">
</div>-->

<form action="<?php echo get_admin_url(); ?>admin.php?">
    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Search Partners:</label>
        <input type="hidden" name="page" value="curriki_partners">
        <input type="search" id="post-search-input" name="partner" value="<?php echo isset($_GET['partner']) ? $_GET['partner'] : ''; ?>">
        <input type="submit" id="search-submit" class="button" value="Search Partners">
    </p>
    <div class="clear"></div>
</form>

<?php
foreach($data['validation_errors'] as $message){
?>
    <div class="notice notice-error is-dismissible">
        <p><?php echo $message; ?></p>
    </div>
<?php
}
foreach($data['success_message'] as $message){
?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo $message; ?></p>
    </div>
<?php
}
?>
<table class="wp-list-table widefat fixed striped pages">
    <thead>
        <tr>
            <td>Name</td>
            <td>Active</td>
            <td>Terms Number Quota</td>
            <td>Total Terms Uploaded</td>
            <td>Search Start Date</td>
            <td>Search End Date</td>
            <td>Contributor</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($partners as $partner): ?>
            <tr>
                <td><?php echo $partner->name; ?></td>
                <td><?php echo $partner->active; ?></td>
                <td><?php echo $partner->termsnumber; ?></td>
                <td><?php echo $partner->uploaded_terms_count; ?></td>
                <td><?php echo $partner->searchstartdate; ?></td>
                <td><?php echo $partner->searchenddate; ?></td>
                <td><?php echo $partner->contributor; ?></td>
                <td>
                    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=edit_partner&edit=<?php echo $partner->partnerid; ?>" class="button button-small">Edit</a>
                    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=delete_partner&delete=<?php echo $partner->partnerid; ?>" class="button button-small" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>

<!--<div class="pagination">
    <ul>
        <li>
            <a href="#">1</a>
        </li>
        <li>
            <a href="#">2</a>
        </li>
    </ul>
</div>-->