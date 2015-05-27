/****
	noconflict declaration & globals
****/

var $j = jQuery;
var clientWidth = document.documentElement.clientWidth;
var $rev_slider;
var tocLink = '<a href="#toc_container" class="toclink" title="Go to table of contents"><i class="fa fa-caret-square-o-up"></i> TABLE OF CONTENTS</a>';
var linkwithin_text='Related Articles';
var hasSidebar = false;



/******
	call on jQuery loaded
*******/

$j(function() {
	
	//Initialize nav menu functions
	navInit();
	
	//weird header / ol formatting
	if ($j('body').hasClass('single-post')) {
	
		$j('h2').parent('li').addClass('htwoList');
		$j('h3').parent('li').addClass('hthreeList');
		$j('h4').parent('li').addClass('hfourList');
		//remove that from widgets
		$j('h2').parent('li.widget').removeClass('htwoList');
	
	}	//end if ($j('body').hasClass('single-post'))
	
	//rearrange content for sidebar, if needed
	if ($j('body').hasClass('content-sidebar')) {
	
		hasSidebar = true;
	
	}	//end if ($j('body').hasClass('content-sidebar'))
	
	moveForSidebar(hasSidebar);

	//create internal wraps for text color/width of internal content, add fullHeight class to page columns with contrasting backgrounds to row with correct bgcolor wrap
	$j('.vc_row, .wpb_row').each(function() {

		if ( !( $j(this).hasClass('vc_inner') ) ) {

			var bgcolor = $j(this).css('background-color');
			var $cols, $wrap;
			switch (bgcolor) {
		
				case "rgb(0, 77, 114)":
					//darkblue
					$cols = $j(this).contents().detach();
					$j(this).prepend("<div class='innerRowWrap darkblue' />");
					$wrap = $j(this).children('.innerRowWrap');
					$cols.prependTo($wrap);
					break;
					
				case "rgb(64, 168, 201)":
					//lightblue
					$cols = $j(this).contents().detach();
					$j(this).prepend("<div class='innerRowWrap lightblue' />");
					$wrap = $j(this).children('.innerRowWrap');
					$cols.prependTo($wrap);
					break;
					
				case "rgb(243, 111, 55)":
					//orange
					$cols = $j(this).contents().detach();
					$j(this).prepend("<div class='innerRowWrap orange' />");
					$wrap = $j(this).children('.innerRowWrap');
					$cols.prependTo($wrap);
					break;
					
				default:
					//assumed white, catchall wraps any other color without color class
					$cols = $j(this).contents().detach();
					$j(this).prepend("<div class='innerRowWrap' />");
					$wrap = $j(this).children('.innerRowWrap');
					$cols.prependTo($wrap);
		
			}	//end switch(bgcolor)
			
			//if column is different bg than row, make sure it is within correct class container
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
							//assumed white, catchall wraps any other color without color class
							$j(this).wrap("<div class='white' />");
				
					}	//end switch(colbg)
				
				}	//end if ($j(this).css('background-color') != bgcolor)
			
			});	//end $j(this).find(".wpb_column[class^='vc_custom']").each(function()
			
		}	//end if ( !( $j(this).hasClass('vc_inner') ) )
	
	});	//end $j('.vc_row').each( function()
	
	$j('.site-inner').slideDown(); // show content after assigning classes

});	//end $j(function()



/*****
	call on page fully loaded
*****/

$j(window).bind('load', function() {

	additionalFunctions(); //call external routines

	rearrangeContent(clientWidth); //pass window width to various functions in width.js on full load
	
	$j(window).resize(function() {
	
		clientWidth = document.documentElement.clientWidth;
		rearrangeContent(clientWidth);
		if (typeof blogHero == 'function') {
			clearTimeout(blogHeroTimeout);
			var blogHeroTimeout = setTimeout(function() { blogHero(); }, 150);
		}
	
	});  //end $j(window).resize(function() - pass window width to various functions in width.js on resize
	
	//run fixdiv once to insure correct behavior on load
	fixDiv();

});	//end onload function



/****
	deprecated stuff caught
****/

function scrollToID() {
	goToAnchor();
}



/****
	additional functions defined - run after full page load, before rearrangeContent/fixDiv
****/

