##
# Handle theme-specific functionality.
# 
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.banner nav a').on('focus', (e)->
		$(this)
			.closest('nav').find('li').removeClass('open')
			.end().end()
			.parents().addClass('open')
			.siblings().removeClass('open').closest('nav')
	)
	
	$('.banner nav select').on('change', (e)->
		if $(this).val()
			window.location.href = $(this).val()
	)
)