<?php
/** Header template.
 * 
 * @package Inkblot
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head><?php wp_head(); ?></head>
	<body id="document" <?php body_class(); ?>>
		<div id="document-header" role="complementary" class="widgets"><?php dynamic_sidebar( 'document-header' ); ?></div><!-- #document-header -->
		<div id="page">
			<div id="page-header" role="complementary" class="widgets"><?php dynamic_sidebar( 'page-header' ); ?></div><!-- #page-header -->
			<header id="header" role="banner">
				<hgroup>
					<h1><a href="<?php echo esc_url( home_url() ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2><?php bloginfo( 'description' ); ?></h2>
				</hgroup>
				<?php if ( $header = get_custom_header() and $header->url ) : ?>
					<a href="<?php echo esc_url( home_url() ); ?>" rel="home"><img src="<?php header_image(); ?>" width="<?php echo $header->width; ?>" height="<?php echo $header->height; ?>" alt=""></a>
				<?php endif; ?>
				<nav>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'show_home' => true, 'container' => false ) ); ?>
					<?php
						if ( get_theme_mod( 'responsive', true ) or inkblot_theme_preview() ) {
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'show_home' => true, 'container' => false, 'items_wrap' => '<select>%3$s</select>', 'walker' => new Walker_InkblotNavMenu_Dropdown ) );
							} else {
								echo '<select>';
								
								wp_list_pages( array( 'title_li' => '', 'walker' => new Walker_InkblotPageMenu_Dropdown ) );
								
								echo '</select>';
							}
						}
					?>
				</nav>
			</header><!-- #header -->
			<div id="content">
				<div id="content-header" role="complementary" class="widgets"><?php dynamic_sidebar( 'content-header' ); ?></div><!-- #content-header -->