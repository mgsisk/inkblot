##
# Handle dynamic template options display.
# 
# @package Inkblot
##

jQuery(($)->
	$templates = [
		'template/contributors.php',
		'template/full-width.php',
		'template/webcomic-archive.php',
		'template/webcomic-homepage.php',
		'template/webcomic-infinite.php'
	]
	
	$('#inkblot-template-options h3').append('<span class="inkblot-template-title"></span>')

	$('#page_template').on('change', ($e)->
		$('[data-inkblot-template-options], [data-inkblot-template-options] h4').hide()

		if -1 != $.inArray($(this).val(), $templates)
			$('#inkblot-template-options .inkblot-template-title').text(' - ' + $('[data-inkblot-template-options="' + $(this).val() + '"] h4').text())
			$('[data-inkblot-template-options="' + $(this).val() + '"]').show()
			$('#inkblot-template-options select[name="inkblot_webcomic_order"]').prop('disabled', true)
			$('[data-inkblot-template-options="' + $(this).val() + '"] select[name="inkblot_webcomic_order"]').prop('disabled', false)
		else
			$('#inkblot-template-options h3 .inkblot-template-title').text('')
			$('[data-inkblot-template-options="none"]').show()
	).trigger('change')
)