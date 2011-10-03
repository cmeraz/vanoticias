jQuery(document).ready(function(jQuery) {
	new AjaxUpload('#wpdt-imgupload_button', {
		action: wpdtAjaxL10n.uploadFileURI + 'upload.func.php?action=upload-image',
		name: 'userfile',
		data: {
    		_ajax_nonce : wpdtAjaxL10n._ajax_nonce
		},
		autoSubmit: true,
		responseType: false,
		onChange: function(file, extension) {
			if (!(extension && /^(jpg|png|jpeg|gif)$/.test(extension))) {
				alert( wpdtAjaxL10n.invalidFileExtMsg );
				return false;
			} else {
				jQuery('#wpdt-ajax-loader-update').show();
			}
		},
		onSubmit: function(file, extension) {
			if (!(extension && /^(jpg|png|jpeg|gif)$/.test(extension))) {
				alert( wpdtAjaxL10n.invalidFileExtMsg );
				return false;
			} else {
				jQuery('#wpdt-ajax-loader-update').show();
			}
		},
		onComplete: function(file, response) {
			//console.log(response);
			WPDashboardTwitter.shorten_imgurl( file );
		}
	});
});