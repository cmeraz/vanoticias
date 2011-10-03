var $j = jQuery.noConflict();

var int=0;//Internet Explorer Fix
$j(window).bind("load", function() {
	int = setInterval("fadeImg()", 300);
});

function fadeImg() {
	var images = $j('.imgfade:hidden').length;
	if (images == 1) {
		clearInterval(int);
		delete int;
	}
	var randomnumber = Math.floor(Math.random()*images);
	//var randomnumber = 0;
	$j('.imgfade:hidden').eq(randomnumber).fadeIn(900);
}

function menu() {
	/*$j("#menu")
	.stop()
	.animate({"opacity": 0}, 0);*/
	
	$j("#menu ul li a").each(function(){
		for (i=0; i<250; i++) {
			if ($j(this).width() < 120) {
				$j(this).css({"font-size" : i});
			} else {
				return;
			}
		}
	});
	
	$j("#menu li ul li a").each(function(){/*$j("#menu ul.children li a").each(function(){*/
		var new_f_z = $j(this).parent().parent().parent().find("a").css("font-size");
		$j(this).css({"font-size" : new_f_z});
	});
	
	$j("#menu li ul").css({"display" : "none"}); /*#menu ul.children*/
	
	$j("#menu li").hover(function() {
		$j(this).addClass("hover").find("ul:first").css({"display" : "block"})/*ul.children*/
		.stop()
		.animate({"opacity": 0}, 0)
		.animate({
			"opacity" : 1
			} , 500
		);
	},
    // Roll Out
    function() {
		$j(this).removeClass("hover").find("ul:first").stop()/*ul.children*/
		.animate({"opacity": 0}, 500, function(){$j(this).css({"display" : "none"})})
	});
} // end of function inputBehaviour

function big_image_hover() {
	$j(".thumbnail").hover(function() {
		if ($j(this).parent().hasClass("first_image")) {
			$j(".first_image_description").css({"display" : "block"})
			.stop()
			.animate({"opacity": 0}, 0)
			.animate({
				"opacity" : 1
				} , 500
			);
		} else {
			$j(this).find(".other_image_description").css({"display" : "block"})
			.stop()
			.animate({"opacity": 0}, 0)
			.animate({
				"opacity" : 1
				} , 500
			);
		}
		$j(this).parent().find(".add_to_favorites").css({"display" : "block"})
			.stop()
			.animate({"opacity": 0, "top": 30}, 0)
			.animate({
				"opacity" : 1,
				"top"     : 0
				} , 500
			);
	},
    // Roll Out
    function() {
		if ($j(this).parent().hasClass("first_image")) {
			$j(this).find(".first_image_description").stop()
			.animate({"opacity": 0}, 500, function(){$j(this).css({"display" : "none"})});
		} else {
			$j(this).find(".other_image_description").stop()
			.animate({"opacity": 0}, 500, function(){$j(this).css({"display" : "none"})});
		}
		$j(this).parent().find(".add_to_favorites").css({"display" : "block"})
			.stop()
			.animate({
				"opacity" : 0
				} , 500
			);
	});
}

