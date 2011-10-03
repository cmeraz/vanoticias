<?php
/*
Plugin Name: Wibiya Toolbar
Plugin URI: http://www.wibiya.com
Description: Wibiya toolbar settings plugin
Version: 2.0
Author: Wibiya
*/

if( !is_admin() ) {
	add_action('wp_print_scripts', 'wibiya_filter_footer');
}
add_action('admin_menu', 'wibiya_config_page');

function wibiya_filter_footer() {
	$wibiya_toolbarpath = get_option('WibiyaToolbarPath');
	$wibiya_enabled = get_option('WibiyaToolbarEN');
	
	if ($wibiya_toolbarpath != '' and $wibiya_enabled) {
		wp_enqueue_script('wibiyaToolbar', $wibiya_toolbarpath, false, false, true );
	}
}

function wibiya_config_page() {
	add_submenu_page('themes.php', __('Wibiya Configuration'), __('Wibiya Configuration'), 'manage_options', 'wibiya-key-config', 'wibiya_config');
}

function wibiya_config() {
	$wibiya_toolbarpath = get_option('WibiyaToolbarPath');
	$wibiya_enabled = get_option('WibiyaToolbarEN');

	if ( isset($_POST['submit']) ) {
		if (isset($_POST['toolbarpath']))
		{
			$wibiya_toolbarpath = $_POST['toolbarpath'];
			if ($_POST['wibiya_enabled'] == 'on')
			{
				$wibiya_enabled = 1;
			}
			else
			{
				$wibiya_enabled = 0;
			}
		}
		else
		{
			$wibiya_toolbarpath = '';
			$wibiya_enabled = 0;
		}
		update_option('WibiyaToolbarPath', $wibiya_toolbarpath);
		update_option('WibiyaToolbarEN', $wibiya_enabled);
		echo "<div id=\"updatemessage\" class=\"updated fade\"><p>Wibiya settings updated.</p></div>\n";
		echo "<script type=\"text/javascript\">setTimeout(function(){jQuery('#updatemessage').hide('slow');}, 3000);</script>";	
	}
	?>
	<div class="wrap">
		<h2>Wibiya Toolbar for WordPress Configuration</h2>
		<div class="postbox-container">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">
					<form action="" method="post" id="wibiya-conf">
					<div id="wibiya_settings" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Wibiya Settings</span></h3>
						<div class="inside">
							<table class="form-table">
								<tr><th valign="top" scrope="row">Wibiya Toolbar On/Off:</th>
								<td valign="top"><input type="checkbox" id="wibiya_enabled" name="wibiya_enabled" <?php echo ($wibiya_enabled ? 'checked="checked"' : ''); ?> /> <label for="wibiya_enabled">Enable or disable the Wibiya Toolbar</label><br/></td></tr>
								<tr><th valign="top" scrope="row"><label for="toolbarpath">Wibiya Toolbar Path:</label></th>
								<td valign="top"><input id="toolbarpath" name="toolbarpath" type="text" size="20" value="<?php echo $wibiya_toolbarpath; ?>"/></td></tr>
							</table>
						</div>
					</div>
					<div class="submit"><input type="submit" class="button-primary" name="submit" value="Update Toolbar &raquo;" /></div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
} 
?>