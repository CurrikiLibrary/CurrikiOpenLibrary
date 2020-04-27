<?php
$lti_type = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiType');
$lti_types = $lti_type->findAll();
?>
@extends('layouts.activity_layout')
@section('content')
<h1>LTI Tools</h1>
<p>
	<a href="<?php echo 'lti-manage?action=tool_add'; ?>" class="button button-primary">Add Tool Provider</a>
</p>
<table class="wp-list-table widefat fixed striped posts" style="width:50%">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
            <strong>too_id</strong>
        </td>            
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
					<?php echo $lti_type->getLti()->getId(); ?>
				</td>
				<td class="column-primary page-title">
					<a class="row-title" href="<?php echo 'lti-manage?&action=tool_edit&id='.$lti_type->getId(); ?>"><?php echo $lti_type->getName(); ?></a>
				</td>
				<td class="column-primary page-title">
					<a href="<?php echo 'lti-manage?action=tool_edit&id='.$lti_type->getId(); ?>">Edit</a> | 
					<a href="<?php echo 'lti-manage?action=tool_delete&id='.$lti_type->getId(); ?>">Delete</a>
				</td>
			</tr>
		<?php } ?>				
    </tbody>
</table>
@endsection