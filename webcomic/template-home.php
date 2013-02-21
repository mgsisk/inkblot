<?php
/**
 * Template Name: Webcomic Homepage
 * 
 * Useful for creating a dedicated landing page for specific
 * Webcomic collections.
 * 
 * @package Inkblot
 */

if ( !webcomic() ) {
	get_template_part( 'page' );
	return;
}

get_header();

$webcomic_collection = get_webcomic_collection();
$webcomic_order      = get_post_meta( get_the_ID(), 'inkblot_webcomic_order', true );
$webcomics           = new WP_Query( array(
	'posts_per_page' => 1,
	'post_type'      => $webcomic_collection ? $webcomic_collection : get_webcomic_collections(),
	'order'          => $webcomic_order ? $webcomic_order : 'DESC'
) );
?>

<?php if ( !get_theme_mod( 'webcomic_content' ) and $webcomics->have_posts() ) : ?>
	<?php while ( $webcomics->have_posts() ) : $webcomics->the_post(); ?>
		<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts data-webcomic-gestures data-webcomic-container>
			<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
		</div><!-- .post-webcomic -->
	<?php endwhile; $webcomics->rewind_posts(); ?>
<?php endif; ?>
<main role="main">
	<?php if ( get_theme_mod( 'webcomic_content' ) and $webcomics->have_posts() ) : ?>
		<?php while ( $webcomics->have_posts() ) : $webcomics->the_post(); ?>
			<div id="webcomic" class="post-webcomic" data-webcomic-shortcuts data-webcomic-gestures data-webcomic-container>
				<?php get_template_part( 'webcomic/webcomic', get_post_type() ); ?>
			</div><!-- .post-webcomic -->
		<?php endwhile; $webcomics->rewind_posts(); ?>
	<?php endif; ?>
	<?php if ( $webcomics->have_posts() ) : ?>
		<?php while ( $webcomics->have_posts() ) : $webcomics->the_post(); ?>
			<?php get_template_part( 'webcomic/content', get_post_type() ); ?>
		<?php endwhile; ?>
	<?php elseif ( $webcomics and current_user_can( 'edit_posts' ) ) : ?>
		<header class="page-header">
			<h1><?php _e( 'No Webcomics', 'inkblot' ); ?></h1>
		</header><!-- .page-header -->
		<div class="page-content">
			<p><?php printf( __( 'Ready to publish your first webcomic? <a href="%s">Start here &raquo;</a>', 'inkblot' ), add_query_arg( array( 'post_type' => $webcomic_collection ? $webcomic_collection : 'webcomic1' ), admin_url( 'post-new.php' ) ) ); ?></p>
		</div><!-- .page-content -->
	<?php endif; ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
		<?php comments_template( '', true ); ?>
	<?php endwhile;?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>