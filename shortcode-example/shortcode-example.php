<?php

/*
  Plugin Name:  Shortcode Example
  Description: Just an simple example on how to create a shortcode
  Version: 1.0
  Author: Richard Dinh
  Author URI: http://www.dinhdesigns.com
  License: GPLv2
 */

//usage: [foobar] 
function shortcode_example_func( $atts ){
	return "foo and bar";
}

// foobar is the shortcode,
// shortcode_example_func is the callback function that will process the shortcode
//The return value of a shortcode handler function is inserted into the post content 
//output in place of the shortcode macro. Remember to use return and not echo - anything 
//that is echoed will be output to the browser, but it won't appear in the correct place on the page.

add_shortcode( 'foobar', 'shortcode_example_func' );

// this is how you pass parameters in there
// [bartag foo="foo-value" bar="somevalue"]
function shortcode_example_bartag( $atts ) {
    // you can set default values in case the user did not type in values   
    //IMPORTANT TIP - Don't use camelCase or UPPER-CASE for your $atts attribute names
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );

    // consider doing some validation and escaping if what they can do might break
    // your site
    return "foo = {$a['foo']} and bar={$a['bar']}";
}
add_shortcode( 'bartag', 'shortcode_example_bartag' );