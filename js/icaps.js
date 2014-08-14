/*
	noconflict declaration
*/

var $j = jQuery;

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