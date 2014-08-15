/******
	noconflict declaration, global vars
******/

var $j = jQuery; 
var loader = '<div id="pageLoad"><img src="' + themeInfo['cssurl'] + '/images/ajax-loader.gif" alt="Page Loading" /></div>';
var clientWidth = document.documentElement.clientWidth;
var $rev_slider;

/******
	call on jQuery loaded
*******/

$j(function() {

	if ($j('body').hasClass('page')) {
		$j('#footer-widgets').before(loader);
		console.log('loading');
	}
	
	//Initialize nav menu functions
	navInit();

});

/******
	call on page fully loaded
******/

$j(window).bind('load', function() {

	rearrangeContent(clientWidth); ////pass window width to various functions in width.js on full load
		$j(window).resize(function() {
			clientWidth = document.documentElement.clientWidth;
			rearrangeContent(clientWidth);
		});  //pass window width to various functions in width.js on resize

	if ($j('body').hasClass('page')) {
		
		//remove empty paragraphs
		$j('p:empty').remove();

		//create internal wraps for text color/width of internal content
		$j('.vc_row').each(function() {
			console.log(this);
			var bgcolor = $j(this).css('background-color');
			switch (bgcolor) {
			
				case "rgb(0, 77, 114)":
					//darkblue
					$j(this).find('.wpb_column').wrapAll("<div class='innerRowWrap darkblue' />");
					break;
				case "rgb(64, 168, 201)":
					//lightblue
					$j(this).find('.wpb_column').wrapAll("<div class='innerRowWrap lightblue' />");
					break;
				case "rgb(243, 111, 55)":
					//orange
					$j(this).find('.wpb_column').wrapAll("<div class='innerRowWrap orange' />");
					break;
				default:
					//assumed white, catchall at least wraps any other color
					$j(this).find('.wpb_column').wrapAll("<div class='innerRowWrap' />");
					
			
			} //end switch(bgcolor)
		
		}); //end $j('.vc_row').each( function()
		
		additionalFunctions(); //call external routines
		
		console.log('loaded');
		$rev_slider = $j(".rev_slider");
		$j('#pageLoad').remove();
		$j('#inner').slideDown(); // show content after assigning classes
		$rev_slider.revprev().revnext(); //reload first slide after revslider to avoid squishing
		goToAnchor();
		bindAnchors();
	} //end if ( $j('body').hasClass('page') )
	
	

}); //end onload function

/******
	deprecated stuff caught
******/

function scrollToID() {
	goToAnchor();
}

/******
	additional functions defined
******/

function additionalFunctions() {
	
	detailBoxBinder();
	
} //end additionalFunctions()

/******
	anchor handler processes offset for nav that is removed from document flow
******/

//rewrite anchor action needed thanks to crazy image requirements
function goToAnchor() { 
	hash = document.location.hash;
	if (hash !="") {
		setTimeout(function() {
			if (location.hash) {
				window.scrollTo(0, 0);
				$j('#nav').addClass('floating-menu');
				var offset = -( parseInt( $j('#nav').outerHeight(true) ) );
				window.location.href = hash;
				window.scrollBy(0, offset);
			} //end if (location.hash)
		}, 500); //end setTimeout(function()
	}
	else {
		return false;
	} //end if (hash !="")
} //end gotToAnchor()

//same as goToAnchor, but for anchor elements targeting the same page
function bindAnchors() {
	$j("a[href^='#']").addClass('anchor').click(function(event) {
		event.preventDefault();
		var pageHash = $j(this).attr('href');
		setTimeout(function() {
			if (pageHash != "" && pageHash != "#" && pageHash != "#!") {
				window.scrollTo(0, 0);
				$j('#nav').addClass('floating-menu');
				var offset = -( parseInt( $j('#nav').outerHeight(true) ) );
				window.location.href = pageHash;
				window.scrollBy(0, offset);
			} //end if (location.hash)
		}, 500); //end setTimeout(function()
	}); //end .click(function()
} //end bindAnchors()