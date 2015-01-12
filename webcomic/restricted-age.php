<?php
/**
 * Age-restricted content template.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<main role="main">
	
	<header class="page-header">
		<h1><?php _e('Age Restricted Content', 'inkblot'); ?></h1>
	</header><!-- .page-header -->
	
	<div class="page-content">
		
		<?php if (is_null(verify_webcomic_age())) : ?>
			
			<form method="post">
				<p><?php _e('Please verify your age.', 'inkblot'); ?></p>
				<p>
					<label for="webcomic_birthday">Date of Birth</label>
					<input type="date" name="webcomic_birthday" id="webcomic_birthday" placeholder="<?php esc_attr_e('Date of Birth', 'inkblot'); ?>">
				</p>
				<p><button type="submit"><?php _e('Verify Age', 'inkblot'); ?></button></p>
			</form>
			
		<?php else : ?>
			
			<p><?php _e("Apologies, but you're not old enough to view this content.", 'inkblot'); ?></p>
			
		<?php endif; ?>
		
	</div><!-- .page-content -->
	
</main>

<?php get_sidebar(); get_footer();