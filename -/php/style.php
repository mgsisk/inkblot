<?php
/**
 * Inline CSS generator.
 * 
 * Generates CSS based on theme modifications, which is then included in the
 * site header, overriding rules declared in `style.css`. Also used in the post
 * editor to ensure the editor matches your site.
 * 
 * @package Inkblot
 * @return string
 */

if ( ! function_exists('get_template_directory')) {
	return '';
}

if ( ! function_exists('inkblot_css')) :
/**
 * Save and output theme customizer CSS.
 *
 * @param string $value Color value to retrieve.
 * @return string
 */
function inkblot_css($selectors = '', $property = '', $value = '') {
	static $css = array();
	
	if ($value) {
		if ('font-family' === $property) {
			$value = '"' . str_replace('+', ' ', substr($value, 0, strpos($value, ':'))) . '", sans-serif';
		} else if (false !== strpos($property, 'image')) {
			$value = "url({$value})";
		} else if (false !== strpos($property, 'position')) {
			$value = "top {$value}";
		} else if (false !== strpos($property, 'color')) {
			if ('blank' === $value[0]) {
				return;
			}
			
			$value[0] = str_replace('#', '', $value[0]);
			
			if (is_numeric($value[1]) and 1 != $value[1]) {
				$hexcolor = $value[0];
				
				if (2 === strlen($value[0])) {
					$value[0] = str_repeat($value[0], 3);
				} else if (3 === strlen($value[0])) {
					$value[0] = "{$value[0][0]}{$value[0][0]}{$value[0][1]}{$value[0][1]}{$value[0][2]}{$value[0][2]}";
				}
				
				$value[0] = hexdec($value[0]);
				$value[0] = 'rgba(' . implode(',', array(
					0xFF & ($value[0] >> 0x10),
					0xFF & ($value[0] >> 0x8),
					0xFF & $value[0]
				)) . ",{$value[1]})";
			} else {
				$value = "#{$value[0]}";
			}
			
			if (0 === strpos($value[0], 'rgba')) {
				$value = $value[0];
			} else {
				unset($hexcolor);
			}
		}
		
		foreach ((array) $selectors as $selector) {
			if (isset($hexcolor)) {
				$css[$selector][] = "{$property}:#{$hexcolor}";
			}
			
			$css[$selector][] = "{$property}:{$value}";
		}
	} else if ( ! $selectors) {
		$output = '';
		
		foreach ($css as $selector => $properties) {
			$output .= "{$selector}{" . implode(';', $properties) . "}";
		}
		
		return $output;
	}
}
endif;

if (is_readable(get_template_directory() . '/-/php/mods.php') and $mod = require get_template_directory() . '/-/php/mods.php') :
	$css = is_customize_preview() ? '/*preview*/' : '';
	$editor = isset($_GET['inkblot-mods']) and 'editor' === $_GET['inkblot-mods'];
	
	foreach (array_keys($mod) as $key) {
		$mod[$key] = get_theme_mod($key, $editor ? $mod[$key] : '');
	}
	
	if (is_customize_preview() or ($mod['content'] and 'one-column' !== $mod['content'])) {
		$pixel = 0;
		$main_width = 100;
		$mod['sidebar1_width'] = $mod['sidebar1_width'] ? $mod['sidebar1_width'] : 25;
		$mod['sidebar2_width'] = $mod['sidebar2_width'] ? $mod['sidebar2_width'] : 25;
		$mod['sidebar3_width'] = $mod['sidebar3_width'] ? $mod['sidebar3_width'] : 25;
		
		inkblot_css('.sidebar1', 'width', "{$mod['sidebar1_width']}%");
		inkblot_css('.sidebar2', 'width', "{$mod['sidebar2_width']}%");
		inkblot_css('.sidebar3', 'width', "{$mod['sidebar3_width']}%");
		
		if ($mod['content'] and 'one-column' !== $mod['content']) {
			$pixel += is_customize_preview() ? 3 : 2;
			$main_width -= $mod['sidebar1_width'];
		}
		
		if (false !== strpos($mod['content'], 'three-column') or false !== strpos($mod['content'], 'four-column')) {
			$pixel += 2;
			$main_width -= $mod['sidebar2_width'];
		}
		
		if (false !== strpos($mod['content'], 'four-column')) {
			$pixel += 2;
			$main_width -= $mod['sidebar3_width'];
		}
		
		// Annoyingly, we have to shave some pixels off of the main width for some browsers.
		inkblot_css('main', 'width', "calc({$main_width}% - {$pixel}px)");
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
		
		if ($editor) {
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
			'h1:not(.site)',
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
			'.post-webcomic nav',
			'.contentinfo'
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
		
		if ($editor) {
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
		
		if ($editor) {
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
			'button:focus',
			'button:hover',
			'input[type="submit"]:focus',
			'input[type="submit"]:hover',
			'input[type="reset"]:focus',
			'input[type="reset"]:hover',
			'input[type="button"]:focus',
			'input[type="button"]:hover'
		), 'background-color', array($mod['page_link_color'], $mod['page_link_opacity']));
		
		inkblot_css(array(
			'.wrapper a',
			'.post-footer span',
			'nav.pagination'
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
		
		if ($editor) {
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
		
		if ($editor) {
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
		
		if ($editor) {
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
	
	if ($editor) {
		inkblot_css('.wp-editor', 'margin', '0 1rem');
		inkblot_css('.wp-editor img', 'border-radius', '.3rem');
		inkblot_css('.wp-editor .alignnone', 'margin', '1rem');
		inkblot_css('.wp-editor .aligncenter', 'margin', '1rem auto');
		inkblot_css('.wp-editor .alignright', 'margin', '0 0 1rem 1rem');
		inkblot_css('.wp-editor .alignleft', 'margin', '0 1rem 1rem 0');
		inkblot_css('.wp-editor .wp-caption', 'padding', '.3rem');
		inkblot_css('.wp-editor .wp-caption-dd', 'font-size', 'smaller');
		inkblot_css('.wp-editor .wp-caption-dd', 'margin', '.5rem 0 0');
	}
	
	$css .= inkblot_css();
	
	if ($responsive_width = get_theme_mod('responsive_width', 0) or is_customize_preview()) {
		$css .= <<<RESPONSIVE
@media only screen and (max-width: {$responsive_width}px) {
	main, .sidebar1, .sidebar2, .sidebar3 {width: 100%}
	.two-column.content-right main, .three-column.content-center main, .three-column.content-right main, .four-column.content-left main, .four-column.content-right main, .four-column.content-far-right main {-moz-order: 1; -ms-order: 1; -o-order: 1; -webkit-order: 1; order: 1}
	.banner nav {background: none}
	.banner nav:before {display: block; visibility: visible}
	.banner nav ul {display: none; visibility: hidden}
	.banner nav select {display: block; visibility: visible; width: 100%}
}
RESPONSIVE;
	}
	
	$css .= get_theme_mod('css', '');
	
	if (isset($_GET['inkblot-mods'])) {
		print $css;
	}
	
	return $css;
endif;