<?php
/**
 * Plugin Name: NGG Sidebar Widget
 * Plugin URI:
 * Description: A widget to show random galleries with or without preview image. Based on the NextGEN Gallery Sidebar Widget.
 * Author: H.-Peter Pfeufer
 * Version: 1.0.1
 * Author URI: http://blog.ppfeufer.de/
 */

/**
 * Changelog:
 *		= 1.0.1 =
 *		Added Screenshot
 *
 *		= 1.0.0 =
 *		Editied NextGEN Gallery Sidebar Widget
 *		Added option to show no images, ohnly Links to galleries
 *		Added german translation
 *		Upload to WP Directory
 */

add_action('widgets_init', create_function('', 'return register_widget("NGG_Sidebar_Widget");'));

class NGG_Sidebar_Widget extends WP_Widget {
	protected $_templates = array();

	function NGG_Sidebar_Widget() {
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('ngg-sidebar-widget', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages', dirname(plugin_basename(__FILE__)) . '/languages');
		}

		$widget_ops = array(
			'classname' => 'ngg-sidebar-widget',
			'description' => __('A widget to show random galleries with or without preview image. Based on the NextGEN Gallery Sidebar Widget.', 'ngg-sidebar-widget')
		);

		$this->WP_Widget('ngg-sidebar-widget', 'NGG Sidebar Widget', $widget_ops);
	}

	function widget($args, $instance) {
		global $wpdb;
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		switch($instance['gallery_order']) {
			case 'added_desc':
				$order = 'gid DESC';
				break;
			case 'added_asc':
				$order = 'gid ASC';
				break;
			default:
				$order = 'RAND()';
				break;
		}

		$excluded_galleries = array();
		$exc = explode(',', $instance['excluded_galleries']);
		foreach($exc as $ex) {
			$ex = trim($ex);
			if(is_numeric($ex)) {
				$excluded_galleries[] = $ex;
			}
		}

		$where = ' ';
		if(count($excluded_galleries) > 0) {
			$where = " WHERE gid NOT IN (" . implode(',', $excluded_galleries) . ")";
		}

		$results = $wpdb->get_results("SELECT * FROM $wpdb->nggallery" . $where . " ORDER BY " . $order . " LIMIT 0, " . $instance['max_galleries']);
		if(is_array($results) && count($results) > 0) {
			$galleries = array();
			foreach($results as $result) {
				if($wpdb->get_var("SELECT COUNT(pid) FROM $wpdb->nggpictures WHERE galleryid = '" . $result->gid . "'") > 0) {
					if($instance['gallery_thumbnail'] == 'preview' && (int) $result->previewpic > 0) {
						// ok
					} elseif($instance['gallery_thumbnail'] == 'random') {
						$result->previewpic = $wpdb->get_var("SELECT pid FROM $wpdb->nggpictures WHERE galleryid = '" . $result->gid . "' ORDER BY RAND() LIMIT 1");
					} else {
						// else take the first image
						$result->previewpic = $wpdb->get_var("SELECT pid FROM $wpdb->nggpictures WHERE galleryid = '" . $result->gid . "' ORDER BY sortorder ASC, pid ASC LIMIT 1");
					}

					$galleries[] = $result;
				}
			}

			if(count($galleries) > 0) {
				$outerTplFile = (file_exists($outerTplFile)) ? $outerTplFile : dirname(__FILE__) . '/templates/tpl.outer.html';

				if ($instance['gallery_thumbnail'] == 'none') {
					$innerTplFile = (file_exists($innerTplFile)) ? $innerTplFile : dirname(__FILE__) . '/templates/tpl.inner_no_thumb.html';
				} else {
					$innerTplFile = (file_exists($innerTplFile)) ? $innerTplFile : dirname(__FILE__) . '/templates/tpl.inner_thumb.html';
				}

				$outerTpl = file_get_contents($outerTplFile);
				$innerTpl = file_get_contents($innerTplFile);

				if(empty($outerTpl)) {
					$outerTpl = '{=inner}';
				}

				$this->parseTemplate('innerTpl', $innerTpl);

				$output = "\n";
				$output .= $args['before_widget'] . "\n";
				$output .= $args['before_title'] . $title . $args['after_title'] . "\n";

				$innerOutput = '';

				foreach($galleries as $gallery) {
					$imagerow = $wpdb->get_row("SELECT * FROM $wpdb->nggpictures WHERE pid = '" . $gallery->previewpic . "'");
					foreach($gallery as $key => $value) {
						$imagerow->$key = $value;
					}

					$image = new nggImage($imagerow);

					$tpl = array(
						'gallery' => (array) $gallery,
						'image' => (array) $image
					);

					if($gallery->pageid > 0) {
						$gallery_link = get_permalink($gallery->pageid);
					} elseif(!empty($instance['default_link'])) {
						$gallery_link = get_permalink($instance['default_link']);
					} else {
						$gallery_link = get_permalink(1);
					}

					$tpl['gallery']['link'] = $gallery_link;

					if(function_exists('getphpthumburl') && trim($instance['autothumb_params']) != '') {
						$tpl['image']['url'] = getphpthumburl($image->imageURL, $instance['autothumb_params']);
					} else {
						$tpl['image']['url'] = $image->thumbURL;
					}

					$tpl['image']['output_width'] = $instance['output_width'];
					$tpl['image']['output_height'] = $instance['output_height'];

					if(trim($instance['autothumb_params']) != '') {
						$tpl['image']['output_width_tag'] = '';
						$tpl['image']['output_height_tag'] = '';
					} else {
						$tpl['image']['output_width_tag'] = ' width="' . $instance['output_width'] . '"';
						$tpl['image']['output_height_tag'] = ' height="' . $instance['output_height'] . '"';
					}

					$innerOutput .= $this->renderTemplate('innerTpl', $tpl);
				}

				$output .= str_replace('{=inner}', $innerOutput, $outerTpl);
				$output .= "\n" . $args['after_widget'] . "\n";
				echo $output;
			}
		}
	}

