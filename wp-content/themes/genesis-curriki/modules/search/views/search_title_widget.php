<?php
    if(!(isset($_REQUEST['partnerid']) && $_REQUEST['partnerid']>1)){
        while (have_posts()) : the_post();
            ?>
            <!--Because the_content() works only inside a WP Loop -->
            <p class="desc"><?php the_content(); ?></p>
            <!-- Page Content -->
            <?php
        endwhile; //resetting the page loop
    }
    wp_reset_query(); //resetting the page query