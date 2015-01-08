##
# Handle site title and description options.
# 
# @package Inkblot
##

(($)->
	wp.customize('blogname', ($value)-> 
		$value.bind(($to)->
			$('#header h1').html($to)
		)
	)
	
	wp.customize('blogdescription', ($value)->
		$value.bind(($to)->
			$('#header > a > p').html($to)
		)
	)
	
	wp.customize('header_textcolor', ($value)->
		$value.bind(($to)->
			$('#header h1, #header p').toggle('blank' != $to)
		)
	)
)(jQuery)
