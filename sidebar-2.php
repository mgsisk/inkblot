<div <?php inkblot_sidebar_class( true ); ?>>
	<ul class="interior">
	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'sidebar-2' ) ) : ?>
	<li><p><?php _e( 'This is Sidebar 2, used in three column layouts. You can add content to this sidebar using Widgets from the WordPress control panel. Adding widgets to this sidebar will remove this message.', 'inkblot' ); ?></p></li>
	<?php endif; ?>
	</ul>
</div>