<?php
/**
 * 404 error template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">	
	
	<?php get_template_part('content', 'none'); ?>
	
</main>

<?php get_sidebar(); get_footer();