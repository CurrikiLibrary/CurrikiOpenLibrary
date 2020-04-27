<?php
/*
 * Template Name: User Edit Profile Template
 *
 * Child Theme Name: Curriki Child Theme for Genesis 2.1
 * Author: Orange Blossom Media
 * Url: http://orangeblossommedia.com/
 */

// Add custom body class to the head
add_filter('body_class', 'curriki_error_page_add_body_class');

function curriki_error_page_add_body_class($classes) {
    $classes[] = 'error_page';
    return $classes;
}

// Execute custom style guide page
add_action('genesis_meta', 'curriki_custom_error_page_loop');

function curriki_custom_error_page_loop() {
    //* Force full-width-content layout setting
    add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');

    remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
    remove_action('genesis_loop', 'genesis_do_loop');

    add_action('genesis_loop', 'curriki_error_page_body', 15);
}

function curriki_error_page_body() {
   
    ?>

<style type="text/css">
    .profile-img
    {
        margin-bottom: 10px;
        margin-left: 55px;
    }
    
    .error_para{
        border: 1px solid #ff3333 !important;
        color: #ff3333 !important;
        font-size: 15px !important;
        font-weight: bold !important;
        padding: 8px !important;
    }
    
    .error-bar-para{
        border: 1px solid #ff3333 !important;        
        font-size: 18px !important;        
        padding: 4px !important;
        color: #D8000C;
        background-color: #FFBABA;        
    }
    .error-bar
    {
        width: 70% !important;
    }
    
    .container_12 .grid_10 {
        min-height: 175px !important;        
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        
        
    });
</script>

<form method="post" action="" enctype="multipart/form-data" id="edit-form">
    <input type="hidden" name="edit_profile" value="yes" />
    <div class="edit-profile-content clearfix"><div class="wrap container_12">
            <div class="grid_10">
                
                <?php
                    if($_GET["error-m"] == "group-spam")
                    {
                ?>                
                        <div class="error-bar">
                            <p class="error-bar-para">This group identified as spam</p>
                        </div>                                
                <?php
                    }
                ?>
                
                
                <?php
                    if($_GET["error-m"] == "rate-comment-spam")
                    {
                ?>                
                        <div class="error-bar">
                            <p class="error-bar-para">Comments for rating identified as spam <?php if(isset($_GET["rtn"]) ){ ?> <a href="<?php echo urldecode($_GET["rtn"]) ?>"><strong>Go Back</strong></a> <?php } ?> </p>
                        </div>                                
                <?php
                    }
                ?>
                
                
            </div>
        </div></div>
    </form>
    <?php
}

genesis();
