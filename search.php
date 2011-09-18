<?php get_header(); ?>

<?php inkblot_inside_content(); ?>

<?php /* Results were found */ if ( have_posts() ) : ?>
	
	<h1><?php _e( 'Search results for', 'inkblot' ); ?> <span class="search-terms"><?php echo wp_specialchars( stripslashes( $_GET[ 's' ] ), true ); ?></span></h1>
	
	<div class="navi navi-posts navi-posts-above">
		<div class="navi-previous"><?php previous_posts_link( __( '&laquo; Previous', 'inkblot' ) ); ?></div>
		<div class="navi-next"><?php next_posts_link( __( 'Next &raquo;', 'inkblot' ) ); ?></div>
	</div>
	
	<?php while ( have_posts() ) : the_post(); ?>
	
		<?php /* Comic posts */ if ( get_option( 'inkblot_comic_archives' ) && in_comic_category() ) { ?>
		
	<div id="comic-<?php the_id(); ?>" <?php post_class(); ?>>
		<div class="object"><?php the_comic( get_option( 'inkblot_comic_archives_size' ), 'self' ); ?></div>
		<h2><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent Link to %s', 'inkblot' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	</div>
	
		<?php /* Non-comic posts */ } else { ?>
		
	<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
		<h2><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent Link to %s', 'inkblot' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="entry"><?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?></div>
		<div class="meta"><?php edit_post_link( __('Edit', 'inkblot' ), '', ' | ' ); the_time( get_option( 'date_format' ) ); ?> | <?php comments_popup_link( __( 'No Comments', 'inkblot' ), __( '1 Comment', 'inkblot' ), __( '% Comments', 'inkblot' ) ); ?></div>
	</div>
	
	<?php } endwhile; ?>
	
	<div class="navi navi-posts navi-posts-below">
		<div class="navi-previous"><?php previous_posts_link( __( '&laquo; Previous', 'inkblot' ) ); ?></div>
		<div class="navi-next"><?php next_posts_link( __( 'Next &raquo;', 'inkblot' ) ); ?></div>
	</div>

<?php /* No results were found */ else : ?>
	
	<h1><?php _e( 'No Results', 'inkblot' ); ?></h1>
	<p><?php printf( __( 'Sorry, but nothing could be found that matched your search terms: <span class="search-terms">%s</span>. Please try again.' ), wp_specialchars( stripslashes( $_GET[ 's' ] ), true ) ); ?></p>
	<?php get_search_form(); ?>
	
<?php endif; ?>

<?php get_footer(); ?>