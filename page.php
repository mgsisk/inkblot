<?php
/**
 * Generic page template.
 * 
 * For Webcomic page templates, see `webcomic/template-archive.php` or
 * `webcomic/template-home.php`.
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