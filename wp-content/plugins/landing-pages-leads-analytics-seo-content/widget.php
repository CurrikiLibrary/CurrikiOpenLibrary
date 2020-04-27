<?php 
    class FormApplicationWidget extends WP_Widget {

        function FormApplicationWidget() 
        {
            global $wp_version;
            $widget_ops = array('classname' => 'widget_application_form', 'description' => "Application form" );
            $control_ops = array('width' => 400, 'height' => 350);
            if (version_compare($wp_version, '4.3.0', '<=')) {
                $this->WP_Widget('widget_application_form', "Application form", $widget_ops, $control_ops);
            } else {
                parent::__construct('widget_application_form', "Application form", $widget_ops, $control_ops);
            }

        }
        function widget($args, $instance) 
        {
            $form = landing_page_wb::form_show( array('sidebar' => true ) );
            if ($form) {
                echo $args['before_widget']; 
                if (isset($instance['title']) && !empty($instance['title'])) {
                    echo $args['before_title'];
                    echo $instance['title'];
                    echo $args['after_title'];
                }
                echo $form;
                echo $args['after_widget'];
            }   
        }

        function update($new_instance, $old_instance) 
        {
            $instance = array();
            if ( ! empty( $new_instance['title'] ) ) {
                $instance['title'] = (string) $new_instance['title'];
            }
            return $instance;
        }

        function form($instance)
        {
            $title = isset( $instance['title'] ) ? $instance['title'] : '';
        ?>
        <br /><br />
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>"> <br /><br />

        <?php
        }
    }
?>