<?php
/**
 * Client class for front-end
 *
 * @package         wp-bannerize
 * @subpackage      wp-bannerize_client
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */

class WPBANNERIZE_FRONTEND extends WPBANNERIZE_CLASS {

    function WPBANNERIZE_FRONTEND() {
		// super
        $this->WPBANNERIZE_CLASS();

		// Load configurations options
        $this->options = get_option( $this->options_key );

		wp_enqueue_script ( 'wp_bannerize_frontend_js' , $this->uri . '/js/wp_bannerize_frontend.js' , array ( 'jquery' ) , '1.4' , true );

		wp_localize_script ( 'wp_bannerize_frontend_js',
							 'wpBannerizeMainL10n' ,
								array (
								'ajaxURL' => $this->ajax_clickcounter
								) );
		/**
		 * Add shortcode
		 *
		 * @since 2.6.0
		 */
		add_shortcode( "wp-bannerize", array(&$this, "bannerize" ) );
    }

    /**
     * Show banner
     *
     * @return
     * @param object $args
     *
     * group                If '' show all group, else code of group (default '')
     * container_before		Main tag container open (default <ul>)
     * container_after		Main tag container close (default </ul>)
     * before               Before tag banner open (default <li %alt%>)
     * after                After tag banner close (default </li>)
     * random               Show random banner sequence (default '')
     * categories           Category ID separated by commas. (default '')
     * limit                Limit rows number (default '' - show all rows)
     *
     */
	function bannerize($args = '') {
		global $wpdb;

		$default = array(
			'group' => '',
			'container_before' => '<ul>',
			'container_after' => '</ul>',
			'before' => '<li %alt%>',
			'after' => '</li>',
			'random' => '',
			'categories' => '',
			'alt_class' => 'alt',
			'link_class' => '',
			'limit' => ''
		);

		$new_args = wp_parse_args($args, $default);

		/**
		 * Check for categories
		 *
		 * @since 2.3.0
		 */
		if ($new_args['categories'] != "") {
			$cat_ids = explode(",", $new_args['categories']);
			if (!is_category($cat_ids)) return;
		}

		$q = "SELECT * FROM `" . $this->table_bannerize . "` WHERE `trash` = '0' ";

		if ($new_args['group'] != "") $q .= " AND `group` = '" . $new_args['group'] . "'";

		/**
		 * Add random option
		 *
		 * @since 2.0.2
		 */
		$q .= ($new_args['random'] == '') ? " ORDER BY `sorter` ASC" : "ORDER BY RAND()";

		/**
		 * Limit rows number
		 *
		 * @since 2.0.0
		 */
		if ($new_args['limit'] != "") $q .= " LIMIT 0," . $new_args['limit'];

		$rows = $wpdb->get_results($q);

		if(count($rows) > 0) {
			$o = '<div class="wp_bannerize">';
			if ($new_args['group'] != "") $o = sprintf( '<div class="wp_bannerize_%s">', str_replace(" ", "_", $rows[0]->group) );
			$o .= $new_args['container_before'];

			$even_before = $odd_before = $alternate_class = "";
			$index = 0;

			$odd_before = str_replace("%alt%", "", $new_args['before']);
			$even_before = str_replace("%alt%", "", $new_args['before']);
			if ($new_args['alt_class'] != "") {
				$alternate_class = 'class="' . $new_args['alt_class'] . '"';
				$even_before = str_replace("%alt%", $alternate_class, $new_args['before']);
			}
			$new_link_class = ($new_args['link_class'] != "") ? ' class="' . $new_args['link_class'] . '"' : "";

			foreach ($rows as $row) {
				$target = ($row->target != "") ? 'target="' . $row->target . '"' : "";
				$o .= (($index % 2 == 0) ? $odd_before : $even_before);
				if($row->mime == "application/x-shockwave-flash") {
					$flash = sprintf('<object width="%s" height="%s" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
					<param value="%s" name="movie">
					<param value="transparent" name="wmode">
					<embed width="%s" height="%s" wmode="transparent" type="application/x-shockwave-flash" src="%s">
					</object>', $row->width, $row->height, $row->filename, $row->width, $row->height, $row->filename);
					$o .= $flash;
				} else {
					$nofollow = ($row->nofollow == "1") ? ' rel="nofollow"' : "";
					$o .= '<a' . $nofollow . ' onclick="SMWPBannerizeJavascript.incrementClickCount(' . $row->id . ')"' . $new_link_class . ' ' . $target . ' href="' . $row->url . '"><img width="' . $row->width . '" height="' . $row->height . '" alt="' . $row->description . '" src="' . $row->filename . '" /></a>';
				}

				if($row->use_description == "1") $o .= '<br/><span class="description">'.$row->description.'</span>';

				$o .= $new_args['after'];
				$index++;
			}
			$o .= $new_args['container_after'];
			$o .= '</div>';
			echo $o;
		}
	}
} // end of class

?>