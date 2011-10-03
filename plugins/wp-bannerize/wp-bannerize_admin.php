<?php
/**
 * Class for Manage Admin (back-end)
 *
 * @package         wp-bannerize
 * @subpackage      wp-bannerize_client
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 * 
 */
class WPBANNERIZE_ADMIN extends WPBANNERIZE_CLASS {

    function WPBANNERIZE_ADMIN() {
		// super
        $this->WPBANNERIZE_CLASS();

        /**
         * Load localizations if available
         *
         * @since 2.4.0
         */
		load_plugin_textdomain ( 'wp-bannerize' , false, 'wp-bannerize/localization'  );

        $this->init();
    }

    /**
     * Init the default plugin options and re-load from WP
     *
     * @since 2.2.2
     */
    function init() {
        /**
         * Add version control in options.
         * In questa maniera dalle release successive quando si chiama il
         * metodo checkTable() è possibile verificare la versione del plugin
         * precedente; senza impazzire con le DESC sulle tabelle.
         *
         */
        $this->options = array('wp_bannerize_version' => $this->version );
        add_option( $this->options_key, $this->options, $this->options_title );

        $this->options = get_option( $this->options_key );

        /**
         * Add option menu in Wordpress backend
         */
		add_action('admin_init', array( $this, 'plugin_init') );
        add_action('admin_menu', array( $this, 'plugin_setup') );

        /**
         * Update version control in options
         *
         * @since 2.2.2
         */
        update_option( $this->options_key, $this->options);
    }

	/**
	 * Register style for plugin
	 *
	 * @since 2.4.9
	 * @return void
	 */
	function plugin_init() {
		wp_register_style('wp-bannerize-style-css', $this->uri . "/css/wp_bannerize_admin.css");
		wp_register_style('fancybox-css', $this->uri . "/js/fancybox/jquery.fancybox-1.3.1.css");
	}

	/**
	 * Execute when plugin is showing on backend
	 *
	 * @since 2.4.9
	 * @return void
	 */
	function plugin_admin_scripts() {
 		/**
         * Add wp_enqueue_script for jquery library
         *
         * @since 2.3.6
         */
        wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_script ( 'fancybox_js' , $this->uri . '/js/fancybox/jquery.fancybox-1.3.1.pack.js' , array ( 'jquery' ) , '1.4' , true );
		wp_enqueue_script ( 'wp_bannerize_admin_js' , $this->uri . '/js/wp_bannerize_admin.js' , array ( 'jquery' ) , '1.4' , true );

        /**
         * Add main admin javascript
         *
         * @since 2.4.0
         */
		wp_localize_script ( 'wp_bannerize_admin_js' , 'wpBannerizeMainL10n' , array (
                                                    'ajaxURL' => $this->ajax_sorter,
													'messageConfirm' => __( 'WARINING!! Do you want delete this banner?'  , 'wp-bannerize' )
													) );
	}

	/**
	 * Execute when plugin is showing on backend
	 *
	 * @return void
	 */
	function plugin_admin_styles() {
        wp_enqueue_style('fancybox-css');
        wp_enqueue_style('wp-bannerize-style-css');
	}

