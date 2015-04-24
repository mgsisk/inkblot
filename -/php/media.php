<?php
/**
 * Contains media-related functionality specific to Inkblot.
 * 
 * @package Inkblot
 */

add_action('delete_attachment', 'inkblot_delete_attachment', 10, 1);
add_filter('display_media_states', 'inkblot_display_media_states', 10, 1);

if ( ! function_exists('inkblot_delete_attachment')) :
/**
 * Update image theme mods when attachments are deleted.
 * 
 * @param integer $id ID of the attachment to delete.
 * @return void
 * @hook delete_attachment
 */
function inkblot_delete_attachment($id) {
	foreach (array(
		'page_background_image',
		'trim_background_image'
	) as $mod) {
		if (get_theme_mod($mod) === wp_get_attachment_url($id)) {
			set_theme_mod($mod, '');
		}
	}
}
endif;

if ( ! function_exists('inkblot_display_media_states')) :
/**
 * Display relevant status for theme media.
 * 
 * @param array $states List of media states.
 * @return array
 * @hook display_media_states
 */
function inkblot_display_media_states($states) {
	global $post;
	
	if ('inkblot-page-background' === get_post_meta($post->ID, '_wp_attachment_context', true)) {
		$states[] = __('Inkblot Page Background', 'inkblot');
	}
	
	if ('inkblot-trim-background' === get_post_meta($post->ID, '_wp_attachment_context', true)) {
		$states[] = __('Inkblot Trim Background', 'inkblot');
	}
	
	if ('inkblot-favicon' === get_post_meta($post->ID, '_wp_attachment_context', true)) {
		$states[] = __('Inkblot Site Icon', 'inkblot');
	}
	
	return $states;
}
endif;