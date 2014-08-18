/******
	noconflict declaration
******/

var $j = jQuery;

/******
	nav menu position set, init hover behavior
******/

function navInit() {

	//store fixed value for menu top position
	$j("#nav").data("top", $j("#nav").offset().top);
	
	//bind class hovermenu to hover status
	$j( ".menu-primary" ).hover(
  		function() {
    		$j( this ).addClass( "hovermenu" );
  		}, function() {
    		$j( this ).removeClass( "hovermenu" );
  		}
  	);
  	
  	//remove class hovermenu on window scroll
  	$j( window ).scroll(function() {
		$j( ".menu-primary" ).removeClass( "hovermenu" );
	});
	
	//toggle class hovermenu on click
	$j( ".menu-primary" ).click(function() {
    		$j( this ).toggleClass( "hovermenu" );
  	});
  	
  	//bind fixDiv() to window scroll; enables fixed topnav
  	$j(window).scroll(fixDiv);
  	
  	//add active class to items pointing to current subdomain (not counting www)
//   	activateSubdomainNavItems();
	
} //end navInit()


//fixes nav to top screen as user scrolls down, add offset to content
function fixDiv() { 

    var $jdiv = $j("#nav");
	if ($j(window).scrollTop() > $jdiv.data("top")) { 
		$jdiv.addClass('floating-menu');
	}
	else {
		$jdiv.removeClass('floating-menu');
	} //end if ($j(window).scrollTop() > $jdiv.data("top"))
	
} //end fixDiv()

//adds active class to menu items for subdomains when on that subdomain
// function activateSubdomainNavItems() {
// 	var whereAmI = window.URL;
// 	$j('.menu-primary li.menu-item').each(function() {
// 		var thisURL = $j(this).attr('href');
// 		if ( thisURL == whereAmI) {
// 			$j(this).addClass('current-menu-item');
// 		}
// 		else if ( (thisURL.indexOf('www') < 1) && (this.hostname == whereAmI.hostname) ) {
// 			$j(this).addClass('current-page-ancestor');
// 		} //end if($j(this).attr('href') == whereAmI)
// 	}); //end $j('.menu-primary li.menu-item').each(function()
// } //end activateSubdomainNavItems()