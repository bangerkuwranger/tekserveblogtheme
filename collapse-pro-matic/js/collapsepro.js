/*!
 * Collapse-Pro-Matic v0.7
 * http://plugins.twinpictures.de/premium-plugins/collapse-pro-matic/
 *
 * Copyright 2013, Twinpictures
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, blend, trade,
 * bake, hack, scramble, difiburlate, digest and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

function setCookie (cookieName, cookieValue, cookieExpire) {
	wbarDate = new Date();
	wbarDate.setTime(wbarDate.getTime() + cookieExpire);
	wbarExpireDate = wbarDate.toUTCString();
	document.cookie=cookieName + '=' + cookieValue + '; expires='+ wbarDate.toUTCString() +'; path=/';
}

function readCookie(cookieName){
	var results = document.cookie.match(cookieName + '=(.*?)(;|$)')
	if(results){
		return(results[1])
	    }
	else {return null}
}

function collapse_init() {
	//force collapse
	jQuery('.force_content_collapse').each(function(index) {
		jQuery(this).css('display', 'none');
	});
	
	//inital collapse
	jQuery('.collapseomatic:not(.colomat-close)').each(function(index) {
		//check for cookies
		if(jQuery(this).attr('cookie')){
			var cookie = readCookie( jQuery(this).attr('cookie') );
			//if the cookie has been set, mark the item as visited
			if(cookie){
				jQuery(this).addClass('colomat-visited');
			}
			//if the expand element was left open
			if(cookie == 'open'){
				//console.log('keep the collapse open');
				jQuery(this).addClass('colomat-close');
			// 	alert('add-line60');
				return;
			}
		}
		var thisid = jQuery(this).attr('id');
		jQuery('[id^=target][id$='+thisid+']').css('display', 'none');
	});
	
	//inital swaptitle for pre-expanded elements
	jQuery('.collapseomatic.colomat-close').each(function(index) {
		var thisid = jQuery(this).attr('id');
		//check for cookies
		if(jQuery(this).attr('cookie')){
			var cookie = readCookie( jQuery(this).attr('cookie') );
			if(cookie == 'closed'){
				//console.log('close the cookie even though it is default open');
				jQuery(this).removeClass('colomat-close');
			// 	alert('rem-line77');
				jQuery('[id^=target][id$='+thisid+']').css('display', 'none');
				return;
			}
		}
		
		if(jQuery("#swap-"+thisid).length > 0){
			swapTitle(this, "#swap-"+thisid);
		}
		if(jQuery("#swapexcerpt-"+thisid).length > 0){
			swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
		}
	});
}

function swapTitle(origObj, swapObj){
	var orightml = jQuery(origObj).html();
	var swaphtml = jQuery(swapObj).html();
	jQuery(origObj).html(swaphtml);
	jQuery(swapObj).html(orightml);
	
	//swap out the alt, if set
	if (jQuery(swapObj).attr('alt')) {
		if (jQuery(origObj).attr('alt')) {
			var origalt = jQuery(origObj).attr('alt');
		}
		else {
			var origalt = jQuery(origObj).attr('title');
		}
		var swapalt = jQuery(swapObj).attr('alt');
		jQuery(origObj).attr({
			alt: swapalt,
			title: swapalt
		});
		jQuery(swapObj).attr('alt', origalt);
	}
	
	//is cufon involved? if so, do that thing
	if(swaphtml.indexOf("<cufon") != -1){
		var trigelem = jQuery(this).get(0).tagName;
		Cufon.replace(trigelem);
	}
}

function closeOtherGroups(rel){
	jQuery('.collapseomatic[rel!="' + rel +'"]').each(function(index) {
		//add close class if open
		if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
			return;
		}	
		if(jQuery(this).hasClass('colomat-close') && jQuery(this).attr('rel') !== undefined){
			jQuery(this).removeClass('colomat-close');
		// 	alert('rem-line129');
			var id = jQuery(this).attr('id');
			//remove parent highlight class
			jQuery('#parent-'+id).removeClass('colomat-parent-highlight');
			
			//check if the title needs to be swapped out
			if(jQuery("#swap-"+id).length > 0){
				swapTitle(this, "#swap-"+id);
			}
			
			//check if the excerpt needs to be swapped out
			if(jQuery("#swapexcerpt-"+id).length > 0){
				swapTitle("#exerpt-"+id, "#swapexcerpt-"+id);
			}
	
			//slideToggle
			if(colomatslideEffect == 'slideToggle'){
				jQuery('[id^=target][id$='+id+']').slideToggle(colomatduration, function() {
					// Animation complete.
					if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
						jQuery(this).css('display', 'inline');
					}
				});
			}
			//slideFade
			else if(colomatslideEffect == 'slideFade'){
				jQuery('[id^=target][id$='+id+']').animate({
					height: "toggle",
					opacity: "toggle"
				}, colomatduration, function (){
					//Animation complete
					if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
						jQuery(this).css('display', 'inline');
					}
				});
			}
			
			//check if there are nested children that need to be collapsed
			var ancestors = jQuery('.collapseomatic', '#target-'+id);
			ancestors.each(function(index) {
				jQuery(this).removeClass('colomat-close');
			// 	alert('rem-line170');
				var thisid = jQuery(this).attr('id');
				jQuery('#target-'+thisid).css('display', 'none');
			})
		}
	});
}

function closeOtherMembers(rel, id){
	var closedHeight = 0;
	var origPos = (jQuery('#'+id).offset()).top;
	var thisPos;
	jQuery('.collapseomatic[rel="' + rel +'"]').each(function(index) {
		if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
			return;
		}
		
		//add close class if open
		if(jQuery(this).attr('id') != id && jQuery(this).attr('id') != 'extra1-'+id && jQuery(this).hasClass('colomat-close') && jQuery(this).attr('rel') !== undefined){
			//collapse the element
		// 	console.log('cOM-id: '+id);
			jQuery(this).removeClass('colomat-close');
		// 	alert('rem-line188');
			var thisid = jQuery(this).attr('id');
			//remove parent highlight class
			jQuery('#parent-'+thisid).removeClass('colomat-parent-highlight');
			
			//check if the title needs to be swapped out
			if(jQuery("#swap-"+thisid).length > 0){
				swapTitle(this, "#swap-"+thisid);
			}
			
			//check if the excerpt needs to be swapped out
			if(jQuery("#swapexcerpt-"+thisid).length > 0){
				swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
			}
			
			//check for snap-shut
			if(!jQuery(this).hasClass('colomat-close') && jQuery(this).hasClass('snap-shut')){
				jQuery('#target-'+thisid).hide();
			}
			
			//slideToggle
			else if(colomatslideEffect == 'slideToggle'){
				jQuery('[id^=target][id$='+thisid+']').slideToggle(colomatduration, function() {
					// Animation complete.
					if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
						jQuery(this).css('display', 'inline');
					}
				});
			}
			//slideFade
			else if(colomatslideEffect == 'slideFade'){
				jQuery('[id^=target][id$='+thisid+']').animate({
					height: "toggle",
					opacity: "toggle"
				}, colomatduration, function(){
					// Animation complete.
					if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
						jQuery(this).css('display', 'inline');
					}
				});
			}
			console.log(jQuery(this).attr('id'));
			thisPos = (jQuery(this).offset()).top;
			console.log('is '+thisPos+' < '+origPos);
			if(thisPos < origPos) {
				closedHeight = closedHeight + jQuery((jQuery(this).attr('id')).replace('extra1','#target')).height();
			}
			console.log(closedHeight);
			
			//check if there are nested children that need to be collapsed
			var ancestors = jQuery('.collapseomatic', '#target-'+id);
			ancestors.each(function(index) {
				if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
					return;
				}
				//deal with extra tirggers
				var pre_id = id.split('-');
				if (pre_id[0].indexOf('extra') != '-1') {
					//console.log('this is an extra trigger');
					pre = pre_id.splice(0, 1);
					id = pre_id.join('-');
					
					//deal with any scroll to links from the Extra Collapse Trigger
					if(jQuery(this).hasClass('scroll-to-trigger')){
						var target_offset = jQuery('#'+id).offset();
						offset_top = target_offset.top;
					}
					
					//deal with any scroll to links from the Title Trigger
					if(jQuery('#'+id).hasClass('scroll-to-trigger')){
						offset_top = jQuery('#scrollonclose-'+id).attr('name');
					}
					
					//toggle master trigger arrow
					jQuery('#'+id).toggleClass('colomat-close');
				// 	alert('toggle-line256');
					
					//toggle any other extra trigger arrows
					jQuery('[id^=extra][id$='+id+']').toggleClass('colomat-close');
				// 	alert('toggle-line260');
				}
				
				if(jQuery(this).attr('id').indexOf('bot-') == '-1'){
					jQuery(this).removeClass('colomat-close');
				// 	alert('rem-line265');
					var thisid = jQuery(this).attr('id');
					//check if the title needs to be swapped out
					if(jQuery("#swap-"+thisid).length > 0){
						swapTitle(this, "#swap-"+thisid);
					}
					//check if the excerpt needs to be swapped out
					if(jQuery("#swapexcerpt-"+thisid).length > 0){
						swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
					}
					jQuery('#target-'+thisid).css('display', 'none');
				}
			})
		}
	});
	console.log(id);
	scrollToID('#'+id, 260, false, closedHeight);
}

jQuery(document).ready(function() {
	//console.log(colomatduration, colomatslideEffect);
	collapse_init();
	
	//Display the collapse wrapper... use to reverse the show-all on no JavaScript degredation.
	jQuery('.content_collapse_wrapper').each(function(index) {
		jQuery(this).css('display', 'inline');
	});
	
	jQuery(document).on({
		mouseenter: function(){
			//stuff to do on mouseover
			jQuery(this).addClass('colomat-hover');
		},
		mouseleave: function(){
			//stuff to do on mouseleave
			jQuery(this).removeClass('colomat-hover');
		}
	}, '.collapseomatic'); //pass the element as an argument to .on
    
	//the main collapse/expand function
	//jQuery('.collapseomatic').on('click', function(event) {
	jQuery(document).on('click', '.collapseomatic', function(event) {
		var offset_top;
		
		if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
			return;
		}
		
		//deal with cookies
		if(jQuery(this).attr('cookie')){
			status = 'open';
			if(jQuery(this).hasClass('colomat-close')){
				status = 'closed';
			}
			expires = colomatcookielife * 86400000;
			setCookie( jQuery(this).attr('cookie') , status, expires);
		}
		
		var id = jQuery(this).attr('id');
		
		//deal with any scroll to links
		if(jQuery(this).hasClass('colomat-close') && jQuery(this).hasClass('scroll-to-trigger')){
			offset_top = jQuery('#scrollonclose-'+id).attr('name');
		}
		
		//deal with extra tirggers
		var pre_id = id.split('-');
		if (pre_id[0].indexOf('extra') != '-1') {
			//console.log('this is an extra trigger');
			pre = pre_id.splice(0, 1);
			id = pre_id.join('-');
		// 	console.log(id);
			//deal with any scroll to links from the Extra Collapse Trigger
			if(jQuery(this).hasClass('scroll-to-trigger')){
				var target_offset = jQuery('#'+id).offset();
				offset_top = target_offset.top;
			}
			
			//deal with any scroll to links from the Title Trigger
			if(jQuery('#'+id).hasClass('scroll-to-trigger')){
				offset_top = jQuery('#scrollonclose-'+id).attr('name');
			}
			
			//toggle master trigger arrow
			jQuery('#'+id).toggleClass('colomat-close');
		// 	alert('toggle-line349');
			
			//toggle any other extra trigger arrows
			jQuery('[id^=extra][id$='+id+']').toggleClass('colomat-close');
		// 	alert('toggle-line352');
		}
		
		else if(id.indexOf('bot-') != '-1'){
			id = id.substr(4);
			jQuery('#'+id).toggleClass('colomat-close');
		// 	alert('toggle-line358');
			
			//deal with any scroll to links from the Internal Collapse Trigger
			if(jQuery(this).hasClass('scroll-to-trigger')){
				var target_offset = jQuery('#'+id).offset();
				offset_top = target_offset.top;
			}
			
			//deal with any scroll to links from the Title Trigger
			if(jQuery('#'+id).hasClass('scroll-to-trigger')){
				offset_top = jQuery('#scrollonclose-'+id).attr('name');
			}
		}
		else{
			jQuery(this).toggleClass('colomat-close');
		// 	alert('toggle-line373');
			//toggle any extra triggers
			jQuery('[id^=extra][id$='+id+']').toggleClass('colomat-close');
		// 	alert('toggle-line377');
		}
		
		//check if the title needs to be swapped out
		if(jQuery("#swap-"+id).length > 0){
			swapTitle(jQuery('#'+id), "#swap-"+id);
		}
		
		//check if the excerpt needs to be swapped out
		if(jQuery("#swapexcerpt-"+id).length > 0){
			swapTitle("#excerpt-"+id, "#swapexcerpt-"+id);
		}
		
		//add visited class
		jQuery(this).addClass('colomat-visited');
		
		//toggle parent highlight class
		var parentID = 'parent-'+id;
		jQuery('#' + parentID).toggleClass('colomat-parent-highlight');
			
		trig_arr = jQuery(this).attr('id').split('-');
		targ = trig_arr.splice(0, 1);
		trig_id = trig_arr.join('-');
		if (!trig_id) {
			trig_id = id;
		}
		
		//check for snap-shut
		if(!jQuery(this).hasClass('colomat-close') && jQuery(this).hasClass('snap-shut')){
			jQuery('[id^=target][id$='+id+']').hide();
		}
		
		//slideToggle
		else if(colomatslideEffect == 'slideToggle'){
			jQuery('[id^=target][id$='+id+']').removeClass('maptastic');
			jQuery('[id^=target][id$='+id+']').slideToggle(colomatduration, function() {
				// Animation complete.
				if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
					jQuery(this).css('display', 'inline');
				}
				
				if(jQuery('#'+trig_id).hasClass('find-me').hasClass('colomat-close')){
					offset_top = jQuery('#find-'+trig_id).attr('name');
					if(!offset_top){
						target_offset = jQuery('#'+trig_id).offset();
						offset_top = target_offset.top;
					}
					jQuery('html, body').animate({scrollTop:offset_top}, 500);
				}
			});
		}
		//slideFade
		else if(colomatslideEffect == 'slideFade'){
			jQuery('[id^=target][id$='+id+']').removeClass('maptastic');
			
			jQuery('[id^=target][id$='+id+']').animate({
				height: "toggle",
				opacity: "toggle"
			}, colomatduration, function() {
				// Animation complete.
				if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
					jQuery(this).css('display', 'inline');
				}
				
				//deal with any findme links
				if( jQuery('#'+trig_id).hasClass('find-me') && jQuery('#'+trig_id).hasClass('colomat-close') ){
					offset_top = jQuery('#find-'+trig_id).attr('name');
					if(!offset_top){
						target_offset = jQuery('#'+trig_id).offset();
						offset_top = target_offset.top;
					}
					offset_top = offset_top + Number( jQuery('#find-'+trig_id).attr('alt') );
					jQuery('html, body').animate({scrollTop:offset_top}, 500);
				}
			});
		}
        
        //deal with grouped items if needed
        if(jQuery(this).attr('rel') !== undefined){
            var rel = jQuery(this).attr('rel');
			if(rel.indexOf('-highlander') != '-1'){
				closeOtherMembers(rel, id);
			}
			else{
				closeOtherGroups(rel);
			}   
        }
		
		if(offset_top){
			jQuery('html, body').animate({scrollTop:offset_top}, 500);
		}
		
    });
	
	//jQuery('.expandall').on('click', function(event) {
	jQuery(document).on('click', '.expandall', function(event) {
		if(jQuery(this).attr('rel') !== undefined){
			var rel = jQuery(this).attr('rel');
			jQuery('.collapseomatic[rel="' + rel +'"].collapseomatic:not(.colomat-close)').each(function(index) {
					jQuery(this).addClass('colomat-close');
				// 	alert('add-line476');
					var thisid = jQuery(this).attr('id');
					jQuery('#parent-'+thisid).addClass('colomat-parent-highlight');
					
					if(jQuery("#swap-"+thisid).length > 0){
						swapTitle(this, "#swap-"+thisid);
					}
					
					if(jQuery("#swapexcerpt-"+thisid).length > 0){
						swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
					}
					
					//slideToggle
					if(colomatslideEffect == 'slideToggle'){
						jQuery('[id^=target][id$='+thisid+']').slideToggle(colomatduration, function() {
							// Animation complete.
							if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
								jQuery(this).css('display', 'inline');
							}
						});
					}
					//slideFade
					else if(colomatslideEffect == 'slideFade'){
						jQuery('[id^=target][id$='+thisid+']').animate({
							height: "toggle",
							opacity: "toggle"
						}, colomatduration, function(){
							//Animation complete
							if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
								jQuery(this).css('display', 'inline');
							}
						});
					}
			});
	    }
		else{
			jQuery('.collapseomatic:not(.colomat-close)').each(function(index) {
				jQuery(this).addClass('colomat-close');
			// 	alert('add-line514');
				var thisid = jQuery(this).attr('id');
				jQuery('#parent-'+thisid).addClass('colomat-parent-highlight');
				
				if(jQuery("#swap-"+thisid).length > 0){
					swapTitle(this, "#swap-"+thisid);
				}
				
				if(jQuery("#swapexcerpt-"+thisid).length > 0){
					swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
				}
				
				//slideToggle
				if(colomatslideEffect == 'slideToggle'){
					jQuery('[id^=target][id$='+thisid+']').slideToggle(colomatduration, function() {
					// Animation complete.
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
				//slideFade
				else if(colomatslideEffect == 'slideFade'){
					jQuery('[id^=target][id$='+thisid+']').animate({
						height: "toggle",
						opacity: "toggle"
					}, colomatduration, function(){
						//animation complete
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
			});
		}
    });
    
	//jQuery('.collapseall').on('click', function(event) {
	jQuery(document).on('click', '.collapseall', function(event) {
		if(jQuery(this).attr('rel') !== undefined){
			var rel = jQuery(this).attr('rel');
			jQuery('.collapseomatic[rel="' + rel +'"].collapseomatic.colomat-close').each(function(index) {
				if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
					return;
				}
				
				jQuery(this).removeClass('colomat-close');
				var thisid = jQuery(this).attr('id');
				jQuery('#parent-'+thisid).removeClass('colomat-parent-highlight');
				
				if(jQuery("#swap-"+thisid).length > 0){
					swapTitle(this, "#swap-"+thisid);
				}
				
				if(jQuery("#swapexcerpt-"+thisid).length > 0){
					swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
				}
				
				//slideToggle
				if(colomatslideEffect == 'slideToggle'){
					jQuery('[id^=target][id$='+thisid+']').slideToggle(colomatduration, function() {
						// Animation complete
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
				//slideFade
				else if(colomatslideEffect == 'slideFade'){
					jQuery('[id^=target][id$='+thisid+']').animate({
						height: "toggle",
						opacity: "toggle"
					}, colomatduration, function(){
						// Animation complete
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
			});
		}
		else{
			jQuery('.collapseomatic.colomat-close').each(function(index) {
				if(jQuery(this).hasClass('colomat-expand-only') && jQuery(this).hasClass('colomat-close')){
					return;
				}
				jQuery(this).removeClass('colomat-close');
			// 	alert('rem-line601');
				var thisid = jQuery(this).attr('id');
				jQuery('#parent-'+thisid).removeClass('colomat-parent-highlight');
				
				if(jQuery("#swap-"+thisid).length > 0){
					swapTitle(this, "#swap-"+thisid);
				}
				
				if(jQuery("#swapexcerpt-"+thisid).length > 0){
					swapTitle("#excerpt-"+thisid, "#swapexcerpt-"+thisid);
				}
				
				//slideToggle
				if(colomatslideEffect == 'slideToggle'){
					jQuery('[id^=target][id$='+thisid+']').slideToggle(colomatduration, function() {
						// Animation complete.
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
				//slideFade
				else if(colomatslideEffect == 'slideFade'){
					jQuery('[id^=target][id$='+thisid+']').animate({
						height: "toggle",
						opacity: "toggle"
					}, colomatduration, function(){
						// Animation complete
						if( jQuery(this).hasClass('colomat-inline') && jQuery(this).is(':visible') ){
							jQuery(this).css('display', 'inline');
						}
					});
				}
			});
		}
    });
	
	//handle new page loads with anchor
	var myFile = document.location.toString();
    if (myFile.match('#')) { // the URL contains an anchor
        // click the navigation item corresponding to the anchor
        var anchor_arr = myFile.split('#');
		if(anchor_arr.length > 1){
			junk = anchor_arr.splice(0, 1);
			anchor = anchor_arr.join('#');
		}
		else{
			anchor = anchor_arr[0];
		}
		jQuery('#' + anchor).click();
		//expand any nested parents
		jQuery('#' + anchor).parents('.collapseomatic_content').each(function(index) {
			parent_arr = jQuery(this).attr('id').split('-');
			junk = parent_arr.splice(0, 1);
			parent = parent_arr.join('-');
			jQuery('#' + parent).click();
		})
    }
	
	//handle anchor links within the same page
	//jQuery('a.expandanchor').on('click', function(event) {
	jQuery(document).on('click', 'a.expandanchor', function(event) {
		event.preventDefault();
		var fullurl = jQuery(this).attr('href');
		if (fullurl.match('#')) { // the URL contains an anchor
			// click the navigation item corresponding to the anchor
			var anchor_arr = fullurl.split('#');
			if(anchor_arr.length > 1){
				junk = anchor_arr.splice(0, 1);
				anchor = anchor_arr.join('#');
			}
			else{
				anchor = anchor_arr[0];
			}
			if(!jQuery('#' + anchor).hasClass('colomat-close')){
				jQuery('#' + anchor).click();
			}
			
			//expand any nested parents
			jQuery('#' + anchor).parents('.collapseomatic_content').each(function(index) {
				parent_arr = jQuery(this).attr('id').split('-');
				junk = parent_arr.splice(0, 1);
				parent = parent_arr.join('-');
				if(!jQuery('#' + parent).hasClass('colomat-close')){
					jQuery('#' + parent).click();
				}
				
			})
		}
	});
	
	//jQuery('a.colomat-nolink').on('click', function(event) {
	jQuery(document).on('click', 'a.colomat-nolink', function(event) {
		event.preventDefault();
	});	
});