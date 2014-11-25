var $j = jQuery;

$j(window).bind("load",function() {

	labelsToPlaceholders();

	$j('.gform_wrapper input.button').click( function() {

		setTimeout( function(){ labelsToPlaceholders(); }, 1000 );

	}); //end $j('.gform_wrapper input.button').click( function()

}); //end $j(window).bind("load",function()

function labelsToPlaceholders() {
	
	var $gformLabels = $j('.gfield');

	if ( $gformLabels.length > 0 ) {

		$gformLabels.each( function() {
	
			var labeltxt = $j(this).find('label').text();
			labeltxt = labeltxt.replace('*', '');
			console.log('label: ' + labeltxt);
			$j(this).find('input, textarea').attr('placeholder', labeltxt).attr('title', labeltxt);
	
		}); //end $gformLabels.each( function()

	} //end if ( $gformLabels.length > 0 )

} //end function labelsToPlaceholders()