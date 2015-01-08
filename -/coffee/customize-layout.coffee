##
# Handle dynamic color customization previews.
# 
# @package Inkblot
##

(($)->
	##
	# Update layout dimensions.
	# 
	# @return null
	##
	inkblot_update_layout = ()->
		$width = 100
		$content = $('wbr.inkblot').data('content')
		$sidebar1 = Number($('wbr.inkblot').data('sidebar1-width'))
		$sidebar2 = Number($('wbr.inkblot').data('sidebar2-width'))
		
		if -1 != $content.indexOf('three')
			$width -= $sidebar1 + $sidebar2 + 2
		else if -1 != $content.indexOf('two')
			$width -= $sidebar1 + 1
		
		if 'three-column-center' == $content
			$('main ').css(
				left: $sidebar1 + 1 + '%'
				position: 'relative'
			)
			
			$('#sidebar1').css(
				left: '-' + $width + '%'
				position: 'relative'
			)
		else
			$('main ').css(
				left: '0'
				position: 'static'
			)
			
			$('#sidebar1').css(
				left: '-' + $width + '%'
				position: 'static'
			)
		
		$('main').css('width', $width + '%')
		$('#sidebar1').css('width', $sidebar1 + '%')
		$('#sidebar2').css('width', $sidebar2 + '%')
		
		$('body').removeClass('one-column two-column-left two-column-right three-column-left three-column-right three-column-center').addClass($content)
	
	wp.customize('content', ($value)->
		$value.bind(($to)->
			$('wbr.inkblot').data('content', $to)
			
			inkblot_update_layout()
		)
	)
	
	wp.customize('sidebar1_width', ($value)->
		$value.bind(($to)->
			$('wbr.inkblot').data('sidebar1-width', $to)
			
			inkblot_update_layout()
		)
	)
	
	wp.customize('sidebar2_width', ($value)->
		$value.bind(($to)->
			$('wbr.inkblot').data('sidebar2-width', $to)
			
			inkblot_update_layout()
		)
	)
	
	wp.customize('min_width', ($value)->
		$value.bind(($to)->
			$('#page, #document > .document-header, #document > .document-footer').css('min-width', if 0 < Number($to) then $to + 'px' else '')
		)
	)
	
	wp.customize('max_width', ($value)->
		$value.bind(($to)->
			$('#page, #document > .document-header, #document > .document-footer').css('max-width', if 0 < Number($to) then $to + 'px' else '')
		)
	)
)(jQuery)
