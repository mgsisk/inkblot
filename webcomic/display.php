<?php
/**
 * Webcomic display template.
 * 
 * Handles webcomic image and navigation display.
 * 
 * @package Inkblot
 */
?>
<div class="post-webcomic" data-webcomic-container data-webcomic-shortcuts data-webcomic-gestures>
	
	<?php print inkblot_widgetized('webcomic-header'); ?>
	
	<?php if (get_theme_mod('webcomic_nav_above', true) or is_customize_preview()) : ?>
		
		<nav class="widgets columns-<?php print get_theme_mod('sidebar-webcomic-navigation-header-columns', true) ? inkblot_count_widgets('webcomic-navigation-header', 5) : 1; ?> above">
			
			<?php
				if ( ! dynamic_sidebar("webcomic-navigation-header")) :
					first_webcomic_link('<aside>%link</aside>', get_theme_mod('first_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('first_webcomic_image'), __('&laquo;', 'inkblot')) : '');
					previous_webcomic_link('<aside>%link</aside>', get_theme_mod('previous_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('previous_webcomic_image'), __('&lsaquo;', 'inkblot')) : '');
					random_webcomic_link('<aside>%link</aside>', get_theme_mod('random_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('random_webcomic_image'), __('&infin;', 'inkblot')) : '');
					next_webcomic_link('<aside>%link</aside>', get_theme_mod('next_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('next_webcomic_image'), __('&rsaquo;', 'inkblot')) : '');
					last_webcomic_link('<aside>%link</aside>', get_theme_mod('last_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('last_webcomic_image'), __('&raquo;', 'inkblot')) : '');
				endif;
			?>
			
		</nav><!-- .widgets.above -->
		
	<?php endif; ?>
	
	<div class="webcomic-image<?php print get_theme_mod('webcomic_resize', true) ? '' : ' scroll'; ?>">
		
		<?php the_webcomic('full', get_theme_mod('webcomic_nav_link', false)); ?>
		
	</div><!-- .webcomic-image -->
	
	<?php if (get_theme_mod('webcomic_nav_below', true) or is_customize_preview()) : ?>
		
		<nav class="widgets columns-<?php print get_theme_mod('sidebar-webcomic-navigation-footer-columns', true) ? inkblot_count_widgets('webcomic-navigation-header', 5) : 1; ?> below">
			
			<?php
				if ( ! dynamic_sidebar("webcomic-navigation-footer")) :
					first_webcomic_link('<aside>%link</aside>', get_theme_mod('first_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('first_webcomic_image'), __('&laquo;', 'inkblot')) : '');
					previous_webcomic_link('<aside>%link</aside>', get_theme_mod('previous_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('previous_webcomic_image'), __('&lsaquo;', 'inkblot')) : '');
					random_webcomic_link('<aside>%link</aside>', get_theme_mod('random_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('random_webcomic_image'), __('&infin;', 'inkblot')) : '');
					next_webcomic_link('<aside>%link</aside>', get_theme_mod('next_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('next_webcomic_image'), __('&rsaquo;', 'inkblot')) : '');
					last_webcomic_link('<aside>%link</aside>', get_theme_mod('last_webcomic_image', false) ? sprintf('<img src="%s" alt="%s">', get_theme_mod('last_webcomic_image'), __('&raquo;', 'inkblot')) : '');
				endif;
			?>
			
		</nav><!-- .widgets.below -->
		
	<?php endif; ?>
	
	<?php print inkblot_widgetized('webcomic-footer'); ?>
	
</div><!-- .post-webcomic -->