<?php
/**
 * Single webcomic template.
 * 
 * This template is nearly identical to the normal `single.php` template, except
 * for the addition of webcomic display.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<?php if ( ! get_theme_mod('webcomic_content', false)) : ?>
	
	<?php
		while (have_posts()) : the_post();
			get_template_part('webcomic/display', get_post_type());
		endwhile;
		
		rewind_posts();
	?>
	
<?php endif; ?>

<main role="main">
	
	<?php
		while (have_posts()) : the_post();
			if (get_theme_mod('webcomic_content', false)) :
				get_template_part('webcomic/display', get_post_type());
			endif;
			
			get_template_part('webcomic/content', get_post_type());
			
			webcomic_transcripts_template();
			
			comments_template('', true);
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();