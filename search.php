<?php
/**
 * Search results template.
 * 
 * @package Inkblot
 * @see http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php if (have_posts()) : ?>
		
		<header class="page-header">
			<h1><?php print get_search_query(); ?></h1>
		</header><!-- .page-header -->
		
		<?php
			while (have_posts()) : the_post();
				(webcomic() and is_a_webcomic())
				? get_template_part('webcomic/image', get_post_type())
				: get_template_part('content', get_post_format());
			endwhile;
			
			print inkblot_posts_nav('below', false, get_theme_mod('paged_navigation', true));
		else :
			get_template_part('content', 'none');
		endif;
	?>
	
</main>

<?php get_sidebar(); get_footer();