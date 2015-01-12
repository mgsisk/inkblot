##
# Handle dynamic title and tagline customization previews.
# 
# @package Inkblot
##

(($)->
	wp.customize('page_background_image', ($value)->
		$value.bind(($to)->
			$('.wrapper').css('background-image', if $to then 'url(' + $to + ')' else 'none')
		)
	)
	
	wp.customize('page_background_repeat', ($value)->
		$value.bind(($to)->
			$('.wrapper').css('background-repeat', $to)
		)
	)
	
	wp.customize('page_background_position_x', ($value)->
		$value.bind(($to)->
			$('.wrapper').css('background-position', 'top ' + $to)
		)
	)
	
	wp.customize('page_background_attachment', ($value)->
		$value.bind(($to)->
			$('.wrapper').css('background-attachment', $to)
		)
	)
	
	wp.customize('trim_background_image', ($value)->
		$value.bind(($to)->
			$('.banner nav, .banner select, .banner nav ul ul, .contentinfo, .post-webcomic nav').css('background-image', if $to then 'url(' + $to + ')' else 'none')
		)
	)
	
	wp.customize('trim_background_repeat', ($value)->
		$value.bind(($to)->
			$('.banner nav, .banner select, .banner nav ul ul, .contentinfo, .post-webcomic nav').css('background-repeat', $to)
		)
	)
	
	wp.customize('trim_background_position_x', ($value)->
		$value.bind(($to)->
			$('.banner nav, .banner select, .banner nav ul ul, .contentinfo, .post-webcomic nav').css('background-position', 'top ' + $to)
		)
	)
	
	wp.customize('trim_background_attachment', ($value)->
		$value.bind(($to)->
			$('.banner nav, .banner select, .banner nav ul ul, .contentinfo, .post-webcomic nav').css('background-attachment', $to)
		)
	)
)(jQuery)
