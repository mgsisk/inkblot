<?php

if ( ! class_exists('Inkblot_Walker_Page_Dropdown')) :
/**
 * Handle responsive dropdown page menu output.
 * 
 * @package Inkblot
 */
class Inkblot_Walker_Page_Dropdown extends Walker_PageDropdown {
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
	 * @param object $page Current page being handled by the walker.
	 * @param integer $depth Depth the walker is currently at.
	 * @param array $args Arguments passed to the walker.
	 * @param integer $current_page ID of the current page.
	 */
	function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {
		if ( ! $output) {
			$output = sprintf('<option value="%s">%s%s%s',
				home_url('/'),
				$args['link_before'],
				__('Home', 'inkblot'),
				$args['link_after']
			);
		}
		
		$classes = array('page_item', "page-item-{$page->ID}");
		
		if ($current_page) {
			$current_page_object = get_page($current_page);
			
			if ($current_page_object and in_array($page->ID, (array) get_post_ancestors($current_page_object->ID))) {
				$classes[] = 'current_page_ancestor';
			}
			
			if ($page->ID === $current_page) {
				$classes[] = 'current_page_item';
			} elseif ($current_page_object and $page->ID === $current_page_object->post_parent) {
				$classes[] = 'current_page_parent';
			}
		} else if (get_option('page_for_posts') === $page->ID) {
			$classes[] = 'current_page_parent';
		}
		
		$time = empty($args['show_date']) ? '' : mysql2date($args['date_format'], 'modified' === $args['show_date'] ? $page->post_modified : $page->post_date);
		$classes = implode(' ', apply_filters('page_css_class', array_filter($classes), $page, $depth, $args, $current_page));
		
		$output .= sprintf('<option value="%s" class=""%s%s>%s%s%s%s%s',
			get_permalink($page->ID),
			esc_attr($classes),
			selected(false !== strpos($classes, 'current_page_item'), true, false),
			str_repeat('&nbsp;', $depth * 4),
			$args['link_before'],
			apply_filters('the_title', $page->post_title, $page->ID),
			$args['link_after'],
			$time
		);
	}
	
	/**
	 * End element output.
	 * 
	 * @param string $output Walker output string.
	 * @param object $page Current page being handled by the walker.
	 * @param integer $depth Depth the walker is currently at.
	 * @param array $args Arguments passed to the walker.
	 */
	function end_el(&$output, $item, $depth = 0, $args = array(), $current_page = 0) {
		$output .= '</option>';
	}
}
endif;