	function renderTemplate($id, $values) {
		$output = '';
		if(isset($this->_templates[$id])) {
			$output = $this->_templates[$id]['template'];
			foreach($this->_templates[$id]['tags'] as $identifier => $val) {
				if(isset($values[$val[0]][$val[1]])) {
					$output = str_replace('{=' . $identifier . '}', $values[$val[0]][$val[1]], $output);
				}
			}
		}

		return $output;
	}

	function parseTemplate($id, $template) {
		$tags = array();
		$pattern = '#\{\=([a-zA-Z0-9\-\_\.]*)\.([a-zA-Z0-9\-\_\.]*)\}#';
		preg_match_all($pattern, $template, $matches);

		if(is_array($matches) && count($matches) > 0) {
			foreach($matches[0] as $key => $value) {
				$identifier = $matches[1][$key] . '.' . $matches[2][$key];
				$tags[$identifier][0] = $matches[1][$key];
				$tags[$identifier][1] = $matches[2][$key];
			}
		}

		$this->_templates[$id]['template'] = $template;
		$this->_templates[$id]['tags'] = $tags;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'title' => 'Galleries',
			'max_galleries' => 6,
			'gallery_order' => 'random',
			'gallery_thumbnail' => 'first',
			'autothumb_params' => '',
			'output_width' => 100,
			'output_height' => 75,
			'default_link' => 1,
			'excluded_galleries' => ''
		));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('max_galleries'); ?>"><?php _e('Maximum Galleries', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('max_galleries'); ?>" name="<?php echo $this->get_field_name('max_galleries'); ?>" value="<?php echo $instance['max_galleries']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('gallery_order'); ?>"><?php _e('Gallery Order', 'ngg-sidebar-widget'); ?></label><br />
			<select id="<?php echo $this->get_field_name('gallery_order'); ?>" name="<?php echo $this->get_field_name('gallery_order'); ?>">
				<option value="random" <?php echo ($instance['gallery_order'] == 'random') ? ' selected="selected"' : ''; ?>><?php _e('Random', 'ngg-sidebar-widget'); ?></option>
				<option value="added_asc" <?php echo ($instance['gallery_order'] == 'added_asc') ? ' selected="selected"' : ''; ?>><?php _e('Date added ASC', 'ngg-sidebar-widget'); ?></option>
				<option value="added_desc" <?php echo ($instance['gallery_order'] == 'added_desc') ? ' selected="selected"' : ''; ?>><?php _e('Date added DESC', 'ngg-sidebar-widget'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('gallery_thumbnail'); ?>"><?php _e('Gallery thumbnail image', 'ngg-sidebar-widget'); ?></label><br />
			<select id="<?php echo $this->get_field_name('gallery_thumbnail'); ?>" name="<?php echo $this->get_field_name('gallery_thumbnail'); ?>">
				<option value="none" <?php echo ($instance['gallery_thumbnail'] == 'none') ? ' selected="selected"' : ''; ?>><?php _e('No Thumbnail, only Link', 'ngg-sidebar-widget'); ?></option>
				<option value="preview" <?php echo ($instance['gallery_thumbnail'] == 'preview') ? ' selected="selected"' : ''; ?>><?php _e('Gallery Preview (set in NGG)', 'ngg-sidebar-widget'); ?></option>
				<option value="first" <?php echo ($instance['gallery_thumbnail'] == 'first') ? ' selected="selected"' : ''; ?>><?php _e('First', 'ngg-sidebar-widget'); ?></option>
				<option value="random" <?php echo ($instance['gallery_thumbnail'] == 'random') ? ' selected="selected"' : ''; ?>><?php _e('Random', 'ngg-sidebar-widget'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('autothumb_params'); ?>"><?php _e('Autothumb Parameters (if installed)', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('autothumb_params'); ?>" name="<?php echo $this->get_field_name('autothumb_params'); ?>" value="<?php echo $instance['autothumb_params']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('output_width'); ?>"><?php _e('Output width', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('output_width'); ?>" name="<?php echo $this->get_field_name('output_width'); ?>" value="<?php echo $instance['output_width']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('output_height'); ?>"><?php _e('Output height', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('output_height'); ?>" name="<?php echo $this->get_field_name('output_height'); ?>" value="<?php echo $instance['output_height']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('default_link'); ?>"><?php _e('Default Link Id (galleries without image page)', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('default_link'); ?>" name="<?php echo $this->get_field_name('default_link'); ?>" value="<?php echo $instance['default_link']?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('excluded_galleries'); ?>"><?php _e('Excluded gallery IDs (comma separated)', 'ngg-sidebar-widget'); ?></label><br />
			<input type="text" id="<?php echo $this->get_field_id('excluded_galleries'); ?>" name="<?php echo $this->get_field_name('excluded_galleries'); ?>" value="<?php echo $instance['excluded_galleries']?>" />
		</p>
	<?php
	}

	function update($new_instance, $old_instance) {
		$new_instance['title'] = htmlspecialchars($new_instance['title']);
		return $new_instance;
	}
}