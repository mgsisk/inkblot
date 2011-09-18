<?php the_post(); get_header(); global $inkblot; ?>

<div id="main">
	<?php if ( '3c3' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>
	<section id="content">
		<div>
			<div id="content-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-above' ); ?></div>
			<article id="error-verifyage" class="age-restricted">
				<h1><?php _e( 'Restricted', 'inkblot' ); ?></h1>
				<div class="content"><p><?php _e( 'This webcomic is age restricted. Please enter your birth date to proceed:', 'inkblot' ); ?><?php webcomic_verify_form(); ?></p></div>
			</article>
			<div id="content-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-below' ); ?></div>
		</div>
	</section><!--#content-->
</div><!--#main-->

<?php get_sidebar(); get_footer(); ?>