function additionalFunctions() {
	
	//bind hover class to special notice if there is a link present
	if ($j('#special-notice a').length > 0 ) {
	
		$j('#special-notice a').hover(function() {
		
			$j('#special-notice').toggleClass('hover');
		
		});	//end $j('#special-notice a').hover(function()
	
	}	//end if ($j('#special-notice a').length > 0 )
	detailBoxBinder();
	$rev_slider = $j(".rev_slider");
	if ($rev_slider.length > 0){
	
		$rev_slider.revprev().revnext(); //reload first slide after revslider to avoid squishing
	
	}	//end if ($rev_slider.length > 0)
	goToAnchor();
	bindAnchors();
	initFooterFolk();
	$j('#pageLoad').slideUp();
	initTekNavSubmenu();
	initTitleOnAjaxFormSubmit();
	//set toc links
	initFaqToc();
	
} //end additionalFunctions()





/****
	global vars for responsive behavior
****/

var currentWidth;
var prevWidth;
var theSearchBox;
var mV = 500;
var mH = 640;
var tV = 768;
var tH = 1024;
var dR = 1200;
var sT = 945;
$j('body').data('hasRun', false);



/****
	single function to call on width change; delegates to specific breakpoint functions
****/

function rearrangeContent(w) {

	//set current width
	prevWidth = currentWidth;
	currentWidth = w;


	//breakpoint resets... undo as we move toward smaller widths. Only runs after first time called per page.
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
		
		}	//end if(w>...)
	
	} 
	else {
	
		//set prevWidth value to 1200 on first run, sets initial value and verifies that rearrangeContent has run
		prevWidth = 1200;
	
	}	//end if($j('body').data('hasRun'))
	
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
	
	}	//end if(w<...)
	
	//breakpoint for grid stacking... typically for weird hacks to default theme breakpoint behavior. *shudder*
	if (w < sT) {
	
		stackEm();
	
	}
	else {
	
		unstackEm();
	
	}	//end if (w < sT)
	
	//set flag after first run to run reset functions
	$j('body').data('hasRun', true);
	
} //end rearrangeContent(w)



/****
	delegate functions for specific breakpoints
****/
		
function mobileV() {

	if (prevWidth >= mV) {
	
	
	
	}	//end if(prevWidth >= mV)

} //end mobileV()


function unmobileV() {

	if (prevWidth < mV) {
	
		
	
	}	//endif(prevWidth < mV)

}	//end unmobileV()


function mobileH() {

	if (prevWidth >= mH) {
	
		shutMobileDrawers();
		
	}	//end if(prevWidth >= mH)

}	//end mobileH()



function unmobileH() {

	if (prevWidth < mH) {
	
		
	
	} 	//end if(prevWidth < mH)

}	//end unmobileH()


function tabletV() {

	if (prevWidth >= tV) {
	
		swapHeaderImgs();
		setMobile();
		
	}	//end if(prevWidth >= tV)

}	//end tabletV()


function untabletV() {

	if (prevWidth < tV) {
	
		swapHeaderImgs();
		setDesktop();
		
	}	//endif(prevWidth < tV) {

}	//end untabletV()


function tabletH() {

	if (prevWidth >= tH) {
	
		mobileMeta();
		
	}	//end if(prevWidth >= tH)

}	//end tabletH()


function untabletH() {

	if (prevWidth < tH) {
		
		remMobileMeta();
		
	}	//endif(prevWidth < tH) {

}	//end untabletH()


function desktopR() {

	

}	//end desktopR()


function stackEm() {

	stackFullHeight();

}	//end stackEm()


function unstackEm() {

	changeFullHeight();

} //end unstackEm()



/****
	functions for specific actions, called by breakpoint functions
****/

//sets mobile  classes
function setMobile() {  

	$j('.menu-primary').addClass('menu-mobile');
	$j('.tekserve-top-widget .searchlink').addClass('mobile');
		
}	//end setMobile()()


//removes mobile classes
function setDesktop() {

	$j('.menu-primary').removeClass('menu-mobile');
	$j('.tekserve-top-widget .searchlink').removeClass('mobile');
	
}	//end setDesktop()


