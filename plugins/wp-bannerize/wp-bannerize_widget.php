<?php
/**
 * Widget support
 *
 * @package         wp-bannerize
 * @subpackage      wp-bannerize_widget
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 *
 */
class WP_BANNERIZE_WIDGET extends WP_Widget {

    /**
     * Same wp-bannerize_class
     *
     * @var string
     */
    var $table_bannerize        = "";

    function WP_BANNERIZE_WIDGET() {
        global $wpdb;

        /**
         * Load localizations if available
         *
         * @since 2.4.0
         */
		load_plugin_textdomain ( 'wp-bannerize' , false, 'wp-bannerize/localization'  );

        $this->table_bannerize = $wpdb->prefix . WP_BANNERIZE_TABLE;
        //
        $widget_ops = array('classname' => 'widget_wp_bannerize', 'description' => 'Amazing Banner Image Manager');
        $control_ops = array('width' => 430, 'height' => 350);
        $this->WP_Widget('wp_bannerize', 'WP Bannerize', $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        global $wpdb;

        /**
         * Patch
         *
         * @since 2.3.9
         */
        extract($args);
        extract($instance);

        /**
         * Check for categories
         *
         * @since 2.3.0
         */
        if( is_array($categories) )  {
            if( ! is_category( $categories ) ) return;
        }

        $q = "SELECT * FROM `" . $this->table_bannerize . "` WHERE `trash` = '0' ";

        if( $group != "") $q .= " AND `group` = '" . $group. "'";

        /**
         * Add random option
         *
         * @since 2.0.2
         */
        $q .= ($random == "" ) ? " ORDER BY `sorter` ASC" : "ORDER BY RAND()";

        /**
         * Limit rows number
         *
         * @since 2.0.0
         */
        if( $limit != "") $q .= " LIMIT 0," . $limit ;

        $rows = $wpdb->get_results( $q );

		if( count($rows) > 0 ) {
			echo $before_widget;

			echo '<div class="wp_bannerize">';

			// @since 2.4.3 - fix widget title output
			$title = apply_filters('widget_title', $instance['title']);
			if($title) {
				 echo $before_title . $title . $after_title;
			}
			echo $container_before;

			$even_before = $odd_before = $alternate_class = "";
			$index = 0;

			$odd_before = str_replace("%alt%", "", $before);
			$even_before = str_replace("%alt%", "", $before);
			if($alt_class != "") {
				$alternate_class = 'class="' . $alt_class . '"';
				$even_before = str_replace("%alt%", $alternate_class, $before);
			}
			$new_link_class = ($link_class != "") ? ' class="'.$link_class.'"' : "";

			foreach( $rows as $row ) {
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

			echo $o;

			echo $container_after;
			echo "</div>";
			echo $after_widget;
		}
    }

    /**
     * Update Widget options
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update( $new_instance, $old_instance ) {
        $instance                   = $old_instance;
        $instance['title']          = strip_tags($new_instance['title']);
        $instance['group']          = strip_tags($new_instance['group']);
        $instance['random'] 		= strip_tags($new_instance['random']);
        $instance['limit']          = strip_tags($new_instance['limit']);
        $instance['categories'] 	= ($new_instance['categories']);

        $instance['container_before'] 	= ($new_instance['container_before']);
        $instance['container_after'] 	= ($new_instance['container_after']);
        $instance['before'] 		= ($new_instance['before']);
        $instance['after']          = ($new_instance['after']);

        $instance['alt_class'] 		= ($new_instance['alt_class']);
        $instance['link_class']		= ($new_instance['link_class']);

        return $instance;
    }

    /**
     * Build the Widget interface - backend side
     *
     * @param array $instance
     */
    function form( $instance ) {
        $instance	= wp_parse_args( (array) $instance,
            array( 'title'      => '',
            'random'            => '0',
            'limit'             => '10',
            'container_before'  => '<ul>',
            'container_after'	=> '</ul>',
            'before'            => '<li %alt%>',
            'after'             => '</li>',
            'categories'        => array(),
            'alt_class'         => 'alt',
            'link_class'        => '' )
        );
        $title                  = strip_tags($instance['title']);
        $group                  = strip_tags($instance['group']);
        $random                 = ($instance['random']);
        $limit                  = strip_tags($instance['limit']);
        $categories             = ($instance['categories']);

        $container_before       = ($instance['container_before']);
        $container_after        = ($instance['container_after']);
        $before                 = ($instance['before']);
        $after                  = ($instance['after']);

        $alt_class              = strip_tags($instance['alt_class']);
        $link_class             = strip_tags($instance['link_class']);

        ?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-bannerize'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<p><label for="<?php echo $this->get_field_id('group'); ?>"><?php _e('Group:', 'wp-bannerize'); ?></label>
        <?php echo $this->get_group( $group ) ?></p>
<p><label for="<?php echo $this->get_field_id('random'); ?>"><?php _e('Random:', 'wp-bannerize'); ?></label>
    <input <?php echo ($random == '1') ? 'checked="chekced"' : '' ?> value="1" type="checkbox" name="<?php echo $this->get_field_name('random'); ?>" id="<?php echo $this->get_field_id('random'); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Show only for these Categories:', 'wp-bannerize'); ?></label></p>
<p><?php echo $this->get_categories_checkboxes($categories) ?></p>

<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Max:', 'wp-bannerize'); ?></label>
    <input type="text" value="<?php echo $limit ?>" name="<?php echo $this->get_field_name('limit'); ?>" id="<?php echo $this->get_field_id('limit'); ?>" /></p>
<p><strong>HTML Markup:</strong></p>
<p><label for="<?php echo $this->get_field_id('container_before'); ?>"><?php _e('container_before:', 'wp-bannerize'); ?></label>
    <input size="8" type="text" value="<?php echo $container_before ?>" name="<?php echo $this->get_field_name('container_before'); ?>" id="<?php echo $this->get_field_id('container_before'); ?>" /></p>

<p><label for="<?php echo $this->get_field_id('before'); ?>"><?php _e('before:', 'wp-bannerize'); ?></label>
    <input size="8" type="text" value="<?php echo $before ?>" name="<?php echo $this->get_field_name('before'); ?>" id="<?php echo $this->get_field_id('before'); ?>" />
    alt class: <input size="8" type="text" value="<?php echo $alt_class ?>" name="<?php echo $this->get_field_name('alt_class'); ?>" id="<?php echo $this->get_field_id('alt_class'); ?>" />
    (Es. &lt;li class="alt"&gt; ...)
</p>

<p><label for="<?php echo $this->get_field_id('link_class'); ?>"><?php _e('link_class:', 'wp-bannerize'); ?></label>
    <input size="8" type="text" value="<?php echo $link_class ?>" name="<?php echo $this->get_field_name('link_class'); ?>" id="<?php echo $this->get_field_id('link_class'); ?>" /></p>


<p><label for="<?php echo $this->get_field_id('after'); ?>"><?php _e('after:', 'wp-bannerize'); ?></label>
    <input size="8" type="text" value="<?php echo $after ?>" name="<?php echo $this->get_field_name('after'); ?>" id="<?php echo $this->get_field_id('after'); ?>" /></p>


<p><label for="<?php echo $this->get_field_id('container_after'); ?>"><?php _e('container_after:', 'wp-bannerize'); ?></label>
    <input size="8" type="text" value="<?php echo $container_after ?>" name="<?php echo $this->get_field_name('container_after'); ?>" id="<?php echo $this->get_field_id('container_after'); ?>" /></p>

    <?php
    }

    /**
     * Return HTML code (select/option) with all group/key retrive from
     * database
     *
     * @global object $wpdb
     * @param string $group
     * @return string
     */
    function get_group($group = '' ) {
        global $wpdb;
        $o = '<select rel="'.$group.'" id="' . $this->get_field_id('group') . '" name="' . $this->get_field_name('group')  . '">' .
            '<option value=""></option>';
        $q = "SELECT `group` FROM `" . $this->table_bannerize . "` GROUP BY `group` ORDER BY `group` ";
        $rows = $wpdb->get_results( $q );
        foreach( $rows as $row ) {
            $sel = ($group == $row->group) ? 'selected="selected"' : "" ;
            $o .= '<option ' . $sel . ' value="' . $row->group . '">' . $row->group . '</option>';
        }
        $o .= '</select>';
        return $o;
    }

    /**
     * Return HTML code (ul/li) with all Wordpress categories
     *
     * @param array $selected_cats
     * @return string
     */
    function get_categories_checkboxes( $selected_cats = null ) {

        $all_categories = get_categories();
        $o = '<ul style="margin-left:12px">';

        foreach($all_categories as $key => $cat) {
            if($cat->parent == "0") $o .= $this->_i_show_category($cat, $selected_cats);
        }
        return $o . '</ul>';
    }

    /**
     * Internal "iterate" recursive function. For build a tree of category
     * Parent/Child
     *
     * @param object $cat_object
     * @param array $selected_cats
     * @return string
     */
    function _i_show_category($cat_object, $selected_cats = null) {
       $checked = "";
       if(!is_null($selected_cats) && is_array($selected_cats)) {
           $checked = (in_array($cat_object->cat_ID, $selected_cats)) ? 'checked="checked"' : "";
       }
       $ou = '<li><label><input ' . $checked .' type="checkbox" name="' . $this->get_field_name('categories').'[]" value="'. $cat_object->cat_ID .'" /> ' . $cat_object->cat_name . '</label>';

       $childs = get_categories('parent=' . $cat_object->cat_ID);
       foreach($childs as $key => $cat) {
           $ou .= '<ul style="margin-left:12px">' . $this->_i_show_category($cat, $selected_cats) . '</ul>';
       }
       $ou .= '</li>';
       return $ou;
    }
}

?>