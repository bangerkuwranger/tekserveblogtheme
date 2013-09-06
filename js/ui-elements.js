var $j = jQuery;
function drawerSlide(drawerID, buttonID){
$j('#'+drawerID).slideToggle();
$j('#'+buttonID).toggleClass("opened");
}
var wrapperbgcolor;
$j(".section").each(function() {
	wrapperbgcolor = $j(this).css('background-color');
	$j(this).wrap('<div class="wrap" />');
	$j(this > '.wrap').wrap('<div class="bgwrapper" style="background="'+wrapperbgcolor+'" />');
});
function fixDiv() {
    var $div = $j("#nav");
    if ($j(window).scrollTop() > $div.data("top")) { 
        $j('#nav').css({'position': 'fixed', 'top': '0', 'width': '100%'}); 
    }
    else {
        $j('#nav').css({'position': 'static', 'top': 'auto', 'width': '100%'});
    }
}

$j("#nav").data("top", $j("#nav").offset().top); // set original position on load
$j(window).scroll(fixDiv);