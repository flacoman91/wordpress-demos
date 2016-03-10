<?php
/*
Plugin Name: Drag n Drop Meta Boxes
Description: Just a demo on how to add an a text field or dropdown for authors in a post edit screen
Version: 1.0
Author: Richard Dinh
*/

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function drag_drop_add_meta_box() {
	
	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'drag_drop_sectionid',
			__( 'Drag N Drop Boxes', 'drag_drop_textdomain' ),
			'drag_drop_meta_box_callback',
			$screen,
			'normal',
			'high'
		);
	}
}

add_action( 'admin_enqueue_scripts', 'drag_drop_around_scripts' );

function drag_drop_around_scripts( $hook ) {
	if ( 'post-new.php' == $hook || 'post.php' == $hook ) {
		wp_enqueue_script( 'drag-it', plugins_url( '/', __FILE__ ) . '/js/drag-drop-meta-boxes.js', array(
			'jquery-ui-sortable',
			'jquery'
		) );
	}
}

add_action( 'do_meta_boxes', 'drag_drop_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function drag_drop_meta_box_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'drag_drop_meta_box', 'drag_drop_meta_box_nonce' );

	$users = get_users();
	
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, 'drag', true );
	//$select_value = get_post_meta( $post->ID, '_instyle_drag_drop_key', true );

	//var_dump($value);
	echo '<div class="sortable">';
	for($i=1; $i<4; $i++){
		$my_val = $value['drag-' .$i]['value'];
		?>

		<div></div>

		<div class="group-caption">
			<h4>PARENT <?php echo $i; ?></h4>
			<div class="move">+</div>
			<div class="group-items">
				<input type="text" name="input-<?php echo $i; ?>" value="<?php echo $my_val; ?>"/>
				<input type="hidden" name="order-<?php echo $i; ?>" class="order" value="<?php echo $i; ?>"/>
			</div>
		</div>

		<?php
	}
	echo "</div>";
?>
<!--	<div id="sortable" style="float:left">-->
<!--		--><?php //$field_groups = array();
//		if (get_post_meta($post->ID,"order_eng_1_1",true)) {
//			for ($i = 1; $i <= 5; $i++){
//				$field_groups[get_post_meta($post->ID,"order_eng_".$i."_1",true)] = $i;
//			}
//			ksort($field_groups);
//			foreach ($field_groups as $field_order => $field_id) {?>
<!--					<tr class="itemthing">-->
<!--						<th class="title">My Title --><?php //echo $field_order; ?><!--</th>-->
<!--						<input class="widefat" name="yomama--><?//= $field_id ?><!--"></input>-->
<!--					</tr>-->
<!--					<tr><td><input name="ordering_1" type="hidden" class="ordering" value="--><?//= $field_id ?><!--"></td></tr>-->
<!--				</table>-->
<!--			--><?// }
//		} else {
//			for ($i = 1; $i <= 5; $i++) {?>
<!--				<table class="form-table dropable">-->
<!--					<tr class="itemthing">-->
<!--						<th class="title">My title --><?php //echo $i; ?><!--</th>-->
<!--						<input class="widefat" name="yomama--><?php //echo $i; ?><!--" />-->
<!--					</tr>-->
<!--					<tr>-->
<!--					<input name="ordering_2" type="hidden" class="ordering"-->
<!--						value="--><?php //echo $i; ?><!--" />-->
<!--					</tr>-->
<!--				</table>-->
<!--			--><?// }
//		}?>
<!--	</div>-->

<!--	<div class="sortable">-->
<!---->
<!--		<div></div>-->
<!---->
<!--		<div class="group-caption">-->
<!--			<h4>PARENT #1</h4>-->
<!--			<div class="move">+</div>-->
<!--			<div class="group-items">-->
<!--				<input type="text" name="input-1"/>-->
<!--				<input type="hidden" name="order-1" class="order" value="1"/>-->
<!--			</div>-->
<!--		</div>-->
<!---->
<!--		<div class="group-caption">-->
<!--			<h4>PARENT #2</h4>-->
<!--			<div class="move">+</div>-->
<!--			<div class="group-items">-->
<!--				<input type="text" name="input-2"/>-->
<!--				<input type="hidden" name="order-2" class="order" value="2"/>-->
<!--			</div>-->
<!--		</div>-->
<!---->
<!--		<div class="group-caption">-->
<!--			<h4>PARENT #3</h4>-->
<!--			<div class="move">+</div>-->
<!--			<div class="group-items">-->
<!--				<input type="text" name="input-3"/>-->
<!--				<input type="hidden" name="order-3" class="order" value="3"/>-->
<!--			</div>-->
<!--		</div>-->
<!---->
<!--		<div></div>-->
<!---->
<!--	</div>-->
<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function drag_drop_save_meta_box_data( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	//var_dump($_POST);
	//die();
	// Check if our nonce is set.
	if ( ! isset( $_POST['drag_drop_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['drag_drop_meta_box_nonce'], 'drag_drop_meta_box' ) ) {
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

	$results = array();
	for($i=1; $i<4; $i++){

//		<input type="text" name="input-3" order="3"/>
//		<input type="hidden" name="order-3" value="3"/>
		//$_POST['input-' . $i];
		//update with correct order
		//$_POST['order-' . $i]; // key
		//$results['drag-' . ] = '';
		$results['drag-' . $_POST['order-' . $i]]['value'] = $_POST['input-' . $i]; // new value
	}

	update_post_meta( $post_id, 'drag', $results );
	/* OK, its safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( isset( $_POST['drag_drop_new_field'] ) ) {
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['drag_drop_new_field'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_drag_drop_value_key', $my_data );
	}
	// this is the dropdown meta box
	if ( isset( $_POST['instyle-author-override'] ) ){
		
		$my_data = sanitize_text_field( $_POST['instyle-author-override'] );
		// Update the meta field in the database.
		update_post_meta( $post_id, '_instyle_drag_drop_key', $my_data );
	}	
}
add_action( 'save_post', 'drag_drop_save_meta_box_data' );
