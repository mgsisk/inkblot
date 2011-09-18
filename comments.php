<?php
	if ( !empty( $_SERVER[ 'SCRIPT_FILENAME' ] ) && 'comments.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) ) die( 'Please do not load this page directly.' );
	
	get_sidebar( 'content-insert' );
	
	if ( post_password_required() ) {
		echo '<div class="nopassword">' . __( 'This post is password protected. Enter the password to view comments.', 'inkblot' ) . '</div>';
		return;
	}
?>

<div id="comments">
<?php if ( have_comments() ) : ?>
	<h2 id="comments-title"><span><?php comments_number( __( 'No Responses', 'inkblot' ), __( '1 Response', 'inkblot' ), __( '% Responses', 'inkblot' ) );?></span></h2>
	<div class="navi navi-paged navi-paged-above"><?php paginate_comments_links(); ?></div>
	<ol class="commentlist"><?php wp_list_comments( 'avatar_size=60' ); ?></ol>
	<div class="navi navi-paged navi-paged-below"><?php paginate_comments_links(); ?></div>
<?php elseif ( 'open' == $post->comment_status ) : //comments are open but none have been posted ?>

<?php else : //Comments are closed ?>

<?php endif; if ( 'open' == $post->comment_status ) : //Comments are allowed ?>
	<div id="respond">
	<?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
		<p><?php printf( __( 'You must be <a href="%s">logged in</a> to comment.', 'inkblot' ), get_option( 'siteurl' ) . '/wp-login.php?redirect_to=' . urlencode( get_permalink() ) ); ?></p>
	<?php else : ?>
		<h2 id="commentsform-title"><span><?php comment_form_title( __( 'Leave a Comment', 'inkblot' ), __( 'Leave a Comment to %s', 'inkblot' ) ); ?></span></h2>
		<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( $user_ID ) : ?>
			<p><?php printf( __( 'Commenting as <a href="%s">%s</a>', 'inkblot' ), get_option( 'siteurl' ) . '/wp-admin/profile.php', $user_identity ); ?> | <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( 'Log Out &raquo;', 'inkblot' ); ?></a></p>
		<?php else : ?>
			<p><label for="author"><?php _e( 'Name', 'inkblot' ); ?></label><input type="text" name="author" id="author" /><?php if ( $req ) _e( ' <span class="comment-required">(required)</span>', 'inkblot' ); ?></p>
			<p><label for="email"><?php _e( 'E-mail', 'inkblot' ); ?></label><input type="text" name="email" id="email" /><?php if ( $req ) _e( ' <span class="comment-required">(required)</span>', 'inkblot' ); ?></p>
			<p><label for="url"><?php _e( 'Website', 'inkblot' ); ?></label><input type="text" name="url" id="url" /></p>
		<?php endif; ?>
			<p><textarea rows="7" cols="40" name="comment" id="comment"></textarea></p>
			<div><?php do_action( 'comment_form', $post->ID ); ?></div>
			<p class="align-right"><span class="comment-allowed-code"><?php printf( __( 'Some <abbr title="%s">XHTML</abbr> Allowed', 'inkblot' ), allowed_tags() ); ?></span>&emsp;<span id="cancel-comment-reply"><?php cancel_comment_reply_link( __( 'Cancel', 'inkblot' ) ); ?></span>&emsp;<input name="submit" type="submit" id="submit" value="<?php _e( 'Comment', 'inkblot' ); ?>" /><?php comment_id_fields(); ?></p>
		</form>
	<?php endif; ?>
	</div> <!-- #respond -->
<?php endif; ?>
</div> <!-- #comments -->