//swaps programmatically added mobile header image url with existing header image url.
//only runs if plugin tekserve-shared-data has created it. Requires a page width.
function swapHeaderImgs() {

	if ($j('#tekserve-shared-data-hours-swap').length != 0) {
	
		var $header = $j('.site-container .site-header .wrap .title-area');
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

	if (!($j('body').data('hasRun'))) {
	
		$j('.collapseomatic').removeClass('colomat-close'); //call on load if window size < 640
		
		$j('.collapseomatic_content').css('display', 'none');
		
	} 	//end if ( !($j('body').data('hasRun')) )
	
}	//end shutMobileDrawers()


//add web-app meta to head
function mobileMeta() {

	$j('head').append('<meta id="apple-mobile-web-app-capable" name="apple-mobile-web-app-capable" content="yes">');
	$j('head').append('<meta id="apple-mobile-web-app-status-bar-style" name="apple-mobile-web-app-status-bar-style" content="black">');
	
}	//end mobileMeta()


//remove web-app meta from head
function remMobileMeta() {

	$j('#apple-mobile-web-app-capable, #apple-mobile-web-app-status-bar-style').remove();
	
}	//end remMobileMeta()


//adjust height of unstacked .fullHeight elements to match that of parent .vc_row
function changeFullHeight() {

	$j('.fullHeight').each(function() {
	
		var rowHeight = $j(this).parents('.vc_row').css('height');
		$j(this).css('height', rowHeight);
	
	});	//end $j('.fullHeight').each(function()

}	//end changeFullHeight()


//set .fullHeight heights to auto when stacked
function stackFullHeight() {

	$j('.fullHeight').css('height', 'auto');

} //end stackFullHeight()



/******
	TekNav Submenu
******/

function initTekNavSubmenu() {

	$j('.nav-primary .current-page-ancestor.menu-item-has-children .sub-menu, .nav-primary .current-menu-item.menu-item-has-children .sub-menu').animate( {'left': 0, 'opacity': 1}, 250 );
	if( $j('.nav-primary .current-page-ancestor.menu-item-has-children .sub-menu, .nav-primary .current-menu-item.menu-item-has-children .sub-menu').length ) {
	
		$j('.nav-primary .current-page-ancestor.menu-item-has-children, .nav-primary .current-menu-item.menu-item-has-children').addClass('current-submenu');
	
	}
	else {
	
		$j('.nav-primary .menu-primary > .menu-item:first-child').addClass('current-submenu');
		getSubmenu('.nav-primary .menu-primary > .menu-item:first-child');
		
	}	//end if( $j('.nav-primary .current-page-ancestor.menu-item-has-children .sub-menu, .nav-primary .current-menu-item.menu-item-has-children .sub-menu').length )
	$j('.nav-primary .menu-item-has-children').click( function(e) {
	
		if (!$j(this).hasClass('current-submenu')) {
		
			e.preventDefault();
			getSubmenu(this);
		
		}	//end if (!$j(this).hasClass('current-submenu'))
	
	});	//end $j('.nav-primary .menu-item-has-children').click( function(e)
	
}	//end function initTekNavSubmenu()

function getSubmenu(newMenu) {
	
	$j('.current-submenu .sub-menu').animate( {'left': '9999px', 'opacity': 0}, 250 );
	setTimeout( function() { 
	
		$j('.current-submenu .sub-menu').css('left', '-9999px');
		$j('.current-submenu').removeClass('current-submenu');
		$j(newMenu).addClass('current-submenu');
		$j('.current-submenu .sub-menu').animate( {'left': 0, 'opacity': 1}, 250 );
	
	}, 260 );	//end setTimeout( function()
	
}	//end function getSubmenu($newMenu)



/******
	Create Placeholders with form titles
******/

function initTitleOnAjaxFormSubmit() {

	labelsToPlaceholders();

	$j('.gform_wrapper input.button, .form-submit input').click( function() {

		setTimeout( function(){ labelsToPlaceholders(); }, 1000 );

	});	//end $j('.gform_wrapper input.button').click( function()

}	//end function initTitleOnAjaxFormSubmit()


function labelsToPlaceholders() {
	
	var $gformLabels = $j('.gfield');
	var $cformLabels = $j('#respond form p.comment-field');
	if ( $gformLabels.length > 0 ) {

		$gformLabels.each( function() {
			
			//don't replace previously defined placeholder
			if( $j(this).find('input, textarea').attr('placeholder') == undefined ) {
			
				var labeltxt = $j(this).find('label').text();
				labeltxt = labeltxt.replace('*', '');
				$j(this).find('input, textarea').attr('placeholder', labeltxt).attr('title', labeltxt);
			
			}	//end if( $j(this).find('input, textarea').attr('placeholder') == undefined )
	
		}); //end $gformLabels.each( function()

	} //end if ( $gformLabels.length > 0 )
	
	if ( $cformLabels.length > 0 ) {
	
		$cformLabels.each( function() {
		
			var labeltxt = $j(this).find('label').text();
			labeltxt = labeltxt.replace('*', '');
			$j(this).find('input, textarea').attr('placeholder', labeltxt).attr('title', labeltxt);
		
		});	//$cformLabels.each( function()
	
	}	//end if ( $cformLabels.length > 0 )

} //end function labelsToPlaceholders()



/******
	Determine whether element is visible on page
******/

function isElementVisible(el) {

    var rect     = el.getBoundingClientRect(),
        vWidth   = window.innerWidth || doc.documentElement.clientWidth,
        vHeight  = window.innerHeight || doc.documentElement.clientHeight,
        efp      = function (x, y) { return document.elementFromPoint(x, y); };     

    // Return false if it's not in the viewport
    if (rect.right < 0 || rect.bottom < 0 || rect.left > vWidth || rect.top > vHeight) {
    
        return false;

	}	//end if (rect.right < 0 || rect.bottom < 0 || rect.left > vWidth || rect.top > vHeight)
	
    // Return true if any of its four corners are visible
    return (
    
          el.contains(efp(rect.left,  rect.top))
      ||  el.contains(efp(rect.right, rect.top))
      ||  el.contains(efp(rect.right, rect.bottom))
      ||  el.contains(efp(rect.left,  rect.bottom))
    
    );
    
}	//end isElementVisible(el)



/******
	move images in FAQ headings to toc and add toc class to body
******/

function initFaqToc() {

	if ($j('#toc_container').length > 0) {
		
		$j('body').addClass('has-toc');
		$j('#toc_container>.toc_list>li').each(function() {

			var theAnchor = $j(this).children('a').attr('href');
			console.log(theAnchor);
			var theTarget = $j(theAnchor);
			if (theTarget.children('img').length > 0) {

				var theImage = theTarget.children('img').attr('src');
				theImage = 'url(' + theImage + ')';
				$j(this).css('backgroundImage', theImage);

			}	//end if (theTarget.children('img').length > 0)

		});	//end $j('#toc_container>.toc_list>li').each(function()
		
		//move first paragraph to position under title (wraps around featured image)
		var $firstP = $j('div.entry-content>p:first-child').detach();
		$j('.entry-title').after($firstP);
		$j('.entry-title').after('<hr style="margin-bottom:2em;" />');
		
		//add TOC links before every body H2 except first
		$j('.entry-content h2').before(tocLink);
		$j('.entry-content a.toclink:first-of-type').remove();
		
		//bind toc anchors
		goToAnchor();
		bindAnchors();

	} //end if($j('#toc_container').length > 0)
	
}	//end initFaqToc()



/******
	move sidebar content depending on genesis layout (addthis moves to page left if sidebar)
******/

function moveForSidebar(hasSidebar) {

	var $linkwithin = $j('.content .linkwithin_hook').detach();
	if (hasSidebar) {

		$j('.sidebar-primary').prepend($linkwithin);
		$j(window).bind('load', function() {
		
			var $addthis = $j('.addthis-smartlayers.at4-share-outer-right');
			if ($addthis.length > 0) {
				$addthis.removeClass( 'at4-share-outer-right' ).addClass( 'at4-share-outer-left' );
				$addthis.children('.atss').removeClass( 'atss-right slideInRight' ).addClass( 'atss-left slideInLeft' );
		
			}	//end if ($addthis.length > 0)
		
		});	//end $j(window).bind('load', function()	
	
	}
	else {
	
		$j('.content .post').append($linkwithin);
	
	}	//end if (hasSidebar)
	
}	//end moveForSidebar(hasSidebar)



/****
	init animation for detailBox elements
****/

function detailBoxBinder() {

	if ($j('.detailBox').length > 0) {
		
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
			if (!($jtarget.hasClass('active'))) {
			
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
			
			}	//end if (!($jtarget.hasClass('active')))
			$j(this).parents('.detailBox').addClass('shifted', 500, 'easeOutBounce');
	
		});	//end $j('.detailBoxTrigger').click(function()
	
		//detailBox mobile back button
		$j('.detailBox-mobile').click(function() {
		
			$j(this).parents('.detailBox').removeClass('shifted', 500, 'easeOutBounce');
		
		});	//end $j('.detailBox-mobile').click(function()
	
	}	//end if ($j('.detailBox').length > 0)

}	//end detailBoxBinder()



