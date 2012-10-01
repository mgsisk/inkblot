<?php
/** Webcomic role-restricted template.
 * 
 * This template will be displayed in place of the normal post
 * content when a user with an inappropriate role tries to view a
 * role-restricted Webcomic.
 * 
 * @package Inkblot
 * @see github.com/mgsisk/webcomic/wiki/Templates
 */

get_header(); ?>

<section id="main" role="main">
	<header class="page-header">
		<h1><?php _e( 'Restricted Content', 'inkblot' ); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<p><?php _e( "Apologies, but you don't have permission to view this content.", 'inkblot' ); ?></p>
	</div><!-- .page-content -->
</section><!-- #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>