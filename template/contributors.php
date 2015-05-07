<?php
/**
 * Template Name: Contributors
 * 
 * Contributors page template.
 * 
 * @package Inkblot
 * @see https://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>

<main role="main">

	<?php
		while (have_posts()) : the_post();
			
			get_template_part('content', 'page');
			
			$users = get_users(array(
				'fields' => 'ID',
				'orderby' => 'post_count',
				'order' => 'DESC',
				'who' => 'authors'
			));
			
			if ($users) :
				foreach ($users as $user) :
					print inkblot_contributor($user, get_post_meta('inkblot_avatar'));
				endforeach;
			endif;
			
			comments_template();
		endwhile;
	?>

</main>

<?php get_sidebar(); get_footer();