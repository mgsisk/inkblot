<?php
/**
 * Webcomic collection archive template.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header();

if ('ASC' === get_theme_mod('webcomic_archive_order', 'ASC')) :
	global $wp_query;
	
	query_posts(array_merge($wp_query->query_vars, array(
		'order' => get_theme_mod('webcomic_archive_order', 'ASC')
	)));
endif; ?>

<main role="main">
	
	<?php if (have_posts()) : ?>
		
		<header class="page-header">
			<h1><?php post_type_archive_title(sprintf('<span class="screen-reader-text">%s </span>', __('Webcomics from', 'inkblot'))); ?></h1>
		</header><!-- .page-header -->
		
		<?php if (WebcomicTag::webcomic_collection_image()) : ?>
			
			<div class="page-image"><?php webcomic_collection_poster(); ?></div><!-- .page-image -->
			
		<?php endif; ?>
		
		<?php if (WebcomicTag::webcomic_collection_description()) : ?>
			
			<div class="page-content"><?php webcomic_collection_description(); ?></div><!-- .page-content -->
			
		<?php endif; ?>
		
		<?php
			while (have_posts()) : the_post();
				get_template_part('webcomic/content', get_post_type());
			endwhile;
			
			print inkblot_posts_nav(false, get_theme_mod('paged_navigation', true));
		else:
			get_template_part('content', 'none');
		endif;
	?>
	
</main>

<?php get_sidebar(); get_footer();