function add_to_favorites() {
	/*var list_cookie = $j.cookie('fav_list_cookie');
	console.log(list_cookie);

	if (list_cookie != null) {
		$j("#favorite_photos ul.favorite_photos_ul").append(list_cookie);
	}*/

	$j('.controls .star_button, .add_to_favorites').click(function () {
		var th = $j(".single_photo .first_image .thumbnail img");
		var img_link = document.location.href;
		var pid = parseInt($j(".single_photo .controls .star_button").attr("rel"));
		
		if (th.length == 0) {
			th = $j(this).parent().find("a:eq(0) img");
			img_link = $j(this).parent().find("a:eq(0)").attr("href");
			pid = parseInt($j(this).parent().find("a:eq(0)").attr("rel"));
		}
		
		var img_src = th.attr("src");
		var img_title = th.attr("title");
	
		var offset = th.offset(); 
		var img_new_x = offset.left;
		var img_new_y = offset.top;
		
		var star_offset = $j("#twitter_favorites .star_button").offset();
		var final_pos_x = star_offset.left + $j("#twitter_favorites .star_button").outerWidth()/2 - 5;
		var final_pos_y = star_offset.top + $j("#twitter_favorites .star_button").outerHeight()/2 - 5;
		
		$j("body").append('<img id="favorite_action" src="'+img_src+'" />');
		$j("#favorite_photos .hidden").removeClass("hidden");
		add_photo_to_favorite_list(img_src, img_title, img_link, pid);
		$j("#favorite_action").css({
			 "top"  : img_new_y,
			 "left" : img_new_x
		})
		.animate({
			"left"    : final_pos_x,
			"top"     : final_pos_y,
			"width"   : 10,
			"height"  : 10,
			"opacity" : 20
		}, 800, function() {$j(this).remove();} );
		return false;
	});
	
	$j('.clear_favorites').click(function () {
		$j("#favorite_photos ul.favorite_photos_ul").html("");
		$j("#favorite_photos .slideshow_button, #favorite_photos .clear_favorites").addClass("hidden");
		$j("#favorite_photos .slideshow_button").attr({"href" : ""});
		var str = "action=reset";
		$j.ajax({
			 type: "POST",
			 url: templatepath+"setcookie.php",
			 data: str,
			 success: function(response) {
			 /*console.log(response); */
			}
		});
		return false;
	});
	
	
	$j("#twitter_favorites .star_button").hover(function() {
		favorite_hover = true;
		$j("#favorite_photos").fadeIn();
		$j("#tweets").fadeOut();
	},
    // Roll Out
    function() {
		favorite_hover = false;
		int = setInterval("hideFavorites()", 500);
	});
	
	$j("#favorite_photos").hover(function() {
		favorite_hover = true;
	},
    // Roll Out
    function() {
		favorite_hover = false;
		int = setInterval("hideFavorites()", 500);
	});
	
	$j('#twitter_favorites .star_button, #twitter_favorites .twitter_button').click(function () {
		return false;
	});

}

var favorite_hover = false;
var int = 0;
function hideFavorites() {
	clearInterval(int);
	delete int;
	if (favorite_hover == false) {
		$j("#favorite_photos").fadeOut();
	}
}
//$j.cookie('fav_list_cookie', null);
function add_photo_to_favorite_list(img_src, img_title, img_link, pid) {
	var new_fav_item = '<li><a href="'+img_link+'" title="'+img_title+'"><img class="zoom" width="134" height="88" src="'+img_src+'" /></a></li>';
	var item_to_search = $j('#favorite_photos a[href="'+img_link+'"]').length;
	if (item_to_search == 0) {
		$j("#favorite_photos ul.favorite_photos_ul").append(new_fav_item);
		zoomIcon();
		var fav_list_val = pid;
		if ($j("#favorite_photos .slideshow_button").attr("href") == "") {
			$j("#favorite_photos .slideshow_button").attr({"href" : sitepath+"?css="+fav_list_val});
		} else {
			var chref = $j("#favorite_photos .slideshow_button").attr("href");
			$j("#favorite_photos .slideshow_button").attr({"href" : chref+","+fav_list_val});
		}
		//SETCOOKIE
		var str = "action=set&favcookie="+fav_list_val;
		$j.ajax({
			 type: "POST",
			 url: templatepath+"setcookie.php",
			 data: str,
			 success: function(response)
			 {
				 //console.log(response);
			 }
		});
	}
}



var twitter_hover = false;
var inttwi = 0;

