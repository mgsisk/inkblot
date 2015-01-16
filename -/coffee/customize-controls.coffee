##
# Handle unique customization controls.
# 
# @package Inkblot
##

(($)->
	##
	# Toggle control visibility.
	# 
	# @param string $settingId
	# @param object $o
	# @return void
	##
	inkblot_toggle_controls = ($settingId, $o)->
		wp.customize($settingId, ($setting)->
			$.each($o.controls, ($i, $controlId)->
				wp.customize.control($controlId, ($control)->
					$visibility = ($to)->
						$control.container.toggle($o.callback($to))
					
					$visibility($setting.get())
					$setting.bind($visibility)
				)
			)
		)
	
	$.each(
		content:
			controls: ['sidebar1_width']
			callback: ($to)->
				return 'one-column' != $to
		page_background_image:
			controls: ['page_background_repeat', 'page_background_position_x', 'page_background_attachment']
			callback: ($to)->
				return !! $to
		trim_background_image:
			controls: ['trim_background_repeat', 'trim_background_position_x', 'trim_background_attachment']
			callback: ($to)->
				return !! $to
	, ($settingId, $o)->
		inkblot_toggle_controls($settingId, $o)
	)
	
	$.each(
		content:
			controls: ['sidebar2_width']
			callback: ($to)->
				return (-1 != $to.indexOf('three'))
	, ($settingId, $o)->
		inkblot_toggle_controls($settingId, $o)
	)
)(jQuery)