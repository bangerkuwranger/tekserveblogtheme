$j = jQuery; //noconflict
function blogSectionHeight() {// sets height for blog front page sections to height of row
	var leftHeight;
	var rightHeight;
	$j('.page-template-static_content-php #wrap #inner .wrap #content .vc_row-fluid').each(function() {
		$j(this).children('.wpb_column').css('min-height', 0);
		leftHeight = $j(this).children('.leftstory').height();
		rightHeight = $j(this).children('.rightstory').height();
		if(leftHeight < rightHeight) {
 			$j(this).children('.leftstory').css('min-height', rightHeight);
		}
		else {
			$j(this).children('.rightstory').css('min-height', leftHeight+'px');
		}
	});
}
$j('document').ready(function() { //call on load
	blogSectionHeight();
	//toggle orange box headers
	$j( ".page-template-static_content-php .widget.widget_categories h2" ).click(function() {
		$j('.widget.widget_categories ul li').slideToggle();
	});
	$j( ".page-template-static_content-php #Front_Page .widget_knrauthorlistwidget h2" ).click(function() {
		$j('#Front_Page .widget_knrauthorlistwidget ul li').slideToggle();
	});
	$j( ".page-template-static_content-php #Front_Page .widget_anual_archive h2" ).click(function() {
		$j('#Front_Page .widget_anual_archive ul li').slideToggle();
	});
});

$j(window).resize(function() { //call on window resize
	blogSectionHeight();
});

////////oh but why can i not make you smaller again! alice will never fit through the doorway now, the white rabbit is lost forever...