function show_twitter() {
	$j("#twitter_favorites .twitter_button").hover(function() {
		twitter_hover = true;
		$j("#tweets").fadeIn();
		$j("#favorite_photos").fadeOut();
	},
    // Roll Out
    function() {
		twitter_hover = false;
		inttwi = setInterval("hideTwitter()", 500);
	});
	
	$j("#tweets").hover(function() {
		twitter_hover = true;
	},
    // Roll Out
    function() {
		twitter_hover = false;
		inttwi = setInterval("hideTwitter()", 500);
	});
}

function hideTwitter() {
	clearInterval(inttwi);
	delete inttwi;
	if (twitter_hover == false) {
		$j("#tweets").fadeOut();
	}
}

var thumbs_hover = false;
var intthumbs = 0;

function show_thumbs() {
	$j(".thumbnails_button").hover(function() {
		thumbs_hover = true;
		$j("#thumbs").fadeIn();
	},
    // Roll Out
    function() {
		thumbs_hover = false;
		intthumbs = setInterval("hideThumbs()", 500);
	});
	
	$j("#thumbs").hover(function() {
		thumbs_hover = true;
	},
    // Roll Out
    function() {
		thumbs_hover = false;
		intthumbs = setInterval("hideThumbs()", 500);
	});
}

function hideThumbs() {
	clearInterval(intthumbs);
	delete intthumbs;
	if (thumbs_hover == false) {
		$j("#thumbs").fadeOut();
	}
}

function loadTweets() {
	if (twitterlogin != '') {
		$j("#tweets .tweets_holder").tweet({
		  join_text: "auto",
		  username: twitterlogin,
		  avatar_size: 60,
		  count: 5,
		  auto_join_text_default: "", 
		  auto_join_text_ed: "",
		  auto_join_text_ing: "",
		  auto_join_text_reply: "",
		  auto_join_text_url: "",
		  loading_text: "..."
		});
	}
}

var tags_hover = false;
var inttag = 0;

function show_tags() {
	$j(".tags_button").hover(function() {
		tags_hover = true;
		$j("#tags_holder").fadeIn();
	},
    // Roll Out
    function() {
		tags_hover = false;
		inttag = setInterval("hideTags()", 500);
	});
	
	$j("#tags_holder").hover(function() {
		tags_hover = true;
	},
    // Roll Out
    function() {
		tags_hover = false;
		inttag = setInterval("hideTags()", 500);
	});
}

function hideTags() {
	clearInterval(inttag);
	delete inttag;
	if (tags_hover == false) {
		$j("#tags_holder").fadeOut();
	}
}


function showComments() {
	$j('.single_photo .controls .comment_button').click(function () {
		$j(".hide_content").slideToggle(800);
		return false;
	})
	.toggle(
		function () {
			$j(this).html("hide comments");
		},
		function () {
			$j(this).html("view comments");
		}
	);
}


function window_resize() {
	var w = $j(window).width();
	if (w > '1160') {
		$j("body").removeClass("small").addClass("wide");
		return false;
	} else {
		$j("body").removeClass("wide").addClass("small");
		return false;
	}
}

function resizeSlideShow() {
	var w = $j(window).width();
	var h = $j(window).height();
	var xx = parseInt($j(".slideshow").css("left"));
	var neww = w - xx - (xx - $j("#left").outerWidth());
	var newh = h - 120;
	$j(".slideshow").css({"width" : neww, "height" : newh});
}

/**** Global variables which are used by the image preview plugin **/
// Thanks DigitalCavalry (http://themeforest.net/user/digitalcavalry) for the box positioning hint. I owe you a beer! ;)
var g_previewImgWidth = 0;
var g_previewImgHeight = 0;
var g_imgExtraYOffset = 0;
var g_showLoader = false;
var g_initHoverX = 0;
var g_initHoverY = 0;
var g_topPositionAdjusted = false;

