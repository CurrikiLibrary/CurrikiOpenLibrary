<?php
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo $head_html; 
echo $body_start_html;
?>    
    
<div class="tp-admin-wrapper">   
    
    <?php
    // Check for any messages to be displayed
  if (isset($_SESSION['error_message'])) {      
?>
    <p style="color: #f00;margin: 0 auto;width: 20%;text-align: center;"><?php echo $_SESSION['error_message']; ?></p>
<?php 
    unset($_SESSION['error_message']);
  }

  if (isset($_SESSION['message'])) {
?>
    <p style="font-weight: bold; color: #00f;"><?php echo $_SESSION['message']; ?></p>
<?php    
    unset($_SESSION['message']);
  }

  if ($ok) {
    ?>    
        <form action="./?do=login&t=<?php echo time(); ?>" method="post">
            <table class="admin-login" border="1" cellpadding="3">
                <thead>
                  <tr>                
                    <th><strong>Login</strong></th>
                  </tr>
                </thead>
                <tbody>       
                    <tr>
                        <td>
                            <label for="username">Username</label>
                            <input type="text" value="" maxlength="50" size="50" name="username" id="username" />
                        </td>                        
                    </tr>
                    <tr>
                        <td>
                            <label for="password">Password</label>
                            <input type="password" value="" maxlength="50" size="50" name="password" id="password" />
                        </td>
                    </tr>            
                    <tr>
                        <td>
                            <div style="width:100%;text-align:center;">
                                <input type="submit" value="Login" id="loginBtn" name="loginBtn" />
                            </div>                            
                        </td>
                    </tr>            
                </tbody>
            </table>                
        </form>   
  
<?php  
} ?>
</div>
<?php
    echo $body_end_html;
?>    
            
</html>