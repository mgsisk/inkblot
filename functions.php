<?php
/** Inkblot theme.
 * 
 * @package Inkblot
 */

/** Set the content width.
 * @var integer
 */
$content_width = isset( $content_width ) ? $content_width : get_theme_mod( 'content_width', 480 );

/** Initialize the theme.
 * 
 * @package Inkblot
 */
class Inkblot {
	/** Internal version number.
	 * @var string
	 */
	protected static $version = '4';
	
	/** Absolute path to the theme directory.
	 * @var string
	 */
	protected static $dir = '';
	
	/** URL to the theme directory.
	 * @var string
	 */
	protected static $url = '';
	
	/** Whether the theme is being previewed.
	 * @var boolean
	 */
	protected static $preview = false;
	
	/** Set class properties and register hooks.
	 * 
	 * @uses Inkblot::$dir
	 * @uses Inkblot::$url
	 * @uses Inkblot::customize_preview_init()
	 * @uses Inkblot::head()
	 * @uses Inkblot::title()
	 * @uses Inkblot::loaded()
	 * @uses Inkblot::widgets_init()
	 * @uses Inkblot::customize_head()
	 * @uses Inkblot::wp_enqueue_scripts()
	 * @uses Inkblot::after_setup_theme()
	 * @uses Inkblot::customize_register()
	 * @uses Inkblot::body_class()
	 * @uses InkblotConfig
	 * @uses inkblot_head()
	 * @uses inkblot_title()
	 * @uses inkblot_wp_loaded()
	 * @uses inkblot_widgets_init()
	 * @uses inkblot_customize_head()
	 * @uses inkblot_enqueue_scripts()
	 * @uses inkblot_after_setup_theme()
	 * @uses inkblot_customize_register()
	 * @uses inkblot_body_class()
	 */
	public function __construct() {
		self::$dir = trailingslashit( get_template_directory() );
		self::$url = trailingslashit( get_template_directory_uri() );
		
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'wp_head', function_exists( 'inkblot_head' ) ? 'inkblot_head' : array( $this, 'wp_head' ), 0 );
		add_action( 'wp_title', function_exists( 'inkblot_title' ) ? 'inkblot_title' : array( $this, 'wp_title' ), 10, 3 );
		add_action( 'wp_loaded', function_exists( 'inkblot_wp_loaded' ) ? 'inkblot_wp_loaded' : array( $this, 'wp_loaded' ) );
		add_action( 'widgets_init', function_exists( 'inkblot_widgets_init' ) ? 'inkblot_widgets_init' : array( $this, 'widgets_init' ) );
		add_action( 'wp_head', function_exists( 'inkblot_customize_head' ) ? 'inkblot_customize_head' : array( $this, 'customize_head' ) );
		add_action( 'wp_enqueue_scripts', function_exists( 'inkblot_enqueue_scripts' ) ? 'inkblot_enqueue_scripts' : array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'after_setup_theme', function_exists( 'inkblot_after_setup_theme' ) ? 'inkblot_after_setup_theme' : array( $this, 'after_setup_theme' ) );
		add_action( 'customize_register', function_exists( 'inkblot_customize_register' ) ? 'inkblot_customize_register' : array( $this, 'customize_register' ), 10, 1 );
		
		add_filter( 'body_class', function_exists( 'inkblot_body_class' ) ? 'inkblot_body_class' : array( $this, 'body_class' ), 10, 2 );
		
