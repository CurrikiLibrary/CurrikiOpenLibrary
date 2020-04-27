<?php
/*
* Template Name: Page With Right Column
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Sajid
* Url: http://curriki.com/
*/


// Add our custom loop
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'mycustom_loop' );

function mycustom_loop() {


	if( have_posts() ) {

		// loop through posts
		while( have_posts() ): the_post();

?>


      <article class="post-<?php the_ID(); ?> page type-page status-publish entry" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
        <header class="entry-header">
          <h1 class="entry-title" itemprop="headline">
            <?php the_title(); ?>
          </h1>
        </header>
        <div class="entry-content" itemprop="text">
          <?php the_content(); ?>
          <p>
            <!-- block-container -->
          </p>
          <p>
            <!-- block_container -->
          </p>
        </div>
      </article>
   
   

<?php endwhile;}wp_reset_postdata(); }
genesis();