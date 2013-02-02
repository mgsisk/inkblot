<?php
/** Webcomic prints template.
 * 
 * This template is similar to the `single.php` template, but used
 * specifically for displaying webcomic print purchasing options.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<main role="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-webcomic">
			<div class="webcomic-image">
				<?php the_webcomic( 'large' ); ?>
			</div><!-- .webcomic-image -->
		</div><!-- .post-webcomic -->
		<header class="post-header">
			<h1><?php the_title(); ?></h1>
		</header><!-- .post-header -->
		<footer class="post-footer">
			<?php inkblot_post_meta(); ?>
		</footer><!-- .post-footer -->
		<div class="post-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<div class="webcomic-prints">
			<?php if ( webcomic_prints_available( true ) ) : ?>
			<div class="webcomic-prints-original">
				<?php webcomic_print_form( 'original', __( '%total Original Print', 'inkblot' ) ); ?>
			</div>
			<?php endif; ?>
			<div class="webcomic-prints-domestic">
				<?php webcomic_print_form( 'domestic', __( '%total Domestic', 'inkblot' ) ); ?>
			</div>
			<div class="webcomic-prints-international">
				<?php webcomic_print_form( 'international', __( '%total International', 'inkblot' ) ); ?>
			</div>
		</div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>