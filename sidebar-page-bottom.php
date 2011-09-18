<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'page-bottom' ) ) { ?>
<div id="page-bottom">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'page-bottom' ); ?>
	</ul>
</div>
<?php } ?>