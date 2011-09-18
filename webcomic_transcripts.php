<div id="transcripts-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-transcripts-above' ); ?></div>
<section id="transcripts"><?php if ( post_password_required() ) { echo '</section><!-- #transcripts -->'; return; } ?>
	<?php if ( have_webcomic_transcripts() ) { ?>
	<h1><?php _e( 'Transcripts', 'inkblot' ); ?></h1>
	<?php list_webcomic_transcripts(); } webcomic_transcribe_form(); ?>
</section><!-- #transcripts -->
<div id="transcripts-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-transcripts-below' ); ?></div>