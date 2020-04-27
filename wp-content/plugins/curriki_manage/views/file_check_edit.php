<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$types = array(
    'D' => 'Flagged',
    'F' => 'Unchecked'
);
?>
<style>
    table {width:80%; margin-left: 5%}
    td {font-size: 16px; padding: 10px;}
    textArea{width:100%}
</style>
<h1>Resource</h1>
<form action="admin.php?page=curriki_file_check&time=<?php echo time();?>" method="post">

    <input type="hidden" name="file_submit_id" value="<?php echo $_REQUEST['file_id']; ?>">
    <input type="hidden" name="submit" value="true">

    <table >
        <tr>
            <td width=200><strong>Title:</strong></td>
            <td><?php echo $data[0]->title; ?></td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td><?php echo $data[0]->description; ?></td>
        </tr>
        <tr>
            <td><strong>Contributer:</strong></td>
            <td><?php echo $data[0]->display_name; ?></td>
        </tr>
        
        <tr>
            <td><strong>Type:</strong></td>
            <td><?php echo $types[$data[0]->resourcechecked]; ?></td>
        </tr>
        <tr>
            <td><strong>Notes:</strong></td>
            <td><?php echo $data[0]->resourcerequestchecknote; ?></td>
        </tr>

        
        <tr>
            <td><strong>Approved:</strong></td>
            <td><input type="radio" name="status" value="T" checked="checked" /></td>
        </tr>
        <tr>
            <td><strong>Disapproved:</strong></td>
            <td><input type="radio" name="status" value="R" checked="checked" /></td>
        </tr>
        <tr>
            <td><strong>Check Notes:</strong></td>
            <td><textarea name="notes"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right">
                <input type="button" value="Cancel" onclick="window.location.href='admin.php?page=curriki_file_check&time=<?php echo time();?>'" />
                <input type="submit" value="Submit" />
            </td>
        </tr>
    </table>
</form>
