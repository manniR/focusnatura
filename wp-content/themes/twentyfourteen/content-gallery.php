<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php twentyfourteen_post_thumbnail(); ?>

	<header class="entry-header">
		<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && twentyfourteen_categorized_blog() ) : ?>
		<div class="entry-meta">
			<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) ); ?></span>
		</div><!-- .entry-meta -->
		<?php
			endif;

			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
			<span class="post-format">
				<a class="entry-format" href="<?php echo esc_url( get_post_format_link( 'gallery' ) ); ?>"><?php echo get_post_format_string( 'gallery' ); ?></a>
			</span>
			<?php twentyfourteen_posted_on(); ?>

			<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ); ?></span>
			<?php endif; ?>

			<?php edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->



	<div class="entry-content">
			<div class="gallery_clear"></div>
			<div id="gallery_" class="photospace">
					<!-- Start Advanced Gallery Html Containers -->
					<div class="thumbs_wrap2">
							<div class="thtumbs_wrap">
									<div id="thumbs_" class="thumbnail_col" style="width: 90px; display: block; opacity: 1;">

									<!-- TODO link to next on click-->
											<a class="advance-link" href="nextImage">

												<!-- TODO Gallery thumbs -->

											</a>




									</div>
							</div>
					</div>
					<!-- Start Advanced Gallery Html Containers -->
					<div id="slideshow_" class="slideshow">
							<span class="image-wrapper current">
									<!-- TODO Gallery image -->

							</span>
					</div>

			</div>
			<div class="gallery_clear"></div>


		<?php
		/*
		 *
		 * Display Gallery
		 *
		 * */



/*		echo '<pre>';
		var_dump('query vars '.get_query_var('img'));
		echo '</pre>';*/



		$gallery=get_post_gallery($post, false);

		$attachment_ids = explode(',',$gallery['ids'] );


/*		echo '<pre>';
		var_dump(wp_get_attachment_metadata( $attachment_ids[1] ));
		echo '</pre>';*/


		echo '<a href="imeage/attachment_id='.$attachment_ids[1] .'">';
		echo wp_get_attachment_image( get_query_var('img'), 'full' );
		echo '</a>';

//		echo '<pre>';
//		var_dump($post);
//		echo '</pre>';


//		echo '<pre>';
//		var_dump(get_image_sizes());
//		echo '</pre>';




		function get_image_sizes( $size = '' ) {

				global $_wp_additional_image_sizes;

				$sizes = array();
				$get_intermediate_image_sizes = get_intermediate_image_sizes();

				// Create the full array with sizes and crop info
				foreach( $get_intermediate_image_sizes as $_size ) {

						if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

								$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
								$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
								$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

								$sizes[ $_size ] = array(
										'width' => $_wp_additional_image_sizes[ $_size ]['width'],
										'height' => $_wp_additional_image_sizes[ $_size ]['height'],
										'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
								);

						}

				}

				// Get only 1 size if found
				if ( $size ) {

						if( isset( $sizes[ $size ] ) ) {
								return $sizes[ $size ];
						} else {
								return false;
						}

				}

				return $sizes;
		}










		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
