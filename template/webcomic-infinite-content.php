<?php
/**
 * Webcomic display template for Webcomic Infinite template.
 * 
 * Webcomic doesn't natively understand this template, but we're keeping it in
 * the `webcomic` directory because it's Webcomic-specific. This template
 * handles comic display for the Webcomic Infinite page template. The contents
 * of this template should always have a single outer-most wrapper (<article>,
 * by default).
 * 
 * @package Inkblot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="post-webcomic">
		
		<?php inkblot_widgetized('webcomic-header'); ?>
		
		<div class="webcomic-image<?php print get_theme_mod('webcomic_resize', true) ? '' : ' scroll'; ?>">
			
			<?php the_webcomic('full'); ?>
			
		</div><!-- .webcomic-image -->
		
		<?php inkblot_widgetized('webcomic-footer'); ?>
		
	</div><!-- .post-webcomic -->
	
	<header class="post-header">
		
		<p class="post-details">
			<?php
				print inkblot_post_datetime();
				
				the_author_posts_link();
				
				if ( ! post_password_required() and (comments_open() or get_comments_number())) :
					comments_popup_link();
				endif;
				
				if (webcomic_prints_available()) :
					printf('<a href="%1$s">%2$s</a>',
						add_query_arg(array('prints' => ''), get_permalink()),
						__('Purchase', 'inkblot')
					);
				endif;
				
				print inkblot_infinite_link();
				
				edit_post_link();
			?>
		</p>
	</header><!-- .post-header -->
	
</article><!-- #post-<?php the_ID(); ?> -->