var g_imgDescription = '';
function imagePreviewBehaviour() {
    $j(".imgpreview").click(function() {
		return false;
	});
	$j(".imgpreview").hover(function(e) {
        var offsetX = 0;       
        var offsetY = -10;
        var hoveredObject = this;        
        var imageSrc = $j(hoveredObject).attr("href");
  
        $j("body").append('<div id="imgPreview"><div id="imgPreviewImg"></div><div id="imgPreviewDescription"></div></div>');
        $j("body").append('<div id="imgLoader"></div>');
		
        $j("#imgLoader")
			.stop()
			.css({
				"opacity" : 0,
				"left"    : (e.pageX + 8) + "px",
			    "top"     : (e.pageY - 25) + "px"
			})
			.animate({opacity: 1}, 400);
        g_showLoader = true;
            
        $j("#imgPreview").hide();
          
		var img = new Image();
		$j(img).load(function() {
			g_previewImgWidth = img.width;
			g_previewImgHeight = img.height;
			g_imgExtraYOffset = 0;
			
			offsetX = -(g_previewImgWidth / 2);
			g_imgDescription = $j(hoveredObject).attr("title");
			if (g_imgDescription.length != 0) {
				$j(hoveredObject).removeAttr('title');
				$j("#imgPreviewDescription").html(g_imgDescription);
				$j("#imgPreview").show();
				g_imgExtraYOffset = $j("#imgPreviewDescription").outerHeight(true);
				$j("#imgPreview").hide();
			} else {
				$j("#imgPreviewDescription").remove();
			}

			g_initHoverX = e.pageX;
			g_initHoverY = e.pageY;
			var browserWidth = $j(window).width();
			var browserHeight = $j(window).height();
			var previewLeftPosition = e.pageX + offsetX;
			var previewTopPosition = e.pageY + offsetY - g_previewImgHeight - g_imgExtraYOffset;
			
			if (g_previewImgHeight > e.clientY) {
			   previewTopPosition += g_imgExtraYOffset + g_previewImgHeight - offsetY*2;
			   g_topPositionAdjusted = true;
			}
			if (previewLeftPosition < 0) {
				previewLeftPosition = 0;
			}
			if (previewLeftPosition + g_previewImgWidth > browserWidth) {
				previewLeftPosition = browserWidth - g_previewImgWidth;
			}

			$j("#imgPreviewImg").html(this);
			$j("#imgPreviewImg").css({"height": g_previewImgHeight+"px"});

			$j("#imgPreview").hide()
				.css({
					 "visibility" : "visible",
					 "height"     : "auto",
					 "width"      : g_previewImgWidth+"px",
					 "top"        : previewTopPosition + "px",
					 "left"       : previewLeftPosition + "px"
				}).show();
			$j("#imgLoader").stop().animate({opacity: 0}, 400, function(){$j(this).remove()});
			g_showLoader = false;
			
			$j("#imgPreview")
				.css({
					"margin"  : "0px",
					"padding" : "0px",
					"opacity" : "0"})
				.animate({opacity: 1}, 500);
		}).attr("src", imageSrc);

    },
    // Roll Out
    function() {
		if (g_imgDescription.length != 0) {
			var titleAtr = $j("#imgPreviewDescription").html();
			$j(this).attr({"title": titleAtr});
			g_imgDescription = '';
		}
		
        $j("#imgPreview").stop().remove(); 
        $j("#imgLoader").stop().remove();
		g_topPositionAdjusted = false;
		g_showLoader = false;
    });    
    
    $j(".imgpreview").mousemove(function(e) {
		var offsetX = -g_previewImgWidth / 2;
		var offsetY = -10;
		
		var browserWidth = $j(window).width();
		var previewLeftPosition = e.pageX + offsetX;
		var previewTopPosition = e.pageY + offsetY - g_previewImgHeight - g_imgExtraYOffset;

		if (g_previewImgHeight > e.clientY || g_topPositionAdjusted == true) {
			if (g_topPositionAdjusted == true) {
				previewTopPosition += g_imgExtraYOffset + g_previewImgHeight - (offsetY*2);
			}
		}
		if (previewLeftPosition < 0) {
			previewLeftPosition = 0;
		}
		if (previewLeftPosition + g_previewImgWidth > browserWidth) {
			previewLeftPosition = browserWidth - g_previewImgWidth;
		}

		$j("#imgPreview")
			.css({
				"top"  : previewTopPosition + "px",
				"left" : previewLeftPosition + "px"
			});

		if (g_showLoader) {
			$j("#imgLoader")
				.css({
					"left" : (e.pageX + 8) + "px",
					"top"  : (e.pageY - 24) + "px"
				});
		}
	});

}; // end of function imagePreviewBehaviour

