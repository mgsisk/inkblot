<?php
/** Comments template.
 * 
 * @package Inkblot
 */
?>

<aside id="comments">
	<div id="comment-header" role="complementary" class="widgets"><?php dynamic_sidebar( 'comment-header' ); ?></div><!-- #comment-header -->
	<?php if ( post_password_required() ) : ?>
		
	<?php else : ?>
		<?php if ( have_comments() ) : ?>
			<header class="comments-header">
				<h1><?php comments_number( '', __( 'One Comment', 'inkblot' ), __( '% Comments', 'inkblot' ) ); ?></h1>
			</header><!-- .comments-header -->
			<?php inkblot_comments_nav( 'above' ); ?>
			<?php
				wp_list_comments( array(
					'style'        => 'div',
					'avatar_size'  => 32,
					'callback'     => 'inkblot_start_comment',
					'end-callback' => 'inkblot_end_comment'
				) );
			?>
			<?php inkblot_comments_nav( 'below' ); ?>
		<?php elseif ( !comments_open() and get_comments_number() and post_type_supports( get_post_type(), 'comments' ) ) : ?>
			
		<?php endif; ?>
		<?php comment_form(); ?>
	<?php endif; ?>
	<div id="comment-footer" role="complementary" class="widgets"><?php dynamic_sidebar( 'comment-footer' ); ?></div><!-- #comment-footer -->
</aside><!-- #comments -->