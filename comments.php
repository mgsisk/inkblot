<?php
/**
 * Comments template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */
?>

<section id="comments">
	
	<?php
		print inkblot_widgetized('comment-header');
		
		if (post_password_required()) :
			// a password is required to view this post
		else :
			if (have_comments()) : ?>
				
				<header class="comments-header">
					<h2><?php comments_number(); ?></h2>
				</header><!-- .comments-header -->
				
			<?php
				wp_list_comments(array(
					'style' => 'div',
					'avatar_size' => 64,
					'callback' => 'inkblot_start_comment',
					'end-callback' => 'inkblot_end_comment'
				));
				
				print inkblot_comments_nav(get_theme_mod('paged_comments', true));
			elseif ( ! comments_open() and get_comments_number() and post_type_supports(get_post_type(), 'comments')) :
				// comments are closed, but the post has and supports comments
			endif;
			
			comment_form();
		endif;
		
		print inkblot_widgetized('comment-footer');
	?>
	
</section><!-- #comments -->