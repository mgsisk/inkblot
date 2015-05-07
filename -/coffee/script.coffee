##
# Handle theme-specific functionality.
# 
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.banner nav a').on('focus', (e)->
		$(this)
			.parents().addClass('open')
			.siblings().removeClass('open').closest('nav')
	)
	
	$('.banner nav a').on('blur', (e)->
		$(this).closest('nav').find('li').removeClass('open')
	)
	
	$('.banner nav select').on('change', (e)->
		if $(this).val()
			window.location.href = $(this).val()
	)
)