// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.tooltip', {
		// creates control instances based on the control's id.
		// our button's id is "tooltip_button"
		createControl : function(id, controlManager) {
			if (id == 'tooltip_button') {
				// creates the button
				var button = controlManager.createButton('tooltip_button', {
					title : 'Insert tooltip', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-tooltip-balloon.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert tooltip', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=tooltip-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('tooltip', tinymce.plugins.tooltip);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="tooltip-form">\
		<table id="tooltip-table" class="form-table">\
			<tr>\
				<th><label for="tooltip-text">Tooltip text</label></th>\
				<td><input type="text" id="tooltip-text" name="tooltip-text" value="" /><br />\
				<small>Enter tooltip text.</small></td>\
			</tr>\
			<tr>\
				<th><label for="tooltip-content">Tooltip content</label></th>\
				<td><textarea id="tooltip-content" name="tooltip-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>tooltip content, it can be text or image (html tag img)</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="tooltip-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#tooltip-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [tooltip';
			shortcode += ' text="'+jQuery('#tooltip-text').val()+'"';
			shortcode += ']';
			shortcode += jQuery('#tooltip-content').val()+'[/tooltip] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()