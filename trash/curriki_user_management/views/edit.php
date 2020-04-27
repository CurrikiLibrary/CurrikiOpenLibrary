<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template user, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    table {width:80%; margin-left: 5%}
    td {font-size: 16px; padding: 10px;}
    textArea{width:100%}
</style>
<h1>User</h1>
<form action="admin.php?page=curriki_user_management_list&time=<?php echo time();?>" method="post">

    <input type="hidden" name="user_submit_id" value="<?php echo $_REQUEST['user_id']; ?>">
    <input type="hidden" name="submit" value="true">

    <table >
        <tr>
            <td width=200><strong>Email:</strong></td>
            <td><input type="text" value="<?php echo $data[0]->email;?>" name="email" /></td>
        </tr>
        <tr>
            <td><strong>First Name:</strong></td>
            <td><input type="text" value="<?php echo $data[0]->firstname;?>" name="firstname" /></td>
        </tr>
        <tr>
            <td><strong>Last Name:</strong></td>
            <td><input type="text" value="<?php echo $data[0]->lastname;?>" name="lastname" /></td>
        </tr>
        <tr>
            <td><strong>Active:</strong></td>
            <td><input type="checkbox" value="T" <?php if($data[0]->active == 'T') echo 'checked';?> name="active" /></td>
        </tr>
        <tr>
            <td><strong>Notify User:</strong></td>
            <td><input type="checkbox" value="T" name="notify" /></td>
        </tr>
        <tr>
            <td colspan="2"><hr/></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right">
                <input type="button" value="Cancel" onclick="window.location.href='admin.php?page=curriki_user_management_list&time=<?php echo time();?>'" />
                <input type="submit" value="Submit" />
            </td>
        </tr>
    </table>
</form>