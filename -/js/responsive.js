/**
 * Responsive menu support.
 * 
 * @package Inkblot
 */
jQuery(function($) {
	$('#header select').on('change', function() {
		if ($(this).val()) {
			window.location.href = $(this).val();
		}
	});
});