/******
	noconflict declaration & globals
******/

var $j = jQuery;
var tocLink = '<a href="#toc_container" class="toclink" title="Go to table of contents"><i class="fa fa-caret-square-o-up"></i> TABLE OF CONTENTS</a>';

/******
	move images in headings to toc and add toc class to body
******/

$j(window).bind('load', function() {
	
	initFaqToc();
	
}); //end $j(window).bind('load', function()

function initFaqToc() {

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
		
		//move first paragraph to position under title (wraps around featured image)
		var $firstP = $j('div.entry-content>p:first-child').detach();
		$j('.entry-title').after($firstP);
		$j('.entry-title').after('<hr style="margin-bottom:2em;" />');
		
		//add TOC links before every body H2 except first
		$j('.entry-content h2').before(tocLink);
		$j('.entry-content a.toclink:first-of-type').remove();
		
		//bind toc anchors
		goToAnchor();
		bindAnchors();

	} //end if($j('#toc_container').length > 0)
	
}	//end initFaqToc()