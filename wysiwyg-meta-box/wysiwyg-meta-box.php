<?php

/*
Plugin Name: WYSIWYG Meta Box
Description: Demo widget to show how to display a meta box
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/

// please refer to 
// http://codex.wordpress.org/Function_Reference/wp_editor
// http://stackoverflow.com/questions/3493313/how-to-add-wysiwyg-editor-in-wordpress-meta-box

add_action( 'add_meta_boxes', 'wysiwyg_metabox_example_add' );              
function wysiwyg_metabox_example_add(){  
    
    add_meta_box('another_wysiwyg_meta_box', 'Another WYSIWYG Meta Box', 'wysiwyg_metabox_example_output_function');
}

function wysiwyg_metabox_example_output_function( $post ){
  
    //so, dont need to use esc_attr in front of get_post_meta
    $value =  get_post_meta($post->ID, 'wyiwyg_meta_value' , true ) ;
    
    $settings = array('textarea_name'=>'MyInputNAME');
    
    wp_editor( htmlspecialchars_decode($value), 'wysiwyg_metabox_id', $settings );
}


function wysiwyg_metabox_example_save_postdata( $post_id ){                 
    $data = '';
    $value =  get_post_meta($post_id, 'wyiwyg_meta_value' , true ); 
    if ( !empty( $_POST['MyInputNAME'] ) ){
        $data = htmlspecialchars( $_POST['MyInputNAME'] );    
        update_post_meta( $post_id, 'wyiwyg_meta_value', $data );
    } else if( !empty($value) ) {
      // some cleanup. delete the value if not there.
        delete_post_meta( $post_id, 'wyiwyg_meta_value' );
    }
    
}
add_action( 'save_post', 'wysiwyg_metabox_example_save_postdata' );