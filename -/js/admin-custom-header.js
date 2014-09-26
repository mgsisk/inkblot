jQuery(function($) {
	$('#inkblot_header_font').on('change', function() {
		if ('' === $(this).val()) {
			$('#name, #desc').css('font-family', 'inherit');
		} else {
			$('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=' + $(this).val() + '">');
			$('#name, #desc').css('font-family', $(this).val().replace(/\+/g, ' ').substr(0, $(this).val().indexOf(':')));
		}
	}).trigger('change');
	
	$('#text-color').wpColorPicker({
		change: function($event, $ui) {
			var $rgba = _.toArray($ui.color.toRgb());
			
			$rgba.push(parseFloat($('#inkblot_header_textopacity').val()));
			
			$('#name, #desc').css('color', 'rgba(' + $rgba.join(', ') + ')');
		}
	});
	
	$('#inkblot_header_textopacity').on('change', function() {
		var $rgba = _.toArray(Color($('#text-color').wpColorPicker('color')).toRgb());
		
		$rgba.push($(this).val());
		
		$('#name, #desc').css('color', 'rgba(' + $rgba.join(', ') + ')');
	});
});