<?php
/**
 * Returns an array of sidebars.
 * 
 * @package Inkblot
 * @return array
 */

$sidebars = array(
	'primary-sidebar' => array(__('Primary Sidebar', 'inkblot'), __('Used in both two and three-column layouts. You can change theme layout from the Appearance > Customize page.', 'inkblot')),
	'secondary-sidebar' => array(__('Secondary Sidebar', 'inkblot'), __('Used in three and four-column layouts. You can change theme layout from the Appearance > Customize page.', 'inkblot')),
	'tertiary-sidebar' => array(__('Tertiary Sidebar', 'inkblot'), __('Used in four-column layouts only. You can change theme layout from the Appearance > Customize page.', 'inkblot')),
	'document-header' => array(__('Document Header', 'inkblot'), __('Located at the very top of the page, outside of the page wrapper.', 'inkblot')),
	'document-footer' => array(__('Document Footer', 'inkblot'), __('Located at the very bottom of the page, outside of the page wrapper.', 'inkblot')),
	'site-header' => array(__('Site Header', 'inkblot'), __('Located at the top of the page, where the site title and navigation are usually displayed.', 'inkblot')),
	'site-footer' => array(__('Site Footer', 'inkblot'), __('Located at the bottom of the page, where copyright information is usually displayed.', 'inkblot')),
	'page-header' => array(__('Page Header', 'inkblot'), __('Located near the top of the page, just inside the page wrapper.', 'inkblot')),
	'page-footer' => array(__('Page Footer', 'inkblot'), __('Located near the bottom of the page, just inside the page wrapper.', 'inkblot')),
	'content-header' => array(__('Content Header', 'inkblot'), __('Located near the top of the page, just inside the content wrapper.', 'inkblot')),
	'content-footer' => array(__('Content Footer', 'inkblot'), __('Located near the bottom of the page, just inside the content wrapper.', 'inkblot')),
	'comment-header' => array(__('Comment Header', 'inkblot'), __('Located above the comments list for a post, just inside the comments wrapper.', 'inkblot')),
	'comment-footer' => array(__('Comment Footer', 'inkblot'), __('Located below the comments list for a post, just inside the comments wrapper.', 'inkblot'))
);

if (webcomic()) {
	$sidebars = array_merge($sidebars, array(
		'webcomic-header' => array(__('Webcomic Header', 'inkblot'), __('Located above the webcomic, just inside the webcomic wrapper.', 'inkblot')),
		'webcomic-footer' => array(__('Webcomic Footer', 'inkblot'), __('Located below the webcomic, just inside the webcomic wrapper.', 'inkblot')),
		'webcomic-navigation-header' => array(__('Webcomic Navigation Header', 'inkblot'), __('Navigation displayed above the webcomic.', 'inkblot')),
		'webcomic-navigation-footer' => array(__('Webcomic Navigation Footer', 'inkblot'), __('Navigation displayed below the webcomic.', 'inkblot'))
	));
}

return $sidebars;