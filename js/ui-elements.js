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
	$j('.bgwrapper').css('position', 'relative');
	viewWidth = $j(window).width();
	$j('.page .hentry').css('overflow', 'visible');
	$j('.entry-content').css('overflow', 'visible');
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

$j(window).resize(function() { //call on window resize
	stretchSection();
	clientWidth = $j(window).width();
	if(clientWidth < 627) {
		moveSearchMobile(); //call on resize if window size becomes < 640
	}
	if(clientWidth > 627){
		moveSearchBack(); //call on resize if window size > 640
	}
	if(clientWidth < 1025) {
		mobileMeta(); //call on resize if window size becomes < 1025
	}
	if(clientWidth > 1024){
		remMobileMeta(); //call on resize if window size > 1024
	}
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

function scrollToID(targetID, delay, isHash) {
	if(isHash == "" || isHash == undefined) {
		isHash = false;
	}
	if(delay == "" || delay == undefined) {
		delay = 0;
	}
	console.log(delay);
    var offset_top = $j('#find-'+targetID.substring(1)).offset();
    var offset_trigger = $j('#extra1-'+targetID.substring(1)).offset();
	offset_trigger = offset_trigger.top;
	if(($j('#extra1-'+targetID.substring(1)).attr('name')) && (isHash == true)) {
		offset_trigger = $j('#extra1-'+targetID.substring(1)).attr('name');
	}
	if(!offset_trigger) {
		offset_trigger = target_offset.top;
	}
	offset_trigger = offset_trigger - 150;
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


$j('document').ready(function() { //call on load

	$j('.dsection').parents('.wpb_wrapper, .wpb_column').addClass('thinMan');//set initial class for drawer containers
	$j('.dsection').addClass('thinMan');
	
	$j('.section').each(function() { //loops through each section, creating a wrapper with the corresponding bg color to be stretched to the window length.
		wrapperbgcolor = $j(this).css('background-color');
		$j(this).wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');//creates full width wrapper element with correct background color
		wrapperbgcolor = color2color(wrapperbgcolor, 'rgba', true, '.65'); //converts background color to rgba with 65% opacity, then adds that to left, right, and drawer content
	});
	
	$j('#nav .wrap').append('<div class="closeButton" onmouseover="$j(this).remove();" onclick="$j(this).remove();">&nbsp;</div>'); //add close button to mobile nav
	
	stretchSection(); //call on load
	
	var clientWidth = document.documentElement.clientWidth;
	if(clientWidth < 600){
		moveSearchMobile(); //call on load if window size < 600
	}
	if(clientWidth < 1025){
		mobileMeta(); //call on load if window size < 1025
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

	$j('.search-results img').removeAttr('width').removeAttr('height');//remove image size attributes from search results page

	//fix ol, ul sizes followed by headers
	$j('h2').parent('li').addClass('htwoList');
	$j('h3').parent('li').addClass('hthreeList');
	$j('h4').parent('li').addClass('hfourList');

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

	$j('.section').removeClass('vc_row-fluid');  //removes vc_fluid badness from sections

	var slugtext;
	$j('.sortable th').each(function() {  //removes slug ugliness from sorttable columns
		slugtext = $j(this).text();
		slugtext = slugtext.replace( 'tekserve_press_', '' );
		$j(this).text(slugtext);
	});

	//add class to quotation sections to adjust margins
	$j('.tekserve-testimonial').parents('.section').addClass('testimonial');

	//remove bottom padding from footer folk
	$j('.footer-folk').parents('.wpb_wrapper').css('padding-bottom', '0');
	
	//remove bottom padding from top slider
	$j('.wpb_revslider_element.wpb_content_element').parents('.wpb_wrapper').css('padding-bottom', '0');
	
	//add group rel="" to original drawer trigger in collapse-o-matic objects with remote triggers
	$j(".collapseomatic"+"[id^=extra]").each(function() {
		var id = $j(this).attr('id');
		if(id != "") {
			id = id.substring(7);
			var rel = $j(this).attr('rel');
			$j('#'+id+'.collapseomatic').attr('rel', rel);
		}
	});
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
	
// 		$j('.drawertrigger').click(function() {
// 
// 			var clickedTrigger = $j(this).attr('id');
// 			var targetDrawer = clickedTrigger.substring(7);
// 			if(!($j('#'+clickedTrigger).hasClass('colomat-close'))) {
// 				targetDrawer = '#' + targetDrawer;
// 				console.log(targetDrawer);
// 				scrollToID(targetDrawer, 500);
// 			}
// 		});
	}, 1000);
});