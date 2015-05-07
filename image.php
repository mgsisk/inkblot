<?php
/**
 * Image attachment template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php while (have_posts()) : the_post(); ?>
		
		<article role="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="post-header">
				<h1><?php the_title(); ?></h1>
				<div class="post-details">
					
					<?php
						print inkblot_post_parent();
						
						print inkblot_image_details();
						
						if ( ! post_password_required() and (comments_open() or get_comments_number())) :
							comments_popup_link();
						endif;
						
						edit_post_link(sprintf(__('Edit %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>'));
					?>
					
				</div>
			</header><!-- .post-header -->
			
			<nav class="navigation image-navigation" role="navigation" aria-label="<?php _e('Image navigation', 'inkblot'); ?>">
				<div class="nav-links">
					<div class="nav-previous"><?php previous_image_link(array(64, 64)); ?></div>
					<div class="nav-next"><?php next_image_link(array(64, 64)); ?></div>
				</div>
			</nav>
			
			<div class="post-image">
				
				<?php the_attachment_link($post->ID, true); ?>
				
			</div><!-- .post-image -->
			
			<?php if ($post->post_excerpt) : ?>
				
				<div class="post-excerpt"><?php the_excerpt(); ?></div><!-- .post-excerpt -->
				
			<?php endif; ?>
			
			<div class="post-content">
				
				<?php
					the_content();
					
					wp_link_pages(array(
						'before' => sprintf('<nav class="navigation pagination post" role="navigation" aria-label="%s"><div class="nav-links">', __('Post pages navigation', 'inkblot')),
						'after' => '</div></nav>',
						'pagelink' => sprintf('<span class="screen-reader-text">%s</span> %%', __('Page', 'inkblot'))
					));
				?>
				
			</div><!-- .post-content -->
			
		</article><!-- #post-<?php the_ID(); ?> -->
		
		<?php comments_template(); ?>
		
	<?php endwhile; ?>
	
</main>

<?php get_sidebar(); get_footer();