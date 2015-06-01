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
}

add_action('after_switch_theme', 'inkblot_after_switch_theme');
add_action('customize_preview_init', 'inkblot_customize_preview_init');
add_action('wp_head', 'inkblot_wp_head', 0);
add_action('wp_loaded', 'inkblot_wp_loaded', 0);
add_action('widgets_init', 'inkblot_widgets_init');
add_action('wp_enqueue_scripts', 'inkblot_wp_enqueue_scripts');
add_action('after_setup_theme', 'inkblot_after_setup_theme');
add_action('wp_footer', 'inkblot_wp_footer');

add_filter('body_class', 'inkblot_body_class', 10, 2);
add_filter('excerpt_more', 'inkblot_excerpt_more');
add_filter('the_content_more_link', 'inkblot_the_content_more_link');

if ( ! function_exists('inkblot_after_switch_theme')) :
/**
 * Activation hook.
 */
function inkblot_after_switch_theme() {
	$content = get_theme_mod('content');
	
	if ($content and in_array($content, array(
		'two-column-left',
		'two-column-right',
		'three-column-left',
		'three-column-right',
		'three-column-center'
	))) {
		set_theme_mod('content', str_replace(array('-left', '-right', '-center'), array(' content-left', ' content-right', ' content-center'), $content));
	}
	
	if (get_theme_mod('uninstall')) {
		remove_theme_mods();
	}
}
endif;

if ( ! function_exists('inkblot_customize_preview_init')) :
/**
 * Enqueue dynamic preview script.
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
 * @uses inkblot_page_description()
 */
function inkblot_wp_head() { ?>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="description" content="<?php inkblot_page_description(); ?>">
	
	<?php if (get_theme_mod('responsive_width', 0) or is_customize_preview()) : ?>
		
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
		
	<?php endif; ?>
	
	<?php if (get_theme_mod('favicon')) : ?>
		
		<link rel="icon" href="<?php print get_theme_mod('favicon'); ?>">
		<link rel="apple-touch-icon" href="<?php print get_theme_mod('favicon'); ?>">
		<link rel="msapplication-TileImage" href="<?php print get_theme_mod('favicon'); ?>">
		
	<?php endif; ?>
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php
}
endif;

if ( ! function_exists('inkblot_wp_loaded')) :
/**
 * Generate theme modification stylesheet.
 */
function inkblot_wp_loaded() {
	if (isset($_GET['inkblot-mods'])) {
		header('Content-Type: text/css');
		
		require_once get_template_directory() . '/-/php/style.php';
		
		exit;
	}
}
endif;

if ( ! function_exists('inkblot_widgets_init')) :
/**
 * Register widgetized areas.
 */
function inkblot_widgets_init() {
	$sidebars = require get_template_directory() . '/-/php/sidebars.php';
	
	foreach ($sidebars as $id => $sidebar) {
		register_sidebar(array(
			'id' => 'sidebar-' . $id,
			'name' => $sidebar[0],
			'description' => $sidebar[1],
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h2>',
			'after_title' => '</h2>'
		));
	}
}
endif;

if ( ! function_exists('inkblot_wp_enqueue_scripts')) :
/**
 * Enqueue scripts and stylesheets.
 */
function inkblot_wp_enqueue_scripts() {
	wp_enqueue_style('inkblot-theme', get_stylesheet_uri());
	
	wp_add_inline_style('inkblot-theme', require get_template_directory() . '/-/php/style.php');
	
	if (get_theme_mod('font') or get_theme_mod('header_font') or get_theme_mod('page_font') or get_theme_mod('title_font') or get_theme_mod('trim_font')) {
		$fonts = array_filter(array(
			get_theme_mod('font'),
			get_theme_mod('header_font'),
			get_theme_mod('page_font'),
			get_theme_mod('title_font'),
			get_theme_mod('trim_font')
		));
		
		wp_enqueue_style('inkblot-font', add_query_arg(array('family' => implode('|', $fonts)), "https://fonts.googleapis.com/css"));
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
 */
function inkblot_after_setup_theme() {
	load_theme_textdomain('inkblot', get_template_directory() . '/-/l10n');
	
	add_editor_style(get_stylesheet_uri());
	
	if (get_theme_mod('font') or get_theme_mod('page_font') or get_theme_mod('title_font')) {
		foreach (array_filter(array(
			get_theme_mod('font'),
			get_theme_mod('page_font'),
			get_theme_mod('title_font')
		)) as $font) {
			add_editor_style(add_query_arg(array('family' => $font), "https://fonts.googleapis.com/css"));
		}
	}
	
	add_editor_style(add_query_arg(array('inkblot-mods' => 'editor'), home_url('/')));
	
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
 */
function inkblot_wp_footer() {
	if (is_customize_preview()) :
		$mod = require get_template_directory() . '/-/php/mods.php'; ?>
		
		<wbr class="inkblot"
			<?php foreach ($mod as $key => $default) : ?>
				
				data-<?php print str_replace('_', '-', $key); ?>="<?php print get_theme_mod($key, $default); ?>"
				
			<?php endforeach; ?>
		>
		<?php
	endif;
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
 */
function inkblot_excerpt_more() {
	return '&#8230; <a href="' . get_permalink() . '" class="more-link">' . sprintf(__('Continue reading %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>') . '</a>';
}
endif;

if ( ! function_exists('inkblot_the_content_more_link')) :
/**
 * Return a more accessible read more link.
 *
 * @return string
 */
function inkblot_the_content_more_link() {
	return '<a href="' . get_permalink() . '" class="more-link">' . sprintf(__('Continue reading %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>') . '</a>';
}
endif;