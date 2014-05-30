<?php
/*
Plugin Name: Foscam
Description: This plugin provides support for a foscam video stream through the use of shortcodes. With this shortcode, you can embed a whole bunch of camera feeds onto your wordpress blog
Author: Richard Dinh
Version: 0.1
Author URI: http://www.dinhdesigns.com
*/

//[foscam url='http://cw0101.myfoscam.org:20064' usr='user' pwd='user' refresh='5000']
function foscam_func( $atts ) {	
	
	$a = shortcode_atts( array(
        'url' => 'http://cw0101.myfoscam.org:20064',
        'usr' => 'user',
		'pwd' => 'user',
		'refresh' => '1000'
    ), $atts );

	
    $url = $a['url'];
	$usr = $a['usr'];
	$pwd = $a['pwd'];
	$refresh = $a['refresh'];
	
	$url = $url . '/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $usr . '&pwd=' . $pwd;
	$time = time();
	
	return "<img src='" . esc_url($url) . "' id='foscam-".esc_attr( $time ) ."' class='foscam' data-refresh='" . absint( $refresh ) . "' data-url='". esc_url( $url ) ."'>";	
}
add_shortcode('foscam', 'foscam_func');


function foscam_add_script(){		
	wp_register_script('foscam', plugins_url('foscam.js', __FILE__), array('jquery') );	
	wp_enqueue_script( 'foscam', plugins_url('foscam.js', __FILE__), array('jquery'), '1.0', false );
}

add_action('wp_enqueue_scripts', 'foscam_add_script');
