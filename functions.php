<?php
/**
 * Inkblot theme functions.
 * 
 * @package Inkblot
 */

/**
 * Set the content width.
 *
 * @var integer
 */
if ( ! isset($content_width)) {
	$content_width = get_theme_mod('content_width', 640);
}

require_once get_template_directory() . '/-/php/tags.php';
require_once get_template_directory() . '/-/php/walker-nav-dropdown.php';
require_once get_template_directory() . '/-/php/walker-page-dropdown.php';

if (is_admin() or is_customize_preview()) {
	require_once get_template_directory() . '/-/php/admin.php';
	require_once get_template_directory() . '/-/php/media.php';
	require_once get_template_directory() . '/-/php/pages.php';
	require_once get_template_directory() . '/-/php/config.php';
}

add_action('customize_preview_init', 'inkblot_customize_preview_init');
add_action('wp_head', 'inkblot_wp_head', 0);
add_action('wp_loaded', 'inkblot_wp_loaded');
add_action('widgets_init', 'inkblot_widgets_init');
add_action('wp_head', 'inkblot_wp_head_customize');
add_action('wp_enqueue_scripts', 'inkblot_wp_enqueue_scripts');
add_action('after_setup_theme', 'inkblot_after_setup_theme');
add_action('wp_footer', 'inkblot_wp_footer');
add_filter('body_class', 'inkblot_body_class', 10, 2);
add_filter('excerpt_more', 'inkblot_excerpt_more');
add_filter('the_content_more_link', 'inkblot_the_content_more_link');


if ( ! function_exists('inkblot_customize_preview_init')) :
/**
 * Enqueue dynamic preview script.
 *
 * @return void
 * @hook customize_preview_init
 */
function inkblot_customize_preview_init() {
	wp_register_script('automattic-color', get_template_directory_uri() . '/-/js/color.js');
	wp_enqueue_script('inkblot-customize-script', get_template_directory_uri() . '/-/js/customize.js', array('jquery', 'customize-preview', 'underscore', 'automattic-color'), '', true);
}
endif;

if ( ! function_exists('inkblot_wp_head')) :
/**
 * Render the <head> portion of the page.
 *
 * @return void
 * @uses inkblot_page_description()
 * @hook wp_head
 */
function inkblot_wp_head() { ?>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="description" content="<?php inkblot_page_description(); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php
}
endif;

if ( ! function_exists('inkblot_wp_loaded')) :
/**
 * Generate custom stylesheet.
 *
 * It would be better to handle this with an `init` hook, but we need
 * `get_theme_mod` to function properly for the theme customizer.
 *
 * @return void
 * @action wp_loaded
 */
function inkblot_wp_loaded() {
	if (isset($_GET['inkblot-style'])) {
		header('Content-Type: text/css');
		
		require_once get_template_directory() . '/-/php/style.php';
		
		exit;
	}
}
endif;

if ( ! function_exists('inkblot_widgets_init')) :
/**
 * Register widgetized areas.
 *
 * @return void
 * @hook widgets_init
 */
