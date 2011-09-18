<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'body-bottom' ) ) { ?>
<div id="body-bottom">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'body-bottom' ); ?>
	</ul>
</div>
<?php } ?>