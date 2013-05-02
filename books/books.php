<?php
/*
 * Plugin Name: Books Example
 * Description: Simple hacked together books example to demo custom post type, form input
 * and the wp nonce.
 */
function books_init() {
  $labels = array(
    'name' => 'Books',
    'singular_name' => 'Book',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Book',
    'edit_item' => 'Edit Book',
    'new_item' => 'New Book',
    'all_items' => 'All Books',
    'view_item' => 'View Book',
    'search_items' => 'Search Books',
    'not_found' =>  'No books found',
    'not_found_in_trash' => 'No books found in Trash',
    'parent_item_colon' => '',
    'menu_name' => 'Books'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'book' ),
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  );

  register_post_type( 'book', $args );
}
add_action( 'init', 'books_init' );

//add filter to ensure the text Book, or book, is displayed when user updates a book

function book_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['book'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Book updated. <a href="%s">View book</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'your_text_domain'),
    3 => __('Custom field deleted.', 'your_text_domain'),
    4 => __('Book updated.', 'your_text_domain'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Book restored to revision from %s', 'your_text_domain'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Book published. <a href="%s">View book</a>', 'your_text_domain'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Book saved.', 'your_text_domain'),
    8 => sprintf( __('Book submitted. <a target="_blank" href="%s">Preview book</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>', 'your_text_domain'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Book draft updated. <a target="_blank" href="%s">Preview book</a>', 'your_text_domain'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'book_updated_messages' );

//display contextual help for Books

function codex_add_help_text( $contextual_help, $screen_id, $screen ) {
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'book' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a book:', 'your_text_domain') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.', 'your_text_domain') . '</li>' .
      '<li>' . __('Specify the correct writer of the book.  Remember that the Author module refers to you, the author of this book review.', 'your_text_domain') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the book review to be published in the future:', 'your_text_domain') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.', 'your_text_domain') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', 'your_text_domain') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:', 'your_text_domain') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>', 'your_text_domain') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'your_text_domain') . '</p>' ;
  } elseif ( 'edit-book' == $screen->id ) {
    $contextual_help =
      '<p>' . __('This is the help screen displaying the table of books blah blah blah.', 'your_text_domain') . '</p>' ;
  }
  return $contextual_help;
}
add_action( 'contextual_help', 'codex_add_help_text', 10, 3 );

function books_help_tab() {
	global $post_ID;
	$screen = get_current_screen();

	if( isset($_GET['post_type']) ) $post_type = $_GET['post_type'];
	else $post_type = get_post_type( $post_ID );

	if( $post_type == 'book' ) :

		$screen->add_help_tab( array(
			'id' => 'you_custom_id', //unique id for the tab
			'title' => 'Custom  Help', //unique visible title for the tab
			'content' => '<h3>Help Title</h3><p>Help content</p>',  //actual help text
		));

	endif;

}

add_action('admin_head', 'books_help_tab');

add_action( 'add_meta_boxes', 'book_add_custom_box' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'book_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'book_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function book_add_custom_box() {
    $screens = array( 'book' );
    foreach ($screens as $screen) {
        add_meta_box(
            'book_sectionid',
            __( 'My Post Section Title', 'book_textdomain' ),
            'book_inner_custom_box',
            $screen
        );
    }
}

/* Prints the box content */
function book_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'book_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
  $value = get_post_meta( $post->ID, '_my_meta_value_key', true );
  echo '<label for="book_new_field">';
       _e("Description for this field", 'book_textdomain' );
  echo '</label> ';
  echo '<input type="text" id="book_new_field" name="book_new_field" value="'.esc_attr($value).'" size="25" />';
}

/* When the post is saved, saves our custom data */
function book_save_postdata( $post_id ) {

	if( !isset($_POST['post_type']) )
		return;
  // First we need to check if the current user is authorised to do this action.
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['book_noncename'] ) || ! wp_verify_nonce( $_POST['book_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Thirdly we can save the value to the database

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  //sanitize user input
  $mydata = sanitize_text_field( $_POST['book_new_field'] );

  // Do something with $mydata
  // either using
  add_post_meta($post_ID, '_my_meta_value_key', $mydata, true) or
    update_post_meta($post_ID, '_my_meta_value_key', $mydata);
  // or a custom table (see Further Reading section below)
}
