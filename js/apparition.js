/******
	noconflict declaration & globals
******/

var $j = jQuery;
var clientWidth = document.documentElement.clientWidth;
var $rev_slider;

function detailBoxBinder() {

	//detailbox slide
	$j('.detailBoxTrigger').click(function() {
			var thistitle = $j(this).html();
			var leftheight = $j(this).parents('.detailBox').find('.detailBox-left').height();
			$j(this).parents('.detailBox').find('.detailBox-mobile-title').html(thistitle);
			
			$j(this).parent().addClass('active');
			var $jothertrigs = $j(this).parent().siblings('.active');
			$j($jothertrigs).removeClass('active');
			var targetid = $j(this).attr('id');
			targetid = targetid.replace('_trigger', '');
			var $jtarget = $j('#'+targetid);
			var $jother = $j('#'+targetid).siblings('.active');
			$j(this).parents('.detailBox').find('.detailBox-right').addClass('active');
			if (!$jtarget.hasClass('active')) {
				$jother.each(function(index, self) {
					var $jthis = $j(this);
					$jthis.removeClass('active').animate({
						left: $jthis.width()
					}, 300).hide(50);
				});
				if (leftheight > 300) {
					$jtarget.css('height',leftheight+'px');
					$jtarget.parents('.detailBox-right').css('height',leftheight+'px');
				}
				$jtarget.addClass('active').show().css({
					left: -($jtarget.width())
				}).animate({
					left: 0
				}, 500);
			}
			$j(this).parents('.detailBox').addClass('shifted', 500, 'easeOutBounce');
		});
	
	//detailBox mobile back button
	$j('.detailBox-mobile').click(function() {
		$j(this).parents('.detailBox').removeClass('shifted', 500, 'easeOutBounce');
	});
}
/*
	icaps - alters apple trademarks to correct capitalization. sigh.
*/

$j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function () {
	var my_html = $j(this).html();
	var my_old_html = $j(this).html();

	// Make sure there are no children so we don't edit more than we want.
	if ($j(this).children().length == 0) {
		my_html = my_html.replace(/(iPad|iPhone|iMac|iPod|iOS|Mac OS X|Mac mini|Mac Pro|MacBook Pro|MacBook Air|iCloud|iLife|iWork|iPhoto|iMovie|iCal|iTunes|AppleCare|Apple)/ig, '<span class="trademark">$1</span>');
		my_html = my_html.replace(/(iNSIDER)/ig, '<span class="insider">$1</span>');
		$j(this).html(my_html);
	}
}); //end $j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function ()


/******
	nav menu position set, init hover behavior
******/

function navInit() {

	//store fixed value for menu top position
	$j("#nav").data("top", $j("#nav").offset().top);
	
	//bind class hovermenu to hover status
	$j( ".menu-primary" ).hover(
  		function() {
    		$j( this ).addClass( "hovermenu" );
  		}, function() {
    		$j( this ).removeClass( "hovermenu" );
  		}
  	);
  	
  	//remove class hovermenu on window scroll
  	$j( window ).scroll(function() {
		$j( ".menu-primary" ).removeClass( "hovermenu" );
	});
	
	//toggle class hovermenu on click
	$j( ".menu-primary" ).click(function() {
    		$j( this ).toggleClass( "hovermenu" );
  	});
  	
  	//bind fixDiv() to window scroll; enables fixed topnav
  	$j(window).scroll(fixDiv);

	
} //end navInit()


//fixes nav to top screen as user scrolls down, add offset to content
function fixDiv() { 

    var $jdiv = $j("#nav");
	if ($j(window).scrollTop() > $jdiv.data("top")) { 
		$jdiv.addClass('floating-menu');
	}
	else {
		$jdiv.removeClass('floating-menu');
	} //end if ($j(window).scrollTop() > $jdiv.data("top"))
	
} //end fixDiv()

/******
	call on jQuery loaded
*******/

$j(function() {
	
	//Initialize nav menu functions
	navInit();
		
	if ($j('body').hasClass('page')) {

		//create internal wraps for text color/width of internal content, add fullHeight class to page columns with contrasting backgrounds to row with correct bgcolor wrap
		$j('.vc_row, .wpb_row').each(function() {

			if ( !( $j(this).hasClass('vc_inner') ) ) {

				var bgcolor = $j(this).css('background-color');
				switch (bgcolor) {
			
					case "rgb(0, 77, 114)":
						//darkblue
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap darkblue' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					case "rgb(64, 168, 201)":
						//lightblue
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap lightblue' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					case "rgb(243, 111, 55)":
						//orange
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap orange' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					default:
						//assumed white, catchall at least wraps any other color
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
					
			
				} //end switch(bgcolor)
			
				$j(this).find(".wpb_column").filter(function() {
					return this.className.match(/\bvc_custom/);
				}).each(function() {
					var colbg = $j(this).css('background-color');
					if ( colbg != bgcolor ) {
					
						$j(this).addClass('fullHeight');
					
						switch (colbg) {
			
							case "rgb(0, 77, 114)":
								//darkblue
								$j(this).wrap("<div class='darkblue' />");
								break;
							case "rgb(64, 168, 201)":
								//lightblue
								$j(this).wrap("<div class='lightblue' />");
								break;
							case "rgb(243, 111, 55)":
								//orange
								$j(this).wrap("<div class='orange' />");
								break;
							default:
								//assumed white, catchall at least wraps any other color
								$j(this).wrap("<div class='white' />");
					
						} //end switch(colbg)
					
					} //end if ($j(this).css('background-color') != bgcolor)
				
				}); //end $j(this).find(".wpb_column[class^='vc_custom']").each(function()
				
			} //end if ( !( $j(this).hasClass('vc_inner') ) )
		
		}); //end $j('.vc_row').each( function()
		
		$j('#inner').slideDown(); // show content after assigning classes

	} //end if ( $j('body').hasClass('page') )


	
});

