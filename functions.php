<?php
//
// Base Loaders
//

/** Load text domain for translations */
load_theme_textdomain( 'inkblot' );

/** Register widgetized areas. */
register_sidebars( 2, array( 'name' => 'Sidebar %d' ) );
register_sidebar( array( 'name' => 'Page Top', 'id' => 'page-top' ) );
register_sidebar( array( 'name' => 'Body Top', 'id' => 'body-top' ) );
register_sidebar( array( 'name' => 'Content Top', 'id' => 'content-top' ) );
register_sidebar( array( 'name' => 'Content Insert', 'id' => 'content-insert' ) );
register_sidebar( array( 'name' => 'Content Bottom', 'id' => 'content-bottom' ) );
register_sidebar( array( 'name' => 'Body Bottom', 'id' => 'body-bottom' ) );
register_sidebar( array( 'name' => 'Page Bottom', 'id' => 'page-bottom' ) );

/** Enqueue necessary javascript. */
function inkblot_template_redirect() {
	wp_enqueue_script( 'inkblot-scripts', get_bloginfo( 'template_directory' ) . '/includes/js/scripts.js', array( 'jquery' ) );
	
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
} add_action( 'template_redirect', 'inkblot_template_redirect' );

/** Ensure WebComic is installed and activated. */
function inkblot_get_header() { if ( !function_exists( 'get_the_comic' ) ) die( sprintf( __( 'It looks like <a href="%s">WebComic</a> is not installed. WebComic must be installed and activated before Inkblot can be used.', 'inkblot' ), 'http://maikeruon.com/webcomic/' ) ); } add_action( 'get_header', 'inkblot_get_header' );

/** Echo <head> content */
function inkblot_head() {
	echo '
<meta http-equiv="content-type" content="' . get_bloginfo( 'html_type' ) . 'charset=' . get_bloginfo( 'charset' ) . '" />
<title>' . wp_title( ' | ', false, 'right' ) . get_bloginfo( 'name' ) . '</title>
<meta name="description" content="' . get_bloginfo( 'description' ) . '" />' . "\n" .
'<link rel="stylesheet" href="' . get_bloginfo( 'stylesheet_url' ) . '" />' . "\n" . 
'<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . "\n";
} add_action( 'wp_head', 'inkblot_head', 0 ); automatic_feed_links();


//
// Template Tags & Option Retrieval
//

/**
 * Verifies that the specified sidebar is active.
 * 
 * This function verifies that the specified sidebar is active,
 * returning true if it has any active widgets.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param str $index The sidebar slug.
 * @return True if the sidebar has widgets.
 */
function is_sidebar_active( $index ) {
  global $wp_registered_sidebars;

  $widgetized = wp_get_sidebars_widgets();
		 
  if ( $widgetized[ $index ] )
  	return true;
}

/**
 * Returns the correct 'link' parameter for the_comic based on user settings.
 * 
 * @package Inkblot
 * @since 2.0.0
 */
function get_inkblot_comic_link() {
	if ( get_option( 'inkblot_comic_link' ) )
		return get_option( 'inkblot_comic_link_direction' );
}

/**
 * Displays beginning <div> container tags and sidebars as necessary.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param str|arr $class One or more classes to add to the class list.
 */
function inkblot_begin_content( $class = false ) {
	$layout = get_inkblot_layout();
	
	get_sidebar( 'body-top' );
	
	if ( $layout->comic ) {
		if ( $layout->special )
			get_sidebar();
		
		$classes = array( 'content' );
		
		if ( 3 == $layout->columns )
			$classes[] = 'medium';
		elseif ( 1 == $layout->columns )
			$classes[] = 'clear';
		
		$classes[] = $layout->content;
		$classes[] = 'content-main';
	
		//Add any user provided classes
		if ( $class ) {
			if ( !is_array( $class ) )
				$class = preg_split( '#\s+#', $class );
			
			$classes = array_merge( $classes, $class );
		}
		
		echo '<div class="' . join( ' ', $classes ) . '">';
	}
}

/**
 * Displays beginning <div> container tags and sidebars as necessary.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param str|arr $class One or more classes to add to the class list.
 */
function inkblot_inside_content( $class = false ) {
	global $post;
	
	$layout = get_inkblot_layout();
	
	if ( !$layout ) {
		echo '<div class="content alignleft content-main"><div class="interior">';
	} elseif ( $layout->comic ) {
		echo '<div class="interior">';
	} else {
		if ( $layout->special ) {
			$temp = $post; //Save any existing post information
			get_sidebar();
			$post = $temp; //Reload our previously saved post information
		}
		
		$classes = array( 'content' );
		
		if ( 3 == $layout->columns )
			$classes[] = 'medium';
		elseif ( 1 == $layout->columns )
			$classes[] = 'clear';
		
		$classes[] = $layout->content;
		$classes[] = 'content-main';
	
		//Add any user provided classes
		if ( $class ) {
			if ( !is_array( $class ) )
				$class = preg_split( '#\s+#', $class );
			
			$classes = array_merge( $classes, $class );
		}
		
		echo '<div class="' . join( ' ', $classes ) . '"><div class="interior">';
	}
}

