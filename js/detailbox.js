function detailBoxBinder() {
	
	//jQuery noconflict
	
	var $j = jQuery;

	//detailbox slide
	$j('.detailBoxTrigger').click(function() {
			var thistitle = $j(this).html();
			var leftheight = $j(this).parents('.detailBox').find('.detailBox-left').height();
			$j(this).parents('.detailBox').find('.detailBox-mobile-title').html(thistitle);
// 			$j(this).parents('.detailBox').addClass('shifted');
			
			$j(this).parent().addClass('active');
			var $jothertrigs = $j(this).parent().siblings('.active');
			$j($jothertrigs).removeClass('active');
			var targetid = $j(this).attr('id');
			targetid = targetid.replace('_trigger', '');
			var $jtarget = $j('#'+targetid);
			var $jother = $j('#'+targetid).siblings('.active');
			$j(this).parents('.detailBox').find('.detailBox-right').addClass('active');
			if (!$jtarget.hasClass('active')) {
				$jother.each(function(index, self) {
					var $jthis = $j(this);
					$jthis.removeClass('active').animate({
						left: $jthis.width()
					}, 300).hide(50);
				});
				if (leftheight > 300) {
					$jtarget.css('height',leftheight+'px');
					$jtarget.parents('.detailBox-right').css('height',leftheight+'px');
				}
				$jtarget.addClass('active').show().css({
					left: -($jtarget.width())
				}).animate({
					left: 0
				}, 500);
			}
			$j(this).parents('.detailBox').addClass('shifted', 500, 'easeOutBounce');
		});
	
	//detailBox mobile back button
	$j('.detailBox-mobile').click(function() {
		$j(this).parents('.detailBox').removeClass('shifted', 500, 'easeOutBounce');
	});
}