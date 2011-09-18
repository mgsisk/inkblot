<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'content-top' ) ) { ?>
<div id="content-top">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'content-top' ); ?>
	</ul>
</div>
<?php } ?>