<?php get_header(); ?>

<?php inkblot_inside_content(); the_post(); ?>
	
	<?php /* Category */ if ( is_category() ) { ?><h1><?php printf( __( '%s Posts', 'inkblot' ), single_cat_title( '', false ) ); ?></h1>
	<?php /* Tag */} elseif( is_tag() ) { ?><h1><?php printf( __( '%s Posts', 'inkblot' ), single_tag_title( '', false ) ); ?></h1>
	<?php /* Author */} elseif ( is_author() ) { ?><h1><?php  printf( __( '%s Posts', 'inkblot' ), get_the_author() ); ?></h1>
	<?php /* Daily */} elseif ( is_day() ) { ?><h1><?php printf( __( '%s Archive', 'inkblot' ), get_the_time( get_option( 'date_format') ) ); ?></h1>
	<?php /* Monthly */} elseif ( is_month() ) { ?><h1><?php printf( __( '%s Archive', 'inkblot' ), get_the_time( 'F Y' ) ); ?></h1>
	<?php /* Yearly */} elseif ( is_year() ) { ?><h1><?php printf( __( '%s Archive', 'inkblot' ), get_the_time( 'Y' ) ); ?></h1>
	<?php /* Chapter */} elseif ( is_tax() ) { ?><h1><?php printf( __( '%s Comics', 'inkblot' ), single_chapter_title( '', false ) ); ?></h1>
	<?php } rewind_posts(); ?>
	
	<div class="navi navi-posts navi-posts-above">
		<div class="navi-previous"><?php previous_posts_link( __( '&laquo; Previous', 'inkblot' ) ); ?></div>
		<div class="navi-next"><?php next_posts_link( __( 'Next &raquo;', 'inkblot' ) ); ?></div>
	</div>
	
	<?php while ( have_posts() ) : the_post(); ?>
	
		<?php /* Comic posts */ if ( get_option( 'inkblot_comic_archives' ) && in_comic_category() ) { ?>
		
	<div id="comic-<?php the_id(); ?>" <?php post_class( 'align-center' ); ?>>
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

<?php get_footer(); ?>