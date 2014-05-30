<?php
/*
  Plugin Name: Tiny MCE Addon
  Description: This plugin demonstrates one of the ways you can add on an extra button into your Tiny MCE WYSIWYG addon.
  Version: 1.2
  Author: Richard Dinh
  Author URI: http://www.dinhdesigns.com
  License: GPLv2
 */

add_action( 'init', 'tiny_mce_addon_buttons' );

function tiny_mce_addon_buttons() {
    add_filter( "mce_external_plugins", "tiny_mce_addon_add_buttons" );
    add_filter( 'mce_buttons', 'tiny_mce_addon_register_buttons' );
}

add_action( 'admin_enqueue_scripts', 'tiny_mce_addon_enqueue_scripts' );

function tiny_mce_addon_enqueue_scripts($hook){
	wp_enqueue_script('jquery-ui-dialog');
	
	// enqueue the css so the modal looks somewhat decent.
	wp_register_style( 'tiny_mce_addon_style', plugins_url() . '/tiny-mce-addon/tiny-mce-addon.css', false, '1.0.0' );
	wp_enqueue_style( 'tiny_mce_addon_style' );	
}

// you will need to define your own shortcode if you are not using Jetpack, ETC.
// you can delete this shortcode section, or modify it to display your own.
add_shortcode('youtube', 'tiny_mce_addon_youtube');

// shortcode part to extract the urls and other params
function tiny_mce_addon_youtube( $atts ){
	// default values if the users did not pass any onto us.
	$a = shortcode_atts( array(
		'url' => 'http://www.youtube.com/embed/n1Oj8oMFqUY',
		'height' => '315',
		'width' => '560',
	), $atts );

	$width = $a['width'];
	$height = $a['height'];
	$url = $a['url'];
	
	return "<iframe width='" . absint( $width ) . "' height='". absint( $height ) . "' src='" . esc_url( $url ) . "' frameborder='0' allowfullscreen></iframe>";
}

function tiny_mce_addon_add_buttons( $plugin_array ) {	
	// needs to match what we defined in tiny-mce-addon.js
    $plugin_array['tinymceYoutubeShortcode'] =   plugins_url() . '/tiny-mce-addon/tiny-mce-addon.js';
    return $plugin_array;
}

function tiny_mce_addon_register_buttons( $buttons ) {
    array_push( $buttons, 'tinymceYoutubeShortcode' );
    return $buttons;
}



// adding a filter to add a class to modify it before it hits the admin page.
// http://wordpress.stackexchange.com/questions/49773/how-to-add-a-class-to-meta-box
// hook is postbox_classes_{$page}_{$id}
add_filter( 'postbox_classes_post_tiny-mce-addon-button','tiny_mce_addon_add_metabox_classes' );

// we're going to hide the meta box from the admin, so we only see it 
function tiny_mce_addon_add_metabox_classes( $classes ) {
    array_push( $classes, 'hidden' );
    return $classes;
}

/**
 * Helper class for adding the image shortcode via the tinyMCE toolbar.
 *
 * @author InStyle
 */
class add_image_button {
	var $pluginname = "tinymceYoutubeShortcode";

	/**
	 * Initiate the main actions and filters for meta box related functionality.
	 *
	 * @return  void
	 */
	public function __construct() {
		// Add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
	}
	
	/**
	 * Add hidden Meta Boxes.
	 *
	 * @param string $post_type Current post type name.
	 * @param object $post Current post object.
	 * @return void
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( 'post' !== $post_type ) {
			return;
		}
		add_meta_box( 'tiny-mce-addon-button', 'tinymceYoutube', array( $this, 'display_image_meta_box' ), $post_type, 'normal', 'high' );
	}
	
	/**
	 * Display the curation meta box. This meta box actually is hidden on the page so it can be called by jquery dialog for our input
	 *
	 * @param object $post Current post object.
	 * @return void
	 */
	public function display_image_meta_box( $post ) {
	?>
		<div id="dialog-form-tinymceYoutube" title="Insert Youtube Video">
		<form id="tinymceYoutubeForm">
			<div id="playerIDHolder">
				<label for="playerID">Youtube URL:</label>
				<input type="text" name="playerID" id="youtube-url" placeholder='https://www.youtube.com/watch?v=ihCkVow47KQ'/>
			</div>		
			<div id="playerHeightHolder">
				<label for="playerHeight">Height:</label>
				<input type="text" name="playerHeight" id="youtube-height" placeholder='315'/>
			</div>
			<div id="playerWidthHolder">
				<label for="playerWidth">Width:</label>
				<input type="text" name="playerWidth" id="youtube-width" placeholder='560'/>
			</div>
		</form>
	  </div>
	<?php		
	}
	
	/**
	 * Wire in the button to tinyMCE
	 */
	function add_image_button() {
		
		add_filter( 'tiny_mce_version', array( &$this, 'change_tinymce_version' ) );
		add_action( 'init', array( &$this, 'addbuttons' ) );
	}

	/**
	 * Actually add buttons for editors
	 */
	function addbuttons() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( 'true' === get_user_option( 'rich_editing' ) ) {
			add_filter( "mce_external_plugins", array( $this, "add_tinymce_plugin" ), 5);
			add_filter( 'mce_buttons',          array( $this, 'register_button' ), 5);
		}
	}

	/**
	 * Register the new button
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	function register_button( $buttons ) {
		array_push( $buttons, "separator", 'tinymceYoutubeShortcode' );

		return $buttons;
	}

	/**
	 * Register the button's JS handler
	 *
	 * @param array $plugin_array
	 *
	 * @return array
	 */
	function add_tinymce_plugin( $plugin_array ) {
		$plugin_array[ $this->pluginname ] = get_bloginfo( 'stylesheet_directory' ) . '/js/bc-editor-plugin.js';

		return $plugin_array;
	}

	/**
	 * Increment the tinyMCE version for cache busting.
	 *
	 * @param int $version
	 *
	 * @return int
	 */
	function change_tinymce_version( $version ) {
		return ++$version;
	}

}

$tiny_mce_addon_button = new add_image_button();
