<?php
/**
 * Header template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head><?php wp_head(); /* @see `inkblot_wp_head()` in `functions.php` */ ?></head>
	<body id="document" <?php body_class(); ?>>
		<a href="#content"><?php _e('Skip to content', 'inkblot'); ?></a>
		
		<?php print inkblot_widgetized('document-header'); ?>
		
		<div class="wrapper">
			
			<?php print inkblot_widgetized('page-header'); ?>
			
			<header role="banner" class="banner widgets columns-<?php print inkblot_count_widgets('site-header'); ?>">
				
				<?php if ( ! dynamic_sidebar('site-header')) : ?>
					
					<a href="<?php print esc_url(home_url()); ?>" rel="home">
						<h1 class="site"><?php bloginfo('name'); ?></h1>
						<p><?php bloginfo('description'); ?></p>
						
						<?php if (get_theme_mod('header_post_thumbnail') and has_post_thumbnail()) : ?>
							
							<?php the_post_thumbnail(array(get_theme_mod('header_width'), get_theme_mod('header_height'))); ?>
							
						<?php elseif ($header = get_custom_header() and $header->url) : ?>
							
							<img src="<?php header_image(); ?>" width="<?php print $header->width; ?>" height="<?php print $header->height; ?>" alt="<?php print esc_attr(get_bloginfo('name')); ?>">
							
						<?php endif; ?>
						
					</a>
					
					<nav role="navigation" aria-label="<?php _e('Primary Navigation', 'inkblot'); ?>">
						
						<?php
							wp_nav_menu(array(
								'theme_location' => 'primary',
								'show_home' => true,
								'container' => false
							));
							
							if (get_theme_mod('responsive_width', 0) or is_customize_preview()) {
								if (has_nav_menu('primary')) {
									wp_nav_menu(array(
										'theme_location' => 'primary',
										'show_home' => true,
										'container' => false,
										'items_wrap' => '<select>%3$s</select>',
										'walker' => new Inkblot_Walker_Nav_Dropdown
									));
								} else {
									print '<select>';
									
									wp_list_pages(array(
										'title_li' => '',
										'walker' => new Inkblot_Walker_Page_Dropdown
									));
									
									print '</select>';
								}
							}
						?>
						
					</nav>
					
				<?php endif; ?>
				
			</header><!-- .banner -->
			<div id="content" class="content" tabindex="-1">
				
				<?php print inkblot_widgetized('content-header'); ?>