<?php

/*
  Plugin Name: Custom Menus
  Description: This example demonstrates how to create pages, categories and assign them to a menu automatically after a theme is setup.
  Version: 1.0
  Author: Richard Dinh
  Author URI: http://www.dinhdesigns.com
  License: GPLv2

 */

// run this after the theme is assigned.
add_action( 'after_setup_theme', 'the_theme_setup' );

function the_theme_setup() {

	require_once(ABSPATH . 'wp-config.php');
	require_once(ABSPATH . 'wp-includes/wp-db.php');
	require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// damn twenty twelve name had a space and is capitalized
	// i would use wp_current_theme, but twenty twelve screwed me
	$theme_name	= get_option( 'template' );


	// First we check to see if our default theme settings have been applied.
	$the_theme_status = get_option( "theme_setup_status_$theme_name" );


	if ( $the_theme_status !== '1' ) {
		// value set to see if we include in the menu
		$categories = array( 'News' => 'true', 'Featured Media' => 'false', 'Gallery' => 'true', 'Spotlight' => 'false', 'Video' => 'false' );

		// make an array for list of category ids
		$created_categories = array( );

		foreach ( $categories as $key => $value ) {
			// this returns the cat id
			$term_id = term_exists( $key, 'category' );

			if ( $term_id !== 0 && $term_id !== null ) {
				// just get the id
				$created_categories[$key] = $term_id;
			} else {
				// cat doesnt exist so we make it
				$created_categories[$key] = wp_create_category( $key );
			}
		}

		// Category 2
		update_option( 'default_category', $created_categories['News'] );

		// delete the default category
		wp_delete_category( 1 );

		$pages = array( 'Bio', 'Contact', 'Tour', );

		// ids of the
		$page_ids = array( );

		foreach ( $pages as $page ) {
			// create new pages
			$post = array(
				'comment_status' => 'closed', // 'closed' means no comments.
				'ping_status' => 'closed', // | 'open' ] // 'closed' means pingbacks or trackbacks turned off
				'post_content' => $page . ' page content goes here. Edit to make this go away', //The full text of the post.
				'post_name' => $page, // The name (slug) for your post
				'post_status' => 'publish', //[ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] //Set the status of the new post.
				'post_title' => $page, //The title of your post.
				'post_type' => 'page', // | 'link' | 'nav_menu_item' | custom post type ] //You may want to insert a regular post, page, link, a menu item or some custom post type
			);


			// check to see if page doesnt already exist so we don't keep adding junk.
			//worked fine in local, but sandbox was acting up.
			$page_check = get_page_by_title( $page );

			if ( is_null( $page_check ) ) {
				$page_ids[$page] = wp_insert_post( $post );
			} else {
				$page_ids[$page] = $page_check->ID;
			}
		}

		// create main menu
		if ( ! is_nav_menu( 'main' ) ) {

			$main_menu_slug = 'main-menu';
			$menu_title = 'main';

			// just create one menu
			//register_nav_menu( $main_menu_slug, $menu_title );

			if ( ! is_nav_menu( $menu_title ) ) {
				$menu_id = wp_create_nav_menu( $menu_title );


				$item = array(
					'menu-item-type' => 'custom',
					'menu-item-url' => site_url(),
					'menu-item-title' => 'Home',
					'menu-item-status' => 'publish'
				);
				wp_update_nav_menu_item( $menu_id, 0, $item );

				foreach ( $pages as $page ) {
					$item = array(
						'menu-item-object-id' => $page_ids[$page],
						'menu-item-object' => 'page',
						'menu-item-type' => 'post_type',
						'menu-item-title' => $page,
						'menu-item-status' => 'publish',
						'menu-item-parent-id' => $menu_id,
					);

					wp_update_nav_menu_item( $menu_id, 0, $item );
				}

				foreach ( $categories as $key => $value ) {

					// do we include it in the menu?
					if ( $value == 'true' ) {
						$item = array(
							'menu-item-object-id' => $created_categories[$key],
							'menu-item-object' => 'category',
							'menu-item-type' => 'taxonomy',
							'menu-item-title' => $key,
							'menu-item-status' => 'publish',
							'menu-item-parent-id' => $menu_id,
						);

						wp_update_nav_menu_item( $menu_id, 0, $item );
					}
				}

				$mods = get_option( "theme_mods_$theme_name" );
				//update mods with menu id at theme location

				$menu_locations = get_nav_menu_locations();

				foreach ($menu_locations as $menu_location => $description){
					$mods['nav_menu_locations'][$menu_location] = $menu_id;
				}

				update_option( "theme_mods_$theme_name", $mods );
			}
		}
	}
	// Else if we are re-activing the theme
	elseif ( $the_theme_status === '1' and isset( $_GET['activated'] ) ) {
		$msg = '
		<div class="updated">
			<p>The ' . get_option( 'current_theme' ) . ' theme was successfully re-activated.</p>
		</div>';
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $msg, '"' ) . '";' ) );
	}

	update_option( "theme_setup_status_$theme_name", '1' );
}