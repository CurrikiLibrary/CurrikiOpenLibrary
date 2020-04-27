<h3 class="modal-title" style="padding-left: 15px;text-align:left;"><?php echo __('Never Miss a Blog Post!','curriki') ?></h3>
<p style="padding-left: 15px;padding-right:35px;">We know you're busy. Sign up and get blog posts as soon as they are published!</p>
<?php 
if(!empty($_POST['curriki_errors'])){
    echo "<div style='clear:both; margin: 0 0 0 5px;'><ul>";
    foreach($_POST['curriki_errors'] as $error){
        echo '<li>'.$error.'</li>';
    }
    echo "</ul></div>";
}?>
<div class="join-login-section grid_11"><div>
    <form method="post" action="" id="newsletterform" name="newsletterform" style="padding: 10px 10px 10px 10px;">
        <div id="newsletter_result"></div>
        <input type="hidden" name="signup_newsletter" value="yes" />
        <label style="display:block;text-align: left;"><strong>Name:</strong>
          <input type="text" name="nl_name" placeholder="<?php echo __('Name','curriki') ?>" value="<?php echo $_POST['nl_name']?>" />
        </label>
        <label style="display:block;text-align: left;"><strong>Email</strong>
        <input type="text" name="nl_email" placeholder="<?php echo __('Email','curriki') ?>" value="<?php echo $_POST['nl_email']?>" />
        </label>
        <input type="submit" class="gform_button button" value="Signup" style="width:280px;display: block;">
    </form>
</div></div>
<script type="text/javascript">
jQuery(document).ready(function () {
    // Attach a submit handler to the form
    jQuery( "#newsletterform" ).submit(function( event ) {
        jQuery( "#newsletter_result" ).empty().append( 'Please wait!' );
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = jQuery( this ),
        nl_name = $form.find( "input[name='nl_name']" ).val(),
        nl_email = $form.find( "input[name='nl_email']" ).val(),
        signup_newsletter = $form.find( "input[name='signup_newsletter']" ).val(),
        url = "?curriki_ajax_action=newsletter";
        // Send the data using post
        var posting = jQuery.post( url, { nl_name: nl_name, nl_email: nl_email, signup_newsletter: signup_newsletter, action: 'curriki_newsletter' } );
        // Put the results in a div
        posting.done(function( data ) {
            if(data.trim() != '1'){
                jQuery( "#newsletter_result" ).empty().append( data );
            }else{
                jQuery( "#newsletter_result" ).empty().append( 'Successfully Signed up for newsletter!' );
            }
        });
        return false;

    });
});
</script>