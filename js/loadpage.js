/******
	noconflict declaration, global vars
******/

var $j = jQuery;
var clientWidth = document.documentElement.clientWidth;
var $rev_slider;

/******
	call on jQuery loaded
*******/

$j(function() {
	
	//Initialize nav menu functions
	navInit();
		
	if ($j('body').hasClass('page')) {
		
		//create internal wraps for text color/width of internal content, add fullHeight class to page columns with contrasting backgrounds to row with correct bgcolor wrap
		$j('.vc_row, .wpb_row').each(function() {

			if ( !( $j(this).hasClass('vc_inner') ) ) {

				var bgcolor = $j(this).css('background-color');
				switch (bgcolor) {
			
					case "rgb(0, 77, 114)":
						//darkblue
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap darkblue' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					case "rgb(64, 168, 201)":
						//lightblue
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap lightblue' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					case "rgb(243, 111, 55)":
						//orange
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap orange' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
						break;
					default:
						//assumed white, catchall at least wraps any other color
						var $cols = $j(this).contents().detach();
						$j(this).prepend("<div class='innerRowWrap' />");
						var $wrap = $j(this).children('.innerRowWrap');
						$cols.prependTo($wrap);
					
			
				} //end switch(bgcolor)
			
				$j(this).find(".wpb_column").filter(function() {
					return this.className.match(/\bvc_custom/);
				}).each(function() {
					var colbg = $j(this).css('background-color');
					if ( colbg != bgcolor ) {
					
						$j(this).addClass('fullHeight');
					
						switch (colbg) {
			
							case "rgb(0, 77, 114)":
								//darkblue
								$j(this).wrap("<div class='darkblue' />");
								break;
							case "rgb(64, 168, 201)":
								//lightblue
								$j(this).wrap("<div class='lightblue' />");
								break;
							case "rgb(243, 111, 55)":
								//orange
								$j(this).wrap("<div class='orange' />");
								break;
							default:
								//assumed white, catchall at least wraps any other color
								$j(this).wrap("<div class='white' />");
					
						} //end switch(colbg)
					
					} //end if ($j(this).css('background-color') != bgcolor)
				
				}); //end $j(this).find(".wpb_column[class^='vc_custom']").each(function()
				
			} //end if ( !( $j(this).hasClass('vc_inner') ) )
		
		}); //end $j('.vc_row').each( function()
		
		$j('#inner').slideDown(); // show content after assigning classes

	} //end if ( $j('body').hasClass('page') )


	
});

/******
	call on page fully loaded
******/

$j(window).bind('load', function() {

	//bind hover class to special notice if there is a link present
	if ($j('#special-notice a').length > 0 ) {
		$j('#special-notice a').hover(function() {
			$j('#special-notice').toggleClass('hover');
		});
	} //end if ($j('#special-notice a').length > 0 )
	
	if ($j('body').hasClass('page')) {
		//remove empty paragraphs
		$j('p:empty').remove();
		additionalFunctions(); //call external routines
		$rev_slider = $j(".rev_slider");
		if ($rev_slider.length > 0){
			$rev_slider.revprev().revnext(); //reload first slide after revslider to avoid squishing
		} //end if ($rev_slider.length > 0)
		goToAnchor();
		bindAnchors();
		$j('#pageLoad').slideUp();
		initFooterFolk();
	} //end if ( $j('body').hasClass('page') )
	
	rearrangeContent(clientWidth); ////pass window width to various functions in width.js on full load
	$j(window).resize(function() {
		clientWidth = document.documentElement.clientWidth;
		rearrangeContent(clientWidth);
	});  //pass window width to various functions in width.js on resize
	

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
			if (pageHash != "" && pageHash != "#" && pageHash != "#!" && pageHash.indexOf("tab-") === -1 ) {
				window.scrollTo(0, 0);
				$j('#nav').addClass('floating-menu');
				var offset = -( parseInt( $j('#nav').outerHeight(true) ) );
				window.location.href = pageHash;
				window.scrollBy(0, offset);
			} //end if (location.hash)
		}, 500); //end setTimeout(function()
	}); //end .click(function()
} //end bindAnchors()