function inkblot_widgets_init() {
	$widgets = array(
		__('Primary Sidebar', 'inkblot') => __('Used in both two and three-column layouts. You can change theme layout from the Appearance > Customize page.', 'inkblot'),
		__('Secondary Sidebar', 'inkblot') => __('Used in three-column layouts only. You can change theme layout from the Appearance > Customize page.', 'inkblot'),
		__('Document Header', 'inkblot') => __('Located at the very top of the page, outside of the page wrapper.', 'inkblot'),
		__('Document Footer', 'inkblot') => __('Located at the very bottom of the page, outside of the page wrapper.', 'inkblot'),
		__('Site Header', 'inkblot') => __('Located at the top of the page, where the site title and navigation are usually displayed.', 'inkblot'),
		__('Site Footer', 'inkblot') => __('Located at the bottom of the page, where copyright information is usually displayed.', 'inkblot'),
		__('Page Header', 'inkblot') => __('Located near the top of the page, just inside the page wrapper.', 'inkblot'),
		__('Page Footer', 'inkblot') => __('Located near the bottom of the page, just inside the page wrapper.', 'inkblot'),
		__('Content Header', 'inkblot') => __('Located near the top of the page, just inside the content wrapper.', 'inkblot'),
		__('Content Footer', 'inkblot') => __('Located near the bottom of the page, just inside the content wrapper.', 'inkblot'),
		__('Comment Header', 'inkblot') => __('Located above the comments list for a post, just inside the comments wrapper.', 'inkblot'),
		__('Comment Footer', 'inkblot') => __('Located below the comments list for a post, just inside the comments wrapper.', 'inkblot')
	);
	
	if (webcomic()) {
		$widgets = array_merge($widgets, array(
			__('Webcomic Header', 'inkblot') => __('Located above the webcomic, just inside the webcomic wrapper.', 'inkblot'),
			__('Webcomic Footer', 'inkblot') => __('Located below the webcomic, just inside the webcomic wrapper.', 'inkblot'),
			__('Webcomic Navigation Header', 'inkblot') => __('Navigation displayed above the webcomic.', 'inkblot'),
			__('Webcomic Navigation Footer', 'inkblot') => __('Navigation displayed below the webcomic.', 'inkblot')
		));
	}
	
	foreach ($widgets as $name => $description) {
		register_sidebar(array(
			'id' => 'sidebar-' . sanitize_title($name),
			'name' => $name,
			'description' => $description,
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h1>',
			'after_title' => '</h1>'
		));
	}
}
endif;

if ( ! function_exists('inkblot_wp_head_customize')) :
/**
 * Include customized styles inline.
 *
 * We need to do this while previewing to ensure customizations show up
 * properly if the user navigates through the theme preview.
 *
 * @return void
 * @hook wp_head
 */
function inkblot_wp_head_customize() {
	if (is_customize_preview()) {
		print '<style class="inkblot">';
		
		locate_template('/-/php/style.php', true);
		
		print '</style>';
	}
}
endif;

if ( ! function_exists('inkblot_wp_enqueue_scripts')) :
/**
 * Enqueue scripts and stylesheets.
 *
 * @return void
 * @hook wp_enqueue_scripts
 */
