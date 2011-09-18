<?php get_header(); the_post(); ?>

<?php /* Display the comic for comic posts */ if ( in_comic_category() ) { ?>
	
	<div id="comic-<?php the_id(); ?>" <?php post_class( 'align-center' ); ?>>
		<div <?php inkblot_comic_navi_class( true ); ?>><?php comics_nav_link( 'limit=' . get_option( 'inkblot_comic_limit' ) ); ?></div>
		<div class="object"><?php the_comic( 'full', get_inkblot_comic_link(), get_option( 'inkblot_comic_limit' ) ); ?></div>
		<div <?php inkblot_comic_navi_class(); ?>><?php comics_nav_link( 'limit=' . get_option( 'inkblot_comic_limit' ) ); ?></div>
	</div>
	
<?php } inkblot_inside_content(); ?>

<?php /* Display post navigation for non-comic posts */ if ( !in_comic_category() ) { ?>
	
	<div class="navi navi-posts navi-posts-above">
		<div class="navi-previous"><span><?php previous_post_link( '%link', '&laquo; %title', false, get_comic_category( true, 'post_link_exclude' ) ); ?></span></div>
		<div class="navi-next"><span><?php next_post_link( '%link', '%title &raquo;', false, get_comic_category( true, 'post_link_exclude' ) ); ?></span></div>
	</div>
	
<?php } ?>

	<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
		<h1><?php the_title(); ?></h1>
		<div class="entry">
			<?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?>
			<?php wp_link_pages( 'before=<div class="navi navi-paged"><span class="navi-pages-title">' . __( 'Pages:', 'inkblot' ) . '</span>&after=</div>' ); ?>
		</div>
	<?php if ( in_comic_category() && get_option( 'inkblot_comic_embed' ) ) { ?>
		<p><label><?php _e( 'Share This Comic', 'inkblot' ); the_comic_embed( get_option( 'inkblot_comic_embed_size' ), get_option( 'inkblot_comic_embed_format' ) ); ?></label></p>
	<?php } transcript_template(); ?>
		<div class="meta meta-single">
			<?php printf( __( 'Posted on %s at %s<!-- by %s--> in ', 'inkblot' ), get_the_time( get_option( 'date_format' ) ), get_the_time(), get_the_author() ); the_category( ', ' ); if ( in_comic_chapter() ) { _e( ' as part of ', 'inkblot' ); the_chapter_link(); ?> &laquo; <?php the_chapter_link( 'volume' ); } if( get_the_tags() ) _e( ' and tagged with ', 'inkblot' ); the_tags( '',', ' ); printf( __( '. Follow responses to this post with the <a href="%s">comments feed</a>. ', 'inkblot' ), get_post_comments_feed_link() ); ?>
			<?php
				if ( ( 'open' == $post->comment_status ) && ( 'open' == $post->ping_status ) )
					printf( __( 'You can <a href="#comment">leave a comment</a> or <a href="%s" rel="trackback">trackback</a> from your own site. ', 'inkblot' ), get_trackback_url() );
				elseif ( !( 'open' == $post->comment_status ) && ( 'open' == $post->ping_status ) )
					printf( __( 'Comments are closed, but you can <a href="%s " rel="trackback">trackback</a> from your own site. ', 'inkblot' ), get_trackback_url() );
				elseif ( ( 'open' == $post->comment_status ) && !( 'open' == $post->ping_status ) )
					_e( 'You can <a href="#comment">leave a comment</a>. ', 'inkblot' );
				edit_post_link( __( 'Edit This', 'inkblot' ) );
			?>
		</div>
	</div>

<?php /* Display post navigation for non-comic posts */ if ( !in_comic_category() ) { ?>
	
	<div class="navi navi-posts navi-posts-below">
		<div class="navi-previous"><span><?php previous_post_link( '%link', '&laquo; %title', false, get_comic_category( true, 'post_link_exclude' ) ); ?></span></div>
		<div class="navi-next"><span><?php next_post_link( '%link', '%title &raquo;', false, get_comic_category( true, 'post_link_exclude' ) ); ?></span></div>
	</div>
	
<?php } ?>

	<?php comments_template(); ?>

<?php get_footer(); ?>