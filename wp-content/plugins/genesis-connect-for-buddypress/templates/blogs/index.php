<?php
gconnect_get_header();
do_action( 'bp_before_directory_blogs_page' );
do_action( 'bp_before_directory_blogs' );

?>
		<form action="" method="post" id="blogs-directory-form" class="dir-form">

			<h3><?php _e( 'Site Directory', 'buddypress' ); ?><?php if ( is_user_logged_in() && bp_blog_signup_enabled() ) : ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . bp_get_blogs_slug() . '/create/'; ?>"><?php _e( 'Create a Site', 'buddypress' ); ?></a><?php endif; ?></h3>

			<?php do_action( 'bp_before_directory_blogs_content' ); ?>

			<div id="blog-dir-search" class="dir-search" role="search">

				<?php bp_directory_blogs_search_form(); ?>

			</div><!-- #blog-dir-search -->

			<div class="item-list-tabs" role="navigation">
				<ul>
					<li class="selected" id="blogs-all"><a href="<?php bp_root_domain(); ?>"><?php printf( __( 'All Sites <span>%s</span>', 'buddypress' ), bp_get_total_blog_count() ); ?></a></li>

					<?php if ( is_user_logged_in() && bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ) : ?>

						<li id="blogs-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_blogs_slug(); ?>"><?php printf( __( 'My Sites <span>%s</span>', 'buddypress' ), bp_get_total_blog_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

					<?php endif; ?>

					<?php do_action( 'bp_blogs_directory_blog_types' ); ?>

				</ul>
				<div class="clear"></div>
			</div><!-- .item-list-tabs -->

			<div class="item-list-tabs" id="bpsubnav" role="navigation">
				<ul>

					<?php do_action( 'bp_blogs_directory_blog_sub_types' ); ?>

					<li id="blogs-order-select" class="last filter">

						<label for="blogs-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
						<select id="blogs-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest', 'buddypress' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

							<?php do_action( 'bp_blogs_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
				<div class="clear"></div>
			</div>

			<div id="blogs-dir-list" class="blogs dir-list">
				<?php gconnect_locate_template( array( 'blogs/blogs-loop.php' ), true ); ?>
			</div><!-- #blogs-dir-list -->

			<?php do_action( 'bp_directory_blogs_content' ); wp_nonce_field( 'directory_blogs', '_wpnonce-blogs-filter' ); do_action( 'bp_after_directory_blogs_content' ); ?>

		</form><!-- #blogs-directory-form -->
<?php
do_action( 'bp_after_directory_blogs' );
do_action( 'bp_after_directory_blogs_page' );
gconnect_get_footer();