function inkblot_wp_enqueue_scripts() {
	wp_enqueue_style('inkblot-theme', add_query_arg(array('inkblot-style' => ''), home_url('/')));
	
	if (
		get_theme_mod('font')
		or get_theme_mod('header_font')
		or get_theme_mod('page_font')
		or get_theme_mod('title_font')
		or get_theme_mod('trim_font')
	) {
		$proto = is_ssl() ? 'https' : 'http';
		$fonts = array_filter(array(
			get_theme_mod('font'),
			get_theme_mod('header_font'),
			get_theme_mod('page_font'),
			get_theme_mod('title_font'),
			get_theme_mod('trim_font')
		));
		
		wp_enqueue_style('inkblot-fonts', add_query_arg(array('family' => implode('|', $fonts)), "{$proto}://fonts.googleapis.com/css"));
	}
	
	wp_enqueue_script('inkblot-script', get_template_directory_uri() . '/-/js/script.js', array('jquery'), '', true);
	
	if (is_singular() and comments_open() and get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
endif;

if ( ! function_exists('inkblot_after_setup_theme')) :
/**
 * Setup theme features.
 *
 * @uses Inkblot::$dir
 * @hook after_setup_theme
 */
function inkblot_after_setup_theme() {
	load_theme_textdomain('inkblot', get_template_directory() . '/-/i18n');
	
	if (get_theme_mod('page_font') or get_theme_mod('title_font')) {
		$proto = is_ssl() ? 'https' : 'http';
		
		foreach (array_filter(array(
			get_theme_mod('page_font'),
			get_theme_mod('title_font')
		)) as $font) {
			add_editor_style(add_query_arg(array('family' => $font), "{$proto}://fonts.googleapis.com/css"));
		}
	}
	
	add_editor_style(add_query_arg(array('inkblot-style' => 'editor'), home_url('/')));
	
	add_filter('use_default_gallery_style', '__return_false');
	add_filter('show_recent_comments_widget_style', '__return_false');
	
	add_theme_support('menus');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('html5', array('caption', 'comment-list', 'comment-form', 'gallery', 'search-form'));
	add_theme_support('post-formats', array('aside', 'audio', 'chat', 'gallery', 'image', 'link', 'status', 'quote', 'video'));
	add_theme_support('custom-background', array(
		'default-color' => 'ffffff',
		'wp-head-callback' => '__return_false'
	));
	add_theme_support('custom-header', array(
		'width' => get_theme_mod('header_width', 960),
		'height' => get_theme_mod('header_height', 240),
		'flex-width' => true,
		'flex-height' => true,
		'wp-head-callback' => '__return_false',
		'default-text-color' => '222',
		'admin-head-callback' => 'inkblot_admin_head',
		'admin-preview-callback' => 'inkblot_admin_head_preview'
	));
	
	register_nav_menu('primary', __('Primary Menu', 'inkblot'));
	
	set_post_thumbnail_size(get_theme_mod('post_thumbnail_width', 144), get_theme_mod('post_thumbnail_height', 144));
}
endif;

if ( ! function_exists('inkblot_wp_footer')) :
/**
 * Add a customization element to the bottom of the page.
 *
 * This element has a number of data attributes that are used to keep things
 * consistent while customizing the theme.
 *
 * @return void
 */
function inkblot_wp_footer() {
	if (is_customize_preview()) {
		$mod = require get_template_directory() . '/-/php/mods.php'; ?>
		<wbr
			class="inkblot"
			<?php foreach ($mod as $key => $default) : ?>
				
				data-<?php print str_replace('_', '-', $key); ?>="<?php print get_theme_mod($key, $default); ?>"
				
			<?php endforeach; ?>

		>
	<?php }
}
endif;

if ( ! function_exists('inkblot_body_class')) :
/**
 * Add the content class to the body tag.
 *
 * Also adds Webcomic-specific classes for easier styling.
 *
 * @param array $classes Array of body classes.
 * @param mixed $class Additional classes passed to `body_class()`.
 * @return array
 * @hook body_class
 */
function inkblot_body_class($classes, $class) {
	$classes[] = get_theme_mod('content', 'one-column');
	
	if (get_theme_mod('responsive_width', 0) or is_customize_preview()) {
		$classes[] = 'responsive';
	}
	
	if (webcomic()) {
		if (is_webcomic_archive()) {
			$classes[] = 'post-type-archive-webcomic';
		}
		
		if (is_webcomic_storyline()) {
			$classes[] = 'tax-webcomic_storyline';
		}
		
		if (is_webcomic_character()) {
			$classes[] = 'tax-webcomic_character';
		}
	}
	
	return $classes;
}
endif;

if ( ! function_exists('inkblot_excerpt_more')) :
/**
 * Return a more accessible read more link.
 *
 * @return string
 * @hook excerpt_more
 */
function inkblot_excerpt_more() {
	return '&#8230; <a href="' . get_permalink() . '" class="more-link">' . sprintf(__('Continue reading %s', 'inkblot'), get_the_title()) . '</a>';
}
endif;

if ( ! function_exists('inkblot_the_content_more_link')) :
/**
 * Return a more accessible read more link.
 *
 * @return string
 * @hook the_content_more_link
 */
function inkblot_the_content_more_link() {
	return '<a href="' . get_permalink() . '" class="more-link">' . sprintf(__('Continue reading %s', 'inkblot'), get_the_title()) . '</a>';
}
endif;

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
			$value = str_replace('+', ' ', substr($value, 0, strpos($value, ':'))) . ', sans-serif';
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
				$value[0] = 'rgba(' . implode(', ', array(
					0xFF & ($value[0] >> 0x10),
					0xFF & ($value[0] >> 0x8),
					0xFF & $value[0]
				)) . ", {$value[1]})";
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
				$css[$selector][] = "{$property}: #{$hexcolor}";
			}
			
			$css[$selector][] = "{$property}: {$value}";
		}
	} else if ( ! $selectors) {
		print "\n\n";
		
		foreach ($css as $selector => $properties) {
			print "{$selector} {" . implode('; ', $properties) . "}\n";
		}
		
		unset($css);
	}
}
endif;