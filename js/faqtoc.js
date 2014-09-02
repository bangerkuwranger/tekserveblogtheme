/******
	noconflict declaration & globals
******/

var $j = jQuery;

/******
	move images in headings to toc and add toc class to body
******/

$j(window).bind('load', function() {
	
	if($j('#toc_container').length > 0) {
		
		$j('body').addClass('has-toc');
		$j('#toc_container>.toc_list>li').each(function() {

			var theAnchor = $j(this).children('a').attr('href');
			console.log(theAnchor);
			var theTarget = $j(theAnchor);
			if (theTarget.children('img').length > 0) {

				var theImage = theTarget.children('img').attr('src');
				theImage = 'url(' + theImage + ')';
				$j(this).css('backgroundImage', theImage);

			} //end if (theTarget.children('img').length > 0)

		}); //end $j('#toc_container>.toc_list>li').each(function()

	} //end if($j('#toc_container').length > 0)
	
}); //end $j(window).bind('load', function()