/******
	call on page fully loaded
******/

$j(window).bind('load', function() {

	//bind hover class to special notice if there is a link present
	if ($j('#special-notice a').length > 0 ) {
		$j('#special-notice a').hover(function() {
			$j('#special-notice').toggleClass('hover');
		});
	} //end if ($j('#special-notice a').length > 0 )
	
	if ($j('body').hasClass('page')) {
		//remove empty paragraphs
		$j('p:empty').remove();
		additionalFunctions(); //call external routines
		$rev_slider = $j(".rev_slider");
		if ($rev_slider.length > 0){
			$rev_slider.revprev().revnext(); //reload first slide after revslider to avoid squishing
		} //end if ($rev_slider.length > 0)
		goToAnchor();
		bindAnchors();
		initFooterFolk();
		$j('#pageLoad').slideUp();
	} //end if ( $j('body').hasClass('page') )
	
	rearrangeContent(clientWidth); //pass window width to various functions in width.js on full load
	$j(window).resize(function() {
		clientWidth = document.documentElement.clientWidth;
		rearrangeContent(clientWidth);
	});  //pass window width to various functions in width.js on resize
	
	setEventsBG();

}); //end onload function

/******
	deprecated stuff caught
******/

function scrollToID() {
	goToAnchor();
}

/******
	additional functions defined
******/

function additionalFunctions() {
	
	detailBoxBinder();
	
} //end additionalFunctions()

/******
	anchor handler processes offset for nav that is removed from document flow
******/

//rewrite anchor action needed thanks to crazy image requirements
function goToAnchor() { 
	hash = document.location.hash;
	if (hash !="") {
		setTimeout(function() {
			if (location.hash) {
				window.scrollTo(0, 0);
				$j('#nav').addClass('floating-menu');
				var offset = -( parseInt( $j('#nav').outerHeight(true) ) );
				window.location.href = hash;
				window.scrollBy(0, offset);
			} //end if (location.hash)
		}, 250); //end setTimeout(function()
	}
	else {
		return false;
	} //end if (hash !="")
} //end gotToAnchor()

//same as goToAnchor, but for anchor elements targeting the same page
function bindAnchors() {
	$j("a[href^='#']").addClass('anchor').click(function(event) {
		event.preventDefault();
		var pageHash = $j(this).attr('href');
		setTimeout(function() {
			if (pageHash != "" && pageHash != "#" && pageHash != "#!" && pageHash.indexOf("tab-") === -1 ) {
				window.scrollTo(0, 0);
				$j('#nav').addClass('floating-menu');
				var offset = -( parseInt( $j('#nav').outerHeight(true) ) );
				window.location.href = pageHash;
				window.scrollBy(0, offset);
			} //end if (location.hash)
		}, 250); //end setTimeout(function()
	}); //end .click(function()
} //end bindAnchors()

/******
	noconflict declaration, global vars
******/

var currentWidth;
var prevWidth;
var theSearchBox;
var mV = 500;
var mH = 626;
var tV = 770;
var tH = 1024;
var dR = 1200;
var sT = 945;
$j('body').data('hasRun', false);

/******
	single function to call on width change; delegates to specific breakpoint functions
******/

function rearrangeContent(w) {

	//set current width
	prevWidth = currentWidth;
	currentWidth = w;


	//breakpoint resets... undo as we move back. Only runs after first time called per page.
	if ( $j('body').data('hasRun') ) {
	
		if (w >= tH) {
			untabletH();
			untabletV();
			unmobileH();
			unmobileV();
		}
		else if (w >= tV) {
			untabletV();
			unmobileH();
			unmobileV();
		}
		else if (w >= mH) {
			unmobileH();
			unmobileV();
		}
		else if (w >= mV) {
			unmobileV();
		} //end if(w>...)
	} 
	else {
		prevWidth = 1200;
	} //end if($j('body').data('hasRun'))
	
	
	//breakpoints for devices... typically specific content moves
	if (w < mV) {
		mobileV();
		mobileH();
		tabletV();
		tabletH();
	}
	else if (w < mH) {
		mobileH();
		tabletV();
		tabletH();
	}
	else if (w < tV) {
		tabletV();
		tabletH();
	}
	else if (w < tH) {
		tabletH();
	}
	else {
		desktopR();
	} //end if(w<...)
	
	
	
	//breakpoint for grid stacking... typically for weird hacks. *shudder*
	if (w < sT) {
		stackEm();
	}
	else {
		unstackEm();
	}
	
	
	//set flag after first run to run reset functions
	$j('body').data('hasRun', true);
	
} //end rearrangeContent(w)

