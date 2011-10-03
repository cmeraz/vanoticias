// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.faq', {
		// creates control instances based on the control's id.
		// our button's id is "faq_button"
		createControl : function(id, controlManager) {
			if (id == 'faq_button') {
				// creates the button
				var button = controlManager.createButton('faq_button', {
					title : 'FAQ Section', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-faq.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'FAQ Section', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=faq-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('faq', tinymce.plugins.faq);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="faq-form">\
		<table id="faq-table" class="form-table">\
			<tr>\
				<th><label for="faq-title">Title</label></th>\
				<td><input type="text" id="faq-title" name="faq-title" value="" /><br />\
				<small>enter toggle title.</small></td>\
			</tr>\
			<tr>\
				<th><label for="faq-content">Toggle content</label></th>\
				<td><textarea id="faq-content" name="faq-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>toggle content.</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="faq-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#faq-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'title'    : ''
				//'content'  : ''
				};
			var shortcode = ' [toggle';
			
			for( var index in options) {
				var value = table.find('#faq-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index])
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			//shortcode += table.find('#faq-content').val()+'[/toggle]';
			shortcode += jQuery('#faq-content').val()+'[/toggle] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()