<?php
/** 404 error template.
 * 
 * @package Inkblot
 */

get_header(); ?>

<main role="main">	
	<header class="page-header">
		<h1><?php _e( 'Not Found', 'inkblot' ) ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<p><?php _e( "Apologies, but we can't seem to find what you're looking for. Perhaps searching will help.", 'inkblot' ); ?></p>
		<?php get_search_form(); ?>
	</div><!-- .page-content -->
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>