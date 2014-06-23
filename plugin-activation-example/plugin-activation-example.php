<?php
/*
Plugin Name: Plugin Activation Example
Description: Just a simple plugin to demonstrate how to use the activation hook, in this example we create categories upon activation
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/


function plugin_activate_example_activate() {
  
  // Activation code here...
  // let's create some categories upon activation 
  //http://codex.wordpress.org/Function_Reference/wp_insert_category
  for( $i =0; $i<5; $i++ ){
    $catarr = array( 'cat_name' => 'My Category' . $i, 'category_description' => 'A Cool Category' . $i, 'category_nicename' => 'category-slug' . $i, 'category_parent' => '' );
    wp_insert_category( $catarr ); 
  }
  
  // should be root path of the wp install
  $wordpress_path = get_home_path();
  require_once( $wordpress_path . '/wp-load.php' ); //not sure if this line is needed
  //activate_plugin() is here:
  require_once( $wordpress_path . '/wp-admin/includes/plugin.php' );
  
  // we're going to activate our plugins that are dependencies
  $plugins = array("filters-example",  "js-example", "shortcode-example" );
  // see
  //http://wordpress.stackexchange.com/questions/62967/why-activate-plugin-is-not-working-in-register-activation-hook
  foreach ($plugins as $plugin){
    $plugin_path = $wordpress_path . 'wp-content/plugins/' . $plugin . '/' . $plugin . '.php';
    
    if( file_exists( $plugin_path ) && is_plugin_inactive( $plugin . '/' . $plugin . '.php' )){ 
      // just double check that the plugin exists and unactivated      
      add_action('update_option_active_plugins', 'plugin_activation_dependencies');     
    }
  }
  
}
register_activation_hook( __FILE__, 'plugin_activate_example_activate' );

function plugin_activation_dependencies(){
  // we're going to activate our plugins
  $plugins = array("filters-example",  "js-example", "shortcode-example" );
  foreach ($plugins as $plugin){
    activate_plugin( $plugin . '/' . $plugin . '.php') ;    
  }
}