/**
 * Displays ending <div> container tags and sidebars as necessary.
 * 
 * @package Inkblot
 * @since 2.0.0
 */
function inkblot_end_content() {
	get_sidebar( 'content-bottom' );
	
	echo '</div> <!-- .interior --> </div> <!-- .content-main -->';
	get_sidebar();
	get_sidebar( 2 );
	
	get_sidebar( 'body-bottom' );
}

/**
 * Displays the correct alignment class based on the site alignment setting.
 * 
 * @package Inkblot
 * @since 2.0.0
 */
function inkblot_site_alignment() {
	$layout = get_inkblot_layout();
	
	if ( !$layout || 2 == $layout->position )
		echo 'aligncenter';
	elseif ( 1 == $layout->position )
		echo 'alignright';
	else
		echo 'alignleft';
}

/**
 * Returns an object containing Inkblot layout information.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @return obj Object containing Inkblot layout information.
 */
function get_inkblot_layout() {
	if ( $lop = get_option( 'inkblot_layout' ) ) {
		$layout = array();
		
		if ( strstr( $lop, '1c' ) ) {
			$layout[ 'columns' ] = 1;
			$layout[ 'comic' ]   = 0;
			$layout[ 'content' ] = 'auto-width';
		} elseif ( strstr( $lop, '2c' ) ) {
			$layout[ 'columns' ] = 2;
			$layout[ 'comic' ]   = ( strstr( $lop, 'i' ) ) ? 1 : 0;
			$layout[ 'content' ] = ( strstr( $lop, 'r' ) ) ? 'alignleft' : 'alignright';
		} else {
			$layout[ 'columns' ] = 3;
			$layout[ 'comic' ]   = ( strstr( $lop, 'i' ) ) ? 1 : 0;
			
			if ( strstr( $lop, 'l' ) )
				$layout[ 'content' ] = 'alignright';
			else
				$layout[ 'content' ] = 'alignleft';
			
			if ( '3coc' == $lop || '3cic' == $lop )
				$layout[ 'special' ] = true;
		}
		
		$layout[ 'position' ] = get_option( 'inkblot_layout_position' );
		
		return ( object ) $layout;
	}
}



//
// Semantic Classes
//

/**
 * Displays semantic sidebar classes.
 * 
 * This function generates and displays a set of semantic classes
 * for the Inkblot sidebars based on user settings.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param bool $right The right sidebar. Defaults to false (left sidebar).
 * @param str|arr $class One or more classes to add to the class list.
 */
function inkblot_sidebar_class( $right = false, $class = false ) {
	$layout = get_inkblot_layout();
	
	//Must be a sidebar
	$classes   = array( 'sidebar' );
	
	//Add the alignment class
	if ( $layout->special )
		$class[] = 'alignleft';
	else
		$classes[] = ( $right ) ? 'alignright' : 'alignleft';
	
	//Add the size class
	if ( 3 == $layout->columns )
		$classes[] = 'x-small';
	
	//Add the hide class
	if ( ( !$layout && $right ) || 1 == $layout->columns || ( 2 == $layout->columns && $right ) )
		$classes[] = 'hide';
	
	//Add the sidebar specifier class
	$classes[] = ( $right ) ? 'sidebar-two' : 'sidebar-one';
	
	//Add any user provided classes
	if ( $class ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		
		$classes = array_merge( $classes, $class );
	}
	
	echo 'class="' . join( ' ', $classes ) . '"';
}

/**
 * Displays semantic comic navigation classes.
 * 
 * This function generates and displays a set of semantic classes
 * for Inkblot's comic navigation blocks based on user settings.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param bool $above The navigation block above the comic.
 * @param str|arr $class One or more classes to add to the class lsit.
 */
function inkblot_comic_navi_class( $above = false, $class = false ) {
	$navi = get_option( 'inkblot_comic_navigation' );
	
	//Must be comic navigation
	$classes   = array( 'navi' );
	$classes[] = 'navi-comic';
	$classes[] = ( $above ) ? 'navi-comic-above' : 'navi-comic-below';
	
	//Hide the top navigation bar based on user settings
	if ( $above && !$navi )
		$classes[] = 'hide';
	
	//Hide the bottom navigation bar based on user settings
	if ( !$above && 1 == $navi )
		$classes[] = 'hide';
	
	//Add any user provided classes
	if ( $class ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		
		$classes = array_merge( $classes, $class );
	}
	
	echo 'class="' . join( ' ', $classes ) . '"';
}

/**
 * Displays semantic site navigation classes.
 * 
 * This function generates and displays a set of semantic classes
 * for Inkblot's site navigation block based on user settings.
 * 
 * @package Inkblot
 * @since 2.0.0
 * 
 * @param str|arr $class One or more classes to add to the class lsit.
 */
