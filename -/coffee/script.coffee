##
# Handle theme-specific functionality.
#
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.banner nav a').on('focus', ->
		$(@)
			.parentsUntil('.menu').addClass('open')
			.siblings().removeClass('open')
	)
	
	$('.banner nav a').on('blur', ->
		$(@).parentsUntil('.menu').removeClass('open')
	)
	
	$('.banner nav select').on('change', ->
		if $(@).val()
			window.location.href = $(@).val()
	)
)