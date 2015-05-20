<?php
/**
 * Returns an array of preconfigured layout options. Used in the Customizer to
 * dynamically update the theme layout based on the layout_theme selection.
 * 
 * @pckage Inkblot
 * @return array
 */
return array(
	'inkblot' => array(
		'name' => __('Inkblot', 'inkblot'),
		'data' => array(
			'layout' => array(
				'content' => 'one-column',
				'sidebar1_width' => 25,
				'sidebar2_width' => 25,
				'sidebar3_width' => 25,
				'min_width' => 0,
				'max_width' => 0,
				'responsive_width' => 0
			),
			'font' => array(
				'font_size' => 100,
				'font' => '',
				'header_font' => '',
				'page_font' => '',
				'title_font' => '',
				'trim_font' => ''
			),
			'color' => array(
				'background_color' => '#fff',
				'page_color' => '#fff',
				'trim_color' => '#000',
				'text_color' => '#000',
				'header_textcolor' => '#000',
				'page_text_color' => '#000',
				'trim_text_color' => '#fff',
				'link_color' => '#767676',
				'link_hover_color' => '#000',
				'page_link_color' => '#767676',
				'page_link_hover_color' => '#000',
				'trim_link_color' => '#767676',
				'trim_link_hover_color' => '#fff'
			)
		)
	),
);