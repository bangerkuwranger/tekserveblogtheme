/******
	noconflict declaration, global vars
******/

var $j = jQuery;
var currentWidth;
var prevWidth;
var theSearchBox;
var mV = 500;
var mH = 626;
var tV = 770;
var tH = 1024;
var dR = 1200;
var sT = 945;
$j('body').data('hasRun', false);

/******
	single function to call on width change; delegates to specific breakpoint functions
******/

function rearrangeContent(w) {

	//set current width
	prevWidth = currentWidth;
	currentWidth = w;


	//breakpoint resets... undo as we move back. Only runs after first time called per page.
	if ( $j('body').data('hasRun') ) {
	
		if (w >= tH) {
			untabletH();
			untabletV();
			unmobileH();
			unmobileV();
		}
		else if (w >= tV) {
			untabletV();
			unmobileH();
			unmobileV();
		}
		else if (w >= mH) {
			unmobileH();
			unmobileV();
		}
		else if (w >= mV) {
			unmobileV();
		} //end if(w>...)
	} 
	else {
		prevWidth = 1200;
	} //end if($j('body').data('hasRun'))
	
	
	//breakpoints for devices... typically specific content moves
	if (w < mV) {
		mobileV();
		mobileH();
		tabletV();
		tabletH();
	}
	else if (w < mH) {
		mobileH();
		tabletV();
		tabletH();
	}
	else if (w < tV) {
		tabletV();
		tabletH();
	}
	else if (w < tH) {
		tabletH();
	}
	else {
		desktopR();
	} //end if(w<...)
	
	
	
	//breakpoint for grid stacking... typically for weird hacks. *shudder*
	if (w < sT) {
		stackEm();
	}
	else {
		unstackEm();
	}
	
	
	//set flag after first run to run reset functions
	$j('body').data('hasRun', true);
	
} //end rearrangeContent(w)

/******
	delegate functions for specific breakpoints
******/
		
function mobileV() {
	if (prevWidth >= mV) {
	
	} //end if(prevWidth >= mV)
} //end mobileV()

function unmobileV() {
	if (prevWidth < mV) {
		
	} //endif(prevWidth < mV) {
} //end unmobileV()


function mobileH() {
	if (prevWidth >= mH) {
	
		moveSearchMobile();
		shutMobileDrawers();
		
	} //end if(prevWidth >= mH) {
} //end mobileH()

function unmobileH() {
	if (prevWidth < mH) {
	
		moveSearchBack();
		
	} //end if(prevWidth < mH) {
} //end unmobileH()


function tabletV() {
	if (prevWidth >= tV) {
	
		swapHeaderImgs();
		
	} //end if(prevWidth >= tV)
} //end tabletV()

function untabletV() {
	if (prevWidth < tV) {
	
		swapHeaderImgs();
		
	} //endif(prevWidth < tV) {
} //end untabletV()


function tabletH() {
	if (prevWidth >= tH) {
	
		mobileMeta();
		
	} //end if(prevWidth >= tH)
} //end tabletH()

function untabletH() {
	if (prevWidth < tH) {
		
		remMobileMeta();
		
	} //endif(prevWidth < tH) {
} //end untabletH()


function desktopR() {

} //end desktopR()


function stackEm() {

	stackFullHeight();

} //end stackEm()


function unstackEm() {

	changeFullHeight();

} //end unstackEm()

/******
	functions for specific actions, called by breakpoint functions
******/

//moves search outside of nav node; called on page load and window resize
function moveSearchMobile() {  

		$j('.right.search').insertBefore('#inner .wrap').wrap("<div class='innerRowWrap' />");
		
} //end moveSearchMobile()

//moves search back into nav node; called on window resize
function moveSearchBack() {

	$j('.right.search').unwrap().insertAfter('#nav div ul li:last-child');
	
} //end moveSearchBack()

//swaps programmatically added mobile header image url with existing header image url.
//only runs if plugin tekserve-shared-data has created it. Requires a page width.
function swapHeaderImgs() {

	if ($j('#tekserve-shared-data-hours-swap').length != 0) {
	
		var $header = $j('#wrap #header .wrap #title-area');
		var firstImg = $header.css('backgroundImage');
		var $swap = $j('#tekserve-shared-data-hours-swap');
		var secondImg = $swap.html();
		if (clientWidth > tV && $header.hasClass('mobile')) {
		
			$header.css('backgroundImage', secondImg).removeClass('mobile');
			$swap.html(firstImg);
			
		} //end if (clientWidth > tV && $header.hasClass('mobile'))
		if (clientWidth <= tV && !($header.hasClass('mobile'))) {
		
			$header.css('backgroundImage', secondImg).addClass('mobile');
			$swap.html(firstImg);
			
		} //end if (clientWidth <= tV && !($header.hasClass('mobile')))
		
	} //end if($j('#tekserve-shared-data-hours-swap').length != 0)
	
} //end swapHeaderImgs()

//keep drawers closed on first load if mobile
function shutMobileDrawers() {

	if ( !($j('body').data('hasRun')) ) {
		$j('.collapseomatic').removeClass('colomat-close'); //call on load if window size < 640
		
		$j('.collapseomatic_content').css('display', 'none');
		
	} //end if ( !($j('body').data('hasRun')) )
	
} //end shutMobileDrawers()

//add web-app meta to head
function mobileMeta() {

	$j('head').append('<meta id="apple-mobile-web-app-capable" name="apple-mobile-web-app-capable" content="yes">');
	
	$j('head').append('<meta id="apple-mobile-web-app-status-bar-style" name="apple-mobile-web-app-status-bar-style" content="black">');
	
} //end mobileMeta()

//remove web-app meta from head
function remMobileMeta() {

	$j('#apple-mobile-web-app-capable, #apple-mobile-web-app-status-bar-style').remove();
	
} //end remMobileMeta()

//adjust height of unstacked .fullHeight elements to match that of parent .vc_row
function changeFullHeight() {

	$j('.fullHeight').each(function() {
	
		var rowHeight = $j(this).parents('.vc_row').css('height');
		$j(this).css('height', rowHeight);
	
	}); //end $j('.fullHeight').each(function()

} //end changeFullHeight()

//set .fullHeight heights to auto when stacked
function stackFullHeight() {

	$j('.fullHeight').css('height', 'auto');

} //end stackFullHeight()