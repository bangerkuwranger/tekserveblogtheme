/******
	Maintain Proper Height / Alignment for Blog archive page (window resize called conditionally in main JS resize function)
******/

function blogHero() {

	var textheight = 0;
	var imgheight = jQuery('.hero .heroimage').height();
	jQuery('.hero .valigncenter > *').each( function() {
		textheight += jQuery(this).height();
	});
	if (textheight > imgheight) {
	
		jQuery('.hero .vc_column_container').css('maxHeight', textheight); 
	
	}
	else {
	
		jQuery('.hero .vc_column_container').css('maxHeight', imgheight);
	
	}	//end if (textheight > imgheight)
	if (jQuery('.hero .valigncenter').height() > imgheight) {
	
		jQuery('.hero .valigncenter').css('display', 'inline');
	
	}
	else {
	
		jQuery('.hero .valigncenter').css('display', 'block');
	
	}	//end if (jQuery('.hero .valigncenter').height() > imgheight)
	var textheight = 0;
	jQuery('.hero .valigncenter > *').each( function() {
		textheight += jQuery(this).height();
	});

}	//end blogHero()

/******
	Initialize Infinite Scroll for Blog archive page
******/

function initInfiniteScroll() {

	var infinite_scroll = {
	
		loading: {
			img: themeInfo.cssurl + "/images/ajax-loader.gif",
			msgText: "<h3>Loading the next set of posts...</h3>",
			finishedMsg: "<h3>All posts loaded.</h3>"
		},
		"nextSelector":".navigation .nav-previous a",
		"navSelector":".navigation",
		"itemSelector":".article-archive",
		"contentSelector":".content",
		"animate":true,
	
	};
	jQuery( infinite_scroll.contentSelector ).infinitescroll( infinite_scroll );

}	//end initInfiniteScroll

/******
	call on page fully loaded
******/

jQuery(window).bind('load', function() {

	if (themeInfo.resultCount && themeInfo.resultCount > 12) {
	
		jQuery('body').addClass('infinite-scroll');
	
	}	//end if (themeInfo.resultCount && themeInfo.resultCount > 12)
	blogHero();
	initInfiniteScroll();

});