<?php get_header(); ?>

<?php inkblot_inside_content(); the_post(); ?>

<div id="post-<?php the_id(); ?>" <?php post_class(); ?>>
	<h1><?php the_title(); ?></h1>
	<div class="entry">
		<?php the_content( __( 'Read More &raquo;', 'inkblot' ) ); ?>
		<?php wp_link_pages( 'before=<div class="navi navi-paged"><span class="navi-pages-title">' . __( 'Pages:', 'inkblot' ) . '</span>&after=</div>' ); ?>
		<?php edit_post_link( __('Edit This', 'inkblot'), '<p class="edit-link">', '</p>' ); ?>
	</div>
</div>

<?php comments_template(); ?>

<?php get_footer(); ?>