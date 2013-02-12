<?php
/** Generic content template.
 * 
 * For Webcomic-specific content, see /webcomic/content.php
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
		<?php if ( is_single() ) : ?>
			<h1><?php the_title(); ?></h1>
		<?php else : ?>
			<h1><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'inkblot' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php endif; ?>
	</header><!-- .post-header -->
	<?php if ( comments_open() ) : ?>
		<div class="post-comments-link">
			<?php comments_popup_link(); ?>
		</div><!-- .post-comments-link -->
	<?php endif; ?>
	<?php if ( is_search() ) : ?>
		<div class="post-excerpt">
			<?php the_excerpt(); ?>
		</div><!-- .post-excerpt -->
	<?php else : ?>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<nav class="post-pages">', 'after' => '</nav>' ) ); ?>
		</div>
	<?php endif; ?>
	<footer class="post-footer">
		<?php inkblot_post_meta(); ?>
	</footer><!-- .post-footer -->
</article><!-- #post-<?php the_ID(); ?> -->