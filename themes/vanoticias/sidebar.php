<div class="col2">
		
     <?php if (get_option('woo_ad_mpu_disable') == "false") include (TEMPLATEPATH . "/ads/mpu_ad.php"); ?>
	
	<!-- TABS STARTS --> 
	
	<center>
	<div id="c_251b9c9a884406e7381cd740c205c3c2" class="completo"><h2 style="color: #000000; margin: 0 0 3px; padding: 2px; font: bold 13px/1.2 Verdana; text-align: center;"><a href="http://www.eltiempo.es/cuauhtemoc.html" style="color: #000000; text-decoration: none;">El Tiempo en Cuauht�moc</a></h2></div><script type="text/javascript" src="http://www.eltiempo.es/widget/widget_loader/251b9c9a884406e7381cd740c205c3c2"></script>
	</center>
	
	<?php if (get_option('woo_tabs') == "false") { ?>
	<div id="tabs">
		
		<ul class="wooTabs tabs">
			<li><a href="#pop"><?php _e('Twitter ',woothemes); ?></a></li>
			<li><a href="#feat"><?php _e('Facebook ',woothemes); ?></a></li>
            <li><a href="#comm"><?php _e('Comments ',woothemes); ?></a></li>
			<li><a href="#tagcloud"><?php _e('Tag ',woothemes); ?></a></li>
            <!--Etiqueta de pestana que llama a la funcion de suscripcion-->
            <li><a href="#sub"><?php _e('Subscribe ',woothemes); ?></a></li>
		</ul>	
		
		<div class="fix"></div>
		
		<div class="inside">
		 <div id="pop">
			<ul> <!-- Llama a la funci�n de twitter widget en archivo twitter.php-->
                <?php include(TEMPLATEPATH . '/includes/twitter.php' ); ?>                    
			</ul>
           </div>
           
         <div id="feat"> 
	        <ul> <!--Llama a la funcion del modulo de Facebook en el archivo facebook.php-->
				<?php include(TEMPLATEPATH . '/includes/facebook.php' ); ?>	
			</ul>
          </div>
          <div id="comm">  
			<ul>
                <?php include(TEMPLATEPATH . '/includes/comments.php' ); ?>                    
			</ul>
	      </div>
			<div id="tagcloud">
                <div>
				    <?php wp_tag_cloud('smallest=12&largest=20'); ?>
                </div>
			</div>
	
        <div id="sub">
	        <ul>
				<li><h3>Mantente informado</h3><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/ico-rss.gif" alt="" /></a></li>
				<li><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><?php _e('Subscribe to the RSS feed',woothemes); ?></a></li>
				<li><a href="http://www.feedburner.com/fb/a/emailverifySubmit?feedId=<?php $feedburner_id = get_option('woo_feedburner_id'); echo $feedburner_id; ?>" 	target="_blank"><?php _e('Subscribe to the feed via email',woothemes); ?></a></li>
			</ul>            
        </div> 
		</div>
		
	</div>
	
	<div class="fix" style="height:15px !important;"></div>
	
	<?php } ?>  
	<!-- TABS END -->
	
	<?php dynamic_sidebar(1); ?> 
	
	<div class="fix"></div>
    
    <div class="subcol fl">

        <?php dynamic_sidebar(3); ?>        
                   
    </div><!--/subcol-->
	
	<div class="subcol fr">
	
		<?php dynamic_sidebar(2); ?>		
			
	</div><!--/subcol-->
		

<div class="fix"></div>
	
</div><!--/col2-->
