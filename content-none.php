<?php
/** Generic no content template.
 * 
 * @package Inkblot
 */
?>
<header class="page-header">
	<h1><?php _e( 'Nothing Found', 'inkblot' ); ?></h1>
</header><!-- .page-header -->
<div class="page-content">
	<p><?php _e( 'Nothing could be found for the requested archive.', 'inkblot' ); ?></p>
	<?php get_search_form(); ?>
</div><!-- .page-content -->