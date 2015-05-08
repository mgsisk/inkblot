<?php
/**
 * Webcomic prints template.
 * 
 * This template is similar to the `single.php` template, but used specifically
 * for displaying webcomic print purchasing options.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<main role="main">
	
	<?php while (have_posts()) : the_post(); ?>
		
		<article role="article" id="post-<?php the_ID(); ?>" <?php post_class('webcomic-prints'); ?>>
			<header class="post-header">
				<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				<div class="post-details">
					
					<?php edit_post_link(sprintf(__('Edit %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>')); ?>
					
				</div>
			</header><!-- .post-header -->
			
			<div class="post-image">
				
				<?php the_webcomic(get_theme_mod('webcomic_archive_size', 'large')); ?>
				
			</div><!-- .post-image -->
			
			<?php if ($post->post_excerpt) : ?>
				
				<div class="post-excerpt"><?php the_excerpt(); ?></div><!-- .post-excerpt -->
				
			<?php endif; ?>
			
			<div class="post-content">
				
				<p>
					
					<?php
						if (webcomic_prints_available(true)) :
							webcomic_print_form('original', __('%total Original Print', 'inkblot'));
						endif;
						
						webcomic_print_form('domestic', __('%total Domestic', 'inkblot'));
						
						webcomic_print_form('international', __('%total International', 'inkblot'));
					?>
					
				</p>
				
			</div><!-- .post-content -->
			
		</article><!-- #post-<?php the_ID(); ?> -->
		
	<?php endwhile; ?>
	
</main>

<?php get_sidebar(); get_footer();