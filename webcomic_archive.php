<?php
/**
 * Template Name: Webcomic Archive
 */

the_post(); get_header(); global $inkblot; $post_meta = current( get_post_meta( $post->ID, 'inkblot' ) );
?>

<div id="main">
	<?php if ( '3c3' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>
	<section id="content">
		<div>
			<div id="content-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-above' ); ?></div>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div class="content"><?php the_content(); wp_link_pages( array( 'before' => '<nav class="paginated">', 'after' => '</nav>' ) ); edit_post_link( __( 'Edit', 'inkblot' ), '<p>', '</p>' ); ?></div>
				<?php
					$format     = ( 'month' == $post_meta[ 'webcomic_group' ] ) ? 'grid' : 'olist';
					$image      = ( !empty( $post_meta[ 'webcomic_image' ] ) ) ? '&image=' . $post_meta[ 'webcomic_image' ] : '';
					$collection = ( !empty( $post_meta[ 'webcomic_collection' ] ) ) ? '&term_group=' . $post_meta[ 'webcomic_collection' ] : '';
					
					webcomic_archive( 'last_only=1&group=' . $post_meta[ 'webcomic_group' ] . '&format=' . $format . $image . $collection );
					comments_template( '', true );
				?>
			</article><!-- #post-<?php the_ID(); ?> -->
			<div id="content-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-below' ); ?></div>
		</div>
	</section><!--#content-->
</div><!--#main-->

<?php get_sidebar(); get_footer(); ?>