function inputBehaviour() {
	$j("input:text, textarea").each(function(){
		$j(this).attr({"rel": $j(this).val()});
		
		$j(this)
		.focus(function () {
			if ($j(this).val() == $j(this).attr('rel')) {
				$j(this).val('');
			}
			$j(this).addClass("focus");
		})
		.blur(function () {
			if ($j(this).val() == '') {
				$j(this).val($j(this).attr('rel'));
			} else {
				$j(this).addClass("notempty");
			}
			$j(this).removeClass("focus");
		});
	});
} // end of function inputBehaviour


function prettyPhotos() {
	$j("a[rel^='prettyPhoto'], .prettyPhoto, .gallery .gallery-item a").prettyPhoto({
		animationSpeed: 'normal', /* fast/slow/normal */
		padding: 15, /* padding for each side of the picture */
		opacity: 0.7, /* Value betwee 0 and 1 */
		showTitle: false, /* true/false */
		allowresize: true, /* true/false */
		counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
		theme: 'facebook', /* light_rounded / dark_rounded / light_square / dark_square */
		hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
		modal: false, /* If set to true, only the close button will close the window */
		changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
		callback: function(){} /* Called when prettyPhoto is closed */
	});
}

function commentsValidateBehaviour() {
	$j("#commentform #submit").click( function(){
		$j(".error").removeClass("error");
		
		var hasError = false;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var emailToVal = $j("#email").val();
		if (emailToVal == '') {
			$j("#email").addClass("error");
			hasError = true;
		} else if (!emailReg.test(emailToVal) && emailToVal != undefined) {	
			$j("#email").addClass("error");
			hasError = true;
		}
		
		var nameVal = $j("#author").val();
		if (nameVal == '' || nameVal == 'Name' ) {
			$j("#author").addClass("error");
			hasError = true;
		}
		
		var messageVal = $j("#comment").val();
		if(messageVal == '' || messageVal == 'Your message') {
			$j("#comment").addClass("error");
			hasError = true;
		}
		
		if (hasError == false) {
			var urlVal = $j("#url").val();
			if (urlVal == '' || urlVal == 'Website (URL)' ) {
				$j("#url").val('');
				urlVal = "";
			}
			//document.commentform.submit();
			$('#commentform').submit();
		}
		
		return false;
	});
}

function zoomIcon() {
	$j(".zoom").parent().hover(function(e) {
		$j(this).css({"position" : "relative"});
		if ($j(this).find('.zoom_ico').length == 0) {
			$j(this).append('<div class="zoom_ico"></div>');
			var zx = $j(this).find(".zoom").outerWidth()/2 - $j(this).find(".zoom_ico").outerWidth()/2;
			if ($j(this).hasClass("ss_thumb")) {
				var zy = $j(this).find(".zoom").outerHeight()/2 - $j(this).find(".zoom_ico").outerHeight()/2;
			} else {
				var zy = $j(this).find(".zoom").outerHeight()/2;
			}
			$j(this).find(".zoom_ico").css({
				"opacity" : 0,
				"top"  : zy,
				"left" : zx
			});
		}
		$j(this).find(".zoom_ico").stop().animate({opacity: 1}, 400);
	},
    // Roll Out
    function(e) {
		$j(this).find(".zoom_ico").stop().animate({opacity: 0}, 400);
	});
}

