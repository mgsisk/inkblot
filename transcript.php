<?php
	if ( !empty( $_SERVER[ 'SCRIPT_FILENAME' ] ) && 'transcripts.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) ) die( 'Please do not load this page directly.' );
	
	if ( post_password_required() ) {
		echo '<p class="nopassword">' . __('This post is password protected. Enter the password to view the transcript.', 'inkblot' ) . '</p>';
		return;
	}
?>

<?php /* A published transcript exists */ if ( have_transcript( 'publish' ) ) :?>
	
	<div id="transcript-title"><span><?php _e( 'Transcript', 'inkblot' ); ?></span></div>
	<div id="transcript"><?php echo $transcript; ?></div>
	
<?php /* Transcribing is allowed and no draft is awaiting moderation */ elseif ( get_option( 'comic_transcripts_allowed' ) && !have_transcript( 'draft' ) ) : ?>

	<div id="transcript-title"><span><?php transcript_form_title(); ?></span></div>
	<div id="transcript">
	<?php /* Users must be registered and logged in to transcript */ if ( get_option( 'comic_transcripts_loggedin' ) && !$user_ID) : ?>
		<p><?php printf( __( 'You must be <a href="%s">logged in</a> to transcribe.', 'inkblot' ), get_option( 'siteurl' ) . '/wp-login.php?redirect_to=' . urlencode( get_permalink() ) ); ?></p>
	<?php else : ?>
		<form action="" method="post" id="transcriptform">
		<div id="transcript-response"></div> <!-- For AJAX Responses -->
		<?php if ( $user_ID ) : ?>
			<p><?php printf( __( 'Transcribing as <a href="%s">%s</a>', 'inkblot' ), get_option( 'siteurl' ) . '/wp-admin/profile.php', $user_identity ); ?> | <a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( 'Log Out &raquo;', 'inkblot' ); ?></a></p>
		<?php else : ?>
			<p><label for="trans_author"><?php _e( 'Name', 'inkblot' ); ?></label><input type="text" name="trans_author" id="trans_author" /><?php if ( $req ) _e( ' <span class="transcript-required">(required)</span>', 'inkblot' ); ?></p>
			<p><label for="trans_email"><?php _e( 'E-mail', 'inkblot' ); ?></label><input type="text" name="trans_email" id="trans_email" /><?php if ( $req ) _e( ' <span class="transcript-required">(required)</span>', 'inkblot' ); ?></p>
			<p><label for="trans_captcha"><?php _e( 'What is one minus two?', 'inkblot' ); ?></label><input type="text" name="trans_captcha" id="trans_captcha" /> <?php _e( ' <span class="transcript-required">(required)</span>', 'inkblot' ); ?></p>
		<?php endif; ?>
			<p><textarea rows="7" cols="40" name="transcript"><?php if ( have_transcript( 'pending' ) ) echo $transcript; ?></textarea></p>
			<p class="align-right"><span class="transcript-allowed-code"><?php printf( __( 'Some <abbr title="%s">XHTML</abbr> Allowed', 'inkblot' ), allowed_tags() ); ?></span>&emsp;<input type="submit" value="<?php _e( 'Transcribe', 'inkblot' ); ?>" /><?php transcript_id_fields( '-1' ); ?></p>
		</form>
	<?php endif; ?>
	</div>

<?php endif; ?>