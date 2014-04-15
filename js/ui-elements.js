var $j = jQuery; //noconflict declaration

function drawerSlide(sectionID, drawerID, buttonID){  //called onclick to toggle the drawers. sectionid, drawerid, and buttonid are declared in body.
$j('#'+sectionID+' .opened').toggleClass("opened"); //spins the triangle for the button on any currently opened drawer of section. 
$j('#'+buttonID).toggleClass("opened");  //spins the triangle for the clicked button. 
  //closes any open drawer.
$j('#'+drawerID).slideToggle();  //opens or closes drawer corresponding to clicked button. 
}
var sectionid;
var wrapperbgcolor; 	
var contrastColor;
function getContrast() { // set contrasting color based on background color
	switch(wrapperbgcolor)
	{
	case 'rgb(243, 111, 55)':
		contrastColor = 'rgb(0, 77, 114)';
		break;	
	case 'rgb(64, 168, 201)':
		contrastColor = 'rgb(243, 111, 55)';
		break;
	case 'rgb(0, 77, 114)':
		contrastColor = 'rgb(243, 111, 55)';
		break;
	default:
		contrastColor = 'rgb(64, 168, 201)';
	}
}


function mobileMeta() {
	$j('head').append('<meta id="apple-mobile-web-app-capable" name="apple-mobile-web-app-capable" content="yes">');
	$j('head').append('<meta id="apple-mobile-web-app-status-bar-style" name="apple-mobile-web-app-status-bar-style" content="black">');
}
function remMobileMeta() {
	$j('#apple-mobile-web-app-capable, #apple-mobile-web-app-status-bar-style').remove();
}

function moveSearchMobile () {  //moves search outside of nav node; called on page load and window resize
	$j('.right.search').insertAfter('#nav');
}
function moveSearchBack () {  //moves search back into nav node; called on window resize
	$j('.right.search').insertAfter('#nav div ul li:last-child');
}

function mobileCloseButton() {

		$j('#nav .wrap .closeButton').toggle();
}

//stretchSection needs browser detection to set % correctly
function stretchSection() {  //function is called on load and on window resize; stretches bgwrapper to viewport width and sets pos & padding
	if($j('.bgwrapper').length) {
		$j('.bgwrapper').css('position', 'relative');
		viewWidth = $j(window).width();
		var viewMargin = (viewWidth - 960) / 2;
		var viewMoveBig = $j('#content').css('padding-left');
		viewMoveBig = viewMoveBig.substring(0, viewMoveBig.length - 2);
		var extraMargin = $j('#inner > .wrap').css('margin-left');
		extraMargin = extraMargin.substring(0, extraMargin.length - 2);
		viewMoveBig = parseInt(viewMoveBig, 10) + parseInt(extraMargin, 10);
		var viewMoveSmall = viewWidth * .1;
		$j('.bgwrapper').css('width', viewWidth);
		if (viewWidth <= 960) {
			$j('.bgwrapper').css('padding','0 10%');
			$j('.bgwrapper').css('left', '-'+viewMoveSmall+'px');
		}
		else {
			$j('.bgwrapper').css('padding','0 '+viewMargin+'px');
			$j('.bgwrapper').css('left', '-'+viewMoveBig+'px');
		}
	}
}

$j(window).resize(function() { //call on window resize
	stretchSection(); //resize call for section stretch
	clientWidth = $j(window).width();
	if(clientWidth < 627) {
		moveSearchMobile(); //call on resize if window size becomes < 640, moves search outside of nav
	}
	if(clientWidth > 627){
		moveSearchBack(); //call on resize if window size > 640, moves search back into nav
	}
	if(clientWidth < 1025) {
		mobileMeta(); //call on resize if window size becomes < 1025, adds apple's happy lil metas for ios
	}
	if(clientWidth > 1024){
		remMobileMeta(); //call on resize if window size > 1024, rems apple's happy lil metas for ios
	}
	swapHeaderImgs(clientWidth); //call on resize if window size becomes < 768, swaps image to mobile
});

function fixDiv() { //fixes nav to top screen as user scrolls down
    var $jdiv = $j("#nav");
    if ($j(window).scrollTop() > $jdiv.data("top")) { 
        $j('#nav').css({'position': 'fixed', 'top': '0', 'width': '100%'}); 
    }
    else {
        $j('#nav').css({'position': 'static', 'top': 'auto', 'width': '100%'});
    }
}

//function to scroll to object with ID targetID. Include hash symbol in targetID when calling function.

