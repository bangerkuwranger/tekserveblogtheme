/*
	noconflict declaration
*/

var $j = jQuery;

/*
	icaps - alters apple trademarks to correct capitalization. sigh.
*/

$j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function () {
	var my_html = $j(this).html();
	var my_old_html = $j(this).html();

	// Make sure there are no children so we don't edit more than we want.
	if ($j(this).children().length == 0) {
		my_html = my_html.replace(/(AirDrop|AirMac|AirPlay|AirPort Express|AirPort Extreme|AirPort Time Capsule|AirTunes|Aperture|Apple|Apple Cinema Display|AppleScript|AppleScript Studio|AppleShare|AppleTalk|Apple TV|AppleWorks|App Nap|Aqua|Back to My Mac|Bonjour|Boot Camp|Claris|ColorSync|Cover Flow|Dashcode|DVD Studio Pro|Exposé|FaceTime|FairPlay|FileVault|Final Cut Pro|Finder|FireWire|GarageBand|iBook|iBooks|iCal|iChat|iDVD|iLife|iMac|iMessage|iMovie|Inkwell|iPad|iPhone|iPhoto|iPod classic|iPod nano|iPod shuffle|iPod Socks|iPod touch|iSight|iTunes|iWork|Keychain|Keynote|LiveType|MacApp|MacBook Air|MacBook Pro|MacDNS|Macintosh|Mac OS|Mac Pro|MacTCP|MagSafe|MainStage|Mission Control|Objective-C|OpenCL|OpenPlay|OS X|Passbook|Photo Booth|Pixlet|PowerBook|Power Mac|Quartz|QuickDraw|QuickTime|Retina|Rosetta|Safari|Sherlock|Siri|Smart Cover|SuperDrive|Time Capsule|Time Machine|TrueType|WaveBurner|WebObjects|Xcode|Xgrid|Xsan|Xserve)/ig, '<span class="rtrademark">$1</span>');
		my_html = my_html.replace(/(AirPrint|Apple CarPlay|AppleLink|Apple Pay|Apple Remote Desktop|Apple Studio Display|AppleVision|Apple Watch|Cinema Tools|DVD@CCESS|EarPods|eMac|EtherTalk|Flyover|iBeacon|ImageWriter|iPad Air|iPad mini|iWeb|LaserWriter|Lightning|LocalTalk|Multi-Touch|NetInfo|Newton|OfflineRT|Photocasting|ProDOS|SnapBack|StyleWriter|TestFlight|Touch ID)/ig, '<span class="trademark">$1</span>');
		my_html = my_html.replace(/(iNSIDER)/ig, '<span class="insider">$1</span>');
		$j(this).html(my_html);
	}
}); //end $j('h1, h2, h3, h1 a, h2 a, h3 a, .detailBoxTrigger, .drawertrigger, .tekserve-case-study-cta').each(function ()