/******
	delegate functions for specific breakpoints
******/
		
function mobileV() {
	if (prevWidth >= mV) {
	
	} //end if(prevWidth >= mV)
} //end mobileV()

function unmobileV() {
	if (prevWidth < mV) {
		
	} //endif(prevWidth < mV) {
} //end unmobileV()


function mobileH() {
	if (prevWidth >= mH) {
	
		moveSearchMobile();
		shutMobileDrawers();
		
	} //end if(prevWidth >= mH) {
} //end mobileH()

function unmobileH() {
	if (prevWidth < mH) {
	
		moveSearchBack();
		
	} //end if(prevWidth < mH) {
} //end unmobileH()


function tabletV() {
	if (prevWidth >= tV) {
	
		swapHeaderImgs();
		
	} //end if(prevWidth >= tV)
} //end tabletV()

function untabletV() {
	if (prevWidth < tV) {
	
		swapHeaderImgs();
		
	} //endif(prevWidth < tV) {
} //end untabletV()


function tabletH() {
	if (prevWidth >= tH) {
	
		mobileMeta();
		
	} //end if(prevWidth >= tH)
} //end tabletH()

function untabletH() {
	if (prevWidth < tH) {
		
		remMobileMeta();
		
	} //endif(prevWidth < tH) {
} //end untabletH()


function desktopR() {

} //end desktopR()


function stackEm() {

	stackFullHeight();

} //end stackEm()


function unstackEm() {

	changeFullHeight();

} //end unstackEm()

/******
	functions for specific actions, called by breakpoint functions
******/

//moves search outside of nav node; called on page load and window resize
function moveSearchMobile() {  

		$j('.right.search').insertBefore('#inner .wrap').wrap("<div class='innerRowWrap' />");
		
} //end moveSearchMobile()

//moves search back into nav node; called on window resize
function moveSearchBack() {

	$j('.right.search').unwrap().insertAfter('#nav div ul li:last-child');
	
} //end moveSearchBack()

//swaps programmatically added mobile header image url with existing header image url.
//only runs if plugin tekserve-shared-data has created it. Requires a page width.
function swapHeaderImgs() {

	if ($j('#tekserve-shared-data-hours-swap').length != 0) {
	
		var $header = $j('#wrap #header .wrap #title-area');
		var firstImg = $header.css('backgroundImage');
		var $swap = $j('#tekserve-shared-data-hours-swap');
		var secondImg = $swap.html();
		if (clientWidth > tV && $header.hasClass('mobile')) {
		
			$header.css('backgroundImage', secondImg).removeClass('mobile');
			$swap.html(firstImg);
			
		} //end if (clientWidth > tV && $header.hasClass('mobile'))
		if (clientWidth <= tV && !($header.hasClass('mobile'))) {
		
			$header.css('backgroundImage', secondImg).addClass('mobile');
			$swap.html(firstImg);
			
		} //end if (clientWidth <= tV && !($header.hasClass('mobile')))
		
	} //end if($j('#tekserve-shared-data-hours-swap').length != 0)
	
} //end swapHeaderImgs()

//keep drawers closed on first load if mobile
function shutMobileDrawers() {

	if ( !($j('body').data('hasRun')) ) {
		$j('.collapseomatic').removeClass('colomat-close'); //call on load if window size < 640
		
		$j('.collapseomatic_content').css('display', 'none');
		
	} //end if ( !($j('body').data('hasRun')) )
	
} //end shutMobileDrawers()

//add web-app meta to head
function mobileMeta() {

	$j('head').append('<meta id="apple-mobile-web-app-capable" name="apple-mobile-web-app-capable" content="yes">');
	
	$j('head').append('<meta id="apple-mobile-web-app-status-bar-style" name="apple-mobile-web-app-status-bar-style" content="black">');
	
} //end mobileMeta()

//remove web-app meta from head
function remMobileMeta() {

	$j('#apple-mobile-web-app-capable, #apple-mobile-web-app-status-bar-style').remove();
	
} //end remMobileMeta()

//adjust height of unstacked .fullHeight elements to match that of parent .vc_row
function changeFullHeight() {

	$j('.fullHeight').each(function() {
	
		var rowHeight = $j(this).parents('.vc_row').css('height');
		$j(this).css('height', rowHeight);
	
	}); //end $j('.fullHeight').each(function()

} //end changeFullHeight()

//set .fullHeight heights to auto when stacked
function stackFullHeight() {

	$j('.fullHeight').css('height', 'auto');

} //end stackFullHeight()

/******
	events background
******/

function setEventsBG() {
	if( $j('body.events-single #wrap div#inner, body.events-archive #wrap div#inner').length > 0 ) {
		var eventsBG = 'url(' + themeInfo["cssurl"] + '/random-image/rotate.php)';
		$j('body.events-single #wrap div#inner, body.events-archive #wrap div#inner').css('backgroundImage', eventsBG);
	}
}