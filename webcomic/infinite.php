<?php
/**
 * Webcomic display template for infinite scrolling.
 * 
 * Handles Webcomic display on infinite-scroll pages. Refer to
 * `template/webcomic-infinite.php` to see how to setup a page for infinite
 * scrolling.
 * 
 * @package Inkblot
 */
?>

<article role="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="post-webcomic">
		
		<?php inkblot_widgetized('webcomic-header'); ?>
		
		<div class="webcomic-image<?php print get_theme_mod('webcomic_resize', true) ? '' : ' scroll'; ?>">
			
			<?php the_webcomic('full'); ?>
			
		</div><!-- .webcomic-image -->
		
		<?php inkblot_widgetized('webcomic-footer'); ?>
		
	</div><!-- .post-webcomic -->
	
	<header class="post-header">
		
		<p class="post-details">
			<?php
				print inkblot_post_datetime();
				
				print inkblot_post_author();
				
				if ( ! post_password_required() and (comments_open() or get_comments_number())) :
					comments_popup_link();
				endif;
				
				if (webcomic_prints_available()) :
					printf('<a href="%1$s">%2$s</a>',
						add_query_arg(array('prints' => ''), get_permalink()),
						__('Purchase', 'inkblot')
					);
				endif;
				
				print webcomic_infinite_link();
				
				edit_post_link(sprintf(__('Edit %1$s', 'inkblot'), '<span class="screen-reader-text">' . get_the_title() . '</span>'));
			?>
		</p>
	</header><!-- .post-header -->
	
</article><!-- #post-<?php the_ID(); ?> -->