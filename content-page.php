<?php
/**
 * Page content template.
 * 
 * @package Inkblot
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if (has_post_thumbnail()) : ?>
		<div class="post-image"><?php the_post_thumbnail(); ?></div><!-- .post-image -->
	<?php endif; ?>
	
	<header class="post-header">
		<h1><?php the_title(); ?></h1>
		<div class="post-details">
			
			<?php
				if ( ! post_password_required() and (comments_open() or get_comments_number())) :
					print comments_popup_link();
				endif;
				
				edit_post_link();
			?>
			
		</div>
	</header><!-- .post-header -->
	<div class="post-content">
		
		<?php
			the_content();
			
			wp_link_pages(array(
				'before' => '<nav class="post-pages">',
				'after' => '</nav>'
			));
		?>
		
	</div>
	
</article><!-- #post-<?php the_ID(); ?> -->