function inkblot_site_navi_class( $class = false ) {
	$navi = get_option( 'inkblot_site_navigation' );
	
	//Must be site navigation
	$classes   = array( 'navi' );
	$classes[] = 'navi-site';
	
	if ( !$navi )
		$classes[] = 'hide';
	
	//Add any user provided classes
	if ( $class ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		
		$classes = array_merge( $classes, $class );
	}
	
	echo 'class="' . join( ' ', $classes ) . '"';
}



//
// Theme Settings
//

/**
 * Creates the default Inkblot settings.
 * 
 * This funciton should only run when the theme is first activated.
 * It will attempt to create all of the default Inkblots stetings.
 * 
 * @package Inkblot
 * @since 1.0.0
 */
if ( !get_option( 'inkblot_version' ) || '2.1.0' != get_option( 'inkblot_version' ) ) {
	function inkblot_install(){
		add_option( 'inkblot_layout', '2cor' );
		add_option( 'inkblot_layout_position', '2' );
		add_option( 'inkblot_site_navigation', '1' );
		add_option( 'inkblot_comic_frontpage', '1' );
		add_option( 'inkblot_comic_frontpage_series', '' );
		add_option( 'inkblot_comic_frontpage_order', 'DESC' );
		add_option( 'inkblot_comic_navigation', '' );
		add_option( 'inkblot_comic_limit', '' );
		add_option( 'inkblot_comic_link', '' );
		add_option( 'inkblot_comic_link_direction', 'next' );
		add_option( 'inkblot_comic_archives', '1' );
		add_option( 'inkblot_comic_archives_size', 'thumb' );
		add_option( 'inkblot_comic_embed', '' );
		add_option( 'inkblot_comic_embed_size', 'thumb' );
		add_option( 'inkblot_comic_embed_format', 'html' );
		
		/** Add or update the 'inkblot_version' setting */
		if ( get_option( 'inkblot_version' ) )
			update_option( 'inkblot_version', '2.1.0' );
		else
			add_option( 'inkblot_version', '2.1.0' );
		
		echo '<div class="updated fade"><p>' . sprintf( __( 'Thanks for choosing Inkblot! Please check the <a href="%s">setting page</a> to configure the theme.', 'inkblot' ), 'themes.php?page=functions.php' ) . '</p></div>';
	} add_action( 'admin_notices', 'inkblot_install' );
}

/**
 * Registers theme settings.
 * 
 * @package Inkblot
 * @since 2.0.0
 */
function inkblot_admin_init() {
	register_setting( 'inkblot_options', 'inkblot_layout' );
	register_setting( 'inkblot_options', 'inkblot_layout_position' );
	register_setting( 'inkblot_options', 'inkblot_site_navigation' );
	register_setting( 'inkblot_options', 'inkblot_comic_frontpage' );
	register_setting( 'inkblot_options', 'inkblot_comic_frontpage_series' );
	register_setting( 'inkblot_options', 'inkblot_comic_frontpage_order' );
	register_setting( 'inkblot_options', 'inkblot_comic_navigation' );
	register_setting( 'inkblot_options', 'inkblot_comic_limit' );
	register_setting( 'inkblot_options', 'inkblot_comic_link' );
	register_setting( 'inkblot_options', 'inkblot_comic_link_direction' );
	register_setting( 'inkblot_options', 'inkblot_comic_archives' );
	register_setting( 'inkblot_options', 'inkblot_comic_archives_size' );
	register_setting( 'inkblot_options', 'inkblot_comic_embed' );
	register_setting( 'inkblot_options', 'inkblot_comic_embed_size' );
	register_setting( 'inkblot_options', 'inkblot_comic_embed_format' );
} add_action( 'admin_init', 'inkblot_admin_init' );

/**
 * Registers the Inkblot administrative page.
 * 
 * @package Inkblot
 * @since 1.0.0
 */
function inkblot_theme_page(){
	add_theme_page( __( 'Inkblot', 'inkblot' ), __( 'Inkblot', 'inkblot' ), 'edit_themes', basename( __FILE__ ), 'inkblot_theme_page_display' );
	
	if ( function_exists( 'add_meta_box' ) )
		add_meta_box( 'inkblot', __( 'Inkblot', 'inkblot' ), 'inkblot_page_meta_box', 'page', 'normal', 'high' );
} add_action( 'admin_menu', 'inkblot_theme_page' );

/**
 * Displays the Inkblot settings page.
 * 
 * @package Inkblot
 * @since 1.0.0
 */
