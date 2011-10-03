<?php
$pop_posts = get_option('woo_popular_posts');
if (empty($pop_posts) || $pop_posts < 1) $pop_posts = 5;
$now = gmdate("Y-m-d H:i:s",time());
$lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-24,date("d"),date("Y")));
$popularposts = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC LIMIT ".$pop_posts;
$posts = $wpdb->get_results($popularposts);
$popular = '';
if($posts){
	foreach($posts as $post){
		$post_title = stripslashes($post->post_title);
		$guid = get_permalink($post->ID);

		$custom_field = get_post_meta($post->ID, "image", true);
?>
		<li>
			<?php if($custom_field) { ?>
				<a title="<?php echo $post_title; ?>" href="<?php echo $guid; ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo $custom_field; ?>&amp;h=35&amp;w=35&amp;zc=1&amp;q=95" alt="<?php echo $post_title; ?>" class="thumbnail" /></a>            
            <?php } ?>                
            
            <a href="<?php echo $guid; ?>" title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a>
    		<div style="clear:both"></div>

        </li>
<?php 
	}
}
?>