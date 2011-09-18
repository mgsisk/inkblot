<?php get_header(); ?>

<?php inkblot_inside_content(); the_post(); ?>
	
	<div class="navi navi-attachment navi-attachment-above">
		<div class="navi-previous"><?php previous_image_link(); ?></div>
		<div class="navi-next"><?php next_image_link(); ?></div>
	</div>
	
	<div id="attachment-<?php the_id(); ?>" <?php post_class(); ?>>
		<h1 class="hide"><?php the_title(); ?></h1>
		<div class="object"><?php echo wp_get_attachment_link( $post->ID, 'medium' ); ?></div>
		<div class="entry">
			<?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?>
			<?php wp_link_pages( 'before=<div class="navi navi-paged"><span class="navi-pages-title">' . __( 'Pages:', 'inkblot' ) . '</span>&after=</div>' ); ?>
		</div>
		<div class="meta meta-single">
			<?php printf( __( 'Attached to <a href="%s">%s</a> on %s at %s<!-- by %s--> in ', 'inkblot' ), get_permalink( $post->post_parent ), get_the_title( $post->post_parent ), get_the_time( get_option( 'date_format' ) ), get_the_time(), get_the_author() ); the_category( ', ' ); if( get_the_tags() ) _e( ' and tagged with ', 'inkblot' ); the_tags( '',', ' ); printf( __( '. Follow responses to this post with the <a href="%s">comments feed</a>. ', 'inkblot' ), get_post_comments_feed_link() ); ?>
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
	
	<div class="navi navi-attachment navi-attachment-below">
		<div class="navi-previous"><?php previous_image_link(); ?></div>
		<div class="navi-next"><?php next_image_link(); ?></div>
	</div>
	
	<?php comments_template(); ?>

<?php get_footer(); ?>