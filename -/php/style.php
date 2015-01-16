<?php
/**
 * Theme stylesheet generator.
 * 
 * To keep a lot of embedded styles and `<link>` tags out of our `<head>` we
 * use this file to generate a custom stylesheet based on `style.css`, theme
 * modifications, and child theme `style.css` (in that order).
 * 
 * @package Inkblot
 */

if ( ! function_exists('get_template_directory')) {
	return;
}

if (isset($_GET['inkblot-style']) and is_readable(get_template_directory() . '/style.css')) {
	require get_template_directory() . '/style.css';
}

if (isset($_GET['inkblot-style'])) :
print "\n\n";

printf('
@font-face {
	font-family: awesome;
	src: url("%1$s/-/font/fontawesome-webfont.eot");
	src: url("%1$s/-/font/fontawesome-webfont.eot?#iefix") format("embedded-opentype"),
		url("%1$s/-/font/fontawesome-webfont.woff") format("woff"),
		url("%1$s/-/font/fontawesome-webfont.ttf") format("truetype"),
		url("%1$s/-/font/fontawesome-webfont.svg#fontawesomeregular") format("svg");
	font-style: normal;
	font-weight: normal;
}',
get_template_directory_uri()
);
endif;

if (is_readable(get_template_directory() . '/-/php/mods.php') and $mod = require get_template_directory() . '/-/php/mods.php') :
	foreach (array_keys($mod) as $key) {
		$mod[$key] = get_theme_mod($key);
	}
	
	if ($mod['content'] and 'one-column' !== $mod['content']) {
		$mod['sidebar1_width'] = $mod['sidebar1_width'] ? $mod['sidebar1_width'] : 25;
		$mod['sidebar2_width'] = $mod['sidebar2_width'] ? $mod['sidebar2_width'] : 25;
		
		$main_width = 100 - $mod['sidebar1_width'];
		
		inkblot_css('.sidebar1', 'width', "{$mod['sidebar1_width']}%");
		
		if (false !== strpos($mod['content'], 'three-column')) {
			$main_width -= $mod['sidebar2_width'];
			
			inkblot_css('.sidebar2', 'width', "{$mod['sidebar2_width']}%");
			
			if ('three-column-center' === $mod['content']) {
				inkblot_css('main', 'left', "{$mod['sidebar1_width']}%");
				inkblot_css('.sidebar1', 'left', "-{$main_width}%");
			}
		}
		
		inkblot_css('main', 'width', "{$main_width}%");
	}
	
	if ($mod['min_width']) {
		inkblot_css(array(
			'.wrapper',
			'.document-header',
			'.document-footer'
		), 'min-width', "{$mod['min_width']}px");
	}
	
	if ($mod['max_width']) {
		inkblot_css(array(
			'.wrapper',
			'.document-header',
			'.document-footer'
		), 'max-width', "{$mod['max_width']}px");
	}
	
	if ($mod['min_width'] and $mod['max_width'] and $mod['min_width'] === $mod['max_width']) {
		inkblot_css(array(
			'.wrapper',
			'.document-header',
			'.document-footer'
		), 'width', "{$mod['min_width']}px");
	}
	
	if ($mod['font_size']) {
		inkblot_css('body', 'font-size', "{$mod['font_size']}%");
	}
	
	if ($mod['font']) {
		inkblot_css('body', 'font-family', $mod['font']);
	}
	
	if ($mod['page_font']) {
		inkblot_css('.wrapper', 'font-family', $mod['page_font']);
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css(array(
				'.wp-editor',
				'.wp-editor caption',
				'.wp-editor th',
				'.wp-editor td',
			), 'font-family', $mod['page_font']);
		}
	}
	
	if ($mod['title_font']) {
		inkblot_css(array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6'
		), 'font-family', $mod['title_font']);
	}
	
	if ($mod['trim_font']) {
		inkblot_css(array(
			'.banner nav',
			'.banner select',
			'.post-webcomic nav'
		), 'font-family', $mod['trim_font']);
	}
	
	if ($mod['background_color']) {
		inkblot_css('body', 'background-color', array($mod['background_color'], $mod['background_opacity']));
	}
	
	if ($mod['page_color']) {
		inkblot_css(array(
			'.wrapper',
			'input',
			'textarea'
		), 'background-color', array($mod['page_color'], $mod['page_opacity']));
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css('.wp-editor', 'background-color', array($mod['page_color'], $mod['page_opacity']));
		}
	}
	
	if ($mod['trim_color']) {
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.banner select',
			'.contentinfo',
			'.post-webcomic nav',
			'button',
			'input[type="submit"]',
			'input[type="reset"]',
			'input[type="button"]'
		), 'background-color', array($mod['trim_color'], $mod['trim_opacity']));
	}
	
	if ($mod['text_color']) {
		inkblot_css('body', 'color', array($mod['text_color'], $mod['text_opacity']));
	}
	
	if ($mod['page_text_color']) {
		inkblot_css(array(
			'.wrapper',
			'input',
			'textarea'
		), 'color', array($mod['page_text_color'], $mod['page_text_opacity']));
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css('.wp-editor', 'color', array($mod['page_text_color'], $mod['page_text_opacity']));
		}
	}
	
	if ($mod['trim_text_color']) {
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.contentinfo',
			'.post-webcomic nav',
			'button',
			'input[type="submit"]',
			'input[type="reset"]',
			'input[type="button"]'
		), 'color', array($mod['trim_text_color'], $mod['trim_text_opacity']));
	}
	
	if ($mod['link_color']) {
		inkblot_css('a', 'color', array($mod['link_color'], $mod['link_opacity']));
	}
	
	if ($mod['link_hover_color']) {
		inkblot_css(array(
			'a:focus',
			'a:hover'
		), 'color', array($mod['link_hover_color'], $mod['link_hover_opacity']));
	}
	
	if ($mod['page_link_color']) {
		inkblot_css(array(
			'.wrapper a',
			'.post-footer span',
			'nav.posts',
			'nav.post-pages',
			'nav.posts-paged',
			'nav.comments-paged'
		), 'color', array($mod['page_link_color'], $mod['page_link_opacity']));
		
		inkblot_css(array(
			'blockquote',
			'hr',
			'pre',
			'th',
			'td',
			'fieldset',
			'input',
			'textarea',
			'.post-footer',
			'.comment',
			'.comment .comment',
			'.pingback',
			'.trackback',
			'.bypostauthor'
		), 'border-color', array($mod['page_link_color'], $mod['page_link_opacity']));
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css('.wp-editor a', 'color', array($mod['page_link_color'], $mod['page_link_opacity']));
			
			inkblot_css(array(
				'.wp-editor .mce-item-table',
				'.wp-editor .mce-item-table th',
				'.wp-editor .mce-item-table td'
			), 'border-color', array($mod['page_link_color'], $mod['page_link_opacity']));
			
			inkblot_css(array(
				'.wp-editor .mce-item-table',
				'.wp-editor .mce-item-table th',
				'.wp-editor .mce-item-table td'
			), 'border-style', 'solid');
		}
	}
	
	if ($mod['page_link_hover_color']) {
		inkblot_css(array(
			'.wrapper a:focus',
			'.wrapper a:hover'
		), 'color', array($mod['page_link_hover_color'], $mod['page_link_hover_opacity']));
		
		inkblot_css(array(
			'input:focus',
			'input:hover',
			'textarea:focus',
			'textarea:hover'
		), 'border-color', array($mod['page_link_hover_color'], $mod['page_link_hover_opacity']));
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css(array(
				'.wp-editor a:focus',
				'.wp-editor a:hover'
			), 'color', array($mod['page_link_hover_color'], $mod['page_link_hover_opacity']));
		}
	}
	
	if ($mod['trim_link_color']) {
		inkblot_css(array(
			'.banner nav:before',
			'.banner nav a',
			'.banner select',
			'.contentinfo a',
			'.post-webcomic nav a'
		), 'color', array($mod['trim_link_color'], $mod['trim_link_opacity']));
	}
	
	if ($mod['trim_link_hover_color']) {
		inkblot_css(array(
			'.banner nav:focus:before',
			'.banner nav:hover:before',
			'.banner nav a:focus',
			'.banner nav a:hover',
			'.banner select:focus',
			'.banner select:hover',
			'.banner li:focus > a',
			'.banner li:hover > a',
			'.banner li.current_page_item > a',
			'.banner li.current_page_ancestor > a',
			'.contentinfo a:focus',
			'.contentinfo a:hover',
			'.post-webcomic nav a:focus',
			'.post-webcomic nav a:hover'
		), 'color', array($mod['trim_link_hover_color'], $mod['trim_link_hover_opacity']));
	}
	
	if ($mod['header_font']) {
		inkblot_css(array(
			'.banner > a'
		), 'font-family', $mod['header_font']);
	}
	
	if ('blank' === $mod['header_textcolor']) {
		inkblot_css(array(
			'.banner h1',
			'.banner p'
		), 'display', 'none');
		
		inkblot_css(array(
			'.banner h1',
			'.banner p'
		), 'visibility', 'hidden');
	} else if ($mod['header_textcolor']) {
		inkblot_css(array(
			'.banner > a',
			'.banner > a:focus',
			'.banner > a:hover'
		), 'color', array($mod['header_textcolor'], $mod['header_textopacity']));
	}
	
	if ($mod['background_image']) {
		inkblot_css('body', 'background-image', $mod['background_image']);
		inkblot_css('body', 'background-repeat', $mod['background_repeat']);
		inkblot_css('body', 'background-position', $mod['background_position_x']);
		inkblot_css('body', 'background-attachment', $mod['background_attachment']);
	}
	
	if ($mod['page_background_image']) {
		inkblot_css('.wrapper', 'background-image', $mod['page_background_image']);
		inkblot_css('.wrapper', 'background-repeat', $mod['page_background_repeat']);
		inkblot_css('.wrapper', 'background-position', $mod['page_background_position_x']);
		inkblot_css('.wrapper', 'background-attachment', $mod['page_background_attachment']);
		
		if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
			inkblot_css('.wp-editor', 'background-image', $mod['page_background_image']);
			inkblot_css('.wp-editor', 'background-repeat', $mod['page_background_repeat']);
			inkblot_css('.wp-editor', 'background-position', $mod['page_background_position_x']);
			inkblot_css('.wp-editor', 'background-attachment', $mod['page_background_attachment']);
		}
	}
	
	if ($mod['trim_background_image']) {
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.contentinfo',
			'.post-webcomic nav'
		), 'background-image', $mod['trim_background_image']);
		
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.contentinfo',
			'.post-webcomic nav'
		), 'background-repeat', $mod['trim_background_repeat']);
		
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.contentinfo',
			'.post-webcomic nav'
		), 'background-position', $mod['trim_background_position_x']);
		
		inkblot_css(array(
			'.banner nav',
			'.banner ul ul',
			'.contentinfo',
			'.post-webcomic nav'
		), 'background-attachment', $mod['trim_background_attachment']);
	}
	
	if (isset($_GET['inkblot-style']) and 'editor' === $_GET['inkblot-style']) {
		inkblot_css('.wp-editor', 'margin', '0 1rem');
		inkblot_css('.wp-editor img', 'border-radius', '.3rem');
		inkblot_css('.wp-editor .alignnone', 'margin', '1rem');
		inkblot_css('.wp-editor .aligncenter', 'margin', '1rem auto');
		inkblot_css('.wp-editor .alignright', 'margin', '0 0 1rem 1rem');
		inkblot_css('.wp-editor .alignleft', 'margin', '0 1rem 1rem 0');
		inkblot_css('.wp-editor .wp-caption', 'padding', '.25rem');
		inkblot_css('.wp-editor .wp-caption-dd', 'font-size', 'smaller');
		inkblot_css('.wp-editor .wp-caption-dd', 'margin', '.5rem 0 0');
	}
	
	inkblot_css();
	
	if ($responsive_width = get_theme_mod('responsive_width', 0)) {
		print <<<RESPONSIVE
@media only screen and (max-width: {$responsive_width}px) {
	main, .sidebar1, .sidebar2 {float: none; left: 0; width: 100%}
	.banner nav {background: none}
	.banner nav:before {display: block; visibility: visible}
	.banner nav ul {display: none; visibility: hidden}
	.banner nav select {display: block; visibility: visible; width: 100%}
}
RESPONSIVE;
	}
	
	print get_theme_mod('css', '');
endif;

print "\n\n";

// deprecated; use a child theme instead.
locate_template(array('custom.css'), isset($_GET['inkblot-style']));

if (is_child_theme() and is_readable(get_stylesheet_directory() . '/style.css')) {
	require get_stylesheet_directory() . '/style.css';
}