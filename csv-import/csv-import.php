<?php 
/* 
Plugin Name: CSV Import
Description: demo plugin that demonstrates how to do file upload from admin page. and process it for csv import
Author: Richard Dinh
the base of this plugin is based off of the file-upload-example
* this code is a demo only. You really should do more validation and stuff
*/

// add the admin options page
add_action('admin_menu', 'csv_import_admin_add_page');

function csv_import_admin_add_page() {
	  add_options_page('CSV Import', 'CSV Import', 'manage_options', 'csv-import', 'csv_import_options_page');	

}

function csv_import_options_page() {
	  if ( empty( $_FILES ) ){
?>
    <div>
        <h2>Upload a csv file here to import categories</h2>
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
              
              $csv = array_map('str_getcsv', file( $movefile['file'] ) );
              
              // the file should be a csv of categories. 
              $cnt =0;
              foreach($csv as $row){
                $my_cat = array('cat_name' => $row[0], 'category_description' => $row[1], 'category_nicename' => $row[2], 'category_parent' => '');
              // Create the category
                $my_cat_id = wp_insert_category($my_cat);                
                
                if($my_cat_id > 0)
                  $cnt++;
              }
              
              echo "$cnt categories added";
              
              // here you can do some stuff with this
          } else {
              echo "Possible file upload attack!\n";
          }
    }
}