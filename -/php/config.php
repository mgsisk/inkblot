<?php
/**
 * Contains configuration-related functionality specific to Inkblot.
 * 
 * @package Inkblot
 */

add_action('customize_register', 'inkblot_customize_register');
add_action('customize_controls_enqueue_scripts', 'inkblot_customize_controls_enqueue_scripts');

if ( ! function_exists('inkblot_customize_register')) :
/**
 * Register theme customization sections, settings, and controls.
 * 
 * @param object $customize WordPress theme customization object.
 * @return void
 * @hook customize_register
 */
function inkblot_customize_register($customize) {
	foreach (array('blogname', 'blogdescription', 'header_textcolor', 'background_color', 'background_image', 'background_repeat', 'background_position_x', 'background_attachment') as $setting) {
		$customize->get_setting($setting)->transport = 'postMessage';
		
		if ('background_image' === $setting) {
			$customize->get_section($setting)->title = __('Site', 'inkblot');
			$customize->get_section($setting)->panel = 'inkblot_background_images';
			$customize->get_section($setting)->priority = 5;
		}
	}
	
	/* ----- Layout --------------------------------------------------------- */
	$customize->add_section('inkblot_layout', array(
		'title' => __('Layout', 'inkblot'),
		'priority' => 25
	));
	
	$customize->add_setting('content', array(
		'default' => 'one-column',
		'transport' => 'postMessage'
	)); $customize->add_control('content', array(
		'type' => 'radio',
		'section' => 'inkblot_layout',
		'priority' => 0,
		'choices' => array(
			'one-column' => __('No sidebars', 'inkblot'),
			'two-column-left' => __('Content on left (one sidebar)', 'inkblot'),
			'two-column-right' => __('Content on right (one sidebar)', 'inkblot'),
			'three-column-left' => __('Content on left (two sidebars)', 'inkblot'),
			'three-column-right' => __('Content on right (two sidebars)', 'inkblot'),
			'three-column-center' => __('Content centered (two sidebars)', 'inkblot')
		)
	));
	
	$customize->add_setting('sidebar1_width', array(
		'default' => 25,
		'transport' => 'postMessage'
	)); $customize->add_control('sidebar1_width', array(
		'type' => 'number',
		'label' => __('Primary Sidebar Width', 'inkblot'),
		'description' => __('The percentage width of the primary Sidebar.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 5,
		'input_attrs' => array(
			'min' => 5,
			'max' => 45,
			'step' => 1
		)
	));
	
	$customize->add_setting('sidebar2_width', array(
		'default' => 25,
		'transport' => 'postMessage'
	)); $customize->add_control('sidebar2_width', array(
		'type' => 'number',
		'label' => __('Secondary Sidebar Width', 'inkblot'),
		'description' => __('The percentage width of the secondary Sidebar.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 10,
		'input_attrs' => array(
			'min' => 5,
			'max' => 45,
			'step' => 1
		)
	));
	
	$customize->add_setting('min_width', array(
		'default' => 0,
		'transport' => 'postMessage'
	)); $customize->add_control('min_width', array(
		'type' => 'number',
		'label' => __('Minimum Width', 'inkblot'),
		'description' => __('The minimum pixel width the page will resize to.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 15,
		'input_attrs' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	$customize->add_setting('max_width', array(
		'default' => 0,
		'transport' => 'postMessage'
	)); $customize->add_control('max_width', array(
		'type' => 'number',
		'label' => __('Maximum Width', 'inkblot'),
		'description' => __('The maximum pixel width the page will resize to.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 20,
		'input_attrs' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	$customize->add_setting('content_width', array(
		'default' => 640,
		'transport' => 'postMessage'
	)); $customize->add_control('content_width', array(
		'type' => 'number',
		'label' => __('Content Width', 'inkblot'),
		'description' => __('Defines the maximum pixel width for post content.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 25,
		'input_attr' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	$customize->add_setting('responsive_width', array('default' => 0));
	$customize->add_control('responsive_width', array(
		'type' => 'number',
		'description' => __('Responsive features will only be used when your page is less than or equal to this pixel width.', 'inkblot'),
		'label' => __('Responsive Width', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 30,
		'input_attrs' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	/* ----- Fonts ---------------------------------------------------------- */
	
	$customize->remove_section('colors');
	
	$customize->add_section('inkblot_fonts', array(
		'title' => __('Fonts', 'inkblot'),
		'priority' => 30
	));
	
	$customize->add_setting('font_size', array(
		'default' => 100,
		'transport' => 'postMessage'
	)); $customize->add_control('font_size', array(
		'type' => 'range',
		'label' => __('Base Size', 'inkblot'),
		'section' => 'inkblot_fonts',
		'priority' => 0,
		'input_attrs' => array(
			'min' => 50,
			'max' => 200,
			'step' => 5
		)
	));
	
	if ($google_fonts = inkblot_get_fonts()) {
		$fonts = array('' => __('(inherit)', 'inkblot'));
		
		foreach ($google_fonts->items as $font) {
			$fonts[sprintf('%s:%s', str_replace(' ', '+', $font->family), implode(',', $font->variants))] = $font->family;
		}
		
		$customize->add_setting('font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('font', array(
			'type' => 'select',
			'label' => __('Site', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 5,
			'choices' => $fonts
		));
		
		$customize->add_setting('header_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('header_font', array(
			'type' => 'select',
			'label' => __('Header', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 10,
			'choices' => $fonts
		));
		
		$customize->add_setting('page_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('page_font', array(
			'type' => 'select',
			'label' => __('Page', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 15,
			'choices' => $fonts
		));
		
		$customize->add_setting('title_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('title_font', array(
			'type' => 'select',
			'label' => __('Titles', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 20,
			'choices' => $fonts
		));
		
		$customize->add_setting('trim_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('trim_font', array(
			'type' => 'select',
			'label' => __('Trim', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 25,
			'choices' => $fonts
		));
	}
	
	/* ----- Colors --------------------------------------------------------- */
	
	$customize->add_panel('inkblot_colors', array(
		'title' => __('Colors', 'inkblot'),
		'priority' => 35
	));
	
	$customize->add_section('inkblot_background_colors', array(
		'title' => __('Backgrounds', 'inkblot'),
		'panel' => 'inkblot_colors',
		'priority' => 0
	));
	
	$customize->add_setting('background_color', array(
		'default' => '#ffffff',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'background_color', array(
		'label' => __('Site', 'inkblot'),
		'section' => 'inkblot_background_colors',
		'priority' => 0
	)));
	
	$customize->add_setting('background_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('background_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_background_colors',
		'priority' => 5,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('page_color', array(
		'default' => '#ffffff',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'page_color', array(
		'label' => __('Page', 'inkblot'),
		'section' => 'inkblot_background_colors',
		'priority' => 10
	)));
	
	$customize->add_setting('page_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('page_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_background_colors',
		'priority' => 15,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('trim_color', array(
		'default' => '#222222',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'trim_color', array(
		'label' => __('Trim', 'inkblot'),
		'section' => 'inkblot_background_colors',
		'priority' => 20
	)));
	
	$customize->add_setting('trim_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('trim_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_background_colors',
		'priority' => 25,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_section('inkblot_text_colors', array(
		'title' => __('Text', 'inkblot'),
		'panel' => 'inkblot_colors',
		'priority' => 5
	));
	
	$customize->add_setting('text_color', array(
		'default' => '#222222',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'text_color', array(
		'label' => __('Site', 'inkblot'),
		'section' => 'inkblot_text_colors',
		'priority' => 0
	)));
	
	$customize->add_setting('text_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('text_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_text_colors',
		'priority' => 5,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->get_control('header_textcolor')->label = 'Header';
	$customize->get_control('header_textcolor')->section = 'inkblot_text_colors';
	$customize->get_control('header_textcolor')->priority = 10;
	
	$customize->add_setting('header_textopacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('header_textopacity', array(
		'type' => 'range',
		'section' => 'inkblot_text_colors',
		'priority' => 15,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('page_text_color', array(
		'default' => '#222222',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'page_text_color', array(
		'label' => __('Page', 'inkblot'),
		'section' => 'inkblot_text_colors',
		'priority' => 20
	)));
	
	$customize->add_setting('page_text_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('page_text_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_text_colors',
		'priority' => 25,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('trim_text_color', array(
		'default' => '#ffffff',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'trim_text_color', array(
		'label' => __('Trim', 'inkblot'),
		'section' => 'inkblot_text_colors',
		'priority' => 30
	)));
	
	$customize->add_setting('trim_text_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('trim_text_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_text_colors',
		'priority' => 35,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_section('inkblot_link_colors', array(
		'title' => __('Links', 'inkblot'),
		'panel' => 'inkblot_colors',
		'priority' => 10
	));
	
	$customize->add_setting('link_color', array(
		'default' => '#999999',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'link_color', array(
		'label' => __('Site', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 0
	)));
	
	$customize->add_setting('link_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('link_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 5,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('link_hover_color', array(
		'default' => '#222222',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'link_hover_color', array(
		'label' => __('Site Hover', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 10
	)));
	
	$customize->add_setting('link_hover_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('link_hover_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 15,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('page_link_color', array(
		'default' => '#999999',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'page_link_color', array(
		'label' => __('Page', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 20
	)));
	
	$customize->add_setting('page_link_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('page_link_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 25,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('page_link_hover_color', array(
		'default' => '#222222',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'page_link_hover_color', array(
		'label' => __('Page Hover', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 30
	)));
	
	$customize->add_setting('page_link_hover_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('page_link_hover_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 35,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('trim_link_color', array(
		'default' => '#999999',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'trim_link_color', array(
		'label' => __('Trim', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 40
	)));
	
	$customize->add_setting('trim_link_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('trim_link_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 45,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	$customize->add_setting('trim_link_hover_color', array(
		'default' => '#ffffff',
		'transport' => 'postMessage'
	)); $customize->add_control(new WP_Customize_Color_Control($customize, 'trim_link_hover_color', array(
		'label' => __('Trim Hover', 'inkblot'),
		'section' => 'inkblot_link_colors',
		'priority' => 50
	)));
	
	$customize->add_setting('trim_link_hover_opacity', array(
		'default' => 1,
		'transport' => 'postMessage'
	)); $customize->add_control('trim_link_hover_opacity', array(
		'type' => 'range',
		'section' => 'inkblot_link_colors',
		'priority' => 55,
		'input_attrs' => array(
			'min' => 0,
			'max' => 1,
			'step' => .05
		)
	));
	
	/* ----- Background Images --------------------------------------------- */
	
	$customize->add_panel('inkblot_background_images', array(
		'title' => __('Background Images', 'inkblot'),
		'priority' => 65
	));
	
	$customize->add_section('inkblot_page_background_image', array(
		'title' => __('Page', 'inkblot'),
		'panel' => 'inkblot_background_images',
		'priority' => 10
	));
	
	$customize->add_setting('page_background_image', array(
		'default' => '',
		'transport' => 'postMessage'
	)); $page_background = new WP_Customize_Image_Control($customize, 'page_background_image', array(
		'label' => __('Background Image', 'inkblot'),
		'section' => 'inkblot_page_background_image',
		'context' => 'inkblot-page-background'
	)); $customize->add_control($page_background);
	
	$customize->add_setting('page_background_repeat', array(
		'default' => 'repeat',
		'transport' => 'postMessage'
	)); $customize->add_control('page_background_repeat', array(
		'type' => 'radio',
		'label' => __('Background Repeat', 'inkblot'),
		'section' => 'inkblot_page_background_image',
		'choices' => array(
			'no-repeat' => __('No Repeat', 'inkblot'),
			'repeat' => __('Tile', 'inkblot'),
			'repeat-x' => __('Tile Horizontally', 'inkblot'),
			'repeat-y' => __('Tile Vertically', 'inkblot')
		)
	));
	
	$customize->add_setting('page_background_position_x', array(
		'default' => 'left',
		'transport' => 'postMessage'
	)); $customize->add_control('page_background_position_x', array(
		'type' => 'radio',
		'label' => __('Background Position', 'inkblot'),
		'section' => 'inkblot_page_background_image',
		'choices' => array(
			'left' => __('Left', 'inkblot'),
			'center' => __('Center', 'inkblot'),
			'right' => __('Right', 'inkblot')
		)
	));
	
	$customize->add_setting('page_background_attachment', array(
		'default' => 'scroll',
		'transport' => 'postMessage'
	)); $customize->add_control('page_background_attachment', array(
		'type' => 'radio',
		'label' => __('Background Attachment', 'inkblot'),
		'section' => 'inkblot_page_background_image',
		'choices' => array(
			'scroll' => __('Scroll', 'inkblot'),
			'fixed' => __('Fixed', 'inkblot')
		)
	));
	
	$customize->add_section('inkblot_trim_background_image', array(
		'title' => __('Trim', 'inkblot'),
		'panel' => 'inkblot_background_images',
		'priority' => 15
	));
	
	$customize->add_setting('trim_background_image', array(
		'default' => '',
		'transport' => 'postMessage'
	)); $trim_background = new WP_Customize_Image_Control($customize, 'trim_background_image', array(
		'label' => __('Background Image', 'inkblot'),
		'section' => 'inkblot_trim_background_image',
		'context' => 'inkblot-trim-background'
	)); $customize->add_control($trim_background);
	
	$customize->add_setting('trim_background_repeat', array(
		'default' => 'repeat',
		'transport' => 'postMessage'
	)); $customize->add_control('trim_background_repeat', array(
		'type' => 'radio',
		'label' => __('Background Repeat', 'inkblot'),
		'section' => 'inkblot_trim_background_image',
		'choices' => array(
			'no-repeat' => __('No Repeat', 'inkblot'),
			'repeat' => __('Tile', 'inkblot'),
			'repeat-x' => __('Tile Horizontally', 'inkblot'),
			'repeat-y' => __('Tile Vertically', 'inkblot')
		)
	));
	
	$customize->add_setting('trim_background_position_x', array(
		'default' => 'left',
		'transport' => 'postMessage'
	)); $customize->add_control('trim_background_position_x', array(
		'type' => 'radio',
		'label' => __('Background Position', 'inkblot'),
		'section' => 'inkblot_trim_background_image',
		'choices' => array(
			'left' => __('Left', 'inkblot'),
			'center' => __('Center', 'inkblot'),
			'right' => __('Right', 'inkblot')
		)
	));
	
	$customize->add_setting('trim_background_attachment', array(
		'default' => 'scroll',
		'transport' => 'postMessage'
	)); $customize->add_control('trim_background_attachment', array(
		'type' => 'radio',
		'label' => __('Background Attachment', 'inkblot'),
		'section' => 'inkblot_trim_background_image',
		'choices' => array(
			'scroll' => __('Scroll', 'inkblot'),
			'fixed' => __('Fixed', 'inkblot')
		)
	));
	
	/* ----- Header Image --------------------------------------------------- */
	
	$customize->add_setting('header_width', array('default' => 960));
	$customize->add_control('header_width', array(
		'type' => 'number',
		'label' => __('Width', 'inkblot'),
		'description' => __('The pixel width of your header image. Changes will not affect previously uploaded header images.', 'inkblot'),
		'section' => 'header_image',
		'input_attr' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	$customize->add_setting('header_height', array('default' => 240));
	$customize->add_control('header_height', array(
		'type' => 'number',
		'label' => __('Height', 'inkblot'),
		'description' => __('The pixel height of your header image. Changes will not affect previously header images.', 'inkblot'),
		'section' => 'header_image',
		'input_attr' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	/* ----- Navigation ----------------------------------------------------- */
	
	$customize->add_setting('paged_navigation', array('default' => true));
	$customize->add_control('paged_navigation', array(
		'type' => 'checkbox',
		'label' => __('Use paged navigation for posts', 'inkblot'),
		'section' => 'nav',
		'priority' => '120'
	));
	
	$customize->add_setting('paged_comments', array('default' => true));
	$customize->add_control('paged_comments', array(
		'type' => 'checkbox',
		'label' => __('Use paged navigation for comments', 'inkblot'),
		'section' => 'nav',
		'priority' => '150'
	));
	
	/* ----- Widgets -------------------------------------------------------- */
	
	$sidebars = require get_template_directory() . '/-/php/sidebars.php';
	
	foreach (array_keys($sidebars) as $sidebar) {
		$customize->add_setting("sidebar-{$sidebar}-columns", array('default' => ! in_array($sidebar, array('primary-sidebar', 'secondary-sidebar'))));
		$customize->add_control("sidebar-{$sidebar}-columns", array(
			'type' => 'checkbox',
			'label' => __('Display widgets as separate columns', 'inkblot'),
			'section' => "sidebar-widgets-sidebar-{$sidebar}"
		));
	}
	
	/* ----- Miscellanea ---------------------------------------------------- */
	
	$customize->add_section('inkblot_miscellanea', array(
		'title' => __('Miscellanea', 'inkblot'),
		'priority' => 990
	));
	
	$customize->add_setting('favicon', array('default' => ''));
	$favicon = new WP_Customize_Image_Control($customize, 'favicon', array(
		'label' => __('Site Icon', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'context' => 'inkblot-favicon',
		'description' => __('The site icon may be displayed by web browsers and mobile devices in various ways.', 'inkblot')
	)); $customize->add_control($favicon);
	
	$customize->add_setting('post_thumbnail_width', array('default' => 144));
	$customize->add_control('post_thumbnail_width', array(
		'type' => 'number',
		'label' => __('Post Thumbnail Width', 'inkblot'),
		'description' => __('The pixel width of post thumbnails. Changes will not affect previously uploaded thumbnails.', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'input_attr' => array(
			'min' => 0,
			'step' => 5
		)
	));
	
	$customize->add_setting('post_thumbnail_height', array('default' => 144));
	$customize->add_control('post_thumbnail_height', array(
		'type' => 'number',
		'label' => __('Post Thumbnail Height', 'inkblot'),
		'description' => __('The pixel height of post thumbnails. Changes will not affect previously uploaded thumbnails.', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'input_attr' => array(
			'min' => 0,
			'step' => 5
		)
	));
	
	$customize->add_setting('uninstall', array('default' => false));
	$customize->add_control('uninstall', array(
		'type' => 'checkbox',
		'label' => __('Remove theme modifications when changing themes', 'inkblot'),
		'section' => 'inkblot_miscellanea'
	));
	
	/* ----- Stylesheet ----------------------------------------------------- */
	
	$customize->add_section('inkblot_css', array(
		'title' => __('Stylesheet', 'inkblot'),
		'priority' => 999
	));
	
	$customize->add_setting('css', array(
		'default' => '',
		'transport' => 'postMessage'
	));
	$customize->add_control('css', array(
		'type' => 'textarea',
		'description' => __('Enter CSS rules below to further customize your theme. Extensive CSS changes should be done using a child theme.', 'inkblot'),
		'section' => 'inkblot_css'
	));
	
	/* ----- Webcomic ------------------------------------------------------- */
	
	if (webcomic()) {
		$sizes = array('' => __('none', 'inkblot'));
		$collections = array('' => __('any collection', 'inkblot'));
		
		foreach (get_intermediate_image_sizes() as $size) {
			$sizes[$size] = $size;
		}
		
		$sizes['full'] = __('full', 'inkblot');
		
		foreach (get_webcomic_collections(true) as $k => $v) {
			$collections[$k] = $v['name'];
		}
		
		$customize->add_panel('webcomic', array(
			'title' => __('Webcomic', 'inkblot'),
			'description' => __('These settings affect features related to the Webcomic plugin.', 'inkblot'),
			'priority' => 980
		));
		
		/* ----- Webcomic Layout -------------------------------------------- */
		
		$customize->add_section('webcomic_layout', array(
			'title' => __('Layout', 'inkblot'),
			'panel' => 'webcomic',
			'priority' => 10
		));
		
		$customize->add_setting('webcomic_content', array('default' => false));
		$customize->add_control('webcomic_content', array(
			'type' => 'checkbox',
			'label' => __('Place webcomic in the content column', 'inkblot'),
			'section' => 'webcomic_layout'
		));
		
		$customize->add_setting('webcomic_resize', array(
			'default' => true
		)); $customize->add_control('webcomic_resize', array(
			'type' => 'checkbox',
			'label' => __('Resize webcomic to fit available space', 'inkblot'),
			'section' => 'webcomic_layout'
		));
		
		/* ----- Webcomic Navigation ---------------------------------------- */
		
		$customize->add_section('webcomic_nav', array(
			'title' => __('Navigation', 'inkblot'),
			'panel' => 'webcomic',
			'priority' => 15
		));
		
		$customize->add_setting('webcomic_nav_link', array('default' => ''));
		$customize->add_control('webcomic_nav_link', array(
			'type' => 'radio',
			'label' => __('Webcomic attachments link to', 'inkblot'),
			'section' => 'webcomic_nav',
			'choices' => array(
				'' => __('Nothing', 'inkblot'),
				'next' => __('Next webcomic', 'inkblot'),
				'previous' => __('Previous webcomic', 'inkblot')
			)
		));
		
		$customize->add_setting('webcomic_nav_above', array(
			'default' => true,
			'transport' => 'postMessage'
		)); $customize->add_control('webcomic_nav_above', array(
			'type' => 'checkbox',
			'label' => __('Show navigation above the webcomic', 'inkblot'),
			'section' => 'webcomic_nav'
		));
		
		$customize->add_setting('webcomic_nav_below', array(
			'default' => true,
			'transport' => 'postMessage'
		)); $customize->add_control('webcomic_nav_below', array(
			'type' => 'checkbox',
			'label' => __('Show navigation below the webcomic', 'inkblot'),
			'section' => 'webcomic_nav'
		));
		
		/* ----- Webcomic Archives ------------------------------------------ */
		
		$customize->add_section('webcomic_archives', array(
			'title' => __('Archives', 'inkblot'),
			'panel' => 'webcomic',
			'priority' => 20
		));
		
		$customize->add_setting('webcomic_archive_order', array('default' => 'ASC'));
		$customize->add_control('webcomic_archive_order', array(
			'type' => 'radio',
			'label' => __('Posts are displayed in', 'inkblot'),
			'section' => 'webcomic_archives',
			'choices' => array(
				'ASC' => __('Chronological order', 'inkblot'),
				'DESC' => __('Reverse chronlogical order', 'inkblot')
				
			)
		));
		
		$customize->add_setting('webcomic_archive_size', array('default' => 'large'));
		$customize->add_control('webcomic_archive_size', array(
			'type' => 'select',
			'label' => __('Show previews of this size ', 'inkblot'),
			'section' => 'webcomic_archives',
			'choices' => $sizes
		));
		
		/* ----- Webcomic Front Page ---------------------------------------- */
		
		$customize->add_section('webcomic_home', array(
			'title' => __('Front Page', 'inkblot'),
			'panel' => 'webcomic',
			'priority' => 25
		));
		
		$customize->add_setting('webcomic_home_order', array('default' => 'DESC'));
		$customize->add_control('webcomic_home_order', array(
			'type' => 'radio',
			'label' => __('Front page displays', 'inkblot'),
			'section' => 'webcomic_home',
			'choices' => array(
				'' => __('Nothing', 'inkblot'),
				'DESC' => __('Your latest webcomic', 'inkblot'),
				'ASC' => __('Your first webcomic', 'inkblot')
			)
		));
		
		$customize->add_setting('webcomic_home_collection', array('default' => ''));
		$customize->add_control('webcomic_home_collection', array(
			'type' => 'select',
			'label' => __('From this collection', 'inkblot'),
			'section' => 'webcomic_home',
			'choices' => $collections
		));
		
		$customize->add_setting('webcomic_front_page_comments', array('default' => false));
		$customize->add_control('webcomic_front_page_comments', array(
			'type' => 'checkbox',
			'label' => __('Include comments on the front page', 'inkblot'),
			'section' => 'webcomic_home'
		));
		
		$customize->add_setting('webcomic_front_page_transcripts', array('default' => false));
		$customize->add_control('webcomic_front_page_transcripts', array(
			'type' => 'checkbox',
			'label' => __('Include transcripts on the front page', 'inkblot'),
			'section' => 'webcomic_home'
		));
	}
}
endif;

if ( ! function_exists('inkblot_customize_controls_enqueue_scripts')) :
/**
 * Enqueue custom control scripts.
 * 
 * @return void
 * @hook customize_controls_enqueue_scripts
 */
function inkblot_customize_controls_enqueue_scripts() {
	wp_enqueue_style('inkblot-customize-controls', get_template_directory_uri() . '/-/css/customize-controls.css');
	wp_enqueue_script('inkblot-customize-controls', get_template_directory_uri() . '/-/js/customize-controls.js', array('jquery', 'customize-controls'), '', true);
}
endif;