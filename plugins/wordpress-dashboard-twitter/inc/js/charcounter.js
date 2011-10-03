jQuery(document).ready(function() {
	jQuery('#wpdt-txtarea').each(function() {
		// get current number of characters
		var length = jQuery(this).val().length;
		var cur = 140 - length;
		jQuery('#wpdt-charcount').text(cur);
	}).bind("keyup focus", function() {
		// get new length of characters
		var new_length = jQuery(this).val().length;
		var cur2 = 140 - new_length;
		jQuery('#wpdt-charcount').text( cur2 );
		if( cur2 < 0 ) {
			jQuery('#wpdt-btn-send-status-update').hide();
		} else {
			jQuery('#wpdt-btn-send-status-update').show();
		}
	});
});