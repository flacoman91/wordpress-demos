<?php
/*
  Plugin Name: Customize Theme Menu Mod
  Description: Allows you to add custom css features for wordpress. This is more of an example of how to add stuff to the customize menu
  Version: 1.2
  Author: Richard Dinh
  Author URI: http://www.dinhdesigns.com
  License: GPLv2
 */

add_action( 'customize_register', 'rd_customize_theme' );

function rd_customize_theme( $wp_customize ) {

	$wp_customize->add_section( 'rd_customize_theme_settings', array(
		'title' => 'Custom Settings',
		'priority' => 35,
	) );


	$wp_customize->add_setting( 'rg_font_h1', array(
		'default' => '',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
	) );


	// todo: convert to JSON decode
	$font1 = array(
		'h1' => 'Signika',
		'h2' => 'Noto Sans',
		'h3' => 'Noto Sans',
		'a'  => 'Signika'
	);
	$font2 = array(
		'h1' => 'Patua One',
		'h2' => 'Noto Serif',
		'h3' => 'Noto Serif',
		'a'  => 'Patua One'
	);

	// json encode this so the javascript file can take these parameters
	$font1_json = json_encode( $font1 );
	$font2_json = json_encode( $font2 );

	// this adds the option to the customize menu
	$wp_customize->add_control( 'rg_font_h1', array(
		'label' => 'Select Font:',
		'section' => 'rd_customize_theme_settings',
		'type' => 'select',
		'choices' => array(
			$font1_json => 'Sans-serif',
			$font2_json => 'Serif',
		),
	) );
}

add_action( 'customize_preview_init', 'rd_customize_customize_preview_js' );

function rd_customize_customize_preview_js() {
	wp_enqueue_script( 'rg-network-customizer', plugins_url() . '/customize-theme-menu-mod/customize-theme-menu-mod.js', array( 'customize-preview' ), false, true );
}


add_action( 'wp_head', 'rd_customize_load_fonts' );

function rd_customize_load_fonts() {
	// inline load the css for the google fonts
	echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Signika:400,600,700|Noto+Sans:400,400italic,700,700italic|Patua+One|Noto+Serif:400,400italic,700,700italic">';
}

add_action( 'wp_head', 'rd_customize_add_customizer_css' );

add_action( 'customize_controls_print_styles', 'rd_customize_add_customizer_css' );

/**
 * This function actually spits out the css inline in the head. It will modify the fonts of whatever is selected
 */
function rd_customize_add_customizer_css() {
	// need to do logic to figure out which set they picked.
	// Set 1:
	/* H1 - FONT: Signika

	 * H2 - FONT: Noto Sans - bold
	 * H3 - FONT: Noto Sans - regularbox
	 *
	 */

	$font1 = array(
		'h1' => 'Signika',
		'h2' => 'Noto Sans',
		'h3' => 'Noto Sans',
		'a'	 => 'Signika'
	);
	$font2 = array(
		'h1' => 'Patua One',
		'h2' => 'Noto Serif',
		'h3' => 'Noto Serif',
		'a'  => 'Patua One'
	);

	$font1_json = json_encode( $font1 );
	$font2_json = json_encode( $font2 );

	switch ( get_theme_mod( 'rg_font_h1' ) ) {
		case $font1_json:
			$font_family_h1 = 'Signika';
			$font_family_h2_h3 = 'Noto Sans';
			break;
		case $font2_json:
			$font_family_h1 = 'Patua One';
			$font_family_h2_h3 = 'Noto Serif';
			break;
		default:
			$font_family_h1 = 'sans-serif';
			$font_family_h2_h3 = 'serif';
			break;
	}
	?>
	<style>

		/* you can add whatever you want in here */

		h1, a { font-family: <?php echo $font_family_h1; ?>, serif; }
		h2, h3, h4 {
			font-family: <?php echo $font_family_h2_h3; ?>, serif;
		}


	</style>
	<?php
}