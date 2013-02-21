<?php
/** Theme stylesheet generator.
 * 
 * To keep a lot of embedded styles and `<link>` tags out of our
 * `<head>` we use this file to generate a custom stylesheet based
 * on `normalize.css`, `style.css`, theme modifications, and
 * `custom.css` (in that order). For convenience, the styles are
 * loaded directly into the site `<head>` while actually customizing
 * the theme to ensure that live modification previews work
 * correctly.
 * 
 * @package Inkblot
 */

if ( !function_exists( 'get_theme_mod' ) ) {
	return;
}

locate_template( array( '-/css/normalize.css' ), true, true );
locate_template( array( 'style.css' ), true, true );

$css = array();
$sidebar1_width = get_theme_mod( 'sidebar1_width', 25 );
$sidebar2_width = get_theme_mod( 'sidebar2_width', 25 );

$css[ '#sidebar1' ][] = sprintf( 'width:%s%%', $sidebar1_width );
$css[ '#sidebar2' ][] = sprintf( 'width:%s%%', $sidebar2_width );

if ( $content = get_theme_mod( 'content' ) and 'one-column' !== $content ) {
	$content_width = floor( 100 - $sidebar1_width );
	
	if ( false !== strpos( $content, 'three-column' ) ) {
		$content_width = floor( $content_width - $sidebar2_width );
	}
	
	if ( 'three-column-center' === $content ) {
		$css[ '#sidebar1' ][] = sprintf( 'left:-%s%%', $content_width );
		$css[ 'main ' ][] = sprintf( 'left:%s%%', $sidebar1_width );
	}
	
	$css[ 'main ' ][] = sprintf( 'width:%s%%', $content_width );
}

if ( $min_width = get_theme_mod( 'min_width', 0 ) ) {
	$css[ '#page' ][] = sprintf( 'min-width:%spx', $min_width );
}

if ( $max_width = get_theme_mod( 'max_width', 0 ) ) {
	$css[ '#page' ][] = sprintf( 'max-width:%spx', $max_width );
}

if ( $min_width and $max_width and $min_width === $max_width ) {
	$css[ '#page' ][] = sprintf( 'width:%spx', $min_width );
}

if ( $page_font = get_theme_mod( 'page_font' ) ) {
	$css[ 'html' ][] = sprintf( 'font-family:"%s"', str_replace( '+', ' ', substr( $page_font, 0, strpos( $page_font, ':' ) ) ) );
}

if ( $title_font = get_theme_mod( 'title_font' ) ) {
	$css[ 'h1,h2,h3,h4,h5,h6' ][] = sprintf( 'font-family:"%s"', str_replace( '+', ' ', substr( $title_font, 0, strpos( $title_font, ':' ) ) ) );
}

if ( $trim_font = get_theme_mod( 'trim_font' ) ) {
	$css[ '#header nav,#header nav select,#footer,nav.webcomics' ][] = sprintf( 'font-family:"%s"', str_replace( '+', ' ', substr( $trim_font, 0, strpos( $trim_font, ':' ) ) ) );
}

if ( $font_size = get_theme_mod( 'font_size' ) and 100 !== $font_size ) {
	$css[ 'html' ][] = sprintf( 'font-size:%s%%', $font_size );
}

if ( $header_textcolor = get_header_textcolor() ) {
	if ( 'blank' === $header_textcolor ) {
		$css[ '#header hgroup' ][] = 'display:none';
	} else {
		$css[ '#header hgroup,#header hgroup a' ][] = sprintf( 'color:#%s', $header_textcolor );
	}
}

if ( $background_color = get_background_color() ) {
	$css[ 'body' ][] = sprintf( 'background-color:#%s', $background_color );
}

if ( $text_color = get_theme_mod( 'text_color' ) ) {
	$css[ '#page' ][] = sprintf( 'color:%s', $text_color );
}

if ( $link_color = get_theme_mod( 'link_color' ) ) {
	$css[ 'a' ][] = sprintf( 'color:%s', $link_color );
	$css[ '.post-actions a:hover,.post-comments-link a:hover,.comment-actions a:hover' ][] = sprintf( 'background-color:%s', $link_color );
}

if ( $link_hover_color = get_theme_mod( 'link_hover_color' ) ) {
	$css[ 'a:hover' ][] = sprintf( 'color:%s', $link_hover_color );
}

if ( $page_color = get_theme_mod( 'page_color' ) ) {
	$css[ '#page' ][] = sprintf( 'background-color:%s', $page_color );
	$css[ '.post-webcomic nav.above' ][] = sprintf( 'border-color:%s', $page_color );
}

