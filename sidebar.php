<?php
/**
 * Standard sidebar template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

if (is_page_template('template/full-width.php') and ! get_post_meta(get_the_ID(), 'inkblot_sidebars', true)) {
	return;
}
?>

<?php if ('one-column' !== get_theme_mod('content', 'one-column') or is_customize_preview()) : ?>
	
	<div class="sidebar1 widgets columns-<?php print inkblot_count_widgets('primary-sidebar'); ?>">
		<h1 class="screen-reader-text"><?php _e('Primary Sidebar', 'inkblot'); ?></h1>
		
		<?php if ( ! dynamic_sidebar('primary-sidebar') and current_user_can('edit_theme_options')) : ?>
			
			<aside class="widget">
				<h1><?php _e('Primary Sidebar', 'inkblot'); ?></h1>
				<p><?php printf(__('This is the primary sidebar. You can remove this message by adding widgets to it from the <a href="%1$s">Customizer</a>.', 'inkblot'), admin_url('customize.php')); ?></p>
			</aside>
			
		<?php endif; ?>	
		
	</div><!-- .sidebar1 -->
	
<?php endif; ?>

<?php if (false !== strpos(get_theme_mod('content', 'one-column'), 'three-column') or is_customize_preview()) : ?>
	
	<div class="sidebar2 widgets columns-<?php print inkblot_count_widgets('secondary-sidebar'); ?>">
		<h1 class="screen-reader-text"><?php _e('Secondary Sidebar', 'inkblot'); ?></h1>
		
		<?php if ( ! dynamic_sidebar('secondary-sidebar') and current_user_can('edit_theme_options')) : ?>
			
			<aside class="widget">
				<h1><?php _e('Secondary Sidebar', 'inkblot'); ?></h1>
				<p><?php printf(__('This is the secondary sidebar. You can remove this message by adding widgets to it from the <a href="%1$s">Customizer</a>.', 'inkblot'), admin_url('customize.php')); ?></p>
			</aside>
			
		<?php endif; ?>	
		
	</div><!-- .sidebar2 -->
	
<?php endif; ?>