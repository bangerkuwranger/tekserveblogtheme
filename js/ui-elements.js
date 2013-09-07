var $j = jQuery;
function drawerSlide(drawerID, buttonID){
$j('#'+drawerID).slideToggle();
$j('#'+buttonID).toggleClass("opened");
}
var wrapperbgcolor;
$j(".section").each(function() {
	wrapperbgcolor = $j(this).css('background-color');
	$j(this).wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');
});
// $j('.section > .wrap').wrap('<div class="bgwrapper" style="background:'+wrapperbgcolor+';" />');

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
$j(window).resize(function() {
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
});