// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.image_preview', {
		// creates control instances based on the control's id.
		// our button's id is "image_preview_button"
		createControl : function(id, controlManager) {
			if (id == 'image_preview_button') {
				// creates the button
				var button = controlManager.createButton('image_preview_button', {
					title : 'Insert nice image preview', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-image_preview.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert nice image preview', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=image_preview-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('image_preview', tinymce.plugins.image_preview);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="image_preview-form">\
		<table id="image_preview-table" class="form-table">\
			<tr>\
				<th><label for="image_preview-title">Title</label></th>\
				<td><input type="text" id="image_preview-title" name="image_preview-title" value="" /><br />\
				<small>Enter image title.</small></td>\
			</tr>\
			<tr>\
				<th><label for="image_preview-url">Image path</label></th>\
				<td><input type="text" id="image_preview-url" name="image_preview-url" value="" /><br />\
				<small>Enter path to the image.</small></td>\
			</tr>\
			<tr>\
				<th><label for="image_preview-content">Link content</label></th>\
				<td><textarea id="image_preview-content" name="image_preview-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>link content, it can be text or image (html tag img)</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="image_preview-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#image_preview-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [image_preview';
			shortcode += ' image_title="'+jQuery('#image_preview-title').val()+'"';
			shortcode += ' image_url="'+jQuery('#image_preview-url').val()+'"';
			
			shortcode += ']';
			//shortcode += table.find('#image_preview-content').val()+'[/toggle]';
			shortcode += jQuery('#image_preview-content').val()+'[/image_preview] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()