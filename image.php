<?php
/**
 * Image attachment template.
 * 
 * @package Inkblot
 * @see http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">
	
	<?php while (have_posts()) : the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="post-header">
				<h1><?php the_title(); ?></h1>
				<div class="post-details">
					
					<?php
						print inkblot_post_parent();
						
						print inkblot_image_details();
						
						if ( ! post_password_required() and (comments_open() or get_comments_number())) :
							comments_popup_link();
						endif;
						
						edit_post_link();
					?>
					
				</div>
			</header><!-- .post-header -->
			
			<nav class="posts">
				
				<?php
					previous_image_link(array(64, 64));
					next_image_link(array(64, 64));
				?>
				
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
						'before' => '<nav class="post-pages">',
						'after' => '</nav>'
					));
				?>
				
			</div><!-- .post-content -->
			
		</article><!-- #post-<?php the_ID(); ?> -->
		
		<?php comments_template(); ?>
		
	<?php endwhile; ?>
	
</main>

<?php get_sidebar(); get_footer();