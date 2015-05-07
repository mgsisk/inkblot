<?php
/**
 * Single post template.
 * 
 * For single Webcomic posts, see `webcomic/single.php`.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php
		while (have_posts()) : the_post();
			get_template_part('content', get_post_format());
			
			the_post_navigation();
			
			comments_template();
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();