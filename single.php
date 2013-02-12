<?php
/** Single post template.
 * 
 * For single Webcomic posts, see /webcomic/single.php
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', get_post_type() ); ?>
		<nav class="posts">
			<?php previous_post_link(); ?>
			<?php next_post_link(); ?>
		</nav>
		<?php comments_template( '', true ); ?>
	<?php endwhile; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>