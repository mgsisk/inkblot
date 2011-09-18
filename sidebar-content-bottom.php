<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'content-bottom' ) ) { ?>
<div id="content-bottom">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'content-bottom' ); ?>
	</ul>
</div>
<?php } ?>