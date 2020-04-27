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
<h1>Resources</h1>

<?php
if(!empty($_REQUEST['file_submit_id'])) {
    echo '<br/><div id="message" class="updated"><p><strong>Resource Saved Successfully </strong>.</p></div><br/><br/>';
}
?>

<div id="outer_wrapper_plugin" >
    <table id="resources" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Contributor</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Note</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Title</th>
                <th>Contributor</th>
                <th>Type</th>
                <th>Date Created</th>
                <th>Note</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td style="width: 30% !important"><a href="?page=curriki_file_check&file_id=<?php echo $row->resourceid; ?>"><?php echo substr($row->title,0,45).'...'; ?></a></td>
                    <td><?php echo $row->display_name; ?></td>
                    <td><?php echo $types[$row->resourcechecked]; ?></td>
                    <td><?php echo $row->createdate; ?></td>
                    <td><?php echo $row->resourcerequestchecknote; ?></td>
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