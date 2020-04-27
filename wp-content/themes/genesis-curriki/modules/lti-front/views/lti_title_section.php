<!-- Page Heading-->
<div class="resources grid_12">    
    <h2 style="color:#7DA941;"><?php echo get_the_title(); ?></h2> <!-- Page Title -->
    <?php
    // TO SHOW THE PAGE CONTENTS
    while (have_posts()) : the_post();
        ?> <!--Because the_content() works only inside a WP Loop -->
        <p class="desc"><?php the_content(); ?></p> <!-- Page Content -->
        <?php
    endwhile; //resetting the page loop    
    wp_reset_query(); //resetting the page query
    ?>
</div>
<div style="clear:both"></div>
<!-- /Page Heading-->