		require_once self::$dir . '-/php/tags.php';
	}
	
	/** Enqueue dynamic preview script.
	 * 
	 * @uses Inkblot::$url
	 * @hook customize_preview_init
	 */
	public function customize_preview_init() {
		wp_enqueue_script( 'inkblot-customizer', self::$url . '-/js/admin-customizer.js', '', '', true );
	}
	
	/** Render the <head> portion of the page.
	 * 
	 * @uses inkblot_page_description()
	 * @hook wp_head
	 */
	public function wp_head() { ?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="description" content="<?php inkblot_page_description(); ?>">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
		<title><?php wp_title( '|' ); ?></title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}
	
	/** Add additional information to page titles.
	 * 
	 * @param string $title The original title.
	 * @param string $sep Separator to place between title elements.
	 * @param string $location Where to place the separator.
	 * @return string
	 * @hook wp_title
	 */
	public function wp_title( $title, $sep, $location ) {
		global $page, $paged;
		
		if ( is_feed() ) {
			return $title;
		}
		
		$name        = get_bloginfo( 'name' );
		$title       = explode( " {$sep} ", $title );
		$pages       = 2 <= max( $page, $paged ) ? sprintf( __( 'Page %s', 'inkblot' ), max( $paged, $page ) ) : '';
		$description = ( is_home() or is_front_page() ) ? get_bloginfo( 'description', 'display' ) : '';
		
		if ( 'right' === $location ) {
			array_unshift( $title, $description, $name, $pages );
		} else {
			array_push( $title, $pages, $name, $description );
		}
		
		$title = array_filter( $title );
		
		return join( " {$sep} ", $title );
	}
	
	/** Generate custom stylesheet.
	 * 
	 * It would be better to handle this with an `init` hook, but we
	 * need `get_theme_mod` to function properly for the theme
	 * customizer.
	 * 
	 * @uses Inkblot::$dir
	 * @action wp_loaded
	 */
	public function wp_loaded() {
		if ( isset( $_GET[ 'inkblot_styles' ] ) ) {
			header( 'Content-Type: text/css' );
			
			require_once self::$dir . '-/php/style.php';
			
			die;
		}
	}
	
	/** Register widgetized areas.
	 * 
	 * @hook widgets_init
	 */
	public function widgets_init() {
		foreach ( array(
			__( 'Primary Sidebar', 'inkblot' )   => __( 'Used in both two and three-column layouts. You can change theme layout from the Appearance > Customize page.', 'inkblot' ),
			__( 'Secondary Sidebar', 'inkblot' ) => __( 'Used in three-column layouts only. You can change theme layout from the Appearance > Customize page.', 'inkblot' ),
			__( 'Document Header', 'inkblot' )   => __( 'Located at the very top of the page, outside of the #page wrapper.', 'inkblot' ),
			__( 'Document Footer', 'inkblot' )   => __( 'Located at the very bottom of the page, outside of the #page wrapper.', 'inkblot' ),
			__( 'Page Header', 'inkblot' )       => __( 'Located near the top of the page, just inside the #page wrapper.', 'inkblot' ),
			__( 'Page Footer', 'inkblot' )       => __( 'Located near the bottom of the page, just inside the #page wrapper.', 'inkblot' ),
			__( 'Content Header', 'inkblot' )    => __( 'Located near the top of the page, just inside the #content wrapper.', 'inkblot' ),
			__( 'Content Footer', 'inkblot' )    => __( 'Located near the bottom of the page, just inside the #content wrapper.', 'inkblot' ),
			__( 'Comment Header', 'inkblot' )    => __( 'Located above the comments list for a post, just inside the #comments wrapper.', 'inkblot' ),
			__( 'Comment Footer', 'inkblot' )    => __( 'Located below the comments list for a post, just inside the #comments wrapper.', 'inkblot' )
		) as $k => $v ) {
			register_sidebar( array(
				'id'            => 'sidebar-' . sanitize_title( $k ),
				'name'          => $k,
				'description'   => $v,
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>'
			) );
		}
	}
	
	/** Include customized styles inline.
	 * 
	 * We need to do this while previewing to ensure customizations show
	 * up properly if the user navigates through the theme preview.
	 * 
	 * @uses Inkblot::$dir
	 * @hook wp_head
	 */
	public function customize_head() {
		if ( self::$preview ) {
			echo '<style>';
			
			require_once self::$dir . '-/php/style.php';
			
			echo '</style>';
		}
	}
	
	/** Enqueue scripts and stylesheets.
	 * 
	 * @uses Inkblot::$url
	 * @uses Inkblot::$preview
	 * @hook wp_enqueue_scripts
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'inkblot-theme', add_query_arg( array( 'inkblot_styles' => '' ), home_url( '/' ) ) );
		
		if ( get_theme_mod( 'page_font' ) or get_theme_mod( 'title_font' ) or get_theme_mod( 'trim_font' ) ) {
			$proto = is_ssl() ? 'https' : 'http';
			$fonts = array_filter( array(
				get_theme_mod( 'page_font' ),
				get_theme_mod( 'title_font' ),
				get_theme_mod( 'trim_font' )
			) );
			
			wp_enqueue_style( 'inkblot-fonts', add_query_arg( array( 'family' => join( '|', $fonts ) ), "{$proto}://fonts.googleapis.com/css" ) );
		}
		
		wp_register_script( 'jquery', '', '', '', true);
		
		if ( get_theme_mod( 'responsive', true ) ) {
			wp_enqueue_script( 'inkblot-responsive', self::$url . '-/js/responsive.js', array( 'jquery' ), '', true );
		}
		
		if ( is_singular() and comments_open() and get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	
	/** Setup theme features.
	 * 
	 * @uses Inkblot::$dir
	 * @hook after_setup_theme
	 */
	public function after_setup_theme() {
		load_theme_textdomain( 'inkblot', self::$dir . '-/i18n' );
		
		add_editor_style();
		
		add_filter( 'use_default_gallery_style', '__return_false' );
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
		
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'status', 'quote', 'video' ) );
		add_theme_support( 'custom-background', array(
			'default-color'    => 'e8e8e8',
			'wp-head-callback' => '__return_false'
		) );
		add_theme_support( 'custom-header', array(
			'width'                  => get_theme_mod( 'header_width', 960 ),
			'height'                 => get_theme_mod( 'header_height', 240 ),
			'flex-width'             => true,
			'flex-height'            => true,
			'default-text-color'     => '333',
			'wp-head-callback'       => '__return_false',
			'admin-head-callback'    => array( 'InkblotAdmin', 'admin_head' ),
			'admin-preview-callback' => array( 'InkblotAdmin', 'admin_preview' )
		) );
		
		register_nav_menu( 'primary', __( 'Primary Menu', 'inkblot' ) );
		
		set_post_thumbnail_size( get_theme_mod( 'post_thumbnail_width', 144 ), get_theme_mod( 'post_thumbnail_height', 144 ) );
	}
	
	/** Set the Inkblot::$preview variable.
	 * 
	 * @param object $customize WordPress theme customization object.
	 * @uses Inkblot::preview_scripts
	 * @hook customize_register
	 */
	public function customize_register( $customize ) {
		self::$preview = $customize->is_preview();
	}
	
	/** Add the content class to the body tag.
	 * 
	 * @param array $classes Array of body classes.
	 * @param mixed $class Additional classes passed to body_class().
	 * @return array
	 * @hook body_class
	 */
	public function body_class( $classes, $class ) {
		$classes[] = get_theme_mod( 'content', 'one-column' );
		
		return $classes;
	}
}

if ( is_admin() ) { // Load and instantiate the administrative class.
	require_once dirname( __FILE__ ) . '/-/php/admin.php'; new InkblotAdmin;
} else { // Instantiate the standard class.
	new Inkblot;
}