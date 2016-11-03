/* Middlebury JS effects */
/* by White Whale Web Services */

// var jq14 = $.noConflict(true); // return control of $ and jQuery variables back to Drupal's jQuery version
var jq14 = $; // Keep using jquery 1.4 for this non-drupal App. Added by Adam 2010-08-05

var ie6 = jq14.browser.msie&&jq14.browser.version<7, // are we in IE6?
	ie8 = jq14.browser.msie&&jq14.browser.version>=8&&jq14.browser.version<9; // are we in IE8?

jq14.easing.def = 'easeInOutQuad'; // set the default easing

jq14(function($) { // on DOM ready
	$('#search_query').inlineLabel();
	// Tabs
	$('#tabs li a').click(function() {
		$(this).parent().addClass('active').siblings().removeClass('active');
		$($(this).attr('href')).show().siblings('.tab').hide();
		return false;
	}).eq(0).click(); // and click the first one
	// Links with images
	$('#content a:has(img),#carousel a:has(img)').addClass('noborder');
	// Dropdowns
	var dropdowns = $('.dropdown');
	dropdowns.hover(function() {
		var self = $(this);
		dropdowns.removeClass('active');
		self.addClass('active');
		clearTimeout(self.data('timer'));
	},function() {
		var self = $(this);
		self.data('timer',setTimeout(function() {
			self.removeClass('active')
		},500));
	});

	// Quickaccess (on gateway pages)
	if($('.quickaccess').length) { // if quickaccess and quickaccess.js are present
		$.ajaxSetup({cache:true});
		$.getScript('/scripts/quickaccess.js',function() {
			$('input.quickaccess').quickaccess({selector:'.qa_links a',sort:true,maxResults:10});
		});
	}
	// Footer positioning and panel
	var footerPanel = $('#footer_panel');
	$(window).resize(nudgeFooter).resize(); // attach nudge footer and do it now
	$('#footer .quick_footer>a').click(function() {
		var windowHeight = $(window).height(),
			li = $(this).parent();
		if(!footerPanel.is(':visible')) {
			li.addClass('active');
			var fromTop = $(this).offset().top;
			footerPanel.slideDown(1000);
			$('html,body').animate({scrollTop:(fromTop+330-windowHeight)+'px'},1100);
		} else {
			if(li.is('.active')) {
				footerPanel.slideUp(1000,function() {
					$('#footer .quick_footer').removeClass('active');
				});
				$('html,body').animate({scrollTop:($('body').height()-windowHeight-300)+'px'},900);
			} else {
				$('#footer .quick_footer').removeClass('active');
				li.addClass('active');
			}
		}
		return false;
	});
	initVideos();
});

function nudgeFooter() {
	var $ = jq14,
		footer = $('#footer').css('visibility','visible');
		windowHeight = $(window).height(), // get the window height
		bodyHeight = footer.css('margin-top',30).offset().top+30; // the bottom of the footer is the bottom of the body; ignore the footer panel
	if(bodyHeight<windowHeight) {
		footer.css('margin-top',Math.max(30,windowHeight-bodyHeight+30)+'px');
	}
}

function initVideos() {
	var $ = jq14,
		videos = $('.open_video');
	if(videos.length) { // if there are videos to embed on this page
		var attachClick = function() {
			if($.browser.msie) videos.css({backgroundImage:'url(/styles/ie/blank.gif)',height:500});
			videos.click(function(e) { // attach a click event
				if(ie6) return true;
				e.preventDefault();  // cancel the original click
				var blackout = $('<div class="blackout"/>').css('opacity',0.7).prependTo('body'),
					overlay = $('<div class="video_overlay"><div class="close_overlay">×</div><div id="youtube_embed">Watching this video requires Flash Player 8 or higher.</div></div>').prependTo('body'),
					url = $(this).attr('href'),
					swf = (url.indexOf('/v/')>-1 ? url : url.replace(/[^?].*?v=([^&]*)/i,'http://www.youtube.com/v/$1&hl=en&fs=1'))+'&enablejsapi=1&hd=1'; // parse out the linked SWF, force-enabling the JS api and the HD quality
					overlay.css('top',($('html').scrollTop()+100)+'px');
				swfobject.embedSWF(swf, // embed the linked SWF
					'youtube_embed', // replacing the item with this ID
					'560', '320', // width&height
					'8', null, null,
					{allowScriptAccess:'always',wmode:'transparent'}, // needed for JS api
					{id:'youtube_embed'}
				);
				blackout.add('.close_overlay').click(function() { // clicking the blackout or close button
					blackout.add(overlay).remove(); // closes the overlay
				});
			});
		}
		if(!window.swfobject) $.getScript('http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js',function() { attachClick(); }); // grab swfObject, then attachClick
		else attachClick();
	}
}

jq14.fn.extend({ // add jQuery plugins
	inlineLabel: function(style,text) { // places labels inside of text inputs
		var $ = jq14;
		if(typeof style !='string') { // if no style is specified (including empty strings)
			style ='inline_label'; // the default CSS class for placeholder text
		}
		text = text || $('label[for='+this.attr('id')+']').hide().text(); // if text is not specified, use the label text
		var self = this,
			blur = function() { // a blur function that doesn't fire the browser blur event
				var val = $.trim(self.val());
				if(!val||val==text) { // if this input has no contents or the contents are identical to the placeholder text
					self.addClass(style) // add the inline_label class
						.val(text) // set the appropriate text
						.one('focus',function() { // and, on the first focus
							self.val('') // remove that text
								.removeClass(style); // and the inline_label class
						});
				}
			};
		self.blur(blur); // on blur, fire the blur function
		blur(); // and fire it now (without firing the browser blur event)
		return this; // return original element for chaining
	}
});
