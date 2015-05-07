##
# Handle theme-specific functionality.
# 
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.banner nav a').on('focus', (e)->
		$(this)
			.parentsUntil('.menu').addClass('open')
			.siblings().removeClass('open')
	)
	
	$('.banner nav a').on('blur', (e)->
		$(this).parentsUntil('.menu').removeClass('open')
	)
	
	$('.banner nav select').on('change', (e)->
		if $(this).val()
			window.location.href = $(this).val()
	)
)