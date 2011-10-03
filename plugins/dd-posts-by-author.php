<?php
/*
Plugin Name: Posts by Author
Plugin URI: http://www.dagondesign.com/articles/posts-by-author-plugin-for-wordpress/
Description: At the bottom of every post, links are shown for that author's last X posts. See options page for config.
Author: Dagon Design
Version: 1.7
Author URI: http://www.dagondesign.com
*/

$ddpa_version = '1.7';

// Setup defaults if options do not exist
add_option('ddpa_all_posts', TRUE);
add_option('ddpa_num', 5);
add_option('ddpa_header', '<h3>Last 5 posts by %A</h3>');
add_option('ddpa_show_date', TRUE);
add_option('ddpa_date_format', 'F jS, Y');
add_option('ddpa_inc_current', FALSE);
add_option('ddpa_newest_first', TRUE);
add_option('ddpa_excluded_cats', '');
add_option('ddpa_excluded_display', '');



function ddpa_add_option_pages() {
	if (function_exists('add_options_page')) {
		add_options_page('Posts by Author', 'DDPostsByAuthor', 8, __FILE__, 'ddpa_options_page');
	}		
}



function ddpa_options_page() {

	global $ddpa_version;

	if (isset($_POST['set_defaults'])) {
		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('ddpa_all_posts', TRUE);
		update_option('ddpa_num', 5);
		update_option('ddpa_header', '<h3>Last 5 posts by %A</h3>');
		update_option('ddpa_show_date', TRUE);
		update_option('ddpa_date_format', 'F jS, Y');
		update_option('ddpa_inc_current', FALSE);
		update_option('ddpa_newest_first', TRUE);
		update_option('ddpa_excluded_cats', '');
		update_option('ddpa_excluded_display', '');

		echo 'Default Options Loaded!';
		echo '</strong></p></div>';

	} else if (isset($_POST['info_update'])) {

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('ddpa_all_posts', (bool) $_POST["ddpa_all_posts"]);
		update_option('ddpa_num', (int) $_POST["ddpa_num"]);
		update_option('ddpa_header', (string) $_POST["ddpa_header"]);
		update_option('ddpa_show_date', (bool) $_POST["ddpa_show_date"]);
		update_option('ddpa_date_format', (string) $_POST["ddpa_date_format"]);
		update_option('ddpa_inc_current', (bool) $_POST["ddpa_inc_current"]);
		update_option('ddpa_newest_first', (bool) $_POST["ddpa_newest_first"]);
		update_option('ddpa_excluded_cats', (string) $_POST["ddpa_excluded_cats"]);
		update_option('ddpa_excluded_display', (string) $_POST["ddpa_excluded_display"]);
			
		echo 'Configuration Updated!';
		echo '</strong></p></div>';

	} ?>

	<div class=wrap>

	<h2>Posts by Author v<?php echo $ddpa_version; ?></h2>

	<p>For information and updates, please visit:<br />
	<a href="http://www.dagondesign.com/articles/posts-by-author-plugin-for-wordpress/">http://www.dagondesign.com/articles/posts-by-author-plugin-for-wordpress/</a></p>

	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />


	<h3>Display</h3>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr valign="top"><td width="35%" align="right">
		Show at the bottom of every post
	</td><td align="left">
		<input type="checkbox" name="ddpa_all_posts" value="checkbox" <?php if (get_option('ddpa_all_posts')) echo "checked='checked'"; ?>/>
		<br />
		<br />If you prefer, you can manually generate the list by 
		<br />typing <b>&lt;!-- ddpostsbyauthor --&gt;</b> in a post.
	</td></tr>


	</table>




	<h3>Options</h3>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr valign="top"><td width="35%" align="right">
		Number of posts to show by author
	</td><td align="left">
		<input name="ddpa_num" type="text" size="10" value="<?php echo get_option('ddpa_num') ?>"/>
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Text to show before list
	</td><td align="left">
		<input name="ddpa_header" type="text" size="40" value="<?php echo get_option('ddpa_header') ?>"/>
		<br />( To show the author's name, use %A - uses the display name)
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Category IDs to exclude from post lists
	</td><td align="left">
		<input name="ddpa_excluded_cats" type="text" size="40" value="<?php echo get_option('ddpa_excluded_cats') ?>"/>
		<br />( separate IDs with a comma )
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Category IDs to not display list on
	</td><td align="left">
		<input name="ddpa_excluded_display" type="text" size="40" value="<?php echo get_option('ddpa_excluded_display') ?>"/>
		<br />( separate IDs with a comma )
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Show date after listed posts
	</td><td align="left">
		<input type="checkbox" name="ddpa_show_date" value="checkbox" <?php if (get_option('ddpa_show_date')) echo "checked='checked'"; ?>/>
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Date format
	</td><td align="left">
		<input name="ddpa_date_format" type="text" size="30" value="<?php echo get_option('ddpa_date_format') ?>"/>
		<br />( Use the standard <a href="http://us3.php.net/date">PHP date() format</a> )
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Include current post in list
	</td><td align="left">
		<input type="checkbox" name="ddpa_inc_current" value="checkbox" <?php if (get_option('ddpa_inc_current')) echo "checked='checked'"; ?>/>
		( If it is one of the last X posts )
	</td></tr>

	<tr valign="top"><td width="35%" align="right">
		Show newest posts first
	</td><td align="left">
		<input type="checkbox" name="ddpa_newest_first" value="checkbox" <?php if (get_option('ddpa_newest_first')) echo "checked='checked'"; ?>/>
		( Otherwise oldest posts will be shown first )
	</td></tr>

	</table>


	<div class="submit">
		<input type="submit" name="set_defaults" value="<?php _e('Load Default Options'); ?> &raquo;" />
		<input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
	</div>

	</form>
	</div><?php
}


function ddpa_show_posts() {

	global $wpdb, $post, $wp_version;

	$ver = (float)$wp_version;
	
	$tp = $wpdb->prefix;

	$ddpa_num = get_option('ddpa_num');
	$ddpa_header = get_option('ddpa_header');
	$ddpa_show_date = get_option('ddpa_show_date');
	$ddpa_date_format = get_option('ddpa_date_format');
	$ddpa_inc_current = get_option('ddpa_inc_current');
	$ddpa_newest_first = get_option('ddpa_newest_first');
	$ddpa_excluded_cats = get_option('ddpa_excluded_cats');
	$ddpa_excluded_display = get_option('ddpa_excluded_display');



	$ddpa_excluded_display = str_replace(' ', '', $ddpa_excluded_display);
	if (trim($ddpa_excluded_display) != '') {
		$ex_display = (array)explode(',', $ddpa_excluded_display);
		foreach((get_the_category()) as $c) { 
			if (in_array($c->cat_ID, $ex_display) === TRUE) {
				return '';
			}
		} 
	}


	$c_post = $post->ID;
	$c_author_id = $post->post_author;

	// see if we show current post
	if (!$ddpa_inc_current) {
		$ddpa_inc_current = " AND ID != " . $c_post . " ";
	} else {
		$ddpa_inc_current = " ";
	}

	// sorting
	$newest_check = ' ASC ';
	if ($ddpa_newest_first) {
		$newest_check = ' DESC ';
	}




	if ($ver < 2.3) {

		// get excluded cat list
		$exclude_check = ' ';
		if (strlen(trim($ddpa_excluded_cats)) > 0) {
			$t_exclude = (array)explode(',', $ddpa_excluded_cats);
			foreach ($t_exclude as $t_e) {
				$exclude_check .= ' AND category_id != ' . (int)$t_exclude[$i] . ' ';
			}
		}

		$last_posts = (array)$wpdb->get_results("
			SELECT ID, post_title, post_date, post_category
			FROM {$tp}posts, {$tp}post2cat 
			WHERE post_author = {$c_author_id} 
			{$exclude_check} 
			AND {$tp}posts.ID = {$tp}post2cat.post_id 
			{$ddpa_inc_current} 
			AND post_status = 'publish' 
			AND post_date < NOW() 
			AND post_type = 'post'
			GROUP BY ID 
			ORDER BY post_date {$newest_check} 
			LIMIT {$ddpa_num}
		");

	} else { // >= 2.3

		// get excluded cat list
		$exclude_check = ' ';
		if (strlen(trim($ddpa_excluded_cats)) > 0) {
			$t_exclude = (array)explode(',', $ddpa_excluded_cats);
			foreach ($t_exclude as $t_e) {
				$exclude_check .= ' AND ' . $tp . 'term_taxonomy.term_id != ' . (int)$t_e . ' ';
			}
		}


		$last_posts = (array)$wpdb->get_results("
			SELECT ID, post_title, post_date
			FROM {$tp}posts, {$tp}term_relationships, {$tp}term_taxonomy
			WHERE post_author = {$c_author_id} 
			AND {$tp}term_relationships.object_id = {$tp}posts.ID
			AND {$tp}term_relationships.term_taxonomy_id = {$tp}term_taxonomy.term_taxonomy_id 
			{$exclude_check} 
			{$ddpa_inc_current} 
			AND post_status = 'publish' 
			AND post_date < NOW() 
			AND post_type = 'post'
			GROUP BY ID 
			ORDER BY post_date {$newest_check} 
			LIMIT {$ddpa_num}
		");

	}


	$author_info = $wpdb->get_row("
		SELECT display_name
		FROM {$tp}users
		WHERE ID = {$c_author_id} 
		LIMIT 1 
	");


	// fix up header
	$ddpa_header = str_replace('%A', $author_info->display_name, $ddpa_header);



	if (count($last_posts) > 0) {

		$the_output = NULL;

		$the_output .= $ddpa_header;

		$the_output .= "<ul>";

		foreach ($last_posts as $lpost) {

			$the_output .= '<li><a href="' . get_permalink($lpost->ID) . '">' . $lpost->post_title . '</a>';

			if ($ddpa_show_date) {
				$the_output .= ' - ' . date($ddpa_date_format, strtotime($lpost->post_date));
			}
		
			$the_output .= '</li>';

		}

		$the_output .= "</ul>";

	}

	return $the_output;

}






function ddpa_generate($content) {

	if (strpos($content, "<!-- ddpostsbyauthor -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content); 
		$content = str_replace("<!-- ddpostsbyauthor -->", ddpa_show_posts(), $content);
	}

	if (is_single()) {
		if (get_option('ddpa_all_posts')) {
			return $content . ddpa_show_posts();
		} else {
			return $content;
		}
	} else {
		return $content;
	}
}




add_filter('the_content', 'ddpa_generate');
add_action('admin_menu', 'ddpa_add_option_pages');






?>