<?php
/**
 * Contains the Inkblot administrative functions.
 * 
 * @package Inkblot
 */

add_action('after_switch_theme', 'inkblot_after_switch_theme');
add_action('custom_header_options', 'inkblot_custom_header_options');
add_action('admin_enqueue_scripts', 'inkblot_admin_enqueue_scripts', 10, 1);

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

if ( ! function_exists('inkblot_admin_enqueue_scripts')) :
/**
 * Register and enqueue page-specific scirpts.
 */
function inkblot_admin_enqueue_scripts($page) {
	global $post;
	
	if ('post.php' === $page and $post and 'page' === $post->post_type) {
		wp_enqueue_script('inkblot-templates-script', get_template_directory_uri() . '/-/js/templates.js', array('jquery'));
	} else if ('appearance_page_custom-header' === $page) {
		if (get_theme_mod('font') or get_theme_mod('page_font')) {
			$fonts = array_filter(array(
				get_theme_mod('font'),
				get_theme_mod('page_font')
			));
			
			wp_enqueue_style('inkblot-fonts', add_query_arg(array('family' => implode('|', $fonts)), 'https://fonts.googleapis.com/css'));
		}
	}
}
endif;

if ( ! function_exists('inkblot_get_fonts')) :
/**
 * Return Google Font data.
 * 
 * @return object
 */
function inkblot_get_fonts() {
	$fonts = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyDGeJxu3MGJVi5RiUw4rQ3Jt_Q4VtSOnyE');
	
	return is_wp_error($fonts) ? false : json_decode($fonts['body']);
}
endif;