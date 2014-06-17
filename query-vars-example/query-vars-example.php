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
get_query_var() only retrieves public query variables that are recognized by WP_Query. This means that if you create your own custom URLs with their own query variables, get_query_var() will not retrieve them without some further work (see below).

Custom Query Vars
In order to be able to add and work with your own custom query vars that you append to URLs (eg: "http://mysite.com/some_page/?my_var=foo" - for example using add_query_arg()) you need to add them to the public query variables available to WP_Query. These are built up when WP_Query instantiates, but fortunately are passed through a filter 'query_vars' before they are actually used to populate the $query_vars property of WP_Query.

So, to expose your new, custom query variable to WP_Query hook into the 'query_vars' filter, add your query variable to the $vars array that is passed by the filter, and remember to return the array as the output of your filter function. See below:
 */
function query_vars_example_filter( $vars ){
  $vars[] = "my_var";
  return $vars;
}

add_filter( 'query_vars', 'query_vars_example_filter' );