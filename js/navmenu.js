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
	
} //end navInit()