function validateContactForm() {
	$j(".contactform").submit(function(){
		$j(".error").removeClass("error");
		
		var randval = Math.random();
		var hasError = false;
		var sub_form = $j(this);
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		$j(this).find("input:text, textarea").each(function(index){
			var input = $j(this);
			var val = input.val();
			if (($j(this).hasClass("required") && val == "") || val == "Message" || val == "Name" || val == "Email") {
				hasError = true;
				$j(this).addClass("error");
			}
			if ($j(this).hasClass("email_input") && $j(this).hasClass("required") && !emailReg.test(val)) {
				hasError = true;
				$j(this).addClass("error");
			}
		});
		
		var mailpath = templatepath+'sendemail.php';
		var str = $j(this).serialize() +"&randval="+randval;
		if (hasError == false) {
			$j(".form_answer").remove();
			$j.ajax({
				 type: "POST",
				 url: mailpath,
				 data: str,
				 success: function(response)
				 {
					if (response == "ok") {
						sub_form.before('<div class="green_message form_answer messagebox">Message has been sent!</div>');
						$j(".form_answer").hide().show("fast");
					} else if (response == "error") {
						sub_form.before('<div class="yellow_message form_answer messagebox">Unknown error please try again later.</div>');
						$j(".form_answer").hide().show("fast");
					}
				 }
			});
		} else {
			if ($j(".form_answer").length == 0) {
				sub_form.before('<div class="blue_message form_answer messagebox">Please fill in all required fields.</div>');
				$j(".form_answer").hide().show("fast");
			} else if ($j(".form_answer").hasClass("green_message")) {
				$j(".form_answer").remove();
				sub_form.before('<div class="blue_message form_answer messagebox">Please fill in all required fields.</div>');
				$j(".form_answer").hide().show("fast");
			}
		}
		
		return false;
	});
}

var g_tooltipDescription = '';
function tooltipBehaviour() {
	$j(".tooltip, .flickr_badge_image img, .social img").hover(function(e) {
        var offsetX = 0;
        var offsetY = 15;
        var hoveredObject = this;        
  
        $j("body").append('<div id="tooltipDiv"></div>');
        $j("#tooltipDiv").hide();

		g_tooltipDescription = $j(hoveredObject).attr("title");
		if (g_tooltipDescription.length != 0) {
			var hovertitle = $j(hoveredObject).attr('title');
			$j(hoveredObject).removeAttr('title');
			if ($j(hoveredObject).parent().attr("title") == hovertitle) {
				$j(hoveredObject).parent().removeAttr('title');
			}
			
			$j("#tooltipDiv").html(g_tooltipDescription);
		} else {
			return;
		}
		
		g_initHoverX = e.pageX;
		g_initHoverY = e.pageY;
		var browserWidth = $j(window).width();
		var tooltipLeft = e.pageX + offsetX;
		var tooltipTop = e.pageY + offsetY;
		var tooltipWidth = $j("#tooltipDiv").outerWidth(true);
		
		if (tooltipLeft < 0) {
			tooltipLeft = 0;
		}
		if (tooltipLeft + tooltipWidth > browserWidth) {
			tooltipLeft = browserWidth - tooltipWidth;
		}
		
		$j("#tooltipDiv").hide()
			.css({
				 "visibility" : "visible",
				 "height"     : "auto",
				 "width"      : "auto",
				 "top"        : tooltipTop + "px",
				 "left"       : tooltipLeft + "px"
			}).show();
		
		var currentTooltipWidth = $j("#tooltipDiv").outerWidth(true);
		if (currentTooltipWidth > 300) {
			$j("#tooltipDiv").css({"width" : "300px"});
		}
		
		$j("#tooltipDiv")
			.css({
				"margin"  : "0px",
				"opacity" : "0"})
			.animate({opacity: 1}, 500);
    },
    // Roll Out
    function() {
		if (g_tooltipDescription.length != 0) {
			var titleAtr = $j("#tooltipDiv").html();
			$j(this).attr({"title": titleAtr});
			g_tooltipDescription = '';
		}
		
        $j("#tooltipDiv").stop().remove(); 
		g_topPositionAdjusted = false;
    });    
    
    $j(".tooltip, .flickr_badge_image img, .social img").mousemove(function(e) {
		var offsetX = 0;
		var offsetY = 15;
		
		var browserWidth = $j(window).width();
		var tooltipLeft = e.pageX + offsetX;
		var tooltipTop = e.pageY + offsetY;
		var tooltipWidth = $j("#tooltipDiv").outerWidth(true);

		if (tooltipLeft < 0) {
			tooltipLeft = 0;
		}
		if (tooltipLeft + tooltipWidth > browserWidth) {
			tooltipLeft = browserWidth - tooltipWidth;
		}

		$j("#tooltipDiv")
			.css({
				"top"  : tooltipTop + "px",
				"left" : tooltipLeft + "px"
			});

	});
} // end of function tooltipBehaviour

