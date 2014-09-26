/**
 * Handle dynamic font customization previews.
 * 
 * @package Inkblot
 */
(function($) {
	function inkblot_font($to, $selectors) {
		if ('' === $to) {
			$($selectors).css('font-family', 'inherit');
		} else {
			$('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + $to + '">');
			$($selectors).css('font-family', $to.replace(/\+/g, ' ').substr(0, $to.indexOf(':')));
		}
	}
	wp.customize('font_size', function($value) {$value.bind(function($to) {
		$('body').css('font-size', $to + '%');
	});});
	
	wp.customize('font', function($value) {$value.bind(function($to) {
		inkblot_font($to, 'body');
	});});
	
	wp.customize('header_font', function($value) {$value.bind(function($to) {
		inkblot_font($to, '#header > a');
	});});
	
	wp.customize('page_font', function($value) {$value.bind(function($to) {
		inkblot_font($to, '#page');
	});});
	
	wp.customize('title_font', function($value) {$value.bind(function($to) {
		inkblot_font($to, 'h1:not(#header h1), h2, h3, h4, h5, h6');
	});});
	
	wp.customize('trim_font', function($value) {$value.bind(function($to) {
		inkblot_font($to, '#header nav, #header select, #footer, .post-webcomic nav');
	});});
})(jQuery);