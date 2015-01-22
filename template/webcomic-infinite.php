<?php
/**
 * Template Name: Webcomic Infinite
 * 
 * Useful for creating an infinite-scroll archive. There isn't much to this
 * template; most of the heavy lifting happens in `script.js` and
 * `functions.php`. Actual content display is handled by
 * `template/webcomic-infinite-content.php` and
 * `template/webcomic-infinite-end.php`.
 * 
 * @package Inkblot
 */

if ( ! webcomic()) {
	get_template_part('page');
	
	return;
}

get_header(); ?>

<main role="main" data-page-id="<?php the_ID(); ?>" data-webcomic-order="<?php print get_post_meta(get_the_ID(), 'inkblot_webcomic_order', true) ?>" data-webcomic-offset="<?php print isset($_GET['offset']) ? $_GET['offset'] : 0 ?>" data-webcomic-collection="<?php print get_post_meta(get_the_ID(), 'inkblot_webcomic_collection', true); ?>">
	
	<!--
		Don't put anything here! Check `template/webcomic-infinite-content.php`
		for the Webcomic display template
	-->
	
</main>

<?php get_sidebar(); get_footer();