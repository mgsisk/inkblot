<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'page-top' ) ) { ?>
<div id="body-bottom">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'page-top' ); ?>
	</ul>
</div>
<?php } ?>