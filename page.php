<?php
/** Generic page template.
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
		<?php comments_template( '', true ); ?>
	<?php endwhile;?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>