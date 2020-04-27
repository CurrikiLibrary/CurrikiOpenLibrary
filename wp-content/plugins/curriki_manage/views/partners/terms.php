<div class="navigation">
    <ul class="nav nav-pills">
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners">Partners</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_partner">Add New Partner</a></li>
        <li class="active"><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=terms">Terms</a></li>
        <li><a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=add_term">Add New Term</a></li>
    </ul>
    <div class="clear"></div>
</div>

<form action="<?php echo get_admin_url(); ?>admin.php?">
    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Search Terms:</label>
        <input type="hidden" name="page" value="curriki_partners">
        <input type="hidden" name="action" value="terms">
        <input type="search" id="post-search-input" name="term" value="<?php echo isset($_GET['term']) ? $_GET['term'] : ''; ?>">
        <input type="submit" id="search-submit" class="button" value="Search Terms">
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
            <td>Term</td>
            <td>Active</td>
            <td>Term Start Date</td>
            <td>Term End Date</td>
            <td>Total Searches</td>
            <td>Partner</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($terms as $term): ?>
            <tr>
                <td><?php echo $term->term; ?></td>
                <td><?php echo $term->active; ?></td>
                <td><?php echo $term->termstartdate; ?></td>
                <td><?php echo $term->termenddate; ?></td>
                <td><?php echo $term->searchcount; ?></td>
                <td><?php echo $term->partnername; ?> - <?php echo $term->partnerid; ?></td>
                <td>
                    <!--<a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&edit=<?php echo $term->partnerid; ?>" class="button button-small">Edit</a>-->
                    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=edit_term&partnerid=<?php echo $term->partnerid; ?>&term=<?php echo $term->term; ?>&active=<?php echo $term->active; ?>" class="button button-small">Edit</a>
                    <a href="<?php echo get_admin_url(); ?>admin.php?page=curriki_partners&action=delete_term&partnerid=<?php echo $term->partnerid; ?>&term=<?php echo $term->term; ?>&active=<?php echo $term->active; ?>" class="button button-small" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>