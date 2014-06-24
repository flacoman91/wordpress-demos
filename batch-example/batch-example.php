<?php

/*
Plugin Name: Batch Example
Description: An example how to perform long running batch processes in Wordpress. 
Version: 1.0
Author: Richard Dinh
*/

add_action('admin_menu', 'batch_example_add_admin_menu'); // Add batch process capability
add_action('admin_enqueue_scripts', 'batch_example_admin_enqueues'); // Plugin hook for adding CSS and JS files required for this plugin

// see http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
add_action('wp_ajax_batchexamplejob', 'batch_example_ajax_process_job'); // Hook to implement AJAX request

// Register the management page
function batch_example_add_admin_menu() {
  add_options_page('Batch Example', 'Batch Example', 'manage_options', 'batch-example', 'batch_example_interface');	
}

/**
 * Admin user interface
 *
 * Most of the code in this function is copied from -
 * Regenerate Thumbnails plugin (http://www.viper007bond.com/wordpress-plugins/regenerate-thumbnails/)
 * code borrowed from 
 * https://wordpress.org/plugins/auto-post-thumbnail/
 * @return void
 */
function batch_example_interface() {
    global $wpdb;
?>
<div>
    <div style="margin-right:260px;">
        <div style='float:left; width: 100%'>
            <div id="message" class="updated fade" style="display:none"></div>

            <div class="wrap">
                <h2>Batch Example</h2>

<?php
                // If the button was clicked
                    if ( !empty($_POST['batch-example']) ) {
                        // Capability check
                        if ( !current_user_can('manage_options') )
                            wp_die('Cheatin&#8217; uh?');

                        // Form nonce check
                        check_admin_referer( 'batch-example' );

                        // for example you can iterate through all of your posts and do stuff.
                        // you can also iterate through another db table with stuff to do.
                        // Get id's of all the published posts 
                        $query = "SELECT * FROM {$wpdb->posts} p where p.post_status = 'publish'
                            and p.post_type = 'post' order by p.ID desc";
                        $posts = $wpdb->get_results($query);

                        if (empty($posts)) {
                            echo '<p>Currently there are no published posts available to do expensive operations.</p>';
                        } else {							
                            echo '<p>We are chugging along. Please be patient!</p>';
                            // Generate the list of IDs
                            $ids = array();
                            
                            $count = 100; //count( $posts );
                            for ( $i=0; $i<$count; $i++ ){
                                $ids[] = $i; // $post->ID;
                            }
                            $ids = implode( ',', $ids );

                            
?>
                <noscript><p><em>You must enable Javascript in order to proceed!</em></p></noscript>

                <div id="batch-example-bar" style="position:relative;height:25px;">
                    <div id="batch-example-bar-percent" style="position:absolute;left:50%;top:50%;width:50px;margin-left:-25px;height:25px;margin-top:-9px;font-weight:bold;text-align:center;"></div>
                </div>

                <script type="text/javascript">
                    // <![CDATA[
                    jQuery(document).ready(function($){
                        var i;
                        var rt_images = [<?php echo $ids; ?>];
                        var rt_total = rt_images.length;
                        var rt_count = 1;
                        var rt_percent = 0;

                        $("#batch-example-bar").progressbar();
                        $("#batch-example-bar-percent").html( "0%" );

                        function batchExampleJob( id ) {
                            $.post( "admin-ajax.php", { action: "batchexamplejob", id: id }, function() {
                                rt_percent = ( rt_count / rt_total ) * 100;
                                $("#batch-example-bar").progressbar( "value", rt_percent );
                                $("#batch-example-bar-percent").html( Math.round(rt_percent) + "%" );
                                rt_count = rt_count + 1;

                                if ( rt_images.length ) {
                                    batchExampleJob( rt_images.shift() );
                                } else {
                                    $("#message").html("<p><strong><?php echo esc_js( sprintf('All done! Processed %d tasks.', $count ) ); ?></strong></p>");
                                    $("#message").show();
                                }

                            });
                        }

                        batchExampleJob( rt_images.shift() );
                    });
                // ]]>
                </script>
<?php
                    }
                    } else {
?>

                <p>Use this tool to perform batch processes</p>
                <p>If the script stops executing for any reason, just <strong>Reload</strong> the page and it will continue from where it stopped.</p>

                <form method="post" action="">
                <?php wp_nonce_field('batch-example') ?>
                    <p><input type="submit" class="button hide-if-no-js" name="batch-example" id="batch-example" value="Do Stuff!" /></p>
                    <noscript><p><em>You must enable Javascript in order to proceed!</em></p></noscript>
                </form>                
            <?php } ?>
            </div>
        </div>        
    </div>
</div>
<?php
} //End batch_example_interface()

/**
 * Add our JS and CSS files
 *
 * @param $hook_suffix
 * @return void
 */
function batch_example_admin_enqueues($hook_suffix) {
    if ( 'settings_page_batch-example' != $hook_suffix ) {
        return;
    }

    // include wordpress jquery ui
    // see http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Default_Scripts_Included_and_Registered_by_WordPress
		wp_enqueue_script( 'jquery-ui-progressbar' );
    // add the style for jquery progress bar.
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
} //End batch_example_admin_enqueues

/**
 * Process single post to generate the post thumbnail
 *
 * @return void
 */
function batch_example_ajax_process_job() {
    if ( !current_user_can( 'manage_options' ) ) {
        die('-1');
    }

    $id = absint( $_POST['id'] );

    if ( empty( $id ) ) {
        die('-1');
    }

    set_time_limit( 60 );

    // Pass on the id to our 'expensive operation' callback function.
    batch_example_expensive_operation($id);

    die(-1);
} //End batch_example_ajax_process_job()

/**
 * Function that could timeout if it was in a big loop
 */
function batch_example_expensive_operation($id)
{
    global $wpdb;
    // do expensive work here that may timeout if we do it repeatedly.
    // you can do various things here.
    
    // for instance, if you have another database containing posts or some other content, you can 
    // create posts one at a time here.
    // you can also do a migration, etc
    
    sleep(1);
    return;   
}// end batch_example_expensive_operation()
