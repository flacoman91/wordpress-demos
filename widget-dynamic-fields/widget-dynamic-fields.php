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
	register_widget( 'Dynamic_Widget_Dropdown_Demo' );
}

class Dynamic_Widget_Dropdown_Demo extends WP_Widget {

    function Dynamic_Widget_Dropdown_Demo() {
        $widget_ops = array( 'classname' => 'agriquip', 'description' => __('Displays Demo Form with dropdown selector', 'agriquip') );
        $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'agriquip-widget' );
        $this->WP_Widget( 'agriquip-widget', __('Dynamic Dropdown Demo', 'agriquip'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        
        echo $before_widget;
        print_r($instance);
        // do stuff here you shoudl theme this or modify the display or something.
        echo $after_widget;
    }


   function update( $new_instance, $old_instance ) {     
    $instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['name'] = strip_tags( $new_instance['name'] );
		$instance['kwtax'] = $new_instance['kwtax'];
    for($i=0; $i<100; $i++){
      // save off any field instances.
      $instance['dynamic-fields-' . $i] = $new_instance['dynamic-fields-' . $i];
    }
		return $instance;

    }

    function form( $instance ) {

        //Set up some default widget settings.
        $defaults = array( 'title' => __('Dropdown Demo', 'agriquip'), 'kwtax' => '');
        
        // set up fake dynamic fields in the $defaults array
        // can't actually make these fields dynamic.
        // see http://stackoverflow.com/questions/16084211/add-dynamic-input-field-into-widget-form-in-wordpress-admin
        
        for($i= 0; $i<100; $i++){
          $defaults['dynamic-fields-' . $i] = '';
        }
        
        $instance = wp_parse_args( (array) $instance, $defaults );       
        $widget_add_id = $this->id . "-add";

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'agriquip'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
        </p>
    <!-- inline javascript -->
		<script>
    var $ =jQuery.noConflict();
    $(document).ready(function(e) {
        $(".<?php echo $widget_add_id; ?>" ).bind('click', function(e) {  
          // unhide the following if there is empty value in it.:          
          $.each($(".<?php echo $widget_add_id; ?>-input-containers").children(), function(){
            //console.log($(this).value());
            if($(this).val() == ''){
              $(this).show();
              return false; // bust out of the each loop if we showed a field
            }
          });
        });
        
        // need to add some logic to reset a field and hide an existing field.
    });
    </script>
		<p>
			<div id="here">Fake dynamic fields</div>
		<div class="<?php echo $widget_add_id; ?>">ADD</div>
    <div class="<?php echo $widget_add_id; ?>-input-containers">
    <!-- dynamic fields here -->
    <?php for( $i =0; $i<100; $i++){ 
      // here we hide the field if there is no value or it is empty
      ?>          
      <input id="<?php echo $this->get_field_id( 'dynamic-fields-' . $i ); ?>" 
             name="<?php echo $this->get_field_name( 'dynamic-fields-' . $i );?>" 
             value="<?php echo $instance['dynamic-fields-' . $i]; ?>" 
             <?php if(empty($instance['dynamic-fields-' . $i])){ echo 'style="display:none;"';  } ?>>
           
    <?php }
    ?>
    </div>
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
