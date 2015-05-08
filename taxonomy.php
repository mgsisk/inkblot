<?php
/**
 * Generic taxonomy archive template.
 * 
 * For Webcomic-specific archives, see `webcomic/archive.php`.
 * 
 * @package Inkblot
 * @see codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">

	<?php if (have_posts()) : ?>
		
		<header class="page-header">
			<h1><?php single_term_title(sprintf('<span class="screen-reader-text">%s </span>', __('Posts with the custom taxonomy term', 'inkblot'))); ?></h1>
		</header><!-- .page-header -->
		
		<?php if (term_description()) : ?>
			
			<div class="page-content"><?php print term_description(); ?></div><!-- .page-content -->
			
		<?php endif; ?>
		
		<?php
			while (have_posts()) : the_post();
				(webcomic() and is_a_webcomic())
				? get_template_part('webcomic/content', get_post_type())
				: get_template_part('content', get_post_format());
			endwhile;
			
			print inkblot_posts_nav(false, get_theme_mod('paged_navigation', true));
		else:
			get_template_part('content', 'none');
		endif;
	?>

</main>

<?php get_sidebar(); get_footer();