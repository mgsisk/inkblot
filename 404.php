<?php get_header(); ?>

<?php inkblot_inside_content(); ?>

<h1><?php _e( 'Error 404', 'inkblot' ); ?></h1>
<p><?php _e( 'Sorry, but we are unable to find what you were looking for. Some recently posted comics are listed below, or try your luck with a ', 'inkblot' ); random_comic_link(  __( 'random comic', 'inkblot' ) ); ?>.</p>
<ul class="recent-comics"><?php recent_comics( 5, 'thumb', '', '<li class="alignleft">' ); ?></ul>

<?php get_footer(); ?>