##
# Handle dynamic color customization previews.
# 
# @package Inkblot
##

(($)->
	##
	# Update theme colors for customization preview.
	# 
	# @param string $id Color to update.
	# @param string $to Color or opacity to update.
	# @param string $selectors HTML element selectors to update.
	# @param string $property CSS color property to update.
	# @return null
	##
	inkblot_color = ($id, $to, $selectors, $property)->
		$id = $id.replace(/_/g, '-')
		$isColor = -1 < $id.indexOf('color')
		$rgba = if $isColor then _.toArray(Color($to).toRgb()) else _.toArray(Color($('wbr.inkblot').data($id.replace(/opacity/g, 'color'))).toRgb())
		
		if $isColor
			$rgba.push(parseFloat($('wbr.inkblot').data($id.replace(/color/g, 'opacity'))))
		else
			$rgba.push($to)
		
		if -1 < $selectors.indexOf(' a')
			$('#inkblot-mods-inline-css').append($selectors + '{' + $property + ':' + 'rgba(' + $rgba.join(',') + ')' + '}')
		else
			$($selectors).css($property, 'rgba(' + $rgba.join(',') + ')')
		
		$('wbr.inkblot').data($id, $to)
	
	wp.customize('background_color', ($value)->
		$value.bind(($to)->
			inkblot_color('background_color', $to, 'body', 'background-color')
		)
	)
	
	wp.customize('background_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('background_opacity', $to, 'body', 'background-color')
		)
	)
	
	wp.customize('page_color', ($value)->
		$value.bind(($to)->
			inkblot_color('page_color', $to, '.wrapper, input, textarea', 'background-color')
		)
	)
	
	wp.customize('page_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('page_opacity', $to, '.wrapper, input, textarea', 'background-color')
		)
	)
	
	wp.customize('trim_color', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_color', $to, '.banner nav, .banner ul ul, .contentinfo, .post-webcomic nav, button, input[type="submit"], input[type="reset"], input[type="button"]', 'background-color')
		)
	)
	
	wp.customize('trim_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_opacity', $to, '.banner nav, .banner ul ul, .contentinfo, .post-webcomic nav, button, input[type="submit"], input[type="reset"], input[type="button"]', 'background-color')
		)
	)
	
	wp.customize('text_color', ($value)->
		$value.bind(($to)->
			inkblot_color('text_color', $to, 'body', 'color')
		)
	)
	
	wp.customize('text_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('text_opacity', $to, 'body', 'color')
		)
	)
	
	wp.customize('header_textcolor', ($value)->
		$value.bind(($to)->
			if 'blank' != $to
				inkblot_color('header_textcolor', $to, '.banner > a', 'color')
		)
	)
	
	wp.customize('header_textopacity', ($value)->
		$value.bind(($to)->
			inkblot_color('header_textopacity', $to, '.banner > a, .banner > a:focus, .banner > a:hover', 'color')
		)
	)
	
	wp.customize('page_text_color', ($value)->
		$value.bind(($to)->
			inkblot_color('page_text_color', $to, '.wrapper, input, textarea', 'color')
		)
	)
	
	wp.customize('page_text_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('page_text_opacity', $to, '.wrapper, input, textarea', 'color')
		)
	)
	
	wp.customize('trim_text_color', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_text_color', $to, '.banner nav, .banner ul ul, .contentinfo, .post-webcomic nav, button, input[type="submit"], input[type="reset"], input[type="button"]', 'color')
		)
	)
	
	wp.customize('trim_text_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_text_opacity', $to, '.banner nav, .banner ul ul, .contentinfo, .post-webcomic nav, button, input[type="submit"], input[type="reset"], input[type="button"]', 'color')
		)
	)
	
	wp.customize('link_color', ($value)->
		$value.bind(($to)->
			inkblot_color('link_color', $to, ' a', 'color')
		)
	)
	
	wp.customize('link_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('link_opacity', $to, ' a', 'color')
		)
	)
	
	wp.customize('link_hover_color', ($value)->
		$value.bind(($to)->
			inkblot_color('link_hover_color', $to, 'a:focus, a:hover', 'color')
		)
	)
	
	wp.customize('link_hover_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('link_hover_opacity', $to, 'a:focus, a:hover', 'color')
		)
	)
	
	wp.customize('page_link_color', ($value)->
		$value.bind(($to)->
			inkblot_color('page_link_color', $to, '.wrapper a, .post-footer span, nav.posts, nav.post-pages, nav.posts-paged, nav.comments-paged', 'color')
			inkblot_color('page_link_color', $to, 'blockquote', 'hr, pre, th, td, fieldset, input, textarea, .post-footer, .comment, .comment .comment, .pingback, .trackback, .bypostauthor', 'border-color')
			
			inkblot_color('header_textcolor', $('wbr.inkblot').data('header-textcolor'), '.banner > a', 'color')
			inkblot_color('trim_link_color', $('wbr.inkblot').data('trim-link-color'), '.banner nav:before, .banner nav a, .banner select, .contentinfo a, .post-webcomic nav a', 'color')
		)
	)
	
	wp.customize('page_link_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('page_link_opacity', $to, '.wrapper a, .post-footer span, nav.posts, nav.post-pages, nav.posts-paged, nav.comments-paged', 'color')
			inkblot_color('page_link_opacity', $to, 'blockquote', 'hr, pre, th, td, fieldset, input, textarea, .post-footer, .comment, .comment .comment, .pingback, .trackback, .bypostauthor', 'border-color')
			
			inkblot_color('header_textcolor', $('wbr.inkblot').data('header-textcolor'), '.banner > a', 'color')
			inkblot_color('trim_link_color', $('wbr.inkblot').data('trim-link-color'), '.banner nav:before, .banner nav a, .banner select, .contentinfo a, .post-webcomic nav a', 'color')
		)
	)
	
	wp.customize('page_link_hover_color', ($value)->
		$value.bind(($to)->
			inkblot_color('page_link_hover_color', $to, '.wrapper a:focus, .wrapper a:hover', 'color')
			inkblot_color('page_link_hover_color', $to, 'input:focus, input:hover, textarea:focus, textarea:hover', 'border-color')
			
			inkblot_color('header_textcolor', $('wbr.inkblot').data('header-textcolor'), '.banner > a:focus, .banner > a:hover', 'color')
			inkblot_color('trim_link_hover_color', $('wbr.inkblot').data('trim-link-hover-color'), '.banner nav:focus:before, .banner nav:hover:before, .banner nav a:focus, .banner nav a:hover, .banner select:focus, .banner select:hover, .banner li:focus > a, .banner li:hover > a, .banner li.current_page_item > a, .banner li.current_page_ancestor > a, .contentinfo a:focus, .contentinfo a:hover, .post-webcomic nav a:focus, .post-webcomic nav a:hover', 'color')
		)
	)
	
	wp.customize('page_link_hover_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('page_link_hover_opacity', $to, '.wrapper a:focus, .wrapper a:hover', 'color')
			inkblot_color('page_link_hover_opacity', $to, 'input:focus, input:hover, textarea:focus, textarea:hover', 'border-color')
			
			inkblot_color('header_textcolor', $('wbr.inkblot').data('header-textcolor'), '.banner > a:focus, .banner > a:hover', 'color')
			inkblot_color('trim_link_hover_color', $('wbr.inkblot').data('trim-link-hover-color'), '.banner nav:focus:before, .banner nav:hover:before, .banner nav a:focus, .banner nav a:hover, .banner select:focus, .banner select:hover, .banner li:focus > a, .banner li:hover > a, .banner li.current_page_item > a, .banner li.current_page_ancestor > a, .contentinfo a:focus, .contentinfo a:hover, .post-webcomic nav a:focus, .post-webcomic nav a:hover', 'color')
		)
	)
	
	wp.customize('trim_link_color', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_link_color', $to, '.banner nav:before, .banner nav a, .banner select, .contentinfo a, .post-webcomic nav a', 'color')
		)
	)
	
	wp.customize('trim_link_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_link_opacity', $to, '.banner nav:before, .banner nav a, .banner select, .contentinfo a, .post-webcomic nav a', 'color')
		)
	)
	
	wp.customize('trim_link_hover_color', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_link_hover_color', $to, '.banner nav:focus:before, .banner nav:hover:before, .banner nav a:focus, .banner nav a:hover, .banner select:focus, .banner select:hover, .banner li:focus > a, .banner li:hover > a, .banner li.current_page_item > a, .banner li.current_page_ancestor > a, .contentinfo a:focus, .contentinfo a:hover, .post-webcomic nav a:focus, .post-webcomic nav a:hover', 'color')
		)
	)
	
	wp.customize('trim_link_hover_opacity', ($value)->
		$value.bind(($to)->
			inkblot_color('trim_link_hover_opacity', $to, '.banner nav:focus:before, .banner nav:hover:before, .banner nav a:focus, .banner nav a:hover, .banner select:focus, .banner select:hover, .banner li:focus > a, .banner li:hover > a, .banner li.current_page_item > a, .banner li.current_page_ancestor > a, .contentinfo a:focus, .contentinfo a:hover, .post-webcomic nav a:focus, .post-webcomic nav a:hover', 'color')
		)
	)
)(jQuery)
