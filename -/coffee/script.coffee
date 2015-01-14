##
# 
# 
# @package Inkblot
##

document.documentElement.className = document.documentElement.className.replace(/no-js/, 'js')

jQuery(($)->
	$('.responsive .banner select').on('change', ($e)->
		if $(this).val()
			window.location.href = $(this).val()
	)
)