if ( $trim_color = get_theme_mod( 'trim_color' ) ) {
	$css[ '::-moz-selection' ][] = sprintf( 'background-color:%s', $trim_color );
	$css[ '::selection' ][] = sprintf( 'background-color:%s', $trim_color );
	$css[ '#page,blockquote,pre,td,nav.posts,nav.posts-paged,nav.comments-paged,.post-footer,.comment,.trackback,.comment .comment' ][] = sprintf( 'border-color:%s', $trim_color );
	$css[ '#header nav,#header nav select,#header nav ul ul,#footer,.post-comments-link a,.post-actions a,.comment-actions a,#commentform .required,.webcomic-transcribe-form .required,.post-webcomic nav' ][] = sprintf( 'background-color:%s', $trim_color );
}

if ( $trim_text_color = get_theme_mod( 'trim_text_color' ) ) {
	$css[ '::-moz-selection' ][] = sprintf( 'color:%s', $trim_text_color );
	$css[ '::selection' ][] = sprintf( 'color:%s', $trim_text_color );
	$css[ '#header nav,#header nav select,#footer,.post-comments-link a,.post-comments-link a:focus,.post-comments-link a:hover,.post-actions a,.post-actions a:focus,.post-actions a:hover,.comment-actions a,.comment-actions a:focus,.comment-actions a:hover,#commentform .required,.webcomic-transcribe-form .required,.post-webcomic nav' ][] = sprintf( 'color:%s', $trim_text_color );
}

if ( $trim_link_color = get_theme_mod( 'trim_link_color' ) ) {
	$css[ '#header nav a,#footer a,.post-webcomic nav a' ][] = sprintf( 'color:%s', $trim_link_color );
}

if ( $trim_link_hover_color = get_theme_mod( 'trim_link_hover_color' ) ) {
	$css[ '#header nav a:focus,#header nav a:hover,#header li:hover > a,#header .current_page_item a,#header .current_page_ancestor a,#footer a:focus,#footer a:hover,.post-webcomic nav a:focus,.post-webcomic nav a:hover' ][] = sprintf( 'color:%s', $trim_link_hover_color );
}

if ( $background_image = get_background_image() ) {
	$css[ 'body' ][] = sprintf( 'background-image:url(%s)', $background_image );
	$css[ 'body' ][] = sprintf( 'background-repeat:%s', get_theme_mod( 'background_repeat', 'repeat' ) );
	$css[ 'body' ][] = sprintf( 'background-position:top %s', get_theme_mod( 'background_position_x', 'left' ) );
	$css[ 'body' ][] = sprintf( 'background-attachment:%s', get_theme_mod( 'background_attachment', 'scroll' ) );
}

if ( $page_background_image = get_theme_mod( 'page_background_image' ) ) {
	$css[ '#page' ][] = sprintf( 'background-image:url(%s)', $page_background_image );
	$css[ '#page' ][] = sprintf( 'background-repeat:%s', get_theme_mod( 'page_background_repeat', 'repeat' ) );
	$css[ '#page' ][] = sprintf( 'background-position:top %s', get_theme_mod( 'background_position_x', 'left' ) );
	$css[ '#page' ][] = sprintf( 'background-attachment:%s', get_theme_mod( 'background_attachment', 'scroll' ) );
}

if ( !get_theme_mod( 'webcomic_resize', true ) ) {
	$css[ '.post-webcomic .webcomic-image img' ][] = 'max-width:none';
}

if ( !get_theme_mod( 'webcomic_nav_above', true ) ) {
	$css[ 'nav.webcomics.above' ][] = 'display:none';
}

if ( !get_theme_mod( 'webcomic_nav_below', true ) ) {
	$css[ 'nav.webcomics.below' ][] = 'display:none';
}

if ( $css = apply_filters( 'inkblot_custom_styles', $css ) ) {
	foreach ( $css as $k => $v ) {
		printf( '%s{%s}', $k, join( ';', ( array ) $v ) );
	}
}

if ( get_theme_mod( 'responsive', true ) ) {
	echo apply_filters( 'inkblot_responsive_styles', sprintf( '@media only screen and (max-width:%spx){main,#sidebar1,#sidebar2{float:none;left:0;width:100%%;}#header nav ul{display:none}#header nav select{display:block;width:100%%}}', get_theme_mod( 'responsive_width', 640 ) ) );
}

locate_template( array( 'custom.css' ), true, true );