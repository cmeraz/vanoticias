// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.slideshow', {
		// creates control instances based on the control's id.
		// our button's id is "slideshow_button"
		createControl : function(id, controlManager) {
			if (id == 'slideshow_button') {
				// creates the button
				var button = controlManager.createButton('slideshow_button', {
					title : 'Insert slideshow', // title of the button
					image : '../wp-content/themes/photoshot/images/ui-slideshow.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Insert slideshow', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=slideshow-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('slideshow', tinymce.plugins.slideshow);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="slideshow-form">\
		<table id="slideshow-table" class="form-table">\
			<tr>\
				<td colspan="2">Create new slideshow section.</td>\
			</tr>\
			<tr>\
				<td>\
				<label for="slideshow-content1">Slide description text</label> <input type="text" id="slideshow-content1" name="slideshow-content1" value="" /><br />\
				<label for="slideshow-image1">Image path</label> <input type="text" id="slideshow-image1" name="slideshow-image1" value="" /><br />\
				<label for="slideshow-url1">Slide url</label> <input type="text" id="slideshow-url1" name="slideshow-url1" value="" /><br />\
				\
				<td>\
				<label for="slideshow-content2">Slide description text</label> <input type="text" id="slideshow-content2" name="slideshow-content2" value="" /><br />\
				<label for="slideshow-image2">Image path</label> <input type="text" id="slideshow-image2" name="slideshow-image2" value="" /><br />\
				<label for="slideshow-url2">Slide url</label> <input type="text" id="slideshow-url2" name="slideshow-url2" value="" /><br />\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="slideshow-submit" class="button-primary" value="Insert" name="submit" />\
		</p>\
		\
		<table id="slide-table" class="form-table">\
			<tr>\
				<td colspan="2">Add one more slide.</td>\
			</tr>\
			<tr>\
				<td>\
				<label for="slideshow-content3">Slide description text</label> <input type="text" id="slideshow-title3" name="slideshow-title3" value="" /><br />\
				<label for="slideshow-image3">Image path</label> <input type="text" id="slideshow-image3" name="slideshow-image3" value="" /><br />\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="slide-submit" class="button-primary" value="Add one slide" name="submit" />\
		</p>\
		</div>');
		
		//var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#slideshow-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var shortcode = ' [slideshow]';
			var slide_description = '';
			var slide_image = '';
			var slide_url = '';
			slide_description = jQuery('#slideshow-content1').val(); slide_image = jQuery('#slideshow-image1').val();
			if (slide_description != '' && slide_image != '') {
				slide_url = jQuery('#slideshow-url1').val();
				if (slide_url != '') {
					slide_url = ' url="'+slide_url+'"';
				}
				shortcode += '[slide image="'+slide_image+'"'+slide_url+']'+slide_description+'[/slide] ';
				slide_description = ''; slide_image = '';
			}
			slide_description = jQuery('#slideshow-content2').val(); slide_image = jQuery('#slideshow-image2').val();
			if (slide_description != '' && slide_image != '') {
				slide_url = jQuery('#slideshow-url2').val();
				if (slide_url != '') {
					slide_url = ' url="'+slide_url+'"';
				}
				shortcode += '[slide image="'+slide_image+'"'+slide_url+']'+slide_description+'[/slide] ';
				slide_description = ''; slide_image = '';
			}
			shortcode += '[/slideshow] ';
			
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