function scrollToID(targetID, delay, isHash, offsetV) {
	if(isHash == "" || isHash == undefined) {
		isHash = false;
	}
	if(delay == "" || delay == undefined) {
		delay = 0;
	}
	if(offsetV == "" || offsetV == undefined) {
		offsetV = 0;
	}
	if(targetID.indexOf('.') !== -1) {
		targetID = '';
		return false;
	}
	console.log(targetID);
	if( $j('#find-'+targetID.substring(1)).length != 0 ) {
		var offset_top = $j('#find-'+targetID.substring(1)).offset();
	}
    var offset_trigger;
    if( $j('#extra1-'+targetID.substring(1)).length != 0 ) {
		offset_trigger = $j('#extra1-'+targetID.substring(1)).offset();
		offset_trigger = offset_trigger.top;
		if(($j('#extra1-'+targetID.substring(1)).attr('name')) && (isHash == true)) {
			offset_trigger = $j($j('#extra1-'+targetID.substring(1)).attr('name')).offset().top;
		}
	}
	if(!offset_trigger) {
		if(!offset_top) {
			offset_trigger = $j(targetID).offset().top;
		}
		else {
			offset_trigger = offset_top;
		}
	}
	console.log('pretrigoff - '+offset_trigger);
	console.log('offsetV - '+offsetV);
	offset_trigger = offset_trigger - 50 - offsetV;
	console.log(offset_top);
	console.log(offset_trigger);
	setTimeout(function(){
		$j('html, body').animate({
			scrollTop: offset_trigger
		}, { 
			duration: 500, 
			easing: 'swing'
		});
	}, delay);
}

//function to swap programmatically added mobile header image url with existing header image url.
//only runs if plugin tekserve-shared-data has created it. Requires a page width.
function swapHeaderImgs(pageWidth) {
	if($j('#tekserve-shared-data-hours-swap').length != 0) {
		console.log('TSD Obj created');
		var firstImg = $j('#wrap #header .wrap #title-area').css('backgroundImage');
		var secondImg = $j('#tekserve-shared-data-hours-swap').html();
		if( $j('#wrap #header .wrap #title-area').hasClass('mobileImg') && (pageWidth > 768) ) {
			$j('#wrap #header .wrap #title-area').css('backgroundImage', secondImg).removeClass('mobileImg');
			$j('#tekserve-shared-data-hours-swap').html(firstImg);
		}
		if( !( $j('#wrap #header .wrap #title-area').hasClass('mobileImg') ) && (pageWidth < 768) ) {
			$j('#wrap #header .wrap #title-area').css('backgroundImage', secondImg).addClass('mobileImg');
			$j('#tekserve-shared-data-hours-swap').html(firstImg);
		}
	}
}

function moveWaywardSubnav() {
	if($j('.tribe-is-responsive #subnav').length != 0) {
		var subNav = $j('.tribe-is-responsive #subnav').detach();
		$j( "#inner" ).before(subNav);
	}
	if($j('.category #subnav').length != 0) {
		var subNav = $j('.category #subnav').detach();
		$j( "#inner" ).before(subNav);
	}
	if($j('.single-post #subnav').length != 0) {
		var subNav = $j('.single-post #subnav').detach();
		$j( "#inner" ).before(subNav);
	}
	if($j('.archive #subnav').length != 0) {
		var subNav = $j('.single-post #subnav').detach();
		$j( "#inner" ).before(subNav);
	}
	if($j('.search-results #subnav').length != 0) {
		var subNav = $j('.single-post #subnav').detach();
		$j( "#inner" ).before(subNav);
	}
}

