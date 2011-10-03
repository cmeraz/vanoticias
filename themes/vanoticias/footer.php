		<div class="fix"></div>

	</div><!--/columns -->
	
	<div id="footer">
		 <p class="fl">&copy; <a href="http://vanoticias.tv"><?php the_time('Y'); ?> <?php bloginfo(); ?>.</a> <!--<?php _e('Powered by',woothemes); ?>--> Todos los derechos reservados.</p>
            <p class="fr">Sitio optimizado para navegar con <!--<?php _e('by',woothemes); ?>--> <a href="http://www.google.com/chrome/" title="Google Chrome Web Browser" target="_blank"><img src="http://vanoticias.tv/chrome.png" width="85" height="24" alt="Google Chrome - Browser" /></a></p>
	</div><!--/footer -->

</div><!--/page -->

<?php wp_footer(); ?>

<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

</body>
</html>