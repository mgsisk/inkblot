<aside id="sidebar1" class="widgetized">
	<div>
	<?php if ( !dynamic_sidebar( 'inkblot-sidebar1' ) ) { //see functions.php hook_init ?>
		<figure>
			<figcaption><?php _e( 'Sidebar 1', 'inkblot' ); ?></figcaption>
			<?php _e( 'This area is widgetized. You can add widgets by going to <em>Appearance > Widgets</em> in the administrative dashboard. Adding widgets will remove this message.', 'inkblot' ); ?>
		</figure>
	<?php } ?>
	</div>
</aside><!--#sidebar1-->

<aside id="sidebar2" class="widgetized">
	<div>
	<?php if ( !dynamic_sidebar( 'inkblot-sidebar2' ) ) { //see functions.php hook_init ?>
		<figure>
			<figcaption><?php _e( 'Sidebar 2', 'inkblot' ); ?></figcaption>
			<?php _e( 'This area is widgetized. You can add widgets by going to <em>Appearance > Widgets</em> in the administrative dashboard. Adding widgets will remove this message.', 'inkblot' ); ?>
		</figure>
	<?php } ?>
	</div>
</aside><!--#sidebar2-->