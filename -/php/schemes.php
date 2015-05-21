<?php
/**
 * Returns an array of preconfigured theme options. Used in the Customizer to
 * dynamically update various theme options.
 * 
 * @pckage Inkblot
 * @return array
 */
return array(
	'inkblot' => array(
		'name' => __('Inkblot', 'inkblot'),
		'data' => array(
			'content' => 'one-column',
			'sidebar1_width' => 25,
			'sidebar2_width' => 25,
			'sidebar3_width' => 25,
			'min_width' => 0,
			'max_width' => 0,
			'responsive_width' => 0,
			'font_size' => 100,
			'font' => '',
			'header_font' => '',
			'page_font' => '',
			'title_font' => '',
			'trim_font' => '',
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
	),
	'archimedes' => array( // https://color.adobe.com/zen-and-tea-color-theme-337112/
		'name' => __('Archimedes', 'inkblot'),
		'data' => array(
			'content' => 'two-column content-left',
			'sidebar1_width' => 38,
			'sidebar2_width' => 15,
			'sidebar3_width' => 15,
			'min_width' => 782,
			'max_width' => 782,
			'responsive_width' => 0,
			'font_size' => 85,
			'font' => 'Dosis:200,300,regular,500,600,700,800',
			'header_font' => 'Sigmar+One:regular',
			'page_font' => '',
			'title_font' => 'Sigmar+One:regular',
			'trim_font' => 'Sigmar+One:regular',
			'background_color' => '#11222b',
			'page_color' => '#f6fee1',
			'trim_color' => '#95aa67',
			'text_color' => '#e2f0d7',
			'header_textcolor' => '#11222b',
			'page_text_color' => '#11222b',
			'trim_text_color' => '#e2f0d7',
			'link_color' => '#95aa67',
			'link_hover_color' => '#bdd588',
			'page_link_color' => '#95aa67',
			'page_link_hover_color' => '#bdd588',
			'trim_link_color' => '#bdd588',
			'trim_link_hover_color' => '#f6fee1'
		)
	),
	)
);