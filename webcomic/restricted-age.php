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
				<p><?php printf(__('Are you at least %1$s years of age?', 'inkblot'), WebcomicTag::get_verify_webcomic_age()); ?></p>
				<p>
					<button type="submit" name="webcomic_birthday" value="1"><?php _e('Yes', 'inkblot'); ?></button>
					<button type="submit" name="webcomic_birthday" value="0"><?php _e('No', 'inkblot'); ?></button>
				</p>
			</form>
			
		<?php else : ?>
			
			<p><?php _e("Apologies, but you're not old enough to view this content.", 'inkblot'); ?></p>
			
		<?php endif; ?>
		
	</div><!-- .page-content -->
	
</main>

<?php get_sidebar(); get_footer();