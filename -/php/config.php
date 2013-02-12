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
	/** Register hooks.
	 * 
	 * @uses InkblotConfig::customize_register()
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ), 10, 1 );
	}
	
	/** Register theme customization sections, settings, and controls.
	 * 
	 * Because we're not using the standard callbacks (to avoid a lot of
	 * ugly inline CSS in our page headers) we have to change the
	 * transport for a number of default theme mods, then handle preview
	 * updates in the `-/js/admin-preview.js` file.
	 * 
	 * @param object $customize WordPress theme customization object.
	 * @uses Control_InkblotNumber
	 * @uses Control_InkblotPageBG
	 * @uses Control_InkblotWebcomicNavigationImage
	 */
	public function customize_register( $customize ) {
		foreach ( array( 'blogname', 'blogdescription', 'header_textcolor', 'header_image', 'background_color', 'background_image', 'background_repeat', 'background_position_x', 'background_attachment' ) as $v ) {
			$customize->get_setting( $v )->transport = 'postMessage';
		}
		
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
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'min_width', array(
			'label'    => __( 'Minimum Width (px)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 10
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'max_width', array(
			'label'    => __( 'Maximum Width (px)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 10
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'sidebar1_width', array(
			'label'    => __( 'Primary Sidebar Width (%)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 5
		) ) );
		
		$customize->add_control( new Control_InkblotNumber( $customize, 'sidebar2_width', array(
			'label'    => __( 'Secondary Sidebar Width (%)', 'inkblot' ),
			'section'  => 'inkblot_layout',
			'min'      => 0,
			'step'     => 5
		) ) );
		
		$customize->add_section( 'inkblot_page_background_image', array( 'title' => __( 'Page Background Image', 'inkblot' ), 'priority' => 90 ) );
		$customize->add_setting( 'page_background_image', array( 'default' => '', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_repeat', array( 'default' => 'repeat', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_position_x', array( 'default' => 'left', 'transport' => 'postMessage' ) );
		$customize->add_setting( 'page_background_attachment', array( 'default' => 'scroll', 'transport' => 'postMessage' ) );
		
		$customize->add_control( new Control_InkblotPageBG( $customize ) );
		
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
	
	/** Inkblot page background control.
	 * 
	 * @package Inkblot
	 */
	class Control_InkblotPageBG extends WP_Customize_Image_Control {
		/** Initialize the control.
		 * 
		 * @uses WP_Customize_Image_Control::__contruct()
		 */
		public function __construct( $manager ) {
			parent::__construct( $manager, 'page_background_image', array(
				'label'   => __( 'Page Background Image', 'inkblot' ),
				'section' => 'inkblot_page_background_image',
				'context' => 'inkblot-page-background'
			) );
		}
		
		/** Render the uploaded images tab.
		 * 
		 * @uses WP_Customize_Image_Control::print_tab_image()
		 */
		public function tab_uploaded() {
			$backgrounds = get_posts( array(
				'post_type'  => 'attachment',
				'meta_key'   => '_wp_attachment_context',
				'meta_value' => 'inkblot-page-background',
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
	
	/** Inkblot first webcomic image control.
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