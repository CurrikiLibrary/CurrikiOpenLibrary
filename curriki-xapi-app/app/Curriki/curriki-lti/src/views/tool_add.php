<?php
if (isset($_POST['submit_lti_tool']) && $_POST['submit_lti_tool'] === 'Add' ){
?>
    <script>
        window.location = "<?php echo admin_url('admin.php?page=curriki-wp-lti'); ?>";
    </script>
<?php } ?>
<style type="text/css">
    #wpfooter{
        display: none !important;
    }
    .version-toggle{
        display: none;
    }
</style>
<script type="text/javascript">
    jQuery('document').ready(function() {
        //jQuery('.version-toggle').toggle()
        jQuery('#id_lti_ltiversion').change(function () {            
            if(this.value === '1.3.0'){     
                jQuery('.version-toggle').show();              
                jQuery('.requireable').each(function(i,obj){                    
                    jQuery(obj).prop('required',true);                                               
                });
            }else if(this.value === 'LTI-1p0'){
                jQuery('.version-toggle').hide();   
                jQuery('.requireable').each(function(i,obj){
                    jQuery(obj).removeAttr('required');                          
                });                             
            }
        })
    })
</script>

<h1>Add LTI Tool Provider</h1>

<form action="<?php echo admin_url('admin.php?page=curriki-wp-lti&controller=toolsettings&action=tool_add'); ?>" method="post">
<div class="fcontainer clearfix" id="yui_3_17_2_1_1564474566068_220">
		<div id="fitem_id_lti_typename" class="form-group row  fitem   ">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            <abbr class="initialism text-danger" title="Required"><i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Required" aria-label="Required"></i></abbr>
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The tool name is used to identify the tool provider within Moodle. The name entered will be visible to teachers when adding external tools within courses.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Tool name" aria-label="Help with Tool name"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_typename">
            Tool name
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text" id="yui_3_17_2_1_1564474566068_259">
        <input type="text" class="form-control" name="lti_typename" id="id_lti_typename" value="" size="" required>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_typename">
            
        </div>
    </div>
</div><div id="fitem_id_lti_toolurl" class="form-group row  fitem   ">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            <abbr class="initialism text-danger" title="Required"><i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Required" aria-label="Required"></i></abbr>                        
        </span>
        <label class="col-form-label d-inline " for="id_lti_toolurl">
            Tool URL
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text">
        <input type="text" class="form-control" name="lti_toolurl" id="id_lti_toolurl" value="" size="64" required>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_toolurl">
            
        </div>
    </div>
</div><div id="fitem_id_lti_description" class="form-group row  fitem   ">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The description of the tool that will be displayed to teachers in the activity list.</p>

<p>This should describe what the tool is for and what it does and any additional information the teacher may need to know.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Tool description" aria-label="Help with Tool description"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_description">
            Tool description
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="textarea">
        <textarea name="lti_description" id="id_lti_description" class="form-control " rows="4" cols="60"></textarea>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_description">
            
        </div>
    </div>
</div>
<label class="col-form-label d-inline " for="id_lti_ltiversion">
    LTI Version
</label>
<div id="fitem_id_lti_ltiversion" class="form-group row  fitem   ">
    <div class="col-md-9 form-inline felement" data-fieldtype="select">        
        <select class="custom-select" name="lti_ltiversion" id="id_lti_ltiversion">
            <option value="LTI-1p0" selected="">LTI 1.0/1.1</option>
            <option value="1.3.0">LTI 1.3</option>
        </select>           
    </div>
</div>

<div id="fitem_id_lti_resourcekey" class="form-group row fitem">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">                        
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right">
                <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Consumer key" aria-label="Help with Consumer key"></i>
            </a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_resourcekey">
            Consumer key
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text" id="yui_3_17_2_1_1564474566068_204">
        <input type="text" class="form-control" name="lti_resourcekey" id="id_lti_resourcekey" value="<?php echo curLtiGenerateRandomString(); ?>" size="">
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_resourcekey">
            
        </div>
    </div>
</div>

<div id="fitem_id_lti_password" class="form-group row fitem">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">                        
            <a class="btn btn-link p-0" role="button">
                <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Shared secret" aria-label="Help with Shared secret"></i>
            </a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_password">
            Shared secret
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="passwordunmask" id="yui_3_17_2_1_1564474566068_207">
        <span data-passwordunmask="wrapper" data-passwordunmaskid="id_lti_password" id="yui_3_17_2_1_1564474566068_206">
            <span data-passwordunmask="editor" id="yui_3_17_2_1_1564474566068_205">
                <input type="text" name="lti_password" id="id_lti_password" value="<?php echo curLtiGenerateRandomString(); ?>" class="form-control d-inline-block">
            </span>                        
        </span>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_password">
            
        </div>
    </div>
