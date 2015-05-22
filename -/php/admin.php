<?php
/**
 * Contains the Inkblot administrative functions.
 * 
 * @package Inkblot
 */

add_action('add_meta_boxes', 'inkblot_add_meta_boxes');
add_action('wp_insert_post', 'inkblot_insert_page', 10, 2);
add_action('customize_register', 'inkblot_customize_register');
add_action('delete_attachment', 'inkblot_delete_attachment', 10, 1);
add_action('admin_enqueue_scripts', 'inkblot_admin_enqueue_scripts', 10, 1);
add_action('customize_controls_enqueue_scripts', 'inkblot_customize_controls_enqueue_scripts');
add_action('customize_controls_print_footer_scripts', 'inkblot_customize_controls_print_footer_scripts');

add_filter('display_media_states', 'inkblot_display_media_states', 10, 1);

if ( ! function_exists('inkblot_add_meta_boxes')) :
/**
 * Add page meta boxes.
 */
function inkblot_add_meta_boxes() {
	add_meta_box('inkblot-template-options', __('Template Options', 'webcomic'), 'inkblot_template_options', 'page', 'normal', 'high');
}
endif;

if ( ! function_exists('inkblot_insert_page')) :
/**
 * Save metadata with pages.
 * 
 * @param integer $id ID of the page to update.
 * @param object $post Post object to update.
 */
function inkblot_insert_page($id, $post) {
	if (
		isset($_POST['inkblot_template_options'])
		and 'page' === $post->post_type
		and ( ! defined('DOING_AUTOSAVE') or ! DOING_AUTOSAVE)
		and wp_verify_nonce($_POST['inkblot_template_options'], 'inkblot_template_options')
		and current_user_can('edit_page', $id)
	) {
		if ($post_id = wp_is_post_revision($id)) {
			$id = $post_id;
		}
		
		$keys = array(
			'inkblot_avatar',
			'inkblot_sidebars',
			'inkblot_show_webcomics',
			'inkblot_webcomic_group',
			'inkblot_webcomic_image',
			'inkblot_webcomic_order',
			'inkblot_webcomic_comments',
			'inkblot_webcomic_term_order',
			'inkblot_webcomic_term_image',
			'inkblot_webcomic_term_target',
			'inkblot_webcomic_transcripts'
		);
		
		foreach ($keys as $key) {
			if (isset($_POST[$key])) {
				update_post_meta($id, $key, $_POST[$key]);
			} else {
				delete_post_meta($id, $key);
			}
		}
	}
}
endif;

