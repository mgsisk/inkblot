##
# Handle dynamic style previews.
# 
# @package Inkblot
##

(($)->
	wp.customize('css', ($value)->
		$value.bind(($to)->
			$('head style.inkblot-custom').remove();
			
			if $to
				$('head').append('<style class="inkblot-custom">' + $to + '</style>');
		)
	)
)(jQuery)
