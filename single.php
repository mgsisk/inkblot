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
			
			the_post_navigation(array(
				'next_text' => sprintf('<span class="screen-reader-text">%s </span>%%title', __('Next post: ', 'inkblot')),
				'prev_text' => sprintf('<span class="screen-reader-text">%s </span>%%title', __('Previous post: ', 'inkblot'))
			));
			
			comments_template();
		endwhile;
	?>
	
</main>

<?php get_sidebar(); get_footer();