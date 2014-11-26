var $j = jQuery;
$j(window).bind("load",function() {

// 	alert('experimental menus!');
	$j('#nav .current-page-ancestor.menu-item-has-children, #nav .current-menu-item.menu-item-has-children').addClass('current-submenu');
	$j('#nav .menu-item-has-children').click( function(e) {
	
		if (!$j(this).hasClass('current-submenu')) {
			e.preventDefault();
// 			alert('nope!!');
			getSubmenu(this);
		}
	
	}); //end $j('#nav .menu-item-has-children').click( function(e)

}); //end $j(window).bind("load",function()

function getSubmenu(newMenu) {

	$j('.current-submenu').removeClass('current-submenu');
	$j(newMenu).addClass('current-submenu');
	

} //end function getSubmenu($newMenu)