<?php
/*
Plugin Name: Widget Dropdown Demo
Description: Demo widget to show how to save settings to a widget using a dropdown form element.
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/

/*
 * Code inpired by HaneD at: http://wordpress.stackexchange.com/questions/73956/using-wp-dropdown-categories-in-widget-options/96077#96077
 */
add_action( 'widgets_init', 'widget_dropdown_demo' );


function widget_dropdown_demo() {
	register_widget( 'Widget_Dropdown_Demo' );
}

class Widget_Dropdown_Demo extends WP_Widget {

    function Widget_Dropdown_Demo() {
        $widget_ops = array( 'classname' => 'agriquip', 'description' => __('Displays Demo Form with dropdown selector', 'agriquip') );
        $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'agriquip-widget' );
        $this->WP_Widget( 'agriquip-widget', __('Dropdown Demo', 'agriquip'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

//        $title = apply_filters('widget_title', $instance['title'] );
//        $tract_id = isset( $instance['exc_equipment_cat'] ) ? $instance['exc_equipment_cat'] : false;
//        $tract = wp_list_pluck(get_terms('exc_equipment_cat', array('parent' => 3)), 'slug');
//        $tractparent = get_term_by('id','3', 'exc_equipment_cat');
//        $tractparent = $tractparent->slug;


        echo $before_widget;

		print_r($instance);

		// do stuff here
        echo $after_widget;
    }


   function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['name'] = strip_tags( $new_instance['name'] );
		$instance['kwtax'] = $new_instance['kwtax'];

		return $instance;

    }

    function form( $instance ) {

        //Set up some default widget settings.
        $defaults = array( 'title' => __('Dropdown Demo', 'agriquip'), 'kwtax' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'agriquip'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
        </p>

          <p>
            <select id="<?php echo $this->get_field_id('kwtax'); ?>" name="<?php echo $this->get_field_name('kwtax'); ?>" class="widefat" style="width:100%;">
                <?php foreach(get_terms('category','parent=0&hide_empty=0') as $term) { ?>
                <option <?php selected( $instance['kwtax'], $term->term_id ); ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                <?php } ?>
            </select>
        </p>

    <?php
    }
}



?>
