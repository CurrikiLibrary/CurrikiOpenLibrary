<?php

add_action('widgets_init', 'curriki_contributor_load_widgets');

function curriki_contributor_load_widgets() {
	register_widget('curriki_Contributor_Widget');
}

class curriki_Contributor_Widget extends WP_Widget {

	function __construct() {
            parent::__construct('curriki_contributor-widget', 'Count Visitors Widget', array('classname' => 'curriki_contributor', 'description' => ''), array('id_base' => 'curriki_contributor-widget'));
//		$widget_ops = array('classname' => 'curriki_contributor', 'description' => '');
//
//		$control_ops = array('id_base' => 'curriki_contributor-widget');
//
//		$this->WP_Widget('curriki_contributor-widget', __( 'Curriki Contributors', 'curriki_theme' ), $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
        extract( $args );

		$post_type = 'contributor';

        $title = $instance['title'];


		echo $before_widget;

		if($post_type == 'all') {
			$post_type_array = $post_types;
		} else {
			$post_type_array = $post_type;
		}

        echo '<ul class="contributors">';

            if ($title != '') :
				echo '<h2 class="widget-title">' . $title . '</h2>';
			endif;

			$recent_posts = new WP_Query(array(
				'showposts' => $post_count,
				'post_type' => $post_type_array,
			));

			while($recent_posts->have_posts()): $recent_posts->the_post();

                echo '<li class="contributor">';

                		echo get_the_post_thumbnail();

                    	echo '<h4>' . get_the_title() . '</h4>';

                    	echo '<h5>' . get_field('contributor_title') . '</h5>';

                echo '</li>';

			endwhile;
			wp_reset_query();

		echo '</ul>';


		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];

		return $instance;
	}

	function form($instance) {
		$defaults = array('title' => 'Contributors');
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'curriki_theme' ) ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php }
}