$j('document').ready(function() { //call on load

	$j('.tekserve_vendors').addClass('bgwrapper');//add bgwrapper to vendors, ala drawers (i.e. inside of row containers. Allows independence from VC framework.)
	$j('.dsection').parents('.wpb_wrapper, .wpb_column').addClass('thinMan');//set initial class for drawer containers
	$j('.dsection').addClass('thinMan');
	$j('.dboxsection').parents('.wpb_wrapper, .wpb_column').addClass('thinMan');//set initial class for detailBox containers
	$j('.dboxsection').addClass('thinMan');
	
	$j('.section').each(function() { //loops through each section, creating a wrapper with the corresponding bg color to be stretched to the window length.
		wrapperbgcolor = $j(this).css('background-color');
		$j(this).wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');//creates full width wrapper element with correct background color
		wrapperbgcolor = color2color(wrapperbgcolor, 'rgba', true, '.65'); //converts background color to rgba with 65% opacity, then adds that to left, right, and drawer content
		if($j(this).hasClass('dboxsection')) { //dbox wrapper moved to exterior of vc row, fixes edge to edge color of dbox section row
			var $jwrapper = $j(this).parent();
			$j(this).parents('.wpb_row').before($jwrapper);
		}
	});
	
	$j('#nav .wrap').append('<div class="closeButton" onmouseover="$j(this).remove();" onclick="$j(this).remove();">&nbsp;</div>'); //add close button to mobile nav
	moveWaywardSubnav(); //move the subnav into place on an events or blog page
	stretchSection(); //stretch sections widths at load
	
	var clientWidth = document.documentElement.clientWidth;
	if(clientWidth < 627){
		moveSearchMobile(); //call on load if window size < 640
	}
	if(clientWidth < 1025){
		mobileMeta(); //call on load if window size < 1025
	}
	if(clientWidth < 769){
		swapHeaderImgs(600); //call on load if window size ≤ 768
	}
	
	$j('#nav .wrap ul').hover(
		function() {
		$j('#nav .wrap').append('<div class="closeButton" onmouseover="$j(this).remove();" onclick="$j(this).remove();">&nbsp;</div>'); //add close button to mobile nav
	// 		mobileCloseButton();
		},
		function() {
			$j('#nav .wrap .closeButton').remove();
		}
	);
	
	$j("#nav").data("top", $j("#nav").offset().top); // set nav original position on load
	$j(window).scroll(fixDiv);
	var viewWidth = $j(window).width();
	$j( ".menu-primary" ).hover(
  		function() {
    		$j( this ).addClass( "hovermenu" );
  		}, function() {
    		$j( this ).removeClass( "hovermenu" );
  		}
  	);
  	$j( window ).scroll(function() {
		$j( ".menu-primary" ).removeClass( "hovermenu" );
	});
	$j( ".menu-primary" ).click(function() {
    		$j( this ).addClass( "hovermenu" );
  	});
	var downid;
	$j( ".drawertrigger" ).click(function() { //fix for the expanding size of background image as drawer is toggled; switches to background-size %50 and back to contain when drawer closes.
		downid = $j(this).attr('name');
		thisID = $j(this).attr('id');
	    setTimeout(function()
		{
// 			$j('#'+thisID).toggleClass('colomat-close');
			$j('#target-'+downid).toggleClass('drawerDown');
// 			$j('#target-'+downid).parents('.dsection').toggleClass("thinMan");
// 			$j('#target-'+downid).parents('.wpb_wrapper, .wpb_column').toggleClass('thinMan');
		}, 400);
	});
	
	//detailBox sliding behavior
	$j('.detailBoxTrigger').click(function() {
		var thistitle = $j(this).html();
		var leftheight = $j(this).parents('.detailBox').find('.detailBox-left').height();
		$j(this).parents('.detailBox').find('.detailBox-mobile-title').html(thistitle);
		$j(this).parents('.detailBox').addClass('shifted');
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
				}, 500).hide(500);
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
    });
    
    //detailBox mobile back button
    $j('.detailBox-mobile').click(function() {
    	$j(this).parents('.detailBox').removeClass('shifted');
    });

	$j('.search-results img').removeAttr('width').removeAttr('height');//remove image size attributes from search results page

	//fix ol, ul sizes followed by headers
	$j('h2').parent('li').addClass('htwoList');
	$j('h3').parent('li').addClass('hthreeList');
	$j('h4').parent('li').addClass('hfourList');
	//remove that from widgets
	$j('h2').parent('li.widget').removeClass('htwoList');

	var bgimgsrc;
	var objectparentsection;
	var objectparentcolumn;
	// var opptxtheight;
	$j(".bgimage div img").each(function() {//takes bgimage class object src and makes it the bgimage of parent .section.vc_row
		bgimgsrc = $j(this).attr('src');
		objectparentsection = $j(this).parents('.section');
		objectparentcolumn = $j(this).parents('.wpb_column');
		$j(objectparentsection).css('background-image', 'url('+bgimgsrc+')');
		if($j(objectparentcolumn).is('div:first-child')){
			$j(objectparentsection).css('background-position', 'bottom left');
		}
		else {
			$j(objectparentsection).css('background-position', 'bottom right');
		}
		$j(this).css('visibility', 'hidden');
		opptxtheight = $j(objectparentsection).css('height');
		if(opptxtheight == undefined) {
			$j(this).css('height', '0');
		}
		else {
			$j(this).css('height', opptxtheight);
		}
	});
	
	//icaps
	$j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function () {
		var my_html = $j(this).html();
		var my_old_html = $j(this).html();
 
		// Make sure there are no children so we don't edit more than we want.
		if ($j(this).children().length == 0) {
			my_html = my_html.replace(/(iPad|iPhone|iMac|iPod|iOS|Mac OS X|Mac mini|Mac Pro|MacBook Pro|MacBook Air|iCloud|iLife|iWork|iPhoto|iMovie|iCal|iTunes|AppleCare|Apple)/ig, '<span class="trademark">$1</span>');
			my_html = my_html.replace(/(iNSIDER)/ig, '<span class="insider">$1</span>');
			$j(this).html(my_html);
		}
	});
	
	$j('.section').each(function() {  //add rightside class to text with left bgimage
		if($j(this).css('backgroundPosition') == '0% 100%') {
			$j(this).find('div.wpb_text_column.wpb_content_element').addClass('rightside');
		}
	});

	$j('.section').removeClass('vc_row-fluid');  //removes vc_fluid badness from sections

	var slugtext;
	$j('.sortable th').each(function() {  //removes slug ugliness from sorttable columns
		slugtext = $j(this).text();
		slugtext = slugtext.replace( 'tekserve_press_', '' );
		$j(this).text(slugtext);
	});

	//remove empty paragraphs
	$j('p:empty').remove();
	
	//youtube still replace
	var iframe = $j('.home.page-template-static_content-php .wpb_video_wrapper iframe');
	$j('div.yt-embed-thumbnail', iframe.contents()).css('backgroundImage', 'url(./images/youtubeclip.jpg) !important');
	
	//add class to quotation/case study sections to adjust margins
	$j('.tekserve-testimonial').parents('.section').addClass('testimonial');
	$j('.tekserve-case-study').parents('.section').addClass('case-study');

	//remove bottom padding from footer folk
	$j('.footer-folk').parents('.wpb_wrapper').css('padding-bottom', '0');
	
	//remove bottom padding from top slider
	$j('.wpb_revslider_element.wpb_content_element').parents('.wpb_wrapper').css('padding-bottom', '0');
	
	//add group rel="" to original drawer trigger in collapse-o-matic objects with remote triggers
	//also add class to drawers open on load
	$j(".collapseomatic"+"[id^=extra]").each(function() {
		var id = $j(this).attr('id');
		if(id != "") {
			id = id.substring(7);
			var rel = $j(this).attr('rel');
			$j('#'+id+'.collapseomatic').attr('rel', rel);
			if($j('#'+id+'.collapseomatic').hasClass('colomat-close')) {
				$j(this).addClass('colomat-close');
			}
		}
	});
	
	//keep drawers closed on mobile
	if(clientWidth < 640){
		$j('.collapseomatic').removeClass('colomat-close'); //call on load if window size < 640
		$j('.collapseomatic_content').css('display', 'none');
	}
	
	//enably bit.ly for addthis smartlayers
	var addthis_share = {
	// ... other options for addthis smartlayers
	url_transforms : {
		shorten: {
			twitter: 'bitly',
			facebook: 'bitly'
		}
		}, 
		shorteners : {
			bitly : {} 
		}
	}
	//code to made map drawers act like other normal drawers
	$j('#trigger-get-directions').click(function() {
		$j('#get-to-tekserve').slideToggle();
	});
	var group = $j('#trigger-get-directions').attr('rel');
	$j("[rel="+group+"]").each(function() {
		var thisID = $j(this).attr('id');
		if(thisID != 'trigger-get-directions') {    
			$j(this).click(function() {
				$j('#get-to-tekserve').slideUp();
				$j('#trigger-get-directions').removeClass('colomat-close');
			});
		}
	});
	
	//scroll to id, intransigent swine! ///////NEED TO FIND HEIGHT of closed Drawer
	setTimeout(function() {
		$j('.drawertrigger').each(function() {
			var myOffset = ($j(this).offset()).top;
			$j(this).attr('name', myOffset);
		});
	
		if(window.location.hash) {
			var hashTarget = window.location.hash;
			console.log(hashTarget);
			if($j(hashTarget).hasClass('find-me')){
				scrollToID(hashTarget, 500, true);
			}
		}
	}, 1000);
	
	//make menu fit larger search box if tekserve custom search exists
	if($j('.tekserve_custom_search').length != 0) {
		$j('.tekserve_custom_search').parents('.menu-primary').addClass('tekserve_custom_search_menu');
	}
	
});
