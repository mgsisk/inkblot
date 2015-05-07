<?php

if ( ! class_exists('Inkblot_Walker_Nav_Dropdown')) :
/**
 * Handle responsive dropdown menu output.
 * 
 * @package Inkblot
 */
class Inkblot_Walker_Nav_Dropdown extends Walker_Nav_Menu {
	/**
	 * Unused.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()){}
	
	/**
	 * Unused.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()){}
	
	/**
	 * Start element output.
	 * 
	 * @param string $output Walker output string.
	 * @param object $item Current item being handled by the walker.
	 * @param integer $depth Depth the walker is currently at.
	 * @param array $args Arguments passed to the walker.
	 * @param integer $id ID of the current item.
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		
		$output .= apply_filters('walker_nav_menu_start_el', sprintf('<option value="%s" class="%s" data-target="%s"%s>%s%s%s%s',
			esc_url($item->url),
			esc_attr($classes),
			esc_attr($item->target),
			selected(false !== strpos($classes, 'current-menu-item'), true, false),
			str_repeat('&nbsp;', $depth * 4),
			$args->link_before,
			apply_filters('the_title', $item->title, $item->ID),
			$args->link_after
		), $item, $depth, $args);
	}
	
	/**
	 * End element output.
	 * 
	 * @param string $output Walker output string.
	 * @param object $item Current item being handled by the walker.
	 * @param integer $depth Depth the walker is currently at.
	 * @param array $args Arguments passed to the walker.
	 */
	function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$output .= '</option>';
	}
}
endif;