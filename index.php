<?php get_header(); ?>
	
<?php /* Display the comic and comic post on the front page */ if ( get_option( 'inkblot_comic_frontpage' ) && !is_paged() ) { ?>
	
	<?php $comics = comic_loop( 1, get_option( 'inkblot_comic_frontpage_series' ), '&order=' . get_option( 'inkblot_comic_frontpage_order' ) ); ?>
	
	<?php /** Display the comic */ while ( $comics->have_posts() ) : $comics->the_post(); ?>
	<div id="comic-<?php the_id(); ?>" <?php post_class( 'align-center' ); ?>>
		<div <?php inkblot_comic_navi_class( true ); ?>><?php comics_nav_link( 'limit=' . get_option( 'inkblot_comic_limit' ) ); ?></div>
		<div class="object"><?php the_comic( 'full', get_inkblot_comic_link(), get_option( 'inkblot_comic_limit' ) ); ?></div>
		<div <?php inkblot_comic_navi_class(); ?>><?php comics_nav_link( 'limit=' . get_option( 'inkblot_comic_limit' ) ); ?></div>
	</div>
	<?php endwhile; inkblot_inside_content(); ?>
	
	<h1 class="hide"><?php bloginfo( 'name' ); ?></h1>
	
	<?php /** Display the comic post */ while ( $comics->have_posts() ) : $comics->the_post(); ?>
		<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent Link to %s', 'inkblot' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="entry"><?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?></div>
			<div class="meta"><?php edit_post_link( __('Edit', 'inkblot' ), '', ' | ' ); the_time( get_option( 'date_format' ) ); ?> | <?php comments_popup_link( __( 'No Comments', 'inkblot' ), __( '1 Comment', 'inkblot' ), __( '% Comments', 'inkblot' ) ); ?></div>
		</div>
	<?php endwhile; ?>
	
	<div class="blog-title"><span><?php _e( 'Blog', 'inkblot' ); ?></span></div>
	
<?php } else { inkblot_inside_content(); } get_sidebar( 'content-insert' ); ?>
	
	<div class="navi navi-posts navi-posts-above">
		<div class="navi-previous"><?php previous_posts_link( __( '&laquo; Previous', 'inkblot' ) ); ?></div>
		<div class="navi-next"><?php next_posts_link( __( 'Next &raquo;', 'inkblot' ) ); ?></div>
	</div>
	
	<?php /* Display the blog */ ignore_comics(); while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
			<h2><a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent Link to %s', 'inkblot' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<div class="entry"><?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?></div>
			<div class="meta"><?php edit_post_link( __('Edit', 'inkblot' ), '', ' | ' ); the_time( get_option( 'date_format' ) ); ?> | <?php comments_popup_link( __( 'No Comments', 'inkblot' ), __( '1 Comment', 'inkblot' ), __( '% Comments', 'inkblot' ) ); ?></div>
		</div>
	<?php endwhile; ?>
	
	<div class="navi navi-posts navi-posts-below">
		<div class="navi-previous"><?php previous_posts_link( __( '&laquo; Previous', 'inkblot' ) ); ?></div>
		<div class="navi-next"><?php next_posts_link( __( 'Next &raquo;', 'inkblot' ) ); ?></div>
	</div>

<?php get_footer(); ?>