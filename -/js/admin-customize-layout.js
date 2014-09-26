/**
 * Handle dynamic color customization previews.
 * 
 * @package Inkblot
 */
(function($) {
	/**
	 * Convert hexidecimal colors to RGB values.
	 * 
	 * @param string $hex Hexidecimal value to convert.
	 * @return mixed
	 */
	function inkblot_update_layout() {
		var $width = 100,
			$content = $('wbr.inkblot').data('content');
			$sidebar1 = Number($('wbr.inkblot').data('sidebar1-width')),
			$sidebar2 = Number($('wbr.inkblot').data('sidebar2-width'));
		
		if (-1 !== $content.indexOf('three')) {
			$width -= $sidebar1 + $sidebar2 + 2;
		} else if (-1 !== $content.indexOf('two')) {
			$width -= $sidebar1 + 1;
		}
		
		if ('three-column-center' === $content) {
			$('main ').css({
				'left': $sidebar1 + 1 + '%',
				'position': 'relative'
			});
			$('#sidebar1').css({
				'left': '-' + $width + '%',
				'position': 'relative'
			});
		} else {
			$('main ').css({
				'left': '0',
				'position': 'static'
			});
			$('#sidebar1').css({
				'left': '-' + $width + '%',
				'position': 'static'
			});
		}
		
		$('main').css('width', $width + '%');
		$('#sidebar1').css('width', $sidebar1 + '%');
		$('#sidebar2').css('width', $sidebar2 + '%');
		
		$('body').removeClass('one-column two-column-left two-column-right three-column-left three-column-right three-column-center').addClass($content);
	}
	
	wp.customize('content', function($value) {$value.bind(function($to) {
		$('wbr.inkblot').data('content', $to);
		
		inkblot_update_layout();
	});});
	
	wp.customize('sidebar1_width', function($value) {$value.bind(function($to) {
		$('wbr.inkblot').data('sidebar1-width', $to);
		
		inkblot_update_layout();
	});});
	
	wp.customize('sidebar2_width', function($value) {$value.bind(function($to) {
		$('wbr.inkblot').data('sidebar2-width', $to);
		
		inkblot_update_layout();
	});});
	
	wp.customize('min_width', function($value) {$value.bind(function($to) {
		$('#page, #document > .document-header, #document > .document-footer').css('min-width', 0 < Number($to) ? $to + 'px' : '');
	});});
	
	wp.customize('max_width', function($value) {$value.bind(function($to) {
		$('#page, #document > .document-header, #document > .document-footer').css('max-width', 0 < Number($to) ? $to + 'px' : '');
	});});
})(jQuery);