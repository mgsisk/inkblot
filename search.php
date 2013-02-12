<?php
/** Search results template.
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">
	<?php if ( have_posts() ) : global $wp_query; ?>
		<header class="page-header">
			<hgroup>
				<h1><?php printf( __( 'Searching for %s', 'inkblot' ), get_search_query() ); ?></h1>
				<h2><?php printf( __( '%s results', 'inkblot' ), $wp_query->found_posts ); ?></h2>
			</hgroup>
		</header><!-- .page-header -->
		<?php inkblot_posts_nav( 'above' ); ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( webcomic() and is_a_webcomic() ) : ?>
				<?php get_template_part( 'webcomic/content', get_post_type() ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endif; ?>
		<?php endwhile; ?>
		<?php inkblot_posts_nav( 'below' ); ?>
	<?php else : ?>
		<header class="page-header">
			<hgroup>
				<h1><?php _e( 'Nothing Found', 'inkblot' ); ?></h1>
				<h2><?php printf( __( 'Searching for %s', 'inkblot' ), get_search_query() ); ?></h2>
			</hgroup>
		</header><!-- .page-header -->
		<div class="page-content">
			<p><?php _e( 'No results containing all your search terms were found.', 'inkblot' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .page-content -->
	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>