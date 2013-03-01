<?php
/** Generic archive template.
 * 
 * For Webcomic-specific archives, see /webcomic/archive.php
 * 
 * @package Inkblot
 * @see codex.wordpress.org/Template_Hierarchy
 */

get_header(); $object = get_queried_object(); $taxonomy = empty( $object->taxonomy ) ? false : get_taxonomy( $object->taxonomy ); ?>

<main role="main">
	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php if ( is_day() ) : ?>
				<hgroup>
					<h1><?php _e( 'Daily Archives', 'inkblot' ); ?></h1>
					<h2><?php the_date(); ?></h2>
				</hgroup>
				<?php printf( __( 'Daily Archives: %s', 'inkblot' ), get_the_date() ); ?>
			<?php elseif ( is_month() ) : ?>
				<hgroup>
					<h1><?php _e( 'Monthly Archives', 'inkblot' ); ?></h1>
					<h2><?php the_date( __( 'F Y', 'inkblot' ) ); ?></h2>
				</hgroup>
			<?php elseif ( is_year() ) : ?>
				<hgroup>
					<h1><?php _e( 'Yearly Archives', 'inkblot' ); ?></h1>
					<h2><?php the_date( __( 'Y', 'inkblot' ) ); ?></h2>
				</hgroup>
			<?php elseif ( is_category() ) : ?>
				<hgroup>
					<h1><?php _e( 'Category Archives', 'inkblot' ); ?></h1>
					<h2><?php single_cat_title(); ?></h2>
				</hgroup>
			<?php elseif ( is_tag() ) : ?>
				<hgroup>
					<h1><?php _e( 'Tag Archives', 'inkblot' ); ?></h1>
					<h2><?php single_tag_title(); ?></h2>
				</hgroup>
			<?php elseif ( is_tax() ) : ?>
				<hgroup>
					<h1><?php printf( __( '%s Archives', 'inkblot' ), $taxonomy->labels->name ); ?></h1>
					<h2><?php echo apply_filters( 'single_term_title', $object->name ); ?></h2>
				</hgroup>
			<?php elseif ( is_author() ) : ?>
				<hgroup>
					<h1><?php _e( 'Author Archives', 'inkblot' ); ?></h1>
					<h2><?php echo apply_filters( 'the_author', $object->display_name ); ?></h2>
				</hgroup>
			<?php elseif ( is_post_type_archive() ) : ?>
				<h1><?php printf( __( '%s Archives', 'inkblot' ), post_type_archive_title( '', false ) ); ?></h1>
			<?php else : ?>
				<h1><?php _e( 'Archives', 'inkblot' ); ?></h1>
			<?php endif; ?>
		</header><!-- .page-header -->
		<?php if ( is_author() and $image = get_avatar( $object->user_email, 60 ) ) : ?>
			<div class="page-image"><?php echo $image; ?></div><!-- .page-image -->
		<?php endif; ?>
		<?php if ( is_category() and $description = category_description() ) : ?>
			<div class="page-content"><?php echo $description; ?></div><!-- .page-content -->
		<?php elseif ( is_tag() and $description = tag_description() ) : ?>
			<div class="page-content"><?php echo $description; ?></div><!-- .page-content -->
		<?php elseif ( is_tax() and $description = term_description( $object->term_id, $object->taxonomy ) ) : ?>
			<div class="page-content"><?php echo $description; ?></div><!-- .page-content -->
		<?php elseif ( is_author() and $description = get_the_author_meta( 'description', $object->ID ) ) : ?>
			<div class="page-content"><?php echo $description; ?></div><!-- .page-content -->
		<?php elseif ( is_post_type_archive() and !empty( $object->description ) ) : ?>
			<div class="page-content"><?php echo wpautop( $object->description ); ?></div><!-- .page-content -->
		<?php endif; ?>
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
		<?php get_template_part( 'content-none', 'archive' ); ?>
	<?php endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>