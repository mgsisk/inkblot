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
		
		<nav role="navigation" class="widgets columns-<?php print inkblot_count_widgets('webcomic-navigation-header', 5); ?> above" aria-label="<?php _e('Webcomic Navigation Header', 'inkblot'); ?>">
			
			<?php
				if ( ! dynamic_sidebar("webcomic-navigation-header")) :
					first_webcomic_link('<aside>%link</aside>');
					previous_webcomic_link('<aside>%link</aside>');
					random_webcomic_link('<aside>%link</aside>');
					next_webcomic_link('<aside>%link</aside>');
					last_webcomic_link('<aside>%link</aside>');
				endif;
			?>
			
		</nav><!-- .widgets.above -->
		
	<?php endif; ?>
	
	<div class="webcomic-image<?php print get_theme_mod('webcomic_resize', true) ? '' : ' scroll'; ?>">
		
		<?php the_webcomic('full', get_theme_mod('webcomic_nav_link', false)); ?>
		
	</div><!-- .webcomic-image -->
	
	<?php if (get_theme_mod('webcomic_nav_below', true) or is_customize_preview()) : ?>
		
		<nav role="navigation" class="widgets columns-<?php print inkblot_count_widgets('webcomic-navigation-header', 5); ?> below" aria-label="<?php _e('Webcomic Navigation Footer', 'inkblot'); ?>">
			
			<?php
				if ( ! dynamic_sidebar("webcomic-navigation-footer")) :
					first_webcomic_link('<aside>%link</aside>');
					previous_webcomic_link('<aside>%link</aside>');
					random_webcomic_link('<aside>%link</aside>');
					next_webcomic_link('<aside>%link</aside>');
					last_webcomic_link('<aside>%link</aside>');
				endif;
			?>
			
		</nav><!-- .widgets.below -->
		
	<?php endif; ?>
	
	<?php print inkblot_widgetized('webcomic-footer'); ?>
	
</div><!-- .post-webcomic -->