/**
 * Handle dynamic template options display.
 * 
 * @package Inkblot
 */
jQuery(function($) {
	$('#inkblot-template-options h3').append('<span class="inkblot-template-title"></span>');
	
	$('#page_template').on('change', function() {
		$('[data-inkblot-template-options], [data-inkblot-template-options] h4').hide();
		
		if (-1 !== $.inArray($(this).val(), [
			'page-templates/contributors.php',
			'page-templates/full-width.php',
			'page-templates/webcomic-archive.php',
			'page-templates/webcomic-homepage.php'
		])) {
			$('#inkblot-template-options .inkblot-template-title').text(' - ' + $('[data-inkblot-template-options="' + $(this).val() + '"] h4').text());
			$('[data-inkblot-template-options="' + $(this).val() + '"]').show();
		} else {
			$('#inkblot-template-options h3 .inkblot-template-title').text('');
			$('[data-inkblot-template-options="none"]').show();
		}
	}).trigger('change');
});