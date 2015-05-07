<?php
/**
 * Generic content template.
 * 
 * For Webcomic-specific content, see `webcomic/content.php`/
 * 
 * @package Inkblot
 */
?>

<article role="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if (has_post_thumbnail()) : ?>
		
		<div class="post-image"><?php the_post_thumbnail(); ?></div><!-- .post-image -->
		
	<?php endif; ?>
	
	<header class="post-header">
		
		<?php if (is_single()) : ?>
			
			<h1><?php the_title(); ?></h1>
			
		<?php else : ?>
			
			<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			
		<?php endif; ?>
		
		<div class="post-details">
			
			<?php
				print inkblot_post_datetime();
				
				print inkblot_post_author();
				
				if ( ! post_password_required() and (comments_open() or get_comments_number())) :
					comments_popup_link();
				endif;
				
				edit_post_link(sprintf(__('Edit %s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>'));
			?>
			
		</div>
	</header><!-- .post-header -->
	
	<?php if (is_search()) : ?>
		
		<div class="post-excerpt"><?php the_excerpt(); ?></div><!-- .post-excerpt -->
		
	<?php else : ?>
		
		<div class="post-content">
			
			<?php
				the_content();
				
				wp_link_pages(array(
					'before' => sprintf('<nav role="navigation" class="post-pages" aria-label="%s">', __('Post Pages Navigation', 'inkblot')),
					'after' => '</nav>'
				));
			?>
			
		</div>
		
	<?php endif; ?>
	
	<footer class="post-footer">
		
		<?php
			the_terms(get_the_ID(), 'category', '<span class="post-categories">', __(', ', 'inkblot'), '</span>');
			
			the_tags('<span class="post-tags">', __(', ', 'inkblot'), '</span>');
		?>
		
	</footer><!-- .post-footer -->
	
</article><!-- #post-<?php the_ID(); ?> -->