/****
	icaps - alters apple trademarks to correct capitalization. sigh.
****/

$j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function () {

	var my_html = $j(this).html();

	// Make sure there are no children so we don't edit more than we want.
	if ($j(this).children().length == 0) {
	
		my_html = my_html.replace(/(AirDrop|AirMac|AirPlay|AirPort Express|AirPort Extreme|AirPort Time Capsule|AirTunes|Aperture|Apple|Apple Cinema Display|AppleScript|AppleScript Studio|AppleShare|AppleTalk|Apple TV|AppleWorks|App Nap|Aqua|Back to My Mac|Bonjour|Boot Camp|Claris|ColorSync|Cover Flow|Dashcode|DVD Studio Pro|Exposé|FaceTime|FairPlay|FileVault|Final Cut Pro|Finder|FireWire|GarageBand|iBook|iBooks|iCal|iChat|iDVD|iLife|iMac|iMessage|iMovie|Inkwell|iPad|iPhone|iPhoto|iPod classic|iPod nano|iPod shuffle|iPod Socks|iPod touch|iSight|iTunes|iWork|Keychain|Keynote|LiveType|MacApp|MacBook Air|MacBook Pro|MacDNS|Macintosh|Mac OS|Mac Pro|MacTCP|MagSafe|MainStage|Mission Control|Objective-C|OpenCL|OpenPlay|OS X|Passbook|Photo Booth|Pixlet|PowerBook|Power Mac|Quartz|QuickDraw|QuickTime|Retina|Rosetta|Safari|Sherlock|Siri|Smart Cover|SuperDrive|Time Capsule|Time Machine|TrueType|WaveBurner|WebObjects|Xcode|Xgrid|Xsan|Xserve)/ig, '<span class="rtrademark">$1</span>');
		my_html = my_html.replace(/(AirPrint|Apple CarPlay|AppleLink|Apple Pay|Apple Remote Desktop|Apple Studio Display|AppleVision|Apple Watch|Cinema Tools|DVD@CCESS|EarPods|eMac|EtherTalk|Flyover|iBeacon|ImageWriter|iPad Air|iPad mini|iWeb|LaserWriter|Lightning|LocalTalk|Multi-Touch|NetInfo|Newton|OfflineRT|Photocasting|ProDOS|SnapBack|StyleWriter|TestFlight|Touch ID)/ig, '<span class="trademark">$1</span>');
		my_html = my_html.replace(/(iNSIDER)/ig, '<span class="insider">$1</span>');
		$j(this).html(my_html);
	
	}	//end if ($j(this).children().length == 0)

}); //end $j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function ()