    /**
     * Setup main init: add hook for backend
     *
	 * @revision 2.4.9
     */
    function plugin_setup() {

		if (function_exists('add_menu_page')) {
			$plugin_page = add_menu_page( $this->plugin_name, $this->plugin_name, 7, $this->directory.'-settings', array(&$this, 'show_banners'), $this->uri . "/css/images/wp-bannerize-16x16.png" );
		}
		if (function_exists('add_submenu_page')) {
			add_submenu_page( $this->directory.'-settings', __('Edit', 'wp-bannerize'), __('Edit', 'wp-bannerize'), 7, $this->directory.'-settings', array(&$this, 'show_banners') );
			$add_new_banner_item = add_submenu_page( $this->directory.'-settings', __('Add New', 'wp-bannerize'), __('Add New', 'wp-bannerize'), 7, $this->directory.'-addnew', array(&$this, 'add_new_banner') );
		}

		add_action( 'admin_print_scripts-'. $plugin_page, array($this, 'plugin_admin_scripts') );
		add_action( 'admin_print_scripts-'. $add_new_banner_item, array($this, 'plugin_admin_scripts') );
		add_action( 'admin_print_styles-'. $plugin_page, array($this, 'plugin_admin_styles') );
		add_action( 'admin_print_styles-'. $add_new_banner_item, array($this, 'plugin_admin_styles') );

        /**
         * Add contextual Help
         */
        if (function_exists('add_contextual_help')) {
            add_contextual_help( $plugin_page ,'<p><strong>New from 2.6.0</strong></p>'.
				'<p>Now, you can use Wordpress shortcode in your post, like:</p>' .
				'<pre>[wp-bannerize limit="2"]</pre>' .
				'<p>Using the same args parameters</p>' .	
			    '<strong>'.__('Use', 'wp-bannerize').':</strong></p>' .
                '<pre>wp_bannerize();</pre>or<br/>' .
                '<pre>wp_bannerize( \'group=a&limit=10\' );</pre>or<br/>' .
                '<pre>wp_bannerize( \'group=a&limit=10&random=1\' );</pre>or<br/>' .
                '<pre>wp_bannerize( \'group=a&limit=10&random=1&before=&lt;li %alt%>&alt_class=pair\' );</pre><br/>' .
                '<pre>
* group               If \'\' show all groups, else show the selected group code (default \'\')
* container_before    Main tag container open (default &lt;ul&gt;)
* container_after     Main tag container close (default &lt;/ul&gt;)
* before              Before tag banner open (default &lt;li %alt% &gt;) see alt_class below
* after               After tag banner close (default &lt;/li&gt;) 
* random              Show random banner sequence (default \'\')
* categories          Category ID separated by commas (defualt \'\')
* alt_class           class alternate for "before" TAG (use before param)
* link_class          Additional class for link TAG A
* limit               Limit rows number (default \'\' - show all rows)</pre>' 			
            );
        }
    }

	/**
	 * Add new banner Panel
	 *
	 * @return void
	 */
	function add_new_banner() {
		global $wpdb;

		if(isset($_POST['command_action']) && $_POST['command_action'] == "insert") {
			$any_error = $this->insertBanner();
		}

		if( $any_error != '') : ?>
        	<div id="message" class="updated fade"><p><?php echo $any_error ?></p></div>
        <?php endif; ?>

<div class="wrap">
	<div class="wp_saidmade_box">
		<p class="wp_saidmade_copy_info"><?php _e('For more info and plugins visit', 'wp-bannerize') ?> <a href="http://www.saidmade.com">Saidmade</a></p>
		<a class="wp_saidmade_logo" href="http://www.saidmade.com/prodotti/wordpress/wp-bannerize/">
			<?php echo $this->plugin_name ?> ver. <?php echo $this->version ?>
		</a>
	</div>
	
	<div id="poststuff" class="metabox-holder">

		<div class="sm-padded">
			<div class="postbox">
				<h3><span><?php  _e('Insert new Banner', 'wp-bannerize')?></span></h3>

				<div class="inside">
					<form class="form_box" name="insert_bannerize" method="post" action=""
						  enctype="multipart/form-data">
						<input type="hidden" name="command_action" value="insert"/>
						<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>

						<table class="form-table wp_bannerize">
							<tr>
								<th scope="row">
									<label for="filename"><?php _e('Image', 'wp-bannerize')?>:</label>
								</th>
								<td><input type="file" name="filename" id="filename" /></td>
							</tr>
							<tr>
								<th scope="row">
									<label for="group"><?php _e('Group', 'wp-bannerize')?>:</label>
								</th>
								<td>
									<input type="text" maxlength="128" name="group" id="group" value="group" /> <?php echo $this->get_combo_group() ?> (<?php _e('Insert a key max 128 chars', 'wp-bannerize')?>)
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="description"><?php _e('Description', 'wp-bannerize')?>:</label>
								</th>
								<td>
									<input type="text" name="description" id="description" /> <input type="checkbox" name="use_description" value="1" /> <?php _e('Use this description in output', 'wp-bannerize') ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="url"><?php _e('URL:', 'wp-bannerize') ?></label></th>
								<td>
									<input type="text" name="url" id="url" /> <label for="target"><?php _e('Target:', 'wp-bannerize')?></label> <?php echo $this->get_target_combo() ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="nofollow"><?php _e('Add “nofollow“ attribute', 'wp-bannerize') ?></label></th>
								<td><input type="checkbox" name="nofollow" id="nofollow" value="1" checked="checked" /></td>
							</tr>
						</table>
						<p class="submit">
							<input class="button-primary" type="submit" value="<?php _e('Insert', 'wp-bannerize')?>"/>
						</p>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
		<?php
	}

    /**
     * Draw Options Panel
	 *
	 * TODO:	finire lo show
	 * 
     */
    function show_banners() {
        global $wpdb;

		$this->options = get_option( $this->options_key );

		if( isset( $_POST['form_action'] ) && $_POST['form_action'] != "" ) {
			switch( $_POST['form_action'] ) {
				case "convert":
					$any_error = $this->convertDatabse();
					break;
				case "nothing":
					$this->options['todo_upgrade'] = "no";
					update_option( $this->options_key, $this->options);
					header("Location: admin.php?page=wp-bannerize-settings");
					break;
				case "deleteall":
					$any_error = $this->dropOldDatabaseTableAndFiles();
					break;
			}
		} else if(isset($this->options['todo_upgrade']) && $this->options['todo_upgrade'] == "yes") : ?>
			<div class="wrap">
				<div class="wp_saidmade_box">
					<p class="wp_saidmade_copy_info"><?php _e('For more info and plugins visit', 'wp-bannerize') ?> <a href="http://www.saidmade.com">Saidmade</a></p>
					<a class="wp_saidmade_logo" href="http://www.saidmade.com/prodotti/wordpress/wp-bannerize/">
						<?php echo $this->plugin_name ?> ver. <?php echo $this->version ?>
					</a>
				</div>
				<h3 class="wp_bannerize_alert"><?php _e('Warning!','wp-bannerize') ?></h3>
				<p class="wp_bannerize_alert"><?php _e('This WP Bannerize release has a different Database Table struct. WP Bannerize has found an old Database Table. Do you want convert old struct for this release?','wp-bannerize') ?></p>
				<form class="wp_bannerize_form_action" method="post" action="">
					<input type="hidden" name="form_action" value="convert"/>
					<input type="submit" value="<?php _e('Convert Database Table', 'wp-bannerize') ?>" />
				</form>
				<form class="wp_bannerize_form_action" method="post" action="">
					<input type="hidden" name="form_action" value="nothing" />
					<input type="submit" value="<?php _e('Do nothing', 'wp-bannerize') ?>" />
				</form>
				<form class="wp_bannerize_form_action" method="post" action="">
					<input type="hidden" name="form_action" value="deleteall" />
					<input type="submit" value="<?php _e('Delete Database and Images', 'wp-bannerize') ?>" />
				</form>
			</div>

		<?php else : ?>
<?php if( isset( $_POST['command_action'] ) && $_POST['command_action'] != "" ) {
            switch( $_POST['command_action'] ) {
				case "trash":
                    $any_error = $this->setBannerToTrash();
                    break;
				case "untrash":
                    $any_error = $this->unsetBannerToTrash();
                    break;
                case "delete":
                    $any_error = $this->deleteBanner();
                    break;
                case "update":
                    $any_error = $this->updateBanner();
                    break;
            }
        }

		if( $any_error != '') : ?>
        	<div id="message" class="updated fade"><p><?php echo $any_error ?></p></div>
        <?php endif; ?>

<div class="wrap">

	<div class="wp_saidmade_box">
		<p class="wp_saidmade_copy_info"><?php _e('For more info and plugins visit', 'wp-bannerize') ?> <a href="http://www.saidmade.com">Saidmade</a></p>
		<a class="wp_saidmade_logo" href="http://www.saidmade.com/prodotti/wordpress/wp-bannerize/">
			<?php echo $this->plugin_name ?> ver. <?php echo $this->version ?>
		</a>
	</div>

	<a class="button add-new-h2" href="?page=wp-bannerize-addnew"><?php _e('Add New', 'wp-bannerize') ?></a>

	<?php
		// Actions
		if(isset($_POST['action']) || isset($_POST['action2']) || isset($_GET['action']) || isset($_GET['action2'] ) ) {
			if(isset($_POST['action']) && $_POST['action'] != "-1" || isset($_POST['action2']) && $_POST['action2'] != "-1" ) {
				$action = ($_POST['action'] != "-1") ? $_POST['action'] : $_POST['action2'];
			} else {
				$action = ($_GET['action'] != "-1") ? $_GET['action'] : $_GET['action2'];
			}
			switch($action) {
				case "trash-selected":
					if(isset($_POST['image_record'])) {
						$id = implode(",", $_POST['image_record']);
						$this->setBannerToTrash($id);
					}
					break;
				case "delete-selected":
					if(isset($_POST['image_record'])) {
						if( is_array($_POST['image_record']) ) foreach($_POST['image_record'] as $id) $this->deleteBanner($id);
					}
					break;
				case "restore-selected":
					if(isset($_POST['image_record'])) {
						$id = implode(",", $_POST['image_record']);
						$this->unsetBannerToTrash($id);
					}
					break;
			}
		}
	?>

	<?php
		$pagenum = ($_GET['pagenum'] == '' ? 1 : $_GET['pagenum']);
		$limit = ($_REQUEST['combo_pagination_filter'] == '' ? 10 : $_REQUEST['combo_pagination_filter']);
		$where = "1";
		$count = array();

		// Build where condictions
		if( isset($_GET['trash']) && $_GET['trash'] != "") {
			$where = sprintf("%s AND trash = '%s'", $where, $_GET['trash']);
		} else {
			$where = "1 AND trash = '0'";
		}

		if( isset($_REQUEST['combo_group_filter']) && $_REQUEST['combo_group_filter'] != "") {
			$where = sprintf("%s AND `group` = '%s'", $where, $_REQUEST['combo_group_filter']);
		}

		// All Total records
		$sql = sprintf("SELECT COUNT(*) AS all_record FROM %s", $this->table_bannerize);
		$result = $wpdb->get_row($sql);
		$count['All'] = intval($result->all_record);

		// Trash
		$sql = sprintf("SELECT COUNT(*) AS trashed FROM %s WHERE trash = '1'", $this->table_bannerize);
		$result = $wpdb->get_row($sql);
		$count['Trash'] = intval($result->trashed);

		$count['Publish'] = $count['All'] - $count['Trash'];

		// Count record with where conditions
		$sql = sprintf("SELECT COUNT(*) AS showing FROM %s WHERE %s", $this->table_bannerize, $where);
		$result = $wpdb->get_row($sql);
		$count['showing'] = $result->showing;

		$num_pages = ceil($count['showing'] / $limit);

		// GET query fields
		$query_search = array( 'trash' => $_GET['trash'], 'combo_group_filter' => $_REQUEST['combo_group_filter'], 'combo_pagination_filter' => $_REQUEST['combo_pagination_filter'] );

		$arraytolink = array_merge(array('edit' => null, 'pagenum' => '%#%'), $query_search);

		$page_links = paginate_links(array(
			'base' => add_query_arg($arraytolink),
			'format' => 'page=wp-bannerize-settings',
			'total' => $num_pages,
			'current' => $pagenum));
		?>
		<form method="post" name="form_show" action="" id="posts-filter">
			<input type="hidden" name="id" />
			<input type="hidden" name="command_action" value="update" />
			<input type="hidden" name="page" value="wp-bannerize-settings" />
			<input type="hidden" name="status" value="<?php echo ( isset($_GET['trash']) ? $_GET['trash'] : "" ) ?>" />

			<ul class="subsubsub">
			<?php
				$links = array();
				$status_links = array( "Publish" => "0", "Trash" => "1");
				foreach($status_links as $status => $value) {
					if($count[$status] > 0 ) {
						$current = "";
						$addurl = "";
						if( (isset($_GET['trash']) && $_GET['trash'] == $value) || (!isset($_GET['trash']) && $value == "0") ) {
							$current = 'class="current"';
						}
						if($value != "") {
							$addurl = "&trash=" . $value;
						}
						$links[] = sprintf("<li><a %s href=\"?page=wp-bannerize-settings%s\">%s <span class=\"count\">(%s)</span></a>", $current, $addurl, __($status, 'wp-bannerize'), $count[$status]);
					}
				}
				$output = implode('| </li>', $links) . '</li>';
				echo $output;
			?>
			</ul>

			<?php if($count["showing"] > 0 ) : ?>

				<div class="tablenav">

					<div class="alignleft actions">
						<select name="action">
							<option value="-1"><?php _e('Actions', 'wp-bannerize') ?></option>
							<?php if(!isset($_GET['trash']) || $_GET['trash'] == "0") : ?>
								<option value="trash-selected"><?php _e('Trash', 'wp-bannerize') ?></option>
							<?php elseif(isset($_GET['trash']) && $_GET['trash'] == "1") : ?>
								<option value="restore-selected"><?php _e('Restore', 'wp-bannerize') ?></option>
								<option value="delete-selected"><?php _e('Delete', 'wp-bannerize') ?></option>
							<?php endif; ?>
						</select>
						<input type="submit" class="button-secondary action" id="doaction" name="doaction" value="<?php _e('Apply', 'wp-bannerize') ?>" />

						<?php echo $this->combo_group_filter(); $this->combo_pagination_filter() ?> <input type="submit" class="button-secondary action" value="<?php _e('Filter', 'wp-bannerize') ?>" />

					</div>

					<div class="tablenav-pages">
						<span class="displaying-num"><?php printf(__("Showing %s-%s of %s", 'wp-bannerize'), $pagenum, ($count['showing'] > $limit ? $limit : $count['showing']), $count['showing']) ?></span>
						<?php echo $page_links ?>
					</div>
					<div class="clear"></div>
				</div>

				<div class="clear"></div>

				<table rel="<?php echo $pagenum . "," . $limit ?>" id="wp_bannerize_list" cellspacing="0" class="widefat">
					<thead>
					<tr>
						<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
						<th class="manage-column" scope="col"></th>
						<th class="manage-column column-image" scope="col"><?php _e('Image', 'wp-bannerize') ?></th>
						<th class="manage-column column-key" scope="col"><?php _e('Group', 'wp-bannerize') ?></th>
						<th class="manage-column column-description" scope="col"><?php _e('Description', 'wp-bannerize') ?></th>
						<th class="manage-column column-url" scope="col"><?php _e('URL', 'wp-bannerize') ?></th>
						<th style="" class="manage-column column-clickcount num" scope="col"><div class="vers"><img src="images/comment-grey-bubble.png" alt="<?php _e('Count Click', 'wp-bannerize') ?>"></div></th>
					</tr>
					</thead>

					<tfoot>
					<tr>
						<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
						<th class="manage-column" scope="col"></th>
						<th class="manage-column column-image" scope="col"><?php _e('Image', 'wp-bannerize') ?></th>
						<th class="manage-column column-key" scope="col"><?php _e('Group', 'wp-bannerize') ?></th>
						<th class="manage-column column-description" scope="col"><?php _e('Description', 'wp-bannerize') ?></th>
						<th class="manage-column column-url" scope="col"><?php _e('URL', 'wp-bannerize') ?></th>
						<th class="manage-column column-clickcount num" scope="col"><div class="vers"><img src="images/comment-grey-bubble.png" alt="<?php _e('Count Click', 'wp-bannerize') ?>"></div></th>
					</tr>
					</tfoot>

					<tbody>
					<?php
						$alt = 0;
						$sql = sprintf("SELECT * FROM %s WHERE %s ORDER BY `sorter`, `group` ASC LIMIT %s,%s", $this->table_bannerize, $where, (($pagenum-1) * $limit), $limit);
						$row = $wpdb->get_results($sql);
						foreach($row as $item) : ?>
							<tr <?php echo ($alt++ %2) ? 'class="alternate"' : "" ?> id="item_<?php echo $item->id ?>">
								<th class="check-column" scope="row"><input type="checkbox" value="<?php echo $item->id ?>" name="image_record[]" /></th>
								<th scope="row"><div class="arrow"></div></th>
								<td class="wp-bannerize-thumbnail">
									<?php if($item->mime == "application/x-shockwave-flash") : ?>
										<a class="fancybox wp_bannerize_flash" rel="wp-bannerize-gallery" title="<?php echo $item->description ?>" href="<?php echo $item->filename ?>"></a>
									<?php else : ?>
										<a class="fancybox" rel="wp-bannerize-gallery" href="<?php echo $item->filename ?>" title="<?php echo $item->description ?>"><img alt="<?php echo $item->description ?>" border="0" src="<?php echo $item->filename ?>" /></a>
									<?php endif; ?>
								</td>
								<td nowrap="nowrap"><?php echo $item->group ?></td>
								<td width="100%"><?php echo $item->description ?>
									<div class="row-actions">
										<?php if($item->trash == "0") : ?>
											<span class="edit"><a onclick="SMWPBannerizeJavascript.showInlineEdit('div#edit_<?php echo $item->id ?>', '<?php $this->inlineEdit($item) ?>')" class="edit_<?php echo $item->id ?>" title="<?php _e('Edit', 'wp-bannerize') ?>" href="#"><?php _e('Edit', 'wp-bannerize') ?></a> | </span>
											<span class="trash"><a class="<?php echo $item->id ?>" title="<?php _e('Trash', 'wp-bannerize') ?>" href="#"><?php _e('Trash', 'wp-bannerize') ?></a> | </span>
											<span class="view"><a class="fancybox submitview" rel="wp-bannerize-gallery" title="<?php echo $item->description ?>"" href="<?php echo $item->filename ?>"><?php _e('View', 'wp-bannerize') ?></a></span>
										<?php else : ?>
											<span class="delete"><a class="<?php echo $item->id ?>" title="<?php _e('Delete', 'wp-bannerize') ?>" href="#"><?php _e('Delete', 'wp-bannerize') ?></a> | </span>
											<span class="restore"><a class="<?php echo $item->id ?>" title="<?php _e('Restore', 'wp-bannerize') ?>" href="#"><?php _e('Restore', 'wp-bannerize') ?></a></span>
										<?php endif; ?>
									</div>
									<div id="edit_<?php echo $item->id ?>"></div>
								</td>
								<td nowrap="nowrap"><?php echo $item->url ?></td>
								<td class="comments column-comments">
									<div class="post-com-count-wrapper">
										<div class="post-com-count">
											<span><?php echo $item->clickcount ?></span>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach;
					?>
					</tbody>
				</table>

				<div class="tablenav">

					<div class="alignleft actions">
						<select name="action2">
							<option value="-1"><?php _e('Actions', 'wp-bannerize') ?></option>
							<?php if(!isset($_GET['trash']) || $_GET['trash'] == "0") : ?>
								<option value="trash-selected"><?php _e('Trash', 'wp-bannerize') ?></option>
							<?php elseif(isset($_GET['trash']) && $_GET['trash'] == "1") : ?>
								<option value="restore-selected"><?php _e('Restore', 'wp-bannerize') ?></option>
								<option value="delete-selected"><?php _e('Delete', 'wp-bannerize') ?></option>
							<?php endif; ?>
						</select>
						<input type="submit" class="button-secondary action" id="doaction2" name="doaction" value="<?php _e('Apply', 'wp-bannerize') ?>" />
					</div>

					<div class="tablenav-pages">
						<span class="displaying-num"><?php printf(__("Showing %s-%s of %s", 'wp-bannerize'), $pagenum, ($count['showing'] > $limit ? $limit : $count['showing']), $count['showing']) ?></span>
						<?php echo $page_links ?>
					</div>
					<div class="clear"></div>
				</div>

			<?php else : ?>
				<div class="clear"></div>
				<p><?php _e('No Banner found!', 'wp-bannerize') ?></p>
			<?php endif; ?>
		</form>

		<form name="wp_bannerize_action" method="post" action="">
			<input type="hidden" name="command_action" value="" />
			<input type="hidden" name="id" />
		</form>

	</div>
	<?php endif; ?>
    <?php
    }

	/**
	 * Show hide form for inline edit in banner list
	 *
	 * @param  $row
	 * @return void
	 */
	function inlineEdit($row) {
		$o = '<div class="inline-edit" style="display:none">' .
		'<label>' .  __('Group') . ':</label> <input size="8" type="text" id="group" name="group" value="' .    $row->group  . '" /> ' . $this->get_combo_group() .
		'<br/><label>' .  __('Description') . ':</label> <input size="32" type="text" name="description" value="' . $row->description . '" /> ' .
		'<input ' . ( ($row->use_description == '1') ? 'checked="checked"' : '' ) . ' type="checkbox" name="use_description" value="1" /> ' . __('Use this description in output', 'wp-bannerize') . '<br/>' .		
		'<label>' .  __('URL') . ':</label> <input type="text" name="url" size="32" value="' . $row->url . '" /> ' .
		'<label style="float:none;display:inline">' . __('Target') . ':</label> ' . $this->get_target_combo( $row->target ) .
		'<br/><label for="clickcount" style="float:none;display:inline">' . __('Click Counter:', 'wp-bannerize') . '</label>' .
		'<input size="4" class="number" type="text" name="clickcount" id="clickcount" value="' . $row->clickcount . '" /> ' .		
		'<label for="nofollow" style="float:none;display:inline">' . __('Add nofollow attribute', 'wp-bannerize') . '</label>' .
		'<input ' . ( ($row->nofollow == '1') ? 'checked="checked"' : '' ) . ' type="checkbox" name="nofollow" id="nofollow" value="1" /><br/>' .
		'<label for="width" style="float:none;display:inline">' . __('Width:', 'wp-bannerize') . '</label> ' .
		'<input size="4" type="text" name="width" id="width" value="' . $row->width . '" /> ' .
		'<label for="height" style="float:none;display:inline">' . __('Height:', 'wp-bannerize') . '</label>' .
		'<input size="4" type="text" name="height" id="height" value="' . $row->height . '" /> ' .
		'<p class="submit inline-edit-save">' .
		'<a onclick="SMWPBannerizeJavascript.hideInlineEdit(' . $row->id . ')" class="button-secondary cancel alignleft" title="' .  __('Cancel') . '" href="#" accesskey="c">' .  __('Cancel') . '</a>' .
		'<a onclick="SMWPBannerizeJavascript.update(' . $row->id . ')" class="button-primary save alignright" title="' .  __('Update') . '" href="#" accesskey="s">' .  __('Update') . '</a>'.
		'</p>' .
		'</div>';
		echo esc_html(addslashes( $o ));
	}

    /**
     * Build the select/option filter group
     *
     * @return
     */
    function combo_group_filter() {
        global $wpdb;
        $o = '<select name="combo_group_filter">' .
            '<option value="">'.__('All groups').'</option>';
        $q = "SELECT `group` FROM `" . $this->table_bannerize . "` GROUP BY `group` ORDER BY `group` ";
        $rows = $wpdb->get_results( $q );
        $sel = "";
        foreach( $rows as $row ) {
            if( $_REQUEST['combo_group_filter'] == $row->group ) $sel = 'selected="selected"'; else $sel = "";
            $o .= '<option ' . $sel . 'value="' . $row->group . '">' . $row->group . '</option>';
        }
        $o .= '</select>';
        echo $o;
    }

	function combo_pagination_filter() { ?>
		<select name="combo_pagination_filter">
			<option <?php echo ($_REQUEST['combo_pagination_filter'] == "10") ? 'selected="selected"' : "" ?> value="10">10</option>
			<option <?php echo ($_REQUEST['combo_pagination_filter'] == "20") ? 'selected="selected"' : "" ?> value="20">20</option>
			<option <?php echo ($_REQUEST['combo_pagination_filter'] == "50") ? 'selected="selected"' : "" ?> value="50">50</option>
		</select>
	<?php
	}

	/**
	 * Build combo group
	 * 
	 * @return string
	 */
    function get_combo_group() {
        global $wpdb;
        $o = '<select id="group_filter">' .
            '<option value=""></option>';
        $q = "SELECT `group` FROM `" . $this->table_bannerize . "` GROUP BY `group` ORDER BY `group` ";
        $rows = $wpdb->get_results( $q );
        $sel = "";
        foreach( $rows as $row ) {
            $o .= '<option value="' . $row->group . '">' . $row->group . '</option>';
        }
        $o .= '</select>';
        return $o;
    }

    /**
     * Get Select Checked Categories
     */
    function get_categories_checkboxes( $cats = null ) {
        if(!is_null($cats)) $cat_array = explode(",", $cats);
        $res = get_categories();
        $o = "";
        foreach($res as $key => $cat) {
            $checked = "";
            if(!is_null($cats)) {
                if( in_array( $cat->cat_ID, $cat_array) )
                    $checked = 'checked="checked"';
            }
            $o .= '<label><input ' . $checked .' type="checkbox" name="categories[]" id="categories[]" value="'. $cat->cat_ID .'" /> ' . $cat->cat_name . '</label> ';
       }
       return $o;
    }

    /**
     * Build combo menu for target
     *
     * @return
     */
    function get_target_combo( $sel = "_blank") {
        $o = '<select name="target" id="target">' .
				'<option></option>' .
				'<option ' . ( ($sel=='_blank')?'selected="selected"':'' ) . '>_blank</option>' .
				'<option ' . ( ($sel=='_parent')?'selected="selected"':'' ) . '>_parent</option>' .
				'<option ' . ( ($sel=='_self')?'selected="selected"':'' ) . '>_self</option>' .
				'<option ' . ( ($sel=='_top')?'selected="selected"':'' ) . '>_top</option>' .
				'</select>';
        return $o;
    }

    /**
     * Esegue l'upload e lo store nel database
     *
     * Array ( [name] 		=> test.pdf
     * 		   [type]		=> application/pdf
     * 		   [tmp_name] 	=> /tmp/phpcXS1lh
     *    	   [error] 		=> 0
     *    	   [size] 		=> 277304 )
     *
     * @return
     */
    function insertBanner() {
        global $wpdb;

        // check post error
		if( $_FILES['filename']['error'] == 0 ) {
			$size           = floor( $_FILES['filename']['size'] / (1024*1024) );
			$mime           = $_FILES['filename']['type'];
			$name           = $_FILES['filename']['name'];
			$temp           = $_FILES['filename']['tmp_name'];

			$group          	= $_POST['group'];
			$description 		= $_POST['description'];
			$use_description 	= $_POST['use_description'];
			$url            	= $_POST['url'];
			$target 	 		= $_POST['target'];
			$nofollow	 		= $_POST['nofollow'];
			$dimensions			= array('0','0');

			$uploads = wp_upload_bits( strtolower($name), null, '' );

			if ( move_uploaded_file( $_FILES['filename']['tmp_name'], $uploads['file'] )) {
				if(function_exists('getimagesize'))	$dimensions = getimagesize($uploads['file']);
				$sql = sprintf("INSERT INTO %s (`group`, `description`, `use_description`, `url`, `filename`, `target`, `nofollow`, `mime`, `realpath`, `width`, `height`) ".
										"VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s)", $this->table_bannerize, $group, $description, $use_description, $url,
										$uploads['url'], $target, $nofollow, $mime, $uploads['file'], $dimensions[0], $dimensions[1]);
				$wpdb->query($sql);
				return "";
			} else {
				$error = sprintf( __('Error while copying [%s] [%s bytes] - [%s]','wp-bannerize'), $_FILES['filename']['name'],  $_FILES['filename']['size'], $_FILES['filename']['error'] );
				return $error;
			}
		} else {
			$error = sprintf( __('No file to upload! - [%s]','wp-bannerize'), $_FILES['filename']['error'] );
			return $error;
		}
    }

	/**
	 * Set one or more banner in trash mode: trash = "1"
	 *
	 * @param  $id		string|array
	 * @return void
	 */
	function setBannerToTrash($id = null) {
		global $wpdb;
		$id = ( is_null($id) ) ? $_POST['id'] : $id;
		$sql = sprintf("UPDATE %s SET trash = '1' WHERE id IN(%s)", $this->table_bannerize, $id);
		$wpdb->query($sql);
	}

	/**
	 * Set one or more banner in publish mode: trash = "0"
	 *
	 * @param  $id		string|array
	 * @return void
	 */
	function unsetBannerToTrash($id = null) {
		global $wpdb;
		$id = ( is_null($id) ) ? $_POST['id'] : $id;
		$sql = sprintf("UPDATE %s SET trash = '0' WHERE id IN(%s)", $this->table_bannerize, $id);
		$wpdb->query($sql);
	}

    /**
     * Delete (permanently) a banner from Database and filesystem. Because a banner is delete from disk, this method
	 * is call from loop for delete more banner
     *
     * @return
     */
    function deleteBanner($id = null) {
        global $wpdb;
		$id = ( is_null($id) ) ? $_POST['id'] : $id;
        $filename = $wpdb->get_var( "SELECT realpath FROM `" . $this->table_bannerize . "` WHERE `id` = " . $id );
        @unlink( $filename );

        $q = "DELETE FROM `" . $this->table_bannerize . "` WHERE `id` = " .$id;
        $wpdb->query($q);
    }

    /**
     * Update a banner. Only db data
     *
     * @return 		(not used)
     */
    function updateBanner() {
        global $wpdb;
		$sql = sprintf("UPDATE %s SET `group` = '%s', `description` = '%s', `url` = '%s', `target` = '%s', `use_description` = '%s', `nofollow` = '%s', `clickcount` = '%s', `width` = '%s', `height` = '%s' WHERE id = %s",
					$this->table_bannerize, $_POST['group'], $_POST['description'], $_POST['url'], $_POST['target'], $_POST['use_description'], $_POST['nofollow'], $_POST['clickcount'], $_POST['width'], $_POST['height'], 
					$_POST['id']);
        $wpdb->query($sql);
    }

    /**
     * Attach settings in Wordpress Plugins list
     */
    function register_plugin_settings( $pluginfile ) {
        $this->plugin_file = $pluginfile;
        add_action( 'plugin_action_links_' . basename( dirname( $pluginfile ) ) . '/' . basename( $pluginfile ), array( &$this, 'plugin_settings' ), 10, 4 );
        add_filter( 'plugin_row_meta',  array(&$this, 'add_plugin_links'), 10, 2);
    }

    /**
     * Add link to Plugin list page
     *
     * @param <type> $links
     * @return string
     */
    function plugin_settings( $links ) {
        $settings_link = '<a href="admin.php?page=wp-bannerize-settings">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
	 * Add links on installed plugin list
	 */
	function add_plugin_links($links, $file) {
        if( $file == plugin_basename( $this->plugin_file ) ) {
            $links[] = '<strong style="color:#fa0">' . __('For more info and plugins visit', 'wp-photo-iphone') . ' <a href="http://www.saidmade.com">Saidmade</a></strong>';
        }
		return $links;
	}

	/**
	 * Call on Plugin Activation
	 *
	 * @since 2.5.0
	 *
	 * @return void
	 */
	function activation_hook() {
		global $wpdb;
		// Table doesn't exists: create it
		$this->createTable();

		$this->checkNeedUpdateFromPreviousDatabase();
		
	}

	/**
	 * Thi smethod is call from "active" Plugin. Check if exists a database table named "$this->old_table_bannerize".
	 * If the table exists, then set a special flag in option for ask to the user three options: convert, do nothing
	 * and remove old database table.
	 * See method above for action hook callback.
	 *
	 * @return void
	 */
	function checkNeedUpdateFromPreviousDatabase() {
		global $wpdb;
		if($wpdb->get_var("SHOW TABLES LIKE '$this->old_table_bannerize'") == $this->old_table_bannerize ) {
			$this->options['todo_upgrade'] = "yes";
		} else {
			$this->options['todo_upgrade'] = "no";
		}
		update_option( $this->options_key, $this->options);
	}

	/**
	 * Read all records from WP Bannerize table previous 2.5.0 release and insert these records into new database table
	 *
	 * @return void
	 */
	function convertDatabse() {
		global $wpdb;

		$dimensions			= array('0','0');

		$sql = sprintf("SELECT * FROM `%s`", $this->old_table_bannerize);
		$old = $wpdb->get_results($sql);
		foreach($old as $olditem) {
			if(function_exists('getimagesize'))	$dimensions = getimagesize( $olditem->realpath );
			$sql = sprintf("INSERT INTO %s (`sorter`, `group`, `description`, `url`, `filename`, `target`, `realpath`, `width`, `height`) ".
										"VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $this->table_bannerize,
										$olditem->sorter, $olditem->group, $olditem->description, $olditem->url,
										$olditem->filename, $olditem->target, $olditem->realpath, $dimensions[0], $dimensions[1]);
			$wpdb->query($sql);
		}
		$this->dropOldDatabaseTable();
		$this->options['todo_upgrade'] = "no";
		update_option( $this->options_key, $this->options);
		?>
			<div class="wrap">
				<div class="wp_saidmade_box">
					<p class="wp_saidmade_copy_info"><?php _e('For more info and plugins visit', 'wp-bannerize') ?> <a href="http://www.saidmade.com">Saidmade</a></p>
					<a class="wp_saidmade_logo" href="http://www.saidmade.com/prodotti/wordpress/wp-bannerize/">
						<?php echo $this->plugin_name ?> ver. <?php echo $this->version ?>
					</a>
				</div>
				<h3 class="wp_bannerize_info"><?php _e('Results','wp-bannerize') ?></h3>
				<p class="wp_bannerize_info"><?php _e('Ok, your old WP Bannerize Database Table has been convert succesfully! Please, press "continue" button to start with new WP Bannerize Release! Thank you for collaboration.','wp-bannerize') ?></p>
				<form class="wp_bannerize_form_action" method="post" action="">
					<input type="submit" value="<?php _e('Continue', 'wp-bannerize') ?>" />
				</form>
			</div>
		<?php
	}

	/**
	 * Delete all image files link into old WP Bannerize table. Drop the old WP Bannerize table
	 *
	 * @return void
	 */
	function dropOldDatabaseTableAndFiles() {
		global $wpdb;

		$sql = sprintf("SELECT * FROM `%s`", $this->old_table_bannerize);
		$old = $wpdb->get_results($sql);
		foreach($old as $olditem) {
			@unlink( $olditem->realpath );
		}
		$this->dropOldDatabaseTable();
		?>
			<div class="wrap">
				<div class="wp_saidmade_box">
					<p class="wp_saidmade_copy_info"><?php _e('For more info and plugins visit', 'wp-bannerize') ?> <a href="http://www.saidmade.com">Saidmade</a></p>
					<a class="wp_saidmade_logo" href="http://www.saidmade.com/prodotti/wordpress/wp-bannerize/">
						<?php echo $this->plugin_name ?> ver. <?php echo $this->version ?>
					</a>
				</div>
				<h3 class="wp_bannerize_info"><?php _e('Results','wp-bannerize') ?></h3>
				<p class="wp_bannerize_info"><?php _e('Ok, ALL previous image file have been deleted succesfully. The old WP Bannerize Database table has been deleted.','wp-bannerize') ?></p>
				<form class="wp_bannerize_form_action" method="post" action="">
					<input type="submit" value="<?php _e('Continue', 'wp-bannerize') ?>" />
				</form>
			</div>
		<?php
	}

	/**
	 * Do a SQL Drop Table on old database table
	 *
	 * @return void
	 */
	function dropOldDatabaseTable() {
		global $wpdb;
		$sql = sprintf("DROP TABLE `%s`", $this->old_table_bannerize);
		$res = $wpdb->query($sql);
	}

    /**
     * Create WP Bannerize table for store banner data
     *
     * @since 2.1.0
     *
     * @return
     */
    function createTable() {
        global $wpdb;
        $q = "CREATE TABLE IF NOT EXISTS `" . $this->table_bannerize . "` (
			   `id` bigint(20) NOT NULL auto_increment,
				`sorter` bigint(20) NOT NULL default 0,
				`clickcount` bigint(20) NOT NULL default 0,
				`group` varchar(128) NOT NULL,
				`description` varchar(255) NOT NULL,
				`use_description` char(1) NOT NULL default '0',
				`url` varchar(255) NOT NULL,
				`target` varchar(32) NOT NULL,
				`nofollow` char(1) NOT NULL default '0',
				`trash` char(1) NOT NULL default '0',
				`mime` varchar(255) NOT NULL,
				`width` int(11) NOT NULL,
				`height` int(11) NOT NULL,
				`filename` varchar(255) NOT NULL,
				`realpath` varchar(255) NOT NULL,
				PRIMARY KEY  (`id`),
				KEY `group` (`group`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $wpdb->query($q);
    }

} // end of class

?>