<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'body-top' ) ) { ?>
<div id="body-top">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'body-top' ); ?>
	</ul>
</div>
<?php } ?>