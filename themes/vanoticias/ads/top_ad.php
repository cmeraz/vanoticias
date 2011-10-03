<div id="topbanner">

	<?php if (get_option('woo_ad_top_adsense') <> "") { echo stripslashes(get_option('woo_ad_top_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_top_url'); ?>" target="_blank"><img src="<?php echo get_option('woo_ad_top_image'); ?>"  alt="<?php _e('Advert',woothemes); ?>" /></a>
		
	<?php } ?>	

</div>
