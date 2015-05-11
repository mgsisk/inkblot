<?php
/**
 * No content template.
 * 
 * @package Inkblot
 */
?>

<?php if ( ! is_home()) : ?>
	
	<header class="post-header">
		<h1><?php _e('Nothing Found', 'inkblot'); ?></h1>
	</header><!-- .page-header -->
	
<?php endif; ?>

<div class="post-content">
	<p>
		
		<?php
			if (is_home() and current_user_can('publish_posts')) :
				printf('<a href="%s" class="first-post">%s</a>',
					admin_url('post-new.php'),
					__('Publish your first post', 'inkblot')
				);
			elseif (is_search()) :
				_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'inkblot');
			elseif ( ! is_home()) :
				_e("Sorry, but we can't seem to find what you're looking for. Perhaps searching will help.", 'inkblot');
			endif;
		?>
		
	</p>
	
	<?php is_home() ? '' : get_search_form(); ?>

</div><!-- .page-content -->