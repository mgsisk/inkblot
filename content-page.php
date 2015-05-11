<?php
/**
 * Page content template.
 * 
 * @package Inkblot
 */
?>

<article role="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
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
				
				edit_post_link(sprintf(__('Edit %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>'));
			?>
			
		</div>
	</header><!-- .post-header -->
	<div class="post-content">
		
		<?php
			the_content();
			
			wp_link_pages(array(
				'before' => sprintf('<nav class="navigation pagination post" role="navigation" aria-label="%s"><div class="nav-links">', __('Post pages navigation', 'inkblot')),
				'after' => '</div></nav>',
				'pagelink' => sprintf('<span class="screen-reader-text">%s</span> %%', __('Page', 'inkblot'))
			));
		?>
		
	</div>
	
</article><!-- #post-<?php the_ID(); ?> -->