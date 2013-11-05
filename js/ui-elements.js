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
$j('.section').each(function() { //loops through each section, creating a wrapper with the corresponding bg color to be stretched to the window length.
	wrapperbgcolor = $j(this).css('background-color');
	$j(this).wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');//creates full width wrapper element with correct background color
	wrapperbgcolor = color2color(wrapperbgcolor, 'rgba', true, '.65'); //converts background color to rgba with 65% opacity, then adds that to left, right, and drawer content
// 	$j('.left', this).css('background-color', wrapperbgcolor);
// 	$j('.right', this).css('background-color', wrapperbgcolor);
	console.log($j(this).children('.wpb_text_column'));// .css('background-color', wrapperbgcolor);
// 	$j('.collapseomatic_content', this).css('background-color', wrapperbgcolor);
	
});

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

function MobileCloseButton() {

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
// 			$j(".section").each(function() { //loops through each section, changing image into background as window shrinks.
// 				console.log($j(this).attr('id'));
// 				var sectionid = $j(this).attr('id');
// 				var bgimgsrc = $j('#'+sectionid+' .left-full').attr('src');
// 				if(bgimgsrc) {
// 					$j('.left-full').css('display','none');
// 					$j("#"+sectionid).css('background-image','url("'+bgimgsrc+'")');
// 					$j(this).css('background-position','bottom left');
// 					$j(this).css('background-repeat','no-repeat');
// 					$j(this).css('background-size','contain');
// 				}
// 				else {
// 					var bgimgsrc = $j('#'+sectionid+' .right-full').attr('src');
// 					if (bgimgsrc) {
// 						$j('.right-full').css('display','none');
// 						$j("#"+sectionid).css('background-image','url("'+bgimgsrc+'")');
// 						$j("#"+sectionid).css('background-position','bottom right');
// 						$j("#"+sectionid).css('background-repeat','no-repeat');
// 						$j("#"+sectionid).css('background-size','contain');
// 					}
// 				}	
// 			});
	}
	else {
		$j('.bgwrapper').css('padding','0 '+viewMargin+'px');
		$j('.bgwrapper').css('left', '-'+viewMoveBig+'px');
// 		$j(".section").each(function() { //loops through each section, removing background image and showing image as window grows.
// 			var bgimgsrc = $j('.left-full').attr('src');
// 			if (bgimgsrc) {
// 				$j('.left-full').css('display','block');
// 				$j(this).css('background-image','none');
// 			}
// 			else {
// 				var bgimgsrc = $j('.right-full').attr('src');
// 				if (bgimgsrc) {
// 					$j('.right-full').css('display','block');
// 					$j(this).css('background-image','none');
// 				}
// 			}	
// 		});
	}
}

$j('#nav .wrap').append('<div class="closeButton" onmouseover="$j(this).remove();" onclick="$j(this).remove();">&nbsp;</div>'); //add close button to mobile nav
stretchSection(); //call on load
var clientWidth = document.documentElement.clientWidth;
if(clientWidth < 600){
	moveSearchMobile(); //call on load if window size < 600
}
if(clientWidth < 1025){
	mobileMeta(); //call on load if window size < 1025
}

$j(window).resize(function() { //call on window resize
	stretchSection();
	clientWidth = document.documentElement.clientWidth;
	if(clientWidth < 600) {
		moveSearchMobile(); //call on resize if window size becomes < 600
	}
	if(clientWidth > 599){
		moveSearchBack(); //call on resize if window size > 599
	}
	if(clientWidth < 1025) {
		mobileMeta(); //call on resize if window size becomes < 1025
	}
	if(clientWidth > 1024){
		remMobileMeta(); //call on resize if window size > 1024
	}
});

$j('#nav .wrap ul').hover(
	function() {
	$j('#nav .wrap').append('<div class="closeButton" onmouseover="$j(this).remove();" onclick="$j(this).remove();">&nbsp;</div>'); //add close button to mobile nav
// 		MobileCloseButton();
	},
	function() {
		$j('#nav .wrap .closeButton').remove();
	}
);



// $j('.twoUp').after('<div class="clear">&nbsp;</div>');  //clears floats after twoup divs in sections

function fixDiv() { //fixes nav to top screen as user scrolls down
    var $jdiv = $j("#nav");
    if ($j(window).scrollTop() > $jdiv.data("top")) { 
        $j('#nav').css({'position': 'fixed', 'top': '0', 'width': '100%'}); 
    }
    else {
        $j('#nav').css({'position': 'static', 'top': 'auto', 'width': '100%'});
    }
}

$j("#nav").data("top", $j("#nav").offset().top); // set original position on load
$j(window).scroll(fixDiv);
var viewWidth = $j(window).width();

var downid;
$j( ".drawertrigger" ).click(function() { //fix for the expanding size of background image as drawer is toggled; switches to background-size %50 and back to contain when drawer closes.
  downid = $j(this).attr('targetid');
  $j('#target-'+downid).toggleClass('drawerDown');
  $j('#target-'+downid).parents('.dsection').toggle();
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

$j('.section').removeClass('vc_row-fluid');//removes vc_fluid badness from sections