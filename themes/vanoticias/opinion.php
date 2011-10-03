<?php
/*
Template Name: Opinion
*/
?>

<?php get_header(); ?>

		<div class="col1">
						
			
			<?php if (have_posts()) : ?>
				
						<?php while (have_posts()) : the_post(); ?>		
						
							<div class="post-alt blog" id="post-<?php the_ID(); ?>">
							
								<h2><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
										                    
			                    <?php 
			                    if(woo_get_embed('embed',595,420))
			                        {   
			                        echo woo_get_embed('embed',595,420); 
			                        }
					            ?>
								<div class="entry">
									<?php if (get_option('woo_image_single') == "false") woo_get_image('image',get_option('woo_single_width'),get_option('woo_single_height'),'thumbnail alignright'); ?>
									<?php the_content(__('<span class="continue">Continue Reading</span>',woothemes)); ?>
								</div>
							
							</div><!--/post-->
							
			               	<?php if (get_option('woo_author') == "true") { ?>
			
			               
			
							<?php } ?>
			               						
					<?php endwhile; ?>
					
									
				<?php endif; ?>	
			
			
						
			<div class="fix"></div>
			
					
            
            <br />
            
            
            
            <div id="archivebox">
        
                <h2><?php _e('Ultimas opiniones:',woothemes); ?></h2>

            </div><!--/archivebox-->
            
            <div class="box">
            
            	<?php 
                if (is_paged()) $is_paged = true;
                
                 $featposts = get_option('woo_show_carousel'); // Number of featured entries to be shown
                 $ex_feat = "-" . get_cat_id(get_option('woo_featured_category'));
                 
                 $showvideo = get_option('woo_show_video');
                 $ex_vid = "-" . get_cat_id(get_option('woo_video_category'));
                 
                 if($featposts == "true"){ $exclude[] = $ex_feat;}
                 if($showvideo == "true"){ $exclude[] = $ex_vid; }
                 if(!empty($exclude)){
                    $ex = implode(',',$exclude);
                 }
                      
                 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; query_posts("cat=$ex&paged=$paged");
                 
              	 //Codigo para excluir categoria Eventos
                 if (is_home()) { 
                 	query_posts("category_name=Opinión");
                 }
                 
            	 if (have_posts()) : $counter = 0;
            	 while (have_posts()) : the_post(); $counter++;?>
            	 
            	 
            	<div class="post <?php if ($counter == 1) { echo 'fl'; } else { echo 'fr'; $counter = 0; } ?>">
                        
            		    <div class="box-post-content">
                        <?php woo_get_image('image',get_option('woo_home_thumb_width'),get_option('woo_home_thumb_height')); ?> 
            			<h2><a title="<?php _e('Permalink to ',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                        <p><em><?php the_time('d F Y'); ?></em></p>
            
            			<p><?php echo strip_tags(get_the_excerpt(), '<a><strong>'); ?></p>
                        </div>
            			<p><span class="continue"><a title="<?php _e('Permalink to ',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>"><?php _e('Read the full story',woothemes); ?></a></span></p>
                        
            		
                 <p class="posted">    <?php _e('Opin&oacuten de  ',woothemes); ?> <?php the_author_posts_link(); ?> <!--<?php the_category(', ') ?>--> <span class="comments"><?php comments_popup_link(__('0 Comments',woothemes), __('1 Comment',woothemes), __('% Comments',woothemes)); ?></span></p> 
            		</div><!--/post-->
            		
            		<?php if ( $counter == 0 ) { echo '<div class="hl-full"></div>'; ?> <div style="clear:both;"></div> <?php } ?>
            	
            	<?php endwhile; ?>
                <?php endif; ?>
            	
            	<div class="fix" style="height:1px"></div>
            		
                <div class="more_entries">
                    <?php if (function_exists('wp_pagenavi')) wp_pagenavi(); else { ?>
                        <div class="fl"><?php previous_posts_link(__('&laquo; Newer Entries ',woothemes)) ?></div>
                        <div class="fr"><?php next_posts_link(__(' Older Entries &raquo;',woothemes)) ?></div>
                        <br class="fix" />
                    <?php } ?>
                </div>		
                
                <div class="fix" style="height:15px"></div>
            	
            </div><!--/box-->          	
           
            											

		</div><!--/col1-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>