// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.message', {
		// creates control instances based on the control's id.
		// our button's id is "message_button"
		createControl : function(id, controlManager) {
			if (id == 'message_button') {
				// creates the button
				var button = controlManager.createButton('message_button', {
					title : 'Insert message', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-message.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert message', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=message-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('message', tinymce.plugins.message);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="message-form">\
		<table id="message-table" class="form-table">\
			<tr>\
				<th><label for="message-type">Message color</label></th>\
				<td>\
				<label>\
				  <input type="radio" name="message-type" value="green" id="type_0" checked="checked" />\
				  Green</label>\
				<label>\
				  <input type="radio" name="message-type" value="blue" id="type_1" />\
				  Blue</label>\
				<label>\
				  <input type="radio" name="message-type" value="yellow" id="type_2" />\
				  Yellow</label><br />\
				  <small>select message color.</small></td>\
			</tr>\
			<tr>\
				<th><label for="message-content">Message text</label></th>\
				<td><textarea id="message-content" name="message-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>Enter message text.</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="message-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		//var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#message-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [message color="'+jQuery('input[name=message-type]:checked').val()+'"';
			shortcode += ']';
			shortcode += jQuery('#message-content').val()+'[/message] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()