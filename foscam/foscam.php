<?php

/**
 * @package foscam
 * @version 0.1
 */
/*
Plugin Name: Foscam
Description: This plugin provides support for a foscam video stream.
Author: Richard Dinh
Version: 0.1
Author URI: http://www.dinhdesigns.com
*/

function foscam_func( $atts ) {	
	
	$options = foscam_get_options();
	$url = $options['url'];	
	
	return "<img src='$url' name='refresh' id='refresh' onload='reload(this)' onerror='reload(this)'>";	
}
add_shortcode('foscam', 'foscam_func');

function foscam_get_options(){
	$option = array();
	$foscam_options = get_option( 'foscam-options' );
	
	$url = !empty( $foscam_options['url'] ) ? $foscam_options['url'] : 'http://cw0101.myfoscam.org:20064'; 
	$usr = !empty( $foscam_options['usr'] ) ? $foscam_options['usr'] : 'user';
	$pwd = !empty( $foscam_options['pwd'] ) ? $foscam_options['pwd'] : 'user';
	$refresh = !empty( $foscam_options['refresh'] ) ? $foscam_options['refresh'] : '5000';

	//http://cw0101.myfoscam.org:20064/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=user&pwd=user
	$option['url'] = $url . '/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=' . $usr . '&pwd=' . $pwd;
	$option['refresh'] = $refresh;
	
	return $option;
}

function foscam_add_script(){
	
	$foscam_options = foscam_get_options();
		
	wp_register_script('foscam', plugins_url('foscam.js', __FILE__) );	
	// how you pass parameters from the php wordpress side to the javascript file
	wp_localize_script( 'foscam', 'foscam_options', $foscam_options );	
	wp_enqueue_script( 'foscam', plugins_url('foscam.js', __FILE__), null, '1.0', false );
}

add_action('wp_enqueue_scripts', 'foscam_add_script');


// need to create a menu page for the options
// url
// port
// usr name
// password
// refresh rate
// add in some remote controls!

/**
 * Code for configuration options for the foscam.
 */


add_action( 'admin_menu', 'foscam_menu' );

/** Step 1. */
function foscam_menu() {
	add_options_page( 'Foscam Options', 'Foscam', 'manage_options', 'foscam-options', 'foscam_options' );
}

/** Step 3. */
function foscam_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$options = array('url' => 'hostname or ip address', 'usr' => 'username', 'pwd' => 'password', 'refresh'=> 'refresh rate (millseconds)');

	if( isset( $_POST['submit'] ) && ( ! empty( $_POST['foscam_nonce_field'] ) && wp_verify_nonce( $_POST['foscam_nonce_field'], 'update_foscam_action' ) ) ) {
		echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
				<p><strong>Settings saved.</strong></p>
				</div>';
		
			$foscam_options = get_option( 'foscam-options' );	
			if ( !is_array( $foscam_options ) ){
				unset($foscam_options);
				$foscam_options = array();
			}
				
			foreach( $options as $option => $optiontitle ){
				$value = $_POST[ 'foscam-options-' . $option ];
				$foscam_options[ $option ] = isset( $value ) ? $value : '';					
			}
			
			update_option( 'foscam-options', $foscam_options );
	}
	?>
		<div class="wrap">
		<h2>Foscam Configuration Options</h2>
		<form method="post">
			<?php wp_nonce_field( 'update_foscam_action', 'foscam_nonce_field' ); ?>
			<table class="form-table">
				<tbody>

					<?php
					$foscam_options = get_option( 'foscam-options' );
					foreach ( $options as $option => $optiontitle ):
						?>
						<tr>
							<th><label> <?php echo $optiontitle ?>: </label></th>
							<td><input type="text" name="foscam-options-<?php echo $option; ?>" id="foscam-options-<?php echo $option; ?>" class="regular-text" value="<?php if ( !empty( $foscam_options[$option] ) ) echo $foscam_options[$option]; ?>"></td>
						</tr>

						<?php
					endforeach;
					?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		
	</div>
<?php
}