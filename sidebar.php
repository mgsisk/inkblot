<?php
/** Standard sidebar template.
 * 
 * @package Inkblot
 */
?>

<div id="sidebar1" role="complementary" class="widgets">
	<?php if ( !dynamic_sidebar( 'primary-sidebar' ) and current_user_can( 'edit_theme_options' ) ) : ?>
		<aside class="widget">
			<h1><?php _e( 'Primary Sidebar', 'inkblot' ); ?></h1>
			<p><?php printf( __( 'This is the primary sidebar. You can remove this message by adding widgets to it from the <a href="%s"><strong>Appearance > Widgets</strong> administrative page</a>.', 'inkblot' ), admin_url( 'widgets.php' ) ); ?></p>
		</aside>
	<?php endif; ?>	
</div><!-- #sidebar1 -->
<div id="sidebar2" role="complementary" class="widgets">
	<?php if ( !dynamic_sidebar( 'secondary-sidebar' ) and current_user_can( 'edit_theme_options' ) ) : ?>
		<aside class="widget">
			<h1><?php _e( 'Secondary Sidebar', 'inkblot' ); ?></h1>
			<p><?php printf( __( 'This is the secondary sidebar. You can remove this message by adding widgets to it from the <a href="%s"><strong>Appearance > Widgets</strong> administrative page</a>.', 'inkblot' ), admin_url( 'widgets.php' ) ); ?></p>
		</aside>
	<?php endif; ?>	
</div><!-- #sidebar2 -->