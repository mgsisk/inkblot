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
 * 
 * @return void
 * @hook after_switch_theme
 */
function inkblot_after_switch_theme() {
	if (get_theme_mod('uninstall')) {
		remove_theme_mods();
	}
}
endif;

if ( ! function_exists('inkblot_admin_enqueue_scripts')) :
/**
 * Register and enqueue page-specific scirpts.
 * 
 * @return void
 * @hook admin_enqueue_scripts
 */
function inkblot_admin_enqueue_scripts($page) {
	global $post;
	
	if ('post.php' === $page and $post and 'page' === $post->post_type) {
		wp_enqueue_script('inkblot-templates-script', get_template_directory_uri() . '/-/js/templates.js', array('jquery'));
	} else if ('appearance_page_custom-header' === $page) {
		if (get_theme_mod('font') or get_theme_mod('page_font')) {
			$proto = is_ssl() ? 'https' : 'http';
			$fonts = array_filter(array(
				get_theme_mod('font'),
				get_theme_mod('page_font')
			));
			
			wp_enqueue_style('inkblot-fonts', add_query_arg(array('family' => implode('|', $fonts)), "{$proto}://fonts.googleapis.com/css"));
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
	$fonts = array();
	
	if ($fonts = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyDGeJxu3MGJVi5RiUw4rQ3Jt_Q4VtSOnyE') and ! is_wp_error($fonts)) {
		$fonts = json_decode($fonts['body']);
	}
	
	return is_wp_error($fonts) ? false : $fonts;
}
endif;