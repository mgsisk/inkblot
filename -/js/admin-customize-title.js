/**
 * Handle dynamic title and tagline customization previews.
 * 
 * @package Inkblot
 */
(function($) {
	wp.customize('blogname', function($value) {$value.bind(function($to) {
		$('#header h1').html($to);
	});});
	
	wp.customize('blogdescription', function($value) {$value.bind(function($to) {
		$('#header > a > p').html($to);
	});});
	
	wp.customize('header_textcolor', function($value) {$value.bind(function($to) {
		$('#header h1, #header p').toggle('blank' !== $to);
	});});
})(jQuery);