var tabSpeed = 500;
function tabsBehaviour() {
	var tabs_count = 0;
	$j(".tabs").each(function(){
		$j(this).children().addClass("tab");
		$j(this)
		.attr("id", "tabs_"+tabs_count)
		.before('<div class="tabs_nav tabs_nav_'+tabs_count+'">')
		.cycle({
			fx: 'fade',
			timeout: 0,
			speed: tabSpeed,
			containerResize: 1,
			before:  tabsOnBefore, 
			pager:  '.tabs_nav_'+tabs_count
		});
		var tab = $j(this);
		$j(".tabs_nav_"+tabs_count+" a").each(function(){
			var currentTabIndex = $j(this).prevAll().length;
			var tabTitle = $j("#tabs_"+tabs_count+" span.tabTitle").eq(currentTabIndex).html();
			$j(this).html(tabTitle);
		});
		var tabtitle = $j(this).find("span").html();
		tabs_count++;
	});
	
	function tabsOnBefore(currSlideElement, nextSlideElement, options) {
		var tabHeight = $j(nextSlideElement).outerHeight();
		$j(nextSlideElement).parent().animate({"height": tabHeight+"px"}, tabSpeed);
	}
}

function toggleBehaviour() {
	$j('.toggle').click(function () {
		$j(this).stop().toggleClass("toggle_close").next('div.toggle_content').toggle(500);
	});
}

function slideshowBehaviour() {
	var ss_count = 0;
	$j(".small_slideshow").each(function(){
		$j(this)
		.wrap('<div class="relative clear_both"></div>')
		.before('<div class="ss_nav ss_nav_'+ss_count+'">')
		.cycle({
			fx: "fade", // choose your transition type, ex: fade, scrollUp, shuffle, etc...
			pause: 1,
			speed: slideShowSpeed,
			timeout: slideShowTimeout,
			delay: -ss_count * 1000,
			before: slideshowOnBefore,
			after:slideshowOnAfter,
//			easing: slideTransitionEffect,
			pager:  '.ss_nav_'+ss_count
		})
		.find('.description').width($j(this).width() - 20);
		ss_count++;
	});
	
	function slideshowOnBefore(currSlideElement, nextSlideElement, options) {
		$j(nextSlideElement).find("div.description").animate({"opacity": 0}, 0);
	}
	
	function slideshowOnAfter(currSlideElement, nextSlideElement, options) {
		$j(nextSlideElement).find("div.description").animate({"opacity": 1}, 2000);
	}
} // end of function slideshowBehaviour

