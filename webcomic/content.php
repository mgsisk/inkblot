<?php
/** Generic webcomic content template.
 * 
 * This is the generic content template used for Webcomic posts.
 * It's almost identical to the standard content template used for
 * other posts, except for the inclusion of a webcomic preview for
 * search and archive pages. To change the actual full webcomic
 * display see the `/webcomic/webcomic.php` template file.
 * 
 * @package Inkblot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ( is_search() or is_archive() ) and get_theme_mod( 'webcomic_archive_size', 'large' ) ) : ?>
		<div class="post-webcomic">
			<div class="webcomic-image">
				<?php the_webcomic( get_theme_mod( 'webcomic_archive_size', 'large' ), 'self' ); ?>
			</div><!-- .webcomic-image -->
		</div><!-- .post-webcomic -->
	<?php endif; ?>
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
		</div><!-- .post-content -->
	<?php endif; ?>
	<footer class="post-footer">
		<?php inkblot_post_meta(); ?>
	</footer><!-- .post-footer -->
</article><!-- #post-<?php the_ID(); ?> -->