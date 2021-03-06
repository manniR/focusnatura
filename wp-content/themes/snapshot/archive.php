<?php get_header(); ?>

<div id="content" class="fullspan">

	<div class="container_16 clearfix">
			
		<div id="leftcontent" class="grid_12">
		    		<?php
 $posts = query_posts($query_string .'&orderby=title&order=asc&posts_per_page=-1');

				$counter = 0; $counter2 = 0;
				while (have_posts()) : the_post();
			?>
	
			<?php $counter++; $counter2++; ?>	
            
         <div class="grid_6 <?php if ($counter == 1) { echo 'alpha'; } else { echo 'omega'; $counter = 0; } ?>">	
									
			<div class="post">
				
				<div class="screenshot">
				
					<div class="screenimg">
					
						<?php if ( get_option('woo_layout') == '1-photo.php' ) { ?>
						
							<?php if ( get_option('woo_resize') ) { woo_get_image('image','330','190'); } else { ?>                            
		<a href="<?php the_permalink() ?>" title="View <?php the_title(); ?>"><img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" alt="<?php the_title(); ?>" /></a>
                                                       
                            <?php } ?>
	
						
						<?php } else { ?>

							<?php if ( get_option('woo_resize') ) { woo_get_image('large-image','330','190','url'); } else { ?>
						<?php } ?>
						<?php } ?>
						
			<span><a href="<?php the_permalink() ?>" target="_blank" title="View <?php the_title(); ?>"><?php the_title(); ?></a>
			</span>
					
					</div>
				
				</div>
   
				<div class="theme">
		      
		      	<div class="grid_4 alpha"></div>
		      	<div class="grid_2 omega"></div>
		      	<div style="clear:both;"></div>		      	 
		      	<div class="grid_4 alpha"><p class="tags"><?php the_tags('', ', ' , ''); ?></p></div>	
		      	<div class="grid_2 omega" style="text-align:right;"><?php if(function_exists('the_ratings')) { the_ratings(); } ?> </div>		     
		      	<div style="clear:both;"></div>	
					
				</div>
					
			</div><!-- /post -->
            
         <div style="clear:both;height:10px;"></div>
            
        </div>
        
        <?php if ( $counter == 0 ) { ?><div style="clear:both;height:0px;"></div><?php } ?>
			
		<?php endwhile; ?>
            
            <div style="clear:both;height:0px;"></div>
			
			<div id="postnav">
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
			</div><!-- /postnav -->						

		</div><!-- /leftcontent --> 
		
		<?php get_sidebar(); ?>
        
	</div><!-- /container_16 -->

</div><!-- /content -->

<?php get_footer(); ?>