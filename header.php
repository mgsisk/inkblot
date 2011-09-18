<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><?php wp_head(); /* see functions.php hook_wp_head_0 */ ?></head>
<body id="body" <?php body_class(); ?>>
	<div id="wrap">
		<div id="page-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-page-above' ); ?></div>
		<header id="header">
			<hgroup>
				<a href="<?php echo home_url( '/' ); ?>" rel="home"><h1><?php bloginfo( 'name' ); ?></h1></a>
				<h2><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
			<nav><?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'navbar', 'link_before' => '<b>', 'link_after' => '</b>' ) ); ?></nav>
			<hr>
		</header><!--#header-->
		<?php global $inkblot; if ( '3c6' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>