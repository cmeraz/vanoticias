// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.prettyPhoto', {
		// creates control instances based on the control's id.
		// our button's id is "prettyPhoto_button"
		createControl : function(id, controlManager) {
			if (id == 'prettyPhoto_button') {
				// creates the button
				var button = controlManager.createButton('prettyPhoto_button', {
					title : 'Insert prettyPhoto link', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-prettyphoto.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert prettyPhoto link', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=prettyPhoto-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('prettyPhoto', tinymce.plugins.prettyPhoto);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="prettyPhoto-form">\
		<table id="prettyPhoto-table" class="form-table">\
			<tr>\
				<th><label for="prettyPhoto-title">Title</label></th>\
				<td><input type="text" id="prettyPhoto-title" name="prettyPhoto-title" value="" /><br />\
				<small>Enter link title.</small></td>\
			</tr>\
			<tr>\
				<th><label for="prettyPhoto-url">Image path</label></th>\
				<td><input type="text" id="prettyPhoto-url" name="prettyPhoto-url" value="" /><br />\
				<small>Enter path to the image or link to the youtube/vimeo video.</small></td>\
			</tr>\
			<tr>\
				<th><label for="prettyPhoto-content">Link content</label></th>\
				<td><textarea id="prettyPhoto-content" name="prettyPhoto-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>link content, it can be text or image (html tag img)</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="prettyPhoto-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#prettyPhoto-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [prettyphoto';
			shortcode += ' image_title="'+jQuery('#prettyPhoto-title').val()+'"';
			shortcode += ' image_url="'+jQuery('#prettyPhoto-url').val()+'"';
			
			shortcode += ']';
			//shortcode += table.find('#prettyPhoto-content').val()+'[/toggle]';
			shortcode += jQuery('#prettyPhoto-content').val()+'[/prettyphoto] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()