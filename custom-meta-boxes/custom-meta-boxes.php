<?php
/*
Plugin Name: Custom Meta Boxes 
Description: Just a demo on how to add an a text field or dropdown for authors in a post edit screen
Version: 1.0
Author: Richard Dinh
*/

/**
 * demonstrates how to remove an existing meta box since we are adding our own.
 */
function ao_remove_authors_box() {	
	remove_meta_box( 'authordiv', 'post', 'normal' );	
}

add_action( 'do_meta_boxes', 'ao_remove_authors_box' );
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function author_override_add_meta_box() {
	
	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'author_override_sectionid',
			__( 'Author Override', 'author_override_textdomain' ),
			'author_override_meta_box_callback',
			$screen,
			'normal',
			'high'
		);
	}
}

add_action( 'do_meta_boxes', 'author_override_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function author_override_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'author_override_meta_box', 'author_override_meta_box_nonce' );

	$users = get_users();
	
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_author_override_value_key', true );
	$select_value = get_post_meta( $post->ID, '_instyle_author_override_key', true );
	
	echo '<p><label for="author_override_new_field">Author</label>';
	echo '<select name="instyle-author-override" id="instyle-author-override">';
	echo '<option value=""></option>';
	foreach($users as $u){
		$friendly_name = $u->data->display_name;	
		echo '<option value="'. esc_attr( $friendly_name ) . '"' , $select_value == $friendly_name ? ' selected="selected"' : '',  '>' . esc_html( $friendly_name ). '</option>';
	}
	
	echo '</select></p>';
	echo '<label>';
	_e( 'Author name to display', 'author_override_textdomain' );	
	echo '</label>';
	echo '<input type="text" id="author_override_new_field" name="author_override_new_field" value="' . esc_attr( $value ) . '" size="50" />';
	echo '<p><label>*Takes precedence over select box</label></p>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function author_override_save_meta_box_data( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['author_override_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['author_override_meta_box_nonce'], 'author_override_meta_box' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, its safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( isset( $_POST['author_override_new_field'] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['author_override_new_field'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_author_override_value_key', $my_data );
	}
	// this is the dropdown meta box
	if ( isset( $_POST['instyle-author-override'] ) ){
		
		$my_data = sanitize_text_field( $_POST['instyle-author-override'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_instyle_author_override_key', $my_data );
	}	
}
add_action( 'save_post', 'author_override_save_meta_box_data' );