jQuery(document).ready(function($j) {
	menu();
	window_resize();
	resizeSlideShow();
	big_image_hover();
	add_to_favorites();
	show_twitter();
	show_thumbs();
	showComments();
	show_tags();
	imagePreviewBehaviour();
	inputBehaviour();
	prettyPhotos();
	commentsValidateBehaviour();
	zoomIcon();
	validateContactForm();
	
	tooltipBehaviour();
	tabsBehaviour();
	toggleBehaviour();
	slideshowBehaviour();
	
	$j('.imgfade').hide();
	
	$j(".imgpreview, .prettyPhoto, a[rel^='prettyPhoto']").each(function(){
		var img = $j(this).find("img").length;
		if (img == 0) {
			$j(this).addClass("imgTexLinkPreview");
		}
	});
	$j("#menu a").removeAttr('title');
	
	$j(window).resize(function() {
		window_resize();
		resizeSlideShow();
	});
	$j(window).load(function() {
		menu();
		/*$j("#menu")
		.stop()
		.animate({
			"opacity" : 1
			} , 1000
		);*/
		loadTweets();
	});
	
	$j.fn.superbgimage.options = {
		id: 'superbgimage', // id for the containter
		z_index: 0, // z-index for the container
		inlineMode: 1, // 0-resize to browser size, 1-do not resize to browser-size
		showimage: 1, // number of first image to display
		vertical_center: 1, // 0-align top, 1-center vertical
		transition: 1, // 0-none, 1-fade, 2-slide down, 3-slide left, 4-slide top, 5-slide right, 6-blind horizontal, 7-blind vertical, 90-slide right/left, 91-slide top/down
		transitionout: 0, // 0-no transition for previous image, 1-transition for previous image
		randomtransition: 0, // 0-none, 1-use random transition (0-7)
		showtitle: 0, // 0-none, 1-show title
		slideshow: 1, // 0-none, 1-autostart slideshow
		slide_interval: gal_interval*1000, // interval for the slideshow
		randomimage: 0, // 0-none, 1-random image
		speed: 'slow', // animation speed
		preload: 1, // 0-none, 1-preload images
		onClick: superbgimage_click, // function-callback click image
		onMouseenter: superbgimage_mouseenter, // function-callback mouseenter
		onMouseleave: superbgimage_mouseleave, // function-callback mouseleave
		onMousemove: superbgimage_mousemove, // function-callback mousemove
		onShow: swap_i_btn // function-callback show image
		//onHide: superbgimage_hide, // function-callback hide image*/
	};
	$j('#thumbs').superbgimage();
	
	$j('a.next_button').click(function() {
		return $j('#thumbs').nextSlide();
	});
	$j('a.prev_button').click(function() {
		return $j('#thumbs').prevSlide();
	});
	
	var my_slideshowActive = true;

	function superbgimage_click(img) {
		$j('#thumbs').nextSlide();
	} 
	function superbgimage_mouseenter(img) {
		if ($j.superbg_slideshowActive) {
			my_slideshowActive = true;
			if ($j('#pause').length == 0) {
				$j('body').prepend('<div id="pause"><\/div>');
			}
			$j('#pause').css('position', 'absolute').css('z-index', 3).show();
			return $j('#thumbs').stopSlideShow();
		} 
	}
	function superbgimage_mouseleave(img) {
		if (my_slideshowActive && ($j('#pause').length > 0) && ($j('#pause').css('display') == 'block')) {
			$j('#pause').hide();
			return $j('#thumbs').startSlideShow();
		}
	}
	// function callback onmousemove, show and move pause-indicator
	function superbgimage_mousemove(img, e) {
		if (my_slideshowActive && ($j('#pause').length > 0)) {
			$j("#pause").css("top",(e.pageY + 0) + "px").css("left",(e.pageX + 25) + "px").show();
		}
	}
	function swap_i_btn(current_pic) {
		var th_index = current_pic - 1;
		var new_href = $j("#thumbs a").eq(th_index).attr("id");
		//console.log(new_href);
		$j(".comment_button").attr({"href": new_href});
	}
	
	if (blockrc == 'true') {
		$j(this).bind("contextmenu", function(e) {
			e.preventDefault();
		});
	}
});