if ( ! function_exists('inkblot_customize_register')) :
/**
 * Register theme customization sections, settings, and controls.
 * 
 * @param object $customize WordPress theme customization object.
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
	
	/* ----- Schemes -------------------------------------------------------- */
	
	$customize->add_section('inkblot_scheme', array(
		'title' => __('Scheme', 'inkblot'),
		'description' => __('Schemes are collections of predefined theme options, including layout, fonts, and colors.', 'inkblot'),
		'priority' => 20
	));
	
	$customize->add_setting('scheme', array(
		'default' => 'inkblot',
		'transport' => 'postMessage'
	)); $customize->add_control('scheme', array(
		'type' => 'radio',
		'section' => 'inkblot_scheme',
		'priority' => 0,
		'choices' => inkblot_get_scheme_choices()
	));
	
	/* ----- Layout --------------------------------------------------------- */
	
	$customize->add_section('inkblot_layout', array(
		'title' => __('Layout', 'inkblot'),
		'priority' => 25
	));
	
	$customize->add_setting('content', array(
		'default' => 'one-column',
		'transport' => 'postMessage'
	)); $customize->add_control('content', array(
		'type' => 'select',
		'section' => 'inkblot_layout',
		'priority' => 5,
		'choices' => array(
			'one-column' => __('No sidebars', 'inkblot'),
			'two-column content-left' => __('Content on left, 1 sidebar', 'inkblot'),
			'two-column content-right' => __('Content on right, 1 sidebar', 'inkblot'),
			'three-column content-left' => __('Content on left, 2 sidebars', 'inkblot'),
			'three-column content-right' => __('Content on right, 2 sidebars', 'inkblot'),
			'three-column content-center' => __('Content centered, 2 sidebars', 'inkblot'),
			'four-column content-far-left' => __('Content on far left, 3 sidebars', 'inkblot'),
			'four-column content-left' => __('Content on left, 3 sidebars', 'inkblot'),
			'four-column content-right' => __('Content on right, 3 sidebars', 'inkblot'),
			'four-column content-far-right' => __('Content on far right, 3 sidebars', 'inkblot'),
		)
	));
	
	$customize->add_setting('sidebar1_width', array(
		'default' => 25,
		'transport' => 'postMessage'
	)); $customize->add_control('sidebar1_width', array(
		'type' => 'number',
		'label' => __('Primary Sidebar Width', 'inkblot'),
		'description' => __('The percentage width of the primary sidebar.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 10,
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
		'description' => __('The percentage width of the secondary sidebar.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 15,
		'input_attrs' => array(
			'min' => 5,
			'max' => 45,
			'step' => 1
		)
	));
	
	$customize->add_setting('sidebar3_width', array(
		'default' => 25,
		'transport' => 'postMessage'
	)); $customize->add_control('sidebar3_width', array(
		'type' => 'number',
		'label' => __('Tertiary Sidebar Width', 'inkblot'),
		'description' => __('The percentage width of the tertiary sidebar.', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 20,
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
		'priority' => 25,
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
		'priority' => 30,
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
		'description' => __('Defines the maximum pixel width for post content. <a href="//codex.wordpress.org/Content_Width">Learn more at the WordPress Codex.</a>', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 35,
		'input_attr' => array(
			'min' => 0,
			'step' => 10
		)
	));
	
	$customize->add_setting('responsive_width', array(
		'default' => 0,
		'transport' => 'postMessage'
	));
	$customize->add_control('responsive_width', array(
		'type' => 'number',
		'description' => __('Responsive features will only be used when your page is less than or equal to this pixel width.', 'inkblot'),
		'label' => __('Responsive Width', 'inkblot'),
		'section' => 'inkblot_layout',
		'priority' => 40,
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
		'label' => __('Size', 'inkblot'),
		'section' => 'inkblot_fonts',
		'priority' => 5,
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
			'priority' => 10,
			'choices' => $fonts
		));
		
		$customize->add_setting('header_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('header_font', array(
			'type' => 'select',
			'label' => __('Header', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 15,
			'choices' => $fonts
		));
		
		$customize->add_setting('page_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('page_font', array(
			'type' => 'select',
			'label' => __('Page', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 20,
			'choices' => $fonts
		));
		
		$customize->add_setting('title_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('title_font', array(
			'type' => 'select',
			'label' => __('Titles', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 25,
			'choices' => $fonts
		));
		
		$customize->add_setting('trim_font', array(
			'default' => '',
			'transport' => 'postMessage'
		)); $customize->add_control('trim_font', array(
			'type' => 'select',
			'label' => __('Trim', 'inkblot'),
			'section' => 'inkblot_fonts',
			'priority' => 30,
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
		'default' => '#000000',
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
		'default' => '#000000',
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
		'default' => '#000000',
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
		'default' => '#767676',
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
		'default' => '#000000',
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
		'default' => '#767676',
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
		'default' => '#000000',
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
		'default' => '#767676',
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
	
	$customize->add_setting('header_post_thumbnail', array('default' => false));
	$customize->add_control('header_post_thumbnail', array(
		'type' => 'checkbox',
		'label' => __('Use featured image for single posts', 'inkblot'),
		'section' => 'header_image'
	));
	
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
		$customize->add_setting("sidebar-{$sidebar}-columns", array(
			'default' => ! in_array($sidebar, array('primary-sidebar', 'secondary-sidebar', 'tertiary-sidebar')),
			'transport' => 'postMessage'
		)); $customize->add_control("sidebar-{$sidebar}-columns", array(
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
	
	$customize->add_setting('favicon', array(
		'default' => '',
		'transport' => 'postMessage'
	)); $favicon = new WP_Customize_Image_Control($customize, 'favicon', array(
		'label' => __('Site Icon', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'context' => 'inkblot-favicon',
		'description' => __('The site icon may be displayed by web browsers and mobile devices in various ways.', 'inkblot')
	)); $customize->add_control($favicon);
	
	$customize->add_setting('post_thumbnail_width', array(
		'default' => 144,
		'transport' => 'postMessage'
	)); $customize->add_control('post_thumbnail_width', array(
		'type' => 'number',
		'label' => __('Post Thumbnail Width', 'inkblot'),
		'description' => __('The pixel width of post thumbnails. Changes will not affect previously uploaded thumbnails.', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'input_attr' => array(
			'min' => 0,
			'step' => 5
		)
	));
	
	$customize->add_setting('post_thumbnail_height', array(
		'default' => 144,
		'transport' => 'postMessage'
	)); $customize->add_control('post_thumbnail_height', array(
		'type' => 'number',
		'label' => __('Post Thumbnail Height', 'inkblot'),
		'description' => __('The pixel height of post thumbnails. Changes will not affect previously uploaded thumbnails.', 'inkblot'),
		'section' => 'inkblot_miscellanea',
		'input_attr' => array(
			'min' => 0,
			'step' => 5
		)
	));
	
	$customize->add_setting('uninstall', array(
		'default' => false,
		'transport' => 'postMessage'
	)); $customize->add_control('uninstall', array(
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
		'description' => __('Enter CSS rules below to further customize your theme.', 'inkblot'),
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
			'label' => __('Place webcomic in the main column', 'inkblot'),
			'section' => 'webcomic_layout'
		));
		
		$customize->add_setting('webcomic_resize', array(
			'default' => true,
			'transport' => 'postMessage'
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

if ( ! function_exists('inkblot_delete_attachment')) :
/**
 * Update image theme mods when attachments are deleted.
 * 
 * @param integer $id ID of the attachment to delete.
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

if ( ! function_exists('inkblot_customize_controls_enqueue_scripts')) :
/**
 * Enqueue custom control scripts.
 */
function inkblot_customize_controls_enqueue_scripts() {
	wp_enqueue_style('inkblot-customize-controls', get_template_directory_uri() . '/-/css/customize-controls.css');
	wp_enqueue_script('inkblot-customize-controls', get_template_directory_uri() . '/-/js/customize-controls.js', array('jquery', 'customize-controls'), '', true);
}
endif;

if ( ! function_exists('inkblot_customize_controls_print_footer_scripts')) :
/**
 * Add customization elements to the bottom of the customizer.
 *
 * These elements have a number of data attributes that are used for various
 * dynamic customizer controls.
 */
function inkblot_customize_controls_print_footer_scripts() {
	$themes = require get_template_directory() . '/-/php/schemes.php';
	
	foreach ($themes as $id => $meta) : ?>
		
		<wbr class="inkblot-scheme <?php print $id; ?>"
			<?php foreach ($meta['mods'] as $key => $value) : ?>
				
				data-<?php print str_replace('_', '-', $key); ?>="<?php print $value; ?>"
				
			<?php endforeach; ?>
		>
			
	<?php endforeach;
}
endif;

if ( ! function_exists('inkblot_display_media_states')) :
/**
 * Display relevant status for theme media.
 * 
 * @param array $states List of media states.
 * @return array
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

if ( ! function_exists('inkblot_template_options')) :
/**
 * Render the webcomic template meta box.
 * 
 * @param object $page Current page object.
 * @uses webcomic()
 * @uses get_webcomic_collections()
 */
function inkblot_template_options($page) {
	wp_nonce_field('inkblot_template_options', 'inkblot_template_options');
	
	$inkblot_avatar = get_post_meta($page->ID, 'inkblot_avatar', true);
	$inkblot_sidebars = get_post_meta($page->ID, 'inkblot_sidebars', true);
	
	$show_webcomics = get_post_meta($page->ID, 'inkblot_show_webcomics', true);
	$webcomic_group = get_post_meta($page->ID, 'inkblot_webcomic_group', true);
	$webcomic_image = get_post_meta($page->ID, 'inkblot_webcomic_image', true);
	$webcomic_order = get_post_meta($page->ID, 'inkblot_webcomic_order', true);
	$webcomic_comments = get_post_meta($page->ID, 'inkblot_webcomic_comments', true);
	$webcomic_term_image = get_post_meta($page->ID, 'inkblot_webcomic_term_image', true);
	$webcomic_term_order = get_post_meta($page->ID, 'inkblot_webcomic_term_order', true);
	$webcomic_term_target = get_post_meta($page->ID, 'inkblot_webcomic_term_target', true);
	$webcomic_transcripts = get_post_meta($page->ID, 'inkblot_webcomic_transcripts', true); ?>
	
	<div data-inkblot-template-options="none">
		<p><strong><?php _e('Select one of the following templates from the Page Attributes meta box to modify template-specific options:', 'inkblot'); ?></strong></p>
		<ul>
			<?php
				foreach (get_page_templates() as $k => $v) {
					if (in_array($v, array(
						'template/contributors.php',
						'template/full-width.php',
						'template/webcomic-archive.php',
						'template/webcomic-homepage.php',
						'template/webcomic-infinite.php'
					))) {
						printf('<li>%s</li>', $k);
					}
				}
			?>
		</ul>
	</div>
	<div data-inkblot-template-options="template/contributors.php">
		<h4><?php _e('Contributors', 'inkblot'); ?></h4>
		<p>
			<input id="inkblot_avatar" name="inkblot_avatar" type="number" min="0" step="8" class="small-text" value="<?php print (int) $inkblot_avatar; ?>">
			<label for="inkblot_avatar"><?php _e('Avatar size', 'inkblot'); ?></label>
		</p>
	</div>
	
	<div data-inkblot-template-options="template/full-width.php">
		<h4><?php _e('Full Width', 'inkblot'); ?></h4>
		<p>
			<input id="inkblot_sidebars" name="inkblot_sidebars" type="checkbox" value="1"<?php checked($inkblot_sidebars); ?>>
			<label for="inkblot_sidebars"><?php _e('Show sidebars below the page content', 'inkblot'); ?></label>
		</p>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-archive.php">
		<h4><?php _e('Webcomic Archive', 'inkblot'); ?></h4>
		
		<?php if (webcomic()) : ?>
			
			<?php
				$select_img = $select_term_img = '';
				
				foreach (get_intermediate_image_sizes() as $size) {
					$select_img .= sprintf('<option value="%s"%s>%s</option>',
						$size,
						selected($size, $webcomic_image, false),
						$size
					);
					
					$select_term_img .= sprintf('<option value="%s"%s>%s</option>',
					 	$size,
					 	selected($size, $webcomic_term_image, false),
					 	$size
					 );
				}
			?>
			
			<p>
				
				<?php
					printf(__('<label for="inkblot_webcomic_archive_group">Show %1$s</label> <label for="inkblot_webcomic_archive_term_image">links as %2$s</label> <label for="inkblot_webcomic_archive_term_target">pointing to %3$s</label> <label for="inkblot_webcomic_archive_term_order">starting with the %4$s term</label>', 'inkblot'),
						sprintf('
							<select id="inkblot_webcomic_archive_group" name="inkblot_webcomic_group">
								<option value="">%s</option>
								<option value="storyline"%s>%s</option>
								<option value="character"%s>%s</option>
							</select>',
							__('collection', 'inkblot'),
							selected('storyline', $webcomic_group, false),
							__('storyline', 'inkblot'),
							selected('character', $webcomic_group, false),
							__('character', 'inkblot')
						),
						sprintf('
							<select id="inkblot_webcomic_archive_term_image" name="inkblot_webcomic_term_image">
								<option value="">%s</option>
								%s
							</select>',
							__('text', 'inkblot'),
							$select_term_img
						),
						sprintf('
							<select id="inkblot_webcomic_archive_term_target" name="inkblot_webcomic_term_target">
								<option value="archive"%s>%s</option>
								<option value="first"%s>%s</option>
								<option value="last"%s>%s</option>
								<option value="random"%s>%s</option>
							</select>',
							selected('archive', $webcomic_term_target, false),
							__('an archive page', 'inkblot'),
							selected('first', $webcomic_term_target, false),
							__('the first webcomic', 'inkblot'),
							selected('last', $webcomic_term_target, false),
							__('the last webcomic', 'inkblot'),
							selected('random', $webcomic_term_target, false),
							__('a random webcomic', 'inkblot')
						),
						sprintf('
							<select id="inkblot_webcomic_archive_term_order" name="inkblot_webcomic_term_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_term_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_term_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			<p>
				<input type="checkbox" name="inkblot_show_webcomics" value="1"<?php checked($show_webcomics); ?>>
				
				<?php
					printf(__('<label for="inkblot_webcomic_archive_image">Show webcomic links as %1$s</label> <label for="inkblot_webcomic_archive_order">starting with the %2$s webcomic</label>', 'inkblot'),
						sprintf('
							<select id="inkblot_webcomic_archive_image" name="inkblot_webcomic_image">
								<option value="">%s</option>
								%s
							</select>',
							__('text', 'inkblot'),
							$select_img
						),
						sprintf('
							<select id="inkblot_webcomic_archive_order" name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %1$s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="https://wordpress.org/plugins/webcomic">Webcomic</a>'); ?></p>
			
		<?php endif; ?>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-homepage.php">
		<h4><?php _e('Webcomic Homepage', 'inkblot'); ?></h4>
		
		<?php if (webcomic()) : ?>
			
			<p>
				
				<?php
					printf(__('<label for="inkblot_webcomic_homepage_order">Show the %1$s webcomic</label>', 'inkblot'),
						sprintf('
							<select id="inkblot_webcomic_homepage_order" name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			<p>
				<input id="inkblot_webcomic_transcripts" name="inkblot_webcomic_transcripts" type="checkbox" value="1"<?php checked($webcomic_transcripts); ?>>
				<label for="inkblot_webcomic_transcripts"><?php _e('Show transcripts', 'inkblot'); ?></label>
			</p>
			<p>
				<input id="inkblot_webcomic_comments" name="inkblot_webcomic_comments" type="checkbox" value="1"<?php checked($webcomic_comments); ?>>
				<label for="inkblot_webcomic_comments"><?php _e('Show comments', 'inkblot'); ?></label>
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %1$s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="https://wordpress.org/plugins/webcomic">Webcomic</a>'); ?></p>
			
		<?php endif; ?>
	</div>
	
	<div data-inkblot-template-options="template/webcomic-infinite.php">
		<h4><?php _e('Webcomic Infinite', 'inkblot'); ?></h4>
		
		<?php if (webcomic()) : ?>
			<p>
				
				<?php
					printf(__('<label for="inkblot_webcomic_infinite_order">Start with the %1$s webcomic</label>', 'inkblot'),
						sprintf('
							<select id="inkblot_webcomic_infinite_order" name="inkblot_webcomic_order">
								<option value="ASC"%s>%s</option>
								<option value="DESC"%s>%s</option>
							</select>',
							selected('ASC', $webcomic_order, false),
							__('first', 'inkblot'),
							selected('DESC', $webcomic_order, false),
							__('latest', 'inkblot')
						)
					);
				?>
				
			</p>
			
		<?php else : ?>
			
			<p><?php printf(__('It looks like %1$s is not installed or activated. This template will not affect the appearance of this page.', 'inkblot'), '<a href="https://wordpress.org/plugins/webcomic">Webcomic</a>'); ?></p>
			<input type="hidden" name="inkblot_webcomic_group" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_group', true); ?>">
			<input type="hidden" name="inkblot_webcomic_image" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_image', true); ?>">
			<input type="hidden" name="inkblot_webcomic_order" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_order', true); ?>">
			<input type="hidden" name="inkblot_webcomic_comments" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_comments', true); ?>">
			<input type="hidden" name="inkblot_webcomic_term_image" value="<?php print get_post_meta($page->ID, 'inkblot_webcomic_term_image', true); ?>">
			
		<?php endif; ?>
	</div>
	<?php
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

if ( ! function_exists('inkblot_get_scheme_choices')) :
/**
 * Return Inkblot themes.
 * 
 * @return array
 */
function inkblot_get_scheme_choices() {
	$themes = require get_template_directory() . '/-/php/schemes.php';
	$options = array();
	
	foreach ($themes as $key => $meta) {
		$options[$key] = $meta['name'];
	}
	
	asort($options);
	
	return $options;
}
endif;