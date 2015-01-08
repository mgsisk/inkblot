##
# Handle webcomic-specific customization options.
# 
# @package Inkblot
##

(($)->
	wp.customize('webcomic_nav_above', ($value)->
		$value.bind(($to)->
			$('.post-webcomic nav.above').toggle($to)
		)
	)
	
	wp.customize('webcomic_nav_below', ($value)->
		$value.bind(($to)->
			$('.post-webcomic nav.below').toggle($to)
		)
	)
)(jQuery)
