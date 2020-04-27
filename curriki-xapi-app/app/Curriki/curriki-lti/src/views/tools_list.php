<?php
global $entityManager;
$lti_type = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiType');
$lti_types = $lti_type->findAll();
?>

<h1>LTI Tools</h1>
<p>
	<a href="<?php echo admin_url('admin.php?page=curriki-wp-lti&controller=toolsettings&action=tool_add'); ?>" class="button button-primary">Add Tool Provider</a>
</p>
<table class="wp-list-table widefat fixed striped posts" style="width:50%">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
            <strong>Tools</strong>
        </td>            
		<td id="cb" class="manage-column column-cb check-column">
            <strong>Actions</strong>
        </td>            
	</thead>
	<tbody id="the-list">
		<?php
			foreach ($lti_types as $lti_type) {
		?>			
			<tr>
				<td class="column-primary page-title">
					<a class="row-title" href="<?php echo admin_url('admin.php?page=curriki-wp-lti&action=tool_edit&id='.$lti_type->getId()); ?>"><?php echo $lti_type->getName(); ?></a>
				</td>
				<td class="column-primary page-title">
					<a href="<?php echo admin_url('admin.php?page=curriki-wp-lti&controller=toolsettings&action=tool_edit&id='.$lti_type->getId()); ?>">Edit</a> | 
					<a href="<?php echo admin_url('admin.php?page=curriki-wp-lti&controller=toolsettings&action=tool_delete&id='.$lti_type->getId()); ?>">Delete</a>
				</td>
			</tr>
		<?php } ?>				
    </tbody>
</table>