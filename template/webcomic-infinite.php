<?php
/**
 * Template Name: Webcomic Infinite
 * 
 * Useful for creating an infinite-scroll archive. The actual logic for this is
 * handled by the Webcomic plugin; you just need an empty container element
 * with a `data-webcomic-infinite` attribute set to the current page ID and
 * Webcomic takes care of the rest.
 * 
 * To adjust the output for infninte scroll comics, see `webcomic/infinite.php`.
 * 
 * @package Inkblot
 */

if ( ! webcomic()) {
	get_template_part('page');
	
	return;
}

get_header(); ?>

<main role="main" data-webcomic-infinite="<?php the_ID(); ?>" data-webcomic-collection="<?php print get_post_meta(get_the_ID(), 'inkblot_webcomic_collection', true); ?>" data-webcomic-order="<?php print get_post_meta(get_the_ID(), 'inkblot_webcomic_order', true) ?>">
	
	<!-- Don't put anything here! See `webcomic/infinite.php` for the Webcomic display template -->
	
</main>

<?php get_sidebar(); get_footer();