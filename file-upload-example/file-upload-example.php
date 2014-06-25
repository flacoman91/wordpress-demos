<?php 
/* 
Plugin Name: File Upload Example
Description: demo plugin that demonstrates how to do file upload from admin page.
Author: Richard Dinh
//http://wordpress.stackexchange.com/questions/98375/how-to-upload-image-with-simple-form
this code is a demo only. You really should do more validation and stuff
*/

// add the admin options page
add_action('admin_menu', 'file_upload_example_admin_add_page');

function file_upload_example_admin_add_page() {
	  add_options_page('File Upload Example', 'File Upload Example', 'manage_options', 'file-upload-example', 'file_upload_example_options_page');	

}

function file_upload_example_options_page() {
	  if ( empty( $_FILES ) ){
?>
    <div>
        <h2>Upload a file here</h2>
        <form action="" method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('csv-import'); ?>

        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="save" value="save">
        </form>
    </div>
    <?php } 
    else{ 
      if ( ! function_exists( 'wp_handle_upload' ) ) 
          require_once( ABSPATH . 'wp-admin/includes/file.php' );
          $uploadedfile = $_FILES['file'];
          $upload_overrides = array( 'test_form' => false );
          $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
          if ( $movefile ) {
              echo "File is valid, and was successfully uploaded.\n";
              var_dump( $movefile );
              // here you can do some stuff with this
          } else {
              echo "Possible file upload attack!\n";
          }
    }
}