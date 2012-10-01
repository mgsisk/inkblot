<?php
/** Page content template.
 * 
 * @package Inkblot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-image">
			<?php the_post_thumbnail(); ?>
		</div><!-- .post-image -->
	<?php endif; ?>
	<header class="post-header">
		<h1><?php the_title(); ?></h1>
	</header><!-- .post-header -->
	<div class="post-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<nav class="post-pages">', 'after' => '</nav>' ) ); ?>
	</div><!-- .post-content -->
	<footer class="post-footer">
		<?php inkblot_post_meta(); ?>
	</footer><!-- .post-footer -->
</article><!-- #post-<?php the_ID(); ?> -->