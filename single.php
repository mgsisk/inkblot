<?php
/**
 * Single post template.
 * 
 * For single Webcomic posts, see `webcomic/single.php`.
 * 
 * @package Inkblot
 * @see http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php
		while (have_posts()) : the_post();
			get_template_part('content', get_post_type());
			
			print inkblot_post_nav();
			
			comments_template();
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();