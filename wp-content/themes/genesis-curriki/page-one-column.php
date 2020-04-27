<?php
/*
* Template Name: One Column Page Template
*
* Child Theme Name: Curriki Child Theme for Genesis 2.1
* Author: Sajid
* Url: http://curriki.com/
*/

remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

function curriki_genesis_do_sidebar() {
	
	if ( ! dynamic_sidebar( 'contactus-sidebar' ) && current_user_can( 'edit_theme_options' )  ) {
		genesis_default_widget_area_content( __( 'primary widget area', 'genesis' ),1);
	}

}
add_action( 'genesis_sidebar', 'curriki_genesis_do_sidebar' );

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		<?php 
		$post_id = get_the_ID();
		$key = 'sidebar_content';
		$content = get_post_meta( $post_id, $key, false);
		?>
		
		var size = '<?=$size?>';
		<?php foreach($content as $cont){ ?>
			var content = '<?php echo $cont; ?>';
			console.log(content);
			$(document).find(".sidebar.sidebar-primary.widget-area").append('<section class="widget"><div class="widget-wrap">'+content+'</div></div>');
	    <?php } ?>
	});
	
</script>
<?php
genesis();