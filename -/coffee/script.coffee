##
# Handle theme-specific functionality.
#
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
		$(this)
	$('.banner nav a').on('focus', ->
			.parentsUntil('.menu').addClass('open')
			.siblings().removeClass('open')
	)
	
		$(this).parentsUntil('.menu').removeClass('open')
	$('.banner nav a').on('blur', ->
	)
	
		if $(this).val()
			window.location.href = $(this).val()
	$('.banner nav select').on('change', ->
	)
)