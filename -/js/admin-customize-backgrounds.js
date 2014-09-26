/**
 * Handle dynamic title and tagline customization previews.
 * 
 * @package Inkblot
 */
(function($) {
	wp.customize('page_background_image', function($value) {$value.bind(function($to) {
		$('#page').css('background-image', $to ? 'url(' + $to + ')' : 'none');
	});});
	
	wp.customize('page_background_repeat', function($value) {$value.bind(function($to) {
		$('#page').css('background-repeat', $to);
	});});
	
	wp.customize('page_background_position_x', function($value) {$value.bind(function($to) {
		$('#page').css('background-position', 'top ' + $to);
	});});
	
	wp.customize('page_background_attachment', function($value) {$value.bind(function($to) {
		$('#page').css('background-attachment', $to);
	});});
	
	wp.customize('trim_background_image', function($value) {$value.bind(function($to) {
		$('#header nav, #header select, #header nav ul ul, #footer, .post-webcomic nav').css('background-image', $to ? 'url(' + $to + ')' : 'none');
	});});
	
	wp.customize('trim_background_repeat', function($value) {$value.bind(function($to) {
		$('#header nav, #header select, #header nav ul ul, #footer, .post-webcomic nav').css('background-repeat', $to);
	});});
	
	wp.customize('trim_background_position_x', function($value) {$value.bind(function($to) {
		$('#header nav, #header select, #header nav ul ul, #footer, .post-webcomic nav').css('background-position', 'top ' + $to);
	});});
	
	wp.customize('trim_background_attachment', function($value) {$value.bind(function($to) {
		$('#header nav, #header select, #header nav ul ul, #footer, .post-webcomic nav').css('background-attachment', $to);
	});});
})(jQuery);