</div>

<div id="fitem_id_lti_clientid_disabled" class="form-group row fitem version-toggle">
    <div class="col-md-3" id="yui_3_17_2_1_1564474566068_245">
        <span class="float-sm-right text-nowrap" id="yui_3_17_2_1_1564474566068_244">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The client ID can be thought of as a unique value used to identify a tool.
It is created automatically for each tool which uses the JWT security profile introduced in LTI 1.3 and should
be part of the details passed to the provider of the tool so that they can configure the connection at their end.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus" data-original-title="" title="" id="yui_3_17_2_1_1564474566068_243">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Client ID" aria-label="Help with Client ID" id="yui_3_17_2_1_1564474566068_247"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_clientid_disabled">
            Client ID
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text" id="yui_3_17_2_1_1564474566068_201">
        <?php $time = time(); ?>
        <input type="text" class="form-control " name="lti_clientid_disabled" id="id_lti_clientid_disabled" value="<?php echo $time; ?>" size="" disabled="disabled">
        <input type="hidden" class="form-control " name="lti_clientid" id="id_lti_clientid" value="<?php echo $time; ?>" size="" />
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_clientid_disabled">
            
        </div>
    </div>
</div>
<div id="fitem_id_lti_publickey" class="form-group row fitem version-toggle">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The public key (in PEM format) provided by the tool to allow signatures of incoming messages and service requests to be verified.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Public key" aria-label="Help with Public key"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_publickey">
            Public key
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="textarea" id="yui_3_17_2_1_1564474566068_253">
        <textarea name="lti_publickey" id="id_lti_publickey" class="form-control " rows="8" cols="60"></textarea>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_publickey">
            
        </div>
    </div>
</div>
<div id="fitem_id_lti_initiatelogin" class="form-group row fitem version-toggle">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The tool URL to which requests for initiating a login are to be sent.  This URL is required before a message can be successfully sent to the tool.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Initiate login URL" aria-label="Help with Initiate login URL"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_initiatelogin">
            Initiate login URL
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text">
        <input type="text" class="form-control requireable" name="lti_initiatelogin" id="id_lti_initiatelogin" value="" size="64">
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_initiatelogin">
            
        </div>
    </div>
</div>

<div id="fitem_id_lti_redirectionuris" class="form-group row fitem version-toggle">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>A list of URIs (one per line) which the tool uses when making authorisation requests.  At least one must be registered before a message can be successfully sent to the tool.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Redirection URI(s)" aria-label="Help with Redirection URI(s)"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_redirectionuris">
            Redirection URI(s)
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="textarea">
        <textarea name="lti_redirectionuris" id="id_lti_redirectionuris" class="form-control requireable" rows="3" cols="60"></textarea>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_redirectionuris">
            
        </div>
    </div>
</div>
<div id="fitem_id_lti_customparameters" class="form-group row  fitem   ">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Custom parameters are settings used by the tool provider. For example, a custom parameter may be used to display
a specific resource from the provider.  Each parameter should be entered on a separate line using a format of &quot;name=value&quot;; for example, &quot;chapter=3&quot;.</p>

<p>It is safe to leave this field unchanged unless directed by the tool provider.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Custom parameters" aria-label="Help with Custom parameters"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_customparameters">
            Custom parameters
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="textarea">
        <textarea name="lti_customparameters" id="id_lti_customparameters" class="form-control " rows="4" cols="60"></textarea>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_customparameters">
            
        </div>
    </div>
</div>
<div id="fitem_id_lti_coursevisible" class="form-group row  fitem   ">
    <!-- <div class="col-md-3">
        <span class="float-sm-right text-nowrap">                        
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>This tool may be shown in the activity chooser for a teacher to select to add to a course. Alternatively, it may be shown in the preconfigured tool drop-down menu when adding an external tool to a course. A further option is for the tool configuration to only be used if the exact tool URL is entered when adding an external tool to a course.</p>
            </div> " data-html="true" tabindex="0" data-trigger="focus">
            <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Tool configuration usage" aria-label="Help with Tool configuration usage"></i>
            </a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_coursevisible">
            Tool configuration usage
        </label>
    </div> -->
    <!-- <div class="col-md-9 form-inline felement" data-fieldtype="select">
        <select class="custom-select
                       
                       " name="lti_coursevisible" id="id_lti_coursevisible">
            <option value="0">Do not show; use only when a matching tool URL is entered</option>
            <option value="1" selected="">Show as preconfigured tool when adding an external tool</option>
            <option value="2">Show in activity chooser and as a preconfigured tool</option>
        </select>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_coursevisible">
            
        </div>
    </div> -->
