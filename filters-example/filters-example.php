<?php
/*
Plugin Name: Filters Example
Description: Just a simple plugin to demonstrate how to use a filter
Version: 1.0
Author: Richard Dinh
License: GPLv2
*/


/*
 * the_title is a filter applied to the post title retrieved from the database, 
 * prior to printing on the screen. In some cases (such as when the_title is used), 
 * the title can be suppressed by returning a falsey value (e.g. NULL, FALSE or the empty string) from the filter function.
 */


/**
 * This filter will just append something to the title. You can do more complicated stuff
 * in these filters since the ID is passed in. You can check category, author, etc based on post ID.
 * @param type $title
 * @param type $id
 * @return type
 */
function filters_example_title( $title, $id ) {
    // here we will just append the post id to show it was processed.
    return $title . " POST " . $id;
}

// this is a default filter included in wordpress
// the numbers are the end are the priority.  lower number means it runs earlier.
// last number is the number of accepted arguments.
add_filter( 'the_title', 'filters_example_title', 10, 2 );



// http://codex.wordpress.org/Plugin_API#Create_a_Filter_Function
// here is an example to strip out bad words in the comments
function filter_profanity( $content ) {
	$profanities = array('badword','alsobad','...');
	$content = str_ireplace( $profanities, '{censored}', $content );
	return $content;
}

add_filter( 'comment_text', 'filter_profanity' );


// say for instance, you have a filter that is defined somewhere, like the filter profanity one.
// you can remove that temporily if you wanted to allow certain people to view the bad words
// inside of your loop where you are executing the comments, you can have this
// remove_filter( comment_text', 'filter_profanity' );