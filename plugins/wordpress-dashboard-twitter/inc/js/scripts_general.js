var WPDashboardTwitter = {
	
	init: function() {
		this.onLoad();
	},
	
	onLoad: function() {
		var needs_jquery_hotfix = ((jQuery.ui.version === undefined) || !jQuery.ui.version.match(/^(1\.[7-9]|[2-9]\.)/));
		jQuery("#wpdt-tabs" + (needs_jquery_hotfix ? ">ul" : "")).tabs({
			selected: wpdtAjaxL10n.startupTab
		});
		
		jQuery('#wpdt-btn-load-replies').click(function() {
			WPDashboardTwitter.tabLoader( 0 );
			return false;
		});
		jQuery('#wpdt-btn-load-direct-messages').click(function() {
			WPDashboardTwitter.tabLoader( 1 );
			return false;
		});
		jQuery('#wpdt-btn-load-sent-messages').click(function() {
			WPDashboardTwitter.tabLoader( 2 );
			return false;
		});
		jQuery('#wpdt-btn-load-favorites').click(function() {
			WPDashboardTwitter.tabLoader( 3 );
			return false;
		});
		jQuery('#wpdt-btn-load-retweets').click(function() {
			WPDashboardTwitter.tabLoader( 4 );
			return false;
		});
		jQuery('#wpdt-btn-load-timeline').click(function() {
			WPDashboardTwitter.tabLoader( 5 );
			return false;
		});
		
		jQuery("#wpdt-tabs" + (needs_jquery_hotfix ? ">ul" : "")).bind('tabsselect', function(event, ui) {
			WPDashboardTwitter.tabLoader( ui.index );
		});
		
		WPDashboardTwitter.tabLoader( wpdtAjaxL10n.startupTab );
		
		jQuery('.wpdt-btn-update-status').click(function() {
			WPDashboardTwitter.showWritePanel();
			return false;
		});
		
		jQuery('#wpdt-btn-cancel-status-update').click(function() {
			WPDashboardTwitter.hideWritePanel();
			return false;
		});
		
		jQuery('#wpdt-btn-send-status-update').click(function() {
			var tweettxt = jQuery('#wpdt-txtarea').val();
			var length = jQuery('#wpdt-txtarea').val().length;
			if( length == 0 || jQuery.trim(tweettxt) == '' ) {
				alert( wpdtAjaxL10n.emptyTweetMsg );
			} else {
				var revert_t = jQuery('#wpdt-btn-send-status-update').text();
				jQuery('#wpdt-btn-send-status-update').text( wpdtAjaxL10n.sendingTweetMsg );
				WPDashboardTwitter.send_update( revert_t );
			}
			return false;
		});
		
		jQuery('#wpdt-btn-shorten-url').click(function() {
			var longurl = jQuery('#wpdt-long-url').val();
			var length = jQuery('#wpdt-long-url').val().length;
			if( length == 0 || jQuery.trim(longurl) == '' ) {
				alert( wpdtAjaxL10n.emptyLongUrlMsg );
			} else {
				WPDashboardTwitter.shorten_url( longurl );
			}
			return false;
		});
		
		jQuery("#wpdt-txtarea").bind("keyup focus", function() {
  			var expr = /^d[\s](.*)[\s].*/i;
 			var tweettext = jQuery(this).val();
			var result = expr.test( tweettext );
			if (result == false) {
				jQuery('#wpdt-btn-send-status-update').text( wpdtAjaxL10n.updateStatusMsg );
			} else {
				jQuery('#wpdt-btn-send-status-update').text( wpdtAjaxL10n.sendDMMsg );
			}
		});
		
		jQuery('#wpdt-btn-verify-userdata').click(function() {
			WPDashboardTwitter.verify_credentials();
			return false;
		});
		
		jQuery('#wp_dashboard_twitter .wpdt_credentials').focus(function() {
			jQuery('#wp_dashboard_twitter .wpdt_credentials').css('background-color', '');
		});
	},
	
	tabLoader: function( index ) {
		switch ( index ) {
			case 0:
				WPDashboardTwitter.load_replies();
				break;
			case 1:
				WPDashboardTwitter.load_direct_messages();
				break;
			case 2:
				WPDashboardTwitter.load_sent_messages();
				break;
			case 3:
				WPDashboardTwitter.load_favorites();
				break;
			case 4:
				WPDashboardTwitter.load_retweets();
				break;
			case 5:
				WPDashboardTwitter.load_timeline();
				break;
		}
	},
	
	showWritePanel: function() {
		var needs_jquery_hotfix = ((jQuery.ui.version === undefined) || !jQuery.ui.version.match(/^(1\.[7-9]|[2-9]\.)/));
		
		jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/charcounter.js");
		/*if( wpdtAjaxL10n.twitPicEnabled == 1 ) {
			jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/ajaxupload.3.5.js", function() {
				jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/scripts_ajaxupload.js");
			});
		}*/
		
		var isHidden = jQuery('#wpdt-update-wrapper').is(':hidden');
		if( isHidden ) {
			jQuery("#wpdt-tabs" + (needs_jquery_hotfix ? ">ul" : "")).tabs('option', 'disabled', [0,1,2,3]);
			jQuery('.wpdt-btn-update-status').fadeOut();
			jQuery('#wpdt-update-wrapper').slideDown();
		}
	},
	
	hideWritePanel: function() {
		var needs_jquery_hotfix = ((jQuery.ui.version === undefined) || !jQuery.ui.version.match(/^(1\.[7-9]|[2-9]\.)/));
		var isVisible = jQuery('#wpdt-update-wrapper').is(':visible');
		if( isVisible ) {
			jQuery("#wpdt-tabs" + (needs_jquery_hotfix ? ">ul" : "")).tabs('option', 'disabled', []);
			jQuery('.wpdt-btn-update-status').fadeIn();
			jQuery('#wpdt-update-wrapper').slideUp();
		}
		jQuery('#wpdt-btn-send-status-update').text( wpdtAjaxL10n.updateStatusMsg );
		jQuery('#wpdt-txtarea').val('');
		jQuery('#wpdt_in_reply_to_statusid').val('');
		jQuery('#wpdt-long-url').val('');
	},
	
	reply: function( type, replytoid ) {
		var needs_jquery_hotfix = ((jQuery.ui.version === undefined) || !jQuery.ui.version.match(/^(1\.[7-9]|[2-9]\.)/));
		jQuery("#wpdt-tabs" + (needs_jquery_hotfix ? ">ul" : "")).tabs('option', 'disabled', [0,1,2,3]);
		jQuery('.wpdt-btn-update-status').hide();
		jQuery('#wpdt-update-wrapper').show();
		
		jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/charcounter.js");
		if( wpdtAjaxL10n.twitPicEnabled == 1 ) {
			jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/ajaxupload.3.5.js", function() {
				jQuery.getScript(wpdtAjaxL10n.uploadFileURI + "js/scripts_ajaxupload.js");
			});
		}
		
		if( type == 0 ) {
			jQuery('#wpdt_in_reply_to_statusid').val( replytoid );
			var replytoname = jQuery('#wpdt-replies-wrapper li#wpdtreply-' + replytoid).find('a.meta-reply').attr('replytoname');
			jQuery('#wpdt-txtarea').val('@' + replytoname + ': ');
		} else if( type == 1 ) {
			var replytoname = jQuery('#wpdt-direct-wrapper li#wpdtdm-' + replytoid).find('a.meta-reply').attr('replytoname');
			jQuery('#wpdt-txtarea').val('d ' + replytoname + ' ');
		} else if( type == 2 ) {
			var tweetxt = jQuery('#wpdt-replies-wrapper li#wpdtreply-' + replytoid + ' .wpdt-text p').html();
			tweetxt = tweetxt.replace(/(<([^>]+)>)/gi, "");
			var replytoname = jQuery('#wpdt-replies-wrapper li#wpdtreply-' + replytoid).find('a.meta-reply').attr('replytoname');
			jQuery('#wpdt-txtarea').val('RT @' + replytoname + ': ' + tweetxt);
			jQuery('#wpdt_in_reply_to_statusid').val('');
		}
		jQuery('#wpdt-txtarea').focus();
		return false;
	},
	
	load_replies: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_replies" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	load_direct_messages: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_direct_messages" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	load_sent_messages: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_sent_messages" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	load_favorites: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_favorites" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	load_retweets: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_retweets" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	load_timeline: function() {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_load_timeline" );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('.wpdt-ajax-loader').show(); };
		wpdt_sack.onCompletion = function() { jQuery('.wpdt-ajax-loader').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	send_update: function( revert_t ) {
		var status_text = jQuery('#wpdt-txtarea').val();
		var in_reply_to_statusid = jQuery('#wpdt_in_reply_to_statusid').val();
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_send_update" );
		wpdt_sack.setVar( "status_text", status_text );
		wpdt_sack.setVar( "in_reply_to_statusid", in_reply_to_statusid );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('#wpdt-ajax-loader-update').show(); };
		wpdt_sack.onCompletion = function() { jQuery('#wpdt-ajax-loader-update').hide(); jQuery('#wpdt-btn-send-status-update').text( revert_t ); WPDashboardTwitter.hideWritePanel(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	shorten_url: function( longurl ) {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_shorten_url" );
		wpdt_sack.setVar( "longurl", longurl );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('#wpdt-ajax-loader-update').show(); };
		wpdt_sack.onCompletion = function(d) { jQuery('#wpdt-ajax-loader-update').hide(); jQuery('#wpdt-long-url').val(''); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	shorten_imgurl: function( imgbasename ) {
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_shorten_imgurl" );
		wpdt_sack.setVar( "imgbasename", imgbasename );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('#wpdt-ajax-loader-update').show(); };
		wpdt_sack.onCompletion = function(d) { jQuery('#wpdt-ajax-loader-update').hide(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	},
	
	verify_credentials: function() {
		var twitter_username = jQuery('#wp_dashboard_twitter #twitter_login').val();
		var twitter_password = jQuery('#wp_dashboard_twitter #twitter_pwd').val();
		var wpdt_sack = new sack(wpdtAjaxL10n.requestUrl);
		wpdt_sack.execute = 1;
		wpdt_sack.method = 'POST';
		wpdt_sack.setVar( "action", "wpdt_verify_credentials" );
		wpdt_sack.setVar( "username", twitter_username );
		wpdt_sack.setVar( "password", WPDashboardTwitter_Helper.base64_encode( twitter_password ) );
		wpdt_sack.setVar( "ajaxCall", 1 );
		wpdt_sack.setVar( "_ajax_nonce", wpdtAjaxL10n._ajax_nonce );
		wpdt_sack.onLoading = function() { jQuery('#wpdt-verify-userdata-ajax-loader').fadeIn(); };
		wpdt_sack.onCompletion = function(d) { jQuery('#wpdt-verify-userdata-ajax-loader').fadeOut(); };
		wpdt_sack.onError = function() { alert('Ajax error') };
		wpdt_sack.runAJAX();
	}
}

jQuery(document).ready(function(jQuery) {
	WPDashboardTwitter.init();
});