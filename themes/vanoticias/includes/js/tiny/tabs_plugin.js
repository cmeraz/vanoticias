// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.tabs', {
		// creates control instances based on the control's id.
		// our button's id is "tabs_button"
		createControl : function(id, controlManager) {
			if (id == 'tabs_button') {
				// creates the button
				var button = controlManager.createButton('tabs_button', {
					title : 'Insert tabs', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-tabs.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert tabs', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=tabs-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="tabs-form">\
		<table id="tabs-table" class="form-table">\
			<tr>\
				<td colspan="2">Create new tabs section.</td>\
			</tr>\
			<tr>\
				<td>\
				<label for="tabs-content1">Enter 1st Tab title</label> <input type="text" id="tabs-title1" name="tabs-title1" value="" /><br />\
				<textarea id="tabs-content1" name="tabs-content1" cols="36" rows="5" rel=""></textarea><br />\
				<small>Tab content.</small></td>\
				\
				<td>\
				<label for="tabs-content2">Enter 2nd Tab title</label> <input type="text" id="tabs-title2" name="tabs-title2" value="" /><br />\
				<textarea id="tabs-content2" name="tabs-content2" cols="36" rows="5" rel=""></textarea><br />\
				<small>Tab content.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="tabs-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		\
		<table id="tab-table" class="form-table">\
			<tr>\
				<td colspan="2">Add one more tab.</td>\
			</tr>\
			<tr>\
				<td>\
				<label for="tab-content">Enter Tab title</label> <input type="text" id="tab-title" name="tab-title" value="" /><br />\
				<textarea id="tab-content" name="tab-content" cols="36" rows="5" rel=""></textarea><br />\
				<small>Tab content.</small></td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="tab-submit" class="button-primary" value="Add one tab" name="submit" />\
		</p>\
		</div>');
		
		//var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#tabs-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [tabs]';
			var tab_title = '';
			var tab_content = '';
			tab_title = jQuery('#tabs-title1').val(); tab_content = jQuery('#tabs-content1').val();
			if (tab_title != '' && tab_content != '') {
				shortcode += '[tab title="'+tab_title+'"]'+tab_content+'[/tab] ';
				tab_title = ''; tab_content = '';
			}
			tab_title = jQuery('#tabs-title2').val(); tab_content = jQuery('#tabs-content2').val();
			if (tab_title != '' && tab_content != '') {
				shortcode += '[tab title="'+tab_title+'"]'+tab_content+'[/tab] ';
				tab_title = ''; tab_content = '';
			}
			shortcode += '[/tabs] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
		
		form.find('#tab-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = '';
			var tab_title = '';
			var tab_content = '';
			tab_title = jQuery('#tab-title').val(); tab_content = jQuery('#tab-content').val();
			if (tab_title != '' && tab_content != '') {
				shortcode += '[tab title="'+tab_title+'"]'+tab_content+'[/tab] ';
			}
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()