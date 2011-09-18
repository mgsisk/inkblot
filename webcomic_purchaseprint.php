<?php the_post(); get_header(); global $inkblot; ?>

<div id="main">
	<?php if ( '3c3' == $inkblot->option( 'layout' ) ) get_sidebar(); ?>
	<section id="content">
		<div>
			<div id="content-above" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-above' ); ?></div>
			<article id="webcomic-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php printf( 'Buy Print - %s', get_the_title() ); ?></h1>
				<div class="content">
					<div class="preview"><?php the_webcomic_object( 'medium', 'self' ); ?></div>
				<?php if ( webcomic_prints_open() ) { ?>
					<p><?php purchase_webcomic_form( 'domestic' ); ?><strong><?php _e( 'Domestic:', 'inkblot' ); ?></strong> <?php the_purchase_webcomic_cost( 'price' ); ?> <sup>+ <?php the_purchase_webcomic_cost( 'shipping', 'domestic' ); _e( ' shipping', 'inkblot' ); ?></sup></p>
					<p><?php purchase_webcomic_form( 'international' ); ?><strong><?php _e( 'International:', 'inkblot' ); ?></strong> <?php the_purchase_webcomic_cost( 'price', 'international' ); ?> <sup>+ <?php the_purchase_webcomic_cost( 'shipping', 'international' ); _e( ' shipping', 'inkblot' ); ?></sup></p>
					<hr>
					<?php if ( $inkblot->option( 'prints_original_toggle' ) && webcomic_prints_open( false, true ) ) { ?><p><?php purchase_webcomic_form( 'original' ); ?><strong><?php _e( 'Original:', 'inkblot' ); ?></strong> <?php the_purchase_webcomic_cost( 'price', 'original' ); ?> <sup>+ <?php the_purchase_webcomic_cost( 'shipping', 'original' ); _e( ' shipping', 'inkblot' ); ?></sup></p><?php } ?>
				<?php } else { ?>
					<p><?php _e( 'Prints are currently unavailable for this webcomic.', 'inkblot' ); ?></p>
				<?php } ?>
				</div>
			</article>
			<div id="content-below" class="widgetized"><?php dynamic_sidebar( 'inkblot-content-below' ); ?></div>
		</div>
	</section><!--#content-->
</div><!--#main-->

<?php get_sidebar(); get_footer(); ?>