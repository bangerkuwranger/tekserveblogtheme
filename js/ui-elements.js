var $j = jQuery;
function drawerSlide(drawerID, buttonID){
$j('#'+drawerID).slideToggle();
$j('#'+buttonID).toggleClass("opened");
}
var $j = jQuery;
function drawerSlide(drawerID, buttonID){
$j('#'+drawerID).slideToggle();
$j('#'+buttonID).toggleClass("opened");
}
var wrapperbgcolor;
$j('.section').each(function() {
	wrapperbgcolor = $j(this).css('background-color');
	switch (wrapperbgcolor) {
		case "#f36f37":
			wrapperbgcolor = 'orange';
			break;
		case "#004d72":
			wrapperbgcolor = 'darkblue';
			break;
		case "#40a8c9":
			wrapperbgcolor = 'lightblue';
			break;
		default:
			wrapperbgcolor = '';
	}
	$j(this).wrap('<div class="wrap" />');
	$j(this > '.wrap').wrap('<div class="bgwrapper '+wrapperbgcolor+'" />');
});