/*****
	nav menu position set, init hover behavior. (Subnav behavior set after page rendered, in initTekNavSubmenu)
*****/

function navInit() {

	//store fixed value for menu top position
	$j('.nav-primary').data('top', $j('.nav-primary').offset().top);
	
	//bind class hovermenu to hover status
	$j('.menu-primary').hover (
	
  		function() {
    		$j(this).addClass('hovermenu');
  		}, 
  		
  		function() {
    		$j(this).removeClass('hovermenu');
  		}
  	
  	);	//$j('.menu-primary').hover
  	
  	//remove class hovermenu on window scroll
  	$j(window).scroll(function() {
  	
		if ($j('.nav-primary').hasClass('floating-menu')) {
		
			$j('.menu-primary').removeClass('hovermenu');
		
		}	//end if ($j('.nav-primary').hasClass('floating-menu'))
	
	});	//end $j(window).scroll(function()
	
	//toggle class hovermenu on click (certain implementations of tap input send click instead of hover)
	$j('.menu-primary').click(function() {
	
		$j(this).addClass( "hovermenu" );
  	
  	});	//end $j('.menu-primary').click(function() 
  	
  	//bind fixDiv() to window scroll; enables fixed topnav
  	$j(window).scroll(fixDiv);
  	
  	//Initialize submenu position
	$j('.sub-menu').css({'left': -9999, 'opacity': 0});
  	
}	//end navInit()



