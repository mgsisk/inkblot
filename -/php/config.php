<?php
/** Contains the InkblotConfig and custom control classes.
 * 
 * @package Inkblot
 */

/** Handle configuration tasks.
 * 
 * @package Inkblot
 */
class InkblotConfig extends Inkblot {
	/** Store subtlepatterns.com JSON data.
	 * @var array
	 */
	private $patterns = array();
	
	/** Register hooks.
	 * 
	 * @uses InkblotConfig::customize_register()
	 * @uses InkblotConfig::customize_controls_print_scripts()
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ), 10, 1 );
		add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ) );
	}
	
	/** Register theme customization sections, settings, and controls.
	 * 
	 * Because we're not using the standard callbacks (to avoid a lot of
	 * ugly inline CSS in our page headers) we have to change the
	 * transport for a number of default theme mods, then handle preview
	 * updates in the `-/js/admin-preview.js` file.
	 * 
	 * @param object $customize WordPress theme customization object.
	 * @uses Control_InkblotBG
	 * @uses Control_InkblotNumber
	 * @uses Control_InkblotWebcomicNavigationImage
	 * @hook customize_register
	 */
	public function customize_register( $customize ) {
		foreach ( array( 'blogname', 'blogdescription', 'header_textcolor', 'header_image', 'background_color', 'background_image', 'background_repeat', 'background_position_x', 'background_attachment' ) as $v ) {
			$customize->get_setting( $v )->transport = 'postMessage';
		}
		
		if ( $patterns = wp_remote_get( 'https://api.github.com/repos/subtlepatterns/SubtlePatterns/git/trees/master' ) and !is_wp_error( $patterns ) ) {
			$this->patterns = json_decode( $patterns[ 'body' ] );
		}
		
		$bg = new Control_InkblotBG( $customize, 'background_image', array(
			'label'   => __( 'Background Image', 'inkblot' ),
			'section' => 'background_image',
			'context' => 'custom-background'
		) );
		
		$bg->add_tab( 'inkblot_bg_more', __( 'More&hellip;', 'inkblot' ), array( $this, 'tab_subtle_patterns' ) );
		
		$customize->add_control( $bg );
		
		$customize->add_section( 'inkblot_fonts', array( 'title' => __( 'Fonts', 'inkblot' ), 'priority' => 30 ) );
		$customize->add_setting( 'font_size', array( 'default' => 100, 'transport' => 'postMessage' ) );
		
		if ( $google_fonts = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyDGeJxu3MGJVi5RiUw4rQ3Jt_Q4VtSOnyE' ) and !is_wp_error( $google_fonts ) and $google_fonts = json_decode( $google_fonts[ 'body' ] ) ) {
			$fonts = array( __( '(default)', 'inkblot' ) );
			
			foreach ( $google_fonts->items as $font ) {
				$fonts[ sprintf( '%s:%s', str_replace( ' ', '+', $font->family ), join( ',', $font->variants ) ) ] = $font->family;
			}
			
			$customize->add_setting( 'page_font', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'trim_font', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'title_font', array( 'default' => '', 'transport' => 'postMessage' ) );
			
			$customize->add_control( 'page_font', array(
				'type'     => 'select',
				'label'    => __( 'Page Font', 'inkblot' ),
				'section'  => 'inkblot_fonts',
				'choices'  => $fonts
			) );
			
			$customize->add_control( 'title_font', array(
				'type'     => 'select',
				'label'    => __( 'Title Font', 'inkblot' ),
				'section'  => 'inkblot_fonts',
				'choices'  => $fonts
			) );
			
			$customize->add_control( 'trim_font', array(
				'type'     => 'select',
				'label'    => __( 'Trim Font', 'inkblot' ),
				'section'  => 'inkblot_fonts',
				'choices'  => $fonts
			) );
		}
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'font_size', array(
			'label'    => __( 'Font Size (%)', 'inkblot' ),
			'section'  => 'inkblot_fonts',
			'priority' => 15,
			'min'      => 25,
			'max'      => 200,
			'step'     => 5
		) ) );
		
		$customize->add_setting( 'page_color', array( 'default' => '#fff', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'text_color', array( 'default' => '#333', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'link_color', array( 'default' => '#888', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'link_hover_color', array( 'default' => '#333', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'trim_color', array( 'default' => '#333', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'trim_text_color', array( 'default' => '#ccc', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'trim_link_color', array( 'default' => '#888', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'trim_link_hover_color', array( 'default' => '#ccc', 'transport' => 'postMessage' ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'page_color', array(
			'label'   => __( 'Page Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 15
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'text_color', array(
			'label'   => __( 'Text Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 20
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'link_color', array(
			'label'   => __( 'Link Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 25
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'link_hover_color', array(
			'label'   => __( 'Link Hover Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 30
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'trim_color', array(
			'label'   => __( 'Trim Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 35
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'trim_text_color', array(
			'label'   => __( 'Trim Text Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 40
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'trim_link_color', array(
			'label'   => __( 'Trim Link Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 45
		) ) );
		
		$customize->add_control( new WP_Customize_Color_Control( $customize, 'trim_link_hover_color', array(
			'label'   => __( 'Trim Link Hover Color', 'inkblot' ),
			'section' => 'colors',
			'priority' => 50
		) ) );
		
		$customize->add_section( 'inkblot_layout', array( 'title' => __( 'Layout', 'inkblot' ), 'priority' => 20 ) );
		$customize->add_setting( 'responsive', array( 'default' => true ) );
		$customize->add_setting( 'content', array( 'default' => 'one-column', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'responsive_width', array( 'default' => 640 ) );
		$customize->add_setting( 'min_width', array( 'default' => 0, 'transport' => 'postMessage' ) );
		$customize->add_setting( 'max_width', array( 'default' => 0, 'transport' => 'postMessage' ) );
		$customize->add_setting( 'sidebar1_width', array( 'default' => 25, 'transport' => 'postMessage' ) );
		$customize->add_setting( 'sidebar2_width', array( 'default' => 25, 'transport' => 'postMessage' ) );
		
		$customize->add_control( 'responsive', array(
			'type'    => 'checkbox',
			'label'   => __( 'Enable responsive features for small screens', 'inkblot' ),
			'section' => 'inkblot_layout'
		) );
		
		$customize->add_control( 'content', array(
			'type'     => 'radio',
			'label'    => __( 'Content', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'choices'  => array(
				'one-column'          => __( 'No sidebars', 'inkblot' ),
				'two-column-left'     => __( 'Content on left (one sidebar)', 'inkblot' ),
				'two-column-right'    => __( 'Content on right (one sidebar)', 'inkblot' ),
				'three-column-left'   => __( 'Content on left (two sidebars)', 'inkblot' ),
				'three-column-right'  => __( 'Content on right (two sidebars)', 'inkblot' ),
				'three-column-center' => __( 'Content centered (two sidebars)', 'inkblot' )
			)
		) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'responsive_width', array(
			'label'    => __( 'Responsive Width (px)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 10,
			'priority' => 10
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'min_width', array(
			'label'    => __( 'Minimum Width (px)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 10,
			'priority' => 15
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'max_width', array(
			'label'    => __( 'Maximum Width (px)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 10,
			'priority' => 15
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'sidebar1_width', array(
			'label'    => __( 'Primary Sidebar Width (%)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 5,
			'priority' => 20
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'sidebar2_width', array(
			'label'    => __( 'Secondary Sidebar Width (%)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 5,
			'priority' => 20
		) ) );
		
		$customize->add_section( 'inkblot_page_background_image', array( 'title' => __( 'Page Background Image', 'inkblot' ), 'priority' => 90 ) );
		$customize->add_setting( 'page_background_image', array( 'default' => '', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_repeat', array( 'default' => 'repeat', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_position_x', array( 'default' => 'left', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_attachment', array( 'default' => 'scroll', 'transport' => 'postMessage' ) );
		
		$pagebg = new Control_InkblotBG( $customize, 'page_background_image', array(
			'label'   => __( 'Page Background Image', 'inkblot' ),
			'section' => 'inkblot_page_background_image',
			'context' => 'inkblot-page-background'
		) );
		
		$pagebg->add_tab( 'inkblot_pagebg_more', __( 'More&hellip;', 'inkblot' ), array( $this, 'tab_subtle_patterns' ) );
		
		$customize->add_control( $pagebg );
		
		$customize->add_control( 'page_background_repeat', array(
			'type'    => 'radio',
			'label'   => __( 'Page Background Repeat', 'inkblot' ),
			'section' => 'inkblot_page_background_image',
			'choices' => array(
				'no-repeat' => __( 'No Repeat', 'inkblot' ),
				'repeat'    => __( 'Tile', 'inkblot' ),
				'repeat-x'  => __( 'Tile Horizontally', 'inkblot' ),
				'repeat-y'  => __( 'Tile Vertically', 'inkblot' )
			)
		) );
		
		$customize->add_control( 'page_background_position_x', array(
			'type'    => 'radio',
			'label'   => __( 'Page Background Position', 'inkblot' ),
			'section' => 'inkblot_page_background_image',
			'choices' => array(
				'left'   => __( 'Left', 'inkblot' ),
				'center' => __( 'Center', 'inkblot' ),
				'right'  => __( 'Right', 'inkblot' )
			)
		) );
		
		$customize->add_control( 'page_background_attachment', array(
			'type'    => 'radio',
			'label'   => __( 'Page Background Attachment', 'inkblot' ),
			'section' => 'inkblot_page_background_image',
			'choices' => array(
				'fixed' => __( 'Fixed', 'inkblot' ),
				'scroll'    => __( 'Scroll', 'inkblot' )
			)
		) );
		
		$customize->add_section( 'inkblot_miscellanea', array( 'title' => __( 'Miscellanea', 'inkblot' ), 'priority' => 999 ) );
		$customize->add_setting( 'header_width', array( 'default' => 960 ) );
		$customize->add_setting( 'header_height', array( 'default' => 240 ) );
		$customize->add_setting( 'post_thumbnail_width', array( 'default' => 144 ) );
		$customize->add_setting( 'post_thumbnail_height', array( 'default' => 144 ) );
		$customize->add_setting( 'content_width', array( 'default' => 480 ) );
		$customize->add_setting( 'uninstall', array( 'default' => false ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'header_width', array(
			'label'    => __( 'Header Image Width (px)', 'inkblot' ),
			'section'  => 'inkblot_miscellanea',
			'min'      => 0,
			'step'     => 10
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'header_height', array(
			'label'    => __( 'Header Image Height (px)', 'inkblot' ),
			'section'  => 'inkblot_miscellanea',
			'min'      => 0,
			'step'     => 10
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'post_thumbnail_width', array(
			'label'    => __( 'Post Thumbnail Width (px)', 'inkblot' ),
			'section'  => 'inkblot_miscellanea',
			'min'      => 0,
			'step'     => 5
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'post_thumbnail_height', array(
			'label'    => __( 'Post Thumbnail Height (px)', 'inkblot' ),
			'section'  => 'inkblot_miscellanea',
			'min'      => 0,
			'step'     => 5
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'content_width', array(
			'label'    => __( 'Content Width (px)', 'inkblot' ),
			'section'  => 'inkblot_miscellanea',
			'min'      => 0,
			'step'     => 5
		) ) );
		
		$customize->add_control( 'uninstall', array(
			'type'    => 'checkbox',
			'label'   => __( 'Remove theme modifications when changing themes', 'inkblot' ),
			'section' => 'inkblot_miscellanea'
		) );
		
		if ( webcomic() ) {
			$sizes       = array( '' => __( '(none)', 'inkblot' ) );
			$collections = array( __( '(any collection)', 'inkblot' ) );
			
			foreach ( get_intermediate_image_sizes() as $size ) {
				$sizes[ $size ] = $size;
			}
			
			$sizes[ 'full' ] = __( 'full', 'inkblot' );
			
			foreach ( get_webcomic_collections( true ) as $k => $v ) {
				$collections[ $k ] = $v[ 'name' ];
			}
			
			$customize->add_section( 'webcomic', array( 'title' => __( 'Webcomic', 'inkblot' ), 'priority' => 990 ) );
			$customize->add_setting( 'webcomic_content', array( 'default' => false, 'transport' => 'postMessage' ) );
			$customize->add_setting( 'webcomic_resize', array( 'default' => true, 'transport' => 'postMessage' ) );
			$customize->add_setting( 'webcomic_home_hook', array( 'default' => true, 'transport' => 'postMessage' ) );
			$customize->add_setting( 'webcomic_home_order', array( 'default' => 'DESC' ) );
			$customize->add_setting( 'webcomic_home_collection', array( 'default' => '' ) );
			$customize->add_setting( 'webcomic_nav_above', array( 'default' => true, 'transport' => 'postMessage' ) );
			$customize->add_setting( 'webcomic_nav_below', array( 'default' => true, 'transport' => 'postMessage' ) );
			$customize->add_setting( 'webcomic_nav_link', array( 'default' => '' ) );
			$customize->add_setting( 'webcomic_archive_size', array( 'default' => 'large' ) );
			$customize->add_setting( 'first_webcomic_image', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'last_webcomic_image', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'previous_webcomic_image', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'next_webcomic_image', array( 'default' => '', 'transport' => 'postMessage' ) );
			$customize->add_setting( 'random_webcomic_image', array( 'default' => '', 'transport' => 'postMessage' ) );
			
			$customize->add_control( 'webcomic_resize', array(
				'type'     => 'checkbox',
				'label'    => __( 'Resize webcomics to fit the available space', 'inkblot' ),
				'section'  => 'webcomic',
				'priority' => 0
			) );
			
			$customize->add_control( 'webcomic_content', array(
				'type'     => 'checkbox',
				'label'    => __( 'Place webcomic in the content column', 'inkblot' ),
				'section'  => 'webcomic',
				'priority' => 5
			) );
			
			$customize->add_control( 'webcomic_nav_above', array(
				'type'     => 'checkbox',
				'label'    => __( 'Show navigation above the webcomic', 'inkblot' ),
				'section'  => 'webcomic',
				'priority' => 10
			) );
			
			$customize->add_control( 'webcomic_nav_below', array(
				'type'     => 'checkbox',
				'label'    => __( 'Show navigation below the webcomic', 'inkblot' ),
				'section'  => 'webcomic',
				'priority' => 15
			) );
			
			$customize->add_control( 'webcomic_home_hook', array(
				'type'     => 'checkbox',
				'label'    => __( 'Show webcomic on the front page', 'inkblot' ),
				'section'  => 'webcomic',
				'priority' => 20
			) );
			
			$customize->add_control( 'webcomic_home_order', array(
				'type'     => 'select',
				'label'    => __( 'Start with this webcomic on the front page:', 'inkblot' ),
				'section'  => 'webcomic',
				'choices'  => array(
					'DESC' => __( 'Most Recently Published', 'inkblot' ),
					'ASC'  => __( 'Chronologically First', 'inkblot' )
				),
				'priority' => 25
			) );
			
			$customize->add_control( 'webcomic_home_collection', array(
				'type'     => 'select',
				'label'    => __( 'Show webcomics from this collection on the front page:', 'inkblot' ),
				'section'  => 'webcomic',
				'choices'  => $collections,
				'priority' => 30
			) );
			
			$customize->add_control( 'webcomic_nav_link', array(
				'type'     => 'select',
				'label'    => __( 'Link webcomic attachments to:', 'inkblot' ),
				'section'  => 'webcomic',
				'choices'  => array(
					''         => __( 'Nothing', 'inkblot' ),
					'next'     => __( 'Next webcomic', 'inkblot' ),
					'previous' => __( 'Previous webcomic', 'inkblot' )
				),
				'priority' => 30
			) );
			
			$customize->add_control( 'webcomic_archive_size', array(
				'type'     => 'select',
				'label'    => __( 'Show webcomic previews of this size on archive pages:', 'inkblot' ),
				'section'  => 'webcomic',
				'choices'  => $sizes,
				'priority' => 30
			) );
			
			$customize->add_control( new Control_InkblotWebcomicNavigationImage( $customize, 'first_webcomic_image', array(
				'label'    => __( 'First Webcomic Image', 'inkblot' ),
				'priority' => 35
			) ) );
			
			$customize->add_control( new Control_InkblotWebcomicNavigationImage( $customize, 'last_webcomic_image', array(
				'label'    => __( 'Last Webcomic Image', 'inkblot' ),
				'priority' => 40
			) ) );
			
			$customize->add_control( new Control_InkblotWebcomicNavigationImage( $customize, 'previous_webcomic_image', array(
				'label'    => __( 'Previous Webcomic Image', 'inkblot' ),
				'priority' => 45
			) ) );
			
			$customize->add_control( new Control_InkblotWebcomicNavigationImage( $customize, 'next_webcomic_image', array(
				'label'    => __( 'Next Webcomic Image', 'inkblot' ),
				'priority' => 50
			) ) );
			
			$customize->add_control( new Control_InkblotWebcomicNavigationImage( $customize, 'random_webcomic_image', array(
				'label'    => __( 'Random Webcomic Image', 'inkblot' ),
				'priority' => 55
			) ) );
		}
	}
	
	/** Enqueue custom control scripts.
	 * 
	 * @uses Inkblot::$url
	 * @hook customize_controls_print_scripts
	 */
	public function customize_controls_print_scripts() {
		wp_enqueue_script( 'inkblot-customizer-controls', self::$url . '-/js/admin-customizer-controls.js', array( 'jquery' ), '', true );
	}
	
	/** Render the SubtlePatterns.com background image tab.
	 * 
	 * @uses Inkblot::$url
	 */
	public function tab_subtle_patterns() {
		if ( $this->patterns ) {
			printf( '<blockquote data-inkblot-admin-url="%s"><p><small>%s</small></p></blockquote>', admin_url(), sprintf( __( '%1$s is created and curated by %2$s.', 'inkblot' ), '<a href="http://subtlepatterns.com" target="_blank">Subtle Patterns</a>', '<a href="http://atlemo.com" target="_blank">Atle Mo</a>' ) );
			
			foreach ( $this->patterns->tree as $pattern ) {
				if ( 'png' === substr( $pattern->path, -3 ) ) {
					printf( '<a href="#" class="thumbnail" data-customize-image-value="https://raw.github.com/subtlepatterns/SubtlePatterns/master/%1$s"><img src="%2$s-/img/subtlepattern.png" title="%3$s" style="background:url(https://raw.github.com/subtlepatterns/SubtlePatterns/master/%1$s)"></a>',
						$pattern->path,
						self::$url,
						str_replace( array( '.png', '_'), array( '', ' ' ), $pattern->path )
					);
				}
			}
		} else {
			printf( '<blockquote><p><small>%s</small></p></blockquote>', __( "Sorry, we couldn't connect to <a href='http://subtlepatterns.com' target='_blank'>subtlepatterns.com</a>", 'inkblot' ) );
		}
	}
	
	/** Import external background images.
	 * 
	 * @uses Inkblot::$dir
	 */
	public static function ajax_sideload_image( $src, $type, $context ) {
		if ( $tmp = download_url( $src ) and !is_wp_error( $tmp ) ) {
			$file = array(
				'name'     => pathinfo( parse_url( $src, PHP_URL_PATH ), PATHINFO_BASENAME ),
				'tmp_name' => $tmp
			);
			
			if ( $id = media_handle_sideload( $file, 0, $file[ 'name' ] ) and !is_wp_error( $id ) ) {
				set_theme_mod( $type, wp_get_attachment_url( $id ) );
				
				update_post_meta( $id, '_wp_attachment_context', $context );
				
				if ( 'custom-background' === $context ) {
					update_post_meta( $id, '_wp_attachment_is_custom_background', 'inkblot' );
				}
				
				echo wp_get_attachment_url( $id );
			} else {
				set_theme_mod( $type, '' );
			}
		}
	}
}

if ( class_exists( 'WP_Customize_Control' ) ) {
	/** Number input control.
	 * 
	 * @package Inkblot
	 */
	class Control_InkblotNumber extends WP_Customize_Control {
		/** Minimum value for input.
		 * @var mixed
		 */
		public $min = false;
		
		/** Maximum value for input.
		 * @var mixed
		 */
		public $max = false;
		
		/** Step value for input.
		 * @var mixed
		 */
		public $step = false;
		
		/** Render the control's content.
		 */
		protected function render_content() {
			$min  = is_int( $this->min ) ? sprintf( ' min="%s"', esc_attr( $this->min ) ) : '';
			$max  = is_int( $this->max ) ? sprintf( ' max="%s"', esc_attr( $this->max ) ) : '';
			$step = is_int( $this->step ) ? sprintf( ' step="%s"', esc_attr( $this->step ) ) : '';
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="number" value="<?php echo esc_attr( $this->value() ); ?>"<?php echo $min, $max, $step, ' '; $this->link(); ?>>
			</label>
			<?php
		}
	}
	
	/** Inkblot background control.
	 * 
	 * @package Inkblot
	 */
	class Control_InkblotBG extends WP_Customize_Image_Control {
		/** Initialize the control.
		 * 
		 * @uses WP_Customize_Image_Control::__contruct()
		 */
		public function __construct( $manager, $id, $args ) {
			parent::__construct( $manager, $id, $args );
		}
		
		/** Render the uploaded images tab.
		 * 
		 * @uses WP_Customize_Image_Control::print_tab_image()
		 */
		public function tab_uploaded() {
			$backgrounds = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attachment_context',
				'meta_value' => $this->context,
				'orderby'    => 'none',
				'nopaging'   => true
			) );
			?><div class="uploaded-target"></div><?php
			
			if ( $backgrounds ) {
				foreach ( ( array ) $backgrounds as $background ) {
					$this->print_tab_image( esc_url_raw( $background->guid ) );
				}
			}
		}
	}
	
	/** Inkblot webcomic navigation image control.
	 * 
	 * @package Inkblot
	 */
	class Control_InkblotWebcomicNavigationImage extends WP_Customize_Image_Control {
		/** Initialize the control.
		 * 
		 * @uses WP_Customize_Image_Control::__contruct()
		 */
		public function __construct( $manager, $id, $args ) {
			parent::__construct( $manager, $id, array_merge( array(
				'section' => 'webcomic',
				'context' => 'inkblot-webcomic-navigation-image'
			), $args ) );
		}
		
		/** Render the uploaded images tab.
		 * 
		 * @uses WP_Customize_Image_Control::print_tab_image()
		 */
		public function tab_uploaded() {
			$backgrounds = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attachment_context',
				'meta_value' => 'inkblot-webcomic-navigation-image',
				'orderby'    => 'none',
				'nopaging'   => true
			) );
			?><div class="uploaded-target"></div><?php
			
			if ( $backgrounds ) {
				foreach ( ( array ) $backgrounds as $background ) {
					$this->print_tab_image( esc_url_raw( $background->guid ) );
				}
			}
		}
	}
}