var $j = jQuery;

$j('document').ready( function() {

	$j('.sub-menu').css({'left': -9999, 'opacity': 0});

}); //end $j('document').ready( function()

$j(window).bind("load",function() {
	
	$j('#nav .current-page-ancestor.menu-item-has-children .sub-menu, #nav .current-menu-item.menu-item-has-children .sub-menu').animate( {'left': 0, 'opacity': 1}, 250 );
	$j('#nav .current-page-ancestor.menu-item-has-children, #nav .current-menu-item.menu-item-has-children').addClass('current-submenu');
	
	$j('#nav .menu-item-has-children').click( function(e) {
	
		if (!$j(this).hasClass('current-submenu')) {
			e.preventDefault();
			getSubmenu(this);
		}
	
	}); //end $j('#nav .menu-item-has-children').click( function(e)

}); //end $j(window).bind("load",function()

function getSubmenu(newMenu) {
	
	$j('.current-submenu .sub-menu').animate( {'left': '9999px', 'opacity': 0}, 250 );
	setTimeout( function() { 
	
	$j('.current-submenu .sub-menu').css( 'left', '-9999px' );
	$j('.current-submenu').removeClass('current-submenu');
	$j(newMenu).addClass('current-submenu');
	$j('.current-submenu .sub-menu').animate( {'left': 0, 'opacity': 1}, 250 );
	
	}, 260 ); //end setTimeout( function()
	
	
	

} //end function getSubmenu($newMenu)