function inkblot_theme_page_display() {
	if ( isset( $_REQUEST[ 'updated' ] ) )
		echo '<div class="updated fade"><p><strong>' . __( 'Settings saved.', 'inkblot' ) . '</strong></p></div>';
	
	$layout     = get_inkblot_layout();
	$categories = get_comic_category( true );
?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Inkblot', 'inkblot' ); ?></h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'inkblot_save_settings' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><label for="inkblot_comic_frontpage_order"><?php _e( 'Comics', 'inkblot' ); ?></label></th>
					<td>
						<input type="checkbox" name="inkblot_comic_frontpage" value="1"<?php if ( get_option( 'inkblot_comic_frontpage' ) ) echo ' checked="checked"'; ?> />
						<label>
							<?php _e( 'Show the', 'inkblot' ); ?>
							<select name="inkblot_comic_frontpage_order" id="inkblot_comic_frontpage_order" style="vertical-align:middle">
								<option value="DESC"<?php if ( 'DESC' == get_option( 'inkblot_comic_frontpage_order' ) ) echo 'selected="selected"'; ?>><?php _e( 'most recent', 'inkblot' ); ?></option>
								<option value="ASC"<?php if ( 'ASC' == get_option( 'inkblot_comic_frontpage_order' ) ) echo 'selected="selected"'; ?>><?php _e( 'first', 'inkblot' ); ?></option>
							</select>
						</label>
						<label>
							<?php _e( 'comic from', 'inkblot' ); ?>
							<select name="inkblot_comic_frontpage_series" style="vertical-align:middle">
								<option value="0"><?php _e( 'any series', 'inkblot' ); ?></option>
							<?php foreach ( $categories as $cat ) { ?>
								<option value="<?php echo $cat ?>"<?php if ( get_option( 'inkblot_comic_frontpage_series' ) == $cat ) echo ' selected="selected"'; echo '>' . get_term_field( 'name', $cat, 'chapter' ); ?></option>
							<?php } ?>
							</select>
							<?php _e( 'on the front page', 'inkblot' ); ?>
						</label>
						<p>
							<input type="checkbox" name="inkblot_comic_archives" value="1"<?php if ( get_option( 'inkblot_comic_archives' ) ) echo ' checked="checked"'; ?> />
							<label>
								<?php _e( 'Show', 'inkblot' ); ?>
								<select name="inkblot_comic_archives_size" style="vertical-align:middle">
									<option value="thumb"<?php if ( 'thumb' == get_option( 'inkblot_comic_archives_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'thumbnail', 'inkblot' ); ?></option>
									<option value="medium"<?php if ( 'medium' == get_option( 'inkblot_comic_archives_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'medium', 'inkblot' ); ?></option>
									<option value="large"<?php if ( 'large' == get_option( 'inkblot_comic_archives_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'large', 'inkblot' ); ?></option>
									<option value="full"<?php if ( 'full' == get_option( 'inkblot_comic_archives_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'full', 'inkblot' ); ?></option>
								</select>
								<?php _e( 'comics on archive and search pages', 'inkblot' ); ?>
							</label>
						</p>
						<p>
							<input type="checkbox" name="inkblot_comic_embed" value="1"<?php if ( get_option( 'inkblot_comic_embed' ) ) echo ' checked="checked"'; ?> />
							<label>
								<?php _e( 'Show comic embed code formatted as', 'inkblot' ); ?>
								<select name="inkblot_comic_embed_format" style="vertical-align:middle">
									<option value="html"<?php if ( 'html' == get_option( 'inkblot_comic_embed_format' ) ) echo ' selected="selected"'; ?>><?php _e( 'html', 'inkblot' ); ?></option>
									<option value="bbcode"<?php if ( 'bbcode' == get_option( 'inkblot_comic_embed_format' ) ) echo ' selected="selected"'; ?>><?php _e( 'bbcode', 'inkblot' ); ?></option>
								</select>
							</label>
							<label>
								<?php _e( 'with', 'inkblot' ); ?>
								<select name="inkblot_comic_embed_size" style="vertical-align:middle">
									<option value="thumb"<?php if ( 'thumb' == get_option( 'inkblot_comic_embed_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'thumbnail', 'inkblot' ); ?></option>
									<option value="medium"<?php if ( 'medium' == get_option( 'inkblot_comic_embed_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'medium', 'inkblot' ); ?></option>
									<option value="large"<?php if ( 'large' == get_option( 'inkblot_comic_embed_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'large', 'inkblot' ); ?></option>
									<option value="full"<?php if ( 'full' == get_option( 'inkblot_comic_embed_size' ) ) echo ' selected="selected"'; ?>><?php _e( 'full', 'inkblot' ); ?></option>
								</select>
								<?php _e( 'comics on single post pages', 'inkblot' ); ?>
							</label>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="inkblot_comic_navigation"><?php _e( 'Navigation', 'inkblot' ); ?></label></th>
					<td>
						<label>
							<?php _e( 'Show comic navigation', 'inkblot' ); ?>
							<select name="inkblot_comic_navigation" id="inkblot_comic_navigation" style="vertical-align:middle">
								<option value="1"<?php if ( 1 == get_option( 'inkblot_comic_navigation' ) ) echo ' selected="selected"'; ?>><?php _e( 'above', 'inkblot' ); ?></option>
								<option value="0"<?php if ( !get_option( 'inkblot_comic_navigation' ) ) echo ' selected="selected"'; ?>><?php _e( 'below', 'inkblot' ); ?></option>
								<option value="2"<?php if ( 2 == get_option( 'inkblot_comic_navigation' ) ) echo ' selected="selected"'; ?>><?php _e( 'above and blow', 'inkblot' ); ?></option>
							</select>
							<?php _e( 'comics', 'inkblot' ); ?>
						</label>
						<label>
							<?php _e( 'and imit comic navigation to the current', 'inkblot' ); ?>
							<select name="inkblot_comic_limit" style="vertical-align:middle">
								<option value=""><?php _e( 'series', 'inkblot' ); ?></option>
								<option value="volume"<?php if ( 'volume' == get_option( 'inkblot_comic_limit' ) ) echo ' selected="selected"'; ?>><?php _e( 'volume', 'inkblot' ); ?></option>
								<option value="chapter"<?php if ( 'chapter' == get_option( 'inkblot_comic_limit' ) ) echo ' selected="selected"'; ?>><?php _e( 'chapter', 'inkblot' ); ?></option>
							</select>
						</label>
						<p>
							<input type="checkbox" name="inkblot_comic_link" value="1"<?php if ( get_option( 'inkblot_comic_link' ) ) echo ' checked="checked"'; ?> />
							<label>
								<?php _e( 'Link comic images to the', 'inkblot' ); ?>
								<select name="inkblot_comic_link_direction" style="vertical-align:middle">
									<option value="next"<?php if ( 'next' == get_option( 'inkblot_comic_link_direction' ) ) echo ' selected="selected"'; ?>><?php _e( 'next', 'inkblot' ); ?></option>
									<option value="previous"<?php if ( 'previous' == get_option( 'inkblot_comic_link_direction' ) ) echo ' selected="selected"'; ?>><?php _e( 'previous', 'inkblot' ); ?></option>
								</select>
								<?php _e( 'comic', 'inkblot' ); ?>
							</label>
						</p>
						<p><label><input type="checkbox" name="inkblot_site_navigation" value="1"<?php if ( get_option( 'inkblot_site_navigation' ) ) echo ' checked="checked"'; ?> /> <?php _e( 'Show the page navigation bar', 'inkblot' ); ?></label></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="inkblot_site_navigation"><?php _e( 'Layout', 'inkblot' ); ?></label></th>
					<td>
						<label><input type="radio" name="inkblot_layout" value="1c"<?php if( '1c' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/1c.png" alt="1c" style="vertical-align: middle;" /></label> &emsp;</label>
						<p class="setting-description"><?php _e( 'One column layouts focus primarily on the comic.', 'inkblot' );  ?></p>
						<label><input type="radio" name="inkblot_layout" value="2cor"<?php if( '2cor' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/2cor.png" alt="2cor" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="2col"<?php if( '2col' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/2col.png" alt="2col" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="2cir"<?php if( '2cir' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/2cir.png" alt="2cir" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="2cil"<?php if( '2cil' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/2cil.png" alt="2cil" style="vertical-align: middle;" /></label>
						<p class="setting-description"><?php _e( 'Two column layouts provide additional flexibility.', 'inkblot' );  ?></p>
						<label><input type="radio" name="inkblot_layout" value="3coc"<?php if( '3coc' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3coc.png" alt="3oc" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="3cor"<?php if( '3cor' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3cor.png" alt="3cor" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="3col"<?php if( '3col' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3col.png" alt="3col" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="3cic"<?php if( '3cic' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3cic.png" alt="3cic" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="3cir"<?php if( '3cir' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3cir.png" alt="3cir" style="vertical-align: middle;" /></label> &emsp;
						<label><input type="radio" name="inkblot_layout" value="3cil"<?php if( '3cil' == get_option( 'inkblot_layout' ) ) echo ' checked="checked"'; ?> /><img src="<?php bloginfo('template_directory') ?>/includes/images/3cil.png" alt="3cil" style="vertical-align: middle;" /></label>
						<p class="setting-description"><?php _e( 'Three column layouts provide maximum configuration options.', 'inkblot' );  ?></p>
						<p>
							<label>
								<?php _e( 'The site should be ', 'inkblot' ); ?>
								<select name="inkblot_layout_position" id="inkblot_layout_position" style="vertical-align:middle">
									<option value="2"<?php if ( 2 == get_option( 'inkblot_layout_position' ) ) echo ' selected="selected"'; ?>><?php _e( 'centered', 'inkblot' ); ?></option>
									<option value="0"<?php if ( !get_option( 'inkblot_layout_position' ) ) echo ' selected="selected"'; ?>><?php _e( 'left-aligned', 'inkblot' ); ?></option>
									<option value="1"<?php if ( 1 == get_option( 'inkblot_layout_position' ) ) echo ' selected="selected"'; ?>><?php _e( 'right-aligned', 'inkblot' ); ?></option>
								</select>
							</label>
						</p>
					</td>
				</tr>
				<tr>
					<th><label for="inkblot_comic_width"><?php _e( 'Dimensions', 'inkblot' ); ?></label></th>
					<td>
						<label>
							<input type="text" name="inkblot_comic_width" id="inkblot_comic_width" class="small-text" /><span class="setting-description">
							<?php _e( 'Enter the width of your comic in pixels and Inkblot will calculate the correct width for your site based on your current layout:', 'inkblot' ); ?> <img src="<?php bloginfo('template_directory') ?>/includes/images/<?php echo get_option( 'inkblot_layout' ); ?>.png" alt="<?php echo get_option( 'inkblot_layout' ); ?>" style="vertical-align: middle;" /></span>
						</label>
						<p><textarea rows="10" cols="50" id="inkblot_css" class="large-text code"></textarea></p>
						<script type="text/javascript">
							jQuery( '#inkblot_comic_width' ) . change( function() {
								var comic  = Number( jQuery( this ) . attr( 'value' ) );
								comic      = ( comic % 1 ) ? Math.floor( comic ) : comic;
								var column = <?php echo $layout->columns; ?>;
								var locale = <?php echo ( $layout->comic ) ? 1 : 0 ?>;
								
								if ( 2 < column ) {
									var site     = ( locale ) ? comic * 2 : comic;
									site         = ( site % 1 ) ? Math.floor( site ) : site;
									var content  = ( locale ) ? comic : site - Math.round( site * .5 );
									var sidebar1 = Math.round( site * .25 ) + 'PX';
									var sidebar2 = Math.round( site * .25 ) + 'PX';
								} else if ( 1 < column ) {
									var site     = ( locale ) ? comic * 1.62 : comic;
									site         = ( site % 2 ) ? Math.floor( site ) : site;
									var content  = ( locale ) ? comic : site - Math.round( site * .38 );
									var sidebar1 = Math.round( site * .38 ) + 'PX';
									var sidebar2 = '<?php _e( 'Not used in your selected layout.', 'inkblot' ) ?>';
								} else {
									var site     = comic;
									var content  = site;
									var sidebar1 = '<?php _e( 'Not used in your selected layout.', 'inkblot' ) ?>';
									var sidebar2 = '<?php _e( 'Not used in your selected layout.', 'inkblot' ) ?>';
								}
								
								var output = '/** <?php _e( 'Site Width - This should replace any existing CSS rule in your style.css file', 'inkblot' ); ?> */\n.group { width: ' + site + 'px; }\n\n/**\n * <?php _e( 'These are the widths that Inkblot will use for the content and sidebar areas. See the style.css file if you want to change these widths.', 'inkblot' ); ?>\n * \n * .content-main will be ' + content + 'px\n * .sidebar-one will be ' + sidebar1 + '\n * .sidebar-two will be ' + sidebar2 + '\n */';
								
								jQuery( '#inkblot_css' ) . html( output );
							} );
						</script>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes','inkblot') ?>" /><input type="hidden" name="action" value="inkblot_save_settings" /> <span class="alignright setting-description"><?php printf( __( 'Inkblot Version %s', 'inkblot' ), get_option( 'inkblot_version' ) ); ?></span></p>
			<?php settings_fields( 'inkblot_options' ); ?>
		</form>
	</div>
<?php
}

/**
 * Displays the Inkblot Metabox
 * 
 * @package Inkblot
 * @since 1.3.0
 */
function inkblot_page_meta_box( $post ) { $categories = get_comic_category( true );	?>
	<strong><?php _e( 'Comic Template Options', 'inkblot' ); ?></strong><br />
	<p>
		<label>
			<?php _e( 'Show the', 'inkblot' ); ?>
			<select name="inkblot_comic_order" id="inkblot_comic_order" style="vertical-align:middle">
				<option value="DESC"<?php if ( 'DESC' == get_option( 'inkblot_comic_frontpage_order' ) ) echo 'selected="selected"'; ?>><?php _e( 'most recent', 'inkblot' ); ?></option>
				<option value="ASC"<?php if ( 'ASC' == get_option( 'inkblot_comic_frontpage_order' ) ) echo 'selected="selected"'; ?>><?php _e( 'first', 'inkblot' ); ?></option>
			</select>
			<?php _e( 'comic', 'inkblot' ); ?>
		</label>
	</p><br />
	<strong><?php _e( 'Archive Template Options', 'inkblot' ); ?></strong><br />
	<p>
		<label>
			<?php _e( 'Organize comics by', 'inkblot' ); ?>
			<select name="inkblot_archive_groupby" style="vertical-align:middle">
				<option value="date"<?php if ( 'date' == get_post_meta( $post->ID, 'inkblot_archive_groupby', true ) ) echo ' selected="selected"'; ?>><?php _e( 'year, month, and day', 'inkblot' ); ?></option>
				<option value="chapter"<?php if ( 'chapter' == get_post_meta( $post->ID, 'inkblot_archive_groupby', true ) ) echo ' selected="selected"'; ?>><?php _e( 'series, volume, and chapter', 'inkblot' ); ?></option>
			</select>
		</label>
		<label>
			<?php _e( 'and display comic links as', 'inkblot' ); ?>
			<select name="inkblot_archive_format" style="vertical-align:middle">
				<option value=""><?php _e( 'text', 'inkblot' ); ?></option>
				<option value="thumb"<?php if ( 'thumb' == get_post_meta( $post->ID, 'inkblot_archive_format', true ) ) echo ' selected="selected"'; ?>><?php _e( 'thumbnail images', 'inkblot' ); ?></option>
				<option value="medium"<?php if ( 'medium' == get_post_meta( $post->ID, 'inkblot_archive_format', true ) ) echo ' selected="selected"'; ?>><?php _e( 'medium images', 'inkblot' ); ?></option>
				<option value="large"<?php if ( 'large' == get_post_meta( $post->ID, 'inkblot_archive_format', true ) ) echo ' selected="selected"'; ?>><?php _e( 'large images', 'inkblot' ); ?></option>
				<option value="full"<?php if ( 'full' == get_post_meta( $post->ID, 'inkblot_archive_format', true ) ) echo ' selected="selected"'; ?>><?php _e( 'full images', 'inkblot' ); ?></option>
				<option value="number"<?php if ( 'number' == get_post_meta( $post->ID, 'inkblot_archive_format', true ) ) echo ' selected="selected"'; ?>><?php _e( 'numbers (chapters only)', 'inkblot' ); ?></option>
			</select>
		</label>
	</p>
	<p>
		<label>
			<?php _e( 'Sort chapters by ', 'inkblot' ); ?>
			<select name="inkblot_archive_orderby" style="vertical-align:middle">
				<option value="id"<?php if ( 'id' == get_post_meta( $post->ID, 'inkblot_archive_order', true ) ) echo ' selected="selected"'; ?>><?php _e( 'ID', 'inkblot' ); ?></option>
				<option value="name"<?php if ( 'name' == get_post_meta( $post->ID, 'inkblot_archive_order', true ) ) echo ' selected="selected"'; ?>><?php _e( 'title', 'inkblot' ); ?></option>
				<option value="count"<?php if ( 'count' == get_post_meta( $post->ID, 'inkblot_archive_order', true ) ) echo ' selected="selected"'; ?>><?php _e( 'page count', 'webcomi' ); ?></option>
			</select>
		</label>
		<label>
			<?php _e( 'and point chapter links to the' ); ?>
			<select name="inkblot_archive_bound" style="vertical-align:middle">
				<option value="first"<?php if ( 'first' == get_post_meta( $post->ID, 'inkblot_archive_bound', true ) ) echo ' selected="selected"'; ?>><?php _e( 'beginning of the chapter', 'inkblot' ); ?></option>
				<option value="last"<?php if ( 'last' == get_post_meta( $post->ID, 'inkblot_archive_bound', true ) ) echo ' selected="selected"'; ?>><?php _e( 'end of the chapter', 'inkblot' ); ?></option>
				<option value="page"<?php if ( 'page' == get_post_meta( $post->ID, 'inkblot_archive_bound', true ) ) echo ' selected="selected"'; ?>><?php _e( 'chapter archive page', 'webcomi' ); ?></option>
			</select>
		</label>
	</p>
	<p><label><input type="checkbox" name="inkblot_archive_post_order" value="1"<?php if ( 'ASC' == get_post_meta( $post->ID, 'inkblot_archive_post_order', true ) ) echo ' checked="checked"'; ?> /> <?php _e( 'Show posts in reverse order', 'inkblot' ); ?></label></p>
	<p><label><input type="checkbox" name="inkblot_archive_order" value="1"<?php if ( 'DESC' == get_post_meta( $post->ID, 'inkblot_archive_order', true ) ) echo ' checked="checked"'; ?> /> <?php _e( 'Show chapters in reverse order', 'inkblot' ); ?></label></p>
	<p><label><input type="checkbox" name="inkblot_archive_descriptions" value="1"<?php if ( get_post_meta( $post->ID, 'inkblot_archive_descriptions', true ) ) echo ' checked="checked"'; ?> /> <?php _e( 'Show chapter descriptions', 'inkblot' ); ?></label></p>
	<p><label><input type="checkbox" name="inkblot_archive_pages" value="1"<?php if ( get_post_meta( $post->ID, 'inkblot_archive_pages', true ) ) echo ' checked="checked"'; ?> /> <?php _e( 'Show chapter page counts', 'inkblot' ); ?></label></p>
	<?php
}

/**
 * Manages the Inkblot Metabox settings
 * 
 * @package Inkblot
 * @since 1.3.0
 */
function inkblot_page_meta_box_save( $id ) {
	if ( !$_REQUEST[ 'original_publish' ] )
		return; //User did not manually update the post
	
	//Make sure we're working with the post, not a revisions
	if ( $the_post = wp_is_post_revision( $id ) )
		$id = $the_post;
	
	/** Attempt to update the comic template options */
	if ( 'comic.php' == get_post_meta( $id, '_wp_page_template', true ) ) {
		if ( $_REQUEST[ 'inkblot_comic_order' ] != get_post_meta( $id, 'inkblot_comic_order', true ) )
			if ( !add_post_meta( $id, 'inkblot_comic_order', $_REQUEST[ 'inkblot_comic_order' ], true ) )
				update_post_meta( $id, 'inkblot_comic_order', $_REQUEST[ 'inkblot_comic_order' ] );
	} else {
		delete_post_meta( $id, 'inkblot_comic_order' );
	}
	
	/** Attempt to update the archive template options */
	if ( 'archives.php' == get_post_meta( $id, '_wp_page_template', true ) ) {
		if ( $_REQUEST[ 'inkblot_archive_groupby' ] != get_post_meta( $id, 'inkblot_archive_groupby', true ) )
			if ( !add_post_meta( $id, 'inkblot_archive_groupby', $_REQUEST[ 'inkblot_archive_groupby' ], true ) )
				update_post_meta( $id, 'inkblot_archive_groupby', $_REQUEST[ 'inkblot_archive_groupby' ] );
		
		if ( !$_REQUEST[ 'inkblot_archive_format' ] )
			delete_post_meta( $id, 'inkblot_archive_format' );
		elseif ( $_REQUEST[ 'inkblot_archive_format' ] && $_REQUEST[ 'inkblot_archive_format' ] != get_post_meta( $id, 'inkblot_archive_format', true ) )
			if ( !add_post_meta( $id, 'inkblot_archive_format', $_REQUEST[ 'inkblot_archive_format' ], true ) )
				update_post_meta( $id, 'inkblot_archive_format', $_REQUEST[ 'inkblot_archive_format' ] );
		
		if ( $_REQUEST[ 'inkblot_archive_orderby' ] && $_REQUEST[ 'inkblot_archive_orderby' ] != get_post_meta( $id, 'inkblot_archive_orderby', true ) )
			if ( !add_post_meta( $id, 'inkblot_archive_orderby', $_REQUEST[ 'inkblot_archive_orderby' ], true ) )
				update_post_meta( $id, 'inkblot_archive_orderby', $_REQUEST[ 'inkblot_archive_orderby' ] );
		
		if ( $_REQUEST[ 'inkblot_archive_bound' ] && $_REQUEST[ 'inkblot_archive_bound' ] != get_post_meta( $id, 'inkblot_archive_bound', true ) )
			if ( !add_post_meta( $id, 'inkblot_archive_bound', $_REQUEST[ 'inkblot_archive_bound' ], true ) )
				update_post_meta( $id, 'inkblot_archive_bound', $_REQUEST[ 'inkblot_archive_bound' ] );
		
		if ( $_REQUEST[ 'inkblot_archive_post_order' ] )
			if ( !add_post_meta( $id, 'inkblot_archive_post_order', 'ASC', true ) )
				update_post_meta( $id, 'inkblot_archive_post_order', 'ASC' );
		else
			if ( !add_post_meta( $id, 'inkblot_archive_post_order', 'DESC', true ) )
				update_post_meta( $id, 'inkblot_archive_post_order', 'DESC' );
		
		if ( $_REQUEST[ 'inkblot_archive_order' ] )
			if ( !add_post_meta( $id, 'inkblot_archive_order', 'DESC', true ) )
				update_post_meta( $id, 'inkblot_archive_order', 'DESC' );
		else
			if ( !add_post_meta( $id, 'inkblot_archive_order', 'ASC', true ) )
				update_post_meta( $id, 'inkblot_archive_order', 'ASC' );
		
		if ( $_REQUEST[ 'inkblot_archive_descriptions' ] )
			add_post_meta( $id, 'inkblot_archive_descriptions', $_REQUEST[ 'inkblot_archive_descriptions' ], true );
		else
			delete_post_meta( $id, 'inkblot_archive_descriptions' );
		
		if ( $_REQUEST[ 'inkblot_archive_pages' ] )
			add_post_meta( $id, 'inkblot_archive_pages', $_REQUEST[ 'inkblot_archive_pages' ], true );
		else
			delete_post_meta( $id, 'inkblot_archive_pages' );
	} else {
		delete_post_meta( $id, 'inkblot_archive_groupby' );
		delete_post_meta( $id, 'inkblot_archive_format' );
		delete_post_meta( $id, 'inkblot_archive_orderby' );
		delete_post_meta( $id, 'inkblot_archive_bound' );
		delete_post_meta( $id, 'inkblot_archive_post_order' );
		delete_post_meta( $id, 'inkblot_archive_order' );
		delete_post_meta( $id, 'inkblot_archive_descriptions' );
		delete_post_meta( $id, 'inkblot_archive_pages' );
	}
} add_action( 'save_post', 'inkblot_page_meta_box_save' );
?>