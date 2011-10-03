<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title>
	<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
	<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Search Results',woothemes); ?><?php } ?>
	<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Author Archives',woothemes); ?><?php } ?>
	<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
	<?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
	<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive',woothemes); ?>&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
	<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Archive',woothemes); ?>&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
	<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php _e('Tag Archive',woothemes); ?>&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
</title>
    
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
       
    <!--[if IE 6]>
    <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js"></script>
    <?php /*<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/menu.js"></script>*/ ?>
    <![endif]-->
       
    <?php if ( is_single() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>

<!--Start flash player -->
<center>
  <param name="movie" value="http://www.vanoticias.tv/wp-content/uploads/2010/11/presidencia.swf" />
  <param name="quality" value="high" />
  <embed src="http://www.vanoticias.tv/wp-content/uploads/2010/11/presidencia.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="964" height="100"></embed>
</object>
</center><!--End of flash player -->


<!-- Head Begins -->
</head>

<body>

<!-- Set video category -->
<?php $cat = get_option('woo_video_category'); $GLOBALS[vid_cat] = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE name='$cat'"); ?>

<div id="page">

<div id="nav"> <!-- START TOP NAVIGATION BAR -->
	
		<div id="nav-left">
	
			<ul id="nav1">
            
            	<?php /* If this is the frontpage */ if ( is_home() ) { ?>
				<li class="current_page_item"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home',woothemes); ?></a></li>
				<?php } else { ?>
				<li class="page_item"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home',woothemes); ?></a></li>
				<?php } ?>
				<?php wp_list_pages('sort_column=menu_order&title_li=' ); ?>		
			
			</ul>
			
			<div id="nav-bar">
				
			</div>
		
		</div><!--/nav-left -->

		<div id="nav-right">		
		
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
				
				<div id="search">
					<input type="text" value="<?php _e('Enter search keyword',woothemes); ?>" onclick="this.value='';" name="s" id="s" />
					<input name="" type="image" src="<?php bloginfo('stylesheet_directory'); ?>/<?php woo_style_path(); ?>/search.gif" value="<?php _e('Go',woothemes); ?>" class="btn" />
				</div><!--/search -->
				
			</form>
		
		</div><!--/nav-right -->
		
	</div><!--/nav-->
	
	<div class="fix"></div>
	
	<div id="header"><!-- START LOGO LEVEL WITH RSS FEED -->
		
		<h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?>"><img src="<?php if ( get_option('woo_logo') <> "" ) {  echo get_option('woo_logo'); } else { ?><?php bloginfo('stylesheet_directory'); ?>/<?php woo_style_path(); ?>/logo.gif<?php } ?>" alt="" /></a></h1>
		
		<!-- Top Ad Starts -->
			<!--
			<?php if (get_option('woo_ad_top_disable') == "false") include (TEMPLATEPATH . "/ads/top_ad.php"); ?>
			-->
			<div id="topbanner">
				<?php dynamic_sidebar(4); ?>
				<div class="fix"></div>				
			</div>
		<!-- Top Ad Ends -->
		
	</div><!--/header -->
    
    	
	
	<div id="suckerfish"><!-- START CATEGORY NAVIGATION (SUCKERFISH CSS) -->
		
			<ul id="nav2">
				<?php wp_list_categories('title_li=&exclude=1,8') ?>	
			</ul>
					
	</div><!--/nav2-->
	
    <div id="columns"><!-- START MAIN CONTENT COLUMNS -->