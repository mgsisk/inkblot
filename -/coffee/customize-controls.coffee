##
# Handle unique customization controls.
# 
# @package Inkblot
##

(($)->
	##
	# Toggle control visibility.
	# 
	# @param string settingId
	# @param object o
	##
	inkblot_toggle_controls = (settingId, object)->
		wp.customize(settingId, (setting)->
			$.each(object.controls, (index, controlId)->
				wp.customize.control(controlId, (control)->
					visibility = (to)->
						control.container.toggle(object.callback(to))
					
					visibility(setting.get())
					setting.bind(visibility)
				)
			)
		)
	
	$.each(
		content:
			controls: ['sidebar1_width']
			callback: (to)->
				return 'one-column' != to
		page_background_image:
			controls: ['page_background_repeat', 'page_background_position_x', 'page_background_attachment']
			callback: (to)->
				return !! to
		trim_background_image:
			controls: ['trim_background_repeat', 'trim_background_position_x', 'trim_background_attachment']
			callback: (to)->
				return !! to
	, (settingId, object)->
		inkblot_toggle_controls(settingId, object)
	)
	
	$.each(
		content:
			controls: ['sidebar2_width']
			callback: (to)->
				return (-1 != to.indexOf('three') or -1 != to.indexOf('four'))
	, (settingId, object)->
		inkblot_toggle_controls(settingId, object)
	)
	
	$.each(
		content:
			controls: ['sidebar3_width']
			callback: (to)->
				return (-1 != to.indexOf('four'))
	, (settingId, object)->
		inkblot_toggle_controls(settingId, object)
	)
	
	##
	# Update options on scheme change.
	##
	$(document).on('change', '#customize-control-scheme input[name="_customize-radio-scheme"]', (event)->
		$.each($('wbr.inkblot-scheme.' + $(this).val()).data(), (index, value)->
			index = index.replace(/([A-Z])/g, (string)->
				return '_' + string.toLowerCase()
			)
			
			if undefined != wp.customize(index)
				wp.customize(index).set(value)
				
				if -1 < index.indexOf('color')
					wp.customize.control(index).container.find('.color-picker-hex')
						.data('data-default-color', value)
						.wpColorPicker('defaultColor', value)
		)
	)
	
	# @todo remove this ugly kludge
	
	$(document).on('click', (event)->
		$.each([
			'primary-sidebar',
			'secondary-sidebar',
			'tertiary-sidebar',
			'document-header',
			'document-footer',
			'site-header',
			'site-footer',
			'page-header',
			'page-footer',
			'content-header',
			'content-footer',
			'comment-header',
			'comment-footer',
			'webcomic-header',
			'webcomic-footer',
			'webcomic-navigation-header',
			'webcomic-navigation-footer'
		], (index, value)->
			$('#customize-control-sidebar-' + value + '-columns').show()
		)
	)
)(jQuery)