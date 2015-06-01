<?php
/**
 * Webcomic transcripts template.
 * 
 * This template is nearly identical to WordPress' own `comments.php` template,
 * except that we have to check for (and display) transcripts that are
 * Pending Review (i.e. transcripts that users can improve upon).
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

global $post; ?>

<section id="webcomic-transcripts">
	
	<?php
		if (post_password_required()) :
			// a password is required to view this post
		else : 
			if ($transcripts = get_webcomic_transcripts()) : ?>
				
				<header class="webcomic-transcripts-header">
					<h2><?php _e('Transcripts', 'inkblot'); ?></h2>
				</header><!-- .comments-header -->
				
			<?php
				foreach ($transcripts as $post) : setup_postdata($post);
					inkblot_webcomic_transcript();
					
					wp_reset_postdata();
				endforeach;
			endif;
			
			if (webcomic_transcripts_open() and $transcripts = get_webcomic_transcripts(true)) :
				foreach ($transcripts as $transcript) :
					webcomic_transcript_form(array(), $transcript);
				endforeach;
			endif;
			
			webcomic_transcript_form();
		endif;
	?>
	
</section><!-- #webcomic-transcripts -->