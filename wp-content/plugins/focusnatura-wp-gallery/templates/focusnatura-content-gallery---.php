<?php
/**
 * The template for displaying posts in the Gallery post format
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<?php get_header() ?>

		<div id="primary" class="content-area">
				<div id="content" class="site-content" role="main">

						<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



			<div class="gallery_clear"></div>
			<div id="gallery_" class="photospace">
					<!-- Start Advanced Gallery Html Containers -->
									<?php
											/**
											 * THUMBS output from lib/focusnatura-gallery.php
											 * html markup with paggination
											 *
											 */
											the_content();
											?>


					<!-- TODO Gallery image -->


					<?php $gallery=get_post_gallery($post, false);

					$attachment_ids = explode(',',$gallery['ids'] );

					$selected_key = array_search( get_query_var( 'img' ), $attachment_ids );
					$available_keys = count($attachment_ids)-1;

					if ($selected_key < $available_keys){
							// next in gallery
							$next_id = $attachment_ids[$selected_key+1];
					} else {
							// first in gallery
							$next_id = $attachment_ids[0];
					}
					?>
					<!-- Start Advanced Gallery Html Containers -->
					<div id="slideshow_" class="slideshow">
						<span class="image-wrapper current">

						<a href="<?= get_permalink( $post->ID ) ?>img/<?= $next_id ?>">
								<?= wp_get_attachment_image( get_query_var( 'img' ), 'full' ); ?>
						</a>
						</span>
					</div>

			</div>
		<div class="gallery_clear"></div>


</article><!-- #post-## -->

<?php endwhile; ?>
</div><!-- #content -->
		</div><!-- #primary -->
<?php get_sidebar( 'content' ); ?>
		</div> <!-- #main-content -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();

