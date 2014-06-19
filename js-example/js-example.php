<?php

/*
Plugin Name: JS Example
Description: Example plugin that demonstrates how to use javascript in wordpress.
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/

/*
 *  Load styles and scripts 
 * see reference:
 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */

function js_example_enqueue_scripts(){  
  
  $js_example_params = array('VALUE', 'Another VALUE');
  
  // you must register your script before you can pass parameters from the php side to the javascript side
  wp_register_script('jsexample', plugins_url('/js/js-example.js', __FILE__));
	// passing an object or array
  wp_localize_script('jsexample', 'js_example_params_object', $js_example_params);
  // passing a single value
  wp_localize_script('jsexample', 'js_example_params_single', 'text I just added from code');
  // this will add the js on any single page.
  wp_enqueue_script( 'jsexample', plugins_url('js-example') . '/js/js-example.js', array(), '1.0.0', true );
  
  // this is how you can load in dependencies.
  // see http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Default_Scripts_Included_and_Registered_by_WordPress
  // for a list of stuff included with wordpress
  // this example requires jquery-ui-dialog
  wp_enqueue_script( 'mytooltip', plugins_url('js-example') . '/js/js-dialog-example.js', array('jquery-ui-dialog'), '1.0.0', true );
  add_filter('the_content', 'js_example_dialog');
}
// these scripts get added to all pages due to the action of wp_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'js_example_enqueue_scripts' );

// to see this example, go to any single post page
function js_example_dialog($content){
  
  $dialog = '<div id="my-dialog" title="Basic dialog" style="display:none; z-index:99;">
  <p>This is the default dialog which is useful for displaying information. This had a dependency of jquery-ui-dialog</p>
  </div>';
  
  return  $dialog . $content;  
}

function load_custom_wp_admin_style($hook) {
  // you can conditionally load different js on specific screens based on the hook
  switch($hook){
    case 'index.php':
      // this will add the js on any single page.
      // notice that there is more than one way to reference a file using plugins_url
      wp_enqueue_script( 'jsadminexample', plugins_url('js-example') . '/js/js-admin-example.js', array(), '1.0.0', true );
    break;
    case 'edit.php':
      wp_enqueue_script( 'jseditexample', plugins_url('js-example') . '/js/js-edit-example.js', array(), '1.0.0', true );
    break;
    case 'post.php':       
      wp_enqueue_script( 'jspostexample', plugins_url('js-example') . '/js/js-post-example.js', array(), '1.0.0', true );
    break;
  }
}
//http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );