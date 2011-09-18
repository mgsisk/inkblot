<?php if ( function_exists( 'dynamic_sidebar' ) && is_sidebar_active( 'content-insert' ) ) { ?>
<div id="content-insert">
	<ul class="group widgetized">
		<?php dynamic_sidebar( 'content-insert' ); ?>
	</ul>
</div>
<?php } ?>