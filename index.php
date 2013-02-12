<?php
/** The main template file.
 * 
 * If you're using Webcomic you'll want to look at
 * /webcomic/index.php
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">
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