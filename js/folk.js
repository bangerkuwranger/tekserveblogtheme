/******
	noconflict declaration
******/

var $j = jQuery;

/******
	load footer folk and logos above footer
******/

function initFooterFolk() {

	insertFooterFolk();
	insertLogoFoursquare();
	insertLogoZagat();
	insertLogoYelp();
	insertLogoBBB();
	$j('#footer-folk').slideDown();

} //end initFooterFolk()

/******
	yelp logo
******/

function insertLogoYelp() {

	!function(doc, id){
	  var js;
	  var scriptElement = doc.getElementsByTagName("script")[0];
	  if (!doc.getElementById(id)) {
		js = doc.createElement("script");
		js.id = id;
		js.src = "//dyn.yelpcdn.com/biz_badge_js/rrc/T-yDGKZZA71nkGQoPQCCng.js";
		scriptElement.parentNode.insertBefore(js, scriptElement);
	  }
	} (document, "yelp-biz-badge-script-rrc-T-yDGKZZA71nkGQoPQCCng");
	
} //end insertLogoYelp()

/******
	bbb logo
******/

function insertLogoBBB() {

	var bbb = '<a target="_blank" title="Tekserve Corp. BBB Business Review" href="http://www.bbb.org/new-york-city/business-reviews/computers-service-and-repair/tekserve-corp-in-new-york-ny-23092/#bbbonlineclick"><img alt="Tekserve Corp. BBB Business Review" style="border: 0;" src="http://seal-newyork.bbb.org/seals/blue-seal-96-50-tekserve-corp-23092.png" /></a>';
	$j('#bbblogo').html(bbb);
	
} //end insertLogoBBB()

/******
	zagat logo
******/

function insertLogoZagat() {

	var zagat = '<a href="http://www.zagat.com/s/tekserve-new-york"><img src="' + zagaturl + '" /></a>';
	$j('#zagatrating').html(zagat);
	
} //end insertLogoZagat()

/******
	foursquare logo
******/

function insertLogoFoursquare() {

	var foursquare = '<a href="https://foursquare.com/v/tekserve-new-york-ny/422f8e00f964a520f81f1fe3"><img src="' + foursquareurl + '" class="foursquare" /><span style="margin: 0; padding: 0; border: 0; font-size: 100%; font: inherit; vertical-align: baseline; color: #fff; cursor: default; display: inline-block; font-size: 12px; font-weight: bold; padding: 5px 0; text-align: center; text-shadow: rgba(0, 0, 0, 0.1) 0 -1px 0; width: 30px; -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; font-size: 17px; float: none; line-height: 32px; margin-right: 23px; padding: 0 10px 0 20px; width: 57px; background: #69bf13; position: relative; top: -15px;"><span itemprop="ratingValue">8.9</span><sup>/<span itemprop="bestRating">10</span></sup></span></a>';
	$j('#foursquare').html(foursquare);
	
} //end insertLogoFoursquare()

/******
	footerfolk
******/

function insertFooterFolk() {

	// var folk = '<img src="' + folkurl + '" />';
// 	$j('#footerfolk').html(folk);

	return false;
	
} //end insertFooterFolk()