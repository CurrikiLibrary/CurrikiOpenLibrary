<?php
use IMSGlobal\LTI\ToolProvider;
require_once('app/tp_ui_helper.php');

$data_body_start["user_current_role"] = $user_current_role;
$head_html = TpUiHelper::headHtml($data);
$body_start_html = TpUiHelper::bodyStartHtml($data_body_start);
$body_end_html = TpUiHelper::bodyEndHtml($data_body_end);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php 
echo $head_html; 
echo $body_start_html;
?>

<?php
// Display table of existing tool consumer records
if ($ok) 
{

    if($_SESSION["isUnrecognisedRole"])
    {
        require_once 'access_denied.php';
    }else{
        require_once 'home.php';
    }
}else{
    echo "Some thing went wrong.";
}
?>

<?php
echo $body_end_html;
?>
</html>