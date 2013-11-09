$j = jQuery; //noconflict
function blogSectionHeight() {// sets height for blog front page sections to height of row
	var rowHeight;
	var wrapperHeight;
	$j('.page-template-static_content-php #wrap #inner .wrap #content .vc_row-fluid .wpb_column').each(function() {
		$j(this).css('min-height', 0);
		rowHeight = $j(this).parents('.vc_row-fluid.wpb_row').css('height');
		wrapperHeight = $j(this).children('.wpb_wrapper').css('height');
// 		console.log(rowHeight+' '+wrapperHeight);
// 		if(parseInt(rowHeight)>parseInt(wrapperHeight)){
// 			$j(this).css('height', wrapperHeight);
// 		}
		if(parseInt(rowHeight)<1000){
			$j(this).css('min-height', rowHeight);
		}
	});
}
$j('document').ready(function() { //call on load
	blogSectionHeight();
});

$j(window).resize(function() { //call on window resize
	blogSectionHeight();
});

////////oh but why can i not make you smaller again! alice will never fit through the doorway now, the white rabbit is lost forever...