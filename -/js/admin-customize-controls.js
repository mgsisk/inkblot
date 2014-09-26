(function($) {
	/**
	 * Toggle control visibility.
	 * 
	 * @param string $settingId
	 * @param object $o
	 * @return void
	 */
	function inkblot_toggle_controls($settingId, $o) {
		wp.customize($settingId, function($setting) {
			$.each($o.controls, function($i, $controlId) {
				wp.customize.control($controlId, function($control) {
					var $visibility = function($to) {
						$control.container.toggle($o.callback($to));
					};
					
					$visibility($setting.get());
					$setting.bind($visibility);
				});
			});
		});
	}
	
	$.each({
		'content': {
			controls: ['sidebar1_width'],
			callback: function($to) {
				return 'one-column' !== $to;
			}
		},
		'page_background_image': {
			controls: ['page_background_repeat', 'page_background_position_x', 'page_background_attachment'],
			callback: function($to) {
				return !! $to;
			}
		},
		'trim_background_image': {
			controls: ['trim_background_repeat', 'trim_background_position_x', 'trim_background_attachment'],
			callback: function($to) {
				return !! $to;
			}
		}
	}, function($settingId, $o) {
		inkblot_toggle_controls($settingId, $o);
	});
	
	$.each({
		'content': {
			controls: ['sidebar2_width'],
			callback: function($to) {
				return (-1 !== $to.indexOf('three'));
			}
		}
	}, function($settingId, $o) {
		inkblot_toggle_controls($settingId, $o);
	});
})(jQuery);