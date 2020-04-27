<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *  (
 */

$status = array(
    'T' => 'Yes',
    'F' => 'No'
);
?>
<style>
    #outer_wrapper_plugin{
        width:90%;
        margin: 0 auto;
        border: solid 1px #999;
        padding:2%;
    }
    #outer_wrapper_plugin table{
        border: solid 1px #555;
    }
</style>
<h1>Users</h1>

<?php
if(!empty($_REQUEST['user_submit_id'])) {
    echo '<br/><div id="message" class="updated"><p><strong>User Saved Successfully </strong>.</p></div><br/><br/>';
}
?>

<div id="outer_wrapper_plugin" >
    <table id="resources" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Active</th>
                <th>Registration Date</th>
                <th>Inactive Date</th>
                <th>Member type</th>
                <th>Sitename</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Active</th>
                <th>Registration Date</th>
                <th>Inactive Date</th>
                <th>Member type</th>
                <th>Sitename</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td><a href="?page=curriki_user_management_list&user_id=<?php echo $row->userid; ?>"><?php echo $row->username; ?></a></td>
                    <td><?php echo $row->email; ?></td>
                    <td><?php echo $row->firstname; ?></td>
                    <td><?php echo $row->lastname; ?></td>
                    <td><?php echo $status[$row->active]; ?></td>
                    <td><?php echo $row->registerdate; ?></td>
                    <td><?php echo $row->inactivedate; ?></td>
                    <td><?php echo $row->membertype; ?></td>
                    <td><?php echo $row->sitename; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#resources').DataTable();
    });
</script>