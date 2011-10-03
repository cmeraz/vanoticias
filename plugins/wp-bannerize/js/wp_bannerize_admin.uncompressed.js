/**
 * Javascript functions
 *
 * @package         wp_bannerize
 * @subpackage      wp_bannerzie_admin.js
 * @author          =undo= <g.fazioli@saidmade.com>
 * @copyright       Copyright (C) 2010 Saidmade Srl
 * @version         2.5.0
 */


var SMWPBannerizeJavascript = {

	/**
	 * Call when the user click on "Update" button on inline edit form
	 *
	 * @param id
	 */
	update : function(id) {
		var f = document.forms['form_show'];
		f.id.value = id;
		f.submit();
	},

	/**
	 * Call when the user click on "Edit" link in the Show View
	 *
	 * @param id
	 * @param c
	 */
	showInlineEdit : function(id, c) {
		jQuery(id).html( unescape(c) );
		jQuery('div.inline-edit').slideDown();
	},

	/**
	 * Call when the user click on "Cancel" button on inline edit form
	 *
	 * @param id
	 */
	hideInlineEdit: function( id ) {
		jQuery('div.inline-edit').slideUp(function() {
			jQuery('div#edit_' + id).html( "" );
		});
	}
};

/**
 * Document Ready setup
 */
jQuery(document).ready(function() {
	jQuery("a.fancybox").fancybox();

	jQuery('table#wp_bannerize_list tbody tr').css('width',jQuery('table#wp_bannerize_list').width() );
	jQuery('table#wp_bannerize_list tbody').sortable({
				axis:"y",
				cursor:"n-resize",
				stop:function() {
					var data_items = jQuery("table#wp_bannerize_list tbody").sortable("serialize");
					var rel_attr = jQuery('table#wp_bannerize_list').attr('rel');
					var info = rel_attr.split(",");
					data_items += "&offset=" + info[0];
					data_items += "&limit=" + info[1];
					jQuery.ajax({
					type: "POST",
					url: wpBannerizeMainL10n.ajaxURL,
					data: data_items})
				}
	});

	// Combo Insert
	jQuery('select#group_filter').change(function() {
		jQuery('input#group').val(jQuery(this).val());
	});

	// edit
	jQuery('span.edit a').click(function() {
		jQuery('div#' + jQuery(this).attr('class') ).slideDown();
	});

	// trash
	jQuery('span.trash a').click(function() {
		var f = document.forms['wp_bannerize_action'];
		f.command_action.value = 'trash';
		f.id.value = jQuery(this).attr('class');
		f.submit();
	});

	// delete
	jQuery('span.delete a').click(function() {
		if( confirm( wpBannerizeMainL10n.messageConfirm ) ) {
			var f = document.forms['wp_bannerize_action'];
			f.command_action.value = 'delete';
			f.id.value = jQuery(this).attr('class');
			f.submit();
		}
	});

	// restore
	jQuery('span.restore a').click(function() {
		var f = document.forms['wp_bannerize_action'];
		f.command_action.value = 'untrash';
		f.id.value = jQuery(this).attr('class');
		f.submit();
	});
});