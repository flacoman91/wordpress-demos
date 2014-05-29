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

	/**
	* Customize Image Reloaded Class
	*
	* Extend WP_Customize_Image_Control allowing access to uploads made within
	* the same context
	* Declaring this inside the customize theme function due to scope issues
	* with wp bootloader.
	*
	*/
	class My_Customize_Image_Reloaded_Control extends WP_Customize_Image_Control {
		/**
		* Constructor.
		*
		* @since 3.4.0
		* @uses WP_Customize_Image_Control::__construct()
		*
		* @param WP_Customize_Manager $manager
		*/
		public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );

		}

		/**
		* Search for images within the defined context
		* If there's no context, it'll bring all images from the library
		*
		*/
		public function tab_uploaded() {
		$my_context_uploads = get_posts( array(
		    'post_type'  => 'attachment',
		    'meta_key'   => '_wp_attachment_context',
		    'meta_value' => $this->context,
		    'orderby'    => 'post_date',
		    'nopaging'   => true,
		) );

		?>

		<div class="uploaded-target"></div>

		<?php
		if ( empty( $my_context_uploads ) )
		    return;

		foreach ( (array) $my_context_uploads as $my_context_upload )
		    $this->print_tab_image( esc_url_raw( $my_context_upload->guid ) );
		}

	} // end class.



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
	// drop down selector
	$wp_customize->add_control( 'rg_font_h1', array(
		'label' => 'Select Font:',
		'section' => 'rd_customize_theme_settings',
		'type' => 'select',
		'choices' => array(
			$font1_json => 'Sans-serif',
			$font2_json => 'Serif',
		),
	) );

	$wp_customize->add_setting( 'some_link_color', array(
			'default'		=> '#ffffff',
			'transport'		=> 'postMessage'
		) );
	
	// a color picker
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'some_link_color', array(
		'label'		=> 'Link Color',
		'section'	=> 'colors',
		'settings'	=> 'some_link_color',
	) ) );

	// image picker
	$wp_customize->add_setting( 'footer_bg_image', array(
			'default' => '', //get_bloginfo( 'template_directory' ) . '/images/logo.png',
			'transport' => 'refresh',
	) );

	$wp_customize->add_control( new My_Customize_Image_Reloaded_Control( $wp_customize, 'footer_bg_image', array(
			'label' => __( 'Footer Background Image' ),
			'section' => 'rd_customize_theme_settings',
			'settings' => 'footer_bg_image',
			'context' => 'footer-bg-image',
			'priority' => 20,

	) ) );
	
	// demonstrates how to add a text box to the interface
	$wp_customize->add_setting( 'p_rgba_color', array(
		'default' => '',
	) );

	$wp_customize->add_control( 'p_rgba_color', array(
		'label' => 'Container Background RGBa Setting',
		'section' => 'colors',
		'type' => 'text',
		'priority' => 40
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

		a {color: <?php	echo get_theme_mod('some_link_color', 'default_value'); ?>; }

		footer {
				background-image: url(<?php echo rd_get_footer_bg_image(); ?>);
		}
		p { color: <?php echo get_theme_mod('p_rgba_color', '#ff4');?> }

	</style>
	<?php
}

function rd_get_footer_bg_image(){
	if (get_theme_mod('footer_bg_image'))
		return get_theme_mod('footer_bg_image');
}