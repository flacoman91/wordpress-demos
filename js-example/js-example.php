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
  // this will add the js on any single page.
  wp_enqueue_script( 'script-name', plugins_url('js-example') . '/js/js-example.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'js_example_enqueue_scripts' );