</div>
<!-- <div id="fitem_id_lti_launchcontainer" class="form-group row  fitem   ">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">
            
            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The launch container affects the display of the tool when launched from the course. Some launch containers provide more screen
real estate to the tool, and others provide a more integrated feel with the Moodle environment.</p>

<ul><li><strong>Default</strong> - Use the launch container specified by the tool configuration.</li>
<li><strong>Embed</strong> - The tool is displayed within the existing Moodle window, in a manner similar to most other Activity types.</li>
<li><strong>Embed, without blocks</strong> - The tool is displayed within the existing Moodle window, with just the navigation controls
    at the top of the page.</li>
<li><strong>New window</strong> - The tool opens in a new window, occupying all the available space.
    Depending on the browser, it will open in a new tab or a popup window.
    It is possible that browsers will prevent the new window from opening.</li>
</ul></div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Default launch container" aria-label="Help with Default launch container"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_launchcontainer">
            Default launch container
        </label>
</div> -->

    <!-- <div class="col-md-9 form-inline felement" data-fieldtype="select">
        <select class="custom-select
                       
                       " name="lti_launchcontainer" id="id_lti_launchcontainer">
            <option value="2">Embed</option>
            <option value="3" selected="">Embed, without blocks</option>
            <option value="5">Existing window</option>
            <option value="4">New window</option>
        </select>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_launchcontainer">
            
        </div>
    </div> -->
</div>

<div class="form-group row fitem advanced show">
    <div class="col-md-3">
    </div>
    <div class="col-md-9 checkbox">
        <div class="form-check">
            <label>                    
                    <input type="checkbox" name="lti_contentitem" class="form-check-input " value="1" id="id_lti_contentitem">
                    Content-Item Message
            </label>
            <span class="text-nowrap">                
                <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>If ticked, the option 'Select content' will be available when adding an external tool.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Content-Item Message" aria-label="Help with Content-Item Message"></i>
</a>
            </span>
        </div>
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_contentitem">
            
        </div>
    </div>
</div><div id="fitem_id_lti_toolurl_ContentItemSelectionRequest" class="form-group row fitem advanced show">
    <!-- <div class="col-md-3">
        <span class="float-sm-right text-nowrap">            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The Content Selection URL will be used to launch the content selection page from the tool provider. If it is empty, the Tool URL will be used</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Content Selection URL" aria-label="Help with Content Selection URL"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_toolurl_ContentItemSelectionRequest">
            Content Selection URL
        </label>
    </div> -->
    <!-- <div class="col-md-9 form-inline felement" data-fieldtype="text" id="yui_3_17_2_1_1564474566068_203">
        <input type="text" class="form-control " name="lti_toolurl_ContentItemSelectionRequest" id="id_lti_toolurl_ContentItemSelectionRequest" value="" size="64" disabled="disabled">
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_toolurl_ContentItemSelectionRequest">
            
        </div>
    </div> -->
</div><div id="fitem_id_lti_icon" class="form-group row fitem advanced show">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>The icon URL allows the icon that shows up in the course listing for this activity to be modified. Instead of using the default
LTI icon, an icon which conveys the type of activity may be specified.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Icon URL" aria-label="Help with Icon URL"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_icon">
            Icon URL
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text">
        <input type="text" class="form-control " name="lti_icon" id="id_lti_icon" value="" size="64">
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_icon">
            
        </div>
    </div>
</div><div id="fitem_id_lti_secureicon" class="form-group row fitem advanced show">
    <div class="col-md-3">
        <span class="float-sm-right text-nowrap">            
            <a class="btn btn-link p-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Similar to the icon URL, but used when the site is accessed securely through SSL. This field is to prevent the browser from displaying a warning about an insecure image.</p>
</div> " data-html="true" tabindex="0" data-trigger="focus">
  <i class="icon fa fa-question-circle text-info fa-fw " title="Help with Secure icon URL" aria-label="Help with Secure icon URL"></i>
</a>
        </span>
        <label class="col-form-label d-inline " for="id_lti_secureicon">
            Secure icon URL
        </label>
    </div>
    <div class="col-md-9 form-inline felement" data-fieldtype="text">
        <input type="text" class="form-control " name="lti_secureicon" id="id_lti_secureicon" value="" size="64">
        <div class="form-control-feedback invalid-feedback" id="id_error_lti_secureicon">
            
        </div>
    </div>
</div>
    <input type="submit" name="submit_lti_tool" value="Add" />
    |
    <a href="<?php echo admin_url('admin.php?page=curriki-wp-lti'); ?>">Back</a>
</div>
</form>

<?php
function curLtiGenerateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>