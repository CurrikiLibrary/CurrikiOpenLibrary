(function( $ ){
	$.fn.llp_box_center = function() {
		this.css({
			position:'fixed',
			left: ($(window).width() - this.outerWidth())/2,
			top: ($(window).height() - this.outerHeight())/2
		});
	};
	$.fn.llp_box = function(options) {
		
		var settings = $.extend({
			width : '400px'
		}, options);

		var _this = this;
		var llp_box_c = '';
		llp_box_c += '<div style="display:none;" class="llp_box-overlay llp_box-overlay-fixed"></div>';
		llp_box_c += '<div style="display:none;" class="llp_box-wrap llp_box-desktop llp_box-type-inline llp_box-opened">';
		llp_box_c += '<div class="llp_box-skin" style="padding: 15px; width: auto; height: auto;">';
		  llp_box_c += '<div class="llp_box-outer">';
			llp_box_c += '<div class="llp_box-inner">';
			llp_box_c += '</div>';
		  llp_box_c += '</div>';
		  llp_box_c += '<a href="javascript:void(0);" class="llp_box-item llp_box-close" title="Close"></a></div>';
		llp_box_c += '</div>';
		  
		$('body').append(llp_box_c);
		var llp_box_overlay = $('.llp_box-overlay');
		var llp_box_close = $('.llp_box-close');
		var llp_box_wrap = $('.llp_box-wrap');
		var llp_box_inner = $('.llp_box-inner');
		var cache_style = '';
		
		this.on('click',function(){
			if(_this.attr('href').indexOf('#') != -1 && _this.attr('href').length > 1){
				var llp_content_target = _this.attr('href').replace('#','');
				var llp_content = $('#'+llp_content_target);
				llp_content.wrap('<div class="llp_placeholder"></div>');
				
				llp_content.appendTo(llp_box_inner);
				cache_style = llp_box_inner.children().attr('style');
				llp_box_inner.children().removeAttr('style');
				llp_box_inner.css('max-width',settings.width);
				$('.llp_placeholder').html('');
				llp_box_overlay.fadeIn();
				llp_box_wrap.fadeIn();
				llp_box_wrap.llp_box_center();
				$(window).resize(function(){
					llp_box_wrap.llp_box_center();	
				});
				return false;
			}
		});
		
		llp_box_overlay.on('click',function(){
			llp_box_overlay.fadeOut();
			llp_box_wrap.fadeOut(function(){
				llp_box_inner.children().attr('style',cache_style).appendTo($('.llp_placeholder'));
				llp_box_inner.html('');	
			});
			return false;
		});
		
		llp_box_close.on('click',function(){
			llp_box_overlay.fadeOut();
			llp_box_wrap.fadeOut(function(){
				llp_box_inner.children().attr('style',cache_style).appendTo($('.llp_placeholder'));
				llp_box_inner.html('');	
			});
			return false;
		});
	}; 
})( jQuery );