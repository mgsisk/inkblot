<?php
/**
 * Role-restricted content template.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header();

global $wp; ?>

<main role="main">
	
	<header class="page-header">
		<h1><?php _e('Restricted Content', 'inkblot'); ?></h1>
	</header><!-- .page-header -->
	
	<div class="page-content">
		
		<?php if (is_null(verify_webcomic_role())) : ?>
			
			<p><?php printf(__('Please <a href="%1$s">login</a> to view this content.', 'inkblot'), wp_login_url(site_url($wp->request))); ?></p>
			
		<?php else : ?>
			
			<p><?php _e("Apologies, but you don't have permission to view this content.", 'inkblot'); ?></p>
			
		<?php endif; ?>
		
	</div><!-- .page-content -->
	
</main>

<?php get_sidebar(); get_footer();