/****
	fixes nav to top screen as user scrolls down, add offset to content
****/

function fixDiv() { 

    var $jdiv = $j('.nav-primary');
    
    // if window is larger than the document content-> no pinned menu and show .site-footer
    if ( $j(window).height() >= ( $j(document).height() - $j('.footer-widgets').height() ) ) {
    
    	$j('.site-footer').addClass('floating');
    	$jdiv.removeClass('floating-menu');
    	
    }
    // if window is not mobile, and difference between content height and window height is larger than the total height of the navbar-> no pinned menu and show .site-footer
    else if ( $j(window).height() - $j('.site-inner').height() >= $j('.nav-primary > .wrap').outerHeight(true) && $j(window).width() > 450 ) {
    
    	$j('.site-footer').addClass('floating');
    	$jdiv.removeClass('floating-menu');
    
    }
    // if window is mobile, and difference between (content + header) height and window height is larger than the total height of the navbar-> no pinned menu and show .site-footer
    else if ( $j(window).height() - $j('.site-inner').height() - $j('.site-header').height() >= $j('.nav-primary > .wrap').outerHeight(true) && $j(window).width() < 450 ) {
    
    	$j('.site-footer').addClass('floating');
    	$jdiv.removeClass('floating-menu');
    
    }
    //	otherwise, use normal pinned menu and sliding .site-footer behavior. Conditions prevent window jumping and hiding footer in certain window configurations
    else {
    
		if ($j(window).scrollTop() > $jdiv.data("top")) {
		
			$jdiv.addClass('floating-menu');
			$j('.site-footer').addClass('floating');
		
		}
		else {
		
			$jdiv.removeClass('floating-menu');
			$j('.site-footer').removeClass('floating');
		
		}	//end if ($j(window).scrollTop() > $jdiv.data("top"))

	}	//end if ( $j(window).height() >= $j(document).height() )
	
}	//end fixDiv()



/****
	anchor handler processes offset for nav that is removed from document flow on fresh page load
****/

//rewrite anchor action needed thanks to crazy image requirements
function goToAnchor() {

	hash = document.location.hash;
	if (hash !="") {
	
		setTimeout(function() {
			if (location.hash) {
			
				window.scrollTo(0, 0);
				fixDiv();
				var offset = -(parseInt($j('.nav-primary').outerHeight(true)));
				window.location.href = hash;
				window.scrollBy(0, offset);
			
			}	//end if (location.hash)
		
		}, 250);	//end setTimeout(function()
	
	}
	else {
	
		return false;
	
	}	//end if (hash !="")

}	//end gotToAnchor()



/****
	same as goToAnchor, but for anchor elements targeting the same page
****/

function bindAnchors() {

	$j("a[href^='#']").addClass('anchor').click( function(event) {
	
		event.preventDefault();
		var pageHash = $j(this).attr('href');
		setTimeout(function() {
		
			if (pageHash != "" && pageHash != "#" && pageHash != "#!" && pageHash.indexOf("tab-") === -1) {
			
				window.scrollTo(0, 0);
				fixDiv();
				var offset = -(parseInt($j('.nav-primary').outerHeight(true)));
				window.location.href = pageHash;
				window.scrollBy(0, offset);
			
			}	//end if (pageHash != "" && pageHash != "#" && pageHash != "#!" && pageHash.indexOf("tab-") === -1)
		
		}, 250);	//end setTimeout(function()
	
	});	//end $j("a[href^='#']").addClass('anchor').click(function()

}	//end bindAnchors()