<?php
/**
 * Single Book Template
 */
?>
<?php get_header(); ?>
<div id="main-area" class="site-content">
<!--<div id="primary" class="site-content">-->
<div class="container">
<div id="content-area" class="clearfix">
  <div id="left-area">
	<?php
	/* Main loop - displays posts */

		$custom = ( get_post_custom($post->ID) ? get_post_custom($post->ID) : false );
		$custom_field = ( isset( $custom['_my_meta_value_key'][0] ) ? $custom['_my_meta_value_key'][0] : false );
	    $purchase_links = ( isset( $custom['purchase_links'][0] ) ? $custom['purchase_links'][0] : false );
//var_dump($custom);
		$purchase_links = unserialize($purchase_links);


	   $image = get_the_post_thumbnail($post->ID, 'rg-book', array('class' => 'book-cover-img') );


	?>

	<div <?php post_class( 'top-post' ); ?>>

		<div class="book-details">
			<div class="book-cover">

				<?php
				if ( ! empty($image) ) {
	        		echo $image;
				}
				?>

			<div class="book-meta">
			    <h1 class="title book-title"><?php the_title(); ?></h1>
				<ul class="book-details">
				<?php
				if ( $custom_field ) {
					echo '<h1>' . $custom_field . '</h1>';
				}

				?>
				</ul>
				<div class="book-fixed">
					<div class="book-purchase-links">
						<?php
						// some logic to figure out how many links we have

						$count =0;
						foreach($purchase_links as $link){
							if($link != false){
								$count++;
							}
						}
						if($count >1){ ?>
							<div class="buy-button">
								<span class="button"><?php _e( 'Buy Now', 'rg-book' ); ?></span>
							</div>
					<?php	} ?>
						<style type="text/css">
						.book-cover .book-meta .book-purchase-links:hover { height: <?php echo ($count+1)*40; ?>px; }
						</style>


						<?php foreach($purchase_links as $link) : ?>
							<div class="buy-button">
								<a class="button" href="<?php echo esc_url( $link['purchase_link'] ); ?>" target="_blank"><?php echo esc_html( $link['name'] ); ?></a>
							</div>
						<?php endforeach; ?>


					</div>
				</div>

			</div>

			</div>
		</div> <!-- end book details -->

		<div class="book-description">

			<?php the_content(); ?>

		</div>

	</div> <!-- end post class -->
  </div> <!-- end left area -->

</div> <!-- end content -->
</div> <!--end container-->
</div> <!-- end main-area site-content primary -->


<?php get_footer(); ?>