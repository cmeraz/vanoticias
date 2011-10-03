<?php
/*
Plugin Name: myEASYhider
Plugin URI: http://myeasywp.com/plugins/myeasyhider/
Description: Easily hide parts of your administration page.
Version: 1.0.6
Author: Ugo Grandolini aka "camaleo"
Author URI: http://grandolini.com
*/
/*	Easily hide parts of your administration page. 
	Copyright (C) 2010 Ugo Grandolini  (email : info@myeasywp.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/

define('MEH_PATH', dirname(__FILE__) . '/');

define('myEASYcomCaller', 'myeasyhider');    # @since 1.0.7: the plugin install folder

if(!is_admin()) {

	/***********
	 * FRONTEND
	 */

	/**
	 * @since 1.0.4 on demand, show the credits on the footer
	 */
	if(file_exists(MEH_PATH . '/inc/myEASYhider-PRO.php') && file_exists(MEH_PATH . '/js/myEASYhider-PRO.js')) {

		// do nothing
	}
	else if(file_exists('../myEASYhider-PRO/inc/myEASYhider-PRO.php') && file_exists('../myEASYhider-PRO/js/myEASYhider-PRO.js')) {

		// do nothing
	}
	else {

		if(get_option('myeasy_showcredits')==true && !function_exists('myeasy_credits') && !defined('MYEASY_SHOWCREDITS')) {    /* 1.0.5 changed all references from 'meh_showcredits' */

			/**
			 * on demand, show the credits on the footer
			 */
			 
			 /*
			define('MEBAK_FOOTER_CREDITS', '<div style="font-size:9px;text-align:center;">'
					.'<a href="http://myeasywp.com" target="_blank">Improve Your Life, Go The myEASY Way&trade;</a>'
					.'</div>');
			
*/
			add_action('wp_footer', 'myeasy_credits');
			function myeasy_credits() {
				/*
				echo MEBAK_FOOTER_CREDITS;*/
				define('MYEASY_SHOWCREDITS', true);
			}
		}
	}
}
else {

	/***********
	 * BACKEND
	 */

	/**
	 * @since 1.0.3 the code is executed only when in the admin pages
	 */
	define('MEH_LOCALE', 'myEASYhider');
	define('SAVE_BTN', __('Update Options', MEH_LOCALE ));
	define('LOAD_USR_BTN', __('Load User Settings', MEH_LOCALE ));  #   1.0.4
	define('SAVE_USR_BTN', __('Save User Settings', MEH_LOCALE ));  #   1.0.4

	#
	#	link to the plugin folder (eg. http://example.com/wordpress-2.9.1/wp-content/plugins/MyPlugin/)
	#
	$myEASYhider_dir = basename(dirname(__FILE__));
	define(MYEASYHIDER_LINK, get_option('siteurl').'/wp-content/plugins/' . $myEASYhider_dir . '/');

	if(file_exists(MEH_PATH . '/inc/myEASYhider-PRO.php') && file_exists(MEH_PATH . '/js/myEASYhider-PRO.js')) 	{

		include_once(MEH_PATH . '/inc/myEASYhider-PRO.php');
		$jspro = MYEASYHIDER_LINK . 'js/myEASYhider-PRO.js';	#	1.0.2
		define('MEH_PRO', true);
	}
	else if(file_exists('../../myEASYhider-PRO/inc/myEASYhider-PRO.php') && file_exists('../../myEASYhider-PRO/js/myEASYhider-PRO.js')) {

		#	debug & development
		#
		include_once('../../myEASYhider-PRO/inc/myEASYhider-PRO.php');
		$jspro = 'http://localhost/myEASYhider-PRO/js/myEASYhider-PRO.js';
		define('MEH_PRO', true);
	}
	else {

		define('MEH_PRO', false);
	}

	require(MEH_PATH . '/inc/myEASYcom.php');								#	1.0.2

	#
	#	hook for adding admin menus
	#
	add_action('admin_menu', 'myEASYhider_add_pages');

	load_plugin_textdomain( MEH_LOCALE, 'wp-content/plugins/' . $myEASYhider_dir, $myEASYhider_dir . '/langs/' );

	wp_enqueue_style( 'meh_style', MYEASYHIDER_LINK.'css/meh.css', '', '20100516', 'screen' );							#	1.0.3
	wp_enqueue_style( 'myeasywp_common', 'http://myeasywp.com/service/css/myeasywp.css', '', '20100516', 'screen' );	#	1.0.3

	wp_enqueue_script( 'meh_js', MYEASYHIDER_LINK.'js/meh.js', '', '20100504', false );
	if(MEH_PRO==true) {

		wp_enqueue_script( 'meh_pro_js', $jspro, '', '20100504', false );
	}

	#
	#	action function for the above hook
	#
	function myEASYhider_add_pages() {

		#	Settings
		#
		add_options_page(__( 'myEASYhider', MEH_LOCALE ), __( 'myEASYhider', MEH_LOCALE ), 'administrator', 'myEASYhider_options', 'myEASYhider_options_page');
	}

	function myEASYhider_options_page() {

		#	Settings
		#
		global $wpdb;

		echo '<div class="wrap">'
				.'<div id="icon-options-general" class="icon32" style="background:url(http://myeasywp.com/service/img/icon.png);"><br /></div>'
				.'<h2>myEASYhider: ' . __( 'Settings', MEH_LOCALE ) . '</h2>'
		;

		if(!defined('MEH_PRO') || MEH_PRO!=true) {

			#	free
			#
			measycom_advertisement('meh');
		}

		if(strlen($_POST['_action'])>0) { $_POST['btn'] = $_POST['_action']; }    #   1.0.4

		switch($_POST['btn']) {

			#----------------
			case LOAD_USR_BTN:
			#----------------
				#
				#   load the selected user settings @since 1.0.4
				#
				unset($_POST['total_def_items'], $_POST['meh_ids']);

//				if(isset($_POST['load_user_settings'])) {
					$_POST['users_menu'] = $_POST['load_user_settings'];
//				}

//echo 'load_user_settings['.$_POST['load_user_settings'].']<br>';    #   debug

				break;
				#
			#----------------
			case SAVE_USR_BTN:
			#----------------
				#
				#   save the selected user settings @since 1.0.4
				#
				if((int)$_POST['total_def_items']>0) {

					$def_items = ''
							. $_POST['header-logo'] . ','
							. $_POST['favorite-actions'] . ','
							. $_POST['menu-posts'] . ','
							. $_POST['menu-media'] . ','
							. $_POST['menu-links'] . ','
							. $_POST['menu-pages'] . ','
							. $_POST['menu-comments'] . ','
							. $_POST['menu-appearance'] . ','
							. $_POST['menu-plugins'] . ','
							. $_POST['menu-users'] . ','
							. $_POST['menu-tools'] . ','
							. $_POST['menu-settings'] . ','
							. $_POST['update-nag'] . ','
							. $_POST['screen-options-link-wrap'] . ','
							. $_POST['contextual-help-link'] . ','
							. $_POST['dashboard_right_now'] . ','
							. $_POST['dashboard_quick_press'] . ','
							. $_POST['dashboard_recent_comments'] . ','
							. $_POST['dashboard_recent_drafts'] . ','
							. $_POST['dashboard_primary'] . ','
							. $_POST['dashboard_incoming_links'] . ','
							. $_POST['dashboard_plugins'] . ','
							. $_POST['dashboard_secondary'] . ','
							. $_POST['wp-version-message'] . ','
							. $_POST['footer-left'] . ','
							. $_POST['footer-upgrade']
					;
					update_option( 'meh_def_items'.$_POST['users_menu'], $def_items );
				}

				if(isset($_POST['meh_ids'])) {

					update_option( 'meh_ids'.$_POST['users_menu'], $_POST['meh_ids'] );
				}

				unset($_POST['total_def_items'], $_POST['meh_ids']);
				break;
				#
			#----------------
			case SAVE_BTN:
			#----------------
				#
				#	save the posted value in the database
				#
				if(isset($_POST['total_allowed_users'])) {

					$allowed_users = '';
					for($i=0;$i<$_POST['total_users'];$i++) {

						if(isset($_POST['user'.$i])) {

							$allowed_users .= $_POST['user'.$i] . ',';
						}
					}
					$allowed_users = trim(substr($allowed_users,0,-1));
					update_option( 'meh_allowed_users', $allowed_users );
				}

				if((int)$_POST['total_def_items']>0) {

					$def_items = ''
							. $_POST['header-logo'] . ','
							. $_POST['favorite-actions'] . ','
							. $_POST['menu-posts'] . ','
							. $_POST['menu-media'] . ','
							. $_POST['menu-links'] . ','
							. $_POST['menu-pages'] . ','
							. $_POST['menu-comments'] . ','
							. $_POST['menu-appearance'] . ','
							. $_POST['menu-plugins'] . ','
							. $_POST['menu-users'] . ','
							. $_POST['menu-tools'] . ','
							. $_POST['menu-settings'] . ','
							. $_POST['update-nag'] . ','
							. $_POST['screen-options-link-wrap'] . ','
							. $_POST['contextual-help-link'] . ','
							. $_POST['dashboard_right_now'] . ','
							. $_POST['dashboard_quick_press'] . ','
							. $_POST['dashboard_recent_comments'] . ','
							. $_POST['dashboard_recent_drafts'] . ','
							. $_POST['dashboard_primary'] . ','
							. $_POST['dashboard_incoming_links'] . ','
							. $_POST['dashboard_plugins'] . ','
							. $_POST['dashboard_secondary'] . ','
							. $_POST['wp-version-message'] . ','
							. $_POST['footer-left'] . ','
							. $_POST['footer-upgrade']
					;
//					update_option( 'meh_def_items', $def_items );                       #   1.0.4
					update_option( 'meh_def_items'.$_POST['users_menu'], $def_items );  #   1.0.4
				}

				if(isset($_POST['meh_ids'])) {

//					update_option( 'meh_ids', $_POST['meh_ids'] );                      #   1.0.4
					update_option( 'meh_ids'.$_POST['users_menu'], $_POST['meh_ids'] ); #   1.0.4
				}

				if(isset($_POST['meh_isACTIVE'])) {

					update_option( 'meh_isACTIVE', 1 );
				}
				else {

					update_option( 'meh_isACTIVE', 0 );
				}

				if(MEH_PRO==true) {

					__meh_pro_update_options();
				}

				if(isset($_POST['myeasy_showcredits'])) {

					update_option( 'myeasy_showcredits', 1 );
				}
				else {

					update_option( 'myeasy_showcredits', 0 );
				}

				unset($_POST['total_def_items']);
				break;
				#
			default:
		}

		#
		#	populate the input fields when the page is loaded
		#
		if(!isset($_POST['total_users'])) {

			#	get the users list
			#
			$sql = 'SELECT count(*) as tu FROM `'.$wpdb->users.'` ';
			$rows = $wpdb->get_results( $sql );

			$allowed_users = explode(',', get_option('meh_allowed_users'));

			$_POST['total_allowed_users'] = count($allowed_users);
			$_POST['total_users'] = $rows[0]->tu;

			$i = 0;
			for($i=0;$i<$_POST['total_allowed_users'];$i++) {

				$_POST['user'.$i] = trim($allowed_users[$i]);
			}
		}

		$i = 0;
		if(!isset($_POST['total_def_items'])) {

			#   1.0.4: BEG
//			$items_values = explode(',', get_option('meh_def_items'));
			$items_values = explode(',', get_option('meh_def_items'.$_POST['users_menu']));

//echo 'count['.count($items_values).']<br>'; #   debug

			if(count($items_values)==1) {

				#   there are no specific settings for this user, get the default ones
				#
				$items_values = explode(',', get_option('meh_def_items'));
			}
			#   1.0.4: END

			$items_classes = array();

			foreach($items_values as $key=>$val) {

				if(!isset($_POST[$val])) {

					$_POST[$val] = $val;
				}

				if($val!='') {

					$items_classes[$val] = 'meh-selector-selected';
				}

				$i++;
			}
		}
		$_POST['total_def_items'] = $i;


		if(!isset($_POST['meh_ids'])) {

//			$_POST['meh_ids'] = get_option('meh_ids');                       #   1.0.4
			$_POST['meh_ids'] = get_option('meh_ids'.$_POST['users_menu']);  #   1.0.4

		}

		if(!isset($_POST['meh_isACTIVE'])) { $_POST['meh_isACTIVE'] = get_option('meh_isACTIVE'); }

//		$_POST['meh_ids'] = measycom_sanitize_input(get_option('meh_ids'), false, '*nospace');		#	1.0.2
		$_POST['meh_ids'] = measycom_sanitize_input($_POST['meh_ids'], false, '*nospace');			#	1.0.4

		if(!isset($_POST['myeasy_showcredits'])) {

			$tmp = get_option('myeasy_showcredits');
			if(strlen($tmp)==0) { $tmp = 1;}

			$_POST['myeasy_showcredits']= $tmp;
		}

		?><script type="text/javascript">var myeasyplugin = 'myeasyhider';</script>
		<form name="meb_settings" id="meb_settings" method="post" action="">
		<input type="hidden" name="total_allowed_users" value="<?php echo $_POST['total_allowed_users']; ?>" />
		<input type="hidden" name="total_users" value="<?php echo $_POST['total_users']; ?>" />
		<input type="hidden" name="total_def_items" value="<?php echo $_POST['total_def_items']; ?>" />
		<input type="hidden" name="_action" id="_action" value="" />
		<input type="hidden" name="load_user_settings" id="load_user_settings" value="" /><?php

		if(MEH_PRO==true) {

			__meh_pro_init_post_options();
		}

/*  1.0.4
		?><div class="light">
			<div class="left"><?php
				#
				#	Allowed users
				#
				echo '<b>' . __('Who can see everything?', MEH_LOCALE ) . '</b>'

					.'<br /><i>' . __('Check the users that will be able to see everything.', MEH_LOCALE ) . '</i>';

			?></div>
			<div class="right"><?php
*/
				$sql = 'SELECT user_login, user_email, display_name '
						.'FROM `'.$wpdb->users.'` '
						.'ORDER BY user_login ASC'
				;
				$rows = $wpdb->get_results( $sql );

				#   1.0.4: BEG
				$selected = '';
				if(strlen($_POST['users_menu'])==0) {

					$selected = ' selected="selected"';
				}
				$users_menu .= ''
						.'<b>' . __('Need to customize the settings for each user?', MEH_LOCALE ) . '</b>'
						.'<p>'
							. __('Leave "Default settings" to set what', MEH_LOCALE )
							. ' <u>' . __('every user', MEH_LOCALE ) . '</u> '
							. __('will be able to see.', MEH_LOCALE )
						.'</p>'
						.'<p>'
							. __('Choose an user to load her/his own settings then set the values as you like.', MEH_LOCALE )
						.'</p>'

						.'<select name=\'users_menu\' onchange="javascript:document.getElementById(\'load_user_settings\').value=this.value;document.getElementById(\'_action\').value=\''.LOAD_USR_BTN.'\';document.meb_settings.submit();">'//LOAD_USR_BTN
							.'<option value=""'.$selected.'>'
								. __('Default settings', MEH_LOCALE )
							.'</option>'
				;
				#   1.0.4: END

				$i = 0;
				foreach($rows as $row) {

					$isCHECKED = _isCHECKED($row->user_login);

					$email = $row->user_email;
					//$email = 'user'.$i.'@example.com';	#	screen shots only

					#   1.0.4: BEG
//					echo '<p>'
//							.'<input type="checkbox" name="user'.$i.'" id="user'.$i.'" value="'.$row->user_login.'"'.$isCHECKED.' /> <label for="user'.$i.'">'
//								.$row->user_login.', '.$email
//							.'</label>'
//						.'</p>';

					$selected = '';
					if($row->user_login==$_POST['users_menu']) {

						$selected = ' selected="selected"';
					}
					$users_menu .= '<option value="'.$row->user_login.'"'.$selected.'>'
							.$row->display_name.', '.$email
						.'</option>'
					;
					#   1.0.4: END

					$i++;
				}

				$users_menu .= '</select><br />'
//						.'<input class="button-secondary" style="margin-right:8px;" type="submit" name="btn" value="'.LOAD_USR_BTN.'" />'
						.'<div style="text-align:right;"><input class="button-primary" style="margin-top:8px;" type="submit" name="btn" value="'.SAVE_USR_BTN.'" /></div>'
						.'<p><i>'
						. __('Do not forget to save the settings for each user you want to customize!', MEH_LOCALE )
						.'</i></p>'
				; #   1.0.4

/*  1.0.4
			?></div>
			<div style="clear:both;"></div>
		</div>
 */
?>
		<div class="light">
			<div class="left"><?php
				#
				#	IDs to hide
				#
				echo '<b>' . __('What will be hidden?', MEH_LOCALE ) . '</b>'

					.'<p><i>' . __('Click on each area to set its status.', MEH_LOCALE )
						.'<br /><br />'
						. __('Areas highlighted in GREEN will be shown to everybody; areas highligted in RED will be only shown to the users selected here above.', MEH_LOCALE )
					. '</i></p>'

					#   1.0.4: BEG
					.'<br />'
					.$users_menu
					#   1.0.4: END
				;

				//$tmp = 'text';	#	debug
				$tmp = 'hidden';

			?></div>

			<input type="<?php echo $tmp; ?>" id="val-header-logo" name="header-logo" value="<?php echo $_POST['header-logo']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-favorite-actions" name="favorite-actions" value="<?php echo $_POST['favorite-actions']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-menu-posts" name="menu-posts" value="<?php echo $_POST['menu-posts']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-media" name="menu-media" value="<?php echo $_POST['menu-media']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-links" name="menu-links" value="<?php echo $_POST['menu-links']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-pages" name="menu-pages" value="<?php echo $_POST['menu-pages']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-comments" name="menu-comments" value="<?php echo $_POST['menu-comments']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-menu-appearance" name="menu-appearance" value="<?php echo $_POST['menu-appearance']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-plugins" name="menu-plugins" value="<?php echo $_POST['menu-plugins']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-users" name="menu-users" value="<?php echo $_POST['menu-users']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-tools" name="menu-tools" value="<?php echo $_POST['menu-tools']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-menu-settings" name="menu-settings" value="<?php echo $_POST['menu-settings']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-update-nag" name="update-nag" value="<?php echo $_POST['update-nag']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-screen-options-link-wrap" name="screen-options-link-wrap" value="<?php echo $_POST['screen-options-link-wrap']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-contextual-help-link" name="contextual-help-link" value="<?php echo $_POST['contextual-help-link']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-dashboard_right_now" name="dashboard_right_now" value="<?php echo $_POST['dashboard_right_now']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_quick_press" name="dashboard_quick_press" value="<?php echo $_POST['dashboard_quick_press']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_recent_comments" name="dashboard_recent_comments" value="<?php echo $_POST['dashboard_recent_comments']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_recent_drafts" name="dashboard_recent_drafts" value="<?php echo $_POST['dashboard_recent_drafts']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_primary" name="dashboard_primary" value="<?php echo $_POST['dashboard_primary']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-dashboard_incoming_links" name="dashboard_incoming_links" value="<?php echo $_POST['dashboard_incoming_links']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_plugins" name="dashboard_plugins" value="<?php echo $_POST['dashboard_plugins']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-dashboard_secondary" name="dashboard_secondary" value="<?php echo $_POST['dashboard_secondary']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-wp-version-message" name="wp-version-message" value="<?php echo $_POST['wp-version-message']; ?>" />

			<input type="<?php echo $tmp; ?>" id="val-footer-left" name="footer-left" value="<?php echo $_POST['footer-left']; ?>" />
			<input type="<?php echo $tmp; ?>" id="val-footer-upgrade" name="footer-upgrade" value="<?php echo $_POST['footer-upgrade']; ?>" />

			<div class="right">
				<div class="meh-selector-img">
					<div title="<?php _e('WordPress Logo'); ?>" class="<?php echo $items_classes['header-logo'] ? $items_classes['header-logo'] : 'meh-selector'; ?>" id="high-header-logo" style="top:0;left:3px;width:17px;height:18px;" onclick="javascript:__meh_selector_toggler('header-logo');"></div>
					<div title="<?php _e('Favorite actions'); ?>" class="<?php echo $items_classes['favorite-actions'] ? $items_classes['favorite-actions'] : 'meh-selector'; ?>" id="high-favorite-actions" style="top:2px;left:331px;width:55px;height:13px;" onclick="javascript:__meh_selector_toggler('favorite-actions');"></div>

					<div title="<?php _e('Posts'); ?>" class="<?php echo $items_classes['menu-posts'] ? $items_classes['menu-posts'] : 'meh-selector'; ?>" id="high-menu-posts" style="clear:left;top:24px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-posts');"></div>
					<div title="<?php _e('Media'); ?>" class="<?php echo $items_classes['menu-media'] ? $items_classes['menu-media'] : 'meh-selector'; ?>" id="high-menu-media" style="clear:left;top:24px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-media');"></div>
					<div title="<?php _e('Links'); ?>" class="<?php echo $items_classes['menu-links'] ? $items_classes['menu-links'] : 'meh-selector'; ?>" id="high-menu-links" style="clear:left;top:24px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-links');"></div>
					<div title="<?php _e('Pages'); ?>" class="<?php echo $items_classes['menu-pages'] ? $items_classes['menu-pages'] : 'meh-selector'; ?>" id="high-menu-pages" style="clear:left;top:24px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-pages');"></div>
					<div title="<?php _e('Comments'); ?>" class="<?php echo $items_classes['menu-comments'] ? $items_classes['menu-comments'] : 'meh-selector'; ?>" id="high-menu-comments" style="clear:left;top:24px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-comments');"></div>

					<div title="<?php _e('Appearance'); ?>" class="<?php echo $items_classes['menu-appearance'] ? $items_classes['menu-appearance'] : 'meh-selector'; ?>" id="high-menu-appearance" style="clear:left;top:33px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-appearance');"></div>
					<div title="<?php _e('Plugins'); ?>" class="<?php echo $items_classes['menu-plugins'] ? $items_classes['menu-plugins'] : 'meh-selector'; ?>" id="high-menu-plugins" style="clear:left;top:33px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-plugins');"></div>
					<div title="<?php _e('Users'); ?>" class="<?php echo $items_classes['menu-users'] ? $items_classes['menu-users'] : 'meh-selector'; ?>" id="high-menu-users" style="clear:left;top:33px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-users');"></div>
					<div title="<?php _e('Tools'); ?>" class="<?php echo $items_classes['menu-tools'] ? $items_classes['menu-tools'] : 'meh-selector'; ?>" id="high-menu-tools" style="clear:left;top:33px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-tools');"></div>
					<div title="<?php _e('Settings'); ?>" class="<?php echo $items_classes['menu-settings'] ? $items_classes['menu-settings'] : 'meh-selector'; ?>" id="high-menu-settings" style="clear:left;top:33px;left:5px;width:56px;height:11px;" onclick="javascript:__meh_selector_toggler('menu-settings');"></div>

					<div style="float:right;position:relative;top:-100px;left:0;width:425px;height:300px;border:0px dotted red;">
						<div title="<?php _e('Update Info'); ?>" class="<?php echo $items_classes['update-nag'] ? $items_classes['update-nag'] : 'meh-selector'; ?>" id="high-update-nag" style="clear:left;top:2px;left:121px;width:120px;height:10px;" onclick="javascript:__meh_selector_toggler('update-nag');"></div>
						<div title="<?php _e('Screen options selector'); ?>" class="<?php echo $items_classes['screen-options-link-wrap'] ? $items_classes['screen-options-link-wrap'] : 'meh-selector'; ?>" id="high-screen-options-link-wrap" style="top:1px;left:240px;width:40px;height:10px;" onclick="javascript:__meh_selector_toggler('screen-options-link-wrap');"></div>
						<div title="<?php _e('Help'); ?>" class="<?php echo $items_classes['contextual-help-link'] ? $items_classes['contextual-help-link'] : 'meh-selector'; ?>" id="high-contextual-help-link" style="top:1px;left:242px;width:20px;height:10px;" onclick="javascript:__meh_selector_toggler('contextual-help-link');"></div>

						<div title="<?php _e('Right Now'); ?>" class="<?php echo $items_classes['dashboard_right_now'] ? $items_classes['dashboard_right_now'] : 'meh-selector'; ?>" id="high-dashboard_right_now" style="clear:left;top:26px;left:6px;width:199px;height:71px;" onclick="javascript:__meh_selector_toggler('dashboard_right_now');"></div>
						<div title="<?php _e('QuickPress'); ?>" class="<?php echo $items_classes['dashboard_quick_press'] ? $items_classes['dashboard_quick_press'] : 'meh-selector'; ?>" id="high-dashboard_quick_press" style="top:26px;left:12px;width:199px;height:84px;" onclick="javascript:__meh_selector_toggler('dashboard_quick_press');"></div>

						<div title="<?php _e('Recent Comments'); ?>" class="<?php echo $items_classes['dashboard_recent_comments'] ? $items_classes['dashboard_recent_comments'] : 'meh-selector'; ?>" id="high-dashboard_recent_comments" style="clear:left;top:31px;left:6px;width:199px;height:58px;" onclick="javascript:__meh_selector_toggler('dashboard_recent_comments');"></div>
						<div title="<?php _e('Recent Drafts'); ?>" class="<?php echo $items_classes['dashboard_recent_drafts'] ? $items_classes['dashboard_recent_drafts'] : 'meh-selector'; ?>" id="high-dashboard_recent_drafts" style="top:31px;left:12px;width:199px;height:25px;" onclick="javascript:__meh_selector_toggler('dashboard_recent_drafts');"></div>
						<div title="<?php _e('WordPress Development Blog'); ?>" class="<?php echo $items_classes['dashboard_primary'] ? $items_classes['dashboard_primary'] : 'meh-selector'; ?>" id="high-dashboard_primary" style="top:38px;left:12px;width:199px;height:57px;" onclick="javascript:__meh_selector_toggler('dashboard_primary');"></div>

						<div title="<?php _e('Incoming Links'); ?>" class="<?php echo $items_classes['dashboard_incoming_links'] ? $items_classes['dashboard_incoming_links'] : 'meh-selector'; ?>" id="high-dashboard_incoming_links" style="clear:left;top:13px;left:6px;width:199px;height:37px;" onclick="javascript:__meh_selector_toggler('dashboard_incoming_links');"></div>
						<div title="<?php _e('Other WordPress News'); ?>" class="<?php echo $items_classes['dashboard_secondary'] ? $items_classes['dashboard_secondary'] : 'meh-selector'; ?>" id="high-dashboard_secondary" style="top:45px;left:12px;width:199px;height:78px;" onclick="javascript:__meh_selector_toggler('dashboard_secondary');"></div>

						<div title="<?php _e('Plugins'); ?>" class="<?php echo $items_classes['dashboard_plugins'] ? $items_classes['dashboard_plugins'] : 'meh-selector'; ?>" id="high-dashboard_plugins" style="clear:left;top:-21px;left:6px;width:199px;height:44px;" onclick="javascript:__meh_selector_toggler('dashboard_plugins');"></div>

						<div title="<?php _e('Version Info'); ?>" class="<?php echo $items_classes['wp-version-message'] ? $items_classes['wp-version-message'] : 'meh-selector'; ?>" id="high-wp-version-message" style="/*background:red;*/clear:left;top:-190px;left:6px;width:199px;height:10px;" onclick="javascript:__meh_selector_toggler('wp-version-message');"></div>
					</div>
					<div style="clear:both;float:left;position:relative;top:-98px;left:0;width:490px;height:18px;border:0px dotted green;">
						<div title="<?php _e('Credits'); ?>" class="<?php echo $items_classes['footer-left'] ? $items_classes['footer-left'] : 'meh-selector'; ?>" id="high-footer-left" style="clear:left;top:1px;left:0;width:159px;height:16px;" onclick="javascript:__meh_selector_toggler('footer-left');"></div>
						<div title="<?php _e('Update Info'); ?>" class="<?php echo $items_classes['footer-upgrade'] ? $items_classes['footer-upgrade'] : 'meh-selector'; ?>" id="high-footer-upgrade" style="top:1px;left:281px;width:49px;height:16px;" onclick="javascript:__meh_selector_toggler('footer-upgrade');"></div>
					</div>
				</div>

				<div style="clear:both;margin:0 0 20px 0;width:490px;text-align:center;border:0px dotted green;"><?php
					echo ''
						.'<input class="button-secondary" type="button" onclick="javascript:__meh_select(\'all\');" value="'
								.__('Hide all', MEH_LOCALE ).'" />'
						.'<input class="button-secondary" style="margin-left:20px;" type="button" onclick="javascript:__meh_select(\'none\');" value="'
								.__('Hide none', MEH_LOCALE ).'" />'
						.'<input class="button-secondary" style="margin-left:20px;" type="button" onclick="javascript:__meh_select(\'common\');" value="'
								.__('Hide most common', MEH_LOCALE ).'" />'
					;
				?></div>

				<div class="medium" style="float:left;"><?php

					echo ''
						.'<p style="font-weight:bold;margin-top:0;">' . __('Custom items', MEH_LOCALE ) . '</p>'

						.'<p>' . __('If you like to hide other items, please enter their names in the field here below.', MEH_LOCALE ) . '</p>'

						.'<p>' . __('Each name must correspond to the "id" attribute of the item you like to hide so, for example, to hide the following element:', MEH_LOCALE ) . '</p>'

							. '<div class="light" style="padding:6px;width:auto;">'
								.'<code>'
									. '&lt;div id="<b>' . __('my-custom-element', MEH_LOCALE ) . '</b>"&gt;<br />'
										. '<span style="margin-left:20px;">' . __('Hallo there!', MEH_LOCALE ) . '</span><br />'
									. '&lt;/div&gt;'
								. '</code>'
							. '</div>'

						. '<p>' . __('You will enter "my-custom-element" here below.', MEH_LOCALE ) . '</p>'
					;

					?><textarea name="meh_ids" style="width:100%;" rows="5"><?php

						echo $_POST['meh_ids'];

					?></textarea><?php

					echo '<p><i>' . __('Note: separate names with commas without adding any extra space (eg. my-custom-element1,my-custom-element2,my-custom-element3).', MEH_LOCALE ) . '</i></p>';

				?></div>

			</div>
			<div style="clear:both;"></div>
		</div><?php

		if(MEH_PRO==true) {

			__meh_pro_html_options();
		}

		?><div class="light">
			<div class="left"><?php
				#
				#	activate
				#
				echo '<b>' . __('Enable myEASYhider?', MEH_LOCALE ) . '</b>'

					.'<br /><i>' . __('Check this to start hiding pending on your choices.', MEH_LOCALE ) . '</i>';

			?></div>

			<div class="right"><?php

					$checked ='';
					if($_POST['meh_isACTIVE']==1) { $checked = ' checked="checked"'; }

				?><input type="checkbox" name="meh_isACTIVE" value="1"<?php echo $checked; ?> />
			</div>
			<div style="clear:both;"></div>
		</div><?php

		if(!defined('MEH_PRO') || MEH_PRO!=true) {

			#	free
			#
			//include_once(MEH_PATH . '/inc/myEASYcom.php');				#	1.0.2

			?><div style="margin:8px 0;text-align:center;"><?php
					#
					#	show credits
					#
					$checked ='';

					if($_POST['myeasy_showcredits']==1) { $checked = ' checked="checked"'; }

					echo '' . __('We invested a lot of time to create this and all the plugins in the myEASY series, please allow us to place a small credit in your blog footer, here is how it will look:',
									MEH_LOCALE );

					?><p><input type="checkbox" name="myeasy_showcredits" value="1"<?php echo $checked; ?> />&nbsp;<?php

						echo __('Yes, I like to help you!', MEH_LOCALE )
								. ' &mdash; ' . __('If you decide not to show the credits, please consider to make a donation!', MEH_LOCALE )
							. '</p>'
						;

			?></div><?php

			measycom_donate('meh');
		}

		?><div class="button-separator">
			<input class="button-primary" style="margin:14px 12px;" type="submit" name="btn" value="<?php echo SAVE_BTN; ?>" />
		</div>

		</form><?php

		//include_once(MEH_PATH . '/inc/myEASYcom.php');				#	1.0.2
		measycom_camaleo_links();

	}

	function _isCHECKED($user) {

		for($i=0;$i<$_POST['total_users'];$i++) {

			if($user==$_POST['user'.$i]) { return ' checked="checked"'; }
		}
		return '';
	}

	function meh_check() {

		if(get_option('meh_isACTIVE')!=true) {

			return;
		}

		if(MEH_PRO==true) {

			__meh_pro_tweak_options();
		}

		#
		#	http://codex.wordpress.org/Function_Reference/wp_get_current_user
		#
		$current_user = wp_get_current_user();
		$allowed_users = explode(',', get_option('meh_allowed_users'));

		foreach($allowed_users as $key=>$val) {

			if($val!='') {

				if($current_user->user_login==$val) {

					return;
				}
			}
		}

		#   1.0.4: BEG
//		$def_items = explode(',', get_option('meh_def_items'));
//		$xtra_items = explode(',', get_option('meh_ids'));

		$def_items = explode(',', get_option('meh_def_items'.$current_user->user_login));

//echo '$def_items['.get_option('meh_def_items'.$current_user->user_login).']<br>';
//echo '$def_items['.count($def_items).']<br>';

		if(count($def_items)==1) {

			#   there are no specific settings for this user, get the default ones
			#
			$def_items = explode(',', get_option('meh_def_items'));
		}

		$xtra_items = explode(',', get_option('meh_ids'.$current_user->user_login));

//echo '$xtra_items['.get_option('meh_ids'.$current_user->user_login).']<br>';
//echo '$xtra_items['.count($xtra_items).']<br>';

		if(count($xtra_items)==1) {

			#   there are no specific settings for this user, get the default ones
			#
			$xtra_items = explode(',', get_option('meh_ids'));
		}
		#   1.0.4: END

		$ids = array_merge($def_items, $xtra_items);

//echo 'current_user['.$current_user->user_login.']<br>';
//echo '$allowed_users<br>';var_dump($allowed_users);echo '<hr>';
//echo '$def_items<br>';var_dump($def_items);echo '<hr>';
//echo '$xtra_items<br>';var_dump($xtra_items);echo '<hr>';
//echo '$ids<br>';var_dump($ids);echo '<hr>';

		$IDS = '';
		foreach($ids as $key=>$val) {

			if($val!='') {

				$IDS .= $val . ',';
			}
		}
		$IDS = trim(substr($IDS,0,-1));

//echo '$IDS['.$IDS.']';

		echo '<script type="text/javascript">'
				.'__meh_check(\''.$IDS.'\');'
			.'</script>'
		;
	}
	add_action('admin_footer', 'meh_check');
}
?>