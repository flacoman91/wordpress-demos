<?php
/*
Plugin Name: Widget Dynamic Fields
Description: Demo widget to show how to save dynamic field settings to a widget.
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/

/*
 * http://wordpress.stackexchange.com/questions/89110/adding-widget-form-fields-dynamically
 */
add_action( 'widgets_init', 'widget_dynamic_fields' );


function widget_dynamic_fields() {
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

		<script>
    var $ =jQuery.noConflict();
    $(document).ready(function() {
        $(".add").click(function() {
			$(this).append(document.createElement("input"));
            return false;
        });
        $(".remove").live('click', function() {
            $(this).parent().remove();
        });
    });
    </script>
		<p>
			<div id="here">testing</div>
		<div class="add">click me </div>


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
