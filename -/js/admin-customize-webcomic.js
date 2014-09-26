/**
 * Handle dynamic title and tagline customization previews.
 * 
 * @package Inkblot
 */
(function($) {
	wp.customize('webcomic_nav_above', function($value) {$value.bind(function($to) {
		$('.post-webcomic nav.above').toggle($to);
	});});
	
	wp.customize('webcomic_nav_below', function($value) {$value.bind(function($to) {
		$('.post-webcomic nav.below').toggle($to);
	});});
})(jQuery);