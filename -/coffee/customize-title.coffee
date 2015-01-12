##
# Handle site title and description options.
# 
# @package Inkblot
##

(($)->
	wp.customize('blogname', ($value)-> 
		$value.bind(($to)->
			$('.banner h1').html($to)
		)
	)
	
	wp.customize('blogdescription', ($value)->
		$value.bind(($to)->
			$('.banner > a > p').html($to)
		)
	)
	
	wp.customize('header_textcolor', ($value)->
		$value.bind(($to)->
			$('.banner h1, .banner p').toggle('blank' != $to)
		)
	)
)(jQuery)
