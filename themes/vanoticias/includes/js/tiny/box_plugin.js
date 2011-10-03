// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.box', {
		// creates control instances based on the control's id.
		// our button's id is "box_button"
		createControl : function(id, controlManager) {
			if (id == 'box_button') {
				// creates the button
				var button = controlManager.createButton('box_button', {
					title : 'Insert floating box', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-box.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert floating box', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=box-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('box', tinymce.plugins.box);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="box-form">\
		<table id="box-table" class="form-table">\
			<tr>\
				<th><label for="box-type">Box orientation</label></th>\
				<td>\
				<label>\
				  <input type="radio" name="box-type" value="left" id="type_0" checked="checked" />\
				  Left</label>\
				<label>\
				  <input type="radio" name="box-type" value="right" id="type_2" />\
				  Right</label><br />\
				  <small>select box orientation.</small></td>\
			</tr>\
			<tr>\
				<th><label for="box-content">Box content</label></th>\
				<td><textarea id="box-content" name="box-content" cols="40" rows="5" rel=""></textarea><br />\
				<small>Enter box text.</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="box-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		</div>');
		
		//var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#box-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [box_'+jQuery('input[name=box-type]:checked').val();
			shortcode += ']';
			shortcode += jQuery('#box-content').val()+'[/box_'+jQuery('input[name=box-type]:checked').val()+'] ';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()