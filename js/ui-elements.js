var $j = jQuery; //noconflict declaration

function drawerSlide(sectionID, drawerID, buttonID){  //called onclick to toggle the drawers. sectionid, drawerid, and buttonid are declared in body.
$j('#'+sectionID+' .opened').toggleClass("opened"); //spins the triangle for the button on any currently opened drawer of section. 
$j('#'+buttonID).toggleClass("opened");  //spins the triangle for the clicked button. 
  //closes any open drawer.
$j('#'+drawerID).slideToggle();  //opens or closes drawer corresponding to clicked button. 
}

var wrapperbgcolor;
$j(".section").each(function() { //loops through each section, creating a wrapper with the corresponding bg color to be stretched to the window length.
	wrapperbgcolor = $j(this).css('background-color');
	$j(this).wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');
});

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
				$j(".section").each(function() { //loops through each section, changing image into background as window shrinks.
			var bgimgsrc = $j('.left-full').attr('src');
			if (bgimgsrc) {
				$j('.left-full').css('display','none');
				$j(this).css('background-image','url('+bgimgsrc+')');
				$j(this).css('background-position','bottom left');
				$j(this).css('background-repeat','no-repeat');
				$j(this).css('background-size','contain');
			}
			else {
				var bgimgsrc = $j('.right-full').attr('src');
				if (bgimgsrc) {
					$j('.right-full').css('display','none');
					$j(this).css('background-image','url('+bgimgsrc+')');
					$j(this).css('background-position','bottom right');
					$j(this).css('background-repeat','no-repeat');
					$j(this).css('background-size','contain');
				}
			}	
		});
	}
	else {
		$j('.bgwrapper').css('padding','0 '+viewMargin+'px');
		$j('.bgwrapper').css('left', '-'+viewMoveBig+'px');
		$j(".section").each(function() { //loops through each section, removing background image and showing image as window grows.
			var bgimgsrc = $j('.left-full').attr('src');
			if (bgimgsrc) {
				$j('.left-full').css('display','block');
				$j(this).css('background-image','none');
			}
			else {
				var bgimgsrc = $j('.right-full').attr('src');
				if (bgimgsrc) {
					$j('.right-full').css('display','block');
					$j(this).css('background-image','none');
				}
			}	
		});
	}
}

stretchSection(); //call on load

$j(window).resize(function() { //call on window resize
	stretchSection();
});

$j('.twoUp').after('<div class="clear">&nbsp;</div>');  //clears floats after twoup divs in sections

function fixDiv() {
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