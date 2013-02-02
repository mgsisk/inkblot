<?php
/** Image attachment template.
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="post-header">
				<h1><?php the_title(); ?></h1>
			</header><!-- .post-header -->
			<footer class="post-footer">
				<?php inkblot_post_meta(); ?>
			</footer><!-- .post-footer -->
			<nav class="posts">
				<?php previous_image_link( array( 32, 32 ) ); ?>
				<?php next_image_link( array( 32, 32 ) ); ?>
			</nav>
			<div class="post-image">
				<?php the_attachment_link( $post->ID, true ); ?>
			</div><!-- .post-attachment -->
			<?php if ( $post->post_excerpt ) : ?>
				<div class="post-excerpt">
					<?php the_excerpt(); ?>
				</div><!-- .post-excerpt -->
			<?php endif; ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<nav class="post-pages">', 'after' => '</nav>' ) ); ?>
			</div><!-- .post-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
		<?php comments_template( '', true ); ?>
	<?php endwhile; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>