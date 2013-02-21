<?php
/** Webcomic home template.
 * 
 * This is the Webcomic-specific homepage template (used in place of
 * the normal `home.php` or `index.php` when Webcomic is active).
 * The only real difference between this template and the normal
 * template is the inclusion of a webcomic.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

if ( inkblot_theme_preview() or ( get_theme_mod( 'webcomic_home_hook', true ) and !is_paged() ) ) {
	$webcomics = new WP_Query( array(
		'posts_per_page' => 1,
		'post_type'      => get_theme_mod( 'webcomic_home_collection' ) ? get_theme_mod( 'webcomic_home_collection' ) : get_webcomic_collections(),
		'order'          => get_theme_mod( 'webcomic_home_order', 'DESC' )
	) );
} else {
	$webcomics = false;
}

get_header(); ?>

<?php if ( !get_theme_mod( 'webcomic_content' ) and $webcomics and $webcomics->have_posts() ) : ?>
	<?php while ( $webcomics->have_posts() ) : $webcomics->the_post(); ?>
		<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts data-webcomic-gestures data-webcomic-container>
			<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
		</div><!-- .post-webcomic -->
	<?php endwhile; $webcomics->rewind_posts(); ?>
<?php endif; ?>
<main role="main">
	<?php if ( $webcomics and $webcomics->have_posts() ) : ?>
		<?php while ( $webcomics->have_posts() ) : $webcomics->the_post(); ?>
			<?php if ( get_theme_mod( 'webcomic_content' ) ) : ?>
				<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts data-webcomic-gestures data-webcomic-container>
					<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
				</div><!-- .post-webcomic -->
			<?php endif; ?>
			<?php get_template_part( 'webcomic/content', get_post_type() ); ?>
		<?php endwhile; ?>
	<?php elseif ( $webcomics and current_user_can( 'edit_posts' ) ) : ?>
		<header class="page-header">
			<h1><?php _e( 'No Webcomics', 'inkblot' ); ?></h1>
		</header><!-- .page-header -->
		<div class="page-content">
			<p><?php printf( __( 'Ready to publish your first webcomic? <a href="%s">Start here &raquo;</a>', 'inkblot' ), add_query_arg( array( 'post_type' => get_theme_mod( 'webcomic_collection', 'webcomic1' ) ), admin_url( 'post-new.php' ) ) ); ?></p>
		</div><!-- .page-content -->
	<?php endif; ?>
	<?php if ( have_posts() ) : ?>
		<?php inkblot_posts_nav( 'above' ); ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
		<?php inkblot_posts_nav( 'below' ); ?>
	<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
		<header class="page-header">
			<h1><?php _e( 'No Posts', 'inkblot' ); ?></h1>
		</header><!-- .page-header -->
		<div class="page-content">
			<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Start here &raquo;</a>', 'inkblot' ), admin_url( 'post-new.php' ) ); ?></p>
		</div><!-- .page-content -->
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>