<?php
/* 
Plugin Name: Query Vars Example
Description: Example plugin that demonstrates how to create your own custom query variables in WordPress
Version: 1.0
Author: Richard Dinh
License: GPLv2
 
*/

//http://codex.wordpress.org/Function_Reference/get_query_var

/**
get_query_var() only retrieves public query variables that are recognized by WP_Query. 
This means that if you create your own custom URLs with their own query variables, 
get_query_var() will not retrieve them without some further work (see below).

Custom Query Vars
In order to be able to add and work with your own custom query vars that you 
append to URLs (eg: "http://mysite.com/some_page/?my_var=foo" - for example 
using add_query_arg()) you need to add them to the public query variables 
available to WP_Query. These are built up when WP_Query instantiates, but 
fortunately are passed through a filter 'query_vars' before they are actually 
used to populate the $query_vars property of WP_Query.

So, to expose your new, custom query variable to WP_Query hook into the 
'query_vars' filter, add your query variable to the $vars array that is passed 
by the filter, and remember to return the array as the output of your filter function. See below:
 */
function query_vars_example_filter( $vars ){
  
  $vars[] = "my_var";
  
  // normally this will not work $_GET['my_var']
  // but now you can then get the queryvar using 
  // global $wp_query;
  //$my_var = $wp_query->query_vars['my_var'];
  return $vars;
}

add_filter( 'query_vars', 'query_vars_example_filter' );

// go to any post in your browser and append the query string my_var=somevalue to see this in action.
// example: http://wordpress.local/?p=4&my_var=123452&someundeclaredvalue=asadsgsdffds
function query_vars_example_content( $content ){
  global $wp_query;
  
  $my_var = isset( $wp_query->query_vars['my_var'] ) ? $wp_query->query_vars['my_var'] : '';
  
  //this was not in the filter, so you wont see it appear. 
  $someundeclaredvalue = isset( $wp_query->query_vars['someundeclaredvalue'] ) ? $wp_query->query_vars['someundeclaredvalue'] : '';
  
  return "the var is >>>>" . $my_var . "<<<< and you should not be able to get any other query vars such as someundeclaredvalue >>>" . $someundeclaredvalue . "<<<"
          . $content;
}

add_filter( 'the_content', 'query_vars_example_content');
  