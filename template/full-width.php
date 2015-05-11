<?php
/**
 * Template Name: Full Width
 * 
 * Full-width page template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php
		while (have_posts()) : the_post();
			get_template_part('content', 'page');
			
			comments_template();
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();