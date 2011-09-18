<?php
/**
 * Template Name: Archive
 */
	get_header();
?>

<?php inkblot_inside_content(); the_post(); ?>

<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
	<h1><?php the_title(); ?></h1>
	<div class="entry">
		<?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?>
		<?php wp_link_pages( 'before=<div class="navi navi-paged"><span class="navi-pages-title">' . __( 'Pages:', 'inkblot' ) . '</span>&after=</div>' ); ?>
		<?php edit_post_link( __('Edit This', 'inkblot'), '<p class="edit-link">', '</p>' ); ?>
	</div>
</div>

<?php
	$args  = ( $webcomic_series ) ? '&series=' . $webcomic_series->term_id : '';
	$args .= '&groupby='      . get_post_meta( $post->ID, 'inkblot_archive_groupby', true );
	$args .= '&format='       . get_post_meta( $post->ID, 'inkblot_archive_format', true );
	$args .= '&post_order='   . get_post_meta( $post->ID, 'inkblot_archive_post_order', true );
	$args .= '&orderby='      . get_post_meta( $post->ID, 'inkblot_archive_orderby', true );
	$args .= '&order='        . get_post_meta( $post->ID, 'inkblot_archive_order', true );
	$args .= '&bound='        . get_post_meta( $post->ID, 'inkblot_archive_bound', true );
	$args .= '&descriptions=' . get_post_meta( $post->ID, 'inkblot_archive_descriptions', true );
	$args .= '&pages='        . get_post_meta( $post->ID, 'inkblot_archive_pages', true );
	
	comic_archive( $args );
?>

<?php if ( get_post_meta( $post->ID, 'inkblot_page_comments', true ) ) comments_template(); ?>

<?php get_footer(); ?>