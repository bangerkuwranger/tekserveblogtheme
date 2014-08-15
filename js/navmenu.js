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
	
} //end navInit()

function fixDiv() { //fixes nav to top screen as user scrolls down, add offset to content
    var $jdiv = $j("#nav");
	if ($j(window).scrollTop() > $jdiv.data("top")) { 
		$jdiv.addClass('floating-menu');
// 		var offset = parseInt( $jdiv.outerHeight(true) ) + parseInt( $j('#header').outerHeight(true) ) + parseInt( $j('#subnav').outerHeight(true) );
// 		console.log(offset);
// 		$j('#inner').css('margin-top', offset+'px');
		
	}
	else {
		$jdiv.removeClass('floating-menu');
// 		$j('#inner').css('margin-top', 0);
	} //end if ($j(window).scrollTop() > $jdiv.data("top"))
} //end fixDiv()