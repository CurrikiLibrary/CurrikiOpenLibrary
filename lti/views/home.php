<?php
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;
?>  

<?php
    if( isset($_SESSION["context_title"]) )
    {
?>        
        <h2 style="color:#7DA941;margin-left:10px;"><?php echo $_SESSION["context_title"]; ?></h2>
<?php
    }
?>      
                                 
            
<?php
    /*
    echo "<pre>";
        var_dump($has_user_context_roles);
    echo "<br />=================================<br />";
        var_dump($_SESSION);
    die;
    */
    if($has_user_context_roles)
    {        
?>
                         
            <?php
                if( (isset($_SESSION['isInstructor']) && $_SESSION['isInstructor']===true) || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']===true) )
                {                    
                    require_once('app/modules/search/index.php');                    
                }
            ?>                 
                   
                    
            <?php
                if(isset($_SESSION['isLearner']) && $_SESSION['isLearner']===true)
                {
            ?>
                    <!-- <ul>
                        <li><a href="#">View Resources</a></li>
                    </ul>-->
                    <?php
                        require_once('app/modules/search/index.php');
                    ?>
            <?php
                }
            ?>                                                
         
<?php
    }
?>
            
<?php
    if($_SESSION["isRoleInstrole"])
    {
?>
        <ul>
            <li> <a href="#">Institution Settings</a> </li>                        
            <?php
                if($_SESSION["isInstroleRelatedToContextRole"])
                {
            ?>
                    <li> <a href="#">Institution User guidelines</a> </li>
            <?php
                }
            ?>
        </ul>                    
<?php
    }
?>
            
            
<ul>
    <?php        
        if( $resource_link->hasToolSettingsService() && array_key_exists("custom_link_setting_url",$resource_link->getSettings()) )
        {
    ?>
            <li><a href="<?php echo $resource_link->getSettings()["custom_link_setting_url"]; ?>"> <?php echo $_SESSION['resource_link_title'];?> </a></li>
    <?php                 
        } ?>                
</ul>

        <?php
            /*
            if( isset($resource_link_settings["lis_result_sourcedid"]) && $_SESSION['isLearner'] === true)
            {                       
                if(isset($_POST["ratevalue"]) && isset($_POST["rate_submit"]) && $_POST["rate_submit"]==1)
                {   
                    TPGradingHelper::replaceResult($db, $_SESSION);
                }
                if(isset($_POST["rate_delete"]) && isset($_POST["rate_delete"]) && $_POST["rate_delete"]==1)
                {   
                    TPGradingHelper::deleteResult($db, $_SESSION);
                }
        ?> 
        
        <br /><br />
        
                <h2>Rating</h2>
                <form method="post">
                    <input type="hidden" name="rate_submit" value="1" />
                    <p><strong>Please Rate the subject:</strong></p>        
                    <label for="no">0%</label> 
                    <input type="radio" name="ratevalue" id="no" value="0.0" <?php echo isset($_POST["ratevalue"]) && $_POST["ratevalue"] === "0.0" ? "checked=checked":""; ?> />

                    <label for="yes">100%</label> 
                    <input type="radio" name="ratevalue" id="yes" value="1.0" <?php echo isset($_POST["ratevalue"]) && $_POST["ratevalue"] === "1.0" ? "checked=checked":""; ?> />
                    
                    <input type="submit" name="submit" value="Submit" />
                </form> 
                
                <form method="post">
                    <input type="hidden" name="rate_delete" value="1" />
                    <input type="submit" name="submit" value="Delete Rating" />
                </form>
                <?php
                    $readResult = TPGradingHelper::readResult($db, $_SESSION);                    
                ?>
                <p> <strong>Result = </strong> <?php echo $readResult!==false ? $readResult:" No Grading Result Found"; ?> </p>
        <?php
            }
            */
        ?>        
        